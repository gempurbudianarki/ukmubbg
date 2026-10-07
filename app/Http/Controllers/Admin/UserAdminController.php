<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserAdminController extends Controller
{
    public function __construct()
    {
        // Hanya Super Admin yang berhak mengelola akun pengguna dan peran
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isSuperAdmin()) {
                abort(403, 'Akses terbatas! Hanya Super Administrator yang berhak mengelola akun dan hak akses.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = User::with('division')->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', $keyword)
                  ->orWhere('email', 'like', $keyword)
                  ->orWhere('nim', 'like', $keyword);
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 50])) {
            $perPage = 10;
        }

        $users = $query->paginate($perPage)->withQueryString();
        $divisions = Division::all();

        $stats = [
            'total' => User::count(),
            'super_admin' => User::where('role', 'super_admin')->count(),
            'division_admin' => User::where('role', 'division_admin')->count(),
            'member' => User::where('role', 'member')->count(),
        ];

        return view('admin.users.index', compact('users', 'divisions', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'nim' => 'nullable|string|max:30|unique:users,nim',
            'password' => 'required|string|min:6',
            'role' => 'required|in:super_admin,division_admin,member',
            'division_id' => 'nullable|required_if:role,division_admin|exists:divisions,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        if ($validated['role'] === 'super_admin') {
            $validated['division_id'] = null;
        }

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nim' => ['nullable', 'string', 'max:30', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:super_admin,division_admin,member',
            'division_id' => 'nullable|required_if:role,division_admin|exists:divisions,id',
        ]);

        // Proteksi agar super admin tidak mendowngrade akunnya sendiri jika cuma ada 1 super admin
        if ($user->id === auth()->id() && $validated['role'] !== 'super_admin') {
            return back()->with('error', 'Anda tidak dapat mengubah peran akun Anda sendiri.');
        }

        if ($validated['role'] === 'super_admin') {
            $validated['division_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "Data akun '{$user->name}' berhasil diperbarui!");
    }

    public function assignRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:super_admin,division_admin,member',
            'division_id' => 'nullable|required_if:role,division_admin|exists:divisions,id',
        ]);

        if ($user->id === auth()->id() && $validated['role'] !== 'super_admin') {
            return back()->with('error', 'Anda tidak dapat mengubah peran akun Anda sendiri.');
        }

        if ($validated['role'] === 'super_admin') {
            $validated['division_id'] = null;
        }

        $user->update([
            'role' => $validated['role'],
            'division_id' => $validated['division_id'] ?? null,
        ]);

        $user->load('division');
        $roleName = match($validated['role']) {
            'super_admin' => 'Super Administrator (Akses Penuh Seluruh UKM)',
            'division_admin' => 'Admin Divisi (' . ($user->division->name ?? 'Divisi') . ')',
            'member' => 'Anggota Mahasiswa Portal',
        };

        return back()->with('success', "Akun '{$user->name}' berhasil ditunjuk sebagai {$roleName}!");
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return back()->with('success', "Kata sandi untuk akun '{$user->name}' berhasil direset!");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun '{$name}' berhasil dihapus dari sistem.");
    }
}
