<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentProfileEditTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected Division $division;
    protected Recruitment $recruitment;
    protected Member $member;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->division = Division::create([
            'slug' => 'pemrograman',
            'name' => 'Divisi Pemrograman',
            'tagline' => 'Coding',
            'description' => 'Desc',
            'focus_topics' => ['Laravel'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#2563eb',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua',
            'leader_nim' => '220101',
            'leader_bio' => 'Bio',
            'is_recruitment_open' => true,
        ]);

        $this->student = User::create([
            'name' => 'Bintang Pratama',
            'email' => 'bintang@student.ac.id',
            'password' => bcrypt('oldpassword123'),
            'role' => 'member',
            'nim' => '2401015555',
            'phone_number' => '081234567890',
            'github_url' => 'https://github.com/bintanglama',
            'avatar' => null,
        ]);

        $this->recruitment = Recruitment::create([
            'user_id' => $this->student->id,
            'registration_code' => 'UKM-2026-BINTANG',
            'full_name' => $this->student->name,
            'email' => $this->student->email,
            'nim' => $this->student->nim,
            'first_choice_division_id' => $this->division->id,
            'semester' => 3,
            'phone_whatsapp' => '081234567890',
            'reason_to_join' => 'Mau belajar pemrograman web dan berkontribusi.',
            'profile_photo' => null,
            'status' => 'accepted',
        ]);

        $this->member = Member::create([
            'user_id' => $this->student->id,
            'recruitment_id' => $this->recruitment->id,
            'division_id' => $this->division->id,
            'name' => $this->student->name,
            'nim' => $this->student->nim,
            'email' => $this->student->email,
            'phone_number' => '081234567890',
            'status' => 'aktif',
            'avatar' => null,
        ]);
    }

    public function test_student_can_view_edit_profile_page(): void
    {
        $response = $this->actingAs($this->student)->get(route('student.profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('Bintang Pratama');
        $response->assertSee('2401015555');
        $response->assertSee('bintang@student.ac.id');
    }

    public function test_student_can_update_profile_and_upload_new_avatar_synced_to_member_and_recruitment(): void
    {
        $newAvatar = UploadedFile::fake()->image('bintang_baru.jpg', 300, 300);

        $response = $this->actingAs($this->student)->put(route('student.profile.update'), [
            'name' => 'Bintang Pratama S.Kom',
            'phone_number' => '089988776655',
            'github_url' => 'https://github.com/bintangpratamareal',
            'avatar' => $newAvatar,
        ]);

        $response->assertRedirect(route('student.profile.edit'));
        $response->assertSessionHas('success');

        // Check user updated
        $this->student->refresh();
        $this->assertEquals('Bintang Pratama S.Kom', $this->student->name);
        $this->assertEquals('089988776655', $this->student->phone_number);
        $this->assertEquals('https://github.com/bintangpratamareal', $this->student->github_url);
        $this->assertNotNull($this->student->avatar);

        // Check recruitment and member records are also synced
        $this->recruitment->refresh();
        $this->assertEquals($this->student->avatar, $this->recruitment->profile_photo);
        $this->assertEquals('Bintang Pratama S.Kom', $this->recruitment->full_name);

        $this->member->refresh();
        $this->assertEquals($this->student->avatar, $this->member->avatar);
        $this->assertEquals('Bintang Pratama S.Kom', $this->member->name);
    }

    public function test_student_can_change_password(): void
    {
        $response = $this->actingAs($this->student)->put(route('student.profile.update'), [
            'name' => 'Bintang Pratama',
            'phone_number' => '081234567890',
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('student.profile.edit'));
        $response->assertSessionHas('success');

        $this->student->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->student->password));
    }
}
