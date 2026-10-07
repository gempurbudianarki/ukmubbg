# Design Spec: White Claymorphism UI/UX Transformation

**Tanggal**: 2026-10-06  
**Status**: Approved by User (Option 1: Modern Clean-White Claymorphism)  
**Tujuan**: Mentransformasi seluruh UI/UX web UKM Ilmu Komputer (Portal Publik, Portal Mahasiswa, dan Admin CMS) menjadi sistem desain **White Claymorphism** modern yang empuk (*puffy*), melayang (*floating*), memiliki tekstur sentuhan 3D nyata (*tactile*), dan tetap ergonomis serta profesional.

---

## 1. Fondasi Sistem Desain & Token CSS (`portal.css`)

### A. Palet & Latar Belakang
- **Body Canvas**: `--bg-body: #eef3f8` (nada soft-slate terang yang memberikan kontras sempurna untuk elemen clay putih).
- **Surface**: `--bg-surface: #ffffff` (putih susu bersih).
- **Subtle Surface**: `--bg-subtle: #f8fafc`.
- **Text & Contrast**:
  - Judul / Heading: `--slate-900: #0f172a` (kontras tinggi, tebal, terbaca sangat jelas).
  - Body Text: `--slate-700: #334155` dan `--slate-600: #475569`.
  - Muted Text: `--slate-400: #94a3b8`.

### B. Formula Dual-Shadow Claymorphism (Elevasi 3D)
Setiap elemen clay menggunakan kombinasi bayangan luar (*outward drop shadow*) dan bayangan dalam (*inset highlight & shadow*):

```css
/* Clay Card Standar */
--clay-card: 
  8px 14px 26px rgba(160, 175, 200, 0.22),
  -6px -6px 16px rgba(255, 255, 255, 0.9),
  inset 3px 3px 6px rgba(255, 255, 255, 0.95),
  inset -3px -3px 8px rgba(165, 180, 205, 0.2);

/* Clay Card Hover (Mengangkat lebih tinggi) */
--clay-card-hover: 
  14px 24px 38px rgba(150, 168, 195, 0.28),
  -8px -8px 20px rgba(255, 255, 255, 0.95),
  inset 4px 4px 8px rgba(255, 255, 255, 0.98),
  inset -4px -4px 10px rgba(165, 180, 205, 0.22);

/* Clay Button Tactile */
--clay-btn: 
  5px 8px 18px rgba(160, 175, 200, 0.24),
  -4px -4px 12px rgba(255, 255, 255, 0.9),
  inset 2px 2px 4px rgba(255, 255, 255, 0.95),
  inset -2px -2px 6px rgba(165, 180, 205, 0.22);

/* Clay Input Debossed (Cekung ke dalam seperti diukir) */
--clay-input: 
  inset 3px 3px 6px rgba(160, 175, 200, 0.22),
  inset -2px -2px 5px rgba(255, 255, 255, 0.9),
  0 1px 2px rgba(255, 255, 255, 0.8);
```

### C. Rounded Corners Puffy
- `--radius-clay-xs`: `10px`
- `--radius-clay-sm`: `16px`
- `--radius-clay-md`: `22px`
- `--radius-clay-lg`: `30px`
- `--radius-clay-pill`: `9999px`

### D. 4 Clay Division Accent Pills
- **Pemrograman**: Cyan Blue (`#0284c7`, background pill: `#e0f2fe`, inset clay bevel)
- **Multimedia**: Royal Violet (`#8b5cf6`, background pill: `#ede9fe`, inset clay bevel)
- **IoT**: Emerald Mint (`#10b981`, background pill: `#d1fae5`, inset clay bevel)
- **Cyber Security**: Rose Crimson (`#f43f5e`, background pill: `#ffe4e6`, inset clay bevel)

---

## 2. Rincian Desain Antarmuka Tiap Modul

### A. Portal Publik
1. **Navbar Sticky**:
   - Berbentuk kapsul mengambang (*floating pill capsule*) dengan warna putih clay, dual shadow lembut, dan item menu aktif berupa pill timbul.
2. **Hero Section & Beranda**:
   - Kartu statistik bertekstur clay dengan angka tebal dan badge ikon 3D empuk.
   - 4 Card Divisi: Dibuat seperti lempengan clay interaktif dengan sudut membulat 28px, ikon divisi bertekstur 3D timbul, dan efek angkat saat kursor diarahkan (*hover lift*).
