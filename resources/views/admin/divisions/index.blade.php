@extends('admin.layouts.app')

@section('title', 'Biodata & Profil Divisi - UKM CMS')
@section('page_title', 'Kelola Biodata 4 Divisi Spesialisasi')

@section('content')
<!-- Header Box -->
<div class="admin-welcome-banner" style="margin-bottom: 2rem; padding: 1.5rem 2rem;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: 14px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--clay-pill); flex-shrink: 0;">
            <i class="fas fa-layer-group"></i>
        </div>
        <div>
            <h1 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.2rem 0;">
                Biodata & Profil 4 Divisi Spesialisasi
            </h1>
            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                Perbarui data Dosen Pembina, Ketua Divisi Mahasiswa, visi misi riset, serta materi silabus pembelajaran tiap divisi.
            </p>
        </div>
    </div>
</div>

<!-- Symmetrical 2x2 Grid Divisi -->
<div class="admin-grid-2">
    @foreach ($divisions as $div)
        <div class="admin-clay-card" style="border-top: 5px solid {{ $div->color_accent }}; display: flex; flex-direction: column; justify-content: space-between; padding: 1.75rem;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                        <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: {{ $div->color_accent }}15; color: {{ $div->color_accent }}; display: flex; align-items: center; justify-content: center; box-shadow: var(--clay-pill); font-size: 1.4rem;">
                            {!! $div->icon_svg !!}
                        </div>
                        <div>
                            <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                                {{ $div->name }}
                            </h2>
                            <span style="font-size: 0.775rem; color: var(--slate-400); font-family: var(--font-mono); font-weight: 600;">
                                /divisi/{{ $div->slug }}
                            </span>
                        </div>
                    </div>
                    <span class="badge" style="background: #f8fafc; color: var(--slate-700); box-shadow: var(--clay-pill); font-size: 0.75rem; font-weight: 700;">
                        <i class="fas fa-newspaper" style="margin-right: 0.25rem; color: var(--slate-400);"></i> {{ $div->posts_count }} Artikel
                    </span>
                </div>

                <p style="font-size: 0.875rem; color: var(--slate-600); margin-bottom: 1.25rem; line-height: 1.55; min-height: 40px;">
                    {{ $div->tagline }}
                </p>

                <!-- Biodata Pengurus Box -->
                <div style="background: #f8fafc; border-radius: var(--radius-md); padding: 1.15rem; margin-bottom: 1.25rem; box-shadow: var(--clay-pill); border: 1px solid rgba(226, 232, 240, 0.7);">
                    <div style="margin-bottom: 0.85rem; border-bottom: 1px solid var(--slate-200); padding-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 38px; height: 38px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0; box-shadow: var(--clay-pill);">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div style="min-width: 0; flex: 1;">
                            <span style="font-size: 0.68rem; color: var(--slate-400); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em; display: block;">Dosen Pembina:</span>
                            <div style="font-weight: 800; color: var(--slate-900); font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $div->adviser_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--slate-500); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $div->adviser_title }}</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 38px; height: 38px; border-radius: 50%; background: #ede9fe; color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0; box-shadow: var(--clay-pill);">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div style="min-width: 0; flex: 1;">
                            <span style="font-size: 0.68rem; color: var(--slate-400); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em; display: block;">Ketua Divisi Mahasiswa:</span>
                            <div style="font-weight: 800; color: var(--slate-900); font-size: 0.9rem;">{{ $div->leader_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--slate-500); font-family: var(--font-mono);">NIM: {{ $div->leader_nim }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div style="display: flex; gap: 0.75rem; margin-top: 0.5rem; padding-top: 0.75rem; border-top: 1px solid var(--slate-100);">
                <a href="{{ route('admin.divisions.edit', $div->id) }}" class="btn btn-primary btn-sm" style="flex: 1; box-shadow: var(--clay-btn); text-align: center; display: inline-flex; align-items: center; justify-content: center; gap: 0.45rem;">
                    <i class="fas fa-pen-to-square"></i>
                    <span>Edit Profil & Biodata</span>
                </a>
                <a href="{{ route('divisions.show', $div->slug) }}" target="_blank" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); display: inline-flex; align-items: center; gap: 0.4rem;">
                    <i class="fas fa-arrow-up-right-from-square"></i>
                    <span>Web Publik</span>
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection
