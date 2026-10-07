<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Masuk Akun - UKM Ilmu Komputer</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background: radial-gradient(circle at 50% 10%, #ffffff 0%, #f1f5f9 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            font-family: var(--font-sans, system-ui, -apple-system, sans-serif);
            color: #1e293b;
            overflow: hidden;
        }

        @media (max-height: 640px) {
            body {
                height: auto;
                min-height: 100vh;
                overflow-y: auto;
                padding: 1.5rem 1rem;
            }
        }

        .login-wrapper {
            width: 100%;
            max-width: 410px;
            margin: 0 auto;
        }

        /* Logo & Brand Header */
        .login-header {
            text-align: center;
            margin-bottom: 1.15rem;
        }

        .login-logo-link {
            display: inline-block;
            text-decoration: none;
            margin-bottom: 0.5rem;
            transition: transform 0.25s ease;
        }

        .login-logo-link:hover {
            transform: scale(1.06);
        }

        .login-logo-img {
            height: 58px;
            width: auto;
            max-width: 80px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.08));
        }

        .login-title {
            color: #0c2340;
            font-size: 1.45rem;
            font-weight: 850;
            letter-spacing: -0.02em;
            margin: 0 0 0.2rem 0;
            line-height: 1.2;
        }

        .login-subtitle {
            color: #64748b;
            font-size: 0.825rem;
            margin: 0;
            font-weight: 500;
            line-height: 1.35;
        }

        /* Card Container (White Claymorphism Compact) */
        .login-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 1.75rem 1.85rem;
            box-shadow: 0 15px 35px -5px rgba(15, 23, 42, 0.08), 0 4px 6px -4px rgba(15, 23, 42, 0.02);
            border: 1.5px solid rgba(226, 232, 240, 0.95);
            position: relative;
        }

        /* Form Controls */
        .form-row {
            margin-bottom: 0.95rem;
        }

        .form-label-custom {
            display: block;
            font-weight: 700;
            color: #1e293b;
            font-size: 0.8rem;
            margin-bottom: 0.35rem;
            letter-spacing: -0.01em;
        }

        .input-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-lead {
            position: absolute;
            left: 1rem;
            color: #94a3b8;
            font-size: 0.875rem;
            pointer-events: none;
            z-index: 2;
            transition: color 0.2s ease;
        }

        .input-field {
            width: 100%;
            height: 44px;
            padding: 0 0.85rem 0 2.65rem;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            font-size: 0.875rem;
            color: #0f172a;
            background: #f8fafc;
            box-shadow: var(--clay-debossed);
            transition: all 0.2s ease;
            outline: none;
        }

        .input-field:focus {
            background: #ffffff;
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }

        .input-box:focus-within .input-icon-lead {
            color: #0284c7;
        }

        .toggle-pw-btn {
            position: absolute;
            right: 0.85rem;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.3rem;
            font-size: 0.875rem;
            z-index: 2;
            transition: color 0.2s ease;
        }

        .toggle-pw-btn:hover {
            color: #334155;
        }

        /* Remember & Forgot Row */
        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.25rem;
            margin-bottom: 1.15rem;
            font-size: 0.775rem;
        }

        .remember-wrap {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #64748b;
            cursor: pointer;
            user-select: none;
            margin: 0;
            font-weight: 500;
        }

        .remember-checkbox {
            width: 15px;
            height: 15px;
            accent-color: #0284c7;
            cursor: pointer;
            margin: 0;
        }

        .forgot-link {
            color: #0284c7;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: #0369a1;
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-submit-login {
            width: 100%;
            height: 44px;
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            border: none;
            border-radius: 9999px;
            font-size: 0.9rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
        }

        .btn-submit-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(2, 132, 199, 0.45);
        }

        .btn-submit-login:active {
            transform: translateY(1px);
        }

        /* Bottom Footer in Card */
        .card-bottom-links {
            margin-top: 1.15rem;
            padding-top: 0.85rem;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            font-size: 0.8rem;
            color: #64748b;
        }

        .register-link {
            color: #0284c7;
            font-weight: 700;
            text-decoration: none;
            margin-left: 0.2rem;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        /* External Back Link */
        .back-home-wrap {
            text-align: center;
            margin-top: 0.95rem;
        }

        .back-home-link {
            color: #64748b;
            font-size: 0.8rem;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: color 0.2s ease;
        }

        .back-home-link:hover {
            color: #0c2340;
        }

        /* Modal Bantuan Lupa Password */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
        }

        .modal-card {
            background: #ffffff;
            border-radius: 18px;
            max-width: 420px;
            width: 100%;
            padding: 1.75rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            animation: modalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-icon-badge {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: #e0f2fe;
            color: #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin: 0 auto 0.85rem;
        }

        .whatsapp-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Header Prominen (Logo & Identitas) -->
        <div class="login-header">
            <a href="{{ route('home') }}" class="login-logo-link" title="Menuju Beranda UKM">
                <img src="{{ asset('images/logo.png') }}" alt="UKM Ilmu Komputer Logo" class="login-logo-img">
            </a>
            <h1 class="login-title">
                Portal Masuk Akun
            </h1>
            <p class="login-subtitle">
                UKM Ilmu Komputer &bull; Pengurus & Mahasiswa
            </p>
        </div>

        <!-- Kartu Formulir Presisi Compact -->
        <div class="login-card">
            @if ($errors->any())
                <div class="alert alert-error" style="font-size: 0.8rem; margin-bottom: 0.95rem; padding: 0.65rem 0.85rem; border-radius: 8px; display: flex; align-items: center; gap: 0.4rem;">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" style="font-size: 0.8rem; margin-bottom: 0.95rem; padding: 0.65rem 0.85rem; border-radius: 8px; display: flex; align-items: center; gap: 0.4rem;">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" novalidate>
                @csrf

                <!-- Input Email -->
                <div class="form-row">
                    <label class="form-label-custom" for="email">
                        Alamat Email Terdaftar
                    </label>
                    <div class="input-box">
                        <i class="fas fa-envelope input-icon-lead"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="input-field" placeholder="nama@ukmilkom.id atau email mahasiswa" required autofocus>
                    </div>
                </div>

                <!-- Input Password -->
                <div class="form-row">
                    <label class="form-label-custom" for="password">
                        Kata Sandi
                    </label>
                    <div class="input-box">
                        <i class="fas fa-lock input-icon-lead"></i>
                        <input type="password" id="password" name="password" class="input-field" style="padding-right: 2.5rem;" placeholder="Masukkan kata sandi Anda" required>
                        <button type="button" class="toggle-pw-btn" onclick="togglePasswordVisibility()" aria-label="Lihat kata sandi">
                            <i class="fas fa-eye" id="passwordEyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember & Forgot Password (Satu Baris Seimbang) -->
                <div class="options-row">
                    <label class="remember-wrap">
                        <input type="checkbox" name="remember" class="remember-checkbox">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                    <a href="javascript:void(0)" onclick="openForgotModal()" class="forgot-link">
                        Lupa kata sandi?
                    </a>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn-submit-login">
                    <span>Masuk ke Dashboard</span>
                    <i class="fas fa-arrow-right" style="font-size: 0.8rem;"></i>
                </button>
            </form>

            <!-- Tautan Registrasi -->
            <div class="card-bottom-links">
                Belum mendaftar sebagai anggota UKM? 
                <a href="{{ route('recruitment.index') }}" class="register-link">
                    Daftar di Sini &rarr;
                </a>
            </div>
        </div>

        <!-- Tombol Kembali ke Website Publik -->
        <div class="back-home-wrap">
            <a href="{{ route('home') }}" class="back-home-link">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Website Utama</span>
            </a>
        </div>
    </div>

    <!-- Modal Popup Bantuan Lupa Kata Sandi -->
    <div class="modal-overlay" id="forgotPasswordModal" onclick="closeForgotModalOnBackdrop(event)">
        <div class="modal-card">
            <div class="modal-icon-badge">
                <i class="fas fa-key"></i>
            </div>
            
            <h3 style="font-size: 1.15rem; font-weight: 850; color: #0c2340; text-align: center; margin: 0 0 0.35rem;">
                Bantuan Pemulihan Kata Sandi
            </h3>
            
            <p style="font-size: 0.825rem; color: #64748b; text-align: center; margin: 0; line-height: 1.5;">
                Untuk menjaga keamanan akun mahasiswa & pengurus, proses reset kata sandi diverifikasi langsung oleh administrator resmi UKM.
            </p>

            <div class="whatsapp-box">
                <div style="font-size: 0.8rem; font-weight: 700; color: #1e293b; margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.4rem;">
                    <i class="fab fa-whatsapp" style="color: #16a34a; font-size: 0.95rem;"></i>
                    Layanan WhatsApp Hotline Admin UKM:
                </div>
                <p style="font-size: 0.775rem; color: #64748b; margin: 0 0 0.75rem; line-height: 1.5;">
                    Sertakan informasi <strong>Nama Lengkap</strong>, <strong>NIM</strong>, dan <strong>Alamat Email</strong> terdaftar untuk verifikasi akun Anda.
                </p>
                <a href="https://wa.me/{{ \App\Models\Setting::get('contact_whatsapp', '6281234567890') }}?text=Halo%20Admin%20UKM%20Ilmu%20Komputer,%20saya%20memerlukan%20bantuan%20reset%20kata%20sandi%20akun%20saya." target="_blank" style="background: #16a34a; color: #ffffff; width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.4rem; font-weight: 700; text-decoration: none; border-radius: 8px; padding: 0.6rem; font-size: 0.825rem; box-shadow: 0 2px 6px rgba(22, 163, 74, 0.3);">
                    <i class="fab fa-whatsapp" style="font-size: 0.95rem;"></i>
                    <span>Hubungi Admin via WhatsApp</span>
                </a>
            </div>

            <div>
                <button type="button" onclick="closeForgotModal()" style="width: 100%; height: 38px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; color: #475569; font-weight: 700; font-size: 0.825rem; cursor: pointer; transition: background 0.2s ease;">
                    Tutup Jendela Bantuan
                </button>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('passwordEyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        function openForgotModal() {
            document.getElementById('forgotPasswordModal').style.display = 'flex';
        }

        function closeForgotModal() {
            document.getElementById('forgotPasswordModal').style.display = 'none';
        }

        function closeForgotModalOnBackdrop(e) {
            if (e.target.id === 'forgotPasswordModal') {
                closeForgotModal();
            }
        }
    </script>
</body>
</html>
