<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $divisionAdmin;
    private Division $divPemrograman;
    private Division $divMultimedia;

    protected function setUp(): void
    {
        parent::setUp();

        $this->divPemrograman = Division::create([
            'slug' => 'pemrograman',
            'name' => 'Divisi Pemrograman',
            'tagline' => 'Coding',
            'description' => 'Coding',
            'focus_topics' => ['Laravel'],
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

        $this->divMultimedia = Division::create([
            'slug' => 'multimedia',
            'name' => 'Divisi Multimedia',
            'tagline' => 'Design',
            'description' => 'Design',
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
        ]);

        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@test.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        $this->divisionAdmin = User::create([
            'name' => 'Admin Pemrograman',
            'email' => 'pemrograman@test.com',
            'password' => bcrypt('password'),
            'role' => 'division_admin',
            'division_id' => $this->divPemrograman->id,
        ]);
    }

    public function test_super_admin_can_view_members_with_filters(): void
    {
        Member::create([
            'nim' => '220101001',
            'name' => 'Ahmad Fadhil',
            'email' => 'ahmad@test.com',
            'division_id' => $this->divPemrograman->id,
            'batch_year' => '2024',
            'status' => 'aktif',
        ]);

        Member::create([
            'nim' => '220101002',
            'name' => 'Citra Lestari',
            'email' => 'citra@test.com',
            'division_id' => $this->divMultimedia->id,
            'batch_year' => '2024',
            'status' => 'alumni',
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('admin.members.index'));
        $response->assertStatus(200);
        $response->assertSee('Ahmad Fadhil');
        $response->assertSee('Citra Lestari');

        // Test filter by division
        $filterResponse = $this->actingAs($this->superAdmin)
            ->get(route('admin.members.index', ['division' => $this->divPemrograman->id]));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('Ahmad Fadhil');
        $filterResponse->assertDontSee('Citra Lestari');

        // Test filter by status
        $statusResponse = $this->actingAs($this->superAdmin)
            ->get(route('admin.members.index', ['status' => 'alumni']));
        $statusResponse->assertStatus(200);
        $statusResponse->assertSee('Citra Lestari');
        $statusResponse->assertDontSee('Ahmad Fadhil');
    }

    public function test_division_admin_only_sees_their_division_members(): void
    {
        Member::create([
            'nim' => '220101001',
            'name' => 'Ahmad Fadhil',
            'email' => 'ahmad@test.com',
            'division_id' => $this->divPemrograman->id,
            'batch_year' => '2024',
            'status' => 'aktif',
        ]);

        Member::create([
            'nim' => '220101002',
            'name' => 'Citra Lestari',
            'email' => 'citra@test.com',
            'division_id' => $this->divMultimedia->id,
            'batch_year' => '2024',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->divisionAdmin)->get(route('admin.members.index'));
        $response->assertStatus(200);
        $response->assertSee('Ahmad Fadhil');
        $response->assertDontSee('Citra Lestari');
    }

    public function test_can_store_new_member_manually(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('admin.members.store'), [
            'nim' => '230101050',
            'name' => 'Doni Saputra',
            'email' => 'doni@test.com',
            'phone_number' => '08987654321',
            'division_id' => $this->divPemrograman->id,
            'batch_year' => '2025',
            'status' => 'aktif',
            'notes' => 'Anggota baru hasil open recruitment offline',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('members', [
            'nim' => '230101050',
            'name' => 'Doni Saputra',
        ]);
    }

    public function test_can_update_member_status(): void
    {
        $member = Member::create([
            'nim' => '220101090',
            'name' => 'Eko Prasetyo',
            'email' => 'eko@test.com',
            'division_id' => $this->divPemrograman->id,
            'batch_year' => '2024',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->superAdmin)->put(route('admin.members.updateStatus', $member), [
            'status' => 'alumni',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'status' => 'alumni',
        ]);
    }

    public function test_can_delete_member(): void
    {
        $member = Member::create([
            'nim' => '220101099',
            'name' => 'Fajar Nugraha',
            'email' => 'fajar@test.com',
            'division_id' => $this->divPemrograman->id,
            'batch_year' => '2024',
            'status' => 'non_aktif',
        ]);

        $response = $this->actingAs($this->superAdmin)->delete(route('admin.members.destroy', $member));
        $response->assertRedirect();
        $this->assertDatabaseMissing('members', [
            'id' => $member->id,
        ]);
    }
}
