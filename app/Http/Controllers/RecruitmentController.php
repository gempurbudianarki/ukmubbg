<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Recruitment;
use App\Models\Setting;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    public function index(Request $request)
    {
        $status = Setting::get('recruitment_status', 'open');
        $batch = Setting::get('recruitment_batch_name', Setting::get('recruitment_batch', 'Gelombang I'));
        $deadline = Setting::get('recruitment_deadline', '31 Oktober 2026');
        $startDate = Setting::get('recruitment_start_date');
        $endDate = Setting::get('recruitment_end_date');
        $closedMessage = Setting::get('recruitment_closed_message', 'Mohon maaf, periode pendaftaran anggota baru saat ini sedang ditutup.');

        $now = now();
        $isWithinSchedule = true;
        if (!empty($startDate) && $now->lt(\Carbon\Carbon::parse($startDate))) {
            $isWithinSchedule = false;
        }
        if (!empty($endDate) && $now->gt(\Carbon\Carbon::parse($endDate))) {
            $isWithinSchedule = false;
        }
        $isOpen = ($status === 'open') && $isWithinSchedule;

        // Retrieve only divisions where recruitment is open
        $divisions = Division::where('is_recruitment_open', true)
            ->withCount('firstChoiceApplicants')
            ->get();

        // Optional preselected division from query parameter ?divisi=pemrograman
        $preselectedDivision = null;
        if ($request->filled('divisi')) {
            $preselectedDivision = Division::where('slug', $request->divisi)
                ->where('is_recruitment_open', true)
                ->withCount('firstChoiceApplicants')
                ->first();
        }

        return view('recruitment.index', compact('status', 'isOpen', 'batch', 'deadline', 'startDate', 'endDate', 'closedMessage', 'divisions', 'preselectedDivision'));
    }

    public function checkAvailability(Request $request)
    {
        $type = $request->query('type') ?? $request->query('field');
        $value = trim($request->query('value', ''));

        if (empty($value)) {
            return response()->json(['available' => true, 'message' => '']);
        }

        if ($type === 'nim') {
            $existsInRecruitment = Recruitment::where('nim', $value)->exists();
            if ($existsInRecruitment) {
                return response()->json([
                    'available' => false,
                    'message' => 'NIM ' . $value . ' sudah terdaftar pada seleksi periode ini. Silakan periksa status seleksi Anda.'
                ]);
            }
            return response()->json(['available' => true, 'message' => 'NIM valid & siap didaftarkan.']);
        }

        if ($type === 'email') {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return response()->json(['available' => false, 'message' => 'Format email belum valid.']);
            }
            $existsInUser = \App\Models\User::where('email', $value)->exists();
            if ($existsInUser && !\Illuminate\Support\Facades\Auth::check()) {
                return response()->json([
                    'available' => false,
                    'is_existing_account' => true,
                    'message' => 'Email ini sudah memiliki akun di portal. Anda dapat login terlebih dahulu atau gunakan email lain.'
                ]);
            }
            return response()->json(['available' => true, 'message' => 'Email siap digunakan sebagai ID akun.']);
        }

        return response()->json(['available' => true]);
    }

    public function store(Request $request)
    {
        $status = Setting::get('recruitment_status', 'open');
        $startDate = Setting::get('recruitment_start_date');
        $endDate = Setting::get('recruitment_end_date');
        $closedMessage = Setting::get('recruitment_closed_message', 'Mohon maaf, periode pendaftaran anggota baru saat ini sedang ditutup.');

        $now = now();
        $isWithinSchedule = true;
        if (!empty($startDate) && $now->lt(\Carbon\Carbon::parse($startDate))) {
            $isWithinSchedule = false;
        }
        if (!empty($endDate) && $now->gt(\Carbon\Carbon::parse($endDate))) {
            $isWithinSchedule = false;
        }
        $isOpen = ($status === 'open') && $isWithinSchedule;

        if (!$isOpen) {
            return back()->with('error', $closedMessage);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'nim' => 'required|string|max:30',
            'email' => 'required|email|max:100',
            'password' => 'nullable|string|min:8|confirmed',
            'phone_whatsapp' => 'required|string|max:25',
            'semester' => 'required|integer|min:1|max:14',
            'class_group' => 'nullable|string|max:20',
            'first_choice_division_id' => 'required|exists:divisions,id',
            'second_choice_division_id' => 'nullable|exists:divisions,id|different:first_choice_division_id',
            'reason_to_join' => 'required|string|min:20',
            'portfolio_url' => 'nullable|url|max:255',
            'github_url' => 'required|url|max:255',
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'file_ktm' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_cv' => 'required|file|mimes:pdf|max:3072',
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'nim.required' => 'NIM wajib diisi.',
            'email.required' => 'Email aktif wajib diisi.',
            'password.min' => 'Kata sandi akun minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'phone_whatsapp.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'first_choice_division_id.required' => 'Pilihan divisi utama wajib dipilih.',
            'second_choice_division_id.different' => 'Divisi pilihan kedua harus berbeda dengan pilihan utama.',
            'reason_to_join.min' => 'Alasan/motivasi bergabung minimal 20 karakter.',
            'portfolio_url.url' => 'Link portofolio harus berupa format URL valid (https://...).',
            'github_url.required' => 'Tautan profil GitHub wajib diisi.',
            'github_url.url' => 'Link GitHub harus berupa format URL valid (https://...).',
            'profile_photo.required' => 'Pas foto resmi mahasiswa wajib diunggah.',
            'profile_photo.image' => 'File foto profil harus berupa file gambar valid.',
            'profile_photo.max' => 'Ukuran foto profil maksimal 2MB.',
            'file_ktm.required' => 'Foto atau scan KTM aktif wajib diunggah.',
            'file_ktm.max' => 'Ukuran file KTM maksimal 2MB.',
            'file_cv.required' => 'Dokumen CV / resume (PDF) wajib diunggah.',
            'file_cv.max' => 'Ukuran file CV maksimal 3MB.',
        ]);

        // Verify that selected divisions are currently open for recruitment and have available quota
        $firstDiv = Division::where('id', $validated['first_choice_division_id'])
            ->where('is_recruitment_open', true)
            ->withCount('firstChoiceApplicants')
            ->first();

        if (!$firstDiv) {
            return back()->withErrors(['first_choice_division_id' => 'Divisi pilihan utama saat ini sedang tidak membuka pendaftaran atau kuota telah terpenuhi.'])->withInput();
        }

        if ($firstDiv->is_quota_full) {
            return back()->withErrors(['first_choice_division_id' => 'Mohon maaf, kuota pendaftaran untuk Divisi ' . $firstDiv->name . ' sudah penuh (' . $firstDiv->first_choice_applicants_count . '/' . $firstDiv->recruitment_quota . '). Silakan pilih divisi lain yang masih tersedia.'])->withInput();
        }

        if (!empty($validated['second_choice_division_id'])) {
            $secondDiv = Division::where('id', $validated['second_choice_division_id'])
                ->where('is_recruitment_open', true)
                ->withCount('firstChoiceApplicants')
                ->first();

            if (!$secondDiv) {
                return back()->withErrors(['second_choice_division_id' => 'Divisi pilihan kedua saat ini sedang tidak membuka pendaftaran atau kuota telah terpenuhi.'])->withInput();
            }

            if ($secondDiv->is_quota_full) {
                return back()->withErrors(['second_choice_division_id' => 'Mohon maaf, kuota pendaftaran untuk Divisi ' . $secondDiv->name . ' (pilihan kedua) sudah penuh.'])->withInput();
            }
        }

        // Check if NIM has already registered in this batch
        $existing = Recruitment::where('nim', $validated['nim'])->first();
        if ($existing) {
            return back()->withErrors([
                'nim' => 'NIM ' . $validated['nim'] . ' sudah terdaftar sebelumnya dalam periode pendaftaran ini. Anda dapat memantau status seleksi di halaman Cek Status.'
            ])->withInput();
        }

        // Handle profile photo upload
        $avatarPath = null;
        if ($request->hasFile('profile_photo')) {
            $avatarPath = $request->file('profile_photo')->store('avatars', 'public');
        }

        // Handle or create user account securely
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            $existingUser = \App\Models\User::where('email', $validated['email'])->first();
            if ($existingUser) {
                if (!empty($validated['password'])) {
                    if (\Illuminate\Support\Facades\Hash::check($validated['password'], $existingUser->password)) {
                        $user = $existingUser;
                        \Illuminate\Support\Facades\Auth::login($user);
                    } else {
                        return back()->withErrors([
                            'email' => 'Email ini sudah terdaftar. Kata sandi yang Anda masukkan tidak cocok dengan akun terdaftar.',
                        ])->withInput();
                    }
                } else {
                    $user = $existingUser;
                    \Illuminate\Support\Facades\Auth::login($user);
                }
            } else {
                $rawPassword = !empty($validated['password']) ? $validated['password'] : ($validated['nim'] . '@Ukm' . date('Y'));
                $user = \App\Models\User::create([
                    'name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'password' => \Illuminate\Support\Facades\Hash::make($rawPassword),
                    'role' => 'member',
                    'nim' => $validated['nim'],
                    'phone_number' => $validated['phone_whatsapp'],
                    'github_url' => $validated['github_url'] ?? null,
                    'avatar' => $avatarPath,
                ]);
                \Illuminate\Support\Facades\Auth::login($user);
            }
        } else {
            $updates = [];
            if ($avatarPath && !$user->avatar) {
                $updates['avatar'] = $avatarPath;
            }
            if (!$user->nim) {
                $updates['nim'] = $validated['nim'];
            }
            if (!$user->phone_number) {
                $updates['phone_number'] = $validated['phone_whatsapp'];
            }
            if (!empty($validated['github_url']) && !$user->github_url) {
                $updates['github_url'] = $validated['github_url'];
            }
            if (!empty($updates)) {
                $user->update($updates);
            }
        }

        if ($request->hasFile('file_ktm')) {
            $validated['file_ktm'] = $request->file('file_ktm')->store('recruitment/ktm', 'public');
        }

        if ($request->hasFile('file_cv')) {
            $validated['file_cv'] = $request->file('file_cv')->store('recruitment/cv', 'public');
        }

        $validated['user_id'] = $user->id;
        $validated['profile_photo'] = $avatarPath ?? $user->avatar;
        $validated['github_url'] = $validated['github_url'] ?? $user->github_url;
        $validated['registration_code'] = Recruitment::generateCode();
        $validated['status'] = 'pending';
        $validated['selection_stage'] = 'administrasi';

        $recruitment = Recruitment::create($validated);

        return redirect()->route('student.dashboard')
            ->with('success', 'Pendaftaran berhasil dikirim! Selamat datang di Portal Mahasiswa.');
    }

    public function success(string $code)
    {
        $applicant = Recruitment::with(['firstChoiceDivision', 'secondChoiceDivision'])
            ->where('registration_code', $code)
            ->firstOrFail();

        return view('recruitment.success', compact('applicant'));
    }

    public function status(Request $request)
    {
        $applicant = null;
        $search = $request->query('search');

        if ($search) {
            $applicant = Recruitment::with(['firstChoiceDivision', 'secondChoiceDivision'])
                ->where('nim', trim($search))
                ->orWhere('registration_code', trim($search))
                ->first();
        }

        return view('recruitment.status', compact('applicant', 'search'));
    }
}
