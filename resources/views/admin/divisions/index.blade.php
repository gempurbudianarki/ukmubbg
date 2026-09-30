@extends('admin.layouts.app')

@section('title', 'Biodata & Profil Divisi - UKM CMS')
@section('page_title', 'Kelola Biodata Divisi & Pengurus')

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--slate-900);">
        Biodata & Profil 4 Divisi
    </h2>
    <p style="color: var(--slate-500); font-size: 0.875rem;">
        Perbarui data Dosen Pembina, Ketua Divisi, visi misi, serta fokus pembelajaran divisi.
    </p>
</div>

<div class="division-grid" style="grid-template-columns: repeat(2, 1fr);">
    @foreach ($divisions as $div)
        <div class="card" style="border-top: 4px solid {{ $div->color_accent }}; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 42px; height: 42px; border-radius: var(--radius-md); background: {{ $div->color_accent }}15; color: {{ $div->color_accent }}; display: flex; align-items: center; justify-content: center;">
                            {!! $div->icon_svg !!}
                        </div>
                        <div>
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--slate-900);">
                                {{ $div->name }}
                            </h3>
                            <span style="font-size: 0.75rem; color: var(--slate-400); font-family: var(--font-mono);">
                                /divisi/{{ $div->slug }}
                            </span>
                        </div>
                    </div>
                    <span class="badge" style="background: var(--slate-100); color: var(--slate-700);">
                        {{ $div->posts_count }} Artikel
                    </span>
                </div>

                <p style="font-size: 0.875rem; color: var(--slate-600); margin-bottom: 1.25rem; line-height: 1.5;">
                    {{ $div->tagline }}
                </p>

                <!-- Biodata Pengurus Summary -->
                <div style="background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1.25rem;">
                    <div style="margin-bottom: 0.75rem;">
                        <span style="font-size: 0.75rem; color: var(--slate-400); text-transform: uppercase; font-weight: 700;">Dosen Pembina:</span>
                        <div style="font-weight: 700; color: var(--slate-900); font-size: 0.9rem;">{{ $div->adviser_name }}</div>
                        <div style="font-size: 0.775rem; color: var(--slate-500);">{{ $div->adviser_title }}</div>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: var(--slate-400); text-transform: uppercase; font-weight: 700;">Ketua Divisi (Mahasiswa):</span>
                        <div style="font-weight: 700; color: var(--slate-900); font-size: 0.9rem;">{{ $div->leader_name }} (NIM: {{ $div->leader_nim }})</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
                <a href="{{ route('admin.divisions.edit', $div->id) }}" class="btn btn-primary btn-sm" style="flex: 1;">
                    Edit Biodata & Profil &rarr;
                </a>
                <a href="{{ route('divisions.show', $div->slug) }}" target="_blank" class="btn btn-outline btn-sm">
                    Lihat Publik
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection
