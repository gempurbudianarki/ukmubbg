# Recruitment Control & Formal Division Attendance Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menghadirkan sistem pengaturan gelombang dan pembatasan divisi pendaftaran oleh Super Admin, serta alur pembukaan sesi presensi divisi resmi yang mencatat pokok bahasan/silabus materi pembelajaran secara formal di web.

**Architecture:** Modifikasi skema basis data pada tabel `divisions` dan `attendance_sessions`, pembangunan halaman pusat kontrol pendaftaran di admin CMS, proteksi ketat jadwal dan kuota divisi pada pendaftaran publik, serta pembaruan form pembuatan sesi dan lembar presensi akademik divisi.

**Tech Stack:** PHP 8.1, Laravel 10, MySQL 8, Blade Template Engine, Vanilla CSS & Modern JavaScript.

**Spec:** `docs/superpowers/specs/2026-10-06-recruitment-control-and-formal-attendance-bap-design.md`

## Global Constraints
- PHP binary must be invoked via `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe"`.
- Semua automated tests harus menggunakan `use RefreshDatabase;` dan lolos 100% tanpa error regresi.
- Desain UI bebas dari tampilan "AI slop", wajib menggunakan Plus Jakarta Sans, kontras tinggi, glassmorphism konsisten, dan micro-interactions halus.
- Pembatasan role: Super Admin memiliki kontrol penuh atas seluruh divisi dan pengaturan rekrutmen; Admin Divisi dibatasi hanya pada lingkup divisinya sendiri.

---

### Task 1: Database Migrations & Eloquent Model Upgrades

**Files:**
- Create: `database/migrations/2026_10_06_000004_add_recruitment_controls_to_divisions.php`
- Create: `database/migrations/2026_10_06_000005_upgrade_attendance_sessions_formal_metadata.php`
- Modify: `app/Models/Division.php`
- Modify: `app/Models/AttendanceSession.php`
- Test: `tests/Feature/RecruitmentControlModelTest.php`

**Interfaces:**
- Consumes: Existing `divisions` and `attendance_sessions` tables.
- Produces: 
  - `Division::$is_recruitment_open` (bool), `Division::$recruitment_quota` (int|null), `Division::$recruitment_notes` (string|null).
  - `AttendanceSession::$day_name` (string), `AttendanceSession::$session_type` (string), `AttendanceSession::$topic_material` (string), `AttendanceSession::$learning_outcomes` (string|null), `AttendanceSession::$instructor_name` (string), `AttendanceSession::$status` (string).

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use App\Models\AttendanceSession;
use App\Models\Division;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecruitmentControlModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_division_recruitment_control_fields(): void
    {
        $division = Division::create([
            'slug' => 'test-divisi',
            'name' => 'Divisi Test',
            'tagline' => 'Tagline',
            'description' => 'Deskripsi',
            'focus_topics' => ['PHP'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#2563eb',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen Pembina',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua',
            'leader_nim' => '210103001',
            'leader_bio' => 'Bio',
            'is_recruitment_open' => true,
            'recruitment_quota' => 25,
            'recruitment_notes' => 'Tersedia 25 kuota',
        ]);

        $this->assertDatabaseHas('divisions', [
            'id' => $division->id,
            'is_recruitment_open' => 1,
            'recruitment_quota' => 25,
            'recruitment_notes' => 'Tersedia 25 kuota',
        ]);
    }

    public function test_attendance_session_formal_metadata_fields(): void
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        $session = AttendanceSession::create([
            'division_id' => null,
            'created_by' => $user->id,
            'title' => 'Pertemuan Riset Algoritma',
            'day_name' => 'Sabtu',
            'session_date' => '2026-10-10',
            'time_start' => '09:00:00',
            'time_end' => '12:00:00',
            'session_type' => 'riset_rutin',
            'location' => 'Lab Komputer 3',
            'topic_material' => 'Dynamic Programming & Graph Traversal',
            'learning_outcomes' => 'Mahasiswa memahami algoritma Dijkstra dan implementasi DFS/BFS.',
            'instructor_name' => 'Muhammad Rayhan Fajar',
            'notes' => 'Membawa laptop masing-masing.',
            'status' => 'open',
        ]);

        $this->assertDatabaseHas('attendance_sessions', [
            'id' => $session->id,
            'day_name' => 'Sabtu',
            'session_type' => 'riset_rutin',
            'topic_material' => 'Dynamic Programming & Graph Traversal',
            'instructor_name' => 'Muhammad Rayhan Fajar',
        ]);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=RecruitmentControlModelTest`
Expected: FAIL due to missing columns in database schema.

- [ ] **Step 3: Write migrations and update models**

Create migration `database/migrations/2026_10_06_000004_add_recruitment_controls_to_divisions.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->boolean('is_recruitment_open')->default(true)->after('social_links');
            $table->integer('recruitment_quota')->nullable()->after('is_recruitment_open');
            $table->string('recruitment_notes')->nullable()->after('recruitment_quota');
        });
    }

    public function down(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->dropColumn(['is_recruitment_open', 'recruitment_quota', 'recruitment_notes']);
        });
    }
};
```

Create migration `database/migrations/2026_10_06_000005_upgrade_attendance_sessions_formal_metadata.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->string('day_name', 20)->default('Senin')->after('title');
            $table->string('session_type', 30)->default('riset_rutin')->after('time_end');
            $table->string('topic_material', 255)->nullable()->after('location');
            $table->text('learning_outcomes')->nullable()->after('topic_material');
            $table->string('instructor_name', 150)->nullable()->after('learning_outcomes');
            $table->enum('status', ['open', 'closed'])->default('open')->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'day_name',
                'session_type',
                'topic_material',
                'learning_outcomes',
                'instructor_name',
                'status',
            ]);
        });
    }
};
```

Update `app/Models/Division.php` `$fillable`:
Add `'is_recruitment_open'`, `'recruitment_quota'`, `'recruitment_notes'`.
Add `$casts`: `'is_recruitment_open' => 'boolean'`, `'recruitment_quota' => 'integer'`.

Update `app/Models/AttendanceSession.php` `$fillable`:
Add `'day_name'`, `'session_type'`, `'topic_material'`, `'learning_outcomes'`, `'instructor_name'`, `'status'`.

- [ ] **Step 4: Run test to verify it passes**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=RecruitmentControlModelTest`
Expected: PASS (2 tests, 2 assertions).

