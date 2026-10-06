<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminEcosystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_super_admin_can_access_all_management_modules()
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        $this->actingAs($superAdmin);

        $this->get(route('admin.dashboard'))->assertStatus(200);
        $this->get(route('admin.projects.index'))->assertStatus(200);
        $this->get(route('admin.events.index'))->assertStatus(200);
        $this->get(route('admin.officers.index'))->assertStatus(200);
        $this->get(route('admin.certificates.index'))->assertStatus(200);
        $this->get(route('admin.galleries.index'))->assertStatus(200);
    }
}
