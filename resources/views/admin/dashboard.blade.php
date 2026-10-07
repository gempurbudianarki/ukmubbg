@extends('admin.layouts.app')

@section('title', 'Ringkasan Dashboard - UKM CMS')
@section('page_title', 'Ringkasan Dashboard')

@section('content')
<!-- Top Analytics Cards (Symmetrical Grid) -->
<div class="admin-stat-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #2563eb;">Total Pendaftar</span>
            <div class="admin-stat-icon" style="background: #eff6ff; color: #2563eb;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
        </div>
        <div class="admin-stat-value">{{ $totalApplicants }}</div>
        <div class="admin-stat-sub">Calon Anggota Terdaftar</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #d97706;">Menunggu Seleksi</span>
            <div class="admin-stat-icon" style="background: #fffbeb; color: #d97706;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #d97706;">{{ $pendingApplicants }}</div>
        <div class="admin-stat-sub">Perlu ditinjau pengurus</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #059669;">Lolos Seleksi</span>
            <div class="admin-stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #059669;">{{ $acceptedApplicants }}</div>
        <div class="admin-stat-sub">Diterima di 4 Divisi</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #10b981;">Anggota Aktif</span>
            <div class="admin-stat-icon" style="background: #d1fae5; color: #10b981;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #10b981;">{{ $totalMembers }}</div>
        <div class="admin-stat-sub">
            <a href="{{ route('admin.members.index') }}" style="color: #2563eb; font-weight: 700;">Lihat Direktori &rarr;</a>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #8b5cf6;">Sesi Presensi</span>
            <div class="admin-stat-icon" style="background: #ede9fe; color: #8b5cf6;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #8b5cf6;">{{ $totalSessions }}</div>
        <div class="admin-stat-sub">
            <a href="{{ route('admin.attendance.index') }}" style="color: #8b5cf6; font-weight: 700;">Rekap Kehadiran &rarr;</a>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #0284c7;">Publikasi & Karya</span>
            <div class="admin-stat-icon" style="background: #e0f2fe; color: #0284c7;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
            </div>
        </div>
        <div class="admin-stat-value">{{ $totalPosts }}</div>
        <div class="admin-stat-sub">Artikel di Kanal UKM</div>
    </div>
</div>

<!-- Super Admin Controls & Recruitment Switcher -->
@if ($isSuperAdmin)
    <div class="admin-clay-card" style="margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.25rem;">
        <div>
            <div style="font-weight: 800; font-size: 1.15rem; color: var(--slate-900); margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>Status Pendaftaran Terpusat (Open Recruitment)</span>
                <span class="badge {{ $recruitmentStatus === 'open' ? 'badge-success' : 'badge-danger' }}" style="box-shadow: var(--clay-pill);">
                    {{ $recruitmentStatus === 'open' ? 'AKTIF' : 'DITUTUP' }}
                </span>
            </div>
            <div style="font-size: 0.875rem; color: var(--slate-600);">
                Calon anggota dapat mendaftar mandiri saat berstatus dibuka.
            </div>
        </div>
        <form action="{{ route('admin.recruitment.toggle') }}" method="POST">
            @csrf
            <button type="submit" class="btn {{ $recruitmentStatus === 'open' ? 'btn-danger' : 'btn-primary' }}" style="border-radius: var(--radius-full); padding: 0.75rem 1.75rem; box-shadow: var(--clay-btn);">
                {{ $recruitmentStatus === 'open' ? 'Tutup Pendaftaran Sekarang' : 'Buka Pendaftaran Sekarang' }}
            </button>
        </form>
    </div>
@endif

<!-- Two Columns: Symmetrical Divisions Stats & Recent Applicants -->
<div class="admin-grid-2">
    <!-- Division Stats -->
    <div class="admin-clay-card" style="display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                Statistik Divisi
            </h3>
            <a href="{{ route('admin.divisions.index') }}" style="font-size: 0.825rem; font-weight: 700; color: #2563eb;">
                Profil 4 Divisi &rarr;
            </a>
        </div>
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Bidang Divisi</th>
                        <th style="text-align: center;">Artikel</th>
                        <th style="text-align: center;">Pendaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($divisionsStats as $ds)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: var(--slate-900);">
                                    {{ $ds['name'] }}
                                </div>
                                <div style="font-size: 0.775rem; color: var(--slate-500);">
                                    Kadiv: {{ $ds['leader'] }}
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge badge-neutral" style="box-shadow: var(--clay-pill);">{{ $ds['posts_count'] }}</span>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge badge-info" style="box-shadow: var(--clay-pill);">{{ $ds['applicants_count'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Applicants -->
    <div class="admin-clay-card" style="display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                Pendaftar Terbaru
            </h3>
            <a href="{{ route('admin.recruitment.index') }}" style="font-size: 0.825rem; font-weight: 700; color: #2563eb;">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nama & NIM</th>
                        <th>Divisi Pilihan</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentApplicants as $applicant)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: var(--slate-900);">
                                    {{ $applicant->full_name }}
                                </div>
                                <div style="font-family: var(--font-mono); font-size: 0.775rem; color: var(--slate-400);">
                                    {{ $applicant->nim }}
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.825rem; font-weight: 600; color: var(--slate-700);">
                                    {{ $applicant->firstChoiceDivision->name }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge {{ $applicant->status_badge_class }}" style="font-size: 0.75rem; box-shadow: var(--clay-pill);">
                                    {{ $applicant->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--slate-400); padding: 2.5rem;">
                                Belum ada pendaftar baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
