@extends('student.layouts.app')

@section('title', 'Presensi & Kehadiran Pertemuan')
@section('page_title', 'Presensi & Kehadiran Pertemuan')

@section('styles')
<style>
    .presensi-hero-card {
        background: #ffffff;
        border-radius: var(--radius-xl);
        padding: 1.75rem 2rem;
        box-shadow: var(--clay-card);
        border: 1.5px solid rgba(226, 232, 240, 0.9);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .presensi-metric-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .presensi-metric-card {
        background: #ffffff;
        border-radius: var(--radius-xl);
        padding: 1.35rem 1.5rem;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: var(--clay-card);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .presensi-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--clay-button-hover);
    }
    .presensi-table-card {
        background: #ffffff;
        border-radius: var(--radius-xl);
        border: 1px solid rgba(226, 232, 240, 0.9);
        overflow: hidden;
        box-shadow: var(--clay-card);
    }
    .pulse-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 0 rgba(16, 185, 129, 0.6);
        animation: pulseAnimation 1.8s infinite;
        margin-right: 0.35rem;
    }
    @keyframes pulseAnimation {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>
@endsection

@section('content')

<!-- Header Info & Persentase Ringkasan -->
<div style="background: #ffffff; border-radius: var(--radius-xl); padding: 1.5rem 1.85rem; border: 1.5px solid rgba(226, 232, 240, 0.9); box-shadow: var(--clay-card); margin-bottom: 1.75rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
            <span class="badge" style="background: {{ $division?->color_accent ?? '#0284c7' }}15; color: {{ $division?->color_accent ?? '#0284c7' }}; font-weight: 800; font-size: 0.75rem;">
                {{ $division?->name ?? 'Divisi UKM' }}
            </span>
            <span style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Tahun Ajaran 2026/2027</span>
        </div>
        <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem;">
            Buku Presensi & Kehadiran Anggota
        </h2>
        <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
            Pantau keaktifan riset, workshop berkala, dan lakukan presensi mandiri saat sesi dibuka oleh ketua divisi.
        </p>
    </div>
    <div style="text-align: right; background: #f0f9ff; padding: 0.75rem 1.25rem; border-radius: 14px; border: 1px solid rgba(2, 132, 199, 0.2);">
        <span style="font-size: 1.85rem; font-weight: 900; color: #0284c7; font-family: var(--font-mono); line-height: 1;">
            {{ $attendanceStats['percentage'] }}%
        </span>
        <div style="font-size: 0.7rem; color: #64748b; font-weight: 800; letter-spacing: 0.06em; margin-top: 0.2rem;">
            KEHADIRAN KUMULATIF
        </div>
    </div>
</div>

<!-- ==========================================
     KARTU SESI AKTIF & PRESENSI MANDIRI (HERO)
     ========================================== -->
@if (isset($activeSession) && $activeSession)
    <div class="presensi-hero-card">
        @if (isset($activeSessionLog) && $activeSessionLog && in_array($activeSessionLog->status, ['hadir', 'izin', 'sakit']))
            <!-- Kehadiran / Izin Sudah Terkonfirmasi -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1.25rem;">
                <div style="display: flex; gap: 1.25rem; align-items: flex-start;">
                    @php
                        $iconColor = $activeSessionLog->status === 'hadir' ? '#059669' : ($activeSessionLog->status === 'izin' ? '#0284c7' : '#d97706');
                        $bgColor = $activeSessionLog->status === 'hadir' ? '#ecfdf5' : ($activeSessionLog->status === 'izin' ? '#f0f9ff' : '#fef3c7');
                        $borderColor = $activeSessionLog->status === 'hadir' ? 'rgba(16, 185, 129, 0.3)' : ($activeSessionLog->status === 'izin' ? 'rgba(2, 132, 199, 0.3)' : 'rgba(217, 119, 6, 0.3)');
                        $badgeClass = $activeSessionLog->status === 'hadir' ? 'badge-success' : ($activeSessionLog->status === 'izin' ? 'badge-info' : 'badge-warning');
                    @endphp
                    <div style="width: 52px; height: 52px; border-radius: 14px; background: {{ $bgColor }}; border: 1.5px solid {{ $borderColor }}; color: {{ $iconColor }}; display: flex; align-items: center; justify-content: center; font-size: 1.65rem; flex-shrink: 0; box-shadow: var(--clay-pill);">
                        <i class="fas {{ $activeSessionLog->status === 'hadir' ? 'fa-circle-check' : ($activeSessionLog->status === 'izin' ? 'fa-file-lines' : 'fa-head-side-cough') }}"></i>
                    </div>
                    <div>
                        <div style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.35rem;">
                            <span class="badge {{ $badgeClass }}" style="font-size: 0.72rem; padding: 0.2rem 0.65rem;">
                                <i class="fas fa-check"></i> {{ strtoupper($activeSessionLog->status) }} Terverifikasi
                            </span>
                            <span style="font-size: 0.75rem; color: #64748b;">
                                {{ $activeSession->day_name }}, {{ $activeSession->session_date->format('d M Y') }}
                            </span>
                        </div>
                        <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem;">
                            {{ $activeSession->title }}
                        </h3>
                        <div style="font-size: 0.85rem; color: #475569; display: flex; gap: 1rem; flex-wrap: wrap;">
                            <span><i class="fas fa-clock" style="color: #0284c7; margin-right: 0.3rem;"></i>{{ substr($activeSession->time_start, 0, 5) }} - {{ substr($activeSession->time_end, 0, 5) }} WIB</span>
                            <span><i class="fas fa-location-dot" style="color: #0284c7; margin-right: 0.3rem;"></i>{{ $activeSession->location }}</span>
                            @if ($activeSession->instructor_name)
                                <span><i class="fas fa-chalkboard-user" style="color: #0284c7; margin-right: 0.3rem;"></i>{{ $activeSession->instructor_name }}</span>
                            @endif
                        </div>
                        @if ($activeSessionLog->notes)
                            <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.4rem; background: #f8fafc; padding: 0.3rem 0.65rem; border-radius: 6px; display: inline-block;">
                                <strong>Keterangan:</strong> {{ $activeSessionLog->notes }}
                            </div>
                        @endif
                        @if ($activeSessionLog->attachment)
                            <div style="margin-top: 0.35rem;">
                                <a href="{{ asset('storage/' . $activeSessionLog->attachment) }}" target="_blank" class="btn btn-outline btn-xs" style="font-size: 0.75rem; padding: 0.2rem 0.6rem;">
                                    <i class="fas fa-paperclip"></i> Lihat Lampiran Bukti
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div style="text-align: right; background: {{ $bgColor }}; padding: 0.85rem 1.25rem; border-radius: 14px; border: 1px solid {{ $borderColor }};">
                    <div style="font-size: 0.7rem; font-weight: 800; color: {{ $iconColor }}; text-transform: uppercase;">
                        METODE PRESENSI
                    </div>
                    <div style="font-size: 0.9rem; font-weight: 800; color: #0f172a; margin-top: 0.15rem;">
                        {{ $activeSessionLog->checkin_type === 'self' ? 'Presensi Mandiri' : 'Dicatat oleh Pengurus' }}
                    </div>
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">
                        {{ $activeSessionLog->checked_in_at ? 'Tercatat pukul ' . $activeSessionLog->checked_in_at->format('H:i') . ' WIB' : 'Terkonfirmasi aktif' }}
                    </div>
                </div>
            </div>

            @if ($activeSession->topic_material || $activeSession->learning_outcomes)
                <div style="margin-top: 1.25rem; padding-top: 1.15rem; border-top: 1px solid #f1f5f9; background: #f8fafc; border-radius: 14px; padding: 1.15rem 1.35rem; border: 1.5px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 0.5rem;">
                        <div>
                            <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: #0284c7; letter-spacing: 0.05em; display: block; margin-bottom: 0.2rem;">
                                Materi Hari Ini:
                            </span>
                            <strong style="color: #0f172a; font-size: 1rem; line-height: 1.4;">
                                {{ $activeSession->topic_material ?? $activeSession->title }}
                            </strong>
                        </div>
                        <button type="button" 
                                onclick="openMateriModal({{ json_encode([
                                    'title' => $activeSession->title,
                                    'topic' => $activeSession->topic_material,
                                    'outcomes' => $activeSession->learning_outcomes,
                                    'instructor' => $activeSession->instructor_name,
                                    'notes' => $activeSession->notes,
                                    'date' => $activeSession->session_date->translatedFormat('l, d F Y'),
                                    'time' => substr($activeSession->time_start, 0, 5) . ' - ' . substr($activeSession->time_end, 0, 5) . ' WIB',
                                    'location' => $activeSession->location,
                                    'type' => ucwords(str_replace('_', ' ', $activeSession->session_type)),
                                ]) }})"
                                class="btn btn-sm btn-outline" 
                                style="font-size: 0.78rem; font-weight: 700; border-radius: 8px; padding: 0.4rem 0.95rem; background: #ffffff; border: 1.5px solid #cbd5e1; color: #0284c7; display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                            <i class="fas fa-book-open"></i> Baca Rangkuman Materi Lengkap
                        </button>
                    </div>
                    @if ($activeSession->learning_outcomes)
                        <div style="font-size: 0.85rem; color: #475569; line-height: 1.65; border-top: 1px dashed #cbd5e1; padding-top: 0.65rem; margin-top: 0.4rem;">
                            <strong style="color: #0c2340;">Capaian & Target Pembelajaran:</strong> {{ $activeSession->learning_outcomes }}
                        </div>
                    @endif
                </div>
            @endif

        @else
            <!-- Belum Melakukan Presensi (Sesi Aktif & Bisa Check-in / Ajukan Izin) -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <div style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.35rem; flex-wrap: wrap;">
                        <span class="badge" style="background: #ecfdf5; color: #059669; font-size: 0.72rem; padding: 0.2rem 0.65rem; font-weight: 700;">
                            <span class="pulse-dot"></span> Sesi Pertemuan Sedang Dibuka
                        </span>
                        @if ($activeSession->passcode_expires_at)
                            @if ($activeSession->isPasscodeExpired())
                                <span class="badge badge-danger" style="font-size: 0.72rem;">
                                    <i class="fas fa-hourglass-end"></i> Passcode Kadaluarsa ({{ $activeSession->passcode_expires_at->format('H:i') }})
                                </span>
                            @else
                                <span class="badge badge-info" style="font-size: 0.72rem;">
                                    <i class="fas fa-clock"></i> Aktif Sampai {{ $activeSession->passcode_expires_at->format('H:i') }} WIB
                                </span>
                            @endif
                        @endif
                        <span style="font-size: 0.75rem; color: #64748b;">
                            {{ $activeSession->day_name }}, {{ $activeSession->session_date->format('d M Y') }}
                        </span>
                    </div>
                    <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem;">
                        {{ $activeSession->title }}
                    </h3>
                    <div style="font-size: 0.85rem; color: #475569; display: flex; gap: 1rem; flex-wrap: wrap;">
                        <span><i class="fas fa-clock" style="color: #0284c7; margin-right: 0.3rem;"></i>{{ substr($activeSession->time_start, 0, 5) }} - {{ substr($activeSession->time_end, 0, 5) }} WIB</span>
                        <span><i class="fas fa-location-dot" style="color: #0284c7; margin-right: 0.3rem;"></i>{{ $activeSession->location }}</span>
                        @if ($activeSession->instructor_name)
                            <span><i class="fas fa-chalkboard-user" style="color: #0284c7; margin-right: 0.3rem;"></i>{{ $activeSession->instructor_name }}</span>
                        @endif
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 0.65rem 1rem; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 0.8rem; color: #64748b;">
                    <strong>Status Anda:</strong> <span style="color: #d97706; font-weight: 700;">Belum Mengisi Presensi</span>
                </div>
            </div>

            @if ($activeSession->topic_material || $activeSession->learning_outcomes)
                <div style="background: #f8fafc; padding: 1.15rem 1.35rem; border-radius: 14px; border: 1.5px solid #e2e8f0; margin-bottom: 1.25rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 0.5rem;">
                        <div>
                            <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: #0284c7; letter-spacing: 0.05em; display: block; margin-bottom: 0.2rem;">
                                Pokok Bahasan & Silabus Hari Ini:
                            </span>
                            <strong style="color: #0f172a; font-size: 1rem; line-height: 1.4;">
                                {{ $activeSession->topic_material ?? $activeSession->title }}
                            </strong>
                        </div>
                        <button type="button" 
                                onclick="openMateriModal({{ json_encode([
                                    'title' => $activeSession->title,
                                    'topic' => $activeSession->topic_material,
                                    'outcomes' => $activeSession->learning_outcomes,
                                    'instructor' => $activeSession->instructor_name,
                                    'notes' => $activeSession->notes,
                                    'date' => $activeSession->session_date->translatedFormat('l, d F Y'),
                                    'time' => substr($activeSession->time_start, 0, 5) . ' - ' . substr($activeSession->time_end, 0, 5) . ' WIB',
                                    'location' => $activeSession->location,
                                    'type' => ucwords(str_replace('_', ' ', $activeSession->session_type)),
                                ]) }})"
                                class="btn btn-sm btn-outline" 
                                style="font-size: 0.78rem; font-weight: 700; border-radius: 8px; padding: 0.4rem 0.95rem; background: #ffffff; border: 1.5px solid #cbd5e1; color: #0284c7; display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                            <i class="fas fa-book-open"></i> Baca Rangkuman Materi Lengkap
                        </button>
                    </div>
                    @if ($activeSession->learning_outcomes)
                        <div style="font-size: 0.85rem; color: #475569; line-height: 1.65; border-top: 1px dashed #cbd5e1; padding-top: 0.65rem; margin-top: 0.4rem;">
                            <strong style="color: #0c2340;">Capaian & Target Pembelajaran:</strong> {{ $activeSession->learning_outcomes }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Tabs Pilihan: Presensi Hadir vs Ajukan Izin / Sakit -->
            <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">
                <button type="button" id="tabHadirBtn" onclick="switchPresensiTab('hadir')" style="background: #0284c7; color: #ffffff; border: none; padding: 0.45rem 1.15rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s;">
                    <i class="fas fa-key" style="margin-right: 0.3rem;"></i> Hadir (Passcode)
                </button>
                <button type="button" id="tabIzinBtn" onclick="switchPresensiTab('izin')" style="background: #f1f5f9; color: #475569; border: none; padding: 0.45rem 1.15rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s;">
                    <i class="fas fa-file-signature" style="margin-right: 0.3rem;"></i> Ajukan Izin / Sakit
                </button>
            </div>

            <!-- Panel 1: Presensi Hadir dengan Passcode -->
            <div id="panelHadir">
                <form action="{{ route('student.presensi.checkin') }}" method="POST">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ $activeSession->id }}">

                    <div style="background: #f0f9ff; border: 1.5px solid rgba(2, 132, 199, 0.3); border-radius: 16px; padding: 1.25rem 1.5rem;">
                        <div style="display: grid; grid-template-columns: 1fr 1.2fr auto; gap: 1rem; align-items: flex-end;">
                            <div>
                                <label style="font-size: 0.775rem; font-weight: 800; color: #0369a1; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.35rem;">
                                    <i class="fas fa-key"></i>
                                    Password / Kode Sesi *
                                </label>
                                <input type="text" name="passcode" required placeholder="Contoh: KOMP26" maxlength="20" style="font-family: var(--font-mono); font-size: 1.15rem; font-weight: 900; letter-spacing: 0.12em; text-transform: uppercase; color: #0284c7; padding: 0.55rem 0.85rem; border-radius: 10px; border: 1.5px solid #7dd3fc; width: 100%; background: #ffffff;" autocomplete="off" {{ $activeSession->isPasscodeExpired() ? 'disabled' : '' }}>
                            </div>

                            <div>
                                <label style="font-size: 0.775rem; font-weight: 700; color: #64748b; margin-bottom: 0.35rem; display: block;">
                                    Catatan / Keterangan Kehadiran (Opsional)
                                </label>
                                <input type="text" name="notes" placeholder="Misal: Hadir di Lab Komputer" class="form-control" style="font-size: 0.85rem; padding: 0.6rem 0.85rem; border-radius: 10px; background: #ffffff;" {{ $activeSession->isPasscodeExpired() ? 'disabled' : '' }}>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.5rem; font-weight: 800; font-size: 0.9rem; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3); border-radius: 10px; white-space: nowrap;" {{ $activeSession->isPasscodeExpired() ? 'disabled' : '' }}>
                                    <i class="fas fa-paper-plane" style="margin-right: 0.35rem;"></i>
                                    Kirim Presensi Hadir
                                </button>
                            </div>
                        </div>

                        <div style="font-size: 0.75rem; color: #0369a1; margin-top: 0.75rem; display: flex; align-items: center; gap: 0.4rem;">
                            <i class="fas fa-circle-info"></i>
                            @if ($activeSession->isPasscodeExpired())
                                <span style="color: #dc2626; font-weight: 700;">Masa aktif kode presensi telah habis. Minta ketua divisi membuka kembali atau memperbarui kode.</span>
                            @else
                                <span>Password presensi dibagikan oleh Ketua Divisi / Instruktur saat sesi berlangsung.</span>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Panel 2: Form Ajukan Izin / Sakit -->
            <div id="panelIzin" style="display: none;">
                <form action="{{ route('student.presensi.permission') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ $activeSession->id }}">

                    <div style="background: #fefce8; border: 1.5px solid rgba(234, 179, 8, 0.4); border-radius: 16px; padding: 1.25rem 1.5rem;">
                        <div style="display: grid; grid-template-columns: 180px 1.5fr 1fr auto; gap: 1rem; align-items: flex-end;">
                            <div>
                                <label style="font-size: 0.775rem; font-weight: 800; color: #854d0e; text-transform: uppercase; margin-bottom: 0.35rem; display: block;">
                                    Jenis Pengajuan *
                                </label>
                                <select name="status" class="form-control" required style="font-size: 0.85rem; padding: 0.6rem; border-radius: 10px; background: #ffffff;">
                                    <option value="izin">Izin Tidak Hadir</option>
                                    <option value="sakit">Sakit / Istirahat</option>
                                </select>
                            </div>

                            <div>
                                <label style="font-size: 0.775rem; font-weight: 800; color: #854d0e; text-transform: uppercase; margin-bottom: 0.35rem; display: block;">
                                    Alasan / Keterangan *
                                </label>
                                <input type="text" name="notes" required placeholder="Contoh: Mengikuti lomba kampus / Demam tinggi" class="form-control" style="font-size: 0.85rem; padding: 0.6rem 0.85rem; border-radius: 10px; background: #ffffff;">
                            </div>

                            <div>
                                <label style="font-size: 0.775rem; font-weight: 800; color: #854d0e; text-transform: uppercase; margin-bottom: 0.35rem; display: block;">
                                    Bukti / Surat (Opsional)
                                </label>
                                <input type="file" name="attachment" accept="image/*,.pdf" class="form-control" style="font-size: 0.75rem; padding: 0.45rem 0.65rem; border-radius: 10px; background: #ffffff;">
                            </div>

                            <div>
                                <button type="submit" class="btn btn-warning" style="padding: 0.65rem 1.35rem; font-weight: 800; font-size: 0.875rem; border-radius: 10px; white-space: nowrap; background: #d97706; color: #ffffff;">
                                    <i class="fas fa-paper-plane" style="margin-right: 0.35rem;"></i>
                                    Kirim Pengajuan
                                </button>
                            </div>
                        </div>

                        <div style="font-size: 0.75rem; color: #854d0e; margin-top: 0.75rem; display: flex; align-items: center; gap: 0.4rem;">
                            <i class="fas fa-circle-info"></i>
                            <span>Pengajuan Izin/Sakit akan otomatis tercatat dan dapat ditinjau oleh ketua divisi / pembina UKM.</span>
                        </div>
                    </div>
                </form>
            </div>

            <script>
                function switchPresensiTab(type) {
                    const panelHadir = document.getElementById('panelHadir');
                    const panelIzin = document.getElementById('panelIzin');
                    const btnHadir = document.getElementById('tabHadirBtn');
                    const btnIzin = document.getElementById('tabIzinBtn');

                    if (type === 'hadir') {
                        panelHadir.style.display = 'block';
                        panelIzin.style.display = 'none';
                        btnHadir.style.background = '#0284c7';
                        btnHadir.style.color = '#ffffff';
                        btnIzin.style.background = '#f1f5f9';
                        btnIzin.style.color = '#475569';
                    } else {
                        panelHadir.style.display = 'none';
                        panelIzin.style.display = 'block';
                        btnIzin.style.background = '#d97706';
                        btnIzin.style.color = '#ffffff';
                        btnHadir.style.background = '#f1f5f9';
                        btnHadir.style.color = '#475569';
                    }
                }
            </script>
        @endif
    </div>
