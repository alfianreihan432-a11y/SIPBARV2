# 🔧 TROUBLESHOOTING GUIDE - SIPBAR V2

Panduan mengatasi masalah umum yang mungkin terjadi.

---

## ❌ Error yang Baru Saja Diperbaiki

### Error: `Call to undefined method WhatsAppNotificationService::sendBorrowingRequestNotification()`

**Penyebab:**
- File lama (`BorrowingForm.php`) memanggil method yang tidak ada
- Method yang benar adalah `notifyNewRequest()`

**Sudah Diperbaiki:**
✅ File `app/Livewire/BorrowingForm.php` sudah diupdate
✅ Sekarang menggunakan method yang benar
✅ Ada error handling untuk WhatsApp notification

**Cara Test:**
```bash
# Refresh browser (Ctrl + F5)
# Coba submit form peminjaman lagi
```

---

## 🐛 Error Umum & Solusinya

### 1. Class 'WhatsAppNotificationService' not found

**Solusi:**
```bash
# Clear compiled files
php artisan clear-compiled
php artisan optimize:clear

# Regenerate autoload
composer dump-autoload
```

### 2. QR Code tidak muncul / 404

**Solusi:**
```bash
# Buat storage link
php artisan storage:link

# Cek folder exists
mkdir -p storage/app/public/qr-codes
chmod -R 775 storage/app/public
```

### 3. Migration error: Table already exists

**Solusi:**
```bash
# Cek migration status
php artisan migrate:status

# Jika ada yang pending
php artisan migrate

# Jika ada yang conflict
php artisan migrate:rollback --step=1
php artisan migrate
```

### 4. Seeder error: Class not found

**Solusi:**
```bash
# Regenerate autoload
composer dump-autoload

# Run seeder lagi
php artisan db:seed --class=QREnhancementDemoSeeder
```

### 5. Scanner tidak bisa akses kamera

**Penyebab:**
- Browser belum diberi izin
- Tidak menggunakan HTTPS (production)
- Tidak localhost (development)

**Solusi:**
```bash
# Development - pastikan pakai localhost atau 127.0.0.1
php artisan serve

# Bukan pakai IP langsung (tidak akan jalan)

# Production - pastikan pakai HTTPS
```

### 6. WhatsApp notification failed

**Ini Normal** jika bot belum dikonfigurasi:
- Sistem tetap jalan
- Notifikasi dicatat sebagai failed di log
- Tidak mempengaruhi workflow

**Jika mau configure:**
```env
# Edit .env
WA_BOT_URL=http://your-bot-url
WA_BOT_API_KEY=your-api-key
```

### 7. Scheduler tidak jalan

**Cek manually:**
```bash
# Test command
php artisan borrowing:send-reminders

# Cek schedule list
php artisan schedule:list

# Test schedule:run
php artisan schedule:run
```

**Production:**
```bash
# Setup cron
crontab -e

# Tambahkan:
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### 8. Session error / CSRF token mismatch

**Solusi:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Regenerate key
php artisan key:generate

# Restart server
# Ctrl+C, lalu php artisan serve
```

### 9. Database connection refused

**Cek .env:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipbarv2
DB_USERNAME=root
DB_PASSWORD=
```

**Pastikan MySQL jalan:**
```bash
# Windows (XAMPP)
# Start MySQL di XAMPP Control Panel

# Linux
sudo service mysql start

# Check
mysql -u root -p
```

### 10. Livewire component not updating

**Solusi:**
```bash
# Clear Livewire cache
php artisan livewire:discover

# Clear all cache
php artisan optimize:clear

# Refresh browser dengan hard reload
# Ctrl + Shift + R (atau Ctrl + F5)
```

---

## 🔍 Debugging Tips

### Cek Log Laravel

```bash
# Tail log real-time
tail -f storage/logs/laravel.log

# Atau buka file:
# storage/logs/laravel-2026-08-12.log
```

### Enable Debug Mode (Development Only)

```env
# .env
APP_DEBUG=true
LOG_LEVEL=debug
```

**JANGAN** pakai debug=true di production!

### Test Database Connection

```bash
php artisan tinker
>>> DB::connection()->getPdo();
>>> // Should show PDO object

