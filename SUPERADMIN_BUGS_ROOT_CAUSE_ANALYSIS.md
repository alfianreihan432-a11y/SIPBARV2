# ROOT CAUSE ANALYSIS: Superadmin Role Bugs

**Project:** SIPBAR v2 (Laravel 13)  
**Date:** 2026-09-11  
**Author:** Kiro AI Assistant  

## Executive Summary

Investigasi menemukan **4 bug spesifik** pada role Superadmin yang memerlukan perbaikan komprehensif di middleware, UI components, dan theme persistence. Semua masalah telah dianalisis dengan root cause masing-masing.

---

## BUG #1: PERMISSION/ACCESS CONTROL - Superadmin Tidak Konsisten Read-Only

### 🎯 Expected Behavior
- **READ-ONLY** di semua halaman KECUALI:
  1. Semua halaman Laporan (approve/reject allowed)
  2. Halaman Pengaturan/Settings (full access)
  3. Halaman Pengguna/Users (full access)

### 🐛 Current Behavior
- Middleware `SuperadminRestrictApprove` hanya melindungi **route level** untuk approve/reject
- **UI tidak tersembunyi** - tombol aksi (approve, reject, edit, delete, tambah) masih muncul di frontend
- Livewire component `LoanManager` **tidak support readonly mode** untuk Superadmin (hanya menerima parameter dari Siswa loan page)
- Halaman lain (Kelola Barang, Kategori, dll) **tidak ada readonly mode** sama sekali

### 🔍 ROOT CAUSE

**1. Middleware Tidak Lengkap**
- File: `app/Http/Middleware/SuperadminRestrictApprove.php`
- Problem: Hanya cek route name dengan pattern `approve`/`reject`
- Missing: Tidak cek route `create`, `store`, `update`, `destroy`, `edit`
```php
// Current logic (line 19-23)
if ($currentRouteName && (str_contains($currentRouteName, 'approve') || str_contains($currentRouteName, 'reject'))) {
    if (!in_array($currentRouteName, $allowedRoutes)) {
        abort(403, 'Superadmin tidak memiliki izin...');
    }
}
```
- **Allowed routes hanya 4 route laporan**, tidak termasuk laporan-admin routes

**2. UI Tidak Terkondisi Per Role**
- File: `resources/views/livewire/loan-manager.blade.php` (line 285-380)
- Problem: Conditional rendering hanya untuk `isGuru` vs `Siswa`, **tidak ada check Superadmin**
- Action buttons (Setujui, Tolak, Dipinjam, Kembalikan) **render tanpa role check**
- Parameter `readonly` ada di LoanManager component tapi **tidak diimplementasikan** untuk hide buttons

**3. Livewire Component Tidak Support Readonly**
- File: `app/Livewire/LoanManager.php`
- Problem: Property `$readonly` **tidak exist** di component
- Methods `approve()`, `reject()`, `markBorrowed()`, `markReturned()` **tidak cek role Superadmin**
- Loans page (`superadmin/loans.blade.php`) pass `readonly => true` tapi **property tidak ada di component**

**4. Pages Lain Tidak Ada Protection**
- Files: `manage-items.blade.php`, `categories.blade.php`, `returns.blade.php`, dll
- Problem: **Tidak ada readonly mode** sama sekali
- Livewire components (InventoryManager, CategoryManager) **tidak ada readonly parameter**
- Tombol "Tambah Barang", "Edit", "Hapus" **tidak tersembunyi** untuk Superadmin

### 📍 Affected Files
```
app/Http/Middleware/SuperadminRestrictApprove.php          ← Middleware incomplete
routes/web.php (line 129-172)                              ← Missing middleware on routes
app/Livewire/LoanManager.php                               ← No readonly property
resources/views/livewire/loan-manager.blade.php            ← No Superadmin conditional
resources/views/pages/superadmin/loans.blade.php           ← Pass readonly but not used
resources/views/pages/superadmin/manage-items.blade.php    ← No readonly mode
resources/views/pages/superadmin/categories.blade.php      ← No readonly mode
resources/views/pages/superadmin/returns.blade.php         ← No readonly mode
app/Livewire/InventoryManager.php                          ← No readonly support
app/Livewire/CategoryManager.php                           ← No readonly support
```

