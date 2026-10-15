<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DivisionAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $divisions = Division::withCount(['posts', 'firstChoiceApplicants', 'members'])
                ->with('admins')
                ->latest()
                ->get();
            $trashedDivisions = Division::onlyTrashed()->withCount(['members'])->get();
            $candidateUsers = User::whereIn('role', ['member', 'division_admin'])
                ->orderBy('name')
                ->get();
        } else {
            $divisions = Division::where('id', $user->division_id)
                ->withCount(['posts', 'firstChoiceApplicants', 'members'])
                ->with('admins')
                ->get();
            $trashedDivisions = collect();
            $candidateUsers = collect();
        }

        return view('admin.divisions.index', compact('divisions', 'trashedDivisions', 'candidateUsers', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            abort(403, 'Akses terbatas untuk Super Administrator.');
        }

        return view('admin.divisions.create', compact('user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            abort(403, 'Akses terbatas untuk Super Administrator.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:divisions,name',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'focus_topics_raw' => 'nullable|string',
            'color_accent' => 'nullable|string|max:25',
            'icon_class' => 'nullable|string|max:60',
            'recruitment_quota' => 'nullable|integer|min:0',
            'is_recruitment_open' => 'nullable',
            'adviser_name' => 'nullable|string|max:100',
            'adviser_title' => 'nullable|string|max:100',
            'leader_name' => 'nullable|string|max:100',
            'leader_nim' => 'nullable|string|max:30',
            'leader_bio' => 'nullable|string',
            'ig_link' => 'nullable|string',
            'github_link' => 'nullable|string',
            'linkedin_link' => 'nullable|string',
            'adviser_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'leader_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Division::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $topics = [];
        if (!empty($validated['focus_topics_raw'])) {
            $topics = array_values(array_filter(array_map('trim', explode("\n", $validated['focus_topics_raw']))));
        }

        $socialLinks = [
            'instagram' => $validated['ig_link'] ?? '',
            'github' => $validated['github_link'] ?? '',
            'linkedin' => $validated['linkedin_link'] ?? '',
        ];

        $division = new Division();
        $division->name = $validated['name'];
        $division->slug = $slug;
        $division->tagline = $validated['tagline'] ?? ('Divisi ' . $validated['name']);
        $division->description = $validated['description'] ?? 'Divisi spesialisasi teknologi pada UKM Ilmu Komputer.';
        $division->vision = $validated['vision'] ?? null;
        $division->mission = $validated['mission'] ?? null;
        $division->focus_topics = $topics;
        $division->social_links = $socialLinks;
        $division->color_accent = $validated['color_accent'] ?: '#0284c7';
        $division->icon_class = $validated['icon_class'] ?: 'fa-laptop-code';
        $division->recruitment_quota = $validated['recruitment_quota'] ?? 25;
        $division->is_recruitment_open = $request->boolean('is_recruitment_open');
        $division->status = 'active';
        $division->adviser_name = $validated['adviser_name'] ?? null;
        $division->adviser_title = $validated['adviser_title'] ?? null;
        $division->leader_name = $validated['leader_name'] ?? null;
        $division->leader_nim = $validated['leader_nim'] ?? null;
        $division->leader_bio = $validated['leader_bio'] ?? null;

        if ($request->hasFile('adviser_photo')) {
            $file = $request->file('adviser_photo');
            $filename = 'adviser_' . $slug . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/divisions'), $filename);
            $division->adviser_photo = 'uploads/divisions/' . $filename;
        }

        if ($request->hasFile('leader_photo')) {
            $file = $request->file('leader_photo');
            $filename = 'leader_' . $slug . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/divisions'), $filename);
            $division->leader_photo = 'uploads/divisions/' . $filename;
        }

        $division->save();

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Divisi baru "' . $division->name . '" berhasil dibentuk dan siap digunakan.');
    }

    public function edit(Division $division)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $division->id !== $user->division_id) {
            abort(403, 'Anda hanya dapat mengedit informasi divisi Anda sendiri.');
        }

        return view('admin.divisions.edit', compact('division', 'user'));
    }

    public function update(Request $request, Division $division)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $division->id !== $user->division_id) {
            abort(403, 'Anda hanya dapat mengedit informasi divisi Anda sendiri.');
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:100|unique:divisions,name,' . $division->id,
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'focus_topics_raw' => 'nullable|string',
            'color_accent' => 'nullable|string|max:25',
            'icon_class' => 'nullable|string|max:60',
            'recruitment_quota' => 'nullable|integer|min:0',
            'adviser_name' => 'nullable|string|max:100',
            'adviser_title' => 'nullable|string|max:100',
            'leader_name' => 'nullable|string|max:100',
            'leader_nim' => 'nullable|string|max:30',
            'leader_bio' => 'nullable|string',
            'ig_link' => 'nullable|string',
            'github_link' => 'nullable|string',
            'linkedin_link' => 'nullable|string',
            'adviser_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'leader_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($user->isSuperAdmin() && !empty($validated['name'])) {
            $division->name = $validated['name'];
        }

        if ($user->isSuperAdmin()) {
            if (!empty($validated['color_accent'])) {
                $division->color_accent = $validated['color_accent'];
            }
            if (!empty($validated['icon_class'])) {
                $division->icon_class = $validated['icon_class'];
            }
            if (isset($validated['recruitment_quota'])) {
                $division->recruitment_quota = $validated['recruitment_quota'];
            }
        }

        if (isset($validated['focus_topics_raw'])) {
            $topics = array_values(array_filter(array_map('trim', explode("\n", $validated['focus_topics_raw']))));
            $division->focus_topics = $topics;
        }

        $socialLinks = [
            'instagram' => $validated['ig_link'] ?? '',
            'github' => $validated['github_link'] ?? '',
            'linkedin' => $validated['linkedin_link'] ?? '',
        ];
        $division->social_links = $socialLinks;

        if ($request->hasFile('adviser_photo')) {
            if ($division->adviser_photo && file_exists(public_path($division->adviser_photo))) {
                @unlink(public_path($division->adviser_photo));
            }
            $file = $request->file('adviser_photo');
            $filename = 'adviser_' . $division->slug . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/divisions'), $filename);
            $division->adviser_photo = 'uploads/divisions/' . $filename;
        }

        if ($request->hasFile('leader_photo')) {
            if ($division->leader_photo && file_exists(public_path($division->leader_photo))) {
                @unlink(public_path($division->leader_photo));
            }
            $file = $request->file('leader_photo');
            $filename = 'leader_' . $division->slug . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/divisions'), $filename);
            $division->leader_photo = 'uploads/divisions/' . $filename;
        }

        $division->tagline = $validated['tagline'] ?? $division->tagline;
        $division->description = $validated['description'] ?? $division->description;
        $division->vision = $validated['vision'] ?? $division->vision;
        $division->mission = $validated['mission'] ?? $division->mission;
        $division->adviser_name = $validated['adviser_name'] ?? $division->adviser_name;
        $division->adviser_title = $validated['adviser_title'] ?? $division->adviser_title;
        $division->leader_name = $validated['leader_name'] ?? $division->leader_name;
        $division->leader_nim = $validated['leader_nim'] ?? $division->leader_nim;
        $division->leader_bio = $validated['leader_bio'] ?? $division->leader_bio;
        $division->save();

        return redirect()->route('admin.divisions.index')->with('success', 'Profil dan biodata divisi ' . $division->name . ' berhasil diperbarui.');
    }

    public function toggleStatus(Division $division)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            abort(403, 'Akses terbatas untuk Super Administrator.');
        }

        $newStatus = ($division->status === 'active') ? 'suspended' : 'active';
        $division->status = $newStatus;
        $division->save();

        $label = ($newStatus === 'active') ? 'DI-AKTIFKAN' : 'DI-SUSPEND (Dibekukan Sementara)';
        return back()->with('success', "Status operasional divisi '{$division->name}' berhasil diubah menjadi {$label}.");
    }

    public function toggleRecruitment(Division $division)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $division->id !== $user->division_id) {
            abort(403, 'Anda tidak berwenang mengatur pendaftaran divisi ini.');
        }

        $division->is_recruitment_open = !$division->is_recruitment_open;
        $division->save();

        $label = $division->is_recruitment_open ? 'DIBUKA' : 'DITUTUP';
        return back()->with('success', "Periode pendaftaran untuk divisi '{$division->name}' berhasil {$label}.");
    }

    public function assignAdmin(Request $request, Division $division)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            abort(403, 'Akses terbatas untuk Super Administrator.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $targetUser = User::findOrFail($request->user_id);
        $targetUser->role = 'division_admin';
        $targetUser->division_id = $division->id;
        $targetUser->save();

        return back()->with('success', "User '{$targetUser->name}' resmi ditunjuk sebagai Admin Divisi untuk {$division->name}.");
    }

    public function removeAdmin(Division $division, User $user)
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Akses terbatas untuk Super Administrator.');
        }

        if ($user->division_id === $division->id && $user->role === 'division_admin') {
            $user->role = 'member';
            $user->save();
        }

        return back()->with('success', "Hak Admin Divisi '{$user->name}' dari {$division->name} telah dicopot (status kembali ke Member).");
    }

    public function destroy(Division $division)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            abort(403, 'Akses terbatas untuk Super Administrator.');
        }

        $divisionName = $division->name;
        $division->status = 'archived';
        $division->save();
        $division->delete(); // Soft delete

        return redirect()->route('admin.divisions.index')
            ->with('success', "Divisi '{$divisionName}' berhasil diarsipkan dan dinonaktifkan secara aman.");
    }

    public function restore($id)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            abort(403, 'Akses terbatas untuk Super Administrator.');
        }

        $division = Division::onlyTrashed()->findOrFail($id);
        $division->restore();
        $division->status = 'active';
        $division->save();

        return redirect()->route('admin.divisions.index')
            ->with('success', "Divisi '{$division->name}' berhasil dipulihkan dan kembali aktif.");
    }
}
