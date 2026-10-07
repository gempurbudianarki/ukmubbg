# White Claymorphism UI/UX Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mentransformasikan seluruh UI/UX web UKM Ilmu Komputer (Portal Publik, Portal Mahasiswa, dan Admin CMS) ke sistem desain **Modern White Claymorphism** (puffy 3D, dual outward/inset shadows, debossed form inputs, tactile button presses, dan palet clean off-white).

**Architecture:** Memusatkan fondasi design token di `public/css/portal.css` dengan variabel CSS dual-shadow, puffy border radii, dan elevasi clay; kemudian mengintegrasikannya ke layout blade master (`layouts/app.blade.php`, `student/layouts/app.blade.php`, `admin/layouts/app.blade.php`) dan seluruh view halaman publik, mahasiswa, serta CMS admin.

**Tech Stack:** Laravel 10 (Blade Templates), Vanilla CSS (`portal.css`), Font Awesome 6.4, Vanilla JS (`portal.js`), PHPUnit / Laravel Feature Tests.

**Spec:** `docs/superpowers/specs/2026-10-06-white-claymorphism-redesign-design.md`

## Global Constraints
- Canvas latar belakang: `#eef3f8` (soft clean light clay).
- Permukaan elemen: `#ffffff` dengan dual-shadow claymorphism (outward drop + inset highlight/bevel).
- Tidak boleh merusak logika bisnis, rute, nama parameter, atau pengujian otomatis yang sudah ada (seluruh 60 test suite harus tetap PASS).
- Form inputs harus bertekstur *debossed* (terasa diukir ke dalam clay).
- Sudut membulat empuk: `14px` hingga `30px` dan *pill* `9999px`.

---

### Task 1: Core Design Tokens & Claymorphism Foundation di `portal.css`

**Files:**
- Modify: `public/css/portal.css:1-350`
- Test: `tests/Feature/LayoutDesignTest.php`

**Interfaces:**
- Consumes: Existing base reset and utility classes in `portal.css`.
- Produces: CSS variables `--clay-card`, `--clay-card-hover`, `--clay-btn`, `--clay-input`, `--radius-clay-*`, and updated base classes `.btn`, `.card`, `.badge`, `.alert`, `.form-control`.

- [ ] **Step 1: Definisikan CSS variables White Claymorphism di `:root`**
  Perbarui palet warna latar menjadi `--bg-body: #eef3f8;`, `--bg-surface: #ffffff;`, tambahkan token dual outward & inset shadow clay, serta border-radius puffy.
- [ ] **Step 2: Perbarui style dasar `.card`, `.card-glass`, dan kontainer komponen**
  Ganti efek glass flat menjadi efek 3D clay dengan dual shadow dan border lembut `rgba(255, 255, 255, 0.9)`.
- [ ] **Step 3: Perbarui style tombol `.btn`, `.btn-primary`, `.btn-glass`, `.btn-outline`**
  Terapkan elevasi clay 3D, efek hover melayang (`translateY(-2px)`), dan efek klik kenyal (`transform: scale(0.98)`).
- [ ] **Step 4: Perbarui `.form-control` & `.form-select` menjadi gaya debossed clay**
  Gunakan inset shadow ganda (`inset 3px 3px 6px rgba(160, 175, 200, 0.22), inset -2px -2px 5px #ffffff`).
- [ ] **Step 5: Jalankan pengujian layout dasar**
  Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=LayoutDesignTest`
  Expected: PASS

---

### Task 2: Redesign Layout & Navbar Portal Publik

**Files:**
- Modify: `resources/views/layouts/app.blade.php`
- Modify: `resources/views/layouts/navbar.blade.php`
- Modify: `resources/views/layouts/footer.blade.php`
- Modify: `public/css/portal.css:350-550`
- Test: `tests/Feature/LayoutDesignTest.php`

**Interfaces:**
- Consumes: Token claymorphism dari Task 1.
- Produces: Floating white clay pill navbar, alert banner clay, dan footer clay.

- [ ] **Step 1: Terapkan floating clay pill navbar pada `.navbar-sticky` & `.navbar-inner`**
  Navbar tampil seperti kapsul putih melayang dengan sudut membulat `9999px`, bayangan clay mengambang, dan item menu aktif berupa pill timbul lembut.
- [ ] **Step 2: Perbarui mobile drawer menu menjadi panel clay putih empuk**
  Tampilan drawer pada perangkat mobile mengadopsi kartu clay dengan item menu membulat.
- [ ] **Step 3: Perbarui alert banner di `layouts/app.blade.php`**
  Alert pesan sukses/gagal diubah menjadi kartu clay putih berbezel warna status timbul (*success, error, info*).
- [ ] **Step 4: Perbarui footer di `layouts/footer.blade.php`**
  Footer dibungkus dalam container clay besar beradius membulat `32px` dengan latar putih bersih.
- [ ] **Step 5: Jalankan pengujian layout**
  Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=LayoutDesignTest`
  Expected: PASS

