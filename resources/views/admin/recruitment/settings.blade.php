@extends('admin.layouts.app')

@section('title', 'Pengaturan Gelombang & Kuota Divisi - Admin UKM')

@section('content')
<div style="max-width: 1100px; margin: 0 auto;">
    <!-- Breadcrumb & Header -->
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; gap: 0.5rem; align-items: center; font-size: 0.85rem; color: var(--slate-500); margin-bottom: 0.5rem;">
            <a href="{{ route('admin.dashboard') }}" style="color: var(--slate-600); text-decoration: none;">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.recruitment.index') }}" style="color: var(--slate-600); text-decoration: none;">Rekrutmen</a>
            <span>/</span>
            <span style="color: var(--slate-800); font-weight: 600;">Pengaturan Gelombang & Kuota</span>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em;">
                    Pengaturan Gelombang & Kuota Divisi
                </h1>
                <p style="color: var(--slate-500); font-size: 0.95rem; margin-top: 0.25rem;">
                    Kelola jadwal otomatis pembukaan pendaftaran dan batasi ketersediaan divisi untuk pendaftar baru.
                </p>
            </div>
            <a href="{{ route('admin.recruitment.index') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 0.4rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Data Pendaftar
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #065f46; display: flex; align-items: center; gap: 0.75rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span style="font-weight: 500; font-size: 0.95rem;">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.recruitment.settings.update') }}" method="POST">
        @csrf

        <!-- CARD 1: GLOBAL RECRUITMENT SCHEDULE -->
        <div class="glass-card" style="padding: 1.75rem; margin-bottom: 2rem; border-radius: 16px;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 1rem;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(37, 99, 235, 0.1); color: var(--accent-blue); display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--slate-900);">
                        Periode & Status Pendaftaran Global
                    </h2>
                    <p style="font-size: 0.85rem; color: var(--slate-500);">
                        Jika status ditutup atau tanggal sekarang di luar jadwal, form pendaftaran publik otomatis ditutup.
                    </p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                <!-- Master Status Switch -->
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--slate-700); margin-bottom: 0.5rem;">
                        Status Pendaftaran Global <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="display: flex; gap: 1rem;">
                        <label style="flex: 1; display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1rem; border: 1px solid var(--slate-200); border-radius: 10px; cursor: pointer; background: var(--bg-surface); font-size: 0.9rem; font-weight: 600;">
                            <input type="radio" name="recruitment_status" value="open" {{ $recruitmentStatus === 'open' ? 'checked' : '' }} style="accent-color: #10b981;">
                            <span style="color: #059669;"> Dibuka (Open)</span>
                        </label>
                        <label style="flex: 1; display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1rem; border: 1px solid var(--slate-200); border-radius: 10px; cursor: pointer; background: var(--bg-surface); font-size: 0.9rem; font-weight: 600;">
                            <input type="radio" name="recruitment_status" value="closed" {{ $recruitmentStatus === 'closed' ? 'checked' : '' }} style="accent-color: #ef4444;">
                            <span style="color: #dc2626;">🔒 Ditutup (Closed)</span>
                        </label>
                    </div>
                </div>

                <!-- Batch Name -->
                <div>
                    <label for="recruitment_batch_name" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--slate-700); margin-bottom: 0.5rem;">
                        Nama Gelombang / Periode <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" id="recruitment_batch_name" name="recruitment_batch_name" value="{{ old('recruitment_batch_name', $batchName) }}" class="form-input" style="width: 100%; font-size: 0.9rem;" placeholder="Contoh: Gelombang I (Ganjil 2026/2027)" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                <!-- Start Date -->
                <div>
                    <label for="recruitment_start_date" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--slate-700); margin-bottom: 0.5rem;">
                        Waktu Mulai Pendaftaran
                    </label>
                    <input type="text" id="recruitment_start_date" name="recruitment_start_date" value="{{ old('recruitment_start_date', $startDate) }}" class="form-input" style="width: 100%; font-size: 0.9rem;" placeholder="YYYY-MM-DD HH:MM">
                    <span style="font-size: 0.75rem; color: var(--slate-400); margin-top: 0.25rem; display: block;">Format: YYYY-MM-DD HH:MM (Contoh: 2026-10-01 08:00)</span>
                </div>

                <!-- End Date -->
                <div>
                    <label for="recruitment_end_date" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--slate-700); margin-bottom: 0.5rem;">
                        Waktu Berakhir / Batas Akhir
                    </label>
                    <input type="text" id="recruitment_end_date" name="recruitment_end_date" value="{{ old('recruitment_end_date', $endDate) }}" class="form-input" style="width: 100%; font-size: 0.9rem;" placeholder="YYYY-MM-DD HH:MM">
                    <span style="font-size: 0.75rem; color: var(--slate-400); margin-top: 0.25rem; display: block;">Format: YYYY-MM-DD HH:MM (Contoh: 2026-10-31 23:59)</span>
                </div>
            </div>

            <!-- Closed Notice Message -->
            <div>
                <label for="recruitment_closed_message" style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--slate-700); margin-bottom: 0.5rem;">
                    Pesan Pemberitahuan Saat Pendaftaran Ditutup
                </label>
                <textarea id="recruitment_closed_message" name="recruitment_closed_message" rows="2" class="form-input" style="width: 100%; font-size: 0.9rem;" placeholder="Pesan yang tampil pada halaman pendaftaran ketika rekrutmen ditutup...">{{ old('recruitment_closed_message', $closedMessage) }}</textarea>
            </div>
        </div>

        <!-- CARD 2: DIVISION SPECIFIC GATING & QUOTAS -->
        <div class="glass-card" style="padding: 1.75rem; margin-bottom: 2rem; border-radius: 16px;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 1rem;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(16, 185, 129, 0.1); color: var(--success); display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--slate-900);">
                        Kontrol Ketersediaan & Kuota per Divisi
                    </h2>
                    <p style="font-size: 0.85rem; color: var(--slate-500);">
                        Pilih divisi mana yang dibuka untuk pendaftar baru. Divisi yang dinonaktifkan tidak akan muncul pada pilihan pendaftaran publik.
                    </p>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--slate-200); text-align: left; color: var(--slate-600); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">
                            <th style="padding: 0.75rem 1rem;">Divisi Spesialisasi</th>
                            <th style="padding: 0.75rem 1rem; text-align: center;">Buka Pendaftaran</th>
                            <th style="padding: 0.75rem 1rem; width: 140px;">Kuota Maksimal</th>
                            <th style="padding: 0.75rem 1rem; text-align: center;">Pendaftar Saat Ini</th>
                            <th style="padding: 0.75rem 1rem;">Catatan Khusus Divisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($divisions as $div)
                            <tr style="border-bottom: 1px solid var(--slate-100); transition: background 0.15s ease;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 10px; height: 10px; border-radius: 50%; background: {{ $div->color_accent }};"></div>
                                        <div>
                                            <div style="font-weight: 700; color: var(--slate-900);">{{ $div->name }}</div>
                                            <div style="font-size: 0.75rem; color: var(--slate-400);">{{ $div->tagline }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <label style="display: inline-flex; align-items: center; gap: 0.4rem; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
                                        <input type="checkbox" name="divisions[{{ $div->id }}][is_recruitment_open]" value="1" {{ $div->is_recruitment_open ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--accent-blue);">
                                        <span style="color: {{ $div->is_recruitment_open ? '#059669' : '#dc2626' }};">
                                            {{ $div->is_recruitment_open ? 'Dibuka' : 'Ditutup' }}
                                        </span>
                                    </label>
                                </td>
                                <td style="padding: 1rem;">
                                    <input type="number" name="divisions[{{ $div->id }}][recruitment_quota]" value="{{ $div->recruitment_quota }}" min="0" placeholder="Tanpa batas" class="form-input" style="width: 100%; font-size: 0.85rem; padding: 0.4rem 0.6rem;">
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    @php
                                        $count = $div->first_choice_applicants_count ?? 0;
                                        $quota = $div->recruitment_quota;
                                        $isFull = $quota && $count >= $quota;
                                    @endphp
                                    <span class="badge {{ $isFull ? 'badge-danger' : 'badge-neutral' }}" style="font-size: 0.8rem; font-weight: 700;">
                                        {{ $count }} {{ $quota ? '/ ' . $quota : 'pendaftar' }}
                                    </span>
                                </td>
                                <td style="padding: 1rem;">
                                    <input type="text" name="divisions[{{ $div->id }}][recruitment_notes]" value="{{ $div->recruitment_notes }}" placeholder="Contoh: Kuota Terbatas / Lab Penuh" class="form-input" style="width: 100%; font-size: 0.85rem; padding: 0.4rem 0.6rem;">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div style="display: flex; justify-content: flex-end; gap: 1rem; align-items: center;">
            <a href="{{ route('admin.recruitment.index') }}" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 700;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right: 0.4rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Seluruh Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
