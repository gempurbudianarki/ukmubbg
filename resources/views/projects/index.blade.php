@extends('layouts.app')

@section('title', 'Karya & Portofolio Inovasi Mahasiswa - UKM Ilmu Komputer')

@section('styles')
<style>
    /* ==========================================================================
       PROJECTS SHOWCASE LUXURY WHITE CLAYMORPHISM SYSTEM
       ========================================================================== */
    .projects-hero {
        text-align: center;
        padding: 4.5rem 1.5rem 2.5rem;
    }

    .project-card-luxury {
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

    .project-card-luxury:hover {
        transform: translateY(-6px);
        box-shadow: 0 25px 45px -10px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(2, 132, 199, 0.2);
    }

    .project-thumb-wrap {
        height: 200px;
        position: relative;
        overflow: hidden;
        background: #0f172a;
    }

    .project-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .project-card-luxury:hover .project-thumb-img {
        transform: scale(1.05);
    }

    .project-thumb-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.1) 0%, rgba(15, 23, 42, 0.75) 100%);
        pointer-events: none;
    }

    .project-tech-chip {
        font-size: 0.725rem;
        font-weight: 700;
        background: #f8fafc;
        color: #334155;
        border: 1px solid #e2e8f0;
        box-shadow: var(--clay-debossed);
        padding: 0.25rem 0.65rem;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
    }
</style>
@endsection

