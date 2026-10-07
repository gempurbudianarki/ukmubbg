<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Member::with('division');

        if (!$user->isSuperAdmin()) {
            $query->where('division_id', $user->division_id);
        } elseif ($request->filled('division')) {
            $query->where('division_id', $request->division);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', $keyword)
                  ->orWhere('nim', 'like', $keyword)
                  ->orWhere('email', 'like', $keyword);
            });
        }

        $members = $query->latest()->paginate(15)->withQueryString();
        $divisions = Division::all();

        // Calculate quick summary metrics
        $metricsQuery = Member::query();
        if (!$user->isSuperAdmin()) {
            $metricsQuery->where('division_id', $user->division_id);
        }

        $totalMembers = (clone $metricsQuery)->count();
        $activeMembers = (clone $metricsQuery)->where('status', 'aktif')->count();
        $alumniMembers = (clone $metricsQuery)->where('status', 'alumni')->count();

        return view('admin.members.index', compact(
            'members',
            'divisions',
            'user',
            'totalMembers',
            'activeMembers',
            'alumniMembers'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nim' => 'required|string|max:20|unique:members,nim',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:25',
            'division_id' => 'required|exists:divisions,id',
            'batch_year' => 'required|string|max:10',
            'status' => 'required|in:aktif,non_aktif,alumni',
            'notes' => 'nullable|string',
        ]);

        if (!$user->isSuperAdmin() && (int)$validated['division_id'] !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk divisi Anda.');
        }

        $validated['join_date'] = now()->toDateString();
        Member::create($validated);

        return redirect()->route('admin.members.index')->with('success', 'Anggota baru berhasil ditambahkan.');
    }

    public function show(Member $member)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && (int)$member->division_id !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk divisi Anda.');
        }

        $member->load(['division', 'user', 'recruitment']);

        // Data Presensi & Kehadiran Mahasiswa
        $totalSessions = \App\Models\AttendanceSession::where('division_id', $member->division_id)->count();
        $attendanceLogs = \App\Models\AttendanceLog::where('member_id', $member->id)
            ->with('session')
            ->latest()
            ->get();

        $attendedCount = $attendanceLogs->whereIn('status', ['hadir', 'terlambat'])->count();
        $permissionCount = $attendanceLogs->where('status', 'izin')->count();
        $sickCount = $attendanceLogs->where('status', 'sakit')->count();
        $absentCount = $attendanceLogs->where('status', 'alpa')->count();
        $percentage = $totalSessions > 0 ? round(($attendedCount / $totalSessions) * 100) : 0;

        $attendanceStats = [
            'total_sessions' => $totalSessions,
            'attended_count' => $attendedCount,
            'permission_count' => $permissionCount,
            'sick_count' => $sickCount,
            'absent_count' => $absentCount,
            'percentage' => $percentage,
            'logs' => $attendanceLogs,
        ];

        // Portofolio Karya yang dibuat mahasiswa ini
        $projects = collect();
        if ($member->user_id) {
            $projects = \App\Models\Project::where('user_id', $member->user_id)->latest()->get();
        }

        // Sertifikat yang diterbitkan atas nama mahasiswa ini
        $certificates = \App\Models\Certificate::where('recipient_nim', $member->nim)
            ->orWhere('recipient_name', $member->name)
            ->latest('issue_date')
            ->get();

        $divisions = Division::all();

        return view('admin.members.show', compact(
            'member',
            'user',
            'attendanceStats',
            'projects',
            'certificates',
            'divisions'
        ));
    }

    public function edit(Member $member)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && (int)$member->division_id !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk divisi Anda.');
        }

        $divisions = $user->isSuperAdmin() ? Division::all() : Division::where('id', $user->division_id)->get();

        return view('admin.members.edit', compact('member', 'divisions', 'user'));
    }

    public function update(Request $request, Member $member)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && (int)$member->division_id !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk divisi Anda.');
        }

        $validated = $request->validate([
            'nim' => ['required', 'string', 'max:20', \Illuminate\Validation\Rule::unique('members')->ignore($member->id)],
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:25',
            'division_id' => 'required|exists:divisions,id',
            'batch_year' => 'required|string|max:10',
            'status' => 'required|in:aktif,non_aktif,alumni',
            'notes' => 'nullable|string',
        ]);

        if (!$user->isSuperAdmin()) {
            $validated['division_id'] = $user->division_id;
        }

        $member->update($validated);

        // Jika terhubung ke User akun mahasiswa, sinkronisasikan nama & email
        if ($member->user) {
            $member->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'nim' => $validated['nim'],
            ]);
        }

        return redirect()->route('admin.members.show', $member->id)
            ->with('success', 'Biodata anggota berhasil diperbarui!');
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $query = Member::with('division')->latest();

        if (!$user->isSuperAdmin()) {
            $query->where('division_id', $user->division_id);
        } elseif ($request->filled('division')) {
            $query->where('division_id', $request->division);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->get();

        $filename = 'Data_Anggota_UKM_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($members) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'NIM', 'Nama Lengkap', 'Email', 'No. WhatsApp / HP', 'Divisi', 'Angkatan', 'Status', 'Tanggal Gabung']);

            foreach ($members as $index => $m) {
                fputcsv($handle, [
                    $index + 1,
                    $m->nim,
                    $m->name,
                    $m->email,
                    $m->phone_number ?? '-',
                    $m->division?->name ?? 'Umum',
                    $m->batch_year,
                    ucfirst($m->status),
                    $m->join_date ? $m->join_date->format('d/m/Y') : '-',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function updateStatus(Request $request, Member $member)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && (int)$member->division_id !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk divisi Anda.');
        }

        $validated = $request->validate([
            'status' => 'required|in:aktif,non_aktif,alumni',
        ]);

        $member->update($validated);

        return back()->with('success', 'Status anggota ' . $member->name . ' berhasil diubah menjadi ' . ucfirst($member->status) . '.');
    }

    public function destroy(Member $member)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && (int)$member->division_id !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk divisi Anda.');
        }

        $memberName = $member->name;
        $member->delete();

        return redirect()->route('admin.members.index')->with('success', 'Data anggota ' . $memberName . ' berhasil dihapus.');
    }
}
