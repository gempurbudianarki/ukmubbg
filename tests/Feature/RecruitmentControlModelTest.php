<?php

namespace Tests\Feature;

use App\Models\AttendanceSession;
use App\Models\Division;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecruitmentControlModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_division_recruitment_control_fields(): void
    {
        $division = Division::create([
            'slug' => 'test-divisi',
            'name' => 'Divisi Test',
            'tagline' => 'Tagline',
            'description' => 'Deskripsi',
            'focus_topics' => ['PHP'],
            'icon_svg' => '<svg></svg>',
            'color_accent' => '#2563eb',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'adviser_name' => 'Dosen Pembina',
            'adviser_title' => 'M.Kom',
            'leader_name' => 'Ketua',
            'leader_nim' => '210103001',
            'leader_bio' => 'Bio',
            'is_recruitment_open' => true,
            'recruitment_quota' => 25,
            'recruitment_notes' => 'Tersedia 25 kuota',
        ]);

        $this->assertDatabaseHas('divisions', [
            'id' => $division->id,
            'is_recruitment_open' => 1,
            'recruitment_quota' => 25,
            'recruitment_notes' => 'Tersedia 25 kuota',
        ]);
    }

    public function test_attendance_session_formal_metadata_fields(): void
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        $session = AttendanceSession::create([
            'division_id' => null,
            'created_by' => $user->id,
            'title' => 'Pertemuan Riset Algoritma',
            'day_name' => 'Sabtu',
            'session_date' => '2026-10-10',
            'time_start' => '09:00:00',
            'time_end' => '12:00:00',
            'session_type' => 'riset_rutin',
            'location' => 'Lab Komputer 3',
            'topic_material' => 'Dynamic Programming & Graph Traversal',
            'learning_outcomes' => 'Mahasiswa memahami algoritma Dijkstra dan implementasi DFS/BFS.',
            'instructor_name' => 'Muhammad Rayhan Fajar',
            'notes' => 'Membawa laptop masing-masing.',
            'status' => 'open',
        ]);

        $this->assertDatabaseHas('attendance_sessions', [
            'id' => $session->id,
            'day_name' => 'Sabtu',
            'session_type' => 'riset_rutin',
            'topic_material' => 'Dynamic Programming & Graph Traversal',
            'instructor_name' => 'Muhammad Rayhan Fajar',
        ]);
    }
}
