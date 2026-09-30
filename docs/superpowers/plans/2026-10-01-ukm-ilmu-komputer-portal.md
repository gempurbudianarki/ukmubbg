# Web Portal & CMS UKM Ilmu Komputer Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun web portal publikasi dan CMS mandiri ala WordPress untuk UKM Ilmu Komputer dengan 4 divisi (Pemrograman, Multimedia, IoT, Cyber Security), profil biodata Pembina & Ketua, sistem Open Recruitment 1 pintu, dan panel administrasi multi-role.

**Architecture:** Arsitektur MVC Native PHP (PHP 8.1+) terstruktur bersih dengan PDO Database Helper, Front-Controller (`public/index.php`), Clean URLs via Apache `.htaccess`, multi-role authentication berbasis session, dan Clean Professional CSS Design System tanpa build tools berat agar langsung instan berjalan di Laragon.

**Tech Stack:** PHP 8.1+ / MySQL (MariaDB), Vanilla CSS modern (CSS variables, responsive grid/flexbox, Google Fonts Plus Jakarta Sans), Vanilla JS, PDO prepared statements.

**Spec:** `docs/superpowers/specs/2026-10-01-ukm-ilmu-komputer-portal-design.md`

## Global Constraints
- Target PHP: PHP 8.1+ yang berjalan di Laragon (`E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe` atau sistem).
- Database: MySQL/MariaDB Laragon (host: `127.0.0.1`, user: `root`, password: empty/default, db: `ukm_ilkom`).
- UI Style: Clean, sharp, professional tech faculty aesthetics (off-white `#f8fafc`, dark navy `#0f172a`, accent royal `#2563eb`).
- Keamanan: Wajib Prepared Statements (anti-SQLi), CSRF Token di form, XSS protection, Password Bcrypt.
- Multi-Role: Super Admin (semua akses) dan Division Admin (hanya divisi terkait).

---

### Task 1: Environment Setup, Database Schema & Migration CLI

**Files:**
- Create: `app/Config/Database.php`
- Create: `app/Config/App.php`
- Create: `database/schema.sql`
- Create: `database/migrate.php`
- Test: `tests/test_database.php`

**Interfaces:**
- Consumes: MySQL Connection via PDO
- Produces: `App\Config\Database::getConnection(): PDO`, migration script yang menginisialisasi 5 tabel (`users`, `divisions`, `posts`, `recruitments`, `settings`) dan seeder default (Super Admin, 4 Division Admins, dan data awal 4 Divisi).

- [ ] **Step 1: Write test for database configuration & connection**
Create `tests/test_database.php` to verify PDO connection and database initialization.

- [ ] **Step 2: Run test to verify initial state (fail before config/schema exist)**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" tests/test_database.php`
Expected: Output connection or file missing error.

- [ ] **Step 3: Implement Database Config, App Config, Schema SQL, and Migration Runner**
Create `app/Config/Database.php`, `app/Config/App.php`, `database/schema.sql`, and `database/migrate.php`.
Include seeder data:
- Super Admin: `admin@ilkom.ukm` / `admin123`
- Division Admins: `pemrograman@ilkom.ukm`, `multimedia@ilkom.ukm`, `iot@ilkom.ukm`, `cyber@ilkom.ukm`
- 4 Divisions with full data: Pemrograman, Multimedia, IoT, Cyber Security (complete with Pembina, Ketua, Bio, Focus Topics).

- [ ] **Step 4: Run migration and verify test passes**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" database/migrate.php`
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" tests/test_database.php`
Expected: Database migrated and test reports PASS with 5 tables and seeded rows.

- [ ] **Step 5: Commit**
```bash
git add app/Config database tests/test_database.php
git commit -m "feat: setup database config, schema, seeder and migration runner"
```

---

### Task 2: Core MVC Engine (Router, Controller, View, Helpers)

**Files:**
- Create: `app/Core/Router.php`
- Create: `app/Core/Controller.php`
- Create: `app/Core/Model.php`
- Create: `app/Helpers/Session.php`
- Create: `app/Helpers/Csrf.php`
- Create: `app/Helpers/Auth.php`
- Create: `app/Helpers/Upload.php`
- Test: `tests/test_core.php`

**Interfaces:**
- Consumes: PHP standard request and session
- Produces: `Router::get()`, `Router::post()`, `Router::dispatch()`, `Controller::view()`, `Controller::json()`, `Session::set/get/flash()`, `Csrf::generateToken()/validateToken()`, `Auth::login()/check()/user()/hasRole()`.

- [ ] **Step 1: Write test for core router and helper functionality**
Create `tests/test_core.php` testing route registration, pattern matching (`/divisi/{slug}`), CSRF generation/validation, and Session flash messages.

