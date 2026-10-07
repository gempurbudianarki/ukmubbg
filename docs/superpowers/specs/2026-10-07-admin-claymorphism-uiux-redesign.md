# Spesifikasi Desain: Redesain UI/UX Admin White Claymorphism Simetris

**Tanggal:** 2026-10-07  
**Status:** Approved by User  
**Topik:** Standardisasi Komponen Kartu Simetris, Elevasi White Claymorphism, dan Tata Letak Proporsional pada 14 Modul Admin CMS UKM

---

## 1. Latar Belakang & Tujuan
Sebelumnya, tampilan pada beberapa modul panel admin memiliki kartu yang melebar tidak proporsional (*stretched*), inkonsistensi bayangan/padding kartu, dan variasi gaya visual antara satu menu dengan menu lainnya. Modul-modul tertentu seperti *Biodata 4 Divisi*, *Galeri Dokumentasi*, *Karya Mahasiswa*, dan data tabular membutuhkan standardisasi agar memiliki kesan sentuhan 3D lembut (*White Claymorphism*), simetris, ergonomis, dan tidak ada ruang kosong yang janggal pada resolusi desktop maupun tablet/mobile.

Tujuan spesifikasi ini:
1. Menyediakan fondasi CSS Claymorphism khusus Admin di `public/css/portal.css` yang reusable, bersih, dan konsisten.
2. Memperbaiki dan menyeimbangkan tata letak kartu di seluruh 14 modul panel admin (3 kategori navigasi).
3. Memastikan semua kartu memiliki batas visual simetris, dual shadow (outward & inset tactile), debossed inputs pada form filter, serta typography yang rapi.

---

## 2. Fondasi CSS Admin Claymorphism (`public/css/portal.css`)

Dibuat set utility class khusus di `portal.css`:
- `.admin-clay-card`: Kartu dasar dengan latar putih bersih (`#ffffff`), `border-radius: var(--radius-lg)` (24px-28px), `box-shadow: var(--clay-card)`, border halus `1px solid rgba(255, 255, 255, 0.8)`, dan padding proporsional (`1.75rem`).
- `.admin-clay-card-flat`: Kartu tanpa padding berlebih, cocok untuk pembungkus tabel data dengan overflow teratur.
- `.admin-stat-grid`: Grid metrik responsif otomatis seimbang (`grid-template-columns: repeat(auto-fit, minmax(220px, 1fr))` atau `repeat(4, 1fr)`).
- `.admin-stat-card`: Kartu ringkasan metrik dengan ikon clay bulat timbul, angka statistik besar (font mono), label uppercase, dan status subteks.
- `.admin-filter-bar`: Toolbar pencarian dan filter dengan gaya clay debossed (`var(--clay-input)`), simetris menyatu dengan kartu tabel.
- `.admin-grid-2`: Tata letak 2 kolom simetris seimbang (50% - 50%) dengan celah `1.5rem` atau `2rem`.
- `.admin-grid-3`: Tata letak 3 kolom simetris untuk galeri media, karya mahasiswa, dan portofolio.
- `.admin-grid-4`: Tata letak 4 kolom simetris untuk metrik status atau kartu ringkasan divisi.
- `.admin-clay-table`: Tabel modern dengan header abu-abu lembut (`#f8fafc`), pemisah halus, efek hover baris, serta badge status beraksen clay pill.

---

## 3. Rencana Pembaharuan Tampilan per Kategori Modul

### Kategori 1: Akademik & Keanggotaan
1. **Ringkasan Dashboard (`/admin`):**
   - Grid 6 kartu metrik atas simetris (Total Pendaftar, Pending Seleksi, Diterima, Anggota Aktif, Sesi Presensi, Total Publikasi).
   - Banner status Oprec Super Admin yang taktil & elegan.
   - Grid 2-kolom seimbang di bagian bawah: Statistik 4 Divisi (kiri) dan Tabel Pendaftar Terbaru (kanan).
2. **Pusat Pendaftaran (`/admin/recruitment`):**
   - Header terpadu dengan tombol aksi cepat ekspor & pengaturan gelombang.
   - Grid 4 metrik status seleksi: Total Masuk, Menunggu Review, Tahap Wawancara, Lolos/Diterima.
   - Filter bar clay debossed menyatu dengan tabel seleksi pendaftar (avatar bulat, chip divisi, aksi cepat review).
