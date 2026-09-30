@extends('layouts.app')

@section('title', 'UKM Ilmu Komputer - Wadah Riset & Inovasi Teknologi')

@section('content')
<!-- 1. Hero Section -->
<section class="hero ambient-glow">
    <div class="container" style="position: relative; z-index: 1;">
        <div class="hero-badge-wrap">
            @if ($recruitmentStatus === 'open')
                <span class="badge badge-success">
                    <span class="badge-pulse"></span>
                    <span>Open Recruitment Dibuka &bull; {{ $recruitmentBatch }} (Batas: {{ $recruitmentDeadline }})</span>
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
            Unit Kegiatan Mahasiswa Fakultas Ilmu Komputer. Ruang kolaborasi untuk mengasah keahlian rekayasa perangkat lunak, eksplorasi multimedia, otomasi IoT, dan ketahanan siber profesional.
        </p>

        <div class="hero-actions">
            @if ($recruitmentStatus === 'open')
                <a href="{{ route('recruitment.index') }}" class="btn btn-primary btn-lg">
                    <span>Gabung Sekarang</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            @endif
            <a href="#divisions" class="btn btn-glass btn-lg">Jelajahi 4 Divisi</a>
            <a href="{{ route('projects.index') }}" class="btn btn-outline btn-lg">Lihat Karya Mahasiswa</a>
        </div>

        <!-- Metric Stat Strip -->
        <div class="stat-strip">
            <div class="stat-item">
                <div class="stat-number">4</div>
                <div class="stat-label">Divisi Spesialisasi</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $stats['posts_count'] ?? 15 }}+</div>
                <div class="stat-label">Publikasi & Riset</div>
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

<!-- 2. Bento Grid 4 Divisions Showcase -->
<section id="divisions" style="padding: 5.5rem 0; background: #ffffff; border-top: 1px solid var(--slate-200); border-bottom: 1px solid var(--slate-200);">
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
                        <p style="font-size: 0.9rem; color: var(--slate-600); margin-bottom: 1.25rem; line-height: 1.6;">
                            {{ Str::limit($division->description, 135) }}
                        </p>

                        <!-- Key topics tags -->
                        <div style="display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 1rem;">
                            @foreach (array_slice($division->focus_topics_list, 0, 3) as $topic)
                                <span style="font-size: 0.775rem; background: var(--slate-100); padding: 0.2rem 0.55rem; border-radius: var(--radius-sm); color: var(--slate-700); font-weight: 500;">
                                    {{ $topic }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="division-leader-info">
                            <div class="avatar-round" style="background: {{ $division->color_accent }}20; color: {{ $division->color_accent }};">
                                {{ strtoupper(substr($division->leader_name, 0, 2)) }}
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: 0.85rem; font-weight: 700; color: var(--slate-900);">
                                    {{ $division->leader_name }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--slate-500);">
                                    Ketua Divisi &bull; NIM: {{ $division->leader_nim }}
                                </div>
                            </div>
                            <a href="{{ route('divisions.show', $division->slug) }}" class="btn btn-outline btn-sm" style="border-radius: var(--radius-full);">
                                Detail &rarr;
                            </a>
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
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem; color: var(--slate-400);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            <span style="font-size: 0.775rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                                {{ $project->division ? $project->division->name : 'Kolaborasi UKM' }}
                            </span>
                        </div>
                    </div>

                    <div class="project-body">
                        <div style="margin-bottom: 0.5rem;">
                            @if ($project->division)
                                <span class="badge" style="background: {{ $project->division->color_accent }}15; color: {{ $project->division->color_accent }}; font-size: 0.725rem;">
                                    {{ $project->division->name }}
                                </span>
                            @else
                                <span class="badge badge-neutral" style="font-size: 0.725rem;">Multi-Divisi</span>
                            @endif
                        </div>

                        <h3 class="project-title">{{ $project->title }}</h3>
                        <p class="project-desc">{{ Str::limit($project->description, 110) }}</p>

                        <div class="tech-tags">
                            @foreach ($project->tech_stack ?? [] as $tech)
                                <span class="tech-tag">{{ $tech }}</span>
                            @endforeach
                        </div>

                        <div class="project-footer">
                            <span style="color: var(--slate-500); font-size: 0.8rem;">
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
<section style="padding: 5rem 0; background: var(--slate-100); border-top: 1px solid var(--slate-200); border-bottom: 1px solid var(--slate-200);">
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
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div class="event-date-box">
                                <span class="event-day">{{ $event->event_date->format('d') }}</span>
                                <span class="event-month">{{ $event->event_date->format('M') }}</span>
                            </div>
                            <span class="badge {{ $event->location_type === 'online' ? 'badge-info' : 'badge-neutral' }}" style="text-transform: capitalize;">
                                {{ $event->location_type }}
                            </span>
                        </div>

                        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--slate-900); margin: 0.75rem 0 0.5rem; line-height: 1.35;">
                            {{ $event->title }}
                        </h3>

                        <p style="font-size: 0.875rem; color: var(--slate-600); line-height: 1.6; margin-bottom: 1.25rem;">
                            {{ Str::limit($event->description, 110) }}
                        </p>
                    </div>

                    <div>
                        <div style="font-size: 0.8rem; color: var(--slate-500); margin-bottom: 1.25rem; display: flex; flex-direction: column; gap: 0.3rem;">
                            <div><strong>Lokasi:</strong> {{ $event->location_venue }}</div>
                            <div><strong>Waktu:</strong> {{ substr($event->time_start, 0, 5) }} - {{ $event->time_end ? substr($event->time_end, 0, 5) : 'Selesai' }} WIB</div>
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

