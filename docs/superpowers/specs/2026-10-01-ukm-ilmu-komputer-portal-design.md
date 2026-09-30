# Spesifikasi Desain: Web Portal & CMS UKM Ilmu Komputer

**Tanggal**: 2026-10-01  
**Status**: Disetujui  
**Target Platform**: PHP 8.1+ / Apache / MySQL (Laragon Localhost & Shared Hosting Ready)

---

## 1. Ringkasan Proyek

Portal web resmi untuk UKM Program Studi Ilmu Komputer yang berfungsi sebagai:
1. **Pusat Publikasi & Kanal Kegiatan**: Media publikasi artikel, berita riset, tutorial, dan dokumentasi kegiatan divisi.
2. **Kanal Profil & Biodata 4 Divisi**:
   - Divisi Pemrograman (Software Engineering / Web / Mobile)
   - Divisi Multimedia (Desain Grafis / UI-UX / Video / Animasi)
   - Divisi Internet of Things (IoT / Robotika / Embedded Systems)
   - Divisi Cyber Security (Keamanan Siber / Jaringan / Ethical Hacking)
   - Masing-masing dilengkapi biodata profil Pembina (Dosen) dan Ketua Divisi (Mahasiswa).
3. **Pusat Open Recruitment Terpadu**: Formulir pendaftaran satu pintu untuk calon anggota baru dengan pemilihan divisi yang diminati serta sistem pengecekan status penerimaan.
4. **Sistem CMS Multi-Role**: Panel administrasi mandiri ala WordPress untuk Super Admin (Pengurus Pusat UKM) dan Admin Divisi.

---

## 2. Arsitektur & Teknologi

- **Backend**: PHP 8.1+ Native OOP dengan pola arsitektur MVC (Model-View-Controller) murni tanpa framework eksternal yang berat, zero-friction di Laragon.
- **Database**: MySQL / MariaDB diakses menggunakan `PDO` dengan *prepared statements* anti SQL Injection.
- **Frontend**: Clean & Professional Design System (Vanilla CSS modern dengan CSS variables, typography Google Fonts Plus Jakarta Sans / Inter, ikon SVG tajam, micro-interactions, fully responsive mobile & desktop).
- **Routing**: Clean URL via Apache `.htaccess` (`/`, `/divisi/{slug}`, `/berita`, `/berita/{slug}`, `/pendaftaran`, `/pendaftaran/cek-status`, `/admin/*`).
- **Autentikasi & Keamanan**:
  - Session berbasis cookie HTTPOnly dengan session regeneration.
  - Password hashing algoritma `PASSWORD_BCRYPT`.
  - Token CSRF di seluruh form request POST.
  - Sanitasi input data (XSS escaping & MIME-type validation saat upload file/foto).

---

## 3. Skema Basis Data (Database Schema)

### 3.1. Tabel `users`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| `id` | INT AUTO_INCREMENT PRIMARY KEY | ID Pengguna |
| `name` | VARCHAR(100) | Nama lengkap pengurus/admin |
| `email` | VARCHAR(100) UNIQUE | Email login |
| `password` | VARCHAR(255) | Hash password bcrypt |
| `role` | ENUM('super_admin', 'division_admin') | Hak akses sistem |
| `division_id` | INT NULL | ID divisi jika role adalah division_admin |
| `avatar` | VARCHAR(255) NULL | Path foto profil pengurus |
| `created_at` | DATETIME DEFAULT CURRENT_TIMESTAMP | Waktu pembuatan akun |

