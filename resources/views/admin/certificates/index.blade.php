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
    <button type="button" onclick="openCertModal()" class="btn btn-primary" style="box-shadow: var(--clay-btn); display: inline-flex; align-items: center; gap: 0.45rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
        </svg>
        <span>Terbitkan Sertifikat Baru</span>
    </button>
</div>

<!-- Symmetrical Stat Strip -->
<div class="admin-stat-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #2563eb;">Total Sertifikat</span>
            <div class="admin-stat-icon" style="background: #eff6ff; color: #2563eb;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="admin-stat-value">{{ $certificates->total() }}</div>
        <div class="admin-stat-sub">Sertifikat Resmi Terverifikasi</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #059669;">Peserta Workshop</span>
            <div class="admin-stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #059669;">
            {{ $certificates->where('role_as', 'Peserta Aktif')->count() }}
        </div>
        <div class="admin-stat-sub">Peserta Pelatihan & Booting</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #8b5cf6;">Panitia & Pemateri</span>
            <div class="admin-stat-icon" style="background: #f5f3ff; color: #8b5cf6;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #8b5cf6;">
            {{ $certificates->where('role_as', '!=', 'Peserta Aktif')->count() }}
        </div>
        <div class="admin-stat-sub">Kredensial Narasumber & Pengurus</div>
    </div>
</div>

<!-- Filter Toolbar (Clay Debossed) -->
<form method="GET" action="{{ route('admin.certificates.index') }}" class="admin-filter-bar">
    <div style="flex: 2; min-width: 220px;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode, nama, atau kegiatan..." class="admin-filter-input" style="width: 100%;">
    </div>

    <select name="role" class="admin-filter-input" style="flex: 1; min-width: 180px;" onchange="this.form.submit()">
        <option value="">Semua Kualifikasi</option>
        <option value="Peserta Aktif" {{ request('role') == 'Peserta Aktif' ? 'selected' : '' }}>Peserta Aktif</option>
        <option value="Pemateri Utama" {{ request('role') == 'Pemateri Utama' ? 'selected' : '' }}>Pemateri Utama</option>
        <option value="Panitia Pelaksana" {{ request('role') == 'Panitia Pelaksana' ? 'selected' : '' }}>Panitia Pelaksana</option>
        <option value="Pengurus Aktif" {{ request('role') == 'Pengurus Aktif' ? 'selected' : '' }}>Pengurus Aktif</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm" style="box-shadow: var(--clay-btn); padding: 0.65rem 1.25rem;">
        Cari Sertifikat
    </button>
    @if (request()->hasAny(['q', 'role']))
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">
            Reset Filter
        </a>
    @endif
</form>

<!-- Certificates Table Card (Full Width with Contained Scroll & Sticky Headers) -->
<div class="admin-clay-card-flat">
    <div class="admin-table-container">
        <table class="admin-table admin-table-certs">
            <thead>
                <tr>
                    <th>Kode Sertifikat</th>
                    <th>Nama Penerima</th>
                    <th>Kegiatan Workshop</th>
                    <th>Peran / Kualifikasi</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($certificates as $cert)
                    <tr>
                        <td>
                            <span class="badge badge-info" style="font-family: var(--font-mono); font-size: 0.775rem; box-shadow: var(--clay-pill);">
                                {{ $cert->certificate_code }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--slate-900);">
                                {{ $cert->recipient_name }}
                            </div>
                            @if ($cert->recipient_nim)
                                <div style="font-family: var(--font-mono); font-size: 0.75rem; color: var(--slate-400); margin-top: 0.15rem;">
                                    NIM: {{ $cert->recipient_nim }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 0.875rem; font-weight: 600; color: var(--slate-800);">
                                {{ $cert->event_name }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--slate-400); margin-top: 0.15rem;">
                                Diterbitkan: {{ $cert->issue_date->format('d M Y') }}
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-neutral" style="font-size: 0.75rem; box-shadow: var(--clay-pill);">
                                {{ $cert->role_as }}
                            </span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 0.4rem; align-items: center; justify-content: flex-end;">
                                <a href="{{ route('certificates.verify', ['code' => $cert->certificate_code]) }}" target="_blank" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); font-size: 0.75rem; padding: 0.35rem 0.65rem;" title="Verifikasi Publik">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Verifikasi</span>
                                </a>
                                <a href="{{ route('admin.certificates.edit', $cert) }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); font-size: 0.75rem; padding: 0.35rem 0.65rem;" title="Edit Sertifikat">
                                    Edit
                                </a>
                                <form action="{{ route('admin.certificates.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('Hapus sertifikat {{ $cert->certificate_code }}?')" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); background: #ffffff; box-shadow: var(--clay-btn); font-size: 0.75rem; padding: 0.35rem 0.65rem;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--slate-400); padding: 3.5rem;">
                            Belum ada sertifikat yang sesuai filter pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($certificates->hasPages())
        <div style="padding: 1.25rem 1.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8); background: #f8fafc; display: flex; justify-content: center;">
            {{ $certificates->links() }}
        </div>
    @endif
