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
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
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
        transition: all 0.2s ease;
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
        font-weight: 700;
        font-size: 0.875rem;
        color: #1e293b;
        margin-bottom: 0.2rem;
    }
    .step-desc {
        font-size: 0.75rem;
        color: #64748b;
        max-width: 140px;
    }

    /* Feature Flow Cards Grid */
    .feature-flow-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(310px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .feature-card {
        background: #ffffff;
        border-radius: var(--radius-xl);
        padding: 1.75rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -5px rgba(0,0,0,0.08);
        border-color: #cbd5e1;
    }
    .feature-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }
    .feature-card-icon {
        width: 52px;
        height: 52px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.45rem;
    }

    /* Two column bottom workspace */
    .dashboard-bottom-grid {
        display: grid;
        grid-template-columns: 1.35fr 1fr;
        gap: 1.75rem;
    }
    @media (max-width: 992px) {
        .dashboard-bottom-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')

@if ($isAccepted)
    <!-- ========================================== -->
    <!-- TAMPILAN RESMI ANGGOTA UKM AKTIF           -->
    <!-- Alur Seleksi & Jadwal Wawancara DIHILANGKAN-->
    <!-- ========================================== -->

    <!-- Welcome Hero Banner -->
    <div class="member-banner">
        <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); font-size: 0.8rem; margin-bottom: 0.75rem;">
                    <i class="fas fa-shield-halved" style="margin-right: 0.35rem;"></i> STATUS: ANGGOTA AKTIF TERDAFTAR
                </span>
                <h1 style="font-size: 1.85rem; font-weight: 800; margin: 0 0 0.5rem; letter-spacing: -0.02em;">
                    Selamat Datang, {{ $user->name }}!
                </h1>
                <p style="color: #cbd5e1; font-size: 0.95rem; margin: 0 0 1.25rem; max-width: 600px; line-height: 1.5;">
                    Portal resmi mahasiswa UKM Ilmu Komputer. Akses Kartu Tanda Anggota (KTA) Digital, rekap presensi per divisi, modul silabus pembelajaran, dan kelola biodata akun Anda secara terpusat.
                </p>
                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                    <a href="{{ route('student.kta') }}" class="btn btn-primary btn-sm" style="background: #0284c7; border: none; font-weight: 700; box-shadow: 0 4px 10px rgba(2, 132, 199, 0.4);">
                        <i class="fas fa-id-card" style="margin-right: 0.4rem;"></i> Kartu Anggota (KTA)
                    </a>
                    <a href="{{ route('student.presensi') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.15); color: #ffffff; border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-clipboard-check" style="margin-right: 0.4rem;"></i> Presensi Divisi
                    </a>
                    <a href="{{ route('student.silabus') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.15); color: #ffffff; border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-book-open" style="margin-right: 0.4rem;"></i> Silabus & Riset
                    </a>
                </div>
            </div>

            <div style="text-align: right; background: rgba(255,255,255,0.06); padding: 1.25rem 1.5rem; border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.12); min-width: 230px;">
                <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.35rem;">
                    Divisi Resmi Anda
                </div>
                <div style="font-size: 1.2rem; font-weight: 800; color: #38bdf8; margin-bottom: 0.5rem;">
                    {{ $division?->name ?? 'Divisi Pemrograman' }}
                </div>
                <div style="font-size: 0.85rem; color: #cbd5e1;">
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
            <div class="stat-icon" style="background: #f1f5f9; color: #0284c7;">
                <i class="fas fa-shield-halved"></i>
            </div>
            <div>
                <div style="font-size: 0.775rem; color: #64748b; font-weight: 600;">Status Kartu</div>
                <div style="font-size: 1.05rem; font-weight: 800; color: #16a34a;">KTA Aktif</div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- ALUR FITUR UTAMA (Dedicated Flow Navigator)-->
    <!-- 3 Card Pintasan Eksklusif per Halaman      -->
    <!-- ========================================== -->
    <div style="margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem;">
            <i class="fas fa-compass" style="color: #0284c7; margin-right: 0.4rem;"></i>
            Alur Fitur & Layanan Anggota
        </h3>
        <p style="font-size: 0.85rem; color: #64748b; margin: 0;">
            Setiap fitur memiliki halaman mandiri yang terfokus untuk menunjang aktivitas keorganisasian Anda.
        </p>
    </div>

    <div class="feature-flow-grid">
        <!-- Feature 1: KTA Digital -->
        <div class="feature-card">
            <div>
                <div class="feature-card-header">
                    <div class="feature-card-icon" style="background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <span class="badge badge-success" style="font-size: 0.75rem;">
                        <i class="fas fa-check" style="margin-right: 0.2rem;"></i> TERVERIFIKASI SISTEM
                    </span>
                </div>
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0 0 0.4rem;">
                    KARTU TANDA ANGGOTA
                </h4>
                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.5; margin: 0 0 1.25rem;">
                    Identitas resmi digital anggota UKM Ilmu Komputer dilengkapi dengan barcode QR verifikasi data resmi dan opsi cetak format kartu fisik.
                </p>
            </div>
            <div>
                <a href="{{ route('student.kta') }}" class="btn btn-outline btn-sm" style="width: 100%; font-weight: 700; border-color: #0284c7; color: #0284c7;">
                    <i class="fas fa-arrow-up-right-from-square" style="margin-right: 0.4rem;"></i> Buka & Cetak Dokumen KTA
                </a>
            </div>
        </div>

        <!-- Feature 2: Presensi Pertemuan -->
        <div class="feature-card">
            <div>
                <div class="feature-card-header">
                    <div class="feature-card-icon" style="background: #dcfce7; color: #16a34a;">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <span class="badge" style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; font-size: 0.75rem; font-weight: 700;">
                        {{ $attendanceStats['percentage'] }}% Keaktifan
                    </span>
                </div>
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0 0 0.4rem;">
                    Presensi & Kehadiran
                </h4>
                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.5; margin: 0 0 1.25rem;">
                    Buku log rekapitulasi kehadiran seluruh pertemuan divisi, workshop, dan riset berkala dengan metrik kehadiran kumulatif.
                </p>
            </div>
            <div>
                <a href="{{ route('student.presensi') }}" class="btn btn-outline btn-sm" style="width: 100%; font-weight: 700; border-color: #16a34a; color: #16a34a;">
                    <i class="fas fa-list-check" style="margin-right: 0.4rem;"></i> Lihat Buku Presensi Lengkap
                </a>
            </div>
        </div>

        <!-- Feature 3: Silabus & Riset -->
        <div class="feature-card">
            <div>
                <div class="feature-card-header">
                    <div class="feature-card-icon" style="background: #f3e8ff; color: #9333ea;">
                        <i class="fas fa-book-bookmark"></i>
                    </div>
                    <span class="badge badge-info" style="font-size: 0.75rem;">
                        {{ $division?->name }}
                    </span>
                </div>
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0 0 0.4rem;">
                    Silabus & Riset Divisi
                </h4>
                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.5; margin: 0 0 1.25rem;">
                    Peta kurikulum bertingkat (Tingkat Dasar, Menengah, Proyek Lanjutan), visi misi divisi, serta profil Dosen Pembina.
                </p>
            </div>
            <div>
                <a href="{{ route('student.silabus') }}" class="btn btn-outline btn-sm" style="width: 100%; font-weight: 700; border-color: #9333ea; color: #9333ea;">
                    <i class="fas fa-graduation-cap" style="margin-right: 0.4rem;"></i> Pelajari Silabus Divisi
                </a>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- WORKSPACE RINGKAS (Bottom 2 Columns)       -->
    <!-- Kiri: Histori Sesi Terakhir               -->
    <!-- Kanan: Info Akun & Edit Profil             -->
    <!-- ========================================== -->
    <div class="dashboard-bottom-grid">
        <!-- Kolom Kiri: Riwayat Presensi Terkini -->
        <div style="background: #ffffff; border-radius: var(--radius-xl); padding: 1.75rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <div>
                    <h4 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0 0 0.2rem;">
                        <i class="fas fa-calendar-day" style="color: #0284c7; margin-right: 0.4rem;"></i>
                        Aktivitas Pertemuan Terakhir
                    </h4>
                    <span style="font-size: 0.775rem; color: #64748b;">
                        Menampilkan sesi paling baru dari {{ $division?->name }}
                    </span>
                </div>
                <a href="{{ route('student.presensi') }}" style="font-size: 0.8rem; font-weight: 700; color: #0284c7; text-decoration: none;">
                    Buku Lengkap &rarr;
                </a>
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
                            @foreach ($attendanceStats['recent_logs']->take(3) as $log)
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
                <div style="text-align: center; padding: 2rem 1.5rem; background: #f8fafc; border-radius: var(--radius-md); color: #64748b; font-size: 0.85rem; border: 1px dashed #cbd5e1;">
                    <i class="fas fa-calendar-check" style="font-size: 1.75rem; color: #94a3b8; margin-bottom: 0.5rem; display: block;"></i>
                    Belum ada riwayat pertemuan presensi yang tercatat untuk akun Anda.
                </div>
            @endif
        </div>

        <!-- Kolom Kanan: Ringkasan Biodata & Akses Profil -->
        <div style="background: #ffffff; border-radius: var(--radius-xl); padding: 1.75rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <h4 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">
                        <i class="fas fa-user" style="color: #0284c7; margin-right: 0.4rem;"></i>
                        Biodata & Kontak
                    </h4>
                    <a href="{{ route('student.profile.edit') }}" style="font-size: 0.775rem; color: #0284c7; font-weight: 700; text-decoration: none;">
                        Edit &rarr;
                    </a>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #f1f5f9;">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 2px solid #0284c7;">
                    <div>
                        <div style="font-weight: 800; font-size: 1rem; color: #0f172a;">{{ $user->name }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-family: var(--font-mono);">NIM: {{ $user->nim }}</div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.85rem;">
                    <div>
                        <span style="color: #64748b; font-size: 0.75rem; display: block;">Alamat Email:</span>
                        <strong style="color: #1e293b;">{{ $user->email }}</strong>
                    </div>
                    <div>
                        <span style="color: #64748b; font-size: 0.75rem; display: block;">Nomor WhatsApp:</span>
                        <strong style="color: #1e293b;">{{ $user->phone_number ?? '-' }}</strong>
                    </div>
                    <div>
                        <span style="color: #64748b; font-size: 0.75rem; display: block;">Tautan GitHub:</span>
                        @if ($user->github_url)
                            <a href="{{ $user->github_url }}" target="_blank" style="color: #0284c7; font-weight: 600; text-decoration: none;">
                                <i class="fab fa-github" style="margin-right: 0.25rem;"></i> {{ $user->github_url }}
                            </a>
                        @else
                            <span style="color: #94a3b8;">Belum ditambahkan</span>
                        @endif
                    </div>
                </div>
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #f1f5f9;">
                <a href="{{ route('student.profile.edit') }}" class="btn btn-outline btn-sm" style="width: 100%; text-align: center; font-weight: 700;">
                    <i class="fas fa-user-gear" style="margin-right: 0.4rem;"></i> Kelola Profil & Kata Sandi
                </a>
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
                @if ($recruitment->interviewer_notes)
                    <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px dashed #bbf7d0; font-size: 0.825rem; color: #15803d;">
                        <strong>Catatan Penguji:</strong> {{ $recruitment->interviewer_notes }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Hub Persiapan Seleksi Calon Anggota -->
    <div class="feature-flow-grid">
        <!-- Card 1: Lengkapi Foto Profil & Biodata -->
        <div class="feature-card">
            <div>
                <div class="feature-card-header">
                    <div class="feature-card-icon" style="background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-user-gear"></i>
                    </div>
                    <span class="badge badge-info" style="font-size: 0.75rem;">Langkah 1</span>
                </div>
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0 0 0.4rem;">
                    Lengkapi Foto & Biodata
                </h4>
                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.5; margin: 0 0 1.25rem;">
                    Pastikan Anda telah mengunggah pas foto formal serta nomor WhatsApp aktif agar panitia mudah menghubungi Anda saat tahapan seleksi.
                </p>
            </div>
            <div>
                <a href="{{ route('student.profile.edit') }}" class="btn btn-primary btn-sm" style="width: 100%; font-weight: 700;">
                    <i class="fas fa-user-pen" style="margin-right: 0.4rem;"></i> Perbarui Foto & Biodata
                </a>
            </div>
        </div>

        <!-- Card 2: Pelajari Silabus Divisi -->
        <div class="feature-card">
            <div>
                <div class="feature-card-header">
                    <div class="feature-card-icon" style="background: #f3e8ff; color: #9333ea;">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <span class="badge badge-info" style="font-size: 0.75rem;">Langkah 2</span>
                </div>
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0 0 0.4rem;">
                    Pelajari Silabus Divisi
                </h4>
                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.5; margin: 0 0 1.25rem;">
                    Pelajari kurikulum, roadmap materi riset, serta visi misi {{ $division?->name }} untuk bekal persiapan dalam sesi wawancara.
                </p>
            </div>
            <div>
                <a href="{{ route('student.silabus') }}" class="btn btn-outline btn-sm" style="width: 100%; font-weight: 700; border-color: #9333ea; color: #9333ea;">
                    <i class="fas fa-graduation-cap" style="margin-right: 0.4rem;"></i> Buka Silabus Divisi
                </a>
            </div>
        </div>

        <!-- Card 3: Status KTA Digital (Terkunci) -->
        <div class="feature-card" style="background: #f8fafc;">
            <div>
                <div class="feature-card-header">
                    <div class="feature-card-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="fas fa-lock"></i>
                    </div>
                    <span class="badge badge-warning" style="font-size: 0.75rem;">Menunggu Lulus</span>
                </div>
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0 0 0.4rem;">
                    Kartu Tanda Anggota (KTA) Belum Terbit
                </h4>
                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.5; margin: 0 0 1.25rem;">
                    KTA Digital resmi berserta QR Code verifikasi akan otomatis terbit dan dapat diunduh begitu Anda dinyatakan lolos seluruh tahapan seleksi.
                </p>
            </div>
            <div>
                <a href="{{ route('student.kta') }}" class="btn btn-outline btn-sm" style="width: 100%; font-weight: 700; color: #64748b; border-color: #cbd5e1;">
                    <i class="fas fa-shield-halved" style="margin-right: 0.4rem;"></i> Cek Ketentuan KTA
                </a>
            </div>
        </div>
    </div>
@endif

@endsection
