@extends('admin.layouts.app')

@section('title', 'Manajemen Anggota UKM')
@section('page_title', 'Direktori Anggota UKM')

@section('content')
<!-- Header Box -->
<div class="admin-welcome-banner" style="margin-bottom: 2rem; padding: 1.5rem 2rem;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: 14px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--clay-pill); flex-shrink: 0;">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <h1 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.2rem 0;">
                Direktori Anggota Resmi UKM
            </h1>
            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                Kelola basis data anggota aktif, alumni, dan pembagian divisi spesialisasi mahasiswa.
            </p>
        </div>
    </div>

    <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
        <a href="{{ route('admin.members.export', request()->query()) }}" class="btn btn-outline" style="background: #ffffff; box-shadow: var(--clay-btn); display: inline-flex; align-items: center; gap: 0.45rem; border-radius: 9999px; padding: 0.65rem 1.25rem;">
            <i class="fas fa-file-csv" style="color: #059669;"></i>
            <span>Export CSV</span>
        </a>
        <button type="button" onclick="openMemberModal()" class="btn btn-primary" style="box-shadow: var(--clay-btn); display: inline-flex; align-items: center; gap: 0.45rem; border-radius: 9999px; padding: 0.65rem 1.25rem;">
            <i class="fas fa-user-plus"></i>
            <span>Tambah Anggota Baru</span>
        </button>
    </div>
</div>

<!-- Symmetrical Metric Cards Strip (4 Grid) -->
<div class="admin-stat-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #0284c7;">Total Terdata</span>
            <div class="admin-stat-icon" style="background: #e0f2fe; color: #0284c7;">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="admin-stat-value">{{ $totalMembers }}</div>
        <div class="admin-stat-sub">Mahasiswa Terdaftar</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #059669;">Anggota Aktif</span>
            <div class="admin-stat-icon" style="background: #ecfdf5; color: #059669;">
                <i class="fas fa-circle-check"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #059669;">{{ $activeMembers }}</div>
        <div class="admin-stat-sub">Mengikuti Riset & Proyek</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #6366f1;">Alumni UKM</span>
            <div class="admin-stat-icon" style="background: #e0e7ff; color: #6366f1;">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #6366f1;">{{ $alumniMembers }}</div>
        <div class="admin-stat-sub">Lulus / Demisioner</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #ec4899;">Divisi Spesialisasi</span>
            <div class="admin-stat-icon" style="background: #fdf2f8; color: #ec4899;">
                <i class="fas fa-layer-group"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #ec4899;">{{ count($divisions) }}</div>
        <div class="admin-stat-sub">Bidang Keahlian</div>
    </div>
</div>

<!-- Filter Toolbar (Clay Debossed) -->
<form method="GET" action="{{ route('admin.members.index') }}" class="admin-filter-bar">
    <div style="flex: 2; min-width: 220px;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIM, atau email..." class="admin-filter-input" style="width: 100%;">
    </div>

    @if ($user->isSuperAdmin())
        <select name="division" class="admin-filter-input" style="flex: 1; min-width: 170px;" onchange="this.form.submit()">
            <option value="">Semua Divisi</option>
            @foreach ($divisions as $div)
                <option value="{{ $div->id }}" {{ request('division') == $div->id ? 'selected' : '' }}>
                    {{ $div->name }}
                </option>
            @endforeach
        </select>
    @endif

    <select name="status" class="admin-filter-input" style="flex: 1; min-width: 150px;" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="non_aktif" {{ request('status') == 'non_aktif' ? 'selected' : '' }}>Non-Aktif</option>
        <option value="alumni" {{ request('status') == 'alumni' ? 'selected' : '' }}>Alumni</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm" style="box-shadow: var(--clay-btn); padding: 0.65rem 1.25rem;">
        Cari Data
    </button>
    @if (request()->hasAny(['q', 'division', 'status']))
        <a href="{{ route('admin.members.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">
            Reset Filter
        </a>
    @endif
</form>

