# Member Management & Attendance Ecosystem Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun modul manajemen Anggota UKM resmi, sistem absensi presensi kegiatan berbasis checklist admin, konversi 1-klik pendaftar rekrutmen diterima menjadi anggota, dan polesan antarmuka admin bebas kaku.

**Architecture:** Laravel MVC berarsitektur bersih (Migrations, Eloquent Models dengan relasi terpusat, Admin Controllers dengan hak akses berjenjang per divisi/super admin, Blade Views berestetika modern dengan micro-interactions JavaScript ringan tanpa dependensi eksternal berat).

**Tech Stack:** PHP 8.1, Laravel 10, MySQL 8, Vanilla CSS (Design Tokens Portal), JavaScript ES6.

**Spec:** `docs/superpowers/specs/2026-10-06-member-and-attendance-ecosystem-design.md`

## Global Constraints
- PHP CLI Path: `E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe`
- Semua pengujian dijalankan via `artisan test` dan wajib hijau (0 failure).
- Mengikuti hak akses yang ada: Super admin mengelola semua divisi; Division admin hanya mengelola divisi miliknya.
- Desain antarmuka konsisten dengan design system di `public/css/portal.css` (bento grid, dark-glass headers, status badges warna-warni).

---

### Task 1: Database Migrations & Eloquent Models

**Files:**
- Create: `database/migrations/2026_10_06_000001_create_members_table.php`
- Create: `database/migrations/2026_10_06_000002_create_attendance_sessions_table.php`
- Create: `database/migrations/2026_10_06_000003_create_attendance_logs_table.php`
- Create: `app/Models/Member.php`
- Create: `app/Models/AttendanceSession.php`
- Create: `app/Models/AttendanceLog.php`
- Modify: `app/Models/Division.php`
- Modify: `app/Models/Recruitment.php`
- Test: `tests/Feature/MemberAttendanceModelTest.php`

**Interfaces:**
- Produces: `Member`, `AttendanceSession`, `AttendanceLog` models with relations:
  - `Member::division()`, `Member::recruitment()`, `Member::attendanceLogs()`
  - `AttendanceSession::division()`, `AttendanceSession::creator()`, `AttendanceSession::logs()`
  - `AttendanceLog::session()`, `AttendanceLog::member()`

- [ ] **Step 1: Write test for models and relationships**
Create `tests/Feature/MemberAttendanceModelTest.php` to verify creating members, attendance sessions, and attendance logs along with relationships.

- [ ] **Step 2: Run test to verify it fails**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=MemberAttendanceModelTest`
Expected: FAIL (tables/classes not found).

- [ ] **Step 3: Create migrations and models**
Implement the 3 migration files and 3 models, plus relationship methods in `Division` and `Recruitment`. Run `artisan migrate`.

- [ ] **Step 4: Run test to verify it passes**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=MemberAttendanceModelTest`
Expected: PASS.

- [ ] **Step 5: Commit**
`git add database/migrations app/Models tests/Feature/MemberAttendanceModelTest.php`
`git commit -m "feat(models): add members and attendance database schema and eloquent models"`

---

### Task 2: Member Management Admin CMS

**Files:**
- Create: `app/Http/Controllers/Admin/MemberAdminController.php`
- Create: `resources/views/admin/members/index.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/MemberAdminTest.php`

**Interfaces:**
- Produces:
  - Route: `GET /admin/members` -> `MemberAdminController@index`
  - Route: `POST /admin/members` -> `MemberAdminController@store`
  - Route: `PUT /admin/members/{member}/status` -> `MemberAdminController@updateStatus`
  - Route: `DELETE /admin/members/{member}` -> `MemberAdminController@destroy`

- [ ] **Step 1: Write test for MemberAdminController**
Create `tests/Feature/MemberAdminTest.php` covering listing, filtering by division and status, store manual member, update status, and delete member.

- [ ] **Step 2: Run test to verify it fails**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=MemberAdminTest`
Expected: FAIL (routes/controller not found).

- [ ] **Step 3: Implement MemberAdminController and Blade View**
Create controller handling index, store, updateStatus, and destroy. Create modern `resources/views/admin/members/index.blade.php` with stats strip, search filter, responsive table, add modal, and delete confirmation modal. Register routes in `routes/web.php`.

- [ ] **Step 4: Run test to verify it passes**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=MemberAdminTest`
Expected: PASS.

- [ ] **Step 5: Commit**
`git add app/Http/Controllers/Admin/MemberAdminController.php resources/views/admin/members/ routes/web.php tests/Feature/MemberAdminTest.php`
`git commit -m "feat(admin): implement member management controller and views"`

---

### Task 3: 1-Click Convert Accepted Recruitment into Member

**Files:**
- Modify: `app/Http/Controllers/Admin/RecruitmentAdminController.php`
- Modify: `resources/views/admin/recruitment/show.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/RecruitmentConversionTest.php`

**Interfaces:**
- Produces:
  - Route: `POST /admin/recruitment/{recruitment}/convert-to-member` -> `RecruitmentAdminController@convertToMember`

