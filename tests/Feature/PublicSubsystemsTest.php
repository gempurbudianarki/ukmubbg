<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSubsystemsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_projects_page_loads_with_filters()
    {
        $response = $this->get(route('projects.index'));
        $response->assertStatus(200);
        $response->assertSee('Showcase Portofolio & Riset', false);
    }

    public function test_events_page_loads()
    {
        $response = $this->get(route('events.index'));
        $response->assertStatus(200);
        $response->assertSee('Agenda & Workshop', false);
    }

    public function test_officers_page_loads()
    {
        $response = $this->get(route('officers.index'));
        $response->assertStatus(200);
        $response->assertSee('Struktur Kepengurusan', false);
        $response->assertSee('Dewan Pembina & Badan Pengurus Harian (BPH)', false);
        $response->assertSee('Struktur Kepengurusan 4 Divisi Spesialisasi', false);
        $response->assertSee('Divisi Pemrograman', false);
        $response->assertSee('Divisi Multimedia', false);
        $response->assertSee('Divisi Internet of Things (IoT)', false);
        $response->assertSee('Divisi Cyber Security', false);
        $response->assertSee('Dosen Pembimbing Divisi Pemrograman', false);
    }

    public function test_homepage_shows_division_advisers()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Dosen Pembina Divisi Pemrograman', false);
        $response->assertSee('Dosen Pembina Divisi Multimedia', false);
        $response->assertSee('Ketua Divisi Mahasiswa', false);
        $response->assertSee('Dr. Ir. Hendra Saputra, M.Kom.', false);
        $response->assertSee('Rina Anggraini, S.Sn., M.Ds.', false);
    }

    public function test_gallery_page_loads()
    {
        $response = $this->get(route('galleries.index'));
        $response->assertStatus(200);
        $response->assertSee('Galeri & Dokumentasi', false);
    }
}
