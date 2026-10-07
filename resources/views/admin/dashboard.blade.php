@extends('admin.layouts.app')

@section('title', 'Ringkasan Dashboard - UKM CMS')
@section('page_title', 'Ringkasan Dashboard')

@section('content')
<!-- Welcome Executive Banner -->
<div class="admin-welcome-banner">
    <div style="display: flex; align-items: center; gap: 1.25rem;">
        <div style="width: 58px; height: 58px; border-radius: 18px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; box-shadow: var(--clay-pill); flex-shrink: 0;">
            <i class="fas fa-crown"></i>
        </div>
        <div>
            <h1 style="font-size: 1.4rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.25rem 0; letter-spacing: -0.01em;">
                Selamat Datang di Command Center, {{ $user->name }}!
            </h1>
            <p style="font-size: 0.875rem; color: var(--slate-500); margin: 0; line-height: 1.5;">
                Kendali terpusat seluruh pendaftaran, direktori anggota, sesi presensi riset, dan publikasi 4 divisi UKM Ilmu Komputer.
            </p>
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 0.85rem; flex-wrap: wrap;">
        <div style="background: var(--bg-body); padding: 0.5rem 1rem; border-radius: 9999px; box-shadow: var(--clay-input); font-size: 0.8rem; font-weight: 700; color: var(--slate-600); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-calendar-day" style="color: #2563eb;"></i>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
        <span class="badge {{ $recruitmentStatus === 'open' ? 'badge-success' : 'badge-danger' }}" style="padding: 0.55rem 1.15rem; font-size: 0.8rem; box-shadow: var(--clay-pill); font-weight: 800; display: inline-flex; align-items: center; gap: 0.45rem;">
            <i class="fas fa-circle-dot" style="font-size: 0.65rem;"></i>
            <span>Oprec: {{ $recruitmentStatus === 'open' ? 'DIBUKA' : 'DITUTUP' }}</span>
        </span>
    </div>
</div>

<!-- Quick Action Command Bar -->
<div class="admin-quick-actions">
    <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--slate-400); margin-right: 0.25rem;">
        <i class="fas fa-bolt" style="color: #f59e0b;"></i> Aksi Cepat:
    </span>
    <a href="{{ route('admin.members.index') }}" class="admin-quick-btn">
        <i class="fas fa-user-plus"></i>
        <span>Kelola Anggota</span>
    </a>
    <a href="{{ route('admin.attendance.create') }}" class="admin-quick-btn">
        <i class="fas fa-clipboard-check"></i>
        <span>Buka Sesi Presensi</span>
    </a>
    <a href="{{ route('admin.announcements.index') }}" class="admin-quick-btn">
        <i class="fas fa-bullhorn"></i>
        <span>Buat Pengumuman</span>
    </a>
    @if ($isSuperAdmin)
        <a href="{{ route('admin.recruitment.settings') }}" class="admin-quick-btn">
            <i class="fas fa-sliders"></i>
            <span>Pengaturan Gelombang</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="admin-quick-btn">
            <i class="fas fa-user-shield"></i>
            <span>Kelola Hak Akses</span>
        </a>
    @endif
</div>

