<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecruitmentSettingsAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $divisionAdmin;
    private Division $division;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);

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
            'adviser_name' => 'Dosen',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua',
            'leader_nim' => '210103001',
            'leader_bio' => 'Bio',
            'is_recruitment_open' => true,
        ]);

        $this->divisionAdmin = User::factory()->create([
            'role' => 'division_admin',
            'division_id' => $this->division->id,
        ]);
    }

    public function test_super_admin_can_view_recruitment_settings_page(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.recruitment.settings'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Gelombang & Kuota Divisi');
        $response->assertSee('Divisi Pemrograman');
    }

    public function test_division_admin_cannot_access_recruitment_settings(): void
    {
        $response = $this->actingAs($this->divisionAdmin)->get(route('admin.recruitment.settings'));
        $response->assertStatus(403);
    }

    public function test_super_admin_can_update_schedule_and_division_status(): void
    {
        $payload = [
            'recruitment_status' => 'open',
            'recruitment_start_date' => '2026-10-01 00:00',
            'recruitment_end_date' => '2026-10-31 23:59',
            'recruitment_batch_name' => 'Gelombang Ganjil 2026',
            'recruitment_closed_message' => 'Pendaftaran saat ini telah ditutup.',
            'divisions' => [
                $this->division->id => [
                    'is_recruitment_open' => '0',
                    'recruitment_quota' => 30,
                    'recruitment_notes' => 'Kuota Penuh',
                ],
            ],
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('admin.recruitment.settings.update'), $payload);
        $response->assertRedirect(route('admin.recruitment.settings'));
        $response->assertSessionHas('success');

        $this->division->refresh();
        $this->assertFalse($this->division->is_recruitment_open);
        $this->assertEquals(30, $this->division->recruitment_quota);
        $this->assertEquals('Kuota Penuh', $this->division->recruitment_notes);
        $this->assertEquals('Gelombang Ganjil 2026', Setting::get('recruitment_batch_name'));
    }
}