- [ ] **Step 1: Write test for conversion workflow**
Create `tests/Feature/RecruitmentConversionTest.php` verifying that an accepted applicant can be converted into an official active member without duplicate entry.

- [ ] **Step 2: Run test to verify it fails**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=RecruitmentConversionTest`
Expected: FAIL.

- [ ] **Step 3: Implement convertToMember method and UI Button**
Add `convertToMember` in `RecruitmentAdminController` with duplicate check by NIM. In `resources/views/admin/recruitment/show.blade.php`, render the button **"Jadikan Anggota UKM"** when status is accepted (or display badge "Sudah Terdaftar Sebagai Anggota" jika sudah dikonversi).

- [ ] **Step 4: Run test to verify it passes**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=RecruitmentConversionTest`
Expected: PASS.

- [ ] **Step 5: Commit**
`git add app/Http/Controllers/Admin/RecruitmentAdminController.php resources/views/admin/recruitment/show.blade.php routes/web.php tests/Feature/RecruitmentConversionTest.php`
`git commit -m "feat(recruitment): add 1-click conversion from accepted applicant to official member"`

---

### Task 4: Attendance Session & Checklist Admin Module

**Files:**
- Create: `app/Http/Controllers/Admin/AttendanceAdminController.php`
- Create: `resources/views/admin/attendance/index.blade.php`
- Create: `resources/views/admin/attendance/create.blade.php`
- Create: `resources/views/admin/attendance/show.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/AttendanceAdminTest.php`

**Interfaces:**
- Produces:
  - Route: `GET /admin/attendance` -> `AttendanceAdminController@index`
  - Route: `GET /admin/attendance/create` -> `AttendanceAdminController@create`
  - Route: `POST /admin/attendance` -> `AttendanceAdminController@store`
  - Route: `GET /admin/attendance/{session}` -> `AttendanceAdminController@show`
  - Route: `PUT /admin/attendance/{session}/logs` -> `AttendanceAdminController@updateLogs`
  - Route: `DELETE /admin/attendance/{session}` -> `AttendanceAdminController@destroy`

- [ ] **Step 1: Write test for AttendanceAdminController**
Create `tests/Feature/AttendanceAdminTest.php` testing creating a session, auto-populating member attendance logs, batch updating attendance statuses (hadir/izin/sakit/alpa), and deleting a session.

- [ ] **Step 2: Run test to verify it fails**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=AttendanceAdminTest`
Expected: FAIL.

- [ ] **Step 3: Implement AttendanceAdminController and views**
Build the controller logic and 3 Blade views:
- `index.blade.php`: List sessions, attendance percentage badges, action buttons.
- `create.blade.php`: Modal or page to create session with date, time, location, division selector.
- `show.blade.php`: Interactive attendance checklist sheet with "Tandai Semua Hadir" JS button, status pill radios, notes, and live recap counters.

- [ ] **Step 4: Run test to verify it passes**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=AttendanceAdminTest`
Expected: PASS.

- [ ] **Step 5: Commit**
`git add app/Http/Controllers/Admin/AttendanceAdminController.php resources/views/admin/attendance/ routes/web.php tests/Feature/AttendanceAdminTest.php`
`git commit -m "feat(attendance): implement session creation and interactive attendance checklist sheet"`

---

### Task 5: Admin UI/UX Polish & Navigation Integration

**Files:**
- Modify: `resources/views/admin/layouts/app.blade.php`
- Modify: `resources/views/admin/dashboard.blade.php`
- Modify: `public/css/portal.css`

**Interfaces:**
- Consumes: `members` and `attendance` routes
- Produces: Polished admin navigation sidebar, dashboard quick widget counters for active members and attendance stats.

- [ ] **Step 1: Update admin navigation in `resources/views/admin/layouts/app.blade.php`**
Add nav links with clean SVG icons for "Anggota UKM" and "Presensi / Absensi". Add subtle active indicators and badge counts.

- [ ] **Step 2: Add quick metric widgets to `resources/views/admin/dashboard.blade.php`**
Add summary card for Total Anggota Aktif and Kehadiran Sesi Terakhir.

- [ ] **Step 3: Polish interactive animations in `public/css/portal.css`**
Add smooth pulse badges, checklist row hover glow, and toast notification fade-ins.

- [ ] **Step 4: Commit**
`git add resources/views/admin/layouts/app.blade.php resources/views/admin/dashboard.blade.php public/css/portal.css`
`git commit -m "feat(ui): update admin navigation, dashboard metrics, and smooth micro-interactions"`

---

### Task 6: Full End-to-End Verification

**Files:**
- Test: Full PHPUnit test suite

- [ ] **Step 1: Seed sample members and attendance sessions**
Update `DatabaseSeeder` with realistic sample members and a sample attendance session so the UI immediately looks populated and beautiful.

- [ ] **Step 2: Run all tests**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test`
Expected: 100% green, 0 failures.

- [ ] **Step 3: Final Commit**
`git add database/seeders/DatabaseSeeder.php`
`git commit -m "chore: seed realistic sample members and attendance data for live testing"`
