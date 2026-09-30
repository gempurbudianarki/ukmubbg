@extends('admin.layouts.app')

@section('title', 'Ringkasan Dashboard - UKM CMS')
@section('page_title', 'Ringkasan Dashboard')

@section('content')
<!-- Top Analytics Cards -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="glass-panel" style="padding: 1.5rem; border-left: 4px solid var(--accent-blue);">
        <div style="font-size: 0.8rem; font-weight: 700; color: var(--slate-400); text-transform: uppercase;">
            Total Pendaftar
        </div>
        <div style="font-size: 2.25rem; font-weight: 800; color: var(--slate-900); margin: 0.25rem 0;">
            {{ $totalApplicants }}
        </div>
        <div style="font-size: 0.775rem; color: var(--slate-500);">
            Calon Anggota Baru (Oprec)
        </div>
    </div>

    <div class="glass-panel" style="padding: 1.5rem; border-left: 4px solid var(--warning);">
        <div style="font-size: 0.8rem; font-weight: 700; color: var(--slate-400); text-transform: uppercase;">
            Menunggu Seleksi
        </div>
        <div style="font-size: 2.25rem; font-weight: 800; color: var(--warning); margin: 0.25rem 0;">
            {{ $pendingApplicants }}
        </div>
        <div style="font-size: 0.775rem; color: var(--slate-500);">
            Perlu ditinjau pengurus
        </div>
    </div>

    <div class="glass-panel" style="padding: 1.5rem; border-left: 4px solid var(--success);">
        <div style="font-size: 0.8rem; font-weight: 700; color: var(--slate-400); text-transform: uppercase;">
            Diterima
        </div>
        <div style="font-size: 2.25rem; font-weight: 800; color: var(--success); margin: 0.25rem 0;">
            {{ $acceptedApplicants }}
        </div>
        <div style="font-size: 0.775rem; color: var(--slate-500);">
            Lolos seleksi divisi
        </div>
    </div>

    <div class="glass-panel" style="padding: 1.5rem; border-left: 4px solid var(--div-pemrograman);">
        <div style="font-size: 0.8rem; font-weight: 700; color: var(--slate-400); text-transform: uppercase;">
            Karya & Artikel
        </div>
        <div style="font-size: 2.25rem; font-weight: 800; color: var(--slate-900); margin: 0.25rem 0;">
            {{ $totalPosts }}
        </div>
        <div style="font-size: 0.775rem; color: var(--slate-500);">
            Publikasi aktif di kanal
        </div>
    </div>
</div>

<!-- Super Admin Controls & Recruitment Switcher -->
@if ($isSuperAdmin)
    <div class="glass-panel" style="padding: 1.75rem 2rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="font-weight: 800; font-size: 1.15rem; color: var(--slate-900); margin-bottom: 0.25rem;">
                Status Pendaftaran Terpusat (Open Recruitment)
            </div>
            <div style="font-size: 0.875rem; color: var(--slate-600);">
                Saat ini pendaftaran berstatus: 
                <strong style="color: {{ $recruitmentStatus === 'open' ? 'var(--success)' : 'var(--danger)' }}; text-transform: uppercase;">
                    {{ $recruitmentStatus === 'open' ? 'SEDANG DIBUKA (AKTIF)' : 'DITUTUP' }}
                </strong>
            </div>
        </div>
        <form action="{{ route('admin.recruitment.toggle') }}" method="POST">
            @csrf
            <button type="submit" class="btn {{ $recruitmentStatus === 'open' ? 'btn-danger' : 'btn-primary' }}">
                {{ $recruitmentStatus === 'open' ? 'Tutup Pendaftaran Sekarang' : 'Buka Pendaftaran Sekarang' }}
            </button>
        </form>
    </div>
@endif

<!-- Two Columns: Divisions Stats & Quick Links -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Division Stats -->
    <div class="glass-panel" style="padding: 1.75rem;">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1.25rem;">
            Statistik Divisi
        </h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Bidang Divisi</th>
                        <th>Artikel</th>
                        <th>Pendaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($divisionsStats as $ds)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: var(--slate-900);">
                                    {{ $ds['name'] }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--slate-500);">
                                    Kadiv: {{ $ds['leader'] }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-neutral">{{ $ds['posts_count'] }}</span>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $ds['applicants_count'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Applicants -->
    <div class="glass-panel" style="padding: 1.75rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900);">
                Pendaftar Terbaru
            </h3>
            <a href="{{ route('admin.recruitment.index') }}" style="font-size: 0.825rem; font-weight: 600; color: var(--accent-blue);">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama & NIM</th>
                        <th>Divisi Pilihan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentApplicants as $applicant)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: var(--slate-900);">
                                    {{ $applicant->full_name }}
                                </div>
                                <div style="font-family: var(--font-mono); font-size: 0.75rem; color: var(--slate-400);">
                                    {{ $applicant->nim }}
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.8rem; font-weight: 600; color: var(--slate-700);">
                                    {{ $applicant->firstChoiceDivision->name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $applicant->status_badge_class }}" style="font-size: 0.725rem;">
                                    {{ $applicant->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--slate-400); padding: 2rem;">
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
