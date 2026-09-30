<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomepageExperienceTest extends TestCase
{
    public function test_homepage_displays_featured_projects_and_upcoming_events()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewHas('featuredProjects');
        $response->assertViewHas('upcomingEvents');
        $response->assertViewHas('divisions');
        $response->assertSee('Divisi Spesialisasi');
        $response->assertSee('Karya & Inovasi Mahasiswa', false);
    }
}
