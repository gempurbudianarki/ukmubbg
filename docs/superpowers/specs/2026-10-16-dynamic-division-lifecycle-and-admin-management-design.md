# Arsitektur & Spesifikasi Desain: Dynamic Division Lifecycle, Multi-Admin Management & Complete Decoupling

## 1. Ringkasan Eksekutif (Executive Summary)

Sistem UKM Ilmu Komputer UBBG sebelumnya memiliki keterbatasan arsitektural di mana spesialisasi divisi UKM terpaku pada 4 divisi tetap (*Pemrograman, Multimedia, IoT, Cyber Security*). Pembatasan ini menimbulkan hardcoding pada stylesheet CSS, controller, kartu pemilihan *Recruitment Wizard*, footer, dan relasi pengurus.

Spesifikasi desain ini merombak sistem divisi menjadi **Universal Dynamic Division Ecosystem**:
1. **Full CRUD & Lifecycle Management:** Super Admin dapat membuat divisi baru, mengedit profil, menonaktifkan/membekukan (*suspend*), menghapus secara aman (*soft delete*), serta memulihkan (*restore*) divisi.
2. **Multi-Admin Divisi Hub:** Super Admin dapat menunjuk satu atau lebih pengguna (`User`) menjadi *Admin Divisi* langsung dari modul manajemen divisi atau mencopot hak akses mereka kapan pun.
3. **Zero Hardcoded Stylesheet & Dynamic Tokens:** Tampilan kartu divisi, warna aksen, ikon FontAwesome, dan badge diinjeksi melalui CSS Custom Properties dinamis (`--accent-color`, `--accent-bg`), memungkinkan penambahan divisi ke-5, ke-10, dst. tanpa mengubah satu baris pun kode aplikasi.
4. **Preservasi Integritas Data Historis:** Penggunaan status *suspended* dan *SoftDeletes* menjamin anggota lama, KTA Digital, riwayat presensi, sertifikat, dan arsip karya mahasiswa tidak akan rusak (*cascade-safe*) ketika sebuah divisi ditutup atau dinonaktifkan.

---

## 2. Tujuan & Batasan (Goals & Non-Goals)

### Tujuan (Goals):
- Memberikan kendali penuh kepada Super Admin untuk menambah divisi baru dengan nama, slug unik, deskripsi, topik fokus, warna aksen (hex color picker), dan ikon FontAwesome kustom.
- Menyediakan mekanisme status lifecycle: `active`, `suspended`, dan `archived` dengan aksi toggle cepat.
- Menyediakan antarmuka manajemen Admin Divisi bertugas (menunjuk user baru atau mencopot peran admin divisi kembali menjadi member).
- Menghapus seluruh logika *hardcode slug* di views publik, recruitment wizard, footer, dan model.
- Memastikan sistem berjalan selamanya (*evergreen*) tanpa perlu refactoring atau deploy kode baru saat struktur organisasi divisi berubah.
- Melindungi data historis dengan *SoftDeletes* pada model Division.

### Batasan (Non-Goals):
- Tidak mengubah skema data tabel `members`, `attendance_sessions`, atau `recruitments` yang sudah ada, melainkan menjaga kompatibilitas relasi `division_id`.
- Tidak membuat struktur multi-tenant terpisah; seluruh divisi tetap berada di bawah naungan UKM Ilmu Komputer UBBG.

---

## 3. Skema Basis Data & Model Eloquent

### 3.1 Migrasi Baru: `upgrade_divisions_table_lifecycle_and_branding`

File: `database/migrations/2026_10_16_000001_upgrade_divisions_table_lifecycle_and_branding.php`

```php
Schema::table('divisions', function (Blueprint $table) {
    if (!Schema::hasColumn('divisions', 'status')) {
        $table->enum('status', ['active', 'suspended', 'archived'])->default('active')->after('social_links');
    }
    if (!Schema::hasColumn('divisions', 'icon_class')) {
        $table->string('icon_class', 60)->default('fa-laptop-code')->after('icon_svg');
    }
    if (!Schema::hasColumn('divisions', 'deleted_at')) {
        $table->softDeletes()->after('updated_at');
    }
});
```

### 3.2 Penyesuaian Model `App\Models\Division`

- Tambahkan *trait* `Illuminate\Database\Eloquent\SoftDeletes`.
- Perbarui `$fillable`:
  ```php
  protected $fillable = [
      'slug', 'name', 'tagline', 'description', 'focus_topics',
      'icon_svg', 'icon_class', 'color_accent', 'banner_image',
      'vision', 'mission', 'adviser_name', 'adviser_title', 'adviser_photo',
      'leader_name', 'leader_nim', 'leader_photo', 'leader_bio',
      'social_links', 'status', 'is_recruitment_open', 'recruitment_quota',
      'recruitment_notes',
  ];
  ```
