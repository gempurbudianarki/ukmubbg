@extends('admin.layouts.app')

@section('title', 'Kelola Agenda & Workshop - UKM CMS')
@section('page_title', 'Kelola Agenda & Workshop')

@section('content')
<div class="glass-panel" style="padding: 1.75rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900);">
                Daftar Pelatihan & Acara UKM
            </h3>
            <p style="font-size: 0.85rem; color: var(--slate-500);">
                Kelola jadwal bootcamp, seminar, dan workshop divisi.
            </p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">
            + Tambah Agenda Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Judul Kegiatan</th>
                    <th>Tanggal & Waktu</th>
                    <th>Tipe & Lokasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: var(--slate-900);">
                                {{ $event->title }}
                            </div>
                            <div style="font-size: 0.775rem; color: var(--slate-400);">
                                {{ $event->division ? $event->division->name : 'Umum' }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--slate-800);">
                                {{ $event->event_date->format('d M Y') }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--slate-500);">
                                {{ substr($event->time_start, 0, 5) }} WIB
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-neutral" style="text-transform: capitalize; margin-bottom: 0.2rem;">
                                {{ $event->location_type }}
                            </span>
                            <div style="font-size: 0.775rem; color: var(--slate-600);">
                                {{ $event->location_venue }}
                            </div>
                        </td>
                        <td>
                            @if ($event->status === 'upcoming')
                                <span class="badge badge-success">Akan Datang</span>
                            @elseif ($event->status === 'completed')
                                <span class="badge badge-neutral">Selesai</span>
                            @else
                                <span class="badge badge-danger">Batal</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-outline btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus agenda ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--slate-400); padding: 2rem;">
                            Belum ada agenda kegiatan yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($events->hasPages())
        <div style="margin-top: 1.5rem;">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
