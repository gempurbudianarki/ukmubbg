<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DivisionAdminLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $divisionAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@ukmilkom.id',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
        ]);

        $division = Division::create([
            'name' => 'Divisi Pemrograman',
            'slug' => 'pemrograman',
            'status' => 'active',
        ]);

        $this->divisionAdmin = User::create([
            'name' => 'Admin Divisi',
            'email' => 'pemrograman@ukmilkom.id',
            'password' => bcrypt('password123'),
            'role' => 'division_admin',
            'division_id' => $division->id,
        ]);
    }

    public function test_super_admin_can_create_new_division(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('admin.divisions.store'), [
            'name' => 'Divisi Data Science',
            'tagline' => 'Analisis Data & AI',
            'description' => 'Eksplorasi big data dan machine learning.',
            'focus_topics_raw' => "Python Data Science\nDeep Learning\nSQL Big Data",
            'color_accent' => '#0ea5e9',
            'icon_class' => 'fa-database',
            'recruitment_quota' => 25,
            'is_recruitment_open' => '1',
            'adviser_name' => 'Dr. Data, M.Kom.',
            'adviser_title' => 'Dosen AI',
            'leader_name' => 'Ketua Data',
            'leader_nim' => '240101001',
            'leader_bio' => 'Bio ketua data.',
        ]);

        $response->assertRedirect(route('admin.divisions.index'));
        $this->assertDatabaseHas('divisions', [
            'name' => 'Divisi Data Science',
            'slug' => 'divisi-data-science',
            'color_accent' => '#0ea5e9',
            'icon_class' => 'fa-database',
            'status' => 'active',
        ]);
    }

    public function test_super_admin_can_toggle_suspend_division(): void
    {
        $division = Division::create([
            'name' => 'Divisi Robotika',
            'slug' => 'robotika',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('admin.divisions.toggleStatus', $division));
        $response->assertSessionHas('success');
        $this->assertEquals('suspended', $division->fresh()->status);

        // Toggle back to active
        $this->actingAs($this->superAdmin)->post(route('admin.divisions.toggleStatus', $division));
        $this->assertEquals('active', $division->fresh()->status);
    }

    public function test_super_admin_can_toggle_recruitment_for_division(): void
    {
        $division = Division::create([
            'name' => 'Divisi Desain',
            'slug' => 'desain',
            'status' => 'active',
            'is_recruitment_open' => true,
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('admin.divisions.toggleRecruitment', $division));
        $response->assertSessionHas('success');
        $this->assertFalse($division->fresh()->is_recruitment_open);
    }

    public function test_super_admin_can_assign_and_remove_division_admin(): void
    {
        $division = Division::create([
            'name' => 'Divisi Cloud Computing',
            'slug' => 'cloud',
            'status' => 'active',
        ]);

        $memberUser = User::create([
            'name' => 'Budi Calon Admin',
            'email' => 'budi@student.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'member',
        ]);

        // Assign Admin
        $response = $this->actingAs($this->superAdmin)->post(route('admin.divisions.assignAdmin', $division), [
            'user_id' => $memberUser->id,
        ]);
        $response->assertSessionHas('success');
        $this->assertEquals('division_admin', $memberUser->fresh()->role);
        $this->assertEquals($division->id, $memberUser->fresh()->division_id);

        // Remove Admin
        $removeResponse = $this->actingAs($this->superAdmin)->post(route('admin.divisions.removeAdmin', [$division, $memberUser]));
        $removeResponse->assertSessionHas('success');
        $this->assertEquals('member', $memberUser->fresh()->role);
    }

    public function test_super_admin_can_soft_delete_and_restore_division(): void
    {
        $division = Division::create([
            'name' => 'Divisi Quantum',
            'slug' => 'quantum',
            'status' => 'active',
        ]);

        // Soft delete
        $response = $this->actingAs($this->superAdmin)->delete(route('admin.divisions.destroy', $division));
        $response->assertRedirect(route('admin.divisions.index'));
        $this->assertSoftDeleted('divisions', ['id' => $division->id]);

        // Restore
        $restoreResponse = $this->actingAs($this->superAdmin)->post(route('admin.divisions.restore', $division->id));
        $restoreResponse->assertRedirect(route('admin.divisions.index'));
        $this->assertNotSoftDeleted('divisions', ['id' => $division->id]);
        $this->assertEquals('active', $division->fresh()->status);
    }

    public function test_division_admin_cannot_create_or_suspend_divisions(): void
    {
        $response = $this->actingAs($this->divisionAdmin)->post(route('admin.divisions.store'), [
            'name' => 'Divisi Unauthorized',
        ]);
        $response->assertForbidden();

        $division = Division::first();
        $suspendResponse = $this->actingAs($this->divisionAdmin)->post(route('admin.divisions.toggleStatus', $division));
        $suspendResponse->assertForbidden();
    }
}
