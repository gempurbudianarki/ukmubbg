<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Post;
use App\Models\Recruitment;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $divisions = Division::withCount('posts')->get();
        $latestPosts = Post::with(['division', 'author'])
            ->published()
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'divisions_count' => $divisions->count(),
            'posts_count' => Post::published()->count(),
            'applicants_count' => Recruitment::count(),
            'active_members' => 120,
        ];

        $recruitmentStatus = Setting::get('recruitment_status', 'open');
        $recruitmentBatch = Setting::get('recruitment_batch', 'Gelombang I');
        $recruitmentDeadline = Setting::get('recruitment_deadline', '31 Oktober 2026');

        $featuredProjects = \App\Models\Project::with('division')
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $upcomingEvents = \App\Models\Event::with('division')
            ->where('status', 'upcoming')
            ->orderBy('event_date')
            ->take(3)
            ->get();

        return view('home.index', compact(
            'divisions',
            'latestPosts',
            'featuredProjects',
            'upcomingEvents',
            'stats',
            'recruitmentStatus',
            'recruitmentBatch',
            'recruitmentDeadline'
        ));
    }

    public function about()
    {
        $divisions = Division::all();
        return view('home.about', compact('divisions'));
    }
}
