@extends('admin.layouts.app')

@section('title', 'Manajemen Divisi Spesialisasi - UKM CMS')
@section('page_title', 'Pusat Manajemen Divisi & Admin Spesialisasi')

@section('content')
<!-- Metric Summary Cards -->
<div class="admin-grid-4" style="margin-bottom: 2rem;">
    @php
        $activeCount = $divisions->where('status', 'active')->count();
        $suspendedCount = $divisions->where('status', 'suspended')->count();
        $totalAdmins = $divisions->sum(fn($d) => $d->admins->count());
        $totalQuota = $divisions->where('status', 'active')->sum('recruitment_quota');
    @endphp

    <div class="admin-clay-card" style="padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: 14px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--clay-pill); flex-shrink: 0;">
            <i class="fas fa-layer-group"></i>
        </div>
        <div>
            <span style="font-size: 0.775rem; color: var(--slate-400); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Divisi Aktif</span>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); font-family: var(--font-mono); line-height: 1.2;">
                {{ $activeCount }}
            </div>
        </div>
    </div>

    <div class="admin-clay-card" style="padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: 14px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--clay-pill); flex-shrink: 0;">
            <i class="fas fa-pause-circle"></i>
        </div>
        <div>
            <span style="font-size: 0.775rem; color: var(--slate-400); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Dibekukan</span>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); font-family: var(--font-mono); line-height: 1.2;">
                {{ $suspendedCount }}
            </div>
        </div>
    </div>

    <div class="admin-clay-card" style="padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: 14px; background: #ede9fe; color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--clay-pill); flex-shrink: 0;">
            <i class="fas fa-user-shield"></i>
        </div>
        <div>
            <span style="font-size: 0.775rem; color: var(--slate-400); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Admin Divisi</span>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); font-family: var(--font-mono); line-height: 1.2;">
                {{ $totalAdmins }}
            </div>
        </div>
    </div>

    <div class="admin-clay-card" style="padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: 14px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--clay-pill); flex-shrink: 0;">
            <i class="fas fa-id-card-clip"></i>
        </div>
        <div>
            <span style="font-size: 0.775rem; color: var(--slate-400); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Total Kuota</span>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); font-family: var(--font-mono); line-height: 1.2;">
                {{ $totalQuota }}
            </div>
        </div>
    </div>
</div>

<!-- Header Action Banner -->
<div class="admin-welcome-banner" style="margin-bottom: 2rem; padding: 1.5rem 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.2rem 0;">
                @if ($user->isSuperAdmin())
                    Ekosistem Divisi & Penugasan Admin
                @else
                    Profil & Biodata Divisi {{ $user->division->name ?? 'Spesialisasi' }}
                @endif
            </h1>
            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                @if ($user->isSuperAdmin())
                    Bentuk divisi baru, kelola status operasional (Aktif/Suspended), atur kuota, serta tunjuk Admin Divisi bertugas.
                @else
                    Kelola profil publik, data Dosen Pembina, Ketua Divisi, visi misi riset, serta materi kurikulum pembelajaran divisi Anda.
                @endif
            </p>
        </div>

        @if ($user->isSuperAdmin())
            <a href="{{ route('admin.divisions.create') }}" class="btn btn-primary" style="padding: 0.75rem 1.35rem; font-weight: 800; box-shadow: 0 4px 14px rgba(37,99,235,0.3); display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-plus-circle"></i>
                <span>Tambah Divisi Baru</span>
            </a>
        @endif
    </div>
</div>

