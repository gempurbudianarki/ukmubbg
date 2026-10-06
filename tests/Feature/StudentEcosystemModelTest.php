<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentEcosystemModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_have_student_member_fields_and_relations(): void
    {
        $user = User::create([
            'name' => 'Bintang Mahasiswa',
            'email' => 'bintang@student.ac.id',
            'password' => bcrypt('secret123'),
            'role' => 'member',
            'nim' => '2301010099',
            'phone_number' => '081234567890',
            'github_url' => 'https://github.com/bintangmhs',
            'avatar' => 'avatars/bintang.jpg',
        ]);

        $this->assertEquals('member', $user->role);
        $this->assertEquals('2301010099', $user->nim);
        $this->assertStringContainsString('bintang.jpg', $user->avatar_url);

        $division = Division::create([
            'name' => 'Divisi Pemrograman Web',
            'slug' => 'pemrograman-web',
            'tagline' => 'Coding and Innovation',
            'description' => 'Fokus rekayasa perangkat lunak dan web modern',
            'focus_topics' => ['Laravel', 'Vue.js', 'React'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#2563eb',
            'vision' => 'Visi Pemrograman',
            'mission' => 'Misi Pemrograman',
            'adviser_name' => 'Dosen Pembina',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua Divisi',
            'leader_nim' => '220101001',
            'leader_bio' => 'Bio Ketua',
        ]);

        $recruitment = Recruitment::create([
            'user_id' => $user->id,
            'registration_code' => Recruitment::generateCode(),
            'full_name' => $user->name,
            'email' => $user->email,
            'nim' => $user->nim,
            'first_choice_division_id' => $division->id,
            'semester' => 3,
            'class_group' => null, // Optional now!
            'phone_whatsapp' => '081234567890',
            'reason_to_join' => 'Mau belajar pemrograman web dan berkontribusi.',
            'profile_photo' => 'avatars/bintang.jpg',
            'github_url' => 'https://github.com/bintangmhs',
            'status' => 'pending',
        ]);

        $this->assertEquals($user->id, $recruitment->user->id);
        $this->assertEquals($recruitment->id, $user->recruitment->id);
        $this->assertStringContainsString('bintang.jpg', $recruitment->avatar_url);

        $member = Member::create([
            'user_id' => $user->id,
            'recruitment_id' => $recruitment->id,
            'division_id' => $division->id,
            'name' => $user->name,
            'nim' => $user->nim,
            'email' => $user->email,
            'phone_number' => '081234567890',
            'status' => 'aktif',
            'avatar' => 'avatars/bintang.jpg',
        ]);

        $this->assertEquals($user->id, $member->user->id);
        $this->assertEquals($member->id, $user->member->id);
        $this->assertStringContainsString('bintang.jpg', $member->avatar_url);
    }
}
