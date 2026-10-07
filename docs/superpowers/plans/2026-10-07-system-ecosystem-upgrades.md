# System Ecosystem Upgrades Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menambahkan fitur presensi mandiri izin/sakit + passcode expiry lock, Berita Acara Presensi (BAP) resmi siap cetak, restrukturisasi menu sidebar admin 3 kategori, showcase pengajuan proyek mahasiswa, dan optimasi KTA CR80 print.

**Architecture:** Menggunakan arsitektur MVC Laravel 10 dengan migrasi database modular, Eloquent relations, validasi form, Blade templates dengan White Claymorphism, dan print-ready CSS untuk format BAP/KTA.

**Tech Stack:** Laravel 10, PHP 8.1+, MySQL, Blade, Vanilla CSS (White Claymorphism / Print styling), Font Awesome, PHPUnit.

**Spec:** [`docs/superpowers/specs/2026-10-07-system-ecosystem-upgrades-design.md`](file:///e:/laragon/www/ukm/docs/superpowers/specs/2026-10-07-system-ecosystem-upgrades-design.md)

## Global Constraints
- Menggunakan skema Tailwind-free / Vanilla CSS yang konsisten dengan desain sistem White Claymorphism yang ada di [`portal.css`](file:///e:/laragon/www/ukm/public/css/portal.css).
- Memastikan hak akses (*authorization*) tetap aman antara Super Admin, Admin Divisi, dan Anggota/Mahasiswa.
- Semua string status konsisten (`hadir`, `izin`, `sakit`, `alpa` untuk presensi; `published`, `pending_review` untuk proyek).

---

### Task 1: Database Migrations & Model Extensions

**Files:**
- Create: `database/migrations/2026_10_07_000002_add_expiry_and_attachments_to_attendance.php`
- Create: `database/migrations/2026_10_07_000003_upgrade_projects_and_create_announcements.php`
- Create: `app/Models/Announcement.php`
- Modify: `app/Models/AttendanceSession.php`
- Modify: `app/Models/AttendanceLog.php`
- Modify: `app/Models/Project.php`

**Interfaces:**
- `AttendanceSession`: fillable `passcode_expires_at` (datetime cast), helper `isPasscodeExpired(): bool`
- `AttendanceLog`: fillable `attachment` (string)
- `Project`: fillable `user_id`, `submission_status` (`published`, `pending_review`)
- `Announcement`: fillable `division_id`, `author_id`, `title`, `content`, `category`

- [ ] **Step 1: Buat file migration `2026_10_07_000002_add_expiry_and_attachments_to_attendance.php`**
- [ ] **Step 2: Buat file migration `2026_10_07_000003_upgrade_projects_and_create_announcements.php`**
- [ ] **Step 3: Update model `AttendanceSession`, `AttendanceLog`, `Project`, dan buat model `Announcement`**
- [ ] **Step 4: Jalankan migrasi database artisan `migrate`**
- [ ] **Step 5: Commit perubahan**
  ```bash
  git add database/migrations/ app/Models/
  git commit -m "feat: add schema migrations for attendance expiry, announcements, and project submissions"
  ```

---

### Task 2: Passcode Expiry Lock & Form Pengajuan Izin/Sakit Mahasiswa

**Files:**
- Modify: `app/Http/Controllers/Student/StudentDashboardController.php`
- Modify: `app/Http/Controllers/Admin/AttendanceAdminController.php`
- Modify: `resources/views/student/presensi.blade.php`
- Modify: `resources/views/admin/attendance/create.blade.php`
- Modify: `resources/views/admin/attendance/show.blade.php`
- Modify: `routes/web.php`
- Modify: `tests/Feature/AttendancePasscodeAndSelfCheckinTest.php`

**Interfaces:**
- Consumes: `AttendanceSession::passcode_expires_at`, `AttendanceLog::attachment`
- Produces: Route `POST /student/presensi/permission` (`student.presensi.permission`)

- [ ] **Step 1: Tambahkan test failing untuk passcode expiry & form pengajuan izin/sakit di `AttendancePasscodeAndSelfCheckinTest.php`**
- [ ] **Step 2: Update `AttendanceAdminController` untuk menangani `passcode_expires_at` / durasi aktif passcode**
- [ ] **Step 3: Update `StudentDashboardController::selfCheckin` untuk memvalidasi batas waktu `passcode_expires_at`**
- [ ] **Step 4: Tambahkan method `submitPermission` di `StudentDashboardController` untuk menangani izin/sakit mandiri**
- [ ] **Step 5: Perbarui view `student/presensi.blade.php` dengan form modal izin/sakit dan informasi timer/kadaluarsa passcode**
- [ ] **Step 6: Perbarui view admin `show.blade.php` untuk menampilkan bukti lampiran (attachment modal/link)**
- [ ] **Step 7: Jalankan test feature dan verifikasi lolos (PASS)**
- [ ] **Step 8: Commit perubahan**
  ```bash
  git add app/Http/Controllers/ resources/views/ routes/web.php tests/Feature/
  git commit -m "feat: implement passcode expiry lock and student permission submission with attachment"
  ```

---

### Task 3: Berita Acara Presensi (BAP) Format Cetak Resmi

**Files:**
- Modify: `app/Http/Controllers/Admin/AttendanceAdminController.php`
- Create: `resources/views/admin/attendance/bap.blade.php`
- Modify: `resources/views/admin/attendance/show.blade.php`
- Modify: `resources/views/admin/attendance/index.blade.php`
- Modify: `routes/web.php`

**Interfaces:**
- Produces: Route `GET /admin/attendance/{session}/bap` (`admin.attendance.bap`)
- View: `resources/views/admin/attendance/bap.blade.php` dengan kop surat formal, rekap kehadiran, capaian topik, dan lembar tanda tangan pengurus & pembina.

- [ ] **Step 1: Daftarkan route `admin.attendance.bap` di `routes/web.php`**
- [ ] **Step 2: Tambahkan method `bap(AttendanceSession $session)` di `AttendanceAdminController`**
- [ ] **Step 3: Buat view `resources/views/admin/attendance/bap.blade.php` dengan layout kertas A4 formal dan print styling**
- [ ] **Step 4: Tambahkan tombol "Cetak BAP Resmi" di halaman detail sesi admin (`show.blade.php`) dan tabel list (`index.blade.php`)**
- [ ] **Step 5: Verifikasi tampilan BAP cetak melalui browser/curl**
- [ ] **Step 6: Commit perubahan**
  ```bash
  git add app/Http/Controllers/Admin/ resources/views/admin/attendance/ routes/web.php
  git commit -m "feat: add formal BAP attendance session print sheet with academic layout"
  ```

---

### Task 4: Restrukturisasi Sidebar Admin (3 Kategori) & Papan Pengumuman Divisi

**Files:**
- Modify: `resources/views/admin/layouts/app.blade.php`
- Create: `app/Http/Controllers/Admin/AnnouncementAdminController.php`
- Create: `resources/views/admin/announcements/index.blade.php`
- Modify: `app/Http/Controllers/Student/StudentDashboardController.php`
- Modify: `resources/views/student/dashboard.blade.php`
- Modify: `routes/web.php`

**Interfaces:**
- Produces: Kelompok navigasi rapi di sidebar admin:
  1. *Keanggotaan & Akademik* (Dashboard, Recruitment, Members, Attendance, Announcements)
  2. *Publikasi & Portofolio* (Posts, Projects, Events, Galleries)
  3. *Organisasi & Pengaturan* (Divisions, Officers, Certificates, Recruitment Settings)
- Produces: Banner kartu pengumuman divisi aktif di dashboard mahasiswa.

- [ ] **Step 1: Restrukturisasi menu sidebar di `resources/views/admin/layouts/app.blade.php`**
- [ ] **Step 2: Buat Controller dan View CRUD pengumuman `AnnouncementAdminController`**
- [ ] **Step 3: Daftarkan route pengumuman admin di `routes/web.php`**
- [ ] **Step 4: Tampilkan pengumuman divisi terbaru di `StudentDashboardController` dan `student/dashboard.blade.php`**
- [ ] **Step 5: Commit perubahan**
  ```bash
  git add resources/views/admin/layouts/ app/Http/Controllers/ resources/views/ routes/web.php
  git commit -m "feat: categorize admin sidebar into 3 sections and add division announcement board"
  ```

---

### Task 5: Showcase Proyek Mahasiswa (Submit Karya Mandiri)

**Files:**
- Create: `app/Http/Controllers/Student/StudentProjectController.php`
- Create: `resources/views/student/projects/index.blade.php`
- Create: `resources/views/student/projects/create.blade.php`
- Modify: `app/Http/Controllers/Admin/ProjectAdminController.php`
- Modify: `resources/views/admin/projects/index.blade.php`
- Modify: `resources/views/student/layouts/app.blade.php`
- Modify: `routes/web.php`

**Interfaces:**
- Produces: Rute `student.projects.index`, `student.projects.create`, `student.projects.store`
- Moderator role: Admin divisi / Super Admin bisa meng-approve / mempublikasikan karya mahasiswa ke katalog publik `/proyek`.

- [ ] **Step 1: Buat `StudentProjectController` untuk mahasiswa submit proyek mereka sendiri**
- [ ] **Step 2: Buat view `resources/views/student/projects/index.blade.php` dan `create.blade.php`**
- [ ] **Step 3: Update `ProjectAdminController` untuk menampilkan status moderasi dan tombol "Publish/Approve"**
- [ ] **Step 4: Tambahkan menu "Karya & Proyek Saya" di sidebar portal mahasiswa `student/layouts/app.blade.php`**
- [ ] **Step 5: Daftarkan rute mahasiswa di `routes/web.php`**
- [ ] **Step 6: Commit perubahan**
  ```bash
  git add app/Http/Controllers/ resources/views/student/ routes/web.php
  git commit -m "feat: enable student project showcase submissions and admin approval workflow"
  ```

---

### Task 6: Peningkatan Cetak Fisik KTA (CR80 Standard Print) & High-Res Card Download

**Files:**
- Modify: `resources/views/student/kta.blade.php`

**Interfaces:**
- Menambahkan media query `@media print` dengan ukuran baku kartu identitas CR80 (85.6mm x 54mm) orientasi landscape tanpa margin header/footer browser.
- Menambahkan tombol unduh Kartu KTA format gambar (canvas rendering) yang siap disimpan di galeri smartphone mahasiswa.

- [ ] **Step 1: Tambahkan CSS `@media print` khusus kartu CR80 pada `student/kta.blade.php`**
- [ ] **Step 2: Tambahkan fungsi JavaScript client-side export gambar HD (Canvas to PNG)**
- [ ] **Step 3: Uji visual tampilan cetak dan download KTA**
- [ ] **Step 4: Commit perubahan**
  ```bash
  git add resources/views/student/kta.blade.php
  git commit -m "feat: polish digital KTA with CR80 ID card print standard and HD image export"
  ```
