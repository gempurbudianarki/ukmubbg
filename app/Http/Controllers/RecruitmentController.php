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
        $divisions = Division::where('is_recruitment_open', true)->get();

        // Optional preselected division from query parameter ?divisi=pemrograman
        $preselectedDivision = null;
        if ($request->filled('divisi')) {
            $preselectedDivision = Division::where('slug', $request->divisi)->where('is_recruitment_open', true)->first();
        }

        return view('recruitment.index', compact('status', 'isOpen', 'batch', 'deadline', 'startDate', 'endDate', 'closedMessage', 'divisions', 'preselectedDivision'));
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
            'phone_whatsapp' => 'required|string|max:25',
            'semester' => 'required|integer|min:1|max:14',
            'class_group' => 'required|string|max:20',
            'first_choice_division_id' => 'required|exists:divisions,id',
            'second_choice_division_id' => 'nullable|exists:divisions,id|different:first_choice_division_id',
            'reason_to_join' => 'required|string|min:20',
            'portfolio_url' => 'nullable|url|max:255',
            'file_ktm' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_cv' => 'nullable|file|mimes:pdf|max:3072',
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'nim.required' => 'NIM wajib diisi.',
            'email.required' => 'Email aktif wajib diisi.',
            'phone_whatsapp.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'first_choice_division_id.required' => 'Pilihan divisi utama wajib dipilih.',
            'second_choice_division_id.different' => 'Divisi pilihan kedua harus berbeda dengan pilihan utama.',
            'reason_to_join.min' => 'Alasan/motivasi bergabung minimal 20 karakter.',
            'portfolio_url.url' => 'Link portofolio harus berupa format URL valid (https://...).',
            'file_ktm.max' => 'Ukuran file KTM maksimal 2MB.',
            'file_cv.max' => 'Ukuran file CV maksimal 3MB.',
        ]);

        // Verify that selected divisions are currently open for recruitment
        $firstDiv = Division::where('id', $validated['first_choice_division_id'])->where('is_recruitment_open', true)->first();
        if (!$firstDiv) {
            return back()->withErrors(['first_choice_division_id' => 'Divisi pilihan utama saat ini sedang tidak membuka pendaftaran atau kuota telah terpenuhi.'])->withInput();
        }

        if (!empty($validated['second_choice_division_id'])) {
            $secondDiv = Division::where('id', $validated['second_choice_division_id'])->where('is_recruitment_open', true)->first();
            if (!$secondDiv) {
                return back()->withErrors(['second_choice_division_id' => 'Divisi pilihan kedua saat ini sedang tidak membuka pendaftaran atau kuota telah terpenuhi.'])->withInput();
            }
        }

        // Check if NIM has already registered in this batch
        $existing = Recruitment::where('nim', $validated['nim'])->first();
        if ($existing) {
            return redirect()->route('recruitment.status', ['search' => $validated['nim']])
                ->with('info', 'NIM ini sudah terdaftar sebelumnya. Anda dapat memantau status seleksi di sini.');
        }

        if ($request->hasFile('file_ktm')) {
            $validated['file_ktm'] = $request->file('file_ktm')->store('recruitment/ktm', 'public');
        }

        if ($request->hasFile('file_cv')) {
            $validated['file_cv'] = $request->file('file_cv')->store('recruitment/cv', 'public');
        }

        $validated['registration_code'] = Recruitment::generateCode();
        $validated['status'] = 'pending';
        $validated['selection_stage'] = 'administrasi';

        $recruitment = Recruitment::create($validated);

        return redirect()->route('recruitment.success', $recruitment->registration_code)
            ->with('success', 'Pendaftaran berhasil dikirim! Simpan kode pendaftaran Anda.');
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
