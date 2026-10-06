<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Recruitment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecruitmentAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Recruitment::with(['firstChoiceDivision', 'secondChoiceDivision']);

        if (!$user->isSuperAdmin()) {
            $query->where('first_choice_division_id', $user->division_id);
        } elseif ($request->filled('divisi')) {
            $query->where('first_choice_division_id', $request->divisi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', $keyword)
                  ->orWhere('nim', 'like', $keyword)
                  ->orWhere('registration_code', 'like', $keyword);
            });
        }

        $applicants = $query->latest()->paginate(15)->withQueryString();
        $divisions = Division::all();

        return view('admin.recruitment.index', compact('applicants', 'divisions', 'user'));
    }

    public function show(Recruitment $recruitment)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $recruitment->first_choice_division_id !== $user->division_id) {
            abort(403, 'Akses terbatas untuk pendaftar divisi lain.');
        }

        return view('admin.recruitment.show', compact('recruitment', 'user'));
    }

    public function updateStatus(Request $request, Recruitment $recruitment)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $recruitment->first_choice_division_id !== $user->division_id) {
            abort(403, 'Akses terbatas untuk pendaftar divisi lain.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,interview,accepted,rejected',
            'selection_stage' => 'nullable|in:administrasi,wawancara,diterima,ditolak',
            'interview_schedule' => 'nullable|date',
            'interview_location' => 'nullable|string|max:255',
            'admin_notes' => 'nullable|string',
        ]);

        if (empty($validated['selection_stage'])) {
            $validated['selection_stage'] = match ($validated['status']) {
                'pending' => 'administrasi',
                'interview' => 'wawancara',
                'accepted' => 'diterima',
                'rejected' => 'ditolak',
                default => 'administrasi',
            };
        }

        $recruitment->update($validated);

        return back()->with('success', 'Status pendaftar ' . $recruitment->full_name . ' berhasil diperbarui.');
    }

    public function convertToMember(Recruitment $recruitment)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $recruitment->first_choice_division_id !== $user->division_id) {
            abort(403, 'Akses terbatas untuk pendaftar divisi lain.');
        }

        if ($recruitment->status !== 'accepted') {
            return back()->with('error', 'Hanya pendaftar berstatus diterima (accepted) yang dapat dijadikan anggota.');
        }

        $existing = \App\Models\Member::where('nim', $recruitment->nim)
            ->orWhere('recruitment_id', $recruitment->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'Pendaftar ini sudah terdaftar sebagai anggota resmi UKM.');
        }

        \App\Models\Member::create([
            'recruitment_id' => $recruitment->id,
            'nim' => $recruitment->nim,
            'name' => $recruitment->full_name,
            'email' => $recruitment->email,
            'phone_number' => $recruitment->phone_whatsapp,
            'division_id' => $recruitment->first_choice_division_id,
            'batch_year' => date('Y'),
            'status' => 'aktif',
            'join_date' => now()->toDateString(),
            'notes' => 'Dikonversi otomatis dari jalur Open Recruitment (' . $recruitment->registration_code . ').',
        ]);

        return back()->with('success', 'Selamat! Pendaftar ' . $recruitment->full_name . ' resmi diangkat menjadi Anggota UKM.');
    }

    public function export(Request $request): StreamedResponse
    {
        $user = Auth::user();
        $query = Recruitment::with(['firstChoiceDivision', 'secondChoiceDivision']);

        if (!$user->isSuperAdmin()) {
            $query->where('first_choice_division_id', $user->division_id);
        } elseif ($request->filled('divisi')) {
            $query->where('first_choice_division_id', $request->divisi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applicants = $query->latest()->get();
        $filename = 'Data_Pendaftar_UKM_Ilkom_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($applicants) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Kode Registrasi',
                'NIM',
                'Nama Lengkap',
                'Email',
                'No WhatsApp',
                'Semester',
                'Kelas',
                'Pilihan 1',
                'Pilihan 2',
                'Alasan Bergabung',
                'Link Portofolio',
                'Status Seleksi',
                'Catatan Admin',
                'Tanggal Daftar'
            ]);

            foreach ($applicants as $app) {
                fputcsv($handle, [
                    $app->registration_code,
                    $app->nim,
                    $app->full_name,
                    $app->email,
                    $app->phone_whatsapp,
                    $app->semester,
                    $app->class_group,
                    $app->firstChoiceDivision?->name ?? '-',
                    $app->secondChoiceDivision?->name ?? '-',
                    $app->reason_to_join,
                    $app->portfolio_url ?? '-',
                    $app->status_label,
                    $app->admin_notes ?? '-',
                    $app->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
