@extends('admin.layouts.app')

@section('title', 'Kelola E-Sertifikat - UKM CMS')
@section('page_title', 'Kelola & Terbitkan E-Sertifikat')

@section('content')
<!-- Header Box -->
<div class="admin-header-box">
    <div>
        <h1 class="admin-header-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#2563eb">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
            <span>Daftar E-Sertifikat Terbit</span>
        </h1>
        <p class="admin-header-desc">
            Terbitkan dan kelola kredensial sertifikat resmi workshop dan kepengurusan UKM dengan verifikasi kode unik.
        </p>
    </div>
</div>

<!-- Layout 2 Kolom Symmetrical: List Sertifikat & Form Terbitkan -->
<div style="display: grid; grid-template-columns: 1fr minmax(320px, 400px); gap: 1.75rem; align-items: flex-start;">
    
    <!-- Certificates List Column -->
    <div>
        <!-- Filter Toolbar -->
        <form method="GET" action="{{ route('admin.certificates.index') }}" class="admin-filter-bar">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode, nama, atau kegiatan..." class="admin-filter-input" style="flex: 2; min-width: 200px;">
            <select name="role" class="admin-filter-input" style="flex: 1; min-width: 170px;" onchange="this.form.submit()">
                <option value="">Semua Kualifikasi</option>
                <option value="Peserta Aktif" {{ request('role') == 'Peserta Aktif' ? 'selected' : '' }}>Peserta Aktif</option>
                <option value="Pemateri Utama" {{ request('role') == 'Pemateri Utama' ? 'selected' : '' }}>Pemateri Utama</option>
                <option value="Panitia Pelaksana" {{ request('role') == 'Panitia Pelaksana' ? 'selected' : '' }}>Panitia Pelaksana</option>
                <option value="Pengurus Aktif" {{ request('role') == 'Pengurus Aktif' ? 'selected' : '' }}>Pengurus Aktif</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm" style="box-shadow: var(--clay-btn);">Filter</button>
            @if (request()->hasAny(['q', 'role']))
                <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">Reset</a>
            @endif
        </form>

        <!-- Certificates Table Card -->
        <div class="admin-clay-card-flat">
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Kode Sertifikat</th>
                            <th>Nama Penerima</th>
                            <th>Kegiatan</th>
                            <th>Peran</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($certificates as $cert)
                            <tr>
                                <td>
                                    <span class="badge badge-info" style="font-family: var(--font-mono); font-size: 0.75rem; box-shadow: var(--clay-pill);">
                                        {{ $cert->certificate_code }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: var(--slate-900);">
                                        {{ $cert->recipient_name }}
                                    </div>
                                    @if ($cert->recipient_nim)
                                        <div style="font-family: var(--font-mono); font-size: 0.75rem; color: var(--slate-400);">
                                            NIM: {{ $cert->recipient_nim }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size: 0.85rem; font-weight: 600; color: var(--slate-800);">
                                        {{ $cert->event_name }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--slate-400);">
                                        {{ $cert->issue_date->format('d M Y') }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-neutral" style="font-size: 0.725rem; box-shadow: var(--clay-pill);">
                                        {{ $cert->role_as }}
                                    </span>
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <div style="display: inline-flex; gap: 0.35rem; align-items: center; justify-content: flex-end;">
                                        <a href="{{ route('certificates.verify', ['code' => $cert->certificate_code]) }}" target="_blank" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); font-size: 0.75rem;" title="Verifikasi Publik">
                                            Lihat
                                        </a>
                                        <a href="{{ route('admin.certificates.edit', $cert) }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); font-size: 0.75rem;" title="Edit Sertifikat">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.certificates.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('Hapus sertifikat ini?')" style="display: inline; margin: 0;">
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
                                <td colspan="5" style="text-align: center; color: var(--slate-400); padding: 3rem;">
                                    Belum ada sertifikat yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($certificates->hasPages())
                <div style="padding: 1.25rem 1.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8); background: #f8fafc;">
                    {{ $certificates->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Issue Certificate Form Column -->
    <div class="admin-clay-card">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.35rem;">
            + Terbitkan Sertifikat Baru
        </h3>
        <p style="font-size: 0.8rem; color: var(--slate-500); margin-bottom: 1.25rem;">
            Data sertifikat otomatis menghasilkan kode verifikasi QR dan tautan validasi.
        </p>

        <form action="{{ route('admin.certificates.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label for="recipient_name" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Nama Penerima *</label>
                <input type="text" id="recipient_name" name="recipient_name" class="admin-filter-input" required placeholder="Contoh: Bintang Ramadhan" style="width: 100%;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="recipient_nim" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">NIM Penerima (Opsional)</label>
                <input type="text" id="recipient_nim" name="recipient_nim" class="admin-filter-input" placeholder="Contoh: 220104012" style="width: 100%;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="recipient_email" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Email Penerima (Opsional)</label>
                <input type="email" id="recipient_email" name="recipient_email" class="admin-filter-input" placeholder="nama@email.com" style="width: 100%;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="event_name" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Nama Kegiatan / Workshop *</label>
                <input type="text" id="event_name" name="event_name" class="admin-filter-input" required placeholder="Contoh: Workshop Fullstack Web Modern" style="width: 100%;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="role_as" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Peran / Kualifikasi *</label>
                <select id="role_as" name="role_as" class="admin-filter-input" required style="width: 100%;">
                    <option value="Peserta Aktif">Peserta Aktif</option>
                    <option value="Pemateri Utama">Pemateri Utama</option>
                    <option value="Panitia Pelaksana">Panitia Pelaksana</option>
                    <option value="Pengurus Aktif">Pengurus Aktif</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="issue_date" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Tanggal Terbit *</label>
                <input type="date" id="issue_date" name="issue_date" value="{{ date('Y-m-d') }}" class="admin-filter-input" required style="width: 100%;">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; box-shadow: var(--clay-btn); font-weight: 700;">
                Terbitkan Sertifikat Sekarang
            </button>
        </form>
    </div>
</div>
@endsection