>>> \App\Models\User::count();
>>> // Should show number of users
```

### Test WhatsApp Service

```bash
php artisan tinker
>>> $service = app(\App\Services\WhatsAppNotificationService::class);
>>> $service->checkBotStatus();
>>> // Shows online: false if not configured (normal)
```

### Test QR Code Generation

```bash
php artisan tinker
>>> $request = \App\Models\BorrowingRequest::first();
>>> $qr = app(\App\Services\QRCodeService::class);
>>> $qr->generateForRequest($request);
>>> // Check storage/app/public/qr-codes/
```

---

## 📱 Browser Issues

### Scanner tidak detect QR

**Checklist:**
- ✅ Pakai Chrome (recommended)
- ✅ Camera permission granted
- ✅ QR Code jelas (tidak blur)
- ✅ Lighting cukup
- ✅ QR Code tidak terlalu kecil

**Test:**
```
1. Buka: /guru/qr/scan
2. F12 (Developer Tools)
3. Console tab
4. Cari error merah
```

### Styling rusak / CSS tidak load

```bash
# Rebuild assets
npm run dev

# Atau production
npm run build

# Clear browser cache
# Ctrl + Shift + Delete
```

---

## 🗄️ Database Issues

### Reset Database (Hati-hati!)

```bash
# Hapus semua data dan recreate
php artisan migrate:fresh

# Dengan seeder
php artisan migrate:fresh --seed

# Atau dengan demo data
php artisan migrate:fresh
php artisan db:seed --class=QREnhancementDemoSeeder
```

### Backup Database

```bash
# Export
mysqldump -u root -p sipbarv2 > backup.sql

# Import
mysql -u root -p sipbarv2 < backup.sql
```

---

## ⚡ Performance Issues

### App lambat

```bash
# Cache everything (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Development - clear cache
php artisan optimize:clear
```

### Database query lambat

**Cek query log:**
```env
# .env
LOG_QUERY=true
DB_SLOW_QUERY_TIME=100
```

**Add indexes** (sudah ada di migrations):
- borrowing_requests.status
- borrowing_requests.qr_token
- borrowing_requests.return_date

---

## 🆘 Last Resort

Jika semua gagal:

```bash
# 1. Stop server (Ctrl+C)

# 2. Clear everything
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload

# 3. Rebuild
composer install
npm install
npm run dev

# 4. Restart
php artisan serve
```

---

## 📞 Dapatkan Bantuan

### Error Log Location

```
storage/logs/laravel.log
storage/logs/laravel-YYYY-MM-DD.log
```

### System Info

```bash
# PHP version
php -v

# Laravel version
php artisan --version

# Check routes
php artisan route:list

# Check config
php artisan config:show

# Check migrations
php artisan migrate:status
```

### Report Bug

Jika menemukan bug, sertakan:
1. Error message lengkap
2. Steps to reproduce
3. Laravel log (dari storage/logs/)
4. Browser & OS version
5. Screenshot (jika UI issue)

---

## ✅ Checklist Sebelum Deploy

- [ ] Environment production di .env
- [ ] APP_DEBUG=false
- [ ] Database credentials correct
- [ ] php artisan migrate (di production)
- [ ] php artisan storage:link
- [ ] npm run build
- [ ] php artisan config:cache
- [ ] php artisan route:cache
- [ ] php artisan view:cache
- [ ] Cron job untuk scheduler
- [ ] SSL certificate installed
- [ ] Permissions correct (775 storage/)
- [ ] Backup database
- [ ] Test all features

---

**Version:** 2.1.0  
**Last Updated:** August 12, 2026

**Butuh bantuan lebih lanjut?**
- Cek `README.md` untuk dokumentasi lengkap
- Cek `PANDUAN_PENGGUNAAN.md` untuk cara pakai
- Cek `DEPLOYMENT.md` untuk deploy production