- [ ] **Step 2: Run test to verify it fails**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" tests/test_core.php`
Expected: Class not found failure.

- [ ] **Step 3: Implement Core MVC classes and Helpers**
Implement `Router`, `Controller`, `Model`, `Session`, `Csrf`, `Auth`, and `Upload` with robust parameter parsing, redirect methods, and MIME validation.

- [ ] **Step 4: Run test to verify it passes**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" tests/test_core.php`
Expected: PASS all core assertion checks.

- [ ] **Step 5: Commit**
```bash
git add app/Core app/Helpers tests/test_core.php
git commit -m "feat: implement core mvc routing, controller, session, and security helpers"
```

---

### Task 3: Data Models (User, Division, Post, Recruitment, Setting)

**Files:**
- Create: `app/Models/User.php`
- Create: `app/Models/Division.php`
- Create: `app/Models/Post.php`
- Create: `app/Models/Recruitment.php`
- Create: `app/Models/Setting.php`
- Test: `tests/test_models.php`

**Interfaces:**
- Consumes: `App\Core\Model` and `App\Config\Database`
- Produces:
  - `User::findByEmail()`, `User::authenticate()`, `User::allWithDivision()`
  - `Division::all()`, `Division::findBySlug()`, `Division::updateBio()`
  - `Post::getLatest()`, `Post::getByDivision()`, `Post::findBySlug()`, `Post::create()`, `Post::update()`, `Post::delete()`
  - `Recruitment::register()`, `Recruitment::findByNim()`, `Recruitment::findByCode()`, `Recruitment::filter()`, `Recruitment::updateStatus()`
  - `Setting::get()`, `Setting::set()`

- [ ] **Step 1: Write tests for all models**
Create `tests/test_models.php` verifying model queries, finding records by slug/email, creating and updating posts and recruitments.

- [ ] **Step 2: Run test to verify failure**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" tests/test_models.php`
Expected: Classes missing.

- [ ] **Step 3: Implement the 5 model classes**
Implement full CRUD methods with prepared statements and return arrays/objects.

- [ ] **Step 4: Run test to verify pass**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" tests/test_models.php`
Expected: PASS all model queries and operations.

- [ ] **Step 5: Commit**
```bash
git add app/Models tests/test_models.php
git commit -m "feat: implement data models for users, divisions, posts, recruitments, and settings"
```

---

### Task 4: Public Design System & Responsive Layout

**Files:**
- Create: `public/assets/css/style.css`
- Create: `public/assets/js/main.js`
- Create: `app/Views/layouts/header.php`
- Create: `app/Views/layouts/navbar.php`
- Create: `app/Views/layouts/footer.php`
- Create: `public/.htaccess`
- Create: `public/index.php`

**Interfaces:**
- Consumes: Modern CSS variables, Google Fonts (`Plus Jakarta Sans`), SVG icons
- Produces: Clean & Professional responsive layouts, navbar with active states & mobile drawer, footer, flash message toast alerts.

- [ ] **Step 1: Create front-controller and .htaccess**
Set up `public/index.php` to autoload classes and handle routing requests. Set up `public/.htaccess` and root `.htaccess` for smooth Laragon URL rewriting.

- [ ] **Step 2: Build CSS Design System in `public/assets/css/style.css`**
Define clean design tokens:
- Primary colors (`--primary: #1e3a8a`, `--primary-light: #2563eb`), division colors (`--code: #0284c7`, `--media: #8b5cf6`, `--iot: #10b981`, `--cyber: #f43f5e`).
- Typography, card components, badges, buttons, forms, tables, modals, mobile responsive navigation.

- [ ] **Step 3: Create Layout Views (`header.php`, `navbar.php`, `footer.php`)**
Assemble semantic HTML5 layout with proper meta tags, mobile menu toggle in `main.js`, and toast notification renderer.

- [ ] **Step 4: Commit**
```bash
git add public/ app/Views/layouts/
git commit -m "feat: setup front controller, clean design system css, and base layouts"
```

---

### Task 5: Public Portal Pages (Home, 4 Division Channels & Bios, Publications)

**Files:**
- Create: `app/Controllers/HomeController.php`
- Create: `app/Controllers/DivisionController.php`
- Create: `app/Controllers/PostController.php`
- Create: `app/Views/home/index.php`
- Create: `app/Views/divisions/show.php`
- Create: `app/Views/posts/index.php`
- Create: `app/Views/posts/show.php`
- Test: `tests/test_public_routes.php`

**Interfaces:**
- Consumes: `Division`, `Post`, `Setting` models
- Produces:
  - Homepage (`/`): Hero, Live Recruitment Banner, 4 Division Cards with Leader Highlight, Latest News feed.
  - Division Channel (`/divisi/{slug}`): Division Banner, Pembina & Ketua Bio Cards, Focus Learning Topics, Division Posts feed.
  - Publications (`/berita` & `/berita/{slug}`): Categorized articles, search by keyword, filter by division.

