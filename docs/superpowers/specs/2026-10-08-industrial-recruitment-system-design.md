# Industrial-Grade Recruitment Registration System Design

**Date**: 2026-10-08  
**Author**: Antigravity & gempurbudianarki  
**Status**: Approved  

---

## 1. Executive Summary & Problem Analysis

Formulir Pendaftaran Mahasiswa Baru (`/pendaftaran`) pada UKM Ilmu Komputer UBBG merupakan gerbang onboarding utama. Sistem pendaftaran sebelumnya mengalami inkonsistensi arsitektur:
1. **Flow Inversion**: Pas foto mahasiswa diminta di Tahap 1 (Biodata), sedangkan berkas lainnya (KTM dan CV) di Tahap 3 (Unggah Berkas). Hal ini merusak model mental pengguna.
2. **Delayed Validation & Form Blocker**: Validasi ukuran file (maksimal 2MB untuk foto dan KTM) hanya dilakukan di akhir saat pengiriman ke server. Pengguna baru mengetahui berkasnya terlalu besar setelah menekan tombol kirim di akhir, menyebabkan hilangnya state dan frustrasi. Selain itu, atribut `required` bawaan HTML5 pada elemen tersembunyi menyebabkan error `An invalid form control is not focusable`.
3. **Monolithic Code Clutter**: Lebih dari 1.000 baris script JavaScript diletakkan inline di dalam satu file Blade template, menyulitkan debugging, pemeliharaan, dan pengujian.
4. **Division Selection Collision**: Pengguna berpotensi memilih divisi cadangan yang sama persis dengan divisi utama tanpa pencegahan aktif secara langsung.

Desain ini menetapkan pembangunan ulang menyeluruh (*rearchitecting from the ground up*) formulir pendaftaran berstandar industri dengan arsitektur **State Machine Terpadu**, **Zero-Latency Client File Pre-checking**, **Mutual Exclusion Division Selector**, dan **Pemisahan Modul Arsitektur Bersih**.

---

## 2. Core Architecture & Subsystems

```
┌────────────────────────────────────────────────────────────────────────┐
│                   FRONTEND RECRUITMENT ENGINE ARCHITECTURE            │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
    ┌───────────────────────────────┼───────────────────────────────┐
    ▼                               ▼                               ▼
[FormStateManager]           [ValidationBus]             [FilePipeline]
• Step transitions           • Field validators          • Instant size check
• Local draft autosave       • Async NIM & Email check   • Drag & drop listener
• Division anti-duplicate    • Custom error tooltips     • Instant preview / drop
• Live summary sink          • Zero native focus lock    • Error shake & reset
```

### A. State Machine Terpusat (`RecruitmentFormEngine`)
Sistem diatur oleh satu state machine tunggal di `public/js/recruitment-wizard.js`:
- **Current Step**: Mengatur tahap aktif (1: Biodata & Akun, 2: Divisi & Motivasi, 3: Berkas & Konfirmasi).
- **Navigation Safety Guard**: Pengguna tidak dapat melompat ke tahap selanjutnya tanpa lolos validasi tahap sebelumnya. Tombol "Kembali" selalu aman tanpa mereset isian formulir.
- **Form Novalidate**: Formulir menggunakan atribut `novalidate` secara eksplisit, menghapus semua browser-level focus lock.

### B. Alur Tahapan yang Tertata Rapi

#### Tahap 1: Identitas Diri & Akun Portal
- **Bidang**:
  - Nama Lengkap (min 3 karakter).
  - NIM (min 4 karakter, auto debounced AJAX check ke `/pendaftaran/check-availability?nim=...`).
  - Semester (1 - 8 dropdown).
  - Nomor WhatsApp (auto formatting prefix `08` / `62`).
  - Kelas / Rombel Kuliah (opsional).
  - Email (format email valid, auto debounced AJAX check ke `/pendaftaran/check-availability?email=...`).
  - Kata Sandi Baru & Konfirmasi (min 8 karakter, live strength meter, live match status).
- **Catatan**: Tidak ada upload berkas apa pun di Tahap 1.

#### Tahap 2: Pilihan Divisi & Motivasi
- **Divisi Utama**:
  - Kartu taktil interaktif (*Claymorphism cards*) menampilkan nama divisi, deskripsi keahlian, ikon, dan kuota.
  - Memilih divisi utama secara otomatis menonaktifkan (*disabled*) divisi yang bersangkutan pada pilihan cadangan.
