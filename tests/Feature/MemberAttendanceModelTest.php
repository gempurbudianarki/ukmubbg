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

class MemberAttendanceModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_creation_and_relations(): void
    {
        $division = Division::create([
            'slug' => 'pemrograman',
            'name' => 'Divisi Pemrograman',
            'tagline' => 'Software Engineering',
            'description' => 'Coding and web dev',
            'focus_topics' => ['Laravel', 'Vue'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#0284c7',
            'vision' => 'Vision text',
            'mission' => 'Mission text',
            'adviser_name' => 'Dosen Pembina',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua Divisi',
            'leader_nim' => '210103001',
            'leader_bio' => 'Bio',
        ]);

        $recruitment = Recruitment::create([
            'registration_code' => 'REG-TEST-001',
            'full_name' => 'Budi Santoso',
            'nim' => '220103010',
            'email' => 'budi@test.com',
            'phone_whatsapp' => '08123456789',
            'semester' => 3,
            'class_group' => 'TI-3A',
            'first_choice_division_id' => $division->id,
            'reason_to_join' => 'Minat coding web dan mobile',
            'status' => 'accepted',
        ]);

        $member = Member::create([
            'recruitment_id' => $recruitment->id,
            'nim' => '220103010',
            'name' => 'Budi Santoso',
            'email' => 'budi@test.com',
            'phone_number' => '08123456789',
            'division_id' => $division->id,
            'batch_year' => '2024',
            'status' => 'aktif',
            'join_date' => now()->toDateString(),
            'notes' => 'Pendaftar oprec berprestasi',
        ]);

        $this->assertDatabaseHas('members', [
            'nim' => '220103010',
            'status' => 'aktif',
        ]);

        $this->assertEquals($division->id, $member->division->id);
        $this->assertEquals($recruitment->id, $member->recruitment->id);
        $this->assertCount(1, $division->members);
    }

    public function test_attendance_session_and_logs(): void
    {
        $division = Division::create([
            'slug' => 'iot',
            'name' => 'Divisi IoT',
            'tagline' => 'Internet of Things',
            'description' => 'Hardware & sensors',
            'focus_topics' => ['ESP32', 'Arduino'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#10b981',
            'vision' => 'Vision text',
            'mission' => 'Mission text',
            'adviser_name' => 'Dosen Pembina',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua IoT',
            'leader_nim' => '210103002',
            'leader_bio' => 'Bio',
        ]);

        $user = User::create([
            'name' => 'Admin UKM',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        $member = Member::create([
            'nim' => '220103099',
            'name' => 'Siti Aisyah',
            'email' => 'siti@test.com',
            'division_id' => $division->id,
            'batch_year' => '2024',
            'status' => 'aktif',
        ]);

        $session = AttendanceSession::create([
            'division_id' => $division->id,
            'title' => 'Rapat Perdana IoT',
            'session_date' => now()->toDateString(),
            'time_start' => '16:00:00',
            'time_end' => '18:00:00',
            'location' => 'Lab Komputer 3',
            'created_by' => $user->id,
        ]);

        $log = AttendanceLog::create([
            'session_id' => $session->id,
            'member_id' => $member->id,
            'status' => 'hadir',
            'notes' => 'Tepat waktu',
        ]);

        $this->assertDatabaseHas('attendance_sessions', ['title' => 'Rapat Perdana IoT']);
        $this->assertDatabaseHas('attendance_logs', ['status' => 'hadir']);

        $this->assertEquals($session->id, $log->session->id);
        $this->assertEquals($member->id, $log->member->id);
        $this->assertCount(1, $session->logs);
        $this->assertCount(1, $member->attendanceLogs);
    }
}
