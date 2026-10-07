@extends('layouts.app')

@section('title', 'Publikasi & Riset - UKM Ilmu Komputer')

@section('content')
<div style="padding: 4rem 0 1.5rem;">
    <div class="container text-center" style="text-align: center;">
        <span class="badge badge-primary" style="margin-bottom: 0.85rem; box-shadow: var(--clay-pill);">Kanal Riset & Informasi</span>
        <h1 style="font-size: 2.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em; margin-bottom: 0.85rem;">
            Publikasi, Berita & Tutorial
        </h1>
        <p style="color: var(--slate-600); max-width: 650px; margin: 0 auto; font-size: 1.1rem; line-height: 1.6;">
            Kumpulan artikel ilmiah populer, dokumentasi kegiatan, proyek riset, dan panduan teknis yang dipublikasikan oleh 4 divisi UKM Ilmu Komputer.
        </p>

        <!-- Search and Filter Bar Capsule -->
        <div style="max-width: 820px; margin: 2.5rem auto 0; background: #ffffff; padding: 1.25rem 1.5rem; border-radius: var(--radius-xl); box-shadow: var(--clay-card);">
            <form action="{{ route('posts.index') }}" method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul artikel atau topik riset..." class="form-control" style="flex: 2; min-width: 220px;">
                
                <select name="divisi" class="form-select" style="flex: 1; min-width: 160px;">
                    <option value="">Semua Divisi</option>
                    @foreach ($divisions as $div)
                        <option value="{{ $div->slug }}" {{ request('divisi') === $div->slug ? 'selected' : '' }}>
                            {{ $div->name }}
                        </option>
                    @endforeach
                </select>

                <select name="kategori" class="form-select" style="flex: 1; min-width: 140px;">
                    <option value="">Semua Kategori</option>
                    <option value="kegiatan" {{ request('kategori') === 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                    <option value="tutorial" {{ request('kategori') === 'tutorial' ? 'selected' : '' }}>Tutorial</option>
                    <option value="berita" {{ request('kategori') === 'berita' ? 'selected' : '' }}>Berita</option>
                    <option value="proyek" {{ request('kategori') === 'proyek' ? 'selected' : '' }}>Proyek</option>
                </select>

                <button type="submit" class="btn btn-primary" style="border-radius: var(--radius-full); padding: 0.65rem 1.5rem;">
                    Cari
                </button>
            </form>
        </div>
    </div>
</div>

<div class="container" style="padding: 4rem 1.5rem;">
    @if ($posts->count() > 0)
        <div class="post-grid">
            @foreach ($posts as $post)
                <article class="post-card">
                    <div class="post-thumb">
                        @if ($post->thumbnail)
                            <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}">
                        @else
                            <div style="color: var(--slate-400); display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                <span style="font-size: 0.8rem; font-weight: 500;">UKM Ilmu Komputer</span>
                            </div>
                        @endif
                    </div>

                    <div class="post-body">
                        <div class="post-meta">
                            <span class="badge" style="background-color: {{ $post->division->color_accent }}15; color: {{ $post->division->color_accent }};">
                                {{ $post->division->name }}
                            </span>
                            <span style="font-size: 0.775rem; color: var(--slate-400);">&bull;</span>
                            <span style="font-size: 0.8rem; color: var(--slate-500); text-transform: capitalize;">{{ $post->category }}</span>
                        </div>

                        <h3 class="post-title">
                            <a href="{{ route('posts.show', $post->slug) }}">
                                {{ $post->title }}
                            </a>
                        </h3>

                        <p class="post-excerpt">
                            {{ Str::limit($post->excerpt, 120) }}
                        </p>

                        <div class="post-footer">
                            <span>{{ $post->created_at->format('d M Y') }}</span>
                            <span>{{ $post->views_count }} views</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div style="margin-top: 3rem;">
            {{ $posts->links() }}
        </div>
    @else
        <div class="card" style="text-align: center; padding: 4rem 2rem;">
            <p style="font-size: 1.1rem; color: var(--slate-600); margin-bottom: 1rem;">Tidak ada artikel yang cocok dengan pencarian Anda.</p>
            <a href="{{ route('posts.index') }}" class="btn btn-outline btn-sm">Reset Pencarian</a>
        </div>
    @endif
</div>
@endsection
