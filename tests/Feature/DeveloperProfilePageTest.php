<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeveloperProfilePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_profile_page_loads_successfully()
    {
        $response = $this->get(route('developer'));

        $response->assertStatus(200);
        $response->assertSee('Gempur Budi Anarki');
        $response->assertSee('Software Architect');
        $response->assertSee('Cybersecurity Researcher');
        $response->assertSee('Universitas Bina Bangsa Getsempena');
        $response->assertSee('KOMDIGI-CSIRT');
        $response->assertSee('Ashhabul Yamin');
        $response->assertSee('https://gempurbudianarki.space');
        $response->assertSee('https://github.com/gempurbudianarki');
        $response->assertSee('https://www.instagram.com/gmprbdarki/');
    }

    public function test_footer_contains_developer_link()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee(route('developer'));
        $response->assertSee('Tentang Developer');
    }
}
