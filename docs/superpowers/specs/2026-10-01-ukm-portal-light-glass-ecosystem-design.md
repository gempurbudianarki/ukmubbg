# Desain Spesifikasi: UKM Ilmu Komputer Web Portal & CMS (Refined Light & Glass Ecosystem)

## 1. Executive Summary & Goals
Dokumen ini merumuskan perombakan menyeluruh terhadap Web Portal & CMS UKM Ilmu Komputer. Proyek ini bertujuan mentransformasi antarmuka yang sebelumnya terkesan kaku/generik ("AI slop") menjadi web portal kampus modern bertaraf internasional dengan gaya **Refined Minimalist Light & Glass** (terinspirasi dari estetika Stripe, Apple, dan Vercel).

Selain perombakan visual, sistem ini melengkapi ekosistem organisasi mahasiswa dengan 6 pilar fitur esensial:
1. **Showcase Karya & Portofolio Mahasiswa** (proyek nyata per divisi).
2. **Event & Workshop Hub** (kalender agenda, workshop, dan pendaftaran).
3. **Struktur Organisasi & Tim Pengurus** (bagan hierarki BPH & 4 Divisi interaktif).
4. **Penyempurnaan Open Recruitment Berjenjang** (alur berkas, wawancara, upload KTM, notifikasi seleksi transparan).
5. **Galeri & Momen Kegiatan** (dokumentasi aktivitas dan prestasi).
6. **Sistem Verifikasi E-Sertifikat & Anggota** (cek validitas sertifikat via kode unik/QR).

---

## 2. Visual Design System ("Refined Minimalist Light & Glass")

### 2.1 Color Palette & Tokens
- **Background Utama**: `--bg-body: #fbfcfd`, `--bg-secondary: #f4f6f9`
- **Surface (Glassmorphism)**: 
  - `--glass-card: rgba(255, 255, 255, 0.88)`
  - `--glass-border: rgba(226, 232, 240, 0.8)`
  - `--glass-border-focus: rgba(37, 99, 235, 0.4)`
  - `--glass-blur: blur(16px)`
- **Warna Identitas & Divisi**:
  - *Brand Primary*: `--primary: #0f172a` (Deep Slate), `--accent-blue: #2563eb` (Royal Tech Blue)
  - *Pemrograman*: `#0284c7` (Cyber Ocean)
  - *Multimedia*: `#8b5cf6` (Electric Violet)
  - *Internet of Things*: `#10b981` (Emerald Tech)
  - *Cyber Security*: `#f43f5e` (Crimson Shield)
- **Status & Feedback**:
  - *Success*: `#10b981`, *Warning*: `#f59e0b`, *Danger*: `#ef4444`, *Info*: `#0ea5e9`
- **Tipografi**:
  - Display & Body: `'Plus Jakarta Sans'`, sans-serif (tracking `-0.02em` untuk headline, high legibility)
  - Kode & Label Teknis: `'JetBrains Mono'`, monospace (untuk NIM, registration code, certificate code, tech stack tags)

### 2.2 Elevation & Components
- **Shadows**:
  - `--shadow-card: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02)`
  - `--shadow-card-hover: 0 20px 30px -10px rgba(15, 23, 42, 0.08), 0 8px 12px -4px rgba(15, 23, 42, 0.04)`
- **Pill Badges**: Bordir halus dengan ambient dot yang berdenyut lembut untuk menunjukkan status live.
- **Button System**:
  - `.btn-primary`: Aksen royal blue dengan inset highlight halus, text white, tactile hover.
  - `.btn-glass`: Putih translusen dengan border crisp dan backdrop-filter.
  - `.btn-outline`: Border slate-200 yang berubah warna dinamis saat hover.

---

## 3. Skema Basis Data & Model Relasi

