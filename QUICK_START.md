# 🚀 SIPBAR V2 - Quick Start Guide

Panduan cepat untuk mulai menggunakan SIPBAR V2 dengan fitur QR Code.

---

## ⚡ Setup dalam 5 Menit

### 1. Clone & Install

```bash
# Clone repository
git clone <repository-url>
cd SIPBARV2

# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env
php artisan key:generate
```

### 2. Database Setup

```bash
# Edit .env - Configure database
DB_DATABASE=sipbarv2
DB_USERNAME=root
DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed demo data (OPTIONAL)
php artisan db:seed --class=QREnhancementDemoSeeder
```

### 3. Storage & Assets

```bash
# Create storage link
php artisan storage:link

# Build assets
npm run dev
```

### 4. Run Application

```bash
# Start development server
php artisan serve

# Open browser: http://localhost:8000
```

---

## 👥 Demo Accounts

Jika Anda menjalankan seeder demo:

### Teacher Account
```
Email: guru.demo@sipbar.test
Password: password
```

### Student Accounts
```
Email: siswa1@sipbar.test - siswa5@sipbar.test
Password: password
```

### Admin Account (if seeded separately)
```
Email: admin@sipbar.test
Password: password
```

---

## 🎯 Testing Workflow

### 1. Test Teacher Approval

```bash
# Login sebagai teacher
1. Go to http://localhost:8000/login
2. Email: guru.demo@sipbar.test
3. Password: password

# Approve request
4. Navigate to "Permohonan" menu
5. Click "Setujui" on pending request
6. Check QR code generated in storage/app/public/qr-codes/
```

### 2. Test QR Scanner

```bash
# Still logged in as teacher
1. Navigate to "Scan QR" menu (/guru/qr/scan)
2. Allow camera permission
3. Scan QR code from approved request
4. Click "Konfirmasi Serah Terima" (Checkout)
5. Status changed to "borrowed"

# Test Checkin
6. Scan the same QR again
7. Select return condition (good/damaged/lost)
8. Add notes (optional)
9. Click "Konfirmasi Pengembalian" (Checkin)
10. Status changed to "returned"
```

### 3. Test Transaction History

```bash
# As admin or teacher
1. Navigate to "Transactions" menu (/transactions)
2. Test filters:
   - Search by student name
   - Search by item name
   - Filter by status
   - Filter by date range
3. Click on a transaction to see detailed timeline
```

### 4. Test Reminder Command

```bash
# In terminal
php artisan borrowing:send-reminders

# Check output for success/failure count
# Verify reminder_sent_at updated in database
```

---

## 🔧 Configuration

### WhatsApp Integration (Optional)

Edit `.env`:

```env
WA_BOT_URL=http://your-whatsapp-bot-url
WA_BOT_API_KEY=your-api-key-here
WA_BOT_TIMEOUT=10
```

**Note:** Sistem tetap berfungsi tanpa WhatsApp bot. Notifikasi hanya akan dicatat sebagai failed.

### Scheduler (Production Only)

Add to crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📱 Mobile Testing

### Test QR Scanner on Mobile

1. Make sure your dev server is accessible from mobile:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

2. Find your local IP:
   ```bash
   # Windows
   ipconfig
   
   # Linux/Mac
   ifconfig
   ```

3. Access from mobile browser:
   ```
   http://YOUR-LOCAL-IP:8000
   ```

4. Login and test QR scanner
5. Allow camera permission when prompted

**Note:** For production, HTTPS is required for camera access.

---

## 🐛 Troubleshooting

### QR Code tidak muncul

```bash
# Check storage link
php artisan storage:link

# Check permissions
chmod -R 775 storage/app/public
```

### Scanner tidak bisa akses kamera

- Use HTTPS (production) or localhost (development)
- Check browser permissions
- Try different browser (Chrome recommended)

### Scheduler tidak jalan

```bash
# Test manually
php artisan schedule:run

# Check crontab (production)
crontab -l

# Check logs
tail -f storage/logs/laravel.log
```

### Database error setelah migrate

```bash
# Rollback and retry
php artisan migrate:rollback
php artisan migrate

# Fresh install (WARNING: deletes all data)
php artisan migrate:fresh --seed
```

---

## 📋 Checklist Testing

Gunakan checklist ini untuk memastikan semua fitur berfungsi:

- [ ] ✅ Login as teacher
- [ ] ✅ View pending requests
- [ ] ✅ Approve request → QR generated
- [ ] ✅ Reject request → reason saved
- [ ] ✅ Open QR scanner
- [ ] ✅ Scan approved QR → checkout works
- [ ] ✅ Scan borrowed QR → checkin works
- [ ] ✅ View transaction history
- [ ] ✅ Test all filters (name, item, status, date)
- [ ] ✅ View transaction detail
- [ ] ✅ Run reminder command manually
- [ ] ✅ Test on mobile device (optional)

---

## 🎓 Learning Resources

### Key Files to Review

**Services (Business Logic):**
- `app/Services/QRCodeService.php`
- `app/Services/BorrowingStateMachine.php`
- `app/Services/BorrowingApprovalService.php`
- `app/Services/WhatsAppNotificationService.php`

**Controllers:**
- `app/Http/Controllers/TeacherApprovalController.php`
- `app/Http/Controllers/TransactionHistoryController.php`

**Livewire Components:**
- `app/Livewire/QRScanner.php`
- `app/Livewire/TeacherApproval.php`

**Views:**
- `resources/views/livewire/qr-scanner.blade.php`
- `resources/views/pages/admin/transactions/index.blade.php`

### Documentation

- **Complete Guide:** `README.md`
- **Deployment:** `DEPLOYMENT.md`
- **Changes:** `CHANGELOG.md`
- **Summary:** `QR_ENHANCEMENT_SUMMARY.md`

---

## 🆘 Need Help?

1. **Check documentation** in README.md
2. **Check logs** in `storage/logs/laravel.log`
3. **Review spec files** in `.kiro/specs/sipbar-qr-enhancement/`
4. **Contact** project team

---

## 🎉 Next Steps

After successful setup:

1. ✅ Customize `.env` for your environment
2. ✅ Create real user accounts
3. ✅ Add real inventory items
4. ✅ Configure WhatsApp integration (if needed)
5. ✅ Setup production server (see DEPLOYMENT.md)
6. ✅ Train your team
7. ✅ Start using!

---

**Version:** 2.1.0  
**Last Updated:** August 12, 2026

Happy coding! 🚀