</div>

<!-- Modal Terbitkan Sertifikat Baru (Claymorphism Style) -->
<div id="certModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(6px); z-index: 999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="admin-clay-card" style="width: 100%; max-width: 580px; max-height: 90vh; overflow-y: auto; padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.75rem;">
            <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--slate-900); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#2563eb">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                <span>Terbitkan Sertifikat Baru</span>
            </h3>
            <button type="button" onclick="closeCertModal()" style="background: none; border: none; font-size: 1.5rem; color: var(--slate-400); cursor: pointer; line-height: 1;">&times;</button>
        </div>

        <form action="{{ route('admin.certificates.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label for="recipient_name" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Nama Penerima *</label>
                <input type="text" id="recipient_name" name="recipient_name" class="admin-filter-input" required placeholder="Contoh: Bintang Ramadhan" style="width: 100%;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                    <label for="recipient_nim" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">NIM Penerima</label>
                    <input type="text" id="recipient_nim" name="recipient_nim" class="admin-filter-input" placeholder="Contoh: 220104012" style="width: 100%;">
                </div>
                <div>
                    <label for="recipient_email" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Email Penerima</label>
                    <input type="email" id="recipient_email" name="recipient_email" class="admin-filter-input" placeholder="nama@email.com" style="width: 100%;">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="event_name" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Nama Kegiatan / Workshop *</label>
                <input type="text" id="event_name" name="event_name" class="admin-filter-input" required placeholder="Contoh: Workshop Fullstack Web Modern" style="width: 100%;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1.5rem;">
                <div>
                    <label for="role_as" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Peran / Kualifikasi *</label>
                    <select id="role_as" name="role_as" class="admin-filter-input" required style="width: 100%;">
                        <option value="Peserta Aktif">Peserta Aktif</option>
                        <option value="Pemateri Utama">Pemateri Utama</option>
                        <option value="Panitia Pelaksana">Panitia Pelaksana</option>
                        <option value="Pengurus Aktif">Pengurus Aktif</option>
                    </select>
                </div>
                <div>
                    <label for="issue_date" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Tanggal Terbit *</label>
                    <input type="date" id="issue_date" name="issue_date" value="{{ date('Y-m-d') }}" class="admin-filter-input" required style="width: 100%;">
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" onclick="closeCertModal()" class="btn btn-outline" style="background: #ffffff; box-shadow: var(--clay-btn);">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary" style="box-shadow: var(--clay-btn); font-weight: 700;">
                    Terbitkan Sertifikat Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCertModal() {
        const modal = document.getElementById('certModal');
        if (modal) modal.style.display = 'flex';
    }

    function closeCertModal() {
        const modal = document.getElementById('certModal');
        if (modal) modal.style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('certModal');
        if (event.target === modal) {
            closeCertModal();
        }
    }
</script>
@endsection
