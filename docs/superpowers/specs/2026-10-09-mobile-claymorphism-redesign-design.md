# Design Spec: Mobile Responsive White Claymorphism UI/UX Transformation

**Tanggal**: 2026-10-09  
**Status**: Approved by User  
**Skill References**: `ui-ux-pro-max` (Design Intelligence: 67 Styles, 96 Palettes, Mobile Ergonomics & Claymorphism Best Practices)  
**Tujuan**: Mentransformasi seluruh antarmuka web UKM Ilmu Komputer UBBG (Portal Publik, Portal Mahasiswa, dan Admin CMS Panel) agar 100% responsif, ergonomis di genggaman layar HP (360px - 480px, tablet 768px, desktop), dengan tetap mempertahankan estetika khas **Modern White Claymorphism** (sentuhan 3D lembut, bayangan ganda inset/outset, sudut kurva puffy, dan palet aksen 4 divisi).

---

## 1. Fondasi Sistem Desain & Token CSS Responsif (`portal.css`)

### A. Breakpoints Ergonomis
- **Mobile Compact (xs)**: `<= 480px` (fokus utama optimasi HP)
- **Mobile Landscape / Phablet (sm)**: `481px - 640px`
- **Tablet / Small Laptop (md)**: `641px - 900px`
- **Desktop (lg)**: `901px - 1240px`
- **Wide Desktop (xl)**: `> 1240px`

### B. Touch Targets & Ergonomi Jari
- Minimum ukuran area interaksi (*touch target*): `44px x 44px` untuk semua tombol navigasi, pill buttons, icon triggers, dan form check-ins.
- Spacing safe-area untuk perangkat dengan navigasi gesture / notch:
  - Header & Navbar padding adaptif: `padding-top: max(1rem, env(safe-area-inset-top))`
  - Bottom mobile drawers: `padding-bottom: max(1.5rem, env(safe-area-inset-bottom))`

### C. Claymorphism Shadows Khusus Mobile (Lightweight GPU Profile)
Pada layar ponsel, bayangan ganda disesuaikan agar tidak berat saat scrolling di perangkat low-end:
```css
@media (max-width: 640px) {
  :root {
    --clay-card: 4px 8px 18px rgba(160, 175, 202, 0.22), -4px -4px 12px #ffffff, inset 1.5px 1.5px 3px rgba(255, 255, 255, 0.95), inset -2px -2px 5px rgba(160, 175, 202, 0.14);
    --clay-card-hover: 6px 12px 22px rgba(150, 168, 195, 0.26), -5px -5px 14px #ffffff, inset 2px 2px 4px rgba(255, 255, 255, 0.98), inset -2.5px -2.5px 6px rgba(160, 175, 202, 0.16);
    --clay-btn: 3px 6px 14px rgba(160, 175, 202, 0.22), -3px -3px 10px #ffffff, inset 1.5px 1.5px 3px rgba(255, 255, 255, 0.95), inset -1.5px -1.5px 4px rgba(160, 175, 202, 0.15);
    --radius-lg: 20px;
    --radius-xl: 24px;
  }
}
```

### D. Tipografi Adaptif (Fluid Typography)
- Hero Title: `font-size: clamp(1.75rem, 5.5vw, 2.95rem)`
- Section Titles: `font-size: clamp(1.4rem, 4.2vw, 2.25rem)`
- Card Headings: `font-size: clamp(1.05rem, 3.2vw, 1.35rem)`
- Body text & inputs: minimum `16px` pada mobile agar browser mobile (Safari/Chrome iOS & Android) tidak melakukan *auto-zoom* yang mengganggu saat fokus input.

---

## 2. Arsitektur Komponen Per Sub-Sistem

### A. Sub-Sistem 1: Portal Publik (`layouts.app`)
1. **Floating Capsule Navbar**:
   - Di mobile (`<= 900px`), menu horizontal disembunyikan.
   - Hamburger button tactile clay dengan animasi open/close.
   - Drawer mobile clay sheet turun dari navbar dengan border putih tebal dan bayangan lembut, menyediakan navigasi ke Beranda, 4 Divisi, Karya, Berita, Events, Verifikasi KTA, dan tombol CTA Pendaftaran/Login.
2. **Hero Section**:
   - Layout split horizontal beralih ke vertikal: Teks sambutan & CTA di atas, logo resmi UKM di bawah dengan dimensi proporsional (`width: min(220px, 60vw)`).
   - Stat Strip: Beralih dari 4 kolom menjadi 2x2 grid dengan divider rapi dan angka yang mudah dibaca.
3. **Bento Grid & Division Cards**:
   - Grid 2 kolom desktop beralih ke 1 kolom vertikal di mobile dengan card padding `1.25rem`.
   - SVG icon dan tag topik divisualisasikan rapi tanpa ada teks yang terpotong.
4. **Alur Pendaftaran Terpadu (Open Recruitment Wizard)**:
   - Stepper bar yang memanjang diubah menjadi **Compact Step Progress Tracker** (Step circle ringkas 1 &rarr; 2 &rarr; 3 dengan label aktif).
   - Touch drag-and-drop / upload file input KTM & Foto dengan area tap besar dan indikator status berhasil unggah.
5. **Showcase Proyek Publik (`/proyek`)**:
   - Filter pill kategori divisi dapat di-scroll horizontal secara halus (*swipeable chip carousel*) tanpa membungkus berlebihan.
   - Grid kartu proyek beralih ke 1 kolom dengan preview thumbnail yang pas.
