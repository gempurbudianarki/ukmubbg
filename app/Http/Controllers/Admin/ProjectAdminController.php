<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Project::with(['division', 'user'])->latest();

        if ($user->isDivisionAdmin()) {
            $query->where('division_id', $user->division_id);
        } elseif ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author_names', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('submission_status', $request->status);
        }

        $projects = $query->paginate(15)->withQueryString();
        $divisions = Division::all();

        return view('admin.projects.index', compact('projects', 'divisions', 'user'));
    }

    public function toggleFeatured(Project $project)
    {
        $user = auth()->user();
        if ($user->isDivisionAdmin() && $project->division_id !== $user->division_id) {
            abort(403, 'Anda tidak berwenang mengubah status featured karya divisi lain.');
        }

        $project->update([
            'is_featured' => !$project->is_featured,
        ]);

        $statusText = $project->is_featured ? 'dijadikan Karya Unggulan' : 'dilepas dari status Unggulan';
        return back()->with('success', "Karya '{$project->title}' berhasil {$statusText}.");
    }

    public function create()
    {
        $user = auth()->user();
        $divisions = $user->isSuperAdmin()
            ? Division::all()
            : Division::where('id', $user->division_id)->get();

        return view('admin.projects.form', [
            'project' => new Project(),
            'divisions' => $divisions,
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'description' => 'required|string',
            'author_names' => 'required|string|max:255',
            'tech_stack_input' => 'nullable|string',
            'demo_url' => 'nullable|url|max:255',
            'repo_url' => 'nullable|url|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'is_featured' => 'nullable|boolean',
        ]);

        if ($user->isDivisionAdmin()) {
            $validated['division_id'] = $user->division_id;
        }

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);

        $stack = [];
        if (!empty($validated['tech_stack_input'])) {
            $stack = array_map('trim', explode(',', $validated['tech_stack_input']));
        }
        $validated['tech_stack'] = $stack;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['submission_status'] = 'published';
        $validated['user_id'] = $user->id;

        Project::create($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Karya mahasiswa berhasil dipublikasikan!');
    }

    public function moderate(Request $request, Project $project)
    {
        $user = auth()->user();
        if ($user->isDivisionAdmin() && $project->division_id !== $user->division_id) {
            abort(403, 'Anda tidak memiliki hak untuk memoderasi karya divisi lain.');
        }

        $validated = $request->validate([
            'status' => 'required|in:published,rejected,pending_review',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $updateData = [
            'submission_status' => $validated['status'],
        ];

        if ($request->has('admin_notes')) {
            $updateData['admin_notes'] = $validated['admin_notes'];
        }

        $project->update($updateData);

        $statusText = match ($validated['status']) {
            'published' => 'disetujui dan ditayangkan ke publik',
            'rejected' => 'ditolak / dikembalikan untuk revisi',
            default => 'diubah statusnya',
        };

        return back()->with('success', "Karya '{$project->title}' berhasil {$statusText}.");
    }

    public function edit(Project $project)
    {
        $user = auth()->user();
        if ($user->isDivisionAdmin() && $project->division_id !== $user->division_id) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit proyek divisi lain.');
        }

        $divisions = $user->isSuperAdmin()
            ? Division::all()
            : Division::where('id', $user->division_id)->get();

        return view('admin.projects.form', [
            'project' => $project,
            'divisions' => $divisions,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $user = auth()->user();
        if ($user->isDivisionAdmin() && $project->division_id !== $user->division_id) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit proyek divisi lain.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'description' => 'required|string',
            'author_names' => 'required|string|max:255',
            'tech_stack_input' => 'nullable|string',
            'demo_url' => 'nullable|url|max:255',
            'repo_url' => 'nullable|url|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'is_featured' => 'nullable|boolean',
        ]);

        if ($user->isDivisionAdmin()) {
            $validated['division_id'] = $user->division_id;
        }

        $stack = [];
        if (!empty($validated['tech_stack_input'])) {
            $stack = array_map('trim', explode(',', $validated['tech_stack_input']));
        }
        $validated['tech_stack'] = $stack;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        $project->update($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Data karya mahasiswa berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        $user = auth()->user();
        if ($user->isDivisionAdmin() && $project->division_id !== $user->division_id) {
            abort(403, 'Anda tidak memiliki izin menghapus karya ini.');
        }

        $project->delete();
        return redirect()->route('admin.projects.index')
            ->with('success', 'Karya berhasil dihapus.');
    }
}