- [ ] **Step 1: Write route test for public controllers**
Create `tests/test_public_routes.php` to simulate dispatching to `/`, `/divisi/pemrograman`, `/berita`, checking HTTP 200 and expected rendered keywords.

- [ ] **Step 2: Implement Controllers and Views**
Implement `HomeController`, `DivisionController`, `PostController` and corresponding view files with rich, clean styling.

- [ ] **Step 3: Run test to verify**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" tests/test_public_routes.php`
Expected: PASS.

- [ ] **Step 4: Commit**
```bash
git add app/Controllers app/Views/home app/Views/divisions app/Views/posts tests/test_public_routes.php
git commit -m "feat: implement public homepage, division channels with bios, and publications"
```

---

### Task 6: Open Recruitment System (Online Form, Ticket Generator & Status Checker)

**Files:**
- Create: `app/Controllers/RecruitmentController.php`
- Create: `app/Views/recruitment/index.php`
- Create: `app/Views/recruitment/success.php`
- Create: `app/Views/recruitment/status.php`
- Test: `tests/test_recruitment_flow.php`

**Interfaces:**
- Consumes: `Recruitment`, `Division`, `Setting` models
- Produces:
  - `/pendaftaran`: Form 1 pintu pendaftaran (NIM, Nama, Email, WA, Semester, Kelas, Pilihan Divisi 1 & 2, Motivasi, Portofolio).
  - `/pendaftaran/sukses`: Kartu bukti registrasi online dengan kode pendaftaran unik (`UKM-2026-XXXX`).
  - `/pendaftaran/cek-status`: Form cek status seleksi calon anggota dengan input NIM / kode pendaftaran.

- [ ] **Step 1: Write test for recruitment registration and verification**
Create `tests/test_recruitment_flow.php` testing validation rules, registration creation, and NIM status lookup.

- [ ] **Step 2: Implement `RecruitmentController` and Views**
Build controller logic with validation (check if recruitment is currently open, validate active NIM), generate unique code, and display status timeline (*Pending / Wawancara / Diterima / Ditolak*).

- [ ] **Step 3: Run test to verify**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" tests/test_recruitment_flow.php`
Expected: PASS.

- [ ] **Step 4: Commit**
```bash
git add app/Controllers/RecruitmentController.php app/Views/recruitment tests/test_recruitment_flow.php
git commit -m "feat: implement 1-door open recruitment system, ticket generator, and status checker"
```

---

### Task 7: Admin Authentication & Multi-Role CMS Layout

**Files:**
- Create: `app/Controllers/AuthController.php`
- Create: `app/Controllers/Admin/DashboardController.php`
- Create: `app/Views/admin/layouts/header.php`
- Create: `app/Views/admin/layouts/sidebar.php`
- Create: `app/Views/admin/layouts/footer.php`
- Create: `app/Views/admin/login.php`
- Create: `app/Views/admin/dashboard.php`
- Test: `tests/test_admin_auth.php`

**Interfaces:**
- Consumes: `User`, `Auth`, `Session`, `Post`, `Recruitment`
- Produces:
  - `/admin/login`: Secure login page with error handling.
  - `/admin/dashboard`: Metrics cards (total applicants, per division counts, total posts, quick action recruitment toggle).
  - Role separation: Super Admin sees all data; Division Admin sees their division metrics only.

- [ ] **Step 1: Write test for admin auth and permission gating**
Create `tests/test_admin_auth.php` to test credential check, role resolution, and unauthorized redirect.

- [ ] **Step 2: Implement AuthController, DashboardController, and Admin Layout Views**
Implement login/logout, CSRF-protected authentication, professional admin sidebar with badges, and overview analytics widgets.

- [ ] **Step 3: Run test to verify**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" tests/test_admin_auth.php`
Expected: PASS.

- [ ] **Step 4: Commit**
```bash
git add app/Controllers/AuthController.php app/Controllers/Admin/DashboardController.php app/Views/admin tests/test_admin_auth.php
git commit -m "feat: implement admin authentication and multi-role cms dashboard"
```

---

### Task 8: CMS Post Management (WordPress-Style Editor & Media Upload)

**Files:**
- Create: `app/Controllers/Admin/PostAdminController.php`
- Create: `app/Views/admin/posts/index.php`
- Create: `app/Views/admin/posts/create.php`
- Create: `app/Views/admin/posts/edit.php`
- Test: `tests/test_post_management.php`

**Interfaces:**
- Consumes: `Post`, `Division`, `Upload`
- Produces: Complete CRUD for articles/publications.
  - Super Admin can manage all posts and assign to any division.
  - Division Admin automatically posts under their own division.
  - Image upload with thumbnail generator and preview.
  - Draft vs Published toggle.

