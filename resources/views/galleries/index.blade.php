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
                <div class="project-card" style="border-radius: var(--radius-lg); overflow: hidden; display: flex; flex-direction: column; border: none;">
                    <div style="height: 240px; background: #0f172a; position: relative; overflow: hidden;">
                        <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;">
                        <span class="badge badge-neutral" style="position: absolute; top: 1rem; right: 1rem; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); color: var(--slate-800); box-shadow: var(--clay-pill); font-weight: 700; font-size: 0.75rem;">
                            {{ $gallery->category }}
                        </span>
                    </div>

                    <div style="padding: 1.5rem;">
                        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.5rem; line-height: 1.4;">
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
                <div style="grid-column: 1 / -1; text-align: center; padding: 3.5rem 2rem; background: #ffffff; border-radius: var(--radius-xl); box-shadow: var(--clay-card); color: var(--slate-500);">
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
