# Spec: Student Account Creation, Edit Profile, & Comprehensive Member Portal Ecosystem

**Tanggal**: 2026-10-06  
**Status**: Approved by User  
**Tujuan**: Menyederhanakan formulir pendaftaran anggota baru dengan pembuatan akun login (Email & Password), upload foto profil, link GitHub, dan CV, menghadirkan fitur Edit Profil di portal mahasiswa, menampilkan foto profil mahasiswa di Admin CMS (Daftar Pendaftar & Daftar Anggota UKM), serta menghadirkan Portal / Dashboard Mahasiswa terpadu (KTA Digital, Riwayat Presensi, Silabus Materi, Status Seleksi).

---

## 1. Latar Belakang & Masalah
1. **Form Pendaftaran Kurang Terintegrasi Akun**:
   - Pendaftar sebelumnya tidak memiliki akun login dan harus mengecek status seleksi manual.
   - Pendaftaran perlu meminta password, foto profil mahasiswa, link GitHub/portofolio, dan berkas CV pendukung.
2. **Kebutuhan Fitur Edit Profil Mahasiswa**:
   - Mahasiswa membutuhkan fitur untuk memperbarui foto profil mereka, nomor kontak WhatsApp, link GitHub, dan mengganti password akun mereka di portal mahasiswa.
3. **Visibilitas Foto Profil di Panel Admin**:
   - Admin perlu melihat foto profil mahasiswa secara visual di:
     - Daftar Pendaftar Rekrutmen (`/admin/recruitment`) & halaman detail pendaftar.
     - Daftar Anggota UKM (`/admin/members`), sehingga admin dan ketua divisi dapat mengenali wajah anggota resmi UKM secara langsung.
4. **Ketiadaan Portal Mahasiswa / Member Hub**:
   - Mahasiswa membutuhkan dashboard personal untuk memantau status seleksi, memiliki Kartu Tanda Anggota (KTA) Digital resmi ber-QR Code, memantau rekap presensi kehadiran, dan mengakses materi pembelajaran yang diajarkan divisi.

---

## 2. Arsitektur & Perubahan Skema Database

### A. Tabel `users`
- Kolom `role`: Memperluas enum/string menjadi `['super_admin', 'division_admin', 'member']` (default: `'member'`).
- Menambahkan kolom `avatar`: (`string`, nullable) path foto profil pengguna.
- Menambahkan kolom `nim`: (`string`, nullable, unique) NIM mahasiswa pemilik akun.
- Menambahkan kolom `phone_number`: (`string`, nullable) nomor WhatsApp pengguna.
- Menambahkan kolom `github_url`: (`string`, nullable) tautan portofolio/GitHub.

### B. Tabel `recruitments`
- Menghapus kewajiban `class_group` (jadikan nullable).
- Menambahkan `user_id`: (`foreignId`, nullable, constrained to `users`, on delete cascade).
- Menambahkan `github_url`: (`string`, nullable) tautan akun GitHub / portofolio karya.
- Menambahkan `profile_photo`: (`string`, nullable) path foto profil pendaftar.
- Kolom `file_cv`: Berkas CV (format PDF).

### C. Tabel `members`
- Menambahkan kolom `user_id`: (`foreignId`, nullable, constrained to `users`, on delete null).
- Menambahkan kolom `avatar`: (`string`, nullable) path foto profil anggota (sinkron dengan akun user).

---

## 3. Alur Fungsionalitas & User Journey

### A. Pendaftaran Terpadu & Pembuatan Akun Otomatis (`/pendaftaran`)
1. **Formulir Pendaftaran**:
   - **Tahap 1: Identitas & Akses Akun**: Nama Lengkap, NIM, Semester, Nomor WhatsApp, Alamat Email (username login), Password & Konfirmasi Password (min 6 karakter), Upload Foto Profil (JPG/PNG, max 2MB).
   - **Tahap 2: Pilihan Divisi & Motivasi**: Divisi Pilihan Utama, Divisi Pilihan Kedua, Motivasi & Tujuan Bergabung UKM.
   - **Tahap 3: Berkas Portofolio**: Link GitHub / Portofolio Digital, Upload CV / Resume (PDF, max 3MB).
