# 🌟 Portal Terpadu UKM Ilmu Komputer (UBBG)

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Tests](https://img.shields.io/badge/Tests-96%20Passed%20(428%20Assertions)-success?style=for-the-badge&logo=phpunit&logoColor=white)](https://phpunit.de)
[![License](https://img.shields.io/badge/License-MIT-blue.svg?style=for-the-badge)](LICENSE)

Portal Web Resmi dan Sistem Manajemen Ekosistem Terpadu **Unit Kegiatan Mahasiswa (UKM) Ilmu Komputer** — **Universitas Bina Bangsa Getsempena (UBBG)** Banda Aceh.

Dibangun dengan arsitektur **Clean MVC Laravel**, memadukan estetika antarmuka **Modern White Claymorphism** yang lembut dan taktil, sistem otentikasi cerdas berbasis multi-role (*Super Admin, Admin Divisi, dan Anggota/Mahasiswa*), serta otomatisasi dokumen pintar seperti **Kartu Tanda Anggota (KTA) Digital 2 Sisi**.

---

## 📌 Gambaran Umum Sistem

Portal ini berfungsi sebagai pusat komando dan etalase digital untuk 4 pilar divisi spesialisasi teknologi:
1. 💻 **Divisi Pemrograman (Software Engineering & Web)**
2. 🎨 **Divisi Multimedia (UI/UX, 3D Art & Motion Graphics)**
3. 🤖 **Divisi Internet of Things (IoT & Otomasi Perangkat Keras)**
4. 🛡️ **Divisi Cyber Security (Keamanan Siber, CTF & Ethical Hacking)**

---

## ✨ Fitur-Fitur Utama

### 1. 🌐 Portal Publik & Showcase Ekosistem
- **Beranda Interaktif:** Showcase divisi, agenda workshop, statistik anggota aktif dinamis, dan publikasi artikel/berita terkini.
- **Pendaftaran Terpadu (Open Recruitment):** Calon pendaftar memilih divisi dengan kontrol kuota buka/tutup dinamis, unggah berkas KTM, serta pembuatan akun login otomatis.
- **Cek Status Seleksi:** Pelacakan tahapan seleksi (pemberkasan, wawancara, hingga penetapan anggota).
- **Showcase Karya Mahasiswa:** Katalog proyek teknologi yang telah lulus kurasi dan moderasi pengurus.
- **Verifikasi Sertifikat Digital:** Pengecekan keaslian nomor seri sertifikat kegiatan secara instan.
- **Halaman Profil Pengembang:** Informasi pengembang resmi portal (*Gempur Budi Anarki*).

---

### 2. 🎓 Portal Mahasiswa (Student Member Workspace)
- **Dashboard Mandiri:** Ringkasan keaktifan, notifikasi pengumuman divisi, dan pintasan layanan.
- **Kartu Tanda Anggota (KTA) Digital 2 Sisi (CR80 ISO ID-1):**
  - **Sisi Depan:** Identitas resmi, pas foto proporsional anti-distorsi (*canvas cover rendering*), NIM, Program Studi, Fakultas, hologram keaslian, dan QR Code verifikasi.
  - **Sisi Belakang:** Strip magnetik keamanan cerdas, ketentuan tata tertib resmi keanggotaan, stempel digital sah, dan barcode fisik Code-128.
  - **Fitur Interaktif:** Tab switcher sisi depan/belakang, tombol balik kartu 3D, unduh **PDF Lengkap 2 Halaman Lanskap** via `jsPDF`, serta unduh **PNG HD** terpisah.
- **Presensi Mandiri dengan Passcode:**
  - Input kode akses kehadiran 6 karakter harian dari instruktur divisi.
  - Formulir izin / sakit dengan lampiran surat pendukung.
  - Riwayat presensi interaktif dengan filter status (*Semua, Hadir, Izin, Sakit, Alpa*) dan pencarian cepat (*instant search*).
- **Silabus & Arsip Pembelajaran:** Akses materi per modul, capaian belajar, materi unduhan, dan video instruksional.
- **Showcase & Pengajuan Proyek:** Mahasiswa dapat mengajukan portofolio karya, melampirkan tautan repositori/demo, serta membaca umpan balik/catatan revisi jika karya ditolak pengurus.
- **Manajemen Profil & Keamanan:** Pengubahan nomor telepon, profil GitHub, ganti foto profil avatar, dan pembaharuan kata sandi.

---

### 3. 🛡️ Panel Manajemen Pengurus & Admin CMS
- **Smart Role-Based Access Control (RBAC):**
  - **Super Admin:** Akses penuh lintas divisi, statistik eksekutif, manajemen akun pengguna, pembina, pengaturan rekrutmen kuota divisi, dan manajemen artikel berita.
  - **Admin Divisi:** Terkunci secara ketat dan aman pada divisi masing-masing (kelola presensi, silabus, dan kurasi karya mahasiswa).
- **Sistem Sesi Presensi & Passcode Generator:** Admin membuat sesi pertemuan, menetapkan materi & waktu kedaluwarsa, serta merilis kode passcode check-in 6 karakter.
- **Moderasi Karya Mahasiswa:** Panel persetujuan karya dengan modal tolak interaktif dan input catatan review/revisi untuk mahasiswa.
- **Konversi Pendaftar Lolos:** Konversi pelamar yang lolos seleksi menjadi anggota resmi dengan sinkronisasi relasi data anggota secara otomatis.
- **Sistem Penanganan Sesi Tahan Banting:** Endpoint `/logout` aman dari error `419 Page Expired` (mendukung `POST` & `GET` serta pengecualian CSRF middleware).

---

## 🛠️ Teknologi & Stack

| Kategori | Teknologi |
|---|---|
| **Backend Framework** | [Laravel 10.x](https://laravel.com) (PHP 8.1+) |
| **Database** | MySQL 8.0 / MariaDB / SQLite |
| **Frontend Styling** | Custom Vanilla CSS (Design System White Claymorphism) |
| **Icons & Font** | FontAwesome 6, Google Fonts (*Plus Jakarta Sans*, *JetBrains Mono*) |
| **Client-Side Rendering** | `html2canvas` & `jsPDF` (High DPI Vector Document Export) |
| **Testing** | PHPUnit (96 Test Cases, 428 Assertions, 100% Green) |
| **Environment** | Laragon / Local Web Server |

---

## 🚀 Panduan Instalasi & Menjalankan Lokal

### 1. Prasyarat Sistem
- PHP `>= 8.1` (dengan ekstensi `pdo_mysql`, `mbstring`, `fileinfo`, `gd`, `openssl`)
- Composer `>= 2.x`
- MySQL Server `>= 8.0` atau MariaDB
- Node.js & NPM (Opsional jika ingin build aset frontend)

### 2. Clone Repositori
```bash
git clone https://github.com/gempurbudianarki/ukmubbg.git
cd ukmubbg
```

### 3. Install Dependensi PHP
```bash
composer install
```

### 4. Salin Konfigurasi Environment
```bash
cp .env.example .env
```

Sesuaikan koneksi database pada file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ukm_ilkom
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi & Database Seeder
```bash
php artisan migrate --seed
```

### 7. Hubungkan Storage Link
```bash
php artisan storage:link
```

### 8. Jalankan Development Server
```bash
php artisan serve --host=127.0.0.1 --port=8000
```
Buka browser Anda dan kunjungi: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Akun Uji Coba Bawaan (Test Credentials)

Semua akun di bawah ini telah terdaftar otomatis melalui database seeder:

| Peran (Role) | Divisi | Email Login | Password | Akses URL |
|---|---|---|---|---|
| **Super Administrator** | Seluruh Divisi | `admin@ukmilkom.id` | `admin123` | `/admin/dashboard` |
| **Admin Divisi** | Pemrograman | `pemrograman@ukmilkom.id` | `pemrograman123` | `/admin/dashboard` |
| **Admin Divisi** | Multimedia | `multimedia@ukmilkom.id` | `multimedia123` | `/admin/dashboard` |
| **Admin Divisi** | IoT | `iot@ukmilkom.id` | `iot123` | `/admin/dashboard` |
| **Admin Divisi** | Cyber Security | `cyber@ukmilkom.id` | `cyber123` | `/admin/dashboard` |
| **Mahasiswa (Member)** | Pemrograman | `bintang@student.ac.id` | `student123` | `/student/dashboard` |

> *Catatan: Sistem menerapkan Smart Login Redirect. Akun `member` langsung diarahkan ke `/student/dashboard`, sementara akun `super_admin` dan `division_admin` diarahkan ke `/admin/dashboard`.*

---

## 🧪 Pengujian Otomatis (Automated Testing)

Untuk menjalankan seluruh rangkaian pengujian fitur:
```bash
php artisan test
```

Hasil verifikasi:
```text
Tests:    96 passed (428 assertions)
Duration: ~7.0s
Status:   100% GREEN
```

---

## 📁 Struktur Direktori Penting

```text
ukm/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/             # Controller Admin CMS (Divisi, Event, Oprec, Presensi, User, dll.)
│   │   │   ├── Student/           # Controller Mahasiswa (Dashboard, Presensi, KTA, Silabus, Proyek)
│   │   │   └── ...
│   │   └── Middleware/            # Guard autentikasi dan pengecualian CSRF
│   └── Models/                    # Eloquent Models (User, Member, Division, Project, Post, dll.)
├── database/
│   ├── migrations/                # Skema database & tabel
│   └── seeders/                   # Seeder data awal akun uji coba & divisi
├── resources/
│   └── views/
│       ├── admin/                 # Blade views untuk panel administrasi
│       ├── student/               # Blade views portal mahasiswa & KTA Digital
│       ├── home/                  # Halaman beranda utama
│       └── layouts/               # Template navbar, footer, & shell
├── routes/
│   └── web.php                    # Rute navigasi aplikasi
└── tests/
    └── Feature/                   # 29 File Feature Tests (Cakupan pengujian menyeluruh)
```

---

## 👨‍💻 Pengembang (Developer)

* **Nama:** Gempur Budi Anarki
* **Afiliasi:** Program Studi Ilmu Komputer, Fakultas Sains, Teknologi, dan Ilmu Kesehatan (FSTIK)
* **Institusi:** Universitas Bina Bangsa Getsempena (UBBG) Banda Aceh
* **GitHub:** [@gempurbudianarki](https://github.com/gempurbudianarki)

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah lisensi terbuka [MIT License](LICENSE).
Hak Cipta © 2026 UKM Ilmu Komputer - Universitas Bina Bangsa Getsempena.
