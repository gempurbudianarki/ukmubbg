@extends('admin.layouts.app')

@section('title', 'Lembar Presensi: ' . $session->title)

@section('content')
<div style="padding-bottom: 4rem;">
    <!-- Top Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem;">
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline btn-sm">&larr; Kembali ke Daftar Sesi</a>
                @if ($session->division)
                    <span class="badge" style="background: {{ $session->division->color_accent }}15; color: {{ $session->division->color_accent }}; font-size: 0.75rem;">
                        {{ $session->division->name }}
                    </span>
                @else
                    <span class="badge badge-neutral" style="font-size: 0.75rem; background: #0f172a; color: #ffffff;">
                        Agenda Pleno (Semua Anggota)
                    </span>
                @endif
            </div>

            <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.025em; margin-bottom: 0.25rem;">
                {{ $session->title }}
            </h1>
            <div style="font-size: 0.875rem; color: var(--slate-500); display: flex; gap: 1rem; flex-wrap: wrap;">
                <span><strong>Hari & Tanggal:</strong> {{ $session->day_name ? $session->day_name . ', ' : '' }}{{ $session->session_date->format('d M Y') }}</span>
                <span>&bull;</span>
                <span><strong>Waktu:</strong> {{ substr($session->time_start, 0, 5) }} {{ $session->time_end ? '- ' . substr($session->time_end, 0, 5) : '' }} WIB</span>
                <span>&bull;</span>
                <span><strong>Lokasi:</strong> {{ $session->location }}</span>
                @if($session->instructor_name)
                    <span>&bull;</span>
                    <span><strong>Pemateri:</strong> {{ $session->instructor_name }}</span>
                @endif
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <button type="button" onclick="markAllPresent()" class="btn btn-outline" style="color: #059669; border-color: rgba(16, 185, 129, 0.4); background: #ecfdf5;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>Tandai Semua Hadir</span>
            </button>
            <button type="button" onclick="document.getElementById('attendanceForm').submit()" class="btn btn-primary" style="box-shadow: 0 4px 14px rgba(37,99,235,0.25);">
                Simpan Rekap Presensi &rarr;
            </button>
        </div>
    </div>

    <!-- Academic Syllabus & Learning Outcomes Card -->
    @if ($session->topic_material || $session->instructor_name || $session->learning_outcomes)
        <div class="glass-card" style="padding: 1.5rem 1.75rem; margin-bottom: 2rem; border-radius: 16px; border-left: 4px solid var(--accent-blue);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 0.75rem;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--accent-blue);">
                        Pokok Bahasan / Silabus Pertemuan
                    </span>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin-top: 0.2rem;">
                        {{ $session->topic_material ?? $session->title }}
                    </h3>
                </div>
                <div style="font-size: 0.85rem; color: var(--slate-700); background: var(--bg-surface); padding: 0.4rem 0.85rem; border-radius: 8px; border: 1px solid var(--slate-200);">
                    <strong>Pemateri / PIC:</strong> {{ $session->instructor_name ?? '-' }}
                </div>
            </div>

            @if ($session->learning_outcomes)
                <div style="background: var(--bg-surface); padding: 1rem 1.25rem; border-radius: 10px; border: 1px solid var(--slate-100); font-size: 0.9rem; color: var(--slate-700); line-height: 1.6; margin-top: 0.75rem;">
                    <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--slate-500); margin-bottom: 0.35rem;">
                        Capaian Pembelajaran & Ringkasan Materi:
                    </div>
                    {{ $session->learning_outcomes }}
                </div>
            @endif
        </div>
    @endif

    <!-- Live Statistics Strip -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div style="background: #ffffff; border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: 1.1rem; box-shadow: var(--shadow-subtle); text-align: center;">
            <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--slate-500);">Total Anggota</div>
            <div id="statTotal" style="font-size: 1.85rem; font-weight: 800; color: var(--slate-900); margin-top: 0.25rem;">{{ $stats['total'] }}</div>
        </div>
        <div style="background: #ecfdf5; border: 1px solid rgba(16, 185, 129, 0.3); border-radius: var(--radius-md); padding: 1.1rem; box-shadow: var(--shadow-subtle); text-align: center;">
            <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #059669;">Hadir</div>
            <div id="statHadir" style="font-size: 1.85rem; font-weight: 800; color: #059669; margin-top: 0.25rem;">{{ $stats['hadir'] }}</div>
        </div>
        <div style="background: #eff6ff; border: 1px solid rgba(14, 165, 233, 0.3); border-radius: var(--radius-md); padding: 1.1rem; box-shadow: var(--shadow-subtle); text-align: center;">
            <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #0284c7;">Izin</div>
            <div id="statIzin" style="font-size: 1.85rem; font-weight: 800; color: #0284c7; margin-top: 0.25rem;">{{ $stats['izin'] }}</div>
        </div>
        <div style="background: #fffbeb; border: 1px solid rgba(245, 158, 11, 0.3); border-radius: var(--radius-md); padding: 1.1rem; box-shadow: var(--shadow-subtle); text-align: center;">
            <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #d97706;">Sakit</div>
            <div id="statSakit" style="font-size: 1.85rem; font-weight: 800; color: #d97706; margin-top: 0.25rem;">{{ $stats['sakit'] }}</div>
        </div>
        <div style="background: #fef2f2; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: var(--radius-md); padding: 1.1rem; box-shadow: var(--shadow-subtle); text-align: center;">
            <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #dc2626;">Alpa</div>
            <div id="statAlpa" style="font-size: 1.85rem; font-weight: 800; color: #dc2626; margin-top: 0.25rem;">{{ $stats['alpa'] }}</div>
        </div>
        <div style="background: #0f172a; color: #ffffff; border-radius: var(--radius-md); padding: 1.1rem; box-shadow: var(--shadow-subtle); text-align: center;">
            <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #94a3b8;">Persentase</div>
            <div id="statRate" style="font-size: 1.85rem; font-weight: 800; color: #38bdf8; margin-top: 0.25rem;">{{ $stats['rate'] }}%</div>
        </div>
    </div>

    <!-- Attendance Sheet Form -->
    <form id="attendanceForm" action="{{ route('admin.attendance.updateLogs', $session) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="background: #ffffff; border: 1px solid var(--slate-200); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-card);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                    <thead>
                        <tr style="background: #0f172a; color: #f8fafc;">
                            <th style="padding: 1rem 1.25rem; width: 45px; text-align: center;">No</th>
                            <th style="padding: 1rem 1.25rem;">Nama & NIM Anggota</th>
                            <th style="padding: 1rem 1.25rem;">Divisi</th>
                            <th style="padding: 1rem 1.25rem; text-align: center; width: 340px;">Status Presensi</th>
                            <th style="padding: 1rem 1.25rem;">Catatan / Keterangan</th>
                        </tr>
                    </thead>
                    <tbody style="divide-y: 1px solid var(--slate-100);">
                        @forelse ($logs as $index => $log)
                            <tr style="border-bottom: 1px solid var(--slate-100); transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding: 1rem 1.25rem; text-align: center; font-weight: 600; color: var(--slate-400);">
                                    {{ $index + 1 }}
                                </td>
                                <td style="padding: 1rem 1.25rem;">
                                    <div style="font-weight: 700; color: var(--slate-900);">
                                        {{ $log->member->name }}
                                    </div>
                                    <div style="font-family: var(--font-mono); font-size: 0.775rem; color: var(--slate-500);">
                                        NIM: {{ $log->member->nim }}
                                    </div>
                                </td>
                                <td style="padding: 1rem 1.25rem;">
                                    <span class="badge" style="background: {{ $log->member->division->color_accent ?? '#0f172a' }}15; color: {{ $log->member->division->color_accent ?? '#0f172a' }}; font-size: 0.725rem;">
                                        {{ $log->member->division->name ?? '-' }}
                                    </span>
                                </td>
                                <td style="padding: 1rem 1.25rem; text-align: center;">
                                    <div style="display: inline-flex; background: var(--slate-100); padding: 0.25rem; border-radius: var(--radius-full); gap: 0.25rem;">
                                        <label style="cursor: pointer; margin: 0;">
                                            <input type="radio" name="logs[{{ $log->id }}][status]" value="hadir" {{ $log->status === 'hadir' ? 'checked' : '' }} onchange="recalculateStats()" style="display: none;" class="status-radio radio-hadir">
                                            <span class="pill-btn pill-hadir">Hadir</span>
                                        </label>
                                        <label style="cursor: pointer; margin: 0;">
                                            <input type="radio" name="logs[{{ $log->id }}][status]" value="izin" {{ $log->status === 'izin' ? 'checked' : '' }} onchange="recalculateStats()" style="display: none;" class="status-radio radio-izin">
                                            <span class="pill-btn pill-izin">Izin</span>
                                        </label>
                                        <label style="cursor: pointer; margin: 0;">
                                            <input type="radio" name="logs[{{ $log->id }}][status]" value="sakit" {{ $log->status === 'sakit' ? 'checked' : '' }} onchange="recalculateStats()" style="display: none;" class="status-radio radio-sakit">
                                            <span class="pill-btn pill-sakit">Sakit</span>
                                        </label>
                                        <label style="cursor: pointer; margin: 0;">
                                            <input type="radio" name="logs[{{ $log->id }}][status]" value="alpa" {{ $log->status === 'alpa' ? 'checked' : '' }} onchange="recalculateStats()" style="display: none;" class="status-radio radio-alpa">
                                            <span class="pill-btn pill-alpa">Alpa</span>
                                        </label>
                                    </div>
                                </td>
                                <td style="padding: 1rem 1.25rem;">
                                    <input type="text" name="logs[{{ $log->id }}][notes]" value="{{ $log->notes }}" placeholder="Keterangan izin / alasan..." class="form-control" style="font-size: 0.8rem; padding: 0.35rem 0.65rem;">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 3rem; text-align: center; color: var(--slate-500);">
                                    Tidak ada data anggota aktif yang terdaftar untuk sesi ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding: 1.25rem 1.5rem; background: var(--slate-50); border-top: 1px solid var(--slate-200); display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.85rem; color: var(--slate-500);">
                    Pastikan seluruh kehadiran telah dicek sebelum menyimpan rekap.
                </span>
                <button type="submit" class="btn btn-primary" style="box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                    Simpan Perubahan Presensi
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    .pill-btn {
        display: inline-block;
        padding: 0.3rem 0.75rem;
        border-radius: var(--radius-full);
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--slate-600);
        transition: all 0.2s ease;
    }
    .radio-hadir:checked + .pill-hadir {
        background: #10b981;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);
    }
    .radio-izin:checked + .pill-izin {
        background: #0284c7;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.4);
    }
    .radio-sakit:checked + .pill-sakit {
        background: #f59e0b;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.4);
    }
    .radio-alpa:checked + .pill-alpa {
        background: #ef4444;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
    }
</style>

<script>
    function markAllPresent() {
        document.querySelectorAll('.radio-hadir').forEach(radio => {
            radio.checked = true;
        });
        recalculateStats();
    }

    function recalculateStats() {
        const total = document.querySelectorAll('.radio-hadir').length;
        const hadir = document.querySelectorAll('.radio-hadir:checked').length;
        const izin = document.querySelectorAll('.radio-izin:checked').length;
        const sakit = document.querySelectorAll('.radio-sakit:checked').length;
        const alpa = document.querySelectorAll('.radio-alpa:checked').length;

        document.getElementById('statHadir').textContent = hadir;
        document.getElementById('statIzin').textContent = izin;
        document.getElementById('statSakit').textContent = sakit;
        document.getElementById('statAlpa').textContent = alpa;

        const rate = total > 0 ? Math.round((hadir / total) * 1000) / 10 : 0;
        document.getElementById('statRate').textContent = rate + '%';
    }
</script>
@endsection