@else
    <!-- Tidak Ada Sesi Aktif Hari Ini -->
    <div style="background: #ffffff; border-radius: var(--radius-xl); padding: 1.5rem 1.75rem; border: 1.5px solid rgba(226, 232, 240, 0.9); box-shadow: var(--clay-card); margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0; color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <strong style="font-size: 0.95rem; color: #0f172a; display: block;">
                    Tidak Ada Sesi Presensi yang Sedang Dibuka
                </strong>
                <span style="font-size: 0.8rem; color: #64748b;">
                    Form presensi mandiri akan otomatis aktif di sini saat ketua divisi membuka sesi riset atau workshop berikutnya.
                </span>
            </div>
        </div>
        <span class="badge badge-neutral" style="font-size: 0.75rem;">
            Standby Agenda
        </span>
    </div>
@endif

<!-- ==========================================
     4 METRIC CARDS (WHITE CLAYMORPHISM)
     ========================================== -->
<div class="presensi-metric-grid">
    <div class="presensi-metric-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Total Pertemuan</div>
                <div style="font-size: 1.75rem; font-weight: 900; color: #0f172a; margin-top: 0.25rem; font-family: var(--font-mono);">
                    {{ $attendanceStats['total_sessions'] }}
                </div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: #f1f5f9; color: #475569; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                <i class="fas fa-calendar-days"></i>
            </div>
        </div>
        <small style="color: #94a3b8; font-size: 0.725rem; margin-top: 0.5rem; display: block;">Sesi resmi diselenggarakan divisi</small>
    </div>

    <div class="presensi-metric-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: 0.72rem; color: #059669; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Hadir Tepat Waktu</div>
                <div style="font-size: 1.75rem; font-weight: 900; color: #059669; margin-top: 0.25rem; font-family: var(--font-mono);">
                    {{ $attendanceStats['attended_count'] }}
                </div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                <i class="fas fa-circle-check"></i>
            </div>
        </div>
        <small style="color: #94a3b8; font-size: 0.725rem; margin-top: 0.5rem; display: block;">Sesi terkonfirmasi hadir</small>
    </div>

    <div class="presensi-metric-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: 0.72rem; color: #0284c7; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Izin Berhalangan</div>
                <div style="font-size: 1.75rem; font-weight: 900; color: #0284c7; margin-top: 0.25rem; font-family: var(--font-mono);">
                    {{ $attendanceStats['permission_count'] }}
                </div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: #f0f9ff; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                <i class="fas fa-hand"></i>
            </div>
        </div>
        <small style="color: #94a3b8; font-size: 0.725rem; margin-top: 0.5rem; display: block;">Disertai konfirmasi pengurus</small>
    </div>

    <div class="presensi-metric-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: 0.72rem; color: #dc2626; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Sakit / Alpa</div>
                <div style="font-size: 1.75rem; font-weight: 900; color: #dc2626; margin-top: 0.25rem; font-family: var(--font-mono);">
                    {{ $attendanceStats['sick_count'] + $attendanceStats['absent_count'] }}
                </div>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: #fef2f2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                <i class="fas fa-circle-xmark"></i>
            </div>
        </div>
        <small style="color: #94a3b8; font-size: 0.725rem; margin-top: 0.5rem; display: block;">Tidak dapat menghadiri agenda</small>
    </div>
