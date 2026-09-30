<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function verify(Request $request)
    {
        $code = trim($request->get('code', ''));
        $certificate = null;
        $searched = false;

        if (!empty($code)) {
            $searched = true;
            $certificate = Certificate::where('certificate_code', $code)
                ->orWhere('recipient_nim', $code)
                ->first();
        }

        return view('certificates.verify', compact('certificate', 'searched', 'code'));
    }
}