- Tambahkan Scope & Helper Methods:
  - `scopeActive($query)`: `where('status', 'active')`
  - `scopeOpenForRecruitment($query)`: `where('status', 'active')->where('is_recruitment_open', true)`
  - `isActive(): bool`: `$this->status === 'active'`
  - `isSuspended(): bool`: `$this->status === 'suspended'`
  - `getAdminsCountAttribute(): int`: `$this->admins()->count()`
  - Relasi `admins()`:
    ```php
    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'division_admin');
    }
    ```

---

## 4. Alur Rute & Logika Bisnis Controller

### 4.1 Rute Web (`routes/web.php`)

Grup rute Admin CMS (`middleware: ['auth', 'admin']`):

```php
// Divisions Management Hub
Route::get('/divisions', [DivisionAdminController::class, 'index'])->name('divisions.index');
Route::get('/divisions/create', [DivisionAdminController::class, 'create'])->name('divisions.create');
Route::post('/divisions', [DivisionAdminController::class, 'store'])->name('divisions.store');
Route::get('/divisions/{division}/edit', [DivisionAdminController::class, 'edit'])->name('divisions.edit');
Route::put('/divisions/{division}', [DivisionAdminController::class, 'update'])->name('divisions.update');
Route::post('/divisions/{division}/toggle-status', [DivisionAdminController::class, 'toggleStatus'])->name('divisions.toggleStatus');
Route::post('/divisions/{division}/toggle-recruitment', [DivisionAdminController::class, 'toggleRecruitment'])->name('divisions.toggleRecruitment');
Route::post('/divisions/{division}/assign-admin', [DivisionAdminController::class, 'assignAdmin'])->name('divisions.assignAdmin');
Route::post('/divisions/{division}/remove-admin/{user}', [DivisionAdminController::class, 'removeAdmin'])->name('divisions.removeAdmin');
Route::delete('/divisions/{division}', [DivisionAdminController::class, 'destroy'])->name('divisions.destroy');
Route::post('/divisions/{id}/restore', [DivisionAdminController::class, 'restore'])->name('divisions.restore');
```

### 4.2 Logika Controller: `DivisionAdminController`

1. **`index(Request $request)`**:
   - Menampilkan tab filter: *Divisi Aktif*, *Suspended*, dan *Terarsip/Dihapus (Trash)*.
   - Mengambil data divisi beserta relasi `admins`, `activeMembers`, `posts`, dan `firstChoiceApplicants`.
   - Mengambil daftar calon admin (seluruh user dengan peran `member` atau calon admin) untuk kebutuhan modal *Tunjuk Admin*.
2. **`store(Request $request)`**:
   - Validasi nama divisi (otomatis menghasilkan slug unik via `Str::slug`), tagline, deskripsi, topik fokus, `color_accent` (format HEX `#rrggbb`), `icon_class`, kuota pendaftaran, dan informasi pembina/ketua.
   - Upload opsional foto pembina dan ketua ke direktori `uploads/divisions/`.
3. **`update(Request $request, Division $division)`**:
   - Memperbarui metadata divisi, warna tema, ikon, kuota, serta biodata ketua dan pembina.
4. **`toggleStatus(Division $division)`**:
   - Jika status `'active'`, dialihkan menjadi `'suspended'`. Jika `'suspended'`, dikembalikan menjadi `'active'`.
   - Flash message memberitahu admin perubahan status.
5. **`assignAdmin(Request $request, Division $division)`**:
   - Validasi `user_id`.
   - Mengubah peran user terpilih menjadi `division_admin` dan mengaitkan `division_id = $division->id`.
6. **`removeAdmin(Division $division, User $user)`**:
   - Melepas hak admin divisi dari user: mengubah peran menjadi `member` dan membersihkan atau mempertahankan histori.
7. **`destroy(Division $division)`**:
   - Menjalankan `$division->delete()` (Soft Delete) dan mengubah status menjadi `'archived'`.
8. **`restore($id)`**:
   - Menjalankan `Division::onlyTrashed()->findOrFail($id)->restore()` dan mengubah status kembali ke `'active'`.

---

## 5. Antarmuka Pengguna & Dynamic White Claymorphism

### 5.1 Panel Divisi Super Admin (`resources/views/admin/divisions/index.blade.php`)
- **Header Komando:** Tombol "+ Tambah Divisi Baru" dengan White Claymorphism elevation.
- **Kartu Ringkasan Metrik:** Total Divisi Aktif, Total Divisi Dibekukan (Suspended), Total Admin Divisi yang Bertugas, dan Total Kuota Penerimaan.
- **Grid Kartu Divisi Interaktif:**
  - Tiap kartu divisi menampilkan aksen warna dan ikon kustomnya.
  - Badge Status: `Aktif` (badge-success), `Suspended` (badge-warning), `Rekrutmen Dibuka/Ditutup`.
  - **Daftar Avatar Admin Bertugas:** Menampilkan badge nama para admin divisi dengan tombol cepat "+ Tunjuk Admin" dan tombol copot hak.
  - Dropdown menu aksi cepat: Edit Profil, Toggle Suspend, Toggle Buka/Tutup Pendaftaran, dan Hapus (Arsipkan).