### 3.2. Tabel `divisions`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| `id` | INT AUTO_INCREMENT PRIMARY KEY | ID Divisi |
| `slug` | VARCHAR(50) UNIQUE | Slug URL (`pemrograman`, `multimedia`, `iot`, `cyber-security`) |
| `name` | VARCHAR(100) | Nama divisi lengkap |
| `tagline` | VARCHAR(255) | Slogan / deskripsi singkat |
| `description` | TEXT | Deskripsi mendalam tentang bidang |
| `focus_topics` | TEXT | Topik/keahlian yang dipelajari (JSON array string) |
| `icon_svg` | TEXT | Ikon visual SVG |
| `banner_image` | VARCHAR(255) NULL | Foto banner divisi |
| `vision` | TEXT | Visi divisi |
| `mission` | TEXT | Misi divisi (daftar poin) |
| `adviser_name` | VARCHAR(100) | Nama dosen pembina divisi |
| `adviser_title` | VARCHAR(100) | Gelar / NIDN pembina |
| `adviser_photo` | VARCHAR(255) NULL | Foto formal pembina |
| `leader_name` | VARCHAR(100) | Nama mahasiswa ketua divisi |
| `leader_nim` | VARCHAR(30) | NIM ketua divisi |
| `leader_photo` | VARCHAR(255) NULL | Foto formal ketua divisi |
| `leader_bio` | TEXT | Sambutan / bio singkat ketua divisi |
| `social_links` | TEXT NULL | Tautan media sosial (JSON: instagram, github, linkedin) |

### 3.3. Tabel `posts`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| `id` | INT AUTO_INCREMENT PRIMARY KEY | ID Publikasi |
| `division_id` | INT | Relasi ke `divisions.id` |
| `author_id` | INT | Relasi ke `users.id` |
| `title` | VARCHAR(255) | Judul artikel |
| `slug` | VARCHAR(255) UNIQUE | Slug URL ramah SEO |
| `excerpt` | VARCHAR(350) | Cuplikan isi artikel untuk kartu preview |
| `content` | LONGTEXT | Isi artikel (HTML / Rich Text) |
| `thumbnail` | VARCHAR(255) NULL | Gambar utama postingan |
| `category` | ENUM('kegiatan', 'tutorial', 'berita', 'proyek') | Kategori artikel |
| `status` | ENUM('published', 'draft') | Status publikasi |
| `views_count` | INT DEFAULT 0 | Jumlah pembaca artikel |
| `created_at` | DATETIME DEFAULT CURRENT_TIMESTAMP | Tanggal dibuat |
| `updated_at` | DATETIME ON UPDATE CURRENT_TIMESTAMP | Tanggal diupdate |

### 3.4. Tabel `recruitments`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| `id` | INT AUTO_INCREMENT PRIMARY KEY | ID Pendaftaran |
| `registration_code` | VARCHAR(20) UNIQUE | Kode unik registrasi (misal: `UKM-2026-XXXX`) |
| `full_name` | VARCHAR(100) | Nama lengkap pendaftar |
| `nim` | VARCHAR(30) | Nomor Induk Mahasiswa |
| `email` | VARCHAR(100) | Email mahasiswa aktif |
| `phone_whatsapp` | VARCHAR(25) | No. WhatsApp aktif |
| `semester` | INT | Semester saat ini |
| `class_group` | VARCHAR(20) | Kelas / Rombel (misal: IF-A, IF-B) |
| `first_choice_division_id` | INT | Pilihan divisi utama |
| `second_choice_division_id` | INT NULL | Pilihan divisi alternatif |
| `reason_to_join` | TEXT | Motivasi & alasan memilih divisi tersebut |
| `portfolio_url` | VARCHAR(255) NULL | Link portofolio / GitHub / Drive |
| `status` | ENUM('pending', 'interview', 'accepted', 'rejected') | Status seleksi |
| `admin_notes` | TEXT NULL | Catatan internal pengurus/pewawancara |
| `created_at` | DATETIME DEFAULT CURRENT_TIMESTAMP | Waktu registrasi |

### 3.5. Tabel `settings`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| `key_name` | VARCHAR(50) PRIMARY KEY | Kunci konfigurasi |
| `value` | TEXT | Nilai konfigurasi |
*(Contoh kunci: `site_name`, `site_tagline`, `recruitment_open`, `recruitment_deadline`, `contact_email`, `contact_instagram`)*

---

## 4. Antarmuka Publik & Fitur Pengguna

1. **Header & Navigasi**:
   - Logo resmi UKM Ilmu Komputer & Brand Mark.
   - Menu navigasi: Beranda, Divisi (Dropdown 4 bidang), Publikasi/Kanal, Tentang Kami, Cek Status Pendaftaran.
   - Tombol CTA: "Daftar Anggota" (disertai badge status aktif).

