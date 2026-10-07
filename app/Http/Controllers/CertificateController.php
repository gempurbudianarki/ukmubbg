<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Member;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function verify(Request $request)
    {
        $code = trim($request->get('code', ''));
        $certificate = null;
        $member = null;
        $searched = false;

        if (!empty($code)) {
            $searched = true;

            // 1. Search for Member by NIM
            $member = Member::with(['division', 'user', 'recruitment'])
                ->where('nim', $code)
                ->first();

            // 2. If not found, search User NIM
            if (!$member) {
                $user = User::where('nim', $code)->first();
                if ($user) {
                    $member = Member::with(['division', 'user', 'recruitment'])
                        ->where('user_id', $user->id)
                        ->first();
                }
            }

            // 3. If not found, search Recruitment registration code or NIM
            if (!$member) {
                $recruitment = Recruitment::where('registration_code', $code)
                    ->orWhere('nim', $code)
                    ->first();
                if ($recruitment) {
                    $member = Member::with(['division', 'user', 'recruitment'])
                        ->where('recruitment_id', $recruitment->id)
                        ->orWhere('user_id', $recruitment->user_id)
                        ->first();
                }
            }

            // 4. Also search for Certificate
            $certificate = Certificate::where('certificate_code', $code)
                ->orWhere('recipient_nim', $code)
                ->first();
        }

        return view('certificates.verify', compact('certificate', 'member', 'searched', 'code'));
    }
}
