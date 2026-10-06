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

    public function test_applicant_sees_selection_stepper_interview_and_locked_kta(): void
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
        $response->assertSee('UKM-2026-DIMAS');
        // Sees applicant stepper and interview schedule
        $response->assertSee('Alur Seleksi Penerimaan Anggota');
        $response->assertSee('Tahap Wawancara');
        $response->assertSee('Lab Komputer 3');
        // KTA is in locked state until accepted
        $response->assertSee('Kartu Tanda Anggota (KTA) Belum Terbit');
        // Sidebar exists
        $response->assertSee('PORTAL MAHASISWA');
    }

    public function test_accepted_member_hides_stepper_and_displays_official_kta_and_metrics(): void
    {
        // Student is officially accepted & promoted to member
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
            'status' => 'accepted',
            'selection_stage' => 'diterima',
        ]);

        $member = Member::create([
            'user_id' => $this->student->id,
            'recruitment_id' => $recruitment->id,
            'division_id' => $this->division->id,
            'name' => $this->student->name,
            'nim' => $this->student->nim,
            'email' => $this->student->email,
            'phone_number' => '081234567899',
            'batch_year' => '2026',
            'status' => 'aktif',
            'avatar' => $this->student->avatar,
        ]);

        $response = $this->actingAs($this->student)->get(route('student.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dimas Arya Mahasiswa');
        $response->assertSee('STATUS: ANGGOTA AKTIF TERDAFTAR');
        // The recruitment stepper and interview schedule MUST be gone
        $response->assertDontSee('Alur Seleksi Penerimaan Anggota');
        $response->assertDontSee('Jadwal Wawancara Divisi Anda:');
        // The official KTA Digital is unlocked and rendered
        $response->assertSee('KARTU TANDA ANGGOTA');
        $response->assertSee('TERVERIFIKASI SISTEM');
        $response->assertSee('Cetak Dokumen KTA');
    }

    public function test_student_can_access_dedicated_kta_page(): void
    {
        $response = $this->actingAs($this->student)->get(route('student.kta'));
        $response->assertStatus(200);
        $response->assertSee('Kartu Tanda Anggota (KTA Digital)');
    }

    public function test_student_can_access_dedicated_presensi_page(): void
    {
        $response = $this->actingAs($this->student)->get(route('student.presensi'));
        $response->assertStatus(200);
        $response->assertSee('Presensi & Kehadiran Pertemuan');
        $response->assertSee('Riwayat Seluruh Sesi Pertemuan Divisi');
    }

    public function test_student_can_access_dedicated_silabus_page(): void
    {
        $response = $this->actingAs($this->student)->get(route('student.silabus'));
        $response->assertStatus(200);
        $response->assertSee('Silabus');
        $response->assertSee('Daftar Modul');
    }
}