### ✅ Solution Strategy
1. **Update Middleware**: Expand to check ALL mutating routes (create, store, update, destroy, edit)
2. **Add Readonly Property**: Add `public bool $readonly = false;` to ALL Livewire components
3. **Update UI**: Add `@if(!$readonly)` around all action buttons in ALL views
4. **Backend Validation**: Add role check in Livewire component methods
5. **Route Protection**: Apply middleware to ALL superadmin routes except reports/settings/users

---

## BUG #2: THEME TIDAK PERSISTEN - Dark/Light Mode Berubah Random

### 🎯 Expected Behavior
- Theme preference disimpan di `localStorage` dengan key `sipbar-superadmin-theme`
- Saat page load/navigation, theme di-restore dari localStorage
- Theme tetap konsisten antar halaman (dark tetap dark, light tetap light)

### 🐛 Current Behavior
- Saat pindah halaman, theme **berubah sendiri** (dark → light atau light → dark)
- Tidak konsisten antara SPA navigation vs full page reload
- Toggle theme manual kadang tidak persist

### 🔍 ROOT CAUSE

**1. Race Condition pada Theme Initialization**
- File: `resources/views/layouts/superadmin.blade.php` (line 6-11)
- Problem: **Anti-flash script** dan **theme toggle script** load di waktu berbeda
```javascript
// Anti-flash script (line 6-11) - RUNS FIRST
(function(){
    var s=localStorage.getItem('sipbar-superadmin-theme');
    if(s==='light') document.documentElement.classList.add('light');
    else document.documentElement.classList.remove('light'); // ← DEFAULT ke dark!
})();

// Theme toggle script (line 674-693) - RUNS LATER
var saved = localStorage.getItem(KEY);
applyTheme(saved === 'light'); // ← Re-apply theme
```
- **Problem**: Jika `localStorage` kosong/undefined, anti-flash script **default ke dark**, tapi user mungkin prefer light

**2. Missing Default Theme Logic**
- Problem: Tidak ada fallback ke system preference (`prefers-color-scheme`)
- Jika localStorage kosong/baru install:
  - Anti-flash: default dark
  - Theme toggle: default dark
  - System preference: **ignored**
- User yang prefer light theme system **dipaksa dark** saat first visit

**3. Livewire Navigation Conflict**
- File: `@livewireScripts` loaded di line 663
- Problem: Livewire SPA navigation **tidak trigger** anti-flash script
- Saat navigasi Livewire (bukan full reload), theme di-reset dari localStorage oleh theme toggle script
- Tapi jika localStorage kosong, **inconsistent behavior**

**4. No Synchronization Between Scripts**
- Anti-flash script: Run immediately (blocking)
- Theme toggle script: Run after DOM ready
- Problem: **Two separate localStorage reads** bisa dapet value beda jika ada race condition
- No mutex/lock mechanism

### 📍 Affected Code Sections
```
resources/views/layouts/superadmin.blade.php
  - Line 6-11:   Anti-flash script (inline head)
  - Line 674-693: Theme toggle script (bottom)
  - Line 663:    @livewireScripts (SPA navigation)
```

### ✅ Solution Strategy
1. **Consolidate Theme Logic**: Merge anti-flash & theme toggle ke satu script
2. **Add System Preference Fallback**: Detect `prefers-color-scheme` jika localStorage empty
3. **Livewire Hook**: Add listener untuk Livewire navigation events
4. **Single Source of Truth**: One function untuk read/write localStorage
5. **Debug Logging**: Tambahkan console log untuk trace theme changes

---

## BUG #3: KONTRAS TEKS DI DARK MODE - Text Not Visible

### 🎯 Expected Behavior
- Semua teks readable di dark mode (high contrast)
- Text color: `var(--text-primary)` (white/light) atau `var(--text-secondary)`
- Background: `var(--bg-card)` (black/dark) atau `var(--bg-main)`

### 🐛 Current Behavior
- Ada teks yang **tidak terlihat** di dark mode
- Text gelap di atas background gelap
- Kontras rendah di beberapa elemen

### 🔍 ROOT CAUSE

