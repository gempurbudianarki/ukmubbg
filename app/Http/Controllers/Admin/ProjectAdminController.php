<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectAdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Project::with('division')->latest();

        if ($user->isDivisionAdmin()) {
            $query->where('division_id', $user->division_id);
        }

        $projects = $query->paginate(15);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $divisions = Division::all();
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

        Project::create($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Karya mahasiswa berhasil dipublikasikan!');
    }

    public function edit(Project $project)
    {
        $user = auth()->user();
        if ($user->isDivisionAdmin() && $project->division_id !== $user->division_id) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit proyek divisi lain.');
        }

        $divisions = Division::all();
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
