<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Division;
use App\Models\Certificate;
use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EndToEndPortalTest extends TestCase
{
    public function test_complete_user_and_admin_ecosystem_journey()
    {
        Storage::fake('public');

        // 1. Visitor views Homepage
        $home = $this->get('/');
        $home->assertStatus(200);
        $home->assertSee('UKM ILMU KOMPUTER');
        $home->assertSee('Divisi Spesialisasi');

        // 2. Visitor views Projects Showcase
        $projects = $this->get(route('projects.index'));
        $projects->assertStatus(200);
        $projects->assertSee('Karya & Inovasi Mahasiswa', false);

        // 3. Visitor views Events
        $events = $this->get(route('events.index'));
        $events->assertStatus(200);
        $events->assertSee('Agenda & Workshop', false);

        // 4. Visitor views Officers
        $officers = $this->get(route('officers.index'));
        $officers->assertStatus(200);
        $officers->assertSee('Struktur Kepengurusan', false);

        // 5. Visitor views Gallery
        $gallery = $this->get(route('galleries.index'));
        $gallery->assertStatus(200);
        $gallery->assertSee('Galeri & Dokumentasi', false);

        // 6. Visitor checks Certificate
        $cert = Certificate::first();
        $this->assertNotNull($cert);
        $verify = $this->get(route('certificates.verify', ['code' => $cert->certificate_code]));
        $verify->assertStatus(200);
        $verify->assertSee('TERVERIFIKASI RESMI');

        // 7. Visitor submits recruitment application
        Setting::where('key_name', 'recruitment_status')->update(['value' => 'open']);
        $div = Division::where('slug', 'pemrograman')->first();

        $submit = $this->post(route('recruitment.store'), [
            'full_name' => 'Calon Anggota Hebat',
            'nim' => '230109999',
            'email' => 'calon@kampus.ac.id',
            'phone_whatsapp' => '081299998888',
            'semester' => 2,
            'class_group' => 'TI-2B',
            'first_choice_division_id' => $div->id,
            'reason_to_join' => 'Sangat bersemangat memperdalam web development dan berkontribusi aktif.',
            'portfolio_url' => 'https://github.com/calonhebat',
            'file_ktm' => UploadedFile::fake()->image('ktm_calon.jpg'),
        ]);

        $submit->assertRedirect();
        $this->assertDatabaseHas('recruitments', ['nim' => '230109999']);

        // 8. Visitor checks recruitment status
        $statusCheck = $this->get(route('recruitment.status', ['search' => '230109999']));
        $statusCheck->assertStatus(200);
        $statusCheck->assertSee('Calon Anggota Hebat');
        $statusCheck->assertSee('TAHAP 1');

        // 9. Super Admin signs in and reviews applicant
        $superAdmin = User::where('role', 'super_admin')->first();
        $this->actingAs($superAdmin);

        $dash = $this->get(route('admin.dashboard'));
        $dash->assertStatus(200);
        $dash->assertSee('Ringkasan Dashboard');

        $applicant = \App\Models\Recruitment::where('nim', '230109999')->first();
        $this->assertNotNull($applicant);

        // Advance to interview stage
        $updateStage = $this->put(route('admin.recruitment.updateStatus', $applicant->id), [
            'status' => 'interview',
            'selection_stage' => 'wawancara',
            'interview_schedule' => now()->addDays(3)->format('Y-m-d\TH:i'),
            'interview_location' => 'Lab Software Engineering Lt. 3',
            'admin_notes' => 'Harap bawa laptop dan tunjukkan prototype portofolio Anda.',
        ]);
        $updateStage->assertRedirect();

        $this->assertDatabaseHas('recruitments', [
            'id' => $applicant->id,
            'status' => 'interview',
            'selection_stage' => 'wawancara',
            'interview_location' => 'Lab Software Engineering Lt. 3',
        ]);
    }
}
