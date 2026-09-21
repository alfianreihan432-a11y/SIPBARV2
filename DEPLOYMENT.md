# Deployment Guide

## IMPORTANT: Production Configuration

**SEBELUM DEPLOY KE PRODUCTION, WAJIB set nilai berikut di file `.env` server production:**

- `APP_ENV=production` - Set environment ke production
- `APP_DEBUG=false` - Matikan debug mode (JANGAN pernah set `APP_DEBUG=true` di production)
- `LOG_LEVEL=error` - Set log level ke error untuk mengurangi log size
- `MAIL_FROM_ADDRESS` - Set ke email resmi sekolah yang valid (jangan gunakan placeholder `hello@example.com`)

**JANGAN pernah deploy dengan `APP_DEBUG=true` di production karena:**
- Menampilkan detail error lengkap ke user jika terjadi error
- Mengekspos sensitive information seperti database credentials, file paths, dll
- Security risk yang serius

## Development vs Production

Environment development saat ini menggunakan:
- `APP_ENV=local`
- `APP_DEBUG=true`
- `LOG_LEVEL=debug`

Konfigurasi ini aman untuk development karena menyediakan detail error untuk debugging, tapi tidak aman untuk production.

Pastikan `.env.example` sudah mencerminkan nilai AMAN sebagai template untuk deploy production.

## Checklist Go-Live

Sebelum deploy ke production, pastikan semua item berikut sudah dicek:

- [ ] `APP_ENV=production` di `.env` server production
- [ ] `APP_DEBUG=false` di `.env` server production
- [ ] `LOG_LEVEL=error` di `.env` server production
- [ ] `MAIL_FROM_ADDRESS` diganti dengan email resmi sekolah di `.env` server production
- [ ] Nomor telepon sekolah diisi di `resources/views/welcome-new-v2.blade.php` baris 287 (ganti placeholder `[NOMOR TELEPON SEKOLAH - ISI SEBELUM GO-LIVE]`)
- [ ] Review dan update `SIPINTU_API_URL`, `SIPINTU_CLIENT_ID`, `SIPINTU_CLIENT_SECRET` jika ada
- [ ] Review dan update `WA_BOT_URL`, `WA_BOT_API_KEY` jika ada
- [ ] Jalankan migration: `php artisan migrate --force`
- [ ] Build assets: `npm run build`
- [ ] Clear cache: `php artisan cache:clear`, `php artisan config:clear`, `php artisan route:clear`
- [ ] Jalankan test suite: `php artisan test`
