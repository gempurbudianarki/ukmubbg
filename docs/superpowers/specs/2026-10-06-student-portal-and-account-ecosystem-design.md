# Spec: Student Account Creation & Comprehensive Member Portal Ecosystem

**Tanggal**: 2026-10-06  
**Status**: Approved by User  
**Tujuan**: Menyederhanakan formulir pendaftaran anggota baru dengan penghapusan field usang (rombel), menambahkan pembuatan akun login (Email & Password), upload foto profil, link GitHub, dan CV, serta menghadirkan Portal / Dashboard Mahasiswa terpadu dengan status seleksi real-time, KTA Digital ber-QR Code, riwayat presensi divisi, dan akses silabus/materi pembelajaran.

---

## 1. Latar Belakang & Masalah
1. **Form Pendaftaran Kurang Modern & Belum Terintegrasi Akun**:
   - Pendaftar sebelumnya tidak memiliki akun login. Mereka harus mengecek status seleksi secara manual menggunakan kode registrasi.
   - Field seperti `class_group` (kelas/rombel) membebani pendaftar dan tidak esensial.
   - Pendaftaran belum meminta password untuk login, foto profil mahasiswa, link GitHub/portofolio secara terstruktur, dan berkas CV pendukung.
2. **Ketiadaan Portal Mahasiswa / Member Hub**:
   - Setelah mendaftar atau diterima menjadi anggota resmi, mahasiswa belum memiliki dashboard personal untuk:
     - Memantau tahapan seleksi (Administrasi &rarr; Wawancara &rarr; Hasil Akhir) beserta lokasi/link jadwal wawancara.
     - Memiliki Kartu Tanda Anggota (KTA) Digital resmi ber-QR Code sebagai identitas keanggotaan UKM.
     - Memantau rekap persentase presensi dan riwayat kehadiran pada pertemuan divisi.
     - Mengakses silabus dan materi pembelajaran yang diajarkan oleh mentor/divisi.

---

## 2. Arsitektur & Perubahan Skema Database

### A. Tabel `users`
- Kolom `role`: Memperluas enum/string menjadi `['super_admin', 'division_admin', 'member']` (default: `'member'`).
- Menambahkan kolom `avatar`: (`string`, nullable) path foto profil mahasiswa.
- Menambahkan kolom `nim`: (`string`, nullable, unique) NIM mahasiswa pemilik akun.

### B. Tabel `recruitments`
- Menghapus kewajiban `class_group` (jadikan nullable atau default string kosong).
- Menambahkan `user_id`: (`foreignId`, nullable, constrained to `users`, on delete cascade).
- Menambahkan `github_url`: (`string`, nullable) tautan akun GitHub / portofolio karya.
- Menambahkan `profile_photo`: (`string`, nullable) path foto profil pendaftar.
- Kolom `reason_to_join`: Tetap sebagai esai tujuan/motivasi bergabung UKM.
- Kolom `file_cv`: Berkas CV (format PDF).

### C. Relasi Member & User
- Saat calon anggota diterima dan dipromosikan menjadi anggota resmi (`members`), akun `user` yang sama otomatis dihubungkan dengan data `Member`.
- Jika data `Member` dibuat secara manual oleh admin, sistem dapat mengaitkan user berdasarkan kecocokan NIM/Email.

---

## 3. Alur Fungsionalitas & User Journey

### A. Pendaftaran Terpadu & Pembuatan Akun Otomatis (`/pendaftaran`)
1. **Formulir Pendaftaran Baru**:
   - **Tahap 1: Identitas & Akses Akun**:
     - Nama Lengkap
     - Nomor Induk Mahasiswa (NIM)
     - Semester Saat Ini (1 - 8)
     - Nomor WhatsApp Aktif
     - Alamat Email Aktif (digunakan sebagai username login)
     - Password & Konfirmasi Password (minimal 6 karakter)
     - Upload Foto Profil (JPG/PNG, maksimal 2MB)
   - **Tahap 2: Pilihan Divisi & Motivasi**:
     - Divisi Pilihan Utama (hanya divisi yang statusnya dibuka)
     - Divisi Pilihan Kedua (opsional, hanya divisi yang dibuka)
     - Motivasi & Tujuan Bergabung UKM (minimal 20 karakter)
   - **Tahap 3: Berkas Portofolio**:
     - Link GitHub / Portofolio Digital
     - Upload CV / Resume (PDF, maksimal 3MB)
