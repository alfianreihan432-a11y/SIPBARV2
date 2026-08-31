# Troubleshooting Sinkronisasi SiPintu - Data Tidak Muncul

## Masalah

Ketika sinkronisasi SiPintu dijalankan, data siswa, guru, dan password tidak muncul di database SIPBAR.

## Root Cause

Berdasarkan log Laravel, error yang terjadi:

```
[2026-08-31 03:36:43] local.ERROR: SiPintu sync students failed {"error":"HTTP 404: The route api/v1/sijuna/students could not be found."}
[2026-08-31 03:36:44] local.ERROR: SiPintu sync teachers failed {"error":"HTTP 404: The route api/v1/sijuna/teachers could not be found."}
```

**Masalah:** Endpoint SiPintu Gateway tidak ditemukan karena URL SiPintu yang dikonfigurasi salah.

## Analisis

### 1. Konfigurasi Endpoint (config/sipintu.php)

```php
'endpoints' => [
    'students' => '/api/v1/sijuna/students',
    'teachers' => '/api/v1/sijuna/teachers',
],
```

### 2. Environment Variables (.env)

```env
SIPINTU_API_URL=http://localhost:8000
SIPINTU_CLIENT_ID=app_yk3qeq4twl7z
SIPINTU_CLIENT_SECRET=sec_oTpMf5DU3hOanGDXUHgbddXwJ1L4V9dE
```

### 3. Masalah Utama

`SIPINTU_API_URL=http://localhost:8000` kemungkinan adalah URL SIPBAR sendiri, bukan URL SiPintu Gateway yang sebenarnya.

Saat job sync mencoba mengakses:
- `http://localhost:8000/api/v1/sijuna/students`
- `http://localhost:8000/api/v1/sijuna/teachers`

Ini mengarah ke server SIPBAR, bukan SiPintu Gateway, sehingga menghasilkan error 404.

## Solusi

### Langkah 1: Cek URL SiPintu Gateway yang Benar

Hubungi admin SiPintu atau cek dokumentasi untuk mendapatkan URL SiPintu Gateway yang benar. Biasanya:
- Development: `http://localhost:3000` atau port lain
- Production: `https://sipintu.sekolah.sch.id` atau domain lain

### Langkah 2: Update .env

Buka file `.env` dan update `SIPINTU_API_URL`:

```env
# Ganti dengan URL SiPintu Gateway yang benar
SIPINTU_API_URL=http://localhost:3000  # atau URL yang benar
SIPINTU_CLIENT_ID=app_yk3qeq4twl7z
SIPINTU_CLIENT_SECRET=sec_oTpMf5DU3hOanGDXUHgbddXwJ1L4V9dE
SIPINTU_REDIRECT_URI=http://localhost:8001/oauth/callback
```

### Langkah 3: Clear Cache

Setelah mengubah `.env`, jalankan:

```bash
php artisan config:clear
php artisan cache:clear
```

### Langkah 4: Verifikasi Koneksi ke SiPintu Gateway

Test koneksi ke SiPintu Gateway dengan curl (ganti URL dengan yang benar):

```powershell
# Test ping
curl.exe -s "http://localhost:3000/api/v1/ping?client_id=app_yk3qeq4twl7z" -H "Accept: application/json"

# Test validate client
curl.exe -s -X POST "http://localhost:3000/api/v1/validate-client" `
  -H "Content-Type: application/json" `
  -H "Accept: application/json" `
  -d '{"client_id": "app_yk3qeq4twl7z", "client_secret": "sec_oTpMf5DU3hOanGDXUHgbddXwJ1L4V9dE"}'

# Test get students
curl.exe -s "http://localhost:3000/api/v1/sijuna/students" `
  -H "X-Client-ID: app_yk3qeq4twl7z" `
  -H "X-Client-Secret: sec_oTpMf5DU3hOanGDXUHgbddXwJ1L4V9dE" `
  -H "Accept: application/json"

# Test get teachers
curl.exe -s "http://localhost:3000/api/v1/sijuna/teachers" `
  -H "X-Client-ID: app_yk3qeq4twl7z" `
  -H "X-Client-Secret: sec_oTpMf5DU3hOanGDXUHgbddXwJ1L4V9dE" `
  -H "Accept: application/json"
