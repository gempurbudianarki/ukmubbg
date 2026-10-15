<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\Recruitment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicDivisionE2ETest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_dynamic_division_lifecycle(): void
    {
        Setting::set('recruitment_status', 'open');

        $superAdmin = User::create([
            'name' => 'Super Admin UKM',
            'email' => 'superadmin@ukmilkom.id',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
        ]);

        // 1. Super Admin creates 5th division
        $this->actingAs($superAdmin)->post(route('admin.divisions.store'), [
            'name' => 'Divisi Quantum Computing',
            'tagline' => 'Riset Komputasi Kuantum',
            'description' => 'Eksplorasi algoritma qubit dan kriptografi kuantum.',
            'focus_topics_raw' => "Qiskit\nQuantum Circuits\nPost-Quantum Cryptography",
            'color_accent' => '#a855f7',
            'icon_class' => 'fa-atom',
            'recruitment_quota' => 10,
            'is_recruitment_open' => '1',
            'adviser_name' => 'Prof. Quantum',
            'adviser_title' => 'Dosen Fisika Komputasi',
            'leader_name' => 'Ketua Quantum',
            'leader_nim' => '240101009',
            'leader_bio' => 'Bio ketua.',
        ])->assertRedirect(route('admin.divisions.index'));

        $division = Division::where('slug', 'divisi-quantum-computing')->firstOrFail();
        $this->assertEquals('#a855f7', $division->color_accent);
        $this->assertEquals('fa-atom', $division->icon_class);

        // 2. Super Admin assigns a user as Division Admin
        $candidate = User::create([
            'name' => 'Quantum Admin',
            'email' => 'quantum@student.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'member',
        ]);

        $this->actingAs($superAdmin)->post(route('admin.divisions.assignAdmin', $division), [
            'user_id' => $candidate->id,
        ])->assertSessionHas('success');

        $this->assertEquals('division_admin', $candidate->fresh()->role);
        $this->assertEquals($division->id, $candidate->fresh()->division_id);

        // 3. New Division appears in Recruitment Wizard
        $wizard = $this->get(route('recruitment.index'));
        $wizard->assertOk();
        $wizard->assertSee('Divisi Quantum Computing');
        $wizard->assertSee('fa-atom');

        // 4. Register a member into this division
        $member = Member::create([
            'user_id' => $candidate->id,
            'nim' => '240101009',
            'name' => 'Mahasiswa Quantum',
            'email' => 'mhs@quantum.ac.id',
            'division_id' => $division->id,
            'batch_year' => '2026',
            'status' => 'aktif',
            'join_date' => now()->toDateString(),
        ]);

        // 5. Super Admin soft-deletes (archives) the division
        $this->actingAs($superAdmin)->delete(route('admin.divisions.destroy', $division))
            ->assertRedirect(route('admin.divisions.index'));

        $this->assertSoftDeleted('divisions', ['id' => $division->id]);

        // Historical member data remains completely intact
        $this->assertDatabaseHas('members', ['id' => $member->id, 'division_id' => $division->id]);

        // 6. Super Admin restores the division
        $this->actingAs($superAdmin)->post(route('admin.divisions.restore', $division->id))
            ->assertRedirect(route('admin.divisions.index'));

        $this->assertNotSoftDeleted('divisions', ['id' => $division->id]);
        $this->assertEquals('active', $division->fresh()->status);
    }
}
