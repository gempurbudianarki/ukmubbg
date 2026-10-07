<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Project;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::withCount('projects')->get();
        return view('divisions.index', compact('divisions'));
    }

    public function show(string $slug)
    {
        $division = Division::where('slug', $slug)->firstOrFail();
        $projects = Project::where('division_id', $division->id)
            ->published()
            ->latest()
            ->paginate(6);

        $otherDivisions = Division::where('id', '!=', $division->id)->get();

        return view('divisions.show', compact('division', 'projects', 'otherDivisions'));
    }
}
