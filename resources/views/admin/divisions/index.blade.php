@extends('admin.layouts.app')

@section('title', 'Biodata & Profil Divisi - UKM CMS')
@section('page_title', 'Kelola Biodata Divisi & Pengurus')

@section('content')
<!-- Header Box -->
<div class="admin-header-box">
    <div>
        <h1 class="admin-header-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#2563eb">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span>Biodata & Profil 4 Divisi Spesialisasi</span>
        </h1>
        <p class="admin-header-desc">
            Perbarui data Dosen Pembina, Ketua Divisi Mahasiswa, visi misi riset, serta materi silabus pembelajaran tiap divisi.
        </p>
    </div>
</div>

<!-- Symmetrical 2x2 Grid Divisi -->
<div class="admin-grid-2">
    @foreach ($divisions as $div)
        <div class="admin-clay-card" style="border-top: 5px solid {{ $div->color_accent }}; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                        <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: {{ $div->color_accent }}15; color: {{ $div->color_accent }}; display: flex; align-items: center; justify-content: center; box-shadow: var(--clay-pill);">
                            {!! $div->icon_svg !!}
                        </div>
                        <div>
                            <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                                {{ $div->name }}
                            </h3>
                            <span style="font-size: 0.775rem; color: var(--slate-400); font-family: var(--font-mono); font-weight: 600;">
                                /divisi/{{ $div->slug }}
                            </span>
                        </div>
                    </div>
                    <span class="badge" style="background: #f8fafc; color: var(--slate-700); box-shadow: var(--clay-pill); font-size: 0.75rem;">
                        {{ $div->posts_count }} Artikel
                    </span>
                </div>

                <p style="font-size: 0.875rem; color: var(--slate-600); margin-bottom: 1.35rem; line-height: 1.55;">
                    {{ $div->tagline }}
                </p>

                <!-- Biodata Pengurus Box -->
                <div style="background: #f8fafc; border-radius: var(--radius-md); padding: 1.15rem; margin-bottom: 1.25rem; box-shadow: var(--clay-pill);">
                    <div style="margin-bottom: 0.85rem; border-bottom: 1px solid var(--slate-200); padding-bottom: 0.65rem;">
                        <span style="font-size: 0.7rem; color: var(--slate-400); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Dosen Pembina Divisi:</span>
                        <div style="font-weight: 800; color: var(--slate-900); font-size: 0.95rem; margin-top: 0.15rem;">{{ $div->adviser_name }}</div>
                        <div style="font-size: 0.775rem; color: var(--slate-500);">{{ $div->adviser_title }}</div>
                    </div>
                    <div>
                        <span style="font-size: 0.7rem; color: var(--slate-400); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Ketua Divisi (Mahasiswa):</span>
                        <div style="font-weight: 800; color: var(--slate-900); font-size: 0.95rem; margin-top: 0.15rem;">{{ $div->leader_name }}</div>
                        <div style="font-size: 0.775rem; color: var(--slate-500); font-family: var(--font-mono);">NIM: {{ $div->leader_nim }}</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div style="display: flex; gap: 0.75rem; margin-top: 0.5rem; pt-3 border-top: 1px solid var(--slate-100);">
                <a href="{{ route('admin.divisions.edit', $div->id) }}" class="btn btn-primary btn-sm" style="flex: 1; box-shadow: var(--clay-btn); text-align: center;">
                    Edit Biodata & Profil &rarr;
                </a>
                <a href="{{ route('divisions.show', $div->slug) }}" target="_blank" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">
                    Lihat Publik
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection
