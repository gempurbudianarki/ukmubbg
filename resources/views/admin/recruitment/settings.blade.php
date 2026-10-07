@extends('admin.layouts.app')

@section('title', 'Pengaturan Gelombang & Kuota Divisi - Admin UKM')
@section('page_title', 'Pengaturan Gelombang & Kuota Rekrutmen')

@section('content')
<!-- Header Box -->
<div class="admin-header-box">
    <div>
        <h1 class="admin-header-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#2563eb">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Pengaturan Gelombang & Kuota Divisi</span>
        </h1>
        <p class="admin-header-desc">
            Kelola jadwal otomatis pembukaan pendaftaran dan batasi ketersediaan kuota divisi untuk pendaftar baru.
        </p>
    </div>
    <a href="{{ route('admin.recruitment.index') }}" class="btn btn-outline" style="background: #ffffff; box-shadow: var(--clay-btn); display: inline-flex; align-items: center; gap: 0.45rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali ke Data Pendaftar</span>
    </a>
</div>

<form action="{{ route('admin.recruitment.settings.update') }}" method="POST">
    @csrf

    <!-- CARD 1: GLOBAL RECRUITMENT SCHEDULE -->
    <div class="admin-clay-card" style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 0.85rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 1rem;">
            <div style="width: 42px; height: 42px; border-radius: var(--radius-sm); background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; box-shadow: var(--clay-pill);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <h2 style="font-size: 1.2rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                    Periode & Status Pendaftaran Global
                </h2>
                <p style="font-size: 0.825rem; color: var(--slate-500); margin: 0;">
                    Jika status ditutup atau tanggal sekarang di luar jadwal, form pendaftaran publik otomatis dinonaktifkan.
                </p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
            <!-- Master Status Switch -->
            <div>
                <label style="display: block; font-size: 0.825rem; font-weight: 800; color: var(--slate-700); margin-bottom: 0.5rem;">
                    Status Pendaftaran Global *
                </label>
                <div style="display: flex; gap: 1rem;">
                    <label style="flex: 1; display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.15rem; border-radius: var(--radius-sm); cursor: pointer; background: #ffffff; box-shadow: var(--clay-pill); font-size: 0.875rem; font-weight: 700;">
                        <input type="radio" name="recruitment_status" value="open" {{ $recruitmentStatus === 'open' ? 'checked' : '' }} style="accent-color: #10b981;">
                        <span style="color: #059669;"> Dibuka (Open)</span>
                    </label>
                    <label style="flex: 1; display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.15rem; border-radius: var(--radius-sm); cursor: pointer; background: #ffffff; box-shadow: var(--clay-pill); font-size: 0.875rem; font-weight: 700;">
                        <input type="radio" name="recruitment_status" value="closed" {{ $recruitmentStatus === 'closed' ? 'checked' : '' }} style="accent-color: #ef4444;">
                        <span style="color: #dc2626;"> Ditutup (Closed)</span>
                    </label>
                </div>
            </div>

            <!-- Batch Name -->
            <div>
                <label for="recruitment_batch_name" style="display: block; font-size: 0.825rem; font-weight: 800; color: var(--slate-700); margin-bottom: 0.5rem;">
                    Nama Gelombang / Periode *
                </label>
                <input type="text" id="recruitment_batch_name" name="recruitment_batch_name" value="{{ old('recruitment_batch_name', $batchName) }}" class="admin-filter-input" style="width: 100%;" placeholder="Contoh: Gelombang I (Ganjil 2026/2027)" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
            <!-- Start Date -->
            <div>
                <label for="recruitment_start_date" style="display: block; font-size: 0.825rem; font-weight: 800; color: var(--slate-700); margin-bottom: 0.5rem;">
                    Waktu Mulai Pendaftaran
                </label>
                <input type="text" id="recruitment_start_date" name="recruitment_start_date" value="{{ old('recruitment_start_date', $startDate) }}" class="admin-filter-input" style="width: 100%;" placeholder="YYYY-MM-DD HH:MM">
                <span style="font-size: 0.75rem; color: var(--slate-400); margin-top: 0.35rem; display: block;">Format: YYYY-MM-DD HH:MM (Contoh: 2026-10-01 08:00)</span>
            </div>

            <!-- End Date -->
            <div>
                <label for="recruitment_end_date" style="display: block; font-size: 0.825rem; font-weight: 800; color: var(--slate-700); margin-bottom: 0.5rem;">
                    Waktu Berakhir / Batas Akhir
                </label>
                <input type="text" id="recruitment_end_date" name="recruitment_end_date" value="{{ old('recruitment_end_date', $endDate) }}" class="admin-filter-input" style="width: 100%;" placeholder="YYYY-MM-DD HH:MM">
                <span style="font-size: 0.75rem; color: var(--slate-400); margin-top: 0.35rem; display: block;">Format: YYYY-MM-DD HH:MM (Contoh: 2026-10-31 23:59)</span>
            </div>
        </div>

        <!-- Closed Notice Message -->
        <div>
            <label for="recruitment_closed_message" style="display: block; font-size: 0.825rem; font-weight: 800; color: var(--slate-700); margin-bottom: 0.5rem;">
                Pesan Pemberitahuan Saat Pendaftaran Ditutup
            </label>
            <textarea id="recruitment_closed_message" name="recruitment_closed_message" rows="2" class="admin-filter-input" style="width: 100%;" placeholder="Pesan yang tampil pada halaman pendaftaran ketika rekrutmen ditutup...">{{ old('recruitment_closed_message', $closedMessage) }}</textarea>
        </div>
    </div>

    <!-- CARD 2: DIVISION SPECIFIC GATING & QUOTAS -->
    <div class="admin-clay-card" style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 0.85rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 1rem;">
            <div style="width: 42px; height: 42px; border-radius: var(--radius-sm); background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; box-shadow: var(--clay-pill);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div>
                <h2 style="font-size: 1.2rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                    Kontrol Ketersediaan & Kuota per Divisi
                </h2>
                <p style="font-size: 0.825rem; color: var(--slate-500); margin: 0;">
                    Tentukan divisi mana yang dibuka untuk pendaftar baru. Divisi non-aktif tidak muncul pada formulir pendaftaran publik.
                </p>
            </div>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Divisi Spesialisasi</th>
                        <th style="text-align: center;">Buka Pendaftaran</th>
                        <th style="width: 150px;">Kuota Maksimal</th>
                        <th style="text-align: center;">Pendaftar Saat Ini</th>
                        <th>Catatan Khusus Divisi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($divisions as $div)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 12px; height: 12px; border-radius: 50%; background: {{ $div->color_accent }}; box-shadow: var(--clay-pill);"></div>
                                    <div>
                                        <div style="font-weight: 700; color: var(--slate-900);">{{ $div->name }}</div>
                                        <div style="font-size: 0.75rem; color: var(--slate-400);">{{ $div->tagline }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <label style="display: inline-flex; align-items: center; gap: 0.4rem; cursor: pointer; font-size: 0.85rem; font-weight: 700;">
                                    <input type="checkbox" name="divisions[{{ $div->id }}][is_recruitment_open]" value="1" {{ $div->is_recruitment_open ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #2563eb;">
                                    <span style="color: {{ $div->is_recruitment_open ? '#059669' : '#dc2626' }};">
                                        {{ $div->is_recruitment_open ? 'Dibuka' : 'Ditutup' }}
                                    </span>
                                </label>
                            </td>
                            <td>
                                <input type="number" name="divisions[{{ $div->id }}][recruitment_quota]" value="{{ $div->recruitment_quota }}" min="0" placeholder="Tanpa batas" class="admin-filter-input" style="width: 100%; font-size: 0.85rem; padding: 0.4rem 0.65rem;">
                            </td>
                            <td style="text-align: center;">
                                @php
                                    $count = $div->first_choice_applicants_count ?? 0;
                                    $quota = $div->recruitment_quota;
                                    $isFull = $quota && $count >= $quota;
                                @endphp
                                <span class="badge {{ $isFull ? 'badge-danger' : 'badge-neutral' }}" style="font-size: 0.8rem; font-weight: 700; box-shadow: var(--clay-pill);">
                                    {{ $count }} {{ $quota ? '/ ' . $quota : 'pendaftar' }}
                                </span>
                            </td>
                            <td>
                                <input type="text" name="divisions[{{ $div->id }}][recruitment_notes]" value="{{ $div->recruitment_notes }}" placeholder="Contoh: Kuota Terbatas / Lab Penuh" class="admin-filter-input" style="width: 100%; font-size: 0.85rem; padding: 0.4rem 0.65rem;">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div style="display: flex; justify-content: flex-end; gap: 1rem; align-items: center;">
        <a href="{{ route('admin.recruitment.index') }}" class="btn btn-outline" style="background: #ffffff; box-shadow: var(--clay-btn);">
            Batal
        </a>
        <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 700; box-shadow: var(--clay-btn);">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin-right: 0.4rem;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Simpan Seluruh Pengaturan
        </button>
    </div>
</form>
@endsection
