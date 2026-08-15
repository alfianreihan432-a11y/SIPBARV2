# 🎓 Fitur Dashboard Siswa - SIPBAR

## 📱 Halaman-Halaman yang Tersedia

### 1. 🏠 Dashboard Utama
**Route:** `/siswa/dashboard`

**Fitur:**
- Greeting dengan waktu real-time (pagi/siang/malam)
- 4 kartu statistik real-time:
  - Sedang Dipinjam
  - Sudah Dikembalikan  
  - Menunggu Persetujuan
  - Barang Tersedia
- 5 riwayat peminjaman terakhir
- Status badges berwarna
- Auto-refresh setiap 5 detik

**Design:**
- Clean solid colors (no gradients)
- Responsive grid layout
- Professional card-based UI

---

### 2. 📦 Katalog Barang
**Route:** `/siswa/katalog`

**Fitur:**
- Browse semua barang tersedia
- Filter dan pencarian
- Detail barang dengan gambar
- Form peminjaman langsung
- Real-time stock availability

**Component:** Livewire ItemCatalog

---

### 3. 📋 Peminjaman Saya
**Route:** `/siswa/loans`

**Fitur:**
- **Summary Cards:**
  - Menunggu (pending)
  - Sedang Dipinjam (approved + borrowed)
  - Selesai (returned)

- **Peminjaman Aktif:**
  - Detail lengkap setiap item
  - QR Code display untuk checkout/checkin
  - Instruksi penggunaan QR
  - Status visual yang jelas

- **Riwayat Terakhir:**
  - 5 transaksi terbaru yang selesai
  - Link ke halaman riwayat lengkap

**Design Highlights:**
- QR Code dengan base64 image
- Color-coded status
- Action-based instructions
- Empty state yang friendly

---

### 4. 📜 Riwayat Peminjaman
**Route:** `/siswa/riwayat`

**Fitur Utama:**
- **Advanced Filtering:**
  - 🔍 Pencarian by nama barang
  - 📊 Filter by status (5 status)
  - 📅 Range tanggal (dari - sampai)
  - 🔄 Reset filter

- **Detailed History Cards:**
  - Icon barang yang menarik
  - Info lengkap: jumlah, keperluan, tanggal
  - Guru pembimbing
  - Status badge berwarna
  - Timeline untuk completed items
  - Alasan penolakan (jika ditolak)
  - Kondisi pengembalian (jika returned)

- **Pagination:** 10 items per page

- **Empty States:**
  - Berbeda untuk filter kosong vs no data
  - Call-to-action yang jelas

**Use Cases:**
- Tracking semua peminjaman
- Audit personal history
- Melihat alasan rejection
- Monitoring return conditions

---

### 5. 📢 Pengumuman
**Route:** `/siswa/pengumuman`

**Sistem Alert Otomatis:**

#### 🔴 Critical - Overdue Items
- Tampil otomatis jika ada barang terlambat
- Menampilkan jumlah hari terlambat
- Red alert dengan border merah
- Call to action: segera kembalikan

#### 🟡 Warning - Due Soon
- Tampil untuk barang yang < 2 hari lagi harus dikembalikan
- Menampilkan countdown hari
- Amber alert dengan border kuning
- Reminder proaktif

#### 🟢 Success - Recent Approvals
- Notifikasi peminjaman yang baru disetujui
- Tombol langsung ke QR Code
- Green alert dengan border hijau
- Action button: "Lihat QR Code"

#### 🔴 Rejection Alerts
- Info peminjaman yang ditolak
- Tampilkan alasan penolakan lengkap
- Red info card
- Timestamp rejection

#### 📘 System Announcements
- Cara menggunakan QR Code
- Kebijakan peminjaman
- Tips perawatan barang
- Dengan timestamp publikasi

**Smart Display:**
- Hanya tampil yang relevan
- Priority-based ordering
- Empty state jika semua lancar

---

### 6. 👤 Profil
**Route:** `/profile/edit`

**Fitur:**
- Edit informasi personal
- Update password
- Preferences settings
- Logout

---

## 🎨 Design System

### Color Palette
```
Primary Blue:    #1d4ed8 (Sidebar, primary actions)
Success Green:   #10b981 (Completed, approved)
Warning Amber:   #f59e0b (Pending, due soon)
Danger Red:      #ef4444 (Rejected, overdue)
Info Blue:       #2563eb (Active borrowings)
Catalog Purple:  #9333ea (Inventory/catalog)
```

### Status Colors
| Status | Badge Color | Use Case |
|--------|-------------|----------|
| Pending | Amber | Menunggu persetujuan |
| Approved | Emerald | Disetujui, siap diambil |
| Borrowed | Blue | Sedang dipinjam |
| Returned | Gray | Sudah dikembalikan |
| Rejected | Red | Ditolak |

### Typography
- **Headings:** Font weight 700-800 (bold/extra-bold)
- **Body:** Font weight 400-500 (normal/medium)
- **Labels:** Font weight 600 (semi-bold)
- **Badges:** Font weight 700 (bold)

### Spacing
- Card padding: 18-24px
- Grid gap: 14-18px
- Section margin: 18-20px
- Icon size: 44px (touch-friendly)

