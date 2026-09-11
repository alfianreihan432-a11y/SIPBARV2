# KIBB Excel Import Feature

## Overview
Fitur untuk import data barang dari file Excel format KIBB (Kartu Inventaris Barang dan Bahan) ke sistem SIPBAR.

## Prerequisites
- Laravel Excel package (maatwebsite/excel) v4.0.2
- PHP zip extension enabled
- Role: Admin (hanya admin yang bisa akses fitur ini)

## Pemetaan Kolom Excel KIBB → Field Item

| Kolom Excel KIBB | Field Database | Deskripsi |
|-----------------|---------------|-----------|
| Kode Barang | `kode_kibb` | Kode asli dari KIBB (disimpan sebagai referensi, bukan code aplikasi) |
| Jenis Barang/Nama Barang | `name` | Nama barang |
| Reg. | `nomor_reg` | Nomor registrasi (string, pertahankan leading zero) |
| Merk type | `merk`, `tipe` | Split di pemisah " - " pertama: kiri = merk, kanan = tipe |
| Ukuran/CC | `description` | Ditambahkan ke deskripsi sebagai "Ukuran: X" |
| Bahan | `description` | Ditambahkan ke deskripsi sebagai "Bahan: X" |
| Tahun Pembelian | `purchase_year` | Tahun pembelian (integer) |
| Nomor Pabrik/Rangka/Mesin/Polisi/BPKB | `description` | Ditambahkan ke deskripsi jika ada (untuk kendaraan) |
| Asal Usul | `description` | Ditambahkan ke deskripsi sebagai "Asal Usul: X" |
| Harga | `price` | Harga (decimal, format angka Indonesia) |
| Keterangan | `description` | Ditambahkan ke deskripsi, juga digunakan untuk match lokasi |
| - | `category_id` | Auto-match dari nama barang ke Category.name |
| - | `location_id` | Auto-match dari keterangan ke Location.name |
| - | `stock` | Default 1 per baris |
| - | `condition` | Default "Baik" |
| - | `status` | Default "Tersedia" |
| - | `code` | Auto-generate: BRG-XXXXXX (hash-based) |
| - | `inventory_number` | Auto-generate: INV-XXXX (sequential) |
| - | `photo_path` | Kosong (user upload manual setelah import) |

## Format File Excel

### Sheet
- Nama sheet: **"KIBB"** (abaikan sheet lain)
- Header: Baris yang mengandung teks "Kode Barang" (deteksi dinamis)
- Data: Dimulai 1 baris setelah baris index angka (1,2,3,...)

### Kolom Excel
No | Kode Barang | Jenis Barang/Nama Barang | Reg. | Merk type | Ukuran/CC | Bahan | Tahun Pembelian | Nomor Pabrik | Nomor Rangka | Nomor Mesin | Nomor Polisi | Nomor BPKB | Asal Usul | Harga | Keterangan

### Akhir Data
Baris data berakhir saat kolom "Kode Barang" dan "Jenis Barang/Nama Barang" sama-sama kosong.

## Fitur

### 1. Upload File
- Tombol "Import KIBB" di halaman Barang (sebelah tombol Tambah Barang)
- Modal upload file .xlsx/.xls
- Validasi: maksimal 10MB

### 2. Preview Data
- Tampilkan tabel hasil mapping
- Highlight baris yang perlu review (kategori/lokasi tidak match)
- Admin bisa edit kategori/lokasi per baris di preview
- Tampilkan ringkasan: total data, siap import, perlu review, dilewati

### 3. Deteksi Duplikat
- Cek `kode_kibb` yang sudah ada di database
- Skip baris duplikat, tampilkan di ringkasan sebagai "dilewati"

### 4. Konfirmasi Import
- Generate `code` dan `inventory_number` secara otomatis
- Insert data ke database dalam transaction
- Tampilkan ringkasan: jumlah berhasil, dilewati, error

### 5. Validasi
- Baris tanpa `name`/Jenis Barang di-skip dan dicatat sebagai error
- Validasi input dasar sebelum import

## Files yang Dibuat/Diubah

### Migration
- `database/migrations/2026_09_09_103455_add_kibb_fields_to_items_table.php`
  - Tambah kolom `kode_kibb` (string, nullable, unique)
  - Tambah kolom `nomor_reg` (string, nullable)

### Model
- `app/Models/Item.php`
  - Tambah `kode_kibb` dan `nomor_reg` ke fillable array

### Import Class
- `app/Imports/KibbImport.php`
  - Parse Excel KIBB dengan deteksi header dinamis
  - Mapping kolom sesuai spesifikasi
  - Auto-match kategori dan lokasi
  - Deteksi duplikat

### Controller
- `app/Http/Controllers/ItemImportController.php`
  - `upload()`: Proses file Excel
  - `preview()`: Tampilkan halaman preview
  - `confirm()`: Konfirmasi dan simpan ke database
  - `cancel()`: Batalkan import

### Routes
- `routes/web.php`
  - POST `/items/import/upload` - Upload file
  - GET `/items/import/preview` - Preview data
  - POST `/items/import/confirm` - Konfirmasi import
  - GET `/items/import/cancel` - Batalkan import
  - Middleware: `role:admin`

### Views
- `resources/views/items/import-preview.blade.php`
  - Halaman preview dengan tabel data
  - Dropdown edit kategori/lokasi per baris
  - Ringkasan statistik dan error

- `resources/views/livewire/inventory-manager.blade.php`
  - Tambah tombol "Import KIBB"
  - Tambah modal upload file

## Cara Penggunaan

1. Login sebagai Admin
2. Buka halaman Barang (Inventory)
3. Klik tombol "Import KIBB" (hijau)
4. Pilih file Excel format KIBB
5. Klik "Upload & Preview"
6. Review data di halaman preview:
   - Edit kategori/lokasi jika perlu
   - Perhatikan baris yang ditandai "Perlu Review"
7. Klik "Konfirmasi Import"
8. Lihat ringkasan hasil import

## Catatan Penting

- `kode_kibb` disimpan sebagai referensi, bukan sebagai `code`/`inventory_number` aplikasi
- `code` dan `inventory_number` di-generate otomatis menggunakan mekanisme yang sudah ada
- Foto barang tidak diisi otomatis (user upload manual setelah import)
- Password/role tidak terpengaruh (ini fitur import barang, bukan user)
- Semua operasi dalam transaction untuk mencegah data corruption
- Session digunakan untuk menyimpan preview data sementara

## Troubleshooting

### Error: "Header Kode Barang tidak ditemukan"
- Pastikan sheet bernama "KIBB"
- Pastikan ada baris yang mengandung teks "Kode Barang"

### Error: "Gagal memproses file"
- Pastikan file format .xlsx atau .xls
- Pastikan file tidak korup
- Pastikan ukuran file < 10MB

### Kategori/Lokasi tidak match otomatis
- Edit manual di halaman preview
- Pastikan nama kategori/lokasi sudah ada di database
- Gunakan keyword yang relevan di keterangan

### Duplikat kode_kibb
- Baris akan dilewati otomatis
- Cek ringkasan "Dilewati" setelah import
