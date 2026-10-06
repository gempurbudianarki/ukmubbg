<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\AttendanceSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    private function getStudentData(): array
    {
        $user = Auth::user();
        $recruitment = $user->recruitment()->with(['firstChoiceDivision', 'secondChoiceDivision'])->latest()->first();
        $member = $user->member()->with('division')->first();

        // Division to focus on: member division or first choice division
        $division = $member?->division ?? $recruitment?->firstChoiceDivision;

        // Acceptance status check
        $isAccepted = ($member !== null) || ($recruitment?->status === 'accepted');

        // Attendance stats
        $attendanceStats = [
            'total_sessions' => 0,
            'attended_count' => 0,
            'permission_count' => 0,
            'sick_count' => 0,
            'absent_count' => 0,
            'percentage' => 0,
            'recent_logs' => collect(),
            'all_logs' => collect(),
        ];

        if ($member && $division) {
            $totalSessions = AttendanceSession::where('division_id', $division->id)->count();
            $allLogs = AttendanceLog::where('member_id', $member->id)
                ->with('session')
                ->latest()
                ->get();

            $attendedCount = $allLogs->whereIn('status', ['hadir', 'terlambat'])->count();
            $permissionCount = $allLogs->where('status', 'izin')->count();
            $sickCount = $allLogs->where('status', 'sakit')->count();
            $absentCount = $allLogs->where('status', 'alpa')->count();

            $percentage = $totalSessions > 0 ? round(($attendedCount / $totalSessions) * 100) : 0;

            $attendanceStats = [
                'total_sessions' => $totalSessions,
                'attended_count' => $attendedCount,
                'permission_count' => $permissionCount,
                'sick_count' => $sickCount,
                'absent_count' => $absentCount,
                'percentage' => $percentage,
                'recent_logs' => $allLogs->take(5),
                'all_logs' => $allLogs,
            ];
        }

        // Division syllabus / academic topics
        $syllabus = $division?->focus_topics ?? [];

        return compact('user', 'recruitment', 'member', 'division', 'attendanceStats', 'syllabus', 'isAccepted');
    }

    public function index()
    {
        return view('student.dashboard', $this->getStudentData());
    }

    public function kta()
    {
        return view('student.kta', $this->getStudentData());
    }

    public function presensi()
    {
        return view('student.presensi', $this->getStudentData());
    }

    public function silabus()
    {
        return view('student.silabus', $this->getStudentData());
    }
}
