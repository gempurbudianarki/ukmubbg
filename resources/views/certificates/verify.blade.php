@extends('layouts.app')

@section('title', 'Verifikasi E-Sertifikat & Anggota - UKM Ilmu Komputer')

@section('content')
<div style="padding: 4.5rem 0 5.5rem;">
    <div class="container-narrow">
        <!-- Header -->
        <div class="section-header" style="margin-bottom: 2.5rem;">
            <div class="section-tag">Validasi Keaslian Dokumen</div>
            <h1 class="section-title">Verifikasi E-Sertifikat & Anggota</h1>
            <p class="section-desc">
                Periksa keabsahan e-sertifikat kegiatan, sertifikat kepengurusan, atau tanda kelulusan workshop yang diterbitkan resmi oleh UKM Ilmu Komputer.
            </p>
        </div>

        <!-- Search Box Form -->
        <div class="glass-panel" style="padding: 2rem; margin-bottom: 3rem;">
            <form action="{{ route('certificates.verify') }}" method="GET">
                <label for="code" class="form-label" style="font-weight: 700;">
                    Masukkan Kode Sertifikat atau NIM Peserta:
                </label>
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <input type="text" id="code" name="code" value="{{ $code }}" placeholder="Contoh: CERT-ILKOM-2026-0812 atau 220104012" class="form-control" style="flex: 1; font-family: var(--font-mono); font-size: 1rem; padding: 0.85rem 1.15rem;" required>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Cek Keabsahan</span>
                    </button>
                </div>
                <div style="font-size: 0.8rem; color: var(--slate-400); margin-top: 0.75rem;">
                    *Kode unik tercetak di bagian bawah atau pada QR code sertifikat fisik/digital Anda.
                </div>
            </form>
        </div>

        <!-- Result Section -->
        @if ($searched)
            @if ($certificate)
                <div class="cert-card-wrap">
                    <div class="cert-stamp">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>TERVERIFIKASI RESMI</span>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <span class="badge badge-info" style="font-family: var(--font-mono); font-size: 0.8rem; padding: 0.3rem 0.75rem;">
                            {{ $certificate->certificate_code }}
                        </span>
                        <div style="font-size: 0.75rem; color: var(--slate-400); margin-top: 0.35rem;">
                            ID Kredensial Resmi Universitas
                        </div>
                    </div>

                    <div style="border-bottom: 1px solid var(--slate-100); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                        <div style="font-size: 0.825rem; font-weight: 600; text-transform: uppercase; color: var(--slate-400); letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                            Nama Penerima Sertifikat
                        </div>
                        <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900);">
                            {{ $certificate->recipient_name }}
                        </h2>
                        @if ($certificate->recipient_nim)
                            <div style="font-family: var(--font-mono); font-size: 0.9rem; color: var(--slate-600); margin-top: 0.2rem;">
                                NIM: {{ $certificate->recipient_nim }}
                            </div>
                        @endif
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                        <div>
                            <div style="font-size: 0.8rem; color: var(--slate-400); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">
                                Nama Kegiatan / Pelatihan
                            </div>
                            <div style="font-size: 1.05rem; font-weight: 700; color: var(--slate-900);">
                                {{ $certificate->event_name }}
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 0.8rem; color: var(--slate-400); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">
                                Peran / Kualifikasi
                            </div>
                            <div style="font-size: 1.05rem; font-weight: 700; color: var(--accent-blue);">
                                {{ $certificate->role_as }}
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 0.8rem; color: var(--slate-400); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">
                                Tanggal Penerbitan
                            </div>
                            <div style="font-size: 0.95rem; font-weight: 600; color: var(--slate-800);">
                                {{ $certificate->issue_date->format('d F Y') }}
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 0.8rem; color: var(--slate-400); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">
                                Penerbit Dokumen
                            </div>
                            <div style="font-size: 0.95rem; font-weight: 600; color: var(--slate-800);">
                                Pengurus UKM Ilmu Komputer
                            </div>
                        </div>
                    </div>

                    <div style="background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: 1.25rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                        <div style="font-size: 0.825rem; color: var(--slate-600); line-height: 1.5;">
                            Dokumen ini tercatat dalam pangkalan data terpusat dan memiliki kekuatan pembuktian digital sebagai portofolio resmi mahasiswa.
                        </div>
                        <button onclick="window.print()" class="btn btn-outline btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Cetak Kredensial</span>
                        </button>
                    </div>
                </div>
            @else
                <div class="glass-panel" style="padding: 3rem 2rem; text-align: center; border-left: 4px solid var(--danger);">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--danger-bg); color: var(--danger); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.5rem;">
                        Data Sertifikat Tidak Ditemukan
                    </h3>
                    <p style="color: var(--slate-600); font-size: 0.95rem; max-width: 520px; margin: 0 auto 1.5rem; line-height: 1.6;">
                        Nomor kode atau NIM <strong>"{{ $code }}"</strong> tidak terdaftar dalam pangkalan data sertifikat kami. Mohon pastikan tidak ada kesalahan ketik karakter atau tanda hubung (-).
                    </p>
                    <a href="{{ route('certificates.verify') }}" class="btn btn-outline btn-sm">Coba Pencarian Baru</a>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
