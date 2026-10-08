<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DivisionAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $divisions = Division::withCount(['posts', 'firstChoiceApplicants'])->get();
        } else {
            $divisions = Division::where('id', $user->division_id)
                ->withCount(['posts', 'firstChoiceApplicants'])
                ->get();
        }

        return view('admin.divisions.index', compact('divisions', 'user'));
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
            'tagline' => 'required|string|max:255',
            'description' => 'required|string',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'focus_topics_raw' => 'required|string',
            'adviser_name' => 'required|string|max:100',
            'adviser_title' => 'required|string|max:100',
            'leader_name' => 'required|string|max:100',
            'leader_nim' => 'required|string|max:30',
            'leader_bio' => 'required|string',
            'ig_link' => 'nullable|string',
            'github_link' => 'nullable|string',
            'linkedin_link' => 'nullable|string',
            'adviser_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'leader_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Convert focus topics lines into array
        $topics = array_filter(array_map('trim', explode("\n", $validated['focus_topics_raw'])));
        $division->focus_topics = array_values($topics);

        // Social links
        $socialLinks = [
            'instagram' => $validated['ig_link'] ?? '',
            'github' => $validated['github_link'] ?? '',
            'linkedin' => $validated['linkedin_link'] ?? '',
        ];
        $division->social_links = $socialLinks;

        // Upload photos if provided
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

        $division->tagline = $validated['tagline'];
        $division->description = $validated['description'];
        $division->vision = $validated['vision'];
        $division->mission = $validated['mission'];
        $division->adviser_name = $validated['adviser_name'];
        $division->adviser_title = $validated['adviser_title'];
        $division->leader_name = $validated['leader_name'];
        $division->leader_nim = $validated['leader_nim'];
        $division->leader_bio = $validated['leader_bio'];
        $division->save();

        return redirect()->route('admin.divisions.index')->with('success', 'Profil dan biodata divisi ' . $division->name . ' berhasil diperbarui.');
    }
}
