@extends('admin.layouts.app')

@section('title', 'Pusat Seleksi Pendaftar - UKM CMS')
@section('page_title', 'Pusat Seleksi Calon Anggota Baru')

@section('content')
<!-- Header Box -->
<div class="admin-welcome-banner" style="margin-bottom: 2rem; padding: 1.5rem 2rem;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: 14px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--clay-pill); flex-shrink: 0;">
            <i class="fas fa-user-plus"></i>
        </div>
        <div>
            <h1 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.2rem 0;">
                Pusat Seleksi Pendaftar Masuk
            </h1>
            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                {{ $user->isSuperAdmin() ? 'Kelola berkas seleksi pendaftar dari seluruh 4 divisi spesialisasi.' : 'Kelola berkas calon anggota yang memilih divisi ' . $user->division->name }}
            </p>
        </div>
    </div>
    
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        @if ($user->isSuperAdmin())
            <a href="{{ route('admin.recruitment.settings') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.45rem; box-shadow: var(--clay-btn); border-radius: 9999px; padding: 0.65rem 1.25rem;">
                <i class="fas fa-clock-rotate-left"></i>
                <span>Pengaturan Gelombang</span>
            </a>
        @endif
        <a href="{{ route('admin.recruitment.export', request()->query()) }}" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 0.45rem; background: #ffffff; box-shadow: var(--clay-btn); border-radius: 9999px; padding: 0.65rem 1.25rem;">
            <i class="fas fa-file-csv" style="color: #059669;"></i>
            <span>Export CSV</span>
        </a>
    </div>
</div>

<!-- Search & Filter Card (Clay Debossed) -->
<form action="{{ route('admin.recruitment.index') }}" method="GET" class="admin-filter-bar">
    <div style="flex: 2; min-width: 220px; position: relative;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIM, atau kode pendaftaran..." class="admin-filter-input" style="width: 100%;">
    </div>

    @if ($user->isSuperAdmin())
        <select name="divisi" class="admin-filter-input" style="flex: 1; min-width: 170px;">
            <option value="">Semua Divisi Pilihan</option>
            @foreach ($divisions as $d)
                <option value="{{ $d->id }}" {{ request('divisi') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
            @endforeach
        </select>
    @endif

    <select name="status" class="admin-filter-input" style="flex: 1; min-width: 160px;">
        <option value="">Semua Status Seleksi</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="interview" {{ request('status') === 'interview' ? 'selected' : '' }}>Tahap Interview</option>
        <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Diterima</option>
        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm" style="box-shadow: var(--clay-btn); padding: 0.65rem 1.25rem;">
        Filter Data
    </button>
    @if (request()->hasAny(['q', 'divisi', 'status']))
        <a href="{{ route('admin.recruitment.index') }}" class="btn btn-outline btn-sm" style="box-shadow: var(--clay-btn); background: #ffffff;">
            Reset
        </a>
    @endif
</form>

<!-- Applicants Table Card (Flat Symmetrical Clay) -->
<div class="admin-clay-card-flat">
    <div class="admin-table-container">
        <table class="admin-table admin-table-recruitment">
            <thead>
                <tr>
                    <th>Kode / Tanggal</th>
                    <th>Nama & NIM</th>
                    <th>Kontak / WhatsApp</th>
                    <th>Semester</th>
                    <th>Pilihan Divisi</th>
                    <th style="text-align: center;">Status Seleksi</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($applicants as $app)
                    @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $app->phone_whatsapp);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                    @endphp
                    <tr>
                        <td>
                            <div style="font-family: var(--font-mono); font-weight: 700; color: #2563eb; font-size: 0.85rem;">
                                {{ $app->registration_code }}
                            </div>
                            <small style="color: var(--slate-400);">{{ $app->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <img src="{{ $app->avatar_url }}" alt="{{ $app->full_name }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 1px solid var(--slate-200); box-shadow: var(--clay-pill); flex-shrink: 0;">
                                <div>
                                    <div style="font-weight: 700; color: var(--slate-900);">
                                        <a href="{{ route('admin.recruitment.show', $app->id) }}" style="color: var(--slate-900);">
                                            {{ $app->full_name }}
                                        </a>
                                    </div>
                                    <small style="color: var(--slate-500); font-family: var(--font-mono);">NIM: {{ $app->nim }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 0.825rem; color: var(--slate-700);">{{ $app->email }}</div>
                            <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($app->full_name) }},%20kami%20dari%20Pengurus%20UKM%20Ilmu%20Komputer..." target="_blank" style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.775rem; color: #10b981; font-weight: 600;">
                                <span>WA: {{ $app->phone_whatsapp }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </td>
                        <td>
                            <span class="badge badge-neutral" style="box-shadow: var(--clay-pill);">
                                Smtr {{ $app->semester }} ({{ $app->class_group }})
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: {{ $app->firstChoiceDivision->color_accent }}; font-size: 0.85rem;">
                                1. {{ $app->firstChoiceDivision->name }}
                            </div>
                            @if ($app->secondChoiceDivision)
                                <small style="color: var(--slate-400);">2. {{ $app->secondChoiceDivision->name }}</small>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <span class="badge {{ $app->status_badge_class }}" style="box-shadow: var(--clay-pill); font-size: 0.75rem;">
                                {{ $app->status_label }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.recruitment.show', $app->id) }}" class="btn btn-outline btn-sm" style="box-shadow: var(--clay-btn); background: #ffffff;">
                                Review &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3.5rem; color: var(--slate-400);">
                            Belum ada calon pendaftar yang cocok dengan filter pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($applicants->total() > 0)
        <div style="padding: 1.25rem 1.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8); background: #f8fafc;">
            {{ $applicants->links() }}
        </div>
    @endif
</div>
@endsection
