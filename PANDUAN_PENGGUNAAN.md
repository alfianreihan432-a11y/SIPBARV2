# 📖 PANDUAN PENGGUNAAN SIPBAR V2

Panduan lengkap cara menggunakan fitur-fitur QR Code di SIPBAR V2.

---

## 📋 Daftar Isi

1. [Setup Awal](#-setup-awal)
2. [Untuk Siswa](#-panduan-untuk-siswa)
3. [Untuk Guru](#-panduan-untuk-guru)
4. [Untuk Admin](#-panduan-untuk-admin)
5. [FAQ](#-faq--pertanyaan-umum)

---

## 🚀 Setup Awal

### Langkah 1: Instalasi Pertama Kali

```bash
# 1. Masuk ke folder project
cd c:\Users\Dell\SIPBARV2

# 2. Install dependencies (HANYA SEKALI)
composer install
npm install

# 3. Copy file environment
cp .env.example .env

# 4. Generate key
php artisan key:generate

# 5. Setup database di .env
# Edit file .env, ubah bagian database:
DB_DATABASE=sipbarv2
DB_USERNAME=root
DB_PASSWORD=

# 6. Jalankan migrasi database
php artisan migrate

# 7. Isi data demo (OPSIONAL)
php artisan db:seed --class=QREnhancementDemoSeeder

# 8. Buat link storage
php artisan storage:link

# 9. Build assets
npm run dev
```

### Langkah 2: Jalankan Aplikasi

```bash
# Setiap kali mau pakai, jalankan:
php artisan serve

# Buka browser: http://localhost:8000
```

### Langkah 3: Login Pertama Kali

Jika sudah jalankan seeder demo:

**Akun Guru:**
- Email: `guru.demo@sipbar.test`
- Password: `password`

**Akun Siswa:**
- Email: `siswa1@sipbar.test` (atau siswa2, siswa3, dst sampai siswa5)
- Password: `password`

---

## 👨‍🎓 PANDUAN UNTUK SISWA

### 1. Membuat Permintaan Peminjaman

#### Langkah-langkah:

1. **Login** ke sistem
   - Buka `http://localhost:8000/login`
   - Masukkan email dan password siswa

2. **Buka Menu Peminjaman**
   - Klik menu "Peminjaman" di sidebar
   - Atau langsung ke `/siswa/peminjaman`

3. **Isi Form Peminjaman**
   ```
   ✏️ Pilih Barang: Proyektor LCD
   ✏️ Jumlah: 1
   ✏️ Tanggal Pinjam: 15/08/2026
   ✏️ Tanggal Kembali: 17/08/2026
   ✏️ Keperluan: Presentasi tugas akhir
   ✏️ Catatan: (opsional)
   ```

4. **Klik "Ajukan Peminjaman"**

5. **Tunggu Persetujuan**
   - Status akan menjadi "Menunggu Persetujuan"
   - Guru akan menerima notifikasi WhatsApp

### 2. Menerima QR Code

Setelah guru menyetujui:

1. **Cek Status di Dashboard**
   - Status berubah jadi "Disetujui"
   - QR Code akan muncul di halaman detail

2. **Simpan QR Code**
   - Screenshot QR Code
   - Atau simpan gambar QR Code
   - Anda akan terima QR via WhatsApp (jika dikonfigurasi)

3. **Siap Digunakan!**
   - QR Code ini akan dipakai 2 kali:
     - Saat **ambil barang** (checkout)
     - Saat **kembalikan barang** (checkin)

### 3. Mengambil Barang (Checkout)

1. **Datang ke Ruang Peminjaman**
   - Bawa HP dengan QR Code

2. **Tunjukkan QR ke Guru**
   - Guru akan scan QR Code Anda
   - Sistem akan tampilkan detail peminjaman Anda

3. **Guru Konfirmasi**
   - Guru klik "Konfirmasi Serah Terima"
   - Status berubah jadi "Sedang Dipinjam"
   - Barang diserahkan ke Anda

4. **Dapat Notifikasi**
   - Anda terima konfirmasi via WhatsApp

### 4. Mengembalikan Barang (Checkin)

1. **Datang ke Ruang Peminjaman**
   - Bawa barang + HP dengan QR Code yang sama

2. **Tunjukkan QR ke Guru**
   - Guru scan QR Code Anda lagi
   - Sistem deteksi ini pengembalian

3. **Guru Cek Kondisi Barang**
   - Guru pilih kondisi:
     - ✅ Baik (tidak ada kerusakan)
     - ⚠️ Rusak (ada kerusakan)
     - ❌ Hilang
   - Guru isi catatan (jika perlu)

4. **Guru Konfirmasi**
   - Guru klik "Konfirmasi Pengembalian"
   - Status berubah jadi "Dikembalikan"
   - Peminjaman selesai!

5. **Dapat Notifikasi**
   - Anda terima konfirmasi pengembalian via WhatsApp

### 5. Mendapat Reminder

**H-1 Sebelum Tanggal Kembali:**
- Sistem otomatis kirim reminder via WhatsApp
- Reminder dikirim setiap hari jam 08:00 pagi
- Isi reminder: pengingat untuk kembalikan barang besok

### 6. Melihat Riwayat

1. **Buka Menu Riwayat**
   - Klik "Riwayat" di sidebar

2. **Lihat Semua Peminjaman**
   - Semua transaksi Anda tampil
   - Bisa filter by status
   - Bisa cari by tanggal

---

## 👨‍🏫 PANDUAN UNTUK GURU

### 1. Login dan Dashboard

1. **Login**
   - Email: `guru.demo@sipbar.test`
   - Password: `password`

2. **Dashboard Guru**
   - Tampilan statistik peminjaman
   - Menu cepat untuk berbagai aksi

### 2. Menyetujui/Menolak Permintaan

#### Melihat Permintaan Pending:

1. **Buka Menu "Permohonan"**
   - Klik "Permohonan" di sidebar
   - Atau langsung ke `/guru/permohonan`

2. **Lihat Daftar Permintaan**
   - Semua permintaan yang belum diproses tampil
   - Info yang ditampilkan:
     - Nama siswa & kelas
     - Barang yang diminta
     - Jumlah
     - Stok tersedia (PENTING!)
     - Tanggal pinjam & kembali
     - Keperluan

#### Menyetujui Permintaan:

1. **Cek Stok Barang**
   - Pastikan stok tersedia cukup
   - Stok tersedia ditampilkan di card

2. **Klik "Setujui"**
   - QR Code otomatis dibuat
   - Status berubah jadi "Disetujui"
   - QR Code dikirim ke siswa via WhatsApp

3. **Konfirmasi**
   - Muncul pesan sukses
   - Permintaan hilang dari daftar pending

#### Menolak Permintaan:

1. **Klik "Tolak"**
   - Modal muncul minta alasan

2. **Isi Alasan Penolakan**
   - Minimal 10 karakter
   - Contoh: "Proyektor sedang dipakai untuk ujian semester"

3. **Klik "Tolak Permintaan"**
   - Status berubah jadi "Ditolak"
   - Siswa dapat notifikasi dengan alasan

### 3. Scan QR Code (Checkout & Checkin)

#### Membuka Scanner:

1. **Klik Menu "Scan QR"**
   - Atau langsung ke `/guru/qr/scan`

2. **Izinkan Akses Kamera**
   - Browser akan minta izin kamera
   - Klik "Allow" / "Izinkan"

3. **Scanner Siap**
   - Kotak scanner muncul
   - Arahkan kamera ke QR Code

#### Proses Checkout (Ambil Barang):

1. **Scan QR Siswa**
   - QR Code dengan status "Disetujui"

2. **Detail Muncul**
   - Info siswa
   - Barang yang dipinjam
   - Tanggal pinjam & kembali

3. **Cek Fisik Barang**
   - Pastikan barang sesuai

4. **Klik "Konfirmasi Serah Terima"**
   - Status berubah → "Sedang Dipinjam"
   - Waktu checkout tercatat
   - Notifikasi dikirim ke siswa

5. **Serahkan Barang**
   - Berikan barang ke siswa

#### Proses Checkin (Terima Barang):

1. **Scan QR Siswa**
   - QR Code yang sama (status "Sedang Dipinjam")

2. **Terima Barang Kembali**
   - Cek kondisi barang

3. **Pilih Kondisi Barang**
   - ✅ **Baik** - Tidak ada kerusakan
   - ⚠️ **Rusak** - Ada kerusakan
   - ❌ **Hilang** - Barang hilang

4. **Isi Catatan (Opsional)**
   - Jelaskan detail jika ada masalah
   - Contoh: "Layar proyektor ada goresan kecil"

5. **Klik "Konfirmasi Pengembalian"**
   - Status berubah → "Dikembalikan"
   - Waktu checkin tercatat
   - Kondisi barang tersimpan
   - Notifikasi dikirim ke siswa

#### Scan QR yang Sudah Selesai:

- QR Code dengan status "Dikembalikan" atau "Ditolak"
- Hanya menampilkan detail (read-only)
- Tidak ada aksi yang bisa dilakukan
- Info: "Transaksi sudah selesai"

### 4. Melihat Riwayat Transaksi

1. **Buka Menu "Transactions"**
   - Atau langsung ke `/transactions`

2. **Filter Transaksi**
   - **Nama Siswa**: Ketik nama untuk cari
   - **Nama Barang**: Ketik nama barang
   - **Status**: Pilih status spesifik
   - **Tanggal**: Pilih range tanggal

3. **Klik "Cari"**
   - Hasil filter muncul
   - Klik "Reset" untuk clear filter

4. **Lihat Detail**
   - Klik "Detail →" pada transaksi
   - Timeline lengkap muncul:
     - Kapan diajukan
     - Kapan disetujui/ditolak
     - Kapan checkout
     - Kapan checkin
     - Kondisi barang saat dikembalikan

### 5. Tips untuk Guru

**DO's ✅**
- Selalu cek stok sebelum menyetujui
- Cek fisik barang saat checkout
- Periksa kondisi saat checkin
- Isi catatan detail jika ada masalah
- Pastikan QR Code jelas saat scan

**DON'Ts ❌**
- Jangan setujui jika stok tidak cukup
- Jangan lupa scan saat serah terima
- Jangan lupa cek kondisi barang
- Jangan skip pengecekan fisik

---

## 👨‍💼 PANDUAN UNTUK ADMIN

### 1. Dashboard Admin

**Akses:**
- Login dengan akun admin
- Dashboard menampilkan:
  - Total peminjaman
  - Statistik per status
  - Chart & grafik

### 2. Kelola Data Barang

#### Tambah Barang Baru:

1. **Menu "Kelola Barang"**
2. **Klik "Tambah Barang"**
3. **Isi Data:**
   ```
   Nama Barang: Laptop ASUS
   Kode: LPT-001
   Kategori: Elektronik
   Stok: 10
   Kondisi: Baik
   Lokasi: Ruang Lab
   ```
4. **Simpan**

#### Edit/Update Stok:

1. **Klik "Edit" pada barang**
2. **Update stok**
3. **Simpan**

### 3. Kelola Akun User

#### Tambah Guru:

1. **Menu "Users"**
2. **Klik "Tambah User"**
3. **Pilih Role: Guru**
4. **Isi Data:**
   ```
   Nama: Pak Budi
   Email: budi@sekolah.com
   Password: ********
   No. WhatsApp: 6281234567890
   ```
5. **Simpan**

#### Tambah Siswa:

Sama seperti guru, tapi:
- Role: Siswa
- Isi juga: Kelas, Jurusan, NISN

### 4. Melihat Semua Transaksi

**Admin bisa lihat SEMUA transaksi** (berbeda dengan guru yang hanya lihat miliknya)

1. **Menu "Transactions"**
2. **Gunakan filter:**
   - Semua guru
   - Semua siswa
   - Semua status
   - Range tanggal
3. **Export data** (fitur akan datang)

### 5. Monitoring System

#### Cek Log WhatsApp:

1. **Buka detail transaksi**
2. **Scroll ke bawah**
3. **Lihat "Log Notifikasi WhatsApp"**
4. **Info yang tampil:**
   - Tipe notifikasi
   - Penerima
   - Status (success/failed)
   - Waktu kirim
   - Error message (jika gagal)

#### Monitor Overdue:

- Transaksi yang terlambat dikembalikan
- Filter by status "overdue"
- Follow up dengan guru/siswa

---

## 🔧 SETUP TAMBAHAN

### Konfigurasi WhatsApp Bot (Opsional)

Jika Anda punya WhatsApp bot service:

1. **Edit file `.env`**
   ```env
   WA_BOT_URL=http://your-whatsapp-bot-url
   WA_BOT_API_KEY=your-secret-api-key
   WA_BOT_TIMEOUT=10
   ```

2. **Restart aplikasi**
   ```bash
   # Stop (Ctrl+C)
   # Start lagi
   php artisan serve
   ```

3. **Test koneksi**
   ```bash
   php artisan tinker
   >>> app(App\Services\WhatsAppNotificationService::class)->checkBotStatus();
   ```

**CATATAN:** Sistem tetap jalan tanpa WhatsApp bot. Notifikasi hanya dicatat sebagai failed di log.

### Setup Reminder H-1 (Production)

Untuk production server, setup cron job:

```bash
# Edit crontab
crontab -e

# Tambahkan baris ini:
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

Scheduler akan jalan otomatis setiap hari jam 08:00.

**Test manual:**
```bash
php artisan borrowing:send-reminders
```

---

## 💡 FAQ / Pertanyaan Umum

### Q1: Apa itu QR Code dan kenapa dipakai 2 kali?

**A:** QR Code adalah kode unik untuk setiap transaksi. Dipakai 2 kali karena:
- **Scan 1 (Checkout)**: Saat siswa ambil barang
- **Scan 2 (Checkin)**: Saat siswa kembalikan barang

Sistem otomatis tahu harus checkout atau checkin berdasarkan status transaksi.

### Q2: Bagaimana jika QR Code hilang?

**A:** Siswa bisa:
1. Login ke sistem
2. Buka riwayat peminjaman
3. Lihat detail transaksi
4. Screenshot QR Code lagi

### Q3: Apakah bisa pinjam tanpa QR Code?

**A:** Tidak. QR Code wajib untuk checkout dan checkin. Ini memastikan:
- Tracking yang akurat
- Tidak ada kesalahan manual
- Audit trail lengkap

### Q4: Bagaimana jika kamera tidak berfungsi?

**A:** 
- Pastikan browser punya izin akses kamera
- Coba browser lain (Chrome recommended)
- Untuk production, pastikan pakai HTTPS
- Atau gunakan laptop/PC dengan webcam

### Q5: Bagaimana jika stok tidak cukup?

**A:** Sistem otomatis mencegah approval jika stok habis:
- Guru tidak bisa approve jika stok < jumlah diminta
- Muncul pesan error: "Stok tidak mencukupi"
- Admin harus tambah stok dulu

### Q6: Apa bedanya "Disetujui" dengan "Sedang Dipinjam"?

**A:**
- **Disetujui**: Guru sudah approve, tapi barang belum diambil siswa
- **Sedang Dipinjam**: Barang sudah di tangan siswa (sudah di-checkout)

### Q7: Bagaimana cara batalkan peminjaman?

**A:** Admin/Guru bisa:
1. Buka detail transaksi
2. Ubah status jadi "Ditolak"
3. Isi alasan pembatalan

### Q8: Apakah notifikasi WhatsApp wajib?

**A:** Tidak wajib. Sistem tetap jalan tanpa WhatsApp:
- Notifikasi hanya dicatat sebagai failed
- Tidak mempengaruhi workflow
- Siswa/guru tetap bisa lihat status di sistem

### Q9: Bagaimana cara test fitur tanpa data riil?

**A:** Gunakan demo seeder:
```bash
php artisan db:seed --class=QREnhancementDemoSeeder
```
Ini akan buat:
- 1 akun guru
- 5 akun siswa
- 5 barang
- Transaksi di berbagai status

### Q10: Bagaimana cara reset database?

**A:** Hati-hati, ini hapus SEMUA data:
```bash
php artisan migrate:fresh --seed
```

---

## 🎯 Skenario Penggunaan Lengkap

### Skenario: Siswa Pinjam Proyektor

#### 1. Siswa (Budi):
```
✏️ Login → siswa1@sipbar.test
📝 Menu "Peminjaman" → Klik "Ajukan Baru"
📋 Isi Form:
   - Barang: Proyektor LCD
   - Jumlah: 1
   - Tanggal Pinjam: Besok
   - Tanggal Kembali: 3 hari lagi
   - Keperluan: Presentasi Tugas Akhir
✅ Klik "Ajukan"
⏳ Status: Menunggu Persetujuan
```

#### 2. Guru (Pak Ahmad):
```
📱 Dapat notif WhatsApp: "Ada peminjaman baru dari Budi"
💻 Login → guru.demo@sipbar.test
📋 Menu "Permohonan"
👀 Lihat request Budi:
   - Proyektor LCD (Stok: 3 tersedia)
   - Keperluan jelas
✅ Klik "Setujui"
🎉 QR Code otomatis dibuat
📱 Budi dapat QR via WhatsApp
```

#### 3. Siswa (Budi) - Hari Pinjam:
```
🏃 Datang ke ruang peminjaman
📱 Buka QR Code di HP
👨‍🏫 Tunjukkan ke Pak Ahmad
```

#### 4. Guru (Pak Ahmad) - Checkout:
```
📷 Menu "Scan QR"
📸 Scan QR Budi
✅ Info muncul: Budi, Proyektor, 1 unit
✅ Klik "Konfirmasi Serah Terima"
📦 Serahkan proyektor ke Budi
```

#### 5. Sistem:
```
✉️ Kirim notif ke Budi: "Barang sudah diserahkan"
📊 Update status → "Sedang Dipinjam"
🕐 Catat waktu checkout
```

#### 6. H-1 Sebelum Kembali:
```
⏰ Jam 08:00 pagi
🤖 Scheduler jalan otomatis
📱 Budi dapat reminder: "Besok barang harus dikembalikan"
```

#### 7. Siswa (Budi) - Hari Kembali:
```
🏃 Datang ke ruang peminjaman
📦 Bawa proyektor
📱 Buka QR Code yang sama di HP
👨‍🏫 Tunjukkan ke Pak Ahmad
```

#### 8. Guru (Pak Ahmad) - Checkin:
```
📷 Scan QR Budi lagi
✅ Info muncul: Checkin mode
🔍 Cek kondisi proyektor → Baik
✅ Pilih "Baik"
💬 Catatan: "Tidak ada kerusakan"
✅ Klik "Konfirmasi Pengembalian"
```

#### 9. Sistem:
```
✉️ Kirim notif ke Budi: "Pengembalian berhasil"
📊 Update status → "Dikembalikan"
🕐 Catat waktu checkin + kondisi
✅ Transaksi SELESAI
```

---

## 📞 Butuh Bantuan?

Jika ada masalah:

1. **Cek dokumentasi:**
   - `README.md` - Panduan teknis
   - `QUICK_START.md` - Setup cepat
   - `DEPLOYMENT.md` - Deploy production

2. **Cek log error:**
   ```bash
   # Lihat log Laravel
   tail -f storage/logs/laravel.log
   ```

3. **Test manual:**
   ```bash
   # Test reminder
   php artisan borrowing:send-reminders
   
   # Cek routes
   php artisan route:list
   
   # Cek schedule
   php artisan schedule:list
   ```

---

**🎉 Selamat Menggunakan SIPBAR V2!**

**Version:** 2.1.0  
**Last Updated:** August 12, 2026