2. **Proses Pengiriman Form**:
   - Simpan foto profil ke direktori penyimpanan `storage/app/public/avatars`.
   - Buat akun di `users` (`role = 'member'`, password di-hash `bcrypt`, path foto profil disimpan).
   - Buat record pendaftar di `recruitments` dengan relasi `user_id` dan `profile_photo`.
   - Lakukan `Auth::login($user)` otomatis.
   - Redirect langsung ke Dashboard Mahasiswa: `/student/dashboard`.

### B. Otentikasi & Smart Role-Based Redirect (`/login`)
- Form login tunggal di `/login` (Email & Password).
- Logika Redirect setelah login:
  - Jika `role == 'super_admin'` atau `role == 'division_admin'` &rarr; redirect ke `/admin`.
  - Jika `role == 'member'` &rarr; redirect ke `/student/dashboard`.

### C. Dashboard / Portal Mahasiswa (`/student/dashboard`)
1. **Header Profil Mahasiswa**:
   - Avatar foto profil mahasiswa, Nama Lengkap, NIM, Email, dan Divisi Spesialisasi.
   - Tombol cepat **"Edit Profil"** menuju `/student/profile`.
   - Badge Status: `Calon Anggota (Tahap: Administrasi/Wawancara)` atau `Anggota Resmi UKM (Aktif)`.
2. **Tab 1: Status Rekrutmen & Stepper Seleksi**:
   - Stepper visual tahapan seleksi (Administrasi &rarr; Wawancara &rarr; Hasil Akhir) beserta lokasi/link wawancara dan catatan penyeleksi.
3. **Tab 2: Kartu Tanda Anggota (KTA) Digital**:
   - ID Card digital bergaya Apple Wallet/Holographic Card berisi Foto Profil, Logo UKM, Nama, NIM, Divisi, dan QR Code verifikasi resmi.
4. **Tab 3: Riwayat Presensi & Kehadiran**:
   - Rekapitulasi persentase kehadiran dan tabel riwayat kehadiran di sesi pertemuan divisi (*Hadir/Izin/Sakit/Alpa*).
5. **Tab 4: Modul & Silabus Pembelajaran**:
   - Daftar pokok bahasan materi yang dipelajari dan rangkuman capaian pembelajaran dari pertemuan divisi.

### D. Fitur Edit Profil Mahasiswa (`/student/profile`)
- Mahasiswa dapat:
  - Mengganti Foto Profil / Avatar (dengan live preview gambar).
  - Mengubah Nama Lengkap dan Nomor WhatsApp.
  - Memperbarui Link GitHub / Portofolio.
  - Mengubah Password Akun (password lama, password baru, konfirmasi password baru).
- Data foto profil otomatis tersinkronisasi ke data anggota dan pendaftar terkait.

### E. Visibilitas Foto Profil di Admin CMS
1. **Tabel Data Rekrutmen (`/admin/recruitment`)**:
   - Menampilkan thumbnail avatar bundar foto profil pendaftar di kolom Nama & Identitas.
   - Di halaman detail pendaftar (`/admin/recruitment/{id}`), foto profil ditampilkan berukuran besar di samping informasi biodata.
2. **Tabel Data Anggota UKM (`/admin/members`)**:
   - Menampilkan thumbnail avatar bundar foto profil anggota di baris tabel anggota.
   - Jika anggota belum memiliki foto profil, tampil avatar inisial nama dengan background warna divisi yang elegan.

---

## 4. Keamanan & Hak Akses
- Middleware `auth`: Memastikan user terautentikasi.
- Middleware `student` / Role check: Membatasi area `/student/*` hanya untuk user ber-role `member`.
- Data Isolation: Mahasiswa hanya dapat mengedit dan melihat profil miliknya sendiri (`Auth::id()`).

---

## 5. Rencana Pengujian Otomatis (Automated Tests)
1. **StudentRegistrationAndAuthTest**:
   - Registrasi pendaftar dengan password & foto profil, otomatis membuat akun user dan login.
   - Redirect sesuai role pada halaman login.
2. **StudentProfileEditTest**:
   - Mahasiswa dapat memperbarui foto profil, nomor telepon, link GitHub, dan password.
3. **AdminProfilePhotoVisibilityTest**:
   - Admin recruitment list dan admin members list menampilkan avatar foto profil mahasiswa.
