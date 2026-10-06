<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Post;
use App\Models\Recruitment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UkmPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_homepage_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('UKM ILKOM');
        $response->assertSee('Divisi Pemrograman');
        $response->assertSee('Divisi Multimedia');
        $response->assertSee('Divisi Internet of Things');
        $response->assertSee('Divisi Cyber Security');
    }

    public function test_division_channel_loads_with_bio_and_adviser()
    {
        $response = $this->get('/divisi/pemrograman');
        $response->assertStatus(200);
        $response->assertSee('Divisi Pemrograman');
        $response->assertSee('Dosen Pembina');
        $response->assertSee('Ketua Divisi');
    }

    public function test_publications_feed_and_detail_page()
    {
        $post = Post::published()->first();
        $this->assertNotNull($post);

        $response = $this->get('/berita');
        $response->assertStatus(200);
        $response->assertSee($post->title);

        $detailResponse = $this->get('/berita/' . $post->slug);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee($post->title);
    }

    public function test_recruitment_submission_and_status_check()
    {
        $division = Division::first();

        $formData = [
            'full_name' => 'Ahmad Fakhri Pratama',
            'nim' => '240103999',
            'email' => 'ahmad.fakhri@example.com',
            'phone_whatsapp' => '081299998888',
            'semester' => 3,
            'class_group' => 'IF-3B',
            'first_choice_division_id' => $division->id,
            'reason_to_join' => 'Saya sangat bersemangat mempelajari arsitektur web modern dan berkontribusi di divisi.',
            'portfolio_url' => 'https://github.com/ahmadfakhri',
        ];

        $response = $this->post('/pendaftaran', $formData);
        $response->assertRedirect();

        $applicant = Recruitment::where('nim', '240103999')->first();
        $this->assertNotNull($applicant);
        $this->assertEquals('Ahmad Fakhri Pratama', $applicant->full_name);
        $this->assertStringStartsWith('UKM-', $applicant->registration_code);

        // Check Status Verification Page
        $statusResponse = $this->get('/pendaftaran/cek-status?search=240103999');
        $statusResponse->assertStatus(200);
        $statusResponse->assertSee('Ahmad Fakhri Pratama');
        $statusResponse->assertSee('Menunggu Verifikasi');
    }

    public function test_admin_guest_protection()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_super_admin_login_and_access_dashboard()
    {
        $admin = User::where('role', 'super_admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Ringkasan Dashboard');
        $response->assertSee('Status Pendaftaran Terpusat');
    }

    public function test_division_admin_can_publish_post()
    {
        $divAdmin = User::where('email', 'pemrograman@ukmilkom.id')->first();
        $this->assertNotNull($divAdmin);

        $postData = [
            'title' => 'Tutorial Unit Testing di Laravel 10',
            'excerpt' => 'Panduan praktis menulis automated tests untuk aplikasi web kampus.',
            'content' => '<p>Automated test memastikan seluruh fitur berfungsi dengan baik tanpa regresi.</p>',
            'category' => 'tutorial',
            'status' => 'published',
        ];

        $response = $this->actingAs($divAdmin)->post('/admin/posts', $postData);
        $response->assertRedirect('/admin/posts');

        $this->assertDatabaseHas('posts', [
            'title' => 'Tutorial Unit Testing di Laravel 10',
            'division_id' => $divAdmin->division_id,
            'author_id' => $divAdmin->id,
        ]);
    }
}
