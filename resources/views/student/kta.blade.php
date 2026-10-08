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

    @media screen and (max-width: 768px) {
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

    /* Print Styles (Landscape Standar Card Presentation) */
    @media print {
        @page {
            size: landscape;
            margin: 10mm;
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
            margin: 0 auto !important;
            padding: 0 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            min-height: 100vh !important;
        }
        .kta-card-view {
            box-shadow: none !important;
            border: 1.5px solid #cbd5e1 !important;
            width: 840px !important;
            max-width: 840px !important;
            border-radius: 20px !important;
            padding: 2.25rem 2.5rem 2rem !important;
            margin: 0 auto 20mm !important;
            page-break-inside: avoid !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        #printableKta,
        #printableKtaBack {
            display: block !important;
        }
        .kta-card-view .kta-header {
            flex-direction: row !important;
            text-align: left !important;
        }
        .kta-card-view .kta-body-wrap {
            flex-direction: row !important;
            align-items: flex-start !important;
        }
        .kta-card-view .kta-grid-fields {
            grid-template-columns: 1fr 1fr !important;
        }
        .kta-card-view .kta-footer {
            flex-direction: row !important;
            text-align: left !important;
        }
    }

    /* Back Card Specific Styles */
    .kta-card-back {
        background: radial-gradient(circle at 15% 15%, rgba(0, 150, 136, 0.05) 0%, rgba(2, 132, 199, 0.04) 40%, transparent 70%),
                    linear-gradient(135deg, #ffffff 0%, #fbfdfd 50%, #f7fafc 100%);
    }

    .kta-back-magstripe {
        height: 38px;
        background: linear-gradient(90deg, #1e293b 0%, #0f172a 50%, #1e293b 100%);
        border-radius: 8px;
        margin: -0.75rem -1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.25rem;
        color: #94a3b8;
        font-family: var(--font-mono, monospace);
        font-size: 0.68rem;
        letter-spacing: 0.14em;
        position: relative;
        z-index: 2;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.4);
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
        <!-- Interactive Front/Back Card Switcher -->
        <div class="kta-view-switcher no-print" style="display: flex; justify-content: center; align-items: center; margin-bottom: 1.5rem;">
            <div style="background: #ffffff; padding: 0.35rem 0.45rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: var(--clay-pill); border: 1.5px solid #e2e8f0;">
                <button type="button" id="tabBtnFront" onclick="switchKtaSide('front')" class="btn-side-tab active" style="padding: 0.55rem 1.35rem; border-radius: 9999px; border: none; font-weight: 800; font-size: 0.825rem; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.45rem; background: #0284c7; color: #ffffff;">
                    <i class="fas fa-id-card"></i> Sisi Depan
                </button>
                <button type="button" id="tabBtnBack" onclick="switchKtaSide('back')" class="btn-side-tab" style="padding: 0.55rem 1.35rem; border-radius: 9999px; border: none; font-weight: 800; font-size: 0.825rem; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.45rem; background: transparent; color: #64748b;">
                    <i class="fas fa-id-badge"></i> Sisi Belakang
                </button>
                <button type="button" onclick="flipKtaSide()" class="btn-flip" title="Balik Sisi Kartu" style="padding: 0.55rem 0.85rem; border-radius: 9999px; border: 1px solid #e2e8f0; background: #f8fafc; color: #0c2340; cursor: pointer; font-size: 0.825rem; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s ease;">
                    <i class="fas fa-rotate"></i>
                </button>
            </div>
        </div>

        <!-- Physical KTA Card View (Front - High Precision Landscape) -->
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
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="kta-member-photo-img" crossorigin="anonymous" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=300&background=0284c7&color=ffffff&bold=true';">
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
                    <div style="font-size: 1.65rem; font-weight: 900; color: #0c2340; line-height: 1.2; margin-bottom: 0.25rem; text-transform: capitalize; word-break: break-word;">
                        {{ $user->name }}
                    </div>

                    <!-- Dynamic Student NIM -->
                    <div style="font-size: 1.15rem; font-weight: 800; color: #334155; margin-bottom: 1.25rem; font-family: var(--font-mono); letter-spacing: 0.02em;">
                        {{ $user->nim ?? ($member?->nim ?? ($recruitment?->nim ?? '24210124')) }}
                    </div>

                    <!-- 2x2 Field Grid -->
                    <div class="kta-grid-fields" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem 1.25rem;">
                        <!-- Program Studi -->
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <div class="kta-field-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                </svg>
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <div style="font-size: 0.72rem; font-weight: 800; color: #0c2340; letter-spacing: 0.03em; text-transform: uppercase; margin-bottom: 2px;">
                                    PROGRAM STUDI
                                </div>
                                <div style="font-size: 0.825rem; font-weight: 600; color: #475569; line-height: 1.4; padding-bottom: 3px; word-break: break-word;">
                                    {{ $user->study_program ?? ($recruitment?->major ?? 'S1 Ilmu Komputer') }}
                                </div>
                            </div>
                        </div>

                        <!-- Fakultas -->
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
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
                            <div style="min-width: 0; flex: 1;">
                                <div style="font-size: 0.72rem; font-weight: 800; color: #0c2340; letter-spacing: 0.03em; text-transform: uppercase; margin-bottom: 2px;">
                                    FAKULTAS
                                </div>
                                <div style="font-size: 0.825rem; font-weight: 600; color: #475569; line-height: 1.4; padding-bottom: 3px; word-break: break-word;">
                                    Fakultas Sains, Teknologi, dan Ilmu Kesehatan
                                </div>
                            </div>
                        </div>

                        <!-- Unit Kegiatan Mahasiswa -->
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <div class="kta-field-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <div style="font-size: 0.72rem; font-weight: 800; color: #0c2340; letter-spacing: 0.03em; text-transform: uppercase; margin-bottom: 2px;">
                                    UNIT KEGIATAN MAHASISWA
                                </div>
                                <div style="display: inline-block; background: #e0f2fe; color: #0369a1; padding: 0.2rem 0.65rem; border-radius: 6px; font-weight: 700; font-size: 0.775rem; line-height: 1.4; margin-top: 0.15rem;">
                                    UKM Teknologi dan Inovasi
                                </div>
                            </div>
                        </div>

                        <!-- Jabatan / Divisi -->
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <div class="kta-field-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <div style="font-size: 0.72rem; font-weight: 800; color: #0c2340; letter-spacing: 0.03em; text-transform: uppercase; margin-bottom: 2px;">
                                    JABATAN / DIVISI
                                </div>
                                <div style="font-size: 0.825rem; font-weight: 600; color: #475569; line-height: 1.4; padding-bottom: 3px; word-break: break-word;">
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

        <!-- Physical KTA Card View (Back - High Precision Landscape) -->
        <div class="kta-card-view kta-card-back" id="printableKtaBack" style="display: none;">
            <!-- Background Security Motif & Guilloche Pattern Layer -->
            <div class="kta-bg-motif" aria-hidden="true">
                <!-- Vector Guilloche Security Waves & Tech Hex Matrix -->
                <svg class="kta-motif-waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 560" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="ktaWaveTealBack" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#009688" stop-opacity="0.08"/>
                            <stop offset="50%" stop-color="#0284c7" stop-opacity="0.06"/>
                            <stop offset="100%" stop-color="#f97316" stop-opacity="0.03"/>
                        </linearGradient>
                        <linearGradient id="ktaWaveBlueBack" x1="100%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#0c2340" stop-opacity="0.07"/>
                            <stop offset="50%" stop-color="#009688" stop-opacity="0.05"/>
                            <stop offset="100%" stop-color="#0284c7" stop-opacity="0.07"/>
                        </linearGradient>
                        <pattern id="ktaSecDotsBack" x="0" y="0" width="22" height="22" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1.2" fill="#009688" fill-opacity="0.07" />
                        </pattern>
                        <pattern id="ktaSecHexBack" x="0" y="0" width="32" height="55.425" patternUnits="userSpaceOnUse">
                            <path d="M 16 0 L 32 9.237 L 32 27.713 L 16 36.95 L 0 27.713 L 0 9.237 Z M 0 55.425 L 16 46.188 L 32 55.425" fill="none" stroke="#0284c7" stroke-width="0.75" stroke-opacity="0.05" />
                        </pattern>
                    </defs>

                    <rect x="0" y="0" width="900" height="560" fill="url(#ktaSecHexBack)" />
                    <rect x="0" y="0" width="900" height="560" fill="url(#ktaSecDotsBack)" />

                    <!-- Concentric Circles Watermark -->
                    <g transform="translate(250, 280)" stroke="url(#ktaWaveTealBack)" fill="none" stroke-width="0.8">
                        <circle r="70" stroke-dasharray="4,4" />
                        <circle r="110" />
                        <circle r="150" stroke-dasharray="6,3" />
                        <circle r="190" />
                    </g>

                    <!-- Guilloche Harmonic Waves -->
                    <g fill="none" stroke="url(#ktaWaveTealBack)">
                        <path d="M-60,180 C120,110 260,280 480,200 C700,120 780,260 960,170" stroke-width="1.4" />
                        <path d="M-60,198 C128,128 268,298 488,218 C708,138 788,278 960,188" stroke-width="1.2" />
                        <path d="M-60,216 C136,146 276,316 496,236 C716,156 796,296 960,206" stroke-width="1.0" />
                    </g>
                    <g fill="none" stroke="url(#ktaWaveBlueBack)">
                        <path d="M-60,350 C160,430 340,210 560,320 C780,420 820,270 960,340" stroke-width="1.4" />
                        <path d="M-60,368 C168,448 348,228 568,338 C788,438 828,288 960,358" stroke-width="1.1" />
                    </g>
                </svg>

                <div class="kta-watermark-logo" style="left: 25px; right: auto;">
                    <img src="{{ asset('images/kta_logo_left.png') }}" alt="" style="filter: grayscale(40%);">
                </div>

                <div class="kta-microtext-strip">
                    UNIT KEGIATAN MAHASISWA ILMU KOMPUTER &bull; UNIVERSITAS BINA BANGSA GETSEMPENA &bull; OFFICIAL MEMBERSHIP CARD BACKSIDE &bull; SECURITY CODE VERIFIED &bull;
                </div>
            </div>

            <!-- Magnetic Stripe Simulator / Smart Chip Header -->
            <div class="kta-back-magstripe">
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <i class="fas fa-microchip" style="color: #38bdf8;"></i>
                    <span>INTEGRATED SMART CAMPUS CREDENTIAL</span>
                </div>
                <div>
                    <span>SEC-ID: {{ strtoupper(substr(md5($user->id . ($user->nim ?? 'KTA')), 0, 10)) }}</span>
                </div>
            </div>

            <!-- Top Header Back Side -->
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #009688; padding-bottom: 0.75rem; margin-bottom: 1.15rem; position: relative; z-index: 2;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <img src="{{ asset('images/kta_logo_left.png') }}" alt="Logo UKM" style="height: 38px; width: auto; object-fit: contain;">
                    <div>
                        <div style="font-size: 0.95rem; font-weight: 900; color: #0c2340; letter-spacing: -0.01em; line-height: 1.2;">
                            UNIT KEGIATAN MAHASISWA ILMU KOMPUTER
                        </div>
                        <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; line-height: 1.2;">
                            Universitas Bina Bangsa Getsempena (UBBG) Banda Aceh
                        </div>
                    </div>
                </div>
                <div>
                    <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-weight: 800; font-size: 0.68rem; padding: 0.3rem 0.75rem; border-radius: 9999px;">
                        <i class="fas fa-id-badge" style="margin-right: 0.25rem;"></i> SISI BELAKANG
                    </span>
                </div>
            </div>

            <!-- Body Grid: Rules (Left) & Sign/Barcode (Right) -->
            <div style="display: grid; grid-template-columns: 1.3fr 0.9fr; gap: 1.5rem; align-items: flex-start; margin-bottom: 1rem; position: relative; z-index: 2;">
                <!-- Left Column: Ketentuan & Tata Tertib -->
                <div>
                    <div style="font-size: 0.75rem; font-weight: 800; color: #009688; letter-spacing: 0.07em; text-transform: uppercase; margin-bottom: 0.45rem; display: flex; align-items: center; gap: 0.4rem;">
                        <i class="fas fa-shield-halved"></i> KETENTUAN PENGGUNAAN KARTU
                    </div>
                    <ol style="margin: 0 0 0.85rem 0; padding-left: 1.15rem; color: #334155; font-size: 0.735rem; line-height: 1.5;">
                        <li style="margin-bottom: 0.25rem;">KTA ini adalah tanda pengenal resmi anggota aktif UKM Ilmu Komputer UBBG.</li>
                        <li style="margin-bottom: 0.25rem;">Wajib dibawa pada kegiatan praktikum lab, workshop divisi, riset, dan rapat akbar.</li>
                        <li style="margin-bottom: 0.25rem;">Hak penggunaan kartu ini melekat secara pribadi dan tidak dapat dipindahtangankan.</li>
                        <li style="margin-bottom: 0.25rem;">Penyalahgunaan kartu dikenakan sanksi tata tertib organisasi sesuai AD/ART UKM.</li>
                        <li style="margin-bottom: 0.25rem;">Jika kartu hilang atau ditemukan, mohon serahkan ke Sekretariat UKM Ilmu Komputer.</li>
                    </ol>

                    <!-- Info Kontak Sekretariat -->
                    <div style="background: rgba(248, 250, 252, 0.85); border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.55rem 0.75rem; font-size: 0.7rem; color: #64748b; line-height: 1.45;">
                        <div style="font-weight: 700; color: #0c2340; margin-bottom: 0.15rem; display: flex; align-items: center; gap: 0.3rem;">
                            <i class="fas fa-building-columns" style="color: #0284c7;"></i> Sekretariat Pusat:
                        </div>
                        Gedung FSTIK Lt. 2, Kampus Terpadu UBBG, Banda Aceh<br>
                        Email: <span style="color: #0284c7; font-weight: 600;">ukm@ilkom.ubbg.ac.id</span> &bull; Web: <span style="font-weight: 600;">ukmilkom.id</span>
                    </div>
                </div>

                <!-- Right Column: Pengesahan & Barcode -->
                <div style="background: rgba(255, 255, 255, 0.95); border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 0.85rem 1rem; box-shadow: inset 0 2px 6px rgba(0,0,0,0.02); display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div style="font-size: 0.7rem; font-weight: 800; color: #0c2340; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 0.15rem;">
                        PENGESAHAN RESMI
                    </div>
                    <div style="font-size: 0.68rem; color: #64748b; margin-bottom: 0.6rem;">
                        Banda Aceh, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </div>

                    <!-- Stamp & Signature Block -->
                    <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; margin-bottom: 0.6rem; width: 100%;">
                        <!-- Digital Stamp Badge -->
                        <div style="border: 2px dashed #009688; border-radius: 50%; width: 62px; height: 62px; display: flex; flex-direction: column; align-items: center; justify-content: center; transform: rotate(-8deg); background: rgba(0,150,136,0.05); color: #009688; flex-shrink: 0;">
                            <i class="fas fa-certificate" style="font-size: 1.1rem; margin-bottom: 1px;"></i>
                            <span style="font-size: 0.52rem; font-weight: 900; letter-spacing: 0.04em;">SAH</span>
                            <span style="font-size: 0.42rem; font-weight: 700;">UKM ILKOM</span>
                        </div>
                        <div style="text-align: left;">
                            <div style="font-size: 0.68rem; font-weight: 800; color: #0c2340;">
                                Dewan Pengurus Pusat
                            </div>
                            <div style="font-size: 0.62rem; color: #009688; font-weight: 700; margin-top: 0.1rem;">
                                <i class="fas fa-check-double"></i> Terverifikasi Sistem
                            </div>
                            <div style="font-size: 0.62rem; color: #64748b; margin-top: 0.1rem;">
                                Periode {{ $member?->batch_year ?? '2026/2027' }}
                            </div>
                        </div>
                    </div>

                    <!-- Clean Vector Barcode -->
                    <div style="width: 100%; border-top: 1px dashed #e2e8f0; padding-top: 0.6rem; margin-top: 0.15rem;">
                        <div style="display: flex; justify-content: center; align-items: center; height: 32px; gap: 2px; margin-bottom: 0.2rem;" title="Barcode NIM {{ $user->nim ?? '24210124' }}">
                            @php
                                $bars = [3,1,2,1,4,1,2,3,1,2,1,3,2,1,4,1,2,1,3,2,1,4,1,2,3,1,1,3,2,1,4,1,2,3,1,2,1,4,2,1,3,1,2,4,2];
                            @endphp
                            @foreach($bars as $idx => $w)
                                <span style="display: inline-block; width: {{ $w }}px; height: 100%; background: {{ $idx % 2 === 0 ? '#0c2340' : 'transparent' }};"></span>
                            @endforeach
                        </div>
                        <div style="font-family: var(--font-mono, monospace); font-size: 0.72rem; font-weight: 800; color: #0c2340; letter-spacing: 0.14em;">
                            * {{ $user->nim ?? ($member?->nim ?? ($recruitment?->nim ?? '24210124')) }} *
                        </div>
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
                    <div style="font-size: 0.775rem; color: #64748b;">Standar kartu pintar identitas kampus (CR80) &bull; Depan & Belakang</div>
                </div>
            </div>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <button id="btnDownloadPdf" onclick="downloadKtaAsPdf()" class="btn btn-primary" style="padding: 0.7rem 1.35rem; font-weight: 700; border-radius: 9999px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3); display: inline-flex; align-items: center;">
                    <i class="fas fa-file-pdf" style="margin-right: 0.45rem;"></i> Unduh PDF Lengkap (2 Sisi)
                </button>
                <button id="btnDownloadFrontPng" onclick="downloadKtaAsPng('front')" class="btn btn-outline" style="padding: 0.7rem 1.25rem; border-radius: 9999px; background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); font-weight: 700; display: inline-flex; align-items: center;">
                    <i class="fas fa-file-image" style="margin-right: 0.45rem; color: #0284c7;"></i> PNG Depan
                </button>
                <button id="btnDownloadBackPng" onclick="downloadKtaAsPng('back')" class="btn btn-outline" style="padding: 0.7rem 1.25rem; border-radius: 9999px; background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); font-weight: 700; display: inline-flex; align-items: center;">
                    <i class="fas fa-file-image" style="margin-right: 0.45rem; color: #009688;"></i> PNG Belakang
                </button>
                <button onclick="window.print()" class="btn btn-outline" style="padding: 0.7rem 1.25rem; border-radius: 9999px; background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); font-weight: 700; display: inline-flex; align-items: center;">
                    <i class="fas fa-print" style="margin-right: 0.45rem; color: #64748b;"></i> Cetak Langsung
                </button>
                <a href="{{ route('student.profile.edit') }}" class="btn btn-outline" style="padding: 0.7rem 1.25rem; border-radius: 9999px; background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); font-weight: 700; display: inline-flex; align-items: center;">
                    <i class="fas fa-camera" style="margin-right: 0.45rem; color: #0284c7;"></i> Ganti Pas Foto
                </a>
                <a href="{{ url('/verifikasi?code=' . ($recruitment?->registration_code ?? $user->nim)) }}" target="_blank" class="btn btn-outline" style="padding: 0.7rem 1.25rem; border-radius: 9999px; background: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); font-weight: 700; display: inline-flex; align-items: center;">
                    <i class="fas fa-qrcode" style="margin-right: 0.45rem; color: #16a34a;"></i> Cek Validasi
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    let currentKtaSide = 'front';

    function switchKtaSide(side) {
        currentKtaSide = side;
        const cardFront = document.getElementById('printableKta');
        const cardBack = document.getElementById('printableKtaBack');
        const tabFront = document.getElementById('tabBtnFront');
        const tabBack = document.getElementById('tabBtnBack');

        if (side === 'front') {
            if (cardFront) cardFront.style.display = 'block';
            if (cardBack) cardBack.style.display = 'none';
            if (tabFront) {
                tabFront.style.background = '#0284c7';
                tabFront.style.color = '#ffffff';
            }
            if (tabBack) {
                tabBack.style.background = 'transparent';
                tabBack.style.color = '#64748b';
            }
        } else {
            if (cardFront) cardFront.style.display = 'none';
            if (cardBack) cardBack.style.display = 'block';
            if (tabBack) {
                tabBack.style.background = '#0284c7';
                tabBack.style.color = '#ffffff';
            }
            if (tabFront) {
                tabFront.style.background = 'transparent';
                tabFront.style.color = '#64748b';
            }
        }
    }

    function flipKtaSide() {
        switchKtaSide(currentKtaSide === 'front' ? 'back' : 'front');
    }

    async function generateKtaCanvas(targetSide = 'front') {
        if (document.fonts && document.fonts.ready) {
            await document.fonts.ready;
        }

        const cardId = targetSide === 'back' ? 'printableKtaBack' : 'printableKta';
        const card = document.getElementById(cardId);
        if (!card) return null;

        // Pastikan kartu sementara tampil agar dapat diukur oleh html2canvas
        const wasHidden = card.style.display === 'none';
        if (wasHidden) {
            card.style.display = 'block';
        }

        try {
            return await html2canvas(card, {
                scale: 3,
                useCORS: true,
                allowTaint: true,
                logging: false,
                backgroundColor: '#ffffff',
                windowWidth: 1280,
                onclone: function(clonedDoc) {
                    const clonedTarget = clonedDoc.getElementById(cardId);
                    if (clonedTarget) {
                        clonedTarget.style.display = 'block';
                        clonedTarget.style.width = '840px';
                        clonedTarget.style.maxWidth = '840px';
                        clonedTarget.style.margin = '0 auto';
                    }

                    if (targetSide === 'front') {
                        // Perbaiki rasio foto pas mahasiswa agar TIDAK ketarik/gepeng (object-fit: cover via Canvas 2D)
                        try {
                            const originalImg = document.querySelector('.kta-member-photo-img');
                            const clonedImg = clonedDoc.querySelector('.kta-member-photo-img');

                            if (originalImg && clonedImg && originalImg.naturalWidth && originalImg.naturalHeight) {
                                const canvas = document.createElement('canvas');
                                const targetW = clonedImg.offsetWidth || 190;
                                const targetH = clonedImg.offsetHeight || 250;
                                
                                canvas.width = targetW * 3;
                                canvas.height = targetH * 3;
                                canvas.style.width = '100%';
                                canvas.style.height = '100%';
                                canvas.style.display = 'block';
                                canvas.style.borderRadius = 'inherit';

                                const ctx = canvas.getContext('2d');
                                const nw = originalImg.naturalWidth;
                                const nh = originalImg.naturalHeight;

                                // Perhitungan presisi aspect-ratio cover
                                const scale = Math.max(canvas.width / nw, canvas.height / nh);
                                const sw = canvas.width / scale;
                                const sh = canvas.height / scale;
                                const sx = (nw - sw) / 2;
                                const sy = (nh - sh) / 2;

                                ctx.drawImage(originalImg, sx, sy, sw, sh, 0, 0, canvas.width, canvas.height);
                                clonedImg.parentNode.replaceChild(canvas, clonedImg);
                            }
                        } catch (e) {
                            console.warn('Fallback foto crop canvas:', e);
                        }

                        // Kunci layout dokumen kloningan agar selalu berupa kartu Lanskap utuh
                        if (clonedTarget) {
                            const clonedBody = clonedTarget.querySelector('.kta-body-wrap');
                            if (clonedBody) {
                                clonedBody.style.flexDirection = 'row';
                                clonedBody.style.alignItems = 'flex-start';
                            }
                            const clonedHeader = clonedTarget.querySelector('.kta-header');
                            if (clonedHeader) {
                                clonedHeader.style.flexDirection = 'row';
                                clonedHeader.style.textAlign = 'left';
                            }
                            const clonedFooter = clonedTarget.querySelector('.kta-footer');
                            if (clonedFooter) {
                                clonedFooter.style.flexDirection = 'row';
                                clonedFooter.style.textAlign = 'left';
                            }
                            const clonedGrid = clonedTarget.querySelector('.kta-grid-fields');
                            if (clonedGrid) {
                                clonedGrid.style.gridTemplateColumns = '1fr 1fr';
                            }
                        }
                    }
                }
            });
        } finally {
            if (wasHidden) {
                card.style.display = 'none';
            }
        }
    }

    async function downloadKtaAsPng(side = 'front') {
        const btnId = side === 'back' ? 'btnDownloadBackPng' : 'btnDownloadFrontPng';
        const btn = document.getElementById(btnId);
        const originalText = btn ? btn.innerHTML : '';
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.35rem;"></i> Memproses HD...';
            btn.disabled = true;
        }

        try {
            const canvas = await generateKtaCanvas(side);
            if (!canvas) throw new Error('Canvas render gagal');

            const suffix = side === 'back' ? 'BELAKANG' : 'DEPAN';
            const nim = '{{ $user->nim ?? ($member?->nim ?? ($recruitment?->nim ?? "ANGGOTA")) }}';
            const link = document.createElement('a');
            link.download = `KTA-UKM-ILKOM-${nim}-${suffix}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        } catch (err) {
            console.error('KTA Render Error:', err);
            alert('Tidak dapat mengonversi gambar otomatis. Anda dapat menggunakan opsi "Unduh PDF Lengkap" atau "Cetak Langsung".');
        } finally {
            if (btn) {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
    }

    async function downloadKtaAsPdf() {
        const btn = document.getElementById('btnDownloadPdf');
        if (!btn) return;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.45rem;"></i> Menyusun PDF 2 Sisi...';
        btn.disabled = true;

        try {
            // Render Sisi Depan
            const canvasFront = await generateKtaCanvas('front');
            if (!canvasFront) throw new Error('Canvas sisi depan gagal');
            const imgDataFront = canvasFront.toDataURL('image/png');

            // Render Sisi Belakang
            const canvasBack = await generateKtaCanvas('back');
            if (!canvasBack) throw new Error('Canvas sisi belakang gagal');
            const imgDataBack = canvasBack.toDataURL('image/png');

            const { jsPDF } = window.jspdf;

            // Standar internasional kartu pintar ID-1 / CR80 (lebar 85.6 mm lanskap)
            const cardWidthMm = 85.6;
            const cardHeightMm = Number(((cardWidthMm * canvasFront.height) / canvasFront.width).toFixed(2));

            const pdf = new jsPDF({
                orientation: 'landscape',
                unit: 'mm',
                format: [cardWidthMm, cardHeightMm]
            });

            // Halaman 1: Sisi Depan
            pdf.addImage(imgDataFront, 'PNG', 0, 0, cardWidthMm, cardHeightMm, undefined, 'FAST');

            // Halaman 2: Sisi Belakang
            pdf.addPage([cardWidthMm, cardHeightMm], 'landscape');
            pdf.addImage(imgDataBack, 'PNG', 0, 0, cardWidthMm, cardHeightMm, undefined, 'FAST');

            const nim = '{{ $user->nim ?? ($member?->nim ?? ($recruitment?->nim ?? "ANGGOTA")) }}';
            pdf.save(`KTA-UKM-ILKOM-${nim}-Lengkap-2Sisi.pdf`);
        } catch (err) {
            console.error('KTA PDF Render Error:', err);
            alert('Gagal menyusun PDF otomatis. Anda dapat menggunakan opsi unduh PNG Depan/Belakang atau Cetak Langsung.');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
</script>
@endsection
