# UKM Portal & CMS Light & Glass Ecosystem Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mengubah Web Portal & CMS UKM Ilmu Komputer dari antarmuka kaku/generic ("AI slop") menjadi web kampus modern bertaraf internasional bergaya "Refined Minimalist Light & Glass" (Stripe/Apple inspired) serta mengimplementasikan 6 pilar fitur terpadu: Showcase Proyek Mahasiswa, Agenda/Event Hub, Bagan Struktur Pengurus, Open Recruitment Berjenjang, Galeri Dokumentasi, dan Verifikasi E-Sertifikat/Anggota.

**Architecture:** Laravel MVC terintegrasi dengan Eloquent ORM, Blade Views modular, CSS Design System kustom (Vanilla CSS ultra-ringan dengan CSS Variables, Glassmorphism, Micro-animations, responsive layout), dan multi-role panel admin (Super Admin & Division Admin).

**Tech Stack:** PHP 8.1+ / Laravel 10+, MySQL/MariaDB Laragon, Vanilla CSS (Refined Light & Glass), Vanilla JavaScript, PHPUnit.

**Spec:** `docs/superpowers/specs/2026-10-01-ukm-portal-light-glass-ecosystem-design.md`

## Global Constraints
- Target Environment: Laragon PHP 8.1+ & MySQL (`127.0.0.1`, user: `root`, pass: empty/default).
- UI Aesthetic: Refined Minimalist Light & Glass (off-white `#fbfcfd`, glass card `rgba(255,255,255,0.88)` with `backdrop-filter: blur(16px)`, clean borders `rgba(226,232,240,0.8)`, fine typography via Plus Jakarta Sans & JetBrains Mono).
- No Heavy JS Bundler Dependencies: Semua fungsionalitas UI, tab filtering, modal lightbox, dan menu mobile berjalan di atas Vanilla JS murni dan CSS performan tinggi.
- Security: CSRF protection pada semua mutasi form, upload validation (MIME, max size), XSS escaping via Blade `{{ }}`, Eloquent prepared statements.

---

### Task 1: Database Migrations, Models, & Realistic Seeders

**Files:**
- Create: `database/migrations/2026_10_01_000005_create_projects_table.php`
- Create: `database/migrations/2026_10_01_000006_create_events_table.php`
- Create: `database/migrations/2026_10_01_000007_create_officers_table.php`
- Create: `database/migrations/2026_10_01_000008_create_galleries_table.php`
- Create: `database/migrations/2026_10_01_000009_create_certificates_table.php`
- Create: `database/migrations/2026_10_01_000010_upgrade_recruitments_table.php`
- Create: `app/Models/Project.php`
- Create: `app/Models/Event.php`
- Create: `app/Models/Officer.php`
- Create: `app/Models/Gallery.php`
- Create: `app/Models/Certificate.php`
- Modify: `app/Models/Recruitment.php`
- Modify: `database/seeders/DatabaseSeeder.php`
- Test: `tests/Feature/EcosystemDatabaseTest.php`

**Interfaces:**
- Consumes: MySQL Connection via Laravel Database configuration
- Produces: Models `Project`, `Event`, `Officer`, `Gallery`, `Certificate` with cast properties (`tech_stack`, `social_links`) and relationships to `Division`.

- [ ] **Step 1: Write test for new models and migration schema**

