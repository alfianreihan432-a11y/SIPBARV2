# 📚 Panduan Lengkap: Login Restriction Mode - SIPBAR v2

**Tanggal:** 2026-09-11  
**Feature:** Login Restriction / Maintenance Mode  
**Purpose:** Membatasi akses siswa sementara tetap mengizinkan staff (Admin, Guru, Kepala Jurusan)

---

## 🎯 APA ITU LOGIN RESTRICTION MODE?

### Konsep
Login Restriction adalah **FITUR MAINTENANCE** yang memungkinkan sekolah membatasi akses login siswa sementara **tetap mengizinkan staff** (Admin, Guru, Kepala Jurusan, Superadmin) masuk ke sistem.

### Use Cases
- 🎓 **Ujian Nasional/UTS/UAS:** Batasi akses siswa agar tidak mengganggu sistem
- 🔧 **Maintenance System:** Matikan akses siswa saat update/perbaikan
- 📊 **Import Data Besar:** Hindari beban sistem dari siswa saat import data
- 🎯 **Selective Testing:** Hanya izinkan beberapa siswa untuk testing

---

## ⚙️ CARA KERJA

### Alur Logic

```
User Login
    ↓
Authentication Check (Password benar?)
    ↓ YES
Cek LOGIN_RESTRICTION_MODE enabled?
    ↓ NO → ✅ ALLOW ALL
    ↓ YES
Cek Role User?
    ├─ Admin/Superadmin → ✅ ALWAYS ALLOW
    ├─ Guru → ✅ ALWAYS ALLOW  
    ├─ Kepala Jurusan → ✅ ALWAYS ALLOW
    └─ Siswa → Cek Whitelist?
              ├─ In whitelist → ✅ ALLOW
              └─ Not in whitelist → ❌ BLOCK ("Akses sementara dibatasi")
```

### File-file Terlibat

1. **Config:** `config/sipbar.php`
2. **Environment:** `.env` → `LOGIN_RESTRICTION_MODE` & `STUDENT_LOGIN_WHITELIST`
3. **Middleware:** `app/Http/Middleware/CheckLoginRestriction.php`
4. **Authentication:** `app/Actions/Fortify/AuthenticateUser.php`

---

## 🔧 KONFIGURASI

### 1. Environment Variables (.env)

```env
# Aktifkan/Nonaktifkan Restriction
LOGIN_RESTRICTION_MODE=true    # true = aktif (blokir siswa tidak di whitelist)
                               # false = nonaktif (semua siswa bisa login)

# Whitelist Siswa (comma-separated)
STUDENT_LOGIN_WHITELIST=4714@smkn1bangsri.sch.id,webdev@smkn1bangsri.sch.id,basket@smkn1bangsri.sch.id
```

### 2. Format Whitelist

Whitelist bisa menggunakan:
- **Email lengkap:** `4714@smkn1bangsri.sch.id`
- **NIS saja:** `4714` (otomatis ditambah domain)
- **Username:** `webdev` (otomatis ditambah domain)

**Contoh:**
```env
STUDENT_LOGIN_WHITELIST=4714,4715,webdev@smkn1bangsri.sch.id,basket
```

Akan di-parse menjadi:
- `4714@smkn1bangsri.sch.id`
- `4715@smkn1bangsri.sch.id`
- `webdev@smkn1bangsri.sch.id`
- `basket@smkn1bangsri.sch.id`

---

## 🚫 KENAPA ERROR "Akses sementara dibatasi"?

### Penyebab Umum

#### 1. ✅ **Mode Aktif + Siswa Tidak di Whitelist** (BY DESIGN)
```
LOGIN_RESTRICTION_MODE=true
STUDENT_LOGIN_WHITELIST=4714,4715
```
→ Siswa dengan NIS 4716 akan di-block ✅ **INI NORMAL!**

#### 2. ❌ **Dashboard Redirect Salah** (BUG)
Jika role baru (e.g., Superadmin, Kepala Jurusan) tidak dihandle di `DashboardController`, user akan fallback ke view yang salah.

**Sudah Diperbaiki:**
- ✅ Superadmin → redirect ke `/superadmin/dashboard`
- ✅ Kepala Jurusan → redirect ke `/kajur/dashboard`
- ✅ Guru → view `dashboard-guru`
- ✅ Siswa → view `dashboard-siswa`
- ✅ Admin → view `dashboard`

#### 3. ❌ **Role Tidak Terdaftar di Logic** (BUG - SEHARUSNYA TIDAK TERJADI)
Jika role baru ditambah tapi tidak ditambahkan ke whitelist check, akan di-treat sebagai siswa.

**Role yang SELALU Diizinkan:**
```php
// Di AuthenticateUser.php & CheckLoginRestriction.php
- admin
- superadmin, super-admin, super_admin (semua variasi)
- guru
- kepala_jurusan
```

---

## ✅ SOLUSI: BUKA AKSES UNTUK SEMUA

### Opsi 1: Matikan Login Restriction (RECOMMENDED untuk Production Normal)

**Edit `.env`:**
```env
LOGIN_RESTRICTION_MODE=false
```

