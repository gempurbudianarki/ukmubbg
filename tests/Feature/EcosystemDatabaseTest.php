<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use App\Models\Event;
use App\Models\Officer;
use App\Models\Gallery;
use App\Models\Certificate;
use App\Models\Division;

class EcosystemDatabaseTest extends TestCase
{
    public function test_new_ecosystem_tables_and_models_work()
    {
        $division = Division::first();
        $random = \Illuminate\Support\Str::random(5);
        $projectSlug = 'smart-campus-iot-gateway-' . $random;
        $eventSlug = 'workshop-ethical-hacking-' . $random;
        $certCode = 'CERT-TEST-' . $random;

        $project = Project::create([
            'division_id' => $division ? $division->id : null,
            'title' => 'Smart Campus IoT Gateway',
            'slug' => $projectSlug,
            'description' => 'Sistem monitoring energi kampus berbasis ESP32.',
            'author_names' => 'Dimas Bagus, Rizki Pratama',
            'tech_stack' => ['ESP32', 'MQTT', 'Laravel'],
            'demo_url' => 'https://iot.campus.ac.id',
            'repo_url' => 'https://github.com/ukm/iot-gateway',
            'is_featured' => true,
        ]);

        $this->assertDatabaseHas('projects', ['slug' => $projectSlug]);
        $this->assertEquals(['ESP32', 'MQTT', 'Laravel'], $project->fresh()->tech_stack);

        $event = Event::create([
            'title' => 'Workshop Ethical Hacking 101',
            'slug' => $eventSlug,
            'description' => 'Mengenal teknik penetration testing dan CTF dasar.',
            'event_date' => now()->addDays(7)->format('Y-m-d'),
            'time_start' => '09:00:00',
            'location_type' => 'offline',
            'location_venue' => 'Lab Terpadu Komputer Lt. 3',
            'status' => 'upcoming',
        ]);
        $this->assertDatabaseHas('events', ['slug' => $eventSlug]);

        $cert = Certificate::create([
            'certificate_code' => $certCode,
            'recipient_name' => 'Ahmad Fauzi',
            'recipient_nim' => '210103099',
            'event_name' => 'Workshop Fullstack Web Modern',
            'role_as' => 'Peserta',
            'issue_date' => now()->format('Y-m-d'),
        ]);
        $this->assertDatabaseHas('certificates', ['certificate_code' => $certCode]);
    }
}
