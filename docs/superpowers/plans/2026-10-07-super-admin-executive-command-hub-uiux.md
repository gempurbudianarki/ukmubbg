# Super Admin Executive Command Hub UI/UX Overhaul Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mentransformasikan antarmuka Super Admin menjadi "Executive Command Hub" yang simetris, modern (White Claymorphism), dengan navigasi hierarkis 3 klaster, dashboard 4x2 simetris, quick action bar, dan standardisasi kartu di seluruh modul admin.

**Architecture:** Memperbarui CSS utility di `public/css/portal.css`, merombak layout master `resources/views/admin/layouts/app.blade.php` dengan integrasi Font Awesome 6.4 dan sidebar collapsible, mendesain ulang dashboard `resources/views/admin/dashboard.blade.php`, serta menstandarisasi komponen kartu di halaman-halaman utama panel admin.

**Tech Stack:** Laravel 10 (Blade Templates), Vanilla CSS (`portal.css`), Font Awesome 6.4, Vanilla JavaScript, PHP 8.4 (`E:\laragon\bin\php\php-8.4.23-Win32-vs17-x64\php.exe`), PHPUnit / Laravel Feature Tests.

**Spec:** `docs/superpowers/specs/2026-10-07-super-admin-executive-command-hub-uiux-design.md`

## Global Constraints
- PHP binary must be invoked via `& "E:\laragon\bin\php\php-8.4.23-Win32-vs17-x64\php.exe"`.
- Seluruh automated feature tests (89 tests / 383 assertions) harus tetap lolos **100% PASS** tanpa ada regresi.
- Menggunakan palet White Claymorphism konsisten: Kanvas `#eef3f8`, Kartu `#ffffff` dengan dual-shadow tactility, dan aksen warna resmi 4 divisi.
- Menghilangkan *inline styling* yang berantakan dan menstandarisasi ke utility class yang dapat digunakan kembali.

---

### Task 1: CSS Utilities & Design Tokens di `portal.css`

**Files:**
- Modify: `public/css/portal.css`
- Test: `tests/Feature/LayoutDesignTest.php`

**Interfaces:**
- Consumes: Token White Claymorphism yang ada di `:root`.
- Produces: 
  - `.admin-stat-grid` (4-kolom stabil di desktop `>= 1024px`, 2-kolom tablet, 1-kolom ponsel).
  - `.admin-quick-actions` & `.admin-quick-btn` (tombol shortcut cepat).
  - `.admin-cluster-heading` (header pemisah klaster navigasi di sidebar).
  - `.admin-profile-badge` (mini profile card di sidebar).
  - `.admin-welcome-banner` (banner sambutan executive di dashboard).
  - `.admin-layout.sidebar-closed` (gaya desktop collapsible sidebar).

- [ ] **Step 1: Tambahkan CSS rules untuk Admin Executive Command Hub di `portal.css`**
  Tambahkan kelas untuk `.admin-cluster-heading`, `.admin-quick-actions`, `.admin-quick-btn`, responsive 4-column `.admin-stat-grid`, `.admin-welcome-banner`, dan collapsible sidebar di akhir bagian admin styles `public/css/portal.css`.

- [ ] **Step 2: Jalankan LayoutDesignTest untuk memastikan CSS valid**
  Run: `& "E:\laragon\bin\php\php-8.4.23-Win32-vs17-x64\php.exe" artisan test --filter=LayoutDesignTest`
  Expected: PASS

---

### Task 2: Redesain Layout Master Admin (`resources/views/admin/layouts/app.blade.php`)

**Files:**
- Modify: `resources/views/admin/layouts/app.blade.php`
- Test: `tests/Feature/AdminEcosystemTest.php`
- Test: `tests/Feature/SecurityAndAccessControlTest.php`

**Interfaces:**
- Consumes: CSS utilities dari Task 1 & Font Awesome 6.4 CDN.
- Produces: 
  - Sidebar hierarkis 3 klaster (*Akademik & Mahasiswa*, *Publikasi & Portofolio*, *Tata Kelola & Super Admin*) dengan ikon Font Awesome seragam.
  - Mini profile card Super Admin di sidebar.
  - Topbar sticky dengan toggle collapse, breadcrumb dinamis, dan avatar dropdown.

- [ ] **Step 1: Pasang Font Awesome 6.4 CDN pada `<head>`**
  Tambahkan `<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">` di baris `<head>`.

- [ ] **Step 2: Rombak struktur Sidebar Navigasi**
  Ganti ikon SVG yang beragam ukuran dengan ikon Font Awesome yang seragam. Kelompokkan ke dalam 3 klaster:
  - Klaster 1: Akademik & Keanggotaan (Dashboard, Pusat Pendaftaran, Anggota UKM, Presensi & BAP, Papan Pengumuman).
  - Klaster 2: Publikasi & Portofolio (Karya Mahasiswa, Agenda & Event, Artikel & Berita, E-Sertifikat, Galeri Dokumentasi).
  - Klaster 3: Tata Kelola & Super Admin (Struktur Pengurus, Biodata 4 Divisi, Pengaturan Gelombang, Manajemen Pengguna).

- [ ] **Step 3: Tambahkan Mini Profile Badge & Desktop Collapse Button**
  Terapkan tombol collapse pada header sidebar dan topbar dengan script `toggleAdminSidebar()`, yang mendukung toggle di desktop dan mobile drawer.

- [ ] **Step 4: Jalankan automated tests untuk memverifikasi keamanan dan akses panel admin**
  Run: `& "E:\laragon\bin\php\php-8.4.23-Win32-vs17-x64\php.exe" artisan test --filter=SecurityAndAccessControlTest`
  Expected: PASS