<!-- Members Table Card -->
<div class="admin-clay-card-flat">
    <div class="admin-table-container">
        <table class="admin-table admin-table-members">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Divisi Spesialisasi</th>
                    <th>Angkatan</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($members as $member)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 1px solid var(--slate-200); box-shadow: var(--clay-pill); flex-shrink: 0;">
                                <div>
                                    <div style="font-weight: 700; color: var(--slate-900);">{{ $member->name }}</div>
                                    <div style="font-size: 0.775rem; color: var(--slate-500);">
                                        {{ $member->email }} 
                                        @if ($member->phone_number) &bull; {{ $member->phone_number }} @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="font-family: var(--font-mono); font-weight: 600; color: var(--slate-700);">
                            {{ $member->nim }}
                        </td>
                        <td>
                            <span class="badge" style="background: {{ $member->division->color_accent ?? '#0f172a' }}15; color: {{ $member->division->color_accent ?? '#0f172a' }}; font-size: 0.75rem; box-shadow: var(--clay-pill);">
                                {{ $member->division->name ?? '-' }}
                            </span>
                        </td>
                        <td style="color: var(--slate-600); font-weight: 600;">
                            {{ $member->batch_year }}
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ route('admin.members.updateStatus', $member) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" style="font-size: 0.775rem; font-weight: 700; padding: 0.3rem 0.6rem; border-radius: var(--radius-sm); border: 1px solid rgba(255,255,255,0.8); cursor: pointer; box-shadow: var(--clay-pill); background: {{ $member->status === 'aktif' ? 'var(--success-bg)' : ($member->status === 'alumni' ? 'var(--info-bg)' : 'var(--slate-100)') }}; color: {{ $member->status === 'aktif' ? '#059669' : ($member->status === 'alumni' ? '#0284c7' : 'var(--slate-700)') }};">
                                    <option value="aktif" {{ $member->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="non_aktif" {{ $member->status === 'non_aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                    <option value="alumni" {{ $member->status === 'alumni' ? 'selected' : '' }}>Alumni</option>
                                </select>
                            </form>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; align-items: center; gap: 0.4rem; justify-content: flex-end;">
                                <a href="{{ route('admin.members.show', $member) }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); padding: 0.35rem 0.65rem;" title="Lihat Dossier Mahasiswa">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Biodata</span>
                                </a>

                                <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); padding: 0.35rem 0.65rem;" title="Edit Data Mahasiswa">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                <form action="{{ route('admin.members.destroy', $member) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('Hapus data anggota {{ $member->name }} secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); box-shadow: var(--clay-btn); background: #ffffff; padding: 0.35rem 0.65rem;" title="Hapus Anggota">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 3.5rem; text-align: center; color: var(--slate-400);">
                            Belum ada data anggota yang sesuai filter pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($members->hasPages())
        <div style="padding: 1.25rem 1.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8); background: #f8fafc; display: flex; justify-content: center;">
            {{ $members->links() }}
        </div>
    @endif
</div>

<!-- Modal Tambah Anggota (Claymorphism Styling) -->
<div id="memberModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(6px); z-index: 999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="admin-clay-card" style="width: 100%; max-width: 540px; padding: 2rem; box-shadow: var(--clay-card-hover);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 1rem;">
            <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--slate-900); margin: 0;">Tambah Anggota Baru</h3>
            <button type="button" onclick="closeMemberModal()" style="background: none; border: none; color: var(--slate-400); font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ route('admin.members.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.8rem;">NIM Mahasiswa *</label>
                    <input type="text" name="nim" required placeholder="Contoh: 230103045" class="admin-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.8rem;">Nama Lengkap *</label>
                    <input type="text" name="name" required placeholder="Nama anggota" class="admin-filter-input" style="width: 100%;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.8rem;">Email Kampus *</label>
                    <input type="email" name="email" required placeholder="nama@kampus.ac.id" class="admin-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.8rem;">No. WhatsApp</label>
                    <input type="text" name="phone_number" placeholder="08xxxxxxxx" class="admin-filter-input" style="width: 100%;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.8rem;">Divisi *</label>
                    <select name="division_id" required class="admin-filter-input" style="width: 100%;">
                        @foreach ($divisions as $div)
                            @if ($user->isSuperAdmin() || (int)$div->id === (int)$user->division_id)
                                <option value="{{ $div->id }}">{{ $div->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.8rem;">Tahun Angkatan *</label>
                    <input type="text" name="batch_year" required value="{{ date('Y') }}" class="admin-filter-input" style="width: 100%;">
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8rem;">Status Keanggotaan *</label>
                <select name="status" required class="admin-filter-input" style="width: 100%;">
                    <option value="aktif">Aktif</option>
                    <option value="non_aktif">Non-Aktif</option>
                    <option value="alumni">Alumni</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8rem;">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Catatan keanggotaan atau prestasi..." class="admin-filter-input" style="width: 100%;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="closeMemberModal()" class="btn btn-outline" style="box-shadow: var(--clay-btn); background: #ffffff;">Batal</button>
                <button type="submit" class="btn btn-primary" style="box-shadow: var(--clay-btn);">Simpan Anggota</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openMemberModal() {
        document.getElementById('memberModal').style.display = 'flex';
    }
    function closeMemberModal() {
        document.getElementById('memberModal').style.display = 'none';
    }
</script>
@endsection
