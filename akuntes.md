# Daftar Akun Uji Coba (Test Accounts) - Portal UKM Ilmu Komputer UBBG

Semua akun di bawah ini telah terdaftar di database sistem dan dapat langsung digunakan untuk pengujian otentikasi serta pengujian hak akses fitur.

**Halaman Login:** [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)

---

## 1. Ringkasan Kredensial Cepat

| No | Tipe Akun / Role | Divisi | Email Login | Password | URL Dashboard Tujuan |
|---|---|---|---|---|---|
| 1 | **Super Administrator** | Seluruh Divisi (Executive) | `admin@ukmilkom.id` | `admin123` | `/admin/dashboard` |
| 2 | **Admin Divisi** | Pemrograman | `pemrograman@ukmilkom.id` | `pemrograman123` | `/admin/dashboard` |
| 3 | **Admin Divisi** | Multimedia | `multimedia@ukmilkom.id` | `multimedia123` | `/admin/dashboard` |
| 4 | **Admin Divisi** | Internet of Things (IoT) | `iot@ukmilkom.id` | `iot123` | `/admin/dashboard` |
| 5 | **Admin Divisi** | Cyber Security | `cyber@ukmilkom.id` | `cyber123` | `/admin/dashboard` |
| 6 | **Mahasiswa (Member)** | Pemrograman | `bintang@student.ac.id` | `student123` | `/student/dashboard` |

---

## 2. Rincian Hak Akses & Fitur Setiap Akun

### 1. Super Administrator UKM
- **Nama Pengguna:** Super Administrator UKM
- **Email:** `admin@ukmilkom.id`
- **Password:** `admin123`
- **Role Sistem:** `super_admin`
- **Akses & Fitur Utama:**
  - Executive Command Hub (Statistik lintas divisi, metrik pendaftar, monitoring presensi).
  - Manajemen Pengguna & Penetapan Role Admin (`/admin/users`).
  - Manajemen Pembina & Struktur Kepengurusan (`/admin/officers`).
  - Pengaturan Rekrutmen & Penutupan/Pembukaan Kuota Divisi (`/admin/recruitment/settings`).
  - Manajemen Agenda & Event Lintas Divisi (`/admin/events`).
  - Konversi Calon Anggota Diterima menjadi Anggota Resmi (`/admin/recruitment`).

---

### 2. Admin Divisi Pemrograman
- **Nama Pengguna:** Admin Divisi Pemrograman
- **Email:** `pemrograman@ukmilkom.id`
- **Password:** `pemrograman123`
- **Role Sistem:** `division_admin` (Terkunci ke Divisi Pemrograman)
- **Akses & Fitur Utama:**
  - Dashboard Divisi Khusus Pemrograman.
  - Sesi Presensi: Pembuatan sesi pertemuan & rilis passcode check-in 6-karakter harian.
  - Silabus Pembelajaran: Pengelolaan materi per modul dan pertemuan.
  - Moderasi Proyek: Persetujuan (Approve) proyek showcase karya mahasiswa divisi pemrograman.
  - Manajemen Anggota Divisi Pemrograman.

---

### 3. Admin Divisi Multimedia
- **Nama Pengguna:** Admin Divisi Multimedia
- **Email:** `multimedia@ukmilkom.id`
- **Password:** `multimedia123`
- **Role Sistem:** `division_admin` (Terkunci ke Divisi Multimedia)
- **Akses & Fitur Utama:**
  - Dashboard Divisi Khusus Multimedia.
  - Sesi Presensi & Passcode Pertemuan Divisi Multimedia.
  - Silabus Pembelajaran UI/UX, Motion Graphic, dan 3D Design.
  - Moderasi Proyek Mahasiswa Divisi Multimedia.
  - Terisolasi secara aman dari data divisi lain.

---

### 4. Admin Divisi Internet of Things (IoT)
- **Nama Pengguna:** Admin Divisi IoT
- **Email:** `iot@ukmilkom.id`
- **Password:** `iot123`
- **Role Sistem:** `division_admin` (Terkunci ke Divisi IoT)
- **Akses & Fitur Utama:**
  - Dashboard Divisi Khusus IoT.
  - Sesi Presensi & Pembuatan Kode Akses Pertemuan Divisi IoT.
  - Silabus Pembelajaran Mikrokontroler, ESP32, dan Jaringan Sensor.
  - Moderasi Proyek Perangkat Keras / Otomasi Mahasiswa IoT.

---

### 5. Admin Divisi Cyber Security
- **Nama Pengguna:** Admin Divisi Cyber Security
- **Email:** `cyber@ukmilkom.id`
- **Password:** `cyber123`
- **Role Sistem:** `division_admin` (Terkunci ke Divisi Cyber Security)
- **Akses & Fitur Utama:**
  - Dashboard Divisi Khusus Cyber Security.
  - Sesi Presensi & Kode Validasi Pertemuan Divisi Cyber Security.
  - Silabus Pembelajaran Ethical Hacking, CTF, dan Security Hardening.
  - Moderasi Proyek Riset Keamanan Mahasiswa Cyber Security.

---

### 6. Mahasiswa / Anggota Resmi (Member)
- **Nama Pengguna:** Bintang Mahasiswa
- **NIM:** `2301010099`
- **Email:** `bintang@student.ac.id`
- **Password:** `student123`
- **Role Sistem:** `member` (Divisi Pemrograman)
- **Akses & Fitur Utama:**
  - **Portal Mahasiswa:** Ringkasan keaktifan, profil anggota (`/student/dashboard`).
  - **KTA Digital Resmi:** Kartu Tanda Anggota dengan barcode / identitas resmi (`/student/kta`).
  - **Presensi Mandiri:** Masukkan kode passcode harian dari instruktur divisi (`/student/presensi`).
  - **Silabus Interaktif:** Akses modul materi & silabus divisi yang diikutinya (`/student/silabus`).
  - **Showcase Proyek:** Pengajuan karya & portofolio proyek untuk dimoderasi admin (`/student/projects`).
  - **Edit Profil & Keamanan:** Pengubahan nomor kontak, URL GitHub, avatar, dan kata sandi (`/student/profile`).

---

*Catatan Keamanan: Sistem menggunakan smart-redirect saat login. Ketika akun ber-role `member` melakukan login, sistem secara otomatis mengarahkannya ke dashboard mahasiswa (`/student/dashboard`), sedangkan akun ber-role `super_admin` dan `division_admin` secara otomatis diarahkan ke dashboard manajemen (`/admin/dashboard`).*
