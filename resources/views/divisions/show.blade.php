@extends('layouts.app')

@section('title', $division->name . ' - UKM Ilmu Komputer')

@section('content')
<!-- Division Banner Header -->
<div style="background: linear-gradient(135deg, var(--slate-900) 0%, {{ $division->color_accent }}40 100%), #0f172a; color: #ffffff; padding: 4.5rem 0;">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
            <div style="width: 56px; height: 56px; border-radius: var(--radius-md); background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; color: #ffffff;">
                {!! $division->icon_svg !!}
            </div>
            <div>
                <span class="badge" style="background-color: {{ $division->color_accent }}; color: #ffffff;">
                    Kanal Resmi Divisi
                </span>
            </div>
        </div>

        <h1 style="font-size: 2.75rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.75rem;">
            {{ $division->name }}
        </h1>
        <p style="font-size: 1.2rem; color: var(--slate-200); max-width: 750px; line-height: 1.6; margin-bottom: 1.5rem;">
            {{ $division->tagline }}
        </p>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('recruitment.index', ['divisi' => $division->slug]) }}" class="btn btn-primary btn-lg">
                Daftar ke Divisi {{ $division->name }} &rarr;
            </a>
            <a href="#bio-pengurus" class="btn btn-outline btn-lg" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3); color: #ffffff;">
                Lihat Biodata Pengurus
            </a>
        </div>
    </div>
</div>

