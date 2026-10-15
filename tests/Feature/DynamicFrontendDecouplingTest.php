<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicFrontendDecouplingTest extends TestCase
{
    use RefreshDatabase;

    public function test_recruitment_wizard_renders_newly_created_custom_division(): void
    {
        Setting::set('recruitment_status', 'open');

        $customDivision = Division::create([
            'name' => 'Divisi Internet of Robotics',
            'slug' => 'robotics',
            'tagline' => 'Robotika Masa Depan',
            'description' => 'Membangun robot otonom dan otomasi canggih.',
            'color_accent' => '#f97316',
            'icon_class' => 'fa-robot',
            'status' => 'active',
            'is_recruitment_open' => true,
            'recruitment_quota' => 15,
        ]);

        $response = $this->get(route('recruitment.index'));
        $response->assertOk();
        $response->assertSee('Divisi Internet of Robotics');
        $response->assertSee('fa-robot');
        $response->assertSee('#f97316');
    }

    public function test_suspended_division_is_hidden_from_recruitment_wizard(): void
    {
        Setting::set('recruitment_status', 'open');

        $activeDiv = Division::create([
            'name' => 'Divisi Aktif',
            'slug' => 'aktif',
            'status' => 'active',
            'is_recruitment_open' => true,
        ]);

        $suspendedDiv = Division::create([
            'name' => 'Divisi Dibekukan',
            'slug' => 'dibekukan',
            'status' => 'suspended',
            'is_recruitment_open' => true,
        ]);

        $response = $this->get(route('recruitment.index'));
        $response->assertOk();
        $response->assertSee('Divisi Aktif');
        $response->assertDontSee('Divisi Dibekukan');
    }

    public function test_footer_renders_dynamic_divisions(): void
    {
        $customDivision = Division::create([
            'name' => 'Divisi Cloud Computing',
            'slug' => 'cloud-computing',
            'status' => 'active',
        ]);

        $suspendedDiv = Division::create([
            'name' => 'Divisi Nonaktif',
            'slug' => 'nonaktif',
            'status' => 'suspended',
        ]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('Divisi Cloud Computing');
        $response->assertDontSee('Divisi Nonaktif');
    }

    public function test_project_and_event_support_custom_division_fallback(): void
    {
        $customDivision = Division::create([
            'name' => 'Divisi Quantum Robotics',
            'slug' => 'quantum-robotics',
            'status' => 'active',
        ]);

        $project = new \App\Models\Project([
            'title' => 'Quantum Sim',
            'division_id' => $customDivision->id,
        ]);
        $project->setRelation('division', $customDivision);

        $event = new \App\Models\Event([
            'title' => 'Quantum Workshop',
            'division_id' => $customDivision->id,
        ]);
        $event->setRelation('division', $customDivision);

        $this->assertNotEmpty($project->thumbnail_url);
        $this->assertNotEmpty($event->banner_url);
    }
}
