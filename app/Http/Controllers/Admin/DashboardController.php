<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Post;
use App\Models\Recruitment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        if ($isSuperAdmin) {
            $totalPosts = Post::count();
            $totalApplicants = Recruitment::count();
            $pendingApplicants = Recruitment::where('status', 'pending')->count();
            $acceptedApplicants = Recruitment::where('status', 'accepted')->count();

            $recentApplicants = Recruitment::with(['firstChoiceDivision'])
                ->latest()
                ->take(5)
                ->get();

            $divisionsStats = Division::withCount(['posts', 'firstChoiceApplicants'])->get();
            $totalMembers = \App\Models\Member::where('status', 'aktif')->count();
            $totalSessions = \App\Models\AttendanceSession::count();
            $totalProjects = \App\Models\Project::count();
        } else {
            $divisionId = $user->division_id;
            $totalPosts = Post::where('division_id', $divisionId)->count();
            $totalApplicants = Recruitment::where('first_choice_division_id', $divisionId)->count();
            $pendingApplicants = Recruitment::where('first_choice_division_id', $divisionId)->where('status', 'pending')->count();
            $acceptedApplicants = Recruitment::where('first_choice_division_id', $divisionId)->where('status', 'accepted')->count();
            $totalMembers = \App\Models\Member::where('division_id', $divisionId)->where('status', 'aktif')->count();
            $totalSessions = \App\Models\AttendanceSession::where('division_id', $divisionId)->count();
            $totalProjects = \App\Models\Project::where('division_id', $divisionId)->count();

            $recentApplicants = Recruitment::with(['firstChoiceDivision'])
                ->where('first_choice_division_id', $divisionId)
                ->latest()
                ->take(5)
                ->get();

            $divisionsStats = Division::where('id', $divisionId)->withCount(['posts', 'firstChoiceApplicants'])->get();
        }

        $recruitmentStatus = Setting::get('recruitment_status', 'open');

        return view('admin.dashboard', compact(
            'user',
            'isSuperAdmin',
            'totalPosts',
            'totalApplicants',
            'pendingApplicants',
            'acceptedApplicants',
            'totalMembers',
            'totalSessions',
            'totalProjects',
            'recentApplicants',
            'divisionsStats',
            'recruitmentStatus'
        ));
    }

    public function toggleRecruitment(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses terbatas untuk Super Admin.');
        }

        $current = Setting::get('recruitment_status', 'open');
        $newStatus = ($current === 'open') ? 'closed' : 'open';
        Setting::set('recruitment_status', $newStatus);

        return back()->with('success', 'Status pendaftaran berhasil diubah menjadi: ' . strtoupper($newStatus));
    }
}
