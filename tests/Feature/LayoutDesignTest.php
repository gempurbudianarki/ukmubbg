<?php

namespace Tests\Feature;

use Tests\TestCase;

class LayoutDesignTest extends TestCase
{
    public function test_homepage_loads_refined_navbar_and_links()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('UKM ILMU KOMPUTER');
        $response->assertSee('Karya');
        $response->assertSee('Agenda');
        $response->assertSee('Pengurus');
        $response->assertSee('Galeri');
        $response->assertSee('Verifikasi');
    }
}