**Restart Application:**
```bash
php artisan config:clear
php artisan cache:clear
```

✅ **Semua siswa bisa login!**  
✅ **Staff tetap bisa login!**  
✅ **Tidak ada yang di-block!**

---

### Opsi 2: Whitelist Semua Siswa (Jika Ingin Mode Tetap Aktif)

**Buat Command untuk Generate Whitelist:**

```bash
php artisan tinker

# Get all student emails
$students = \App\Models\User::role('siswa')->pluck('email')->implode(',');
echo $students;
```

**Copy output dan paste ke `.env`:**
```env
STUDENT_LOGIN_WHITELIST=student1@...,student2@...,student3@...
```

**Restart:**
```bash
php artisan config:clear
```

---

### Opsi 3: Hapus Middleware (TIDAK RECOMMENDED - Breaking Future Updates)

**Edit `bootstrap/app.php`:**
```php
// COMMENT OUT atau HAPUS line ini:
// $middleware->appendToGroup('web', [
//     \App\Http\Middleware\CheckLoginRestriction::class,
// ]);
```

⚠️ **WARNING:** Ini akan disable fitur secara permanen. Lebih baik gunakan Opsi 1!

---

## 🛡️ SAFEGUARD: Mencegah Masalah di Masa Depan

### 1. Tambahkan Role Baru ke Whitelist Logic

**Jika menambah role baru (e.g., `petugas`, `wakasek`):**

**Edit `app/Http/Middleware/CheckLoginRestriction.php`:**
```php
// Tambahkan role baru di sini
if ($user->hasRole('guru') || $user->hasRole('kepala_jurusan') || $user->hasRole('petugas')) {
    return $next($request);
}
```

**Edit `app/Actions/Fortify/AuthenticateUser.php`:**
```php
$isAdminOrSuperAdmin = $user->hasAnyRole(['admin', 'superadmin', 'super-admin', 'super_admin']);
$isTeacher = $user->hasRole('guru');
$isKepalaJurusan = $user->hasRole('kepala_jurusan');
$isPetugas = $user->hasRole('petugas'); // TAMBAH INI

// Update kondisi
if (! $isAdminOrSuperAdmin && ! $isTeacher && ! $isKepalaJurusan && ! $isPetugas) {
    // Check whitelist for students
}
```

---

### 2. Handle Role Baru di DashboardController

**Edit `app/Http/Controllers/DashboardController.php`:**
```php
public function index(Request $request)
{
    $user = $request->user();

    // Tambahkan check untuk role baru
    if ($user->hasRole('petugas')) {
        return view('dashboard-petugas'); // atau redirect
    }

    // ... existing code ...
}
```

---

### 3. Dokumentasi Environment Variables

**Tambahkan ke `.env.example`:**
```env
# ══════════════════════════════════════════
# LOGIN RESTRICTION MODE
# ══════════════════════════════════════════
# Mode pembatasan login siswa untuk maintenance/ujian
# false = Semua siswa bisa login (NORMAL MODE)
# true = Hanya siswa di whitelist yang bisa login (MAINTENANCE MODE)
LOGIN_RESTRICTION_MODE=false

# Daftar siswa yang diizinkan login saat restriction aktif
# Format: email atau NIS, pisahkan dengan koma
# Contoh: 4714,4715,webdev@smkn1bangsri.sch.id
STUDENT_LOGIN_WHITELIST=
```

---

## 🧪 TESTING CHECKLIST

### Test SEMUA Role

Setelah mengubah `LOGIN_RESTRICTION_MODE=false`:

- [ ] **Admin** (`admintu@smkn1bangsri.sch.id`) → ✅ Bisa login, redirect ke dashboard admin
- [ ] **Superadmin** (`superadmin@smkn1bangsri.sch.id`) → ✅ Bisa login, redirect ke `/superadmin/dashboard`
- [ ] **Guru** (any guru account) → ✅ Bisa login, tampil `dashboard-guru`
- [ ] **Kepala Jurusan PPLG** → ✅ Bisa login, redirect ke `/kajur/dashboard`
- [ ] **Kepala Jurusan AKL** → ✅ Bisa login, redirect ke `/kajur/dashboard`
- [ ] **Kepala Jurusan PM** → ✅ Bisa login, redirect ke `/kajur/dashboard`
- [ ] **Kepala Jurusan MPLB** → ✅ Bisa login, redirect ke `/kajur/dashboard`
- [ ] **Kepala Jurusan TO** → ✅ Bisa login, redirect ke `/kajur/dashboard`
- [ ] **Siswa** (random student) → ✅ Bisa login, tampil `dashboard-siswa`

---

## 📋 DEPLOYMENT CHECKLIST - PRODUCTION

### Saat Deploy ke Production:

1. **Backup `.env` production saat ini**
   ```bash
   cp .env .env.backup
   ```

