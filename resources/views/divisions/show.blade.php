@extends('layouts.app')

@section('title', $division->name . ' - UKM Ilmu Komputer')

@section('styles')
<style>
    /* ==========================================================================
       DIVISION CHANNEL LUXURY WHITE CLAYMORPHISM SYSTEM
       ========================================================================== */
    .division-hero-clean {
        background: radial-gradient(circle at 90% 15%, {{ $division->color_accent }}14 0%, #ffffff 70%);
        border-bottom: 1.5px solid rgba(226, 232, 240, 0.9);
        padding: 4.25rem 0 3.5rem;
        position: relative;
    }

    .division-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: {{ $division->color_accent }}15;
        color: {{ $division->color_accent }};
        border: 1px solid {{ $division->color_accent }}30;
        font-weight: 800;
        font-size: 0.775rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 0.35rem 0.95rem;
        border-radius: 9999px;
        box-shadow: var(--clay-pill);
    }

    .division-hero-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        background: #ffffff;
        border: 1.5px solid {{ $division->color_accent }}30;
        box-shadow: var(--clay-card);
        display: flex;
        align-items: center;
        justify-content: center;
        color: {{ $division->color_accent }};
        font-size: 1.6rem;
    }

    .channel-clay-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid rgba(226, 232, 240, 0.95);
        box-shadow: var(--clay-card);
        padding: 2.25rem;
        margin-bottom: 2rem;
        position: relative;
    }

    .channel-quote-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid {{ $division->color_accent }};
        border-radius: 12px;
        padding: 1.15rem 1.35rem;
        box-shadow: var(--clay-debossed);
        color: #334155;
        font-size: 0.95rem;
        line-height: 1.7;
    }

    .channel-project-item {
        background: #ffffff;
        border-radius: 18px;
        border: 1.5px solid #e2e8f0;
        box-shadow: var(--clay-card);
        display: flex;
        flex-direction: row;
        gap: 1.5rem;
        padding: 1.35rem;
        align-items: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .channel-project-item:hover {
        transform: translateY(-3px);
        box-shadow: var(--clay-card-hover);
        border-color: #cbd5e1;
    }

    @media (max-width: 768px) {
        .channel-project-item {
            flex-direction: column;
            align-items: stretch;
        }
    }

    .channel-project-thumb {
        width: 170px;
        height: 115px;
        flex-shrink: 0;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #ffffff;
        box-shadow: var(--clay-pill);
        background: #f1f5f9;
    }

    @media (max-width: 768px) {
        .channel-project-thumb {
            width: 100%;
            height: 170px;
        }
    }

    .channel-project-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .channel-layout-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2.5rem;
    }

    @media (max-width: 992px) {
        .channel-layout-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div style="background: #f8fafc; padding-bottom: 5rem;">

    <!-- ==========================================
         CLEAN WHITE CLAYMORPHISM DIVISION HERO
         ========================================== -->
    <div class="division-hero-clean">
        <div class="container">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
                <div class="division-hero-icon">
                    {!! $division->icon_svg !!}
                </div>
                <div>
                    <span class="division-hero-badge">
                        <i class="fas fa-layer-group"></i> Kanal Resmi Divisi
                    </span>
                </div>
            </div>

            <h1 style="font-size: 2.85rem; font-weight: 900; color: #0c2340; letter-spacing: -0.02em; margin: 0 0 0.75rem; line-height: 1.2;">
                {{ $division->name }}
            </h1>
            <p style="font-size: 1.15rem; color: #475569; max-width: 760px; line-height: 1.6; margin: 0 0 1.85rem; font-weight: 500;">
                {{ $division->tagline }}
            </p>

            <div style="display: flex; gap: 0.85rem; flex-wrap: wrap;">
                <a href="{{ route('recruitment.index', ['divisi' => $division->slug]) }}" class="btn btn-primary" style="padding: 0.75rem 1.75rem; border-radius: 9999px; font-weight: 800; background: linear-gradient(135deg, {{ $division->color_accent }}, #0c2340); border: none; box-shadow: 0 4px 14px {{ $division->color_accent }}40;">
                    Daftar ke Divisi {{ $division->name }} &rarr;
                </a>
                <a href="#bio-pengurus" class="btn btn-outline" style="padding: 0.75rem 1.5rem; border-radius: 9999px; font-weight: 700; background: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); color: #0f172a;">
                    Lihat Biodata Pengurus
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="container" style="padding-top: 3.5rem;">
        <div class="channel-layout-grid">
            
            <!-- Left Column: Deskripsi, Visi Misi, Fokus, dan Karya -->
            <div>
                <!-- Overview Card -->
                <div class="channel-clay-card" style="border-top: 5px solid {{ $division->color_accent }};">
                    <h2 style="font-size: 1.45rem; font-weight: 850; color: #0c2340; margin: 0 0 1rem;">
                        Tentang {{ $division->name }}
                    </h2>
                    <p style="color: #475569; line-height: 1.75; font-size: 0.975rem; margin-bottom: 1.75rem;">
                        {{ $division->description }}
                    </p>

                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #0c2340; margin: 0 0 0.65rem;">
                        Visi Divisi
                    </h3>
                    <div class="channel-quote-box" style="margin-bottom: 1.75rem;">
                        "{{ $division->vision }}"
                    </div>

                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #0c2340; margin: 0 0 0.65rem;">
                        Misi & Program Kerja
                    </h3>
                    <div style="color: #475569; line-height: 1.7; white-space: pre-line; margin-bottom: 1.75rem; font-size: 0.95rem;">
                        {{ $division->mission }}
                    </div>

                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #0c2340; margin: 0 0 0.75rem;">
                        Fokus Pembelajaran & Riset
                    </h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.45rem;">
                        @foreach ($division->focus_topics_list as $topic)
                            <span style="background: #f8fafc; border: 1px solid #e2e8f0; box-shadow: var(--clay-debossed); color: #334155; font-size: 0.785rem; font-weight: 700; padding: 0.35rem 0.85rem; border-radius: 9999px;">
                                &bull; {{ $topic }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Division Projects Section -->
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
                        <h2 style="font-size: 1.45rem; font-weight: 850; color: #0c2340; margin: 0;">
                            Karya & Portofolio Inovasi {{ $division->name }}
                        </h2>
                        <span class="badge" style="background: {{ $division->color_accent }}15; color: {{ $division->color_accent }}; font-weight: 800; font-size: 0.75rem; border: 1px solid {{ $division->color_accent }}30; padding: 0.35rem 0.85rem; border-radius: 9999px;">
                            {{ $projects->total() }} Proyek
                        </span>
                    </div>

                    @if ($projects->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                            @foreach ($projects as $project)
                                <article class="channel-project-item">
                                    <div class="channel-project-thumb">
                                        <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}">
                                    </div>
                                    <div style="display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1;">
                                        <div>
                                            <h3 style="font-size: 1.12rem; font-weight: 800; line-height: 1.4; margin: 0 0 0.4rem; color: #0c2340;">
                                                {{ $project->title }}
                                            </h3>
                                            <p style="font-size: 0.85rem; color: #64748b; line-height: 1.55; margin: 0 0 0.75rem;">
                                                {{ Str::limit($project->description, 130) }}
                                            </p>
                                            <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-bottom: 0.75rem;">
                                                @foreach ($project->tech_stack ?? [] as $tech)
                                                    <span style="font-size: 0.7rem; font-weight: 700; background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; padding: 0.2rem 0.55rem; border-radius: 6px;">
                                                        {{ $tech }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; color: #64748b; flex-wrap: wrap; gap: 0.5rem; border-top: 1px dashed #f1f5f9; padding-top: 0.65rem;">
                                            <span>Tim: <strong style="color: #0c2340;">{{ $project->author_names }}</strong></span>
                                            <div style="display: flex; gap: 0.4rem;">
                                                @if ($project->repo_url)
                                                    <a href="{{ $project->repo_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm" style="font-size: 0.75rem; padding: 0.3rem 0.7rem; border-radius: 9999px; background: #ffffff;">GitHub</a>
                                                @endif
                                                @if ($project->demo_url)
                                                    <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm" style="font-size: 0.75rem; padding: 0.3rem 0.85rem; border-radius: 9999px; background: linear-gradient(135deg, {{ $division->color_accent }}, #0c2340); border: none;">Demo</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div style="margin-top: 2rem;">
                            {{ $projects->links() }}
                        </div>
                    @else
                        <div class="channel-clay-card" style="text-align: center; padding: 3rem 1.5rem; color: #64748b;">
                            <p style="margin-bottom: 1rem;">Belum ada proyek yang dipublikasikan untuk divisi ini.</p>
                            <a href="{{ route('projects.index') }}" class="btn btn-outline btn-sm" style="border-radius: 9999px;">Lihat Seluruh Proyek</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Biodata Pembina & Biodata Ketua Divisi -->
            <div id="bio-pengurus">
                
                <!-- Biodata Pembina -->
                <div class="channel-clay-card" style="border-top: 5px solid #b45309;">
                    <div style="font-size: 0.725rem; font-weight: 800; color: #b45309; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                        <i class="fas fa-graduation-cap"></i> BIODATA PEMBINA
                    </div>
                    <h3 style="font-size: 1.15rem; font-weight: 850; color: #0c2340; margin: 0 0 1.15rem;">
                        Dosen Pembina Bidang
                    </h3>

                    <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.25rem;">
                        @if ($division->adviser_photo)
                            <img src="{{ asset($division->adviser_photo) }}" alt="{{ $division->adviser_name }}" style="width: 74px; height: 74px; border-radius: 16px; object-fit: cover; border: 3px solid #ffffff; box-shadow: var(--clay-pill);">
                        @else
                            <div style="width: 74px; height: 74px; border-radius: 16px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: 900; border: 3px solid #ffffff; box-shadow: var(--clay-pill);">
                                {{ strtoupper(substr($division->adviser_name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <div style="font-weight: 850; color: #0c2340; font-size: 1.05rem; line-height: 1.3;">
                                {{ $division->adviser_name }}
                            </div>
                            <div style="font-size: 0.825rem; color: #64748b; line-height: 1.4; margin-top: 0.25rem;">
                                {{ $division->adviser_title }}
                            </div>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; box-shadow: var(--clay-debossed); border-radius: 12px; padding: 0.95rem 1rem; font-size: 0.825rem; color: #475569; line-height: 1.6;">
                        Membimbing riset kurikulum, standarisasi etika keilmuan, serta supervisi keikutsertaan kompetisi tingkat regional dan nasional.
                    </div>
                </div>

                <!-- Biodata Ketua Divisi -->
                <div class="channel-clay-card" style="border-top: 5px solid {{ $division->color_accent }};">
                    <div style="font-size: 0.725rem; font-weight: 800; color: {{ $division->color_accent }}; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                        <i class="fas fa-user-tie"></i> KETUA BIDANG
                    </div>
                    <h3 style="font-size: 1.15rem; font-weight: 850; color: #0c2340; margin: 0 0 1.15rem;">
                        Ketua Divisi (Mahasiswa)
                    </h3>

                    <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.25rem;">
                        @if ($division->leader_photo)
                            <img src="{{ asset($division->leader_photo) }}" alt="{{ $division->leader_name }}" style="width: 74px; height: 74px; border-radius: 16px; object-fit: cover; border: 3px solid #ffffff; box-shadow: var(--clay-pill);">
                        @else
                            <div style="width: 74px; height: 74px; border-radius: 16px; background: {{ $division->color_accent }}15; color: {{ $division->color_accent }}; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: 900; border: 3px solid #ffffff; box-shadow: var(--clay-pill);">
                                {{ strtoupper(substr($division->leader_name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <div style="font-weight: 850; color: #0c2340; font-size: 1.05rem; line-height: 1.3;">
                                {{ $division->leader_name }}
                            </div>
                            <div style="font-size: 0.825rem; color: #64748b; font-family: var(--font-mono); margin-top: 0.25rem; font-weight: 700;">
                                NIM: {{ $division->leader_nim }}
                            </div>
                        </div>
                    </div>

                    <div style="font-size: 0.885rem; color: #475569; line-height: 1.65; margin-bottom: 1.25rem; font-style: italic; background: #f8fafc; padding: 0.95rem 1rem; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: var(--clay-debossed);">
                        "{{ $division->leader_bio }}"
                    </div>

                    @php $socials = $division->social_links_list; @endphp
                    @if (!empty($socials))
                        <div style="display: flex; gap: 0.45rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                            @if (!empty($socials['instagram']))
                                <a href="{{ $socials['instagram'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm" style="font-size: 0.75rem; border-radius: 9999px; background: #ffffff;">
                                    Instagram
                                </a>
                            @endif
                            @if (!empty($socials['github']))
                                <a href="{{ $socials['github'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm" style="font-size: 0.75rem; border-radius: 9999px; background: #ffffff;">
                                    GitHub
                                </a>
                            @endif
                            @if (!empty($socials['linkedin']))
                                <a href="{{ $socials['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm" style="font-size: 0.75rem; border-radius: 9999px; background: #ffffff;">
                                    LinkedIn
                                </a>
                            @endif
                        </div>
                    @endif

                    <a href="{{ route('recruitment.index', ['divisi' => $division->slug]) }}" class="btn btn-primary" style="width: 100%; border-radius: 9999px; font-weight: 800; background: linear-gradient(135deg, {{ $division->color_accent }}, #0c2340); border: none; box-shadow: 0 4px 14px {{ $division->color_accent }}40; text-align: center;">
                        Daftar ke Divisi Ini &rarr;
                    </a>
                </div>

                <!-- Other Divisions Quick Nav -->
                <div class="channel-clay-card">
                    <h4 style="font-size: 0.95rem; font-weight: 850; color: #0c2340; margin: 0 0 0.85rem;">
                        Jelajahi Divisi Lainnya
                    </h4>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem; padding: 0; margin: 0;">
                        @foreach ($otherDivisions as $other)
                            <li>
                                <a href="{{ route('divisions.show', $other->slug) }}" class="btn btn-outline btn-sm" style="display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 1rem; color: #334155; font-size: 0.85rem; width: 100%; box-sizing: border-box; border-radius: 12px; background: #ffffff; text-decoration: none;">
                                    <span>{{ $other->name }}</span>
                                    <span>&rarr;</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
