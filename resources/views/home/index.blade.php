@extends('layouts.app')

@section('title', 'UKM Ilmu Komputer - Wadah Riset & Inovasi Teknologi')

@section('content')
<!-- 1. Hero Section -->
<section class="hero ambient-glow">
    <div class="container" style="position: relative; z-index: 1;">
        <div class="hero-split-layout">
            <!-- Left Text Column -->
            <div class="hero-text-col">
                <div class="hero-badge-wrap" style="justify-content: flex-start;">
                    @if ($recruitmentStatus === 'open')
                        <span class="badge badge-success">
                            <span class="badge-pulse"></span>
                            <span>Open Recruitment &bull; {{ $recruitmentBatch }}</span>
                        </span>
                    @else
                        <span class="badge badge-warning">
                            <span>Pendaftaran Periode Ini Sedang Ditutup</span>
                        </span>
                    @endif
                </div>

                <h1 class="hero-title">
                    Pusat Riset, Inovasi & Rekayasa <span>Teknologi Digital</span> Mahasiswa
                </h1>

                <p class="hero-subtitle">
                    Portal kolaborasi riset resmi UKM Program Studi Ilmu Komputer, Fakultas Sains, Teknologi, dan Ilmu Kesehatan (FSTIK) Universitas Bina Bangsa Getsempena. Eksplorasi rekayasa perangkat lunak modern, karya multimedia interaktif, otomasi mikrokontroler IoT, dan pengujian ketahanan siber.
                </p>

                <div class="hero-actions">
                    @if ($recruitmentStatus === 'open')
                        <a href="{{ route('recruitment.index') }}" class="btn btn-primary btn-lg">
                            <span>Daftar Jadi Anggota</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    @endif
                    <a href="#divisions" class="btn btn-glass btn-lg">Jelajahi 4 Divisi</a>
                    <a href="{{ route('projects.index') }}" class="btn btn-outline btn-lg">Showcase Karya</a>
                </div>
            </div>

            <!-- Right Column: Pure Official Logo Without Background -->
            <div style="display: flex; justify-content: center; align-items: center;">
                <img src="{{ asset('images/logo.png') }}" alt="Logo UKM Ilmu Komputer" class="hero-pure-logo">
            </div>
        </div>
    </div>
</section>

<!-- 2. Metric Stat Strip Overview -->
<section class="stat-section" style="padding: 2rem 0 3.5rem;">
    <div class="container">
        <div class="stat-strip">
            <div class="stat-item">
                <div class="stat-number">4</div>
                <div class="stat-label">Divisi Spesialisasi</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $stats['projects_count'] ?? 12 }}+</div>
                <div class="stat-label">Karya & Inovasi</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $stats['active_members'] ?? 120 }}+</div>
                <div class="stat-label">Anggota Aktif Kampus</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">1 Pintu</div>
                <div class="stat-label">Sistem Seleksi Terpadu</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Bento Grid 4 Divisions Showcase -->