```

**Expected Response:**
```json
{
  "success": true,
  "data": [...],
  "total": 100
}
```

### Langkah 5: Pastikan Queue Worker Berjalan

Jika menggunakan queue untuk sync:

```bash
# Development
php artisan queue:work

# Production (dengan Supervisor)
sudo supervisorctl start sipbar-worker:*
```

### Langkah 6: Jalankan Sync Ulang

Setelah konfigurasi benar:

1. Buka halaman "Kelola Pengguna"
2. Klik "Sinkronkan dari SiPintu"
3. Tunggu proses selesai

Atau jalankan via command line:

```bash
php artisan sipintu:sync-users --force
```

## Verifikasi

### Cek Log Laravel

```bash
Get-Content storage/logs/laravel.log -Tail 50
```

**Expected Output (Success):**
```
[2026-08-31 10:00:00] local.INFO: SiPintu sync started {"force_refresh":true,"batch_size":100}
[2026-08-31 10:00:05] local.INFO: SiPintu sync job completed {"duration":5,"stats":{"students":{"fetched":850,"created":800,"updated":50,"skipped":0,"errors":0},"teachers":{"fetched":150,"created":120,"updated":30,"skipped":0,"errors":0}}}
```

### Cek Database

```bash
php artisan tinker
```

```php
// Cek user dari SiPintu
User::where('data_source', 'sipintu')->count();

// Cek user siswa
User::where('data_source', 'sipintu')->whereHas('roles', fn($q) => $q->where('name', 'Siswa'))->count();

// Cek user guru
User::where('data_source', 'sipintu')->whereHas('roles', fn($q) => $q->where('name', 'Guru'))->count();

// Cek password (hash)
$user = User::where('nis', '1234567890')->first();
$user->password; // Harus berupa hash, bukan plain text
```

## Password Generation

Password di-generate otomatis oleh job sync:

**Siswa:**
- Format: `siswa{NIS}`
- Contoh: `siswa2024001`
- Disimpan sebagai hash di database

**Guru:**
- Format: `guru{NIP}`
- Contoh: `guru198501012010011001`
- Disimpan sebagai hash di database

**Email:**
- Siswa: `{NIS}@sipbar.sch.id`
- Guru: `{NIP}@sipbar.sch.id`

## Checklist Sebelum Sync

- [ ] `SIPINTU_API_URL` di `.env` sudah benar (bukan URL SIPBAR sendiri)
- [ ] SiPintu Gateway server berjalan dan accessible
- [ ] `SIPINTU_CLIENT_ID` dan `SIPINTU_CLIENT_SECRET` valid
- [ ] Endpoint `/api/v1/sijuna/students` dan `/api/v1/sijuna/teachers` ada di SiPintu Gateway
- [ ] Queue worker berjalan (jika menggunakan queue)
- [ ] Cache sudah di-clear setelah mengubah `.env`

## Common Issues

### Issue 1: Connection Timeout

**Error:** `Tidak dapat terhubung ke SiPintu — server mungkin offline.`

**Solusi:**
- Pastikan SiPintu Gateway server berjalan
- Cek firewall/network
- Verifikasi URL SiPintu benar

### Issue 2: 401 Unauthorized

**Error:** `HTTP 401: Unauthorized`

**Solusi:**
- Verifikasi `SIPINTU_CLIENT_ID` dan `SIPINTU_CLIENT_SECRET` di `.env`
- Pastikan client masih aktif di SiPintu Gateway
- Re-register client jika perlu

### Issue 3: 404 Not Found

**Error:** `HTTP 404: The route api/v1/sijuna/students could not be found.`

**Solusi:**
- Pastikan endpoint benar di SiPintu Gateway
- Cek dokumentasi SiPintu untuk endpoint terbaru
- Update `config/sipintu.php` jika endpoint berubah

### Issue 4: Job Tidak Diproses

**Error:** Sync dispatched tapi tidak ada proses

**Solusi:**
- Pastikan queue worker berjalan: `php artisan queue:work`
- Cek `QUEUE_CONNECTION` di `.env` (harus `database` atau `redis`, bukan `sync`)
- Cek tabel `jobs` di database

## Support

Jika masalah masih berlanjut:
1. Cek log lengkap: `storage/logs/laravel.log`
2. Test endpoint SiPintu dengan curl manual
3. Hubungi admin SiPintu untuk verifikasi endpoint dan credentials
