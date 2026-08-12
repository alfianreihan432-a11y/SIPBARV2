# Requirements: SIPBAR QR Enhancement & WhatsApp Integration

**Project**: SIPBARV2 Enhancement  
**Version**: 1.0  
**Date**: 2026-08-12  
**Status**: Draft - Awaiting Review

---

## Executive Summary

This document specifies requirements for enhancing SIPBARV2 (existing Laravel-based school inventory borrowing system) with:
1. **QR Code Checkout/Checkin Workflow** - Single QR code used twice (borrow & return)
2. **WhatsApp Notification Integration** - Via existing microservice
3. **Automated Reminder System** - H-1 deadline notifications
4. **Enhanced Transaction State Machine** - Aligned with target specification

---

## Current State Analysis (SIPBARV2)

### Existing Features ✅
- User management (Siswa, Guru, Admin roles via Spatie Permissions)
- Item inventory management (categories, locations, suppliers)
- Borrowing request submission by students
- Teacher approval/rejection workflow
- QR code generation (basic - via QRCode model)
- Dashboard for each role
- SIPINTU OAuth SSO integration

### Existing Database Schema
**Key Models:**
- `BorrowingRequest`: Main transaction model
- `QRCode`: Linked to borrowing requests
- `Item`: Inventory with stock tracking
- `User`: With roles (siswa/guru/admin)

**Current Status Flow:**
```
pending → approved → qr_ready → borrowed → returned
          ↓
        rejected
```

### Gaps Identified 🔴
1. **QR Workflow**: No checkout/checkin scanning implementation
2. **State Machine**: Status names differ from target spec
3. **WhatsApp Integration**: Not implemented
4. **Scheduler**: No H-1 reminder system
5. **Stock Validation**: May need enhancement for approval flow
6. **QR Reusability**: Need to verify single QR for dual-use implementation

---

## Assumptions & Clarifications Required ⚠️

### ASSUMPTION 1: State Machine Alignment
**Current SIPBARV2**: `pending` → `approved` → `qr_ready` → `borrowed` → `returned`  
**Target Spec**: `menunggu_persetujuan` → `disetujui` → `dipinjam` → `dikembalikan`

**PROPOSED RESOLUTION:**
- Keep existing status names to avoid breaking existing data
- Map functionality as follows:
  - `pending` = "menunggu_persetujuan" (awaiting teacher approval)
  - `approved` = "disetujui" (approved, QR generated, ready for checkout)
  - `borrowed` = "dipinjam" (checked out via QR scan)
  - `returned` = "dikembalikan" (checked in via QR scan)
  - Remove `qr_ready` (merge with `approved` - QR generated immediately on approval)

**PLEASE CONFIRM**: Is this mapping acceptable, or do you prefer to migrate existing statuses to Indonesian names?

---

### ASSUMPTION 2: QR Code Behavior
**Target Spec**: "Satu QR/token dipakai dua kali, untuk checkout maupun checkin"

**Current Implementation**: QRCode model exists with `borrowing_request_id`, `code`, `is_active`, `scanned_at`

**PROPOSED IMPLEMENTATION:**
- QR token remains constant throughout transaction lifecycle
- Action determined by transaction status:
  - Status `approved` + QR scan → Trigger checkout → Status becomes `borrowed`
  - Status `borrowed` + QR scan → Show return form → Status becomes `returned`
  - Status `rejected` or `returned` + QR scan → Show read-only detail (no action)

**PLEASE CONFIRM**: Is this the correct interpretation?

---

### ASSUMPTION 3: WhatsApp Service Integration
**Given**: External Node.js microservice with REST API

**PROPOSED APPROACH:**
- Store `WA_BOT_URL` and `WA_BOT_API_KEY` in `.env`
- Use Laravel HTTP Client (`Http::withHeaders()->post()`)
- Implement as Service class: `app/Services/WhatsAppNotificationService.php`
- Failure handling: Log error, DO NOT block transaction
- Async preferred (via Jobs/Queues) to avoid blocking user experience

**QUESTIONS:**
1. Should WA notifications be queued (recommended) or synchronous?
2. Is retry logic needed if WA bot is temporarily offline?
3. Should we implement circuit breaker pattern for resilience?

---

### ASSUMPTION 4: Stock Management
**Target Spec**: "WHEN guru mencoba menyetujui pengajuan untuk barang yang stok tersedianya habis, THE SYSTEM SHALL menolak aksi tersebut"

**Current State**: `Item` model has `stock` field, but need to verify:
- Is `stock` = available stock, or total stock?
- How should reserved stock (approved but not yet borrowed) be tracked?