---

### Task 3: Redesign Beranda & Halaman Kanal Publik

**Files:**
- Modify: `resources/views/home/index.blade.php`
- Modify: `resources/views/home/about.blade.php`
- Modify: `resources/views/divisions/index.blade.php`
- Modify: `resources/views/divisions/show.blade.php`
- Modify: `resources/views/projects/index.blade.php`
- Modify: `resources/views/events/index.blade.php`
- Modify: `resources/views/officers/index.blade.php`
- Modify: `resources/views/galleries/index.blade.php`
- Modify: `resources/views/certificates/verify.blade.php`
- Modify: `resources/views/posts/index.blade.php`
- Modify: `resources/views/posts/show.blade.php`
- Test: `tests/Feature/HomepageExperienceTest.php`
- Test: `tests/Feature/PublicSubsystemsTest.php`

**Interfaces:**
- Consumes: Navbar dan token clay.
- Produces: Seluruh halaman publik dalam balutan White Claymorphism.

- [ ] **Step 1: Modifikasi Hero section & 4 Division cards di `home/index.blade.php`**
  Jadikan 4 card divisi seperti balok clay 3D interaktif yang empuk, dengan ikon divisi timbul dan badge kuota/status pendaftaran.
- [ ] **Step 2: Modifikasi Showcase Proyek, Event, & Postingan di Beranda**
  Bungkus preview proyek dan event dalam card clay putih dengan thumbnail beradius lembut.
- [ ] **Step 3: Modifikasi `projects/index.blade.php` dan `events/index.blade.php`**
  Filter tombol kategori dijadikan clay pill buttons dan card galeri proyek dibuat timbul 3D.
- [ ] **Step 4: Modifikasi `officers/index.blade.php` & `galleries/index.blade.php`**
  Kartu profil BPH/Koordinator divisi dengan avatar bundar berbezel clay 3D.
- [ ] **Step 5: Modifikasi `certificates/verify.blade.php` & `posts/index.blade.php`**
  Form cek sertifikat dengan input debossed clay dan kartu verifikasi ber-badge hijau timbul.
- [ ] **Step 6: Jalankan pengujian beranda & subsistem publik**
  Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=PublicSubsystemsTest`
  Expected: PASS

---

### Task 4: Redesign Formulir Rekrutmen Terpadu & Pelacakan Status

**Files:**
- Modify: `resources/views/recruitment/index.blade.php`
- Modify: `resources/views/recruitment/status.blade.php`
- Modify: `resources/views/recruitment/success.blade.php`
- Modify: `public/css/portal.css:800-950`
- Test: `tests/Feature/RecruitmentWorkflowTest.php`
- Test: `tests/Feature/RecruitmentWindowGuardTest.php`

**Interfaces:**
- Consumes: Debossed input classes and clay card wrappers.
- Produces: Claymorphism multi-step recruitment registration and status tracker.

- [ ] **Step 1: Terapkan kartu clay putih besar pada form pendaftaran `recruitment/index.blade.php`**
  Tahapan stepper dijadikan bulatan 3D clay bernomor dengan efek glow aktif.
- [ ] **Step 2: Terapkan input debossed clay pada field teks, password, file upload KTM/CV, dan textarea motivasi**
  Area upload file diberi border putus-putus lembut bertekstur cekung (*debossed dropzone*).
- [ ] **Step 3: Perbarui halaman sukses `recruitment/success.blade.php` dan cek status `recruitment/status.blade.php`**
  Badge kode registrasi dibuat dalam bentuk clay pill timbul besar, disertai tombol cepat menuju portal mahasiswa.
- [ ] **Step 4: Jalankan pengujian alur rekrutmen**
  Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=RecruitmentWorkflowTest`
  Expected: PASS

---

### Task 5: Redesign Portal Mahasiswa (Student Hub)

**Files:**
- Modify: `resources/views/student/layouts/app.blade.php`
- Modify: `resources/views/student/dashboard.blade.php`
- Modify: `resources/views/student/kta.blade.php`
- Modify: `resources/views/student/presensi.blade.php`
- Modify: `resources/views/student/silabus.blade.php`
- Modify: `resources/views/student/profile.blade.php`
- Test: `tests/Feature/StudentDashboardAccessTest.php`
- Test: `tests/Feature/StudentProfileEditTest.php`

