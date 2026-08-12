# SIPBAR V2 - Sistem Informasi Peminjaman Barang

Sistem informasi peminjaman barang sekolah berbasis web dengan fitur QR Code checkout/checkin dan integrasi notifikasi WhatsApp.

## 🎯 Fitur Utama

### Untuk Siswa
- ✅ Mengajukan peminjaman barang secara online
- ✅ Menerima notifikasi WhatsApp untuk setiap status peminjaman
- ✅ Melihat QR Code untuk checkout/checkin
- ✅ Mendapat reminder H-1 sebelum tanggal pengembalian
- ✅ Melihat riwayat peminjaman

### Untuk Guru
- ✅ Menyetujui atau menolak pengajuan peminjaman
- ✅ Scan QR Code untuk checkout (serah terima barang)
- ✅ Scan QR Code untuk checkin (pengembalian barang)
- ✅ Input kondisi barang saat pengembalian
- ✅ Melihat riwayat transaksi yang ditugaskan
- ✅ Dashboard dengan statistik peminjaman

### Untuk Admin
- ✅ Kelola data barang dan stok
- ✅ Kelola akun guru dan siswa
- ✅ Melihat seluruh riwayat transaksi
- ✅ Filter dan pencarian transaksi lanjutan
- ✅ Melihat log notifikasi WhatsApp
- ✅ Statistik lengkap sistem

## 🔄 Alur Workflow

```
1. Siswa mengajukan peminjaman
   └─> Guru menerima notifikasi WA

2. Guru menyetujui/menolak
   ├─> Disetujui: Siswa menerima QR Code via WA
   └─> Ditolak: Siswa menerima alasan penolakan

3. Siswa datang dengan QR Code
   └─> Guru scan QR → Checkout (barang diserahkan)

4. H-1 sebelum tanggal kembali
   └─> Siswa menerima reminder via WA

5. Siswa kembalikan barang dengan QR Code
   └─> Guru scan QR → Input kondisi → Checkin (selesai)
```

## 🏗️ State Machine

```
pending → approved → borrowed → returned
    ↓
rejected (terminal)
```

**Aturan Transisi:**
- QR Code dibuat saat status → `approved`
- QR Code yang sama digunakan untuk checkout DAN checkin
- Aksi ditentukan oleh status saat scan:
  - `approved` → Checkout (serah terima)
  - `borrowed` → Checkin (pengembalian)
  - Status lain → Read-only (tidak ada aksi)

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates + Livewire 3.x
- **Styling**: TailwindCSS
- **Database**: MySQL/MariaDB
- **QR Code**: endroid/qr-code v6.1
- **Scanner**: html5-qrcode (browser-based)
- **Authentication**: Laravel Fortify + Spatie Laravel Permission

## 📋 Requirements

- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js & NPM (untuk asset compilation)
- GD atau Imagick extension (untuk QR code generation)

## 🚀 Installation

### 1. Clone Repository

```bash
git clone <repository-url>
cd SIPBARV2
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipbarv2
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Configure WhatsApp Integration

Edit `.env` file untuk konfigurasi WhatsApp bot (opsional):

```env
WA_BOT_URL=http://localhost:3000
WA_BOT_API_KEY=your-api-key-here
WA_BOT_TIMEOUT=10
```

**Catatan:** Jika WhatsApp bot tidak tersedia, sistem akan tetap berfungsi normal. Notifikasi hanya akan dicatat sebagai failed di log.

### 6. Run Migrations

```bash
# Run all migrations
php artisan migrate

# Seed initial data (optional)
php artisan db:seed
```

### 7. Storage Link

```bash
# Create symbolic link for storage
php artisan storage:link
```

### 8. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 9. Run Application

```bash
# Development server
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## ⚙️ Configuration

### Scheduler Setup (Production)

Untuk menjalankan reminder H-1 otomatis, tambahkan cron job:

```bash
# Edit crontab
crontab -e

# Tambahkan baris ini
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Scheduler akan menjalankan command `borrowing:send-reminders` setiap hari pukul 08:00.

### Manual Reminder Test

```bash
php artisan borrowing:send-reminders
```

## 👥 Default Users

Setelah seeding, Anda dapat login dengan:

**Admin:**
- Email: admin@sipbar.test
- Password: password

**Guru:**
- Email: guru@sipbar.test
- Password: password

**Siswa:**
- NISN: 1234567890
- Password: password

## 📱 WhatsApp Integration

### Endpoint yang Dipanggil

Sistem akan memanggil endpoint berikut pada WhatsApp bot:

1. **GET /status** - Cek status bot
2. **POST /notify/pengajuan_baru** - Notifikasi pengajuan baru ke guru
3. **POST /notify/ditolak** - Notifikasi penolakan ke siswa
4. **POST /notify/disetujui** - Notifikasi persetujuan + QR code ke siswa
5. **POST /notify/checkout** - Notifikasi checkout ke siswa
6. **POST /notify/reminder_h1** - Reminder H-1 ke siswa
7. **POST /notify/dikembalikan** - Notifikasi pengembalian selesai ke siswa

### Payload Format

Contoh payload untuk approval:

```json
{
  "nomorSiswa": "6281234567890",
  "namaSiswa": "John Doe",
  "barang": "Proyektor LCD",
  "tglKembali": "15-08-2026",
  "qrBase64": "data:image/png;base64,..."
}
```

## 🗂️ Project Structure

```
app/
├── Console/Commands/
│   └── SendBorrowingReminders.php   # H-1 reminder command
├── Exceptions/
│   ├── InsufficientStockException.php
│   └── InvalidStateTransitionException.php
├── Http/Controllers/
│   ├── TeacherApprovalController.php    # Approval/reject
│   └── TransactionHistoryController.php # Transaction history
├── Livewire/
│   ├── QRScanner.php                    # QR scanner component
│   └── TeacherApproval.php              # Approval UI
├── Models/
│   ├── BorrowingRequest.php
│   ├── QRCode.php
│   └── WhatsAppNotificationLog.php
└── Services/
    ├── BorrowingApprovalService.php     # Orchestration
    ├── BorrowingStateMachine.php        # State management
    ├── QRCodeService.php                # QR generation/lookup
    └── WhatsAppNotificationService.php  # WA integration