**PROPOSED:**
- Add `available_stock` virtual attribute/calculation
- `available_stock` = `stock` - (count of approved/borrowed requests for this item)
- Validate on approval: `available_stock >= requested_quantity`

**PLEASE CONFIRM**: Does this align with your stock tracking needs?

---

### ASSUMPTION 5: Scheduler Implementation
**Target**: H-1 (1 day before) reminder for borrowed items

**PROPOSED:**
- Laravel Task Scheduler in `routes/console.php` (Laravel 11 structure)
- Command: `php artisan borrowing:send-reminders`
- Runs daily at 08:00 AM
- Selects: `status = 'borrowed' AND return_date = tomorrow`
- Tracks sent reminders to avoid duplicates (add `reminder_sent_at` column)

**QUESTION**: What time should reminder run? (default: 08:00 AM)

---

### ASSUMPTION 6: Mobile-First Scanner
**Target**: "Tampilan harus tetap nyaman dipakai dari HP"

**PROPOSED:**
- QR Scanner page: `/qr/scan` (authenticated, guru role)
- Uses `html5-qrcode` JavaScript library
- Responsive design with large scan area
- Camera permission handling with clear instructions
- Fallback: Manual token input if camera unavailable

**PLEASE CONFIRM**: Should scanner be accessible to all roles or guru only?

---

## Functional Requirements (EARS Format)

### FR-1: Student Submission
**WHEN** a student submits a valid borrowing request form,  
**THE SYSTEM SHALL**:
1. Create a new `BorrowingRequest` record with status `pending`
2. Assign to appropriate teacher (based on `teacher_id` or department logic)
3. Call `POST /notify/pengajuan-baru` on WA Bot with payload:
   - `nomorGuru`: Teacher's phone number
   - `namaSiswa`: Student name
   - `kelas`: Student class
   - `barang`: Item name
   - `jumlah`: Quantity
   - `tglPinjam`: Borrow date
   - `tglKembali`: Return date
   - `keperluan`: Purpose
   - `linkKeputusan`: URL to approval page
4. If WA call fails, log error but continue (transaction must succeed)
5. Redirect student to success page with request reference number

**VALIDATION:**
- Required fields: item, quantity, borrow_date, return_date, purpose
- `return_date` must be after `borrow_date`
- `quantity` must be positive integer

---

### FR-2: Teacher Rejection
**WHEN** a teacher rejects a borrowing request,  
**THE SYSTEM SHALL**:
1. Validate that `rejection_reason` field is not empty
2. Update status to `rejected`
3. Store `rejection_reason` in database
4. Call `POST /notify/ditolak` on WA Bot with payload:
   - `nomorSiswa`: Student phone number
   - `namaSiswa`: Student name
   - `barang`: Item name
   - `alasan`: Rejection reason
5. If WA call fails, log error but continue
6. Notify teacher of successful rejection

**PRECONDITION:**
- Request status must be `pending`
- Only assigned teacher can reject

---

### FR-3: Teacher Approval with QR Generation
**WHEN** a teacher approves a borrowing request,  
**THE SYSTEM SHALL**:
1. **FIRST** validate available stock:
   - Calculate: `available_stock = item.stock - (count of active requests)`
   - If `available_stock < requested_quantity`, REJECT with error message
2. Generate unique cryptographic token (UUID v4 or similar)
3. Generate QR code image from token
4. Store token in `qr_codes` table linked to `borrowing_request_id`
5. Update status to `approved`
6. Record `approved_at` timestamp
7. Convert QR image to base64
8. Call `POST /notify/disetujui` on WA Bot with payload:
   - `nomorSiswa`: Student phone number
   - `namaSiswa`: Student name
   - `barang`: Item name
   - `tglKembali`: Return date
   - `qrBase64`: Base64-encoded QR image
9. If WA call fails, log error but continue
10. Notify teacher of successful approval

**PRECONDITION:**
- Request status must be `pending`
- Only assigned teacher can approve

**POSTCONDITION:**
- QR code is immediately scannable for checkout

---

### FR-4: QR Scan - Checkout (First Scan)
**WHEN** a guru scans a QR code AND the associated transaction status is `approved`,  
**THE SYSTEM SHALL**:
1. Decode QR token
2. Lookup `BorrowingRequest` by `qr_codes.code`
3. **IF** token not found, display user-friendly error: "QR Code tidak valid atau tidak ditemukan"
4. **IF** status is NOT `approved`, proceed to FR-7 (read-only view)
5. Display transaction details:
   - Student name, class, item, quantity, purpose
   - Borrow & return dates
   - Prominent "Konfirmasi Serah Terima" button
