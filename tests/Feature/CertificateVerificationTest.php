<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Certificate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CertificateVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_can_view_verification_page()
    {
        $response = $this->get(route('certificates.verify'));
        $response->assertStatus(200);
        $response->assertSee('Verifikasi E-Sertifikat & Anggota', false);
    }

    public function test_valid_certificate_code_returns_verified_status()
    {
        $cert = Certificate::first();
        $this->assertNotNull($cert);

        $response = $this->get(route('certificates.verify', ['code' => $cert->certificate_code]));
        $response->assertStatus(200);
        $response->assertSee($cert->recipient_name);
        $response->assertSee('TERVERIFIKASI RESMI', false);
    }

    public function test_invalid_certificate_shows_not_found_message()
    {
        $response = $this->get(route('certificates.verify', ['code' => 'INVALID-CODE-999']));
        $response->assertStatus(200);
        $response->assertSee('Tidak Ditemukan', false);
    }

    public function test_valid_member_nim_returns_verified_member_kta()
    {
        $member = \App\Models\Member::first();
        $this->assertNotNull($member);

        $response = $this->get(route('certificates.verify', ['code' => $member->nim]));
        $response->assertStatus(200);
        $response->assertSee($member->name);
        $response->assertSee($member->nim);
        $response->assertSee('KEANGGOTAAN TERVERIFIKASI & AKTIF', false);
        $response->assertSee('KARTU TANDA ANGGOTA UKM', false);
    }
}