### 3.1 Tabel `projects` (Karya & Portofolio Mahasiswa)
- `id` (BIGINT, PK, Auto Increment)
- `division_id` (BIGINT, Nullable, FK ke `divisions.id` - null jika kolaborasi multi-divisi)
- `title` (VARCHAR 255)
- `slug` (VARCHAR 255, Unique)
- `description` (TEXT)
- `author_names` (VARCHAR 255 - nama-nama mahasiswa perancang)
- `tech_stack` (JSON - contoh: `["Laravel", "Flutter", "Tailwind", "MySQL"]`)
- `demo_url` (VARCHAR 255, Nullable)
- `repo_url` (VARCHAR 255, Nullable)
- `thumbnail` (VARCHAR 255, Nullable)
- `is_featured` (BOOLEAN, default: false)
- `timestamps`

### 3.2 Tabel `events` (Agenda & Workshop Hub)
- `id` (BIGINT, PK, Auto Increment)
- `division_id` (BIGINT, Nullable, FK ke `divisions.id`)
- `title` (VARCHAR 255)
- `slug` (VARCHAR 255, Unique)
- `description` (TEXT)
- `banner_image` (VARCHAR 255, Nullable)
- `event_date` (DATE)
- `time_start` (TIME)
- `time_end` (TIME, Nullable)
- `location_type` (ENUM: `'offline'`, `'online'`, `'hybrid'`)
- `location_venue` (VARCHAR 255 - e.g. "Lab Terpadu Komputer Lt. 3" atau "Zoom Meeting")
- `registration_link` (VARCHAR 255, Nullable)
- `max_participants` (INT, Nullable)
- `status` (ENUM: `'upcoming'`, `'completed'`, `'cancelled'`)
- `timestamps`

### 3.3 Tabel `officers` (Struktur Organisasi)
- `id` (BIGINT, PK, Auto Increment)
- `name` (VARCHAR 255)
- `nim` (VARCHAR 30)
- `period` (VARCHAR 20 - e.g. "2026/2027")
- `department_level` (ENUM: `'bph'`, `'pemrograman'`, `'multimedia'`, `'iot'`, `'cyber'`)
- `position` (VARCHAR 100 - e.g. "Ketua Umum", "Sekretaris Umum", "Koordinator Divisi", "Staff Riset")
- `photo` (VARCHAR 255, Nullable)
- `social_links` (JSON - keys: `linkedin`, `github`, `instagram`)
- `sort_order` (INT, default: 0)
- `timestamps`

### 3.4 Tabel `galleries` (Dokumentasi Aktivitas & Prestasi)
- `id` (BIGINT, PK, Auto Increment)
- `title` (VARCHAR 255)
- `category` (VARCHAR 100 - e.g. "Workshop", "Hackathon", "Musyawarah", "Prestasi")
- `image_path` (VARCHAR 255)
- `caption` (TEXT, Nullable)
- `event_date` (DATE, Nullable)
- `timestamps`

### 3.5 Tabel `certificates` (Sistem Verifikasi E-Sertifikat & Anggota)
- `id` (BIGINT, PK, Auto Increment)
- `certificate_code` (VARCHAR 50, Unique, Index - e.g. "CERT-ILKOM-2026-0812")
- `recipient_name` (VARCHAR 255)
- `recipient_nim` (VARCHAR 30, Nullable)
- `recipient_email` (VARCHAR 255, Nullable)
- `event_name` (VARCHAR 255)
- `role_as` (VARCHAR 100 - "Peserta", "Pemateri", "Juara 1 Hackathon", "Pengurus Aktif")
- `issue_date` (DATE)
- `file_path` (VARCHAR 255, Nullable)
- `timestamps`

### 3.6 Peningkatan Tabel `recruitments`
Penambahan kolom:
- `selection_stage` (ENUM: `'administrasi'`, `'wawancara'`, `'diterima'`, `'ditolak'`, default: `'administrasi'`)
- `file_ktm` (VARCHAR 255, Nullable)
- `file_cv` (VARCHAR 255, Nullable)
- `interview_schedule` (DATETIME, Nullable)
- `interview_location` (VARCHAR 255, Nullable)

---

## 4. Alur Pengguna & Halaman Web (Frontend)

