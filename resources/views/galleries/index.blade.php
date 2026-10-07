@extends('layouts.app')

@section('title', 'Galeri & Dokumentasi Kegiatan - UKM Ilmu Komputer')

@section('styles')
<style>
    /* ==========================================================================
       GALLERY LUXURY WHITE CLAYMORPHISM SYSTEM
       ========================================================================== */
    .gallery-hero {
        text-align: center;
        padding: 4.5rem 1.5rem 2.5rem;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.75rem;
        margin-bottom: 3.5rem;
    }

    .gallery-card-luxury {
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

    .gallery-card-luxury:hover {
        transform: translateY(-6px);
        box-shadow: 0 25px 45px -10px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(2, 132, 199, 0.2);
    }

    .gallery-photo-wrap {
        height: 240px;
        position: relative;
        overflow: hidden;
        background: #0f172a;
    }

    .gallery-photo-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .gallery-card-luxury:hover .gallery-photo-img {
        transform: scale(1.05);
    }
</style>
@endsection

@section('content')
<div style="background: #f8fafc; padding-bottom: 5rem;">

    <!-- Hero Header -->
    <div class="container">
        <div class="gallery-hero">
            <span class="badge" style="background: #e0f2fe; color: #0284c7; padding: 0.4rem 1.15rem; border-radius: 9999px; font-weight: 800; font-size: 0.78rem; letter-spacing: 0.05em; text-transform: uppercase; box-shadow: var(--clay-pill); margin-bottom: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="fas fa-camera-retro"></i> Galeri & Dokumentasi
            </span>
            <h1 style="font-size: 2.75rem; font-weight: 900; color: #0c2340; letter-spacing: -0.02em; margin: 0 0 0.85rem; line-height: 1.2;">
                Galeri Momen & Prestasi Organisasi
            </h1>
            <p style="color: #64748b; max-width: 680px; margin: 0 auto; font-size: 1.05rem; line-height: 1.6;">
                Arsip foto kebersamaan, musyawarah kerja, aksi hackathon, praktikum lab, dan penganugerahan penghargaan yang mewarnai perjalanan UKM Ilmu Komputer.
            </p>
        </div>
    </div>

    <div class="container">
        <!-- Filter Tabs Toolbar -->
        <div style="background: #ffffff; border-radius: 20px; border: 1.5px solid rgba(226, 232, 240, 0.95); box-shadow: var(--clay-card); padding: 1rem 1.5rem; margin-bottom: 2.5rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('galleries.index') }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline' }} btn-sm" style="border-radius: 9999px; font-weight: 700; font-size: 0.8rem; padding: 0.45rem 1rem;">
                Semua Momen
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('galleries.index', ['category' => $cat]) }}" class="btn {{ request('category') === $cat ? 'btn-primary' : 'btn-outline' }} btn-sm" style="border-radius: 9999px; font-weight: 700; font-size: 0.8rem; padding: 0.45rem 1rem;">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        <!-- Gallery Grid -->
        <div class="gallery-grid">
            @forelse ($galleries as $gallery)
                <div class="gallery-card-luxury">
                    <div class="gallery-photo-wrap">
                        <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="gallery-photo-img">
                        <span class="badge" style="position: absolute; top: 12px; right: 12px; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25); font-weight: 800; font-size: 0.725rem; border-radius: 9999px;">
                            {{ $gallery->category }}
                        </span>
                    </div>

                    <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h3 style="font-size: 1.15rem; font-weight: 850; color: #0f172a; margin: 0 0 0.5rem; line-height: 1.35;">
                                {{ $gallery->title }}
                            </h3>
                            @if ($gallery->caption)
                                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.55; margin: 0 0 1rem;">
                                    {{ $gallery->caption }}
                                </p>
                            @endif
                        </div>

                        @if ($gallery->event_date)
                            <div style="border-top: 1px solid #f1f5f9; padding-top: 0.75rem; font-size: 0.775rem; color: #94a3b8; font-family: var(--font-mono, monospace); display: flex; align-items: center; gap: 0.35rem;">
                                <i class="fas fa-calendar-day" style="color: #0284c7;"></i>
                                {{ $gallery->event_date->format('d F Y') }}
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 4.5rem 2rem; background: #ffffff; border-radius: 24px; border: 1.5px solid #e2e8f0; box-shadow: var(--clay-card); color: #64748b;">
                    <div style="width: 64px; height: 64px; border-radius: 20px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.85rem; margin: 0 auto 1.25rem; box-shadow: var(--clay-pill);">
                        <i class="fas fa-images"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 0.4rem;">
                        Belum Ada Foto Dokumentasi
                    </h3>
                    <p style="color: #64748b; font-size: 0.9rem; max-width: 480px; margin: 0 auto;">
                        Dokumentasi visual untuk kategori ini belum dipublikasikan.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Numbered Pagination (White Claymorphism 1, 2, 3...) -->
        @if ($galleries->total() > 0)
            <div style="margin-top: 3.5rem;">
                {{ $galleries->links('pagination.clay') }}
            </div>
        @endif
    </div>
</div>
@endsection
