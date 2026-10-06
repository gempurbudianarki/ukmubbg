<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $recruitment = $user->recruitment;
        $member = $user->member;

        return view('student.profile', compact('user', 'recruitment', 'member'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone_number' => 'required|string|max:25',
            'github_url' => 'nullable|url|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone_number.required' => 'Nomor WhatsApp wajib diisi.',
            'github_url.url' => 'Tautan GitHub harus berformat URL valid (https://...).',
            'avatar.image' => 'File foto profil harus berupa file gambar valid.',
            'avatar.max' => 'Ukuran foto profil maksimal 2MB.',
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->name = $validated['name'];
        $user->phone_number = $validated['phone_number'];
        $user->github_url = $validated['github_url'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Sync avatar and profile info to recruitment record if exists
        if ($user->recruitment) {
            $user->recruitment->update([
                'full_name' => $user->name,
                'phone_whatsapp' => $user->phone_number,
                'github_url' => $user->github_url,
                'profile_photo' => $user->avatar,
            ]);
        }

        // Sync avatar and profile info to official member record if exists
        if ($user->member) {
            $user->member->update([
                'name' => $user->name,
                'phone_number' => $user->phone_number,
                'avatar' => $user->avatar,
            ]);
        }

        return redirect()->route('student.profile.edit')
            ->with('success', 'Profil dan informasi akun Anda berhasil diperbarui!');
    }
}