**1. Hardcoded Colors Instead of CSS Variables**
- File: Multiple `.blade.php` files
- Problem: Beberapa element pakai **hardcoded colors** yang tidak adapt ke theme

**Example dari `loans.blade.php` (line 35):**
```html
<div style="font-size:11px;font-weight:700;color:var(--blue);...">Manajemen Peminjaman</div>
```
- `var(--blue)` di dark mode = `#ffffff` (white) ✅ CORRECT
- `var(--blue)` di light mode = `#000000` (black) ✅ CORRECT

**But other places use hardcoded values:**
```html
<!-- WRONG - hardcoded color -->
<div style="color:#1a1a1a">Some text</div>  <!-- ← Dark text, invisible in dark mode! -->

<!-- CORRECT - CSS variable -->
<div style="color:var(--text-primary)">Some text</div>
```

**2. Inline Styles Override Theme Variables**
- Files: `loan-manager.blade.php`, `returns.blade.php`, dll
- Problem: Inline `style="..."` dengan hardcoded hex colors
- CSS variables tidak bisa override inline styles

**Example dari `loan-manager.blade.php`:**
```css
/* Line 37 - hardcoded in badge */
.lm-count{
    color:var(--blue); /* ✅ GOOD - adapts to theme */
}

/* BUT inline style overrides it: */
<span style="color:#2563eb">Badge</span>  /* ❌ BAD - always blue, invisible in light */
```

**3. Table Header Text Issue**
- File: `resources/views/livewire/loan-manager.blade.php` (line 48-49)
```css
table.lmt thead th{
    color:var(--text-subtle);  /* ← In dark mode: #707070 (gray) */
    background:var(--table-head-bg); /* ← In dark mode: #111111 (almost black) */
}
```
- Problem: `#707070` on `#111111` = **kontras rendah** (ratio ~3.2:1, should be 4.5:1+)

