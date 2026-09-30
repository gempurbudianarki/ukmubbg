@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - UKM Ilmu Komputer')

@section('content')
<div class="container-narrow" style="padding: 4rem 1.5rem;">
    <div class="card" style="text-align: center; padding: 3rem 2rem; border-top: 4px solid var(--success);">
        <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--success-bg); color: var(--success); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <span class="badge badge-success" style="margin-bottom: 0.75rem;">Registrasi Diterima</span>
        <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em; margin-bottom: 0.75rem;">
            Pendaftaran Berhasil Dikirim!
        </h1>
        <p style="color: var(--slate-600); max-width: 550px; margin: 0 auto 2rem; font-size: 1.05rem;">
            Terima kasih telah mendaftar, <strong>{{ $applicant->full_name }}</strong>. Berkas Anda telah masuk ke sistem kami untuk ditinjau oleh pengurus divisi.
        </p>

        <!-- Ticket Card Box -->
        <div style="background-color: var(--slate-50); border: 2px dashed var(--slate-300); border-radius: var(--radius-lg); padding: 2rem; max-width: 500px; margin: 0 auto 2rem; text-align: left;">
            <div style="text-align: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--slate-200);">
                <div style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--slate-500);">
                    Kode Pendaftaran Anda
                </div>
                <div style="font-family: var(--font-mono); font-size: 1.85rem; font-weight: 800; color: var(--primary-light); letter-spacing: 0.05em; margin-top: 0.25rem;">
                    {{ $applicant->registration_code }}
                </div>
                <small style="color: var(--slate-500); font-size: 0.75rem;">Simpan atau screenshot kode ini untuk pengecekan status berkala.</small>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.9rem;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--slate-500);">Nama Lengkap:</span>
                    <strong style="color: var(--slate-800);">{{ $applicant->full_name }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--slate-500);">NIM:</span>
                    <span style="font-family: var(--font-mono); font-weight: 600;">{{ $applicant->nim }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--slate-500);">Semester / Kelas:</span>
                    <span style="color: var(--slate-800);">Semester {{ $applicant->semester }} ({{ $applicant->class_group }})</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--slate-500);">Pilihan Utama:</span>
                    <strong style="color: {{ $applicant->firstChoiceDivision->color_accent }};">
                        {{ $applicant->firstChoiceDivision->name }}
                    </strong>
                </div>
                @if ($applicant->secondChoiceDivision)
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--slate-500);">Pilihan Kedua:</span>
                        <span style="color: var(--slate-700);">{{ $applicant->secondChoiceDivision->name }}</span>
                    </div>
                @endif
                <div style="display: flex; justify-content: space-between; padding-top: 0.5rem; border-top: 1px solid var(--slate-200);">
                    <span style="color: var(--slate-500);">Status Awal:</span>
                    <span class="badge badge-warning">{{ $applicant->status_label }}</span>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('recruitment.status', ['search' => $applicant->nim]) }}" class="btn btn-primary">
                Pantau Status Pendaftaran &rarr;
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
