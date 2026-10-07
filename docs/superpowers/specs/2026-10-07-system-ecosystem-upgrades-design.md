# Desain Teknis: Peningkatan Ekosistem Sistem Informasi UKM Ilmu Komputer

Tanggal: 2026-10-07  
Status: Disetujui  
Penyusun: Software Architect & Engineering Team  

---

## 1. Latar Belakang & Tujuan
Berdasarkan audit komprehensif terhadap alur sistem saat ini, sistem telah memiliki arsitektur dasar yang kuat. Namun, terdapat beberapa kebutuhan fungsional dan peningkatan administratif agar sistem memenuhi standar operasional resmi organisasi kampus:
1. **Presensi Mandiri & Kehadiran yang Kredibel**: Menutup celah titip absen dengan sistem kadaluarsa passcode (*expiry time lock*), serta memberi mahasiswa jalur resmi untuk mengajukan izin/sakit disertai bukti/alasan.
2. **Berita Acara Presensi (BAP) Resmi**: Menyediakan laporan format cetak/BAP pertemuan resmi dengan standar administrasi perkuliahan dan kemahasiswaan.
3. **Peningkatan UX & Navigasi Admin**: Mengelompokkan menu sidebar admin yang sebelumnya mendatar (*flat list*) menjadi 3 kategori logis berorientasi tugas.
4. **Partisipasi Aktif Mahasiswa**: Mahasiswa dapat mengirimkan karya proyek portofolio (*showcase*) untuk dikurasi divisi dan dipublikasikan di halaman publik.
5. **KTA Digital HD & Pengumuman Internal**: Memastikan ID Card dapat diunduh/dicetak dengan standar ukuran kartu fisik, serta adanya papan pengumuman internal divisi.

---

## 2. Rincian Modul & Fungsionalitas Baru

### A. Modul Presensi Lanjutan (Advanced Attendance & Formal BAP)
1. **Kadaluarsa Passcode (*Passcode Expiry*)**:
   - Kolom `passcode_expires_at` pada `attendance_sessions`.
   - Admin saat membuat atau mengedit sesi dapat menentukan berapa menit passcode aktif (default 60 menit sejak sesi dibuat atau waktu mulai).
   - Validasi pada `StudentDashboardController::selfCheckin`: jika waktu sekarang (`now()`) melewati `passcode_expires_at`, tolak presensi dengan pesan ramah: *"Masa berlaku kode presensi telah habis. Silakan hubungi ketua divisi."*
2. **Pengajuan Izin / Sakit Mandiri**:
   - Form modal atau kartu di `/student/presensi` dengan opsi status `izin` atau `sakit`, catatan alasan, dan unggah lampiran bukti (surat dokter / disposisi) berformat image/pdf.
   - Kolom baru `attachment` pada tabel `attendance_logs`.
   - Endpoint: `POST /student/presensi/permission` -> memvalidasi dan memperbarui `AttendanceLog` anggota dengan status `izin`/`sakit`, `notes`, `attachment`, `checkin_type: 'self'`.
3. **Cetak Berita Acara Presensi (BAP) Resmi**:
   - Route: `GET /admin/attendance/{session}/bap`.
   - Tampilan cetak A4 formal (*print-ready view*) berstandar kampus:
     - Kop surat resmi UKM Ilmu Komputer.
     - Informasi sesi: Judul, Divisi, Tanggal & Jam, Ruangan/Lokasi, Tipe Sesi, Pemateri/Instruktur.
     - Rincian kurikulum: Topik Bahasan & Capaian Pembelajaran (*Learning Outcomes*).
     - Rekap kuantitatif: Total Anggota, Hadir, Izin, Sakit, Alpa, dan Persentase Kehadiran.
     - Tabel daftar hadir lengkap berisi NIM, Nama Anggota, Status Kehadiran, Jam Masuk, dan Tipe Presensi.
     - Kolom pengesahan tanda tangan: Ketua Divisi / Instruktur dan Pembina UKM.

