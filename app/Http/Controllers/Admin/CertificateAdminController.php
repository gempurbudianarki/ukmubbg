<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificateAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::query();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('certificate_code', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('recipient_nim', 'like', "%{$search}%")
                  ->orWhere('event_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role_as', $request->input('role'));
        }

        $certificates = $query->latest('issue_date')->latest('id')->paginate(15)->withQueryString();

        return view('admin.certificates.index', compact('certificates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_nim' => 'nullable|string|max:30',
            'recipient_email' => 'nullable|email|max:100',
            'event_name' => 'required|string|max:255',
            'role_as' => 'required|string|max:100',
            'issue_date' => 'required|date',
        ]);

        $validated['certificate_code'] = 'CERT-ILKOM-' . date('Y') . '-' . strtoupper(Str::random(4));

        Certificate::create($validated);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'E-Sertifikat berhasil diterbitkan!');
    }

    public function edit(Certificate $certificate)
    {
        return view('admin.certificates.edit', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'certificate_code' => 'required|string|max:50|unique:certificates,certificate_code,' . $certificate->id,
            'recipient_name' => 'required|string|max:255',
            'recipient_nim' => 'nullable|string|max:30',
            'recipient_email' => 'nullable|email|max:100',
            'event_name' => 'required|string|max:255',
            'role_as' => 'required|string|max:100',
            'issue_date' => 'required|date',
        ]);

        $certificate->update($validated);

        return redirect()->route('admin.certificates.index')
            ->with('success', "Data sertifikat {$certificate->certificate_code} berhasil diperbarui!");
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }
}
