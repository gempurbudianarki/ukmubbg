@extends('layouts.app')

@section('title', 'Pendaftaran Anggota Baru - UKM Ilmu Komputer')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Wizard Stepper Header */
    .wizard-stepper-container {
        margin-bottom: 2.5rem;
        background: #ffffff;
        border: none;
        border-radius: var(--radius-xl);
        padding: 1.75rem 2.25rem;
        box-shadow: var(--clay-card);
    }
    .wizard-progress-bar-bg {
        height: 8px;
        background: var(--bg-body);
        box-shadow: var(--clay-debossed);
        border-radius: 9999px;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .wizard-progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #2563eb, #0284c7);
        border-radius: 9999px;
        transition: width 0.35s ease;
        width: 33.33%;
    }
    .wizard-steps {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        position: relative;
    }
    .wizard-step-node {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        cursor: pointer;
        user-select: none;
        flex: 1;
        transition: all 0.2s ease;
    }
    .wizard-step-circle {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: var(--bg-body);
        border: none;
        box-shadow: var(--clay-pill);
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        flex-shrink: 0;
        transition: all 0.25s ease;
    }
    .wizard-step-node.active .wizard-step-circle {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border: none;
        color: #ffffff;
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.35);
    }
    .wizard-step-node.completed .wizard-step-circle {
        background: #10b981;
        border: none;
        color: #ffffff;
        box-shadow: 0 6px 14px rgba(16, 185, 129, 0.3);
    }
    .wizard-step-meta {
        display: flex;
        flex-direction: column;
    }
    .wizard-step-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--slate-700);
        transition: color 0.2s ease;
    }
    .wizard-step-node.active .wizard-step-title {
        color: #2563eb;
    }
    .wizard-step-node.completed .wizard-step-title {
        color: #10b981;
    }
    .wizard-step-desc {
        font-size: 0.75rem;
        color: var(--slate-400);
    }

    @media (max-width: 768px) {
        .wizard-stepper-container {
            padding: 1.25rem 1rem;
        }
        .wizard-step-meta {
            display: none;
        }
        .wizard-step-node {
            justify-content: center;
        }
        .wizard-step-circle {
            width: 38px;
            height: 38px;
            font-size: 0.875rem;
        }
    }

    /* Step content transitions */
    .step-section {
        display: none;
        animation: fadeInStep 0.3s ease-in-out forwards;
    }
    .step-section.active {
        display: block;
    }
    @keyframes fadeInStep {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Live Summary Card */
    .summary-card {
        background: #ffffff;
        border-radius: var(--radius-xl);
        padding: 1.75rem 2rem;
        color: var(--slate-900);
        margin-top: 1.75rem;
        margin-bottom: 1.75rem;
        border: none;
        box-shadow: var(--clay-card);
    }
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-top: 1.25rem;
    }
    .summary-item-label {
        font-size: 0.75rem;
        color: var(--slate-400);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.05em;
    }
    .summary-item-val {
        font-size: 0.975rem;
        font-weight: 700;
        color: var(--slate-800);
        margin-top: 0.25rem;
        word-break: break-all;
    }

    /* Validation highlight */
    .form-control.field-invalid {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
    }
</style>
@endsection

@section('content')
<div style="padding: 3.5rem 0 1.5rem;">
    <div class="container-narrow" style="text-align: center;">
        <span class="badge badge-success" style="margin-bottom: 0.75rem;">
            <span class="badge-pulse"></span>
            <span>Open Recruitment &bull; {{ $batch }}</span>
        </span>
        <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.03em; margin-bottom: 0.5rem;">
            Formulir Pendaftaran Anggota Baru
        </h1>
        <p style="color: var(--slate-600); font-size: 1.05rem; line-height: 1.6; max-width: 650px; margin: 0 auto;">
            Satu pintu pendaftaran resmi mahasiswa Program Studi Ilmu Komputer, Fakultas Sains, Teknologi, dan Ilmu Kesehatan (FSTIK) UBBG. Pengisian formulir dibagi menjadi <strong>3 tahapan mudah</strong>: Biodata, Pilihan Divisi, dan Unggah Berkas.
        </p>
    </div>
</div>

