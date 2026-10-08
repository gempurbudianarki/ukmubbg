# Industrial-Grade Recruitment Registration System Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun ulang formulir pendaftaran anggota baru (`/pendaftaran`) menjadi sistem kelas industri dengan alur 3-tahap yang tertata, validasi client-side instan tanpa jeda, arsitektur state machine modular, dan proteksi integritas data menyeluruh.

**Architecture:** Frontend digerakkan oleh State Machine tunggal di `recruitment-wizard.js` yang mengatur navigasi bertahap, pre-check ukuran & ekstensi berkas secara seketika (*zero-latency*), *mutual exclusion* pilihan divisi, dan draft autosave. View Blade dipisahkan bersih tanpa script inline monolitik, terhubung ke backend Laravel yang memvalidasi ketersediaan NIM/Email via AJAX dan memproses registrasi berintegritas tinggi.

**Tech Stack:** Laravel 10 / PHP 8.1, Vanilla JavaScript ES6+, Vanilla CSS with Claymorphism & Micro-animations, MySQL.

**Spec:** `docs/superpowers/specs/2026-10-08-industrial-recruitment-system-design.md`

## Global Constraints
- Atribut `novalidate` harus selalu aktif pada `<form>` untuk mencegah error bawaan browser `An invalid form control is not focusable`.
- Pengecekan ukuran file (Foto <= 2MB, KTM <= 2MB, CV <= 3MB) harus terjadi instan saat file dipilih/didrop di client, bukan ditunggu sampai submit akhir.
- Pas Foto resmi profil diletakkan bersama KTM dan CV di Tahap 3 (Unggah Berkas). Tahap 1 murni Identitas Diri dan Akun.
- Tidak boleh ada script JavaScript inline di dalam template Blade `resources/views/recruitment/index.blade.php`.
- Semua 96 unit & feature test yang ada harus tetap 100% lulus (green).

---

### Task 1: Audit & Refine Backend Availability API & File Handling
**Files:**
- Modify: `app/Http/Controllers/RecruitmentController.php`
- Test: `tests/Feature/RecruitmentWorkflowTest.php`

**Interfaces:**
- Produces: `GET /pendaftaran/check-availability?field={nim|email}&value={val}` returning `{ available: bool, message: string }`
- Produces: `POST /pendaftaran` handling validation, disk storage, user creation, auto login, and flash redirect.

- [x] **Step 1: Verify and run existing recruitment tests**
Run PHPUnit test to confirm baseline green state.
```powershell
& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=RecruitmentWorkflowTest
```

- [x] **Step 2: Add specific unit/feature test for availability check endpoint**
Ensure endpoint returns 200 with JSON `{ available: true }` for fresh NIM/Email, and `{ available: false }` for existing records.

- [x] **Step 3: Verify controller validation messages match industrial standard**
Ensure `RecruitmentController@store` custom messages are user-friendly and localized in Bahasa Indonesia.

- [x] **Step 4: Commit Task 1 changes**
```powershell
git commit -m "feat(recruitment): ensure robust availability check and validation error handling"
```

---

### Task 2: Build Industrial State Machine in `recruitment-wizard.js`
**Files:**
- Modify: `public/js/recruitment-wizard.js`

**Interfaces:**
- Produces: `RecruitmentEngine` with:
  - `updateStepUI(step)`
  - `validateStep1()`, `validateStep2()`, `validateStep3()`, `validateAllSteps()`
  - `handlePhotoSelect(input)`, `handleFileKtmSelect(input)`, `handleFileCvSelect(input)`
  - `selectPrimaryDivision(id, name)`, `selectSecondaryDivision(id)`, `clearSecondaryDivision()`
  - `initDropzones()` with drag-and-drop file transfer
  - `saveDraftToStorage()`, `checkExistingDraft()`, `clearDraftAndReset()`

- [x] **Step 1: Implement Zero-Latency Instant File Size & Type Inspector**
Check file size against `MAX_PHOTO_BYTES = 2MB`, `MAX_KTM_BYTES = 2MB`, and `MAX_CV_BYTES = 3MB` immediately on `change` and `drop`. Clear input, trigger shake animation, and set distinct error label if exceeded.

