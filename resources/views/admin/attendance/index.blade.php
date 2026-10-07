@extends('admin.layouts.app')

@section('title', 'Sistem Presensi Kegiatan & Rapat')
@section('page_title', 'Presensi & Absensi Kegiatan')

@section('content')
<!-- Header Box -->
<div class="admin-welcome-banner" style="margin-bottom: 2rem; padding: 1.5rem 2rem;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: 14px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--clay-pill); flex-shrink: 0;">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <div>
            <h1 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.2rem 0;">
                Sistem Presensi & Berita Acara (BAP)
            </h1>
            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                Monitoring kehadiran rapat pleno, riset divisi, passcode check-in, dan cetak dokumen BAP resmi.
            </p>
        </div>
    </div>

    <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary" style="box-shadow: var(--clay-btn); display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 9999px; padding: 0.65rem 1.35rem;">
        <i class="fas fa-plus"></i>
        <span>Buat Sesi Absensi Baru</span>
    </a>
</div>

<!-- Filter Toolbar (Clay Debossed) -->
<form method="GET" action="{{ route('admin.attendance.index') }}" class="admin-filter-bar">
    <div style="flex: 2; min-width: 220px;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul sesi atau lokasi..." class="admin-filter-input" style="width: 100%;">
    </div>

    @if ($user->isSuperAdmin())
        <select name="division" class="admin-filter-input" style="flex: 1; min-width: 180px;" onchange="this.form.submit()">
            <option value="">Semua Agenda & Divisi</option>
            <option value="pleno" {{ request('division') === 'pleno' ? 'selected' : '' }}>Agenda Pleno (Semua Anggota)</option>
            @foreach ($divisions as $div)
                <option value="{{ $div->id }}" {{ request('division') == $div->id ? 'selected' : '' }}>
                    {{ $div->name }}
                </option>
            @endforeach
        </select>
    @endif

    <button type="submit" class="btn btn-primary btn-sm" style="box-shadow: var(--clay-btn); padding: 0.65rem 1.25rem;">
        Cari Sesi
    </button>
    @if (request()->hasAny(['q', 'division']))
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">
            Reset Filter
        </a>
    @endif
</form>

<!-- Symmetrical Sessions Grid (3 Columns) -->
<div class="admin-grid-3">
    @forelse ($sessions as $session)
        <div class="admin-clay-card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 1.5rem 1.65rem;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.85rem; gap: 0.5rem;">
                    <div style="display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap;">
                        @if ($session->division)
                            <span class="badge" style="background: {{ $session->division->color_accent }}15; color: {{ $session->division->color_accent }}; font-size: 0.725rem; box-shadow: var(--clay-pill);">
                                {{ $session->division->name }}
                            </span>
                        @else
                            <span class="badge" style="background: #0f172a; color: #ffffff; font-size: 0.725rem; box-shadow: var(--clay-pill);">
                                Agenda Pleno UKM
                            </span>
                        @endif

                        <span class="badge {{ $session->status === 'open' ? 'badge-success' : 'badge-neutral' }}" style="font-size: 0.7rem; box-shadow: var(--clay-pill);">
                            {{ $session->status === 'open' ? 'DIBUKA' : 'DITUTUP' }}
                        </span>
                    </div>

                    <span style="font-size: 0.775rem; font-family: var(--font-mono); color: var(--slate-500); font-weight: 700;">
                        {{ $session->session_date->format('d M Y') }}
                    </span>
                </div>

                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.6rem; line-height: 1.35;">
                    <a href="{{ route('admin.attendance.show', $session) }}" style="color: inherit;">
                        {{ $session->title }}
                    </a>
                </h3>

                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.85rem;">
                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 700;">Passcode Presensi:</span>
                    <span style="font-family: var(--font-mono); font-size: 0.825rem; font-weight: 800; color: #0284c7; background: #e0f2fe; padding: 0.2rem 0.6rem; border-radius: var(--radius-xs); box-shadow: var(--clay-pill);">
                        {{ $session->passcode ?? '-' }}
                    </span>
                </div>

                <div style="font-size: 0.825rem; color: var(--slate-500); display: flex; flex-direction: column; gap: 0.25rem; margin-bottom: 1.15rem;">
                    <div>
                        <strong style="color: var(--slate-700);">Waktu:</strong> {{ substr($session->time_start, 0, 5) }} {{ $session->time_end ? '- ' . substr($session->time_end, 0, 5) : '' }} WIB
                    </div>
                    <div>
                        <strong style="color: var(--slate-700);">Lokasi:</strong> {{ $session->location }}
                    </div>
                </div>

                <!-- Attendance Rate Bar -->
                @php
                    $rate = $session->attendance_rate;
                @endphp
                <div style="background: #f8fafc; border-radius: var(--radius-md); padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; box-shadow: var(--clay-pill);">
                    <div>
                        <div style="font-size: 0.7rem; color: var(--slate-400); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Kehadiran</div>
                        <div style="font-weight: 800; font-size: 1.15rem; font-family: var(--font-mono); color: {{ $rate >= 75 ? '#059669' : ($rate >= 50 ? '#d97706' : '#dc2626') }};">
                            {{ $rate }}%
                        </div>
                    </div>
                    <div style="font-size: 0.8rem; color: var(--slate-600); text-align: right;">
                        <strong style="color: var(--slate-900);">{{ $session->logs->where('status', 'hadir')->count() }}</strong> / {{ $session->logs->count() }} Terdata
                    </div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; pt-3 border-top: 1px solid var(--slate-100);">
                <form action="{{ route('admin.attendance.destroy', $session) }}" method="POST" onsubmit="return confirm('Hapus sesi absensi ini berserta seluruh log kehadiran?');" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); background: #ffffff; box-shadow: var(--clay-btn); font-size: 0.75rem;">
                        Hapus
                    </button>
                </form>

                <div style="display: flex; gap: 0.4rem; align-items: center;">
                    <a href="{{ route('admin.attendance.bap', $session) }}" target="_blank" class="btn btn-outline btn-sm" style="font-size: 0.75rem; background: #ffffff; box-shadow: var(--clay-btn);" title="Cetak Berita Acara Presensi Resmi">
                        Cetak BAP
                    </a>
                    <a href="{{ route('admin.attendance.show', $session) }}" class="btn btn-primary btn-sm" style="font-size: 0.775rem; box-shadow: var(--clay-btn);">
                        Detail &rarr;
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; background: #ffffff; border-radius: var(--radius-lg); box-shadow: var(--clay-card); padding: 4rem 2rem; text-align: center; color: var(--slate-500);">
            <div style="font-size: 1.15rem; font-weight: 800; color: var(--slate-700); margin-bottom: 0.5rem;">
                Belum ada sesi absensi yang dibuat.
            </div>
            <p style="font-size: 0.875rem; margin-bottom: 1.5rem; color: var(--slate-400);">
                Buat sesi rapat pleno atau bootcamp divisi untuk mulai merekap kehadiran anggota secara rapi.
            </p>
            <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary btn-sm" style="box-shadow: var(--clay-btn);">
                Buat Sesi Pertama
            </a>
        </div>
    @endforelse
</div>

@if ($sessions->hasPages())
    <div style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $sessions->links() }}
    </div>
@endif
@endsection
