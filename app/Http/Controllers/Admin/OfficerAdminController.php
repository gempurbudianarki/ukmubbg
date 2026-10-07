<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfficerAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isSuperAdmin()) {
                abort(403, 'Akses terbatas! Hanya Super Administrator yang berhak mengelola struktur organisasi & pengurus.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Officer::query();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_level')) {
            $query->where('department_level', $request->input('department_level'));
        }

        $officers = $query->orderBy('sort_order')->orderBy('id', 'desc')->paginate(20)->withQueryString();

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

    public function edit(Officer $officer)
    {
        return view('admin.officers.edit', compact('officer'));
    }

    public function update(Request $request, Officer $officer)
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
            if ($officer->photo && Storage::disk('public')->exists($officer->photo)) {
                Storage::disk('public')->delete($officer->photo);
            }
            $validated['photo'] = $request->file('photo')->store('officers', 'public');
        }

        $validated['sort_order'] = $request->input('sort_order', 10);

        $officer->update($validated);

        return redirect()->route('admin.officers.index')
            ->with('success', "Data pengurus {$officer->name} berhasil diperbarui!");
    }

    public function destroy(Officer $officer)
    {
        if ($officer->photo && Storage::disk('public')->exists($officer->photo)) {
            Storage::disk('public')->delete($officer->photo);
        }

        $officer->delete();

        return redirect()->route('admin.officers.index')
            ->with('success', 'Data pengurus berhasil dihapus.');
    }
}
