# Student Portal, Registration Auth, Edit Profile & Admin Visibility Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menghadirkan sistem pendaftaran terpadu dengan pembuatan akun otomatis (Email & Password), upload foto profil, link GitHub, portal mahasiswa lengkap (Status Seleksi, KTA Digital ber-QR Code, Presensi, Silabus), fitur Edit Profil mahasiswa, serta visibilitas foto profil di admin CMS.

**Architecture:** Memperluas model `User`, `Recruitment`, dan `Member`, menambahkan rute dan controller khusus mahasiswa (`/student/*`), memperbarui form pendaftaran publik, serta mengintegrasikan visual avatar pada tabel dan detail admin.

**Tech Stack:** PHP 8.1, Laravel 10, MySQL 8, Blade Template Engine, Vanilla CSS & Modern JavaScript.

**Spec:** `docs/superpowers/specs/2026-10-06-student-portal-and-account-ecosystem-design.md`

## Global Constraints
- PHP binary must be invoked via `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe"`.
- Automated tests harus tetap berjalan di database terisolasi `ukm_ilkom_testing`.
- Seluruh 45+ existing tests harus tetap hijau (0 regression failures).
- Desain UI bebas dari tampilan "AI slop", menggunakan Glassmorphism elegan, Plus Jakarta Sans, dan micro-interactions halus.

---

### Task 1: Database Migrations & Eloquent Model Upgrades

**Files:**
- Create: `database/migrations/2026_10_06_000006_upgrade_users_and_recruitments_for_student_portal.php`
- Modify: `app/Models/User.php`
- Modify: `app/Models/Recruitment.php`
- Modify: `app/Models/Member.php`
- Test: `tests/Feature/StudentEcosystemModelTest.php`