</div>

<!-- ==========================================
     TABEL RIWAYAT PRESENSI PERTEMUAN
     ========================================== -->
<div class="presensi-table-card">
    <div style="padding: 1.15rem 1.75rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
        <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-list-check" style="color: #0284c7;"></i>
            <span>Riwayat Seluruh Sesi Pertemuan Divisi</span>
        </h3>
        <span style="font-size: 0.775rem; color: #64748b;">
            Menampilkan {{ $attendanceStats['all_logs']->count() }} catatan kehadiran resmi
        </span>
    </div>

    @if ($attendanceStats['all_logs']->count() > 0)
        <div class="table-responsive">
            <table class="table" style="font-size: 0.85rem; margin-bottom: 0;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 0.95rem 1.25rem; font-weight: 700; color: #475569;">Topik & Silabus Pertemuan</th>
                        <th style="padding: 0.95rem 1.25rem; font-weight: 700; color: #475569;">Tanggal & Waktu</th>
                        <th style="padding: 0.95rem 1.25rem; font-weight: 700; color: #475569;">Lokasi Ruang</th>
                        <th style="padding: 0.95rem 1.25rem; font-weight: 700; color: #475569; text-align: center;">Status Anda</th>
                        <th style="padding: 0.95rem 1.25rem; font-weight: 700; color: #475569;">Metode & Catatan</th>
                        <th style="padding: 0.95rem 1.25rem; font-weight: 700; color: #475569; text-align: center;">Materi Sesi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendanceStats['all_logs'] as $log)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 0.95rem 1.25rem;">
                                <strong style="color: #0f172a; font-size: 0.885rem; display: block;">
                                    {{ $log->session?->title }}
                                </strong>
                                @if ($log->session?->topic_material)
                                    <span style="font-size: 0.775rem; color: #64748b; display: block; margin-top: 0.15rem;">
                                        Materi: {{ $log->session->topic_material }}
                                    </span>
                                @endif
                                @if ($log->session?->instructor_name)
                                    <span style="font-size: 0.75rem; color: #0284c7; font-weight: 600;">
                                        Pemateri: {{ $log->session->instructor_name }}
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 0.95rem 1.25rem; color: #334155;">
                                <div style="font-weight: 700;">
                                    {{ \Carbon\Carbon::parse($log->session?->session_date)->translatedFormat('l, d F Y') }}
                                </div>
                                <small style="color: #64748b;">
                                    {{ substr($log->session?->time_start, 0, 5) }} - {{ substr($log->session?->time_end, 0, 5) }} WIB
                                </small>
                            </td>
                            <td style="padding: 0.95rem 1.25rem; color: #475569;">
                                <i class="fas fa-location-dot" style="color: #94a3b8; margin-right: 0.3rem;"></i>
                                {{ $log->session?->location }}
                            </td>
                            <td style="padding: 0.95rem 1.25rem; text-align: center;">
                                <span class="badge {{ $log->status_badge }}" style="font-size: 0.775rem; padding: 0.3rem 0.75rem; font-weight: 700;">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td style="padding: 0.95rem 1.25rem; color: #64748b; font-size: 0.8rem;">
                                <div style="display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.2rem;">
                                    @if ($log->checkin_type === 'self')
                                        <span class="badge" style="background: #ecfdf5; color: #059669; font-size: 0.675rem;">
                                            <i class="fas fa-mobile-screen"></i> Mandiri
                                        </span>
                                    @else
                                        <span class="badge" style="background: #f1f5f9; color: #64748b; font-size: 0.675rem;">
                                            <i class="fas fa-user-pen"></i> Pengurus
                                        </span>
                                    @endif

                                    @if ($log->checked_in_at)
                                        <span style="font-size: 0.725rem; color: #94a3b8;">
                                            {{ $log->checked_in_at->format('H:i') }} WIB
                                        </span>
                                    @endif
                                </div>
                                <div>{{ $log->notes ?? '-' }}</div>
                            </td>
                            <td style="padding: 0.95rem 1.25rem; text-align: center;">
                                @if ($log->session)
                                    <button type="button" 
                                            onclick="openMateriModal({{ json_encode([
                                                'title' => $log->session->title,
                                                'topic' => $log->session->topic_material,
                                                'outcomes' => $log->session->learning_outcomes,
                                                'instructor' => $log->session->instructor_name,
                                                'notes' => $log->session->notes,
                                                'date' => \Carbon\Carbon::parse($log->session->session_date)->translatedFormat('l, d F Y'),
                                                'time' => substr($log->session->time_start, 0, 5) . ' - ' . substr($log->session->time_end, 0, 5) . ' WIB',
                                                'location' => $log->session->location,
                                                'type' => ucwords(str_replace('_', ' ', $log->session->session_type)),
                                            ]) }})"
                                            class="btn btn-outline btn-xs"
                                            style="font-size: 0.75rem; padding: 0.3rem 0.75rem; border-radius: 8px; color: #0284c7; border: 1.5px solid #bae6fd; background: #f0f9ff; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.3rem;">
                                        <i class="fas fa-book-open"></i> Baca Materi
                                    </button>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.75rem;">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 3rem 1.5rem; color: #64748b;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">
                <i class="fas fa-clipboard-question"></i>
            </div>
            <h4 style="font-size: 1.05rem; font-weight: 700; color: #1e293b; margin-bottom: 0.35rem;">
                Belum Ada Catatan Presensi
            </h4>
            <p style="font-size: 0.85rem; color: #64748b; max-width: 450px; margin: 0 auto;">
                Sesi absensi akan otomatis muncul di sini setelah ketua divisi membuka dan mencatat kehadiran sesi pembelajaran resmi.
            </p>
        </div>
    @endif