- [ ] **Step 1: Write test for Post CRUD and division filtering**
Create `tests/test_post_management.php` verifying post creation, thumbnail upload simulation, update, and division restriction.

- [ ] **Step 2: Implement `PostAdminController` and Views**
Build clean WordPress-style post management with rich formatting toolbar, image preview, category selector, and table search.

- [ ] **Step 3: Run test to verify**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" tests/test_post_management.php`
Expected: PASS.

- [ ] **Step 4: Commit**
```bash
git add app/Controllers/Admin/PostAdminController.php app/Views/admin/posts tests/test_post_management.php
git commit -m "feat: implement cms post publishing, editing, and media upload management"
```

---

### Task 9: CMS Division Bio & Profile Management

**Files:**
- Create: `app/Controllers/Admin/DivisionAdminController.php`
- Create: `app/Views/admin/divisions/index.php`
- Create: `app/Views/admin/divisions/edit.php`
- Test: `tests/test_division_management.php`

**Interfaces:**
- Consumes: `Division`, `Upload`, `Auth`
- Produces:
  - Form edit biodata Pembina (Nama, Gelar, Foto) dan Ketua Divisi (Nama, NIM, Bio Sambutan, Foto, Media Sosial).
  - Edit Visi, Misi, Deskripsi, dan Fokus Keahlian Divisi.
  - Division Admin can only edit their own division's bio; Super Admin can edit all 4 divisions.

- [ ] **Step 1: Write test for division bio update and role restriction**
Create `tests/test_division_management.php` checking permissions and profile updates.

- [ ] **Step 2: Implement `DivisionAdminController` and Views**
Build photo upload preview for Pembina & Ketua, structured social media links inputs, and focus topics tag inputs.

- [ ] **Step 3: Run test to verify**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" tests/test_division_management.php`
Expected: PASS.

- [ ] **Step 4: Commit**
```bash
git add app/Controllers/Admin/DivisionAdminController.php app/Views/admin/divisions tests/test_division_management.php
git commit -m "feat: implement cms division bio, leader profile, and adviser management"
```

---

### Task 10: CMS Recruitment Hub & Applicant Management (Status & CSV Export)

**Files:**
- Create: `app/Controllers/Admin/RecruitmentAdminController.php`
- Create: `app/Views/admin/recruitment/index.php`
- Create: `app/Views/admin/recruitment/show.php`
- Test: `tests/test_recruitment_admin.php`

**Interfaces:**
- Consumes: `Recruitment`, `Division`, `Setting`
- Produces:
  - Recruitment Hub table with filtering by Division, Semester, and Status.
  - Direct WhatsApp Contact button (`https://wa.me/62...`).
  - Applicant detail modal/view with interview notes and status selector (`pending`, `interview`, `accepted`, `rejected`).
  - Export all or filtered applicants to CSV / Excel spreadsheet (`/admin/recruitment/export`).
  - Open/Close recruitment toggle in Settings.

- [ ] **Step 1: Write test for recruitment admin actions and CSV export**
Create `tests/test_recruitment_admin.php` testing status update, notes persistence, and CSV export header/content generation.

- [ ] **Step 2: Implement `RecruitmentAdminController` and Views**
Build interactive applicants table, status switcher, WhatsApp quick action, and CSV export stream.

- [ ] **Step 3: Run test to verify**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" tests/test_recruitment_admin.php`
Expected: PASS.

- [ ] **Step 4: Commit**
```bash
git add app/Controllers/Admin/RecruitmentAdminController.php app/Views/admin/recruitment tests/test_recruitment_admin.php
git commit -m "feat: implement cms recruitment hub, applicant status workflow, and csv export"
```

---

### Task 11: End-to-End Verification & Browser Experience Testing

**Files:**
- Verify: Full web portal via local server / browser subagent
- Create: `tests/run_all_tests.php`

**Interfaces:**
- Consumes: All modules and routes
- Produces: Comprehensive verification report of public portal, 4 division channels, recruitment form submission, admin login, publishing, and applicant review.

- [ ] **Step 1: Create master test runner `tests/run_all_tests.php`**
Run all unit and integration test suites in a single command.

- [ ] **Step 2: Run all tests and ensure 100% PASS**
Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" tests/run_all_tests.php`
Expected: 100% passed tests across all modules.

- [ ] **Step 3: Start local PHP server and perform live verification**
Start PHP development server or test directly via Laragon, verify UI aesthetics, navigation, and recruitment flow.

- [ ] **Step 4: Final Git Commit**
```bash
git add .
git commit -m "chore: complete test suite and end-to-end verification for UKM Ilmu Komputer web portal"
```
