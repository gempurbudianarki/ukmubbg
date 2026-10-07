@extends('layouts.app')

@section('title', 'Agenda & Workshop - UKM Ilmu Komputer')

@section('content')
<div style="padding: 4rem 0 5rem;">
    <div class="container">
        <!-- Header -->
        <div class="section-header">
            <div class="section-tag">Agenda & Workshop</div>
            <h1 class="section-title">Kalender Pelatihan & Event Teknologi</h1>
            <p class="section-desc">
                Ikuti bootcamp teknis, seminar inspiratif, kompetisi hackathon, dan sesi bedah teknologi yang diselenggarakan secara rutin oleh divisi UKM.
            </p>
        </div>

        <!-- Upcoming Section -->
        <div style="margin-bottom: 4rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8);">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900); display: flex; align-items: center; gap: 0.75rem;">
                    <span>Agenda Akan Datang</span>
                    <span style="font-size: 0.85rem; font-weight: 600; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.05em;">Upcoming</span>
                </h2>
                <span class="badge badge-success" style="box-shadow: var(--clay-pill);">
                    <span class="badge-pulse"></span>
                    <span>{{ $upcomingEvents->count() }} Kegiatan Aktif</span>
                </span>
            </div>

            <div class="events-grid">
                @forelse ($upcomingEvents as $event)
                    <div class="event-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column; border-radius: var(--radius-lg); border: none;">
                        <div style="height: 190px; position: relative; overflow: hidden;">
                            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(15,23,42,0.1) 0%, rgba(15,23,42,0.75) 100%);"></div>
                            
                            <div style="position: absolute; top: 1rem; left: 1rem;">
                                <div class="event-date-box" style="box-shadow: var(--clay-pill);">
                                    <span class="event-day">{{ $event->event_date->format('d') }}</span>
                                    <span class="event-month">{{ $event->event_date->format('M') }}</span>
                                </div>
                            </div>

                            <div style="position: absolute; top: 1rem; right: 1rem;">
                                <span class="badge {{ $event->location_type === 'online' ? 'badge-info' : 'badge-neutral' }}" style="background: rgba(255,255,255,0.9); color: var(--slate-800); box-shadow: var(--clay-pill); font-weight: 700;">
                                    {{ strtoupper($event->location_type) }}
                                </span>
                            </div>
                        </div>

                        <div style="padding: 1.75rem; display: flex; flex-direction: column; justify-content: space-between; flex: 1;">
                            <div>
                                <div style="margin-bottom: 0.75rem;">
                                    @if ($event->division)
                                        <span class="badge" style="background: {{ $event->division->color_accent }}18; color: {{ $event->division->color_accent }}; font-size: 0.75rem; font-weight: 700; box-shadow: var(--clay-pill);">
                                            {{ $event->division->name }}
                                        </span>
                                    @else
                                        <span class="badge badge-neutral" style="font-size: 0.75rem; box-shadow: var(--clay-pill);">Agenda Umum UKM</span>
                                    @endif
                                </div>

                                <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.65rem; line-height: 1.35;">
                                    {{ $event->title }}
                                </h3>

                                <p style="font-size: 0.875rem; color: var(--slate-600); line-height: 1.6; margin-bottom: 1.25rem;">
                                    {{ $event->description }}
                                </p>
                            </div>

                            <div>
                                <div style="font-size: 0.825rem; color: var(--slate-600); margin-bottom: 1.25rem; display: flex; flex-direction: column; gap: 0.45rem; padding: 1rem; background: var(--bg-body); border-radius: var(--radius-md); box-shadow: var(--clay-debossed);">
                                    <div><strong style="color: var(--slate-800);">📍 Lokasi:</strong> {{ $event->location_venue }}</div>
                                    <div><strong style="color: var(--slate-800);">⏰ Waktu:</strong> {{ substr($event->time_start, 0, 5) }} - {{ $event->time_end ? substr($event->time_end, 0, 5) : 'Selesai' }} WIB</div>
                                    @if ($event->max_participants)
                                        <div><strong style="color: var(--slate-800);">👥 Kuota:</strong> Terbatas untuk {{ $event->max_participants }} peserta</div>
                                    @endif
                                </div>

                                @if ($event->registration_link)
                                    <a href="{{ $event->registration_link }}" target="_blank" class="btn btn-accent btn-sm" style="width: 100%; border-radius: var(--radius-full);">
                                        Daftar / Booking Kursi &rarr;
                                    </a>
                                @else
                                    <span class="btn btn-glass btn-sm" style="width: 100%; cursor: default; border-radius: var(--radius-full);">
                                        Pendaftaran di Tempat (OTS)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3.5rem 2rem; background: #ffffff; border-radius: var(--radius-xl); box-shadow: var(--clay-card); color: var(--slate-500);">
                        Saat ini belum ada agenda terdekat yang dibuka pendaftarannya.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Past Events Section -->
        <div>
            <div style="margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8);">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900);">
                    Riwayat Kegiatan Sebelumnya
                </h2>
            </div>

            <div class="events-grid">
                @forelse ($pastEvents as $event)
                    <div class="event-card" style="opacity: 0.9; border: none; border-radius: var(--radius-lg); padding: 1.5rem;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div class="event-date-box" style="background: var(--slate-700); box-shadow: var(--clay-pill);">
                                    <span class="event-day">{{ $event->event_date->format('d') }}</span>
                                    <span class="event-month">{{ $event->event_date->format('M') }}</span>
                                </div>
                                <span class="badge badge-neutral" style="box-shadow: var(--clay-pill);">Selesai</span>
                            </div>

                            <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--slate-900); margin: 1rem 0 0.5rem;">
                                {{ $event->title }}
                            </h3>

                            <p style="font-size: 0.875rem; color: var(--slate-600); line-height: 1.5; margin-bottom: 1.25rem;">
                                {{ Str::limit($event->description, 100) }}
                            </p>
                        </div>

                        <div style="font-size: 0.825rem; color: var(--slate-500); padding-top: 0.75rem; border-top: 1px dashed rgba(203, 213, 225, 0.7);">
                            <div><strong>Lokasi:</strong> {{ $event->location_venue }}</div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; padding: 2.5rem; text-align: center; background: #ffffff; border-radius: var(--radius-xl); box-shadow: var(--clay-card); color: var(--slate-400); font-size: 0.95rem;">
                        Belum ada riwayat kegiatan terdahulu yang diarsipkan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