<!-- Symmetrical 4-Column x 2-Row Metric Grid (8 Cards) -->
<div class="admin-stat-grid">
    <!-- Card 1: Total Pendaftar -->
    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #0284c7;">Total Pendaftar</span>
            <div class="admin-stat-icon" style="background: #e0f2fe; color: #0284c7;">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
        <div class="admin-stat-value">{{ $totalApplicants }}</div>
        <div class="admin-stat-sub">
            <a href="{{ route('admin.recruitment.index') }}" style="color: #0284c7; font-weight: 700; text-decoration: none;">
                Lihat Semua Pelamar &rarr;
            </a>
        </div>
    </div>

    <!-- Card 2: Menunggu Seleksi -->
    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #d97706;">Menunggu Seleksi</span>
            <div class="admin-stat-icon" style="background: #fef3c7; color: #d97706;">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #d97706;">{{ $pendingApplicants }}</div>
        <div class="admin-stat-sub">
            <a href="{{ route('admin.recruitment.index', ['status' => 'pending']) }}" style="color: #d97706; font-weight: 700; text-decoration: none;">
                Perlu Ditinjau &rarr;
            </a>
        </div>
    </div>

    <!-- Card 3: Lolos Seleksi -->
    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #059669;">Lolos Diterima</span>
            <div class="admin-stat-icon" style="background: #ecfdf5; color: #059669;">
                <i class="fas fa-circle-check"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #059669;">{{ $acceptedApplicants }}</div>
        <div class="admin-stat-sub">
            <a href="{{ route('admin.recruitment.index', ['status' => 'accepted']) }}" style="color: #059669; font-weight: 700; text-decoration: none;">
                Siap Diangkat Anggota &rarr;
            </a>
        </div>
    </div>

    <!-- Card 4: Anggota Aktif -->
    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #10b981;">Anggota Aktif</span>
            <div class="admin-stat-icon" style="background: #d1fae5; color: #10b981;">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #10b981;">{{ $totalMembers }}</div>
        <div class="admin-stat-sub">
            <a href="{{ route('admin.members.index') }}" style="color: #10b981; font-weight: 700; text-decoration: none;">
                Direktori Anggota &rarr;
            </a>
        </div>
    </div>

    <!-- Card 5: Sesi Presensi Riset -->
    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #8b5cf6;">Sesi Presensi</span>
            <div class="admin-stat-icon" style="background: #ede9fe; color: #8b5cf6;">
                <i class="fas fa-clipboard-check"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #8b5cf6;">{{ $totalSessions }}</div>
        <div class="admin-stat-sub">
            <a href="{{ route('admin.attendance.index') }}" style="color: #8b5cf6; font-weight: 700; text-decoration: none;">
                Rekap BAP Pertemuan &rarr;
            </a>
        </div>
    </div>

    <!-- Card 6: Karya Mahasiswa -->
    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #6366f1;">Karya Mahasiswa</span>
            <div class="admin-stat-icon" style="background: #e0e7ff; color: #6366f1;">
                <i class="fas fa-laptop-code"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #6366f1;">
            {{ \App\Models\Project::count() }}
        </div>
        <div class="admin-stat-sub">
            <a href="{{ route('admin.projects.index') }}" style="color: #6366f1; font-weight: 700; text-decoration: none;">
                Moderasi Karya &rarr;
            </a>
        </div>
    </div>

    <!-- Card 7: Publikasi & Berita -->
    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #0ea5e9;">Publikasi Kanal</span>
            <div class="admin-stat-icon" style="background: #e0f2fe; color: #0ea5e9;">
                <i class="fas fa-newspaper"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #0ea5e9;">{{ $totalPosts }}</div>
        <div class="admin-stat-sub">
            <a href="{{ route('admin.posts.index') }}" style="color: #0ea5e9; font-weight: 700; text-decoration: none;">
                Kelola Artikel &rarr;
            </a>
        </div>
    </div>

    <!-- Card 8: Saklar Status Rekrutmen Live -->
    @if ($isSuperAdmin)
        <div class="admin-stat-card" style="border: 1.5px solid {{ $recruitmentStatus === 'open' ? '#a7f3d0' : '#fecaca' }};">
            <div class="admin-stat-header">
                <span class="admin-stat-label" style="color: {{ $recruitmentStatus === 'open' ? '#059669' : '#dc2626' }};">
                    <i class="fas fa-power-off"></i> Status Pendaftaran Terpusat
                </span>
                <span class="badge {{ $recruitmentStatus === 'open' ? 'badge-success' : 'badge-danger' }}" style="box-shadow: var(--clay-pill); font-size: 0.7rem;">
                    {{ strtoupper($recruitmentStatus) }}
                </span>
            </div>
            <form action="{{ route('admin.recruitment.toggle') }}" method="POST" style="margin-top: 0.25rem;">
                @csrf
                <button type="submit" class="btn {{ $recruitmentStatus === 'open' ? 'btn-danger' : 'btn-primary' }} btn-sm" style="width: 100%; border-radius: 9999px; box-shadow: var(--clay-btn); font-size: 0.775rem; padding: 0.5rem 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.4rem;">
                    <i class="fas {{ $recruitmentStatus === 'open' ? 'fa-lock' : 'fa-unlock' }}"></i>
                    <span>{{ $recruitmentStatus === 'open' ? 'Tutup Pendaftaran' : 'Buka Pendaftaran' }}</span>
                </button>
            </form>
            <div class="admin-stat-sub" style="margin-top: 0.45rem; text-align: center; font-size: 0.725rem;">
                Saklar kontrol pendaftaran live
            </div>
        </div>
    @else
        <div class="admin-stat-card">
            <div class="admin-stat-header">
                <span class="admin-stat-label" style="color: #64748b;">Lingkup Divisi</span>
                <div class="admin-stat-icon" style="background: #f1f5f9; color: #64748b;">
                    <i class="fas fa-shield"></i>
                </div>
            </div>
            <div class="admin-stat-value" style="font-size: 1.45rem;">
                {{ auth()->user()->division->name ?? 'Divisi' }}
            </div>
            <div class="admin-stat-sub">Admin Fungsional Terikat</div>
        </div>
    @endif