<section id="divisions" style="padding: 4.5rem 0;">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Struktur Keahlian</div>
            <h2 class="section-title">4 Bidang Divisi Spesialisasi</h2>
            <p class="section-desc">
                Setiap bidang dipandu oleh Dosen Pembina berkompeten dan dipimpin oleh Ketua Divisi mahasiswa untuk mewadahi minat spesifikmu.
            </p>
        </div>

        <div class="division-grid">
            @foreach ($divisions as $division)
                <div class="division-card" style="--div-accent: {{ $division->color_accent }}; --div-bg: {{ $division->color_accent }}15;">
                    <div>
                        <div class="division-icon-box">
                            {!! $division->icon_svg !!}
                        </div>
                        <h3 class="division-title">{{ $division->name }}</h3>
                        <p class="division-tagline">{{ $division->tagline }}</p>
                        <p style="font-size: 0.925rem; color: var(--slate-600); margin-bottom: 1.25rem; line-height: 1.6;">
                            {{ Str::limit($division->description, 140) }}
                        </p>

                        <!-- Key topics tags -->
                        <div style="display: flex; flex-wrap: wrap; gap: 0.45rem; margin-bottom: 1.25rem;">
                            @foreach (array_slice($division->focus_topics_list, 0, 3) as $topic)
                                <span style="display: inline-flex; align-items: center; font-size: 0.75rem; font-weight: 700; padding: 0.35rem 0.85rem; background: var(--bg-body); color: var(--slate-700); border-radius: var(--radius-full); box-shadow: var(--clay-pill);">
                                    {{ $topic }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <!-- Leadership & Mentorship Strip -->
                        <div style="display: flex; flex-direction: column; gap: 0.65rem; margin-top: 1.25rem;">
                            <!-- Dosen Pembina Divisi -->
                            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0.95rem; background: var(--bg-body); border-radius: var(--radius-md); box-shadow: var(--clay-pill); border-left: 3.5px solid {{ $division->color_accent }};">
                                <div style="width: 38px; height: 38px; border-radius: 50%; background: {{ $division->color_accent }}18; color: {{ $division->color_accent }}; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0; box-shadow: var(--clay-pill);">
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-size: 0.68rem; font-weight: 800; color: {{ $division->color_accent }}; text-transform: uppercase; letter-spacing: 0.04em;">
                                        Dosen Pembina {{ $division->name }}
                                    </div>
                                    <div style="font-size: 0.85rem; font-weight: 800; color: var(--slate-900); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $division->adviser_name }}">
                                        {{ $division->adviser_name }}
                                    </div>
                                    <div style="font-size: 0.72rem; color: var(--slate-500); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $division->adviser_title }}">
                                        {{ $division->adviser_title }}
                                    </div>
                                </div>
                            </div>

                            <!-- Ketua Divisi Mahasiswa -->
                            <div class="division-leader-info" style="margin-top: 0; padding: 0.75rem 0.95rem;">
                                <div class="avatar-round" style="width: 38px; height: 38px; font-size: 0.8rem; background: {{ $division->color_accent }}15; color: {{ $division->color_accent }};">
                                    {{ strtoupper(substr($division->leader_name, 0, 2)) }}
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-size: 0.68rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.04em;">
                                        Ketua Divisi Mahasiswa
                                    </div>
                                    <div style="font-size: 0.85rem; font-weight: 800; color: var(--slate-900); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $division->leader_name }}
                                    </div>
                                    <div style="font-size: 0.72rem; color: var(--slate-500); font-weight: 600;">
                                        NIM: {{ $division->leader_nim }}
                                    </div>
                                </div>
                                <a href="{{ route('divisions.show', $division->slug) }}" class="btn btn-outline btn-sm" style="padding: 0.4rem 0.75rem; font-size: 0.775rem;">
                                    Detail &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 3. Featured Projects Showcase -->
<section style="padding: 5.5rem 0;">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Inovasi Nyata</div>
            <h2 class="section-title">Karya & Inovasi Mahasiswa</h2>
            <p class="section-desc">
                Eksplorasi proyek perangkat lunak, sistem cerdas, antarmuka visual, dan modul keamanan siber yang dirancang langsung oleh mahasiswa UKM.
            </p>
        </div>

        <div class="projects-grid">
            @forelse ($featuredProjects as $project)
                <div class="project-card">
                    <div class="project-thumb">
                        <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}">
                        <div class="project-thumb-overlay"></div>
                    </div>

                    <div class="project-body">
                        <div>
                            <div style="margin-bottom: 0.6rem;">
                                @if ($project->division)
                                    <span class="badge" style="background: {{ $project->division->color_accent }}15; color: {{ $project->division->color_accent }}; font-size: 0.725rem; font-weight: 700;">
                                        {{ $project->division->name }}
                                    </span>
                                @else
                                    <span class="badge badge-neutral" style="font-size: 0.725rem; font-weight: 700;">Multi-Divisi</span>
                                @endif
                            </div>

                            <h3 class="project-title">{{ $project->title }}</h3>
                            <p class="project-desc">{{ Str::limit($project->description, 115) }}</p>

                            <div class="tech-tags">
                                @foreach ($project->tech_stack ?? [] as $tech)
                                    <span class="tech-tag">{{ $tech }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="project-footer">
                            <span style="color: var(--slate-600); font-size: 0.8rem; font-weight: 600;">
                                Tim: {{ Str::limit($project->author_names, 24) }}
                            </span>
                            <div style="display: flex; gap: 0.4rem;">
                                @if ($project->repo_url)
                                    <a href="{{ $project->repo_url }}" target="_blank" class="btn btn-outline btn-sm" title="Source Code">
                                        GitHub
                                    </a>
                                @endif
                                @if ($project->demo_url)
                                    <a href="{{ $project->demo_url }}" target="_blank" class="btn btn-primary btn-sm" title="Live Preview">
                                        Demo
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--slate-500);">
                    Belum ada proyek yang ditampilkan.
                </div>
            @endforelse
        </div>

        <div style="text-align: center; margin-top: 3rem;">
            <a href="{{ route('projects.index') }}" class="btn btn-glass btn-lg">
                <span>Lihat Seluruh Portofolio Karya Mahasiswa</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- 4. Upcoming Events & Workshop Hub -->
