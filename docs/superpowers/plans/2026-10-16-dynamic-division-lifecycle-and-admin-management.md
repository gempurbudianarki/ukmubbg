# Dynamic Division Lifecycle & Admin Management Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [x]`) syntax for tracking.

**Goal:** Transform the UKM division subsystem into an evergreen dynamic ecosystem where Super Admins can create new divisions with custom colors and icons, assign/remove division admins, toggle suspension, and safely soft-delete without touching application code.

**Architecture:** Add `status`, `icon_class`, and `SoftDeletes` to the `divisions` table; expand `DivisionAdminController` with full lifecycle operations (create, store, toggle-status, toggle-recruitment, assign-admin, remove-admin, soft-delete, restore); replace hardcoded division styles in views and recruitment wizard with CSS custom property token injection (`--div-accent`, `--div-accent-bg`).

**Tech Stack:** Laravel 10.x, PHP 8.1+, MySQL / SQLite, Vanilla CSS (White Claymorphism Design System), FontAwesome 6, PHPUnit.

**Spec:** `docs/superpowers/specs/2026-10-16-dynamic-division-lifecycle-and-admin-management-design.md`

## Global Constraints

- **PHP Version:** PHP 8.1+
- **Laravel Framework:** 10.x
- **Design System:** Modern White Claymorphism (custom vanilla CSS, no Tailwind)
- **Data Integrity:** SoftDeletes must be used on divisions to ensure existing members, KTA, attendance logs, and student portfolios are preserved.
- **Access Control:** Only `super_admin` can create, delete, suspend, or assign admins to divisions. `division_admin` remains strictly isolated to their assigned division.

---

### Task 1: Database Migration & Eloquent Model Upgrade

**Files:**
- Create: `database/migrations/2026_10_16_000001_upgrade_divisions_table_lifecycle_and_branding.php`
- Modify: `app/Models/Division.php`
- Test: `tests/Feature/DynamicDivisionModelTest.php`

**Interfaces:**
- Consumes: Existing `divisions` table
- Produces: `Division` model with `SoftDeletes`, `status` (`active`, `suspended`, `archived`), `icon_class`, scopes `scopeActive()`, `scopeOpenForRecruitment()`, and `admins()` relation.

- [x] **Step 1: Write the failing test for Division model extensions**

Create `tests/Feature/DynamicDivisionModelTest.php`:
```php
<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicDivisionModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_division_supports_status_icon_class_and_soft_deletes(): void
    {
        $division = Division::create([
            'name' => 'Divisi Artificial Intelligence',
            'slug' => 'artificial-intelligence',
            'tagline' => 'Riset AI & Machine Learning',
            'description' => 'Eksplorasi model bahasa dan visi komputer.',
            'status' => 'active',
            'color_accent' => '#6366f1',
            'icon_class' => 'fa-robot',
            'is_recruitment_open' => true,
            'recruitment_quota' => 20,
        ]);

        $this->assertDatabaseHas('divisions', [
            'id' => $division->id,
            'status' => 'active',
            'icon_class' => 'fa-robot',
            'color_accent' => '#6366f1',
        ]);

        $this->assertTrue($division->isActive());
        $this->assertFalse($division->isSuspended());

        // Test Soft Delete
        $division->delete();
        $this->assertSoftDeleted('divisions', ['id' => $division->id]);
        $this->assertNull(Division::find($division->id));
        $this->assertNotNull(Division::withTrashed()->find($division->id));
    }

    public function test_division_admins_relation(): void
    {
        $division = Division::create([
            'name' => 'Divisi Game Development',
            'slug' => 'game-dev',
            'status' => 'active',
        ]);

        $admin = User::create([
            'name' => 'Admin Game Dev',
            'email' => 'gamedev@ukmilkom.id',
            'password' => bcrypt('password123'),
            'role' => 'division_admin',
            'division_id' => $division->id,
        ]);

        $this->assertTrue($division->admins->contains($admin));
        $this->assertEquals(1, $division->admins_count);
    }
}
```

- [x] **Step 2: Run test to verify it fails**

Run: `& 'E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe' artisan test --filter=DynamicDivisionModelTest`
Expected: FAIL because columns `status`, `icon_class`, and `deleted_at` do not exist yet.

