# Dokumentasi Sinkronisasi Data SiPintu ke SIPBAR

## Ringkasan

Fitur sinkronisasi data Siswa dan Guru dari SiPintu Identity & API Gateway ke database lokal SIPBAR. Fitur ini memungkinkan import ~1000 pengguna dari SiPintu secara otomatis dengan mekanisme upsert (create/update) berdasarkan NIS/NIP.

## Struktur Database

### Tabel `users`

Kolom yang ditambahkan untuk tracking sync:

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| `sipintu_synced_at` | timestamp | Waktu terakhir data disinkronkan dari SiPintu |
| `data_source` | string | Sumber data: 'manual' atau 'sipintu' |

### Mapping Field SiPintu ke Tabel Users

#### Siswa
| Field SiPintu | Field SIPBAR | Catatan |
|---------------|--------------|---------|
| `nama` / `name` | `name` | Nama lengkap |
| `nis` | `nis` | Nomor Induk Siswa (unique identifier) |
| `kelas` / `rombel` | `kelas` | Kelas siswa |
| `jurusan` | `jurusan` | Jurusan |
| `alamat` | `alamat` | Alamat |
| `tanggal_lahir` / `birth_date` | `tanggal_lahir` | Tanggal lahir |
| - | `email` | Auto-generate: `{nis}@sipbar.sch.id` |
| - | `password` | Auto-generate: `siswa{nis}` (hashed) |
| - | `data_source` | Set ke 'sipintu' |
| - | `sipintu_synced_at` | Timestamp saat sync |

#### Guru
| Field SiPintu | Field SIPBAR | Catatan |
|---------------|--------------|---------|
| `nama` / `name` | `name` | Nama lengkap |
| `nip` | `nip` | Nomor Induk Pegawai (unique identifier) |
| `jabatan` / `position` | `jabatan` | Jabatan |
| `alamat` | `alamat` | Alamat |
| `tanggal_lahir` / `birth_date` | `tanggal_lahir` | Tanggal lahir |
| `no_hp` / `phone` | `phone` | Nomor HP |
| - | `email` | Auto-generate: `{nip}@sipbar.sch.id` |
| - | `password` | Auto-generate: `guru{nip}` (hashed) |
| - | `data_source` | Set ke 'sipintu' |
| - | `sipintu_synced_at` | Timestamp saat sync |

## Komponen Sistem

### 1. Artisan Command: `SipintuSyncUsers`

**File:** `app/Console/Commands/SipintuSyncUsers.php`

**Signature:**
```bash
php artisan sipintu:sync-users [--force] [--batch=100]
```

**Options:**
- `--force`: Force refresh data dari SiPintu (skip cache)
- `--batch=100`: Jumlah record per batch (default: 100)

**Fitur:**
- Progress bar untuk tracking proses
- Log ke Laravel Log (`storage/logs/laravel.log`)
- Statistik: fetched, created, updated, skipped, errors
- Batch processing untuk performance
- Error handling per-item (satu error tidak menghentikan seluruh proses)
- Idempotent (dijalankan berkali-kali tidak membuat duplikat)

**Contoh Output:**
```
=== Sinkronisasi Data dari SiPintu Gateway ===

📚 Mengambil data Siswa...
   ✓ 850 data siswa ditemukan
   Memproses data siswa...
████████████████████████████████████████ 100%

👨‍🏫 Mengambil data Guru...
   ✓ 150 data guru ditemukan
   Memproses data guru...
████████████████████████████████████████ 100%

=== Ringkasan Sinkronisasi ===
Durasi: 15 detik

📚 Siswa:
   Difetch: 850
   Dibuat:  800
   Diupdate: 50
   Dilewati: 0
   Error:    0

👨‍🏫 Guru:
   Difetch: 150
   Dibuat:  120
   Diupdate: 30
   Dilewati: 0
   Error:    0

✓ Sinkronisasi selesai: 920 user baru, 80 diperbarui, 0 dilewati.
```

### 2. Controller Endpoint: `SipintuStatusController::sync()`

**File:** `app/Http/Controllers/SipintuStatusController.php`

**Route:** `POST /api/internal/sipintu/sync`

**Request Body:**
```json
{
  "force_refresh": true,
  "batch_size": 100
}
```

**Response:**
```json
{
  "success": true,
  "message": "Sinkronisasi selesai",
  "output": "=== Sinkronisasi Data dari SiPintu Gateway ===\n..."
}
```

### 3. Livewire Component: `UserManager`

**File:** `app/Livewire/UserManager.php`

**Method:** `syncFromSipintu()`

**Trigger:** Tombol "Sinkronkan dari SiPintu" di halaman Kelola Pengguna

**Behavior:**
- Mengirim request POST ke `/api/internal/sipintu/sync`
- Refresh data users setelah sync selesai
- Tampilkan notifikasi success/error

### 4. UI: Halaman Kelola Pengguna

**File:** `resources/views/livewire/user-manager.blade.php`

**Tombol Sync:**
- Lokasi: Header tabel "Daftar Pengguna"
- Style: Tombol biru gradient dengan icon sync
- Loading state: "Sedang Sync..." saat proses berjalan

## Penjadwalan Otomatis

**File:** `routes/console.php`

**Schedule:**
```php
Schedule::command('sipintu:sync-users')
    ->dailyAt('01:00')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground();
```

