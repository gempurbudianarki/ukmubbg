@extends('layouts.app')

@section('title', 'Bidang Divisi Spesialisasi - UKM Ilmu Komputer')

@section('content')
<div style="padding: 4rem 0 2rem;">
    <div class="container text-center" style="text-align: center;">
        <span class="badge badge-primary" style="margin-bottom: 0.75rem;">Struktur Organisasi</span>
        <h1 style="font-size: 2.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em; margin-bottom: 0.75rem;">
            4 Bidang Divisi UKM Ilmu Komputer
        </h1>
        <p style="color: var(--slate-600); max-width: 650px; margin: 0 auto; font-size: 1.1rem;">
            Setiap divisi memiliki fokus kurikulum, pembina ahli, ketua bidang, serta portofolio inovasi masing-masing.
        </p>
    </div>
</div>

<div class="container" style="padding: 2rem 1.5rem 5rem;">
    <div class="division-grid">
        @foreach ($divisions as $division)
            <div class="division-card" style="--div-accent: {{ $division->color_accent }}; --div-bg: {{ $division->color_accent }}15;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem;">
                        <div class="division-icon-box" style="margin-bottom: 0;">
                            {!! $division->icon_svg !!}
                        </div>
                        <span class="badge" style="background-color: #ffffff; color: {{ $division->color_accent }}; border-color: {{ $division->color_accent }}30;">
                            {{ $division->projects_count }} Karya Inovasi
                        </span>
                    </div>

                    <h2 class="division-title" style="font-size: 1.5rem; font-weight: 800;">{{ $division->name }}</h2>
                    <p class="division-tagline">{{ $division->tagline }}</p>
                    <p style="color: var(--slate-600); font-size: 0.925rem; line-height: 1.6; margin-bottom: 1.5rem;">
                        {{ $division->description }}
                    </p>

                    <h4 style="font-size: 0.825rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-500); margin-bottom: 0.5rem;">
                        Fokus Riset & Keahlian:
                    </h4>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.45rem; margin-bottom: 1.5rem;">
                        @foreach ($division->focus_topics_list as $topic)
                            <span class="clay-badge" style="background: #ffffff; color: var(--slate-700); font-size: 0.75rem;">
                                &bull; {{ $topic }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div>
                    <div style="background-color: #f6f9fc; border: 2px solid #ffffff; box-shadow: var(--clay-input); border-radius: var(--radius-md); padding: 1.15rem; margin-bottom: 1.25rem;">
                        <div style="font-size: 0.775rem; color: var(--slate-500); font-weight: 700; margin-bottom: 0.25rem; text-transform: uppercase;">Dosen Pembina Divisi:</div>
                        <div style="font-weight: 800; color: var(--slate-900); font-size: 0.95rem;">{{ $division->adviser_name }}</div>
                        <div style="font-size: 0.8rem; color: var(--slate-600);">{{ $division->adviser_title }}</div>
                    </div>

                    <div class="division-leader-info" style="margin-top: 0; padding-top: 0; border-top: none;">
                        <div class="avatar-round" style="background: {{ $division->color_accent }}15; color: {{ $division->color_accent }}; border-color: {{ $division->color_accent }}30;">
                            {{ strtoupper(substr($division->leader_name, 0, 2)) }}
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="font-size: 0.875rem; font-weight: 800; color: var(--slate-900);">
                                {{ $division->leader_name }}
                            </div>
                            <div style="font-size: 0.775rem; color: var(--slate-500); font-weight: 500;">
                                Ketua Divisi (NIM: {{ $division->leader_nim }})
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                        <a href="{{ route('divisions.show', $division->slug) }}" class="btn btn-outline" style="flex: 1;">
                            Kanal & Biodata &rarr;
                        </a>
                        <a href="{{ route('recruitment.index', ['divisi' => $division->slug]) }}" class="btn btn-primary">
                            Daftar Divisi Ini
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
