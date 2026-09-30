<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificateAdminController extends Controller
{
    public function index()
    {
        $certificates = Certificate::latest('issue_date')->paginate(15);
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

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }
}