</div>

<!-- Modal Rangkuman & Detail Materi Pertemuan -->
<div id="materiModal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 1.25rem;">
    <div style="background: #ffffff; border-radius: 20px; max-width: 680px; width: 100%; border: 1.5px solid #e2e8f0; box-shadow: 0 25px 50px rgba(0,0,0,0.25); overflow: hidden; animation: modalFadeIn 0.2s ease;">
        <!-- Modal Header -->
        <div style="background: linear-gradient(135deg, #f8fafc, #f1f5f9); padding: 1.35rem 1.75rem; border-bottom: 1.5px solid #e2e8f0; display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
            <div>
                <span id="modalSessionType" class="badge" style="background: #e0f2fe; color: #0284c7; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; margin-bottom: 0.45rem; display: inline-block; border: 1px solid #bae6fd; border-radius: 9999px; padding: 0.25rem 0.75rem;">
                    Workshop Teknis
                </span>
                <h3 id="modalSessionTitle" style="font-size: 1.3rem; font-weight: 900; color: #0c2340; margin: 0; line-height: 1.3;">
                    Judul Sesi Pertemuan
                </h3>
                <div style="font-size: 0.825rem; color: #64748b; margin-top: 0.35rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <span><i class="fas fa-calendar" style="color: #0284c7; margin-right: 0.25rem;"></i><span id="modalSessionDate">Tanggal</span></span>
                    <span><i class="fas fa-clock" style="color: #0284c7; margin-right: 0.25rem;"></i><span id="modalSessionTime">Waktu</span></span>
                    <span><i class="fas fa-location-dot" style="color: #0284c7; margin-right: 0.25rem;"></i><span id="modalSessionLocation">Lokasi</span></span>
                </div>
            </div>
            <button type="button" onclick="closeMateriModal()" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 50%; width: 34px; height: 34px; font-size: 1.25rem; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; line-height: 1;">
                &times;
            </button>
        </div>

        <!-- Modal Body -->
        <div style="padding: 1.5rem 1.75rem; max-height: 70vh; overflow-y: auto;">
            <!-- Topik Materi -->
            <div style="margin-bottom: 1.25rem; background: #f0f9ff; border: 1.5px solid #bae6fd; border-radius: 14px; padding: 1.15rem 1.35rem;">
                <div style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #0284c7; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                    Pokok Bahasan / Topik Pertemuan:
                </div>
                <div id="modalSessionTopic" style="font-weight: 800; font-size: 1.1rem; color: #0c2340; line-height: 1.45;">
                    Topik
                </div>
                <div style="font-size: 0.825rem; color: #0369a1; margin-top: 0.45rem; font-weight: 600;">
                    Instruktur / Pemateri: <span id="modalSessionInstructor" style="color: #0c2340; font-weight: 800;">-</span>
                </div>
            </div>

            <!-- Capaian Pembelajaran -->
            <div style="margin-bottom: 1.25rem;">
                <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                    <i class="fas fa-graduation-cap" style="color: #0284c7;"></i> Target & Capaian Pembelajaran:
                </h4>
                <div id="modalSessionOutcomes" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 1.1rem 1.25rem; font-size: 0.885rem; color: #334155; line-height: 1.7; white-space: pre-line;">
                    Capaian
                </div>
            </div>

            <!-- Catatan Pengurus / Arahan Tambahan -->
            <div id="modalNotesContainer" style="margin-bottom: 0.5rem;">
                <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                    <i class="fas fa-clipboard-list" style="color: #10b981;"></i> Catatan Khusus & Arahan Divisi:
                </h4>
                <div id="modalSessionNotes" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 1rem 1.25rem; font-size: 0.865rem; color: #475569; line-height: 1.65;">
                    Catatan
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div style="background: #f8fafc; padding: 1rem 1.75rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
            <button type="button" onclick="closeMateriModal()" class="btn btn-primary btn-sm" style="border-radius: 9999px; padding: 0.55rem 1.75rem; font-weight: 800; background: linear-gradient(135deg, #0284c7, #0369a1); border: none; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);">
                Tutup Jendela
            </button>
        </div>
    </div>