### B. Restrukturisasi Navigasi Admin & Pengumuman Divisi
1. **Sidebar Navigasi Admin Terkategori**:
   - Mengubah `resources/views/admin/layouts/app.blade.php`:
     - **Keanggotaan & Akademik**: Dashboard, Open Recruitment, Data Anggota, Presensi & BAP.
     - **Publikasi & Portofolio**: Artikel / Berita, Proyek Showcase, Agenda Kegiatan, Galeri Foto.
     - **Organisasi & Sistem**: Profil Divisi, Struktur Pengurus, E-Sertifikat, Pengaturan Gelombang.
2. **Papan Pengumuman Internal (Announcements)**:
   - Model `Announcement`: `id`, `division_id` (nullable untuk pengumuman umum seluruh UKM), `author_id`, `title`, `content`, `badge_type` (info/penting/agenda), `created_at`.
   - Ditampilkan di bagian atas Dashboard Mahasiswa (`/student/dashboard`) sebagai kartu pengumuman terkini divisi.

### C. Showcase Proyek Mahasiswa (Student Project Submission)
1. **Penyesuaian Skema Proyek**:
   - Kolom pada tabel `projects`: `user_id` (nullable), `status` (enum: `published`, `pending_review`, default `published`).
2. **Fitur Pengajuan Proyek di Portal Mahasiswa**:
   - Route: `GET /student/proyek` (melihat proyek yang disubmit), `POST /student/proyek` (submit karya baru dengan link demo, github, thumbnail, deskripsi).
   - Pengurus divisi / Super Admin di `/admin/projects` dapat meninjau dan mengubah status menjadi `published` agar tampil di `/proyek` publik.

### D. Optimasi KTA Digital (ID Card Print & HD Image Download)
1. **CSS Print CR80 Card**:
   - Menambahkan media query `@media print` khusus untuk kartu KTA di `student/kta.blade.php` dengan dimensi 85.6mm x 54mm, margin 0, memastikan orientasi *landscape* ID card tanpa terpotong header/footer browser.
2. **Download Kartu Beresolusi Tinggi**:
   - Peningkatan tombol cetak/unduh kartu agar memudahkan anggota menyimpan KTA digital ke galeri ponsel atau siap cetak fisik.

---

## 3. Skema Basis Data (Database Migrations)

1. `2026_10_07_000002_add_expiry_and_attachments_to_attendance.php`:
   - `attendance_sessions`: `passcode_expires_at` (timestamp, nullable).
   - `attendance_logs`: `attachment` (string, nullable).
2. `2026_10_07_000003_upgrade_projects_and_create_announcements.php`:
   - Modifikasi `projects`: tambah `user_id` (unsignedBigInteger, nullable, foreign key ke users), `submission_status` (string, default 'published').
   - Buat tabel `announcements`:
     - `id`
     - `division_id` (foreign key ke divisions, nullable)
     - `author_id` (foreign key ke users)
     - `title` (string)
     - `content` (text)
     - `category` (string, default 'info')
     - `timestamps`

---

## 4. Pengujian & Verifikasi (Test Matrix)
1. **Unit & Feature Tests**:
   - Tes validasi kadaluarsa passcode: input passcode setelah `passcode_expires_at` gagal dengan pesan sesi kadaluarsa.
   - Tes pengajuan izin/sakit mahasiswa: log kehadiran terisi dengan status `izin`/`sakit`, catatan tertera, dan file terunggah.
   - Tes akses halaman BAP resmi oleh admin divisi dan super admin.
   - Tes submit proyek oleh mahasiswa dengan status `pending_review` dan publikasi oleh admin.
2. **Browser & UI Verification**:
   - Tampilan cetak BAP presensi rapi tanpa overflow.
   - Sidebar admin terorganisir per kategori.
   - Form modal izin/sakit di portal mahasiswa berfungsi mulus.
