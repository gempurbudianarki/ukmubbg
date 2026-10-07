@extends('layouts.app')

@section('title', 'Bidang Divisi Spesialisasi - UKM Ilmu Komputer')

@section('styles')
<style>
    /* ==========================================================================
       DIVISIONS LUXURY WHITE CLAYMORPHISM SYSTEM
       ========================================================================== */
    .divisions-hero {
        text-align: center;
        padding: 4.5rem 1.5rem 2.5rem;
    }

    .divisions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(440px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }

    @media (max-width: 640px) {
        .divisions-grid {
            grid-template-columns: 1fr;
        }
    }

    .division-card-luxury {
        background: #ffffff;
        border-radius: 24px;
        border: 1.5px solid rgba(226, 232, 240, 0.95);
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.06), 0 4px 6px -4px rgba(15, 23, 42, 0.02);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .division-card-luxury:hover {
        transform: translateY(-6px);
        box-shadow: 0 25px 45px -10px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(2, 132, 199, 0.15);
    }

    .division-card-accent-strip {
        height: 6px;
        width: 100%;
    }

    .division-card-body {
        padding: 2.25rem 2.25rem 1.75rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .division-icon-clay {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        box-shadow: var(--clay-pill);
        border: 2px solid #ffffff;
        flex-shrink: 0;
    }

    .topic-pill-debossed {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        box-shadow: var(--clay-debossed);
        color: #334155;
        font-size: 0.775rem;
        font-weight: 700;
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s ease;
    }

    .topic-pill-debossed:hover {
        background: #ffffff;
        color: #0284c7;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }

    .leadership-duo-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin: 1.5rem 0;
    }

    @media (max-width: 500px) {
        .leadership-duo-grid {
            grid-template-columns: 1fr;
        }
    }

    .leadership-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem 1.15rem;
        box-shadow: var(--clay-debossed);
    }
</style>
@endsection

@section('content')
<div style="background: #f8fafc; padding-bottom: 5rem;">

    <!-- Hero Header -->
    <div class="container">
        <div class="divisions-hero">
            <span class="badge" style="background: #e0f2fe; color: #0284c7; padding: 0.4rem 1.15rem; border-radius: 9999px; font-weight: 800; font-size: 0.78rem; letter-spacing: 0.05em; text-transform: uppercase; box-shadow: var(--clay-pill); margin-bottom: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="fas fa-layer-group"></i> 4 DIVISI KEILMUAN & RISET
            </span>
            <h1 style="font-size: 2.75rem; font-weight: 900; color: #0c2340; letter-spacing: -0.02em; margin: 0 0 0.85rem; line-height: 1.2;">
                Bidang Spesialisasi & Kurikulum Divisi
            </h1>
            <p style="color: #64748b; max-width: 680px; margin: 0 auto; font-size: 1.05rem; line-height: 1.6;">
                Setiap divisi didampingi langsung oleh Dosen Pembina ahli, kurikulum riset berjenjang, kepemimpinan mahasiswa, serta ekosistem proyek teknologi yang siap dipamerkan ke publik.
            </p>
        </div>
    </div>

    <!-- Divisions Grid -->
    <div class="container">
        <div class="divisions-grid">
            @foreach ($divisions as $division)
                @php
                    $accent = $division->color_accent ?? '#0284c7';
                @endphp
                <div class="division-card-luxury">
                    <!-- Top Accent Color Bar -->
                    <div class="division-card-accent-strip" style="background: linear-gradient(90deg, #0c2340 0%, {{ $accent }} 50%, #009688 100%);"></div>

                    <div class="division-card-body">
                        <div>
                            <!-- Header: Icon & Stats -->
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; gap: 1rem;">
                                <div class="division-icon-clay" style="background: {{ $accent }}15; color: {{ $accent }}; border-color: {{ $accent }}30;">
                                    {!! $division->icon_svg !!}
                                </div>
                                <span class="badge" style="background: #ffffff; color: {{ $accent }}; border: 1.5px solid {{ $accent }}30; font-weight: 800; font-size: 0.75rem; padding: 0.4rem 0.95rem; border-radius: 9999px; box-shadow: var(--clay-pill);">
                                    <i class="fas fa-rocket" style="margin-right: 0.25rem;"></i> {{ $division->projects_count }} Karya Inovasi
                                </span>
                            </div>

                            <!-- Title & Tagline -->
                            <h2 style="font-size: 1.65rem; font-weight: 850; color: #0f172a; margin: 0 0 0.4rem; letter-spacing: -0.01em;">
                                {{ $division->name }}
                            </h2>
                            <div style="font-size: 0.875rem; font-weight: 700; color: {{ $accent }}; margin-bottom: 1rem;">
                                {{ $division->tagline }}
                            </div>
                            <p style="color: #64748b; font-size: 0.925rem; line-height: 1.65; margin: 0 0 1.5rem;">
                                {{ $division->description }}
                            </p>

                            <!-- Focus Topics / Kurikulum -->
                            <div style="margin-bottom: 1.5rem;">
                                <div style="font-size: 0.725rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: #94a3b8; margin-bottom: 0.65rem;">
                                    Fokus Silabus & Riset Keahlian:
                                </div>
                                <div style="display: flex; flex-wrap: wrap; gap: 0.45rem;">
                                    @foreach ($division->focus_topics_list as $topic)
                                        <span class="topic-pill-debossed">
                                            <i class="fas fa-circle-dot" style="font-size: 0.5rem; color: {{ $accent }};"></i>
                                            {{ $topic }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div>
                            <!-- Leadership Duo (Dosen Pembina & Ketua Divisi) -->
                            <div class="leadership-duo-grid">
                                <!-- Dosen Pembina -->
                                <div class="leadership-box">
                                    <div style="font-size: 0.68rem; font-weight: 800; color: #b45309; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.35rem;">
                                        <i class="fas fa-graduation-cap"></i> Pembina Divisi
                                    </div>
                                    <div style="font-weight: 800; font-size: 0.885rem; color: #0f172a; line-height: 1.3;">
                                        {{ $division->adviser_name }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.15rem;">
                                        {{ $division->adviser_title }}
                                    </div>
                                </div>

                                <!-- Ketua Divisi Mahasiswa -->
                                <div class="leadership-box">
                                    <div style="font-size: 0.68rem; font-weight: 800; color: #0284c7; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.35rem;">
                                        <i class="fas fa-user-tie"></i> Ketua Divisi
                                    </div>
                                    <div style="font-weight: 800; font-size: 0.885rem; color: #0f172a; line-height: 1.3;">
                                        {{ $division->leader_name }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.15rem; font-family: var(--font-mono, monospace);">
                                        NIM: {{ $division->leader_nim }}
                                    </div>
                                </div>
                            </div>

                            <!-- Dual Call to Action Buttons -->
                            <div style="display: flex; gap: 0.75rem; margin-top: 1.25rem;">
                                <a href="{{ route('divisions.show', $division->slug) }}" class="btn btn-outline" style="flex: 1; border-radius: 9999px; font-weight: 700; font-size: 0.85rem; padding: 0.7rem 1rem; background: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); text-align: center;">
                                    Kanal & Kurikulum &rarr;
                                </a>
                                <a href="{{ route('recruitment.index', ['divisi' => $division->slug]) }}" class="btn btn-primary" style="flex: 1; border-radius: 9999px; font-weight: 800; font-size: 0.85rem; padding: 0.7rem 1rem; background: linear-gradient(135deg, {{ $accent }}, #0f172a); border: none; box-shadow: 0 4px 14px {{ $accent }}40; text-align: center;">
                                    Daftar Divisi Ini
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
