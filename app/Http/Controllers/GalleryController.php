<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Gallery::select('category')->distinct()->pluck('category');
        $query = Gallery::latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $galleries = $query->paginate(12);

        return view('galleries.index', compact('galleries', 'categories'));
    }
}
