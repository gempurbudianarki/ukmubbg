@extends('layouts.app')

@section('title', 'Agenda, Workshop & Kalender Event - UKM Ilmu Komputer')

@section('styles')
<style>
    /* ==========================================================================
       EVENTS & WORKSHOP LUXURY WHITE CLAYMORPHISM SYSTEM
       ========================================================================== */
    .events-hero {
        text-align: center;
        padding: 4.5rem 1.5rem 2.5rem;
    }

    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 2rem;
        margin-bottom: 3.5rem;
    }

    @media (max-width: 640px) {
        .events-grid {
            grid-template-columns: 1fr;
        }
    }

    .event-card-luxury {
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

    .event-card-luxury:hover {
        transform: translateY(-6px);
        box-shadow: 0 25px 45px -10px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(2, 132, 199, 0.2);
    }

    .event-poster-wrap {
        height: 210px;
        position: relative;
        overflow: hidden;
        background: #0f172a;
    }

    .event-poster-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .event-card-luxury:hover .event-poster-img {
        transform: scale(1.04);
    }

    .event-poster-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15,23,42,0.15) 0%, rgba(15,23,42,0.85) 100%);
        pointer-events: none;
    }

    /* Floating Calendar Date Chip */
    .event-floating-date {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        border: 1.5px solid rgba(255, 255, 255, 0.9);
        border-radius: 14px;
        padding: 0.45rem 0.85rem;
        text-align: center;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
        z-index: 2;
    }

    .event-floating-day {
        font-size: 1.35rem;
        font-weight: 900;
        color: #0c2340;
        line-height: 1;
        display: block;
        font-family: var(--font-mono, monospace);
    }

    .event-floating-month {
        font-size: 0.68rem;
        font-weight: 800;
        color: #0284c7;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-top: 0.15rem;
        display: block;
    }

    /* Event Meta Box (Debossed) */
    .event-meta-debossed {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        box-shadow: var(--clay-debossed);
        border-radius: 14px;
        padding: 1rem 1.15rem;
        margin-bottom: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        font-size: 0.825rem;
    }
</style>
@endsection

