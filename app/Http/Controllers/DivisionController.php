<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Post;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::withCount('posts')->get();
        return view('divisions.index', compact('divisions'));
    }

    public function show(string $slug)
    {
        $division = Division::where('slug', $slug)->firstOrFail();
        $posts = Post::with('author')
            ->where('division_id', $division->id)
            ->published()
            ->latest()
            ->paginate(6);

        $otherDivisions = Division::where('id', '!=', $division->id)->get();

        return view('divisions.show', compact('division', 'posts', 'otherDivisions'));
    }
}