6. **Verifikasi Sertifikat & KTA Publik (`/verifikasi`)**:
   - Input verifikasi nomor sertifikat / NIM dibuat full-width dengan tombol verifikasi clay yang mudah ditekan.

---

### B. Sub-Sistem 2: Portal Mahasiswa (`student.layouts.app`)
1. **Off-Canvas Mobile Drawer Sidebar**:
   - Pada layar `< 1024px`, sidebar 250px disembunyikan di luar layar (`transform: translateX(-100%)`).
   - Header mobile mahasiswa menampilkan logo ringkas, judul halaman, foto profil mini, dan tombol hamburger toggle.
   - Ketika hamburger ditekan, sidebar meluncur halus dengan latar *backdrop blur* (`rgba(15, 23, 42, 0.45)`). Tap pada area luar menutup drawer secara instan.
2. **KTA Digital 2-Sisi Interaktif (`/student/kta`)**:
   - **Tantangan**: Ukuran desktop KTA standar CR80 ID-1 berlebar 780px-840px, menyebabkan horizontal overflow parah pada layar HP 360px-400px.
   - **Solusi**: Kontainer kartu dibungkus dalam wrapper `kta-viewport-scaler` yang memanfaatkan kalkulasi skala dinamis berbasis CSS container width:
     - Kartu tetap mempertahankan rasio standar (85.6mm x 53.98mm).
     - Seluruh elemen internal (pas foto canvas cover, QR code verifikasi, barcode fisik Code-128, watermark UBBG, nomor NIM, dan tata tertib) tetap proporsional dan tidak berantakan.
     - Fungsi ekspor **PDF High-DPI 2 Halaman Lanskap** (`jsPDF`) dan **PNG HD** (`html2canvas`) tetap menghasilkan resolusi tajam standar sertifikat/KTA fisik.
   - Tombol balik kartu 3D (*Flip Card*) dan Tab Sisi Depan/Belakang diatur agar nyaman ditekan satu jempol.
3. **Presensi Mandiri Real-Time (`/student/presensi`)**:
   - Input Passcode 6 Digit: Kotak input passcode dibuat adaptif (`width: clamp(38px, 12vw, 56px)`, `height: clamp(48px, 14vw, 64px)`) sehingga muat sempurna pada layar HP sekecil 360px (seperti Samsung Galaxy atau iPhone SE).
   - Metrik Kehadiran: Grid 4 kartu metrik kehadiran beralih ke 2x2 grid clay cards.
   - **Riwayat Presensi - Responsive Card Stack**:
     - Di desktop: tabel konvensional.
     - Di HP (`<= 640px`): otomatis berganti menjadi **daftar kartu clay vertikal** per pertemuan, menampilkan tanggal, topik materi, instruktur, dan status badge kehadiran (*Hadir*, *Izin*, *Sakit*, *Alpa*) tanpa perlu scrolling horizontal kaku.
4. **Silabus Pembelajaran & Proyek Mahasiswa**:
   - Grid modul silabus beralih ke 1 kolom dengan expand/collapse topik.
   - Form pengajuan proyek & upload file thumbnail ramah sentuhan.

---

### C. Sub-Sistem 3: Admin CMS Panel (`admin.layouts.app`)
1. **Admin Off-Canvas Sidebar & Mobile Header**:
   - Pengurus UKM dapat mengelola sistem dengan mudah dari smartphone.
   - Mobile top navigation bar menampilkan breadcrumb, tanggal masehi, toggle dark/light (bila ada), dan profil admin.
2. **Command Hub & Metric Stat Grid**:
   - Grid metrik 4-kolom x 2-baris (8 kartu metrik) beralih ke 2 kolom di tablet/layar sedang, dan 1 kolom di HP kecil.
   - Bar Aksi Cepat (*Quick Action Pills*): scrollable horizontal pills dengan indikator swipe halus.
3. **Data Tables Menjadi Claymorphic Card Stack**:
   - Tabel Anggota (`/admin/members`), Pelamar Oprec (`/admin/recruitment`), Presensi Sesi (`/admin/attendance`), dan Pengguna (`/admin/users`) bertransformasi menjadi kartu data ergonomis pada layar `< 768px`:
     - Setiap baris data menjadi satu kartu clay putih dengan avatar, nama, divisi, status badge, dan deretan tombol aksi cepat (*Detail*, *Edit*, *Hapus*, *Konversi*).
     - Membantu admin meninjau berkas pendaftar atau memvalidasi kehadiran mahasiswa saat di lapangan langsung dari HP.
4. **Cetak BAP (Berita Acara Pertemuan)**:
   - Preview dokumen BAP disesuaikan dengan CSS `@media print` dan mode pratinjau mobile yang rapi.

---

## 3. Strategi Pengujian & Verifikasi
- **Viewport Testing**:
  1. Layar HP Compact: 360px (Samsung Galaxy S8/A-series, Android umum)
  2. Layar HP Standar: 375px - 390px (iPhone 12/13/14/15/16)
  3. Layar HP Lebar: 412px - 430px (Pixel, iPhone Pro Max, Samsung Ultra)
  4. Tablet: 768px (iPad Mini / Portrait)
  5. Desktop: 1200px+
- **Verifikasi Fungsionalitas**:
  - Touch interaction drawer menu (buka, tutup, klik tautan).
  - Passcode presensi di HP 360px tanpa horizontal scrollbar.
  - Kartu KTA 2 sisi di HP: Flip 3D lancar, tidak overflow, tombol unduh PDF & PNG berfungsi normal.
  - Semua data table berubah menjadi card stack di layar kecil.
