# Desain Arsitektur: Modul Anggota UKM, Presensi/Absensi Rapat & Penyempurnaan Rekrutmen

## 1. Ringkasan Eksekutif
Dokumen ini merinci penambahan dua subsistem inti organisasi ke dalam portal UKM Ilmu Komputer:
1. **Manajemen Anggota UKM (`Member Management`)**: Pengelolaan data anggota resmi organisasi, status keanggotaan (aktif, non-aktif, alumni), filter divisi, operasi penghapusan anggota, dan konversi 1-klik dari pendaftar rekrutmen yang diterima.
2. **Sistem Presensi / Absensi Kegiatan (`Attendance System`)**: Pembuatan sesi absensi rapat/kegiatan/workshop berbasis checklist admin, perekaman status kehadiran (Hadir, Izin, Sakit, Alpa), tombol cepat *Tandai Semua Hadir*, dan ringkasan persentase keaktifan anggota.
3. **Penyempurnaan Rekrutmen & UI/UX Bebas "AI Slop"**: Form pendaftaran yang mulus, feedback interaktif, dan panel admin berdesain modern dengan glassmorphism accents, kartu statistik yang rapi, dan micro-animations.

---

## 2. Struktur Database & Model

### 2.1 Tabel `members`
Tabel penyimpan data anggota resmi UKM:
- `id` (bigint, PK)
- `recruitment_id` (bigint, nullable, FK ke `recruitments.id`, cascade on delete / null on delete)
- `nim` (string(20), unique, index)
- `name` (string(255))
- `email` (string(255))
- `phone_number` (string(25), nullable)
- `division_id` (bigint, FK ke `divisions.id`)
- `batch_year` (string(10)) - Angkatan mahasiswa (e.g. "2024", "2025")
- `status` (enum: `aktif`, `non_aktif`, `alumni`, default: `aktif`)
- `join_date` (date, default current date)
- `notes` (text, nullable)
- `timestamps`

### 2.2 Tabel `attendance_sessions`
Tabel pencatat agenda pertemuan/rapat/workshop yang memerlukan absensi:
- `id` (bigint, PK)
- `division_id` (bigint, nullable, FK ke `divisions.id`) - Jika NULL artinya sesi untuk seluruh divisi (pleno)
- `title` (string(255)) - Contoh: "Rapat Pleno Awal Semester", "Bootcamp Divisi Pemrograman"
- `session_date` (date)
- `time_start` (time)
- `time_end` (time, nullable)
- `location` (string(255)) - Ruang lab / auditorium / online
- `created_by` (bigint, FK ke `users.id`)
- `notes` (text, nullable)
- `timestamps`

### 2.3 Tabel `attendance_logs`
Tabel pencatat lembar kehadiran tiap anggota per sesi:
- `id` (bigint, PK)
- `session_id` (bigint, FK ke `attendance_sessions.id`, cascade on delete)
- `member_id` (bigint, FK ke `members.id`, cascade on delete)
- `status` (enum: `hadir`, `izin`, `sakit`, `alpa`, default: `hadir`)
- `notes` (text, nullable) - Alasan izin / sakit
- `timestamps`
- Unique constraint: `['session_id', 'member_id']`

---

## 3. Alur Kerja & Spesifikasi Antarmuka

### 3.1 Modul Manajemen Anggota (`/admin/members`)
- **Daftar Anggota (`index`)**:
  - Filter interaktif: Divisi (All / Pemrograman / Multimedia / IoT / Cyber Security) dan Status (Semua / Aktif / Non-Aktif / Alumni).
  - Kolom pencarian real-time (Nama / NIM).
  - Kartu Metrik Cepat: Total Anggota, Anggota Aktif, Alumni, Rasio per Divisi.
  - Aksi: Tombol Edit Status, Tombol Hapus Anggota (dengan konfirmasi aman).
- **Tambah Anggota Baru (`store`)**:
  - Modal form cepat: Input NIM, Nama, Email, No. WA, Divisi, Angkatan, Status.
- **Konversi 1-Klik dari Oprec (`/admin/recruitment/{id}/convert-to-member`)**:
  - Pada halaman detail pendaftar oprec yang berstatus `accepted`, terdapat tombol **"Konversi Jadi Anggota UKM"**.
  - Sistem otomatis mengecek apakah NIM sudah ada di `members`. Jika belum, langsung membuat entitas `Member` baru dengan status `aktif` dan menghubungkan `recruitment_id`.

### 3.2 Modul Sistem Presensi (`/admin/attendance`)
- **Daftar Sesi Presensi (`index`)**:
  - Menampilkan daftar agenda yang sudah/sedang berjalan beserta statistik kehadiran singkat (contoh: 28/30 Hadir).
  - Tombol Buat Sesi Baru.
- **Buat Sesi Baru (`create` / `store`)**:
  - Form: Judul Agenda, Divisi Peserta (Semua Divisi atau Divisi Spesifik), Tanggal, Waktu Mulai & Selesai, Lokasi.
  - Saat sesi dibuat, sistem otomatis menginisialisasi record `attendance_logs` untuk seluruh anggota aktif yang relevan dengan status default `hadir` atau `alpa` sehingga lembar checklist siap diisi.
- **Lembar Checklist Presensi (`show` / `updateBatch`)**:
  - Menampilkan tabel anggota dengan radio/switch status per baris: `[Hadir]` `[Izin]` `[Sakit]` `[Alpa]`.
  - Tombol Cepat: **"Tandai Semua Hadir"** via JavaScript tanpa reload.
  - Input catatan per baris untuk alasan izin/sakit.
  - Tombol Simpan Perubahan yang memberikan konfirmasi toast interaktif.
- **Hapus Sesi**:
  - Admin dapat menghapus sesi rapat yang salah dibuat berserta log kehadirannya.

### 3.3 Navigasi Admin & Konsistensi UI
- Update menu navigasi Admin (`resources/views/admin/layouts/app.blade.php`):
  - Tambahkan menu **"Anggota UKM"** (ikon grup/pengguna) di bawah Rekrutmen.
  - Tambahkan menu **"Presensi / Absensi"** (ikon kalender/checklist).
- Tampilan diselaraskan dengan estetika portal yang sudah dibangun (glassmorphism cards, badges warna divisi yang konsisten, animasi halus, responsive layout).

---

## 4. Rencana Pengujian (Testing Matrix)
1. **Model & Relasi**:
   - Relasi `Member` ke `Division` dan `Recruitment`.
   - Relasi `AttendanceSession` ke `Division`, `User`, dan `AttendanceLog`.
   - Relasi `AttendanceLog` ke `AttendanceSession` dan `Member`.
2. **Feature Tests**:
   - Admin dapat melihat daftar anggota, menyaring berdasarkan divisi & status.
   - Admin dapat menambah dan menghapus anggota.
   - Admin dapat mengonversi pendaftar yang diterima (`accepted`) menjadi anggota UKM.
   - Admin dapat membuat sesi presensi, mengisi checklist kehadiran, dan menyimpan status.
   - Pembatasan hak akses: Division admin hanya mengelola divisi miliknya, sedangkan Super admin mengelola semua.
