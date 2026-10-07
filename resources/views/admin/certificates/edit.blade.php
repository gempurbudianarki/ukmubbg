@extends('admin.layouts.app')

@section('title', 'Edit E-Sertifikat - ' . $certificate->certificate_code)
@section('page_title', 'Edit Data E-Sertifikat')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding-bottom: 3rem;">
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.025em; margin-bottom: 0.25rem;">
                Edit E-Sertifikat
            </h1>
            <p style="color: var(--slate-500); font-size: 0.9rem;">
                Perbaiki identitas penerima atau kegiatan untuk sertifikat {{ $certificate->certificate_code }}.
            </p>
        </div>
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline" style="background: #ffffff;">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="glass-panel" style="padding: 2rem;">
        <form action="{{ route('admin.certificates.update', $certificate) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="certificate_code">Kode Sertifikat Unik *</label>
                <input type="text" id="certificate_code" name="certificate_code" class="form-control" style="font-family: var(--font-mono); font-weight: 600;" required value="{{ old('certificate_code', $certificate->certificate_code) }}">
                <small style="color: var(--slate-400); font-size: 0.75rem;">Kode ini digunakan oleh mahasiswa dan publik untuk verifikasi keaslian di web.</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="recipient_name">Nama Lengkap Penerima *</label>
                    <input type="text" id="recipient_name" name="recipient_name" class="form-control" required value="{{ old('recipient_name', $certificate->recipient_name) }}">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="recipient_nim">NIM Mahasiswa (Opsional)</label>
                    <input type="text" id="recipient_nim" name="recipient_nim" class="form-control" value="{{ old('recipient_nim', $certificate->recipient_nim) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="recipient_email">Email Penerima (Opsional)</label>
                <input type="email" id="recipient_email" name="recipient_email" class="form-control" value="{{ old('recipient_email', $certificate->recipient_email) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="event_name">Nama Kegiatan / Workshop *</label>
                <input type="text" id="event_name" name="event_name" class="form-control" required value="{{ old('event_name', $certificate->event_name) }}">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="role_as">Peran / Kualifikasi *</label>
                    <select id="role_as" name="role_as" class="form-control" required>
                        <option value="Peserta Aktif" {{ old('role_as', $certificate->role_as) == 'Peserta Aktif' ? 'selected' : '' }}>Peserta Aktif</option>
                        <option value="Pemateri Utama" {{ old('role_as', $certificate->role_as) == 'Pemateri Utama' ? 'selected' : '' }}>Pemateri Utama</option>
                        <option value="Panitia Pelaksana" {{ old('role_as', $certificate->role_as) == 'Panitia Pelaksana' ? 'selected' : '' }}>Panitia Pelaksana</option>
                        <option value="Juara 1 Kompetisi" {{ old('role_as', $certificate->role_as) == 'Juara 1 Kompetisi' ? 'selected' : '' }}>Juara 1 Kompetisi</option>
                        <option value="Juara 2 Kompetisi" {{ old('role_as', $certificate->role_as) == 'Juara 2 Kompetisi' ? 'selected' : '' }}>Juara 2 Kompetisi</option>
                        <option value="Juara 3 Kompetisi" {{ old('role_as', $certificate->role_as) == 'Juara 3 Kompetisi' ? 'selected' : '' }}>Juara 3 Kompetisi</option>
                        <option value="Pengurus Aktif" {{ old('role_as', $certificate->role_as) == 'Pengurus Aktif' ? 'selected' : '' }}>Pengurus Aktif</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="issue_date">Tanggal Terbit *</label>
                    <input type="date" id="issue_date" name="issue_date" class="form-control" required value="{{ old('issue_date', optional($certificate->issue_date)->format('Y-m-d')) }}">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid var(--slate-100);">
                <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