6. **WHEN** teacher confirms checkout:
   - Update status to `borrowed`
   - Record `borrowed_at` timestamp (actual checkout time)
   - Decrement item available stock (if tracked separately)
   - Display success message: "Barang berhasil diserahkan kepada [Student Name]"

**SECURITY:**
- Only authenticated users with `guru` role can access scanner
- Log all scan attempts for audit trail

---

### FR-5: Scheduler - H-1 Reminder
**WHEN** the daily scheduler runs (08:00 AM) AND finds transactions with status `borrowed` AND `return_date` = tomorrow,  
**THE SYSTEM SHALL**:
1. Select all matching `BorrowingRequest` records
2. **FOR EACH** transaction:
   - Check if `reminder_sent_at` is NULL OR not today (prevent duplicate sends)
   - Call `POST /notify/reminder-h1` on WA Bot with payload:
     - `nomorSiswa`: Student phone number
     - `namaSiswa`: Student name
     - `barang`: Item name
     - `tglKembali`: Return date
   - If WA call succeeds, update `reminder_sent_at` to current timestamp
   - If WA call fails, log error and continue to next transaction
3. Generate summary report: X reminders sent, Y failed

**CONFIGURATION:**
- Scheduler runs via Laravel Task Scheduling
- Cron entry: `* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1`
- Command: `php artisan borrowing:send-reminders`

---

### FR-6: QR Scan - Checkin (Second Scan)
**WHEN** a guru scans a QR code AND the associated transaction status is `borrowed`,  
**THE SYSTEM SHALL**:
1. Decode QR token and lookup transaction (same as FR-4 steps 1-3)
2. **IF** status is NOT `borrowed`, proceed to FR-7 (read-only view)
3. Display return form with:
   - Transaction details (read-only)
   - Item condition dropdown: ["Baik", "Rusak Ringan", "Rusak Berat", "Hilang"]
   - Return notes textarea (optional)
   - "Konfirmasi Pengembalian" button
4. **WHEN** teacher submits return form:
   - Validate required field: `return_condition`
   - Update status to `returned`
   - Record `returned_at` timestamp (actual return time)
   - Store `return_condition` and `return_notes`
   - Increment item available stock (restore)
   - Call `POST /notify/dikembalikan` on WA Bot with payload:
     - `nomorSiswa`: Student phone number
     - `namaSiswa`: Student name
     - `barang`: Item name
     - `waktuKembali`: Actual return timestamp
   - If WA call fails, log error but continue
   - Display success message: "Pengembalian barang berhasil dicatat"

**BUSINESS RULE:**
- If `return_condition` is "Rusak Berat" or "Hilang", system may flag for admin review (future enhancement)

---

### FR-7: QR Scan - Read-Only View
**WHEN** a guru scans a QR code AND the associated transaction status is `rejected` OR `returned`,  
**THE SYSTEM SHALL**:
1. Display transaction details in read-only mode:
   - Student info, item, dates, purpose
   - Status badge (clearly showing "Ditolak" or "Sudah Dikembalikan")
   - Rejection reason (if status = `rejected`)
   - Return condition & notes (if status = `returned`)
   - Timestamps for all state transitions
2. NO action buttons displayed
3. Message: "Transaksi ini sudah selesai dan tidak dapat diubah"

**PURPOSE**: Maintain QR code as permanent audit trail reference

---

### FR-8: Transaction History & Search
**WHEN** an admin or guru opens the history page,  
**THE SYSTEM SHALL** provide:
1. **Search & Filter Controls:**
   - Student name (text input with autocomplete)
   - Item name (dropdown or autocomplete)
   - Date range (from/to date pickers)
   - Status (multi-select: all, pending, approved, borrowed, returned, rejected)
   - Teacher (dropdown, admin only)
2. **Results Table:**
   - Student, Item, Quantity, Borrow Date, Return Date, Status
   - Action column: "View Details" button
   - Pagination (25 records per page)
3. **Export Function:**
   - Download as CSV or Excel (future enhancement)
4. **Performance:**
   - Search should complete within 2 seconds for up to 10,000 records

**AUTHORIZATION:**
- Guru: See only transactions assigned to them
- Admin: See all transactions

---

