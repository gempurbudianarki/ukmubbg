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