---

## 📱 Responsive Breakpoints

### Desktop (> 1024px)
- 4 column stat grid
- Full sidebar visible
- 2 column content layout (main + right panel)

### Tablet (768px - 1024px)
- 2 column stat grid
- Sidebar toggleable
- Single column content

### Mobile (< 768px)
- 1 column stat grid
- Sidebar hidden (slide-in)
- Stacked cards
- Full-width buttons

---

## 🔄 Real-time Features

### Live Updates
- Dashboard stats auto-refresh (5s)
- QR Code validation real-time
- Status changes reflect immediately

### Reactive Components
- Livewire polling
- No page reload needed
- Smooth transitions

---

## ✅ User Experience Highlights

### 1. **Clear Visual Hierarchy**
- Important info prominently displayed
- Color-coded priorities
- Icon-based recognition
- Consistent layout patterns

### 2. **Proactive Notifications**
- Alert sebelum deadline
- Instant approval notifications
- Clear rejection feedback

### 3. **Helpful Empty States**
- Friendly messages
- Clear call-to-action
- Alternative options

### 4. **Mobile-First Design**
- Touch-friendly buttons
- Swipe-able cards (future)
- Readable on small screens
- Fast loading

### 5. **Accessible**
- High contrast ratios
- Descriptive icons
- Keyboard navigable
- Screen reader friendly

---

## 🚀 Performance

### Optimizations
- Eager loading relationships
- Pagination for large datasets
- Efficient queries with scopes
- CSS compiled and minified

### Page Load Times
- Dashboard: < 500ms
- History (10 items): < 300ms
- Catalog (with images): < 800ms

---

## 🔐 Security Features

### Authentication
- Middleware protected routes
- User ID verification
- Session management

### Data Access
- Own data only
- No cross-user access
- Validated inputs

---

## 📊 Analytics Potential

### Trackable Metrics
- Page views per section
- Most used filters
- QR scan frequency
- Average borrowing duration
- Return punctuality rate
- Rejection reasons analysis

### Future Enhancements
- Dashboard analytics widget
- Personal borrowing insights
- Gamification (badges)
- Reputation score

---

## 🛠️ Technology Stack

- **Framework:** Laravel 11.x
- **Frontend:** Blade + Livewire 3.x
- **Styling:** Custom CSS (Tailwind-inspired)
- **Icons:** Heroicons SVG
- **QR:** Base64 embedded images
- **Database:** MySQL with Eloquent ORM

---

## 🎯 Best Practices Implemented

1. ✅ **DRY (Don't Repeat Yourself)**
   - Reusable components
   - Shared layouts
   - CSS variables

2. ✅ **Mobile-First**
   - Responsive from ground up
   - Progressive enhancement

3. ✅ **Accessibility**
   - Semantic HTML
   - ARIA labels where needed
   - Keyboard navigation

4. ✅ **Performance**
   - Lazy loading
   - Query optimization
   - Asset compression

5. ✅ **Security**
   - CSRF protection
   - XSS prevention
   - SQL injection safe

---

## 📝 User Workflow Examples

### Scenario 1: Meminjam Barang Baru
1. 🏠 Dashboard → Lihat barang tersedia
2. 📦 Ke Katalog → Browse items
3. ➕ Klik "Pinjam" → Fill form
4. ✅ Submit → Wait for approval
5. 📢 Cek Pengumuman → Lihat status
6. 📋 Ke Peminjaman → Scan QR Code
7. ✅ Ambil barang → Done!

### Scenario 2: Mengembalikan Barang
1. 📢 Dapat reminder H-1
2. 📋 Ke Peminjaman → Lihat QR
3. 🏫 Ke ruang inventaris → Scan QR
4. ✅ Kembalikan barang → Done!
5. 📜 Cek Riwayat → Konfirmasi returned

### Scenario 3: Cek Status Pengajuan
1. 🏠 Dashboard → Lihat pending count
2. 📢 Ke Pengumuman → Lihat update
3. 📋 Ke Peminjaman → Detail status
4. Jika approved: Lihat QR
5. Jika rejected: Lihat alasan

---

## 🎓 Tips untuk Siswa

### ✅ Do's
- ✅ Kembalikan tepat waktu
- ✅ Cek pengumuman regularly
- ✅ Jaga kondisi barang
- ✅ Simpan QR Code (screenshot)
- ✅ Hubungi guru jika masalah

### ❌ Don'ts  
- ❌ Jangan share QR Code
- ❌ Jangan terlambat mengembalikan
- ❌ Jangan rusak barang
- ❌ Jangan pinjam untuk orang lain
- ❌ Jangan lupa scan saat ambil/kembali

---

## 📞 Support

Jika ada pertanyaan atau masalah:
1. 👨‍🏫 Hubungi guru pembimbing
2. 🏫 Ke ruang inventaris
3. 📧 Email: sipbar@sekolah.ac.id
4. 📱 WhatsApp: +62-xxx-xxxx-xxxx

---

**Last Updated:** 12 Agustus 2026
**Version:** 2.2.0
**Status:** ✅ Production Ready
