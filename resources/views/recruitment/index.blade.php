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
            font-size: 0.85rem;
        }
        .card {
            padding: 1.5rem 1.15rem !important;
        }
        .grid-2col {
            grid-template-columns: 1fr !important;
        }
        .wizard-action-footer {
            flex-direction: column-reverse !important;
            gap: 0.75rem !important;
        }
        .wizard-action-footer button,
        .wizard-action-footer a {
            width: 100% !important;
            justify-content: center !important;
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
            Satu pintu pendaftaran resmi mahasiswa Program Studi Ilmu Komputer UBBG. Sistem dilengkapi <strong>Autosave Cerdas</strong>, validasi instan, dan alur 3 tahap terintegrasi.
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
                    Data yang sebelumnya Anda isi telah dipulihkan otomatis. Anda dapat melanjutkan atau memulai dari awal.
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

    <!-- Server Flash Errors -->
    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #991b1b; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-circle-exclamation" style="font-size: 1.25rem;"></i>
            <span style="font-weight: 500; font-size: 0.95rem;">{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="shake-error" style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; color: #991b1b; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);">
            <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 800; font-size: 1rem; margin-bottom: 0.5rem;">
                <i class="fas fa-triangle-exclamation"></i> Mohon Periksa Kembali Isian Anda:
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
                                Lengkapi data sesuai Kartu Tanda Mahasiswa aktif Anda. Akun login akan dibuat secara otomatis.
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

                    <!-- NIM & Semester (Real-time availability check on NIM) -->
                    <div class="grid-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label" for="nim">Nomor Induk Mahasiswa (NIM) *</label>
                            <div class="field-indicator-wrap">
                                <input type="text" id="nim" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" placeholder="Contoh: 2301010099" required autocomplete="off" oninput="debounceCheckNim(this)">
                                <i class="fas fa-spinner field-status-icon status-loading" id="nimLoadingIcon"></i>
                                <i class="fas fa-check-circle field-status-icon status-valid" id="nimValidIcon"></i>
                                <i class="fas fa-circle-xmark field-status-icon status-invalid" id="nimInvalidIcon"></i>
                            </div>
                            <small id="nimFeedbackText" style="display: block; font-size: 0.775rem; margin-top: 0.35rem; color: var(--slate-400);">
                                NIM akan digunakan untuk verifikasi status mahasiswa aktif dan login akun.
                            </small>
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

                    <!-- Nomor WhatsApp & Kelas -->
                    <div class="grid-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label" for="phone_whatsapp">Nomor WhatsApp Aktif *</label>
                            <input type="tel" id="phone_whatsapp" name="phone_whatsapp" class="form-control @error('phone_whatsapp') is-invalid @enderror" value="{{ old('phone_whatsapp') }}" placeholder="Contoh: 081298765432" required oninput="formatWhatsapp(this)">
                            <small style="color: var(--slate-400); font-size: 0.775rem;">Panitia akan menghubungi Anda melalui WhatsApp untuk undangan wawancara.</small>
                            @error('phone_whatsapp') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="class_group">Kelas / Rombel Kuliah (Opsional)</label>
                            <input type="text" id="class_group" name="class_group" class="form-control @error('class_group') is-invalid @enderror" value="{{ old('class_group') }}" placeholder="Contoh: IF-23A atau Reguler Pagi">
                            <small style="color: var(--slate-400); font-size: 0.775rem;">Membantu sinkronisasi jadwal praktikum divisi.</small>
                            @error('class_group') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Pas Foto Resmi Profil (Clay Drag-and-Drop Dropzone) -->
                    <div class="form-group" style="margin-bottom: 1.75rem;">
                        <label class="form-label" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                            <span><i class="fas fa-image" style="color: #2563eb; margin-right: 0.4rem;"></i> Pas Foto Resmi Mahasiswa</span>
                            <span style="font-size: 0.75rem; color: var(--slate-400);">Maksimal 2MB (JPG/PNG/WEBP)</span>
                        </label>
                        <div class="dropzone-clay" id="photoDropzone">
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/webp" onchange="handlePhotoSelect(this)">
                            <div class="dropzone-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div style="font-weight: 700; color: var(--slate-800); font-size: 0.95rem;">
                                Klik atau Seret Pas Foto ke Sini
                            </div>
                            <p style="font-size: 0.8rem; color: var(--slate-500); margin: 0.25rem 0 0;">
                                Pas foto setengah badan formal. Otomatis dicetak pada <strong>KTA Digital Mahasiswa</strong>.
                            </p>

                            <!-- Dropzone Live Preview -->
                            <div class="dropzone-preview" id="photoDropzonePreview">
                                <img src="" id="photoThumbImg" class="dropzone-preview-thumb" alt="Preview">
                                <div style="flex: 1; min-width: 0;">
                                    <div id="photoFileName" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-800); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></div>
                                    <div id="photoFileSize" style="font-size: 0.75rem; color: #10b981; font-weight: 600;"></div>
                                </div>
                                <button type="button" class="btn btn-outline btn-sm" onclick="removePhotoUpload(event)" style="border-radius: var(--radius-full); padding: 0.35rem 0.75rem; font-size: 0.75rem; color: #ef4444; border-color: #fecaca;">
                                    <i class="fas fa-trash-can"></i> Ganti
                                </button>
                            </div>
                        </div>
                        @error('profile_photo') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
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
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()?->email) }}" placeholder="Contoh: nama@student.ac.id" required {{ auth()->check() ? 'readonly' : '' }} autocomplete="email" oninput="debounceCheckEmail(this)">
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
                        <!-- Password & Confirmation -->
                        <div class="grid-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" for="password">Kata Sandi Baru *</label>
                                <div class="password-field-wrap">
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required autocomplete="new-password" oninput="checkPasswordStrength(this)">
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
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi kata sandi" required autocomplete="new-password" oninput="checkPasswordMatch()">
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
                            <span>Autosave aktif: Data formulir tersimpan otomatis di browser Anda.</span>
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

                                    $isSelected = (old('first_choice_division_id', optional($preselectedDivision)->id) == $d->id);
                                @endphp
                                <div class="division-select-card {{ $themeClass }} {{ $isSelected ? 'selected' : '' }}" 
                                     id="divCardPrimary_{{ $d->id }}" 
                                     data-id="{{ $d->id }}" 
                                     data-name="{{ $d->name }}" 
                                     onclick="selectPrimaryDivision('{{ $d->id }}', '{{ $d->name }}')">
                                    <div class="division-card-header">
                                        <div class="division-card-icon">
                                            <i class="fas {{ $divIcon }}"></i>
                                        </div>
                                        <div class="division-card-radio"></div>
                                    </div>
                                    <h4 class="division-card-title">{{ $d->name }}</h4>
                                    <p class="division-card-desc">
                                        {{ $d->description ?: $defaultDesc }}
                                    </p>
                                    <div class="division-card-footer">
                                        <span class="badge" style="background: rgba(37,99,235,0.08); color: #2563eb; font-weight: 700;">
                                            <i class="fas fa-users" style="margin-right: 0.3rem;"></i> Kuota: {{ $d->recruitment_quota ?: 'Terbuka' }}
                                        </span>
                                        <span style="font-weight: 600; color: #10b981;">
                                            <i class="fas fa-circle-check"></i> Buka
                                        </span>
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
                            Jika kuota pada divisi utama telah penuh atau Anda memiliki minat sekunder, panitia dapat mempertimbangkan divisi cadangan ini.
                        </p>
                        <div class="secondary-division-pills">
                            @foreach ($divisions as $d)
                                <div class="secondary-division-pill {{ (old('second_choice_division_id') == $d->id) ? 'selected' : '' }}" 
                                     id="divPillSecondary_{{ $d->id }}" 
                                     data-id="{{ $d->id }}" 
                                     onclick="selectSecondaryDivision('{{ $d->id }}')">
                                    <i class="fas fa-check-circle" style="font-size: 0.8rem; display: {{ (old('second_choice_division_id') == $d->id) ? 'inline-block' : 'none' }};"></i>
                                    <span>{{ $d->name }}</span>
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
                        <textarea id="reason_to_join" name="reason_to_join" rows="5" class="form-control @error('reason_to_join') is-invalid @enderror" placeholder="Ceritakan ketertarikan Anda pada divisi yang dipilih, pengalaman relevan sebelumnya, dan kontribusi apa yang ingin Anda berikan..." required oninput="countReasonChars(this)">{{ old('reason_to_join') }}</textarea>
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
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.75rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--slate-200);">
                        <div>
                            <span class="badge badge-info" style="font-size: 0.775rem; margin-bottom: 0.4rem;">
                                Tahap 3 dari 3: Berkas & Konfirmasi
                            </span>
                            <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.25rem;">
                                Unggah Dokumen Berkas & Konfirmasi Pendaftaran
                            </h3>
                            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                                Unggah Kartu Tanda Mahasiswa (KTM) aktif dan portofolio (jika ada), lalu periksa kembali kebenaran data Anda.
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 1.5rem; font-weight: 800; color: #10b981; font-family: var(--font-mono);">03</span>
                            <span style="font-size: 0.8rem; color: var(--slate-400); display: block;">/ 03 Tahap</span>
                        </div>
                    </div>

                    <!-- Dropzone Berkas KTM & CV -->
                    <div class="grid-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.75rem;">
                        <!-- KTM Upload -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                                <span><i class="fas fa-id-card" style="color: #2563eb; margin-right: 0.35rem;"></i> Foto / Scan KTM Aktif</span>
                                <span style="font-size: 0.75rem; color: var(--slate-400);">Maks 2MB (JPG/PNG/PDF)</span>
                            </label>
                            <div class="dropzone-clay" id="ktmDropzone">
                                <input type="file" id="file_ktm" name="file_ktm" accept=".jpg,.jpeg,.png,.pdf" onchange="handleFileKtmSelect(this)">
                                <div class="dropzone-icon" style="color: #2563eb;">
                                    <i class="fas fa-file-arrow-up"></i>
                                </div>
                                <div style="font-weight: 700; color: var(--slate-800); font-size: 0.9rem;">
                                    Upload Foto / PDF KTM
                                </div>
                                <p style="font-size: 0.75rem; color: var(--slate-500); margin: 0.25rem 0 0;">
                                    Format JPG, PNG, atau PDF.
                                </p>
                                <div class="dropzone-preview" id="ktmDropzonePreview">
                                    <div class="dropzone-preview-thumb" id="ktmThumbWrap" style="display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #2563eb; background: #eff6ff;">
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
                            @error('file_ktm') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <!-- CV Upload -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                                <span><i class="fas fa-file-pdf" style="color: #ef4444; margin-right: 0.35rem;"></i> CV / Resume Singkat (Opsional)</span>
                                <span style="font-size: 0.75rem; color: var(--slate-400);">Maks 3MB (PDF)</span>
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
                                    Bila Anda memiliki riwayat proyek atau sertifikat.
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
                            @error('file_cv') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Tautan GitHub & Portofolio Online -->
                    <div class="grid-2col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label" for="github_url">
                                <i class="fab fa-github" style="margin-right: 0.35rem;"></i> Tautan Profil GitHub (Opsional)
                            </label>
                            <input type="url" id="github_url" name="github_url" class="form-control @error('github_url') is-invalid @enderror" value="{{ old('github_url', auth()->user()?->github_url) }}" placeholder="https://github.com/username" onblur="autoFormatUrl(this)">
                            <small style="color: var(--slate-400); font-size: 0.75rem;">Sangat disukai untuk calon divisi Pemrograman & Cyber Security.</small>
                            @error('github_url') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="portfolio_url">
                                <i class="fas fa-globe" style="margin-right: 0.35rem;"></i> Link Portofolio / Karya Lainnya (Opsional)
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
                            <input type="checkbox" id="agreementCheck" required style="width: 18px; height: 18px; cursor: pointer; margin-top: 2px;">
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
<script>
    const DRAFT_STORAGE_KEY = 'ukm_recruitment_draft_v2';
    let currentStep = 1;
    const totalSteps = 3;
    let isSubmitting = false;

    // Timer debouncing for NIM & Email check
    let nimDebounceTimer = null;
    let emailDebounceTimer = null;
    let autosaveDebounceTimer = null;

    /* -------------------------------------------------------------
       STEP NAVIGATION & UI PROGRESS
       ------------------------------------------------------------- */
    function updateStepUI(step) {
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

        const progressFill = document.getElementById('wizardProgressFill');
        if (progressFill) {
            const pct = (step / totalSteps) * 100;
            progressFill.style.width = pct + '%';
        }

        if (step === 3) {
            refreshSummary();
        }

        // Scroll smoothly to top of form
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

        function mark(el, validCondition) {
            if (!el) return;
            if (!validCondition) {
                el.classList.add('field-invalid');
                el.classList.remove('field-valid');
                el.classList.add('shake-error');
                setTimeout(() => el.classList.remove('shake-error'), 450);
                isValid = false;
                if (!firstInvalid) firstInvalid = el;
            } else {
                el.classList.remove('field-invalid');
                el.classList.add('field-valid');
            }
        }

        mark(fullName, fullName && fullName.value.trim().length >= 3);
        mark(nim, nim && nim.value.trim().length >= 4);
        mark(semester, semester && semester.value !== '');
        mark(phone, phone && phone.value.replace(/[^0-9]/g, '').length >= 9);
        mark(email, email && email.checkValidity() && email.value.trim().length > 0);

        if (password) {
            mark(password, password.value.length >= 8);
            if (passwordConfirm) {
                mark(passwordConfirm, passwordConfirm.value === password.value && passwordConfirm.value.length >= 8);
            }
        }

        if (!isValid && firstInvalid) {
            firstInvalid.focus();
            return false;
        }

        return true;
    }

    function validateStep2() {
        const division = document.getElementById('first_choice_division_id');
        const reason = document.getElementById('reason_to_join');

        let isValid = true;
        let firstInvalid = null;

        if (!division || !division.value) {
            isValid = false;
            const cardGrid = document.querySelector('.division-cards-grid');
            if (cardGrid) {
                cardGrid.classList.add('shake-error');
                setTimeout(() => cardGrid.classList.remove('shake-error'), 450);
            }
        }

        if (!reason || reason.value.trim().length < 20) {
            if (reason) {
                reason.classList.add('field-invalid');
                reason.classList.add('shake-error');
                setTimeout(() => reason.classList.remove('shake-error'), 450);
                if (!firstInvalid) firstInvalid = reason;
            }
            isValid = false;
        } else {
            reason.classList.remove('field-invalid');
            reason.classList.add('field-valid');
        }

        if (!isValid) {
            if (firstInvalid) firstInvalid.focus();
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

    /* -------------------------------------------------------------
       TACTILE DIVISION CARDS SELECTOR
       ------------------------------------------------------------- */
    function selectPrimaryDivision(id, name) {
        document.getElementById('first_choice_division_id').value = id;

        // Update active card class
        document.querySelectorAll('.division-select-card').forEach(card => {
            card.classList.remove('selected');
        });
        const activeCard = document.getElementById('divCardPrimary_' + id);
        if (activeCard) {
            activeCard.classList.add('selected');
        }

        // Disable selected primary division in secondary pills
        const secInput = document.getElementById('second_choice_division_id');
        if (secInput && secInput.value === id) {
            clearSecondaryDivision();
        }

        document.querySelectorAll('.secondary-division-pill').forEach(pill => {
            if (pill.getAttribute('data-id') === id) {
                pill.classList.add('disabled');
            } else {
                pill.classList.remove('disabled');
            }
        });

        triggerAutosave();
        refreshSummary();
    }

    function selectSecondaryDivision(id) {
        const primId = document.getElementById('first_choice_division_id').value;
        if (id === primId) return;

        const secInput = document.getElementById('second_choice_division_id');
        secInput.value = id;

        document.querySelectorAll('.secondary-division-pill').forEach(pill => {
            pill.classList.remove('selected');
            const check = pill.querySelector('i');
            if (check) check.style.display = 'none';
        });

        const activePill = document.getElementById('divPillSecondary_' + id);
        if (activePill) {
            activePill.classList.add('selected');
            const check = activePill.querySelector('i');
            if (check) check.style.display = 'inline-block';
        }

        triggerAutosave();
    }

    function clearSecondaryDivision() {
        const secInput = document.getElementById('second_choice_division_id');
        if (secInput) secInput.value = '';

        document.querySelectorAll('.secondary-division-pill').forEach(pill => {
            pill.classList.remove('selected');
            const check = pill.querySelector('i');
            if (check) check.style.display = 'none';
        });

        triggerAutosave();
    }

    /* -------------------------------------------------------------
       REAL-TIME AVAILABILITY CHECKS (NIM & EMAIL)
       ------------------------------------------------------------- */
    function debounceCheckNim(input) {
        clearTimeout(nimDebounceTimer);
        const val = input.value.trim();
        const loadIcon = document.getElementById('nimLoadingIcon');
        const validIcon = document.getElementById('nimValidIcon');
        const invalidIcon = document.getElementById('nimInvalidIcon');
        const textEl = document.getElementById('nimFeedbackText');

        if (!val || val.length < 4) {
            if (loadIcon) loadIcon.style.display = 'none';
            if (validIcon) validIcon.style.display = 'none';
            if (invalidIcon) invalidIcon.style.display = 'none';
            input.classList.remove('field-valid', 'field-invalid');
            if (textEl) {
                textEl.innerText = 'NIM akan digunakan untuk verifikasi status mahasiswa aktif dan login akun.';
                textEl.style.color = 'var(--slate-400)';
            }
            triggerAutosave();
            return;
        }

        if (loadIcon) loadIcon.style.display = 'block';
        if (validIcon) validIcon.style.display = 'none';
        if (invalidIcon) invalidIcon.style.display = 'none';

        nimDebounceTimer = setTimeout(() => {
            fetch(`/pendaftaran/check-availability?type=nim&value=${encodeURIComponent(val)}`)
                .then(r => r.json())
                .then(data => {
                    if (loadIcon) loadIcon.style.display = 'none';
                    if (data.available) {
                        if (validIcon) validIcon.style.display = 'block';
                        input.classList.remove('field-invalid');
                        input.classList.add('field-valid');
                        if (textEl) {
                            textEl.innerText = data.message || 'NIM siap didaftarkan.';
                            textEl.style.color = '#10b981';
                        }
                    } else {
                        if (invalidIcon) invalidIcon.style.display = 'block';
                        input.classList.remove('field-valid');
                        input.classList.add('field-invalid');
                        if (textEl) {
                            textEl.innerText = data.message;
                            textEl.style.color = '#ef4444';
                        }
                    }
                })
                .catch(() => {
                    if (loadIcon) loadIcon.style.display = 'none';
                });
        }, 500);

        triggerAutosave();
    }

    function debounceCheckEmail(input) {
        clearTimeout(emailDebounceTimer);
        const val = input.value.trim();
        const loadIcon = document.getElementById('emailLoadingIcon');
        const validIcon = document.getElementById('emailValidIcon');
        const invalidIcon = document.getElementById('emailInvalidIcon');
        const textEl = document.getElementById('emailFeedbackText');

        if (!val || !val.includes('@') || !val.includes('.')) {
            if (loadIcon) loadIcon.style.display = 'none';
            if (validIcon) validIcon.style.display = 'none';
            if (invalidIcon) invalidIcon.style.display = 'none';
            input.classList.remove('field-valid', 'field-invalid');
            triggerAutosave();
            return;
        }

        if (loadIcon) loadIcon.style.display = 'block';
        if (validIcon) validIcon.style.display = 'none';
        if (invalidIcon) invalidIcon.style.display = 'none';

        emailDebounceTimer = setTimeout(() => {
            fetch(`/pendaftaran/check-availability?type=email&value=${encodeURIComponent(val)}`)
                .then(r => r.json())
                .then(data => {
                    if (loadIcon) loadIcon.style.display = 'none';
                    if (data.available) {
                        if (validIcon) validIcon.style.display = 'block';
                        input.classList.remove('field-invalid');
                        input.classList.add('field-valid');
                        if (textEl) {
                            textEl.innerText = data.message || 'Email valid & siap digunakan.';
                            textEl.style.color = '#10b981';
                        }
                    } else {
                        if (invalidIcon) invalidIcon.style.display = 'block';
                        input.classList.remove('field-valid');
                        input.classList.add('field-invalid');
                        if (textEl) {
                            textEl.innerText = data.message;
                            textEl.style.color = '#ef4444';
                        }
                    }
                })
                .catch(() => {
                    if (loadIcon) loadIcon.style.display = 'none';
                });
        }, 500);

        triggerAutosave();
    }

    /* -------------------------------------------------------------
       PASSWORD TOGGLE & STRENGTH METER
       ------------------------------------------------------------- */
    function togglePasswordVisibility(fieldId, btn) {
        const input = document.getElementById(fieldId);
        if (!input) return;
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    }

    function checkPasswordStrength(input) {
        const val = input.value;
        const fill = document.getElementById('passwordMeterFill');
        const label = document.getElementById('passwordStrengthLabel');

        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        if (fill && label) {
            fill.className = 'password-meter-fill';
            if (val.length === 0) {
                fill.style.width = '0%';
                label.innerText = 'Kekuatan: -';
                label.style.color = 'var(--slate-400)';
            } else if (score <= 1 || val.length < 8) {
                fill.classList.add('strength-weak');
                label.innerText = 'Kekuatan: Lemah';
                label.style.color = '#ef4444';
            } else if (score === 2 || score === 3) {
                fill.classList.add('strength-medium');
                label.innerText = 'Kekuatan: Cukup Baik';
                label.style.color = '#f59e0b';
            } else {
                fill.classList.add('strength-strong');
                label.innerText = 'Kekuatan: Sangat Kuat';
                label.style.color = '#10b981';
            }
        }

        checkPasswordMatch();
    }

    function checkPasswordMatch() {
        const pass = document.getElementById('password');
        const passConfirm = document.getElementById('password_confirmation');
        const matchText = document.getElementById('passwordMatchText');

        if (!pass || !passConfirm || !matchText) return;

        if (passConfirm.value.length === 0) {
            matchText.innerText = 'Ketik ulang kata sandi yang sama.';
            matchText.style.color = 'var(--slate-400)';
            passConfirm.classList.remove('field-valid', 'field-invalid');
            return;
        }

        if (pass.value === passConfirm.value) {
            matchText.innerText = '✓ Kata sandi cocok!';
            matchText.style.color = '#10b981';
            passConfirm.classList.remove('field-invalid');
            passConfirm.classList.add('field-valid');
        } else {
            matchText.innerText = '✗ Kata sandi belum cocok.';
            matchText.style.color = '#ef4444';
            passConfirm.classList.remove('field-valid');
            passConfirm.classList.add('field-invalid');
        }
    }

    function formatWhatsapp(input) {
        let val = input.value.replace(/[^0-9]/g, '');
        if (val.startsWith('62')) {
            val = '0' + val.substring(2);
        }
        input.value = val;
        triggerAutosave();
    }

    function countReasonChars(textarea) {
        const len = textarea.value.length;
        const countSpan = document.getElementById('reasonCharCount');
        const display = document.getElementById('charCountDisplay');
        const hint = document.getElementById('reasonQualityHint');

        if (countSpan) countSpan.innerText = len;

        if (display && hint) {
            if (len >= 60) {
                display.style.color = '#10b981';
                hint.innerText = 'Luar biasa! Motivasi Anda sangat komprehensif.';
                hint.style.color = '#10b981';
                textarea.classList.remove('field-invalid');
                textarea.classList.add('field-valid');
            } else if (len >= 20) {
                display.style.color = '#2563eb';
                hint.innerText = 'Bagus! Sudah memenuhi syarat minimal 20 karakter.';
                hint.style.color = '#2563eb';
                textarea.classList.remove('field-invalid');
                textarea.classList.add('field-valid');
            } else {
                display.style.color = 'var(--slate-400)';
                hint.innerText = `Kurang ${20 - len} karakter lagi.`;
                hint.style.color = '#ef4444';
            }
        }

        triggerAutosave();
    }

    function autoFormatUrl(input) {
        let val = input.value.trim();
        if (val && !val.startsWith('http://') && !val.startsWith('https://')) {
            input.value = 'https://' + val;
        }
        triggerAutosave();
    }

    /* -------------------------------------------------------------
       SMART CLAY DROPZONE FILE UPLOADERS
       ------------------------------------------------------------- */
    function handlePhotoSelect(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];

        // Client-side file size check (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file foto profil melebihi batas 2MB. Silakan pilih foto dengan resolusi lebih kecil.');
            input.value = '';
            return;
        }

        const preview = document.getElementById('photoDropzonePreview');
        const thumb = document.getElementById('photoThumbImg');
        const nameEl = document.getElementById('photoFileName');
        const sizeEl = document.getElementById('photoFileSize');

        if (nameEl) nameEl.innerText = file.name;
        if (sizeEl) sizeEl.innerText = (file.size / 1024).toFixed(1) + ' KB (Siap diunggah)';

        const reader = new FileReader();
        reader.onload = function(e) {
            if (thumb) thumb.src = e.target.result;
            if (preview) preview.style.display = 'flex';
        };
        reader.readAsDataURL(file);
    }

    function removePhotoUpload(e) {
        if (e) e.stopPropagation();
        const input = document.getElementById('profile_photo');
        if (input) input.value = '';
        const preview = document.getElementById('photoDropzonePreview');
        if (preview) preview.style.display = 'none';
    }

    function handleFileKtmSelect(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];

        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file KTM melebihi batas 2MB. Silakan kompres atau pilih berkas lain.');
            input.value = '';
            return;
        }

        const preview = document.getElementById('ktmDropzonePreview');
        const nameEl = document.getElementById('ktmFileName');
        const sizeEl = document.getElementById('ktmFileSize');

        if (nameEl) nameEl.innerText = file.name;
        if (sizeEl) sizeEl.innerText = (file.size / 1024).toFixed(1) + ' KB (Terverifikasi)';
        if (preview) preview.style.display = 'flex';
    }

    function removeKtmUpload(e) {
        if (e) e.stopPropagation();
        const input = document.getElementById('file_ktm');
        if (input) input.value = '';
        const preview = document.getElementById('ktmDropzonePreview');
        if (preview) preview.style.display = 'none';
    }

    function handleFileCvSelect(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];

        if (file.size > 3 * 1024 * 1024) {
            alert('Ukuran berkas CV melebihi batas 3MB.');
            input.value = '';
            return;
        }

        const preview = document.getElementById('cvDropzonePreview');
        const nameEl = document.getElementById('cvFileName');
        const sizeEl = document.getElementById('cvFileSize');

        if (nameEl) nameEl.innerText = file.name;
        if (sizeEl) sizeEl.innerText = (file.size / 1024).toFixed(1) + ' KB';
        if (preview) preview.style.display = 'flex';
    }

    function removeCvUpload(e) {
        if (e) e.stopPropagation();
        const input = document.getElementById('file_cv');
        if (input) input.value = '';
        const preview = document.getElementById('cvDropzonePreview');
        if (preview) preview.style.display = 'none';
    }

    // Drag and drop event listeners for dropzones
    ['photoDropzone', 'ktmDropzone', 'cvDropzone'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;

        ['dragenter', 'dragover'].forEach(eventName => {
            el.addEventListener(eventName, (e) => {
                e.preventDefault();
                el.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            el.addEventListener(eventName, (e) => {
                e.preventDefault();
                el.classList.remove('dragover');
            }, false);
        });
    });

    /* -------------------------------------------------------------
       AUTOSAVE DRAFT & LOCALSTORAGE RESILIENCY
       ------------------------------------------------------------- */
    function triggerAutosave() {
        clearTimeout(autosaveDebounceTimer);
        autosaveDebounceTimer = setTimeout(() => {
            saveDraftToStorage();
        }, 400);
    }

    function saveDraftToStorage() {
        const draft = {
            full_name: document.getElementById('full_name')?.value || '',
            nim: document.getElementById('nim')?.value || '',
            semester: document.getElementById('semester')?.value || '',
            phone_whatsapp: document.getElementById('phone_whatsapp')?.value || '',
            email: document.getElementById('email')?.value || '',
            class_group: document.getElementById('class_group')?.value || '',
            first_choice_division_id: document.getElementById('first_choice_division_id')?.value || '',
            second_choice_division_id: document.getElementById('second_choice_division_id')?.value || '',
            reason_to_join: document.getElementById('reason_to_join')?.value || '',
            github_url: document.getElementById('github_url')?.value || '',
            portfolio_url: document.getElementById('portfolio_url')?.value || '',
            timestamp: new Date().getTime()
        };

        // Only save if at least NIM or Name has been typed
        if (draft.full_name || draft.nim || draft.reason_to_join) {
            localStorage.setItem(DRAFT_STORAGE_KEY, JSON.stringify(draft));
            showAutosaveToast();
        }
    }

    function showAutosaveToast() {
        const toast = document.getElementById('autosaveToast');
        if (!toast) return;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2000);
    }

    function checkExistingDraft() {
        const raw = localStorage.getItem(DRAFT_STORAGE_KEY);
        if (!raw) return;

        try {
            const draft = JSON.parse(raw);
            const currentName = document.getElementById('full_name')?.value || '';
            const currentNim = document.getElementById('nim')?.value || '';

            // If form was empty, show banner to restore
            if (!currentName && !currentNim && (draft.full_name || draft.nim)) {
                const banner = document.getElementById('draftRecoveryBanner');
                if (banner) banner.style.display = 'flex';
                applyDraftValues(draft);
            }
        } catch (e) {
            console.error('Error loading draft', e);
        }
    }

    function applyDraftValues(draft) {
        if (!draft) return;
        if (draft.full_name) document.getElementById('full_name').value = draft.full_name;
        if (draft.nim) document.getElementById('nim').value = draft.nim;
        if (draft.semester) document.getElementById('semester').value = draft.semester;
        if (draft.phone_whatsapp) document.getElementById('phone_whatsapp').value = draft.phone_whatsapp;
        if (draft.email && !document.getElementById('email').readOnly) document.getElementById('email').value = draft.email;
        if (draft.class_group) document.getElementById('class_group').value = draft.class_group;
        if (draft.reason_to_join) {
            const reason = document.getElementById('reason_to_join');
            if (reason) {
                reason.value = draft.reason_to_join;
                countReasonChars(reason);
            }
        }
        if (draft.github_url) document.getElementById('github_url').value = draft.github_url;
        if (draft.portfolio_url) document.getElementById('portfolio_url').value = draft.portfolio_url;

        if (draft.first_choice_division_id) {
            const card = document.getElementById('divCardPrimary_' + draft.first_choice_division_id);
            if (card) {
                selectPrimaryDivision(draft.first_choice_division_id, card.getAttribute('data-name') || '');
            }
        }

        if (draft.second_choice_division_id) {
            selectSecondaryDivision(draft.second_choice_division_id);
        }
    }

    function dismissDraftBanner() {
        const banner = document.getElementById('draftRecoveryBanner');
        if (banner) banner.style.display = 'none';
    }

    function clearDraftAndReset() {
        localStorage.removeItem(DRAFT_STORAGE_KEY);
        const banner = document.getElementById('draftRecoveryBanner');
        if (banner) banner.style.display = 'none';
        document.getElementById('recruitmentMultiStepForm')?.reset();
        clearSecondaryDivision();
        document.querySelectorAll('.division-select-card').forEach(c => c.classList.remove('selected'));
        document.getElementById('first_choice_division_id').value = '';
        currentStep = 1;
        updateStepUI(1);
    }

    /* -------------------------------------------------------------
       LIVE SUMMARY REFRESH
       ------------------------------------------------------------- */
    function refreshSummary() {
        const nameVal = document.getElementById('full_name')?.value || '-';
        const nimVal = document.getElementById('nim')?.value || '-';
        const semVal = document.getElementById('semester')?.value;
        const emailVal = document.getElementById('email')?.value || '-';

        const primId = document.getElementById('first_choice_division_id')?.value;
        let divText = '-';
        if (primId) {
            const card = document.getElementById('divCardPrimary_' + primId);
            if (card) {
                divText = card.getAttribute('data-name') || '-';
            }
        }

        const nameEl = document.getElementById('summaryName');
        const nimSemEl = document.getElementById('summaryNimSemester');
        const divEl = document.getElementById('summaryDivision');
        const emailEl = document.getElementById('summaryEmail');

        if (nameEl) nameEl.innerText = nameVal;
        if (nimSemEl) nimSemEl.innerText = nimVal + (semVal ? ' (Semester ' + semVal + ')' : '');
        if (divEl) divEl.innerText = divText;
        if (emailEl) emailEl.innerText = emailVal;
    }

    /* -------------------------------------------------------------
       FORM SUBMISSION & ANTI DOUBLE-SUBMIT
       ------------------------------------------------------------- */
    document.addEventListener('DOMContentLoaded', function() {
        // Attach autosave listeners to all text fields
        ['full_name', 'semester', 'class_group', 'github_url', 'portfolio_url'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', triggerAutosave);
        });

        // Initialize draft check
        checkExistingDraft();

        // Check if server returned validation errors and jump to appropriate step
        @if ($errors->has('first_choice_division_id') || $errors->has('second_choice_division_id') || $errors->has('reason_to_join'))
            currentStep = 2;
        @elseif ($errors->has('file_ktm') || $errors->has('file_cv') || $errors->has('github_url') || $errors->has('portfolio_url'))
            currentStep = 3;
        @else
            currentStep = 1;
        @endif

        updateStepUI(currentStep);

        const reasonEl = document.getElementById('reason_to_join');
        if (reasonEl) countReasonChars(reasonEl);

        // Preselect primary division if loaded from controller query parameter
        const preId = document.getElementById('first_choice_division_id')?.value;
        if (preId) {
            const card = document.getElementById('divCardPrimary_' + preId);
            if (card) {
                selectPrimaryDivision(preId, card.getAttribute('data-name') || '');
            }
        }

        // Handle Form Submit with Loading State
        const form = document.getElementById('recruitmentMultiStepForm');
        const submitBtn = document.getElementById('submitFormBtn');
        const submitIcon = document.getElementById('submitIcon');
        const submitText = document.getElementById('submitText');

        if (form && submitBtn) {
            form.addEventListener('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return;
                }

                const agree = document.getElementById('agreementCheck');
                if (agree && !agree.checked) {
                    e.preventDefault();
                    agree.focus();
                    alert('Mohon centang pernyataan persetujuan tata tertib dan keabsahan dokumen sebelum mengirim.');
                    return;
                }

                isSubmitting = true;
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.85';
                submitBtn.style.cursor = 'not-allowed';
                if (submitIcon) submitIcon.className = 'fas fa-circle-notch fa-spin';
                if (submitText) submitText.innerText = 'Mengunggah Berkas & Mendaftarkan Akun...';

                // Clear draft upon successful submission trigger
                localStorage.removeItem(DRAFT_STORAGE_KEY);
            });
        }
    });
</script>
@endsection
