<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin CMS - UKM Ilmu Komputer</title>
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
</head>
<body style="background: linear-gradient(135deg, var(--slate-900) 0%, var(--primary-dark) 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">
    <div style="width: 100%; max-width: 440px;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); display: inline-flex; align-items: center; justify-content: center; color: #ffffff; margin-bottom: 1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 style="color: #ffffff; font-size: 1.6rem; font-weight: 800; letter-spacing: -0.02em;">
                Portal CMS Pengurus
            </h1>
            <p style="color: var(--slate-400); font-size: 0.9rem; margin-top: 0.25rem;">
                UKM Program Studi Ilmu Komputer
            </p>
        </div>

        <div class="card" style="box-shadow: var(--shadow-xl); border: none;">
            @if ($errors->any())
                <div class="alert alert-error" style="font-size: 0.85rem;">
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" style="font-size: 0.85rem;">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email Pengurus</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="admin@ukmilkom.id" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.85rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: var(--slate-600); cursor: pointer;">
                        <input type="checkbox" name="remember">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">
                    Masuk ke Dashboard &rarr;
                </button>
            </form>

            <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--slate-100); font-size: 0.8rem; color: var(--slate-500); line-height: 1.6;">
                <strong>Akun Uji Coba:</strong><br>
                &bull; Super Admin: <code>admin@ukmilkom.id</code> / <code>admin123</code><br>
                &bull; Admin Pemrograman: <code>pemrograman@ukmilkom.id</code> / <code>pemrograman123</code><br>
                &bull; Admin Multimedia: <code>multimedia@ukmilkom.id</code> / <code>multimedia123</code><br>
                &bull; Admin IoT: <code>iot@ukmilkom.id</code> / <code>iot123</code><br>
                &bull; Admin Cyber: <code>cyber@ukmilkom.id</code> / <code>cyber123</code>
            </div>
        </div>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ route('home') }}" style="color: var(--slate-400); font-size: 0.875rem;">
                &larr; Kembali ke Website Utama
            </a>
        </div>
    </div>
</body>
</html>