2. **Beranda (Home)**:
   - Hero banner modern dengan aksen teknologi yang bersih, tipografi tegas.
   - Live badge status Open Recruitment.
   - Section Grid 4 Divisi dengan highlight nama ketua dan pembina.
   - Feed publikasi kegiatan & artikel teranyar dengan filter per divisi.
   - Stat counter (4 Divisi, 100+ Anggota Aktif, Puluhan Proyek).

3. **Halaman Kanal Divisi (`/divisi/{slug}`)**:
   - Banner & deskripsi fokus bidang keahlian.
   - Showcase Biodata Pembina (Foto, Nama, Gelar) dan Ketua Divisi (Foto, Nama, NIM, Bio, Tautan Sosial).
   - Daftar keahlian / teknologi yang diasah dalam divisi (misal Cyber Security: Kali Linux, Wireshark, OWASP Top 10, Network Defense).
   - Feed publikasi khusus yang diterbitkan oleh divisi terkait.
   - Tombol "Daftar Divisi Ini" yang langsung memicu form pendaftaran dengan pre-select divisi tersebut.

4. **Halaman Publikasi (`/berita` & `/berita/{slug}`)**:
   - Layout kartu modern, pencarian artikel berdasarkan kata kunci, filter kategori dan divisi.
   - Tampilan detail artikel format editorial yang nyaman dibaca, tombol share ke sosial media, dan artikel terkait.

5. **Formulir Pendaftaran & Cek Status (`/pendaftaran` & `/pendaftaran/cek-status`)**:
   - Validasi data di sisi client dan server (NIM unik per periode pendaftaran).
   - Notifikasi sukses langsung menyajikan kode pendaftaran dan kartu bukti pendaftaran online.
   - Fitur "Cek Status" menggunakan NIM untuk mengecek progres seleksi (Pending, Jadwal Interview, Diterima).

---

## 5. CMS & Dashboard Admin

1. **Sistem Login Multi-Role (`/admin/login`)**:
   - Super Admin: Akses penuh semua modul.
   - Division Admin: Akses dibatasi pada postingan divisinya, biodata pengurus divisinya, dan data calon anggota yang memilih divisinya.

2. **Dashboard Overview**:
   - Ringkasan metriks pendaftar, total artikel, dan grafik pendaftar per divisi.
   - Switcher Buka/Tutup Open Recruitment untuk Super Admin.

3. **Modul Publikasi (Posts Management)**:
   - List data artikel dengan pencarian & filter status (Published/Draft).
   - Form pembuatan artikel baru dengan Rich Text Editor, media upload thumbnail, dan preview.

4. **Modul Biodata Divisi**:
   - Update profil divisi, visi, misi, serta ganti foto dan data Pembina & Ketua Divisi secara mandiri.

5. **Modul Open Recruitment (Pusat Seleksi)**:
   - Tabel pendaftar lengkap dengan badge status warna-warni (*Pending: Kuning, Interview: Biru, Diterima: Hijau, Ditolak: Abu-abu*).
   - Aksi cepat modal: Lihat detail data diri, klik nomor WhatsApp langsung terbuka ke chat calon anggota, input catatan interview, ubah status seleksi.
   - Fitur **Export CSV / Excel** untuk arsip dan pencetakan data calon anggota.

6. **Modul Manajemen User (Super Admin)**:
   - Kelola akun admin untuk 4 divisi (tambah user, reset password, nonaktifkan).

---

## 6. Kualitas Visual & Desain (Clean & Professional)

- **Desain Standar Tinggi**: Menghindari tampilan polos/template gratisan jadul. Menerapkan gaya web produk tech enterprise (Vercel/Linear style dengan aksen kampus modern).
- **Palet Warna**:
  - Background: `#f8fafc` (Clean Light Slate) & kartu `#ffffff`.
  - Primary Brand: `#1e3a8a` (Deep Blue Royal) / `#2563eb` (Tech Blue).
  - Aksen Divisi:
    - Pemrograman: `#0284c7` (Sky Blue / Code)
    - Multimedia: `#8b5cf6` (Creative Violet)
    - IoT: `#10b981` (Emerald Green / Hardware)
    - Cyber Security: `#f43f5e` (Crimson Rose / Shield)
- **Responsif**: 100% responsif pada smartphone (iPhone, Android) dan desktop.
