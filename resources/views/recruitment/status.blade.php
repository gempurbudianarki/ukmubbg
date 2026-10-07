@extends('layouts.app')

@section('title', 'Cek Status Pendaftaran - UKM Ilmu Komputer')

@section('content')
<div style="padding: 4.5rem 0 2rem;">
    <div class="container-narrow text-center" style="text-align: center;">
        <span class="badge badge-info" style="margin-bottom: 0.75rem;">Portal Calon Anggota</span>
        <h1 style="font-size: 2.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.03em; margin-bottom: 0.75rem;">
            Cek Status Seleksi Penerimaan
        </h1>
        <p style="color: var(--slate-600); font-size: 1.1rem; line-height: 1.6;">
            Masukkan Nomor Induk Mahasiswa (NIM) atau Kode Pendaftaran unik Anda untuk memantau progres verifikasi berkas, jadwal wawancara, dan pengumuman akhir.
        </p>

        <!-- Search Form -->
        <form action="{{ route('recruitment.status') }}" method="GET" style="max-width: 550px; margin: 2.5rem auto 0; display: flex; gap: 0.5rem;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik NIM atau Kode Pendaftaran (contoh: 220104012)..." class="form-control" style="font-size: 1rem; padding: 0.85rem 1.15rem; font-family: var(--font-mono);" required>
            <button type="submit" class="btn btn-primary" style="padding: 0.85rem 1.75rem;">
                Cek Status
            </button>
        </form>
    </div>
</div>

