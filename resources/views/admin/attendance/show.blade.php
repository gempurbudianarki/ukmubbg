@extends('admin.layouts.app')

@section('title', 'Lembar Presensi: ' . $session->title)

@section('content')
<div style="padding-bottom: 4rem;">
    <!-- Top Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem;">
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline btn-sm">&larr; Kembali ke Daftar Sesi</a>
                @if ($session->division)
                    <span class="badge" style="background: {{ $session->division->color_accent }}15; color: {{ $session->division->color_accent }}; font-size: 0.75rem; font-weight: 700;">
                        {{ $session->division->name }}
                    </span>
                @else
                    <span class="badge badge-neutral" style="font-size: 0.75rem; background: #0f172a; color: #ffffff;">
                        Agenda Pleno (Semua Anggota)
                    </span>
                @endif
                <span class="badge {{ $session->status === 'open' ? 'badge-success' : 'badge-danger' }}" style="font-size: 0.75rem;">
                    {{ $session->status === 'open' ? 'Sesi Terbuka' : 'Sesi Ditutup' }}
                </span>
            </div>

            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.025em; margin-bottom: 0.25rem;">
                {{ $session->title }}
            </h1>
            <div style="font-size: 0.85rem; color: var(--slate-500); display: flex; gap: 0.85rem; flex-wrap: wrap;">
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

        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <!-- Toggle Open / Closed Session -->
            <form action="{{ route('admin.attendance.toggleStatus', $session) }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn {{ $session->status === 'open' ? 'btn-outline' : 'btn-success' }}" style="{{ $session->status === 'open' ? 'border-color: #ef4444; color: #dc2626;' : '' }}" title="Ubah status akses presensi">
                    <i class="fas {{ $session->status === 'open' ? 'fa-lock' : 'fa-lock-open' }}" style="margin-right: 0.35rem;"></i>
                    <span>{{ $session->status === 'open' ? 'Tutup Sesi Presensi' : 'Buka Kembali Sesi' }}</span>
                </button>
            </form>

            <a href="{{ route('admin.attendance.bap', $session) }}" target="_blank" class="btn btn-secondary" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;" title="Cetak Berita Acara Presensi Resmi Format A4">
                <i class="fas fa-print" style="margin-right: 0.35rem;"></i>
                <span>Cetak BAP Resmi</span>
            </a>
            <button type="button" onclick="markAllPresent()" class="btn btn-outline" style="color: #059669; border-color: rgba(16, 185, 129, 0.4); background: #ecfdf5;">
                <i class="fas fa-check-double" style="margin-right: 0.35rem;"></i>
                <span>Tandai Semua Hadir</span>
            </button>
            <button type="button" onclick="document.getElementById('attendanceForm').submit()" class="btn btn-primary" style="box-shadow: 0 4px 14px rgba(37,99,235,0.25);">
                <i class="fas fa-floppy-disk" style="margin-right: 0.35rem;"></i>
                Simpan Seluruh Rekap
            </button>
        </div>
    </div>

    <!-- Password / Passcode Showcase Banner (Proyektor & Presensi Mandiri) -->
    <div style="background: #ffffff; border: 1.5px solid rgba(226, 232, 240, 0.9); border-radius: 18px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; box-shadow: var(--clay-card); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem;">
        <div style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #0284c7, #0369a1); display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 1.45rem; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3); flex-shrink: 0;">
                <i class="fas fa-key"></i>
            </div>
            <div>
                <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 800; color: #64748b; letter-spacing: 0.08em;">
                    KODE / PASSWORD PRESENSI MANDIRI MAHASISWA
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.25rem; flex-wrap: wrap;">
                    <span id="passcodeDisplay" style="font-family: var(--font-mono); font-size: 1.75rem; font-weight: 900; color: #0284c7; letter-spacing: 0.12em; background: #f0f9ff; padding: 0.15rem 0.85rem; border-radius: 10px; border: 1px dashed rgba(2, 132, 199, 0.5);">
                        {{ $session->passcode ?? '-' }}
                    </span>
                    <button type="button" onclick="copyPasscode()" class="btn btn-outline btn-sm" id="copyBtn" style="padding: 0.35rem 0.75rem; font-size: 0.775rem;">
                        <i class="fas fa-copy"></i>
                        <span id="copyBtnText">Salin Kode</span>
                    </button>
                    @if ($session->passcode_expires_at)
                        @if ($session->isPasscodeExpired())
                            <span class="badge badge-danger" style="font-size: 0.75rem;">
                                <i class="fas fa-hourglass-end"></i> Kadaluarsa ({{ $session->passcode_expires_at->format('H:i') }})
                            </span>
                        @else
                            <span class="badge badge-success" style="font-size: 0.75rem;">
                                <i class="fas fa-clock"></i> Aktif s/d {{ $session->passcode_expires_at->format('H:i') }} WIB
                            </span>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
            <!-- Form Update Passcode -->
            <form action="{{ route('admin.attendance.updatePasscode', $session) }}" method="POST" style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                @csrf
                <input type="text" name="passcode" placeholder="Ganti kode..." maxlength="20" style="font-family: var(--font-mono); text-transform: uppercase; font-weight: 700; width: 130px; font-size: 0.85rem; padding: 0.4rem 0.65rem; border-radius: 8px; border: 1px solid #cbd5e1;">
                <button type="submit" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                    <i class="fas fa-arrows-rotate"></i> Ubah Kode
                </button>
            </form>
        </div>
    </div>

    <!-- Academic Syllabus & Learning Outcomes Card -->
    @if ($session->topic_material || $session->instructor_name || $session->learning_outcomes)
        <div style="background: #ffffff; border-radius: 16px; border: 1px solid rgba(226, 232, 240, 0.9); border-left: 4px solid #0284c7; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; box-shadow: var(--shadow-subtle);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 0.5rem;">
                <div>
                    <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #0284c7;">
                        Pokok Bahasan / Silabus Pembelajaran
                    </span>
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-top: 0.15rem;">
                        {{ $session->topic_material ?? $session->title }}
                    </h3>
                </div>
                <div style="font-size: 0.825rem; color: #334155; background: #f8fafc; padding: 0.35rem 0.85rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <strong>Pemateri / Instruktur:</strong> {{ $session->instructor_name ?? '-' }}
                </div>
            </div>

            @if ($session->learning_outcomes)
                <div style="background: #f8fafc; padding: 0.85rem 1.15rem; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 0.85rem; color: #475569; line-height: 1.6; margin-top: 0.5rem;">
                    <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #64748b; margin-bottom: 0.25rem;">
                        Capaian Target Kompetensi:
                    </div>
                    {{ $session->learning_outcomes }}
                </div>
            @endif
        </div>
    @endif

    <!-- Live Statistics Strip -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: var(--radius-md); padding: 1rem; box-shadow: var(--clay-pill); text-align: center;">
            <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #64748b;">Total Anggota</div>
            <div id="statTotal" style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-top: 0.2rem;">{{ $stats['total'] }}</div>
        </div>
        <div style="background: #ecfdf5; border: 1px solid rgba(16, 185, 129, 0.3); border-radius: var(--radius-md); padding: 1rem; box-shadow: var(--clay-pill); text-align: center;">
            <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #059669;">Hadir</div>
            <div id="statHadir" style="font-size: 1.75rem; font-weight: 800; color: #059669; margin-top: 0.2rem;">{{ $stats['hadir'] }}</div>
        </div>
        <div style="background: #f0f9ff; border: 1px solid rgba(2, 132, 199, 0.3); border-radius: var(--radius-md); padding: 1rem; box-shadow: var(--clay-pill); text-align: center;">
            <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #0284c7;">Izin</div>
            <div id="statIzin" style="font-size: 1.75rem; font-weight: 800; color: #0284c7; margin-top: 0.2rem;">{{ $stats['izin'] }}</div>
        </div>
        <div style="background: #fffbeb; border: 1px solid rgba(245, 158, 11, 0.3); border-radius: var(--radius-md); padding: 1rem; box-shadow: var(--clay-pill); text-align: center;">
            <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #d97706;">Sakit</div>
            <div id="statSakit" style="font-size: 1.75rem; font-weight: 800; color: #d97706; margin-top: 0.2rem;">{{ $stats['sakit'] }}</div>
        </div>
        <div style="background: #fef2f2; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: var(--radius-md); padding: 1rem; box-shadow: var(--clay-pill); text-align: center;">
            <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #dc2626;">Alpa</div>
            <div id="statAlpa" style="font-size: 1.75rem; font-weight: 800; color: #dc2626; margin-top: 0.2rem;">{{ $stats['alpa'] }}</div>
        </div>
        <div style="background: #ffffff; border: 1.5px solid #0284c7; border-radius: var(--radius-md); padding: 1rem; box-shadow: var(--clay-pill); text-align: center;">
            <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #0284c7;">Kehadiran</div>
            <div id="statRate" style="font-size: 1.75rem; font-weight: 800; color: #0284c7; margin-top: 0.2rem;">{{ $stats['rate'] }}%</div>
        </div>
    </div>

    <!-- Toolbar Pencarian Anggota & Aksi Cepat -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 0.95rem 1.25rem; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.65rem; flex: 1; max-width: 380px;">
            <i class="fas fa-search" style="color: #94a3b8;"></i>
            <input type="text" id="memberSearchInput" onkeyup="filterMemberTable()" placeholder="Cari nama atau NIM anggota di tabel..." class="form-control" style="font-size: 0.85rem; padding: 0.4rem 0.75rem;">
        </div>

        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <button type="button" onclick="markAllStatus('alpa')" class="btn btn-outline btn-sm" style="font-size: 0.775rem; color: #dc2626; border-color: rgba(239, 68, 68, 0.4);">
                Reset ke Alpa
            </button>
            <span style="font-size: 0.8rem; color: #64748b;">
                Menampilkan <span id="visibleRowCount">{{ $logs->count() }}</span> dari {{ $logs->count() }} Anggota
            </span>
        </div>
    </div>

    <!-- Attendance Sheet Form -->
    <form id="attendanceForm" action="{{ route('admin.attendance.updateLogs', $session) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.9); border-radius: 16px; overflow: hidden; box-shadow: var(--clay-card);">
            <div style="overflow-x: auto;">
                <table id="attendanceTable" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
                    <thead>
                        <tr style="background: #0f172a; color: #f8fafc;">
                            <th style="padding: 0.85rem 1rem; width: 45px; text-align: center;">No</th>
                            <th style="padding: 0.85rem 1rem;">Nama & NIM Anggota</th>
                            <th style="padding: 0.85rem 1rem;">Divisi</th>
                            <th style="padding: 0.85rem 1rem; text-align: center; width: 320px;">Status Presensi</th>
                            <th style="padding: 0.85rem 1rem; text-align: center; width: 150px;">Aksi Cepat</th>
                            <th style="padding: 0.85rem 1rem;">Catatan & Metode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $index => $log)
                            <tr class="member-row" data-name="{{ strtolower($log->member->name ?? '') }}" data-nim="{{ strtolower($log->member->nim ?? '') }}" style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding: 0.85rem 1rem; text-align: center; font-weight: 600; color: #94a3b8;">
                                    {{ $index + 1 }}
                                </td>
                                <td style="padding: 0.85rem 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                                        <img src="{{ $log->member->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($log->member->name) . '&background=0284c7&color=ffffff' }}" alt="" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                                        <div>
                                            <div style="font-weight: 700; color: #0f172a; font-size: 0.885rem;">
                                                {{ $log->member->name }}
                                            </div>
                                            <div style="font-family: var(--font-mono); font-size: 0.75rem; color: #64748b;">
                                                NIM: {{ $log->member->nim }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 0.85rem 1rem;">
                                    <span class="badge" style="background: {{ $log->member->division->color_accent ?? '#0f172a' }}15; color: {{ $log->member->division->color_accent ?? '#0f172a' }}; font-size: 0.725rem;">
                                        {{ $log->member->division->name ?? '-' }}
                                    </span>
                                </td>
                                <td style="padding: 0.85rem 1rem; text-align: center;">
                                    <div style="display: inline-flex; background: #f1f5f9; padding: 0.2rem; border-radius: 9999px; gap: 0.2rem;">
                                        <label style="cursor: pointer; margin: 0;">
                                            <input type="radio" name="logs[{{ $log->id }}][status]" value="hadir" {{ $log->status === 'hadir' ? 'checked' : '' }} onchange="recalculateStats()" style="display: none;" class="status-radio radio-hadir" id="radio_{{ $log->id }}_hadir">
                                            <span class="pill-btn pill-hadir">Hadir</span>
                                        </label>
                                        <label style="cursor: pointer; margin: 0;">
                                            <input type="radio" name="logs[{{ $log->id }}][status]" value="izin" {{ $log->status === 'izin' ? 'checked' : '' }} onchange="recalculateStats()" style="display: none;" class="status-radio radio-izin" id="radio_{{ $log->id }}_izin">
                                            <span class="pill-btn pill-izin">Izin</span>
                                        </label>
                                        <label style="cursor: pointer; margin: 0;">
                                            <input type="radio" name="logs[{{ $log->id }}][status]" value="sakit" {{ $log->status === 'sakit' ? 'checked' : '' }} onchange="recalculateStats()" style="display: none;" class="status-radio radio-sakit" id="radio_{{ $log->id }}_sakit">
                                            <span class="pill-btn pill-sakit">Sakit</span>
                                        </label>
                                        <label style="cursor: pointer; margin: 0;">
                                            <input type="radio" name="logs[{{ $log->id }}][status]" value="alpa" {{ $log->status === 'alpa' ? 'checked' : '' }} onchange="recalculateStats()" style="display: none;" class="status-radio radio-alpa" id="radio_{{ $log->id }}_alpa">
                                            <span class="pill-btn pill-alpa">Alpa</span>
                                        </label>
                                    </div>
                                </td>
                                <td style="padding: 0.85rem 1rem; text-align: center;">
                                    <!-- 1-Click Quick Mark Action directly by Ketua Divisi -->
                                    <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                        <button type="button" onclick="quickMarkDirect({{ $log->id }}, 'hadir')" class="btn btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.72rem; background: #ecfdf5; color: #059669; border: 1px solid rgba(16, 185, 129, 0.3);" title="Hadirkan Langsung">
                                            <i class="fas fa-check"></i> Hadir
                                        </button>
                                        <button type="button" onclick="quickMarkDirect({{ $log->id }}, 'alpa')" class="btn btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.72rem; background: #fef2f2; color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.3);" title="Set Alpa">
                                            <i class="fas fa-xmark"></i>
                                        </button>
                                    </div>
                                </td>
                                <td style="padding: 0.85rem 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <input type="text" name="logs[{{ $log->id }}][notes]" value="{{ $log->notes }}" placeholder="Keterangan..." class="form-control" style="font-size: 0.785rem; padding: 0.3rem 0.6rem; flex: 1;">
                                        @if ($log->attachment)
                                            <a href="{{ asset('storage/' . $log->attachment) }}" target="_blank" class="btn btn-outline btn-xs" style="padding: 0.25rem 0.5rem; font-size: 0.72rem; color: #0284c7; border-color: rgba(2, 132, 199, 0.4); white-space: nowrap;" title="Lihat Lampiran Bukti Izin/Sakit">
                                                <i class="fas fa-paperclip"></i> Bukti
                                            </a>
                                        @endif
                                        @if ($log->checkin_type === 'self')
                                            <span class="badge" style="background: #ecfdf5; color: #059669; font-size: 0.675rem; white-space: nowrap;" title="{{ $log->checked_in_at ? 'Presensi mandiri pada ' . $log->checked_in_at->format('H:i d/m/Y') : 'Mandiri' }}">
                                                <i class="fas fa-mobile-screen"></i> Mandiri
                                            </span>
                                        @else
                                            <span class="badge" style="background: #f1f5f9; color: #64748b; font-size: 0.675rem; white-space: nowrap;">
                                                <i class="fas fa-user-pen"></i> Pengurus
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 3rem; text-align: center; color: #64748b;">
                                    Tidak ada data anggota aktif yang terdaftar untuk sesi ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding: 1.15rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <span style="font-size: 0.825rem; color: #64748b;">
                    Klik tombol "Simpan Perubahan Presensi" untuk memastikan semua perubahan status tersimpan permanen.
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
        padding: 0.25rem 0.7rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        transition: all 0.18s ease;
    }
    .radio-hadir:checked + .pill-hadir {
        background: #10b981;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.4);
    }
    .radio-izin:checked + .pill-izin {
        background: #0284c7;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.4);
    }
    .radio-sakit:checked + .pill-sakit {
        background: #f59e0b;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.4);
    }
    .radio-alpa:checked + .pill-alpa {
        background: #ef4444;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
    }
