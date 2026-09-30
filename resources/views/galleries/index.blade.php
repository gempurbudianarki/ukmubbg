@extends('layouts.app')

@section('title', 'Galeri & Dokumentasi Kegiatan - UKM Ilmu Komputer')

@section('content')
<div style="padding: 4rem 0 5rem;">
    <div class="container">
        <!-- Header -->
        <div class="section-header">
            <div class="section-tag">Galeri & Dokumentasi</div>
            <h1 class="section-title">Dokumentasi Momen & Prestasi</h1>
            <p class="section-desc">
                Rekaman visual kebersamaan, musyawarah kerja, aksi hackathon, kompetisi CTF, dan workshop yang mengukir jejak perjalanan UKM.
            </p>
        </div>

        <!-- Filter Chips -->
        <div class="filter-tabs">
            <a href="{{ route('galleries.index') }}" class="filter-tab {{ !request('category') ? 'active' : '' }}">
                Semua Momen
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('galleries.index', ['category' => $cat]) }}" class="filter-tab {{ request('category') === $cat ? 'active' : '' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        <!-- Gallery Grid -->
        <div class="projects-grid">
            @forelse ($galleries as $gallery)
                <div class="project-card" style="border-radius: var(--radius-lg); overflow: hidden;">
                    <div style="height: 220px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); display: flex; align-items: center; justify-content: center; position: relative;">
                        @if ($gallery->image_path && file_exists(public_path('storage/' . $gallery->image_path)))
                            <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="color: var(--slate-400); display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
                                    {{ $gallery->category }}
                                </span>
                            </div>
                        @endif
                        <span class="badge badge-neutral" style="position: absolute; top: 1rem; right: 1rem; background: rgba(15, 23, 42, 0.75); color: #ffffff; border: none; font-size: 0.725rem;">
                            {{ $gallery->category }}
                        </span>
                    </div>

                    <div style="padding: 1.5rem;">
                        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.5rem; line-height: 1.4;">
                            {{ $gallery->title }}
                        </h3>
                        @if ($gallery->caption)
                            <p style="font-size: 0.875rem; color: var(--slate-600); line-height: 1.6; margin-bottom: 1rem;">
                                {{ $gallery->caption }}
                            </p>
                        @endif
                        @if ($gallery->event_date)
                            <div style="font-size: 0.775rem; color: var(--slate-400); font-family: var(--font-mono);">
                                Tanggal: {{ $gallery->event_date->format('d F Y') }}
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: #ffffff; border-radius: var(--radius-lg); border: 1px solid var(--slate-200); color: var(--slate-500);">
                    Belum ada foto dokumentasi yang diunggah dalam kategori ini.
                </div>
            @endforelse
        </div>

        @if ($galleries->hasPages())
            <div style="margin-top: 3rem; display: flex; justify-content: center;">
                {{ $galleries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
