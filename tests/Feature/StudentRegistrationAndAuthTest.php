<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Recruitment;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentRegistrationAndAuthTest extends TestCase
{
    use RefreshDatabase;

    protected Division $division;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->division = Division::create([
            'slug' => 'pemrograman-web',
            'name' => 'Divisi Pemrograman Web',
            'tagline' => 'Coding and Innovation',
            'description' => 'Fokus rekayasa web',
            'focus_topics' => ['PHP', 'Laravel'],
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

        Setting::set('recruitment_start_date', Carbon::yesterday()->format('Y-m-d H:i'));
        Setting::set('recruitment_end_date', Carbon::tomorrow()->format('Y-m-d H:i'));
        Setting::set('recruitment_status', 'open');
    }

    public function test_student_registers_and_gets_account_created_with_auto_login(): void
    {
        $avatarFile = UploadedFile::fake()->image('pasfoto_alief.jpg', 400, 400);

        $payload = [
            'full_name' => 'Alief Mahasiswa',
            'nim' => '2401010077',
            'email' => 'alief@student.ac.id',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
            'phone_whatsapp' => '081298765432',
            'semester' => 3,
            'first_choice_division_id' => $this->division->id,
            'reason_to_join' => 'Ingin memperdalam Laravel framework dan fullstack web.',
            'github_url' => 'https://github.com/aliefdev',
            'profile_photo' => $avatarFile,
        ];

        $response = $this->post(route('recruitment.store'), $payload);

        // Check user account created
        $user = User::where('email', 'alief@student.ac.id')->first();
        $this->assertNotNull($user);
        $this->assertEquals('member', $user->role);
        $this->assertEquals('2401010077', $user->nim);
        $this->assertEquals('https://github.com/aliefdev', $user->github_url);
        $this->assertNotNull($user->avatar);
        $this->assertTrue(Hash::check('secret12345', $user->password));

        // Check recruitment record created and linked
        $recruitment = Recruitment::where('email', 'alief@student.ac.id')->first();
        $this->assertNotNull($recruitment);
        $this->assertEquals($user->id, $recruitment->user_id);
        $this->assertEquals($user->avatar, $recruitment->profile_photo);
        $this->assertEquals('https://github.com/aliefdev', $recruitment->github_url);

        // Check auto login
        $this->assertAuthenticatedAs($user);

        // Redirects to student dashboard
        $response->assertRedirect(route('student.dashboard'));
    }

    public function test_smart_login_redirects_member_to_student_dashboard_and_admin_to_admin_dashboard(): void
    {
        // 1. Student / Member Login
        $memberUser = User::create([
            'name' => 'Citra Lestari',
            'email' => 'citra@student.ac.id',
            'password' => bcrypt('citra12345'),
            'role' => 'member',
            'nim' => '2401010088',
        ]);

        $responseMember = $this->post(route('login.post'), [
            'email' => 'citra@student.ac.id',
            'password' => 'citra12345',
        ]);

        $responseMember->assertRedirect(route('student.dashboard'));

        // Logout
        $this->post(route('logout'));

        // 2. Admin Login
        $adminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@ukmilkom.id',
            'password' => bcrypt('admin123'),
            'role' => 'super_admin',
        ]);

        $responseAdmin = $this->post(route('login.post'), [
            'email' => 'admin@ukmilkom.id',
            'password' => 'admin123',
        ]);

        $responseAdmin->assertRedirect(route('admin.dashboard'));
    }
}