2. **Update `.env` dengan setting yang diinginkan:**
   ```bash
   # PILIH SALAH SATU:
   
   # Opsi A: Buka akses semua (RECOMMENDED)
   LOGIN_RESTRICTION_MODE=false
   
   # Opsi B: Tetap restrict tapi whitelist lebih banyak
   LOGIN_RESTRICTION_MODE=true
   STUDENT_LOGIN_WHITELIST=osis@smkn1bangsri.sch.id,webdev@smkn1bangsri.sch.id,etc
   ```

3. **Clear all caches:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   php artisan optimize
   ```

4. **Test login untuk SETIAP role** (gunakan checklist di atas)

5. **Monitor logs untuk error:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

---

## 🔍 TROUBLESHOOTING

### Error Masih Muncul Setelah Set `LOGIN_RESTRICTION_MODE=false`?

**1. Pastikan config ter-reload:**
```bash
php artisan config:clear
php artisan config:cache
```

**2. Check config value di runtime:**
```bash
php artisan tinker
>>> config('sipbar.login_restriction.enabled')
# Harus return: false
```

**3. Check .env file benar:**
```bash
cat .env | grep LOGIN_RESTRICTION_MODE
# Harus tampil: LOGIN_RESTRICTION_MODE=false
```

---

### Specific Role Tidak Bisa Login?

**Check DashboardController:**
```bash
php artisan route:list | grep dashboard
```

Pastikan role tersebut punya explicit handling di `DashboardController@index`.

---

### Siswa Tertentu Tidak Bisa Login (Mode Aktif)?

**Check whitelist:**
```bash
php artisan tinker
>>> config('sipbar.login_restriction.whitelisted_students')
```

Pastikan email/NIS siswa ada di list.

---

## 📊 MONITORING & ANALYTICS

### Log Login Attempts

Tambahkan logging di `AuthenticateUser.php` untuk debugging:

```php
\Log::info('Login attempt', [
    'email' => $user->email,
    'role' => $user->roles->pluck('name'),
    'restriction_mode' => config('sipbar.login_restriction.enabled'),
    'is_whitelisted' => $isWhitelisted ?? 'N/A',
]);
```

### Dashboard Stats

Tambahkan widget di admin dashboard:
- Restriction mode status (ON/OFF)
- Jumlah siswa di whitelist
- Login attempts today
- Blocked login attempts

---

## 🎓 BEST PRACTICES

### 1. Communication
Sebelum aktifkan restriction mode, **INFORM STAFF & SISWA**:
- Email blast
- Announcement di website
- WhatsApp broadcast
- Notice di login page

### 2. Timing
Aktifkan restriction **HANYA saat diperlukan**:
- ✅ Ujian Nasional/UTS/UAS
- ✅ Maintenance besar
- ❌ Jangan di-default ON untuk production normal

### 3. Whitelist Management
Gunakan Google Sheets/Excel untuk manage whitelist:
- Column A: NIS
- Column B: Nama
- Column C: Email
- Export column A atau C → paste ke `.env`

### 4. Testing
**SELALU test** di staging/local sebelum production:
```bash
# Local test dengan restriction ON
LOGIN_RESTRICTION_MODE=true
STUDENT_LOGIN_WHITELIST=test_student@smkn1bangsri.sch.id

# Verify:
# ✅ Test student bisa login
# ❌ Other students di-block
# ✅ All staff bisa login
```

---

## 💡 FUTURE IMPROVEMENTS

### Suggested Enhancements:

1. **Admin UI untuk Toggle Mode**
   - Button di admin dashboard: "Enable/Disable Maintenance Mode"
   - Auto-update `.env` via admin panel

2. **Whitelist Management UI**
   - Upload CSV siswa
   - Add/remove individual students
   - Bulk actions

3. **Scheduled Restriction**
   - Set start/end datetime
   - Auto enable/disable based on schedule
   - Useful for ujian terjadwal

4. **Notification System**
   - Email admin saat restriction mode changed
   - Log semua blocked login attempts
   - Alert jika banyak failed attempts

5. **Custom Messages per Event**
   - Ujian: "Sistem sedang digunakan untuk ujian"
   - Maintenance: "Sistem dalam perbaikan"
   - Custom message per periode

---

## ✅ KESIMPULAN

### Error "Akses sementara dibatasi" adalah:

1. ✅ **FITUR BY DESIGN** - Bukan bug!
2. ✅ **Mudah Di-disable** - Set `LOGIN_RESTRICTION_MODE=false`
3. ✅ **Code Sudah Benar** - Staff always allowed, hanya siswa yang di-filter
4. ✅ **Well Documented** - Guide ini untuk referensi

### Quick Fix untuk Production:

```bash
# 1. Edit .env
LOGIN_RESTRICTION_MODE=false

# 2. Clear cache
php artisan config:clear && php artisan cache:clear

# 3. Done! Semua bisa login.
```

---

**Dokumentasi ini dibuat untuk:**
- ✅ Menjelaskan feature login restriction
- ✅ Troubleshooting error "Akses sementara dibatasi"
- ✅ Panduan deployment production
- ✅ Best practices & safeguards

**Maintainer:** System Administrator SIPBAR  
**Last Updated:** 2026-09-11  
**Version:** 2.0 - Comprehensive Guide
