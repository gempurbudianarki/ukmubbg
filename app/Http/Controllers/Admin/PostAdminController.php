<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Post::with(['division', 'author']);

        if (!$user->isSuperAdmin()) {
            $query->where('division_id', $user->division_id);
        } elseif ($request->filled('divisi')) {
            $query->where('division_id', $request->divisi);
        }

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where('title', 'like', $keyword);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $posts = $query->latest()->paginate(10)->withQueryString();
        $divisions = Division::all();

        return view('admin.posts.index', compact('posts', 'divisions', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        $divisions = $user->isSuperAdmin() ? Division::all() : Division::where('id', $user->division_id)->get();

        return view('admin.posts.create', compact('divisions', 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:350',
            'content' => 'required|string',
            'category' => 'required|in:kegiatan,tutorial,berita,proyek',
            'status' => 'required|in:published,draft',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ];

        if ($user->isSuperAdmin()) {
            $rules['division_id'] = 'required|exists:divisions,id';
        }

        $validated = $request->validate($rules);

        $divisionId = $user->isSuperAdmin() ? $validated['division_id'] : $user->division_id;

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'post_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/posts'), $filename);
            $thumbnailPath = 'uploads/posts/' . $filename;
        }

        $slug = Str::slug($validated['title']);
        $uniqueSlug = $slug;
        $counter = 1;
        while (Post::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = $slug . '-' . $counter;
            $counter++;
        }

        Post::create([
            'division_id' => $divisionId,
            'author_id' => $user->id,
            'title' => $validated['title'],
            'slug' => $uniqueSlug,
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'thumbnail' => $thumbnailPath,
            'category' => $validated['category'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Publikasi artikel berhasil diterbitkan.');
    }

    public function edit(Post $post)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $post->division_id !== $user->division_id) {
            abort(403, 'Anda tidak berwenang mengedit artikel dari divisi lain.');
        }

        $divisions = $user->isSuperAdmin() ? Division::all() : Division::where('id', $user->division_id)->get();

        return view('admin.posts.edit', compact('post', 'divisions', 'user'));
    }

    public function update(Request $request, Post $post)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $post->division_id !== $user->division_id) {
            abort(403, 'Anda tidak berwenang mengedit artikel dari divisi lain.');
        }

        $rules = [
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:350',
            'content' => 'required|string',
            'category' => 'required|in:kegiatan,tutorial,berita,proyek',
            'status' => 'required|in:published,draft',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ];

        if ($user->isSuperAdmin()) {
            $rules['division_id'] = 'required|exists:divisions,id';
        }

        $validated = $request->validate($rules);

        if ($user->isSuperAdmin()) {
            $post->division_id = $validated['division_id'];
        }

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($post->thumbnail && file_exists(public_path($post->thumbnail))) {
                @unlink(public_path($post->thumbnail));
            }

            $file = $request->file('thumbnail');
            $filename = 'post_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/posts'), $filename);
            $post->thumbnail = 'uploads/posts/' . $filename;
        }

        $post->title = $validated['title'];
        $post->excerpt = $validated['excerpt'];
        $post->content = $validated['content'];
        $post->category = $validated['category'];
        $post->status = $validated['status'];
        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $post->division_id !== $user->division_id) {
            abort(403, 'Anda tidak berwenang menghapus artikel ini.');
        }

        if ($post->thumbnail && file_exists(public_path($post->thumbnail))) {
            @unlink(public_path($post->thumbnail));
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Artikel telah dihapus.');
    }

    public function toggleStatus(Post $post)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $post->division_id !== $user->division_id) {
            abort(403, 'Anda tidak berwenang mengubah status artikel divisi lain.');
        }

        $newStatus = $post->status === 'published' ? 'draft' : 'published';
        $post->update(['status' => $newStatus]);

        $label = $newStatus === 'published' ? 'dipublikasikan ke publik' : 'diarsipkan sebagai draf';
        return back()->with('success', "Status artikel '{$post->title}' berhasil {$label}.");
    }
}