<div class="container-narrow" style="padding: 0.5rem 1.5rem 5rem;">
    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #991b1b; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-circle-exclamation" style="font-size: 1.25rem;"></i>
            <span style="font-weight: 500; font-size: 0.95rem;">{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #991b1b;">
            <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; margin-bottom: 0.4rem;">
                <i class="fas fa-circle-exclamation"></i> Terdapat data yang belum sesuai:
            </div>
            <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.85rem;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (!$isOpen)
        <!-- Registration Closed State -->
        <div class="card" style="text-align: center; padding: 3.5rem 2rem; border-left: 6px solid var(--warning); border-radius: var(--radius-xl); box-shadow: var(--clay-card); border-top: none; border-right: none; border-bottom: none;">
            <div style="width: 68px; height: 68px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.75rem; box-shadow: var(--clay-pill);">
                <i class="fas fa-lock"></i>
            </div>
            <div class="badge badge-neutral" style="margin-bottom: 0.85rem; box-shadow: var(--clay-pill);">
                Status: Pendaftaran Sedang Ditutup
            </div>
            <h2 style="font-size: 1.85rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.75rem; letter-spacing: -0.02em;">
                Pendaftaran Sedang Ditutup
            </h2>
            <p style="color: var(--slate-600); margin-bottom: 2rem; max-width: 540px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                {{ $closedMessage ?? 'Periode pendaftaran anggota baru saat ini sedang tidak aktif atau batas waktu gelombang telah berakhir.' }}
            </p>
            @if(!empty($startDate) || !empty($endDate))
                <div style="background: var(--bg-body); box-shadow: var(--clay-debossed); border-radius: var(--radius-md); padding: 0.85rem 1.5rem; display: inline-flex; gap: 1.5rem; font-size: 0.875rem; color: var(--slate-700); margin-bottom: 2rem;">
                    @if(!empty($startDate))
                        <div><strong>Mulai:</strong> {{ $startDate }}</div>
                    @endif
                    @if(!empty($endDate))
                        <div><strong>Batas Akhir:</strong> {{ $endDate }}</div>
                    @endif
                </div>
                <br>
            @endif
            <a href="{{ route('recruitment.status') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; border-radius: var(--radius-full);">
                <span>Cek Status Seleksi Anda</span>
                <span>&rarr;</span>
            </a>
        </div>
    @else
        <!-- Multi-Step Interactive Form Card -->
        <div class="wizard-stepper-container">
            <!-- Progress Line Indicator -->
            <div class="wizard-progress-bar-bg">
                <div class="wizard-progress-bar-fill" id="wizardProgressFill"></div>
            </div>

            <!-- Steps Head Navigator -->
            <div class="wizard-steps">
                <!-- Step 1 Head -->
                <div class="wizard-step-node active" id="stepNode1" onclick="jumpToStep(1)">
                    <div class="wizard-step-circle" id="circleStep1">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="wizard-step-meta">
                        <div class="wizard-step-title">Tahap 1</div>
                        <div class="wizard-step-desc">Biodata & Akun</div>
                    </div>
                </div>

                <!-- Step 2 Head -->
                <div class="wizard-step-node" id="stepNode2" onclick="jumpToStep(2)">
                    <div class="wizard-step-circle" id="circleStep2">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="wizard-step-meta">
                        <div class="wizard-step-title">Tahap 2</div>
                        <div class="wizard-step-desc">Pilihan Divisi</div>
                    </div>
                </div>

                <!-- Step 3 Head -->
                <div class="wizard-step-node" id="stepNode3" onclick="jumpToStep(3)">
                    <div class="wizard-step-circle" id="circleStep3">
                        <i class="fas fa-file-shield"></i>
                    </div>
                    <div class="wizard-step-meta">
                        <div class="wizard-step-title">Tahap 3</div>
                        <div class="wizard-step-desc">Berkas & Konfirmasi</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 2.75rem 2.5rem; border-radius: var(--radius-xl); box-shadow: var(--clay-card); border: none;">
            <form id="recruitmentMultiStepForm" action="{{ route('recruitment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- ============================================== -->
                <!-- TAHAP 1: BIODATA MAHASISWA & AKUN LOGIN        -->
                <!-- ============================================== -->
                <div class="step-section active" id="stepSection1">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.75rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--slate-200);">
                        <div>
                            <span class="badge badge-info" style="font-size: 0.775rem; margin-bottom: 0.4rem;">
                                Tahap 1 dari 3: Identitas Diri
                            </span>
                            <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.25rem;">
                                Informasi Biodata & Akun Mahasiswa
                            </h3>
                            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                                Lengkapi data sesuai Kartu Tanda Mahasiswa aktif Anda. Akun login akan dibuat otomatis.
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 1.5rem; font-weight: 800; color: #2563eb; font-family: var(--font-mono);">01</span>
                            <span style="font-size: 0.8rem; color: var(--slate-400); display: block;">/ 03 Tahap</span>
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label class="form-label" for="full_name">Nama Lengkap Mahasiswa *</label>
                        <input type="text" id="full_name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" placeholder="Contoh: Bintang Ramadhan" required>
                        @error('full_name') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <!-- NIM & Semester -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label" for="nim">Nomor Induk Mahasiswa (NIM) *</label>
                            <input type="text" id="nim" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" placeholder="Contoh: 220104012" required>
                            @error('nim') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="semester">Semester Saat Ini *</label>
                            <select id="semester" name="semester" class="form-control @error('semester') is-invalid @enderror" required>
                                <option value="">Pilih Semester</option>
                                @for ($s = 1; $s <= 8; $s++)
                                    <option value="{{ $s }}" {{ old('semester') == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
                                @endfor
                            </select>
                            @error('semester') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div class="form-group">
                        <label class="form-label" for="phone_whatsapp">Nomor WhatsApp Aktif *</label>
                        <input type="text" id="phone_whatsapp" name="phone_whatsapp" class="form-control @error('phone_whatsapp') is-invalid @enderror" value="{{ old('phone_whatsapp') }}" placeholder="Contoh: 081298765432" required>
                        <small style="color: var(--slate-400); font-size: 0.775rem;">Panitia akan menghubungi Anda melalui WhatsApp untuk konfirmasi seleksi & wawancara.</small>
                        @error('phone_whatsapp') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <!-- Pas Foto Profil -->
                    <div class="form-group" style="background: var(--slate-50); border: 1px dashed var(--slate-300); border-radius: var(--radius-lg); padding: 1.25rem; margin-bottom: 1.5rem;">
                        <div style="display: flex; gap: 1.25rem; align-items: center; flex-wrap: wrap;">
                            <div id="photoPreviewContainer" style="width: 70px; height: 70px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 2px solid #cbd5e1; flex-shrink: 0;">
                                <i class="fas fa-camera" style="font-size: 1.5rem; color: #94a3b8;" id="cameraPlaceholderIcon"></i>
                                <img id="photoPreviewImg" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                            </div>
                            <div style="flex: 1; min-width: 240px;">
                                <label class="form-label" for="profile_photo" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem;">
                                    <span style="font-weight: 700; color: #0f172a;"><i class="fas fa-image" style="color: #2563eb; margin-right: 0.4rem;"></i> Pas Foto Resmi Mahasiswa</span>
                                    <span style="font-size: 0.75rem; color: var(--slate-400);">Maks 2MB (JPG/PNG/WEBP)</span>
                                </label>
                                <input type="file" id="profile_photo" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/jpeg,image/png,image/webp" onchange="previewProfilePhoto(this)">
                                <p style="font-size: 0.75rem; color: var(--slate-500); margin-top: 0.35rem; margin-bottom: 0;">
                                    Foto setengah badan formal. Foto ini otomatis tampil di <strong>KTA Digital Mahasiswa</strong> dan buku data anggota UKM.
                                </p>
                            </div>
                        </div>
                        @error('profile_photo') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <!-- Akun & Akses Portal -->
                    <div style="background: linear-gradient(135deg, rgba(37,99,235,0.04), rgba(14,165,233,0.04)); border: 1px solid rgba(37,99,235,0.18); border-radius: var(--radius-lg); padding: 1.25rem; margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <span style="display: inline-flex; width: 24px; height: 24px; border-radius: 6px; background: #2563eb; color: #fff; align-items: center; justify-content: center; font-size: 0.75rem;"><i class="fas fa-lock"></i></span>
                            <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--slate-900); margin: 0;">Akun & Akses Portal Mahasiswa</h4>
                        </div>
                        <p style="font-size: 0.8rem; color: var(--slate-600); margin-bottom: 1rem;">
                            Akun ini akan langsung aktif agar Anda dapat login ke <strong>Portal Mahasiswa</strong>, memantau seleksi, dan mengunduh KTA Digital.
                        </p>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label" for="email">Alamat Email Aktif (ID Login) *</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()?->email) }}" placeholder="Contoh: nama@student.ac.id" required {{ auth()->check() ? 'readonly' : '' }}>
                            @error('email') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        @guest
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" for="password">Kata Sandi Baru *</label>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                                @error('password') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" for="password_confirmation">Ulangi Kata Sandi *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi kata sandi" required>
                            </div>
                        </div>
                        @endguest
                    </div>

                    <!-- Kelas / Rombel -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="class_group">Kelas / Rombel Kuliah (Opsional)</label>
                        <input type="text" id="class_group" name="class_group" class="form-control @error('class_group') is-invalid @enderror" value="{{ old('class_group') }}" placeholder="Contoh: IF-22A atau Reguler Pagi">
                        @error('class_group') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <!-- Action Step 1 Footer -->
                    <div style="margin-top: 2.25rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-200); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <div style="font-size: 0.8rem; color: var(--slate-500);">
                            <i class="fas fa-shield-halved" style="color: #10b981; margin-right: 0.35rem;"></i>
                            Data pendaftaran Anda terlindungi dan terenkripsi.
                        </div>
                        <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(2)" style="display: inline-flex; align-items: center; gap: 0.6rem; font-weight: 700; padding: 0.85rem 1.75rem;">
                            <span>Lanjut ke Tahap 2: Pilihan Divisi</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- TAHAP 2: PILIHAN BIDANG DIVISI & MOTIVASI      -->
                <!-- ============================================== -->
                <div class="step-section" id="stepSection2">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.75rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--slate-200);">
                        <div>
                            <span class="badge badge-info" style="font-size: 0.775rem; margin-bottom: 0.4rem;">
                                Tahap 2 dari 3: Penjurusan
                            </span>
                            <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.25rem;">
                                Pilihan Bidang Divisi & Motivasi Riset
                            </h3>
                            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                                Tentukan fokus bidang riset teknologi yang ingin Anda kembangkan bersama UKM Ilmu Komputer.
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 1.5rem; font-weight: 800; color: #2563eb; font-family: var(--font-mono);">02</span>
                            <span style="font-size: 0.8rem; color: var(--slate-400); display: block;">/ 03 Tahap</span>
                        </div>
                    </div>

                    <!-- Divisi Utama & Cadangan -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label" for="first_choice_division_id">Divisi Pilihan Utama *</label>
                            <select id="first_choice_division_id" name="first_choice_division_id" class="form-control @error('first_choice_division_id') is-invalid @enderror" required onchange="updateDivisionPreview()">
                                <option value="">-- Pilih Divisi Utama --</option>
                                @foreach ($divisions as $d)
                                    <option value="{{ $d->id }}" {{ (old('first_choice_division_id', optional($preselectedDivision)->id) == $d->id) ? 'selected' : '' }}>
                                        {{ $d->name }}{{ $d->recruitment_quota ? ' (Kuota: ' . $d->recruitment_quota . ')' : '' }}{{ $d->recruitment_notes ? ' • ' . $d->recruitment_notes : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <small style="color: var(--slate-400); font-size: 0.75rem;">Divisi utama yang menjadi prioritas seleksi dan wawancara Anda.</small>
                            @error('first_choice_division_id') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="second_choice_division_id">Divisi Pilihan Kedua (Opsional)</label>
                            <select id="second_choice_division_id" name="second_choice_division_id" class="form-control @error('second_choice_division_id') is-invalid @enderror">
                                <option value="">-- Bebas / Tidak Memilih --</option>
                                @foreach ($divisions as $d)
                                    <option value="{{ $d->id }}" {{ old('second_choice_division_id') == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}{{ $d->recruitment_quota ? ' (Kuota: ' . $d->recruitment_quota . ')' : '' }}{{ $d->recruitment_notes ? ' • ' . $d->recruitment_notes : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <small style="color: var(--slate-400); font-size: 0.75rem;">Pilihan alternatif jika kuota divisi utama telah terpenuhi.</small>
                            @error('second_choice_division_id') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Motivasi & Komitmen -->
                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                            <label class="form-label" for="reason_to_join" style="margin-bottom: 0;">Motivasi & Komitmen Bergabung *</label>
                            <span id="charCountDisplay" style="font-size: 0.75rem; color: var(--slate-400); font-family: var(--font-mono);">
                                <span id="reasonCharCount">0</span> / 20 karakter minimal
                            </span>
                        </div>
                        <textarea id="reason_to_join" name="reason_to_join" rows="5" class="form-control @error('reason_to_join') is-invalid @enderror" placeholder="Ceritakan ketertarikan Anda pada divisi yang dipilih, pengalaman relevan sebelumnya, dan apa yang ingin Anda capai bersama UKM..." required oninput="countReasonChars(this)">{{ old('reason_to_join') }}</textarea>
                        <small style="color: var(--slate-400); font-size: 0.775rem;">Uraikan minat Anda dengan jelas (minimal 20 karakter). Hal ini akan menjadi bahan pertimbangan penguji saat sesi wawancara.</small>
                        @error('reason_to_join') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <!-- Action Step 2 Footer -->
                    <div style="margin-top: 2.25rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-200); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <button type="button" class="btn btn-outline btn-lg" onclick="prevStep(1)" style="display: inline-flex; align-items: center; gap: 0.6rem; padding: 0.85rem 1.5rem;">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali ke Tahap 1: Biodata</span>
                        </button>
                        <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(3)" style="display: inline-flex; align-items: center; gap: 0.6rem; font-weight: 700; padding: 0.85rem 1.75rem;">
                            <span>Lanjut ke Tahap 3: Unggah Berkas</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- TAHAP 3: UNGGAH BERKAS, PORTOFOLIO & REVIEW    -->
                <!-- ============================================== -->
                <div class="step-section" id="stepSection3">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.75rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--slate-200);">
                        <div>
                            <span class="badge badge-info" style="font-size: 0.775rem; margin-bottom: 0.4rem;">
                                Tahap 3 dari 3: Berkas & Konfirmasi
                            </span>
                            <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.25rem;">
                                Unggah Berkas & Konfirmasi Pendaftaran
                            </h3>
                            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                                Lampirkan kartu tanda mahasiswa aktif dan link portofolio (jika ada), lalu periksa kembali kebenaran data Anda.
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 1.5rem; font-weight: 800; color: #10b981; font-family: var(--font-mono);">03</span>
                            <span style="font-size: 0.8rem; color: var(--slate-400); display: block;">/ 03 Tahap</span>
                        </div>
                    </div>

                    <!-- Upload Berkas KTM & CV -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label" for="file_ktm">Upload Foto KTM (JPG, PNG, atau PDF)</label>
                            <input type="file" id="file_ktm" name="file_ktm" class="form-control" accept=".jpg,.jpeg,.png,.pdf" onchange="updateKtmPreview(this)">
                            <small style="color: var(--slate-400); font-size: 0.75rem;">Kartu Tanda Mahasiswa aktif, maksimal 2MB.</small>
                            @error('file_ktm') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="file_cv">Upload CV / Resume Singkat (Opsional, PDF)</label>
                            <input type="file" id="file_cv" name="file_cv" class="form-control" accept=".pdf">
                            <small style="color: var(--slate-400); font-size: 0.75rem;">Format dokumen PDF, maksimal 3MB.</small>
                            @error('file_cv') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- GitHub & Portfolio URLs -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label" for="github_url">Tautan Profil GitHub (Opsional)</label>
                            <input type="url" id="github_url" name="github_url" class="form-control @error('github_url') is-invalid @enderror" value="{{ old('github_url', auth()->user()?->github_url) }}" placeholder="https://github.com/username">
                            <small style="color: var(--slate-400); font-size: 0.75rem;">Sangat disukai untuk pendaftar divisi pemrograman & cyber security.</small>
                            @error('github_url') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="portfolio_url">Link Portofolio / Karya Lainnya (Opsional)</label>
                            <input type="url" id="portfolio_url" name="portfolio_url" class="form-control @error('portfolio_url') is-invalid @enderror" value="{{ old('portfolio_url') }}" placeholder="https://dribbble.com, Behance, Google Drive">
                            <small style="color: var(--slate-400); font-size: 0.75rem;">Link karya desain, sertifikat, atau repositori proyek nyata.</small>
                            @error('portfolio_url') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Ringkasan Konfirmasi Live Preview Card -->
                    <div class="summary-card">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-clipboard-check" style="color: #38bdf8; font-size: 1.15rem;"></i>
                                <span style="font-weight: 800; font-size: 1rem; letter-spacing: -0.01em;">Ringkasan Data Pendaftaran Anda</span>
                            </div>
                            <span class="badge" style="background: rgba(56, 189, 248, 0.2); color: #38bdf8; font-size: 0.75rem;">Siap Dikirim</span>
                        </div>

                        <div class="summary-grid">
                            <div>
                                <div class="summary-item-label">Nama Mahasiswa</div>
                                <div class="summary-item-val" id="summaryName">-</div>
                            </div>
                            <div>
                                <div class="summary-item-label">NIM & Semester</div>
                                <div class="summary-item-val" id="summaryNimSemester">-</div>
                            </div>
                            <div>
                                <div class="summary-item-label">Divisi Pilihan Utama</div>
                                <div class="summary-item-val" id="summaryDivision" style="color: #38bdf8;">-</div>
                            </div>
                            <div>
                                <div class="summary-item-label">Email ID Login</div>
                                <div class="summary-item-val" id="summaryEmail">-</div>
                            </div>
                        </div>

                        <div style="margin-top: 1rem; padding-top: 0.85rem; border-top: 1px solid rgba(255, 255, 255, 0.1); font-size: 0.8rem; color: #cbd5e1; display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" id="agreementCheck" required style="width: 16px; height: 16px; cursor: pointer;">
                            <label for="agreementCheck" style="margin: 0; cursor: pointer;">
                                Saya menyatakan bahwa seluruh informasi di atas adalah benar dan saya siap mengikuti alur seleksi & wawancara UKM Ilmu Komputer.
                            </label>
                        </div>
                    </div>

                    <!-- Action Step 3 Footer -->
                    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-200); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <button type="button" class="btn btn-outline btn-lg" onclick="prevStep(2)" style="display: inline-flex; align-items: center; gap: 0.6rem; padding: 0.85rem 1.5rem;">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali ke Tahap 2: Pilihan Divisi</span>
                        </button>
                        <button type="submit" id="submitFormBtn" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #10b981, #059669); border-color: #10b981; font-weight: 800; padding: 0.95rem 2.25rem; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);">
                            <i class="fas fa-paper-plane" style="margin-right: 0.5rem;"></i>
                            <span>Kirim Formulir Pendaftaran Sekarang &rarr;</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 3;

    function updateStepUI(step) {
        // Toggle active section
        for (let i = 1; i <= totalSteps; i++) {
            const section = document.getElementById('stepSection' + i);
            const node = document.getElementById('stepNode' + i);
            const circle = document.getElementById('circleStep' + i);

            if (!section || !node || !circle) continue;

            if (i === step) {
                section.classList.add('active');
                node.classList.add('active');
                node.classList.remove('completed');
                circle.innerHTML = getStepDefaultIcon(i);
            } else if (i < step) {
                section.classList.remove('active');
                node.classList.remove('active');
                node.classList.add('completed');
                circle.innerHTML = '<i class="fas fa-check"></i>';
            } else {
                section.classList.remove('active');
                node.classList.remove('active');
                node.classList.remove('completed');
                circle.innerHTML = getStepDefaultIcon(i);
            }
        }

        // Update progress bar
        const progressFill = document.getElementById('wizardProgressFill');
        if (progressFill) {
            const pct = (step / totalSteps) * 100;
            progressFill.style.width = pct + '%';
        }

        // Update Summary if arriving at step 3
        if (step === 3) {
            refreshSummary();
        }

        // Scroll smoothly to form top
        const container = document.querySelector('.wizard-stepper-container');
        if (container) {
            container.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function getStepDefaultIcon(step) {
        if (step === 1) return '<i class="fas fa-user"></i>';
        if (step === 2) return '<i class="fas fa-layer-group"></i>';
        if (step === 3) return '<i class="fas fa-file-shield"></i>';
        return step;
    }

    function validateStep1() {
        const fullName = document.getElementById('full_name');
        const nim = document.getElementById('nim');
        const semester = document.getElementById('semester');
        const phone = document.getElementById('phone_whatsapp');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');

        let isValid = true;
        let firstInvalid = null;

        function markField(input, valid, errorMsg) {
            if (!input) return;
            if (!valid) {
                input.classList.add('field-invalid');
                isValid = false;
                if (!firstInvalid) firstInvalid = input;
            } else {
                input.classList.remove('field-invalid');
            }
        }

        markField(fullName, fullName && fullName.value.trim().length > 0);
        markField(nim, nim && nim.value.trim().length > 0);
        markField(semester, semester && semester.value !== '');
        markField(phone, phone && phone.value.trim().length >= 8);
        markField(email, email && email.checkValidity() && email.value.trim().length > 0);

        if (password) {
            markField(password, password.value.length >= 8);
            if (passwordConfirm) {
                markField(passwordConfirm, passwordConfirm.value === password.value && passwordConfirm.value.length >= 8);
            }
        }

        if (!isValid && firstInvalid) {
            firstInvalid.focus();
            firstInvalid.reportValidity();
            return false;
        }

        return true;
    }

    function validateStep2() {
        const division = document.getElementById('first_choice_division_id');
        const reason = document.getElementById('reason_to_join');

        let isValid = true;
        let firstInvalid = null;

        function markField(input, valid) {
            if (!input) return;
            if (!valid) {
                input.classList.add('field-invalid');
                isValid = false;
                if (!firstInvalid) firstInvalid = input;
            } else {
                input.classList.remove('field-invalid');
            }
        }

        markField(division, division && division.value !== '');
        markField(reason, reason && reason.value.trim().length >= 20);

        if (!isValid && firstInvalid) {
            firstInvalid.focus();
            firstInvalid.reportValidity();
            return false;
        }

        return true;
    }

    function nextStep(target) {
        if (target === 2) {
            if (!validateStep1()) return;
        } else if (target === 3) {
            if (!validateStep2()) return;
        }
        currentStep = target;
        updateStepUI(currentStep);
    }

    function prevStep(target) {
        currentStep = target;
        updateStepUI(currentStep);
    }

    function jumpToStep(target) {
        if (target < currentStep) {
            currentStep = target;
            updateStepUI(currentStep);
        } else if (target === 2 && currentStep === 1) {
            nextStep(2);
        } else if (target === 3) {
            if (currentStep === 1) {
                if (validateStep1()) {
                    currentStep = 2;
                    if (validateStep2()) {
                        currentStep = 3;
                        updateStepUI(3);
                    } else {
                        updateStepUI(2);
                    }
                }
            } else if (currentStep === 2) {
                nextStep(3);
            }
        }
    }

    function refreshSummary() {
        const nameVal = document.getElementById('full_name')?.value || '-';
        const nimVal = document.getElementById('nim')?.value || '-';
        const semVal = document.getElementById('semester')?.value;
        const emailVal = document.getElementById('email')?.value || '-';

        const divSelect = document.getElementById('first_choice_division_id');
        let divText = '-';
        if (divSelect && divSelect.selectedIndex > 0) {
            divText = divSelect.options[divSelect.selectedIndex].text.split('(')[0].trim();
        }

        document.getElementById('summaryName').innerText = nameVal;
        document.getElementById('summaryNimSemester').innerText = nimVal + (semVal ? ' (Semester ' + semVal + ')' : '');
        document.getElementById('summaryDivision').innerText = divText;
        document.getElementById('summaryEmail').innerText = emailVal;
    }

    function previewProfilePhoto(input) {
        const previewImg = document.getElementById('photoPreviewImg');
        const placeholderIcon = document.getElementById('cameraPlaceholderIcon');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (previewImg) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                }
                if (placeholderIcon) {
                    placeholderIcon.style.display = 'none';
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function countReasonChars(textarea) {
        const len = textarea.value.length;
        const countSpan = document.getElementById('reasonCharCount');
        const display = document.getElementById('charCountDisplay');
        if (countSpan) countSpan.innerText = len;
        if (display) {
            if (len >= 20) {
                display.style.color = '#10b981';
            } else {
                display.style.color = 'var(--slate-400)';
            }
        }
    }

    function updateDivisionPreview() {
        // Can trigger live update if needed
    }

    function updateKtmPreview(input) {
        // Handled
    }

    // Auto-detect errors from backend and navigate to relevant step
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->has('first_choice_division_id') || $errors->has('second_choice_division_id') || $errors->has('reason_to_join'))
            currentStep = 2;
        @elseif ($errors->has('file_ktm') || $errors->has('file_cv') || $errors->has('github_url') || $errors->has('portfolio_url'))
            currentStep = 3;
        @else
            currentStep = 1;
        @endif

        updateStepUI(currentStep);

        const reasonEl = document.getElementById('reason_to_join');
        if (reasonEl) {
            countReasonChars(reasonEl);
        }
    });
</script>
@endsection
