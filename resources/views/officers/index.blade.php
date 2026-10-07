@extends('layouts.app')

@section('title', 'Struktur Kepengurusan & Dewan Pembina - UKM Ilmu Komputer')

@section('styles')
<style>
    /* ==========================================================================
       OFFICERS LUXURY WHITE CLAYMORPHISM SYSTEM (CENTERED & CLEAN)
       ========================================================================== */
    .officers-hero {
        text-align: center;
        padding: 4rem 1.5rem 2.5rem;
        position: relative;
    }

    .officers-nav-pills {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.65rem;
        margin: 2rem auto 0;
        max-width: 960px;
    }

    .officer-nav-pill {
        background: #ffffff;
        color: #475569;
        font-size: 0.825rem;
        font-weight: 700;
        padding: 0.55rem 1.15rem;
        border-radius: 9999px;
        border: 1.5px solid #e2e8f0;
        box-shadow: var(--clay-pill);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .officer-nav-pill:hover {
        transform: translateY(-2px);
        color: #0284c7;
        border-color: #7dd3fc;
        box-shadow: 0 8px 20px rgba(2, 132, 199, 0.15);
    }

    /* Section Subheadings */
    .officer-section-header {
        text-align: center;
        margin-bottom: 2.25rem;
    }

    .officer-section-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #e0f2fe;
        color: #0284c7;
        font-size: 0.775rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 0.35rem 0.95rem;
        border-radius: 9999px;
        box-shadow: var(--clay-pill);
        margin-bottom: 0.65rem;
    }

    /* Centered Flex Layouts for Perfect Balance */
    .officers-centered-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1.75rem;
        margin: 0 auto;
    }

    /* Executive & Officer Card Base */
    .executive-card {
        width: 290px;
        max-width: 100%;
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid rgba(226, 232, 240, 0.95);
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.02);
        overflow: hidden;
        position: relative;
        display: flex;
        flex-direction: column;
        transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.28s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.28s ease;
    }

    .executive-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 35px -8px rgba(15, 23, 42, 0.12), 0 1px 3px rgba(0, 0, 0, 0.05);
        border-color: rgba(2, 132, 199, 0.4);
    }

    /* Card Top Gradient Banner */
    .card-banner-stripe {
        height: 75px;
        position: relative;
        background: linear-gradient(135deg, #0c2340 0%, #0369a1 60%, #009688 100%);
    }

    .card-banner-stripe.pemrograman {
        background: linear-gradient(135deg, #0f172a 0%, #0284c7 60%, #38bdf8 100%);
    }

    .card-banner-stripe.multimedia {
        background: linear-gradient(135deg, #311042 0%, #7c3aed 60%, #ec4899 100%);
    }

    .card-banner-stripe.iot {
        background: linear-gradient(135deg, #451a03 0%, #d97706 60%, #fbbf24 100%);
    }

    .card-banner-stripe.cyber {
        background: linear-gradient(135deg, #062c21 0%, #059669 60%, #10b981 100%);
    }

    .card-banner-stripe.dosen {
        background: linear-gradient(135deg, #451a03 0%, #b45309 50%, #f59e0b 100%);
    }

    /* Avatar Staging */
    .avatar-stage {
        margin-top: -46px;
        display: flex;
        justify-content: center;
        position: relative;
        z-index: 2;
    }

    .executive-avatar-box {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: #ffffff;
        border: 4px solid #ffffff;
        box-shadow: 0 10px 20px -3px rgba(15, 23, 42, 0.15), var(--clay-card);
        overflow: hidden;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .executive-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .executive-avatar-initials {
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 30% 30%, #f8fafc 0%, #e2e8f0 100%);
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.85rem;
        font-weight: 900;
        font-family: var(--font-sans);
        letter-spacing: -0.02em;
    }

    /* Role Badge Pill */
    .executive-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.725rem;
        font-weight: 800;
        padding: 0.28rem 0.75rem;
        border-radius: 9999px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.5rem;
        box-shadow: var(--clay-pill);
    }

    .badge-bph {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .badge-dosen {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .badge-staff {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    /* Card Details */
    .executive-body {
        padding: 1rem 1.5rem 1.5rem;
        text-align: center;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .executive-name {
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 0.25rem;
        line-height: 1.3;
    }

    .executive-position {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0284c7;
        margin-bottom: 0.4rem;
    }

    .executive-id-badge {
        font-size: 0.75rem;
        color: #64748b;
        font-family: var(--font-mono, monospace);
        background: #f8fafc;
        padding: 0.2rem 0.65rem;
        border-radius: 6px;
        display: inline-block;
        border: 1px solid #e2e8f0;
        margin-bottom: 1rem;
    }

    /* Social Action Strip */
    .executive-social-strip {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.6rem;
        padding-top: 0.85rem;
        border-top: 1px dashed #e2e8f0;
    }

    .social-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        text-decoration: none;
        box-shadow: var(--clay-pill);
        transition: all 0.2s ease;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #64748b;
    }

    .social-btn:hover {
        transform: scale(1.1);
    }

    .social-btn.linkedin:hover {
        background: #0a66c2;
        color: #ffffff;
        border-color: #0a66c2;
    }

    .social-btn.github:hover {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }

    .social-btn.instagram:hover {
        background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285aeb 90%);
        color: #ffffff;
        border-color: transparent;
    }

    /* Division Segment Clean Title Block */
    .division-clean-block {
        margin-bottom: 4rem;
        scroll-margin-top: 90px;
    }

    .division-clean-header {
        text-align: center;
        margin-bottom: 1.75rem;
    }
</style>
@endsection

@section('content')
<div style="background: #f8fafc; padding-bottom: 5rem;">

    <!-- ==========================================
         HERO SECTION
         ========================================== -->
    <div class="container">
        <div class="officers-hero">
            <span class="officer-section-badge">
                <i class="fas fa-sitemap"></i> STRUKTUR KEPENGURUSAN
            </span>
            <h1 style="font-size: 2.65rem; font-weight: 900; color: #0c2340; letter-spacing: -0.02em; margin: 0 0 0.85rem; line-height: 1.2;">
                Susunan Organisasi & Pengurus UKM
            </h1>
            <p style="color: #64748b; font-size: 1.05rem; max-width: 720px; margin: 0 auto; line-height: 1.6;">
                Mengenal nakhoda, para pembimbing ahli, dan tim koordinator 4 divisi spesialisasi UKM Ilmu Komputer yang berdedikasi memajukan riset, kurikulum, dan teknologi kampus.
            </p>

            <!-- Quick Navigation Jump Pills (FontAwesome Icons, No Emojis) -->
            <div class="officers-nav-pills">
                <a href="#bph-section" class="officer-nav-pill">
                    <i class="fas fa-crown" style="color: #0284c7;"></i> Dewan Pembina & BPH
                </a>
                <a href="#pemrograman-section" class="officer-nav-pill">
                    <i class="fas fa-code" style="color: #0284c7;"></i> Pemrograman
                </a>
                <a href="#multimedia-section" class="officer-nav-pill">
                    <i class="fas fa-palette" style="color: #7c3aed;"></i> Multimedia
                </a>
                <a href="#iot-section" class="officer-nav-pill">
                    <i class="fas fa-microchip" style="color: #d97706;"></i> IoT & Hardware
                </a>
                <a href="#cyber-section" class="officer-nav-pill">
                    <i class="fas fa-shield-halved" style="color: #059669;"></i> Cyber Security
                </a>
            </div>
        </div>
    </div>

    <div class="container">

        <!-- ==========================================
             1. DEWAN PEMBINA & BPH (BADAN PENGURUS HARIAN)
             ========================================== -->
        <section id="bph-section" style="margin-bottom: 4.5rem; scroll-margin-top: 90px;">
            <div class="officer-section-header">
                <span class="officer-section-badge" style="background: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-award"></i> TATA KELOLA TERTINGGI
                </span>
                <h2 style="font-size: 1.85rem; font-weight: 850; color: #0f172a; margin: 0 0 0.35rem;">
                    Dewan Pembina & Badan Pengurus Harian (BPH)
                </h2>
                <p style="color: #64748b; font-size: 0.95rem; margin: 0; max-width: 600px; margin: 0 auto;">
                    Penanggung jawab arah kebijakan strategis, tata kelola administrasi, dan koordinasi umum organisasi UKM.
                </p>
            </div>

            <div class="officers-centered-grid">
                @foreach ($bphOfficers as $officer)
                    @php
                        $posLower = strtolower($officer->position);
                        $isDosen = str_contains($posLower, 'pembina') || str_contains($posLower, 'pembimbing') || str_contains($posLower, 'dosen');
                        $isLeader = str_contains($posLower, 'ketua') || str_contains($posLower, 'presiden') || str_contains($posLower, 'wakil');
                    @endphp
                    <div class="executive-card">
                        <!-- Top Stripe -->
                        <div class="card-banner-stripe {{ $isDosen ? 'dosen' : '' }}"></div>

                        <!-- Avatar Stage -->
                        <div class="avatar-stage">
                            <div class="executive-avatar-box">
                                @if ($officer->photo)
                                    <img src="{{ asset('storage/' . $officer->photo) }}" alt="{{ $officer->name }}" class="executive-avatar-img">
                                @else
                                    <div class="executive-avatar-initials" style="{{ $isDosen ? 'background: #fef3c7; color: #b45309;' : '' }}">
                                        {{ strtoupper(substr($officer->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="executive-body">
                            <div>
                                <!-- Role Badge -->
                                <div>
                                    @if ($isDosen)
                                        <span class="executive-badge badge-dosen">
                                            <i class="fas fa-graduation-cap"></i> Dewan Pembina
                                        </span>
                                    @elseif ($isLeader)
                                        <span class="executive-badge badge-bph">
                                            <i class="fas fa-crown"></i> Pimpinan Inti
                                        </span>
                                    @else
                                        <span class="executive-badge badge-bph">
                                            <i class="fas fa-user-check"></i> Pengurus Harian
                                        </span>
                                    @endif
                                </div>

                                <h3 class="executive-name">{{ $officer->name }}</h3>
                                <div class="executive-position">{{ $officer->position }}</div>
                                <div class="executive-id-badge">
                                    <i class="fas {{ $isDosen ? 'fa-id-badge' : 'fa-id-card' }}" style="margin-right: 0.25rem;"></i>
                                    {{ $isDosen ? 'NIP' : 'NIM' }}: {{ $officer->nim ?? '-' }}
                                </div>
                            </div>

                            <!-- Social Links Strip -->
                            <div class="executive-social-strip">
                                @if (isset($officer->social_links['linkedin']) && $officer->social_links['linkedin'])
                                    <a href="{{ $officer->social_links['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="social-btn linkedin" title="LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                @endif
                                @if (isset($officer->social_links['github']) && $officer->social_links['github'])
                                    <a href="{{ $officer->social_links['github'] }}" target="_blank" rel="noopener noreferrer" class="social-btn github" title="GitHub">
                                        <i class="fab fa-github"></i>
                                    </a>
                                @endif
                                @if (isset($officer->social_links['instagram']) && $officer->social_links['instagram'])
                                    <a href="{{ $officer->social_links['instagram'] }}" target="_blank" rel="noopener noreferrer" class="social-btn instagram" title="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                @endif
                                @if (empty($officer->social_links) || (!isset($officer->social_links['linkedin']) && !isset($officer->social_links['github']) && !isset($officer->social_links['instagram'])))
                                    <span style="font-size: 0.725rem; color: #94a3b8; font-weight: 600;">
                                        <i class="fas fa-circle-check" style="color: #10b981;"></i> Pengurus Terverifikasi
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- ==========================================
             2. STRUKTUR 4 DIVISI SPESIALISASI
             ========================================== -->
        <div style="text-align: center; margin-bottom: 3rem;">
            <span class="officer-section-badge" style="background: #f1f5f9; color: #475569;">
                <i class="fas fa-layer-group"></i> BIDANG SPESIALISASI
            </span>
            <h2 style="font-size: 1.85rem; font-weight: 850; color: #0f172a; margin: 0 0 0.35rem;">
                Struktur Kepengurusan 4 Divisi Spesialisasi
            </h2>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0; max-width: 600px; margin: 0 auto;">
                Dipandu oleh Dosen Pembina keilmuan dan dipimpin oleh Koordinator Divisi mahasiswa.
            </p>
        </div>

        @php
            $divisionsData = [
                [
                    'id' => 'pemrograman-section',
                    'name' => 'Divisi Pemrograman',
                    'slug' => 'pemrograman',
                    'accent' => '#0284c7',
                    'icon' => 'fa-code',
                    'officers' => $pemrogramanOfficers,
                    'desc' => 'Rekayasa Perangkat Lunak, Fullstack Web, Mobile App & Algoritma Tingkat Lanjut',
                    'bg_light' => '#f0f9ff',
                ],
                [
                    'id' => 'multimedia-section',
                    'name' => 'Divisi Multimedia',
                    'slug' => 'multimedia',
                    'accent' => '#7c3aed',
                    'icon' => 'fa-palette',
                    'officers' => $multimediaOfficers,
                    'desc' => 'UI/UX Design, Motion Graphic, Animasi, 3D Modelling & Produksi Audio Visual',
                    'bg_light' => '#faf5ff',
                ],
                [
                    'id' => 'iot-section',
                    'name' => 'Divisi Internet of Things (IoT)',
                    'slug' => 'iot',
                    'accent' => '#d97706',
                    'icon' => 'fa-microchip',
                    'officers' => $iotOfficers,
                    'desc' => 'Embedded Systems, Smart Devices, Sensor Jaringan, Mikrokontroler & Robotika',
                    'bg_light' => '#fffbeb',
                ],
                [
                    'id' => 'cyber-section',
                    'name' => 'Divisi Cyber Security',
                    'slug' => 'cyber',
                    'accent' => '#059669',
                    'icon' => 'fa-shield-halved',
                    'officers' => $cyberOfficers,
                    'desc' => 'Keamanan Informasi, Ethical Hacking, Forensik Digital, CTF & Cyber Defense',
                    'bg_light' => '#ecfdf5',
                ],
            ];
        @endphp

        <div>
            @foreach ($divisionsData as $divGroup)
                <div class="division-clean-block" id="{{ $divGroup['id'] }}">
                    <!-- Clean Centered Division Header (Tanpa Kotak Besar) -->
                    <div class="division-clean-header">
                        <div style="display: inline-flex; align-items: center; justify-content: center; gap: 0.65rem; margin-bottom: 0.45rem;">
                            <div style="width: 38px; height: 38px; border-radius: 10px; background: {{ $divGroup['bg_light'] }}; color: {{ $divGroup['accent'] }}; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; box-shadow: var(--clay-pill); border: 1px solid {{ $divGroup['accent'] }}30;">
                                <i class="fas {{ $divGroup['icon'] }}"></i>
                            </div>
                            <h3 style="font-size: 1.5rem; font-weight: 850; color: #0c2340; margin: 0;">
                                {{ $divGroup['name'] }}
                            </h3>
                        </div>
                        <p style="font-size: 0.885rem; color: #64748b; margin: 0 auto 0.75rem; max-width: 580px;">
                            {{ $divGroup['desc'] }}
                        </p>
                        <div style="display: inline-flex; align-items: center; gap: 0.5rem;">
                            <span class="badge" style="background: {{ $divGroup['bg_light'] }}; color: {{ $divGroup['accent'] }}; font-weight: 800; font-size: 0.75rem; padding: 0.35rem 0.85rem; border-radius: 9999px; border: 1px solid {{ $divGroup['accent'] }}30;">
                                {{ $divGroup['officers']->count() }} Anggota Tim & Pembimbing
                            </span>
                            <a href="{{ route('divisions.show', $divGroup['slug']) }}" class="btn btn-outline btn-sm" style="font-size: 0.75rem; padding: 0.35rem 0.85rem; border-radius: 9999px; font-weight: 700; background: #ffffff;">
                                Kanal Divisi &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Centered Officers Cards -->
                    <div class="officers-centered-grid">
                        @forelse ($divGroup['officers'] as $officer)
                            @php
                                $posLower = strtolower($officer->position);
                                $isDosen = str_contains($posLower, 'pembina') || str_contains($posLower, 'pembimbing') || str_contains($posLower, 'dosen');
                                $isLeader = str_contains($posLower, 'ketua') || str_contains($posLower, 'koordinator');
                            @endphp
                            <div class="executive-card">
                                <!-- Top Stripe -->
                                <div class="card-banner-stripe {{ $isDosen ? 'dosen' : $divGroup['slug'] }}"></div>

                                <!-- Avatar Stage -->
                                <div class="avatar-stage">
                                    <div class="executive-avatar-box">
                                        @if ($officer->photo)
                                            <img src="{{ asset('storage/' . $officer->photo) }}" alt="{{ $officer->name }}" class="executive-avatar-img">
                                        @else
                                            <div class="executive-avatar-initials" style="{{ $isDosen ? 'background: #fef3c7; color: #b45309;' : 'background: ' . $divGroup['bg_light'] . '; color: ' . $divGroup['accent'] . ';' }}">
                                                {{ strtoupper(substr($officer->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="executive-body">
                                    <div>
                                        <!-- Role Badge -->
                                        <div>
                                            @if ($isDosen)
                                                <span class="executive-badge badge-dosen">
                                                    <i class="fas fa-chalkboard-user"></i> Dosen Pembimbing
                                                </span>
                                            @elseif ($isLeader)
                                                <span class="executive-badge" style="background: {{ $divGroup['bg_light'] }}; color: {{ $divGroup['accent'] }}; border: 1px solid {{ $divGroup['accent'] }}40;">
                                                    <i class="fas fa-user-tie"></i> Koordinator Divisi
                                                </span>
                                            @else
                                                <span class="executive-badge badge-staff">
                                                    <i class="fas fa-laptop-code"></i> Pengurus Divisi
                                                </span>
                                            @endif
                                        </div>

                                        <h4 class="executive-name">{{ $officer->name }}</h4>
                                        <div class="executive-position" style="color: {{ $divGroup['accent'] }};">{{ $officer->position }}</div>
                                        <div class="executive-id-badge">
                                            <i class="fas {{ $isDosen ? 'fa-id-badge' : 'fa-id-card' }}" style="margin-right: 0.25rem;"></i>
                                            {{ $isDosen ? 'NIP' : 'NIM' }}: {{ $officer->nim ?? '-' }}
                                        </div>
                                    </div>

                                    <!-- Social Links Strip -->
                                    <div class="executive-social-strip">
                                        @if (isset($officer->social_links['linkedin']) && $officer->social_links['linkedin'])
                                            <a href="{{ $officer->social_links['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="social-btn linkedin" title="LinkedIn">
                                                <i class="fab fa-linkedin-in"></i>
                                            </a>
                                        @endif
                                        @if (isset($officer->social_links['github']) && $officer->social_links['github'])
                                            <a href="{{ $officer->social_links['github'] }}" target="_blank" rel="noopener noreferrer" class="social-btn github" title="GitHub">
                                                <i class="fab fa-github"></i>
                                            </a>
                                        @endif
                                        @if (isset($officer->social_links['instagram']) && $officer->social_links['instagram'])
                                            <a href="{{ $officer->social_links['instagram'] }}" target="_blank" rel="noopener noreferrer" class="social-btn instagram" title="Instagram">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                        @endif
                                        @if (empty($officer->social_links) || (!isset($officer->social_links['linkedin']) && !isset($officer->social_links['github']) && !isset($officer->social_links['instagram'])))
                                            <span style="font-size: 0.725rem; color: #94a3b8; font-weight: 600;">
                                                <i class="fas fa-check" style="color: {{ $divGroup['accent'] }};"></i> Tim Riset Aktif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div style="color: #94a3b8; font-style: italic; width: 100%; text-align: center; padding: 2rem;">
                                Belum ada data pengurus yang dipublikasikan untuk divisi ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