<div class="container-narrow" style="padding: 1.5rem 1.5rem 5rem;">
    @if ($search)
        @if ($applicant)
            <div class="card" style="padding: 2.75rem 2.5rem; border-radius: var(--radius-xl); box-shadow: var(--clay-card); border: none;">
                <!-- Header Card -->
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 1.5rem;">
                    <div>
                        <span class="badge {{ $applicant->status_badge_class }}" style="font-size: 0.85rem; padding: 0.35rem 0.95rem; box-shadow: var(--clay-pill);">
                            {{ $applicant->status_label }}
                        </span>
                        <h2 style="font-size: 1.85rem; font-weight: 800; color: var(--slate-900); margin-top: 0.65rem;">
                            {{ $applicant->full_name }}
                        </h2>
                        <div style="font-size: 0.9rem; color: var(--slate-500); font-family: var(--font-mono); margin-top: 0.35rem;">
                            NIM: <strong>{{ $applicant->nim }}</strong> &bull; Kode: <strong>{{ $applicant->registration_code }}</strong>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 0.8rem; color: var(--slate-400);">Tanggal Masuk</span>
                        <div style="font-size: 0.9rem; font-weight: 600; color: var(--slate-700);">
                            {{ $applicant->created_at->format('d F Y, H:i') }} WIB
                        </div>
                    </div>
                </div>

                <!-- Visual Horizontal Stage Stepper -->
                @php
                    $currentStage = $applicant->selection_stage ?? 'administrasi';
                    $stageIndex = match($currentStage) {
                        'administrasi' => 1,
                        'wawancara' => 2,
                        'diterima', 'ditolak' => 3,
                        default => 1,
                    };
                @endphp

                <div style="margin-bottom: 2.5rem;">
                    <div style="font-size: 0.825rem; font-weight: 700; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">
                        Tahapan Seleksi Terpadu:
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                        <!-- Step 1 -->
                        <div style="padding: 1.25rem; border-radius: var(--radius-md); box-shadow: {{ $stageIndex >= 1 ? 'var(--clay-pill)' : 'var(--clay-debossed)' }}; background: {{ $stageIndex >= 1 ? '#eff6ff' : 'var(--bg-body)' }}; border: none;">
                            <div style="font-size: 0.75rem; font-weight: 800; color: {{ $stageIndex >= 1 ? '#2563eb' : 'var(--slate-400)' }};">
                                TAHAP 1
                            </div>
                            <div style="font-size: 0.925rem; font-weight: 700; color: var(--slate-900); margin-top: 0.25rem;">
                                Seleksi Administrasi & Berkas
                            </div>
                            <div style="font-size: 0.775rem; color: var(--slate-500); margin-top: 0.35rem;">
                                {{ $stageIndex > 1 ? '✓ Lolos Verifikasi' : 'Sedang Ditinjau' }}
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div style="padding: 1.25rem; border-radius: var(--radius-md); box-shadow: {{ $stageIndex >= 2 ? 'var(--clay-pill)' : 'var(--clay-debossed)' }}; background: {{ $stageIndex >= 2 ? '#eff6ff' : 'var(--bg-body)' }}; border: none;">
                            <div style="font-size: 0.75rem; font-weight: 800; color: {{ $stageIndex >= 2 ? '#2563eb' : 'var(--slate-400)' }};">
                                TAHAP 2
                            </div>
                            <div style="font-size: 0.925rem; font-weight: 700; color: var(--slate-900); margin-top: 0.25rem;">
                                Sesi Wawancara Divisi
                            </div>
                            <div style="font-size: 0.775rem; color: var(--slate-500); margin-top: 0.35rem;">
                                {{ $stageIndex > 2 ? '✓ Selesai Wawancara' : ($stageIndex === 2 ? 'Sedang Berlangsung' : 'Menunggu') }}
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div style="padding: 1.25rem; border-radius: var(--radius-md); box-shadow: {{ $stageIndex >= 3 ? 'var(--clay-pill)' : 'var(--clay-debossed)' }}; background: {{ $stageIndex >= 3 ? ($applicant->status === 'accepted' ? '#ecfdf5' : '#fef2f2') : 'var(--bg-body)' }}; border: none;">
                            <div style="font-size: 0.75rem; font-weight: 800; color: {{ $stageIndex >= 3 ? ($applicant->status === 'accepted' ? '#059669' : '#dc2626') : 'var(--slate-400)' }};">
                                TAHAP 3
                            </div>
                            <div style="font-size: 0.925rem; font-weight: 700; color: var(--slate-900); margin-top: 0.25rem;">
                                Pengumuman Final
                            </div>
                            <div style="font-size: 0.775rem; color: var(--slate-500); margin-top: 0.35rem;">
                                {{ $stageIndex >= 3 ? ($applicant->status === 'accepted' ? 'Resmi Diterima' : 'Tidak Lolos') : 'Menunggu Hasil' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Division Selection Summary -->
                <div style="background: var(--bg-body); box-shadow: var(--clay-debossed); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 2rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <span style="font-size: 0.75rem; color: var(--slate-400); text-transform: uppercase; font-weight: 700;">Divisi Pilihan 1:</span>
                            <div style="font-weight: 800; color: {{ $applicant->firstChoiceDivision->color_accent }}; font-size: 1.1rem; margin-top: 0.25rem;">
                                {{ $applicant->firstChoiceDivision->name }}
                            </div>
                        </div>
                        <div>
                            <span style="font-size: 0.75rem; color: var(--slate-400); text-transform: uppercase; font-weight: 700;">Divisi Pilihan 2:</span>
                            <div style="font-weight: 700; color: var(--slate-700); font-size: 1.1rem; margin-top: 0.25rem;">
                                {{ $applicant->secondChoiceDivision?->name ?? 'Tidak Ada Pilihan Kedua' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interview Schedule Details if assigned -->
                @if ($applicant->interview_schedule)
                    <div style="background: #f0f9ff; box-shadow: var(--clay-pill); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 1.5rem;">
                        <h4 style="font-size: 0.95rem; font-weight: 800; color: #0369a1; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Jadwal Wawancara Telah Ditentukan:</span>
                        </h4>
                        <div style="font-size: 0.9rem; color: #0c4a6e; line-height: 1.6;">
                            <div><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($applicant->interview_schedule)->format('d F Y, H:i') }} WIB</div>
                            <div><strong>Tempat / Media:</strong> {{ $applicant->interview_location ?? 'Ruang Lab Terpadu Komputer' }}</div>
                        </div>
                    </div>
                @endif

                <!-- Status Explanations -->
                @if ($applicant->status === 'pending')
                    <div style="background: #fffbeb; box-shadow: var(--clay-pill); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 1.5rem;">
                        <h4 style="font-size: 0.95rem; font-weight: 800; color: #92400e; margin-bottom: 0.35rem;">
                            Tahap Verifikasi Berkas Sedang Berjalan
                        </h4>
                        <p style="font-size: 0.875rem; color: #78350f; line-height: 1.6; margin: 0;">
                            Berkas, KTM, dan motivasi Anda sedang ditinjau oleh pengurus Divisi {{ $applicant->firstChoiceDivision->name }}. Pastikan nomor WhatsApp Anda aktif untuk menerima undangan wawancara.
                        </p>
                    </div>
                @elseif ($applicant->status === 'interview')
                    <div style="background: #f0f9ff; box-shadow: var(--clay-pill); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 1.5rem;">
                        <h4 style="font-size: 0.95rem; font-weight: 800; color: #075985; margin-bottom: 0.35rem;">
                            Selamat! Anda Berhak Mengikuti Wawancara Divisi
                        </h4>
                        <p style="font-size: 0.875rem; color: #0c4a6e; line-height: 1.6; margin: 0;">
                            Berkas Anda dinyatakan lolos kualifikasi administrasi. Harap hadir tepat waktu sesuai jadwal wawancara di atas.
                        </p>
                    </div>
                @elseif ($applicant->status === 'accepted')
                    <div style="background: #ecfdf5; box-shadow: var(--clay-pill); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 1.5rem;">
                        <h4 style="font-size: 0.95rem; font-weight: 800; color: #065f46; margin-bottom: 0.35rem;">
                            Selamat! Anda Resmi Diterima Sebagai Anggota Baru
                        </h4>
                        <p style="font-size: 0.875rem; color: #064e3b; line-height: 1.6; margin: 0;">
                            Selamat bergabung di keluarga besar UKM Ilmu Komputer pada bidang <strong>{{ $applicant->firstChoiceDivision->name }}</strong>! Harap bersiap untuk agenda First Gathering anggota baru.
                        </p>
                    </div>
                @elseif ($applicant->status === 'rejected')
                    <div style="background: #fef2f2; box-shadow: var(--clay-pill); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 1.5rem;">
                        <h4 style="font-size: 0.95rem; font-weight: 800; color: #991b1b; margin-bottom: 0.35rem;">
                            Belum Berhasil di Periode Ini
                        </h4>
                        <p style="font-size: 0.875rem; color: #7f1d1d; line-height: 1.6; margin: 0;">
                            Terima kasih atas antusiasme Anda. Tetap semangat, Anda tetap dipersilakan mengikuti seluruh workshop dan seminar terbuka UKM kami.
                        </p>
                    </div>
                @endif

                @if ($applicant->admin_notes)
                    <div style="background: var(--bg-body); box-shadow: var(--clay-debossed); border-radius: var(--radius-md); padding: 1.25rem; margin-top: 1rem;">
                        <strong style="font-size: 0.8rem; color: var(--slate-600); text-transform: uppercase;">Catatan Khusus Pengurus:</strong>
                        <div style="font-size: 0.9rem; color: var(--slate-900); margin-top: 0.35rem; line-height: 1.5;">
                            {{ $applicant->admin_notes }}
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="card" style="text-align: center; padding: 3.5rem 2rem; border-radius: var(--radius-xl); box-shadow: var(--clay-card); border: none;">
                <p style="font-size: 1.2rem; font-weight: 800; color: var(--slate-800); margin-bottom: 0.5rem;">
                    Data pendaftar dengan NIM / Kode <strong>"{{ $search }}"</strong> tidak ditemukan.
                </p>
                <p style="color: var(--slate-500); font-size: 0.95rem; margin-bottom: 1.75rem;">
                    Pastikan angka NIM atau kode registrasi yang Anda masukkan sudah benar tanpa spasi.
                </p>
                <a href="{{ route('recruitment.index') }}" class="btn btn-primary btn-sm" style="border-radius: var(--radius-full);">
                    Daftar Sebagai Anggota Baru Sekarang
                </a>
            </div>
        @endif
    @else
        <!-- Initial Guidance Card -->
        <div class="card" style="text-align: center; padding: 3.5rem 2rem; border-radius: var(--radius-xl); box-shadow: var(--clay-card); border: none;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: var(--clay-pill);">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.5rem;">
                Cari Data Pendaftaran Anda
            </h3>
            <p style="color: var(--slate-600); max-width: 480px; margin: 0 auto; font-size: 0.95rem; line-height: 1.6;">
                Ketikkan NIM atau Kode Pendaftaran unik yang Anda dapatkan setelah submit formulir untuk melihat status verifikasi.
            </p>
        </div>
    @endif
</div>
@endsection
