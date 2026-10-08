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

        $activeSessions = collect();
        $activeSession = null;
        $activeSessionLog = null;

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

            $activeSessions = AttendanceSession::where(function ($q) use ($division) {
                $q->where('division_id', $division->id)->orWhereNull('division_id');
            })
            ->where('status', 'open')
            ->latest('session_date')
            ->get();

            $activeSession = $activeSessions->first();
            if ($activeSession) {
                $activeSessionLog = AttendanceLog::where('session_id', $activeSession->id)
                    ->where('member_id', $member->id)
                    ->first();
            }
        }

        // Division syllabus / academic topics
        $syllabus = $division?->focus_topics_list ?? [];

        // Division sessions / meeting archives
        $divisionSessions = collect();
        if ($division) {
            $divisionSessions = AttendanceSession::where('division_id', $division->id)
                ->orderByDesc('session_date')
                ->get();
        }

        // Active announcements for student's division or general UKM
        $announcements = \App\Models\Announcement::with(['author', 'division'])
            ->where(function ($q) use ($division) {
                if ($division) {
                    $q->where('division_id', $division->id)->orWhereNull('division_id');
                } else {
                    $q->whereNull('division_id');
                }
            })
            ->orderByDesc('is_pinned')
            ->latest()
            ->take(3)
            ->get();

        return compact(
            'user',
            'recruitment',
            'member',
            'division',
            'attendanceStats',
            'syllabus',
            'isAccepted',
            'activeSessions',
            'activeSession',
            'activeSessionLog',
            'divisionSessions',
            'announcements'
        );
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

    public function selfCheckin(Request $request)
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return back()->with('error', 'Akun Anda belum terdaftar sebagai anggota resmi UKM.');
        }

        $request->validate([
            'session_id' => 'required|exists:attendance_sessions,id',
            'passcode' => 'required|string',
            'notes' => 'nullable|string|max:255',
        ]);

        $session = AttendanceSession::findOrFail($request->session_id);

        if (!empty($session->division_id) && (int)$session->division_id !== (int)$member->division_id) {
            return back()->with('error', 'Sesi pertemuan ini khusus untuk divisi lain.');
        }

        if ($session->status !== 'open') {
            return back()->with('error', 'Sesi presensi ini telah ditutup oleh pengurus divisi.');
        }

        if (!$session->allow_self_checkin) {
            return back()->with('error', 'Sesi ini hanya menerima presensi langsung oleh ketua divisi.');
        }

        if ($session->isPasscodeExpired()) {
            return back()->with('error', 'Masa berlaku kode presensi sesi ini telah habis. Silakan hubungi ketua divisi.');
        }

        $inputPasscode = strtoupper(trim($request->passcode));
        $validPasscode = strtoupper(trim($session->passcode ?? ''));

        if (empty($validPasscode) || $inputPasscode !== $validPasscode) {
            return back()->with('error', 'Password / Kode presensi salah! Silakan tanyakan kode sesi kepada ketua divisi.');
        }

        $log = AttendanceLog::firstOrNew([
            'session_id' => $session->id,
            'member_id' => $member->id,
        ]);

        $log->status = 'hadir';
        $log->checkin_type = 'self';
        $log->checked_in_at = now();
        if ($request->filled('notes')) {
            $log->notes = $request->notes;
        }
        $log->save();

        return back()->with('success', 'Presensi mandiri berhasil! Anda tercatat HADIR pada sesi "' . $session->title . '".');
    }

    public function submitPermission(Request $request)
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return back()->with('error', 'Akun Anda belum terdaftar sebagai anggota resmi UKM.');
        }

        $request->validate([
            'session_id' => 'required|exists:attendance_sessions,id',
            'status' => 'required|in:izin,sakit',
            'notes' => 'required|string|max:255',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ], [
            'status.required' => 'Pilih jenis pengajuan (Izin atau Sakit).',
            'notes.required' => 'Alasan / keterangan izin wajib diisi.',
            'attachment.max' => 'Ukuran lampiran bukti maksimal 2MB.',
        ]);

        $session = AttendanceSession::findOrFail($request->session_id);

        if (!empty($session->division_id) && (int)$session->division_id !== (int)$member->division_id) {
            return back()->with('error', 'Sesi pertemuan ini khusus untuk divisi lain.');
        }

        if ($session->status !== 'open') {
            return back()->with('error', 'Sesi pertemuan ini telah ditutup oleh pengurus divisi.');
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attendance_attachments', 'public');
        }

        $log = AttendanceLog::firstOrNew([
            'session_id' => $session->id,
            'member_id' => $member->id,
        ]);

        $log->status = $request->status;
        $log->checkin_type = 'self';
        $log->checked_in_at = now();
        $log->notes = $request->notes;
        if ($attachmentPath) {
            $log->attachment = $attachmentPath;
        }
        $log->save();

        $label = $request->status === 'sakit' ? 'SAKIT' : 'IZIN';
        return back()->with('success', 'Pengajuan ' . $label . ' Anda untuk sesi "' . $session->title . '" berhasil dikirim dan dicatat.');
    }

    public function silabus()
    {
        return view('student.silabus', $this->getStudentData());
    }
}
