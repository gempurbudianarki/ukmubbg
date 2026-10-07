<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\AttendanceSession;
use App\Models\Division;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = AttendanceSession::with(['division', 'creator', 'logs']);

        if (!$user->isSuperAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('division_id', $user->division_id)
                  ->orWhereNull('division_id');
            });
        } elseif ($request->filled('division')) {
            if ($request->division === 'pleno') {
                $query->whereNull('division_id');
            } else {
                $query->where('division_id', $request->division);
            }
        }

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', $keyword)
                  ->orWhere('location', 'like', $keyword);
            });
        }

        $sessions = $query->latest('session_date')->paginate(12)->withQueryString();
        $divisions = Division::all();

        return view('admin.attendance.index', compact('sessions', 'divisions', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        $divisions = $user->isSuperAdmin() ? Division::all() : Division::where('id', $user->division_id)->get();

        return view('admin.attendance.create', compact('divisions', 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Intelligent auto-fills for formal metadata if omitted
        if (!$request->filled('day_name') && $request->filled('session_date')) {
            $daysIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $dayOfWeek = \Carbon\Carbon::parse($request->session_date)->dayOfWeek;
            $request->merge(['day_name' => $daysIndo[$dayOfWeek]]);
        }
        if (!$request->filled('session_type')) {
            $request->merge(['session_type' => 'riset_rutin']);
        }
        if (!$request->filled('topic_material') && $request->filled('title')) {
            $request->merge(['topic_material' => $request->title]);
        }
        if (!$request->filled('instructor_name')) {
            $request->merge(['instructor_name' => $user->name]);
        }
        if (!$request->filled('passcode')) {
            $request->merge(['passcode' => AttendanceSession::generatePasscode()]);
        } else {
            $request->merge(['passcode' => strtoupper(trim($request->passcode))]);
        }
        if (!$request->has('allow_self_checkin')) {
            $request->merge(['allow_self_checkin' => true]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'day_name' => 'required|string|max:20',
            'session_date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'nullable',
            'session_type' => 'required|string|in:riset_rutin,workshop_teknis,mentoring_proyek,evaluasi_bulanan,sidang_pleno',
            'location' => 'required|string|max:255',
            'topic_material' => 'required|string|max:255',
            'learning_outcomes' => 'nullable|string',
            'instructor_name' => 'required|string|max:150',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:open,closed',
            'passcode' => 'nullable|string|max:50',
            'allow_self_checkin' => 'nullable|boolean',
        ]);

        if (!$user->isSuperAdmin()) {
            $validated['division_id'] = $user->division_id;
        }

        $durationMinutes = $request->filled('passcode_duration_minutes') ? (int)$request->passcode_duration_minutes : 60;
        $validated['passcode_expires_at'] = $durationMinutes > 0 ? now()->addMinutes($durationMinutes) : null;

        $validated['created_by'] = $user->id;
        $session = AttendanceSession::create($validated);

        // Auto populate attendance logs for active members
        $membersQuery = Member::where('status', 'aktif');
        if (!empty($session->division_id)) {
            $membersQuery->where('division_id', $session->division_id);
        }

        $activeMembers = $membersQuery->get();
        foreach ($activeMembers as $member) {
            AttendanceLog::create([
                'session_id' => $session->id,
                'member_id' => $member->id,
                'status' => 'hadir',
            ]);
        }

        return redirect()->route('admin.attendance.show', $session)
            ->with('success', 'Sesi absensi "' . $session->title . '" berhasil dibuat dengan ' . $activeMembers->count() . ' anggota terdaftar. Password Presensi: ' . $session->passcode);
    }

    public function show(AttendanceSession $session)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && !empty($session->division_id) && (int)$session->division_id !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk sesi divisi lain.');
        }

        $session->load(['division', 'creator', 'logs.member.division']);

        $logs = $session->logs->sortBy(function ($log) {
            return $log->member->name ?? '';
        });

        $stats = [
            'total' => $logs->count(),
            'hadir' => $logs->where('status', 'hadir')->count(),
            'izin' => $logs->where('status', 'izin')->count(),
            'sakit' => $logs->where('status', 'sakit')->count(),
            'alpa' => $logs->where('status', 'alpa')->count(),
        ];

        $stats['rate'] = $stats['total'] > 0 ? round(($stats['hadir'] / $stats['total']) * 100, 1) : 0;

        return view('admin.attendance.show', compact('session', 'logs', 'stats', 'user'));
    }

    public function bap(AttendanceSession $session)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && !empty($session->division_id) && (int)$session->division_id !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk sesi divisi lain.');
        }

        $session->load(['division', 'creator', 'logs.member.division']);

        $logs = $session->logs->sortBy(function ($log) {
            return $log->member->name ?? '';
        });

        $stats = [
            'total' => $logs->count(),
            'hadir' => $logs->where('status', 'hadir')->count(),
            'izin' => $logs->where('status', 'izin')->count(),
            'sakit' => $logs->where('status', 'sakit')->count(),
            'alpa' => $logs->where('status', 'alpa')->count(),
        ];

        $stats['rate'] = $stats['total'] > 0 ? round(($stats['hadir'] / $stats['total']) * 100, 1) : 0;

        return view('admin.attendance.bap', compact('session', 'logs', 'stats', 'user'));
    }

    public function updateLogs(Request $request, AttendanceSession $session)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && !empty($session->division_id) && (int)$session->division_id !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk sesi divisi lain.');
        }

        $validated = $request->validate([
            'logs' => 'required|array',
            'logs.*.status' => 'required|in:hadir,izin,sakit,alpa',
            'logs.*.notes' => 'nullable|string|max:255',
        ]);

        foreach ($validated['logs'] as $logId => $data) {
            AttendanceLog::where('id', $logId)
                ->where('session_id', $session->id)
                ->update([
                    'status' => $data['status'],
                    'notes' => $data['notes'] ?? null,
                ]);
        }

        return back()->with('success', 'Rekap kehadiran berhasil diperbarui secara instan.');
    }

    public function quickMark(Request $request, AttendanceSession $session)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && !empty($session->division_id) && (int)$session->division_id !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk sesi divisi lain.');
        }

        $validated = $request->validate([
            'log_id' => 'required|exists:attendance_logs,id',
            'status' => 'required|in:hadir,izin,sakit,alpa',
            'notes' => 'nullable|string|max:255',
        ]);

        $log = AttendanceLog::where('id', $validated['log_id'])
            ->where('session_id', $session->id)
            ->with('member')
            ->firstOrFail();

        $log->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $log->notes,
            'checkin_type' => 'admin',
            'checked_in_at' => $validated['status'] === 'hadir' ? now() : $log->checked_in_at,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status presensi ' . ($log->member->name ?? '') . ' berhasil diubah menjadi ' . strtoupper($validated['status']),
                'log' => $log,
            ]);
        }

        return back()->with('success', 'Presensi ' . ($log->member->name ?? 'Anggota') . ' berhasil diubah menjadi ' . strtoupper($validated['status']) . '.');
    }

    public function toggleStatus(AttendanceSession $session)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && !empty($session->division_id) && (int)$session->division_id !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk sesi divisi lain.');
        }

        $newStatus = $session->status === 'open' ? 'closed' : 'open';
        $session->update(['status' => $newStatus]);

        $label = $newStatus === 'open' ? 'DIBUKA (Menerima Presensi Mandiri)' : 'DITUTUP';
        return back()->with('success', 'Status sesi presensi berhasil diubah menjadi ' . $label . '.');
    }

    public function updatePasscode(Request $request, AttendanceSession $session)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && !empty($session->division_id) && (int)$session->division_id !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk sesi divisi lain.');
        }

        $newPasscode = $request->filled('passcode')
            ? strtoupper(trim($request->passcode))
            : AttendanceSession::generatePasscode();

        $durationMinutes = $request->filled('passcode_duration_minutes') ? (int)$request->passcode_duration_minutes : 60;
        $expiresAt = $durationMinutes > 0 ? now()->addMinutes($durationMinutes) : null;

        $session->update([
            'passcode' => $newPasscode,
            'passcode_expires_at' => $expiresAt,
        ]);

        return back()->with('success', 'Password / Kode Presensi berhasil diperbarui menjadi: ' . $newPasscode . ' (Berlaku ' . $durationMinutes . ' menit)');
    }

    public function destroy(AttendanceSession $session)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && !empty($session->division_id) && (int)$session->division_id !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk sesi divisi lain.');
        }

        $title = $session->title;
        $session->delete();

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Sesi absensi "' . $title . '" berhasil dihapus.');
    }
}
