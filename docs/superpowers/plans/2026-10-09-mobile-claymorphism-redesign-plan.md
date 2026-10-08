# Mobile Responsive White Claymorphism Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menjadikan seluruh antarmuka web UKM Ilmu Komputer UBBG (Portal Publik, Portal Mahasiswa, dan Admin CMS) 100% responsif dan ergonomis di layar ponsel (HP) dan desktop dengan mempertahankan desain bersih **White Claymorphism**.

**Architecture:** Memperkuat token CSS responsif di `portal.css` dengan fluid typography, GPU-friendly mobile dual-shadows, dan utilitas Card Stack, lalu merefaktor ketiga sub-sistem (Publik, Mahasiswa & KTA Card, Admin CMS) agar memiliki drawer off-canvas yang mulus dan komponen yang proporsional di viewport 360px–1440px.

**Tech Stack:** Laravel 10.x, Vanilla CSS (White Claymorphism Design System), FontAwesome 6, Google Fonts (*Plus Jakarta Sans*, *JetBrains Mono*), html2canvas, jsPDF.

**Spec:** [docs/superpowers/specs/2026-10-09-mobile-claymorphism-redesign-design.md](file:///e:/laragon/www/ukm/docs/superpowers/specs/2026-10-09-mobile-claymorphism-redesign-design.md)

## Global Constraints
- Tetap menggunakan konsep White Claymorphism (warna dasar `#eef3f8`, surface `#ffffff`, dual outward & inset shadows lembut).
- Touch target minimum `44px x 44px` untuk elemen interaktif di ponsel.
- Bebas horizontal window overflow (`overflow-x: hidden` terkendali, tidak ada scrolling horizontal yang tidak disengaja di HP 360px).
- KTA Digital tetap mempertahankan rasio standar fisik ISO ID-1 CR80 (85.6mm x 53.98mm) dan ekspor PDF 2 halaman tetap tajam.
- Data table di HP berganti menjadi Claymorphic Card Stack yang mudah dibaca dan di-tap.

---

### Task 1: Fondasi Token CSS Responsif & Card Stack Utility di `portal.css`

**Files:**
- Modify: `public/css/portal.css`

**Interfaces:**
- Produces: CSS variables `--clay-card-sm`, `--clay-pill-sm`, fluid typography classes, mobile table utilities (`.table-responsive-cards`, `.mobile-only`, `.desktop-only`), and touch targets.

- [ ] **Step 1: Tambahkan Media Queries Mobile & Token Clay Ringan di `public/css/portal.css`**
  Tambahkan variabel mobile di `@media (max-width: 640px)` untuk bayangan clay yang ringan dan fluid font scaling.
- [ ] **Step 2: Tambahkan Utilitas Responsive Card Stack di `public/css/portal.css`**
  Buat class `.table-responsive-card` dan aturan `@media (max-width: 768px)` di mana elemen `tr` berubah menjadi `block card` dengan `td` fleksibel berlabel `data-label`.
- [ ] **Step 3: Tambahkan Aturan Anti-Zoom Input & Safe Area di `portal.css`**
  Pastikan input form memiliki `font-size: 16px` pada mobile dan container layout memiliki padding safe-area.
- [ ] **Step 4: Verifikasi integritas sintaks CSS**
  Validasi bahwa tidak ada syntax error pada `portal.css`.
- [ ] **Step 5: Commit perubahan Task 1**
  ```bash
  git add public/css/portal.css
  git commit -m "feat(css): add mobile responsive tokens, fluid typography, and card stack utility"
  ```

---

### Task 2: Refaktor Responsif Portal Publik (`layouts.app` & Public Pages)

**Files:**
- Modify: `resources/views/layouts/navbar.blade.php`
- Modify: `resources/views/layouts/footer.blade.php`
- Modify: `resources/views/home/index.blade.php`
- Modify: `resources/views/recruitment/index.blade.php`
- Modify: `resources/views/projects/index.blade.php`
- Modify: `resources/views/certificates/verify.blade.php`

**Interfaces:**
- Consumes: Token mobile dari `portal.css`.
- Produces: Seamless mobile drawer sheet, hero vertikal proporsional, compact wizard pendaftaran, dan carousel filter kategori.

- [ ] **Step 1: Perbarui Mobile Drawer di `resources/views/layouts/navbar.blade.php`**
  Pastikan mobile drawer sheet memiliki animasi transisi empuk, tombol menu lengkap, dan backdrop click-to-close.
- [ ] **Step 2: Perbarui Hero Section & Bento Grid di `resources/views/home/index.blade.php`**
  Sesuaikan `.hero-split-layout` agar logo dan teks tersusun vertikal yang seimbang di layar `<= 900px`, serta stat strip 2x2.
- [ ] **Step 3: Perbarui Stepper Wizard di `resources/views/recruitment/index.blade.php`**
  Ubah stepper bar menjadi compact step indicator di mobile dengan touch targets besar untuk tombol Next/Prev.
- [ ] **Step 4: Perbarui Filter Chips & Grid di `resources/views/projects/index.blade.php`**
  Buat toolbar filter divisi scrollable secara horizontal (`overflow-x: auto; white-space: nowrap`) dan kartu proyek single column.
- [ ] **Step 5: Perbarui Verifikasi Sertifikat & Footer di `certificates/verify.blade.php` & `layouts/footer.blade.php`**
  Pastikan box input verifikasi full-width dan kolom footer tersusun rapi di ponsel.
- [ ] **Step 6: Commit perubahan Task 2**
  ```bash
  git add resources/views/layouts/ resources/views/home/ resources/views/recruitment/ resources/views/projects/ resources/views/certificates/
  git commit -m "feat(public): make public portal navbar, hero, wizard, and projects fully mobile responsive"
  ```

---

### Task 3: Refaktor Portal Mahasiswa (Layout Workspace, Dashboard & Silabus)

**Files:**
- Modify: `resources/views/student/layouts/app.blade.php`
- Modify: `resources/views/student/dashboard.blade.php`
- Modify: `resources/views/student/profile.blade.php`
- Modify: `resources/views/student/silabus.blade.php`

**Interfaces:**
- Consumes: Mobile tokens & layout grid.
- Produces: Off-canvas sidebar dengan hamburger toggle + backdrop blur, 2x2 metric cards, stacked profile view.

- [ ] **Step 1: Implementasikan Off-Canvas Drawer di `resources/views/student/layouts/app.blade.php`**
  Pada layar `< 1024px`, sidebar disembunyikan. Tambahkan mobile top bar dengan tombol toggle hamburger, judul halaman, dan backdrop overlay.
- [ ] **Step 2: Optimasi Dashboard Mahasiswa di `resources/views/student/dashboard.blade.php`**
  Susun kartu sambutan, status seleksi stepper, dan metrik kartu agar pas di layar 360px tanpa meluber.
- [ ] **Step 3: Optimasi Pengaturan Profil di `resources/views/student/profile.blade.php`**
  Ubah grid 2-kolom profil menjadi 1-kolom di mobile, avatar preview di tengah, input field ergonomis.
- [ ] **Step 4: Optimasi Silabus di `resources/views/student/silabus.blade.php`**
  Ubah modul kurikulum dan info dosen pembina/ketua divisi menjadi susunan vertikal yang rapi.
- [ ] **Step 5: Commit perubahan Task 3**
  ```bash
  git add resources/views/student/layouts/ resources/views/student/dashboard.blade.php resources/views/student/profile.blade.php resources/views/student/silabus.blade.php
  git commit -m "feat(student): implement off-canvas navigation and responsive student dashboard & syllabus"
  ```

---

### Task 4: Proportional Responsive Scaler KTA Digital Mahasiswa

**Files:**
- Modify: `resources/views/student/kta.blade.php`

**Interfaces:**
- Consumes: KTA Card container.
- Produces: Responsive scaled KTA view di HP (tanpa horizontal overflow, proporsi ISO ID-1 tetap presisi, flip 3D lancar, export PDF/PNG tetap resolusi tinggi).

- [ ] **Step 1: Bungkus Kartu KTA dalam Responsive Scaler Container**
  Buat `.kta-scaler-outer` dan `.kta-scaler-inner` yang otomatis menghitung skala `transform: scale(...)` atau fluid CSS scaling pada layar `< 768px`.
- [ ] **Step 2: Tata Ulang Tombol Aksi KTA (Tab Switcher, Balik 3D, Unduh PDF/PNG)**
  Susun tombol aksi agar menjadi grid 2-kolom atau baris taktil penuh yang mudah ditekan jempol di HP.
- [ ] **Step 3: Pastikan Kompatibilitas `html2canvas` & `jsPDF`**
  Pastikan saat pengguna mengklik "Unduh PDF" atau "Unduh PNG", elemen asli tetap di-render pada resolusi skala 1 (High DPI) tanpa terdistorsi oleh scaling layar ponsel.
- [ ] **Step 4: Commit perubahan Task 4**
  ```bash
  git add resources/views/student/kta.blade.php
  git commit -m "feat(kta): add responsive scaler for digital KTA card on mobile viewports"
  ```

---

### Task 5: Presensi Mandiri & Responsive Card Stack Riwayat Presensi Mahasiswa

**Files:**
- Modify: `resources/views/student/presensi.blade.php`

**Interfaces:**
- Consumes: Card stack utility dan passcode boxes.
- Produces: 6-box passcode pas di layar 360px, kartu sesi aktif yang empuk, dan tabel riwayat presensi yang bertransformasi jadi daftar kartu clay vertikal di ponsel.

- [ ] **Step 1: Optimasi Kotak Input 6-Digit Passcode untuk Layar HP**
  Gunakan lebar fleksibel `width: clamp(38px, 12.5vw, 54px)` dan `height: clamp(48px, 15vw, 62px)` dengan `gap: 0.35rem` sehingga 6 kotak passcode muat tanpa horizontal scrollbar di layar 360px.
- [ ] **Step 2: Transformasi Tabel Riwayat Presensi ke Responsive Card Stack di HP**
  Ubah baris tabel di layar `< 640px` menjadi kartu bertingkat yang menampilkan Tanggal, Waktu, Sesi, Status Hadir/Izin/Sakit/Alpa, dan tombol lampiran.
- [ ] **Step 3: Optimasi Modal Izin / Sakit untuk Layar Sentuh**
  Pastikan formulir upload surat keterangan dokter/izin nyaman diakses dan tombol submit mudah ditekan.
- [ ] **Step 4: Commit perubahan Task 5**
  ```bash
  git add resources/views/student/presensi.blade.php
  git commit -m "feat(presensi): make self-checkin passcode and attendance log table mobile responsive"
  ```

---

### Task 6: Refaktor Admin CMS Panel (Navigation, Metrik & Card Stack Data Tables)

**Files:**
- Modify: `resources/views/admin/layouts/app.blade.php`
- Modify: `resources/views/admin/dashboard.blade.php`
- Modify: `resources/views/admin/members/index.blade.php`
- Modify: `resources/views/admin/recruitment/index.blade.php`
- Modify: `resources/views/admin/attendance/index.blade.php`
- Modify: `resources/views/admin/attendance/create.blade.php`
- Modify: `resources/views/admin/users/index.blade.php`

**Interfaces:**
- Consumes: Admin sidebar dan data tables.
- Produces: Off-canvas admin drawer, metrik 2-kolom / 1-kolom, quick action swipe bar, dan card stack untuk tabel anggota, pelamar, sesi, dan pengguna.

- [ ] **Step 1: Implementasikan Admin Mobile Header & Off-Canvas Sidebar**
  Di `admin/layouts/app.blade.php`, buat header mobile dengan hamburger toggle dan backdrop blur untuk navigasi pengurus dari HP.
- [ ] **Step 2: Optimasi Dashboard Admin CMS di `admin/dashboard.blade.php`**
  Sesuaikan grid metrik 8-kartu agar beralih ke 2-kolom di tablet dan 1-kolom di HP. Buat bar Quick Action menjadi swipeable horizontal pills.
- [ ] **Step 3: Implementasikan Card Stack di Tabel Anggota & Pelamar**
  Di `admin/members/index.blade.php` dan `admin/recruitment/index.blade.php`, buat data tabel bertransformasi jadi kartu rapi di mobile lengkap dengan tombol aksi cepat (*Lihat*, *Edit*, *Status*).
- [ ] **Step 4: Implementasikan Card Stack di Tabel Presensi & Pengguna**
  Di `admin/attendance/index.blade.php` dan `admin/users/index.blade.php`, pastikan daftar sesi presensi dan akun pengguna dapat dikontrol dengan lancar dari HP.
- [ ] **Step 5: Optimasi Form Buka Sesi Presensi di `admin/attendance/create.blade.php`**
  Pastikan form input materi, tanggal, jam, dan passcode generator nyaman diisi lewat layar HP.
- [ ] **Step 6: Commit perubahan Task 6**
  ```bash
  git add resources/views/admin/
  git commit -m "feat(admin): make admin CMS layout, metric cards, and data tables mobile responsive"
  ```

---

### Task 7: Pengujian Menyeluruh (Sanity Check & Viewport Testing)

**Files:**
- Verify: Seluruh views yang telah dimodifikasi.

- [ ] **Step 1: Jalankan Blade Syntax & Asset Linting Check**
  Pastikan tidak ada tag blade yang rusak atau file CSS yang putus.
- [ ] **Step 2: Verifikasi Uji Coba Viewport Responsif di Berbagai Ukuran**
  - HP Compact (360px x 640px)
  - HP Modern (390px x 844px)
  - Tablet (768px x 1024px)
  - Desktop (1280px x 800px)
- [ ] **Step 3: Verifikasi Bebas Horizontal Scrollbar Window di Seluruh Halaman Utama**
- [ ] **Step 4: Final Commit & Dokumentasi Selesai**
  ```bash
  git commit --allow-empty -m "chore: complete mobile responsive white claymorphism redesign verification"
  ```
