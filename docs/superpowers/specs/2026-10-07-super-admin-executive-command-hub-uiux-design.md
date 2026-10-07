# Spesifikasi Desain: Super Admin Executive Command Hub UI/UX Overhaul

**Tanggal:** 2026-10-07  
**Status:** Approved by User  
**Topik:** Pembaruan Menyeluruh UI/UX Panel Super Admin UKM Ilmu Komputer (Layout Master, Sidebar Hierarkis 3 Klaster, Topbar Interaktif, Dashboard Metrik Simetris 4x2, Quick Action Bar, serta Standardisasi Kartu di Seluruh Modul)

---

## 1. Latar Belakang & Masalah Saat Ini

Dari hasil penelaahan menyeluruh pada modul panel admin:
1. **Navigasi Sidebar yang Menumpuk**:
   - 14 tautan menu ditumpuk secara vertikal tanpa pengelompokan yang jelas.
   - Ikon navigasi menggunakan tag `<svg>` inline dengan dimensi tidak seragam, sementara header kategori menggunakan tag `<i>` Font Awesome yang tidak ter-load (Font Awesome CDN belum terpasang di `admin/layouts/app.blade.php`).
   - Tidak ada indikator badge notifikasi (misal: jumlah calon pendaftar yang menunggu review), dan sidebar tidak bisa di-collapse di layar desktop.
2. **Dashboard yang Tidak Simetris & Berantakan**:
   - 6 kartu metrik atas (`.admin-stat-grid`) menggunakan `repeat(auto-fit, minmax(210px, 1fr))` sehingga menghasilkan baris tidak rata (misal 4 kartu di baris atas dan 2 kartu menggantung di baris bawah).
   - Banner kontrol rekrutmen berupa kontainer polos dengan form submit yang kaku.
   - Bagian bawah (Statistik Divisi vs Pendaftar Terbaru) kurang memiliki ruang napas, visual styling tabel minimal, dan belum ada pusat aksi cepat (*Quick Actions*).
3. **Inkonsistensi Komponen Kartu di Seluruh Modul**:
   - Banyak elemen yang menggunakan *inline styling* darurat pada blade file (`style="..."`), menyebabkan jarak margin/padding, bayangan, dan ukuran font tidak konsisten antara halaman Pusat Pendaftaran, Anggota, Presensi, Biodata Divisi, dan Manajemen Pengguna.

---

## 2. Arsitektur & Prinsip Desain: "Executive Command Hub"

Sistem ini memperkuat konsep **Modern White Claymorphism**:
- **Kanvas**: `#eef3f8` (soft clean light clay).
- **Permukaan Kartu**: `#ffffff` dengan dual-shadow tactility:
  - `box-shadow: var(--clay-card)`
  - `border-radius: var(--radius-lg)` (24px)
  - `border: 1px solid rgba(255, 255, 255, 0.95)`
- **Tipografi**: `Plus Jakarta Sans` untuk teks & heading, `JetBrains Mono` untuk angka metrik dan kode.
- **Ikonografi Terpadu**: Font Awesome 6.4 Free (Solid & Regular) di seluruh modul admin.

---

## 3. Komponen Spesifik yang Didesain Ulang

### A. Layout Master Admin (`resources/views/admin/layouts/app.blade.php`)
1. **Pemasangan CDN Font Awesome 6.4**:
   `<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">`
2. **Sidebar Interaktif & Terstruktur (260px)**:
   - **Header Brand**: Logo UKM, teks judul "UKM ILKOM CMS", dan badge pill *"SUPER ADMIN"* berlatar biru/emas timbul.
   - **Mini Profile Card**: Avatar profil bulat, nama admin, email, dan status chip.
   - **3 Klaster Navigasi**:
     - **Klaster 1: AKADEMIK & MAHASISWA**
       - Ringkasan Dashboard (`fa-chart-pie`)
       - Pusat Pendaftaran (`fa-user-plus`)
       - Anggota UKM (`fa-users`)
       - Presensi & BAP (`fa-clipboard-check`)
       - Papan Pengumuman (`fa-bullhorn`)
     - **Klaster 2: PUBLIKASI & PORTOFOLIO**
       - Karya Mahasiswa (`fa-laptop-code`)
       - Agenda & Event (`fa-calendar-days`)
       - Artikel & Berita (`fa-newspaper`)
       - E-Sertifikat (`fa-certificate`)
       - Galeri Dokumentasi (`fa-images`)
     - **Klaster 3: TATA KELOLA & SUPER ADMIN**
       - Struktur Pengurus BPH (`fa-sitemap`)
       - Biodata 4 Divisi (`fa-layer-group`)
       - Pengaturan Gelombang (`fa-sliders`)
       - Manajemen Pengguna & Hak Akses (`fa-user-shield`)
   - **Status Active Indicator**: Efek pill clay timbul putih dengan aksen bar vertikal di sisi kiri (`--accent-blue`).
   - **Sidebar Footer**: Tombol *"Lihat Web Publik"* (pill outline) dan *"Keluar"* (clay soft danger).
