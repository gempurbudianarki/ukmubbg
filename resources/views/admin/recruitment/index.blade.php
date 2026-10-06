@extends('admin.layouts.app')

@section('title', 'Pusat Seleksi Pendaftar - UKM CMS')
@section('page_title', 'Pusat Seleksi Calon Anggota Baru')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--slate-900);">
            Data Pendaftaran Masuk
        </h2>
        <p style="color: var(--slate-500); font-size: 0.875rem;">
            {{ $user->isSuperAdmin() ? 'Kelola seleksi pendaftar dari seluruh divisi' : 'Kelola pendaftar yang memilih divisi ' . $user->division->name }}
        </p>
    </div>
    
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        @if ($user->isSuperAdmin())
            <a href="{{ route('admin.recruitment.settings') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.4rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Pengaturan Gelombang & Kuota</span>
            </a>
        @endif
        <a href="{{ route('admin.recruitment.export', request()->query()) }}" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 0.4rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Export Data (Excel/CSV)</span>
        </a>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card" style="margin-bottom: 1.5rem; padding: 1rem 1.5rem;">
    <form action="{{ route('admin.recruitment.index') }}" method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIM, atau kode..." class="form-control" style="flex: 2; min-width: 200px;">

        @if ($user->isSuperAdmin())
            <select name="divisi" class="form-select" style="flex: 1; min-width: 160px;">
                <option value="">Semua Divisi Pilihan</option>
                @foreach ($divisions as $d)
                    <option value="{{ $d->id }}" {{ request('divisi') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                @endforeach
            </select>
        @endif

        <select name="status" class="form-select" style="flex: 1; min-width: 150px;">
            <option value="">Semua Status Seleksi</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="interview" {{ request('status') === 'interview' ? 'selected' : '' }}>Tahap Interview</option>
            <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Diterima</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>

        <button type="submit" class="btn btn-outline">Terapkan Filter</button>
    </form>
</div>

<!-- Applicants Table Card -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode / Waktu</th>
                    <th>Nama & NIM</th>
                    <th>Kontak / WA</th>
                    <th>Kelas</th>
                    <th>Pilihan Divisi</th>
                    <th>Status Seleksi</th>
                    <th style="text-align: right;">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($applicants as $app)
                    @php
                        // Format WA phone for direct link
                        $cleanPhone = preg_replace('/[^0-9]/', '', $app->phone_whatsapp);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                    @endphp
                    <tr>
                        <td>
                            <div style="font-family: var(--font-mono); font-weight: 700; color: var(--primary-light); font-size: 0.85rem;">
                                {{ $app->registration_code }}
                            </div>
                            <small style="color: var(--slate-400);">{{ $app->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <img src="{{ $app->avatar_url }}" alt="{{ $app->full_name }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 1px solid var(--slate-200); flex-shrink: 0;">
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
                            <div>{{ $app->email }}</div>
                            <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($app->full_name) }},%20kami%20dari%20Pengurus%20UKM%20Ilmu%20Komputer..." target="_blank" style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.8rem; color: #10b981; font-weight: 600;">
                                <span>WA: {{ $app->phone_whatsapp }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </td>
                        <td>
                            Smtr {{ $app->semester }} ({{ $app->class_group }})
                        </td>
                        <td>
                            <div style="font-weight: 700; color: {{ $app->firstChoiceDivision->color_accent }}; font-size: 0.875rem;">
                                1. {{ $app->firstChoiceDivision->name }}
                            </div>
                            @if ($app->secondChoiceDivision)
                                <small style="color: var(--slate-500);">2. {{ $app->secondChoiceDivision->name }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $app->status_badge_class }}">
                                {{ $app->status_label }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.recruitment.show', $app->id) }}" class="btn btn-outline btn-sm">
                                Review & Status &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: var(--slate-500);">
                            Belum ada calon pendaftar yang cocok dengan filter pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($applicants->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--slate-200);">
            {{ $applicants->links() }}
        </div>
    @endif
</div>
@endsection