---

### Task 3: Redesain Dashboard Super Admin (`resources/views/admin/dashboard.blade.php`)

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php`
- Test: `tests/Feature/UkmPortalTest.php`
- Test: `tests/Feature/RecruitmentSettingsAdminTest.php`

**Interfaces:**
- Consumes: Data yang disediakan oleh `DashboardController` (`$totalApplicants`, `$pendingApplicants`, `$acceptedApplicants`, `$totalMembers`, `$totalSessions`, `$totalPosts`, `$recruitmentStatus`, `$divisionsStats`, `$recentApplicants`).
- Produces: 
  - Welcome Executive Banner.
  - Symmetrical 4x2 Metric Cards Grid (8 kartu terdistribusi rata).
  - Quick Action Command Bar.
  - 50:50 Symmetrical Bottom Grid (Statistik 4 Divisi vs 5 Pendaftar Terbaru Masuk).

- [ ] **Step 1: Implementasikan Welcome Executive Banner**
  Buat banner clay putih dengan sapaan hangat untuk admin, status gelombang aktif saat ini, dan waktu terkini.

- [ ] **Step 2: Susun Symmetrical 4-Kolom x 2-Baris Stat Grid**
  - Baris 1: Total Pendaftar, Menunggu Seleksi, Lolos Diterima, Anggota Aktif.
  - Baris 2: Sesi Presensi, Karya Mahasiswa, Publikasi Artikel, Card Switcher Oprec Terintegrasi.

- [ ] **Step 3: Pasang Quick Action Command Bar**
  Tambahkan bar tombol shortcut:
  - `+ Tambah Anggota Baru`
  - `+ Buka Presensi Baru`
  - `⚙️ Pengaturan Gelombang`
  - `👤 Kelola Pengguna`

- [ ] **Step 4: Rapikan Grid Bawah 50:50 (Statistik 4 Divisi & Pendaftar Terbaru)**
  Terapkan aksen warna divisi (Pemrograman: biru, Multimedia: ungu, IoT: hijau, Cyber: merah) pada tabel divisi kiri, dan tabel pendaftar terbaru kanan dengan avatar inisial, chip divisi, serta link review.

- [ ] **Step 5: Jalankan test dashboard dan recruitment**
  Run: `& "E:\laragon\bin\php\php-8.4.23-Win32-vs17-x64\php.exe" artisan test --filter=UkmPortalTest`
  Expected: PASS

---

### Task 4: Standardisasi Komponen Kartu di Modul-Modul Utama

**Files:**
- Modify: `resources/views/admin/divisions/index.blade.php` (Rapikan kartu 2x2 divisi, hapus inline SVG tidak perlu).
- Modify: `resources/views/admin/users/index.blade.php` (Standarisasi 4 kartu metrik pengguna, filter bar, dan tabel).
- Modify: `resources/views/admin/recruitment/index.blade.php` (Standarisasi filter toolbar, status badge, dan tabel).
- Modify: `resources/views/admin/attendance/index.blade.php` (Standarisasi 3-kolom grid sesi pertemuan dan tombol aksi BAP).
- Modify: `resources/views/admin/members/index.blade.php` (Standarisasi kartu metrik anggota dan filter debossed).
- Test: `tests/Feature/UserAdminTest.php`
- Test: `tests/Feature/MemberAdminTest.php`
- Test: `tests/Feature/AttendanceAdminTest.php`

**Interfaces:**
- Consumes: Utility `.admin-clay-card`, `.admin-stat-grid`, `.admin-filter-bar`, `.admin-table` dari `portal.css`.
- Produces: Tampilan visual yang konsisten 100% di semua menu tanpa perbedaan font, margin, atau bayangan yang janggal.

- [ ] **Step 1: Perbarui `resources/views/admin/divisions/index.blade.php`**
  Ganti header SVG dengan ikon Font Awesome, rapikan grid 2x2 divisi dengan badge status dan padding simetris.

- [ ] **Step 2: Perbarui `resources/views/admin/users/index.blade.php`**
  Standarisasi grid 4 metrik akun, form pencarian/filter debossed, dan tabel akun pengguna.

- [ ] **Step 3: Perbarui `resources/views/admin/recruitment/index.blade.php` & `members/index.blade.php`**
  Standarisasi filter toolbar, chip divisi bergradasi, dan layout tabel.

- [ ] **Step 4: Perbarui `resources/views/admin/attendance/index.blade.php`**
  Standarisasi grid kartu sesi presensi, passcode chip, dan tombol BAP cetak.

- [ ] **Step 5: Jalankan pengujian modul admin**
  Run: `& "E:\laragon\bin\php\php-8.4.23-Win32-vs17-x64\php.exe" artisan test --filter=UserAdminTest`
  Run: `& "E:\laragon\bin\php\php-8.4.23-Win32-vs17-x64\php.exe" artisan test --filter=MemberAdminTest`
  Run: `& "E:\laragon\bin\php\php-8.4.23-Win32-vs17-x64\php.exe" artisan test --filter=AttendanceAdminTest`
  Expected: PASS

---

### Task 5: Verifikasi Penuh & Regresi Suite Testing

**Files:**
- Verify: Seluruh 27 file Feature Tests di `tests/Feature/`.

- [ ] **Step 1: Jalankan seluruh test suite Laravel (89 tests)**
  Run: `& "E:\laragon\bin\php\php-8.4.23-Win32-vs17-x64\php.exe" artisan test`
  Expected: 89 passed (383 assertions).

- [ ] **Step 2: Verifikasi visual dan interaksi**
  Pastikan tidak ada error sintaks Blade, semua tautan routing valid, dan layout responsif di desktop maupun mobile.