```

## 🔐 Security

- ✅ Token QR menggunakan UUID v4 (cryptographically random)
- ✅ Role-based access control (admin, guru, siswa)
- ✅ CSRF protection pada semua form
- ✅ Authorization check di setiap controller
- ✅ WhatsApp API key disimpan di environment variable

## 📊 Database Schema

### borrowing_requests

Kolom utama:
- `user_id` - ID siswa peminjam
- `teacher_id` - ID guru yang ditugaskan
- `item_id` - ID barang yang dipinjam
- `quantity` - Jumlah barang
- `status` - pending, approved, borrowed, returned, rejected
- `qr_token` - Token UUID untuk QR code
- `checkout_at`, `returned_at` - Timestamp aktual
- `checkout_by`, `checkin_by` - User yang melakukan scan
- `reminder_sent_at` - Timestamp reminder terkirim
- `return_condition` - good, damaged, lost

### qr_codes

- `borrowing_request_id` - Foreign key
- `code` - Token QR (sama dengan borrowing_requests.qr_token)
- `scan_count` - Jumlah kali di-scan
- `last_scanned_at` - Waktu scan terakhir

### whatsapp_notification_logs

- `borrowing_request_id` - Foreign key
- `notification_type` - pengajuan_baru, ditolak, disetujui, dll
- `recipient_phone` - Nomor tujuan
- `status` - pending, success, failed
- `http_status_code` - HTTP status dari bot
- `error_message` - Pesan error jika gagal

## 🧪 Testing

### Manual Testing Checklist

1. **Approval Flow**
   - Login sebagai guru
   - Buka `/guru/permohonan`
   - Setujui request → cek QR code di storage
   - Tolak request → cek alasan tersimpan

2. **QR Scanner**
   - Login sebagai guru
   - Buka `/guru/qr/scan`
   - Scan QR approved → Checkout
   - Scan QR borrowed → Checkin dengan kondisi

3. **Transaction History**
   - Login sebagai admin
   - Buka `/transactions`
   - Test filter by nama, barang, status, tanggal
   - Klik detail transaksi

4. **Scheduler**
   - Setup data: borrowing dengan return_date = besok
   - Run: `php artisan borrowing:send-reminders`
   - Cek `reminder_sent_at` terupdate

### Unit Tests (Coming Soon)

```bash
php artisan test --testsuite=Unit
```

### Feature Tests (Coming Soon)

```bash
php artisan test --testsuite=Feature
```

## 📝 API Documentation

### Internal API (untuk frontend)

Semua route menggunakan web middleware (session-based auth).

**Teacher Routes:**
```
GET    /guru/permohonan             - List pending requests
POST   /guru/permohonan/{id}/approve - Approve request
POST   /guru/permohonan/{id}/reject  - Reject request
GET    /guru/qr/scan                - QR scanner page
```

**Admin Routes:**
```
GET    /transactions        - Transaction history with filters
GET    /transactions/{id}   - Transaction detail
```

## 🐛 Troubleshooting

### QR Code tidak muncul

```bash
# Pastikan storage link sudah dibuat
php artisan storage:link

# Cek permissions folder storage
chmod -R 775 storage/app/public
```

### Scanner tidak berfungsi di mobile

- Pastikan menggunakan HTTPS (kamera browser butuh secure context)
- Atau gunakan `localhost` untuk testing
- Izinkan akses kamera di browser settings

### Scheduler tidak jalan

```bash
# Test schedule manually
php artisan schedule:run

# Cek apakah cron sudah disetup
crontab -l

# Cek log
tail -f storage/logs/laravel.log
```

### WhatsApp notification failed

- Sistem akan tetap berfungsi normal
- Cek log: `storage/logs/laravel.log`
- Cek tabel `whatsapp_notification_logs` untuk detail error
- Pastikan `WA_BOT_URL` dan `WA_BOT_API_KEY` di `.env` benar

## 📄 License

This project is proprietary software. All rights reserved.

## 🤝 Contributing

Untuk kontribusi atau bug report, silakan hubungi tim development.

## 📧 Contact

- Project Lead: [Your Name]
- Email: [your.email@example.com]

---

**Version:** 2.0.0  
**Last Updated:** August 12, 2026