### FR-9: Invalid QR Code Handling
**IF** a QR token is scanned AND it does not exist in the database,  
**THEN THE SYSTEM SHALL**:
1. Display user-friendly error page (NOT Laravel exception page)
2. Error message: "QR Code tidak valid atau tidak terdaftar dalam sistem"
3. Suggested actions:
   - "Pastikan QR Code berasal dari sistem SIPBAR"
   - "Hubungi admin jika masalah berlanjut"
4. Button to return to scanner page
5. Log the invalid scan attempt with timestamp & scanner user ID

---

### FR-10: WhatsApp Bot Resilience
**WHEN** any WA Bot API call is made,  
**THE SYSTEM SHALL**:
1. Set HTTP timeout to 10 seconds
2. Include header: `X-API-Key: {WA_BOT_API_KEY}`
3. **IF** call succeeds (HTTP 200-299):
   - Log success with transaction ID reference
4. **IF** call fails (timeout, 4xx, 5xx, network error):
   - Log error with full details (endpoint, payload, error message)
   - **DO NOT** rollback the main transaction
   - Continue with application flow as normal
5. **OPTIONAL** (for future): Implement retry queue for failed notifications

**RATIONALE**: WhatsApp notification is supplementary; transaction integrity is primary concern

---

## Non-Functional Requirements

### NFR-1: Security
1. All QR tokens MUST be cryptographically random (UUID v4 minimum)
2. QR tokens MUST NOT be sequential or predictable
3. Scanner page MUST require authentication (`auth` middleware)
4. Scanner page MUST check role (`role:guru` middleware)
5. All state-changing endpoints MUST validate CSRF token
6. WA Bot API key MUST be stored in `.env`, NOT in code

### NFR-2: Performance
1. QR code generation MUST complete within 1 second
2. QR scan & lookup MUST complete within 0.5 seconds
3. History search MUST complete within 2 seconds for 10K records
4. WA Bot calls SHOULD NOT block user response (use queues if needed)

### NFR-3: Usability
1. QR scanner MUST work on mobile browsers (Chrome, Safari on iOS/Android)
2. Scanner UI MUST have large, clear camera viewport (minimum 300x300px)
3. Error messages MUST be in Indonesian and user-friendly (no technical jargon)
4. All datetime displays MUST use Indonesian locale format

### NFR-4: Reliability
1. System MUST continue operating if WA Bot is offline
2. Reminders MUST NOT send duplicates on same day
3. QR tokens MUST remain valid throughout transaction lifecycle
4. All transactions MUST be logged for audit trail

### NFR-5: Maintainability
1. WA Bot integration MUST be encapsulated in Service class
2. Status transitions MUST be handled via State Machine pattern (or clear switch/match statements)
3. Migration scripts MUST be reversible (`down()` method)
4. All new features MUST have inline code comments explaining business logic

---

## Data Model Changes Required

### Table: `borrowing_requests` (MODIFY)
**Add columns:**
- `qr_token` VARCHAR(255) UNIQUE NULLABLE (denormalized for quick lookup)
- `reminder_sent_at` TIMESTAMP NULLABLE (track H-1 reminder sent)
- `checkout_by` BIGINT UNSIGNED NULLABLE FOREIGN KEY(`users.id`) (guru who scanned for checkout)
- `checkin_by` BIGINT UNSIGNED NULLABLE FOREIGN KEY(`users.id`) (guru who scanned for checkin)

**Modify columns:**
- `status` ENUM: Keep existing values or migrate to Indonesian (see ASSUMPTION 1)
- Add index on `status` + `return_date` for scheduler performance

---

### Table: `qr_codes` (REVIEW & POSSIBLY MODIFY)
**Current columns are sufficient, but verify:**
- `code` should store the unique token (UUID)
- `is_active` might be redundant if status is managed via transaction state
- `scanned_at` might need to track BOTH scans (consider renaming to `first_scanned_at` and adding `last_scanned_at`)

**RECOMMENDATION**: Add `scan_count` INTEGER DEFAULT 0 to track number of scans for analytics

---

### Table: `whatsapp_notification_logs` (NEW - Optional but Recommended)
**Purpose**: Audit trail for all WA notification attempts

**Columns:**
- `id` BIGINT UNSIGNED PRIMARY KEY
- `borrowing_request_id` BIGINT UNSIGNED NULLABLE FOREIGN KEY
- `notification_type` ENUM('pengajuan_baru', 'ditolak', 'disetujui', 'reminder_h1', 'dikembalikan')
- `recipient_phone` VARCHAR(20)
- `payload` JSON (store full payload sent)
- `status` ENUM('pending', 'success', 'failed')
- `http_status_code` INTEGER NULLABLE
- `error_message` TEXT NULLABLE
- `sent_at` TIMESTAMP
- `created_at`, `updated_at`

