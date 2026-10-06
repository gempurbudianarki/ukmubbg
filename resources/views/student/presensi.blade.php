@extends('student.layouts.app')

@section('title', 'Presensi & Kehadiran Pertemuan')
@section('page_title', 'Presensi & Kehadiran Pertemuan')

@section('styles')
<style>
    .presensi-metric-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .presensi-metric-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .presensi-table-card {
        background: #ffffff;
        border-radius: var(--radius-xl);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
</style>
@endsection

@section('content')

<!-- Header Info -->
<div style="background: #ffffff; border-radius: var(--radius-xl); padding: 1.75rem 2rem; border: 1px solid #e2e8f0; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem;">
            Buku Presensi & Kehadiran: {{ $division?->name ?? 'Divisi UKM' }}
        </h2>
        <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
            Pantau keaktifan kehadiran Anda dalam setiap agenda riset dan workshop resmi divisi.
        </p>
    </div>
    <div style="text-align: right;">
        <span style="font-size: 1.75rem; font-weight: 800; color: #0284c7;">
            {{ $attendanceStats['percentage'] }}%
        </span>
        <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">KEHADIRAN KUMULATIF</div>
    </div>
</div>

<!-- 4 Metric Cards -->
<div class="presensi-metric-grid">
    <div class="presensi-metric-card">
        <div style="font-size: 0.775rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Total Pertemuan</div>
        <div style="font-size: 1.65rem; font-weight: 800; color: #0f172a; margin-top: 0.35rem;">
            {{ $attendanceStats['total_sessions'] }}
        </div>
        <small style="color: #94a3b8; font-size: 0.75rem;">Sesi resmi diselenggarakan</small>
    </div>

    <div class="presensi-metric-card">
        <div style="font-size: 0.775rem; color: #16a34a; font-weight: 700; text-transform: uppercase;">Hadir Tepat Waktu</div>
        <div style="font-size: 1.65rem; font-weight: 800; color: #16a34a; margin-top: 0.35rem;">
            {{ $attendanceStats['attended_count'] }}
        </div>
        <small style="color: #94a3b8; font-size: 0.75rem;">Sesi terkonfirmasi hadir</small>
    </div>

    <div class="presensi-metric-card">
        <div style="font-size: 0.775rem; color: #0284c7; font-weight: 700; text-transform: uppercase;">Izin Berhalangan</div>
        <div style="font-size: 1.65rem; font-weight: 800; color: #0284c7; margin-top: 0.35rem;">
            {{ $attendanceStats['permission_count'] }}
        </div>
        <small style="color: #94a3b8; font-size: 0.75rem;">Disertai konfirmasi pengurus</small>
    </div>

    <div class="presensi-metric-card">
        <div style="font-size: 0.775rem; color: #dc2626; font-weight: 700; text-transform: uppercase;">Sakit / Alpa</div>
        <div style="font-size: 1.65rem; font-weight: 800; color: #dc2626; margin-top: 0.35rem;">
            {{ $attendanceStats['sick_count'] + $attendanceStats['absent_count'] }}
        </div>
        <small style="color: #94a3b8; font-size: 0.75rem;">Tidak dapat hadir</small>
    </div>
</div>

<!-- Table of Attendance Logs -->
<div class="presensi-table-card">
    <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">
            <i class="fas fa-list-check" style="color: #0284c7; margin-right: 0.4rem;"></i>
            Riwayat Seluruh Sesi Pertemuan Divisi
        </h3>
        <span style="font-size: 0.775rem; color: #64748b;">
            Menampilkan {{ $attendanceStats['all_logs']->count() }} catatan kehadiran
        </span>
    </div>

    @if ($attendanceStats['all_logs']->count() > 0)
        <div class="table-responsive">
            <table class="table" style="font-size: 0.875rem; margin-bottom: 0;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 1rem 1.25rem; font-weight: 700; color: #475569;">Topik & Materi Pembelajaran</th>
                        <th style="padding: 1rem 1.25rem; font-weight: 700; color: #475569;">Tanggal & Waktu</th>
                        <th style="padding: 1rem 1.25rem; font-weight: 700; color: #475569;">Lokasi Ruangan</th>
                        <th style="padding: 1rem 1.25rem; font-weight: 700; color: #475569;">Status Anda</th>
                        <th style="padding: 1rem 1.25rem; font-weight: 700; color: #475569;">Catatan / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendanceStats['all_logs'] as $log)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 1rem 1.25rem;">
                                <strong style="color: #0f172a; font-size: 0.9rem; display: block;">
                                    {{ $log->session?->title }}
                                </strong>
                                @if ($log->session?->topic_material)
                                    <span style="font-size: 0.775rem; color: #64748b; display: block; margin-top: 0.2rem;">
                                        Materi: {{ $log->session->topic_material }}
                                    </span>
                                @endif
                                @if ($log->session?->instructor_name)
                                    <span style="font-size: 0.75rem; color: #0284c7; font-weight: 600;">
                                        Pemateri: {{ $log->session->instructor_name }}
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 1rem 1.25rem; color: #334155;">
                                <div style="font-weight: 600;">
                                    {{ \Carbon\Carbon::parse($log->session?->session_date)->translatedFormat('l, d F Y') }}
                                </div>
                                <small style="color: #64748b;">
                                    {{ substr($log->session?->time_start, 0, 5) }} - {{ substr($log->session?->time_end, 0, 5) }} WIB
                                </small>
                            </td>
                            <td style="padding: 1rem 1.25rem; color: #475569;">
                                <i class="fas fa-location-dot" style="color: #94a3b8; margin-right: 0.3rem;"></i>
                                {{ $log->session?->location }}
                            </td>
                            <td style="padding: 1rem 1.25rem;">
                                <span class="badge {{ $log->status_badge }}" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td style="padding: 1rem 1.25rem; color: #64748b; font-size: 0.825rem;">
                                {{ $log->notes ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 3rem 1.5rem; color: #64748b;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">
                <i class="fas fa-clipboard-question"></i>
            </div>
            <h4 style="font-size: 1.05rem; font-weight: 700; color: #1e293b; margin-bottom: 0.35rem;">
                Belum Ada Catatan Presensi
            </h4>
            <p style="font-size: 0.85rem; color: #64748b; max-width: 450px; margin: 0 auto;">
                Sesi absensi akan otomatis muncul di sini setelah ketua divisi membuka dan mencatat kehadiran sesi pembelajaran resmi.
            </p>
        </div>
    @endif
</div>

@endsection
