# Konfigurasi Queue untuk Sinkronisasi SiPintu

## Masalah yang Diperbaiki

Error `FatalError: Maximum execution time of 30 seconds exceeded` terjadi karena proses sync berjalan synchronous di dalam request Livewire. Solusi: pindahkan proses sync ke Queue Job agar berjalan di background.

## 1. Konfigurasi .env

Pastikan konfigurasi queue di `.env` sudah benar:

```env
# Queue Connection (PENTING: jangan gunakan 'sync' untuk production)
QUEUE_CONNECTION=database
# Atau gunakan redis jika tersedia:
# QUEUE_CONNECTION=redis

# Redis Configuration (jika menggunakan redis)
# REDIS_HOST=127.0.0.1
# REDIS_PASSWORD=null
# REDIS_PORT=6379
```

**JANGAN gunakan `QUEUE_CONNECTION=sync`** karena ini akan menjalankan job secara synchronous (sama seperti sebelumnya) dan tidak akan menyelesaikan masalah timeout.

## 2. Setup Queue Table (untuk database queue)

Jika menggunakan `QUEUE_CONNECTION=database`, jalankan migration untuk membuat tabel jobs:

```bash
php artisan queue:table
php artisan migrate
```

Ini akan membuat tabel:
- `jobs` - menyimpan job yang antri
- `failed_jobs` - menyimpan job yang gagal
- `job_batches` - menyimpan batch job

## 3. Menjalankan Queue Worker

### Development

Buka terminal baru dan jalankan:

```bash
php artisan queue:work
```

Worker ini akan terus berjalan dan memproses job yang antri. Jangan tutup terminal ini.

### Production (Linux)

Gunakan Supervisor untuk menjalankan queue worker secara terus-menerus:

**Install Supervisor:**
```bash
sudo apt-get install supervisor
```

**Buat config file:**
```bash
sudo nano /etc/supervisor/conf.d/sipbar-worker.conf
```

**Isi config:**
```ini
[program:sipbar-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/worker.log
```

**Restart Supervisor:**
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start sipbar-worker:*
```

### Production (Windows)

Gunakan Task Scheduler atau jalankan sebagai service dengan NSSM (Non-Sucking Service Manager):

**Install NSSM:**
```bash
nssm install SIPBARWorker "C:\path\to\php.exe" "C:\path\to\project\artisan" queue:work
```

## 4. Verifikasi Queue Berjalan

### Cek Status Job

```bash
# Lihat job yang antri
php artisan queue:monitor

# Lihat job yang gagal
php artisan queue:failed

# Retry semua job yang gagal
php artisan queue:retry all
```

### Cek Log

```bash
# Lihat log worker
tail -f storage/logs/worker.log

# Lihat log Laravel
tail -f storage/logs/laravel.log | grep "SiPintu sync"
```

## 5. Testing Queue Job

### Manual Test via Tinker

```bash
php artisan tinker
```

```php
// Dispatch job manual
App\Jobs\SyncSipintuUsersJob::dispatch(forceRefresh: true, batchSize: 100,chunkSize: 200);

// Cek status cache
Cache::get('sipintu_sync_status');
```

### Test via UI

1. Buka halaman "Kelola Pengguna"
2. Klik tombol "Sinkronkan dari SiPintu"
3. Status akan muncul: "Sinkronisasi dimulai di background..."
4. Status akan update otomatis setiap 3 detik via polling
5. Setelah selesai, data akan otomatis refresh

## 6. Troubleshooting

### Job tidak diproses

**Masalah:** Job dispatched tapi tidak diproses.

**Solusi:**
- Pastikan queue worker berjalan (`php artisan queue:work`)
- Cek `QUEUE_CONNECTION` di `.env` (harus `database` atau `redis`, bukan `sync`)
- Cek tabel `jobs` di database apakah job tercatat

### Worker berhenti

**Masalah:** Worker berhenti setelah beberapa waktu.

**Solusi:**
- Gunakan Supervisor (Linux) atau NSSM (Windows) untuk auto-restart
- Atau gunakan `--daemon` flag: `php artisan queue:work --daemon`

### Memory error

**Masalah:** Job consume terlalu banyak memory.

**Solusi:**
- Kurangi `chunkSize` di job (default 200, coba 100)
- Atur memory limit di PHP: `memory_limit=256M` di php.ini

### Timeout masih terjadi

**Masalah:** Job masih timeout meskipun sudah di-queue.

**Solusi:**
- Cek `max_execution_time` di php.ini (set ke 300 atau lebih)
- Job sudah memiliki `$timeout = 300` (5 menit)
- Kurangi `chunkSize` untuk mempercepat proses

## 7. Konfigurasi Tambahan

### Job Timeout

Di `SyncSipintuUsersJob.php`:
```php
public $timeout = 300; // 5 menit
```

### Retry Policy

Di `SyncSipintuUsersJob.php`:
```php
public $tries = 3; // Coba 3 kali
public $backoff = [60, 120, 300]; // Delay: 1min, 2min, 5min
```

### Chunk Size

Parameter saat dispatch job:
```php
SyncSipintuUsersJob::dispatch(
    forceRefresh: true,
    batchSize: 100,    // Database batch size
    chunkSize: 200     // Memory chunk size
);
```

## 8. Monitoring

### Cek Status Sync via Cache

```php
$status = Cache::get('sipintu_sync_status');
// Returns: ['status' => 'running|completed|failed', 'message' => '...', 'stats' => [...]]
```

### Dashboard Monitoring

Untuk production, pertimbangkan menggunakan:
- Laravel Horizon (jika menggunakan Redis)
- Laravel Telescope untuk debugging queue
- Custom dashboard untuk monitoring status sync

## 9. Cleanup

### Hapus Job Lama

```bash
# Hapus job yang sudah completed
php artisan queue:flush

# Hapus job failed yang sudah lama
php artisan queue:flush --failed
```

### Reset Status Cache

```bash
php artisan tinker
>>> Cache::forget('sipintu_sync_status');
```

## 10. Best Practices

1. **Selalu gunakan queue untuk proses berat** - Jangan jalankan proses lama di request-response cycle
2. **Monitor queue worker** - Pastikan worker selalu berjalan di production
3. **Set retry policy** - Gunakan retry untuk job yang bisa gagal sementara (network error, dll)
4. **Log error** - Semua error di-log untuk troubleshooting
5. **Test di development** - Pastikan queue berjalan dengan baik sebelum deploy ke production
6. **Gunakan chunking** - Pecah data besar menjadi chunk kecil untuk menghindari memory issues
