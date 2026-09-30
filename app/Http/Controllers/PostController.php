<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['division', 'author'])->published();

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', $keyword)
                  ->orWhere('excerpt', 'like', $keyword)
                  ->orWhere('content', 'like', $keyword);
            });
        }

        if ($request->filled('divisi')) {
            $query->whereHas('division', function ($q) use ($request) {
                $q->where('slug', $request->divisi);
            });
        }

        if ($request->filled('kategori')) {
            $query->where('category', $request->kategori);
        }

        $posts = $query->latest()->paginate(9)->withQueryString();
        $divisions = Division::all();

        return view('posts.index', compact('posts', 'divisions'));
    }

    public function show(string $slug)
    {
        $post = Post::with(['division', 'author'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment view count safely
        $post->increment('views_count');

        $relatedPosts = Post::with('division')
            ->where('division_id', $post->division_id)
            ->where('id', '!=', $post->id)
            ->published()
            ->latest()
            ->take(3)
            ->get();

        return view('posts.show', compact('post', 'relatedPosts'));
    }
}
