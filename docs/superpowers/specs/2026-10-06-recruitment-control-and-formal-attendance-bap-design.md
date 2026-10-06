# Spec: Recruitment Control Center & Formal Division Attendance System

**Tanggal**: 2026-10-06  
**Status**: Approved by User (Streamlined Web-First, Tanpa Cetak Fisik BAP)  
**Tujuan**: Menghadirkan sistem pembukaan rekrutmen berbasis jadwal waktu & seleksi divisi terpusat oleh Super Admin, serta sistem pembukaan sesi presensi divisi resmi yang mencatat silabus materi pembelajaran secara formal di web.

---

## 1. Latar Belakang & Masalah
1. **Pendaftaran Perlu Kontrol Jadwal & Pembatasan Divisi**:
   - Pendaftaran tidak dibuka terus-menerus; ada periode tanggal mulai dan tanggal penutupan yang diatur oleh Super Admin.
   - Super Admin dapat menentukan divisi mana saja yang aktif dibuka dan mana yang ditutup (misalnya kuota penuh di divisi tertentu).
   - Pengunjung publik pada halaman `/pendaftaran` hanya dapat memilih divisi yang sedang berstatus dibuka. Jika pendaftaran ditutup secara global atau di luar jadwal, sistem menampilkan pengumuman resmi dan menonaktifkan formulir.
2. **Presensi Perlu Prosedur Buka Sesi Formal per Divisi**:
   - Setiap Admin Divisi wajib membuka sesi pertemuan terlebih dahulu sebelum melakukan absensi.
   - Sesi mencakup informasi formal kegiatan:
     - Hari & Tanggal pelaksanaan (nama hari terisi otomatis berdasarkan tanggal)
     - Waktu mulai dan selesai
     - Lokasi / Ruang Lab / Media Pertemuan
     - Pokok Bahasan & Silabus (Materi apa saja yang dipelajari)
     - Resume / Capaian Pembelajaran sesi
     - Nama Pemateri / Instruktur / PIC
   - Setelah sesi dibuka, admin mengisi daftar hadir anggota divisi secara interaktif di layar web lengkap dengan live counter statistik kehadiran.

---

## 2. Arsitektur & Perubahan Skema Database

### A. Tabel `divisions`
Menambahkan kolom kontrol rekrutmen divisi:
- `is_recruitment_open` (`boolean`, default: `true`): Status buka/tutup pendaftaran divisi ini.
- `recruitment_quota` (`integer`, nullable, default: `null`): Kuota maksimal pendaftar (opsional).
- `recruitment_notes` (`string`, nullable): Catatan ringkas untuk pendaftar (misal: "Kuota Terbatas").

### B. Tabel `settings` (Konfigurasi Global Gelombang Pendaftaran)
Kunci konfigurasi yang dikelola Super Admin:
- `recruitment_status`: `'open'` | `'closed'`
- `recruitment_start_date`: Tanggal & waktu mulai pembukaan (`Y-m-d H:i`)
- `recruitment_end_date`: Tanggal & waktu penutupan pendaftaran (`Y-m-d H:i`)
- `recruitment_batch_name`: Nama gelombang (misal: "Gelombang Ganjil 2026/2027")
- `recruitment_closed_message`: Pesan formal saat pendaftaran ditutup.

### C. Tabel `attendance_sessions`
Memperluas metadata sesi presensi agar formal dan tercatat jelas apa yang dipelajari:
- `day_name`: (`string`, panjang: 20) Hari pelaksanaan (Senin, Selasa, dst., auto-detect dari tanggal)
- `session_type`: (`enum`: `riset_rutin`, `workshop_teknis`, `mentoring_proyek`, `evaluasi_bulanan`, `sidang_pleno`, default: `riset_rutin`)
- `topic_material`: (`string`, panjang: 255) Pokok Bahasan / Silabus Pertemuan yang dipelajari
- `learning_outcomes`: (`text`, nullable) Uraian materi yang dipelajari & resume pembahasan
- `instructor_name`: (`string`, panjang: 150) Nama Pemateri / Mentor / PIC Sesi
- `status`: (`enum`: `open`, `closed`, default: `open`)

---

## 3. Alur Fungsionalitas & User Interface