**Interfaces:**
- Consumes: Existing `users`, `recruitments`, and `members` tables.
- Produces:
  - `User::$role` supports `'member'`, `User::$avatar`, `User::$nim`, `User::$phone_number`, `User::$github_url`.
  - `Recruitment::$user_id`, `Recruitment::$profile_photo`, `Recruitment::$github_url`, `class_group` nullable.
  - `Member::$user_id`, `Member::$avatar`.
  - Relasi `User::recruitment()`, `User::member()`, `Recruitment::user()`, `Member::user()`.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentEcosystemModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_member_role_and_profile_fields(): void
    {
        $user = User::create([
            'name' => 'Bintang Mahasiswa',
            'email' => 'bintang@student.ac.id',
            'password' => bcrypt('secret123'),
            'role' => 'member',
            'nim' => '220104012',
            'avatar' => 'avatars/bintang.jpg',
            'phone_number' => '081234567890',
            'github_url' => 'https://github.com/bintang',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'member',
            'nim' => '220104012',
            'avatar' => 'avatars/bintang.jpg',
        ]);
        $this->assertTrue($user->isMember());
    }

    public function test_recruitment_and_member_user_relations(): void
    {
        $user = User::factory()->create(['role' => 'member', 'nim' => '220104012']);
        $division = Division::create([
            'slug' => 'pemrograman',
            'name' => 'Divisi Pemrograman',
            'tagline' => 'Code',
            'description' => 'Desc',
            'focus_topics' => ['PHP'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#2563eb',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua',
            'leader_nim' => '210103001',
            'leader_bio' => 'Bio',
        ]);

        $recruitment = Recruitment::create([
            'user_id' => $user->id,
            'registration_code' => 'REG-TEST-001',
            'full_name' => 'Bintang Mahasiswa',
            'nim' => '220104012',
            'email' => 'bintang@student.ac.id',
            'phone_whatsapp' => '081234567890',
            'semester' => 3,
            'class_group' => null,
            'first_choice_division_id' => $division->id,
            'reason_to_join' => 'Belajar web modern secara mendalam.',
            'profile_photo' => 'avatars/bintang.jpg',
            'github_url' => 'https://github.com/bintang',
        ]);

        $member = Member::create([
            'user_id' => $user->id,
            'division_id' => $division->id,
            'nim' => '220104012',
            'name' => 'Bintang Mahasiswa',
            'email' => 'bintang@student.ac.id',
            'avatar' => 'avatars/bintang.jpg',
            'status' => 'aktif',
        ]);

        $this->assertEquals($user->id, $recruitment->user->id);
        $this->assertEquals($user->id, $member->user->id);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=StudentEcosystemModelTest`
Expected: FAIL due to missing columns and methods.

- [ ] **Step 3: Create migration and update models**

Create migration `database/migrations/2026_10_06_000006_upgrade_users_and_recruitments_for_student_portal.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('member')->change();
            $table->string('avatar')->nullable()->after('password');
            $table->string('nim', 30)->nullable()->unique()->after('avatar');
            $table->string('phone_number', 30)->nullable()->after('nim');
            $table->string('github_url')->nullable()->after('phone_number');
        });

        Schema::table('recruitments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->string('class_group', 30)->nullable()->change();
            $table->string('profile_photo')->nullable()->after('portfolio_url');
            $table->string('github_url')->nullable()->after('profile_photo');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->string('avatar')->nullable()->after('phone_number');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'avatar']);
        });

        Schema::table('recruitments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'profile_photo', 'github_url']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'nim', 'phone_number', 'github_url']);
        });
    }
};
```

Update `app/Models/User.php`:
- Add `'avatar'`, `'nim'`, `'phone_number'`, `'github_url'` to `$fillable`.
- Add helper methods:
  ```php
  public function isMember(): bool
  {
      return $this->role === 'member';
  }
  public function getAvatarUrlAttribute(): string
  {
      if ($this->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar)) {
          return asset('storage/' . $this->avatar);
      }
      return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0284c7&color=fff&bold=true';
  }
  public function recruitment()
  {
      return $this->hasOne(Recruitment::class);
  }
  public function member()
  {
      return $this->hasOne(Member::class);
  }
  ```

Update `app/Models/Recruitment.php`:
- Add `'user_id'`, `'profile_photo'`, `'github_url'` to `$fillable`.
- Add relation `user()`: `return $this->belongsTo(User::class);`.
- Add accessor `getAvatarUrlAttribute()`:
  ```php
  public function getAvatarUrlAttribute(): string
  {
      if ($this->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->profile_photo)) {
          return asset('storage/' . $this->profile_photo);
      }
      return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&background=2563eb&color=fff&bold=true';
  }
  ```

Update `app/Models/Member.php`:
- Add `'user_id'`, `'avatar'` to `$fillable`.
- Add relation `user()`: `return $this->belongsTo(User::class);`.
- Add accessor `getAvatarUrlAttribute()`:
  ```php
  public function getAvatarUrlAttribute(): string
  {
      if ($this->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar)) {
          return asset('storage/' . $this->avatar);
      }
      return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=' . substr($this->division?->color_accent ?? '#0f172a', 1) . '&color=fff&bold=true';
  }
  ```

- [ ] **Step 4: Run test to verify it passes**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=StudentEcosystemModelTest`
Expected: PASS (2 tests, 5 assertions).

- [ ] **Step 5: Commit changes**

```bash
git add database/migrations/* app/Models/* tests/Feature/StudentEcosystemModelTest.php
git commit -m "feat(schema): upgrade users, recruitments, and members for student portal ecosystem"
```

---

### Task 2: Integrated Registration with Account Creation & Smart Login

**Files:**
- Modify: `resources/views/recruitment/index.blade.php`
- Modify: `app/Http/Controllers/RecruitmentController.php`
- Modify: `app/Http/Controllers/AuthController.php`
- Test: `tests/Feature/StudentRegistrationAndAuthTest.php`

