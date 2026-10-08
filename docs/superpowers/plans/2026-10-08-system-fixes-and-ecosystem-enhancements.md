# Rencana Implementasi Perbaikan Sistem & Peningkatan Ekosistem UKM

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Memperbaiki seluruh bug kritis (route crash `posts.show`, double json encoding silabus), mengaktifkan modul publikasi artikel ke publik, menyinkronkan data relasi anggota, menghapus file boilerplate rusak, serta meningkatkan fitur moderasi proyek dan KTA.

**Architecture:** Memanfaatkan arsitektur MVC Laravel 10 dan Eloquent ORM. Menyelaraskan routing di `routes/web.php`, memperbaiki serialisasi array cast pada model `Division`, menyinkronkan `division_id` pada tabel `users`, serta memperkaya UI/UX Blade tanpa menambah ketergantungan library berat.

**Tech Stack:** PHP 8.1+, Laravel 10, Blade Template Engine, Vanilla CSS Design System (White Claymorphism), PHPUnit/Pest.

**Spec:** [docs/superpowers/specs/2026-10-07-system-ecosystem-upgrades-design.md](file:///e:/laragon/www/ukm/docs/superpowers/specs/2026-10-07-system-ecosystem-upgrades-design.md)

## Global Constraints
- Seluruh 93 automated tests yang sudah ada harus tetap pass (green), ditambah tests baru untuk setiap perbaikan.
- Pertahankan standar estetika White Claymorphism pada seluruh komponen UI/UX yang dimodifikasi.
- Tidak merusak isolasi keamanan antar role (`super_admin`, `division_admin`, `member`).

---

### Task 1: Perbaikan Bug `posts.show` & Pengaktifan Modul Publikasi Publik
Menghilangkan crash error 500 saat edit artikel dan mengaktifkan rute publik artikel agar tulisan admin dapat dibaca oleh publik.

**Files:**
- Modify: `routes/web.php`
- Modify: `resources/views/layouts/navbar.blade.php`
- Modify: `resources/views/home/index.blade.php`
- Modify: `tests/Feature/UkmPortalTest.php`

**Interfaces:**
- Produces: `route('posts.index')` -> `/berita`, `route('posts.show', $slug)` -> `/berita/{slug}`.

- [ ] **Step 1: Update automated test di `tests/Feature/UkmPortalTest.php` untuk memvalidasi halaman berita publik**
Ganti `test_publications_feed_and_detail_page_removed` menjadi pengujian bahwa `/berita` dan `/berita/{slug}` dapat diakses dengan status 200 OK.

- [ ] **Step 2: Jalankan test dan pastikan gagal (fail) karena rute belum aktif**
Jalankan: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=UkmPortalTest`

- [ ] **Step 3: Tambahkan rute publik artikel di `routes/web.php`**
```php
Route::get('/berita', [PostController::class, 'index'])->name('posts.index');
Route::get('/berita/{slug}', [PostController::class, 'show'])->name('posts.show');
```

- [ ] **Step 4: Tambahkan menu "Berita" di `resources/views/layouts/navbar.blade.php`**
Tambahkan item navigasi desktop dan mobile drawer agar pengunjung dapat membaca artikel.

- [ ] **Step 5: Tampilkan section artikel terbaru di `resources/views/home/index.blade.php`**
Tampilkan grid `$latestPosts` dengan kartu berdesain claymorphism yang rapi.

- [ ] **Step 6: Jalankan test dan pastikan PASS**
Jalankan: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=UkmPortalTest`

---

### Task 2: Perbaikan Serialisasi `focus_topics` Silabus & Tampilan Kurikulum
Memperbaiki double JSON encoding pada `DivisionAdminController` dan memastikan `StudentDashboardController` selalu menghasilkan array kurikulum.

**Files:**
- Modify: `app/Http/Controllers/Admin/DivisionAdminController.php`
- Modify: `app/Http/Controllers/Student/StudentDashboardController.php`
- Test: `tests/Feature/StudentMeetingMaterialReaderTest.php`

**Interfaces:**
- Consumes: `$division->focus_topics_list`
- Produces: `$syllabus` sebagai `array` valid di portal mahasiswa.

- [ ] **Step 1: Tulis automated test di `tests/Feature/StudentMeetingMaterialReaderTest.php`**
Uji skenario di mana divisi diedit oleh admin divisi, kemudian mahasiswa membuka `/student/silabus` dan seluruh topik kurikulum tetap tampil utuh (bukan string atau kosong).

- [ ] **Step 2: Jalankan test untuk memverifikasi bug terjadi atau regresi**
Jalankan: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=StudentMeetingMaterialReaderTest`

- [ ] **Step 3: Perbaiki assignment di `DivisionAdminController.php`**
Ganti:
```php
$division->focus_topics = array_values($topics);
$division->social_links = $socialLinks;
```
(Tanpa `json_encode` manual karena `$casts = ['focus_topics' => 'array']` sudah meng-encode otomatis).

- [ ] **Step 4: Perbaiki pengambilan data di `StudentDashboardController.php`**
Ganti:
```php
$syllabus = $division?->focus_topics_list ?? [];
```

- [ ] **Step 5: Jalankan test dan pastikan PASS**
Jalankan: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=StudentMeetingMaterialReaderTest`

---

### Task 3: Sinkronisasi Relasi Pengguna pada Konversi Oprec (`convertToMember`)
Memastikan kolom `division_id` pada tabel `users` ikut diperbarui saat pelamar diterima dan dikonversi menjadi anggota.

**Files:**
- Modify: `app/Http/Controllers/Admin/RecruitmentAdminController.php`
- Test: `tests/Feature/RecruitmentConversionTest.php`

- [ ] **Step 1: Tambahkan assertion pada `tests/Feature/RecruitmentConversionTest.php`**
Pastikan `$user->fresh()->division_id === $division->id`.

- [ ] **Step 2: Jalankan test untuk verifikasi kegagalan**
Jalankan: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=RecruitmentConversionTest`

- [ ] **Step 3: Perbarui `RecruitmentAdminController::convertToMember`**
Sinkronkan relasi akun user:
```php
if ($recruitment->user) {
    $recruitment->user->update([
        'division_id' => $recruitment->first_choice_division_id,
    ]);
}
```

- [ ] **Step 4: Jalankan test dan pastikan PASS**
Jalankan: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=RecruitmentConversionTest`

---

### Task 4: Perbaikan Metrik Dinamis Beranda & Pembersihan File Dead-Code
Menghapus angka 120 hardcoded pada beranda dan menghapus file boilerplate `welcome.blade.php`.

**Files:**
- Modify: `app/Http/Controllers/HomeController.php`
- Delete: `resources/views/welcome.blade.php`
- Test: `tests/Feature/HomepageExperienceTest.php`

- [ ] **Step 1: Perbarui `HomeController.php` baris 27**
Ganti `'active_members' => 120` dengan:
```php
'active_members' => \App\Models\Member::where('status', 'aktif')->count(),
```

- [ ] **Step 2: Hapus file rusak yang tidak digunakan `resources/views/welcome.blade.php`**
Hapus file tersebut dari repository.

- [ ] **Step 3: Jalankan test beranda**
Jalankan: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=HomepageExperienceTest`

---

### Task 5: Peningkatan Moderasi Karya Mahasiswa (Alasan Penolakan/Review Notes)
Menambahkan kolom catatan admin (`admin_notes`) pada karya mahasiswa agar ketika proyek ditolak/diminta revisi, mahasiswa mendapatkan umpan balik yang jelas di portalnya.

**Files:**
- Create: Migration database penambahan kolom `admin_notes` pada tabel `projects`
- Modify: `app/Models/Project.php`
- Modify: `app/Http/Controllers/Admin/ProjectAdminController.php`
- Modify: `resources/views/admin/projects/index.blade.php`
- Modify: `resources/views/student/projects/index.blade.php`
- Test: `tests/Feature/StudentProjectShowcaseAndModerationTest.php`

- [ ] **Step 1: Buat dan jalankan migrasi untuk `admin_notes` di tabel `projects`**
- [ ] **Step 2: Perbarui controller moderasi `ProjectAdminController::moderate` untuk menyimpan `admin_notes`**
- [ ] **Step 3: Tambahkan input catatan moderasi di UI Admin dan tampilkan di UI Mahasiswa**
- [ ] **Step 4: Tambahkan automated test dan verifikasi seluruh test suite berstatus PASS**

---

### Task 6: Peningkatan UI/UX KTA Digital (Download Gambar Langsung) & Filter Presensi
Menambahkan tombol download gambar KTA HD dan fitur filter bulan pada riwayat presensi mahasiswa.

**Files:**
- Modify: `resources/views/student/kta.blade.php`
- Modify: `resources/views/student/presensi.blade.php`

- [ ] **Step 1: Tambahkan skrip download gambar KTA via canvas di `student/kta.blade.php`**
- [ ] **Step 2: Tambahkan filter tab bulan pada riwayat kehadiran di `student/presensi.blade.php`**
- [ ] **Step 3: Uji visual dan pastikan responsif pada perangkat desktop maupun mobile**

---

### Task 7: Verifikasi Menyeluruh (Full Regression Test Suite)
Memastikan seluruh pengujian otomatis (semua 29 test files) berjalan sukses tanpa error.

- [ ] **Step 1: Jalankan `php artisan test` penuh**
- [ ] **Step 2: Pastikan 100% assertions lulus**
