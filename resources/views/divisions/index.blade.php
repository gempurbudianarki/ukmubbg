@extends('layouts.app')

@section('title', 'Bidang Divisi Spesialisasi - UKM Ilmu Komputer')

@section('content')
<div style="background-color: #ffffff; border-bottom: 1px solid var(--slate-200); padding: 3.5rem 0;">
    <div class="container text-center" style="text-align: center;">
        <span class="badge badge-primary" style="margin-bottom: 0.75rem;">Struktur Organisasi</span>
        <h1 style="font-size: 2.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em; margin-bottom: 0.75rem;">
            4 Bidang Divisi UKM Ilmu Komputer
        </h1>
        <p style="color: var(--slate-600); max-width: 650px; margin: 0 auto; font-size: 1.1rem;">
            Setiap divisi memiliki fokus kurikulum, pembina ahli, ketua bidang, serta kanal publikasi karya masing-masing.
        </p>
    </div>
</div>

<div class="container" style="padding: 4rem 1.5rem;">
    <div class="division-grid">
        @foreach ($divisions as $division)
            <div class="division-card" style="--div-accent: {{ $division->color_accent }}; --div-bg: {{ $division->color_accent }}15;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div class="division-icon-box" style="margin-bottom: 0;">
                            {!! $division->icon_svg !!}
                        </div>
                        <span class="badge" style="background-color: {{ $division->color_accent }}15; color: {{ $division->color_accent }};">
                            {{ $division->posts_count }} Publikasi
                        </span>
                    </div>

                    <h2 class="division-title" style="font-size: 1.5rem;">{{ $division->name }}</h2>
                    <p class="division-tagline">{{ $division->tagline }}</p>
                    <p style="color: var(--slate-600); font-size: 0.925rem; line-height: 1.6; margin-bottom: 1.5rem;">
                        {{ $division->description }}
                    </p>

                    <h4 style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-500); margin-bottom: 0.5rem;">
                        Fokus Riset & Keahlian:
                    </h4>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 1.5rem;">
                        @foreach ($division->focus_topics_list as $topic)
                            <span style="font-size: 0.775rem; background: var(--slate-100); padding: 0.25rem 0.6rem; border-radius: var(--radius-sm); color: var(--slate-700); font-weight: 600;">
                                &bull; {{ $topic }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div>
                    <div style="background-color: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1.25rem;">
                        <div style="font-size: 0.8rem; color: var(--slate-500); margin-bottom: 0.25rem;">Dosen Pembina Divisi:</div>
                        <div style="font-weight: 700; color: var(--slate-900); font-size: 0.9rem;">{{ $division->adviser_name }}</div>
                        <div style="font-size: 0.775rem; color: var(--slate-600);">{{ $division->adviser_title }}</div>
                    </div>

                    <div class="division-leader-info" style="margin-top: 0; padding-top: 0; border-top: none;">
                        <div class="avatar-sm">
                            {{ strtoupper(substr($division->leader_name, 0, 2)) }}
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="font-size: 0.85rem; font-weight: 700; color: var(--slate-900);">
                                {{ $division->leader_name }}
                            </div>
                            <div style="font-size: 0.775rem; color: var(--slate-500);">
                                Ketua Divisi (NIM: {{ $division->leader_nim }})
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.75rem; margin-top: 1.25rem;">
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