- [x] **Step 3: Create migration and update Division model**

Create `database/migrations/2026_10_16_000001_upgrade_divisions_table_lifecycle_and_branding.php`:
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
    }

    public function down(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            if (Schema::hasColumn('divisions', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            if (Schema::hasColumn('divisions', 'icon_class')) {
                $table->dropColumn('icon_class');
            }
            if (Schema::hasColumn('divisions', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
```

Update `app/Models/Division.php` to include:
- `use Illuminate\Database\Eloquent\SoftDeletes;`
- Add `'status'`, `'icon_class'` to `$fillable`.
- Add methods:
  ```php
  public function admins()
  {
      return $this->hasMany(User::class)->where('role', 'division_admin');
  }

  public function getAdminsCountAttribute(): int
  {
      return $this->admins()->count();
  }

  public function scopeActive($query)
  {
      return $query->where('status', 'active');
  }

  public function scopeOpenForRecruitment($query)
  {
      return $query->where('status', 'active')->where('is_recruitment_open', true);
  }

  public function isActive(): bool
  {
      return $this->status === 'active';
  }

  public function isSuspended(): bool
  {
      return $this->status === 'suspended';
  }
  ```

- [x] **Step 4: Run migration and verify test passes**

Run: `& 'E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe' artisan test --filter=DynamicDivisionModelTest`
Expected: PASS

- [x] **Step 5: Commit**

```bash
git add database/migrations/2026_10_16_000001_upgrade_divisions_table_lifecycle_and_branding.php app/Models/Division.php tests/Feature/DynamicDivisionModelTest.php
git commit -m "feat(divisions): add status lifecycle, icon_class, and soft deletes to divisions"
```

---

### Task 2: Division Management Backend CRUD & Lifecycle Controller

**Files:**
- Modify: `routes/web.php`
- Modify: `app/Http/Controllers/Admin/DivisionAdminController.php`
- Test: `tests/Feature/DivisionAdminLifecycleTest.php`

**Interfaces:**
- Consumes: `Division`, `User`, auth middleware `['auth', 'admin']`
- Produces: Full division management endpoints: `create`, `store`, `update`, `toggleStatus`, `toggleRecruitment`, `assignAdmin`, `removeAdmin`, `destroy`, `restore`.

- [x] **Step 1: Write failing test for DivisionAdminController lifecycle**

Create `tests/Feature/DivisionAdminLifecycleTest.php`:
```php
<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DivisionAdminLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $divisionAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@ukmilkom.id',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
        ]);

        $division = Division::create([
            'name' => 'Divisi Pemrograman',
            'slug' => 'pemrograman',
            'status' => 'active',
        ]);

        $this->divisionAdmin = User::create([
            'name' => 'Admin Divisi',
            'email' => 'pemrograman@ukmilkom.id',
            'password' => bcrypt('password123'),
            'role' => 'division_admin',
            'division_id' => $division->id,
        ]);
    }

    public function test_super_admin_can_create_new_division(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('admin.divisions.store'), [
            'name' => 'Divisi Data Science',
            'tagline' => 'Analisis Data & AI',
            'description' => 'Eksplorasi big data dan machine learning.',
            'focus_topics_raw' => "Python Data Science\nDeep Learning\nSQL Big Data",
            'color_accent' => '#0ea5e9',
            'icon_class' => 'fa-database',
            'recruitment_quota' => 25,
            'is_recruitment_open' => '1',
            'adviser_name' => 'Dr. Data, M.Kom.',
            'adviser_title' => 'Dosen AI',
            'leader_name' => 'Ketua Data',
            'leader_nim' => '240101001',
            'leader_bio' => 'Bio ketua data.',
        ]);

        $response->assertRedirect(route('admin.divisions.index'));
        $this->assertDatabaseHas('divisions', [
            'name' => 'Divisi Data Science',
            'slug' => 'divisi-data-science',
            'color_accent' => '#0ea5e9',
            'icon_class' => 'fa-database',
            'status' => 'active',
        ]);
    }

    public function test_super_admin_can_toggle_suspend_division(): void
    {
        $division = Division::create([
            'name' => 'Divisi Robotika',
            'slug' => 'robotika',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('admin.divisions.toggleStatus', $division));
        $response->assertSessionHas('success');
        $this->assertEquals('suspended', $division->fresh()->status);

        // Toggle back to active
        $this->actingAs($this->superAdmin)->post(route('admin.divisions.toggleStatus', $division));
        $this->assertEquals('active', $division->fresh()->status);
    }

    public function test_super_admin_can_assign_and_remove_division_admin(): void
    {
        $division = Division::create([
            'name' => 'Divisi Cloud Computing',
            'slug' => 'cloud',
            'status' => 'active',
        ]);

        $memberUser = User::create([
            'name' => 'Budi Calon Admin',
            'email' => 'budi@student.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'member',
        ]);

        // Assign Admin
        $response = $this->actingAs($this->superAdmin)->post(route('admin.divisions.assignAdmin', $division), [
            'user_id' => $memberUser->id,
        ]);
        $response->assertSessionHas('success');
        $this->assertEquals('division_admin', $memberUser->fresh()->role);
        $this->assertEquals($division->id, $memberUser->fresh()->division_id);

        // Remove Admin
        $removeResponse = $this->actingAs($this->superAdmin)->post(route('admin.divisions.removeAdmin', [$division, $memberUser]));
        $removeResponse->assertSessionHas('success');
        $this->assertEquals('member', $memberUser->fresh()->role);
    }

    public function test_division_admin_cannot_create_or_suspend_divisions(): void
    {
        $response = $this->actingAs($this->divisionAdmin)->post(route('admin.divisions.store'), [
            'name' => 'Divisi Hack',
        ]);
        $response->assertForbidden();
    }
}
```

- [x] **Step 2: Run test to verify it fails**

Run: `& 'E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe' artisan test --filter=DivisionAdminLifecycleTest`
Expected: FAIL (routes / actions do not exist).

- [x] **Step 3: Update routes and implement DivisionAdminController actions**

In `routes/web.php`:
Update Division routes under `admin` prefix:
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

In `app/Http/Controllers/Admin/DivisionAdminController.php`:
Implement:
- `create()`: Super Admin check; return form view.
- `store()`: Validate request (name, color_accent, icon_class, topics, leader, adviser, quota), generate slug, upload photos, create Division.
- `update()`: Update division fields, color, icon, topics, leader, adviser, quota.
- `toggleStatus()`: Super Admin check; toggle between 'active' and 'suspended'.
- `toggleRecruitment()`: Super Admin or assigned Division Admin check; toggle `is_recruitment_open`.
- `assignAdmin()`: Super Admin check; validate `user_id`, update user `role => 'division_admin'`, `division_id => $division->id`.
- `removeAdmin()`: Super Admin check; update user `role => 'member'`.
- `destroy()`: Super Admin check; `$division->update(['status' => 'archived']); $division->delete();`.
- `restore()`: Super Admin check; restore soft-deleted division, set `status => 'active'`.

- [x] **Step 4: Run test to verify it passes**

Run: `& 'E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe' artisan test --filter=DivisionAdminLifecycleTest`
Expected: PASS

- [x] **Step 5: Commit**

```bash
git add routes/web.php app/Http/Controllers/Admin/DivisionAdminController.php tests/Feature/DivisionAdminLifecycleTest.php
git commit -m "feat(admin): implement complete dynamic division lifecycle and admin assignment controllers"
```

---

### Task 3: Admin CMS Views (Interactive Management Hub & Live Preview Form)

**Files:**
- Create: `resources/views/admin/divisions/create.blade.php`
- Modify: `resources/views/admin/divisions/index.blade.php`
- Modify: `resources/views/admin/divisions/edit.blade.php`

**Interfaces:**
- Consumes: `$divisions`, `$users` (candidates for admin assignment), `$user`
- Produces: Claymorphism division hub with metrics, quick status toggles, assign admin modal, and live color/icon preview form.

- [x] **Step 1: Build `create.blade.php` with live color picker, icon selector, and real-time preview card**

Create `resources/views/admin/divisions/create.blade.php` featuring:
- Live Clay Preview Card that updates color and icon immediately upon input.
- Color Accent input (`<input type="color">` and HEX text input) + preset modern tech color chips (Sky, Emerald, Violet, Rose, Amber, Indigo, Cyan, Orange).
- Icon Class selector with 12 quick-select technology icons (`fa-laptop-code`, `fa-palette`, `fa-microchip`, `fa-shield-halved`, `fa-robot`, `fa-gamepad`, `fa-brain`, `fa-database`, `fa-network-wired`, `fa-code`, `fa-satellite-dish`, `fa-cube`).
- Fields for division name, tagline, description, focus topics (1 line per topic), recruitment quota, adviser bio, and leader bio.

- [x] **Step 2: Update `index.blade.php` with Division Metrics, Multi-Admin Manager, and Quick Action Badges**

In `resources/views/admin/divisions/index.blade.php`:
- Add metric header: Total Divisi Aktif, Total Suspended, Total Admin Divisi Bertugas, Total Kuota.
- Add "+ Tambah Divisi Baru" button for Super Admin.
- Display each division as an elevated Claymorphism card showing:
  - Custom color banner & icon.
  - Status Badge (`Aktif`, `Suspended`, `Pendaftaran Buka/Tutup`).
  - **Admin Divisi Bertugas List**: Avatars and names of assigned admins with quick "Copot Hak" button and "+ Tunjuk Admin" modal trigger.
  - Action buttons: Edit Profil, Toggle Suspend, Toggle Pendaftaran, and Hapus (Arsipkan).
  - Include an interactive modal for Super Admin to select a user and assign them as Admin Divisi.

- [x] **Step 3: Update `edit.blade.php` to support color picker, icon selector, and status toggle**

In `resources/views/admin/divisions/edit.blade.php`:
- Add fields for `color_accent`, `icon_class`, and `recruitment_quota`.
- Sync with the same live preview mechanism as the create view.

- [x] **Step 4: Verify views rendering via browser subagent or curl/artisan test**

Create a test checking that `/admin/divisions` and `/admin/divisions/create` render HTTP 200 for Super Admin.
Run: `& 'E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe' artisan test --filter=DivisionAdminLifecycleTest`
Expected: PASS

- [x] **Step 5: Commit**

```bash
git add resources/views/admin/divisions/
git commit -m "feat(views): create interactive division management hub with live preview and admin modal"
```

---

### Task 4: Complete Decoupling of Frontend & Client-Side Views

**Files:**
- Modify: `resources/views/recruitment/index.blade.php`
- Modify: `resources/views/layouts/footer.blade.php`
- Modify: `app/Models/Project.php`
- Modify: `app/Models/Event.php`
- Test: `tests/Feature/DynamicFrontendDecouplingTest.php`

**Interfaces:**
- Consumes: `Division::where('status', 'active')`
- Produces: 100% dynamic views that automatically accommodate any newly created division without hardcoded slug checks or fixed division counts.

- [x] **Step 1: Write test verifying newly created divisions render dynamically in Recruitment Wizard**

Create `tests/Feature/DynamicFrontendDecouplingTest.php`:
```php
<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicFrontendDecouplingTest extends TestCase
{
    use RefreshDatabase;

    public function test_recruitment_wizard_renders_newly_created_custom_division(): void
    {
        Setting::set('recruitment_status', 'open');

        $customDivision = Division::create([
            'name' => 'Divisi Internet of Robotics',
            'slug' => 'robotics',
            'tagline' => 'Robotika Masa Depan',
            'description' => 'Membangun robot otonom dan otomasi canggih.',
            'color_accent' => '#f97316',
            'icon_class' => 'fa-robot',
            'status' => 'active',
            'is_recruitment_open' => true,
            'recruitment_quota' => 15,
        ]);

        $response = $this->get(route('recruitment.index'));
        $response->assertOk();
        $response->assertSee('Divisi Internet of Robotics');
        $response->assertSee('fa-robot');
        $response->assertSee('#f97316');
    }

    public function test_suspended_division_is_hidden_from_recruitment_wizard(): void
    {
        Setting::set('recruitment_status', 'open');

        $activeDiv = Division::create([
            'name' => 'Divisi Aktif',
            'slug' => 'aktif',
            'status' => 'active',
            'is_recruitment_open' => true,
        ]);

        $suspendedDiv = Division::create([
            'name' => 'Divisi Dibekukan',
            'slug' => 'dibekukan',
            'status' => 'suspended',
            'is_recruitment_open' => true,
        ]);

        $response = $this->get(route('recruitment.index'));
        $response->assertOk();
        $response->assertSee('Divisi Aktif');
        $response->assertDontSee('Divisi Dibekukan');
    }
}
```

- [x] **Step 2: Run test to verify it fails**

Run: `& 'E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe' artisan test --filter=DynamicFrontendDecouplingTest`
Expected: FAIL or incomplete matching.

- [x] **Step 3: Decouple `recruitment/index.blade.php` and `RecruitmentController.php`**

In `app/Http/Controllers/RecruitmentController.php`:
Ensure `$divisions = Division::where('status', 'active')->where('is_recruitment_open', true)->withCount('firstChoiceApplicants')->get();`.

In `resources/views/recruitment/index.blade.php`:
Remove:
```blade
if (str_contains($slug, 'multi') ... ) { ... }
```
Replace with dynamic tokens:
```blade
@php
    $accentColor = $d->color_accent ?: '#0284c7';
    $iconClass = $d->icon_class ?: 'fa-laptop-code';
    $quota = $d->recruitment_quota;
    $remainingQuota = $d->remaining_quota;
    $isFull = (bool)$d->is_quota_full;
    $registeredCount = (int)($d->first_choice_applicants_count ?? 0);
    $isSelected = !$isFull && (old('first_choice_division_id', optional($preselectedDivision)->id) == $d->id);
    $percentage = ($quota && $quota > 0) ? min(100, round(($registeredCount / $quota) * 100)) : null;
@endphp
<div class="division-select-card {{ $isSelected ? 'selected' : '' }} {{ $isFull ? 'is-full disabled' : '' }}" 
     id="divCardPrimary_{{ $d->id }}" 
     style="--div-accent: {{ $accentColor }}; --div-accent-bg: {{ $accentColor }}14; --div-accent-border: {{ $accentColor }}40;"
     data-id="{{ $d->id }}" 
     data-name="{{ $d->name }}" 
     data-is-full="{{ $isFull ? '1' : '0' }}"
     onclick="selectPrimaryDivision('{{ $d->id }}', '{{ addslashes($d->name) }}', {{ $isFull ? 'true' : 'false' }})">
    <div class="division-card-header">
        <div class="division-card-icon" style="background: {{ $isFull ? '#fee2e2' : 'var(--div-accent-bg)' }}; color: {{ $isFull ? '#ef4444' : 'var(--div-accent)' }};">
            <i class="fas {{ $iconClass }}"></i>
        </div>
        <div class="division-card-radio"></div>
    </div>
    <h4 class="division-card-title">{{ $d->name }}</h4>
    <p class="division-card-desc">
        {{ $d->description ?: 'Pengembangan kompetensi dan inovasi teknologi terpadu.' }}
    </p>
    ...
```

In `resources/views/layouts/footer.blade.php`:
Replace static 4 links with:
```blade
@php
    $footerDivisions = \App\Models\Division::where('status', 'active')->take(6)->get();
@endphp
@foreach($footerDivisions as $fdiv)
    <li>
        <a href="{{ route('divisions.show', $fdiv->slug) }}">
            {{ $fdiv->name }}
        </a>
    </li>
@endforeach
```

In `app/Models/Project.php` & `app/Models/Event.php`:
Refactor `getThumbnailUrlAttribute` to safely fallback to general asset or dynamic division theme rather than match statement on static slugs.

- [x] **Step 4: Run test to verify it passes**

Run: `& 'E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe' artisan test --filter=DynamicFrontendDecouplingTest`
Expected: PASS

- [x] **Step 5: Commit**

```bash
git add resources/views/recruitment/index.blade.php resources/views/layouts/footer.blade.php app/Http/Controllers/RecruitmentController.php app/Models/Project.php app/Models/Event.php tests/Feature/DynamicFrontendDecouplingTest.php
git commit -m "feat(decouple): eliminate hardcoded division slugs and make recruitment wizard and footer fully dynamic"
```

---

### Task 5: End-to-End Regression Verification & Historical Integrity Audit

**Files:**
- Create: `tests/Feature/DynamicDivisionE2ETest.php`
- Modify: `tests/Feature/*` (ensure all existing tests pass with the new schema)

**Interfaces:**
- Consumes: Complete application suite
- Produces: 100% GREEN test suite confirming zero regressions across member conversion, digital KTA card generation, attendance passcode, and recruitment.

- [x] **Step 1: Write comprehensive end-to-end integration test**

Create `tests/Feature/DynamicDivisionE2ETest.php`:
```php
<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\Recruitment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicDivisionE2ETest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_dynamic_division_lifecycle(): void
    {
        Setting::set('recruitment_status', 'open');

        $superAdmin = User::create([
            'name' => 'Super Admin UKM',
            'email' => 'superadmin@ukmilkom.id',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
        ]);

        // 1. Super Admin creates 5th division
        $this->actingAs($superAdmin)->post(route('admin.divisions.store'), [
            'name' => 'Divisi Quantum Computing',
            'tagline' => 'Riset Komputasi Kuantum',
            'description' => 'Eksplorasi algoritma qubit dan kriptografi kuantum.',
            'focus_topics_raw' => "Qiskit\nQuantum Circuits\nPost-Quantum Cryptography",
            'color_accent' => '#a855f7',
            'icon_class' => 'fa-atom',
            'recruitment_quota' => 10,
            'is_recruitment_open' => '1',
            'adviser_name' => 'Prof. Quantum',
            'adviser_title' => 'Dosen Fisika Komputasi',
            'leader_name' => 'Ketua Quantum',
            'leader_nim' => '240101009',
            'leader_bio' => 'Bio ketua.',
        ])->assertRedirect(route('admin.divisions.index'));

        $division = Division::where('slug', 'divisi-quantum-computing')->firstOrFail();
        $this->assertEquals('#a855f7', $division->color_accent);
        $this->assertEquals('fa-atom', $division->icon_class);

        // 2. Super Admin assigns a user as Division Admin
        $candidate = User::create([
            'name' => 'Quantum Admin',
            'email' => 'quantum@student.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'member',
        ]);

        $this->actingAs($superAdmin)->post(route('admin.divisions.assignAdmin', $division), [
            'user_id' => $candidate->id,
        ])->assertSessionHas('success');

        $this->assertEquals('division_admin', $candidate->fresh()->role);
        $this->assertEquals($division->id, $candidate->fresh()->division_id);

        // 3. New Division appears in Recruitment Wizard
        $wizard = $this->get(route('recruitment.index'));
        $wizard->assertOk();
        $wizard->assertSee('Divisi Quantum Computing');
        $wizard->assertSee('fa-atom');

        // 4. Register a member into this division
        $member = Member::create([
            'user_id' => $candidate->id,
            'nim' => '240101009',
            'name' => 'Mahasiswa Quantum',
            'email' => 'mhs@quantum.ac.id',
            'division_id' => $division->id,
            'batch_year' => '2026',
            'status' => 'aktif',
            'join_date' => now()->toDateString(),
        ]);

        // 5. Super Admin soft-deletes (archives) the division
        $this->actingAs($superAdmin)->delete(route('admin.divisions.destroy', $division))
            ->assertRedirect(route('admin.divisions.index'));

        $this->assertSoftDeleted('divisions', ['id' => $division->id]);

        // Historical member data remains completely intact
        $this->assertDatabaseHas('members', ['id' => $member->id, 'division_id' => $division->id]);

        // 6. Super Admin restores the division
        $this->actingAs($superAdmin)->post(route('admin.divisions.restore', $division->id))
            ->assertRedirect(route('admin.divisions.index'));

        $this->assertNotSoftDeleted('divisions', ['id' => $division->id]);
        $this->assertEquals('active', $division->fresh()->status);
    }
}
```

- [x] **Step 2: Run all Feature Tests across the entire application**

Run: `& 'E:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe' artisan test`
Expected: ALL test suites pass (100% Green).

- [x] **Step 3: Commit all remaining changes and update documentation**

```bash
git add tests/ docs/
git commit -m "test(divisions): add full e2e integration test for dynamic division lifecycle and admin assignment"
```