</div>

<!-- Symmetrical 2 Columns: 50% Statistik Divisi vs 50% Pendaftar Terbaru -->
<div class="admin-grid-2">
    <!-- Division Performance Stats -->
    <div class="admin-clay-card" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <div style="width: 34px; height: 34px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; box-shadow: var(--clay-pill);">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                        Statistik 4 Divisi
                    </h2>
                </div>
                <a href="{{ route('admin.divisions.index') }}" style="font-size: 0.8rem; font-weight: 700; color: #2563eb; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
                    <span>Lihat Biodata</span> &rarr;
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
                                    <div style="font-weight: 800; color: var(--slate-900); display: flex; align-items: center; gap: 0.5rem;">
                                        <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $ds->color_accent ?? '#2563eb' }}; display: inline-block;"></span>
                                        <span>{{ $ds->name }}</span>
                                    </div>
                                    <div style="font-size: 0.775rem; color: var(--slate-500); margin-left: 1rem;">
                                        Kadiv: {{ $ds->leader_name ?? 'Pengurus' }}
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-neutral" style="box-shadow: var(--clay-pill); font-family: var(--font-mono); font-weight: 700;">
                                        {{ $ds->posts_count }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-info" style="box-shadow: var(--clay-pill); font-family: var(--font-mono); font-weight: 700;">
                                        {{ $ds->first_choice_applicants_count }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Applicants Table -->
    <div class="admin-clay-card" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <div style="width: 34px; height: 34px; border-radius: 10px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; box-shadow: var(--clay-pill);">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                        Pendaftar Terbaru Masuk
                    </h2>
                </div>
                <a href="{{ route('admin.recruitment.index') }}" style="font-size: 0.8rem; font-weight: 700; color: #2563eb; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
                    <span>Lihat Semua</span> &rarr;
                </a>
            </div>

            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nama & NIM</th>
                            <th>Pilihan Divisi</th>
                            <th style="text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentApplicants as $applicant)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                                        <div class="admin-profile-avatar" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                            {{ strtoupper(substr($applicant->full_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 800; color: var(--slate-900); font-size: 0.85rem;">
                                                {{ $applicant->full_name }}
                                            </div>
                                            <div style="font-family: var(--font-mono); font-size: 0.75rem; color: var(--slate-400);">
                                                {{ $applicant->nim }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--slate-700); background: var(--bg-body); padding: 0.25rem 0.65rem; border-radius: 9999px; box-shadow: var(--clay-pill);">
                                        {{ $applicant->firstChoiceDivision->name ?? '-' }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge {{ $applicant->status_badge_class }}" style="font-size: 0.725rem; box-shadow: var(--clay-pill); font-weight: 700;">
                                        {{ $applicant->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; color: var(--slate-400); padding: 2.5rem;">
                                    <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.4;"></i>
                                    Belum ada pendaftar baru yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