3. **Anggota UKM (`/admin/members`):**
   - Grid 4 kartu statistik anggota (Total Anggota, Anggota Aktif, Alumni, Total Divisi).
   - Toolbar filter (pencarian, filter divisi, filter angkatan).
   - Kartu tabel anggota simetris dengan info NIM, peran, divisi, status keaktifan, dan aksi detail.
4. **Presensi & BAP (`/admin/attendance`):**
   - Grid metrik kehadiran (Total Sesi, Sesi Aktif Hari Ini, Rata-rata Kehadiran, Total BAP).
   - Filter bar divisi & tipe pertemuan.
   - Tabel sesi presensi dengan tombol BAP cetak & QR Code scanner yang terstruktur.
5. **Papan Pengumuman (`/admin/announcements`):**
   - Grid kartu pengumuman proporsional atau list kartu berprioritas (badge pinned/urgent, target audience, tanggal terbit, tombol aksi edit/hapus).

### Kategori 2: Publikasi & Portofolio
6. **Karya Mahasiswa (`/admin/projects`):**
   - Grid 3-kolom simetris untuk kartu proyek mahasiswa (preview banner/screenshot, judul, nama pembuat + divisi, badge status verifikasi: Pending, Published, Rejected, link repository/demo).
7. **Agenda & Event (`/admin/events`):**
   - Grid metrik event (Upcoming, Completed, Batal).
   - Filter bar + tabel/kartu jadwal kegiatan (tanggal, lokasi, kuota peserta, dan status).
8. **Artikel & Publikasi (`/admin/posts`):**
   - Metrik publikasi per divisi.
   - Toolbar pencarian + tabel artikel (thumbnail mini, judul, kategori/divisi, penulis, status draft/published).
9. **Struktur Pengurus (`/admin/officers`):**
   - Kartu hierarki kepengurusan: Pembina & BPH di tingkat atas, diikuti koordinator 4 divisi di bawahnya dalam grid simetris.
   - Dialog/modal atau form penambahan pengurus baru yang rapi.
10. **E-Sertifikat (`/admin/certificates`):**
    - Grid simetris kartu sertifikat terbitan (nama penerima, event, nomor sertifikat, verifikasi QR, unduh PDF).
11. **Galeri Dokumentasi (`/admin/galleries`):**
    - Grid 3-kolom kartu foto kegiatan (thumbnail rasio 16:9, judul acara, divisi, tanggal dokumentasi, aksi hapus/edit).

### Kategori 3: Organisasi & Pengaturan
12. **Biodata 4 Divisi (`/admin/divisions`):**
    - Grid simetris 2x2 (atau 4 kolom seimbang) dengan kartu bernuansa aksen warna divisi masing-masing (Pemrograman: Cyan-Biru, Multimedia: Ungu, IoT: Hijau Emerald, Cyber: Rose Pink).
    - Menampilkan: Ikon timbul 3D, Nama Divisi, Tagline, Profil Dosen Pembina, Profil Ketua Divisi Mahasiswa, jumlah artikel, dan tombol edit.
13. **Pengaturan Gelombang (`/admin/recruitment/settings`):**
    - Grid 2-kolom seimbang: Kolom kiri form kontrol gelombang (buka/tutup, batas tanggal, kuota per divisi), kolom kanan status ringkasan pendaftaran live & panduan operasional.
14. **Manajemen Pengguna (`/admin/users`):**
    - Grid metrik pengguna (Super Admin, Admin Divisi, Anggota).
    - Toolbar filter role & pencarian + tabel manajemen user akun.

---

## 4. Rencana Pengujian
- **Visual & Layout Test:** Memastikan tidak ada kartu yang melebar aneh atau bertumpuk di resolusi desktop (1280px-1920px) dan laptop/tablet.
- **Interaksi & Form Test:** Memastikan semua tombol aksi (edit, hapus, modal, export, toggle) tetap berfungsi normal dengan form token CSRF yang aman.
- **Automated Feature Test:** Menjalankan PHPUnit suite (`php artisan test`) untuk memastikan seluruh 89 automated tests tetap lulus 100% HIJAU.
