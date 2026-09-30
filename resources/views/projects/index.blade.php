@extends('layouts.app')

@section('title', 'Karya & Portofolio Mahasiswa - UKM Ilmu Komputer')

@section('content')
<div style="padding: 4rem 0 5rem;">
    <div class="container">
        <!-- Breadcrumb / Header -->
        <div class="section-header" style="margin-bottom: 2.5rem;">
            <div class="section-tag">Showcase Portofolio & Riset</div>
            <h1 class="section-title">Karya & Inovasi Mahasiswa</h1>
            <p class="section-desc">
                Koleksi produk digital, aplikasi web/mobile, sistem hardware terintegrasi, dan alat uji keamanan yang dibangun oleh mahasiswa UKM Ilmu Komputer.
            </p>
        </div>

        <!-- Filter & Search Toolbar -->
        <div style="background: var(--glass-bg); backdrop-filter: var(--glass-blur); border: 1px solid var(--glass-border); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; margin-bottom: 3rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; box-shadow: var(--shadow-subtle);">
            <!-- Division Pill Filters -->
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                <a href="{{ route('projects.index') }}" class="btn {{ !request('division') ? 'btn-primary' : 'btn-outline' }} btn-sm" style="border-radius: var(--radius-full);">
                    Semua Bidang
                </a>
                @foreach ($divisions as $div)
                    <a href="{{ route('projects.index', ['division' => $div->slug, 'search' => request('search')]) }}" class="btn {{ request('division') === $div->slug ? 'btn-primary' : 'btn-outline' }} btn-sm" style="border-radius: var(--radius-full);">
                        {{ $div->name }}
                    </a>
                @endforeach
            </div>

            <!-- Search Form -->
            <form action="{{ route('projects.index') }}" method="GET" style="display: flex; gap: 0.5rem; width: 100%; max-width: 320px;">
                @if (request('division'))
                    <input type="hidden" name="division" value="{{ request('division') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari karya / tech stack..." class="form-control" style="padding: 0.45rem 0.85rem; font-size: 0.85rem;">
                <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            </form>
        </div>

        <!-- Projects Grid -->
        <div class="projects-grid">
            @forelse ($projects as $project)
                <div class="project-card">
                    <div class="project-thumb">
                        @if ($project->thumbnail)
                            <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}">
                        @else
                            <div style="color: var(--slate-400); display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                                <span style="font-size: 0.775rem; font-weight: 600; text-transform: uppercase;">
                                    {{ $project->division ? $project->division->name : 'Multi-Divisi' }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="project-body">
                        <div style="margin-bottom: 0.5rem;">
                            @if ($project->division)
                                <span class="badge" style="background: {{ $project->division->color_accent }}15; color: {{ $project->division->color_accent }}; font-size: 0.725rem;">
                                    {{ $project->division->name }}
                                </span>
                            @else
                                <span class="badge badge-neutral" style="font-size: 0.725rem;">Kolaborasi Lintas Divisi</span>
                            @endif
                        </div>

                        <h3 class="project-title">{{ $project->title }}</h3>
                        <p class="project-desc">{{ $project->description }}</p>

                        <div class="tech-tags">
                            @foreach ($project->tech_stack ?? [] as $tech)
                                <span class="tech-tag">{{ $tech }}</span>
                            @endforeach
                        </div>

                        <div class="project-footer">
                            <span style="color: var(--slate-500); font-size: 0.8rem;">
                                Inovator: <strong>{{ $project->author_names }}</strong>
                            </span>
                            <div style="display: flex; gap: 0.4rem;">
                                @if ($project->repo_url)
                                    <a href="{{ $project->repo_url }}" target="_blank" class="btn btn-outline btn-sm">GitHub</a>
                                @endif
                                @if ($project->demo_url)
                                    <a href="{{ $project->demo_url }}" target="_blank" class="btn btn-primary btn-sm">Demo</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: #ffffff; border-radius: var(--radius-lg); border: 1px solid var(--slate-200);">
                    <div style="font-size: 1.1rem; font-weight: 700; color: var(--slate-700); margin-bottom: 0.5rem;">
                        Tidak ada karya yang sesuai kriteria pencarian.
                    </div>
                    <p style="color: var(--slate-500); font-size: 0.9rem; margin-bottom: 1.5rem;">
                        Coba gunakan kata kunci lain atau pilih seluruh bidang divisi.
                    </p>
                    <a href="{{ route('projects.index') }}" class="btn btn-outline btn-sm">Reset Filter</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($projects->hasPages())
            <div style="margin-top: 3rem; display: flex; justify-content: center;">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