Create `tests/Feature/EcosystemDatabaseTest.php`:
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use App\Models\Event;
use App\Models\Officer;
use App\Models\Gallery;
use App\Models\Certificate;
use App\Models\Division;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EcosystemDatabaseTest extends TestCase
{
    public function test_new_ecosystem_tables_and_models_work()
    {
        $division = Division::first();

        $project = Project::create([
            'division_id' => $division ? $division->id : null,
            'title' => 'Smart Campus IoT Gateway',
            'slug' => 'smart-campus-iot-gateway',
            'description' => 'Sistem monitoring energi kampus berbasis ESP32.',
            'author_names' => 'Dimas Bagus, Rizki Pratama',
            'tech_stack' => ['ESP32', 'MQTT', 'Laravel'],
            'demo_url' => 'https://iot.campus.ac.id',
            'repo_url' => 'https://github.com/ukm/iot-gateway',
            'is_featured' => true,
        ]);

        $this->assertDatabaseHas('projects', ['slug' => 'smart-campus-iot-gateway']);
        $this->assertEquals(['ESP32', 'MQTT', 'Laravel'], $project->fresh()->tech_stack);

        $event = Event::create([
            'title' => 'Workshop Ethical Hacking 101',
            'slug' => 'workshop-ethical-hacking-101',
            'description' => 'Mengenal teknik penetration testing dan CTF dasar.',
            'event_date' => now()->addDays(7)->format('Y-m-d'),
            'time_start' => '09:00:00',
            'location_type' => 'offline',
            'location_venue' => 'Lab Terpadu Komputer Lt. 3',
            'status' => 'upcoming',
        ]);
        $this->assertDatabaseHas('events', ['slug' => 'workshop-ethical-hacking-101']);

        $cert = Certificate::create([
            'certificate_code' => 'CERT-TEST-2026',
            'recipient_name' => 'Ahmad Fauzi',
            'recipient_nim' => '210103099',
            'event_name' => 'Workshop Fullstack Web Modern',
            'role_as' => 'Peserta',
            'issue_date' => now()->format('Y-m-d'),
        ]);
        $this->assertDatabaseHas('certificates', ['certificate_code' => 'CERT-TEST-2026']);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=EcosystemDatabaseTest`
Expected: FAIL (Migration and tables do not exist yet).

- [ ] **Step 3: Implement database migrations**

Create migration files for `projects`, `events`, `officers`, `galleries`, `certificates`, and column additions to `recruitments`.

- [ ] **Step 4: Implement Eloquent Models**

Create `app/Models/Project.php`, `Event.php`, `Officer.php`, `Gallery.php`, `Certificate.php`, and update `Recruitment.php`.

- [ ] **Step 5: Enhance `DatabaseSeeder.php` with rich sample data**

Add realistic data: 4 featured projects (1 for each division), 3 upcoming/past events, complete organizational structure (Pembina, Ketum, Sekum, Bendum, and 4 division coordinators), 6 gallery items, and sample certificates.

- [ ] **Step 6: Run migration & seed, run test to verify PASS**

Run: `php artisan migrate:fresh --seed`
Run: `php artisan test --filter=EcosystemDatabaseTest`
Expected: PASS.

- [ ] **Step 7: Commit**

```bash
git add app/Models database/migrations database/seeders tests/Feature/EcosystemDatabaseTest.php
git commit -m "feat: add ecosystem database schema, models, rich seeders, and tests"
```

---

### Task 2: Core Design System Overhaul ("Refined Minimalist Light & Glass")

**Files:**
- Modify: `public/css/portal.css`
- Modify: `public/js/portal.js`
- Modify: `resources/views/layouts/app.blade.php`
- Modify: `resources/views/layouts/navbar.blade.php`
- Modify: `resources/views/layouts/footer.blade.php`
- Test: `tests/Feature/LayoutDesignTest.php`

**Interfaces:**
- Consumes: CSS root variables, HTML structure
- Produces: Modern glassmorphism UI classes (`.glass-card`, `.glass-navbar`, `.hero-ambient`, `.bento-grid`, `.pill-badge`, `.btn-glass`), responsive navigation drawer, and smooth micro-interactions.

- [ ] **Step 1: Write test for layout rendering and assets**

Create `tests/Feature/LayoutDesignTest.php`:
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class LayoutDesignTest extends TestCase
{
    public function test_homepage_loads_refined_navbar_and_links()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('UKM ILMU KOMPUTER');
        $response->assertSee('Karya');
        $response->assertSee('Agenda');
        $response->assertSee('Pengurus');
        $response->assertSee('Galeri');
        $response->assertSee('Verifikasi');
    }
}
```

- [ ] **Step 2: Run test to check current failure**

Run: `php artisan test --filter=LayoutDesignTest`
Expected: FAIL (Navigation does not have all new links yet).

- [ ] **Step 3: Redesign `public/css/portal.css` with Refined Light & Glass Aesthetic**

Implement:
- Smooth ambient radial gradient mesh background for hero sections.
- Premium glass card classes: `backdrop-filter: blur(16px)`, `background: rgba(255, 255, 255, 0.85)`, crisp `1px solid rgba(226, 232, 240, 0.8)` borders.
- Bento-grid responsive layout utilities.
- Tactile buttons with subtle inset borders and hover elevation.
- Division badges with custom glow colors.
- Interactive tabs with pill indicator transitions.

- [ ] **Step 4: Update `navbar.blade.php`, `footer.blade.php`, and `portal.js`**

Implement:
- Frosted glass sticky navbar with backdrop blur.
- Links to: Divisi, Karya Mahasiswa (`/proyek`), Agenda (`/events`), Pengurus (`/pengurus`), Galeri (`/galeri`), Verifikasi (`/verifikasi`), and Oprec CTA.
- Mobile slide-down drawer with smooth toggle.
- Modern minimalist footer with university branding, division quick-links, and contact details.

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test --filter=LayoutDesignTest`
Expected: PASS.

- [ ] **Step 6: Commit**

```bash
git add public/css/portal.css public/js/portal.js resources/views/layouts/ tests/Feature/LayoutDesignTest.php
git commit -m "feat: overhaul portal design system to refined minimalist light and glass"
```

---

### Task 3: Homepage Redesign (Hero Ambient, Bento Divisi, Featured Showcase & Agenda)

**Files:**
- Modify: `app/Http/Controllers/HomeController.php`
- Modify: `resources/views/home/index.blade.php`
- Test: `tests/Feature/HomepageExperienceTest.php`

**Interfaces:**
- Consumes: Models `Division`, `Project`, `Event`, `Post`, `Setting`
- Produces: High-impact homepage with Bento grid of 4 divisions, interactive project showcase tabs, upcoming event cards, and live oprec status banner.

- [ ] **Step 1: Write test for homepage data feeding**

Create `tests/Feature/HomepageExperienceTest.php`:
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomepageExperienceTest extends TestCase
{
    public function test_homepage_displays_featured_projects_and_upcoming_events()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewHas('featuredProjects');
        $response->assertViewHas('upcomingEvents');
        $response->assertViewHas('divisions');
        $response->assertSee('Divisi Spesialisasi');
        $response->assertSee('Karya & Inovasi Mahasiswa');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=HomepageExperienceTest`
Expected: FAIL (Controller does not pass `featuredProjects` and `upcomingEvents`).

- [ ] **Step 3: Update `HomeController.php`**

Query `featuredProjects` (`Project::with('division')->where('is_featured', true)->take(6)->get()`), `upcomingEvents` (`Event::with('division')->where('status', 'upcoming')->orderBy('event_date')->take(3)->get()`), and recent posts.

- [ ] **Step 4: Overhaul `resources/views/home/index.blade.php`**

Replace generic templates with:
- **Hero Ambient Section**: Apple/Stripe-level elegance, radial lighting, dynamic oprec pill badge with pulse animation, headline "Pusat Riset, Inovasi & Rekayasa Teknologi Mahasiswa", primary and secondary glass CTA buttons, metric strip with refined typography.
- **Bento Divisi Spesialisasi Grid**: 4 unique division cards with accent badges, tech keywords, and leader previews.
- **Showcase Karya Mahasiswa**: Grid of top student projects with tech badges (Laravel, Flutter, ESP32, Figma), live demo & repo links.
- **Upcoming Events & Workshop Hub**: Clean date badge cards with venue information and registration button.
- **Latest Insights**: Editorial cards with author avatars and reading time.
- **Call-to-Action Oprec**: Frosted glass card with deadline timer and registration button.

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test --filter=HomepageExperienceTest`
Expected: PASS.

- [ ] **Step 6: Commit**

```bash
git add app/Http/Controllers/HomeController.php resources/views/home/index.blade.php tests/Feature/HomepageExperienceTest.php
git commit -m "feat: complete homepage overhaul with bento divisions and showcase widgets"
```

---

### Task 4: Public Subsystems (Projects Showcase, Events Hub, Officers Hierarchy, Gallery)

**Files:**
- Create: `app/Http/Controllers/ProjectController.php`
- Create: `app/Http/Controllers/EventController.php`
- Create: `app/Http/Controllers/OfficerController.php`
- Create: `app/Http/Controllers/GalleryController.php`
- Create: `resources/views/projects/index.blade.php`
- Create: `resources/views/events/index.blade.php`
- Create: `resources/views/officers/index.blade.php`
- Create: `resources/views/galleries/index.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/PublicSubsystemsTest.php`

**Interfaces:**
- Consumes: HTTP GET routes `/proyek`, `/events`, `/pengurus`, `/galeri`
- Produces: Dedicated public pages with division filters, search capability, and clean glass layout.

- [ ] **Step 1: Write test for public subsystem routes**

Create `tests/Feature/PublicSubsystemsTest.php`:
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicSubsystemsTest extends TestCase
{
    public function test_projects_page_loads_with_filters()
    {
        $response = $this->get(route('projects.index'));
        $response->assertStatus(200);
        $response->assertSee('Showcase Portofolio & Riset');
    }

    public function test_events_page_loads()
    {
        $response = $this->get(route('events.index'));
        $response->assertStatus(200);
        $response->assertSee('Agenda & Workshop');
    }

    public function test_officers_page_loads()
    {
        $response = $this->get(route('officers.index'));
        $response->assertStatus(200);
        $response->assertSee('Struktur Kepengurusan');
    }

    public function test_gallery_page_loads()
    {
        $response = $this->get(route('galleries.index'));
        $response->assertStatus(200);
        $response->assertSee('Galeri & Dokumentasi');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=PublicSubsystemsTest`
Expected: FAIL (Routes and controllers do not exist yet).

- [ ] **Step 3: Implement Controllers and register Routes**

Create `ProjectController`, `EventController`, `OfficerController`, and `GalleryController`. Register routes in `routes/web.php`.

- [ ] **Step 4: Implement Blade Views**

- `resources/views/projects/index.blade.php`: Division filter pills, search input, responsive cards showing author names, tech stack pills, demo/repo buttons.
- `resources/views/events/index.blade.php`: Segmented tabs for "Upcoming" and "Past Events", card with location chip (online/offline), date badge, and registration link.
- `resources/views/officers/index.blade.php`: Organizational hierarchy visualization:
  - Top: Dewan Pembina
  - Middle: Badan Pengurus Harian (Ketum, Sekum, Bendum)
  - Bottom: 4 Divisi Koordinator & Staff dengan avatar, badge jabatan, dan link sosial (LinkedIn, GitHub).
- `resources/views/galleries/index.blade.php`: Masonry grid with category filter chips and lightbox image preview.

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test --filter=PublicSubsystemsTest`
Expected: PASS.

- [ ] **Step 6: Commit**

```bash
git add app/Http/Controllers/ resources/views/ routes/web.php tests/Feature/PublicSubsystemsTest.php
git commit -m "feat: implement public showcase, events hub, officers hierarchy, and gallery pages"
```

---

### Task 5: E-Certificate Verification System & Member Lookup

**Files:**
- Create: `app/Http/Controllers/CertificateController.php`
- Create: `resources/views/certificates/verify.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/CertificateVerificationTest.php`

**Interfaces:**
- Consumes: Route `/verifikasi` (GET query `code` or form POST/GET)
- Produces: Public certificate validation card with official verification badge, recipient info, role, date, and QR-code visual simulation.

- [ ] **Step 1: Write test for certificate verification**

Create `tests/Feature/CertificateVerificationTest.php`:
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Certificate;

class CertificateVerificationTest extends TestCase
{
    public function test_can_view_verification_page()
    {
        $response = $this->get(route('certificates.verify'));
        $response->assertStatus(200);
        $response->assertSee('Verifikasi E-Sertifikat & Anggota');
    }

    public function test_valid_certificate_code_returns_verified_status()
    {
        $cert = Certificate::first();
        $this->assertNotNull($cert);

        $response = $this->get(route('certificates.verify', ['code' => $cert->certificate_code]));
        $response->assertStatus(200);
        $response->assertSee($cert->recipient_name);
        $response->assertSee('TERVERIFIKASI ASLI');
    }

    public function test_invalid_certificate_shows_not_found_message()
    {
        $response = $this->get(route('certificates.verify', ['code' => 'INVALID-CODE-999']));
        $response->assertStatus(200);
        $response->assertSee('Tidak Ditemukan');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=CertificateVerificationTest`
Expected: FAIL (Controller and view do not exist).

- [ ] **Step 3: Implement `CertificateController.php` and View**

Implement search query handling by `code` or `recipient_nim`, and render a certificate card with green authentic checkmark badge, certificate serial number, issuer signature stamp, and download/share button.

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter=CertificateVerificationTest`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add app/Http/Controllers/CertificateController.php resources/views/certificates/ routes/web.php tests/Feature/CertificateVerificationTest.php
git commit -m "feat: implement public e-certificate verification system"
```

---

### Task 6: Multi-Stage Open Recruitment Flow & Applicant Portal

**Files:**
- Modify: `app/Http/Controllers/RecruitmentController.php`
- Modify: `resources/views/recruitment/index.blade.php`
- Modify: `resources/views/recruitment/status.blade.php`
- Test: `tests/Feature/RecruitmentWorkflowTest.php`

**Interfaces:**
- Consumes: Multipart form with KTM upload, validation rules, registration tracking code
- Produces: Multi-step registration wizard, file upload to `storage/app/public/recruitment`, visual stage pipeline in status tracker (`Administrasi` -> `Wawancara` -> `Diterima`/`Ditolak`).

- [ ] **Step 1: Write test for enhanced recruitment flow**

Create `tests/Feature/RecruitmentWorkflowTest.php`:
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Division;
use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RecruitmentWorkflowTest extends TestCase
{
    public function test_can_submit_registration_with_ktm_upload()
    {
        Storage::fake('public');
        Setting::where('key_name', 'recruitment_status')->update(['value' => 'open']);

        $division = Division::first();

        $response = $this->post(route('recruitment.store'), [
            'full_name' => 'Bintang Ramadhan',
            'nim' => '220104012',
            'email' => 'bintang@kampus.ac.id',
            'whatsapp' => '081298765432',
            'semester' => 3,
            'division_id' => $division->id,
            'reason_joining' => 'Ingin memperdalam keahlian rekayasa sistem dan riset kolaboratif.',
            'portfolio_url' => 'https://github.com/bintangramadhan',
            'file_ktm' => UploadedFile::fake()->image('ktm.jpg'),
        ]);

        $response->assertRedirect(route('recruitment.status'));
        $this->assertDatabaseHas('recruitments', [
            'nim' => '220104012',
            'selection_stage' => 'administrasi',
        ]);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=RecruitmentWorkflowTest`
Expected: FAIL (No file handling or `selection_stage` handled yet).

- [ ] **Step 3: Update `RecruitmentController.php` and Views**

- Update validation to accept `file_ktm` (mimes: jpg, jpeg, png, pdf, max: 2048).
- Store file safely in `public/uploads/recruitment`.
- In `recruitment/index.blade.php`: Clean multi-step styled form with division radio cards, file upload dropzone, and clear requirements.
- In `recruitment/status.blade.php`: Visual horizontal stage stepper (`1. Berkas Administrasi` -> `2. Wawancara Divisi` -> `3. Pengumuman Kelulusan`) with active status highlights and interview schedules.

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter=RecruitmentWorkflowTest`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add app/Http/Controllers/RecruitmentController.php resources/views/recruitment/ tests/Feature/RecruitmentWorkflowTest.php
git commit -m "feat: enhance open recruitment workflow with ktm upload and visual stage stepper"
```

---

### Task 7: Admin CMS Unified Redesign & Full Management Modules

**Files:**
- Modify: `resources/views/admin/layouts/app.blade.php`
- Modify: `resources/views/admin/dashboard.blade.php`
- Create: `app/Http/Controllers/Admin/ProjectAdminController.php`
- Create: `app/Http/Controllers/Admin/EventAdminController.php`
- Create: `app/Http/Controllers/Admin/OfficerAdminController.php`
- Create: `app/Http/Controllers/Admin/CertificateAdminController.php`
- Create: `app/Http/Controllers/Admin/GalleryAdminController.php`
- Create: `resources/views/admin/projects/index.blade.php`
- Create: `resources/views/admin/projects/form.blade.php`
- Create: `resources/views/admin/events/index.blade.php`
- Create: `resources/views/admin/events/form.blade.php`
- Create: `resources/views/admin/officers/index.blade.php`
- Create: `resources/views/admin/certificates/index.blade.php`
- Create: `resources/views/admin/galleries/index.blade.php`
- Modify: `resources/views/admin/recruitment/show.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/AdminEcosystemTest.php`

**Interfaces:**
- Consumes: Authenticated Super Admin & Division Admin sessions
- Produces: Cohesive, light-themed admin portal with full CRUD controls and recruitment stage progression actions.

- [ ] **Step 1: Write test for admin ecosystem routes**

Create `tests/Feature/AdminEcosystemTest.php`:
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class AdminEcosystemTest extends TestCase
{
    public function test_super_admin_can_access_all_management_modules()
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        $this->actingAs($superAdmin);

        $this->get(route('admin.dashboard'))->assertStatus(200);
        $this->get(route('admin.projects.index'))->assertStatus(200);
        $this->get(route('admin.events.index'))->assertStatus(200);
        $this->get(route('admin.officers.index'))->assertStatus(200);
        $this->get(route('admin.certificates.index'))->assertStatus(200);
        $this->get(route('admin.galleries.index'))->assertStatus(200);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=AdminEcosystemTest`
Expected: FAIL (Admin controllers and views do not exist).

- [ ] **Step 3: Update `admin/layouts/app.blade.php` and `dashboard.blade.php`**

- Apply Refined Light & Glass styling to admin sidebar, topbar, and cards.
- Add navigation links for: Proyek Mahasiswa, Agenda/Event, Struktur Pengurus, Galeri, E-Sertifikat, Pendaftaran, dan Artikel.
- Redesign analytics metrics on dashboard with soft elevation and real-time counts.

- [ ] **Step 4: Implement Admin Controllers and Views**

- `ProjectAdminController`: Create/edit project with tech stack tags, division association, and thumbnail upload.
- `EventAdminController`: Manage dates, location, participant capacity, and status.
- `OfficerAdminController`: Manage hierarchy, sorting, positions, and photos.
- `CertificateAdminController`: Issue certificates, auto-generate unique codes (`CERT-ILKOM-YYYY-XXXX`), and export/list.
- `GalleryAdminController`: Upload activity photos with category tagging.
- Update `admin/recruitment/show.blade.php`: Action buttons to advance candidates (`Administrasi` -> `Wawancara` -> `Diterima`), schedule interview date/venue, and view uploaded KTM.

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test --filter=AdminEcosystemTest`
Expected: PASS.

- [ ] **Step 6: Commit**

```bash
git add app/Http/Controllers/Admin/ resources/views/admin/ routes/web.php tests/Feature/AdminEcosystemTest.php
git commit -m "feat: implement unified admin cms modules for projects, events, officers, certificates, and galleries"
```

---

### Task 8: End-to-End Integration, Visual Polish & Verification

**Files:**
- Test: `tests/Feature/EndToEndPortalTest.php`
- Modify: `resources/views/divisions/show.blade.php`
- Modify: `resources/views/posts/index.blade.php`
- Modify: `resources/views/posts/show.blade.php`

**Interfaces:**
- Consumes: Whole application stack
- Produces: Verified complete system without broken links, flawless responsive layout, crisp UI on mobile and desktop.

- [ ] **Step 1: Write comprehensive end-to-end test**

Create `tests/Feature/EndToEndPortalTest.php` testing full user journey:
- Visitor explores homepage and filters projects.
- Visitor views division detail and reads post.
- Visitor submits recruitment form and verifies tracking code.
- Visitor checks certificate verification.
- Admin logs in, updates recruitment stage, adds a new event, and publishes a project.

- [ ] **Step 2: Run all test suites**

Run: `php artisan test`
Expected: All tests PASS with zero regressions.

- [ ] **Step 3: Visual inspection in browser**

Verify:
- Clean typography and spacing.
- Working mobile menu drawer.
- Glassmorphism effects render smoothly without visual glitches.
- No console errors or missing asset 404s.

- [ ] **Step 4: Final Commit**

```bash
git add .
git commit -m "feat: complete UI/UX overhaul and full feature ecosystem for UKM portal"
```
