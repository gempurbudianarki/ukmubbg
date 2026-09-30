<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicSubsystemsTest extends TestCase
{
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
    }

    public function test_gallery_page_loads()
    {
        $response = $this->get(route('galleries.index'));
        $response->assertStatus(200);
        $response->assertSee('Galeri & Dokumentasi', false);
    }
}
