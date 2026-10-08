<?php

namespace Tests\Feature;

use App\Models\AttendanceLog;
use App\Models\AttendanceSession;
use App\Models\Division;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentMeetingMaterialReaderTest extends TestCase
{
    use RefreshDatabase;

    private User $studentUser;
    private Division $division;
    private Member $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->division = Division::create([
            'slug' => 'pemrograman',
            'name' => 'Divisi Pemrograman',
            'tagline' => 'Coding is fun',
            'description' => 'Divisi pemrograman',
            'focus_topics' => ['Clean Code', 'REST API Architecture'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#0284c7',
            'vision' => 'Visi Pemrograman',
            'mission' => 'Misi Pemrograman',
            'adviser_name' => 'Dosen Pembina',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua Pemrograman',
            'leader_nim' => '220101001',
            'leader_bio' => 'Bio',
            'is_recruitment_open' => true,
        ]);

        $this->studentUser = User::create([
            'name' => 'Bintang Mahasiswa',
            'email' => 'bintang@student.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'division_id' => $this->division->id,
            'nim' => '2301010099',
        ]);

        $this->member = Member::create([
            'user_id' => $this->studentUser->id,
            'division_id' => $this->division->id,
            'nim' => '2301010099',
            'name' => 'Bintang Mahasiswa',
            'email' => 'bintang@student.ac.id',
            'status' => 'aktif',
        ]);
    }

    public function test_student_can_read_full_meeting_topic_and_learning_outcomes_on_presensi_page()
    {
        $session = AttendanceSession::create([
            'division_id' => $this->division->id,
            'title' => 'Pertemuan Mingguan #4: Clean Architecture',
            'day_name' => 'Selasa',
            'session_date' => now()->format('Y-m-d'),
            'time_start' => '16:00:00',
            'time_end' => '18:00:00',
            'session_type' => 'workshop_teknis',
            'location' => 'Lab Komputer 3 Gedung Fasilkom',
            'topic_material' => 'Standarisasi Repository Pattern & RESTful API Architecture',
            'learning_outcomes' => 'Mahasiswa memahami pemisahan controller, service layer, repository pattern, dan implementasi automated feature tests.',
            'instructor_name' => 'Muhammad Rayhan Fajar',
            'notes' => 'Diikuti oleh seluruh anggota aktif Divisi Pemrograman.',
            'created_by' => $this->studentUser->id,
            'status' => 'open',
            'passcode' => 'KOMP26',
            'allow_self_checkin' => true,
        ]);

        AttendanceLog::create([
            'session_id' => $session->id,
            'member_id' => $this->member->id,
            'status' => 'hadir',
            'notes' => 'Hadir tepat waktu',
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('student.presensi'));

        $response->assertStatus(200);
        $response->assertSee('Standarisasi Repository Pattern & RESTful API Architecture');
        $response->assertSee('Mahasiswa memahami pemisahan controller, service layer, repository pattern, dan implementasi automated feature tests.');
        $response->assertSee('Baca Rangkuman Materi Lengkap');
        $response->assertSee('materiModal');
        $response->assertSee('Baca Materi');
    }

    public function test_student_can_read_meeting_archives_on_silabus_page()
    {
        AttendanceSession::create([
            'division_id' => $this->division->id,
            'title' => 'Pertemuan Mingguan #4: Clean Architecture',
            'day_name' => 'Selasa',
            'session_date' => now()->subDays(2)->format('Y-m-d'),
            'time_start' => '16:00:00',
            'time_end' => '18:00:00',
            'session_type' => 'workshop_teknis',
            'location' => 'Lab Komputer 3',
            'topic_material' => 'Standarisasi Repository Pattern & RESTful API Architecture',
            'learning_outcomes' => 'Mahasiswa memahami pemisahan controller, service layer, repository pattern, dan implementasi automated feature tests.',
            'instructor_name' => 'Muhammad Rayhan Fajar',
            'notes' => 'Materi telah selesai disampaikan.',
            'created_by' => $this->studentUser->id,
            'status' => 'closed',
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('student.silabus'));

        $response->assertStatus(200);
        $response->assertSee('Arsip Sesi Pertemuan');
        $response->assertSee('Standarisasi Repository Pattern &amp; RESTful API Architecture', false);
        $response->assertSee('Mahasiswa memahami pemisahan controller, service layer, repository pattern, dan implementasi automated feature tests.');
        $response->assertSee('silabusMateriModal');
        $response->assertSee('Rangkuman Penuh');
    }

    public function test_syllabus_topics_render_properly_after_admin_updates_division_profile()
    {
        $admin = User::create([
            'name' => 'Admin Pemrograman',
            'email' => 'admin.prog@test.com',
            'password' => Hash::make('password123'),
            'role' => 'division_admin',
            'division_id' => $this->division->id,
        ]);

        $updateData = [
            'tagline' => 'Updated Tagline',
            'description' => 'Updated Description',
            'vision' => 'Updated Vision',
            'mission' => 'Updated Mission',
            'focus_topics_raw' => "Full-Stack Laravel & Vue\nDomain-Driven Design\nCloud Native Kubernetes",
            'adviser_name' => 'Dosen Pembina Baru',
            'adviser_title' => 'M.T.',
            'leader_name' => 'Ketua Baru',
            'leader_nim' => '230101999',
            'leader_bio' => 'Bio Baru',
        ];

        $updateResponse = $this->actingAs($admin)->put(route('admin.divisions.update', $this->division->id), $updateData);
        $updateResponse->assertRedirect(route('admin.divisions.index'));

        // Mahasiswa mengakses halaman silabus
        $response = $this->actingAs($this->studentUser)->get(route('student.silabus'));
        $response->assertStatus(200);
        $response->assertSee('Full-Stack Laravel &amp; Vue', false);
        $response->assertSee('Domain-Driven Design');
        $response->assertSee('Cloud Native Kubernetes');
        $response->assertDontSee('Topik pembelajaran belum diperbarui oleh ketua divisi.');
    }
}