### 5.2 Form Tambah & Edit Divisi (`resources/views/admin/divisions/form.blade.php`)
- Input Nama Divisi (auto slug generator via JS).
- **Color Accent Picker**: Input native HTML `<input type="color">` dipadukan dengan input teks HEX dan tombol cepat preset palet modern (Indigo, Sky, Emerald, Rose, Amber, Violet, Cyan).
- **Icon Selector**: Input kelas FontAwesome disertai galeri 12 preset ikon teknologi yang dapat dipilih dalam 1-klik (misal: `fa-laptop-code`, `fa-palette`, `fa-microchip`, `fa-shield-halved`, `fa-robot`, `fa-gamepad`, `fa-brain`, `fa-database`, `fa-network-wired`, `fa-vr-cardboard`, `fa-drone`, `fa-code`).
- **Live Clay Preview Card**: Kartu pratinjau yang langsung berubah warna dan ikonnya secara *real-time* saat admin mengetik atau memilih warna.

### 5.3 Decoupling Total di Sisi Publik & Pendaftaran

#### 1. Recruitment Wizard (`resources/views/recruitment/index.blade.php`)
- Menghapus pengecekan manual slug divisi.
- Loop kartu divisi menggunakan CSS variable dinamis:
  ```blade
  <div class="division-select-card {{ $isSelected ? 'selected' : '' }} {{ $isFull ? 'is-full disabled' : '' }}"
       id="divCardPrimary_{{ $d->id }}"
       style="--div-accent: {{ $d->color_accent ?? '#0284c7' }}; --div-accent-bg: {{ $d->color_accent ?? '#0284c7' }}14; --div-accent-border: {{ $d->color_accent ?? '#0284c7' }}40;"
       onclick="selectPrimaryDivision('{{ $d->id }}', '{{ addslashes($d->name) }}', {{ $isFull ? 'true' : 'false' }})">
      <div class="division-card-icon" style="background: var(--div-accent-bg); color: var(--div-accent);">
          <i class="fas {{ $d->icon_class ?? 'fa-cube' }}"></i>
      </div>
      ...
  </div>
  ```
- CSS `.division-select-card.selected`:
  ```css
  border-color: var(--div-accent, #0284c7) !important;
  box-shadow: 0 0 0 3px var(--div-accent-bg, rgba(2,132,199,0.2)), var(--clay-card) !important;
  ```

#### 2. Footer & Navigasi Publik (`resources/views/layouts/footer.blade.php`)
- Mengganti tautan 4 divisi hardcoded dengan loop dinamis `$footerDivisions = \App\Models\Division::where('status', 'active')->get();` yang disediakan oleh View Composer atau pemanggilan langsung.

---

## 6. Penanganan Skenario Khusus & Edge Cases

| Skenario | Penanganan Sistem |
|---|---|
| **Divisi yang memiliki anggota di-suspend** | Anggota lama tetap bisa login, melihat materi, dan mengunduh KTA. Namun mahasiswa baru tidak dapat memilih divisi ini di formulir pendaftaran, dan admin divisi tidak dapat membuka sesi presensi baru sampai diaktifkan kembali. |
| **Divisi dihapus oleh Super Admin** | Sistem menerapkan *Soft Delete*. Relasi di tabel `members`, `attendance_logs`, dan `projects` tetap utuh mengarah ke `division_id`. Divisi langsung hilang dari antarmuka publik dan masuk ke tab arsip admin. |
| **Divisi dipulihkan (Restore)** | Divisi kembali berstatus `active` dan langsung muncul kembali di portal publik serta daftar divisi aktif. |
| **User dicopot dari jabatan Admin Divisi** | Peran user diubah kembali menjadi `member`. User tidak lagi memiliki akses ke panel CMS `/admin/*`, namun akunnya tetap dapat login ke `/student/dashboard`. |
| **Divisi baru tanpa icon_svg** | Sistem mengandalkan `icon_class` FontAwesome sebagai renderer utama dengan fallback ikon standar `fa-cube`. |

---

## 7. Rencana Verifikasi Otomatis (Testing Strategy)

Membuat pengujian fitur menyeluruh di `tests/Feature/DynamicDivisionManagementTest.php`:
1. `test_super_admin_can_create_new_custom_division()`: Memastikan Super Admin berhasil membuat divisi baru lengkap dengan warna HEX dan ikon kustom.
2. `test_super_admin_can_toggle_suspend_division()`: Memastikan divisi suspended tidak muncul di daftar pendaftaran publik.
3. `test_super_admin_can_assign_and_remove_division_admin()`: Memastikan perubahan role user dari `member` menjadi `division_admin` dan sebaliknya.
4. `test_soft_deleted_division_preserves_existing_members_and_kta()`: Memastikan data anggota dan KTA digital tetap dapat dirender meskipun divisinya diarsipkan.
5. `test_recruitment_wizard_dynamically_renders_new_division_cards()`: Memastikan divisi baru yang dibuat otomatis muncul di form pendaftaran tanpa modifikasi kode blade.
