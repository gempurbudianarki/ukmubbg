@extends('admin.layouts.app')

@section('title', 'Kelola Struktur Organisasi & Pengurus - UKM CMS')
@section('page_title', 'Kelola Struktur Organisasi & Pengurus UKM')

@section('content')
<!-- Header Box -->
<div class="admin-header-box">
    <div>
        <h1 class="admin-header-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#2563eb">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>Kelola Struktur Organisasi & Pengurus</span>
        </h1>
        <p class="admin-header-desc">
            Hierarki resmi kepengurusan: Dosen Pembina UKM &bull; Badan Pengurus Harian (BPH) &bull; Dosen & Koordinator 4 Divisi.
        </p>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
        <a href="{{ route('officers.index') }}" target="_blank" class="btn btn-outline" style="background: #ffffff; box-shadow: var(--clay-btn); display: inline-flex; align-items: center; gap: 0.45rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
            <span>Lihat Web Publik &rarr;</span>
        </a>
        <button type="button" onclick="openOfficerModal()" class="btn btn-primary" style="box-shadow: var(--clay-btn); display: inline-flex; align-items: center; gap: 0.45rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Pengurus Baru</span>
        </button>
    </div>
</div>

<!-- Symmetrical Stat Strip -->
<div class="admin-stat-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #2563eb;">Total Pengurus</span>
            <div class="admin-stat-icon" style="background: #eff6ff; color: #2563eb;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
        <div class="admin-stat-value">{{ $officers->total() }}</div>
        <div class="admin-stat-sub">Pejabat & Pembimbing Terdaftar</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #0284c7;">Pimpinan & BPH</span>
            <div class="admin-stat-icon" style="background: #e0f2fe; color: #0284c7;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #0284c7;">
            {{ $officers->where('department_level', 'bph')->count() }}
        </div>
        <div class="admin-stat-sub">Pembina, Ketum, Sekum, Bendum</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #8b5cf6;">Pengurus 4 Divisi</span>
            <div class="admin-stat-icon" style="background: #f5f3ff; color: #8b5cf6;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #8b5cf6;">
            {{ $officers->where('department_level', '!=', 'bph')->count() }}
        </div>
        <div class="admin-stat-sub">Dosen & Koordinator Spesialisasi</div>
    </div>
</div>

<!-- Filter Toolbar (Clay Debossed) -->
<form method="GET" action="{{ route('admin.officers.index') }}" class="admin-filter-bar">
    <div style="flex: 2; min-width: 220px;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIM/NIP, atau jabatan..." class="admin-filter-input" style="width: 100%;">
    </div>

    <select name="department_level" class="admin-filter-input" style="flex: 1; min-width: 180px;" onchange="this.form.submit()">
        <option value="">Semua Tingkat / Divisi</option>
        <option value="bph" {{ request('department_level') == 'bph' ? 'selected' : '' }}>BPH & Pembina UKM</option>
        <option value="pemrograman" {{ request('department_level') == 'pemrograman' ? 'selected' : '' }}>Divisi Pemrograman</option>
        <option value="multimedia" {{ request('department_level') == 'multimedia' ? 'selected' : '' }}>Divisi Multimedia</option>
        <option value="iot" {{ request('department_level') == 'iot' ? 'selected' : '' }}>Divisi IoT</option>
        <option value="cyber" {{ request('department_level') == 'cyber' ? 'selected' : '' }}>Divisi Cyber Security</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm" style="box-shadow: var(--clay-btn); padding: 0.65rem 1.25rem;">
        Cari Data
    </button>
    @if (request()->hasAny(['q', 'department_level']))
        <a href="{{ route('admin.officers.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">
            Reset Filter
        </a>
    @endif
</form>