</div>

<script>
function openMateriModal(data) {
    document.getElementById('modalSessionType').innerText = data.type || 'Sesi Pertemuan';
    document.getElementById('modalSessionTitle').innerText = data.title || 'Detail Sesi Pertemuan';
    document.getElementById('modalSessionDate').innerText = data.date || '-';
    document.getElementById('modalSessionTime').innerText = data.time || '-';
    document.getElementById('modalSessionLocation').innerText = data.location || '-';
    document.getElementById('modalSessionTopic').innerText = data.topic || data.title || '-';
    document.getElementById('modalSessionInstructor').innerText = data.instructor || 'Pengurus Divisi';
    document.getElementById('modalSessionOutcomes').innerText = data.outcomes || 'Peserta mempelajari topik bahasan tertera secara mendalam.';
    
    const notesElem = document.getElementById('modalSessionNotes');
    const notesContainer = document.getElementById('modalNotesContainer');
    if (data.notes && data.notes.trim() !== '') {
        notesElem.innerText = data.notes;
        notesContainer.style.display = 'block';
    } else {
        notesContainer.style.display = 'none';
    }

    const modal = document.getElementById('materiModal');
    modal.style.display = 'flex';
}

function closeMateriModal() {
    document.getElementById('materiModal').style.display = 'none';
}

window.addEventListener('click', function(e) {
    const modal = document.getElementById('materiModal');
    if (e.target === modal) {
        closeMateriModal();
    }
});
</script>

@endsection
