@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - UKM Ilmu Komputer')

@section('content')
<div class="container-narrow" style="padding: 4rem 1.5rem;">
    <div class="card" style="text-align: center; padding: 3.5rem 2.5rem; border-radius: var(--radius-xl); box-shadow: var(--clay-card); border: none;">
        <div style="width: 72px; height: 72px; border-radius: 50%; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: var(--clay-pill);">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <span class="badge badge-success" style="margin-bottom: 0.85rem; box-shadow: var(--clay-pill);">Registrasi Diterima</span>
        <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em; margin-bottom: 0.75rem;">
            Pendaftaran Berhasil Dikirim!
        </h1>
        <p style="color: var(--slate-600); max-width: 550px; margin: 0 auto 2.25rem; font-size: 1.05rem; line-height: 1.6;">
            Terima kasih telah mendaftar, <strong>{{ $applicant->full_name }}</strong>. Berkas Anda telah masuk ke sistem kami untuk ditinjau oleh pengurus divisi.
        </p>

        <!-- Ticket Card Box -->
        <div style="background: var(--bg-body); box-shadow: var(--clay-debossed); border: 2px dashed rgba(203, 213, 225, 0.8); border-radius: var(--radius-lg); padding: 2rem; max-width: 500px; margin: 0 auto 2.5rem; text-align: left;">
            <div style="text-align: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8);">
                <div style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--slate-500);">
                    Kode Pendaftaran Anda
                </div>
                <div style="font-family: var(--font-mono); font-size: 2rem; font-weight: 800; color: var(--accent-blue); letter-spacing: 0.05em; margin-top: 0.25rem;">
                    {{ $applicant->registration_code }}
                </div>
                <small style="color: var(--slate-400); font-size: 0.775rem;">Simpan atau screenshot kode ini untuk pengecekan status berkala.</small>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.925rem;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--slate-500);">Nama Lengkap:</span>
                    <strong style="color: var(--slate-800);">{{ $applicant->full_name }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--slate-500);">NIM:</span>
                    <span style="font-family: var(--font-mono); font-weight: 700; color: var(--slate-800);">{{ $applicant->nim }}</span>
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
                <div style="display: flex; justify-content: space-between; padding-top: 0.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8); align-items: center;">
                    <span style="color: var(--slate-500);">Status Awal:</span>
                    <span class="badge badge-warning" style="box-shadow: var(--clay-pill);">{{ $applicant->status_label }}</span>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('recruitment.status', ['search' => $applicant->nim]) }}" class="btn btn-primary" style="border-radius: var(--radius-full); padding: 0.75rem 1.75rem;">
                Pantau Status Pendaftaran &rarr;
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline" style="border-radius: var(--radius-full); padding: 0.75rem 1.75rem;">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
