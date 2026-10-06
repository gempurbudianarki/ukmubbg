<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProfilePhotoVisibilityTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Division $division;

    protected function setUp(): void
    {
        parent::setUp();

        $this->division = Division::create([
            'slug' => 'multimedia-broadcasting',
            'name' => 'Divisi Multimedia',
            'tagline' => 'Creative & Visuals',
            'description' => 'Fokus desain dan video',
            'focus_topics' => ['Figma', 'Blender', 'Premiere Pro'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#8b5cf6',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen Multimedia',
            'adviser_title' => 'M.Sn',
            'leader_name' => 'Ketua Multimedia',
            'leader_nim' => '220102001',
            'leader_bio' => 'Bio',
            'is_recruitment_open' => true,
        ]);

        $this->admin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@ukmilkom.id',
            'password' => bcrypt('admin123'),
            'role' => 'super_admin',
        ]);
    }

    public function test_admin_can_see_applicant_photos_in_recruitment_list_and_detail(): void
    {
        $applicant = Recruitment::create([
            'registration_code' => 'UKM-2026-FOTO1',
            'full_name' => 'Nadia Salsabila',
            'email' => 'nadia@student.ac.id',
            'nim' => '2401050011',
            'first_choice_division_id' => $this->division->id,
            'semester' => 2,
            'phone_whatsapp' => '081234567899',
            'reason_to_join' => 'Ingin belajar motion graphic dan 3D visual.',
            'profile_photo' => 'avatars/nadia_photo.jpg',
            'github_url' => 'https://github.com/nadiasalsa',
            'status' => 'pending',
            'selection_stage' => 'administrasi',
        ]);

        // Recruitment list
        $responseList = $this->actingAs($this->admin)->get(route('admin.recruitment.index'));
        $responseList->assertStatus(200);
        $responseList->assertSee('Nadia Salsabila');
        $responseList->assertSee('avatars/nadia_photo.jpg');

        // Recruitment detail
        $responseDetail = $this->actingAs($this->admin)->get(route('admin.recruitment.show', $applicant->id));
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('Nadia Salsabila');
        $responseDetail->assertSee('avatars/nadia_photo.jpg');
        $responseDetail->assertSee('https://github.com/nadiasalsa');
    }

    public function test_admin_can_see_member_photos_in_members_list(): void
    {
        $member = Member::create([
            'division_id' => $this->division->id,
            'name' => 'Reza Pahlevi',
            'nim' => '2301040022',
            'email' => 'reza@student.ac.id',
            'phone_number' => '081298765432',
            'status' => 'aktif',
            'avatar' => 'avatars/reza_member.jpg',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.members.index'));
        $response->assertStatus(200);
        $response->assertSee('Reza Pahlevi');
        $response->assertSee('avatars/reza_member.jpg');
    }

    public function test_converting_applicant_to_member_copies_user_id_and_avatar(): void
    {
        $user = User::create([
            'name' => 'Fahmi Hidayat',
            'email' => 'fahmi@student.ac.id',
            'password' => bcrypt('secret123'),
            'role' => 'member',
            'nim' => '2401030033',
            'avatar' => 'avatars/fahmi_photo.jpg',
        ]);

        $applicant = Recruitment::create([
            'user_id' => $user->id,
            'registration_code' => 'UKM-2026-FAHMI',
            'full_name' => $user->name,
            'email' => $user->email,
            'nim' => $user->nim,
            'first_choice_division_id' => $this->division->id,
            'semester' => 3,
            'phone_whatsapp' => '081233445566',
            'reason_to_join' => 'Mau belajar dan berkontribusi.',
            'profile_photo' => $user->avatar,
            'status' => 'accepted',
            'selection_stage' => 'diterima',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.recruitment.convertToMember', $applicant->id), [
            'batch_year' => '2026',
        ]);

        $response->assertRedirect();

        $member = Member::where('nim', '2401030033')->first();
        $this->assertNotNull($member);
        $this->assertEquals($user->id, $member->user_id);
        $this->assertEquals('avatars/fahmi_photo.jpg', $member->avatar);
    }
}
