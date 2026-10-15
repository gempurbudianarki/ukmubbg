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

class ExcelExportTest extends TestCase
{
    use RefreshDatabase;

    private Division $divisionWeb;
    private Division $divisionAi;
    private User $superAdmin;
    private User $divisionAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->divisionWeb = Division::create([
            'name' => 'Divisi Pemrograman Web',
            'slug' => 'pemrograman-web',
            'description' => 'Web dev division',
            'status' => 'active',
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#0284c7',
        ]);

        $this->divisionAi = Division::create([
            'name' => 'Divisi Kecerdasan Buatan',
            'slug' => 'kecerdasan-buatan',
            'description' => 'AI division',
            'status' => 'active',
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#8b5cf6',
        ]);

        $this->superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@ukm.test',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
        ]);

        $this->divisionAdmin = User::create([
            'name' => 'Admin Divisi Web',
            'email' => 'admin.web@ukm.test',
            'password' => bcrypt('password123'),
            'role' => 'division_admin',
            'division_id' => $this->divisionWeb->id,
        ]);
    }

    public function test_super_admin_can_export_recruitment_excel(): void
    {
        Recruitment::create([
            'registration_code' => 'REG-2026-0001',
            'full_name' => 'Ahmad Fauzi',
            'nim' => '2401010088',
            'email' => 'ahmad@ukm.test',
            'phone_whatsapp' => '081234567890',
            'semester' => 3,
            'first_choice_division_id' => $this->divisionWeb->id,
            'reason_to_join' => 'Ingin memperdalam keahlian backend web.',
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('admin.recruitment.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.ms-excel; charset=UTF-8');
        $this->assertStringContainsString('.xls', $response->headers->get('content-disposition'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('UNIVERSITAS BINA BANGSA GETSEMPENA', $content);
        $this->assertStringContainsString('UKM ILMU KOMPUTER', $content);
        $this->assertStringContainsString('mso-number-format', $content);
        $this->assertStringContainsString('2401010088', $content);
        $this->assertStringContainsString('081234567890', $content);
        $this->assertStringContainsString('Ahmad Fauzi', $content);
    }

    public function test_super_admin_can_export_members_excel(): void
    {
        Member::create([
            'nim' => '2401010099',
            'name' => 'Rina Marlina',
            'email' => 'rina@ukm.test',
            'phone_number' => '085211223344',
            'division_id' => $this->divisionWeb->id,
            'batch_year' => '2024',
            'status' => 'aktif',
            'join_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('admin.members.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.ms-excel; charset=UTF-8');
        $this->assertStringContainsString('.xls', $response->headers->get('content-disposition'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('UNIVERSITAS BINA BANGSA GETSEMPENA', $content);
        $this->assertStringContainsString('mso-number-format', $content);
        $this->assertStringContainsString('2401010099', $content);
        $this->assertStringContainsString('085211223344', $content);
        $this->assertStringContainsString('Rina Marlina', $content);
    }

    public function test_admin_can_export_attendance_session_excel(): void
    {
        $session = AttendanceSession::create([
            'title' => 'Pertemuan Riset Laravel 10',
            'division_id' => $this->divisionWeb->id,
            'session_date' => now()->toDateString(),
            'time_start' => '14:00',
            'session_type' => 'workshop_teknis',
            'location' => 'Lab Komputer Terpadu',
            'topic_material' => 'Eloquent Relationship Optimization',
            'instructor_name' => 'Rayhan Fajar',
            'status' => 'closed',
            'passcode' => 'LARAVEL',
            'created_by' => $this->superAdmin->id,
        ]);

        $member = Member::create([
            'nim' => '2401010011',
            'name' => 'Dedi Setiawan',
            'email' => 'dedi@ukm.test',
            'division_id' => $this->divisionWeb->id,
            'batch_year' => '2024',
            'status' => 'aktif',
        ]);

        AttendanceLog::create([
            'session_id' => $session->id,
            'member_id' => $member->id,
            'status' => 'hadir',
            'checked_in_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('admin.attendance.export', $session));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.ms-excel; charset=UTF-8');
        $this->assertStringContainsString('.xls', $response->headers->get('content-disposition'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('Pertemuan Riset Laravel 10', $content);
        $this->assertStringContainsString('Dedi Setiawan', $content);
        $this->assertStringContainsString('2401010011', $content);
        $this->assertStringContainsString('HADIR', $content);
    }

    public function test_division_admin_export_is_isolated_to_own_division(): void
    {
        // Member of Web
        Member::create([
            'nim' => '2401010001',
            'name' => 'Member Web',
            'email' => 'mweb@ukm.test',
            'division_id' => $this->divisionWeb->id,
            'batch_year' => '2024',
            'status' => 'aktif',
        ]);

        // Member of AI
        Member::create([
            'nim' => '2401010002',
            'name' => 'Member AI Secret',
            'email' => 'mai@ukm.test',
            'division_id' => $this->divisionAi->id,
            'batch_year' => '2024',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->divisionAdmin)->get(route('admin.members.export'));
        $response->assertStatus(200);

        $content = $response->streamedContent();
        $this->assertStringContainsString('Member Web', $content);
        $this->assertStringNotContainsString('Member AI Secret', $content);
    }
}
