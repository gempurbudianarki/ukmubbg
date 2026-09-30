@extends('admin.layouts.app')

@section('title', 'Kelola E-Sertifikat - UKM CMS')
@section('page_title', 'Kelola & Terbitkan E-Sertifikat')

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Certificates List -->
    <div class="glass-panel" style="padding: 1.75rem;">
        <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1.25rem;">
            Daftar E-Sertifikat Terbit
        </h3>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Sertifikat</th>
                        <th>Nama Penerima</th>
                        <th>Kegiatan</th>
                        <th>Peran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($certificates as $cert)
                        <tr>
                            <td>
                                <span class="badge badge-info" style="font-family: var(--font-mono); font-size: 0.75rem;">
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
                                <div style="font-size: 0.85rem; color: var(--slate-800);">
                                    {{ $cert->event_name }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--slate-400);">
                                    {{ $cert->issue_date->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-neutral" style="font-size: 0.75rem;">
                                    {{ $cert->role_as }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.4rem;">
                                    <a href="{{ route('certificates.verify', ['code' => $cert->certificate_code]) }}" target="_blank" class="btn btn-outline btn-sm">
                                        Lihat
                                    </a>
                                    <form action="{{ route('admin.certificates.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('Hapus sertifikat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--slate-400); padding: 2rem;">
                                Belum ada sertifikat yang diterbitkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($certificates->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $certificates->links() }}
            </div>
        @endif
    </div>

    <!-- Issue Certificate Form -->
    <div class="glass-panel" style="padding: 1.75rem;">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1.25rem;">
            + Terbitkan Sertifikat Baru
        </h3>

        <form action="{{ route('admin.certificates.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="recipient_name">Nama Penerima *</label>
                <input type="text" id="recipient_name" name="recipient_name" class="form-control" required placeholder="Contoh: Bintang Ramadhan">
            </div>

            <div class="form-group">
                <label class="form-label" for="recipient_nim">NIM Penerima (Opsional)</label>
                <input type="text" id="recipient_nim" name="recipient_nim" class="form-control" placeholder="Contoh: 220104012">
            </div>

            <div class="form-group">
                <label class="form-label" for="recipient_email">Email Penerima (Opsional)</label>
                <input type="email" id="recipient_email" name="recipient_email" class="form-control" placeholder="nama@email.com">
            </div>

            <div class="form-group">
                <label class="form-label" for="event_name">Nama Kegiatan / Workshop *</label>
                <input type="text" id="event_name" name="event_name" class="form-control" required placeholder="Contoh: Workshop Fullstack Web Modern">
            </div>

            <div class="form-group">
                <label class="form-label" for="role_as">Peran / Kualifikasi *</label>
                <select id="role_as" name="role_as" class="form-control" required>
                    <option value="Peserta Aktif">Peserta Aktif</option>
                    <option value="Pemateri Utama">Pemateri Utama</option>
                    <option value="Panitia Pelaksana">Panitia Pelaksana</option>
                    <option value="Juara 1 Kompetisi">Juara 1 Kompetisi</option>
                    <option value="Pengurus Aktif">Pengurus Aktif</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" for="issue_date">Tanggal Terbit *</label>
                <input type="date" id="issue_date" name="issue_date" value="{{ date('Y-m-d') }}" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Generate & Terbitkan
            </button>
        </form>
    </div>
</div>
@endsection