**Interfaces:**
- Consumes: `Request` with name, nim, email, password, password_confirmation, profile_photo, github_url, etc.
- Produces: Created `User` with `role = 'member'`, created `Recruitment` linked to `user`, auto-login, redirect to `student.dashboard`.
- Produces: `AuthController@login` smart redirect depending on role.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentRegistrationAndAuthTest extends TestCase
{
    use RefreshDatabase;

    private Division $division;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Setting::set('recruitment_status', 'open');

        $this->division = Division::create([
            'slug' => 'pemrograman',
            'name' => 'Divisi Pemrograman',
            'tagline' => 'Code',
            'description' => 'Desc',
            'focus_topics' => ['PHP'],
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
    }

    public function test_student_registers_and_auto_creates_account_and_logs_in(): void
    {
        $payload = [
            'full_name' => 'Bintang Pratama',
            'nim' => '240103055',
            'email' => 'bintang@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone_whatsapp' => '081234567890',
            'semester' => 2,
            'first_choice_division_id' => $this->division->id,
            'reason_to_join' => 'Saya ingin belajar arsitektur backend dan AI agentic modern.',
            'github_url' => 'https://github.com/bintangpratama',
            'profile_photo' => UploadedFile::fake()->image('bintang.jpg'),
        ];

        $response = $this->post(route('recruitment.store'), $payload);
        $response->assertRedirect(route('student.dashboard'));

        $this->assertAuthenticated();

        $user = User::where('email', 'bintang@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('member', $user->role);
        $this->assertEquals('240103055', $user->nim);
        $this->assertNotNull($user->avatar);

        $this->assertDatabaseHas('recruitments', [
            'user_id' => $user->id,
            'nim' => '240103055',
            'github_url' => 'https://github.com/bintangpratama',
        ]);
    }

    public function test_login_redirects_member_to_student_dashboard(): void
    {
        $memberUser = User::create([
            'name' => 'Anggota Test',
            'email' => 'anggota@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'member',
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'anggota@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('student.dashboard'));
    }

    public function test_login_redirects_admin_to_admin_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'admin@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=StudentRegistrationAndAuthTest`
Expected: FAIL.

- [ ] **Step 3: Update RecruitmentController, AuthController, and Blade View**

In `app/Http/Controllers/RecruitmentController.php`:
- Update validation in `store()`:
  ```php
  'password' => 'required|string|min:6|confirmed',
  'profile_photo' => 'nullable|image|max:2048',
  'github_url' => 'nullable|url|max:255',
  'email' => 'required|email|max:100|unique:users,email',
  'nim' => 'required|string|max:30|unique:users,nim',
  ```
- If `profile_photo` uploaded: `$avatarPath = $request->file('profile_photo')->store('avatars', 'public');`
- Create `User`:
  ```php
  $user = User::create([
      'name' => $validated['full_name'],
      'email' => $validated['email'],
      'password' => bcrypt($validated['password']),
      'role' => 'member',
      'avatar' => $avatarPath ?? null,
      'nim' => $validated['nim'],
      'phone_number' => $validated['phone_whatsapp'],
      'github_url' => $validated['github_url'] ?? null,
  ]);
  ```
- Create `Recruitment`:
  Include `'user_id' => $user->id`, `'profile_photo' => $avatarPath ?? null`, `'github_url' => $validated['github_url'] ?? null`.
- Call `Auth::login($user)`.
- Redirect to `route('student.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di Portal Mahasiswa UKM.')`.

In `app/Http/Controllers/AuthController.php`:
- Update `login()` redirect logic:
  ```php
  $user = Auth::user();
  if ($user->isMember()) {
      return redirect()->intended(route('student.dashboard'))
          ->with('success', 'Selamat datang kembali di Portal Mahasiswa, ' . $user->name . '!');
  }
  return redirect()->intended(route('admin.dashboard'))
      ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
  ```

In `resources/views/recruitment/index.blade.php`:
- Update Step 1:
  - Add Password & Konfirmasi Password inputs.
  - Add Upload Foto Profil input with preview.
  - Remove Rombel / Kelas input.
- Update Step 3:
  - Add Link GitHub input (`github_url`).

- [ ] **Step 4: Run test to verify it passes**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=StudentRegistrationAndAuthTest`
Expected: PASS (3 tests, 11 assertions).

- [ ] **Step 5: Commit changes**

```bash
git add app/Http/Controllers/RecruitmentController.php app/Http/Controllers/AuthController.php resources/views/recruitment/index.blade.php tests/Feature/StudentRegistrationAndAuthTest.php
git commit -m "feat(auth): integrate student account creation during registration with smart role redirect"
```

---

### Task 3: Student Portal Dashboard & KTA Digital

**Files:**
- Create: `app/Http/Controllers/Student/StudentDashboardController.php`
- Create: `resources/views/student/layouts/app.blade.php`
- Create: `resources/views/student/dashboard.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/StudentDashboardAccessTest.php`

**Interfaces:**
- Consumes: Authenticated member `User`, related `Recruitment`, `Member`, division sessions, attendance logs.
- Produces: Route `student.dashboard` (`GET /student/dashboard`) rendering status timeline, digital ID Card with QR Code, presence statistics, and division syllabus modules.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use App\Models\AttendanceLog;
use App\Models\AttendanceSession;
use App\Models\Division;
use App\Models\Member;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentDashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $memberUser;
    private Division $division;

    protected function setUp(): void
    {
        parent::setUp();

        $this->division = Division::create([
            'slug' => 'pemrograman',
            'name' => 'Divisi Pemrograman',
            'tagline' => 'Software Engineering',
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

        $this->memberUser = User::create([
            'name' => 'Ahmad Fauzan',
            'email' => 'fauzan@student.com',
            'password' => bcrypt('password123'),
            'role' => 'member',
            'nim' => '220104088',
        ]);
    }

    public function test_guest_cannot_access_student_dashboard(): void
    {
        $response = $this->get(route('student.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_student_can_view_dashboard_with_selection_status_and_kta(): void
    {
        Recruitment::create([
            'user_id' => $this->memberUser->id,
            'registration_code' => 'REG-2026-999',
            'full_name' => 'Ahmad Fauzan',
            'nim' => '220104088',
            'email' => 'fauzan@student.com',
            'phone_whatsapp' => '081298765432',
            'semester' => 3,
            'first_choice_division_id' => $this->division->id,
            'reason_to_join' => 'Motivasi belajar coding web modern.',
            'status' => 'interview',
            'selection_stage' => 'wawancara',
            'interview_schedule' => now()->addDays(2),
            'interview_location' => 'Lab Komputer 3 Gedung Fasilkom',
        ]);

        $response = $this->actingAs($this->memberUser)->get(route('student.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Portal Mahasiswa');
        $response->assertSee('Tahap Wawancara');
        $response->assertSee('Lab Komputer 3 Gedung Fasilkom');
        $response->assertSee('Kartu Tanda Anggota');
        $response->assertSee('220104088');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=StudentDashboardAccessTest`
Expected: FAIL due to missing routes/views.

- [ ] **Step 3: Implement controller, views, and routes**

In `routes/web.php`:
```php
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Student\StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\Student\StudentProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Student\StudentProfileController::class, 'update'])->name('profile.update');
});
```

Create `app/Http/Controllers/Student/StudentDashboardController.php`:
- `index()`:
  - If user is admin (`isSuperAdmin() || isDivisionAdmin()`), redirect to `route('admin.dashboard')`.
  - Fetch `$recruitment = Recruitment::where('user_id', $user->id)->first()`.
  - Fetch `$member = Member::where('user_id', $user->id)->orWhere('nim', $user->nim)->first()`.
  - Fetch division (from member or recruitment choice).
  - Fetch division sessions & attendance logs for this member.
  - Calculate attendance percentage.
  - Return view `student.dashboard`.

Create `resources/views/student/layouts/app.blade.php`:
- Modern minimalist layout with top navbar:
  - Logo UKM ILKOM, "Portal Mahasiswa", link Dashboard, link Edit Profil, badge status, avatar pill, and Logout form.
  - Responsive container, flash messages support.

Create `resources/views/student/dashboard.blade.php`:
- Header card with avatar, name, NIM, email, division badge, and "Edit Profil" button.
- 4 Tabs / Sections:
  1. Status Seleksi & Timeline Rekrutmen (Administrasi &rarr; Wawancara &rarr; Hasil).
  2. KTA Digital (Apple wallet / Holographic card design with official UKM logo, avatar, NIM, name, QR Code verifikasi).
  3. Riwayat Presensi & Kehadiran (Live progress meter & attendance log table).
  4. Silabus & Rangkuman Materi Pembelajaran Divisi.

- [ ] **Step 4: Run test to verify it passes**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=StudentDashboardAccessTest`
Expected: PASS (2 tests, 7 assertions).

- [ ] **Step 5: Commit changes**

```bash
git add routes/web.php app/Http/Controllers/Student/StudentDashboardController.php resources/views/student/* tests/Feature/StudentDashboardAccessTest.php
git commit -m "feat(student): build student portal dashboard with selection timeline and digital KTA"
```

---

### Task 4: Student Edit Profile Feature

**Files:**
- Create: `app/Http/Controllers/Student/StudentProfileController.php`
- Create: `resources/views/student/profile.blade.php`
- Test: `tests/Feature/StudentProfileEditTest.php`

**Interfaces:**
- Consumes: Route `student.profile` (`GET`), `student.profile.update` (`PUT`).
- Produces: Update user's name, phone_number, github_url, avatar upload, password change, and synchronization with `Member` and `Recruitment`.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentProfileEditTest extends TestCase
{
    use RefreshDatabase;

    private User $memberUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->memberUser = User::create([
            'name' => 'Nama Lama',
            'email' => 'student@test.com',
            'password' => bcrypt('oldpassword'),
            'role' => 'member',
            'phone_number' => '0811111111',
            'github_url' => 'https://github.com/old',
        ]);
    }

    public function test_student_can_update_profile_info_and_avatar(): void
    {
        $payload = [
            'name' => 'Nama Baru Lengkap',
            'phone_number' => '0899999999',
            'github_url' => 'https://github.com/newprofile',
            'avatar' => UploadedFile::fake()->image('newavatar.jpg'),
        ];

        $response = $this->actingAs($this->memberUser)->put(route('student.profile.update'), $payload);
        $response->assertRedirect(route('student.profile'));
        $response->assertSessionHas('success');

        $this->memberUser->refresh();
        $this->assertEquals('Nama Baru Lengkap', $this->memberUser->name);
        $this->assertEquals('0899999999', $this->memberUser->phone_number);
        $this->assertEquals('https://github.com/newprofile', $this->memberUser->github_url);
        $this->assertNotNull($this->memberUser->avatar);
    }

    public function test_student_can_change_password(): void
    {
        $payload = [
            'name' => $this->memberUser->name,
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ];

        $response = $this->actingAs($this->memberUser)->put(route('student.profile.update'), $payload);
        $response->assertRedirect(route('student.profile'));

        $this->memberUser->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->memberUser->password));
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=StudentProfileEditTest`
Expected: FAIL.

- [ ] **Step 3: Implement controller and view**

Create `app/Http/Controllers/Student/StudentProfileController.php`:
- `edit()`: Return view `student.profile` with `compact('user')`.
- `update(Request $request)`:
  - Validate:
    - `name => required|string|max:100`
    - `phone_number => nullable|string|max:25`
    - `github_url => nullable|url|max:255`
    - `avatar => nullable|image|max:2048`
    - `current_password => nullable|string`
    - `new_password => nullable|string|min:6|confirmed`
  - If password change requested: verify `Hash::check($request->current_password, $user->password)`, then update password.
  - If new avatar uploaded: store file, delete old avatar if exists, update `$user->avatar`.
  - Update `Member` and `Recruitment` names and avatars if linked.
  - Redirect back with success flash message.

Create `resources/views/student/profile.blade.php`:
- Header: "Edit Profil Mahasiswa".
- Avatar section with current image / preview.
- Form inputs: Nama Lengkap, Email (disabled/readonly), NIM (disabled/readonly), Nomor WhatsApp, Link GitHub.
- Keamanan Akun: Password Saat Ini, Password Baru, Konfirmasi Password Baru.
- Tombol Simpan Perubahan Profil.

- [ ] **Step 4: Run test to verify it passes**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=StudentProfileEditTest`
Expected: PASS (2 tests, 8 assertions).

- [ ] **Step 5: Commit changes**

```bash
git add app/Http/Controllers/Student/StudentProfileController.php resources/views/student/profile.blade.php tests/Feature/StudentProfileEditTest.php
git commit -m "feat(student): implement student profile edit and password change"
```

---

### Task 5: Admin Photo Visibility in Recruitment & Members CMS

**Files:**
- Modify: `resources/views/admin/recruitment/index.blade.php`
- Modify: `resources/views/admin/recruitment/show.blade.php`
- Modify: `resources/views/admin/members/index.blade.php`
- Modify: `app/Http/Controllers/Admin/RecruitmentAdminController.php` (sync avatar and `user_id` when converting to member)
- Test: `tests/Feature/AdminProfilePhotoVisibilityTest.php`

**Interfaces:**
- Consumes: `Recruitment::$avatar_url`, `Member::$avatar_url`.
- Produces: Visual avatar thumbnail rendered in admin recruitment table, show page, and admin members table.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProfilePhotoVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
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
            'focus_topics' => ['PHP'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#2563eb',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua',
            'leader_nim' => '210103001',
            'leader_bio' => 'Bio',
        ]);
    }

    public function test_admin_recruitment_list_displays_applicant_avatar(): void
    {
        $applicant = Recruitment::create([
            'registration_code' => 'REG-PHOTO-01',
            'full_name' => 'Citra Maharani',
            'nim' => '230104077',
            'email' => 'citra@gmail.com',
            'phone_whatsapp' => '081233445566',
            'semester' => 2,
            'first_choice_division_id' => $this->division->id,
            'reason_to_join' => 'Motivasi belajar desain antarmuka.',
            'profile_photo' => 'avatars/citra.jpg',
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('admin.recruitment.index'));
        $response->assertStatus(200);
        $response->assertSee('Citra Maharani');
        $response->assertSee('avatar-thumb', false);
    }

    public function test_admin_members_list_displays_member_avatar(): void
    {
        Member::create([
            'division_id' => $this->division->id,
            'nim' => '230104077',
            'name' => 'Citra Maharani',
            'email' => 'citra@gmail.com',
            'avatar' => 'avatars/citra.jpg',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('admin.members.index'));
        $response->assertStatus(200);
        $response->assertSee('Citra Maharani');
        $response->assertSee('member-avatar', false);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=AdminProfilePhotoVisibilityTest`
Expected: FAIL due to missing avatar classes/markup.

- [ ] **Step 3: Update views and controller**

In `resources/views/admin/recruitment/index.blade.php`:
- In the table row for each applicant, add avatar thumbnail:
  ```html
  <div style="display: flex; align-items: center; gap: 0.75rem;">
      <img src="{{ $app->avatar_url }}" alt="{{ $app->full_name }}" class="avatar-thumb" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid var(--slate-200);">
      <div>
          <div style="font-weight: 700; color: var(--slate-900);">{{ $app->full_name }}</div>
          <div style="font-size: 0.8rem; color: var(--slate-500);">{{ $app->nim }}</div>
      </div>
  </div>
  ```

In `resources/views/admin/recruitment/show.blade.php`:
- In the applicant profile card, display the profile photo prominently with avatar frame and GitHub badge.

In `resources/views/admin/members/index.blade.php`:
- In the table row for each member, add member avatar thumbnail:
  ```html
  <div style="display: flex; align-items: center; gap: 0.75rem;">
      <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="member-avatar" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid var(--slate-200);">
      <div>
          <div style="font-weight: 700; color: var(--slate-900);">{{ $member->name }}</div>
          <div style="font-size: 0.8rem; color: var(--slate-500);">NIM: {{ $member->nim }}</div>
      </div>
  </div>
  ```

In `app/Http/Controllers/Admin/RecruitmentAdminController.php` (`convertToMember`):
- When creating member, include `'user_id' => $recruitment->user_id`, `'avatar' => $recruitment->profile_photo`.

- [ ] **Step 4: Run test to verify it passes**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test --filter=AdminProfilePhotoVisibilityTest`
Expected: PASS (2 tests, 6 assertions).

- [ ] **Step 5: Commit changes**

```bash
git add resources/views/admin/recruitment/* resources/views/admin/members/* app/Http/Controllers/Admin/RecruitmentAdminController.php tests/Feature/AdminProfilePhotoVisibilityTest.php
git commit -m "feat(admin): display student profile photos in recruitment and members list"
```

---

### Task 6: Database Seeding & Full Test Suite Verification

**Files:**
- Modify: `database/seeders/DatabaseSeeder.php`
- Run: Full test suite verification

- [ ] **Step 1: Update DatabaseSeeder with sample student user**
- Create sample member user account: `bintang@student.ac.id` / `student123`.
- Link sample recruitment and member to this user account with avatar.

- [ ] **Step 2: Run migrate:fresh --seed**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan migrate:fresh --seed`
Expected: SUCCESS.

- [ ] **Step 3: Run full automated test suite**

Run: `& "E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" artisan test`
Expected: All 50+ tests PASS (0 failures).

- [ ] **Step 4: Commit changes**

```bash
git add database/seeders/DatabaseSeeder.php
git commit -m "chore: seed sample student user account and verify 100% test suite"
```
