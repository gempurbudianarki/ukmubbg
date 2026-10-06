@extends('student.layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: var(--radius-xl);
        padding: 2.25rem;
        color: #ffffff;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.3);
    }
    .welcome-card::after {
        content: '';
        position: absolute;
        right: -60px;
        top: -60px;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(37,99,235,0.25) 0%, rgba(37,99,235,0) 70%);
        border-radius: 50%;
    }
    .grid-dashboard {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }
    @media (max-width: 992px) {
        .grid-dashboard {
            grid-template-columns: 1fr;
        }
    }
    /* Stepper */
    .stepper-container {
        background: #ffffff;
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        border: 1px solid var(--slate-200);
        margin-bottom: 2rem;
    }
    .stepper-timeline {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-top: 1.5rem;
    }
    .stepper-timeline::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 40px;
        right: 40px;
        height: 3px;
        background: var(--slate-200);
        z-index: 1;
    }
    .step-item {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 33.33%;
    }
    .step-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid var(--slate-300);
        color: var(--slate-400);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }
    .step-item.completed .step-circle {
        background: var(--success);
        border-color: var(--success);
        color: #ffffff;
    }
    .step-item.active .step-circle {
        background: var(--primary-600);
        border-color: var(--primary-600);
        color: #ffffff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
    }
    .step-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--slate-800);
        margin-bottom: 0.25rem;
    }
    .step-desc {
        font-size: 0.75rem;
        color: var(--slate-500);
    }

    /* KTA Card */
    .kta-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #0369a1 100%);
        border-radius: var(--radius-xl);
        padding: 1.75rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .kta-chip {
        width: 44px;
        height: 32px;
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        border-radius: 6px;
        display: inline-block;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
    }
    .kta-avatar {
        width: 100px;
        height: 125px;
        object-fit: cover;
        border-radius: 8px;
        border: 3px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
    }
</style>
@endsection