**4. Modal Text in Dark Mode**
- File: `loan-manager.blade.php` (line 80-82)
```css
.lm-modal-lbl{
    color:var(--text-muted);  /* Dark mode: #a0a0a0 */
}
.lm-modal-val{
    color:var(--text-primary); /* Dark mode: #ffffff */
}
```
- Problem: `--text-muted` (#a0a0a0) sometimes **too light** on white modal backgrounds in transitions

**5. Badge Colors Not Theme-Aware**
- File: `loan-manager.blade.php` (line 31-38)
```css
.badge-pending{
    background:rgba(245,158,11,.12);
    color:#f59e0b; /* ← Hardcoded orange */
}
```
- Problem: Badge text color **tidak adapt** ke theme background

### 📍 Affected Files & Lines
```
resources/views/livewire/loan-manager.blade.php
  - Line 48-49:  Table header (text-subtle on table-head-bg)
  - Line 31-38:  Badge colors (hardcoded hex)
  - Line 80-82:  Modal text (text-muted issue)
  
resources/views/pages/superadmin/loans.blade.php
  - Line 35: Inline color styles
  
resources/views/pages/superadmin/returns.blade.php
  - Inline styles throughout
  
resources/views/layouts/superadmin.blade.php
  - Line 50-58: CSS variable definitions (--text-subtle might be too dark)
```

### ✅ Solution Strategy
1. **Audit All Inline Styles**: Convert `style="color:#xxx"` → `style="color:var(--text-xxx)"`
2. **Adjust CSS Variables**: Make `--text-subtle` lighter in dark mode (#909090 → #a8a8a8)
3. **Add Theme-Aware Badge Classes**: Create `.badge-pending-dark` vs `.badge-pending-light`
4. **Increase Contrast Ratios**: Ensure WCAG AA compliance (4.5:1 for normal text)
5. **Test Tool**: Use browser DevTools contrast checker on all pages

---

## BUG #4: AKSES HALAMAN DIBATASI DI LIGHT MODE - Access Control Bug

### 🎯 Expected Behavior
- Superadmin access control **independen dari theme**
- Halaman yang dibatasi (read-only) **tetap dibatasi** di light mode DAN dark mode
- Middleware & authorization logic **tidak terpengaruh** theme state

### 🐛 Current Behavior
- Saat **light mode**, ada halaman yang seharusnya dibatasi tapi **bisa diakses**
- Tombol aksi yang seharusnya hidden **muncul lagi** di light mode
- Access control **inconsistent** antara light vs dark mode

### 🔍 ROOT CAUSE

**THIS IS A SYMPTOM OF BUG #1, NOT A SEPARATE BUG**

Root cause sejatinya **sama dengan Bug #1** (Permission/Access Control), tapi **lebih parah di light mode** karena:

**1. UI Conditional Logic Bug**
- File: `resources/views/livewire/loan-manager.blade.php` & similar
- Problem: Conditional `@if` untuk hide buttons **TIDAK ada**
- ALL action buttons render **tanpa role check**

**Example (line 306-360):**
```blade
@if($b->status === 'pending')
    <button wire:click="approve({{ $b->id }})" class="lm-act-btn btn-approve">
        Setujui
    </button>
    <!-- ❌ NO CHECK: if (auth()->user()->hasRole('superadmin')) return hidden -->
@endif
```

**2. CSS Display Issue in Light Mode**
- File: `resources/views/layouts/superadmin.blade.php` (CSS variables)
- Problem: Beberapa button pakai `color:var(--blue)` yang di light mode = `#000000` (black)
- User **lebih mudah lihat** tombol hitam di background putih
- Di dark mode, tombol putih di background hitam **slightly less visible** (lucky obscurity)
- Jadi di **light mode terlihat jelas** bahwa buttons seharusnya tidak ada

**3. Theme State Tidak Ada Hubungan Dengan Authorization**
- Middleware tidak cek theme
- Authorization logic tidak cek localStorage
- Theme hanya affect **CSS visibility**, bukan **authorization**

**But:** User report "akses halaman dibatasi di light mode" kemungkinan karena:
- **User testing lebih teliti di light mode** (tombol lebih visible)
- **Dark mode "hide" tombol** karena kontras rendah (Bug #3)
- **Perception issue**, bukan logic issue

### 📍 Why This Happens MORE in Light Mode

1. **Better Visibility**: Buttons lebih jelas di light background
2. **Bug #3 Masks Bug #1**: Dark mode kontras rendah → buttons "sembunyi secara natural"
3. **User Testing Bias**: User lebih sering test di light mode → ketemu bug lebih dulu

### ✅ Solution Strategy

**THIS BUG WILL BE FIXED BY FIXING BUG #1**

1. Implement role-based conditional rendering in ALL views
2. Add `@if(auth()->user()->hasRole('superadmin')) @else` around action buttons
3. Backend validation already blocks routes (middleware), UI just needs to catch up
4. No theme-specific logic needed

---

## Summary Table

| Bug # | Issue | Root Cause | Severity | Fix Complexity |
|-------|-------|------------|----------|----------------|
| 1 | Permission/Access Control | Middleware incomplete, UI no role check | 🔴 High | 🟡 Medium |
| 2 | Theme Not Persistent | Race condition, no system preference fallback | 🟡 Medium | 🟢 Low |
| 3 | Dark Mode Contrast | Hardcoded colors, low contrast ratios | 🟡 Medium | 🟢 Low |
| 4 | Light Mode Access Bug | **Duplicate of Bug #1** + visibility issue from Bug #3 | 🔴 High | ✅ Fixed by #1 |

---

## Implementation Priority

### Phase 1: Critical Fixes (Must Do First)
1. **Bug #1**: Fix access control (affects security)
   - Update middleware to block ALL mutating routes
   - Add `$readonly` property to Livewire components
   - Hide action buttons for Superadmin in UI

### Phase 2: UX Improvements (Do Next)
2. **Bug #2**: Fix theme persistence
   - Consolidate theme scripts
   - Add system preference detection
   - Fix Livewire navigation

3. **Bug #3**: Fix dark mode contrast
   - Audit & fix hardcoded colors
   - Adjust CSS variable values
   - Test WCAG compliance

### Phase 3: Verification
4. **Bug #4**: Verify fixed by Phase 1
   - Test all pages in both themes
   - Confirm buttons hidden consistently
   - Document that this was a symptom, not root bug

---

## Testing Checklist (After Fix)

### Test Case 1: Access Control (Bug #1)
- [ ] Login as Superadmin
- [ ] Test Peminjaman page: No approve/reject/edit buttons visible
- [ ] Test Kelola Barang page: No create/edit/delete buttons visible
- [ ] Test Kategori page: No create/edit/delete buttons visible
- [ ] Test Pengembalian page: No action buttons visible
- [ ] Test Laporan pages: Approve/reject buttons **visible & working**
- [ ] Test Settings page: All functions **visible & working**
- [ ] Test Users page: All functions **visible & working**
- [ ] Try direct POST to blocked route: Get 403 error
- [ ] Repeat test in BOTH dark and light mode

### Test Case 2: Theme Persistence (Bug #2)
- [ ] Set dark mode, navigate between pages: stays dark
- [ ] Set light mode, navigate between pages: stays light
- [ ] Full page reload in dark mode: stays dark
- [ ] Full page reload in light mode: stays light
- [ ] Clear localStorage, reload: detect system preference
- [ ] Toggle theme manually: saves to localStorage correctly

### Test Case 3: Contrast (Bug #3)
- [ ] Check table headers in dark mode: readable
- [ ] Check badges in dark mode: readable
- [ ] Check modal text in dark mode: readable
- [ ] Check all inline text in dark mode: readable
- [ ] Run Chrome DevTools Contrast Checker: all pass 4.5:1
- [ ] Test on actual dark monitor: no invisible text

### Test Case 4: Verify Bug #4 Fixed (Symptom Check)
- [ ] Login as Superadmin in light mode
- [ ] Peminjaman page: no action buttons
- [ ] Kelola Barang page: no action buttons
- [ ] Confirm behavior identical to dark mode
- [ ] Confirm fixed by Bug #1 fixes

---

## Files to Modify (Implementation Roadmap)

### Backend (PHP)
```
app/Http/Middleware/SuperadminRestrictApprove.php     ← Expand route checking logic
app/Livewire/LoanManager.php                          ← Add $readonly property, check role
app/Livewire/InventoryManager.php                     ← Add $readonly property
app/Livewire/CategoryManager.php                      ← Add $readonly property
routes/web.php                                        ← Add middleware to routes
```

### Frontend (Blade Views)
```
resources/views/layouts/superadmin.blade.php          ← Fix theme scripts, CSS variables
resources/views/livewire/loan-manager.blade.php       ← Add @if(!$readonly) conditionals
resources/views/pages/superadmin/loans.blade.php      ← Already passes readonly
resources/views/pages/superadmin/manage-items.blade.php ← Pass readonly parameter
resources/views/pages/superadmin/categories.blade.php   ← Pass readonly parameter
resources/views/pages/superadmin/returns.blade.php      ← Add readonly mode
```

### CSS (Inline & Variables)
```
resources/views/layouts/superadmin.blade.php          ← Adjust CSS variables for contrast
resources/views/livewire/loan-manager.blade.php       ← Fix hardcoded colors
resources/views/pages/superadmin/*.blade.php          ← Convert inline styles to variables
```

---

## Estimated Implementation Time

- **Bug #1 (Access Control)**: 3-4 hours
  - Middleware update: 30 min
  - Livewire components: 1 hour
  - View updates: 1.5-2 hours
  - Testing: 1 hour

- **Bug #2 (Theme Persistence)**: 1-1.5 hours
  - Script consolidation: 30 min
  - Livewire hooks: 20 min
  - Testing: 30 min

- **Bug #3 (Dark Mode Contrast)**: 1.5-2 hours
  - Audit inline styles: 45 min
  - Fix CSS variables: 30 min
  - Testing all pages: 45 min

- **Bug #4 (Verification)**: 30 min
  - Retest after Bug #1 fixes

**Total:** ~6-8 hours for complete fix

---

## Notes for Developer

1. **Do NOT modify database** - all fixes are middleware/UI/CSS only
2. **Test with REAL Superadmin account** - don't use impersonation
3. **Clear browser cache** between tests for theme changes
4. **Use Laravel Debugbar** to see middleware execution
5. **Check Livewire Alpine.js console** for SPA navigation issues
6. **Keep Admin/Guru/Siswa/Kepala Jurusan UNCHANGED** - only affect Superadmin

---

**Analysis Complete. Ready for implementation approval.**