<!-- Officers Table Card (Full Width with Contained Scroll & Sticky Headers) -->
<div class="admin-clay-card-flat">
    <div class="admin-table-container">
        <table class="admin-table admin-table-officers">
            <thead>
                <tr>
                    <th style="width: 55px; text-align: center;">Foto</th>
                    <th>Nama & Identitas</th>
                    <th>Tingkat / Divisi</th>
                    <th>Jabatan Resmi</th>
                    <th style="text-align: center;">Urutan</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($officers as $officer)
                    @php
                        $badge = $officer->badge_style;
                    @endphp
                    <tr>
                        <td style="text-align: center;">
                            @if ($officer->photo)
                                <img src="{{ asset('storage/' . $officer->photo) }}" alt="{{ $officer->name }}" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 1.5px solid var(--slate-200); box-shadow: var(--clay-pill); display: inline-block;">
                            @else
                                <div style="width: 42px; height: 42px; border-radius: 50%; background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; box-shadow: var(--clay-pill);">
                                    {{ strtoupper(substr($officer->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--slate-900); font-size: 0.925rem;">
                                {{ $officer->name }}
                            </div>
                            <div style="font-family: var(--font-mono); font-size: 0.775rem; color: var(--slate-500); margin-top: 0.15rem;">
                                {{ $officer->nim }} &bull; Periode {{ $officer->period }}
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; font-size: 0.75rem; font-weight: 700; box-shadow: var(--clay-pill);">
                                {{ $officer->department_label }}
                            </span>
                        </td>
                        <td>
                            <span style="font-size: 0.875rem; font-weight: 700; color: var(--slate-800);">
                                {{ $officer->position }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <span style="font-family: var(--font-mono); font-size: 0.8rem; font-weight: 700; background: var(--bg-body); padding: 0.25rem 0.65rem; border-radius: var(--radius-xs); box-shadow: var(--clay-pill); color: var(--slate-700);">
                                {{ $officer->sort_order }}
                            </span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 0.4rem; align-items: center; justify-content: flex-end;">
                                <a href="{{ route('admin.officers.edit', $officer) }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); font-size: 0.75rem; padding: 0.35rem 0.65rem;" title="Edit Data Pengurus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit</span>
                                </a>
                                <form action="{{ route('admin.officers.destroy', $officer->id) }}" method="POST" onsubmit="return confirm('Hapus pejabat pengurus {{ addslashes($officer->name) }}?')" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); background: #ffffff; box-shadow: var(--clay-btn); font-size: 0.75rem; padding: 0.35rem 0.65rem;" title="Hapus Pengurus">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--slate-400); padding: 3.5rem;">
                            Belum ada data pengurus yang sesuai filter pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($officers->hasPages())
        <div style="padding: 1.25rem 1.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8); background: #f8fafc; display: flex; justify-content: center;">
            {{ $officers->links() }}
        </div>
    @endif
</div>

<!-- Modal Tambah Pengurus Baru (Claymorphism Style) -->
<div id="officerModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(6px); z-index: 999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="admin-clay-card" style="width: 100%; max-width: 620px; max-height: 90vh; overflow-y: auto; padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.75rem;">
            <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--slate-900); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#2563eb">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <span>Tambah Pengurus Baru</span>
            </h3>
            <button type="button" onclick="closeOfficerModal()" style="background: none; border: none; font-size: 1.5rem; color: var(--slate-400); cursor: pointer; line-height: 1;">&times;</button>
        </div>

        <form action="{{ route('admin.officers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Template Preset Selector -->
            <div style="background: #eff6ff; border-radius: var(--radius-md); padding: 0.95rem 1.15rem; margin-bottom: 1.25rem; box-shadow: var(--clay-pill);">
                <label for="presetSelector" style="color: #2563eb; font-weight: 800; font-size: 0.8rem; display: block; margin-bottom: 0.35rem;">
                    ⚡ Template Jabatan Cepat (Otomatis Atur Tingkat, Posisi & Urutan)
                </label>
                <select id="presetSelector" class="admin-filter-input" style="width: 100%; font-size: 0.825rem; background: #ffffff;" onchange="applyRolePreset(this.value)">
                    <option value="">-- Pilih Rekomendasi Struktur --</option>
                    <optgroup label="1. Pimpinan & Pembina Utama UKM (BPH)">
                        <option value="pembina">Dosen Pembina Utama UKM</option>
                        <option value="ketua_umum">Ketua Umum UKM</option>
                        <option value="wakil_ketua">Wakil Ketua Umum UKM</option>
                        <option value="sekretaris">Sekretaris Umum</option>
                        <option value="bendahara">Bendahara Umum</option>
                    </optgroup>
                    <optgroup label="2. Dosen Pembimbing per Divisi">
                        <option value="pembina_pemrograman">Dosen Pembimbing Divisi Pemrograman</option>
                        <option value="pembina_multimedia">Dosen Pembimbing Divisi Multimedia</option>
                        <option value="pembina_iot">Dosen Pembimbing Divisi IoT</option>
                        <option value="pembina_cyber">Dosen Pembimbing Divisi Cyber Security</option>
                    </optgroup>
                    <optgroup label="3. Ketua / Koordinator Divisi (Mahasiswa)">
                        <option value="ketua_pemrograman">Koordinator Divisi Pemrograman</option>
                        <option value="ketua_multimedia">Koordinator Divisi Multimedia</option>
                        <option value="ketua_iot">Koordinator Divisi IoT</option>
                        <option value="ketua_cyber">Koordinator Divisi Cyber Security</option>
                    </optgroup>
                    <optgroup label="4. Pengurus / Staf Divisi">
                        <option value="staf_pemrograman">Staf Ahli Divisi Pemrograman</option>
                        <option value="staf_multimedia">Staf Ahli Divisi Multimedia</option>
                        <option value="staf_iot">Staf Ahli Divisi IoT</option>
                        <option value="staf_cyber">Staf Ahli Divisi Cyber Security</option>
                    </optgroup>
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="name" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Nama Lengkap & Gelar *</label>
                <input type="text" id="name" name="name" class="admin-filter-input" required placeholder="Contoh: Dr. Ir. Hendra Saputra, M.Kom. atau Fathan Al-Ghifari" style="width: 100%;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="nim" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">NIM Mahasiswa / NIP Dosen *</label>
                <input type="text" id="nim" name="nim" class="admin-filter-input" required placeholder="Contoh: 210103001 (NIM) atau 1980... (NIP)" style="width: 100%;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                    <label for="period" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Periode *</label>
                    <input type="text" id="period" name="period" value="2026/2027" class="admin-filter-input" required style="width: 100%;">
                </div>
                <div>
                    <label for="sort_order" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Urutan Tampil</label>
                    <input type="number" id="sort_order" name="sort_order" value="10" class="admin-filter-input" style="width: 100%;">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="department_level" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Tingkat / Divisi *</label>
                <select id="department_level" name="department_level" class="admin-filter-input" required style="width: 100%;">
                    <option value="bph">BPH / Pimpinan & Pembina Utama UKM</option>
                    <option value="pemrograman">Divisi Pemrograman</option>
                    <option value="multimedia">Divisi Multimedia</option>
                    <option value="iot">Divisi IoT</option>
                    <option value="cyber">Divisi Cyber Security</option>
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="position" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Nama Jabatan Resmi *</label>
                <input type="text" id="position" name="position" class="admin-filter-input" required placeholder="Contoh: Dosen Pembina Utama UKM atau Ketua Umum UKM" style="width: 100%;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="photo" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Foto Profil Resmi</label>
                <input type="file" id="photo" name="photo" class="admin-filter-input" accept="image/*" style="width: 100%;">
                <small style="color: var(--slate-400); font-size: 0.725rem; display: block; margin-top: 0.25rem;">Format JPG, PNG, WEBP. Maks 2MB.</small>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" onclick="closeOfficerModal()" class="btn btn-outline" style="background: #ffffff; box-shadow: var(--clay-btn);">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary" style="box-shadow: var(--clay-btn); font-weight: 700;">
                    Simpan Pejabat Pengurus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openOfficerModal() {
        const modal = document.getElementById('officerModal');
        if (modal) modal.style.display = 'flex';
    }

    function closeOfficerModal() {
        const modal = document.getElementById('officerModal');
        if (modal) modal.style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('officerModal');
        if (event.target === modal) {
            closeOfficerModal();
        }
    }

    function applyRolePreset(val) {
        if (!val) return;
        const level = document.getElementById('department_level');
        const pos = document.getElementById('position');
        const sort = document.getElementById('sort_order');

        switch(val) {
            case 'pembina':
                level.value = 'bph';
                pos.value = 'Dosen Pembina Utama UKM';
                sort.value = 1;
                break;
            case 'ketua_umum':
                level.value = 'bph';
                pos.value = 'Ketua Umum UKM';
                sort.value = 2;
                break;
            case 'wakil_ketua':
                level.value = 'bph';
                pos.value = 'Wakil Ketua Umum UKM';
                sort.value = 3;
                break;
            case 'sekretaris':
                level.value = 'bph';
                pos.value = 'Sekretaris Umum';
                sort.value = 4;
                break;
            case 'bendahara':
                level.value = 'bph';
                pos.value = 'Bendahara Umum';
                sort.value = 5;
                break;
            case 'pembina_pemrograman':
                level.value = 'pemrograman';
                pos.value = 'Dosen Pembimbing Divisi Pemrograman';
                sort.value = 5;
                break;
            case 'ketua_pemrograman':
                level.value = 'pemrograman';
                pos.value = 'Koordinator Divisi Pemrograman';
                sort.value = 6;
                break;
            case 'pembina_multimedia':
                level.value = 'multimedia';
                pos.value = 'Dosen Pembimbing Divisi Multimedia';
                sort.value = 7;
                break;
            case 'ketua_multimedia':
                level.value = 'multimedia';
                pos.value = 'Koordinator Divisi Multimedia';
                sort.value = 8;
                break;
            case 'pembina_iot':
                level.value = 'iot';
                pos.value = 'Dosen Pembimbing Divisi IoT';
                sort.value = 9;
                break;
            case 'ketua_iot':
                level.value = 'iot';
                pos.value = 'Koordinator Divisi IoT';
                sort.value = 10;
                break;
            case 'pembina_cyber':
                level.value = 'cyber';
                pos.value = 'Dosen Pembimbing Divisi Cyber Security';
                sort.value = 11;
                break;
            case 'ketua_cyber':
                level.value = 'cyber';
                pos.value = 'Koordinator Divisi Cyber Security';
                sort.value = 12;
                break;
            case 'staf_pemrograman':
                level.value = 'pemrograman';
                pos.value = 'Staf Divisi Pemrograman';
                sort.value = 15;
                break;
            case 'staf_multimedia':
                level.value = 'multimedia';
                pos.value = 'Staf Divisi Multimedia';
                sort.value = 16;
                break;
            case 'staf_iot':
                level.value = 'iot';
                pos.value = 'Staf Divisi IoT';
                sort.value = 17;
                break;
            case 'staf_cyber':
                level.value = 'cyber';
                pos.value = 'Staf Divisi Cyber Security';
                sort.value = 18;
                break;
        }
    }
</script>
@endsection
