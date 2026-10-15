<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicDivisionModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_division_supports_status_icon_class_and_soft_deletes(): void
    {
        $division = Division::create([
            'name' => 'Divisi Artificial Intelligence',
            'slug' => 'artificial-intelligence',
            'tagline' => 'Riset AI & Machine Learning',
            'description' => 'Eksplorasi model bahasa dan visi komputer.',
            'status' => 'active',
            'color_accent' => '#6366f1',
            'icon_class' => 'fa-robot',
            'is_recruitment_open' => true,
            'recruitment_quota' => 20,
        ]);

        $this->assertDatabaseHas('divisions', [
            'id' => $division->id,
            'status' => 'active',
            'icon_class' => 'fa-robot',
            'color_accent' => '#6366f1',
        ]);

        $this->assertTrue($division->isActive());
        $this->assertFalse($division->isSuspended());

        // Test Soft Delete
        $division->delete();
        $this->assertSoftDeleted('divisions', ['id' => $division->id]);
        $this->assertNull(Division::find($division->id));
        $this->assertNotNull(Division::withTrashed()->find($division->id));
    }

    public function test_division_admins_relation(): void
    {
        $division = Division::create([
            'name' => 'Divisi Game Development',
            'slug' => 'game-dev',
            'status' => 'active',
        ]);

        $admin = User::create([
            'name' => 'Admin Game Dev',
            'email' => 'gamedev@ukmilkom.id',
            'password' => bcrypt('password123'),
            'role' => 'division_admin',
            'division_id' => $division->id,
        ]);

        $this->assertTrue($division->admins->contains($admin));
        $this->assertEquals(1, $division->admins_count);
    }
}
