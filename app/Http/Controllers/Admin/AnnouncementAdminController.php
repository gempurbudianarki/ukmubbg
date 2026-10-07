<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Announcement::with(['division', 'author']);

        if (!$user->isSuperAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('division_id', $user->division_id)
                  ->orWhereNull('division_id');
            });
        } elseif ($request->filled('division')) {
            if ($request->division === 'umum') {
                $query->whereNull('division_id');
            } else {
                $query->where('division_id', $request->division);
            }
        }

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', $keyword)
                  ->orWhere('content', 'like', $keyword);
            });
        }

        $announcements = $query->orderByDesc('is_pinned')->latest()->paginate(10)->withQueryString();
        $divisions = Division::all();

        return view('admin.announcements.index', compact('announcements', 'divisions', 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'category' => 'required|in:info,penting,agenda',
            'division_id' => 'nullable|exists:divisions,id',
            'is_pinned' => 'nullable|boolean',
        ]);

        if (!$user->isSuperAdmin() && !empty($validated['division_id']) && (int)$validated['division_id'] !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk divisi Anda.');
        }

        $validated['author_id'] = $user->id;
        $validated['is_pinned'] = $request->boolean('is_pinned');

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman baru berhasil diterbitkan untuk anggota.');
    }

    public function destroy(Announcement $announcement)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && !empty($announcement->division_id) && (int)$announcement->division_id !== (int)$user->division_id) {
            abort(403, 'Akses terbatas untuk divisi Anda.');
        }

        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