@section('content')
<div style="background: #f8fafc; padding-bottom: 5rem;">

    <!-- Hero Header -->
    <div class="container">
        <div class="events-hero">
            <span class="badge" style="background: #e0f2fe; color: #0284c7; padding: 0.4rem 1.15rem; border-radius: 9999px; font-weight: 800; font-size: 0.78rem; letter-spacing: 0.05em; text-transform: uppercase; box-shadow: var(--clay-pill); margin-bottom: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="fas fa-calendar-star"></i> AGENDA & BOOTCAMP RESMI
            </span>
            <h1 style="font-size: 2.75rem; font-weight: 900; color: #0c2340; letter-spacing: -0.02em; margin: 0 0 0.85rem; line-height: 1.2;">
                Kalender Pelatihan & Workshop Teknologi
            </h1>
            <p style="color: #64748b; max-width: 700px; margin: 0 auto; font-size: 1.05rem; line-height: 1.6;">
                Ikuti workshop pemrograman, lab praktikum keamanan siber, bedah hardware IoT, serta seminar teknologi yang diadakan secara terbuka untuk seluruh civitas akademika.
            </p>
        </div>
    </div>

    <!-- Upcoming Events Section -->
    <div class="container">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid #e2e8f0; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2 style="font-size: 1.65rem; font-weight: 850; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.65rem;">
                    <i class="fas fa-bolt" style="color: #0284c7;"></i>
                    <span>Agenda Mendatang (Upcoming)</span>
                </h2>
                <p style="color: #64748b; font-size: 0.875rem; margin: 0.25rem 0 0;">
                    Daftar dan amankan kursi Anda sebelum kuota penuh.
                </p>
            </div>
            <span class="badge badge-success" style="box-shadow: var(--clay-pill); font-size: 0.78rem; font-weight: 800; padding: 0.45rem 1rem; border-radius: 9999px;">
                <span class="badge-pulse"></span>
                <span>{{ $upcomingEvents->count() }} Kegiatan Dibuka</span>
            </span>
        </div>

        <div class="events-grid">
            @forelse ($upcomingEvents as $event)
                @php
                    $divAccent = $event->division?->color_accent ?? '#0284c7';
                @endphp
                <div class="event-card-luxury">
                    <!-- Poster Area -->
                    <div class="event-poster-wrap">
                        <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="event-poster-img">
                        <div class="event-poster-overlay"></div>

                        <!-- Date Badge -->
                        <div class="event-floating-date">
                            <span class="event-floating-day">{{ $event->event_date->format('d') }}</span>
                            <span class="event-floating-month">{{ $event->event_date->format('M') }}</span>
                        </div>

                        <!-- Mode / Location Type Badge -->
                        <div style="position: absolute; top: 1rem; right: 1rem; z-index: 2;">
                            <span class="badge" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25); font-weight: 800; font-size: 0.725rem; padding: 0.35rem 0.85rem; border-radius: 9999px;">
                                <i class="fas {{ $event->location_type === 'online' ? 'fa-video' : 'fa-building' }}" style="margin-right: 0.3rem; color: #38bdf8;"></i>
                                {{ strtoupper($event->location_type) }}
                            </span>
                        </div>

                        <!-- Division Badge on Poster Bottom -->
                        <div style="position: absolute; bottom: 0.85rem; left: 1rem; right: 1rem; z-index: 2;">
                            @if ($event->division)
                                <span class="badge" style="background: {{ $divAccent }}; color: #ffffff; font-size: 0.725rem; font-weight: 800; box-shadow: 0 4px 10px rgba(0,0,0,0.3); border-radius: 6px;">
                                    {{ $event->division->name }}
                                </span>
                            @else
                                <span class="badge" style="background: #0284c7; color: #ffffff; font-size: 0.725rem; font-weight: 800; border-radius: 6px;">
                                    Agenda Terbuka UKM
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Content Body -->
                    <div style="padding: 1.75rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h3 style="font-size: 1.25rem; font-weight: 850; color: #0f172a; margin: 0 0 0.65rem; line-height: 1.35;">
                                {{ $event->title }}
                            </h3>
                            <p style="font-size: 0.885rem; color: #64748b; line-height: 1.6; margin: 0 0 1.25rem;">
                                {{ Str::limit($event->description, 130) }}
                            </p>
                        </div>

                        <div>
                            <!-- Meta Details Box -->
                            <div class="event-meta-debossed">
                                <div style="color: #334155;">
                                    <strong style="color: #0f172a;"><i class="fas fa-location-dot" style="color: #ef4444; margin-right: 0.35rem;"></i> Lokasi:</strong>
                                    {{ $event->location_venue }}
                                </div>
                                <div style="color: #334155;">
                                    <strong style="color: #0f172a;"><i class="fas fa-clock" style="color: #0284c7; margin-right: 0.35rem;"></i> Waktu:</strong>
                                    {{ substr($event->time_start, 0, 5) }} - {{ $event->time_end ? substr($event->time_end, 0, 5) : 'Selesai' }} WIB
                                </div>
                                @if ($event->max_participants)
                                    <div style="color: #334155;">
                                        <strong style="color: #0f172a;"><i class="fas fa-users" style="color: #10b981; margin-right: 0.35rem;"></i> Kuota:</strong>
                                        Maksimal {{ $event->max_participants }} Peserta
                                    </div>
                                @endif
                            </div>

                            <!-- CTA Button -->
                            @if ($event->registration_link)
                                <a href="{{ $event->registration_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary" style="width: 100%; border-radius: 9999px; font-weight: 800; font-size: 0.9rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #0284c7, #0369a1); border: none; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35); text-align: center; display: block;">
                                    Daftar / Booking Kursi &rarr;
                                </a>
                            @else
                                <span class="btn btn-outline" style="width: 100%; border-radius: 9999px; font-weight: 700; font-size: 0.85rem; padding: 0.7rem 1.5rem; background: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); text-align: center; display: block; color: #64748b; cursor: default;">
                                    <i class="fas fa-ticket"></i> Pendaftaran di Lokasi (OTS)
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: #ffffff; border-radius: 24px; box-shadow: var(--clay-card); border: 1.5px solid #e2e8f0; color: #64748b;">
                    <div style="width: 64px; height: 64px; border-radius: 20px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.85rem; margin: 0 auto 1.25rem; box-shadow: var(--clay-pill);">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 0.4rem;">
                        Belum Ada Agenda Mendatang
                    </h3>
                    <p style="color: #64748b; font-size: 0.9rem; max-width: 480px; margin: 0 auto;">
                        Jadwal pelatihan dan bootcamp periode ini sedang disiapkan oleh pengurus divisi. Pantau pengumuman terbaru secara berkala.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- ==========================================
             Past Events Section
             ========================================== -->
        @if ($pastEvents->isNotEmpty())
            <div style="margin-top: 4rem;">
                <div style="margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid #e2e8f0;">
                    <h2 style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.65rem;">
                        <i class="fas fa-clock-rotate-left" style="color: #64748b;"></i>
                        <span>Riwayat Kegiatan Sebelumnya (Past Events)</span>
                    </h2>
                    <p style="color: #64748b; font-size: 0.85rem; margin: 0.25rem 0 0;">
                        Dokumentasi agenda dan materi yang telah berhasil terselenggara.
                    </p>
                </div>

                <div class="events-grid">
                    @foreach ($pastEvents as $event)
                        <div style="background: #ffffff; border-radius: 20px; border: 1.5px solid #e2e8f0; padding: 1.5rem; box-shadow: var(--clay-card); display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.85rem;">
                                    <span class="badge" style="background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; font-weight: 700; font-size: 0.725rem;">
                                        <i class="fas fa-check-double" style="color: #10b981; margin-right: 0.25rem;"></i> Telah Selesai
                                    </span>
                                    <span style="font-size: 0.775rem; color: #94a3b8; font-family: var(--font-mono, monospace);">
                                        {{ $event->event_date->format('d M Y') }}
                                    </span>
                                </div>

                                <h4 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem; line-height: 1.35;">
                                    {{ $event->title }}
                                </h4>
                                <p style="font-size: 0.825rem; color: #64748b; line-height: 1.55; margin: 0 0 1rem;">
                                    {{ Str::limit($event->description, 100) }}
                                </p>
                            </div>

                            <div style="font-size: 0.775rem; color: #64748b; border-top: 1px dashed #e2e8f0; padding-top: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
                                <span><i class="fas fa-location-dot" style="margin-right: 0.25rem;"></i> {{ $event->location_venue }}</span>
                                <span class="badge" style="background: #f8fafc; color: #0284c7; font-size: 0.7rem;">{{ $event->division?->name ?? 'Umum' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
