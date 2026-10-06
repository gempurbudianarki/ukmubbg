# Spec: Recruitment Control Center & Formal Academic Attendance with BAP Generator

**Tanggal**: 2026-10-06  
**Status**: Approved by User  
**Tujuan**: Menghadirkan sistem pembukaan rekrutmen berbasis jadwal waktu & seleksi divisi terpusat oleh Super Admin, serta sistem presensi sesi divisi resmi berstandar nasional yang mencatat silabus materi pembelajaran dan menghasilkan dokumen Berita Acara Presensi (BAP) akademik siap cetak.

---

## 1. Latar Belakang & Masalah
1. **Pendaftaran Tidak Terjadwal & Tidak Fleksibel**:
   - Sebelumnya, form pendaftaran terbuka tanpa pembatasan jadwal waktu otomatis (tanggal buka/tutup).
   - Super Admin belum memiliki kontrol per divisi (misalnya membuka kuota hanya untuk Divisi Pemrograman dan IoT, sedangkan Multimedia dan Cyber Security ditutup sementara).
   - Pengunjung publik dapat memilih divisi yang kuotanya sebenarnya sudah penuh atau sedang tidak membuka rekrutmen.
2. **Presensi Kurang Formal & Belum Memenuhi Standar Akademik Kampus/Nasional**:
   - Setiap divisi di lingkungan kampus wajib memiliki rekaman kegiatan yang jelas: hari & tanggal, jam pelaksanaan, nama ruangan/media, materi/silabus pokok bahasan, capaian pembelajaran (resume materi), dan penanggung jawab/pemateri.
   - Belum ada dokumen resmi Berita Acara Presensi (BAP) yang memuat kop resmi organisasi, rekap statistik kehadiran, tanda tangan Ketua Divisi, dan Dosen Pembina untuk pelaporan pertanggungjawaban (LPJ).

---

## 2. Arsitektur & Perubahan Skema Database

### A. Tabel `divisions`
Menambahkan kolom kontrol rekrutmen tingkat divisi:
- `is_recruitment_open` (`boolean`, default: `true`): Flag pembukaan rekrutmen untuk divisi ini.
- `recruitment_quota` (`integer`, nullable, default: `null`): Kuota maksimal pendaftar (opsional/unlimited jika null).
- `recruitment_notes` (`string`, nullable): Catatan khusus divisi (misal: "Hanya untuk angkatan 2025/2026" atau "Kuota Terpenuhi").

### B. Tabel `settings` (Konfigurasi Global Gelombang Pendaftaran)
Kunci konfigurasi yang dikelola Super Admin:
- `recruitment_status`: `'open'` | `'closed'`
- `recruitment_start_date`: Tanggal & waktu mulai pembukaan (format: `Y-m-d H:i`)
- `recruitment_end_date`: Tanggal & waktu penutupan pendaftaran (format: `Y-m-d H:i`)
- `recruitment_batch_name`: Nama gelombang (misal: "Gelombang Ganjil 2026/2027")
- `recruitment_closed_message`: Pesan formal saat pendaftaran ditutup.

### C. Tabel `attendance_sessions`
Memperluas metadata sesi presensi agar setara standar akademik/BAP nasional:
- `day_name`: (`string`, panjang: 20) Hari pelaksanaan (Senin, Selasa, Rabu, Kamis, Jumat, Sabtu, Minggu)
- `session_type`: (`enum`: `riset_rutin`, `workshop_teknis`, `mentoring_proyek`, `evaluasi_bulanan`, `sidang_pleno`, default: `riset_rutin`)
- `topic_material`: (`string`, panjang: 255) Pokok Bahasan / Silabus Pertemuan yang dipelajari
- `learning_outcomes`: (`text`, nullable) Uraian materi yang dipelajari, ringkasan pembahasan, atau capaian kompetensi
- `instructor_name`: (`string`, panjang: 150) Nama Pemateri / Instruktur / Mentor / PIC Sesi
- `status`: (`enum`: `open`, `closed`, default: `open`) Status sesi presensi

---

## 3. Alur Fungsionalitas & User Journey

### A. Kontrol Pendaftaran (Super Admin)
1. **Halaman Pengaturan Gelombang (`/admin/recruitment/settings`)**:
   - Super Admin dapat:
     - Mengubah switch status pendaftaran global (`Buka Pendaftaran` vs `Tutup Pendaftaran`).
     - Mengatur periode waktu mulai (`start_date`) dan batas akhir (`end_date`).
     - Menentukan nama gelombang dan pesan formal ketika pendaftaran ditutup.
     - Mengatur status pembukaan divisi masing-masing via switch interaktif, kuota pendaftar, dan catatan divisi.
     - Melihat ringkasan real-time: Total pendaftar per divisi vs kuota yang ditetapkan.