<!-- 5. Latest Insights / Publications -->
<section style="padding: 5.5rem 0;">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Kanal Publikasi</div>
            <h2 class="section-title">Wawasan, Riset & Dokumentasi</h2>
            <p class="section-desc">
                Artikel kajian teknis, dokumentasi workshop, dan ulasan perkembangan teknologi mutakhir.
            </p>
        </div>

        <div class="projects-grid">
            @foreach ($latestPosts as $post)
                <article class="project-card">
                    <div class="project-thumb">
                        <div style="color: var(--slate-400); display: flex; flex-direction: column; align-items: center; gap: 0.4rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            <span style="font-size: 0.75rem; font-weight: 600;">{{ $post->division->name }}</span>
                        </div>
                    </div>

                    <div class="project-body">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <span class="badge" style="background: {{ $post->division->color_accent }}15; color: {{ $post->division->color_accent }}; font-size: 0.725rem;">
                                {{ $post->division->name }}
                            </span>
                            <span style="font-size: 0.75rem; color: var(--slate-400);">&bull;</span>
                            <span style="font-size: 0.75rem; color: var(--slate-500); text-transform: capitalize;">{{ $post->category }}</span>
                        </div>

                        <h3 class="project-title">
                            <a href="{{ route('posts.show', $post->slug) }}">
                                {{ $post->title }}
                            </a>
                        </h3>

                        <p class="project-desc">{{ Str::limit($post->excerpt, 110) }}</p>

                        <div class="project-footer">
                            <span style="color: var(--slate-400); font-size: 0.775rem;">
                                {{ $post->created_at->format('d M Y') }}
                            </span>
                            <a href="{{ route('posts.show', $post->slug) }}" style="font-size: 0.8rem; font-weight: 600; color: var(--accent-blue);">
                                Baca Selengkapnya &rarr;
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<!-- 6. Oprec Call to Action Banner -->
<section style="background: #0f172a; color: #ffffff; padding: 5rem 0; position: relative; overflow: hidden;">
    <div class="container" style="text-align: center; position: relative; z-index: 1;">
        <span class="badge badge-success" style="margin-bottom: 1.5rem;">
            <span class="badge-pulse"></span>
            <span>Pendaftaran Anggota Baru Terbuka</span>
        </span>
        <h2 style="font-size: 2.75rem; font-weight: 800; letter-spacing: -0.03em; margin-bottom: 1rem; color: #ffffff;">
            Siap Menjadi Bagian dari Inovator Kampus?
        </h2>
        <p style="color: var(--slate-300); max-width: 650px; margin: 0 auto 2.25rem; font-size: 1.1rem; line-height: 1.7;">
            Satu formulir pendaftaran untuk semua divisi spesialisasi. Pilih bidang minat utama, tunjukkan potensimu, dan bangun portofolio terbaikmu bersama kami.
        </p>
        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('recruitment.index') }}" class="btn btn-accent btn-lg">
                Daftar Online Sekarang &rarr;
            </a>
            <a href="{{ route('recruitment.status') }}" class="btn btn-glass btn-lg" style="color: #ffffff; border-color: var(--slate-700); background: rgba(255, 255, 255, 0.1);">
                Cek Status Seleksi
            </a>
        </div>
    </div>
</section>
@endsection
