<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Hanya super_admin dan division_admin yang diizinkan masuk ke panel Admin CMS
        if (!in_array($user->role, ['super_admin', 'division_admin'])) {
            if ($user->role === 'member') {
                return redirect()->route('student.dashboard')
                    ->with('error', 'Akses ditolak! Halaman ini dikhususkan untuk Pengurus dan Administrator UKM.');
            }

            abort(403, 'Akses terbatas untuk Pengurus dan Administrator UKM.');
        }

        return $next($request);
    }
}