3. **Karya & Agenda**:
   - Card proyek & event dengan thumbnail foto beradius 18px, badge divisi clay pastel, dan tombol aksi 3D.
4. **Formulir Pendaftaran (Oprec)**:
   - Kontainer multi-step dengan kartu clay putih besar.
   - Input text, textarea, select, dan file upload mengadopsi gaya *debossed clay* (cekung empuk).
   - Stepper penunjuk langkah berupa bulatan 3D clay dengan efek glow lembut.
5. **Kanal Pengurus, Galeri, Verifikasi, & Riset**:
   - Kartu pengurus beravatar bundar dengan bezel clay.
   - Kartu verifikasi sertifikat dengan badge status validasi hijau timbul.
   - Card artikel dengan preview teks yang rapi dan elegan.

### B. Portal Mahasiswa (Student Hub)
1. **Sidebar & Topbar**:
   - Beralih ke tema light clay yang serasi dengan nuansa utama (`#ffffff` sidebar dengan latar `#eef3f8`).
   - Link navigasi berstatus aktif memiliki bayangan timbul clay biru lembut dengan teks tajam.
   - Mini Profile Card di sidebar bertekstur clay dengan foto profil berbezel melingkar 3D.
2. **Dashboard Mahasiswa**:
   - Banner sambutan dengan bayangan clay melayang (*floating clay card*).
   - Stepper seleksi rekrutmen: Bulatan tahapan dibuat 3D clay bertekstur empuk.
   - Metrik kehadiran dan jadwal wawancara tersusun dalam grid clay cards.
3. **Kartu Tanda Anggota (KTA) Digital**:
   - Kartu identitas 3D clay premium: Sudut 24px, logo UKM berhologram halus, barcode & QR code dengan frame cekung, dan foto profil mahasiswa bertekstur timbul.
4. **Presensi & Silabus**:
   - Kotak silabus akademik bermaterial clay putih.
   - Status kehadiran (*Hadir / Izin / Sakit / Alpa*) menggunakan pill clay warna-warni yang menggemaskan.
5. **Halaman Edit Profil**:
   - Card preview avatar interaktif dan form input debossed clay.

### C. Admin CMS Panel
1. **Layout Admin (Sidebar, Header, Main Content)**:
   - Sidebar putih clay yang bersih dan rapi, ikon navigasi bersahabat.
   - Topbar floating dengan nama administrator dan avatar timbul.
2. **Stat Cards & Widgets**:
   - Total pendaftar, anggota aktif, kegiatan, dan postingan dibungkus kartu clay putih dengan persentase tren timbul.
3. **Tabel Data (Rekrutmen, Anggota, Presensi, Modul Lain)**:
   - Tabel dikelilingi wrapper kartu clay beradius 22px dengan garis pemisah tipis dan lembut.
   - Baris tabel memiliki transisi hover yang halus.
   - Thumbnail foto pendaftar/anggota dibuat bundar dengan efek bezel timbul.
4. **Pusat Rekrutmen & Attendance Sheet**:
   - Form pembuatan sesi pertemuan dan checklist presensi anggota dikemas dalam card clay putih dengan tombol switch/radio empuk.

---

## 3. Interaksi & Animasi Tactile
- **Hover**: Elemen naik sejauh `-3px` s.d. `-5px` dengan pelebaran bayangan ambient luar.
- **Active / Click**: Elemen menyusut lembut `scale(0.98)` atau `translateY(1px)` dengan penurunan bayangan luar dan penegasan bayangan dalam, memberikan umpan balik taktil seperti menekan tombol karet/plastisin asli.
- **Transisi**: Menggunakan cubic-bezier ergonomis `cubic-bezier(0.16, 1, 0.3, 1)` berdurasi `0.22s`.

---

## 4. Rencana Verifikasi
- Menjalankan kembali seluruh test suite (`php artisan test`) untuk memastikan tidak ada perubahan logika, routing, atau fungsionalitas yang terganggu (harus tetap 60 passed).
- Memeriksa tampilan visual melalui browser untuk memastikan estetika White Claymorphism tampil sempurna di resolusi desktop dan mobile.