---

## State Machine Diagram (Proposed)

```
┌─────────────────────────────────────────────────────────────┐
│                    BORROWING REQUEST LIFECYCLE               │
└─────────────────────────────────────────────────────────────┘

    [Student Submits]
           │
           ↓
    ┌──────────┐
    │ pending  │ ←─── Initial state after submission
    └──────────┘
         │  │
         │  │ (Teacher Decision)
         │  │
         │  └──────→ [Rejected + reason]
         │                  ↓
         │            ┌──────────┐
         │            │ rejected │ [TERMINAL STATE]
         │            └──────────┘
         │
         ↓ [Approved + QR Generated]
    ┌──────────┐
    │ approved │ ←─── QR code is generated & sent
    └──────────┘
         │
         │ (Teacher scans QR - First scan)
         ↓
    ┌──────────┐
    │ borrowed │ ←─── Item checked out
    └──────────┘      (Reminder sent H-1)
         │
         │ (Teacher scans QR - Second scan + condition input)
         ↓
    ┌──────────┐
    │ returned │ [TERMINAL STATE]
    └──────────┘
```

**Key Points:**
- Only ONE QR token exists per transaction (generated at `approved` state)
- Same QR token used for BOTH checkout (approved→borrowed) and checkin (borrowed→returned)
- Action triggered depends on CURRENT STATUS, not QR content

---

## Out of Scope (Future Enhancements)

The following are explicitly OUT OF SCOPE for this enhancement but documented for future consideration:

1. **Email notifications** (only WhatsApp for now)
2. **Overdue automatic status updates** (requires additional scheduler)
3. **Penalty/fine calculation** for late returns
4. **Item damage charge tracking** (requires financial module)
5. **Multi-item per transaction** (current assumes one item per request)
6. **Student self-service QR viewing** (QR only sent via WhatsApp)
7. **QR code expiration** (QR remains valid indefinitely for audit trail)
8. **Offline QR scanning** (requires PWA with service workers)
9. **Barcode scanning** (only QR codes for now)
10. **Integration with library system** (out of scope)

---

## Open Questions for Review

Please review and provide feedback on the following:

### 🔴 CRITICAL - Must Decide
1. **Status field names**: Keep English (`pending`, `approved`, etc.) or migrate to Indonesian? (See ASSUMPTION 1)
2. **Stock tracking method**: Confirm the approach in ASSUMPTION 4
3. **WA Bot communication**: Synchronous or queued? (See ASSUMPTION 3)

### 🟡 IMPORTANT - Should Clarify
4. **Scanner access**: Guru only or all authenticated users?
5. **Reminder time**: What time should H-1 reminders be sent? (default: 08:00)
6. **Multi-item support**: Will you need multiple items in one request in future?

### 🟢 NICE TO HAVE - Can Decide Later
7. **Notification retry**: Implement retry queue for failed WA calls?
8. **Admin dashboard**: Should admin have a "Resend Notification" button?
9. **Analytics**: Track QR scan attempts (including invalid scans)?

---

## Acceptance Criteria Summary

This enhancement will be considered COMPLETE when:

✅ **Student Flow:**
- [x] Student can submit borrowing request
- [x] Student receives WhatsApp notification on approval/rejection
- [x] Student receives QR code via WhatsApp on approval
- [x] Student receives H-1 reminder via WhatsApp

✅ **Teacher Flow:**
- [x] Teacher can approve/reject with validation
- [x] Teacher can scan QR via mobile camera
- [x] QR scan shows correct action based on status (checkout vs checkin)
- [x] Teacher can input item condition on return

✅ **Admin Flow:**
- [x] Admin can search/filter transaction history
- [x] Admin can view all transaction details
- [x] Admin has visibility into WA notification status (via logs)

✅ **System Behavior:**
- [x] Single QR code works for both checkout and checkin
- [x] Stock validation prevents over-approval
- [x] WA Bot failures do not break transactions
- [x] Scheduler sends reminders daily without duplicates
- [x] Invalid QR codes show friendly error messages
- [x] All state transitions are auditable

---

## Next Steps

1. **REVIEW THIS DOCUMENT** - Provide feedback on assumptions & open questions
2. Once approved, proceed to **design.md** (database migrations, architecture, route structure)
3. Then proceed to **tasks.md** (break down into implementable units)
4. Begin implementation task by task

---

**Document Status**: 🟡 DRAFT - Awaiting stakeholder review  
**Last Updated**: 2026-08-12  
**Author**: Kiro AI Assistant
