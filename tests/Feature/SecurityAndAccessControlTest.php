<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAndAccessControlTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $divisionAdmin1;
    private User $divisionAdmin2;
    private User $memberUser;
    private Division $divisiPemrograman;
    private Division $divisiMultimedia;

    protected function setUp(): void
    {
        parent::setUp();

        $this->divisiPemrograman = Division::create([
            'slug' => 'pemrograman',
            'name' => 'Divisi Pemrograman',
            'tagline' => 'Software & Coding',
            'description' => 'Riset software',
            'focus_topics' => ['PHP', 'Laravel'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#0284c7',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dr. Hendra, M.Kom.',
            'adviser_title' => 'Dosen Software Engineering',
            'leader_name' => 'Rayhan Fajar',
            'leader_nim' => '210103045',
            'leader_bio' => 'Bio',
            'is_recruitment_open' => true,
        ]);

        $this->divisiMultimedia = Division::create([
            'slug' => 'multimedia',
            'name' => 'Divisi Multimedia',
            'tagline' => 'Design & Visual',
            'description' => 'Desain visual',
            'focus_topics' => ['UI/UX', 'Blender'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#8b5cf6',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Rina, M.Ds.',
            'adviser_title' => 'Dosen Multimedia',
            'leader_name' => 'Aulia Rahma',
            'leader_nim' => '210103082',
            'leader_bio' => 'Bio',
            'is_recruitment_open' => true,
        ]);

        $this->superAdmin = User::create([
            'name' => 'Super Admin UKM',
            'email' => 'superadmin@ukm.test',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
        ]);

        $this->divisionAdmin1 = User::create([
            'name' => 'Admin Divisi Pemrograman',
            'email' => 'admin.prog@ukm.test',
            'password' => bcrypt('password123'),
            'role' => 'division_admin',
            'division_id' => $this->divisiPemrograman->id,
        ]);

        $this->divisionAdmin2 = User::create([
            'name' => 'Admin Divisi Multimedia',
            'email' => 'admin.multi@ukm.test',
            'password' => bcrypt('password123'),
            'role' => 'division_admin',
            'division_id' => $this->divisiMultimedia->id,
        ]);

        $this->memberUser = User::create([
            'name' => 'Mahasiswa Anggota',
            'email' => 'member@ukm.test',
            'password' => bcrypt('password123'),
            'role' => 'member',
            'division_id' => $this->divisiPemrograman->id,
            'nim' => '220103099',
        ]);
    }

    public function test_guest_is_redirected_to_login_when_accessing_admin()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_member_is_blocked_from_admin_dashboard_and_redirected_to_student_dashboard()
    {
        $response = $this->actingAs($this->memberUser)->get(route('admin.dashboard'));
        $response->assertRedirect(route('student.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_member_is_blocked_from_admin_users_management()
    {
        $response = $this->actingAs($this->memberUser)->get(route('admin.users.index'));
        $response->assertRedirect(route('student.dashboard'));
    }

    public function test_division_admin_cannot_access_user_and_role_management()
    {
        $response = $this->actingAs($this->divisionAdmin1)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    public function test_division_admin_cannot_access_officers_management()
    {
        $response = $this->actingAs($this->divisionAdmin1)->get(route('admin.officers.index'));
        $response->assertStatus(403);
    }

    public function test_super_admin_has_full_access_to_user_management()
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Pengguna', false);
    }

    public function test_super_admin_has_full_access_to_officers_management()
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.officers.index'));
        $response->assertStatus(200);
        $response->assertSee('Kelola Struktur Organisasi', false);
    }

    public function test_division_admin_cannot_edit_event_of_other_division()
    {
        $eventMultimedia = Event::create([
            'title' => 'Workshop 3D Animasi',
            'division_id' => $this->divisiMultimedia->id,
            'description' => 'Workshop Blender 3D',
            'event_date' => now()->addDays(5)->toDateString(),
            'time_start' => '09:00',
            'location_type' => 'offline',
            'location_venue' => 'Lab Multimedia',
            'status' => 'upcoming',
            'slug' => 'workshop-3d-animasi-test',
        ]);

        // Admin Pemrograman mencoba mengedit event Multimedia -> HARUS DITOLAK 403
        $response = $this->actingAs($this->divisionAdmin1)->get(route('admin.events.edit', $eventMultimedia));
        $response->assertStatus(403);

        // Admin Pemrograman mencoba menghapus event Multimedia -> HARUS DITOLAK 403
        $deleteResponse = $this->actingAs($this->divisionAdmin1)->delete(route('admin.events.destroy', $eventMultimedia));
        $deleteResponse->assertStatus(403);
    }

    public function test_recruitment_registration_requires_strong_password()
    {
        $response = $this->post(route('recruitment.store'), [
            'full_name' => 'Calon Mahasiswa Baru',
            'nim' => '230103777',
            'email' => 'calon@ukm.test',
            'password' => 'short', // Kurang dari 8 karakter
            'password_confirmation' => 'short',
            'phone_whatsapp' => '081234567890',
            'semester' => 1,
            'first_choice_division_id' => $this->divisiPemrograman->id,
            'reason_to_join' => 'Saya ingin belajar pemrograman website secara profesional dan mendalam bersama tim UKM.',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_division_admin_cannot_edit_or_delete_post_of_other_division()
    {
        $postMultimedia = \App\Models\Post::create([
            'division_id' => $this->divisiMultimedia->id,
            'author_id' => $this->divisionAdmin2->id,
            'title' => 'Tutorial Blender 3D Shading',
            'slug' => 'tutorial-blender-3d-shading',
            'excerpt' => 'Panduan teknik shading dasar pada Blender.',
            'content' => 'Langkah-langkah membuat node shader.',
            'category' => 'tutorial',
            'status' => 'published',
        ]);

        // Admin Pemrograman mencoba mengedit artikel Multimedia -> HARUS 403
        $editResponse = $this->actingAs($this->divisionAdmin1)->get(route('admin.posts.edit', $postMultimedia));
        $editResponse->assertStatus(403);

        $updateResponse = $this->actingAs($this->divisionAdmin1)->put(route('admin.posts.update', $postMultimedia), [
            'title' => 'Hacked Title',
            'excerpt' => 'Hacked excerpt',
            'content' => 'Hacked content',
            'category' => 'tutorial',
            'status' => 'published',
        ]);
        $updateResponse->assertStatus(403);

        $deleteResponse = $this->actingAs($this->divisionAdmin1)->delete(route('admin.posts.destroy', $postMultimedia));
        $deleteResponse->assertStatus(403);
    }

    public function test_division_admin_cannot_manipulate_attendance_session_of_other_division()
    {
        $sessionMultimedia = \App\Models\AttendanceSession::create([
            'title' => 'Workshop UI/UX Figma',
            'division_id' => $this->divisiMultimedia->id,
            'session_date' => now()->toDateString(),
            'time_start' => '09:00',
            'session_type' => 'workshop_teknis',
            'location' => 'Lab Komputer 2',
            'topic_material' => 'Figma Auto Layout',
            'instructor_name' => 'Aulia Rahma',
            'status' => 'open',
            'passcode' => 'FIGMA1',
            'created_by' => $this->divisionAdmin2->id,
        ]);

        // Admin Pemrograman mencoba melihat BAP dan mengutak-atik status sesi Multimedia -> HARUS 403
        $viewResponse = $this->actingAs($this->divisionAdmin1)->get(route('admin.attendance.show', $sessionMultimedia));
        $viewResponse->assertStatus(403);

        $toggleResponse = $this->actingAs($this->divisionAdmin1)->post(route('admin.attendance.toggleStatus', $sessionMultimedia));
        $toggleResponse->assertStatus(403);

        $deleteResponse = $this->actingAs($this->divisionAdmin1)->delete(route('admin.attendance.destroy', $sessionMultimedia));
        $deleteResponse->assertStatus(403);
    }

    public function test_division_admin_cannot_manipulate_member_of_other_division()
    {
        $memberMultimedia = \App\Models\Member::create([
            'nim' => '230103555',
            'name' => 'Budi Santoso',
            'email' => 'budi@ukm.test',
            'division_id' => $this->divisiMultimedia->id,
            'batch_year' => '2023',
            'status' => 'aktif',
            'join_date' => now()->toDateString(),
        ]);

        // Admin Pemrograman mencoba melihat, mengedit, atau menghapus member divisi Multimedia -> HARUS 403
        $showResponse = $this->actingAs($this->divisionAdmin1)->get(route('admin.members.show', $memberMultimedia));
        $showResponse->assertStatus(403);

        $editResponse = $this->actingAs($this->divisionAdmin1)->get(route('admin.members.edit', $memberMultimedia));
        $editResponse->assertStatus(403);

        $deleteResponse = $this->actingAs($this->divisionAdmin1)->delete(route('admin.members.destroy', $memberMultimedia));
        $deleteResponse->assertStatus(403);
    }

    public function test_student_cannot_edit_or_delete_other_student_project()
    {
        $otherStudent = User::create([
            'name' => 'Mahasiswa Lain',
            'email' => 'other@ukm.test',
            'password' => bcrypt('password123'),
            'role' => 'member',
            'division_id' => $this->divisiPemrograman->id,
            'nim' => '220103001',
        ]);

        $project = \App\Models\Project::create([
            'title' => 'Aplikasi Kasir Mobile',
            'slug' => 'aplikasi-kasir-mobile',
            'division_id' => $this->divisiPemrograman->id,
            'user_id' => $otherStudent->id,
            'description' => 'Aplikasi kasir berbasis Flutter.',
            'author_names' => 'Mahasiswa Lain',
            'tech_stack' => ['Flutter', 'Dart', 'Firebase'],
            'submission_status' => 'published',
        ]);

        // MemberUser mencoba mengedit project milik OtherStudent -> HARUS 403
        $editResponse = $this->actingAs($this->memberUser)->get(route('student.projects.edit', $project));
        $editResponse->assertStatus(403);

        $updateResponse = $this->actingAs($this->memberUser)->put(route('student.projects.update', $project), [
            'title' => 'Hijacked App',
            'description' => 'Hijacked description',
            'author_names' => 'Attacker',
        ]);
        $updateResponse->assertStatus(403);

        $deleteResponse = $this->actingAs($this->memberUser)->delete(route('student.projects.destroy', $project));
        $deleteResponse->assertStatus(403);
    }
}
