@extends('admin.layouts.app')

@section('title', 'Sistem Presensi Kegiatan & Rapat')

@section('content')
<div style="padding-bottom: 3rem;">
    <!-- Top Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.025em; margin-bottom: 0.35rem;">
                Presensi & Absensi Kegiatan
            </h1>
            <p style="color: var(--slate-500); font-size: 0.925rem;">
                Monitoring kehadiran rapat pleno, bootcamp divisi, dan evaluasi berkala anggota UKM.
            </p>
        </div>

        <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary" style="box-shadow: 0 4px 14px rgba(37,99,235,0.25);">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span>Buat Sesi Absensi Baru</span>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div style="background: #ffffff; border: 1px solid var(--slate-200); border-radius: var(--radius-lg); padding: 1.25rem; margin-bottom: 1.5rem; box-shadow: var(--shadow-subtle);">
        <form method="GET" action="{{ route('admin.attendance.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; justify-content: space-between;">
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; flex: 1;">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul sesi atau lokasi..." class="form-control" style="max-width: 280px; font-size: 0.875rem;">

                @if ($user->isSuperAdmin())
                    <select name="division" class="form-control" style="max-width: 220px; font-size: 0.875rem;" onchange="this.form.submit()">
                        <option value="">Semua Agenda & Divisi</option>
                        <option value="pleno" {{ request('division') === 'pleno' ? 'selected' : '' }}>Agenda Pleno (Semua Anggota)</option>
                        @foreach ($divisions as $div)
                            <option value="{{ $div->id }}" {{ request('division') == $div->id ? 'selected' : '' }}>
                                {{ $div->name }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                @if (request()->hasAny(['q', 'division']))
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline btn-sm">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Sessions Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
        @forelse ($sessions as $session)
            <div style="background: #ffffff; border: 1px solid var(--slate-200); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-card); display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s ease, box-shadow 0.2s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                <div style="padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                        @if ($session->division)
                            <span class="badge" style="background: {{ $session->division->color_accent }}15; color: {{ $session->division->color_accent }}; font-size: 0.725rem;">
                                {{ $session->division->name }}
                            </span>
                        @else
                            <span class="badge badge-neutral" style="font-size: 0.725rem; background: #0f172a; color: #ffffff;">
                                Agenda Pleno (Umum)
                            </span>
                        @endif

                        <span style="font-size: 0.775rem; font-family: var(--font-mono); color: var(--slate-500); font-weight: 600;">
                            {{ $session->session_date->format('d M Y') }}
                        </span>
                    </div>

                    <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.5rem; line-height: 1.35;">
                        <a href="{{ route('admin.attendance.show', $session) }}" style="color: inherit;">
                            {{ $session->title }}
                        </a>
                    </h3>

                    <div style="font-size: 0.825rem; color: var(--slate-500); display: flex; flex-direction: column; gap: 0.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <strong>Waktu:</strong> {{ substr($session->time_start, 0, 5) }} {{ $session->time_end ? '- ' . substr($session->time_end, 0, 5) : '' }} WIB
                        </div>
                        <div>
                            <strong>Lokasi:</strong> {{ $session->location }}
                        </div>
                    </div>

                    <!-- Attendance Rate Strip -->
                    @php
                        $rate = $session->attendance_rate;
                    @endphp
                    <div style="background: var(--slate-50); border-radius: var(--radius-md); padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-size: 0.75rem; color: var(--slate-500); text-transform: uppercase; font-weight: 600;">Tingkat Kehadiran</div>
                            <div style="font-weight: 800; font-size: 1.1rem; color: {{ $rate >= 75 ? '#059669' : ($rate >= 50 ? '#d97706' : '#dc2626') }};">
                                {{ $rate }}%
                            </div>
                        </div>
                        <div style="font-size: 0.8rem; color: var(--slate-600); text-align: right;">
                            <strong>{{ $session->logs->where('status', 'hadir')->count() }}</strong> / {{ $session->logs->count() }} Anggota Hadir
                        </div>
                    </div>
                </div>

                <div style="padding: 1rem 1.5rem; background: var(--slate-50); border-top: 1px solid var(--slate-100); display: flex; justify-content: space-between; align-items: center;">
                    <form action="{{ route('admin.attendance.destroy', $session) }}" method="POST" onsubmit="return confirm('Hapus sesi absensi ini berserta seluruh log kehadiran?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); border-color: rgba(239, 68, 68, 0.3); font-size: 0.775rem;">
                            Hapus
                        </button>
                    </form>

                    <a href="{{ route('admin.attendance.show', $session) }}" class="btn btn-primary btn-sm" style="font-size: 0.8rem;">
                        Buka Lembar Presensi &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; background: #ffffff; border: 1px solid var(--slate-200); border-radius: var(--radius-lg); padding: 4rem 2rem; text-align: center; color: var(--slate-500);">
                <div style="font-size: 1.1rem; font-weight: 700; color: var(--slate-700); margin-bottom: 0.5rem;">
                    Belum ada sesi absensi yang dibuat.
                </div>
                <p style="font-size: 0.875rem; margin-bottom: 1.5rem;">
                    Buat sesi rapat pleno atau workshop untuk mulai merekap kehadiran anggota secara rapi.
                </p>
                <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary btn-sm">
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
</div>
@endsection