2. **Proses Pengiriman Form**:
   - Validasi backend memeriksa ketersediaan email & NIM di tabel `users`.
   - Membuat record pendaftar di `recruitments`.
   - Membuat record akun pengguna di `users` (`role = 'member'`, password di-hash `bcrypt`, avatar foto profil disimpan).
   - Menghubungkan `recruitment.user_id = user.id`.
   - Melakukan `Auth::login($user)`.
   - Redirect langsung ke Dashboard Mahasiswa: `/student/dashboard` dengan alert selamat datang.

### B. Otentikasi & Smart Role-Based Redirect (`/login`)
- Form login tunggal di `/login` (Email & Password).
- Logika Redirect setelah login:
  - Jika `role == 'super_admin'` atau `role == 'division_admin'` &rarr; redirect ke `/admin`.
  - Jika `role == 'member'` &rarr; redirect ke `/student/dashboard`.

### C. Dashboard / Portal Mahasiswa (`/student/dashboard`)
Halaman personal mahasiswa dengan desain modern bergaya Apple/Vercel (Glassmorphism, dark/light contrast, responsif):
1. **Header Profil Mahasiswa**:
   - Avatar foto profil, Nama Lengkap, NIM, Email, dan Divisi Spesialisasi.
   - Badge Status: `Calon Anggota (Tahap: Administrasi/Wawancara)` atau `Anggota Resmi UKM (Aktif)`.
2. **Tab / Bagian 1: Status Rekrutmen & Timeline Seleksi**:
   - Stepper visual tahapan seleksi:
     - 1. Administrasi Berkas (Ditinjau)
     - 2. Sesi Wawancara (Tanggal, Jam, dan Lokasi/Link Google Meet jika dijadwalkan admin)
     - 3. Pengumuman Kelulusan (Diterima / Ditolak, beserta Catatan Reviewer)
3. **Tab / Bagian 2: Kartu Tanda Anggota (KTA) Digital**:
   - ID Card digital vertikal/horizontal premium:
     - Logo Resmi UKM Ilmu Komputer & Universitas.
     - Foto Profil & Chip Holografik visual.
     - Nama Lengkap & NIM.
     - Divisi Spesialisasi & Angkatan.
     - QR Code unik yang mengarah ke link verifikasi keabsahan anggota (`/verifikasi?nim=...`).
4. **Tab / Bagian 3: Riwayat Presensi & Kehadiran**:
   - Menampilkan persentase kehadiran mahasiswa pada kegiatan divisi terkait.
   - Tabel riwayat presensi: Tanggal pertemuan, judul agenda, status kehadiran (*Hadir, Izin, Sakit, Alpa*), dan catatan.
5. **Tab / Bagian 4: Modul & Silabus Pembelajaran**:
   - Daftar pokok bahasan materi yang dipelajari pada pertemuan divisi.
   - Uraian capaian pembelajaran dan resume materi yang disampaikan pemateri/instruktur divisi.

---

## 4. Keamanan & Hak Akses
- Middleware `auth`: Memastikan pengunjung harus login.
- Middleware `student` / Role Check: Memastikan hanya user dengan role `member` yang dapat mengakses `/student/*`. Jika admin mengakses, diarahkan ke dashboard admin.
- Data Isolation: Mahasiswa hanya dapat melihat data profil, status pendaftaran, dan presensi miliknya sendiri (`Auth::id()`).

---

## 5. Rencana Pengujian Otomatis (Automated Tests)
1. **StudentRegistrationTest**:
   - Mahasiswa mendaftar dengan email & password, berhasil membuat user baru ber-role `member` dan login otomatis.
   - Upload foto profil dan link GitHub tersimpan di database.
2. **StudentPortalAccessTest**:
   - Mahasiswa yang login dapat membuka `/student/dashboard`.
   - Menguji tampilan timeline status seleksi.
   - Menguji tampilan KTA Digital dan riwayat presensi.
   - User non-login atau role yang tidak berhak dibatasi secara aman.
