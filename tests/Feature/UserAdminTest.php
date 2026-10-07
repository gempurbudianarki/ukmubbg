<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $memberUser;
    private Division $division;

    protected function setUp(): void
    {
        parent::setUp();

        $this->division = Division::create([
            'slug' => 'pemrograman',
            'name' => 'Divisi Pemrograman',
            'tagline' => 'Coding',
            'description' => 'Coding',
            'focus_topics' => ['PHP', 'Laravel'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#0284c7',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen A',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua A',
            'leader_nim' => '210103001',
            'leader_bio' => 'Bio',
        ]);

        $this->superAdmin = User::create([
            'name' => 'Super Admin UKM',
            'email' => 'super@ukm.test',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        $this->memberUser = User::create([
            'name' => 'Bintang Mahasiswa',
            'email' => 'bintang@student.test',
            'password' => bcrypt('password'),
            'role' => 'member',
        ]);
    }

    public function test_super_admin_can_view_user_management_page(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Pengguna');
        $response->assertSee('Tunjuk Role');
    }

    public function test_super_admin_can_assign_account_to_division_admin(): void
    {
        $response = $this->actingAs($this->superAdmin)->put(route('admin.users.assignRole', $this->memberUser->id), [
            'role' => 'division_admin',
            'division_id' => $this->division->id,
        ]);

        $response->assertRedirect();
        $this->memberUser->refresh();

        $this->assertEquals('division_admin', $this->memberUser->role);
        $this->assertEquals($this->division->id, $this->memberUser->division_id);
    }

    public function test_super_admin_can_assign_account_to_super_admin(): void
    {
        $response = $this->actingAs($this->superAdmin)->put(route('admin.users.assignRole', $this->memberUser->id), [
            'role' => 'super_admin',
        ]);

        $response->assertRedirect();
        $this->memberUser->refresh();

        $this->assertEquals('super_admin', $this->memberUser->role);
        $this->assertNull($this->memberUser->division_id);
    }

    public function test_super_admin_cannot_demote_self(): void
    {
        $response = $this->actingAs($this->superAdmin)->put(route('admin.users.assignRole', $this->superAdmin->id), [
            'role' => 'member',
        ]);

        $response->assertSessionHas('error');
        $this->superAdmin->refresh();
        $this->assertEquals('super_admin', $this->superAdmin->role);
    }

    public function test_super_admin_can_reset_user_password(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('admin.users.resetPassword', $this->memberUser->id), [
            'new_password' => 'secret1234',
        ]);

        $response->assertSessionHas('success');
    }
}