<section style="padding: 4.5rem 0;">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Kalender Pelatihan</div>
            <h2 class="section-title">Agenda Bootcamp & Workshop</h2>
            <p class="section-desc">
                Tingkatkan skill digitalmu lewat seminar terarah, bootcamp intensif, dan sesi sharing berkala.
            </p>
        </div>

        <div class="events-grid">
            @forelse ($upcomingEvents as $event)
                <div class="event-card">
                    <div class="event-banner">
                        <img src="{{ $event->banner_url }}" alt="{{ $event->title }}">
                        <div class="event-banner-overlay"></div>
                        <div style="position: absolute; top: 0.85rem; left: 0.85rem;">
                            <div class="event-date-box">
                                <span class="event-day">{{ $event->event_date->format('d') }}</span>
                                <span class="event-month">{{ $event->event_date->format('M') }}</span>
                            </div>
                        </div>
                        <div style="position: absolute; top: 0.85rem; right: 0.85rem;">
                            <span class="badge {{ $event->location_type === 'online' ? 'badge-info' : 'badge-neutral' }}" style="backdrop-filter: blur(8px); text-transform: uppercase; font-weight: 800; font-size: 0.7rem;">
                                {{ $event->location_type }}
                            </span>
                        </div>
                    </div>

                    <div class="event-body">
                        <div>
                            <div style="margin-bottom: 0.5rem;">
                                @if ($event->division)
                                    <span class="badge" style="background: {{ $event->division->color_accent }}15; color: {{ $event->division->color_accent }}; font-size: 0.725rem; font-weight: 700;">
                                        {{ $event->division->name }}
                                    </span>
                                @else
                                    <span class="badge badge-neutral" style="font-size: 0.725rem; font-weight: 700;">Agenda Umum</span>
                                @endif
                            </div>

                            <h3 class="event-title">
                                {{ $event->title }}
                            </h3>

                            <p class="event-desc">
                                {{ Str::limit($event->description, 110) }}
                            </p>
                        </div>

                        <div>
                            <div class="event-meta-box">
                                <div><strong style="color: var(--slate-800);">Lokasi:</strong> {{ $event->location_venue }}</div>
                                <div><strong style="color: var(--slate-800);">Waktu:</strong> {{ substr($event->time_start, 0, 5) }} - {{ $event->time_end ? substr($event->time_end, 0, 5) : 'Selesai' }} WIB</div>
                            </div>

                            @if ($event->registration_link)
                                <a href="{{ $event->registration_link }}" target="_blank" class="btn btn-primary btn-sm" style="width: 100%;">
                                    Registrasi Kursi &rarr;
                                </a>
                            @else
                                <a href="{{ route('events.index') }}" class="btn btn-outline btn-sm" style="width: 100%;">
                                    Detail Agenda
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: var(--slate-500);">
                    Belum ada agenda terdekat saat ini.
                </div>
            @endforelse
        </div>

        <div style="text-align: center; margin-top: 2.5rem;">
            <a href="{{ route('events.index') }}" class="btn btn-outline">
                Lihat Seluruh Riwayat & Jadwal Workshop &rarr;
            </a>
        </div>
    </div>
</section>

