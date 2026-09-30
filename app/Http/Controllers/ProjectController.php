<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Division;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $divisions = Division::all();
        $query = Project::with('division')->latest();

        if ($request->filled('division')) {
            $query->whereHas('division', function ($q) use ($request) {
                $q->where('slug', $request->division);
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('author_names', 'like', '%' . $request->search . '%');
            });
        }

        $projects = $query->paginate(9);

        return view('projects.index', compact('projects', 'divisions'));
    }
}
