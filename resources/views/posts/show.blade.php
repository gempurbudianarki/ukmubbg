@extends('layouts.app')

@section('title', $post->title . ' - UKM Ilmu Komputer')
@section('meta_description', Str::limit(strip_tags($post->excerpt), 150))

@section('content')
<article style="background: #ffffff; padding: 4rem 0; border-bottom: 1px solid var(--slate-200);">
    <div class="container-narrow">
        <!-- Breadcrumb / Header meta -->
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <a href="{{ route('divisions.show', $post->division->slug) }}" class="badge" style="background-color: {{ $post->division->color_accent }}15; color: {{ $post->division->color_accent }}; font-weight: 700;">
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

        <h1 style="font-size: 2.75rem; font-weight: 800; color: var(--slate-900); line-height: 1.25; letter-spacing: -0.02em; margin-bottom: 1.5rem;">
            {{ $post->title }}
        </h1>

        <!-- Author bio strip -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 0; border-top: 1px solid var(--slate-100); border-bottom: 1px solid var(--slate-100); margin-bottom: 2.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div class="avatar-sm">
                    {{ strtoupper(substr($post->author->name, 0, 2)) }}
                </div>
                <div>
                    <div style="font-weight: 700; color: var(--slate-900); font-size: 0.925rem;">
                        {{ $post->author->name }}
                    </div>
                    <div style="font-size: 0.775rem; color: var(--slate-500);">
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
            <div style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 2.5rem; box-shadow: var(--shadow-md);">
                <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}" style="width: 100%; max-height: 480px; object-fit: cover;">
            </div>
        @endif

        <!-- Post Content -->
        <div style="font-size: 1.1rem; line-height: 1.85; color: var(--slate-800);" class="post-content-body">
            {!! $post->content !!}
        </div>

        <!-- Share / Division Card -->
        <div style="margin-top: 3.5rem; padding: 2rem; background-color: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="font-weight: 700; color: var(--slate-900); font-size: 1.05rem;">
                    Tertarik dengan topik di bidang {{ $post->division->name }}?
                </div>
                <div style="font-size: 0.9rem; color: var(--slate-600);">
                    Bergabunglah bersama kami di Open Recruitment UKM Ilmu Komputer.
                </div>
            </div>
            <a href="{{ route('recruitment.index', ['divisi' => $post->division->slug]) }}" class="btn btn-primary">
                Daftar Divisi Ini
            </a>
        </div>
    </div>
</article>

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
