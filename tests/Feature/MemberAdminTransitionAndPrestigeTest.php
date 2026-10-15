<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\Recruitment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberAdminTransitionAndPrestigeTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_promoted_to_admin_preserves_history_and_gains_prestige_badge_and_dual_switcher(): void
    {
        Setting::set('recruitment_status', 'open');

        $superAdmin = User::create([
            'name' => 'Super Admin UKM',
            'email' => 'superadmin@ukmilkom.id',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
        ]);

        $divPemrograman = Division::create([
            'name' => 'Divisi Pemrograman',
            'slug' => 'pemrograman',
            'color_accent' => '#0284c7',
            'icon_class' => 'fa-laptop-code',
            'status' => 'active',
        ]);

        $divIot = Division::create([
            'name' => 'Divisi Internet of Things',
            'slug' => 'iot',
            'color_accent' => '#d97706',
            'icon_class' => 'fa-microchip',
            'status' => 'active',
        ]);

        // 1. Create a regular student member belonging to Divisi Pemrograman
        $studentUser = User::create([
            'name' => 'Ahmad Fajar',
            'email' => 'ahmad@student.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'member',
            'division_id' => $divPemrograman->id,
            'nim' => '2401010055',
        ]);

        $recruitment = Recruitment::create([
            'registration_code' => 'REG-2026-0055',
            'user_id' => $studentUser->id,
            'full_name' => 'Ahmad Fajar',
            'nim' => '2401010055',
            'email' => 'ahmad@student.ac.id',
            'phone_whatsapp' => '081234567890',
            'semester' => 3,
            'reason_to_join' => 'Motivasi riset dan belajar sistem perangkat lunak.',
            'batch' => 'Gelombang I',
            'first_choice_division_id' => $divPemrograman->id,
            'status' => 'accepted',
            'kta_number' => 'KTA-2026-0055',
        ]);

        $member = Member::create([
            'user_id' => $studentUser->id,
            'recruitment_id' => $recruitment->id,
            'nim' => '2401010055',
            'name' => 'Ahmad Fajar',
            'email' => 'ahmad@student.ac.id',
            'division_id' => $divPemrograman->id,
            'batch_year' => '2026',
            'status' => 'aktif',
            'join_date' => now()->toDateString(),
        ]);

        // Initially as member: KTA shows regular member title and no admin badge
        $this->actingAs($studentUser);
        $ktaResponse = $this->get(route('student.kta'));
        $ktaResponse->assertOk();
        $ktaResponse->assertSee('2401010055');
        $ktaResponse->assertDontSee('Lencana Pengurus');

        // 2. Super Admin assigns Ahmad as Admin of Divisi IoT
        $this->actingAs($superAdmin)->post(route('admin.divisions.assignAdmin', $divIot), [
            'user_id' => $studentUser->id,
        ])->assertSessionHas('success');

        $studentUser->refresh();
        $this->assertEquals('division_admin', $studentUser->role);
        $this->assertEquals($divIot->id, $studentUser->division_id);

        // Member table original division is preserved
        $member->refresh();
        $this->assertEquals($divPemrograman->id, $member->division_id);

        // 3. Now as Division Admin, student/kta displays leadership prestige badge
        $this->actingAs($studentUser);
        $ktaAdminResponse = $this->get(route('student.kta'));
        $ktaAdminResponse->assertOk();
        $ktaAdminResponse->assertSee('Lencana Pengurus');
        $ktaAdminResponse->assertSee('Admin Divisi');

        // 4. Student portal has switcher link to Admin CMS
        $studentDash = $this->get(route('student.dashboard'));
        $studentDash->assertOk();
        $studentDash->assertSee(route('admin.dashboard'));

        // 5. Admin portal has switcher link back to Student Portal
        $adminDash = $this->get(route('admin.dashboard'));
        $adminDash->assertOk();
        $adminDash->assertSee(route('student.dashboard'));

        // 6. Super Admin revokes admin rights
        $this->actingAs($superAdmin)->post(route('admin.divisions.removeAdmin', [$divIot, $studentUser]))
            ->assertSessionHas('success');

        $studentUser->refresh();
        $this->assertEquals('member', $studentUser->role);
        // Safely reverted to original member division
        $this->assertEquals($divPemrograman->id, $studentUser->division_id);

        // KTA reverts back to regular member title
        $this->actingAs($studentUser);
        $ktaReverted = $this->get(route('student.kta'));
        $ktaReverted->assertOk();
        $ktaReverted->assertDontSee('Lencana Pengurus');
    }
}
