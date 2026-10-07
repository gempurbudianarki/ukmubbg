@extends('admin.layouts.app')

@section('title', 'Kelola Agenda & Workshop - UKM CMS')
@section('page_title', 'Kelola Agenda & Workshop')

@section('content')
<!-- Header Box -->
<div class="admin-header-box">
    <div>
        <h1 class="admin-header-title">
            <i class="fas fa-calendar-days" style="color: #2563eb; font-size: 1.5rem;"></i>
            <span>Daftar Pelatihan & Acara UKM</span>
        </h1>
        <p class="admin-header-desc">
            {{ auth()->user()->isSuperAdmin() ? 'Kelola jadwal bootcamp spesialisasi, seminar teknologi, dan workshop 4 divisi UKM.' : 'Kelola agenda pelatihan, workshop, dan kegiatan khusus divisi ' . (auth()->user()->division->name ?? '') }}
        </p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary" style="box-shadow: var(--clay-btn); display: inline-flex; align-items: center; gap: 0.45rem;">
        <i class="fas fa-plus"></i>
        <span>Tambah Agenda Baru</span>
    </a>
</div>

<!-- Filter Toolbar (Clay Debossed) -->
<form method="GET" action="{{ route('admin.events.index') }}" class="admin-filter-bar">
    <div style="flex: 2; min-width: 220px;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama agenda / lokasi..." class="admin-filter-input" style="width: 100%;">
    </div>
    
    <select name="division_id" class="admin-filter-input" style="flex: 1; min-width: 180px;" onchange="this.form.submit()">
        <option value="">Semua Divisi / Umum</option>
        @foreach ($divisions as $div)
            <option value="{{ $div->id }}" {{ request('division_id') == $div->id ? 'selected' : '' }}>
                {{ $div->name }}
            </option>
        @endforeach
    </select>

    <select name="status" class="admin-filter-input" style="flex: 1; min-width: 160px;" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Batal</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm" style="box-shadow: var(--clay-btn); padding: 0.65rem 1.25rem;">
        Filter
    </button>
    @if (request()->hasAny(['q', 'division_id', 'status']))
        <a href="{{ route('admin.events.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">
            Reset
        </a>
    @endif
</form>

<!-- Events Table Card (Flat Symmetrical Clay) -->
<div class="admin-clay-card-flat">
    <div class="admin-table-container">
        <table class="admin-table admin-table-events">
            <thead>
                <tr>
                    <th>Judul Kegiatan & Divisi</th>
                    <th>Jadwal Pelaksanaan</th>
                    <th>Tipe & Lokasi</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: var(--slate-900); font-size: 0.95rem;">
                                {{ $event->title }}
                            </div>
                            <div style="margin-top: 0.25rem;">
                                @if ($event->division)
                                    <span class="badge" style="background: {{ $event->division->color_accent }}15; color: {{ $event->division->color_accent }}; font-size: 0.725rem; box-shadow: var(--clay-pill);">
                                        {{ $event->division->name }}
                                    </span>
                                @else
                                    <span class="badge badge-neutral" style="font-size: 0.725rem; box-shadow: var(--clay-pill);">Pleno / Umum</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--slate-800);">
                                {{ $event->event_date->format('d M Y') }}
                            </div>
                            <div style="font-size: 0.775rem; color: var(--slate-500); font-family: var(--font-mono);">
                                {{ substr($event->time_start, 0, 5) }} WIB
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-neutral" style="text-transform: capitalize; margin-bottom: 0.2rem; box-shadow: var(--clay-pill); font-size: 0.725rem;">
                                {{ $event->location_type }}
                            </span>
                            <div style="font-size: 0.8rem; color: var(--slate-600); font-weight: 500;">
                                {{ $event->location_venue }}
                            </div>
                        </td>
                        <td style="text-align: center;">
                            @if ($event->status === 'upcoming')
                                <span class="badge badge-success" style="box-shadow: var(--clay-pill); font-size: 0.75rem;">Akan Datang</span>
                            @elseif ($event->status === 'completed')
                                <span class="badge badge-neutral" style="box-shadow: var(--clay-pill); font-size: 0.75rem;">Selesai</span>
                            @else
                                <span class="badge badge-danger" style="box-shadow: var(--clay-pill); font-size: 0.75rem;">Batal</span>
                            @endif
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 0.35rem; align-items: center; justify-content: flex-end;">
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); font-size: 0.75rem;">
                                    Edit
                                </a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus agenda ini?')" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); background: #ffffff; box-shadow: var(--clay-btn); font-size: 0.75rem;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--slate-400); padding: 3.5rem;">
                            Belum ada agenda kegiatan yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($events->total() > 0)
        <div style="padding: 1.25rem 1.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8); background: #f8fafc;">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
