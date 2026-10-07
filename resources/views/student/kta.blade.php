@extends('student.layouts.app')

@section('title', 'Kartu Tanda Anggota (KTA Digital)')
@section('page_title', 'Kartu Tanda Anggota (KTA Digital)')

@section('styles')
<style>
    .kta-showcase-wrap {
        max-width: 840px;
        margin: 0 auto 2.5rem;
        width: 100%;
    }

    .kta-page-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    /* Template Physical KTA Card View */
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
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .kta-card-view:hover {
        transform: translateY(-4px);
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.15), 0 0 0 1px rgba(226, 232, 240, 0.9);
    }

    /* Top Accent Line */
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

    /* Bottom Accent Line */
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

    .kta-body-wrap {
        position: relative;
        z-index: 2;
        display: flex;
        gap: 2rem;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .kta-member-photo-box {
        width: 190px;
        height: 250px;
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
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #009688;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
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

    /* Actions Bar */
    .kta-actions-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 1.25rem 1.75rem;
        box-shadow: var(--clay-card);
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }

    /* Information 3-grid */
    .kta-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .kta-info-card {
        background: #ffffff;
        border-radius: var(--radius-xl);
        padding: 1.85rem;
        box-shadow: var(--clay-card);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
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
            gap: 1.25rem;
            text-align: center;
        }
    }

    /* Print Styles (Standarisasi CR80 Card: 85.6mm x 54mm) */
    @media print {
        @page {
            size: auto;
            margin: 15mm;
        }
        body {
            background: #ffffff !important;
            color: #000000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .student-sidebar,
        .student-header,
        .student-topbar,
        .no-print,
        .kta-page-header,
        .kta-actions-card,
        .kta-info-grid,
        .breadcrumb-trail,
        nav,
        footer {
            display: none !important;
        }
        .student-content-area,
        .student-main,
        .student-main-wrap {
            padding: 0 !important;
            margin: 0 !important;
            background: #ffffff !important;
        }
        .kta-showcase-wrap {
            max-width: 100% !important;
            margin: 20mm auto !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }
        .kta-card-view {
            box-shadow: none !important;
            border: 1px dashed #94a3b8 !important;
            width: 85.6mm !important;
            max-width: 85.6mm !important;
            min-height: 54mm !important;
            border-radius: 4mm !important;
            padding: 4mm 5mm !important;
            margin: 0 auto !important;
            page-break-inside: avoid !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        /* Penyesuaian font skala cetak fisik kartu CR80 */
        .kta-card-view .kta-header {
            padding-bottom: 2mm !important;
            margin-bottom: 2.5mm !important;
            border-bottom-width: 1px !important;
        }
        .kta-card-view .kta-member-photo-box {
            width: 22mm !important;
            height: 28mm !important;
            border-radius: 2mm !important;
        }
        .kta-card-view .kta-footer {
            padding-top: 2mm !important;
            margin-top: 2mm !important;
        }
    }
</style>
@endsection

@section('content')

@if ($isAccepted)
    <!-- Page Header & Status -->
    <div class="kta-page-header no-print">
        <div>
            <div style="font-size: 0.8rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.25rem;">
                IDENTITAS DIGITAL MAHASISWA
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 900; color: #0c2340; margin: 0; letter-spacing: -0.02em;">
                Kartu Tanda Anggota (KTA) Resmi
            </h1>
        </div>
        <div>
            <span class="badge" style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; box-shadow: var(--clay-pill); font-weight: 800; font-size: 0.8rem; padding: 0.5rem 1.15rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="fas fa-circle-check" style="color: #16a34a;"></i> STATUS: AKTIF TERVERIFIKASI
            </span>
        </div>
    </div>

    <!-- Centerpiece KTA Showcase Area -->
    <div class="kta-showcase-wrap">
        <!-- Physical KTA Card View (High Precision Landscape) -->
        <div class="kta-card-view" id="printableKta">
            <!-- Background Security Motif & Guilloche Pattern Layer -->
            <div class="kta-bg-motif" aria-hidden="true">
                <!-- Vector Guilloche Security Waves & Tech Hex Matrix -->
                <svg class="kta-motif-waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 560" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="ktaWaveTeal" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#009688" stop-opacity="0.09"/>
                            <stop offset="50%" stop-color="#0284c7" stop-opacity="0.07"/>
                            <stop offset="100%" stop-color="#f97316" stop-opacity="0.04"/>
                        </linearGradient>
                        <linearGradient id="ktaWaveBlue" x1="100%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#0c2340" stop-opacity="0.08"/>
                            <stop offset="50%" stop-color="#009688" stop-opacity="0.06"/>
                            <stop offset="100%" stop-color="#0284c7" stop-opacity="0.08"/>
                        </linearGradient>
                        <!-- Security Micro-Dot Grid -->
                        <pattern id="ktaSecDots" x="0" y="0" width="22" height="22" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1.2" fill="#009688" fill-opacity="0.08" />
                        </pattern>
                        <!-- Security Hex Pattern -->
                        <pattern id="ktaSecHex" x="0" y="0" width="32" height="55.425" patternUnits="userSpaceOnUse">
                            <path d="M 16 0 L 32 9.237 L 32 27.713 L 16 36.95 L 0 27.713 L 0 9.237 Z M 0 55.425 L 16 46.188 L 32 55.425" fill="none" stroke="#0284c7" stroke-width="0.75" stroke-opacity="0.06" />
                        </pattern>
                    </defs>

                    <!-- Subtle Hex and Dot Patterns -->
                    <rect x="230" y="0" width="670" height="560" fill="url(#ktaSecHex)" />
                    <rect x="0" y="0" width="900" height="560" fill="url(#ktaSecDots)" />

                    <!-- Concentric Watermark Security Circles (Campus Official) -->
                    <g transform="translate(640, 275)" stroke="url(#ktaWaveTeal)" fill="none" stroke-width="0.8">
                        <circle r="70" stroke-dasharray="4,4" />
                        <circle r="110" />
                        <circle r="150" stroke-dasharray="6,3" />
                        <circle r="190" />
                        <circle r="230" stroke-dasharray="8,4" />
                        <circle r="270" />
                    </g>

                    <!-- Guilloche Harmonic Sine Waves Band 1 -->
                    <g fill="none" stroke="url(#ktaWaveTeal)">
                        <path d="M-60,160 C120,90 260,260 480,180 C700,100 780,240 960,150" stroke-width="1.6" />
                        <path d="M-60,178 C128,108 268,278 488,198 C708,118 788,258 960,168" stroke-width="1.3" />
                        <path d="M-60,196 C136,126 276,296 496,216 C716,136 796,276 960,186" stroke-width="1.1" />
                        <path d="M-60,214 C144,144 284,314 504,234 C724,154 804,294 960,204" stroke-width="1.0" />
                        <path d="M-60,232 C152,162 292,332 512,252 C732,172 812,312 960,222" stroke-width="1.2" />
                        <path d="M-60,250 C160,180 300,350 520,270 C740,190 820,330 960,240" stroke-width="1.4" />
                    </g>

                    <!-- Guilloche Intersecting Harmonic Waves Band 2 -->
                    <g fill="none" stroke="url(#ktaWaveBlue)">
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

            <!-- Header Card: Logo UKM & Universitas -->
            <div class="kta-header">
                <!-- Left: Molecule UKM Logo + Identitas Resmi -->
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

                <!-- Right: UBBG & HIMAKOM Logos -->
                <div>
                    <img src="{{ asset('images/kta_logo_right.png') }}" alt="Logo UBBG & HIMAKOM" style="height: 52px; width: auto; object-fit: contain;">
                </div>
            </div>

            <!-- Body Card: Photo & Dynamic Student Data -->
            <div class="kta-body-wrap">
                <!-- Photo Box (Left) -->
                <div class="kta-member-photo-box">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="kta-member-photo-img" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=300&background=0284c7&color=ffffff&bold=true';">
                    <!-- Holographic Seal Badge -->
                    <div class="kta-holo-seal" title="Identitas Keaslian Kartu Resmi">
                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#009688" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        <span>VALID</span>
                    </div>
                </div>

                <!-- Profile Information (Right) -->
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 0.775rem; font-weight: 800; color: #009688; letter-spacing: 0.07em; text-transform: uppercase; margin-bottom: 0.25rem;">
                        PROFIL ANGGOTA
                    </div>

                    <!-- Dynamic Student Name -->
                    <div style="font-size: 1.65rem; font-weight: 900; color: #0c2340; line-height: 1.15; margin-bottom: 0.25rem; text-transform: capitalize; word-break: break-word;">
                        {{ $user->name }}
                    </div>

                    <!-- Dynamic Student NIM -->
                    <div style="font-size: 1.15rem; font-weight: 800; color: #334155; margin-bottom: 1.35rem; font-family: var(--font-mono); letter-spacing: 0.02em;">
                        {{ $user->nim ?? ($member?->nim ?? ($recruitment?->nim ?? '24210124')) }}
                    </div>

                    <!-- 2x2 Field Grid -->
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
                                    {{ $user->study_program ?? ($recruitment?->major ?? 'S1 Ilmu Komputer') }}
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
                                    Anggota &bull; {{ $division?->name ?? 'Divisi Teknologi' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Card: Verification & QR -->
            <div class="kta-footer">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.6rem;">
                        <span class="badge badge-success" style="font-size: 0.725rem; font-weight: 700; background: #dcfce7; color: #15803d; border-color: #bbf7d0;">
                            <i class="fas fa-shield-halved" style="margin-right: 0.25rem;"></i> ANGGOTA RESMI AKTIF
                        </span>
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">
                            Periode {{ $member?->batch_year ?? '2026/2027' }}
                        </span>
                    </div>
                    <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 0.35rem;">
                        Divalidasi resmi oleh UKM Ilmu Komputer &bull; Universitas Bina Bangsa Getsempena
                    </div>
                </div>

                <!-- Right: Scan Verification Text & Dynamic QR Code -->
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="font-size: 0.825rem; font-weight: 800; color: #0c2340; text-align: right; line-height: 1.35; max-width: 140px;">
                        Scan untuk<br>verifikasi<br>anggota
                    </div>
                    <div style="background: #ffffff; padding: 4px; border: 1.5px solid #e2e8f0; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); flex-shrink: 0;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=85x85&data={{ urlencode(url('/verifikasi?code=' . ($recruitment?->registration_code ?? $user->nim))) }}" alt="QR Code" style="width: 70px; height: 70px; display: block;">
                    </div>
                </div>
            </div>

            <!-- Bottom Accent Gradient Bar -->
            <div class="kta-bottom-bar"></div>
        </div>

        <!-- Action Control Bar -->
        <div class="kta-actions-card no-print">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; box-shadow: var(--clay-pill);">
                    <i class="fas fa-fingerprint"></i>
                </div>
                <div>
                    <div style="font-weight: 800; font-size: 0.95rem; color: #0c2340;">Aksi Dokumen Resmi</div>
                    <div style="font-size: 0.775rem; color: #64748b;">Standar kartu pintar identitas kampus (CR80)</div>
                </div>
            </div>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <button onclick="window.print()" class="btn btn-primary" style="padding: 0.7rem 1.35rem; font-weight: 700; border-radius: 9999px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);">
                    <i class="fas fa-print" style="margin-right: 0.4rem;"></i> Cetak / Simpan PDF
                </button>
                <button id="btnDownloadKta" onclick="downloadKtaAsPng()" class="btn btn-outline" style="padding: 0.7rem 1.25rem; border-radius: 9999px; background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); font-weight: 700;">
                    <i class="fas fa-file-image" style="margin-right: 0.4rem; color: #0284c7;"></i> Unduh Gambar HD (PNG)
                </button>
                <a href="{{ route('student.profile.edit') }}" class="btn btn-outline" style="padding: 0.7rem 1.25rem; border-radius: 9999px; background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); font-weight: 700;">
                    <i class="fas fa-camera" style="margin-right: 0.4rem; color: #0284c7;"></i> Ganti Pas Foto
                </a>
                <a href="{{ url('/verifikasi?code=' . ($recruitment?->registration_code ?? $user->nim)) }}" target="_blank" class="btn btn-outline" style="padding: 0.7rem 1.25rem; border-radius: 9999px; background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); font-weight: 700;">
                    <i class="fas fa-qrcode" style="margin-right: 0.4rem; color: #16a34a;"></i> Cek Validasi
                </a>
            </div>
        </div>

        <!-- Information 3-grid Below Card -->
        <div class="kta-info-grid no-print">
            <div class="kta-info-card">
                <div>
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; margin-bottom: 1rem; box-shadow: var(--clay-pill);">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <h4 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0 0 0.45rem;">
                        Verifikasi Presensi & Sesi
                    </h4>
                    <p style="font-size: 0.85rem; color: #64748b; line-height: 1.55; margin: 0;">
                        Barcode QR pada kartu terenkripsi unik dengan NIM Anda untuk absensi kehadiran rapat akbar dan workshop divisi secara otomatis.
                    </p>
                </div>
            </div>

            <div class="kta-info-card">
                <div>
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; margin-bottom: 1rem; box-shadow: var(--clay-pill);">
                        <i class="fas fa-flask"></i>
                    </div>
                    <h4 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0 0 0.45rem;">
                        Akses Laboratorium & Riset
                    </h4>
                    <p style="font-size: 0.85rem; color: #64748b; line-height: 1.55; margin: 0;">
                        Tunjukkan kartu KTA ini kepada pengawas untuk meminjam modul praktikum IoT, akses repositori lokal, serta workstation riset UKM.
                    </p>
                </div>
            </div>

            <div class="kta-info-card">
                <div>
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; margin-bottom: 1rem; box-shadow: var(--clay-pill);">
                        <i class="fas fa-award"></i>
                    </div>
                    <h4 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0 0 0.45rem;">
                        Validitas Sertifikat & Portofolio
                    </h4>
                    <p style="font-size: 0.85rem; color: #64748b; line-height: 1.55; margin: 0;">
                        Data identitas terhubung langsung ke sistem E-Sertifikat dan arsip karya mahasiswa untuk portofolio akademik kelulusan.
                    </p>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Locked KTA View For Applicants -->
    <div style="max-width: 650px; margin: 2rem auto; background: #ffffff; border-radius: var(--radius-xl); padding: 3rem 2rem; border: 1px dashed #cbd5e1; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
        <div style="width: 72px; height: 72px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.85rem; margin: 0 auto 1.5rem; box-shadow: 0 4px 10px rgba(217, 119, 6, 0.15);">
            <i class="fas fa-id-card-clip"></i>
        </div>
        <h2 style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
            Kartu Tanda Anggota (KTA) Digital Belum Terbit
        </h2>
        <p style="font-size: 0.9rem; color: #64748b; line-height: 1.6; max-width: 520px; margin: 0 auto 1.75rem;">
            Saat ini Anda masih berstatus sebagai <strong>Calon Anggota (Tahap Seleksi)</strong>. KTA Digital resmi dengan QR Code verifikasi sistem akan otomatis aktif dan dapat diunduh segera setelah Anda dinyatakan <strong>Lolos Seleksi</strong> oleh pengurus divisi.
        </p>
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline btn-sm">
            &larr; Pantau Jadwal & Status Seleksi
        </a>
    </div>
@endif

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    function downloadKtaAsPng() {
        const btn = document.getElementById('btnDownloadKta');
        if (!btn) return;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.4rem;"></i> Memproses HD...';
        btn.disabled = true;

        const card = document.getElementById('printableKta');
        if (!card) {
            btn.innerHTML = originalText;
            btn.disabled = false;
            return;
        }

        html2canvas(card, {
            scale: 3,
            useCORS: true,
            allowTaint: true,
            logging: false,
            backgroundColor: null
        }).then(function(canvas) {
            const link = document.createElement('a');
            link.download = 'KTA-UKM-ILKOM-{{ $user->nim ?? "ANGGOTA" }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            btn.innerHTML = originalText;
            btn.disabled = false;
        }).catch(function(err) {
            console.error('KTA Render Error:', err);
            alert('Tidak dapat mengonversi gambar otomatis. Anda dapat menggunakan opsi "Cetak / Simpan PDF".');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>
@endsection