</style>

<script>
    function copyPasscode() {
        const text = document.getElementById('passcodeDisplay').innerText.trim();
        navigator.clipboard.writeText(text).then(() => {
            const btnText = document.getElementById('copyBtnText');
            btnText.innerText = 'Tersalin!';
            setTimeout(() => {
                btnText.innerText = 'Salin Kode';
            }, 2000);
        });
    }

    function markAllPresent() {
        document.querySelectorAll('.radio-hadir').forEach(radio => {
            radio.checked = true;
        });
        recalculateStats();
    }

    function markAllStatus(status) {
        document.querySelectorAll('.radio-' + status).forEach(radio => {
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

    function filterMemberTable() {
        const query = document.getElementById('memberSearchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.member-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const nim = row.getAttribute('data-nim') || '';
            if (name.includes(query) || nim.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('visibleRowCount').textContent = visibleCount;
    }

    // 1-Click direct attendance quick mark via Fetch API
    function quickMarkDirect(logId, status) {
        const radio = document.getElementById('radio_' + logId + '_' + status);
        if (radio) {
            radio.checked = true;
            recalculateStats();
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('log_id', logId);
        formData.append('status', status);

        fetch('{{ route('admin.attendance.quickMark', $session) }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Show subtle toast or feedback
                console.log(data.message);
            }
        })
        .catch(err => {
            console.error(err);
        });
    }
</script>
@endsection
