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
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; border-bottom: 2px solid var(--slate-200); padding-bottom: 0.75rem;">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900);">
                    Agenda Akan Datang (Upcoming)
                </h2>
                <span class="badge badge-success">
                    <span class="badge-pulse"></span>
                    <span>{{ $upcomingEvents->count() }} Kegiatan Aktif</span>
                </span>
            </div>

            <div class="events-grid">
                @forelse ($upcomingEvents as $event)
                    <div class="event-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                        <div style="height: 180px; position: relative; overflow: hidden;">
                            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(15,23,42,0.1) 0%, rgba(15,23,42,0.75) 100%);"></div>
                            
                            <div style="position: absolute; top: 0.85rem; left: 0.85rem;">
                                <div class="event-date-box" style="box-shadow: 0 4px 12px rgba(0,0,0,0.4);">
                                    <span class="event-day">{{ $event->event_date->format('d') }}</span>
                                    <span class="event-month">{{ $event->event_date->format('M') }}</span>
                                </div>
                            </div>

                            <div style="position: absolute; top: 0.85rem; right: 0.85rem;">
                                <span class="badge {{ $event->location_type === 'online' ? 'badge-info' : 'badge-neutral' }}" style="background: rgba(15,23,42,0.85); color: #ffffff; backdrop-filter: blur(8px);">
                                    {{ $event->location_type }}
                                </span>
                            </div>
                        </div>

                        <div style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; flex: 1;">
                            <div>
                                <div style="margin-bottom: 0.5rem;">
                                    @if ($event->division)
                                        <span class="badge" style="background: {{ $event->division->color_accent }}15; color: {{ $event->division->color_accent }}; font-size: 0.725rem;">
                                            {{ $event->division->name }}
                                        </span>
                                    @else
                                        <span class="badge badge-neutral" style="font-size: 0.725rem;">Agenda Umum UKM</span>
                                    @endif
                                </div>

                                <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.5rem; line-height: 1.35;">
                                    {{ $event->title }}
                                </h3>

                                <p style="font-size: 0.875rem; color: var(--slate-600); line-height: 1.6; margin-bottom: 1.25rem;">
                                    {{ $event->description }}
                                </p>
                            </div>

                            <div>
                                <div style="font-size: 0.8rem; color: var(--slate-600); margin-bottom: 1.25rem; display: flex; flex-direction: column; gap: 0.35rem; padding: 0.85rem; background: var(--slate-50); border-radius: var(--radius-sm);">
                                    <div><strong>Lokasi:</strong> {{ $event->location_venue }}</div>
                                    <div><strong>Waktu:</strong> {{ substr($event->time_start, 0, 5) }} - {{ $event->time_end ? substr($event->time_end, 0, 5) : 'Selesai' }} WIB</div>
                                    @if ($event->max_participants)
                                        <div><strong>Kuota:</strong> Terbatas untuk {{ $event->max_participants }} peserta</div>
                                    @endif
                                </div>

                                @if ($event->registration_link)
                                    <a href="{{ $event->registration_link }}" target="_blank" class="btn btn-accent btn-sm" style="width: 100%;">
                                        Daftar / Booking Kursi &rarr;
                                    </a>
                                @else
                                    <span class="btn btn-glass btn-sm" style="width: 100%; cursor: default;">
                                        Pendaftaran di Tempat (OTS)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: #ffffff; border-radius: var(--radius-lg); border: 1px solid var(--slate-200); color: var(--slate-500);">
                        Saat ini belum ada agenda terdekat yang dibuka pendaftarannya.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Past Events Section -->
        <div>
            <div style="margin-bottom: 1.5rem; border-bottom: 2px solid var(--slate-200); padding-bottom: 0.75rem;">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900);">
                    Riwayat Kegiatan Sebelumnya
                </h2>
            </div>

            <div class="events-grid">
                @forelse ($pastEvents as $event)
                    <div class="event-card" style="opacity: 0.85;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div class="event-date-box" style="background: var(--slate-700);">
                                    <span class="event-day">{{ $event->event_date->format('d') }}</span>
                                    <span class="event-month">{{ $event->event_date->format('M') }}</span>
                                </div>
                                <span class="badge badge-neutral">Selesai</span>
                            </div>

                            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--slate-900); margin: 0.75rem 0 0.5rem;">
                                {{ $event->title }}
                            </h3>

                            <p style="font-size: 0.85rem; color: var(--slate-600); line-height: 1.5; margin-bottom: 1rem;">
                                {{ Str::limit($event->description, 100) }}
                            </p>
                        </div>

                        <div style="font-size: 0.8rem; color: var(--slate-500);">
                            <div><strong>Lokasi:</strong> {{ $event->location_venue }}</div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; color: var(--slate-400); font-size: 0.9rem;">
                        Belum ada riwayat kegiatan terdahulu yang diarsipkan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
