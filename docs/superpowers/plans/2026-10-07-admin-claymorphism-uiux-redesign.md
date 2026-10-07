# Admin White Claymorphism UI/UX Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menstandarisasi tata letak visual, proporsi kartu simetris, dan estetika White Claymorphism di seluruh 14 modul panel admin UKM CMS.

**Architecture:** Membangun utility CSS sistem Claymorphism khusus Admin di `public/css/portal.css` (kartu taktil berbayang ganda, debossed filter toolbar, grid metrik simetris), kemudian menerapkannya secara modular ke 14 view Blade di 3 kelompok navigasi (Akademik & Keanggotaan, Publikasi & Portofolio, Organisasi & Pengaturan).

**Tech Stack:** Laravel Blade, CSS3 White Claymorphism Design System (CSS Custom Properties), PHPUnit Feature Testing.

**Spec:** [`docs/superpowers/specs/2026-10-07-admin-claymorphism-uiux-redesign.md`](file:///e:/laragon/www/ukm/docs/superpowers/specs/2026-10-07-admin-claymorphism-uiux-redesign.md)

## Global Constraints

- Wajib menggunakan design token White Claymorphism yang ada (`--clay-card`, `--clay-pill`, `--clay-input`, `--radius-lg`, `--radius-md`, `--bg-body`).
- Semua kartu statistik dan form filter harus simetris (tidak boleh melebar kosong atau asimetris).
- Tanpa mengubah rute backend, controller logic, atau skema basis data yang sudah berjalan.
- Seluruh 89 automated tests (`php artisan test`) harus tetap 100% HIJAU (PASS).

---

### Task 1: Fondasi CSS Admin Claymorphism & Layout Base

**Files:**
- Modify: `public/css/portal.css`
- Modify: `resources/views/admin/layouts/app.blade.php`

**Interfaces:**
- Consumes: CSS variables `--clay-card`, `--clay-pill`, `--clay-input`, `--radius-lg`, `--radius-md`, `--slate-*`.
- Produces: Utility classes `.admin-clay-card`, `.admin-clay-card-flat`, `.admin-stat-grid`, `.admin-stat-card`, `.admin-filter-bar`, `.admin-grid-2`, `.admin-grid-3`, `.admin-grid-4`, `.admin-table-card`.

- [ ] **Step 1: Tambahkan utility CSS Admin Claymorphism di `public/css/portal.css`**
Menambahkan kelas-kelas:
```css
/* Admin Claymorphism Components */
.admin-clay-card {
    background: #ffffff;
    border-radius: var(--radius-lg);
    box-shadow: var(--clay-card);
    border: 1px solid rgba(255, 255, 255, 0.9);
    padding: 1.75rem 2rem;
    position: relative;
    transition: var(--transition);
}
.admin-clay-card-flat {
    background: #ffffff;
    border-radius: var(--radius-lg);
    box-shadow: var(--clay-card);
    border: 1px solid rgba(255, 255, 255, 0.9);
    overflow: hidden;
}
.admin-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2rem;
}
.admin-stat-card {
    background: #ffffff;
    border-radius: var(--radius-lg);
    box-shadow: var(--clay-card);
    border: 1px solid rgba(255, 255, 255, 0.9);
    padding: 1.35rem 1.6rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: var(--transition);
}
.admin-stat-card:hover {
    box-shadow: var(--clay-card-hover);
    transform: translateY(-2px);
}
.admin-filter-bar {
    background: #ffffff;
    border-radius: var(--radius-md);
    box-shadow: var(--clay-pill);
    border: 1px solid rgba(255, 255, 255, 0.9);
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    display: flex;
    gap: 0.85rem;
    flex-wrap: wrap;
    align-items: center;
}
.admin-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.75rem;
}
.admin-grid-3 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
}
.admin-grid-4 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.25rem;
}
@media (max-width: 900px) {
    .admin-grid-2 { grid-template-columns: 1fr; }
}
```

- [ ] **Step 2: Update `resources/views/admin/layouts/app.blade.php`**
Memperbarui container `.admin-content-body` agar rapi dan simetris, memastikan breadcrumb/header modul memiliki padding dan kontras yang konsisten.

- [ ] **Step 3: Verifikasi sintaks dan layout**
Jalankan pengecekan test cepat: `php artisan test --filter=SecurityAndAccessControlTest`.
Pastikan test tetap lulus.

- [ ] **Step 4: Commit**
```bash
git add public/css/portal.css resources/views/admin/layouts/app.blade.php
git commit -m "style: add admin claymorphism utility classes and update admin layout"
```

---

### Task 2: Redesain Modul Akademik & Keanggotaan (5 Views)

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php`
- Modify: `resources/views/admin/recruitment/index.blade.php`
- Modify: `resources/views/admin/members/index.blade.php`
- Modify: `resources/views/admin/attendance/index.blade.php`
- Modify: `resources/views/admin/announcements/index.blade.php`

**Interfaces:**
- Consumes: `.admin-clay-card`, `.admin-stat-grid`, `.admin-stat-card`, `.admin-filter-bar`, `.admin-grid-2`.

- [ ] **Step 1: Perbarui `resources/views/admin/dashboard.blade.php`**
Gunakan `.admin-stat-grid` untuk 6 kartu ringkasan, `.admin-clay-card` untuk kontrol Oprec Super Admin, dan `.admin-grid-2` untuk perbandingan simetris Statistik Divisi vs Pendaftar Terbaru.

- [ ] **Step 2: Perbarui `resources/views/admin/recruitment/index.blade.php`**
Tambahkan strip 4 metrik status (Total, Pending, Interview, Diterima/Ditolak), wrap form pencarian ke dalam `.admin-filter-bar`, dan bungkus tabel ke dalam `.admin-clay-card-flat` simetris.

- [ ] **Step 3: Perbarui `resources/views/admin/members/index.blade.php`**
Terapkan `.admin-stat-grid` untuk 4 kartu indikator keanggotaan (Total, Aktif, Alumni, Divisi), wrap filter toolbar ke `.admin-filter-bar`, dan ganti style tabel dengan `.admin-clay-card-flat`.

- [ ] **Step 4: Perbarui `resources/views/admin/attendance/index.blade.php`**
Standarisasi metrik sesi kehadiran ke `.admin-stat-grid`, rapikan search/filter ke `.admin-filter-bar`, dan bungkus daftar sesi presensi ke `.admin-clay-card-flat`.

- [ ] **Step 5: Perbarui `resources/views/admin/announcements/index.blade.php`**
Terapkan kartu simetris pengumuman dengan tag prioritas, target penerima, serta tombol kelola yang rapi.

- [ ] **Step 6: Jalankan pengujian test suite**
Run: `php artisan test`
Expected: 89 passed.

- [ ] **Step 7: Commit**
```bash
git add resources/views/admin/dashboard.blade.php resources/views/admin/recruitment/index.blade.php resources/views/admin/members/index.blade.php resources/views/admin/attendance/index.blade.php resources/views/admin/announcements/index.blade.php
git commit -m "feat(ui): redesign academic and membership admin views to symmetrical claymorphism"
```

---

### Task 3: Redesain Modul Publikasi & Portofolio (6 Views)

**Files:**
- Modify: `resources/views/admin/projects/index.blade.php`
- Modify: `resources/views/admin/events/index.blade.php`
- Modify: `resources/views/admin/posts/index.blade.php`
- Modify: `resources/views/admin/officers/index.blade.php`
- Modify: `resources/views/admin/certificates/index.blade.php`
- Modify: `resources/views/admin/galleries/index.blade.php`

**Interfaces:**
- Consumes: `.admin-clay-card`, `.admin-stat-grid`, `.admin-grid-3`, `.admin-filter-bar`.

- [ ] **Step 1: Perbarui `resources/views/admin/projects/index.blade.php`**
Ubah daftar karya mahasiswa menjadi grid kartu simetris `.admin-grid-3` dengan kartu rasio gambar preview, status verifikasi (Pending / Disetujui / Ditolak), serta tombol aksi langsung yang rapi.

- [ ] **Step 2: Perbarui `resources/views/admin/events/index.blade.php`**
Gunakan kartu metrik agenda, filter bar tanggal & divisi, serta tabel jadwal kegiatan simetris dalam `.admin-clay-card-flat`.

- [ ] **Step 3: Perbarui `resources/views/admin/posts/index.blade.php`**
Gunakan metrik artikel, filter bar kategori, dan tabel artikel simetris dengan chip divisi.

- [ ] **Step 4: Perbarui `resources/views/admin/officers/index.blade.php`**
Atur struktur hierarki pengurus dalam kartu terstruktur (Pembina & BPH dalam kartu simetris atas, lalu koordinator divisi dalam kartu seimbang di bawah) dengan form penambahan yang rapi.

- [ ] **Step 5: Perbarui `resources/views/admin/certificates/index.blade.php`**
Format daftar e-sertifikat ke dalam kartu cetak & rincian nomor verifikasi yang simetris.

- [ ] **Step 6: Perbarui `resources/views/admin/galleries/index.blade.php`**
Terapkan grid kartu media `.admin-grid-3` dengan aspect-ratio 16:9, label divisi clay pill, dan tombol aksi edit/hapus yang elegan.

- [ ] **Step 7: Jalankan pengujian test suite**
Run: `php artisan test`
Expected: 89 passed.

- [ ] **Step 8: Commit**
```bash
git add resources/views/admin/projects/index.blade.php resources/views/admin/events/index.blade.php resources/views/admin/posts/index.blade.php resources/views/admin/officers/index.blade.php resources/views/admin/certificates/index.blade.php resources/views/admin/galleries/index.blade.php
git commit -m "feat(ui): redesign publication and portfolio admin views to symmetrical claymorphism"
```

---

### Task 4: Redesain Modul Organisasi & Pengaturan (3 Views)

**Files:**
- Modify: `resources/views/admin/divisions/index.blade.php`
- Modify: `resources/views/admin/recruitment/settings.blade.php`
- Modify: `resources/views/admin/users/index.blade.php`

**Interfaces:**
- Consumes: `.admin-clay-card`, `.admin-grid-2`, `.admin-stat-grid`, `.admin-filter-bar`.

- [ ] **Step 1: Perbarui `resources/views/admin/divisions/index.blade.php`**
Ubah ke layout `.admin-grid-2` yang simetris dan memiliki tinggi seimbang:
  - Ikon 3D timbul sesuai warna aksen masing-masing divisi.
  - Kartu profil Dosen Pembina & Ketua Divisi yang proporsional.
  - Statistik artikel & pendaftar divisi dalam pill taktil.

- [ ] **Step 2: Perbarui `resources/views/admin/recruitment/settings.blade.php`**
Ubah ke layout `.admin-grid-2` simetris:
  - Kolom kiri: Form pengaturan gelombang pendaftaran, status toggle, tanggal, dan target kuota tiap divisi.
  - Kolom kanan: Kartu status live Oprec, indikator kuota real-time, dan panduan pengurus.

- [ ] **Step 3: Perbarui `resources/views/admin/users/index.blade.php`**
Gunakan kartu metrik pengguna (Super Admin vs Admin Divisi), filter bar role & divisi, serta tabel akun pengguna yang rapi dan aman.

- [ ] **Step 4: Jalankan pengujian test suite**
Run: `php artisan test`
Expected: 89 passed.

- [ ] **Step 5: Commit**
```bash
git add resources/views/admin/divisions/index.blade.php resources/views/admin/recruitment/settings.blade.php resources/views/admin/users/index.blade.php
git commit -m "feat(ui): redesign organization and settings admin views to symmetrical claymorphism"
```

---

### Task 5: Verifikasi Menyeluruh & Regresi Pengujian Otomatis

**Files:**
- Verify all 14 views & CSS

- [ ] **Step 1: Jalankan full test suite PHPUnit**
Run: `php artisan test`
Expected: 89 passed (383 assertions).

- [ ] **Step 2: Verifikasi halaman admin di browser**
Buka URL `/admin`, `/admin/recruitment`, `/admin/members`, `/admin/divisions`, `/admin/projects`, `/admin/events`, `/admin/officers` untuk memastikan render visual simetris, responsif, dan tidak ada elemen pecah.

- [ ] **Step 3: Final Commit & Dokumentasi**
```bash
git commit --allow-empty -m "chore: complete symmetrical white claymorphism uiux overhaul for admin panel"
```