<!-- Grid Kartu Divisi -->
<div class="admin-grid-2" style="margin-bottom: 2.5rem;">
    @foreach ($divisions as $div)
        @php
            $accent = $div->color_accent ?: '#0284c7';
            $isSuspended = ($div->status === 'suspended');
        @endphp
        <div class="admin-clay-card" style="border-top: 5px solid {{ $accent }}; display: flex; flex-direction: column; justify-content: space-between; padding: 1.75rem; position: relative; {{ $isSuspended ? 'opacity: 0.88; background: #fafafa;' : '' }}">
            <div>
                <!-- Top Header: Icon, Name, and Status Badges -->
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                        <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: {{ $accent }}18; color: {{ $accent }}; display: flex; align-items: center; justify-content: center; box-shadow: var(--clay-pill); font-size: 1.35rem; flex-shrink: 0;">
                            @if(!empty($div->icon_class))
                                <i class="fas {{ $div->icon_class }}"></i>
                            @elseif(!empty($div->icon_svg))
                                {!! $div->icon_svg !!}
                            @else
                                <i class="fas fa-cube"></i>
                            @endif
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                                    {{ $div->name }}
                                </h2>
                                @if ($isSuspended)
                                    <span class="badge badge-warning" style="font-size: 0.7rem; font-weight: 800;">
                                        <i class="fas fa-pause"></i> SUSPENDED
                                    </span>
                                @else
                                    <span class="badge badge-success" style="font-size: 0.7rem; font-weight: 800;">
                                        <i class="fas fa-check"></i> AKTIF
                                    </span>
                                @endif

                                @if ($div->is_recruitment_open)
                                    <span class="badge badge-info" style="font-size: 0.7rem; font-weight: 700;">
                                        Oprec Buka ({{ $div->remaining_quota ?? $div->recruitment_quota }} sisa)
                                    </span>
                                @else
                                    <span class="badge badge-neutral" style="font-size: 0.7rem; font-weight: 700;">
                                        Oprec Tutup
                                    </span>
                                @endif
                            </div>
                            <span style="font-size: 0.75rem; color: var(--slate-400); font-family: var(--font-mono); font-weight: 600;">
                                /divisi/{{ $div->slug }}
                            </span>
                        </div>
                    </div>
                </div>

                <p style="font-size: 0.875rem; color: var(--slate-600); margin-bottom: 1.25rem; line-height: 1.55;">
                    {{ $div->tagline }}
                </p>

                <!-- Box Admin Divisi Bertugas -->
                <div style="background: #f8fafc; border-radius: var(--radius-md); padding: 1rem 1.15rem; margin-bottom: 1.15rem; box-shadow: var(--clay-pill); border: 1px solid rgba(226, 232, 240, 0.8);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.65rem;">
                        <span style="font-size: 0.725rem; color: var(--slate-500); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.35rem;">
                            <i class="fas fa-user-shield" style="color: #8b5cf6;"></i> Admin Divisi Bertugas ({{ $div->admins->count() }}):
                        </span>
                        @if ($user->isSuperAdmin())
                            <button type="button" class="btn btn-outline btn-sm" onclick="openAssignAdminModal('{{ $div->id }}', '{{ addslashes($div->name) }}')" style="font-size: 0.725rem; padding: 0.25rem 0.65rem; border-radius: 9999px; background: #ffffff; color: #2563eb; font-weight: 700;">
                                <i class="fas fa-user-plus" style="margin-right: 0.25rem;"></i> + Tunjuk Admin
                            </button>
                        @endif
                    </div>

                    @if ($div->admins->isEmpty())
                        <div style="font-size: 0.8rem; color: var(--slate-400); font-style: italic; padding: 0.4rem 0;">
                            Belum ada user yang ditunjuk sebagai admin divisi ini.
                        </div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 0.45rem;">
                            @foreach ($div->admins as $adminUser)
                                <div style="display: flex; justify-content: space-between; align-items: center; background: #ffffff; padding: 0.45rem 0.75rem; border-radius: 10px; border: 1px solid #e2e8f0;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; min-width: 0;">
                                        <img src="{{ $adminUser->avatar_url }}" alt="Avatar" style="width: 26px; height: 26px; border-radius: 50%; object-fit: cover;">
                                        <div style="min-width: 0;">
                                            <div style="font-size: 0.825rem; font-weight: 700; color: var(--slate-800); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $adminUser->name }}
                                            </div>
                                            <div style="font-size: 0.7rem; color: var(--slate-400);">
                                                {{ $adminUser->email }}
                                            </div>
                                        </div>
                                    </div>
                                    @if ($user->isSuperAdmin())
                                        <form action="{{ route('admin.divisions.removeAdmin', [$div->id, $adminUser->id]) }}" method="POST" onsubmit="return confirm('Copot hak admin divisi dari {{ addslashes($adminUser->name) }}?')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline btn-sm" style="font-size: 0.68rem; padding: 0.2rem 0.5rem; border-color: #fecaca; color: #dc2626; border-radius: 6px;" title="Copot Hak Admin">
                                                Copot
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Box Biodata Pembina & Ketua -->
                <div style="background: #ffffff; border-radius: var(--radius-md); padding: 0.9rem 1.15rem; margin-bottom: 1.25rem; box-shadow: var(--clay-pill); border: 1px solid rgba(226, 232, 240, 0.7);">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.8rem;">
                        <div>
                            <span style="font-size: 0.68rem; color: var(--slate-400); text-transform: uppercase; font-weight: 800; display: block;">Pembina:</span>
                            <strong style="color: var(--slate-800);">{{ $div->adviser_name ?: '-' }}</strong>
                        </div>
                        <div>
                            <span style="font-size: 0.68rem; color: var(--slate-400); text-transform: uppercase; font-weight: 800; display: block;">Ketua Divisi:</span>
                            <strong style="color: var(--slate-800);">{{ $div->leader_name ?: '-' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem; padding-top: 0.85rem; border-top: 1px solid var(--slate-100); flex-wrap: wrap;">
                <a href="{{ route('admin.divisions.edit', $div->id) }}" class="btn btn-primary btn-sm" style="flex: 1; box-shadow: var(--clay-btn); justify-content: center; min-width: 130px;">
                    <i class="fas fa-pen-to-square" style="margin-right: 0.35rem;"></i> Edit Profil
                </a>

                @if ($user->isSuperAdmin())
                    <!-- Toggle Suspend Button -->
                    <form action="{{ route('admin.divisions.toggleStatus', $div->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); color: {{ $isSuspended ? '#16a34a' : '#d97706' }}; border-color: {{ $isSuspended ? '#bbf7d0' : '#fde68a' }};" title="{{ $isSuspended ? 'Aktifkan Divisi Kembali' : 'Bekukan / Suspend Divisi' }}">
                            <i class="fas {{ $isSuspended ? 'fa-play' : 'fa-pause' }}"></i>
                            <span style="margin-left: 0.25rem;">{{ $isSuspended ? 'Aktifkan' : 'Suspend' }}</span>
                        </button>
                    </form>

                    <!-- Toggle Recruitment Button -->
                    <form action="{{ route('admin.divisions.toggleRecruitment', $div->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); color: var(--slate-700);" title="{{ $div->is_recruitment_open ? 'Tutup Pendaftaran Divisi Ini' : 'Buka Pendaftaran Divisi Ini' }}">
                            <i class="fas {{ $div->is_recruitment_open ? 'fa-lock' : 'fa-lock-open' }}"></i>
                        </button>
                    </form>

                    <!-- Soft Delete Button -->
                    <form action="{{ route('admin.divisions.destroy', $div->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan dan mengarsipkan divisi {{ addslashes($div->name) }}? Data historis anggota dan presensi lama akan tetap tersimpan aman.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); color: #dc2626; border-color: #fecaca;" title="Arsipkan / Hapus Divisi">
                            <i class="fas fa-trash-can"></i>
                        </button>
                    </form>
                @endif

                <a href="{{ route('divisions.show', $div->slug) }}" target="_blank" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);" title="Lihat Tampilan Web Publik">
                    <i class="fas fa-arrow-up-right-from-square"></i>
                </a>
            </div>
        </div>
    @endforeach
</div>

@if ($user->isSuperAdmin() && $trashedDivisions->isNotEmpty())
    <!-- Section Divisi Terarsip (Soft Deleted) -->
    <div class="admin-clay-card" style="padding: 1.75rem; margin-top: 2rem; border-left: 5px solid var(--slate-400);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div style="display: flex; align-items: center; gap: 0.65rem;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #f1f5f9; color: var(--slate-600); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fas fa-box-archive"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                        Divisi Diarsipkan (Soft-Deleted)
                    </h3>
                    <p style="font-size: 0.8rem; color: var(--slate-500); margin: 0;">
                        Divisi di bawah ini dinonaktifkan dari publik, namun data historis mahasiswa tetap tersimpan di database.
                    </p>
                </div>
            </div>
            <span class="badge badge-neutral" style="font-weight: 700;">{{ $trashedDivisions->count() }} Divisi</span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            @foreach($trashedDivisions as $tdiv)
                <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 0.85rem 1.15rem; border-radius: 12px; border: 1px dashed #cbd5e1;">
                    <div>
                        <strong style="color: var(--slate-800); font-size: 0.95rem;">{{ $tdiv->name }}</strong>
                        <span style="font-size: 0.75rem; color: var(--slate-400); margin-left: 0.5rem; font-family: var(--font-mono);">
                            /divisi/{{ $tdiv->slug }}
                        </span>
                        <div style="font-size: 0.75rem; color: var(--slate-500); margin-top: 0.2rem;">
                            Dihapus pada: {{ $tdiv->deleted_at->format('d/m/Y H:i') }} &bull; {{ $tdiv->members_count }} Anggota Terhubung
                        </div>
                    </div>
                    <form action="{{ route('admin.divisions.restore', $tdiv->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm" style="background: #ffffff; color: #16a34a; border-color: #bbf7d0; font-weight: 700;">
                            <i class="fas fa-rotate-left" style="margin-right: 0.35rem;"></i> Pulihkan (Restore)
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Modal Tunjuk Admin Divisi (Super Admin Only) -->
@if ($user->isSuperAdmin())
<div id="assignAdminModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.45); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="admin-clay-card" style="max-width: 500px; width: 100%; background: #ffffff; border-radius: var(--radius-xl); padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.85rem;">
            <div style="display: flex; align-items: center; gap: 0.6rem;">
                <span style="width: 32px; height: 32px; border-radius: 8px; background: #ede9fe; color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 0.95rem;">
                    <i class="fas fa-user-plus"></i>
                </span>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                    Tunjuk Admin Divisi
                </h3>
            </div>
            <button type="button" onclick="closeAssignAdminModal()" style="background: none; border: none; font-size: 1.35rem; color: var(--slate-400); cursor: pointer;">
                &times;
            </button>
        </div>

        <p style="font-size: 0.85rem; color: var(--slate-500); margin-bottom: 1.25rem;">
            Pilih pengguna yang akan ditunjuk sebagai <strong>Admin Divisi</strong> untuk <span id="modalDivisionName" style="font-weight: 800; color: var(--slate-900);"></span>.
        </p>

        <form id="assignAdminForm" method="POST" action="">
            @csrf
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" for="user_id">Pilih Akun Pengguna *</label>
                <select id="user_id" name="user_id" class="form-control" required style="font-size: 0.9rem;">
                    <option value="" disabled selected>-- Pilih Pengguna Terdaftar --</option>
                    @foreach($candidateUsers as $cand)
                        <option value="{{ $cand->id }}">
                            {{ $cand->name }} ({{ $cand->email }}) [Role: {{ $cand->role }}]
                        </option>
                    @endforeach
                </select>
                <small style="color: var(--slate-400); font-size: 0.775rem; margin-top: 0.35rem; display: block;">
                    User yang ditunjuk akan otomatis memperoleh hak akses CMS untuk divisi ini.
                </small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="closeAssignAdminModal()" class="btn btn-outline" style="background: #ffffff;">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary" style="font-weight: 800;">
                    <i class="fas fa-check" style="margin-right: 0.35rem;"></i> Konfirmasi Penunjukan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAssignAdminModal(divisionId, divisionName) {
        document.getElementById('modalDivisionName').innerText = divisionName;
        document.getElementById('assignAdminForm').action = '/admin/divisions/' + divisionId + '/assign-admin';
        document.getElementById('assignAdminModal').style.display = 'flex';
    }

    function closeAssignAdminModal() {
        document.getElementById('assignAdminModal').style.display = 'none';
    }
</script>
@endif
@endsection
