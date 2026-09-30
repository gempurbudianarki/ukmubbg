<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Division;
use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RecruitmentWorkflowTest extends TestCase
{
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
            'portfolio_url' => 'https://github.com/bintangramadhan',
            'file_ktm' => UploadedFile::fake()->image('ktm.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('recruitments', [
            'nim' => '220104012',
            'selection_stage' => 'administrasi',
        ]);
    }
}
