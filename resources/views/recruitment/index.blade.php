@extends('layouts.app')

@section('title', 'Pendaftaran Anggota Baru - UKM Ilmu Komputer')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Wizard Stepper Container */
    .wizard-stepper-container {
        margin-bottom: 2rem;
        background: #ffffff;
        border: none;
        border-radius: var(--radius-xl);
        padding: 1.5rem 2rem;
        box-shadow: var(--clay-card);
    }
    .wizard-progress-bar-bg {
        height: 8px;
        background: var(--bg-body);
        box-shadow: var(--clay-debossed);
        border-radius: 9999px;
        margin-bottom: 1.25rem;
        position: relative;
        overflow: hidden;
    }
    .wizard-progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #2563eb, #0284c7);
        border-radius: 9999px;
        transition: width 0.4s cubic-bezier(0.16, 1, 0.3, 1);
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
        transition: all 0.25s ease;
    }
    .wizard-step-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--bg-body);
        border: none;
        box-shadow: var(--clay-pill);
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.95rem;
        flex-shrink: 0;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .wizard-step-node.active .wizard-step-circle {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #ffffff;
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.35);
        transform: scale(1.06);
    }
    .wizard-step-node.completed .wizard-step-circle {
        background: #10b981;
        color: #ffffff;
        box-shadow: 0 6px 14px rgba(16, 185, 129, 0.3);
    }
    .wizard-step-meta {
        display: flex;
        flex-direction: column;
    }
    .wizard-step-title {
        font-size: 0.925rem;
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

    /* Permanently hide spinning loading icons */
    .field-status-icon.status-loading {
        display: none !important;
    }

    @media (max-width: 768px) {
        .wizard-stepper-container {
            padding: 1.15rem 0.85rem;
            margin-bottom: 1.25rem;
        }
        .wizard-step-meta {
            display: none;
        }
        .wizard-step-node {
            justify-content: center;
        }
        .wizard-step-circle {
            width: 36px;
            height: 36px;
            font-size: 0.825rem;
        }
        .card {
            padding: 1.5rem 1.15rem !important;
            border-radius: var(--radius-lg) !important;
        }
        .grid-2col {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
        .division-cards-grid {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
        .dropzone-clay {
            padding: 1.25rem 1rem !important;
        }
        .dropzone-clay .dropzone-icon {
            width: 44px !important;
            height: 44px !important;
            font-size: 1.25rem !important;
        }
        .form-control {
            font-size: 16px !important; /* Prevents auto zoom in mobile browser */
        }
        .summary-card {
            padding: 1.25rem 1rem !important;
        }
        .summary-grid {
            grid-template-columns: 1fr !important;
            gap: 0.85rem !important;
        }
        .wizard-action-footer {
            flex-direction: column-reverse !important;
            gap: 0.75rem !important;
            margin-top: 1.75rem !important;
            padding-top: 1.25rem !important;
        }
        .wizard-action-footer button,
        .wizard-action-footer a {
            width: 100% !important;
            justify-content: center !important;
            padding: 0.85rem 1.25rem !important;
            font-size: 0.925rem !important;
        }
    }

    @media (max-width: 480px) {
        .card {
            padding: 1.25rem 0.95rem !important;
        }
        .wizard-steps {
            gap: 0.5rem;
        }
        .secondary-division-pills {
            gap: 0.45rem;
        }
        .secondary-division-pill {
            font-size: 0.775rem;
            padding: 0.5rem 0.85rem;
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
        font-size: 0.725rem;
        color: var(--slate-400);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.05em;
    }
    .summary-item-val {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--slate-800);
        margin-top: 0.25rem;
        word-break: break-all;
    }

    /* Validation Highlighting */
    .form-control.field-invalid {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
    }
    .form-control.field-valid {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
    }

    /* Toast Notification for Autosave */
    .autosave-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #0f172a;
        color: #f8fafc;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius-full);
        box-shadow: 0 10px 25px rgba(0,0,0,0.25);
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 9999;
        pointer-events: none;
    }
    .autosave-toast.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* Inline Step Error Alert */
    .step-error-alert {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: var(--radius-lg);
        padding: 0.85rem 1.15rem;
        margin-bottom: 1.25rem;
        color: #991b1b;
        font-size: 0.875rem;
        font-weight: 600;
        display: none;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.08);
        animation: errorShake 0.4s ease-in-out;
    }

    /* Division Secondary Pills */
    .secondary-division-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
        margin-top: 0.5rem;
    }
    .secondary-division-pill {
        padding: 0.6rem 1.15rem;
        border-radius: var(--radius-full);
        background: #ffffff;
        box-shadow: var(--clay-pill);
        border: 2px solid transparent;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--slate-700);
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }
    .secondary-division-pill:hover {
        transform: translateY(-2px);
        box-shadow: var(--clay-card-hover);
    }
    .secondary-division-pill.selected {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    }
    .secondary-division-pill.disabled {
        opacity: 0.35;
        pointer-events: none;
        text-decoration: line-through;
        background: #f1f5f9;
        box-shadow: none;
    }

    .dropzone-error-msg {
        display: none;
        color: #ef4444;
        font-size: 0.8rem;
        margin-top: 0.45rem;
        font-weight: 600;
        animation: errorShake 0.4s ease-in-out;
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
            Satu pintu pendaftaran resmi mahasiswa Program Studi Ilmu Komputer UBBG. Sistem dilengkapi <strong>Autosave Cerdas</strong>, validasi berlapis per-tahap, dan pembuatan akun otomatis.
        </p>
    </div>
</div>

<div class="container-narrow" style="padding: 0.5rem 1.5rem 5rem;">

    <!-- Draft Recovery Notification Banner -->
    <div id="draftRecoveryBanner" class="draft-banner-clay" style="display: none;">
        <div class="draft-banner-info">
            <div class="draft-banner-icon">
                <i class="fas fa-clock-rotate-left"></i>
            </div>
            <div>
                <strong style="color: #1e3a8a; font-size: 0.95rem; display: block;">Draft Pendaftaran Ditemukan</strong>
                <span style="font-size: 0.8rem; color: #475569;">
                    Data yang sebelumnya Anda isi telah dipulihkan otomatis. Anda dapat melanjutkan pengisian formulir.
                </span>
            </div>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <button type="button" class="btn btn-outline btn-sm" onclick="clearDraftAndReset()" style="font-size: 0.8rem; padding: 0.4rem 0.85rem; border-color: #cbd5e1; color: #64748b;">
                <i class="fas fa-trash-can" style="margin-right: 0.35rem;"></i> Mulai Baru
            </button>
            <button type="button" class="btn btn-primary btn-sm" onclick="dismissDraftBanner()" style="font-size: 0.8rem; padding: 0.4rem 1rem;">
                <i class="fas fa-check" style="margin-right: 0.35rem;"></i> Gunakan Draft
            </button>
        </div>
    </div>

    <!-- Server Flash Error Message -->
    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #991b1b; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-circle-exclamation" style="font-size: 1.25rem;"></i>
            <span style="font-weight: 600; font-size: 0.95rem;">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Server Validation Errors Alert (Dismissable & Interactive) -->
    @if ($errors->any())
        <div id="serverErrorsAlert" class="shake-error" style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; color: #991b1b; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1); position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 800; font-size: 1rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-triangle-exclamation"></i> Terdapat data yang perlu diperbaiki:
                </div>
                <button type="button" onclick="document.getElementById('serverErrorsAlert').style.display='none'" style="background: none; border: none; font-size: 1.25rem; color: #991b1b; cursor: pointer; padding: 0 4px;" title="Tutup">
                    &times;
                </button>
            </div>
            <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.875rem; line-height: 1.5;">
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
            <!-- IMPORTANT: novalidate prevents browser blocking submit on hidden steps with is not focusable -->
            <form id="recruitmentMultiStepForm" action="{{ route('recruitment.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <!-- ============================================== -->
                <!-- TAHAP 1: BIODATA MAHASISWA & AKUN LOGIN        -->
                <!-- ============================================== -->
                <div class="step-section active" id="stepSection1">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--slate-200);">
                        <div>
                            <span class="badge badge-info" style="font-size: 0.775rem; margin-bottom: 0.4rem;">
                                Tahap 1 dari 3: Identitas Diri
                            </span>
                            <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.25rem;">
                                Informasi Biodata & Akun Mahasiswa
                            </h3>
                            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                                Lengkapi data sesuai Kartu Tanda Mahasiswa aktif Anda. Akun login akan dibuat secara otomatis.
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 1.5rem; font-weight: 800; color: #2563eb; font-family: var(--font-mono);">01</span>
                            <span style="font-size: 0.8rem; color: var(--slate-400); display: block;">/ 03 Tahap</span>
                        </div>
                    </div>

                    <!-- Inline Step Error Alert -->
                    <div id="stepAlert1" class="step-error-alert">
                        <i class="fas fa-circle-exclamation" style="font-size: 1.1rem; color: #ef4444;"></i>
                        <span id="stepAlertMsg1"></span>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label class="form-label" for="full_name">Nama Lengkap Mahasiswa *</label>
                        <input type="text" id="full_name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" placeholder="Contoh: Bintang Ramadhan" autocomplete="name">
                        @error('full_name') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <!-- NIM & Semester (Real-time availability check on NIM) -->
                    <div class="grid-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label" for="nim">Nomor Induk Mahasiswa (NIM) *</label>
                            <div class="field-indicator-wrap">
                                <input type="text" id="nim" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" placeholder="Contoh: 2301010099" autocomplete="off" oninput="debounceCheckNim(this)">
                                <i class="fas fa-spinner field-status-icon status-loading" id="nimLoadingIcon"></i>
                                <i class="fas fa-check-circle field-status-icon status-valid" id="nimValidIcon"></i>
                                <i class="fas fa-circle-xmark field-status-icon status-invalid" id="nimInvalidIcon"></i>
                            </div>
                            <small id="nimFeedbackText" style="display: block; font-size: 0.775rem; margin-top: 0.35rem; color: var(--slate-400);">
                                NIM akan digunakan untuk verifikasi status keanggotaan dan KTA Digital.
                            </small>
                            @error('nim') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="semester">Semester Saat Ini *</label>
                            <select id="semester" name="semester" class="form-control @error('semester') is-invalid @enderror">
                                <option value="">-- Pilih Semester --</option>
                                @for ($s = 1; $s <= 8; $s++)
                                    <option value="{{ $s }}" {{ old('semester') == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
                                @endfor
                            </select>
                            @error('semester') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Nomor WhatsApp & Kelas -->
                    <div class="grid-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label" for="phone_whatsapp">Nomor WhatsApp Aktif *</label>
                            <input type="tel" id="phone_whatsapp" name="phone_whatsapp" class="form-control @error('phone_whatsapp') is-invalid @enderror" value="{{ old('phone_whatsapp') }}" placeholder="Contoh: 081298765432" oninput="formatWhatsapp(this)">
                            <small style="color: var(--slate-400); font-size: 0.775rem;">Panitia akan menghubungi Anda melalui WhatsApp untuk konfirmasi seleksi & wawancara.</small>
                            @error('phone_whatsapp') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="class_group">Kelas / Rombel Kuliah (Opsional)</label>
                            <input type="text" id="class_group" name="class_group" class="form-control @error('class_group') is-invalid @enderror" value="{{ old('class_group') }}" placeholder="Contoh: IF-23A atau Reguler Pagi">
                            <small style="color: var(--slate-400); font-size: 0.775rem;">Membantu sinkronisasi jadwal praktikum divisi.</small>
                            @error('class_group') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Akun & Akses Portal (Real-time Email Check, Password Strength & Toggle) -->
                    <div style="background: linear-gradient(135deg, rgba(37,99,235,0.04), rgba(14,165,233,0.04)); border: 1.5px solid rgba(37,99,235,0.18); border-radius: var(--radius-xl); padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: var(--clay-card-sm);">
                        <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                            <span style="display: inline-flex; width: 28px; height: 28px; border-radius: 8px; background: #2563eb; color: #fff; align-items: center; justify-content: center; font-size: 0.85rem;"><i class="fas fa-lock"></i></span>
                            <h4 style="font-size: 1rem; font-weight: 800; color: var(--slate-900); margin: 0;">Akun & Akses Portal Mahasiswa</h4>
                        </div>
                        <p style="font-size: 0.825rem; color: var(--slate-600); margin-bottom: 1.25rem; line-height: 1.5;">
                            Akun ini langsung terhubung dengan <strong>Portal Mahasiswa</strong> untuk memantau hasil seleksi, presensi mandiri dengan passcode, dan mengunduh KTA Digital.
                        </p>

                        <!-- Email ID Login -->
                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label class="form-label" for="email">Alamat Email Aktif (ID Login) *</label>
                            <div class="field-indicator-wrap">
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()?->email) }}" placeholder="Contoh: nama@student.ac.id" {{ auth()->check() ? 'readonly' : '' }} autocomplete="email" oninput="debounceCheckEmail(this)">
                                <i class="fas fa-spinner field-status-icon status-loading" id="emailLoadingIcon"></i>
                                <i class="fas fa-check-circle field-status-icon status-valid" id="emailValidIcon"></i>
                                <i class="fas fa-circle-xmark field-status-icon status-invalid" id="emailInvalidIcon"></i>
                            </div>
                            <small id="emailFeedbackText" style="display: block; font-size: 0.775rem; margin-top: 0.35rem; color: var(--slate-400);">
                                Pastikan email aktif untuk menerima notifikasi pengumuman.
                            </small>
                            @error('email') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        @guest
                        <!-- Password & Confirmation (Validated via JavaScript, without browser focus lock) -->
                        <div class="grid-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" for="password">Kata Sandi Baru *</label>
                                <div class="password-field-wrap">
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" autocomplete="new-password" oninput="checkPasswordStrength(this)">
                                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" title="Lihat/Sembunyikan Kata Sandi">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-meter-wrap">
                                    <div class="password-meter-bar">
                                        <div class="password-meter-fill" id="passwordMeterFill"></div>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; font-size: 0.725rem; color: var(--slate-400);">
                                        <span id="passwordStrengthLabel">Kekuatan: -</span>
                                        <span>Min 8 karakter</span>
                                    </div>
                                </div>
                                @error('password') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" for="password_confirmation">Ulangi Kata Sandi *</label>
                                <div class="password-field-wrap">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi kata sandi" autocomplete="new-password" oninput="checkPasswordMatch()">
                                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_confirmation', this)" title="Lihat/Sembunyikan Kata Sandi">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="passwordMatchText" style="font-size: 0.725rem; color: var(--slate-400); margin-top: 0.5rem;">
                                    Ketik ulang kata sandi yang sama.
                                </div>
                            </div>
                        </div>
                        @endguest
                    </div>

                    <!-- Action Step 1 Footer -->
                    <div class="wizard-action-footer" style="margin-top: 2.25rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-200); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <div style="font-size: 0.8rem; color: var(--slate-500); display: flex; align-items: center; gap: 0.4rem;">
                            <i class="fas fa-shield-halved" style="color: #10b981;"></i>
                            <span>Autosave aktif: Data tersimpan aman di browser Anda.</span>
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
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--slate-200);">
                        <div>
                            <span class="badge badge-info" style="font-size: 0.775rem; margin-bottom: 0.4rem;">
                                Tahap 2 dari 3: Penjurusan Teknologi
                            </span>
                            <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.25rem;">
                                Pilihan Bidang Divisi & Motivasi Riset
                            </h3>
                            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                                Pilih divisi keahlian utama yang ingin Anda tekuni. Anda juga dapat memilih divisi cadangan.
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 1.5rem; font-weight: 800; color: #2563eb; font-family: var(--font-mono);">02</span>
                            <span style="font-size: 0.8rem; color: var(--slate-400); display: block;">/ 03 Tahap</span>
                        </div>
                    </div>

                    <!-- Inline Step Error Alert -->
                    <div id="stepAlert2" class="step-error-alert">
                        <i class="fas fa-circle-exclamation" style="font-size: 1.1rem; color: #ef4444;"></i>
                        <span id="stepAlertMsg2"></span>
                    </div>

                    <!-- Hidden inputs for form submission -->
                    <input type="hidden" id="first_choice_division_id" name="first_choice_division_id" value="{{ old('first_choice_division_id', optional($preselectedDivision)->id) }}">
                    <input type="hidden" id="second_choice_division_id" name="second_choice_division_id" value="{{ old('second_choice_division_id') }}">

                    <!-- Divisi Pilihan Utama: Interactive Tactile Clay Cards -->
                    <div style="margin-bottom: 2rem;">
                        <label class="form-label" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                            <span style="font-weight: 800; font-size: 1rem; color: var(--slate-900);">
                                <i class="fas fa-star" style="color: #f59e0b; margin-right: 0.4rem;"></i> Pilih Divisi Utama (Prioritas Seleksi) *
                            </span>
                            <span style="font-size: 0.775rem; color: var(--slate-500);">Wajib memilih 1 divisi</span>
                        </label>

                        <div class="division-cards-grid">
                            @foreach ($divisions as $d)
                                @php
                                    $slug = strtolower($d->slug ?? '');
                                    $themeClass = 'div-theme-pemrograman';
                                    $divIcon = 'fa-laptop-code';
                                    $defaultDesc = 'Rekayasa perangkat lunak, web architecture, dan cloud deployment.';

                                    if (str_contains($slug, 'multi') || str_contains($slug, 'desain')) {
                                        $themeClass = 'div-theme-multimedia';
                                        $divIcon = 'fa-palette';
                                        $defaultDesc = 'UI/UX design, visual identity, 3D modelling, dan motion graphic.';
                                    } elseif (str_contains($slug, 'iot') || str_contains($slug, 'hardware') || str_contains($slug, 'robot')) {
                                        $themeClass = 'div-theme-iot';
                                        $divIcon = 'fa-microchip';
                                        $defaultDesc = 'Sistem embedded, mikrokontroler ESP32, sensor, dan automasi cerdas.';
                                    } elseif (str_contains($slug, 'cyber') || str_contains($slug, 'security')) {
                                        $themeClass = 'div-theme-cyber';
                                        $divIcon = 'fa-shield-halved';
                                        $defaultDesc = 'Keamanan siber, ethical hacking, CTF challenges, dan threat audit.';
                                    }

                                    $quota = $d->recruitment_quota;
                                    $remainingQuota = $d->remaining_quota;
                                    $isFull = (bool)$d->is_quota_full;
                                    $registeredCount = (int)($d->first_choice_applicants_count ?? 0);
                                    $isSelected = !$isFull && (old('first_choice_division_id', optional($preselectedDivision)->id) == $d->id);
                                    $percentage = ($quota && $quota > 0) ? min(100, round(($registeredCount / $quota) * 100)) : null;
                                @endphp
                                <div class="division-select-card {{ $themeClass }} {{ $isSelected ? 'selected' : '' }} {{ $isFull ? 'is-full disabled' : '' }}" 
                                     id="divCardPrimary_{{ $d->id }}" 
                                     data-id="{{ $d->id }}" 
                                     data-name="{{ $d->name }}" 
                                     data-is-full="{{ $isFull ? '1' : '0' }}"
                                     onclick="selectPrimaryDivision('{{ $d->id }}', '{{ addslashes($d->name) }}', {{ $isFull ? 'true' : 'false' }})">
                                    <div class="division-card-header">
                                        <div class="division-card-icon" style="{{ $isFull ? 'background: #fee2e2; color: #ef4444;' : '' }}">
                                            <i class="fas {{ $divIcon }}"></i>
                                        </div>
                                        <div class="division-card-radio"></div>
                                    </div>
                                    <h4 class="division-card-title">{{ $d->name }}</h4>
                                    <p class="division-card-desc">
                                        {{ $d->description ?: $defaultDesc }}
                                    </p>

                                    @if ($percentage !== null)
                                        <div style="margin: 0.75rem 0 0.25rem;">
                                            <div style="display: flex; justify-content: space-between; font-size: 0.72rem; font-weight: 700; color: {{ $isFull ? '#dc2626' : 'var(--slate-500)' }}; margin-bottom: 0.25rem;">
                                                <span>Keterisian Kuota</span>
                                                <span>{{ $registeredCount }} / {{ $quota }} ({{ $percentage }}%)</span>
                                            </div>
                                            <div style="width: 100%; background: #e2e8f0; height: 6px; border-radius: 999px; overflow: hidden;">
                                                <div style="width: {{ $percentage }}%; background: {{ $isFull ? '#ef4444' : ($percentage >= 80 ? '#f59e0b' : '#3b82f6') }}; height: 100%; border-radius: 999px; transition: width 0.3s ease;"></div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="division-card-footer" style="margin-top: 0.6rem;">
                                        @if ($isFull)
                                            <span class="badge" style="background: rgba(239,68,68,0.12); color: #dc2626; font-weight: 700;">
                                                <i class="fas fa-ban" style="margin-right: 0.3rem;"></i> Kuota Penuh
                                            </span>
                                            <span style="font-weight: 700; color: #dc2626; font-size: 0.75rem;">
                                                <i class="fas fa-circle-xmark"></i> Ditutup
                                            </span>
                                        @elseif ($quota !== null)
                                            <span class="badge" style="background: rgba(37,99,235,0.08); color: #2563eb; font-weight: 700;">
                                                <i class="fas fa-users" style="margin-right: 0.3rem;"></i> Sisa: {{ $remainingQuota }} Kursi
                                            </span>
                                            <span style="font-weight: 600; color: #10b981; font-size: 0.75rem;">
                                                <i class="fas fa-circle-check"></i> Tersedia
                                            </span>
                                        @else
                                            <span class="badge" style="background: rgba(16,185,129,0.08); color: #059669; font-weight: 700;">
                                                <i class="fas fa-infinity" style="margin-right: 0.3rem;"></i> Kuota: Terbuka
                                            </span>
                                            <span style="font-weight: 600; color: #10b981; font-size: 0.75rem;">
                                                <i class="fas fa-circle-check"></i> Buka
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('first_choice_division_id') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <!-- Divisi Pilihan Kedua (Opsional / Cadangan): Secondary Pills -->
                    <div style="background: var(--bg-body); border-radius: var(--radius-xl); padding: 1.5rem; margin-bottom: 2rem; box-shadow: var(--clay-debossed);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <label class="form-label" style="font-weight: 700; color: var(--slate-800); margin: 0;">
                                <i class="fas fa-shuffle" style="color: #6366f1; margin-right: 0.4rem;"></i> Divisi Pilihan Kedua / Cadangan (Opsional)
                            </label>
                            <button type="button" class="btn btn-outline btn-sm" onclick="clearSecondaryDivision()" style="font-size: 0.725rem; padding: 0.25rem 0.65rem; border-color: #cbd5e1;">
                                Reset Pilihan Kedua
                            </button>
                        </div>
                        <p style="font-size: 0.775rem; color: var(--slate-500); margin: 0 0 0.85rem;">
                            Jika kuota pada divisi utama telah penuh, panitia dapat mempertimbangkan divisi cadangan ini. <em>Harus berbeda dengan divisi utama.</em>
                        </p>
                        <div class="secondary-division-pills">
                            @foreach ($divisions as $d)
                                @php
                                    $secIsFull = (bool)$d->is_quota_full;
                                    $secSelected = !$secIsFull && (old('second_choice_division_id') == $d->id);
                                @endphp
                                <div class="secondary-division-pill {{ $secSelected ? 'selected' : '' }} {{ $secIsFull ? 'disabled is-full' : '' }}" 
                                     id="divPillSecondary_{{ $d->id }}" 
                                     data-id="{{ $d->id }}" 
                                     data-is-full="{{ $secIsFull ? '1' : '0' }}"
                                     onclick="selectSecondaryDivision('{{ $d->id }}', {{ $secIsFull ? 'true' : 'false' }})">
                                    <i class="fas fa-check-circle" style="font-size: 0.8rem; display: {{ $secSelected ? 'inline-block' : 'none' }};"></i>
                                    <span>{{ $d->name }}</span>
                                    @if ($secIsFull)
                                        <span style="font-size: 0.675rem; font-weight: 700; color: #dc2626; margin-left: 0.35rem;">[Penuh]</span>
                                    @elseif ($d->recruitment_quota !== null)
                                        <span style="font-size: 0.675rem; font-weight: 600; color: #64748b; margin-left: 0.35rem;">({{ $d->remaining_quota }} sisa)</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @error('second_choice_division_id') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <!-- Motivasi & Komitmen Bergabung -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                            <label class="form-label" for="reason_to_join" style="margin-bottom: 0;">
                                Motivasi & Komitmen Bergabung *
                            </label>
                            <span id="charCountDisplay" style="font-size: 0.775rem; color: var(--slate-400); font-family: var(--font-mono); font-weight: 700;">
                                <span id="reasonCharCount">0</span> / 20 karakter minimal
                            </span>
                        </div>
                        <textarea id="reason_to_join" name="reason_to_join" rows="5" class="form-control @error('reason_to_join') is-invalid @enderror" placeholder="Ceritakan ketertarikan Anda pada divisi yang dipilih, pengalaman relevan sebelumnya, dan kontribusi apa yang ingin Anda berikan..." oninput="countReasonChars(this)">{{ old('reason_to_join') }}</textarea>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.4rem;">
                            <small style="color: var(--slate-400); font-size: 0.775rem;">Uraikan minat Anda dengan jelas sebagai bahan pertimbangan penguji saat sesi wawancara.</small>
                            <small id="reasonQualityHint" style="font-size: 0.75rem; color: var(--slate-400); font-weight: 600;"></small>
                        </div>
                        @error('reason_to_join') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <!-- Action Step 2 Footer -->
                    <div class="wizard-action-footer" style="margin-top: 2.25rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-200); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
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
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--slate-200);">
                        <div>
                            <span class="badge badge-info" style="font-size: 0.775rem; margin-bottom: 0.4rem;">
                                Tahap 3 dari 3: Berkas & Konfirmasi
                            </span>
                            <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.25rem;">
                                Unggah Dokumen Berkas & Konfirmasi Pendaftaran
                            </h3>
                            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                                Lampirkan kartu tanda mahasiswa aktif dan portofolio (jika ada), lalu periksa kembali kebenaran data Anda sebelum mengirim.
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 1.5rem; font-weight: 800; color: #10b981; font-family: var(--font-mono);">03</span>
                            <span style="font-size: 0.8rem; color: var(--slate-400); display: block;">/ 03 Tahap</span>
                        </div>
                    </div>

                    <!-- Inline Step Error Alert -->
                    <div id="stepAlert3" class="step-error-alert">
                        <i class="fas fa-circle-exclamation" style="font-size: 1.1rem; color: #ef4444;"></i>
                        <span id="stepAlertMsg3"></span>
                    </div>

                    <!-- Dropzone Berkas Dokumen: Pas Foto, KTM, dan CV (Semua Wajib) -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem; margin-bottom: 1.75rem;">
                        <!-- 1. Pas Foto Resmi Upload (Wajib) -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                                <span><i class="fas fa-camera" style="color: #2563eb; margin-right: 0.35rem;"></i> Pas Foto Resmi Mahasiswa *</span>
                                <span style="font-size: 0.75rem; color: #ef4444; font-weight: 700;">Wajib &bull; Maks 2MB</span>
                            </label>
                            <div class="dropzone-clay" id="photoDropzone">
                                <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/webp" onchange="handlePhotoSelect(this)">
                                <div class="dropzone-icon" style="color: #2563eb;">
                                    <i class="fas fa-camera"></i>
                                </div>
                                <div style="font-weight: 700; color: var(--slate-800); font-size: 0.9rem;">
                                    Upload Pas Foto Mahasiswa
                                </div>
                                <p style="font-size: 0.75rem; color: var(--slate-500); margin: 0.25rem 0 0;">
                                    JPG, PNG, WEBP. Dicetak pada KTA Digital.
                                </p>
                                <div class="dropzone-preview" id="photoDropzonePreview">
                                    <img src="" id="photoThumbImg" class="dropzone-preview-thumb" alt="Preview">
                                    <div style="flex: 1; min-width: 0;">
                                        <div id="photoFileName" style="font-weight: 700; font-size: 0.825rem; color: var(--slate-800); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></div>
                                        <div id="photoFileSize" style="font-size: 0.725rem; color: #10b981; font-weight: 600;"></div>
                                    </div>
                                    <button type="button" class="btn btn-outline btn-sm" onclick="removePhotoUpload(event)" style="border-radius: var(--radius-full); padding: 0.3rem 0.65rem; font-size: 0.725rem; color: #ef4444; border-color: #fecaca;">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="photoDropzoneError" class="dropzone-error-msg" style="display: none; color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem; font-weight: 600;"></div>
                            @error('profile_photo') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <!-- 2. KTM Upload (Wajib) -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                                <span><i class="fas fa-id-card" style="color: #0284c7; margin-right: 0.35rem;"></i> Foto / Scan KTM Aktif *</span>
                                <span style="font-size: 0.75rem; color: #ef4444; font-weight: 700;">Wajib &bull; Maks 2MB</span>
                            </label>
                            <div class="dropzone-clay" id="ktmDropzone">
                                <input type="file" id="file_ktm" name="file_ktm" accept=".jpg,.jpeg,.png,.pdf" onchange="handleFileKtmSelect(this)">
                                <div class="dropzone-icon" style="color: #0284c7;">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div style="font-weight: 700; color: var(--slate-800); font-size: 0.9rem;">
                                    Upload Foto / PDF KTM
                                </div>
                                <p style="font-size: 0.75rem; color: var(--slate-500); margin: 0.25rem 0 0;">
                                    JPG, PNG, atau PDF untuk verifikasi mahasiswa aktif.
                                </p>
                                <div class="dropzone-preview" id="ktmDropzonePreview">
                                    <div class="dropzone-preview-thumb" id="ktmThumbWrap" style="display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #0284c7; background: #eff6ff;">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <div id="ktmFileName" style="font-weight: 700; font-size: 0.825rem; color: var(--slate-800); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></div>
                                        <div id="ktmFileSize" style="font-size: 0.725rem; color: #10b981; font-weight: 600;"></div>
                                    </div>
                                    <button type="button" class="btn btn-outline btn-sm" onclick="removeKtmUpload(event)" style="border-radius: var(--radius-full); padding: 0.3rem 0.65rem; font-size: 0.725rem; color: #ef4444; border-color: #fecaca;">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="ktmDropzoneError" class="dropzone-error-msg" style="display: none; color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem; font-weight: 600;"></div>
                            @error('file_ktm') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <!-- 3. CV Upload (Wajib) -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                                <span><i class="fas fa-file-pdf" style="color: #ef4444; margin-right: 0.35rem;"></i> Dokumen CV / Resume (PDF) *</span>
                                <span style="font-size: 0.75rem; color: #ef4444; font-weight: 700;">Wajib &bull; Maks 3MB</span>
                            </label>
                            <div class="dropzone-clay" id="cvDropzone">
                                <input type="file" id="file_cv" name="file_cv" accept=".pdf" onchange="handleFileCvSelect(this)">
                                <div class="dropzone-icon" style="color: #ef4444;">
                                    <i class="fas fa-file-lines"></i>
                                </div>
                                <div style="font-weight: 700; color: var(--slate-800); font-size: 0.9rem;">
                                    Upload Dokumen CV (PDF)
                                </div>
                                <p style="font-size: 0.75rem; color: var(--slate-500); margin: 0.25rem 0 0;">
                                    Format PDF portofolio / biodata riwayat diri.
                                </p>
                                <div class="dropzone-preview" id="cvDropzonePreview">
                                    <div class="dropzone-preview-thumb" style="display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #ef4444; background: #fef2f2;">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <div id="cvFileName" style="font-weight: 700; font-size: 0.825rem; color: var(--slate-800); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></div>
                                        <div id="cvFileSize" style="font-size: 0.725rem; color: #10b981; font-weight: 600;"></div>
                                    </div>
                                    <button type="button" class="btn btn-outline btn-sm" onclick="removeCvUpload(event)" style="border-radius: var(--radius-full); padding: 0.3rem 0.65rem; font-size: 0.725rem; color: #ef4444; border-color: #fecaca;">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="cvDropzoneError" class="dropzone-error-msg" style="display: none; color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem; font-weight: 600;"></div>
                            @error('file_cv') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Tautan GitHub (Wajib) & Portofolio Online (Opsional) -->
                    <div class="grid-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label" for="github_url">
                                <i class="fab fa-github" style="margin-right: 0.35rem;"></i> Tautan Profil GitHub *
                            </label>
                            <input type="url" id="github_url" name="github_url" class="form-control @error('github_url') is-invalid @enderror" value="{{ old('github_url', auth()->user()?->github_url) }}" placeholder="https://github.com/username" onblur="autoFormatUrl(this)">
                            <small style="color: var(--slate-400); font-size: 0.75rem;">Wajib dicantumkan untuk verifikasi akun programmer / developer.</small>
                            @error('github_url') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="portfolio_url">
                                <i class="fas fa-globe" style="margin-right: 0.35rem;"></i> Link Portofolio Lainnya (Opsional)
                            </label>
                            <input type="url" id="portfolio_url" name="portfolio_url" class="form-control @error('portfolio_url') is-invalid @enderror" value="{{ old('portfolio_url') }}" placeholder="https://dribbble.com/..., Figma, Drive" onblur="autoFormatUrl(this)">
                            <small style="color: var(--slate-400); font-size: 0.75rem;">Tautan karya desain, aplikasi demo, atau drive sertifikat.</small>
                            @error('portfolio_url') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Ringkasan Konfirmasi Live Preview Card -->
                    <div class="summary-card">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-clipboard-check" style="color: #2563eb; font-size: 1.2rem;"></i>
                                <span style="font-weight: 800; font-size: 1rem; letter-spacing: -0.01em; color: var(--slate-900);">Ringkasan Data Pendaftaran Anda</span>
                            </div>
                            <span class="badge badge-success" style="font-size: 0.75rem;">
                                <i class="fas fa-check" style="margin-right: 0.25rem;"></i> Siap Dikirim
                            </span>
                        </div>

                        <div class="summary-grid">
                            <div>
                                <div class="summary-item-label">Nama Mahasiswa</div>
                                <div class="summary-item-val" id="summaryName">-</div>
                                <button type="button" onclick="jumpToStep(1)" style="background: none; border: none; padding: 0; color: #2563eb; font-size: 0.75rem; font-weight: 600; cursor: pointer; margin-top: 0.25rem;">Ubah Biodata &rarr;</button>
                            </div>
                            <div>
                                <div class="summary-item-label">NIM & Semester</div>
                                <div class="summary-item-val" id="summaryNimSemester">-</div>
                            </div>
                            <div>
                                <div class="summary-item-label">Divisi Pilihan Utama</div>
                                <div class="summary-item-val" id="summaryDivision" style="color: #2563eb;">-</div>
                                <button type="button" onclick="jumpToStep(2)" style="background: none; border: none; padding: 0; color: #2563eb; font-size: 0.75rem; font-weight: 600; cursor: pointer; margin-top: 0.25rem;">Ubah Divisi &rarr;</button>
                            </div>
                            <div>
                                <div class="summary-item-label">Email ID Login</div>
                                <div class="summary-item-val" id="summaryEmail">-</div>
                            </div>
                        </div>

                        <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--slate-200); font-size: 0.85rem; color: var(--slate-700); display: flex; align-items: flex-start; gap: 0.65rem;">
                            <input type="checkbox" id="agreementCheck" style="width: 18px; height: 18px; cursor: pointer; margin-top: 2px;">
                            <label for="agreementCheck" style="margin: 0; cursor: pointer; line-height: 1.5;">
                                Saya menyatakan dengan sesungguhnya bahwa seluruh informasi dan dokumen yang saya unggah adalah benar dan sah. Saya bersedia mengikuti seluruh tahapan seleksi dan mematuhi kode etik UKM Ilmu Komputer UBBG.
                            </label>
                        </div>
                    </div>

                    <!-- Action Step 3 Footer (Anti Double Submit) -->
                    <div class="wizard-action-footer" style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-200); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <button type="button" class="btn btn-outline btn-lg" onclick="prevStep(2)" style="display: inline-flex; align-items: center; gap: 0.6rem; padding: 0.85rem 1.5rem;">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali ke Tahap 2: Pilihan Divisi</span>
                        </button>
                        <button type="submit" id="submitFormBtn" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #10b981, #059669); border-color: #10b981; font-weight: 800; padding: 0.95rem 2.25rem; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);">
                            <i class="fas fa-paper-plane" id="submitIcon" style="margin-right: 0.5rem;"></i>
                            <span id="submitText">Kirim Formulir Pendaftaran Sekarang &rarr;</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif
</div>

<!-- Autosave Notification Toast -->
<div id="autosaveToast" class="autosave-toast">
    <i class="fas fa-cloud-arrow-up" style="color: #38bdf8;"></i>
    <span>Draf formulir tersimpan otomatis</span>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/recruitment-wizard.js') }}"></script>
@endsection