- [ ] **Step 5: Commit changes**

```bash
git add database/migrations/* app/Models/* tests/Feature/RecruitmentControlModelTest.php
git commit -m "feat(schema): add division recruitment controls and formal attendance metadata"
```

---

### Task 2: Super Admin Recruitment Control Center (Settings Page & API)

**Files:**
- Modify: `routes/web.php`
- Modify: `app/Http/Controllers/Admin/RecruitmentAdminController.php`
- Create: `resources/views/admin/recruitment/settings.blade.php`
- Modify: `resources/views/admin/recruitment/index.blade.php` (tambahkan tombol navigasi "Pengaturan Periode & Kuota")
- Test: `tests/Feature/RecruitmentSettingsAdminTest.php`

**Interfaces:**
- Consumes: `Setting::get()`, `Setting::set()`, `Division::all()`.
- Produces:
  - Route `admin.recruitment.settings` (`GET /admin/recruitment/settings`)
  - Route `admin.recruitment.settings.update` (`POST /admin/recruitment/settings`)

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecruitmentSettingsAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $divisionAdmin;
    private Division $division;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);

        $this->division = Division::create([
            'slug' => 'pemrograman',
            'name' => 'Divisi Pemrograman',
            'tagline' => 'Code',
            'description' => 'Desc',
            'focus_topics' => ['Laravel'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#2563eb',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua',
            'leader_nim' => '210103001',
            'leader_bio' => 'Bio',
            'is_recruitment_open' => true,
        ]);

        $this->divisionAdmin = User::factory()->create([
            'role' => 'division_admin',
            'division_id' => $this->division->id,
        ]);
    }

    public function test_super_admin_can_view_recruitment_settings_page(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.recruitment.settings'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Gelombang & Kuota Divisi');
        $response->assertSee('Divisi Pemrograman');
    }

    public function test_division_admin_cannot_access_recruitment_settings(): void
    {
        $response = $this->actingAs($this->divisionAdmin)->get(route('admin.recruitment.settings'));
        $response->assertStatus(403);
    }

    public function test_super_admin_can_update_schedule_and_division_status(): void
    {
        $payload = [
            'recruitment_status' => 'open',
            'recruitment_start_date' => '2026-10-01 00:00',
            'recruitment_end_date' => '2026-10-31 23:59',
            'recruitment_batch_name' => 'Gelombang Ganjil 2026',
            'recruitment_closed_message' => 'Pendaftaran saat ini telah ditutup.',
            'divisions' => [
                $this->division->id => [
                    'is_recruitment_open' => '0',
                    'recruitment_quota' => 30,
                    'recruitment_notes' => 'Kuota Penuh',
                ],
            ],
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('admin.recruitment.settings.update'), $payload);
        $response->assertRedirect(route('admin.recruitment.settings'));
        $response->assertSessionHas('success');

        $this->division->refresh();
        $this->assertFalse($this->division->is_recruitment_open);
        $this->assertEquals(30, $this->division->recruitment_quota);
        $this->assertEquals('Kuota Penuh', $this->division->recruitment_notes);
        $this->assertEquals('Gelombang Ganjil 2026', Setting::get('recruitment_batch_name'));
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=RecruitmentSettingsAdminTest`
Expected: FAIL due to missing routes/methods.

- [ ] **Step 3: Implement controller methods, routes, and view**

In `routes/web.php` inside `middleware(['auth'])`:
```php
Route::get('/admin/recruitment/settings', [RecruitmentAdminController::class, 'settings'])->name('admin.recruitment.settings');
Route::post('/admin/recruitment/settings', [RecruitmentAdminController::class, 'updateSettings'])->name('admin.recruitment.settings.update');
```

In `app/Http/Controllers/Admin/RecruitmentAdminController.php`:
Implement:
- `settings()`: Periksa `abort_if(!Auth::user()->isSuperAdmin(), 403)`. Ambil konfigurasi dari `Setting::get(...)` dan `$divisions = Division::withCount('firstChoiceRecruitments')->get()`. Return view `admin.recruitment.settings`.
- `updateSettings(Request $request)`: Validasi dan simpan data ke `Setting::set(...)`, loop array `divisions` untuk update `is_recruitment_open`, `recruitment_quota`, `recruitment_notes` pada model `Division`. Return redirect with success message.

Create view `resources/views/admin/recruitment/settings.blade.php`:
Desain form glassmorphism premium dengan:
- Kartu Gelombang Global: switch Buka/Tutup, input `start_date` datetime-local, `end_date` datetime-local, Nama Gelombang, dan Pesan saat Ditutup.
- Kartu Tabel Divisi: nama divisi, toggle switch buka/tutup per divisi, input kuota numerik, input catatan khusus, serta badge live counter pendaftar saat ini.
- Tombol simpan perubahan.

- [ ] **Step 4: Run test to verify it passes**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=RecruitmentSettingsAdminTest`
Expected: PASS (3 tests, 9 assertions).

- [ ] **Step 5: Commit changes**

```bash
git add routes/web.php app/Http/Controllers/Admin/RecruitmentAdminController.php resources/views/admin/recruitment/* tests/Feature/RecruitmentSettingsAdminTest.php
git commit -m "feat(admin): implement recruitment schedule and division quota settings center"
```

---

### Task 3: Public Recruitment Guard & Division Availability Filter

**Files:**
- Modify: `app/Http/Controllers/RecruitmentController.php`
- Modify: `resources/views/recruitment/index.blade.php`
- Test: `tests/Feature/RecruitmentWindowGuardTest.php`

**Interfaces:**
- Consumes: `Setting::get()`, `Division::where('is_recruitment_open', true)`.
- Produces: Public view that renders either the registration form with only active divisions or a formal closed notice banner.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecruitmentWindowGuardTest extends TestCase
{
    use RefreshDatabase;

    private Division $divOpen;
    private Division $divClosed;

    protected function setUp(): void
    {
        parent::setUp();

        $this->divOpen = Division::create([
            'slug' => 'pemrograman',
            'name' => 'Divisi Pemrograman',
            'tagline' => 'Code',
            'description' => 'Desc',
            'focus_topics' => ['Laravel'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#2563eb',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen A',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua A',
            'leader_nim' => '210103001',
            'leader_bio' => 'Bio',
            'is_recruitment_open' => true,
            'recruitment_quota' => 20,
        ]);

        $this->divClosed = Division::create([
            'slug' => 'multimedia',
            'name' => 'Divisi Multimedia',
            'tagline' => 'Design',
            'description' => 'Desc',
            'focus_topics' => ['Figma'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#8b5cf6',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen B',
            'adviser_title' => 'M.Ds',
            'leader_name' => 'Ketua B',
            'leader_nim' => '210103002',
            'leader_bio' => 'Bio',
            'is_recruitment_open' => false,
            'recruitment_quota' => 0,
            'recruitment_notes' => 'Kuota Penuh',
        ]);
    }

    public function test_registration_page_only_shows_open_divisions(): void
    {
        Setting::set('recruitment_status', 'open');
        Setting::set('recruitment_start_date', now()->subDay()->format('Y-m-d H:i'));
        Setting::set('recruitment_end_date', now()->addDays(5)->format('Y-m-d H:i'));

        $response = $this->get(route('recruitment.index'));
        $response->assertStatus(200);
        $response->assertSee('Divisi Pemrograman');
        $response->assertDontSee('value="' . $this->divClosed->id . '"', false);
    }

    public function test_registration_submission_rejects_closed_division(): void
    {
        Setting::set('recruitment_status', 'open');
        Setting::set('recruitment_start_date', now()->subDay()->format('Y-m-d H:i'));
        Setting::set('recruitment_end_date', now()->addDays(5)->format('Y-m-d H:i'));

        $response = $this->post(route('recruitment.store'), [
            'full_name' => 'Calon Peserta',
            'nim' => '240103099',
            'email' => 'calon@mail.com',
            'phone_whatsapp' => '081234567890',
            'semester' => 1,
            'class_group' => 'IF-1A',
            'first_choice_division_id' => $this->divClosed->id,
            'reason_to_join' => 'Saya ingin belajar desain komunikasi visual secara mendalam.',
        ]);

        $response->assertSessionHasErrors('first_choice_division_id');
    }

    public function test_registration_blocked_when_schedule_expired(): void
    {
        Setting::set('recruitment_status', 'open');
        Setting::set('recruitment_start_date', now()->subDays(10)->format('Y-m-d H:i'));
        Setting::set('recruitment_end_date', now()->subDay()->format('Y-m-d H:i')); // expired

        $response = $this->get(route('recruitment.index'));
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Sedang Ditutup');

        $storeResponse = $this->post(route('recruitment.store'), [
            'full_name' => 'Calon Peserta',
            'nim' => '240103099',
            'email' => 'calon@mail.com',
            'phone_whatsapp' => '081234567890',
            'semester' => 1,
            'class_group' => 'IF-1A',
            'first_choice_division_id' => $this->divOpen->id,
            'reason_to_join' => 'Saya ingin belajar web programming secara mendalam.',
        ]);

        $storeResponse->assertRedirect();
        $storeResponse->assertSessionHas('error');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=RecruitmentWindowGuardTest`
Expected: FAIL.

- [ ] **Step 3: Implement controller logic and update view**

In `app/Http/Controllers/RecruitmentController.php`:
- Check window logic:
  ```php
  $status = Setting::get('recruitment_status', 'open');
  $startDate = Setting::get('recruitment_start_date');
  $endDate = Setting::get('recruitment_end_date');
  $now = now();
  $isWithinSchedule = true;
  if ($startDate && $now->lt(\Carbon\Carbon::parse($startDate))) {
      $isWithinSchedule = false;
  }
  if ($endDate && $now->gt(\Carbon\Carbon::parse($endDate))) {
      $isWithinSchedule = false;
  }
  $isOpen = ($status === 'open') && $isWithinSchedule;
  ```
- Retrieve only open divisions:
  `$divisions = Division::where('is_recruitment_open', true)->get();`
- In `store()`, check `$isOpen`. Also custom rule or validation query verifying `$division->is_recruitment_open`.
- Update `resources/views/recruitment/index.blade.php`:
  If `!$isOpen`, display an elegant announcement container with gelombang name, schedule badge, and next period notice instead of the registration form.

- [ ] **Step 4: Run test to verify it passes**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=RecruitmentWindowGuardTest`
Expected: PASS (3 tests, 7 assertions).

- [ ] **Step 5: Commit changes**

```bash
git add app/Http/Controllers/RecruitmentController.php resources/views/recruitment/index.blade.php tests/Feature/RecruitmentWindowGuardTest.php
git commit -m "feat(recruitment): enforce recruitment time window and open division selection guard"
```

---

### Task 4: Formal Division Attendance Session Creation & Metadata Flow

**Files:**
- Modify: `app/Http/Controllers/Admin/AttendanceAdminController.php`
- Modify: `resources/views/admin/attendance/create.blade.php`
- Modify: `resources/views/admin/attendance/show.blade.php`
- Test: `tests/Feature/FormalAttendanceSessionTest.php`

**Interfaces:**
- Consumes: `AttendanceSession`, `Member`, `AttendanceLog`.
- Produces: Formal session with auto-detected day name, session type, curriculum topic, learning outcomes, instructor name, and interactive presence sheet.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use App\Models\AttendanceSession;
use App\Models\Division;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormalAttendanceSessionTest extends TestCase
{
    use RefreshDatabase;

    private User $divisionAdmin;
    private Division $division;
    private Member $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->division = Division::create([
            'slug' => 'pemrograman',
            'name' => 'Divisi Pemrograman',
            'tagline' => 'Code',
            'description' => 'Desc',
            'focus_topics' => ['Laravel'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#2563eb',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen A',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua A',
            'leader_nim' => '210103001',
            'leader_bio' => 'Bio',
        ]);

        $this->divisionAdmin = User::factory()->create([
            'role' => 'division_admin',
            'division_id' => $this->division->id,
        ]);

        $this->member = Member::create([
            'division_id' => $this->division->id,
            'nim' => '220103001',
            'name' => 'Ahmad Fauzan',
            'email' => 'fauzan@mail.com',
            'status' => 'aktif',
        ]);
    }

    public function test_division_admin_can_create_formal_session_with_syllabus(): void
    {
        $payload = [
            'division_id' => $this->division->id,
            'title' => 'Workshop Arsitektur RESTful API',
            'day_name' => 'Sabtu',
            'session_date' => '2026-10-10',
            'time_start' => '13:00',
            'time_end' => '16:00',
            'session_type' => 'workshop_teknis',
            'location' => 'Lab Komputer 2',
            'topic_material' => 'Authentication Sanctum & Resource Collections',
            'learning_outcomes' => 'Peserta berhasil mengimplementasikan endpoint auth dan API transformer.',
            'instructor_name' => 'Muhammad Rayhan Fajar',
            'notes' => 'Diikuti seluruh anggota tingkat 2.',
        ];

        $response = $this->actingAs($this->divisionAdmin)->post(route('admin.attendance.store'), $payload);
        $response->assertRedirect();

        $session = AttendanceSession::latest('id')->first();
        $this->assertNotNull($session);
        $this->assertEquals('Sabtu', $session->day_name);
        $this->assertEquals('workshop_teknis', $session->session_type);
        $this->assertEquals('Authentication Sanctum & Resource Collections', $session->topic_material);
        $this->assertEquals('Muhammad Rayhan Fajar', $session->instructor_name);

        // Verify logs auto populated
        $this->assertDatabaseHas('attendance_logs', [
            'session_id' => $session->id,
            'member_id' => $this->member->id,
            'status' => 'hadir',
        ]);
    }

    public function test_session_detail_page_displays_academic_syllabus_box(): void
    {
        $session = AttendanceSession::create([
            'division_id' => $this->division->id,
            'created_by' => $this->divisionAdmin->id,
            'title' => 'Sesi Riset Algoritma',
            'day_name' => 'Rabu',
            'session_date' => '2026-10-07',
            'time_start' => '16:00',
            'time_end' => '18:00',
            'session_type' => 'riset_rutin',
            'location' => 'Ruang Riset 301',
            'topic_material' => 'Graph Theory & Shortest Path',
            'learning_outcomes' => 'Penyelesaian soal kompetisi ICPC regional.',
            'instructor_name' => 'Ketua Divisi',
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->divisionAdmin)->get(route('admin.attendance.show', $session));
        $response->assertStatus(200);
        $response->assertSee('Rabu, 07 Okt 2026');
        $response->assertSee('Graph Theory & Shortest Path');
        $response->assertSee('Penyelesaian soal kompetisi ICPC regional.');
        $response->assertSee('Ketua Divisi');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=FormalAttendanceSessionTest`
Expected: FAIL.

- [ ] **Step 3: Implement controller validation & views update**

In `app/Http/Controllers/Admin/AttendanceAdminController.php`:
- Update `store()` validation:
  ```php
  $validated = $request->validate([
      'title' => 'required|string|max:255',
      'division_id' => 'nullable|exists:divisions,id',
      'day_name' => 'required|string|max:20',
      'session_date' => 'required|date',
      'time_start' => 'required',
      'time_end' => 'nullable',
      'session_type' => 'required|string|in:riset_rutin,workshop_teknis,mentoring_proyek,evaluasi_bulanan,sidang_pleno',
      'location' => 'required|string|max:255',
      'topic_material' => 'required|string|max:255',
      'learning_outcomes' => 'nullable|string',
      'instructor_name' => 'required|string|max:150',
      'notes' => 'nullable|string',
  ]);
  ```
In `resources/views/admin/attendance/create.blade.php`:
- Tambahkan dropdown `session_type` dengan label formal:
  - Riset Rutin Divisi
  - Workshop & Pelatihan Teknis
  - Mentoring & Review Proyek
  - Evaluasi Bulanan & Rapat Kerja
  - Sidang Pleno Bersama
- Tambahkan input Hari (`day_name`) dengan script responsif auto-detect saat tanggal dipilih:
  ```javascript
  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  document.getElementById('session_date').addEventListener('change', function() {
      const d = new Date(this.value);
      if (!isNaN(d)) {
          document.getElementById('day_name').value = days[d.getDay()];
      }
  });
  ```
- Tambahkan input `topic_material` (Pokok Bahasan / Silabus Pertemuan).
- Tambahkan textarea `learning_outcomes` (Capaian Pembelajaran / Resume Materi yang Dipelajari).
- Tambahkan input `instructor_name` (Nama Pemateri / Instruktur / PIC).

In `resources/views/admin/attendance/show.blade.php`:
- Tambahkan kartu informasi akademik resmi:
  - Header badge: Hari, Tanggal, Jam, Lokasi, Tipe Sesi, dan Nama Pemateri.
  - Kartu "Silabus Pokok Bahasan & Resume Pembelajaran" yang menampilkan secara jelas materi yang dipelajari pada pertemuan ini.
  - Lembar checklist kehadiran anggota dengan status pills dan live counter.

- [ ] **Step 4: Run test to verify it passes**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=FormalAttendanceSessionTest`
Expected: PASS (2 tests, 8 assertions).

- [ ] **Step 5: Commit changes**

```bash
git add app/Http/Controllers/Admin/AttendanceAdminController.php resources/views/admin/attendance/* tests/Feature/FormalAttendanceSessionTest.php
git commit -m "feat(attendance): add formal session creation with day name, syllabus, and instructor metadata"
```

---

### Task 5: End-to-End Verification & Database Seeder Refresh

**Files:**
- Modify: `database/seeders/DatabaseSeeder.php`
- Run: Full test suite

- [ ] **Step 1: Update DatabaseSeeder with formal session and recruitment period**
- Set default recruitment settings (`recruitment_status` = `open`, start_date = 2026-10-01 00:00, end_date = 2026-10-31 23:59).
- Set division quotas and open states on `divisions`.
- Populate sample attendance session with formal `day_name`, `session_type`, `topic_material`, `learning_outcomes`, and `instructor_name`.

- [ ] **Step 2: Run migrate:fresh --seed**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan migrate:fresh --seed`
Expected: SUCCESS.

- [ ] **Step 3: Run full automated test suite**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test`
Expected: All 39+ tests PASS (0 failures).

- [ ] **Step 4: Commit changes**

```bash
git add database/seeders/DatabaseSeeder.php
git commit -m "chore: update database seeder with formal academic attendance and recruitment schedule"
```
