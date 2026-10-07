@extends('layouts.app')

@section('title', $division->name . ' - UKM Ilmu Komputer')

@section('content')
<!-- Division Banner Header -->
<!-- Division Banner Header -->
<div style="background: linear-gradient(135deg, #0f172a 0%, {{ $division->color_accent }} 100%); color: #ffffff; padding: 4.5rem 0; box-shadow: 0 10px 30px rgba(160, 175, 202, 0.2);">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
            <div style="width: 58px; height: 58px; border-radius: var(--radius-md); background: rgba(255,255,255,0.2); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; color: #ffffff; border: 2px solid rgba(255,255,255,0.4); box-shadow: var(--clay-pill);">
                {!! $division->icon_svg !!}
            </div>
            <div>
                <span class="badge" style="background-color: #ffffff; color: {{ $division->color_accent }}; border: 2px solid #ffffff;">
                    Kanal Resmi Divisi
                </span>
            </div>
        </div>

        <h1 style="font-size: 2.75rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.75rem;">
            {{ $division->name }}
        </h1>
        <p style="font-size: 1.2rem; color: var(--slate-100); max-width: 750px; line-height: 1.6; margin-bottom: 1.75rem; font-weight: 500;">
            {{ $division->tagline }}
        </p>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('recruitment.index', ['divisi' => $division->slug]) }}" class="btn btn-primary btn-lg">
                Daftar ke Divisi {{ $division->name }} &rarr;
            </a>
            <a href="#bio-pengurus" class="btn btn-glass btn-lg" style="color: #0f172a;">
                Lihat Biodata Pengurus
            </a>
        </div>
    </div>
</div>

