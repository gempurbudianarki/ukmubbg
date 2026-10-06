@extends('student.layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page_title', 'Dashboard & Informasi Anggota')

@section('styles')
<style>
    /* Metric / Stat Cards */
    .stat-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        padding: 1.35rem;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 1.15rem;
        box-shadow: 0 1px 3px 0 rgba(0,0,0,0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.08);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    /* Welcome Banner */
    .member-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0369a1 100%);
        border-radius: var(--radius-xl);
        padding: 2.25rem;
        color: #ffffff;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.2);
    }
    .member-banner::after {
        content: '';
        position: absolute;
        right: -30px;
        bottom: -30px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.2) 0%, rgba(56, 189, 248, 0) 70%);
        border-radius: 50%;
    }

    /* Stepper For Applicants Only */
    .stepper-box {
        background: #ffffff;
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        border: 1px solid #e2e8f0;
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
        background: #e2e8f0;
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
        border: 3px solid #cbd5e1;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
    }
    .step-item.completed .step-circle {
        background: #10b981;
        border-color: #10b981;
        color: #ffffff;
    }
    .step-item.active .step-circle {
        background: #0284c7;
        border-color: #0284c7;
        color: #ffffff;
        box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.2);
    }
    .step-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }
    .step-desc {
        font-size: 0.75rem;
        color: #64748b;
    }

    /* KTA Card Styling */
    .kta-container {
        background: linear-gradient(135deg, #090d16 0%, #1e1b4b 55%, #0369a1 100%);
        border-radius: var(--radius-xl);
        padding: 2rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25);
    }
    .kta-chip-card {
        width: 45px;
        height: 34px;
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        border-radius: 6px;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
    }
    .kta-photo {
        width: 105px;
        height: 130px;
        object-fit: cover;
        border-radius: 8px;
        border: 3px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.35);
        background: #334155;
    }

    /* Dashboard Layout Grid */
    .dashboard-two-col {
        display: grid;
        grid-template-columns: 1.8fr 1.2fr;
        gap: 2rem;
    }
    @media (max-width: 1024px) {
        .dashboard-two-col {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')

@if ($isAccepted)
    <!-- ========================================== -->
    <!-- TAMPILAN RESMI ANGGOTA (SUDAH DITERIMA)     -->
    <!-- Stepper & Jadwal Wawancara Langsung Hilang -->
    <!-- ========================================== -->

    <!-- Welcome Banner Anggota Resmi -->
    <div class="member-banner">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(52, 211, 153, 0.4); padding: 0.35rem 0.85rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; color: #34d399; margin-bottom: 1rem; backdrop-filter: blur(4px);">
                    <i class="fas fa-certificate"></i> STATUS: ANGGOTA AKTIF TERDAFTAR
                </div>
                <h1 style="font-size: 1.95rem; font-weight: 800; margin: 0 0 0.5rem; letter-spacing: -0.02em;">
                    Halo, {{ $user->name }}!
                </h1>
                <p style="color: #cbd5e1; font-size: 0.95rem; margin: 0 0 1.5rem; max-width: 650px; line-height: 1.6;">
                    Selamat datang di ruang kerja anggota UKM Ilmu Komputer. KTA Digital Anda telah aktif dan dapat dicetak sewaktu-waktu. Pantau rekapitulasi presensi dan silabus divisi Anda di bawah ini.
                </p>
                <div style="display: flex; gap: 0.85rem; flex-wrap: wrap;">
                    <a href="#kta-section" class="btn btn-sm btn-primary" style="box-shadow: 0 4px 6px -1px rgba(37,99,235,0.4);">
                        <i class="fas fa-id-card" style="margin-right: 0.4rem;"></i> Lihat KTA Digital
                    </a>
                    <a href="{{ route('student.profile.edit') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.12); color: #ffffff; border: 1px solid rgba(255,255,255,0.25);">
                        <i class="fas fa-user-gear" style="margin-right: 0.4rem;"></i> Edit Profil & Sandi
                    </a>
                    <button onclick="window.print()" class="btn btn-sm" style="background: rgba(255,255,255,0.12); color: #ffffff; border: 1px solid rgba(255,255,255,0.25);">
                        <i class="fas fa-print" style="margin-right: 0.4rem;"></i> Cetak Dokumen KTA
                    </button>
                </div>
            </div>

            <div style="text-align: right; background: rgba(255,255,255,0.06); padding: 1.25rem 1.5rem; border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.1); min-width: 230px;">
                <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.35rem;">
                    Divisi Resmi Anda
                </div>
                <div style="font-size: 1.15rem; font-weight: 800; color: #38bdf8; margin-bottom: 0.5rem;">
                    {{ $division?->name ?? 'Divisi Pemrograman' }}
                </div>
                <div style="font-size: 0.8rem; color: #cbd5e1;">
                    NIM: <strong>{{ $user->nim }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stat KPI Cards -->
    <div class="stat-cards-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #e0f2fe; color: #0284c7;">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <div style="font-size: 0.775rem; color: #64748b; font-weight: 600;">Divisi Terdaftar</div>
                <div style="font-size: 1.1rem; font-weight: 800; color: #0f172a;">{{ $division?->name }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #dcfce7; color: #16a34a;">
                <i class="fas fa-user-check"></i>
            </div>
            <div>
                <div style="font-size: 0.775rem; color: #64748b; font-weight: 600;">Persentase Presensi</div>
                <div style="font-size: 1.35rem; font-weight: 800; color: #16a34a;">{{ $attendanceStats['percentage'] }}%</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <div style="font-size: 0.775rem; color: #64748b; font-weight: 600;">Kehadiran Sesi</div>
                <div style="font-size: 1.35rem; font-weight: 800; color: #0f172a;">{{ $attendanceStats['attended_count'] }} Hadir</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #f1f5f9; color: #475569;">
                <i class="fas fa-shield-halved"></i>
            </div>
            <div>
                <div style="font-size: 0.775rem; color: #64748b; font-weight: 600;">Status Kartu</div>
                <div style="font-size: 1.05rem; font-weight: 800; color: #16a34a;">KTA Aktif</div>
            </div>
        </div>
    </div>

@else
    <!-- ========================================== -->
    <!-- TAMPILAN CALON MAHASISWA (TAHAP SELEKSI)   -->
    <!-- Menampilkan Stepper & Jadwal Wawancara      -->
    <!-- ========================================== -->

    <div style="background: #ffffff; border-radius: var(--radius-xl); padding: 2rem; border: 1px solid #e2e8f0; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
            <div>
                <span class="badge badge-warning" style="font-size: 0.8rem; margin-bottom: 0.75rem;">
                    <i class="fas fa-hourglass-half" style="margin-right: 0.3rem;"></i> Tahap Seleksi Calon Anggota
                </span>
                <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem;">
                    Selamat Datang, {{ $user->name }}!
                </h1>
                <p style="color: #64748b; font-size: 0.9rem; margin: 0;">
                    Pendaftaran Anda pada <strong>{{ $division?->name }}</strong> sedang dalam proses verifikasi panitia. Pantau tahapan Anda di bawah:
                </p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">KODE PENDAFTARAN</div>
                <div style="font-size: 1.15rem; font-weight: 800; color: #0284c7; font-family: var(--font-mono);">
                    {{ $recruitment?->registration_code ?? 'UKM-2026-PENDING' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Alur Seleksi Stepper (Khusus Calon Anggota) -->
    <div class="stepper-box">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">
                <i class="fas fa-route" style="color: #0284c7; margin-right: 0.5rem;"></i>
                Alur Seleksi Penerimaan Anggota
            </h3>
            <span style="font-size: 0.75rem; font-weight: 600; color: #64748b;">Periode 2026/2027</span>
        </div>

        @php
            $currStatus = $recruitment?->status ?? 'pending';
            $isStep1Done = true;
            $isStep2Active = ($currStatus === 'interview');
            $isStep2Done = ($currStatus === 'accepted');
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
                <div class="step-desc">Tahap Wawancara & Uji Minat</div>
            </div>

            <div class="step-item">
                <div class="step-circle">3</div>
                <div class="step-title">3. Kelulusan</div>
                <div class="step-desc">Penetapan Anggota UKM</div>
            </div>
        </div>

        <!-- Jadwal Wawancara Khusus jika status interview -->
        @if ($recruitment?->status === 'interview' && ($recruitment?->interview_schedule || $recruitment?->interview_location))
            <div style="margin-top: 1.75rem; padding: 1.25rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: var(--radius-md);">
                <div style="font-weight: 700; color: #166534; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-calendar-check" style="color: #16a34a;"></i>
                    Jadwal Wawancara Divisi Anda:
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.875rem;">
                    <div>
                        <span style="color: #64748b; display: block; font-size: 0.75rem;">Waktu & Tanggal:</span>
                        <strong style="color: #1e293b;">
                            {{ \Carbon\Carbon::parse($recruitment->interview_schedule)->translatedFormat('l, d F Y - H:i') }} WIB
                        </strong>
                    </div>
                    <div>
                        <span style="color: #64748b; display: block; font-size: 0.75rem;">Lokasi / Ruangan:</span>
                        <strong style="color: #1e293b;">
                            {{ $recruitment->interview_location ?? 'Sekretariat UKM Ilmu Komputer' }}
                        </strong>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endif

<!-- ========================================== -->
<!-- TWO COLUMN WORKSPACE:                      -->
<!-- Kolom Kiri: Presensi & Silabus             -->
<!-- Kolom Kanan: KTA Digital & Data Kontak     -->
<!-- ========================================== -->
<div class="dashboard-two-col">
    <!-- Left Column: Presensi & Silabus Divisi -->
    <div>
        <!-- Presensi & Riwayat Kehadiran Divisi -->
        <div id="presensi-section" style="background: #ffffff; border-radius: var(--radius-lg); padding: 1.75rem; border: 1px solid #e2e8f0; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">
                        <i class="fas fa-clipboard-user" style="color: #0284c7; margin-right: 0.5rem;"></i>
                        Kehadiran & Presensi Pertemuan
                    </h3>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0.25rem 0 0;">
                        Rekapitulasi kehadiran di sesi pembelajaran divisi formal
                    </p>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 1.6rem; font-weight: 800; color: #0284c7;">
                        {{ $attendanceStats['percentage'] }}%
                    </span>
                    <span style="font-size: 0.75rem; color: #94a3b8; display: block;">Tingkat Kehadiran</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div style="background: #f8fafc; padding: 1rem; border-radius: var(--radius-md); border: 1px solid #e2e8f0;">
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Total Sesi Diselenggarakan</div>
                    <div style="font-size: 1.25rem; font-weight: 800; color: #0f172a;">
                        {{ $attendanceStats['total_sessions'] }} Pertemuan
                    </div>
                </div>
                <div style="background: #f8fafc; padding: 1rem; border-radius: var(--radius-md); border: 1px solid #e2e8f0;">
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Kehadiran Tercatat</div>
                    <div style="font-size: 1.25rem; font-weight: 800; color: #16a34a;">
                        {{ $attendanceStats['attended_count'] }} Hadir
                    </div>
                </div>
            </div>

            @if ($attendanceStats['recent_logs']->count() > 0)
                <div class="table-responsive">
                    <table class="table" style="font-size: 0.85rem; margin-bottom: 0;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                <th style="padding: 0.75rem 1rem; color: #475569; font-weight: 700;">Topik Pertemuan</th>
                                <th style="padding: 0.75rem 1rem; color: #475569; font-weight: 700;">Tanggal</th>
                                <th style="padding: 0.75rem 1rem; color: #475569; font-weight: 700;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendanceStats['recent_logs'] as $log)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 0.85rem 1rem;">
                                        <strong>{{ $log->session?->title }}</strong>
                                        <div style="font-size: 0.75rem; color: #64748b;">
                                            {{ $log->session?->location }} &bull; {{ substr($log->session?->time_start, 0, 5) }} WIB
                                        </div>
                                    </td>
                                    <td style="padding: 0.85rem 1rem; color: #334155;">
                                        {{ \Carbon\Carbon::parse($log->session?->session_date)->translatedFormat('d M Y') }}
                                    </td>
                                    <td style="padding: 0.85rem 1rem;">
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
                <div style="text-align: center; padding: 1.5rem; background: #f8fafc; border-radius: var(--radius-md); color: #64748b; font-size: 0.85rem; border: 1px dashed #cbd5e1;">
                    <i class="fas fa-calendar-xmark" style="font-size: 1.5rem; color: #94a3b8; margin-bottom: 0.5rem; display: block;"></i>
                    Belum ada riwayat pertemuan presensi yang tercatat untuk akun Anda.
                </div>
            @endif
        </div>

        <!-- Silabus & Materi Pembelajaran Divisi -->
        <div id="silabus-section" style="background: #ffffff; border-radius: var(--radius-lg); padding: 1.75rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">
                    <i class="fas fa-book-bookmark" style="color: #0284c7; margin-right: 0.5rem;"></i>
                    Silabus & Riset Divisi {{ $division?->name }}
                </h3>
                <span class="badge badge-info" style="font-size: 0.75rem;">Kurikulum Resmi</span>
            </div>
            <p style="font-size: 0.825rem; color: #64748b; margin-bottom: 1.25rem;">
                Materi teknis terarah dan kurikulum riset yang akan Anda kuasai selama berdinamika di divisi ini:
            </p>

            <div style="display: flex; flex-wrap: wrap; gap: 0.65rem;">
                @if (is_array($syllabus) && count($syllabus) > 0)
                    @foreach ($syllabus as $item)
                        <span style="background: #f1f5f9; color: #1e293b; border: 1px solid #e2e8f0; padding: 0.5rem 0.85rem; border-radius: var(--radius-md); font-size: 0.825rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.45rem;">
                            <i class="fas fa-check-circle" style="color: #0284c7; font-size: 0.8rem;"></i>
                            {{ $item }}
                        </span>
                    @endforeach
                @else
                    <span style="color: #94a3b8; font-size: 0.85rem;">Topik pembelajaran belum diperbarui oleh ketua divisi.</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Digital KTA Card & Student Information -->
    <div>
        <!-- Kartu Tanda Anggota (KTA) Digital -->
        <div id="kta-section">
            @if ($isAccepted)
                <!-- KTA Resmi Aktif -->
                <div class="kta-container">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #ffffff; color: #0f172a; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                UKM
                            </div>
                            <div>
                                <div style="font-size: 0.8rem; font-weight: 800; letter-spacing: 0.05em; line-height: 1.1;">KARTU TANDA ANGGOTA</div>
                                <div style="font-size: 0.675rem; color: rgba(255,255,255,0.75);">UKM ILMU KOMPUTER</div>
                            </div>
                        </div>
                        <div class="kta-chip-card"></div>
                    </div>

                    <div style="display: flex; gap: 1.25rem; align-items: center; margin-bottom: 1.5rem;">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="kta-photo">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 0.65rem; color: rgba(255,255,255,0.65); text-transform: uppercase; font-weight: 600;">
                                Nama Anggota
                            </div>
                            <div style="font-size: 1.05rem; font-weight: 800; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.4rem;">
                                {{ $user->name }}
                            </div>

                            <div style="font-size: 0.65rem; color: rgba(255,255,255,0.65); text-transform: uppercase; font-weight: 600;">
                                Nomor Induk Mahasiswa
                            </div>
                            <div style="font-size: 0.95rem; font-weight: 700; letter-spacing: 0.05em; color: #38bdf8; margin-bottom: 0.4rem; font-family: var(--font-mono);">
                                {{ $user->nim ?? '2401010000' }}
                            </div>

                            <div style="font-size: 0.65rem; color: rgba(255,255,255,0.65); text-transform: uppercase; font-weight: 600;">
                                Divisi Resmi
                            </div>
                            <div style="font-size: 0.85rem; font-weight: 700; color: #f8fafc;">
                                {{ $division?->name ?? 'Divisi Pemrograman' }}
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: flex-end; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.18);">
                        <div>
                            <div style="font-size: 0.65rem; color: rgba(255,255,255,0.65);">VALIDASI RESMI</div>
                            <div style="font-size: 0.775rem; font-weight: 800; color: #34d399;">
                                <i class="fas fa-shield-halved" style="margin-right: 0.25rem;"></i> TERVERIFIKASI SISTEM
                            </div>
                        </div>
                        <!-- QR Code Verified -->
                        <div style="background: #ffffff; padding: 4px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=64x64&data={{ urlencode(url('/verifikasi?code=' . ($recruitment?->registration_code ?? $user->nim))) }}" alt="QR Code" style="width: 52px; height: 52px; display: block;">
                        </div>
                    </div>
                </div>

                <div style="margin-top: 0.85rem; text-align: center;">
                    <button onclick="window.print()" class="btn btn-outline btn-sm" style="width: 100%;">
                        <i class="fas fa-download" style="margin-right: 0.4rem;"></i> Unduh / Cetak KTA Digital (PDF)
                    </button>
                </div>

            @else
                <!-- KTA Terkunci / Belum Diterbitkan (Sesuai Permintaan User) -->
                <div style="background: #ffffff; border-radius: var(--radius-xl); padding: 2rem; border: 1px dashed #cbd5e1; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h4 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 0.4rem;">
                        Kartu Tanda Anggota (KTA) Belum Terbit
                    </h4>
                    <p style="font-size: 0.825rem; color: #64748b; line-height: 1.6; margin-bottom: 1.25rem;">
                        KTA Digital resmi berserta QR Code verifikasi akan otomatis terbit dan dapat diunduh begitu Anda dinyatakan <strong>Lolos Seleksi</strong> oleh pengurus UKM.
                    </p>
                    <span class="badge badge-warning" style="font-size: 0.775rem;">
                        <i class="fas fa-hourglass-start" style="margin-right: 0.3rem;"></i> Menunggu Kelulusan
                    </span>
                </div>
            @endif
        </div>

        <!-- Student Data Card Details -->
        <div style="background: #ffffff; border-radius: var(--radius-lg); padding: 1.5rem; border: 1px solid #e2e8f0; margin-top: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin: 0;">
                    Informasi Akun Mahasiswa
                </h4>
                <a href="{{ route('student.profile.edit') }}" style="font-size: 0.775rem; color: #0284c7; font-weight: 600; text-decoration: none;">
                    Edit Data &rarr;
                </a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.85rem;">
                <div>
                    <span style="color: #64748b; display: block; font-size: 0.75rem;">Email Login:</span>
                    <strong style="color: #1e293b;">{{ $user->email }}</strong>
                </div>
                <div>
                    <span style="color: #64748b; display: block; font-size: 0.75rem;">Nomor WhatsApp:</span>
                    <strong style="color: #1e293b;">{{ $user->phone_number ?? '-' }}</strong>
                </div>
                <div>
                    <span style="color: #64748b; display: block; font-size: 0.75rem;">Profil GitHub:</span>
                    @if ($user->github_url)
                        <a href="{{ $user->github_url }}" target="_blank" style="color: #0284c7; font-weight: 600; text-decoration: none;">
                            <i class="fab fa-github" style="margin-right: 0.25rem;"></i> {{ $user->github_url }}
                        </a>
                    @else
                        <span style="color: #94a3b8;">Belum ditambahkan</span>
                    @endif
                </div>
            </div>

            <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid #f1f5f9;">
                <a href="{{ route('student.profile.edit') }}" class="btn btn-outline btn-sm" style="width: 100%; text-align: center;">
                    <i class="fas fa-user-pen" style="margin-right: 0.4rem;"></i> Kelola Biodata & Kata Sandi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
