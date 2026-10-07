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
        $response->assertSee('Rabu');
        $response->assertSee('Graph Theory & Shortest Path');
        $response->assertSee('Penyelesaian soal kompetisi ICPC regional.');
        $response->assertSee('Ketua Divisi');
    }

    public function test_division_admin_can_view_and_print_formal_bap_sheet(): void
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

        $response = $this->actingAs($this->divisionAdmin)->get(route('admin.attendance.bap', $session));
        $response->assertStatus(200);
        $response->assertSee('BERITA ACARA PERTEMUAN');
        $response->assertSee('Graph Theory & Shortest Path');
        $response->assertSee('Dosen Pembina UKM');
    }
}
