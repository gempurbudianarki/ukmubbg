<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Division;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RecruitmentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_can_submit_registration_with_ktm_upload()
    {
        Storage::fake('public');
        Setting::where('key_name', 'recruitment_status')->update(['value' => 'open']);

        $division = Division::first();

        $response = $this->post(route('recruitment.store'), [
            'full_name' => 'Bintang Ramadhan',
            'nim' => '220104012',
            'email' => 'bintang@kampus.ac.id',
            'phone_whatsapp' => '081298765432',
            'semester' => 3,
            'class_group' => 'TI-3A',
            'first_choice_division_id' => $division->id,
            'reason_to_join' => 'Ingin memperdalam keahlian rekayasa sistem dan riset kolaboratif.',
            'github_url' => 'https://github.com/bintangramadhan',
            'portfolio_url' => 'https://github.com/bintangramadhan',
            'profile_photo' => UploadedFile::fake()->image('pasfoto.jpg'),
            'file_ktm' => UploadedFile::fake()->image('ktm.jpg'),
            'file_cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('recruitments', [
            'nim' => '220104012',
            'selection_stage' => 'administrasi',
        ]);
    }

    public function test_check_availability_endpoint_returns_valid_json()
    {
        // 1. Fresh NIM is available
        $resNim = $this->getJson(route('recruitment.checkAvailability', ['field' => 'nim', 'value' => '99999999']));
        $resNim->assertOk()->assertJson(['available' => true]);

        // 2. Existing NIM is unavailable
        \App\Models\Recruitment::create([
            'user_id' => null,
            'full_name' => 'Existing Student',
            'nim' => '88888888',
            'email' => 'existing@kampus.ac.id',
            'phone_whatsapp' => '08123456789',
            'semester' => 3,
            'first_choice_division_id' => Division::first()->id,
            'reason_to_join' => 'Motivasi yang cukup panjang untuk recruitment.',
            'registration_code' => 'REG-88888888',
            'selection_stage' => 'administrasi',
        ]);
        $resNimTaken = $this->getJson(route('recruitment.checkAvailability', ['field' => 'nim', 'value' => '88888888']));
        $resNimTaken->assertOk()->assertJson(['available' => false]);

        // 3. Existing User Email is unavailable
        \App\Models\User::factory()->create([
            'email' => 'terdaftar@kampus.ac.id',
        ]);
        $resEmailTaken = $this->getJson(route('recruitment.checkAvailability', ['field' => 'email', 'value' => 'terdaftar@kampus.ac.id']));
        $resEmailTaken->assertOk()->assertJson(['available' => false]);
    }

    public function test_can_submit_registration_with_all_files_photo_ktm_cv()
    {
        Storage::fake('public');
        Setting::where('key_name', 'recruitment_status')->update(['value' => 'open']);
        $division = Division::first();

        $response = $this->post(route('recruitment.store'), [
            'full_name' => 'Citra Lestari',
            'nim' => '230109988',
            'email' => 'citra@kampus.ac.id',
            'phone_whatsapp' => '085298761111',
            'semester' => 2,
            'class_group' => 'IF-2B',
            'first_choice_division_id' => $division->id,
            'reason_to_join' => 'Sangat tertarik mengembangkan riset dan kontribusi aktif di divisi.',
            'github_url' => 'https://github.com/citralestari',
            'profile_photo' => UploadedFile::fake()->image('pasfoto.jpg'),
            'file_ktm' => UploadedFile::fake()->image('ktm.jpg'),
            'file_cv' => UploadedFile::fake()->create('curriculum_vitae.pdf', 500, 'application/pdf'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('recruitments', [
            'nim' => '230109988',
            'email' => 'citra@kampus.ac.id',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'citra@kampus.ac.id',
        ]);
    }
}
