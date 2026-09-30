<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use Illuminate\Http\Request;

class OfficerAdminController extends Controller
{
    public function index()
    {
        $officers = Officer::orderBy('sort_order')->paginate(20);
        return view('admin.officers.index', compact('officers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:30',
            'period' => 'required|string|max:20',
            'department_level' => 'required|in:bph,pemrograman,multimedia,iot,cyber',
            'position' => 'required|string|max:100',
            'photo' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('officers', 'public');
        }

        $validated['sort_order'] = $request->input('sort_order', 10);

        Officer::create($validated);

        return redirect()->route('admin.officers.index')
            ->with('success', 'Data pengurus berhasil ditambahkan!');
    }

    public function destroy(Officer $officer)
    {
        $officer->delete();
        return redirect()->route('admin.officers.index')
            ->with('success', 'Data pengurus berhasil dihapus.');
    }
}
