<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecruitmentConversionTest extends TestCase
{
    use RefreshDatabase;

    public function test_accepted_applicant_can_be_converted_to_official_member(): void
    {
        $division = Division::create([
            'slug' => 'cyber-security',
            'name' => 'Divisi Cyber Security',
            'tagline' => 'Ethical Hacking',
            'description' => 'Cyber security and pentesting',
            'focus_topics' => ['OWASP', 'CTF'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#f43f5e',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen Cyber',
            'adviser_title' => 'M.Cs',
            'leader_name' => 'Ketua Cyber',
            'leader_nim' => '210103003',
            'leader_bio' => 'Bio',
        ]);

        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        $applicant = Recruitment::create([
            'registration_code' => 'REG-CYBER-001',
            'full_name' => 'Rian Hidayat',
            'nim' => '230103088',
            'email' => 'rian@test.com',
            'phone_whatsapp' => '0812999888',
            'semester' => 3,
            'class_group' => 'TI-3B',
            'first_choice_division_id' => $division->id,
            'reason_to_join' => 'Ingin belajar pentesting',
            'status' => 'accepted',
            'selection_stage' => 'diterima',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.recruitment.convertToMember', $applicant));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('members', [
            'nim' => '230103088',
            'name' => 'Rian Hidayat',
            'division_id' => $division->id,
            'recruitment_id' => $applicant->id,
            'status' => 'aktif',
        ]);

        // Attempting to convert again should notify that member already exists without creating duplicate
        $secondResponse = $this->actingAs($admin)
            ->post(route('admin.recruitment.convertToMember', $applicant));

        $secondResponse->assertRedirect();
        $this->assertEquals(1, Member::where('nim', '230103088')->count());
    }

    public function test_non_accepted_applicant_cannot_be_converted(): void
    {
        $division = Division::create([
            'slug' => 'multimedia',
            'name' => 'Divisi Multimedia',
            'tagline' => 'Creative',
            'description' => 'Creative multimedia',
            'focus_topics' => ['Blender'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#8b5cf6',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen M',
            'adviser_title' => 'M.Ds',
            'leader_name' => 'Ketua M',
            'leader_nim' => '210103004',
            'leader_bio' => 'Bio',
        ]);

        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        $pendingApplicant = Recruitment::create([
            'registration_code' => 'REG-MM-002',
            'full_name' => 'Gita Gutawa',
            'nim' => '230103099',
            'email' => 'gita@test.com',
            'phone_whatsapp' => '0812999777',
            'semester' => 1,
            'class_group' => 'TI-1A',
            'first_choice_division_id' => $division->id,
            'reason_to_join' => 'Design',
            'status' => 'pending',
            'selection_stage' => 'administrasi',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.recruitment.convertToMember', $pendingApplicant));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('members', [
            'nim' => '230103099',
        ]);
    }
}
