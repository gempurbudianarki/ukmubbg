<?php

namespace Tests\Feature;

use App\Models\AttendanceLog;
use App\Models\AttendanceSession;
use App\Models\Division;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private Division $divIoT;
    private Member $member1;
    private Member $member2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->divIoT = Division::create([
            'slug' => 'iot',
            'name' => 'Divisi IoT',
            'tagline' => 'Hardware',
            'description' => 'IoT and robotics',
            'focus_topics' => ['ESP32'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#10b981',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen IoT',
            'adviser_title' => 'M.T',
            'leader_name' => 'Ketua IoT',
            'leader_nim' => '210103005',
            'leader_bio' => 'Bio',
        ]);

        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        $this->member1 = Member::create([
            'nim' => '230104001',
            'name' => 'Bintang Pratama',
            'email' => 'bintang@test.com',
            'division_id' => $this->divIoT->id,
            'batch_year' => '2025',
            'status' => 'aktif',
        ]);

        $this->member2 = Member::create([
            'nim' => '230104002',
            'name' => 'Dina Salsabila',
            'email' => 'dina@test.com',
            'division_id' => $this->divIoT->id,
            'batch_year' => '2025',
            'status' => 'aktif',
        ]);
    }

    public function test_can_create_attendance_session_and_auto_populates_logs(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('admin.attendance.store'), [
            'title' => 'Workshop Sensor ESP32',
            'division_id' => $this->divIoT->id,
            'session_date' => now()->toDateString(),
            'time_start' => '13:00',
            'time_end' => '15:30',
            'location' => 'Lab Jaringan Komputer Lt. 2',
            'notes' => 'Membawa laptop dan kabel data USB type C',
        ]);

        $response->assertRedirect();

        $session = AttendanceSession::where('title', 'Workshop Sensor ESP32')->first();
        $this->assertNotNull($session);
        $this->assertEquals($this->divIoT->id, $session->division_id);

        // Verify logs were auto-created for the 2 active members of that division
        $this->assertCount(2, $session->logs);
        $this->assertTrue($session->logs->contains('member_id', $this->member1->id));
        $this->assertTrue($session->logs->contains('member_id', $this->member2->id));
    }

    public function test_can_view_session_checklist_sheet(): void
    {
        $session = AttendanceSession::create([
            'title' => 'Rapat Pleno Mingguan',
            'division_id' => null, // General plenary session for all
            'session_date' => now()->toDateString(),
            'time_start' => '16:00',
            'location' => 'Auditorium Utama',
            'created_by' => $this->superAdmin->id,
        ]);

        AttendanceLog::create([
            'session_id' => $session->id,
            'member_id' => $this->member1->id,
            'status' => 'hadir',
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.attendance.show', $session));

        $response->assertStatus(200);
        $response->assertSee('Rapat Pleno Mingguan');
        $response->assertSee('Bintang Pratama');
    }

    public function test_can_batch_update_attendance_logs(): void
    {
        $session = AttendanceSession::create([
            'title' => 'Evaluasi Proyek IoT',
            'division_id' => $this->divIoT->id,
            'session_date' => now()->toDateString(),
            'time_start' => '14:00',
            'location' => 'Lab IoT',
            'created_by' => $this->superAdmin->id,
        ]);

        $log1 = AttendanceLog::create([
            'session_id' => $session->id,
            'member_id' => $this->member1->id,
            'status' => 'hadir',
        ]);

        $log2 = AttendanceLog::create([
            'session_id' => $session->id,
            'member_id' => $this->member2->id,
            'status' => 'hadir',
        ]);

        $response = $this->actingAs($this->superAdmin)->put(route('admin.attendance.updateLogs', $session), [
            'logs' => [
                $log1->id => [
                    'status' => 'hadir',
                    'notes' => 'Tepat waktu',
                ],
                $log2->id => [
                    'status' => 'izin',
                    'notes' => 'Ada praktikum pengganti',
                ],
            ],
        ]);

        $response->assertRedirect();
        $this->assertEquals('hadir', $log1->fresh()->status);
        $this->assertEquals('izin', $log2->fresh()->status);
        $this->assertEquals('Ada praktikum pengganti', $log2->fresh()->notes);
    }

    public function test_can_delete_attendance_session(): void
    {
        $session = AttendanceSession::create([
            'title' => 'Sesi Uji Coba Batal',
            'session_date' => now()->toDateString(),
            'time_start' => '10:00',
            'location' => 'Ruang 1',
            'created_by' => $this->superAdmin->id,
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('admin.attendance.destroy', $session));
        $response->assertRedirect();
        $this->assertDatabaseMissing('attendance_sessions', ['id' => $session->id]);
    }
}
