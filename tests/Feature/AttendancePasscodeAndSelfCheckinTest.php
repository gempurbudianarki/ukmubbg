<?php

namespace Tests\Feature;

use App\Models\AttendanceLog;
use App\Models\AttendanceSession;
use App\Models\Division;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendancePasscodeAndSelfCheckinTest extends TestCase
{
    use RefreshDatabase;

    private User $divisionAdmin;
    private Division $division;
    private User $studentUser;
    private Member $studentMember;

    protected function setUp(): void
    {
        parent::setUp();

        $this->division = Division::create([
            'slug' => 'pemrograman',
            'name' => 'Divisi Pemrograman',
            'tagline' => 'Code',
            'description' => 'Desc',
            'focus_topics' => ['Laravel', 'Vue'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#2563eb',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen Pembina',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua Divisi Pemrograman',
            'leader_nim' => '210103001',
            'leader_bio' => 'Bio',
        ]);

        $this->divisionAdmin = User::factory()->create([
            'name' => 'Ketua Pemrograman',
            'role' => 'division_admin',
            'division_id' => $this->division->id,
        ]);

        $this->studentUser = User::factory()->create([
            'name' => 'Bintang Mahasiswa',
            'email' => 'bintang@student.ac.id',
            'role' => 'member',
            'nim' => '2301010099',
            'division_id' => $this->division->id,
        ]);

        $this->studentMember = Member::create([
            'user_id' => $this->studentUser->id,
            'division_id' => $this->division->id,
            'nim' => '2301010099',
            'name' => 'Bintang Mahasiswa',
            'email' => 'bintang@student.ac.id',
            'status' => 'aktif',
        ]);
    }

    public function test_division_admin_can_create_session_with_custom_passcode(): void
    {
        $response = $this->actingAs($this->divisionAdmin)->post(route('admin.attendance.store'), [
            'division_id' => $this->division->id,
            'title' => 'Pertemuan Riset Laravel 11',
            'day_name' => 'Rabu',
            'session_date' => '2026-10-07',
            'time_start' => '14:00',
            'time_end' => '16:00',
            'session_type' => 'riset_rutin',
            'location' => 'Lab Komputer 3',
            'topic_material' => 'Authentication & API Tokens',
            'instructor_name' => 'Ketua Pemrograman',
            'passcode' => 'KODERESMI',
            'allow_self_checkin' => 1,
        ]);

        $response->assertRedirect();

        $session = AttendanceSession::where('title', 'Pertemuan Riset Laravel 11')->first();
        $this->assertNotNull($session);
        $this->assertEquals('KODERESMI', $session->passcode);
        $this->assertTrue($session->allow_self_checkin);
        $this->assertEquals('open', $session->status);
    }

    public function test_division_admin_can_quick_mark_member_attendance_directly(): void
    {
        $session = AttendanceSession::create([
            'division_id' => $this->division->id,
            'created_by' => $this->divisionAdmin->id,
            'title' => 'Sesi Bootcamp Algoritma',
            'day_name' => 'Rabu',
            'session_date' => '2026-10-07',
            'time_start' => '14:00',
            'location' => 'Lab 1',
            'passcode' => 'BOOT88',
            'status' => 'open',
        ]);

        $log = AttendanceLog::create([
            'session_id' => $session->id,
            'member_id' => $this->studentMember->id,
            'status' => 'alpa',
        ]);

        $response = $this->actingAs($this->divisionAdmin)->post(route('admin.attendance.quickMark', $session), [
            'log_id' => $log->id,
            'status' => 'hadir',
            'notes' => 'Diabsen langsung di kelas oleh Ketua',
        ]);

        $response->assertRedirect();
        $this->assertEquals('hadir', $log->fresh()->status);
        $this->assertEquals('admin', $log->fresh()->checkin_type);
        $this->assertNotNull($log->fresh()->checked_in_at);
        $this->assertEquals('Diabsen langsung di kelas oleh Ketua', $log->fresh()->notes);
    }

    public function test_division_admin_can_toggle_session_status_and_update_passcode(): void
    {
        $session = AttendanceSession::create([
            'division_id' => $this->division->id,
            'created_by' => $this->divisionAdmin->id,
            'title' => 'Sesi Evaluasi Sprint',
            'day_name' => 'Kamis',
            'session_date' => '2026-10-08',
            'time_start' => '16:00',
            'location' => 'Ruang Rapat',
            'passcode' => 'SPRINT1',
            'status' => 'open',
        ]);

        // Toggle to closed
        $this->actingAs($this->divisionAdmin)->post(route('admin.attendance.toggleStatus', $session))
            ->assertRedirect();
        $this->assertEquals('closed', $session->fresh()->status);

        // Update passcode
        $this->actingAs($this->divisionAdmin)->post(route('admin.attendance.updatePasscode', $session), [
            'passcode' => 'NEWPASS99',
        ])->assertRedirect();
        $this->assertEquals('NEWPASS99', $session->fresh()->passcode);
    }

    public function test_student_can_self_checkin_with_valid_passcode(): void
    {
        $session = AttendanceSession::create([
            'division_id' => $this->division->id,
            'created_by' => $this->divisionAdmin->id,
            'title' => 'Workshop Microservices',
            'day_name' => 'Rabu',
            'session_date' => now()->toDateString(),
            'time_start' => '13:00',
            'location' => 'Lab Jaringan',
            'passcode' => 'MICRO99',
            'allow_self_checkin' => true,
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->studentUser)->post(route('student.presensi.checkin'), [
            'session_id' => $session->id,
            'passcode' => 'micro99', // case insensitive check
            'notes' => 'Hadir di baris depan',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $log = AttendanceLog::where('session_id', $session->id)
            ->where('member_id', $this->studentMember->id)
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals('hadir', $log->status);
        $this->assertEquals('self', $log->checkin_type);
        $this->assertNotNull($log->checked_in_at);
        $this->assertEquals('Hadir di baris depan', $log->notes);
    }

    public function test_student_self_checkin_rejected_on_wrong_passcode(): void
    {
        $session = AttendanceSession::create([
            'division_id' => $this->division->id,
            'created_by' => $this->divisionAdmin->id,
            'title' => 'Workshop Microservices',
            'day_name' => 'Rabu',
            'session_date' => now()->toDateString(),
            'time_start' => '13:00',
            'location' => 'Lab Jaringan',
            'passcode' => 'MICRO99',
            'allow_self_checkin' => true,
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->studentUser)->post(route('student.presensi.checkin'), [
            'session_id' => $session->id,
            'passcode' => 'WRONGCODE',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $log = AttendanceLog::where('session_id', $session->id)
            ->where('member_id', $this->studentMember->id)
            ->first();

        $this->assertNull($log);
    }

    public function test_student_self_checkin_rejected_when_session_is_closed(): void
    {
        $session = AttendanceSession::create([
            'division_id' => $this->division->id,
            'created_by' => $this->divisionAdmin->id,
            'title' => 'Workshop Microservices',
            'day_name' => 'Rabu',
            'session_date' => now()->toDateString(),
            'time_start' => '13:00',
            'location' => 'Lab Jaringan',
            'passcode' => 'MICRO99',
            'allow_self_checkin' => true,
            'status' => 'closed',
        ]);

        $response = $this->actingAs($this->studentUser)->post(route('student.presensi.checkin'), [
            'session_id' => $session->id,
            'passcode' => 'MICRO99',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_student_self_checkin_rejected_when_passcode_has_expired(): void
    {
        $session = AttendanceSession::create([
            'division_id' => $this->division->id,
            'created_by' => $this->divisionAdmin->id,
            'title' => 'Workshop Microservices',
            'day_name' => 'Rabu',
            'session_date' => now()->toDateString(),
            'time_start' => '13:00',
            'location' => 'Lab Jaringan',
            'passcode' => 'MICRO99',
            'passcode_expires_at' => now()->subMinutes(10), // expired 10 minutes ago
            'allow_self_checkin' => true,
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->studentUser)->post(route('student.presensi.checkin'), [
            'session_id' => $session->id,
            'passcode' => 'MICRO99',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $log = AttendanceLog::where('session_id', $session->id)
            ->where('member_id', $this->studentMember->id)
            ->first();

        $this->assertNull($log);
    }

    public function test_student_can_submit_permission_or_sick_request_with_attachment(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $session = AttendanceSession::create([
            'division_id' => $this->division->id,
            'created_by' => $this->divisionAdmin->id,
            'title' => 'Bootcamp Flutter',
            'day_name' => 'Rabu',
            'session_date' => now()->toDateString(),
            'time_start' => '14:00',
            'location' => 'Lab Mobile',
            'passcode' => 'FLUT99',
            'status' => 'open',
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->create('surat_dokter.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->studentUser)->post(route('student.presensi.permission'), [
            'session_id' => $session->id,
            'status' => 'sakit',
            'notes' => 'Demam tinggi dan istirahat dokter',
            'attachment' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $log = AttendanceLog::where('session_id', $session->id)
            ->where('member_id', $this->studentMember->id)
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals('sakit', $log->status);
        $this->assertEquals('self', $log->checkin_type);
        $this->assertEquals('Demam tinggi dan istirahat dokter', $log->notes);
        $this->assertNotNull($log->attachment);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($log->attachment);
    }
}