@section('content')
<div style="background: #f8fafc; padding-bottom: 5rem;">

    <!-- Hero Header -->
    <div class="container">
        <div class="projects-hero">
            <span class="badge" style="background: #e0f2fe; color: #0284c7; padding: 0.4rem 1.15rem; border-radius: 9999px; font-weight: 800; font-size: 0.78rem; letter-spacing: 0.05em; text-transform: uppercase; box-shadow: var(--clay-pill); margin-bottom: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="fas fa-rocket"></i> Showcase Portofolio & Riset
            </span>
            <h1 style="font-size: 2.75rem; font-weight: 900; color: #0c2340; letter-spacing: -0.02em; margin: 0 0 0.85rem; line-height: 1.2;">
                Karya & Inovasi Mahasiswa
            </h1>
            <p style="color: #64748b; max-width: 720px; margin: 0 auto; font-size: 1.05rem; line-height: 1.6;">
                Koleksi karya inovasi, aplikasi mobile/web, prototipe IoT, desain kreatif, dan alat uji keamanan siber yang diciptakan oleh mahasiswa UKM Ilmu Komputer.
            </p>
        </div>
    </div>

    <div class="container">
        <!-- Filter & Search Toolbar (White Claymorphism Card) -->
        <div style="background: #ffffff; border-radius: 20px; border: 1.5px solid rgba(226, 232, 240, 0.95); box-shadow: var(--clay-card); padding: 1.25rem 1.75rem; margin-bottom: 2.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <!-- Division Pill Filters -->
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                <a href="{{ route('projects.index') }}" class="btn {{ !request('division') ? 'btn-primary' : 'btn-outline' }} btn-sm" style="border-radius: 9999px; font-weight: 700; font-size: 0.8rem; padding: 0.45rem 1rem;">
                    Semua Bidang
                </a>
                @foreach ($divisions as $div)
                    <a href="{{ route('projects.index', ['division' => $div->slug, 'search' => request('search')]) }}" class="btn {{ request('division') === $div->slug ? 'btn-primary' : 'btn-outline' }} btn-sm" style="border-radius: 9999px; font-weight: 700; font-size: 0.8rem; padding: 0.45rem 1rem;">
                        {{ $div->name }}
                    </a>
                @endforeach
            </div>

            <!-- Search Form -->
            <form action="{{ route('projects.index') }}" method="GET" style="display: flex; gap: 0.5rem; width: 100%; max-width: 320px;">
                @if (request('division'))
                    <input type="hidden" name="division" value="{{ request('division') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari karya / tech stack..." class="form-control" style="padding: 0.55rem 1rem; font-size: 0.85rem; border-radius: 9999px; background: #ffffff; box-shadow: var(--clay-debossed);">
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius: 9999px; font-weight: 700; padding: 0.55rem 1.15rem;">Cari</button>
            </form>
        </div>

        <!-- Projects Grid -->
        <div class="projects-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.75rem;">
            @forelse ($projects as $project)
                @php
                    $divAccent = $project->division?->color_accent ?? '#0284c7';
                @endphp
                <div class="project-card-luxury">
                    <!-- Thumbnail Container -->
                    <div class="project-thumb-wrap">
                        <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}" class="project-thumb-img">
                        <div class="project-thumb-overlay"></div>

                        <!-- Division Badge on Thumbnail -->
                        <div style="position: absolute; top: 12px; left: 12px; z-index: 2;">
                            @if ($project->division)
                                <span class="badge" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); color: #ffffff; border: 1px solid rgba(255,255,255,0.25); font-weight: 800; font-size: 0.725rem; border-radius: 9999px;">
                                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: {{ $divAccent }}; margin-right: 0.35rem;"></span>
                                    {{ $project->division->name }}
                                </span>
                            @else
                                <span class="badge" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); color: #ffffff; font-weight: 700; font-size: 0.725rem; border-radius: 9999px;">
                                    Kolaborasi Lintas Divisi
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h3 style="font-size: 1.2rem; font-weight: 850; color: #0f172a; margin: 0 0 0.5rem; line-height: 1.35;">
                                {{ $project->title }}
                            </h3>
                            <p style="font-size: 0.85rem; color: #64748b; line-height: 1.55; margin: 0 0 1rem;">
                                {{ Str::limit($project->description, 110) }}
                            </p>

                            <!-- Tech Stack Pills -->
                            @if (!empty($project->tech_stack))
                                <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-bottom: 1.25rem;">
                                    @foreach ($project->tech_stack as $tech)
                                        <span class="project-tech-chip">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Footer: Inovator & Action Links -->
                        <div style="border-top: 1px solid #f1f5f9; padding-top: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                            <span style="color: #64748b; font-size: 0.8rem; display: flex; align-items: center; gap: 0.35rem;">
                                <i class="fas fa-user-astronaut" style="color: #0284c7;"></i>
                                <strong style="color: #0f172a;">{{ $project->author_names }}</strong>
                            </span>

                            <div style="display: flex; gap: 0.4rem;">
                                @if ($project->repo_url)
                                    <a href="{{ $project->repo_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm" style="font-size: 0.75rem; padding: 0.35rem 0.75rem; border-radius: 9999px; font-weight: 700; background: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill);">
                                        <i class="fab fa-github"></i> Repo
                                    </a>
                                @endif
                                @if ($project->demo_url)
                                    <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm" style="font-size: 0.75rem; padding: 0.35rem 0.85rem; border-radius: 9999px; font-weight: 800; background: linear-gradient(135deg, #0284c7, #0369a1); border: none; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);">
                                        <i class="fas fa-external-link-alt"></i> Demo
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 4.5rem 2rem; background: #ffffff; border-radius: 24px; border: 1.5px solid #e2e8f0; box-shadow: var(--clay-card);">
                    <div style="width: 64px; height: 64px; border-radius: 20px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.85rem; margin: 0 auto 1.25rem; box-shadow: var(--clay-pill);">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 0.4rem;">
                        Tidak Ada Karya yang Ditemukan
                    </h3>
                    <p style="color: #64748b; font-size: 0.9rem; max-width: 480px; margin: 0 auto 1.5rem;">
                        Coba gunakan kata kunci lain atau pilih opsi semua bidang divisi untuk melihat seluruh karya inovasi.
                    </p>
                    <a href="{{ route('projects.index') }}" class="btn btn-outline btn-sm" style="border-radius: 9999px; font-weight: 700; padding: 0.55rem 1.25rem;">
                        Reset Filter
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Numbered Pagination (White Claymorphism 1, 2, 3...) -->
        @if ($projects->total() > 0)
            <div style="margin-top: 3.5rem;">
                {{ $projects->links('pagination.clay') }}
            </div>
        @endif
    </div>
</div>
@endsection
