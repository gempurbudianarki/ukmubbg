<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Certificate;

class CertificateVerificationTest extends TestCase
{
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
}
