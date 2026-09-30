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

        return view('home.index', compact(
            'divisions',
            'latestPosts',
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