@section('content')
<div class="welcome-card">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: gap; gap: 1.5rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.1); padding: 0.35rem 0.85rem; border-radius: 9999px; font-size: 0.775rem; font-weight: 600; margin-bottom: 0.85rem; backdrop-filter: blur(4px);">
                <i class="fas fa-id-card-clip" style="color: var(--accent-cyan);"></i>
                Kode Pendaftaran: <strong>{{ $recruitment?->registration_code ?? 'MEMBER-RESMI' }}</strong>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; margin-bottom: 0.5rem; letter-spacing: -0.02em;">
                Selamat Datang, {{ $user->name }}!
            </h1>
            <p style="color: var(--slate-300); font-size: 0.95rem; margin-bottom: 1.25rem; max-width: 600px; line-height: 1.6;">
                Ini adalah portal akun mahasiswa Anda untuk memantau status seleksi, mengunduh KTA Digital, melihat silabus pembelajaran, dan memantau persentase kehadiran kegiatan.
            </p>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <a href="{{ route('student.profile.edit') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-user-pen" style="margin-right: 0.4rem;"></i> Edit Profil & Foto
                </a>
                <button onclick="window.print()" class="btn btn-sm btn-primary">
                    <i class="fas fa-print" style="margin-right: 0.4rem;"></i> Cetak Kartu Anggota (KTA)
                </button>
            </div>
        </div>

        <div style="text-align: right; background: rgba(255,255,255,0.06); padding: 1.25rem; border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.1); min-width: 220px;">
            <div style="font-size: 0.75rem; color: var(--slate-400); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.35rem;">
                Status Seleksi
            </div>
            <div style="margin-bottom: 0.5rem;">
                @if ($recruitment?->status === 'accepted' || $member)
                    <span class="badge badge-success" style="font-size: 0.85rem; padding: 0.35rem 0.85rem;">
                        <i class="fas fa-check-circle" style="margin-right: 0.3rem;"></i> Diterima Resmi
                    </span>
                @elseif ($recruitment?->status === 'interview')
                    <span class="badge badge-info" style="font-size: 0.85rem; padding: 0.35rem 0.85rem;">
                        <i class="fas fa-comments" style="margin-right: 0.3rem;"></i> Tahap Wawancara
                    </span>
                @elseif ($recruitment?->status === 'rejected')
                    <span class="badge badge-danger" style="font-size: 0.85rem; padding: 0.35rem 0.85rem;">
                        <i class="fas fa-times-circle" style="margin-right: 0.3rem;"></i> Belum Lolos
                    </span>
                @else
                    <span class="badge badge-warning" style="font-size: 0.85rem; padding: 0.35rem 0.85rem;">
                        <i class="fas fa-clock" style="margin-right: 0.3rem;"></i> Verifikasi Administrasi
                    </span>
                @endif
            </div>
            <div style="font-size: 0.8rem; color: var(--slate-300);">
                Fokus: <strong>{{ $division?->name ?? 'Belum Ditentukan' }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="grid-dashboard">
    <!-- Left Column: Stepper, Presensi, Silabus -->
    <div>
        <!-- Stepper Status Seleksi Real-Time -->
        <div class="stepper-container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                    <i class="fas fa-route" style="color: var(--primary-600); margin-right: 0.5rem;"></i>
                    Alur Seleksi Penerimaan Anggota
                </h3>
                <span style="font-size: 0.775rem; color: var(--slate-500); font-weight: 600;">Periode 2026/2027</span>
            </div>

            @php
                $currStatus = $recruitment?->status ?? 'pending';
                $isStep1Done = true;
                $isStep2Active = ($currStatus === 'interview');
                $isStep2Done = ($currStatus === 'accepted');
                $isStep3Active = ($currStatus === 'accepted');
            @endphp

            <div class="stepper-timeline">
                <div class="step-item completed">
                    <div class="step-circle"><i class="fas fa-check"></i></div>
                    <div class="step-title">1. Administrasi</div>
                    <div class="step-desc">Berkas & Biodata Terverifikasi</div>
                </div>

                <div class="step-item {{ $isStep2Done ? 'completed' : ($isStep2Active ? 'active' : '') }}">
                    <div class="step-circle">
                        @if ($isStep2Done)
                            <i class="fas fa-check"></i>
                        @else
                            2
                        @endif
                    </div>
                    <div class="step-title">2. Wawancara</div>
                    <div class="step-desc">Uji Minat & Motivasi</div>
                </div>

                <div class="step-item {{ $isStep3Active ? 'completed' : '' }}">
                    <div class="step-circle">
                        @if ($isStep3Active)
                            <i class="fas fa-trophy"></i>
                        @else
                            3
                        @endif
                    </div>
                    <div class="step-title">3. Kelulusan</div>
                    <div class="step-desc">Penetapan Anggota UKM</div>
                </div>
            </div>

            <!-- Detail Box Jadwal Wawancara / Catatan Admin jika ada -->
            @if ($recruitment?->interview_schedule || $recruitment?->interview_location)
                <div style="margin-top: 1.75rem; padding: 1.25rem; background: var(--primary-50); border: 1px solid rgba(37,99,235,0.2); border-radius: var(--radius-md);">
                    <div style="font-weight: 700; color: var(--primary-900); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-calendar-check" style="color: var(--primary-600);"></i>
                        Jadwal Wawancara Divisi Anda:
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.875rem;">
                        <div>
                            <span style="color: var(--slate-500); display: block; font-size: 0.75rem;">Waktu & Tanggal:</span>
                            <strong style="color: var(--slate-800);">
                                {{ \Carbon\Carbon::parse($recruitment->interview_schedule)->translatedFormat('l, d F Y - H:i') }} WIB
                            </strong>
                        </div>
                        <div>
                            <span style="color: var(--slate-500); display: block; font-size: 0.75rem;">Lokasi / Ruangan:</span>
                            <strong style="color: var(--slate-800);">
                                {{ $recruitment->interview_location ?? 'Sekretariat UKM Ilmu Komputer' }}
                            </strong>
                        </div>
                    </div>
                </div>
            @endif

            @if ($recruitment?->admin_notes)
                <div style="margin-top: 1rem; padding: 1rem; background: var(--slate-50); border-radius: var(--radius-md); font-size: 0.85rem; color: var(--slate-700);">
                    <strong>Catatan Penguji / Panitia:</strong> {{ $recruitment->admin_notes }}
                </div>
            @endif
        </div>

        <!-- Presensi & Riwayat Kehadiran Divisi -->
        <div style="background: #ffffff; border-radius: var(--radius-lg); padding: 1.75rem; border: 1px solid var(--slate-200); margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                        <i class="fas fa-clipboard-user" style="color: var(--primary-600); margin-right: 0.5rem;"></i>
                        Kehadiran & Presensi Pertemuan
                    </h3>
                    <p style="font-size: 0.8rem; color: var(--slate-500); margin: 0.25rem 0 0;">
                        Rekapitulasi kehadiran di sesi pembelajaran divisi formal
                    </p>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 1.6rem; font-weight: 800; color: var(--primary-600);">
                        {{ $attendanceStats['percentage'] }}%
                    </span>
                    <span style="font-size: 0.75rem; color: var(--slate-400); display: block;">Tingkat Kehadiran</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div style="background: var(--slate-50); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
                    <div style="font-size: 0.75rem; color: var(--slate-500); font-weight: 600;">Total Sesi Diselenggarakan</div>
                    <div style="font-size: 1.25rem; font-weight: 800; color: var(--slate-800);">
                        {{ $attendanceStats['total_sessions'] }} Pertemuan
                    </div>
                </div>
                <div style="background: var(--slate-50); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
                    <div style="font-size: 0.75rem; color: var(--slate-500); font-weight: 600;">Kehadiran Tercatat</div>
                    <div style="font-size: 1.25rem; font-weight: 800; color: var(--success);">
                        {{ $attendanceStats['attended_count'] }} Hadir
                    </div>
                </div>
            </div>

            @if ($attendanceStats['recent_logs']->count() > 0)
                <div class="table-responsive">
                    <table class="table" style="font-size: 0.85rem; margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th>Pertemuan / Topik</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendanceStats['recent_logs'] as $log)
                                <tr>
                                    <td>
                                        <strong>Pertemuan #{{ $log->session?->meeting_number }}</strong> - {{ $log->session?->title }}
                                    </td>
                                    <td>{{ $log->session?->date?->translatedFormat('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $log->status_badge }}">
                                            {{ ucfirst($log->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 1.5rem; background: var(--slate-50); border-radius: var(--radius-md); color: var(--slate-500); font-size: 0.85rem;">
                    <i class="fas fa-calendar-xmark" style="font-size: 1.5rem; color: var(--slate-400); margin-bottom: 0.5rem; display: block;"></i>
                    Belum ada riwayat pertemuan presensi yang tercatat untuk akun Anda.
                </div>
            @endif
        </div>

        <!-- Silabus & Materi Pembelajaran Divisi -->
        <div style="background: #ffffff; border-radius: var(--radius-lg); padding: 1.75rem; border: 1px solid var(--slate-200);">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.5rem;">
                <i class="fas fa-book-bookmark" style="color: var(--primary-600); margin-right: 0.5rem;"></i>
                Silabus & Kompetensi Divisi {{ $division?->name }}
            </h3>
            <p style="font-size: 0.825rem; color: var(--slate-500); margin-bottom: 1.25rem;">
                Materi teknis terarah dan kurikulum riset yang akan Anda kuasai selama berdinamika di divisi ini:
            </p>

            <div style="display: flex; flex-wrap: wrap; gap: 0.65rem;">
                @if (is_array($syllabus) && count($syllabus) > 0)
                    @foreach ($syllabus as $item)
                        <span style="background: var(--slate-100); color: var(--slate-800); border: 1px solid var(--slate-200); padding: 0.45rem 0.85rem; border-radius: var(--radius-md); font-size: 0.825rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.4rem;">
                            <i class="fas fa-code" style="color: var(--primary-600); font-size: 0.75rem;"></i>
                            {{ $item }}
                        </span>
                    @endforeach
                @else
                    <span style="color: var(--slate-400); font-size: 0.85rem;">Topik pembelajaran belum diperbarui oleh ketua divisi.</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Digital KTA Card & Student Information -->
    <div>
        <!-- Kartu Tanda Anggota (KTA) Digital -->
        <div class="kta-card" id="ktaCardSection">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <div style="width: 28px; height: 28px; border-radius: 6px; background: #ffffff; color: var(--slate-900); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem;">
                        UKM
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; font-weight: 800; letter-spacing: 0.05em; line-height: 1.1;">KARTU TANDA ANGGOTA</div>
                        <div style="font-size: 0.65rem; color: rgba(255,255,255,0.7);">UKM ILMU KOMPUTER</div>
                    </div>
                </div>
                <div class="kta-chip"></div>
            </div>

            <div style="display: flex; gap: 1.25rem; align-items: center; margin-bottom: 1.5rem;">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="kta-avatar">
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 0.65rem; color: rgba(255,255,255,0.6); text-transform: uppercase; font-weight: 600;">
                        Nama Lengkap
                    </div>
                    <div style="font-size: 1.05rem; font-weight: 800; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.4rem;">
                        {{ $user->name }}
                    </div>

                    <div style="font-size: 0.65rem; color: rgba(255,255,255,0.6); text-transform: uppercase; font-weight: 600;">
                        NIM Mahasiswa
                    </div>
                    <div style="font-size: 0.95rem; font-weight: 700; letter-spacing: 0.05em; color: var(--accent-cyan); margin-bottom: 0.4rem;">
                        {{ $user->nim ?? '2401010000' }}
                    </div>

                    <div style="font-size: 0.65rem; color: rgba(255,255,255,0.6); text-transform: uppercase; font-weight: 600;">
                        Divisi
                    </div>
                    <div style="font-size: 0.85rem; font-weight: 600;">
                        {{ $division?->name ?? 'Ilmu Komputer' }}
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.15);">
                <div>
                    <div style="font-size: 0.65rem; color: rgba(255,255,255,0.6);">STATUS VALIDASI</div>
                    <div style="font-size: 0.75rem; font-weight: 700; color: #4ade80;">
                        <i class="fas fa-shield-halved" style="margin-right: 0.25rem;"></i> TERVERIFIKASI SISTEM
                    </div>
                </div>
                <!-- QR Code Verified -->
                <div style="background: #ffffff; padding: 4px; border-radius: 6px;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=64x64&data={{ urlencode(url('/verifikasi?code=' . ($recruitment?->registration_code ?? $user->nim))) }}" alt="QR Code" style="width: 50px; height: 50px; display: block;">
                </div>
            </div>
        </div>

        <!-- Student Data Card Details -->
        <div style="background: #ffffff; border-radius: var(--radius-lg); padding: 1.5rem; border: 1px solid var(--slate-200); margin-top: 1.5rem;">
            <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--slate-900); margin-bottom: 1rem;">
                Informasi Kontak & Akun Mahasiswa
            </h4>
            <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.85rem;">
                <div>
                    <span style="color: var(--slate-500); display: block; font-size: 0.75rem;">Email Login:</span>
                    <strong style="color: var(--slate-800);">{{ $user->email }}</strong>
                </div>
                <div>
                    <span style="color: var(--slate-500); display: block; font-size: 0.75rem;">Nomor WhatsApp:</span>
                    <strong style="color: var(--slate-800);">{{ $user->phone_number ?? '-' }}</strong>
                </div>
                <div>
                    <span style="color: var(--slate-500); display: block; font-size: 0.75rem;">Profil GitHub:</span>
                    @if ($user->github_url)
                        <a href="{{ $user->github_url }}" target="_blank" style="color: var(--primary-600); font-weight: 600;">
                            <i class="fab fa-github" style="margin-right: 0.25rem;"></i> {{ $user->github_url }}
                        </a>
                    @else
                        <span style="color: var(--slate-400);">Belum ditambahkan</span>
                    @endif
                </div>
            </div>

            <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--slate-100);">
                <a href="{{ route('student.profile.edit') }}" class="btn btn-outline btn-sm" style="width: 100%; text-align: center;">
                    <i class="fas fa-gear" style="margin-right: 0.4rem;"></i> Kelola Biodata & Password
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
