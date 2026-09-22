<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $this->put('site_name', 'SIPBAR', 'text', 'general', 'Nama Sistem');
        $this->put('site_subtitle', 'SMKN 1 BANGSRI', 'text', 'general', 'Subjudul Sistem');
        $this->put('site_logo', '/logossmkn1.png', 'image', 'general', 'Logo Utama');
        $this->put('site_logo_landing', '/logossmkn1.png', 'image', 'general', 'Logo Landing Page');
        $this->put('site_logo_login', '/logossmkn1.png', 'image', 'general', 'Logo Halaman Login');
        $this->put('site_logo_dashboard', '/logossmkn1.png', 'image', 'general', 'Logo Dashboard');
        $this->put('site_favicon', '/favicon.ico', 'image', 'general', 'Favicon (Logo Tab Browser)');
        $this->put('site_title', 'SIPBAR – Sistem Informasi Pengelolaan Barang', 'text', 'general', 'Judul Halaman (SEO)');

        $this->put('hero_badge', 'Sistem Inventaris Modern', 'text', 'hero', 'Badge Hero');
        $this->put('hero_title', 'Kelola Inventaris<br><em>Lebih Mudah</em> & Efisien', 'text', 'hero', 'Judul Hero');
        $this->put('hero_description', 'Platform web modern untuk mengelola inventaris sekolah secara digital, transparan, dan terintegrasi.', 'text', 'hero', 'Deskripsi Hero');
        $this->put('hero_background', '/sekolaheskasaba.jpeg', 'image', 'hero', 'Gambar Background Hero');
        $this->put('hero_cta_text', 'Dashboard', 'text', 'hero', 'Teks Tombol CTA Utama');
        $this->put('hero_cta_alt_text', 'Pelajari Lebih Lanjut', 'text', 'hero', 'Teks Tombol CTA Alternatif');

        $this->put('features_eyebrow', 'Kapabilitas Sistem', 'text', 'features', 'Label Fitur');
        $this->put('features_title', 'Tata Kelola Inventaris <em>Cepat & Terintegrasi</em>', 'text', 'features', 'Judul Section Fitur');
        $this->put('features_description', 'Mulai dari pengajuan siswa, approval guru secara instan, hingga serah-terima barang dengan QR code.', 'text', 'features', 'Deskripsi Section Fitur');

        $this->putJson('feature_cards', [
            [
                'index' => '01',
                'title' => 'Sirkulasi Peminjaman Digital & Validasi QR Code',
                'description' => 'Pengajuan mandiri & verifikasi ambil barang via QR code tanpa formulir kertas.',
                'icon' => 'qr',
                'workflow' => ['Pengajuan Siswa', 'Approval Guru', 'Scan QR Sarpras'],
                'link_text' => 'Pelajari Alur Peminjaman',
                'route' => 'loans.index',
            ],
            [
                'index' => '02',
                'title' => 'Manajemen Stok & Tracking Aset',
                'description' => 'Katalog aset lengkap dengan nomor registrasi, kondisi fisik, dan lokasi penempatan.',
                'icon' => 'inventory',
                'workflow' => [],
                'link_text' => 'Kelola Inventaris',
                'route' => 'inventory.index',
            ],
            [
                'index' => '03',
                'title' => 'Verifikasi Pengembalian',
                'description' => 'Cek kondisi fisik barang otomatis saat dikembalikan guna menjaga kualitas aset.',
                'icon' => 'return',
                'workflow' => [],
                'link_text' => 'Lihat Pengembalian',
                'route' => 'returns.index',
            ],
            [
                'index' => '04',
                'title' => 'Audit & Rekapitulasi Otomatis',
                'description' => 'Laporan sirkulasi & statistik pemakaian barang berkala secara instan dan akurat.',
                'icon' => 'report',
                'workflow' => [],
                'link_text' => 'Buka Laporan',
                'route' => 'reports.index',
            ],
            [
                'index' => '05',
                'title' => 'Kontrol Akses Multi-Peran',
                'description' => 'Hak akses terstruktur untuk Siswa, Guru Penanggung Jawab, Kepala Jurusan, dan Sarpras.',
                'icon' => 'users',
                'workflow' => [],
                'link_text' => 'Atur Pengguna',
                'route' => 'users.index',
            ],
        ], 'features');

        $this->put('stats_eyebrow', 'Data Inventaris System', 'text', 'stats', 'Label Statistik');
        $this->put('stats_title', 'Inventaris Sekolah<br><em>dalam Real-Time Data</em>', 'text', 'stats', 'Judul Section Statistik');
        $this->put('stats_description', 'Kelola dan pantau seluruh aset fisik sekolah secara terintegrasi, transparan, dan dapat diakses dari mana saja dengan sistem inventaris modern.', 'text', 'stats', 'Deskripsi Section Statistik');
        $this->put('stats_cta_text', 'Jelajahi Data Inventaris', 'text', 'stats', 'Teks Tombol CTA Statistik');

        $this->putJson('stats_data', [
            ['label' => 'Total Barang Terdata', 'sublabel' => 'Terintegrasi seluruh unit', 'trend' => 'Real-time', 'icon' => 'box', 'color' => 'blue'],
            ['label' => 'Kategori Aset', 'sublabel' => 'terbanyak', 'trend' => 'Terstruktur', 'icon' => 'tag', 'color' => 'cyan'],
            ['label' => 'Pengguna Aktif', 'sublabel' => '', 'trend' => 'Tersinkron', 'icon' => 'users', 'color' => 'purple'],
            ['label' => 'Sirkulasi Peminjaman', 'sublabel' => 'Proses approval cepat', 'trend' => 'Selesai', 'icon' => 'swap', 'color' => 'green'],
        ], 'stats');

        $this->put('about_eyebrow', 'Tentang Platform SIPBAR', 'text', 'about', 'Label Tentang');
        $this->put('about_title', 'Membangun Sistem Peminjaman Barang yang <span class="headline-accent">Terintegrasi</span>', 'text', 'about', 'Judul Section Tentang');
        $this->put('about_description', 'SIPBAR mentransformasi pencatatan inventaris sekolah konvensional menjadi ekosistem digital yang terintegrasi, transparan, dan dapat diakses dari mana saja.', 'text', 'about', 'Deskripsi Section Tentang');
        $this->put('about_image', '/sekolaheskasaba.jpeg', 'image', 'about', 'Gambar Section Tentang');
        $this->put('about_badge_year', '2026', 'text', 'about', 'Tahun Badge');
        $this->put('about_badge_name', 'SMKN 1 BANGSRI', 'text', 'about', 'Nama Badge');
        $this->put('about_caption_label', 'Gedung Utama Sekolah', 'text', 'about', 'Label Caption Gambar');
        $this->put('about_caption_sub', 'Pusat kegiatan belajar mengajar dan inovasi digital', 'text', 'about', 'Sub Caption Gambar');

        $this->putJson('about_features', [
            ['number' => '01', 'title' => 'Integrasi', 'description' => 'Persetujuan cepat tanpa kertas — guru dapat menyetujui peminjaman langsung dari smartphone.', 'icon' => 'check', 'primary' => true],
            ['number' => '02', 'title' => 'Akurasi', 'description' => 'Inventaris real-time — stok aset bertambah/berkurang otomatis setiap transaksi terverifikasi.', 'icon' => 'lightning', 'primary' => false],
            ['number' => '03', 'title' => 'Akuntabilitas', 'description' => 'Riwayat & log transparan — setiap pergerakan barang memiliki jejak audit lengkap.', 'icon' => 'clipboard', 'primary' => false],
            ['number' => '04', 'title' => 'Aksesibilitas', 'description' => 'Akses fleksibel multi-perangkat — responsif di PC, tablet, maupun ponsel.', 'icon' => 'device', 'primary' => false],
        ], 'about');

        $this->put('footer_brand_name', 'SIPBAR', 'text', 'footer', 'Nama Brand Footer');
        $this->put('footer_brand_subtitle', 'SMKN 1 BANGSRI', 'text', 'footer', 'Subjudul Brand Footer');
        $this->put('footer_description', 'Sistem inventaris berbasis web yang lebih efektif, efisien, dan transparan untuk sekolah.', 'text', 'footer', 'Deskripsi Footer');
        $this->put('footer_copyright', '© '.date('Y').' SIPBAR – Sistem Informasi Pengelolaan Barang. All rights reserved.', 'text', 'footer', 'Teks Copyright');
        $this->put('footer_heading_menu', 'Menu', 'text', 'footer', 'Judul Kolom Menu');
        $this->put('footer_heading_features', 'Fitur', 'text', 'footer', 'Judul Kolom Fitur');
        $this->put('footer_heading_help', 'Bantuan', 'text', 'footer', 'Judul Kolom Bantuan');

        $this->putJson('footer_links', [
            'navigation' => [
                ['text' => 'Beranda', 'url' => '#beranda'],
                ['text' => 'Fitur', 'url' => '#fitur'],
                ['text' => 'Tentang', 'url' => '#tentang'],
            ],
            'information' => [
                ['text' => 'Manajemen Barang', 'url' => '#fitur'],
                ['text' => 'Peminjaman', 'url' => '#fitur'],
                ['text' => 'Pengembalian', 'url' => '#fitur'],
                ['text' => 'Laporan', 'url' => '#fitur'],
                ['text' => 'Pengguna', 'url' => '#fitur'],
            ],
            'legal' => [
                ['text' => 'Panduan Penggunaan', 'url' => '#'],
                ['text' => 'FAQ', 'url' => '#'],
                ['text' => 'Kebijakan Privasi', 'url' => '#'],
                ['text' => 'Syarat & Ketentuan', 'url' => '#'],
            ],
            'social' => [
                ['text' => 'Instagram', 'url' => '', 'icon' => 'instagram'],
                ['text' => 'Facebook', 'url' => '', 'icon' => 'facebook'],
                ['text' => 'Twitter', 'url' => '', 'icon' => 'twitter'],
            ],
        ], 'footer');

        $this->put('contact_email', 'info@smkn1bangsri.sch.id', 'text', 'contact', 'Email Kontak');
        $this->put('contact_phone', '(0291) 123-4567', 'text', 'contact', 'Telepon Kontak');
        $this->put('contact_address', 'Jl. Raya Bangsri No. 123, Jepara, Jawa Tengah', 'text', 'contact', 'Alamat Kontak');

        $this->put('nav_link_home', 'Beranda', 'text', 'navigation', 'Link Beranda');
        $this->put('nav_link_features', 'Fitur', 'text', 'navigation', 'Link Fitur');
        $this->put('nav_link_inventory', 'Inventaris', 'text', 'navigation', 'Link Inventaris');
        $this->put('nav_link_about', 'Tentang', 'text', 'navigation', 'Link Tentang');
        $this->put('nav_link_help', 'Bantuan', 'text', 'navigation', 'Link Bantuan');
        $this->put('nav_link_features_mobile', 'Fitur Utama', 'text', 'navigation', 'Link Fitur (Mobile)');
        $this->put('nav_link_inventory_mobile', 'Katalog Inventaris', 'text', 'navigation', 'Link Inventaris (Mobile)');
        $this->put('nav_link_about_mobile', 'Tentang SIPBAR', 'text', 'navigation', 'Link Tentang (Mobile)');
        $this->put('nav_link_help_mobile', 'Bantuan & Kontak', 'text', 'navigation', 'Link Bantuan (Mobile)');

        SiteSetting::clearCache();
    }

    private function put(string $key, string $value, string $type, string $group, string $label): void
    {
        if (SiteSetting::query()->where('key', $key)->exists()) {
            return;
        }

        SiteSetting::set($key, $value, $type, $group);
        SiteSetting::query()->where('key', $key)->update(['label' => $label]);
    }

    private function putJson(string $key, array $value, string $group): void
    {
        if (SiteSetting::query()->where('key', $key)->exists()) {
            return;
        }

        SiteSetting::setJson($key, $value, $group);
    }
}
