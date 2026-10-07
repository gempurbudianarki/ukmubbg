<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentProjectController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $projects = Project::where('user_id', $user->id)
            ->with('division')
            ->latest()
            ->paginate(10);

        return view('student.projects.index', compact('projects', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        $studentDivision = $this->getStudentDivision($user);
        $divisions = $studentDivision ? collect([$studentDivision]) : Division::all();

        return view('student.projects.create', [
            'project' => new Project(),
            'divisions' => $divisions,
            'studentDivision' => $studentDivision,
            'defaultDivisionId' => $studentDivision?->id,
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $studentDivision = $this->getStudentDivision($user);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'division_id' => $studentDivision ? 'nullable' : 'required|exists:divisions,id',
            'description' => 'required|string',
            'author_names' => 'required|string|max:255',
            'tech_stack_input' => 'nullable|string',
            'demo_url' => 'nullable|url|max:255',
            'repo_url' => 'nullable|url|max:255',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($studentDivision) {
            $validated['division_id'] = $studentDivision->id;
        }

        $validated['user_id'] = $user->id;
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);

        $stack = [];
        if (!empty($validated['tech_stack_input'])) {
            $stack = array_map('trim', explode(',', $validated['tech_stack_input']));
        }
        $validated['tech_stack'] = $stack;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }

        $validated['submission_status'] = 'pending_review';
        $validated['is_featured'] = false;

        Project::create($validated);

        return redirect()->route('student.projects.index')
            ->with('success', 'Karya berhasil diajukan! Menunggu peninjauan dan persetujuan pengurus.');
    }

    public function edit(Project $project)
    {
        $user = Auth::user();
        if ($project->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit proyek ini.');
        }

        $studentDivision = $this->getStudentDivision($user);
        $divisions = $studentDivision ? collect([$studentDivision]) : Division::all();

        return view('student.projects.create', [
            'project' => $project,
            'divisions' => $divisions,
            'studentDivision' => $studentDivision,
            'defaultDivisionId' => $project->division_id,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $user = Auth::user();
        if ($project->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak untuk memperbarui proyek ini.');
        }

        $studentDivision = $this->getStudentDivision($user);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'division_id' => $studentDivision ? 'nullable' : 'required|exists:divisions,id',
            'description' => 'required|string',
            'author_names' => 'required|string|max:255',
            'tech_stack_input' => 'nullable|string',
            'demo_url' => 'nullable|url|max:255',
            'repo_url' => 'nullable|url|max:255',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($studentDivision) {
            $validated['division_id'] = $studentDivision->id;
        }

        $stack = [];
        if (!empty($validated['tech_stack_input'])) {
            $stack = array_map('trim', explode(',', $validated['tech_stack_input']));
        }
        $validated['tech_stack'] = $stack;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }

        // Ketika diperbarui, set status kembali ke pending_review untuk diverifikasi
        $validated['submission_status'] = 'pending_review';

        $project->update($validated);

        return redirect()->route('student.projects.index')
            ->with('success', 'Perubahan karya berhasil disimpan dan dikirim kembali untuk ditinjau.');
    }

    private function getStudentDivision($user)
    {
        $member = $user->member()->with('division')->first();
        if ($member && $member->division) {
            return $member->division;
        }

        if ($user->division_id) {
            return Division::find($user->division_id);
        }

        $recruitment = $user->recruitment()->with('firstChoiceDivision')->latest()->first();
        return $recruitment?->firstChoiceDivision;
    }

    public function destroy(Project $project)
    {
        $user = Auth::user();
        if ($project->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin menghapus karya ini.');
        }

        $project->delete();

        return redirect()->route('student.projects.index')
            ->with('success', 'Karya berhasil dihapus.');
    }
}