2. **Validasi & Tampilan Publik (`/pendaftaran`)**:
   - **Pengecekan Waktu Otomatis**:
     - Jika status pendaftaran global `closed` ATAU waktu sekarang di luar rentang `start_date` sampai `end_date`, halaman `/pendaftaran` menampilkan **Pemberitahuan Resmi Penutupan Rekrutmen** lengkap dengan tanggal pembukaan gelombang berikutnya. Tombol pendaftaran dinonaktifkan.
   - **Filter Divisi Aktif**:
     - Form pendaftaran hanya menampilkan pilihan divisi (Pilihan 1 dan Pilihan 2) yang memiliki `is_recruitment_open == true` dan belum melebihi kuota.
     - Setiap opsi divisi menampilkan status badge (misal: "Buka - Kuota Tersedia" atau "Sisa Kuota: 12").
     - Backend controller (`RecruitmentController@store`) memvalidasi ulang: menolak pendaftaran jika divisi pilihan utama/kedua sedang ditutup atau kuota sudah penuh.

### B. Sesi Presensi Formal & Berita Acara Pertemuan (BAP)
1. **Pembukaan Sesi Formal oleh Admin Divisi (`/admin/attendance/create`)**:
   - Admin Divisi mengisi form sesi lengkap:
     - Divisi (otomatis terkunci untuk admin divisi terkait)
     - Hari & Tanggal (hari terdeteksi otomatis via JavaScript berdasarkan tanggal yang dipilih)
     - Jam Mulai & Jam Selesai
     - Tipe Sesi (Riset Rutin, Workshop Teknis, Mentoring Proyek, Evaluasi Bulanan)
     - Lokasi / Ruangan (Laboratorium, Gedung Kuliah, atau Link Daring)
     - Pokok Bahasan / Silabus yang dipelajari
     - Capaian Pembelajaran & Uraian Materi yang dipelajari
     - Nama Pemateri / Instruktur / PIC
   - Saat sesi disimpan, seluruh anggota aktif divisi otomatis dibuatkan log absensi berstatus default `hadir`.
2. **Pengisian Presensi Interaktif (`/admin/attendance/{id}`)**:
   - Admin menandai status presensi masing-masing anggota (`Hadir`, `Izin`, `Sakit`, `Alpa`) dan memberikan catatan jika izin/sakit.
   - Tersedia tombol cepat "Tandai Semua Hadir".
   - Terdapat tombol aksi menuju **"Cetak Berita Acara Presensi (BAP)"**.
3. **Dokumen Berita Acara Presensi Resmi (`/admin/attendance/{id}/bap`)**:
   - Tampilan dokumen resmi standar akademik universitas:
     - Kop surat resmi UKM Ilmu Komputer & Fakultas.
     - Judul: **BERITA ACARA PERTEMUAN & DAFTAR PRESENSI KEGIATAN**.
     - Nomor Dokumen: BAP otomatis (format: `BAP-ILKOM/YYYY/MM/ID`).
     - Tabel Informasi Sesi: Hari/Tanggal, Jam, Tempat, Divisi, Tipe Pertemuan, Pemateri.
     - Kotak Materi & Capaian Pembelajaran: Pokok bahasan silabus dan resume materi yang dipelajari.
     - Rekapitulasi Statistik Kehadiran: Total Anggota, Hadir, Izin, Sakit, Alpa, serta Persentase Kehadiran (% Rate).
     - Tabel Daftar Kehadiran Anggota: No, NIM, Nama Anggota, Angkatan, Status Kehadiran, Paraf/Status, Catatan.
     - Kolom Tanda Tangan Resmi: PIC Pemateri Sesi, Ketua Divisi, dan Mengetahui Ketua Umum UKM / Dosen Pembina.
     - Cetak siap pakai (`window.print()`) dengan CSS print yang memotong header/sidebar sistem web, sehingga hasil print PDF atau kertas A4 sangat rapi dan presisi.

---

## 4. Keamanan & Hak Akses (Role & Permission)
- **Super Admin**:
  - Akses penuh ke pengaturan rekrutmen global dan seluruh divisi.
  - Dapat membuat dan melihat sesi presensi seluruh divisi atau sesi pleno bersama.
- **Admin Divisi**:
  - Hanya dapat melihat dan membuat sesi presensi untuk divisinya sendiri.
  - Berita Acara (BAP) hanya dapat diakses/dikelola untuk divisinya sendiri.
- **Guest / Publik**:
  - Hanya dapat mendaftar jika gelombang rekrutmen dan divisi terkait berstatus aktif dibuka.

---

## 5. Rencana Pengujian Otomatis (Automated Tests)
1. **RecruitmentControlTest**:
   - Memastikan pendaftaran ditolak jika jadwal pendaftaran belum dimulai atau sudah lewat tenggat.
   - Memastikan Super Admin dapat mengaktifkan/menonaktifkan divisi tertentu.
   - Memastikan form pendaftaran hanya mengizinkan pemilihan divisi yang dibuka.
2. **AttendanceBAPTest**:
   - Memastikan sesi presensi menyimpan hari, tipe sesi, silabus materi, dan pemateri.
   - Memastikan halaman BAP menghasilkan perhitungan statistik kehadiran yang akurat dan dapat dirender dengan status HTTP 200.
   - Memastikan hak akses admin divisi dibatasi sesuai divisinya.
