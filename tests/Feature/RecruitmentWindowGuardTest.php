<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecruitmentWindowGuardTest extends TestCase
{
    use RefreshDatabase;

    private Division $divOpen;
    private Division $divClosed;

    protected function setUp(): void
    {
        parent::setUp();

        $this->divOpen = Division::create([
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
            'is_recruitment_open' => true,
            'recruitment_quota' => 20,
        ]);

        $this->divClosed = Division::create([
            'slug' => 'multimedia',
            'name' => 'Divisi Multimedia',
            'tagline' => 'Design',
            'description' => 'Desc',
            'focus_topics' => ['Figma'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#8b5cf6',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen B',
            'adviser_title' => 'M.Ds',
            'leader_name' => 'Ketua B',
            'leader_nim' => '210103002',
            'leader_bio' => 'Bio',
            'is_recruitment_open' => false,
            'recruitment_quota' => 0,
            'recruitment_notes' => 'Kuota Penuh',
        ]);
    }

    public function test_registration_page_only_shows_open_divisions(): void
    {
        Setting::set('recruitment_status', 'open');
        Setting::set('recruitment_start_date', now()->subDay()->format('Y-m-d H:i'));
        Setting::set('recruitment_end_date', now()->addDays(5)->format('Y-m-d H:i'));

        $response = $this->get(route('recruitment.index'));
        $response->assertStatus(200);
        $response->assertSee('Divisi Pemrograman');
        $response->assertDontSee('Divisi Multimedia');
    }

    public function test_registration_submission_rejects_closed_division(): void
    {
        Setting::set('recruitment_status', 'open');
        Setting::set('recruitment_start_date', now()->subDay()->format('Y-m-d H:i'));
        Setting::set('recruitment_end_date', now()->addDays(5)->format('Y-m-d H:i'));

        $response = $this->post(route('recruitment.store'), [
            'full_name' => 'Calon Peserta',
            'nim' => '240103099',
            'email' => 'calon@mail.com',
            'phone_whatsapp' => '081234567890',
            'semester' => 1,
            'class_group' => 'IF-1A',
            'first_choice_division_id' => $this->divClosed->id,
            'reason_to_join' => 'Saya ingin belajar desain komunikasi visual secara mendalam.',
        ]);

        $response->assertSessionHasErrors('first_choice_division_id');
    }

    public function test_registration_blocked_when_schedule_expired(): void
    {
        Setting::set('recruitment_status', 'open');
        Setting::set('recruitment_start_date', now()->subDays(10)->format('Y-m-d H:i'));
        Setting::set('recruitment_end_date', now()->subDay()->format('Y-m-d H:i')); // expired

        $response = $this->get(route('recruitment.index'));
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Sedang Ditutup');

        $storeResponse = $this->post(route('recruitment.store'), [
            'full_name' => 'Calon Peserta',
            'nim' => '240103099',
            'email' => 'calon@mail.com',
            'phone_whatsapp' => '081234567890',
            'semester' => 1,
            'class_group' => 'IF-1A',
            'first_choice_division_id' => $this->divOpen->id,
            'reason_to_join' => 'Saya ingin belajar web programming secara mendalam.',
        ]);

        $storeResponse->assertRedirect();
        $storeResponse->assertSessionHas('error');
    }
}
