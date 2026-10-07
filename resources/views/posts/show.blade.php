@extends('layouts.app')

@section('title', $post->title . ' - UKM Ilmu Komputer')
@section('meta_description', Str::limit(strip_tags($post->excerpt), 150))

@section('content')
<div style="padding: 3.5rem 0 5rem;">
    <div class="container-narrow">
        <article class="card" style="padding: 3.5rem 3rem; border: none; border-radius: var(--radius-xl); box-shadow: var(--clay-card); background: #ffffff;">
            <!-- Breadcrumb / Header meta -->
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <a href="{{ route('divisions.show', $post->division->slug) }}" class="badge" style="background-color: {{ $post->division->color_accent }}18; color: {{ $post->division->color_accent }}; font-weight: 700; box-shadow: var(--clay-pill);">
                    {{ $post->division->name }}
                </a>
                <span style="font-size: 0.85rem; color: var(--slate-400);">&bull;</span>
                <span style="font-size: 0.85rem; color: var(--slate-500); text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em;">
                    {{ $post->category }}
                </span>
                <span style="font-size: 0.85rem; color: var(--slate-400);">&bull;</span>
                <span style="font-size: 0.85rem; color: var(--slate-500);">
                    Diterbitkan pada {{ $post->created_at->format('d F Y') }}
                </span>
            </div>

            <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--slate-900); line-height: 1.3; letter-spacing: -0.02em; margin-bottom: 1.75rem;">
                {{ $post->title }}
            </h1>

            <!-- Author bio strip -->
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 0; border-top: 1px solid rgba(226, 232, 240, 0.8); border-bottom: 1px solid rgba(226, 232, 240, 0.8); margin-bottom: 2.5rem;">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div class="avatar-sm" style="box-shadow: var(--clay-pill);">
                        {{ strtoupper(substr($post->author->name, 0, 2)) }}
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--slate-900); font-size: 0.95rem;">
                            {{ $post->author->name }}
                        </div>
                        <div style="font-size: 0.8rem; color: var(--slate-500);">
                            Kontributor {{ $post->division->name }}
                        </div>
                    </div>
                </div>
                <div style="font-size: 0.85rem; color: var(--slate-500);">
                    <strong>{{ $post->views_count }}</strong> Kali Dibaca
                </div>
            </div>

            <!-- Featured Image -->
            @if ($post->thumbnail)
                <div style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 2.5rem; box-shadow: var(--clay-card);">
                    <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}" style="width: 100%; max-height: 480px; object-fit: cover;">
                </div>
            @endif

            <!-- Post Content -->
            <div style="font-size: 1.1rem; line-height: 1.85; color: var(--slate-800);" class="post-content-body">
                {!! $post->content !!}
            </div>

            <!-- Share / Division Card -->
            <div style="margin-top: 3.5rem; padding: 2rem; background: var(--bg-body); box-shadow: var(--clay-debossed); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <div style="font-weight: 800; color: var(--slate-900); font-size: 1.1rem;">
                        Tertarik dengan topik di bidang {{ $post->division->name }}?
                    </div>
                    <div style="font-size: 0.9rem; color: var(--slate-600); margin-top: 0.25rem;">
                        Bergabunglah bersama kami di Open Recruitment UKM Ilmu Komputer.
                    </div>
                </div>
                <a href="{{ route('recruitment.index', ['divisi' => $post->division->slug]) }}" class="btn btn-primary" style="border-radius: var(--radius-full);">
                    Daftar Divisi Ini
                </a>
            </div>
        </article>
    </div>
</div>

<!-- Related Posts -->
@if ($relatedPosts->count() > 0)
<section style="padding: 4rem 0;">
    <div class="container">
        <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900); margin-bottom: 2rem;">
            Publikasi Terkait di {{ $post->division->name }}
        </h3>
        <div class="post-grid">
            @foreach ($relatedPosts as $rel)
                <article class="post-card">
                    <div class="post-body">
                        <div class="post-meta">
                            <span class="badge" style="background-color: {{ $rel->division->color_accent }}15; color: {{ $rel->division->color_accent }};">
                                {{ $rel->division->name }}
                            </span>
                            <span style="font-size: 0.8rem; color: var(--slate-500);">{{ $rel->created_at->format('d M Y') }}</span>
                        </div>
                        <h4 class="post-title">
                            <a href="{{ route('posts.show', $rel->slug) }}">{{ $rel->title }}</a>
                        </h4>
                        <p class="post-excerpt">{{ Str::limit($rel->excerpt, 100) }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