<div class="container" style="padding: 4rem 1.5rem;">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
        <!-- Left Column: Deskripsi, Visi Misi, Fokus, dan Feed Artikel Divisi -->
        <div>
            <!-- Overview Section -->
            <div class="card" style="margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--slate-900); margin-bottom: 1rem;">
                    Tentang {{ $division->name }}
                </h2>
                <p style="color: var(--slate-700); line-height: 1.8; font-size: 1rem; margin-bottom: 1.5rem;">
                    {{ $division->description }}
                </p>

                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.75rem;">
                    Visi Divisi
                </h3>
                <p style="color: var(--slate-600); line-height: 1.7; margin-bottom: 1.5rem; background: var(--slate-50); padding: 1rem; border-left: 3px solid {{ $division->color_accent }}; border-radius: var(--radius-sm);">
                    "{{ $division->vision }}"
                </p>

                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.75rem;">
                    Misi & Program Kerja
                </h3>
                <div style="color: var(--slate-700); line-height: 1.7; white-space: pre-line; margin-bottom: 1.5rem;">
                    {{ $division->mission }}
                </div>

                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.75rem;">
                    Fokus Pembelajaran & Riset
                </h3>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    @foreach ($division->focus_topics_list as $topic)
                        <div style="background: var(--slate-100); border: 1px solid var(--slate-200); padding: 0.4rem 0.85rem; border-radius: var(--radius-md); font-size: 0.85rem; font-weight: 600; color: var(--slate-800);">
                            {{ $topic }}
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Division Posts / Publications Section -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900);">
                        Kanal Artikel & Dokumentasi {{ $division->name }}
                    </h2>
                    <span class="badge" style="background-color: {{ $division->color_accent }}15; color: {{ $division->color_accent }}; font-weight: 700;">
                        {{ $posts->total() }} Postingan
                    </span>
                </div>

                @if ($posts->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        @foreach ($posts as $post)
                            <article class="card card-hover" style="display: flex; flex-direction: row; gap: 1.5rem; padding: 1.25rem;">
                                <div style="width: 180px; height: 120px; flex-shrink: 0; background-color: var(--slate-100); border-radius: var(--radius-md); overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                    @if ($post->thumbnail)
                                        <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: var(--slate-400);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    @endif
                                </div>
                                <div style="display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1;">
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.4rem;">
                                            <span class="badge badge-info" style="font-size: 0.725rem; text-transform: capitalize;">{{ $post->category }}</span>
                                            <span style="font-size: 0.775rem; color: var(--slate-400);">&bull;</span>
                                            <span style="font-size: 0.775rem; color: var(--slate-500);">{{ $post->created_at->format('d M Y') }}</span>
                                        </div>
                                        <h3 style="font-size: 1.1rem; font-weight: 700; line-height: 1.4; margin-bottom: 0.4rem;">
                                            <a href="{{ route('posts.show', $post->slug) }}" style="color: var(--slate-900);">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        <p style="font-size: 0.875rem; color: var(--slate-600); line-height: 1.5;">
                                            {{ Str::limit($post->excerpt, 120) }}
                                        </p>
                                    </div>
                                    <div style="font-size: 0.8rem; color: var(--slate-400); margin-top: 0.5rem;">
                                        Penulis: {{ $post->author->name }} &bull; {{ $post->views_count }} pembaca
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div style="margin-top: 2rem;">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="card" style="text-align: center; padding: 3rem;">
                        <p style="color: var(--slate-500); margin-bottom: 1rem;">Belum ada artikel yang diterbitkan untuk kanal divisi ini.</p>
                        @auth
                            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">Tulis Artikel Pertama</a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Biodata Pembina & Biodata Ketua Divisi -->
        <div id="bio-pengurus">
            <!-- Biodata Pembina -->
            <div class="card" style="margin-bottom: 2rem; border-top: 4px solid var(--slate-800);">
                <div class="section-tag" style="color: var(--slate-500);">Biodata Pembina</div>
                <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--slate-900); margin-bottom: 1rem;">
                    Dosen Pembina Bidang
                </h3>

                <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.25rem;">
                    @if ($division->adviser_photo)
                        <img src="{{ asset($division->adviser_photo) }}" alt="{{ $division->adviser_name }}" style="width: 72px; height: 72px; border-radius: var(--radius-md); object-fit: cover;">
                    @else
                        <div style="width: 72px; height: 72px; border-radius: var(--radius-md); background: linear-gradient(135deg, var(--slate-700), var(--slate-900)); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800;">
                            {{ strtoupper(substr($division->adviser_name, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <div style="font-weight: 800; color: var(--slate-900); font-size: 1.05rem;">
                            {{ $division->adviser_name }}
                        </div>
                        <div style="font-size: 0.85rem; color: var(--slate-600); line-height: 1.4; margin-top: 0.2rem;">
                            {{ $division->adviser_title }}
                        </div>
                    </div>
                </div>

                <div style="background-color: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-sm); padding: 0.85rem; font-size: 0.825rem; color: var(--slate-600); line-height: 1.5;">
                    Membimbing riset kurikulum, standarisasi etika keilmuan, serta supervisi keikutsertaan kompetisi tingkat regional dan nasional.
                </div>
            </div>

            <!-- Biodata Ketua Divisi -->
            <div class="card" style="margin-bottom: 2rem; border-top: 4px solid {{ $division->color_accent }};">
                <div class="section-tag" style="color: {{ $division->color_accent }};">Biodata Ketua Bidang</div>
                <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--slate-900); margin-bottom: 1rem;">
                    Ketua Divisi (Mahasiswa)
                </h3>

                <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.25rem;">
                    @if ($division->leader_photo)
                        <img src="{{ asset($division->leader_photo) }}" alt="{{ $division->leader_name }}" style="width: 72px; height: 72px; border-radius: var(--radius-md); object-fit: cover;">
                    @else
                        <div style="width: 72px; height: 72px; border-radius: var(--radius-md); background: linear-gradient(135deg, {{ $division->color_accent }}, var(--slate-900)); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800;">
                            {{ strtoupper(substr($division->leader_name, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <div style="font-weight: 800; color: var(--slate-900); font-size: 1.05rem;">
                            {{ $division->leader_name }}
                        </div>
                        <div style="font-size: 0.85rem; color: var(--slate-500); font-family: var(--font-mono); margin-top: 0.2rem;">
                            NIM: {{ $division->leader_nim }}
                        </div>
                    </div>
                </div>

                <div style="font-size: 0.9rem; color: var(--slate-700); line-height: 1.7; margin-bottom: 1.25rem; font-style: italic;">
                    "{{ $division->leader_bio }}"
                </div>

                @php $socials = $division->social_links_list; @endphp
                @if (!empty($socials))
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                        @if (!empty($socials['instagram']))
                            <a href="{{ $socials['instagram'] }}" target="_blank" class="btn btn-outline btn-sm">
                                Instagram
                            </a>
                        @endif
                        @if (!empty($socials['github']))
                            <a href="{{ $socials['github'] }}" target="_blank" class="btn btn-outline btn-sm">
                                GitHub
                            </a>
                        @endif
                        @if (!empty($socials['linkedin']))
                            <a href="{{ $socials['linkedin'] }}" target="_blank" class="btn btn-outline btn-sm">
                                LinkedIn
                            </a>
                        @endif
                        @if (!empty($socials['behance']))
                            <a href="{{ $socials['behance'] }}" target="_blank" class="btn btn-outline btn-sm">
                                Behance
                            </a>
                        @endif
                    </div>
                @endif

                <a href="{{ route('recruitment.index', ['divisi' => $division->slug]) }}" class="btn btn-primary" style="width: 100%;">
                    Daftar ke Divisi Ini &rarr;
                </a>
            </div>

            <!-- Other Divisions Quick Nav -->
            <div class="card">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.75rem;">
                    Jelajahi Divisi Lainnya
                </h4>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach ($otherDivisions as $other)
                        <li>
                            <a href="{{ route('divisions.show', $other->slug) }}" style="display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0.75rem; border-radius: var(--radius-sm); background: var(--slate-50); color: var(--slate-700); font-weight: 600; font-size: 0.85rem;">
                                <span>{{ $other->name }}</span>
                                <span>&rarr;</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
