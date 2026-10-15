<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationControlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Division::create([
            'name' => 'Divisi Pemrograman Web',
            'slug' => 'pemrograman-web',
            'description' => 'Web development division',
            'status' => 'active',
            'icon' => 'fas fa-code',
            'color' => '#2563eb',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/navigation');
        $response->assertRedirect('/login');
    }

    public function test_division_admin_cannot_access_navigation_settings(): void
    {
        $division = Division::first();
        $admin = User::factory()->create([
            'role' => 'division_admin',
            'division_id' => $division->id,
        ]);

        $response = $this->actingAs($admin)->get('/admin/navigation');
        $response->assertStatus(403);
    }

    public function test_super_admin_can_view_navigation_settings_page(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($superAdmin)->get('/admin/navigation');
        $response->assertStatus(200);
        $response->assertSee('Navigasi & Menu Publik');
        $response->assertSee('nav_galleries');
        $response->assertSee('nav_recruitment');
    }

    public function test_super_admin_can_toggle_navigation_items(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($superAdmin)->post('/admin/navigation', [
            'nav_home' => '1',
            'nav_divisions' => '1',
            'nav_projects' => '1',
            'nav_posts' => '1',
            'nav_events' => '1',
            'nav_officers' => '1',
            'nav_galleries' => '0',
            'nav_verify' => '1',
            'nav_recruitment' => '0',
        ]);

        $response->assertRedirect('/admin/navigation');
        $response->assertSessionHas('success');

        $this->assertEquals('0', Setting::get('nav_galleries'));
        $this->assertEquals('0', Setting::get('nav_recruitment'));
        $this->assertEquals('1', Setting::get('nav_home'));
    }

    public function test_navbar_respects_navigation_settings_visibility(): void
    {
        // 1. By default, Galeri and Oprec are active
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee(route('galleries.index'));
        $response->assertSee(route('recruitment.index'));

        // 2. Turn off Galeri and Recruitment
        Setting::set('nav_galleries', '0');
        Setting::set('nav_recruitment', '0');

        $responseDisabled = $this->get('/');
        $responseDisabled->assertStatus(200);
        $responseDisabled->assertDontSee(route('galleries.index'));
        $responseDisabled->assertDontSee('Daftar Anggota (Oprec)');

        // 3. Turn them back on
        Setting::set('nav_galleries', '1');
        Setting::set('nav_recruitment', '1');

        $responseEnabled = $this->get('/');
        $responseEnabled->assertStatus(200);
        $responseEnabled->assertSee(route('galleries.index'));
        $responseEnabled->assertSee(route('recruitment.index'));
    }
}