**Interfaces:**
- Consumes: Autentikasi `auth` dan role `member`.
- Produces: Portal Mahasiswa berestetika White Claymorphism menyeluruh.

- [ ] **Step 1: Transformasi layout sidebar & topbar `student/layouts/app.blade.php`**
  Ubah tema gelap sidebar menjadi light white clay (`#ffffff`) dengan border pemisah lembut, link aktif berupa clay pill biru empuk, dan kartu profil mini dengan avatar timbul.
- [ ] **Step 2: Redesign Student Dashboard (`student/dashboard.blade.php`)**
  Kartu ucapan selamat datang, stepper seleksi rekrutmen 3D, jadwal wawancara, dan kartu metrik kehadiran.
- [ ] **Step 3: Redesign KTA Digital (`student/kta.blade.php`)**
  Kartu identitas 3D clay premium: Sudut 26px, debossed chip, hologram halus, frame QR code cekung, dan tombol cetak clay.
- [ ] **Step 4: Redesign Presensi & Silabus (`student/presensi.blade.php`, `student/silabus.blade.php`)**
  Pill status kehadiran clay (Hadir: hijau, Izin: kuning, Sakit: biru, Alpa: merah) dan kartu silabus pertemuan.
- [ ] **Step 5: Redesign Edit Profil (`student/profile.blade.php`)**
  Kartu preview foto avatar timbul dengan debossed fields input teks & password.
- [ ] **Step 6: Jalankan pengujian portal mahasiswa**
  Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=StudentDashboardAccessTest`
  Expected: PASS

---

### Task 6: Redesign Admin CMS Panel

**Files:**
- Modify: `resources/views/admin/layouts/app.blade.php`
- Modify: `resources/views/admin/dashboard.blade.php`
- Modify: `resources/views/admin/recruitment/index.blade.php`
- Modify: `resources/views/admin/recruitment/show.blade.php`
- Modify: `resources/views/admin/recruitment/settings.blade.php`
- Modify: `resources/views/admin/members/index.blade.php`
- Modify: `resources/views/admin/attendance/index.blade.php`
- Modify: `resources/views/admin/attendance/create.blade.php`
- Modify: `resources/views/admin/attendance/show.blade.php`
- Modify: `resources/views/admin/login.blade.php`
- Test: `tests/Feature/MemberAdminTest.php`
- Test: `tests/Feature/AttendanceAdminTest.php`
- Test: `tests/Feature/RecruitmentConversionTest.php`

**Interfaces:**
- Consumes: Admin controller outputs and role authorization.
- Produces: Panel Admin CMS dalam tema White Claymorphism yang bersih dan nyaman dipakai.

- [ ] **Step 1: Transformasi layout `admin/layouts/app.blade.php` & Halaman Login `admin/login.blade.php`**
  Sidebar putih clay dengan navigasi beradius 16px, header mengambang, dan card login clay 3D.
- [ ] **Step 2: Redesign Admin Dashboard widgets & stat cards (`admin/dashboard.blade.php`)**
  Kartu metrik statistik 3D timbul dengan angka tebal dan badge divisi pastel.
- [ ] **Step 3: Redesign Kontainer Tabel Data (Pusat Rekrutmen, Anggota, Presensi)**
  Tabel dibungkus dalam wrapper kartu clay putih beradius `24px` dengan avatar bundar berbezel timbul.
- [ ] **Step 4: Redesign Tombol Aksi & Kontrol (1-Click Convert, Export CSV, Attendance BAP Sheet)**
  Tombol-tombol operasional dengan efek tactile 3D yang responsif saat diklik.
- [ ] **Step 5: Jalankan pengujian admin**
  Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=MemberAdminTest`
  Expected: PASS

---

### Task 7: Full Test Suite & Visual Verification

**Files:**
- Test: Seluruh unit & feature tests di `tests/Feature/*`

- [ ] **Step 1: Jalankan seluruh test suite lengkap**
  Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test`
  Expected: PASS (Semua 60 tests / 273 assertions lulus 100%)
- [ ] **Step 2: Verifikasi visual web melalui peramban**
  Periksa portal publik (`/`), portal mahasiswa (`/student/dashboard`), dan panel admin (`/admin`).
- [ ] **Step 3: Update task tracking artifact**
  Tandai semua tahapan selesai di `task.md`.
