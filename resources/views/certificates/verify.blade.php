@extends('layouts.app')

@section('title', 'Verifikasi E-Sertifikat & Anggota - UKM Ilmu Komputer')

@section('styles')
<style>
    /* Official Template KTA Card Styling */
    .kta-card-view {
        background: radial-gradient(circle at 85% 15%, rgba(0, 150, 136, 0.05) 0%, rgba(2, 132, 199, 0.03) 40%, transparent 70%),
                    linear-gradient(135deg, #ffffff 0%, #fbfdfd 50%, #f7fafc 100%);
        border-radius: 24px;
        padding: 2.25rem 2.5rem 2rem;
        color: #0f172a;
        position: relative;
        overflow: hidden;
        border: 2px solid #e2e8f0;
        box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.1), 0 0 0 1px rgba(226, 232, 240, 0.8);
        max-width: 780px;
        margin: 0 auto 2.5rem;
    }

    .kta-card-view::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 8px;
        background: linear-gradient(90deg, #0c2340 0%, #0c2340 50%, #009688 50%, #009688 78%, #f97316 78%, #f97316 100%);
        z-index: 5;
    }

    .kta-bottom-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 9px;
        background: linear-gradient(90deg, #0c2340 0%, #009688 65%, #f97316 100%);
        border-radius: 0 0 24px 24px;
        z-index: 5;
    }

    /* Background Motif & Security Elements */
    .kta-bg-motif {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 1;
        overflow: hidden;
    }

    .kta-motif-waves {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .kta-watermark-logo {
        position: absolute;
        right: 25px;
        top: 48%;
        transform: translateY(-50%);
        width: 300px;
        height: 300px;
        opacity: 0.055;
        pointer-events: none;
        mix-blend-mode: multiply;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .kta-watermark-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: grayscale(30%);
    }

    .kta-microtext-strip {
        position: absolute;
        bottom: 12px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 6.5px;
        font-family: var(--font-mono, monospace);
        letter-spacing: 0.24em;
        font-weight: 700;
        color: #0c2340;
        opacity: 0.12;
        text-transform: uppercase;
        user-select: none;
        pointer-events: none;
    }

    .kta-header {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 1.15rem;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #009688;
    }

    .kta-member-photo-box {
        width: 180px;
        height: 240px;
        flex-shrink: 0;
        background: #f0f7fb;
        border: 2px solid #e0f2fe;
        border-radius: 18px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 18px -4px rgba(12, 35, 64, 0.08), inset 0 2px 5px rgba(0,0,0,0.03);
        position: relative;
    }

    .kta-member-photo-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .kta-holo-seal {
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(204,251,241,0.92) 35%, rgba(224,242,254,0.92) 70%, rgba(254,243,199,0.92) 100%);
        border: 1px solid rgba(255,255,255,0.95);
        border-radius: 6px;
        padding: 3px 6px;
        font-size: 8px;
        font-weight: 800;
        color: #0c2340;
        display: flex;
        align-items: center;
        gap: 3px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        backdrop-filter: blur(2px);
    }

    .kta-field-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #009688;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(0, 150, 136, 0.25);
    }

    .kta-footer {
        position: relative;
        z-index: 2;
        padding-top: 1.25rem;
        margin-top: 0.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @media (max-width: 768px) {
        .kta-card-view {
            padding: 1.5rem 1.25rem;
        }
        .kta-body-wrap {
            flex-direction: column !important;
            align-items: center !important;
        }
        .kta-grid-fields {
            grid-template-columns: 1fr !important;
        }
        .kta-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        .kta-footer {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }

    @media print {
        header, nav, footer, .section-header, .no-print, .search-card-wrap {
            display: none !important;
        }
        .kta-card-view {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            page-break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endsection

@section('content')
<div style="padding: 4.5rem 0 5.5rem;">
    <div class="container-narrow">
        <!-- Header -->
        <div class="section-header" style="margin-bottom: 2.5rem;">
            <div class="section-tag">Validasi Keaslian Dokumen</div>
            <h1 class="section-title">Verifikasi E-Sertifikat & Anggota</h1>
            <p class="section-desc">
                Periksa keabsahan e-sertifikat kegiatan, status keanggotaan aktif (KTA/KTM), atau tanda kelulusan workshop resmi UKM Ilmu Komputer.
            </p>
        </div>

        <!-- Search Box Form -->
        <div class="card search-card-wrap no-print" style="padding: 2.25rem; margin-bottom: 3rem; border: none;">
            <form action="{{ route('certificates.verify') }}" method="GET">
                <label for="code" class="form-label" style="font-weight: 700; margin-bottom: 0.75rem;">
                    Masukkan Nomor Induk Mahasiswa (NIM) atau Kode Sertifikat:
                </label>
                <div style="display: flex; gap: 0.85rem; flex-wrap: wrap;">
                    <input type="text" id="code" name="code" value="{{ $code }}" placeholder="Contoh: 2301010099 atau CERT-ILKOM-2026-0812" class="form-control" style="flex: 1; font-family: var(--font-mono); font-size: 1rem; padding: 0.95rem 1.25rem;" required>
                    <button type="submit" class="btn btn-primary btn-lg" style="border-radius: var(--radius-full);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Cek Keabsahan</span>
                    </button>
                </div>
                <div style="font-size: 0.825rem; color: var(--slate-400); margin-top: 0.85rem;">
                    *Sistem otomatis mendeteksi barcode QR KTA Mahasiswa maupun nomor seri E-Sertifikat.
                </div>
            </form>
        </div>

        <!-- Result Section -->
        @if ($searched)
            @if ($member)
                <!-- KASUS 1: ANGGOTA RESMI AKTIF TERVERIFIKASI (KTM / KTA DITEMUKAN) -->
                <div style="margin-bottom: 1.5rem; text-align: center;">
                    <div class="cert-stamp" style="box-shadow: var(--clay-pill); display: inline-flex; background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-weight: 800; padding: 0.6rem 1.4rem; border-radius: var(--radius-full); align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>STATUS: KEANGGOTAAN TERVERIFIKASI & AKTIF</span>
                    </div>
                    <p style="font-size: 0.95rem; color: var(--slate-600); max-width: 620px; margin: 0 auto 2rem;">
                        Data identitas di bawah ini terdaftar secara sah dan tercatat aktif dalam pangkalan data resmi UKM Ilmu Komputer Universitas Bina Bangsa Getsempena.
                    </p>
                </div>

                <!-- RENDERING OFFICIAL PHYSICAL KTA / KTM DIGITAL -->
                <div class="kta-card-view">
                    <!-- Background Security Motif & Guilloche Pattern Layer -->
                    <div class="kta-bg-motif" aria-hidden="true">
                        <!-- Vector Guilloche Security Waves & Tech Hex Matrix -->
                        <svg class="kta-motif-waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 560" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="ktaVerifyWaveTeal" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#009688" stop-opacity="0.09"/>
                                    <stop offset="50%" stop-color="#0284c7" stop-opacity="0.07"/>
                                    <stop offset="100%" stop-color="#f97316" stop-opacity="0.04"/>
                                </linearGradient>
                                <linearGradient id="ktaVerifyWaveBlue" x1="100%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" stop-color="#0c2340" stop-opacity="0.08"/>
                                    <stop offset="50%" stop-color="#009688" stop-opacity="0.06"/>
                                    <stop offset="100%" stop-color="#0284c7" stop-opacity="0.08"/>
                                </linearGradient>
                                <!-- Security Micro-Dot Grid -->
                                <pattern id="ktaVerifySecDots" x="0" y="0" width="22" height="22" patternUnits="userSpaceOnUse">
                                    <circle cx="2" cy="2" r="1.2" fill="#009688" fill-opacity="0.08" />
                                </pattern>
                                <!-- Security Hex Pattern -->
                                <pattern id="ktaVerifySecHex" x="0" y="0" width="32" height="55.425" patternUnits="userSpaceOnUse">
                                    <path d="M 16 0 L 32 9.237 L 32 27.713 L 16 36.95 L 0 27.713 L 0 9.237 Z M 0 55.425 L 16 46.188 L 32 55.425" fill="none" stroke="#0284c7" stroke-width="0.75" stroke-opacity="0.06" />
                                </pattern>
                            </defs>

                            <!-- Subtle Hex and Dot Patterns -->
                            <rect x="230" y="0" width="670" height="560" fill="url(#ktaVerifySecHex)" />
                            <rect x="0" y="0" width="900" height="560" fill="url(#ktaVerifySecDots)" />

                            <!-- Concentric Watermark Security Circles (Campus Official) -->
                            <g transform="translate(640, 275)" stroke="url(#ktaVerifyWaveTeal)" fill="none" stroke-width="0.8">
                                <circle r="70" stroke-dasharray="4,4" />
                                <circle r="110" />
                                <circle r="150" stroke-dasharray="6,3" />
                                <circle r="190" />
                                <circle r="230" stroke-dasharray="8,4" />
                                <circle r="270" />
                            </g>

                            <!-- Guilloche Harmonic Sine Waves Band 1 -->
                            <g fill="none" stroke="url(#ktaVerifyWaveTeal)">
                                <path d="M-60,160 C120,90 260,260 480,180 C700,100 780,240 960,150" stroke-width="1.6" />
                                <path d="M-60,178 C128,108 268,278 488,198 C708,118 788,258 960,168" stroke-width="1.3" />
                                <path d="M-60,196 C136,126 276,296 496,216 C716,136 796,276 960,186" stroke-width="1.1" />
                                <path d="M-60,214 C144,144 284,314 504,234 C724,154 804,294 960,204" stroke-width="1.0" />
                                <path d="M-60,232 C152,162 292,332 512,252 C732,172 812,312 960,222" stroke-width="1.2" />
                                <path d="M-60,250 C160,180 300,350 520,270 C740,190 820,330 960,240" stroke-width="1.4" />
                            </g>

                            <!-- Guilloche Intersecting Harmonic Waves Band 2 -->
                            <g fill="none" stroke="url(#ktaVerifyWaveBlue)">
                                <path d="M-60,370 C160,450 340,230 560,340 C780,440 820,290 960,360" stroke-width="1.5" />
                                <path d="M-60,388 C168,468 348,248 568,358 C788,458 828,308 960,378" stroke-width="1.2" />
                                <path d="M-60,406 C176,486 356,266 576,376 C796,476 836,326 960,396" stroke-width="1.0" />
                                <path d="M-60,424 C184,504 364,284 584,394 C804,494 844,344 960,414" stroke-width="1.1" />
                                <path d="M-60,442 C192,522 372,302 592,412 C812,512 852,362 960,432" stroke-width="1.3" />
                            </g>
                        </svg>

                        <!-- Large Watermark Molecule / UKM Logo in background -->
                        <div class="kta-watermark-logo">
                            <img src="{{ asset('images/kta_logo_left.png') }}" alt="">
                        </div>

                        <!-- Micro-print security ribbon -->
                        <div class="kta-microtext-strip">
                            UNIVERSITAS BINA BANGSA GETSEMPENA &bull; UKM ILMU KOMPUTER &bull; OFFICIAL MEMBERSHIP CARD &bull; VERIFIED CREDENTIAL &bull; DIGITAL IDENTIFICATION SYSTEM &bull;
                        </div>
                    </div>

                    <!-- Header Card -->
                    <div class="kta-header">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <img src="{{ asset('images/kta_logo_left.png') }}" alt="Logo UKM" style="height: 64px; width: auto; object-fit: contain;">
                            <div>
                                <div style="font-size: 0.72rem; font-weight: 700; color: #0284c7; letter-spacing: 0.08em; text-transform: uppercase;">
                                    IDENTITAS RESMI ANGGOTA
                                </div>
                                <div style="font-size: 1.35rem; font-weight: 900; color: #0c2340; letter-spacing: -0.01em; line-height: 1.15;">
                                    KARTU TANDA ANGGOTA UKM
                                </div>
                                <div style="font-size: 0.85rem; font-weight: 600; color: #64748b; line-height: 1.2;">
                                    Universitas Bina Bangsa Getsempena
                                </div>
                            </div>
                        </div>

                        <div>
                            <img src="{{ asset('images/kta_logo_right.png') }}" alt="UBBG & HIMAKOM" style="height: 52px; width: auto; object-fit: contain;">
                        </div>
                    </div>

                    <!-- Body Card -->
                    <div class="kta-body-wrap" style="display: flex; gap: 2rem; align-items: flex-start; margin-bottom: 1.5rem;">
                        <!-- Photo Box -->
                        <div class="kta-member-photo-box">
                            <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="kta-member-photo-img" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&size=300&background=0284c7&color=ffffff&bold=true';">
                            <!-- Holographic Seal Badge -->
                            <div class="kta-holo-seal" title="Identitas Keaslian Kartu Resmi">
                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#009688" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                                <span>VALID</span>
                            </div>
                        </div>

                        <!-- Profile Info -->
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 0.775rem; font-weight: 800; color: #009688; letter-spacing: 0.07em; text-transform: uppercase; margin-bottom: 0.25rem;">
                                PROFIL ANGGOTA
                            </div>

                            <div style="font-size: 1.65rem; font-weight: 900; color: #0c2340; line-height: 1.15; margin-bottom: 0.25rem; text-transform: capitalize; word-break: break-word;">
                                {{ $member->name }}
                            </div>

                            <div style="font-size: 1.15rem; font-weight: 800; color: #334155; margin-bottom: 1.35rem; font-family: var(--font-mono); letter-spacing: 0.02em;">
                                {{ $member->nim }}
                            </div>

                            <!-- 2x2 Fields Grid -->
                            <div class="kta-grid-fields" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem 1.25rem;">
                                <!-- Program Studi -->
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div class="kta-field-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                        </svg>
                                    </div>
                                    <div style="min-width: 0;">
                                        <div style="font-size: 0.72rem; font-weight: 800; color: #0c2340; letter-spacing: 0.03em; text-transform: uppercase;">
                                            PROGRAM STUDI
                                        </div>
                                        <div style="font-size: 0.825rem; font-weight: 600; color: #475569; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $member->user?->study_program ?? ($member->recruitment?->major ?? 'S1 Ilmu Komputer') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Fakultas -->
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div class="kta-field-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="2" y1="22" x2="22" y2="22"/>
                                            <line x1="4" y1="18" x2="20" y2="18"/>
                                            <line x1="6" y1="18" x2="6" y2="11"/>
                                            <line x1="10" y1="18" x2="10" y2="11"/>
                                            <line x1="14" y1="18" x2="14" y2="11"/>
                                            <line x1="18" y1="18" x2="18" y2="11"/>
                                            <polygon points="12 2 2 7 22 7"/>
                                        </svg>
                                    </div>
                                    <div style="min-width: 0;">
                                        <div style="font-size: 0.72rem; font-weight: 800; color: #0c2340; letter-spacing: 0.03em; text-transform: uppercase;">
                                            FAKULTAS
                                        </div>
                                        <div style="font-size: 0.825rem; font-weight: 600; color: #475569; line-height: 1.3;">
                                            Fakultas Sains, Teknologi, dan Ilmu Kesehatan
                                        </div>
                                    </div>
                                </div>

                                <!-- Unit Kegiatan Mahasiswa -->
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div class="kta-field-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                        </svg>
                                    </div>
                                    <div style="min-width: 0;">
                                        <div style="font-size: 0.72rem; font-weight: 800; color: #0c2340; letter-spacing: 0.03em; text-transform: uppercase;">
                                            UNIT KEGIATAN MAHASISWA
                                        </div>
                                        <div style="display: inline-block; background: #e0f2fe; color: #0369a1; padding: 0.2rem 0.65rem; border-radius: 6px; font-weight: 700; font-size: 0.775rem; margin-top: 0.15rem;">
                                            UKM Teknologi dan Inovasi
                                        </div>
                                    </div>
                                </div>

                                <!-- Jabatan / Divisi -->
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div class="kta-field-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                    <div style="min-width: 0;">
                                        <div style="font-size: 0.72rem; font-weight: 800; color: #0c2340; letter-spacing: 0.03em; text-transform: uppercase;">
                                            JABATAN / DIVISI
                                        </div>
                                        <div style="font-size: 0.825rem; font-weight: 600; color: #475569; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            Anggota &bull; {{ $member->division?->name ?? 'Divisi Teknologi' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Card -->
                    <div class="kta-footer">
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.6rem;">
                                <span class="badge badge-success" style="font-size: 0.725rem; font-weight: 700; background: #dcfce7; color: #15803d; border-color: #bbf7d0;">
                                    <i class="fas fa-shield-halved" style="margin-right: 0.3rem;"></i> ANGGOTA RESMI AKTIF
                                </span>
                                <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">
                                    Periode {{ $member->batch_year ?? '2026/2027' }}
                                </span>
                            </div>
                            <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 0.35rem;">
                                Divalidasi resmi oleh UKM Ilmu Komputer &bull; Universitas Bina Bangsa Getsempena
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="font-size: 0.825rem; font-weight: 800; color: #0c2340; text-align: right; line-height: 1.35; max-width: 140px;">
                                Scan untuk<br>verifikasi<br>anggota
                            </div>
                            <div style="background: #ffffff; padding: 4px; border: 1.5px solid #e2e8f0; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); flex-shrink: 0;">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=85x85&data={{ urlencode(url('/verifikasi?code=' . $member->nim)) }}" alt="QR Code" style="width: 70px; height: 70px; display: block;">
                            </div>
                        </div>
                    </div>

                    <div class="kta-bottom-bar"></div>
                </div>

                <!-- Verification Extra Information Details Box -->
                <div class="card no-print" style="max-width: 780px; margin: 0 auto; padding: 2rem; border-radius: var(--radius-xl); box-shadow: var(--clay-card); margin-bottom: 2rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1.25rem;">
                        <i class="fas fa-circle-check" style="color: #10b981; margin-right: 0.4rem;"></i> Informasi Autentikasi Anggota
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; font-size: 0.875rem; margin-bottom: 1.5rem;">
                        <div>
                            <span style="color: var(--slate-500); display: block; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Status Keanggotaan</span>
                            <strong style="color: #15803d;">Aktif Terdaftar (Sivitas Resmi)</strong>
                        </div>
                        <div>
                            <span style="color: var(--slate-500); display: block; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Dosen Pembina Divisi</span>
                            <strong style="color: var(--slate-900);">{{ $member->division?->adviser_name }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--slate-500); display: block; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Ketua Divisi</span>
                            <strong style="color: var(--slate-900);">{{ $member->division?->leader_name }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--slate-500); display: block; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Email Akun</span>
                            <strong style="color: var(--slate-900);">{{ $member->email }}</strong>
                        </div>
                    </div>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <button onclick="window.print()" class="btn btn-primary btn-sm">
                            <i class="fas fa-print" style="margin-right: 0.3rem;"></i> Cetak Dokumen Verifikasi
                        </button>
                        <a href="{{ route('certificates.verify') }}" class="btn btn-outline btn-sm">
                            Cek Verifikasi Lain
                        </a>
                    </div>
                </div>

            @elseif ($certificate)
                <!-- KASUS 2: E-SERTIFIKAT KEGIATAN DITEMUKAN -->
                <div class="cert-card-wrap" style="box-shadow: var(--clay-card); border-radius: var(--radius-xl); border: none;">
                    <div class="cert-stamp" style="box-shadow: var(--clay-pill);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>TERVERIFIKASI RESMI</span>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <span class="badge badge-info" style="font-family: var(--font-mono); font-size: 0.825rem; padding: 0.4rem 0.9rem; box-shadow: var(--clay-pill);">
                            {{ $certificate->certificate_code }}
                        </span>
                        <div style="font-size: 0.775rem; color: var(--slate-400); margin-top: 0.45rem;">
                            ID Kredensial Resmi Universitas
                        </div>
                    </div>

                    <div style="border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                        <div style="font-size: 0.825rem; font-weight: 600; text-transform: uppercase; color: var(--slate-400); letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                            Nama Penerima Sertifikat
                        </div>
                        <h2 style="font-size: 1.85rem; font-weight: 800; color: var(--slate-900);">
                            {{ $certificate->recipient_name }}
                        </h2>
                        @if ($certificate->recipient_nim)
                            <div style="font-family: var(--font-mono); font-size: 0.925rem; color: var(--slate-600); margin-top: 0.25rem;">
                                NIM: {{ $certificate->recipient_nim }}
                            </div>
                        @endif
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                        <div>
                            <div style="font-size: 0.8rem; color: var(--slate-400); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">
                                Nama Kegiatan / Pelatihan
                            </div>
                            <div style="font-size: 1.05rem; font-weight: 700; color: var(--slate-900);">
                                {{ $certificate->event_name }}
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 0.8rem; color: var(--slate-400); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">
                                Peran / Kualifikasi
                            </div>
                            <div style="font-size: 1.05rem; font-weight: 700; color: var(--accent-blue);">
                                {{ $certificate->role_as }}
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 0.8rem; color: var(--slate-400); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">
                                Tanggal Penerbitan
                            </div>
                            <div style="font-size: 0.95rem; font-weight: 600; color: var(--slate-800);">
                                {{ $certificate->issue_date->format('d F Y') }}
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 0.8rem; color: var(--slate-400); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">
                                Penerbit Dokumen
                            </div>
                            <div style="font-size: 0.95rem; font-weight: 600; color: var(--slate-800);">
                                Pengurus UKM Ilmu Komputer
                            </div>
                        </div>
                    </div>

                    <div style="background: var(--bg-body); box-shadow: var(--clay-debossed); border-radius: var(--radius-md); padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                        <div style="font-size: 0.85rem; color: var(--slate-600); line-height: 1.5;">
                            Dokumen ini tercatat dalam pangkalan data terpusat dan memiliki kekuatan pembuktian digital sebagai portofolio resmi mahasiswa.
                        </div>
                        <button onclick="window.print()" class="btn btn-outline btn-sm" style="border-radius: var(--radius-full);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Cetak Kredensial</span>
                        </button>
                    </div>
                </div>
            @else
                <!-- KASUS 3: DATA TIDAK DITEMUKAN -->
                <div class="card" style="padding: 3.5rem 2rem; text-align: center; border-left: 6px solid var(--danger); border-radius: var(--radius-xl);">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: #fee2e2; color: #dc2626; box-shadow: var(--clay-pill); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h3 style="font-size: 1.4rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.5rem;">
                        Data Tidak Ditemukan
                    </h3>
                    <p style="color: var(--slate-600); font-size: 0.95rem; max-width: 520px; margin: 0 auto 1.75rem; line-height: 1.6;">
                        Nomor identitas NIM atau kode sertifikat <strong>"{{ $code }}"</strong> tidak terdaftar dalam pangkalan data anggota resmi maupun arsip sertifikat kami. Mohon pastikan tidak ada kesalahan ketik karakter atau tanda hubung (-).
                    </p>
                    <a href="{{ route('certificates.verify') }}" class="btn btn-outline btn-sm" style="border-radius: var(--radius-full);">Coba Pencarian Baru</a>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