- [x] **Step 2: Implement Division Mutual Exclusion Algorithm**
When Primary Division is selected:
- Automatically remove primary division from secondary if previously matching.
- Add `.disabled` state to corresponding secondary pill so duplicate cannot be selected.
- Update live summary in Step 3.

- [x] **Step 3: Implement Debounced Real-Time AJAX Availability Validator**
Debounce 450ms for NIM and Email input, show animated spinner, then check against `/pendaftaran/check-availability`. Toggle green checkmark or red cross with error message.

- [x] **Step 4: Implement Password Strength Meter & Confirm Matcher**
Real-time regex checking for password length, uppercase, numbers, and symbols. Live matching verification with confirmation input.

- [x] **Step 5: Implement Anti-Double Submit & Safe Submitting State**
When form is submitted, validate all steps. If valid, set `isSubmitting = true`, disable button, change text to "Mengunggah Berkas & Mendaftarkan Akun...", and show spinner.

- [x] **Step 6: Commit Task 2 changes**
```powershell
git commit -m "feat(recruitment): implement industrial state machine and instant file pipeline in recruitment-wizard.js"
```

---

### Task 3: Structure Pristine 3-Step Wizard Template in Blade
**Files:**
- Modify: `resources/views/recruitment/index.blade.php`

**Interfaces:**
- Consumes: `recruitment-wizard.js` asset script and `portal.css`
- Produces: Clean semantic HTML without inline scripts, containing:
  - `stepSection1`: Identitas Diri (Nama, NIM, Semester, WA, Kelas, Email, Kata Sandi).
  - `stepSection2`: Divisi Utama (Cards) & Divisi Cadangan (Pills) & Motivasi.
  - `stepSection3`: Pas Foto, KTM, CV Dropzones + Live Summary + Persetujuan + Submit.

- [x] **Step 1: Verify Tahap 1 has no file dropzones**
Ensure all personal data fields have proper IDs, accessibility labels, and feedback containers.

- [x] **Step 2: Verify Tahap 2 has tactile division cards and secondary pills**
Ensure each card has `divCardPrimary_{id}` and pills have `divPillSecondary_{id}` with mutual exclusion bindings.

- [x] **Step 3: Verify Tahap 3 contains all 3 document dropzones in responsive grid**
Ensure `photoDropzone`, `ktmDropzone`, and `cvDropzone` are styled cleanly with error message containers `photoDropzoneError`, `ktmDropzoneError`, `cvDropzoneError`.

- [x] **Step 4: Ensure script tag cleanly calls external `recruitment-wizard.js`**
Confirm zero inline script blocks remain in `resources/views/recruitment/index.blade.php`.

- [x] **Step 5: Commit Task 3 changes**
```powershell
git commit -m "refactor(recruitment): clean up Blade template and link external wizard script"
```

---

### Task 4: Polish CSS Styles & Visual Ergonomics
**Files:**
- Modify: `public/css/portal.css`

- [x] **Step 1: Enhance dropzone visual cues**
Add hover glow, claymorphism shadows, drag-over highlight, and error shake keyframes.

- [x] **Step 2: Enhance division selector cards & secondary pills**
Provide tactile elevation (`transform: translateY(-3px)`), active blue ring, and disabled strike-through pill styling.

- [x] **Step 3: Ensure responsive layout on mobile screens**
Verify stepper collapses to compact icons and action footers stack nicely on screens < 768px.

- [x] **Step 4: Commit Task 4 changes**
```powershell
git commit -m "style(recruitment): polish claymorphism dropzones, pills, and animations"
```

---

### Task 5: Full Automated Regression Verification
**Files:**
- Run test suites across the whole application.

- [x] **Step 1: Clear application and view caches**
```powershell
& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan optimize:clear
```

- [x] **Step 2: Run complete PHPUnit test suite**
```powershell
& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test
```
Verify 98/98 tests pass.

- [x] **Step 3: Final Git status inspection & branch check**
Ensure working directory is clean and ready for user manual testing.