**Konfigurasi:**
- **Waktu:** Setiap hari jam 01:00 dini hari
- **Timezone:** Asia/Jakarta
- **Without Overlapping:** Mencegah multiple instance berjalan bersamaan
- **On One Server:** Untuk environment multi-server
- **Run In Background:** Tidak blocking scheduler lain

## Error Handling Strategy

### 3 Layer Error Handling

**Layer 1: Connection Exception**
- Menangani koneksi timeout atau server SiPintu offline
- Pesan: "Tidak dapat terhubung ke SiPintu — server mungkin offline."
- Log ke Laravel Log

**Layer 2: HTTP Response Validation**
- Menangani response non-200 dari SiPintu (404, 401, 500, dll)
- Return status code dan pesan error dari SiPintu
- Log error detail

**Layer 3: Per-Item Exception**
- Try-catch di dalam loop untuk setiap record
- Satu record gagal tidak menghentikan seluruh proses
- Kumpulkan semua error untuk dilaporkan di akhir

### Logging

Semua aktivitas sync di-log ke `storage/logs/laravel.log`:

```log
[2026-08-31 10:00:00] local.INFO: SiPintu sync started {"force_refresh":true,"batch_size":100}
[2026-08-31 10:00:05] local.ERROR: SiPintu sync student error {"data":{"nis":"123"},"error":"..."}
[2026-08-31 10:00:15] local.INFO: SiPintu sync completed {"duration":15,"stats":{...}}
```

## Caching Strategy

**Cache Duration:** 10 menit untuk data siswa dan guru

**Cache Key:**
- `sipintu:students:{md5(params)}`
- `sipintu:teachers:{md5(params)}`

**Force Refresh:**
- Gunakan `--force` flag di command
- Atau `force_refresh=true` di API request

## Cara Penggunaan

### 1. Manual via Command Line

```bash
# Sinkronisasi normal (dengan cache)
php artisan sipintu:sync-users

# Force refresh (skip cache)
php artisan sipintu:sync-users --force

# Custom batch size
php artisan sipintu:sync-users --batch=200

# Force refresh dengan custom batch
php artisan sipintu:sync-users --force --batch=50
```

### 2. Manual via UI

1. Login sebagai Admin
2. Buka menu "Kelola Pengguna"
3. Klik tombol "Sinkronkan dari SiPintu" di header tabel
4. Tunggu proses selesai (loading state akan muncul)
5. Data akan otomatis refresh setelah sync selesai

### 3. Otomatis via Scheduler

Sync akan berjalan otomatis setiap hari jam 01:00 dini hari. Pastikan:
- Laravel scheduler cron job sudah terkonfigurasi di server
- Server timezone sudah diset ke Asia/Jakarta
- SiPintu Gateway accessible pada jam tersebut

## Monitoring & Troubleshooting

### Cek Status Sync Terakhir

Query database untuk cek user yang disinkronkan:

```sql
-- Cek user dari SiPintu
SELECT COUNT(*) FROM users WHERE data_source = 'sipintu';

-- Cek waktu sync terakhir
SELECT MAX(sipintu_synced_at) FROM users WHERE data_source = 'sipintu';

-- Cek user manual vs SiPintu
SELECT data_source, COUNT(*) FROM users GROUP BY data_source;
```

### Cek Log

```bash
# Lihat log terbaru
tail -f storage/logs/laravel.log | grep "SiPintu sync"

# Filter error sync
tail -f storage/logs/laravel.log | grep "SiPintu sync.*ERROR"
```

### Troubleshooting Common Issues

**Issue: Sync gagal dengan error "Tidak dapat terhubung ke SiPintu"**
- Cek koneksi ke SiPintu Gateway
- Verifikasi `SIPINTU_API_URL` di `.env`
- Pastikan SiPintu Gateway online

**Issue: Error 404 pada endpoint SiPintu**
- Verifikasi endpoint SiPintu sudah benar
- Cek dokumentasi SiPintu untuk endpoint terbaru
- Update `config/sipintu.php` jika endpoint berubah

**Issue: Credential invalid**
- Verifikasi `SIPINTU_CLIENT_ID` dan `SIPINTU_CLIENT_SECRET` di `.env`
- Pastikan client masih aktif di SiPintu Gateway
- Re-register client jika perlu

**Issue: Duplikat user**
- Sync menggunakan upsert berdasarkan NIS/NIP, seharusnya tidak ada duplikat
- Cek jika ada user manual dengan NIS/NIP yang sama
- Hapus duplikat manual sebelum sync

## Keamanan

- **Server-side only:** Semua pemanggilan ke SiPintu terjadi di backend
- **Credentials dari .env:** Client ID dan Secret tidak di-hardcode
- **Middleware Auth:** Endpoint sync dilindungi oleh middleware auth
- **No password reset:** Update user existing tidak mereset password
- **Role assignment:** Role (Siswa/Guru) otomatis assigned saat sync

## Performance Considerations

- **Batch processing:** Default 100 record per batch untuk mengurangi load database
- **Caching:** 10 menit cache untuk mengurangi call ke SiPintu
- **Background scheduler:** Sync otomatis berjalan di background
- **Without Overlapping:** Mencegah multiple sync berjalan bersamaan

## Future Enhancements

- [ ] Tambahkan table `sync_logs` untuk audit trail terpisah
- [ ] Queue-based sync untuk data sangat besar (>10,000)
- [ ] Real-time progress tracking untuk UI
- [ ] Differential sync (hanya sync data yang berubah)
- [ ] Conflict resolution untuk data manual vs SiPintu