3. **Topbar Interaktif (Sticky 70px)**:
   - Tombol toggle sidebar untuk desktop dan mobile.
   - Breadcrumb dinamis (`Admin / Dashboard / ...`).
   - Indikator live status pendaftaran (Open/Closed).
   - Avatar ringkas dengan nama Super Admin.

### B. Dashboard Super Admin (`resources/views/admin/dashboard.blade.php`)
1. **Welcome Command Banner**:
   - Banner clay putih menyapa user, menampilkan info gelombang yang sedang aktif serta tautan cepat.
2. **Grid Metrik Simetris 4-Kolom (2 Baris Seimbang)**:
   - **Baris 1: Pilar Rekrutmen & Anggota**:
     - Total Pendaftar (`#0284c7`, icon `fa-user-plus`)
     - Menunggu Seleksi (`#d97706`, icon `fa-clock`)
     - Lolos Seleksi (`#059669`, icon `fa-circle-check`)
     - Anggota Aktif (`#10b981`, icon `fa-users`)
   - **Baris 2: Pilar Operasional & Konten**:
     - Sesi Presensi Riset (`#8b5cf6`, icon `fa-calendar-check`)
     - Karya Mahasiswa (`#ec4899`, icon `fa-laptop-code`)
     - Publikasi Artikel (`#3b82f6`, icon `fa-newspaper`)
     - Kontrol Status Pendaftaran (Widget Switcher Live Buka/Tutup dengan form toggle terintegrasi).
3. **Quick Action Bar (Pusat Aksi Cepat)**:
   - Tombol shortcut berdesain pill clay:
     - `+ Tambah Anggota Baru` -> `route('admin.members.index')`
     - `+ Buka Sesi Presensi` -> `route('admin.attendance.create')`
     - `+ Buat Pengumuman` -> `route('admin.announcements.index')`
     - `⚙️ Pengaturan Gelombang` -> `route('admin.recruitment.settings')`
4. **Grid 2-Kolom Bawah Simetris (50% : 50%)**:
   - **Kolom Kiri: Statistik & Kinerja 4 Divisi**:
     - Tabel rapi ber-accent border divisi masing-masing (Pemrograman: Biru, Multimedia: Ungu, IoT: Hijau, Cyber: Merah).
     - Kolom: Divisi & Kadiv, Artikel, Pendaftar.
   - **Kolom Kanan: Pendaftar Terbaru Masuk**:
     - Tabel ringkas ber-avatar mini, Nama, NIM, Tag Divisi Pilihan, Status Seleksi Badge, dan link langsung ke detail seleksi.

### C. Standardisasi Komponen Kartu di Seluruh Modul (`public/css/portal.css`)
1. `.admin-stat-grid`: Grid 4-kolom stabil di desktop (`grid-template-columns: repeat(4, 1fr)` pada layar `>= 1100px`, 2 kolom pada tablet, 1 kolom pada ponsel).
2. `.admin-stat-card`: Ukuran seimbang, ikon bulat timbul dengan warna aksen lembut, tipografi mono tebal.
3. `.admin-filter-bar`: Toolbar debossed yang menyatu harmonis dengan kontainer data.
4. `.admin-table`: Header abu-abu bersih `#f8fafc`, padding sel proporsional `1rem 1.25rem`, visual hover baris yang lembut.
5. Pembersihan inline style berlebih pada modul-modul utama (Biodata Divisi, Manajemen Pengguna, Presensi, Anggota).

---

## 4. Kriteria Pengujian & Keberhasilan

1. **Pengujian Tampilan & Visual**:
   - Seluruh ikon Font Awesome muncul sempurna tanpa broken glyphs.
   - Grid metrik dashboard terisi 4 kolom rapi tanpa ada kartu menggantung sendirian.
   - Navigasi sidebar terkelompok rapi ke dalam 3 kategori dengan indikator aktif yang jelas.
2. **Pengujian Fungsionalitas**:
   - Toggle buka/tutup pendaftaran di dashboard tetap berjalan normal.
   - Toggle sidebar di mobile dan desktop berfungsi halus.
   - Seluruh form dan filter di modul-modul admin tetap beroperasi normal.
3. **Automated Tests**:
   - Seluruh 89 automated tests (`php artisan test`) harus tetap lolos **100% HIJAU (PASS)** tanpa ada regresi.