<div class="container" style="padding: 4rem 1.5rem 5rem;">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
        <!-- Left Column: Deskripsi, Visi Misi, Fokus, dan Feed Artikel Divisi -->
        <div>
            <!-- Overview Section -->
            <div class="card" style="margin-bottom: 2.5rem; border-top: 6px solid {{ $division->color_accent }};">
                <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1rem;">
                    Tentang {{ $division->name }}
                </h2>
                <p style="color: var(--slate-700); line-height: 1.8; font-size: 1rem; margin-bottom: 1.5rem;">
                    {{ $division->description }}
                </p>

                <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.75rem;">
                    Visi Divisi
                </h3>
                <p style="color: var(--slate-700); line-height: 1.7; margin-bottom: 1.5rem; background: #f6f9fc; padding: 1.15rem; border-left: 4px solid {{ $division->color_accent }}; border-radius: var(--radius-md); box-shadow: var(--clay-input);">
                    "{{ $division->vision }}"
                </p>

                <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.75rem;">
                    Misi & Program Kerja
                </h3>
                <div style="color: var(--slate-700); line-height: 1.7; white-space: pre-line; margin-bottom: 1.5rem;">
                    {{ $division->mission }}
                </div>

                <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.75rem;">
                    Fokus Pembelajaran & Riset
                </h3>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    @foreach ($division->focus_topics_list as $topic)
                        <div class="clay-badge" style="background: #ffffff; color: var(--slate-800); font-size: 0.825rem; font-weight: 700;">
                            &bull; {{ $topic }}
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Division Projects Section -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900);">
                        Karya & Portofolio Inovasi {{ $division->name }}
                    </h2>
                    <span class="badge" style="background-color: #ffffff; color: {{ $division->color_accent }}; font-weight: 700; border-color: {{ $division->color_accent }}30;">
                        {{ $projects->total() }} Proyek
                    </span>
                </div>

                @if ($projects->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        @foreach ($projects as $project)
                            <article class="card" style="display: flex; flex-direction: row; gap: 1.5rem; padding: 1.5rem; align-items: center;">
                                <div style="width: 180px; height: 120px; flex-shrink: 0; background-color: #0f172a; border-radius: var(--radius-md); overflow: hidden; display: flex; align-items: center; justify-content: center; border: 2px solid #ffffff; box-shadow: var(--clay-pill);">
                                    <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div style="display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1;">
                                    <div>
                                        <h3 style="font-size: 1.15rem; font-weight: 800; line-height: 1.4; margin-bottom: 0.4rem; color: var(--slate-900);">
                                            {{ $project->title }}
                                        </h3>
                                        <p style="font-size: 0.875rem; color: var(--slate-600); line-height: 1.5; margin-bottom: 0.75rem;">
                                            {{ Str::limit($project->description, 130) }}
                                        </p>
                                        <div class="tech-tags" style="margin-bottom: 0.5rem;">
                                            @foreach ($project->tech_stack ?? [] as $tech)
                                                <span class="tech-tag" style="font-size: 0.7rem; padding: 0.2rem 0.6rem;">{{ $tech }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; color: var(--slate-500); flex-wrap: wrap; gap: 0.5rem;">
                                        <span>Tim: {{ $project->author_names }}</span>
                                        <div style="display: flex; gap: 0.5rem;">
                                            @if ($project->repo_url)
                                                <a href="{{ $project->repo_url }}" target="_blank" class="btn btn-outline btn-sm">GitHub</a>
                                            @endif
                                            @if ($project->demo_url)
                                                <a href="{{ $project->demo_url }}" target="_blank" class="btn btn-primary btn-sm">Demo</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div style="margin-top: 2rem;">
                        {{ $projects->links() }}
                    </div>
                @else
                    <div class="card" style="text-align: center; padding: 3rem;">
                        <p style="color: var(--slate-500); margin-bottom: 1rem;">Belum ada proyek yang dipublikasikan untuk divisi ini.</p>
                        <a href="{{ route('projects.index') }}" class="btn btn-outline btn-sm">Lihat Seluruh Proyek</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Biodata Pembina & Biodata Ketua Divisi -->
        <div id="bio-pengurus">
            <!-- Biodata Pembina -->
            <div class="card" style="margin-bottom: 2rem; border-top: 6px solid var(--slate-800);">
                <div class="section-tag" style="color: var(--slate-700);">Biodata Pembina</div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1rem;">
                    Dosen Pembina Bidang
                </h3>

                <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.25rem;">
                    @if ($division->adviser_photo)
                        <img src="{{ asset($division->adviser_photo) }}" alt="{{ $division->adviser_name }}" style="width: 76px; height: 76px; border-radius: var(--radius-md); object-fit: cover; border: 2px solid #ffffff; box-shadow: var(--clay-pill);">
                    @else
                        <div style="width: 76px; height: 76px; border-radius: var(--radius-md); background: linear-gradient(135deg, var(--slate-700), var(--slate-900)); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; font-weight: 800; border: 2px solid #ffffff; box-shadow: var(--clay-pill);">
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

                <div style="background-color: #f6f9fc; border: 2px solid #ffffff; box-shadow: var(--clay-input); border-radius: var(--radius-md); padding: 1rem; font-size: 0.825rem; color: var(--slate-600); line-height: 1.6;">
                    Membimbing riset kurikulum, standarisasi etika keilmuan, serta supervisi keikutsertaan kompetisi tingkat regional dan nasional.
                </div>
            </div>

            <!-- Biodata Ketua Divisi -->
            <div class="card" style="margin-bottom: 2rem; border-top: 6px solid {{ $division->color_accent }};">
                <div class="section-tag" style="color: {{ $division->color_accent }};">Biodata Ketua Bidang</div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1rem;">
                    Ketua Divisi (Mahasiswa)
                </h3>

                <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.25rem;">
                    @if ($division->leader_photo)
                        <img src="{{ asset($division->leader_photo) }}" alt="{{ $division->leader_name }}" style="width: 76px; height: 76px; border-radius: var(--radius-md); object-fit: cover; border: 2px solid #ffffff; box-shadow: var(--clay-pill);">
                    @else
                        <div style="width: 76px; height: 76px; border-radius: var(--radius-md); background: linear-gradient(135deg, {{ $division->color_accent }}, var(--slate-900)); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; font-weight: 800; border: 2px solid #ffffff; box-shadow: var(--clay-pill);">
                            {{ strtoupper(substr($division->leader_name, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <div style="font-weight: 800; color: var(--slate-900); font-size: 1.05rem;">
                            {{ $division->leader_name }}
                        </div>
                        <div style="font-size: 0.85rem; color: var(--slate-500); font-family: var(--font-mono); margin-top: 0.2rem; font-weight: 600;">
                            NIM: {{ $division->leader_nim }}
                        </div>
                    </div>
                </div>

                <div style="font-size: 0.9rem; color: var(--slate-700); line-height: 1.7; margin-bottom: 1.25rem; font-style: italic; background: #f6f9fc; padding: 1rem; border-radius: var(--radius-md); box-shadow: var(--clay-input);">
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
                <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.75rem;">
                    Jelajahi Divisi Lainnya
                </h4>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach ($otherDivisions as $other)
                        <li>
                            <a href="{{ route('divisions.show', $other->slug) }}" class="clay-pill-btn" style="display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 1rem; color: var(--slate-700); font-size: 0.85rem;">
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