<!-- 5. Latest Publications & Articles Section -->
@if(isset($latestPosts) && $latestPosts->count() > 0)
<section style="padding: 4.5rem 0; background: var(--bg-body, #f8fafc);">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Kanal Riset & Wawasan</div>
            <h2 class="section-title">Publikasi & Artikel Terbaru</h2>
            <p class="section-desc">
                Wawasan teknologi, artikel edukasi, tutorial praktis, dan dokumentasi riset dari anggota 4 divisi UKM Ilmu Komputer.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            @foreach ($latestPosts->take(3) as $post)
                <div style="background: #ffffff; border-radius: var(--radius-xl); border: 1.5px solid rgba(226, 232, 240, 0.9); box-shadow: var(--clay-card); overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    <div>
                        <div style="height: 180px; width: 100%; position: relative; overflow: hidden; background: #e2e8f0;">
                            @if ($post->thumbnail)
                                <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, {{ $post->division->color_accent ?? '#0284c7' }}22, #ffffff); color: {{ $post->division->color_accent ?? '#0284c7' }}; font-size: 2.5rem;">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            @endif
                            <div style="position: absolute; top: 0.75rem; left: 0.75rem;">
                                <span class="badge" style="background: {{ $post->division->color_accent ?? '#0284c7' }}; color: #ffffff; font-weight: 800; font-size: 0.7rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    {{ $post->division->name ?? 'UKM ILKOM' }}
                                </span>
                            </div>
                        </div>

                        <div style="padding: 1.25rem 1.5rem;">
                            <div style="display: flex; gap: 0.75rem; align-items: center; font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">
                                <span><i class="fas fa-calendar" style="margin-right: 0.25rem;"></i>{{ $post->created_at->format('d M Y') }}</span>
                                <span>&bull;</span>
                                <span style="text-transform: capitalize;"><i class="fas fa-tag" style="margin-right: 0.25rem;"></i>{{ $post->category }}</span>
                            </div>

                            <h3 style="font-size: 1.05rem; font-weight: 800; color: #0c2340; margin: 0 0 0.5rem; line-height: 1.4;">
                                <a href="{{ route('posts.show', $post->slug) }}" style="text-decoration: none; color: inherit;">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            <p style="font-size: 0.85rem; color: #475569; line-height: 1.6; margin: 0;">
                                {{ Str::limit($post->excerpt, 110) }}
                            </p>
                        </div>
                    </div>

                    <div style="padding: 0.85rem 1.5rem 1.25rem; border-top: 1px dashed #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.775rem; color: #64748b; font-weight: 600;">
                            <i class="fas fa-user-pen" style="color: #0284c7; margin-right: 0.25rem;"></i> {{ $post->author->name ?? 'Redaksi' }}
                        </span>
                        <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-outline btn-xs" style="font-size: 0.75rem; font-weight: 700; border-radius: 9999px; padding: 0.3rem 0.85rem; color: #0284c7; border: 1.5px solid #bae6fd; background: #ffffff;">
                            Baca Selengkapnya &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="text-align: center; margin-top: 2.5rem;">
            <a href="{{ route('posts.index') }}" class="btn btn-glass btn-lg">
                <span>Eksplorasi Seluruh Publikasi & Tutorial</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- 6. Oprec Call to Action Banner (Clay Card) -->
<section style="padding: 2rem 0 5rem;">
    <div class="container">
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 4.5rem 2rem; border-radius: var(--radius-xl); box-shadow: 10px 20px 40px rgba(15,23,42,0.25), inset 2px 2px 5px rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.2); text-align: center; position: relative; overflow: hidden;">
            <span class="badge badge-success" style="margin-bottom: 1.5rem;">
                <span class="badge-pulse"></span>
                <span>Pendaftaran Anggota Baru Terbuka</span>
            </span>
            <h2 style="font-size: clamp(2rem, 3.5vw, 2.75rem); font-weight: 800; letter-spacing: -0.03em; margin-bottom: 1rem; color: #ffffff;">
                Siap Menjadi Bagian dari Inovator Kampus?
            </h2>
            <p style="color: var(--slate-300); max-width: 650px; margin: 0 auto 2.25rem; font-size: 1.05rem; line-height: 1.7;">
                Satu formulir pendaftaran untuk semua divisi spesialisasi. Pilih bidang minat utama, tunjukkan potensimu, dan bangun portofolio terbaikmu bersama kami.
            </p>
            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('recruitment.index') }}" class="btn btn-accent btn-lg">
                    Daftar Online Sekarang &rarr;
                </a>
                <a href="{{ route('recruitment.status') }}" class="btn btn-glass btn-lg" style="color: #0f172a;">
                    Cek Status Seleksi
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
