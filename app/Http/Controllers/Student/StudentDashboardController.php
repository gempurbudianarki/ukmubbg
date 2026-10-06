<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\AttendanceSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recruitment = $user->recruitment()->with(['firstChoiceDivision', 'secondChoiceDivision'])->latest()->first();
        $member = $user->member()->with('division')->first();

        // Division to focus on: member division or first choice division
        $division = $member?->division ?? $recruitment?->firstChoiceDivision;

        // Attendance stats if official member
        $attendanceStats = [
            'total_sessions' => 0,
            'attended_count' => 0,
            'percentage' => 0,
            'recent_logs' => collect(),
        ];

        if ($member && $division) {
            $totalSessions = AttendanceSession::where('division_id', $division->id)->count();
            $attendedCount = AttendanceLog::where('member_id', $member->id)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->count();

            $percentage = $totalSessions > 0 ? round(($attendedCount / $totalSessions) * 100) : 0;

            $recentLogs = AttendanceLog::where('member_id', $member->id)
                ->with('session')
                ->latest()
                ->take(5)
                ->get();

            $attendanceStats = [
                'total_sessions' => $totalSessions,
                'attended_count' => $attendedCount,
                'percentage' => $percentage,
                'recent_logs' => $recentLogs,
            ];
        }

        // Acceptance status check
        $isAccepted = ($member !== null) || ($recruitment?->status === 'accepted');

        // Division syllabus / academic topics
        $syllabus = $division?->focus_topics ?? [];

        return view('student.dashboard', compact('user', 'recruitment', 'member', 'division', 'attendanceStats', 'syllabus', 'isAccepted'));
    }
}