- **Divisi Cadangan (Opsional)**:
  - *Secondary pills selector*.
  - Dilengkapi tombol reset pilihan cadangan.
  - Secara otomatis mencegah duplikasi dengan divisi utama (*mutual exclusion algorithm*).
- **Motivasi Bergabung**:
  - Live character counter dengan indikator kualitas (min 20 karakter).

#### Tahap 3: Unggah Berkas & Konfirmasi Final
- **Konsolidasi 3 Dropzone Berkas**:
  1. **Pas Foto Resmi**: Maks 2MB (JPG, PNG, WEBP).
  2. **Foto / Scan KTM**: Maks 2MB (JPG, PNG, PDF).
  3. **CV / Resume**: Maks 3MB (PDF).
- **Zero-Latency Client-Side Pre-Check**:
  - Event listener pada `change` dan `drop`.
  - Jika ukuran file melebihi batas, file **seketika ditolak**:
    - Nilai input file dikosongkan.
    - Dropzone diberi border merah dan animasi shake.
    - Pesan error spesifik muncul tepat di bawah kotak dropzone: `"❌ Ukuran berkas (X.X MB) melebihi batas Y MB. Silakan kompres atau pilih berkas lain."`
  - Jika file valid, thumbnail preview langsung ditampilkan dengan indikator ukuran berkas terverifikasi.
- **Ringkasan Data Interaktif (Live Summary)**:
  - Pratinjau Nama, NIM, Semester, Divisi Pilihan, dan Email sebelum kirim.
  - Tautan *"Ubah Data"* cepat yang melompat ke tahap terkait.
- **Persetujuan & Tombol Submit**:
  - Checkbox pernyataan keabsahan dokumen.
  - Tombol Submit anti-double-click: otomatis terkunci (`disabled`), berubah menjadi animasi spinner, dan menampilkan pesan proses pengunggahan.

---

### C. Pemisahan Kode & Struktur Direktori

1. **View Template**: `resources/views/recruitment/index.blade.php`
   - Hanya berisi elemen struktural HTML, markup Claymorphism, dan link asset.
   - Tanpa script inline raksasa.
2. **Client Script Engine**: `public/js/recruitment-wizard.js`
   - Berisi seluruh state engine, validator, autosave, file pipeline, dan event bindings.
3. **Styles**: `public/css/portal.css`
   - Berisi animasi kartu divisi, dropzone drag-and-drop, debossed indicators, dan status badges.
4. **Backend Controller**: `app/Http/Controllers/RecruitmentController.php`
   - `checkAvailability()`: Real-time asynchronous availability endpoint untuk NIM dan Email.
   - `store()`: Validasi keamanan akhir, penyimpanan berkas ke disk aman, pembuatan akun user, pembuatan profil member, sinkronisasi relasi pelamar, dan auto-login.

---

## 3. Resiliensi & Keamanan Data (Enterprise Standards)

1. **LocalStorage Draft Autosave**:
   - Disimpan secara lokal pada browser pengguna (`ukm_recruitment_draft_v2`) dengan debounced timer (400ms).
   - Banner pemulihan draf cerdas (*Smart Recovery Banner*) jika browser ditutup sebelum selesai mengisi.
   - Penghapusan draft otomatis saat formulir berhasil disubmit.
2. **Anti Double Submit Protection**:
   - Flag `isSubmitting` di frontend mencegah pengiriman berulang saat koneksi lambat.
3. **Database Transaction Integrity**:
   - Backend controller membungkus pembuatan user, member, berkas, dan lamaran dalam `DB::transaction()` untuk menjamin konsistensi data.

---

## 4. Verification & Testing Strategy

1. **PHPUnit Automated Test Suite**:
   - `RecruitmentWorkflowTest`: Validasi alur registrasi, validasi berkas, dan pendaftaran user.
   - `RecruitmentWindowGuardTest`: Verifikasi penjagaan jadwal buka/tutup pendaftaran dan kuota divisi.
   - `SecurityAndAccessControlTest`: Verifikasi kekuatan password dan hak akses.
2. **Client-Side Behavioral Verification**:
   - Pengujian manual langsung oleh pengguna tanpa gangguan subagent.
   - Verifikasi respon instan penolakan file > 2MB.
   - Verifikasi sinkronisasi divisi utama dan cadangan.
