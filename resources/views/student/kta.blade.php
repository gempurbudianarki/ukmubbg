@extends('student.layouts.app')

@section('title', 'Kartu Tanda Anggota (KTA Digital)')
@section('page_title', 'Kartu Tanda Anggota (KTA Digital)')

@section('styles')
<style>
    .kta-page-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 2.5rem;
        align-items: start;
    }
    @media (max-width: 992px) {
        .kta-page-grid {
            grid-template-columns: 1fr;
        }
    }
    .kta-card-view {
        background: linear-gradient(135deg, #090d16 0%, #1e1b4b 55%, #0369a1 100%);
        border-radius: var(--radius-xl);
        padding: 2.25rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 25px 35px -5px rgba(0, 0, 0, 0.35);
    }
    .kta-card-view::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 180px;
        height: 180px;
        background: radial-gradient(circle, rgba(56,189,248,0.2) 0%, transparent 70%);
        border-radius: 50%;
    }
    .kta-gold-chip {
        width: 48px;
        height: 36px;
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        border-radius: 6px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.4);
    }
    .kta-member-photo {
        width: 110px;
        height: 140px;
        object-fit: cover;
        border-radius: 8px;
        border: 3px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        background: #334155;
    }
    .guide-card {
        background: #ffffff;
        border-radius: var(--radius-xl);
        padding: 2rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
</style>
@endsection

@section('content')

@if ($isAccepted)
    <div class="kta-page-grid">
        <!-- Left: Kartu Fisik Digital Visual -->
        <div>
            <div class="kta-card-view" id="printableKta">
                <!-- Header Card -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #ffffff; color: #0f172a; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.95rem; box-shadow: 0 2px 5px rgba(0,0,0,0.25);">
                            UKM
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 800; letter-spacing: 0.05em; line-height: 1.1;">KARTU TANDA ANGGOTA</div>
                            <div style="font-size: 0.7rem; color: rgba(255,255,255,0.75);">UKM ILMU KOMPUTER &bull; FASILKOM</div>
                        </div>
                    </div>
                    <div class="kta-gold-chip"></div>
                </div>

                <!-- Body Card: Photo & Student Data -->
                <div style="display: flex; gap: 1.5rem; align-items: center; margin-bottom: 2rem;">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="kta-member-photo">
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.65rem; color: rgba(255,255,255,0.65); text-transform: uppercase; font-weight: 600;">
                            Nama Lengkap Anggota
                        </div>
                        <div style="font-size: 1.15rem; font-weight: 800; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.5rem; color: #ffffff;">
                            {{ $user->name }}
                        </div>

                        <div style="font-size: 0.65rem; color: rgba(255,255,255,0.65); text-transform: uppercase; font-weight: 600;">
                            Nomor Induk Mahasiswa (NIM)
                        </div>
                        <div style="font-size: 1.05rem; font-weight: 700; letter-spacing: 0.05em; color: #38bdf8; margin-bottom: 0.5rem; font-family: var(--font-mono);">
                            {{ $user->nim ?? '2401010000' }}
                        </div>

                        <div style="font-size: 0.65rem; color: rgba(255,255,255,0.65); text-transform: uppercase; font-weight: 600;">
                            Divisi Riset & Teknologi
                        </div>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #f8fafc;">
                            {{ $division?->name ?? 'Divisi Pemrograman' }}
                        </div>
                    </div>
                </div>

                <!-- Footer Card: Verification & QR -->
                <div style="display: flex; justify-content: space-between; align-items: flex-end; padding-top: 1.25rem; border-top: 1px solid rgba(255,255,255,0.18);">
                    <div>
                        <div style="font-size: 0.65rem; color: rgba(255,255,255,0.65);">STATUS VALIDASI SISTEM</div>
                        <div style="font-size: 0.825rem; font-weight: 800; color: #34d399;">
                            <i class="fas fa-shield-halved" style="margin-right: 0.25rem;"></i> ANGGOTA RESMI AKTIF
                        </div>
                        <div style="font-size: 0.7rem; color: rgba(255,255,255,0.7); margin-top: 0.25rem;">
                            Angkatan: {{ $member?->batch_year ?? '2026' }}
                        </div>
                    </div>
                    <!-- Official QR Code -->
                    <div style="background: #ffffff; padding: 5px; border-radius: 8px; box-shadow: 0 3px 6px rgba(0,0,0,0.25);">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=72x72&data={{ urlencode(url('/verifikasi?code=' . ($recruitment?->registration_code ?? $user->nim))) }}" alt="QR Code" style="width: 58px; height: 58px; display: block;">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;" class="no-print">
                <button onclick="window.print()" class="btn btn-primary" style="flex: 1; padding: 0.85rem 1.25rem;">
                    <i class="fas fa-print" style="margin-right: 0.4rem;"></i> Cetak / Unduh KTA (PDF)
                </button>
                <a href="{{ route('student.profile.edit') }}" class="btn btn-outline" style="padding: 0.85rem 1.25rem;">
                    <i class="fas fa-camera" style="margin-right: 0.4rem;"></i> Ganti Pas Foto
                </a>
            </div>
        </div>

        <!-- Right: Information & Guidelines -->
        <div class="guide-card">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 0.5rem;">
                <i class="fas fa-circle-info" style="color: #0284c7; margin-right: 0.4rem;"></i>
                Informasi & Panduan Pemegang KTA
            </h3>
            <p style="font-size: 0.85rem; color: #64748b; line-height: 1.6; margin-bottom: 1.5rem;">
                Kartu Tanda Anggota (KTA) Digital ini merupakan identitas resmi Anda sebagai sivitas aktif UKM Ilmu Komputer.
            </p>

            <div style="display: flex; flex-direction: column; gap: 1.15rem;">
                <div style="display: flex; gap: 0.85rem; align-items: flex-start;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0;">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <div>
                        <strong style="font-size: 0.9rem; color: #1e293b; display: block;">Verifikasi Presensi Cepat</strong>
                        <span style="font-size: 0.8rem; color: #64748b; line-height: 1.5; display: block;">
                            QR Code pada KTA dapat discan oleh pengurus divisi untuk absensi kehadiran di setiap workshop, riset, atau rapat akbar.
                        </span>
                    </div>
                </div>

                <div style="display: flex; gap: 0.85rem; align-items: flex-start;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0;">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div>
                        <strong style="font-size: 0.9rem; color: #1e293b; display: block;">Akses Laboratorium Komputer</strong>
                        <span style="font-size: 0.8rem; color: #64748b; line-height: 1.5; display: block;">
                            Tunjukkan KTA ini untuk mendapatkan akses penggunaan fasilitas riset, server lokal UKM, dan perangkat hardware IoT.
                        </span>
                    </div>
                </div>

                <div style="display: flex; gap: 0.85rem; align-items: flex-start;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0;">
                        <i class="fas fa-award"></i>
                    </div>
                    <div>
                        <strong style="font-size: 0.9rem; color: #1e293b; display: block;">E-Sertifikat & Sertifikasi</strong>
                        <span style="font-size: 0.8rem; color: #64748b; line-height: 1.5; display: block;">
                            Nomor identitas NIM yang tertera terhubung otomatis dengan sistem e-sertifikat kegiatan dan portofolio UKM.
                        </span>
                    </div>
                </div>
            </div>

            <div style="margin-top: 1.75rem; padding-top: 1.25rem; border-top: 1px solid #f1f5f9; font-size: 0.8rem; color: #94a3b8;">
                <i class="fas fa-lock" style="margin-right: 0.3rem;"></i> KTA ini diterbitkan secara digital oleh UKM Ilmu Komputer periode 2026/2027.
            </div>
        </div>
    </div>
@else
    <!-- Locked KTA View For Applicants -->
    <div style="max-width: 650px; margin: 2rem auto; background: #ffffff; border-radius: var(--radius-xl); padding: 3rem 2rem; border: 1px dashed #cbd5e1; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
        <div style="width: 72px; height: 72px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.85rem; margin: 0 auto 1.5rem; box-shadow: 0 4px 10px rgba(217, 119, 6, 0.15);">
            <i class="fas fa-id-card-clip"></i>
        </div>
        <h2 style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
            Kartu Tanda Anggota (KTA) Digital Belum Terbit
        </h2>
        <p style="font-size: 0.9rem; color: #64748b; line-height: 1.6; max-width: 500px; margin: 0 auto 1.75rem;">
            Saat ini Anda masih berstatus sebagai <strong>Calon Anggota (Tahap Seleksi)</strong>. KTA Digital resmi dengan QR Code verifikasi sistem akan otomatis aktif dan dapat diunduh segera setelah Anda dinyatakan <strong>Lolos Seleksi</strong> oleh pengurus divisi.
        </p>
        <div style="display: inline-flex; gap: 0.75rem;">
            <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                <i class="fas fa-route" style="margin-right: 0.4rem;"></i> Cek Status Seleksi di Dashboard
            </a>
            <a href="{{ route('student.profile.edit') }}" class="btn btn-outline">
                <i class="fas fa-camera" style="margin-right: 0.4rem;"></i> Siapkan Foto Profil
            </a>
        </div>
    </div>
@endif

@endsection
