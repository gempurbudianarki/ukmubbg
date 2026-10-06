<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentDashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    protected Division $division;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->division = Division::create([
            'slug' => 'pemrograman-web',
            'name' => 'Divisi Pemrograman Web',
            'tagline' => 'Coding and Innovation',
            'description' => 'Fokus rekayasa web',
            'focus_topics' => ['Laravel Framework', 'REST API', 'Modern Javascript'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#2563eb',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen Pembina',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua Pemrograman',
            'leader_nim' => '220101001',
            'leader_bio' => 'Bio',
            'is_recruitment_open' => true,
        ]);

        $this->student = User::create([
            'name' => 'Dimas Arya Mahasiswa',
            'email' => 'dimas@student.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'member',
            'nim' => '2401019999',
            'phone_number' => '081234567899',
            'avatar' => 'avatars/dimas.jpg',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('student.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_student_can_view_dashboard_with_stepper_and_kta(): void
    {
        $recruitment = Recruitment::create([
            'user_id' => $this->student->id,
            'registration_code' => 'UKM-2026-DIMAS',
            'full_name' => $this->student->name,
            'email' => $this->student->email,
            'nim' => $this->student->nim,
            'first_choice_division_id' => $this->division->id,
            'semester' => 3,
            'phone_whatsapp' => '081234567899',
            'reason_to_join' => 'Mau belajar pemrograman web dan berkontribusi.',
            'profile_photo' => $this->student->avatar,
            'status' => 'interview',
            'selection_stage' => 'wawancara',
            'interview_schedule' => now()->addDays(2),
            'interview_location' => 'Lab Komputer 3',
        ]);

        $response = $this->actingAs($this->student)->get(route('student.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dimas Arya Mahasiswa');
        $response->assertSee('2401019999');
        $response->assertSee('Divisi Pemrograman Web');
        $response->assertSee('Tahap Wawancara');
        $response->assertSee('UKM-2026-DIMAS');
        $response->assertSee('Kartu Tanda Anggota');
        $response->assertSee('Laravel Framework');
    }
}