1. **Navbar Translucent Glass (`layouts.navbar`)**:
   - Brand Icon bercahaya dengan logo UKM Ilmu Komputer.
   - Menu Navigasi:
     - Beranda (`/`)
     - Divisi Keahlian (`/#divisions` atau `/divisi/{slug}`)
     - Karya Mahasiswa (`/proyek`)
     - Agenda & Workshop (`/events`)
     - Struktur Pengurus (`/pengurus`)
     - Galeri (`/galeri`)
     - Cek Sertifikat (`/verifikasi`)
   - Tombol CTA: Status Oprec (Pill Badge) + "Daftar / Cek Status".

2. **Beranda (`home.index`)**:
   - Hero Section dengan mesh gradient light halus, badge live status oprec, headline berkarakter riset & teknologi, serta statistik counter organisasi.
   - Bento Grid 4 Divisi dengan color accent dinamis, daftar fokus teknologi, kutipan pembina & ketua divisi.
   - Grid Proyek Pilihan (Featured Projects) dengan tab filter instan.
   - Strip Agenda Workshop & Bootcamp Terdekat.
   - Wawasan & Publikasi Terkini dari Divisi.
   - Banner Pendaftaran Oprec dengan deadline counter.

3. **Karya & Portofolio (`projects.index`)**:
   - Filter divisi, search bar karya, kartu interaktif dengan thumbnail, badge tech stack (Laravel, Flutter, ESP32, Figma, Wireshark), live demo button, dan GitHub repo link.

4. **Agenda & Workshop (`events.index`)**:
   - Card kalender terorganisir per status (Akan Datang vs Riwayat), badge tanggal, kuota kursi, dan form RSVP pendaftaran.

5. **Struktur Pengurus (`officers.index`)**:
   - Bagan organisasi modern:
     - Dewan Pembina Dosen
     - Badan Pengurus Harian (BPH): Ketum, Sekum, Bendum
     - 4 Koordinator Divisi beserta tim anggota dengan kartu avatar, jabatan, dan icon sosial.

6. **Galeri Dokumentasi (`galleries.index`)**:
   - Grid foto responsif dengan filter kategori dan modal preview foto.

7. **Verifikasi Sertifikat (`certificates.verify`)**:
   - Input pencarian kode sertifikat / NIM.
   - Kartu sertifikat digital yang elegan dengan stempel verifikasi hijau resmi, tanda tangan digital, tanggal terbit, dan role peserta.

8. **Pendaftaran & Cek Status Oprec (`recruitment.index` & `recruitment.status`)**:
   - Multi-step form bersih dengan validasi form instan, input biodata, upload KTM/portfolio, dan halaman sukses dengan kode pendaftaran siap salin.
   - Halaman status interaktif yang menampilkan progress bar seleksi (Administrasi $\rightarrow$ Wawancara $\rightarrow$ Pengumuman).

---

## 5. Panel Administrasi (CMS Terpadu)

- Mengadopsi arsitektur Blade terstruktur di `resources/views/admin`:
  - **Dashboard**: Ringkasan pendaftar, proyek, artikel, dan status oprec.
  - **Kelola Karya Mahasiswa**: CRUD proyek dengan upload thumbnail dan input tech stack tags.
  - **Kelola Agenda & Event**: CRUD agenda dengan penentuan batas peserta dan lokasi.
  - **Kelola Pengurus**: Manajemen bagan dan foto pengurus UKM.
  - **Kelola Galeri**: Upload foto momen dan kategori.
  - **Kelola E-Sertifikat**: Generator nomor sertifikat dan daftar sertifikat yang dapat dicari publik.
  - **Kelola Oprec**: Pipeline verifikasi berkas, perubahan status seleksi, dan penjadwalan wawancara.
  - **Kelola Artikel & Divisi**: Fasilitas pengelolaan wawasan dan bio kepengurusan divisi.

---

## 6. Rencana Pengujian & Keamanan
- **Keamanan**: CSRF Protection di seluruh formulir, sanitasi nama file upload, MIME validation (JPEG, PNG, PDF), prepared statements via Eloquent ORM.
- **Validasi Input**: Validasi ketat NIM, email, format kode pendaftaran, dan ukuran file upload maksimal 2MB.
- **Performance**: Vanilla CSS modern yang ringan tanpa bundle JS berat, lazy loading gambar, indexing database pada slug dan registration/certificate codes.
