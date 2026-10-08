<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentProjectShowcaseAndModerationTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private User $admin;
    private Division $division;
    private Member $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->division = Division::create([
            'name' => 'Divisi Pemrograman',
            'slug' => 'pemrograman',
            'tagline' => 'Pusat Rekayasa Perangkat Lunak',
            'description' => 'Divisi Rekayasa Perangkat Lunak',
            'focus_topics' => ['Laravel', 'Web Development'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#009688',
            'vision' => 'Visi Pemrograman',
            'mission' => 'Misi Pemrograman',
            'adviser_name' => 'Dr. Dosen',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua Pemrograman',
            'leader_nim' => '210103001',
            'leader_bio' => 'Bio Ketua',
        ]);

        $this->student = User::factory()->create([
            'role' => 'student',
            'nim' => '220101999',
            'name' => 'Budi Santoso',
        ]);

        $this->member = Member::create([
            'user_id' => $this->student->id,
            'division_id' => $this->division->id,
            'nim' => '220101999',
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'status' => 'aktif',
        ]);

        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'name' => 'Super Administrator',
        ]);
    }

    public function test_student_can_view_own_projects_list(): void
    {
        $response = $this->actingAs($this->student)->get(route('student.projects.index'));
        $response->assertStatus(200);
        $response->assertSeeText('Karya & Proyek Inovasi Saya', false);
        $response->assertSee('Ajukan Karya Baru');
    }

    public function test_student_can_submit_project_and_status_is_pending_review(): void
    {
        $response = $this->actingAs($this->student)->post(route('student.projects.store'), [
            'title' => 'Aplikasi Smart Campus',
            'division_id' => $this->division->id,
            'description' => 'Aplikasi inovatif berbasis web dan mobile untuk civitas akademika.',
            'author_names' => 'Budi Santoso, Siti Rahma',
            'tech_stack_input' => 'Laravel, Vue.js, TailwindCSS',
            'demo_url' => 'https://smartcampus.example.com',
            'repo_url' => 'https://github.com/budisantoso/smartcampus',
        ]);

        $response->assertRedirect(route('student.projects.index'));

        $this->assertDatabaseHas('projects', [
            'title' => 'Aplikasi Smart Campus',
            'user_id' => $this->student->id,
            'submission_status' => 'pending_review',
        ]);

        // Pastikan belum muncul di halaman publik /proyek
        $publicResponse = $this->get(route('projects.index'));
        $publicResponse->assertStatus(200);
        $publicResponse->assertDontSee('Aplikasi Smart Campus');
    }

    public function test_admin_can_approve_and_publish_student_project(): void
    {
        $project = Project::create([
            'title' => 'Robot Pemadam Api Cerdas',
            'slug' => 'robot-pemadam-api-cerdas',
            'division_id' => $this->division->id,
            'user_id' => $this->student->id,
            'description' => 'Robot pemadam api otonom dengan sensor deteksi inframerah.',
            'author_names' => 'Budi Santoso',
            'tech_stack' => ['Arduino', 'C++'],
            'submission_status' => 'pending_review',
        ]);

        // Verifikasi admin view melihat proyek dengan badge review
        $adminIndex = $this->actingAs($this->admin)->get(route('admin.projects.index'));
        $adminIndex->assertStatus(200);
        $adminIndex->assertSee('Robot Pemadam Api Cerdas');
        $adminIndex->assertSee('Review');

        // Admin approve proyek
        $moderateResponse = $this->actingAs($this->admin)->post(route('admin.projects.moderate', $project->id), [
            'status' => 'published',
        ]);

        $moderateResponse->assertRedirect();
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'submission_status' => 'published',
        ]);

        // Sekarang harus muncul di landing page publik /proyek
        $publicResponse = $this->get(route('projects.index'));
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('Robot Pemadam Api Cerdas');
    }

    public function test_student_and_division_admin_are_strictly_locked_to_their_own_division(): void
    {
        // Divisi lain
        $otherDivision = Division::create([
            'name' => 'Divisi Multimedia',
            'slug' => 'multimedia',
            'tagline' => 'Visual Arts',
            'description' => 'Desain Grafis dan Video',
            'focus_topics' => ['Figma', 'Blender'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#8b5cf6',
            'vision' => 'Visi MM',
            'mission' => 'Misi MM',
            'adviser_name' => 'Dosen MM',
            'adviser_title' => 'M.Ds',
            'leader_name' => 'Ketua MM',
            'leader_nim' => '210103002',
            'leader_bio' => 'Bio MM',
        ]);

        // 1. Mahasiswa membuka form pengajuan karya: kategori divisi terkunci ke divisi miliknya
        $createResponse = $this->actingAs($this->student)->get(route('student.projects.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSeeText($this->division->name);
        $createResponse->assertSee('Terkunci Resmi');

        // Jika mahasiswa mencoba mengirim division_id divisi lain, backend tetap memaksanya ke divisi miliknya
        $storeResponse = $this->actingAs($this->student)->post(route('student.projects.store'), [
            'title' => 'Proyek Web Inovatif',
            'division_id' => $otherDivision->id, // Mencoba manipulasi divisi lain
            'description' => 'Deskripsi proyek mahasiswa',
            'author_names' => 'Budi Santoso',
        ]);

        $storeResponse->assertRedirect(route('student.projects.index'));
        $this->assertDatabaseHas('projects', [
            'title' => 'Proyek Web Inovatif',
            'division_id' => $this->division->id, // Harus tetap divisi miliknya sendiri!
            'user_id' => $this->student->id,
        ]);

        // 2. Admin divisi pemrograman juga terkunci ke divisinya sendiri
        $divAdmin = User::factory()->create([
            'role' => 'division_admin',
            'division_id' => $this->division->id,
            'name' => 'Admin Pemrograman',
        ]);

        $adminCreateResponse = $this->actingAs($divAdmin)->get(route('admin.projects.create'));
        $adminCreateResponse->assertStatus(200);
        $adminCreateResponse->assertSee('Terkunci Divisi Anda');
        $adminCreateResponse->assertSeeText($this->division->name);
    }

    public function test_admin_can_reject_student_project_with_review_notes_and_student_sees_feedback(): void
    {
        $project = Project::create([
            'title' => 'Sistem Rekomendasi AI',
            'slug' => 'sistem-rekomendasi-ai',
            'division_id' => $this->division->id,
            'user_id' => $this->student->id,
            'description' => 'Sistem rekomendasi berbasis machine learning.',
            'author_names' => 'Budi Santoso',
            'tech_stack' => ['Python', 'FastAPI'],
            'submission_status' => 'pending_review',
        ]);

        $feedback = 'Mohon lengkapi URL repositori GitHub dan tautan live demo yang valid.';

        $rejectResponse = $this->actingAs($this->admin)->post(route('admin.projects.moderate', $project->id), [
            'status' => 'rejected',
            'admin_notes' => $feedback,
        ]);

        $rejectResponse->assertRedirect();
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'submission_status' => 'rejected',
            'admin_notes' => $feedback,
        ]);

        // Mahasiswa melihat catatan review tersebut di halaman proyeknya
        $studentResponse = $this->actingAs($this->student)->get(route('student.projects.index'));
        $studentResponse->assertStatus(200);
        $studentResponse->assertSee('Catatan Review Pengurus:');
        $studentResponse->assertSee($feedback);
    }
}