### A. Kontrol Pendaftaran (Super Admin)
1. **Menu Pengaturan Gelombang (`/admin/recruitment/settings`)**:
   - Super Admin dapat:
     - Mengubah switch status pendaftaran global (Buka/Tutup).
     - Mengatur tanggal & jam mulai serta tanggal & jam selesai.
     - Mengatur nama gelombang dan pesan formal ketika pendaftaran ditutup.
     - Switch on/off pembukaan per divisi, serta mengatur kuota maksimal masing-masing divisi.
     - Melihat kartu ringkasan jumlah pendaftar saat ini per divisi vs kuota.
2. **Validasi & Tampilan Publik (`/pendaftaran`)**:
   - **Otomatisasi Jadwal**:
     - Jika status global `closed` ATAU waktu sekarang di luar rentang `start_date` sampai `end_date`, form pendaftaran disembunyikan dan digantikan dengan banner pengumuman formal status rekrutmen.
   - **Filter Divisi Aktif**:
     - Pilihan divisi (Pilihan 1 dan Pilihan 2) pada form hanya memunculkan divisi yang `is_recruitment_open == true` dan belum melebihi kuota.
     - Validasi backend menolak pengiriman form jika memilih divisi yang sedang ditutup.

### B. Prosedur Pembukaan Sesi Presensi oleh Admin Divisi
1. **Buka Sesi Baru (`/admin/attendance/create`)**:
   - Admin Divisi masuk ke form pembukaan sesi resmi:
     - Divisi otomatis terkunci ke divisi miliknya (Super Admin bisa memilih divisi mana saja atau sesi pleno).
     - Pilih Tanggal & Hari (Hari terisi otomatis secara responsif saat tanggal dipilih, misal memilih 2026-10-10 langsung mengisi "Sabtu").
     - Jam Mulai & Jam Selesai.
     - Tipe Sesi (*Riset Rutin*, *Workshop Teknis*, *Mentoring Proyek*, *Evaluasi Bulanan*).
     - Ruang / Lokasi Kegiatan.
     - **Materi Pokok Bahasan**: Mengisi judul topik/silabus materi yang dipelajari.
     - **Capaian Pembelajaran / Resume Materi**: Rangkuman apa saja yang dipelajari anggota dalam pertemuan tersebut.
     - **Pemateri / PIC Sesi**: Nama pemateri atau ketua yang memimpin pertemuan.
   - Setelah disimpan, sistem langsung mengarahkan ke lembar checklist kehadiran anggota aktif divisi tersebut.
2. **Lembar Pengisian Presensi Interaktif (`/admin/attendance/{id}`)**:
   - Menampilkan kartu rincian sesi: Hari, Tanggal, Jam, Lokasi, Pemateri, serta Box Silabus & Materi Pembelajaran.
   - Tabel interaktif anggota divisi dengan radio button status (`Hadir`, `Izin`, `Sakit`, `Alpa`).
   - Tombol cepat **"Tandai Semua Hadir"** dengan konfirmasi instan.
   - Kolom catatan izin/sakit per anggota.
   - Live counter kehadiran yang otomatis mengkalkulasi persentase kehadiran real-time.
   - Tombol simpan perubahan presensi.

---

## 4. Hak Akses (Role & Permission)
- **Super Admin**:
  - Akses menu pengaturan rekrutmen dan kuota seluruh divisi.
  - Dapat membuka sesi presensi untuk divisi mana pun atau sesi pleno.
- **Admin Divisi**:
  - Hanya dapat membuka dan mengisi sesi presensi untuk divisinya sendiri.
  - Menu pengaturan rekrutmen global hanya dapat diubah oleh Super Admin.

---

## 5. Rencana Pengujian Otomatis (Automated Tests)
1. **RecruitmentControlTest**:
   - Jadwal pendaftaran tertutup menolak pendaftaran baru.
   - Mematikan divisi A membuat divisi A tidak muncul di opsi pendaftaran dan dilarang dipilih.
   - Super admin berhasil memperbarui pengaturan gelombang dan divisi.
2. **AttendanceSessionFlowTest**:
   - Pembuatan sesi berhasil menyimpan hari, silabus materi yang dipelajari, dan nama pemateri.
   - Admin divisi hanya dapat membuat sesi untuk divisinya sendiri.
   - Batch update status presensi anggota berhasil disimpan dengan benar.
