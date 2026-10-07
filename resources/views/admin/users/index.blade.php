@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna & Peran - UKM CMS')
@section('page_title', 'Manajemen Pengguna & Hak Akses (Roles)')

@section('content')
<!-- Header Box -->
<div class="admin-welcome-banner" style="margin-bottom: 2rem; padding: 1.5rem 2rem;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: 14px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--clay-pill); flex-shrink: 0;">
            <i class="fas fa-user-shield"></i>
        </div>
        <div>
            <h1 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.2rem 0;">
                Daftar Akun Pengguna & Hak Akses
            </h1>
            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                Super Admin dapat menunjuk peran (role) akun kapan saja, mengubah divisi penugasan, atau mereset password.
            </p>
        </div>
    </div>
    <button type="button" class="btn btn-primary" onclick="openCreateUserModal()" style="box-shadow: var(--clay-btn); display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 9999px; padding: 0.65rem 1.35rem;">
        <i class="fas fa-user-plus"></i>
        <span>Tambah Akun Baru</span>
    </button>
</div>

<!-- Symmetrical Metric Cards (4 Grid) -->
<div class="admin-stat-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #0284c7;">Total Pengguna</span>
            <div class="admin-stat-icon" style="background: #e0f2fe; color: #0284c7;">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="admin-stat-value">{{ $stats['total'] }}</div>
        <div class="admin-stat-sub">Akun terdaftar di sistem</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #d97706;">Super Admin</span>
            <div class="admin-stat-icon" style="background: #fef3c7; color: #d97706;">
                <i class="fas fa-crown"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #d97706;">{{ $stats['super_admin'] }}</div>
        <div class="admin-stat-sub">Otoritas sistem penuh</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #6366f1;">Admin Divisi</span>
            <div class="admin-stat-icon" style="background: #e0e7ff; color: #6366f1;">
                <i class="fas fa-shield-halved"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #4f46e5;">{{ $stats['division_admin'] }}</div>
        <div class="admin-stat-sub">Pengurus 4 spesialisasi</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-label" style="color: #10b981;">Anggota Mahasiswa</span>
            <div class="admin-stat-icon" style="background: #ecfdf5; color: #10b981;">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #10b981;">{{ $stats['member'] }}</div>
        <div class="admin-stat-sub">Anggota portal aktif</div>
    </div>
</div>

<!-- Filter Toolbar (Clay Debossed) -->
<form method="GET" action="{{ route('admin.users.index') }}" class="admin-filter-bar">
    <div style="flex: 2; min-width: 220px;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau NIM..." class="admin-filter-input" style="width: 100%;">
    </div>

    <select name="role" class="admin-filter-input" style="flex: 1; min-width: 170px;">
        <option value="">Semua Peran (Roles)</option>
        <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
        <option value="division_admin" {{ request('role') === 'division_admin' ? 'selected' : '' }}>Admin Divisi</option>
        <option value="member" {{ request('role') === 'member' ? 'selected' : '' }}>Mahasiswa (Member)</option>
    </select>

    <select name="division_id" class="admin-filter-input" style="flex: 1; min-width: 170px;">
        <option value="">Semua Divisi</option>
        @foreach ($divisions as $d)
            <option value="{{ $d->id }}" {{ request('division_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary btn-sm" style="box-shadow: var(--clay-btn); padding: 0.65rem 1.25rem;">
        Filter Akun
    </button>
    @if (request()->hasAny(['q', 'role', 'division_id']))
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">
            Reset
        </a>
    @endif
</form>

<!-- Users Table Card (Flat Symmetrical Clay) -->
<div class="admin-clay-card-flat">
    <div class="admin-table-container">
        <table class="admin-table admin-table-users">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Email & NIM</th>
                    <th style="text-align: center;">Peran (Role)</th>
                    <th>Divisi Penugasan</th>
                    <th>Terdaftar</th>
                    <th style="text-align: right;">Aksi & Tunjuk Peran</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 1.5px solid var(--slate-200); box-shadow: var(--clay-pill);">
                                <div>
                                    <div style="font-weight: 800; color: var(--slate-900);">
                                        {{ $u->name }}
                                        @if ($u->id === auth()->id())
                                            <span class="badge" style="background: #eff6ff; color: #2563eb; font-size: 0.68rem; margin-left: 0.25rem; box-shadow: var(--clay-pill);">Anda</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="color: var(--slate-700); font-weight: 600;">{{ $u->email }}</div>
                            <div style="font-size: 0.75rem; color: var(--slate-400); font-family: var(--font-mono);">
                                NIM: {{ $u->nim ?? '-' }}
                            </div>
                        </td>
                        <td style="text-align: center;">
                            @if ($u->role === 'super_admin')
                                <span class="badge" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-weight: 800; font-size: 0.725rem; box-shadow: var(--clay-pill);">
                                    Super Admin
                                </span>
                            @elseif ($u->role === 'division_admin')
                                <span class="badge" style="background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; font-weight: 800; font-size: 0.725rem; box-shadow: var(--clay-pill);">
                                    Admin Divisi
                                </span>
                            @else
                                <span class="badge" style="background: #ecfdf5; color: #15803d; border: 1px solid #bbf7d0; font-weight: 700; font-size: 0.725rem; box-shadow: var(--clay-pill);">
                                    Mahasiswa
                                </span>
                            @endif
                        </td>
                        <td>
                            @if ($u->division)
                                <span class="badge" style="background: {{ $u->division->color_accent }}15; color: {{ $u->division->color_accent }}; font-size: 0.75rem; font-weight: 700; box-shadow: var(--clay-pill);">
                                    {{ $u->division->name }}
                                </span>
                            @elseif ($u->role === 'super_admin')
                                <span style="font-size: 0.75rem; color: var(--slate-500); font-weight: 600;">
                                    Semua Divisi UKM
                                </span>
                            @else
                                <span style="font-size: 0.75rem; color: var(--slate-400);">-</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-size: 0.8rem; color: var(--slate-500); font-family: var(--font-mono);">
                                {{ $u->created_at->format('d M Y') }}
                            </span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 0.35rem; align-items: center; justify-content: flex-end;">
                                <!-- Quick Assign Role Button -->
                                <button type="button" class="btn btn-sm" onclick='openAssignRoleModal(@json($u))' style="background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 800; box-shadow: var(--clay-btn);" title="Tunjuk Peran Akun Ini">
                                    Tunjuk Role
                                </button>

                                <!-- Edit Button -->
                                <button type="button" class="btn btn-outline btn-sm" onclick='openEditUserModal(@json($u))' style="background: #ffffff; padding: 0.35rem 0.6rem; font-size: 0.75rem; box-shadow: var(--clay-btn);" title="Edit Profil Akun">
                                    Edit
                                </button>

                                <!-- Reset Password Button -->
                                <button type="button" class="btn btn-sm" onclick="openResetPasswordModal({{ $u->id }}, '{{ addslashes($u->name) }}')" style="background: #ffffff; color: var(--slate-700); padding: 0.35rem 0.6rem; font-size: 0.75rem; box-shadow: var(--clay-btn);" title="Reset Kata Sandi">
                                    Reset
                                </button>

                                <!-- Delete Button -->
                                @if ($u->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ addslashes($u->name) }}?')" style="margin: 0; display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); background: #ffffff; padding: 0.35rem 0.6rem; font-size: 0.75rem; box-shadow: var(--clay-btn);" title="Hapus Akun">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--slate-400); padding: 3.5rem;">
                            Tidak ada akun pengguna yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div style="padding: 1.25rem 1.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8); background: #f8fafc;">
            {{ $users->links() }}
        </div>
    @endif
</div>

<!-- Modal 1: Tunjuk Peran (Assign Role) Khusus Super Admin -->
<div id="modalAssignRole" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(6px); z-index: 1000; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="admin-clay-card" style="width: 100%; max-width: 540px; padding: 2rem; box-shadow: var(--clay-card-hover);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.75rem;">
            <div>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--slate-900);">
                    Tunjuk Peran & Hak Akses Akun
                </h3>
                <p style="margin: 0.25rem 0 0; font-size: 0.8rem; color: var(--slate-500);">
                    Tentukan tingkat otoritas dan penugasan divisi untuk pengguna ini.
                </p>
            </div>
            <button type="button" onclick="closeAssignRoleModal()" style="background: none; border: none; font-size: 1.5rem; color: var(--slate-400); cursor: pointer;">&times;</button>
        </div>

        <form id="assignRoleForm" method="POST">
            @csrf
            @method('PUT')

            <div style="background: #f8fafc; border-radius: var(--radius-md); padding: 0.85rem 1.15rem; margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--clay-pill);">
                <div>
                    <div style="font-size: 0.725rem; color: var(--slate-400); font-weight: 700; text-transform: uppercase;">Pengguna Terpilih</div>
                    <div style="font-size: 1rem; font-weight: 800; color: var(--slate-900);" id="assignRoleUserName"></div>
                    <div style="font-size: 0.775rem; color: var(--slate-500); font-family: var(--font-mono);" id="assignRoleUserEmail"></div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.725rem; color: var(--slate-400); font-weight: 700; text-transform: uppercase;">Peran Saat Ini</div>
                    <span id="assignRoleCurrentBadge" class="badge"></span>
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="font-weight: 800; color: var(--slate-900); font-size: 0.85rem; margin-bottom: 0.65rem; display: block;">
                    Pilih Peran Baru *
                </label>
                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                    <!-- Option 1: Super Admin -->
                    <label style="border-radius: var(--radius-md); padding: 0.85rem 1.15rem; display: flex; align-items: flex-start; gap: 0.75rem; cursor: pointer; background: #ffffff; box-shadow: var(--clay-pill);">
                        <input type="radio" name="role" value="super_admin" id="roleSuperAdmin" onchange="toggleAssignDivision()" style="margin-top: 0.25rem; accent-color: #d97706;">
                        <div>
                            <div style="font-weight: 800; color: #b45309; font-size: 0.9rem;">
                                Super Administrator
                            </div>
                            <div style="font-size: 0.75rem; color: var(--slate-500); margin-top: 0.15rem;">
                                Akses penuh tanpa batas ke semua menu, divisi, users, dan struktur pengurus.
                            </div>
                        </div>
                    </label>

                    <!-- Option 2: Division Admin -->
                    <label style="border-radius: var(--radius-md); padding: 0.85rem 1.15rem; display: flex; align-items: flex-start; gap: 0.75rem; cursor: pointer; background: #ffffff; box-shadow: var(--clay-pill);">
                        <input type="radio" name="role" value="division_admin" id="roleDivisionAdmin" onchange="toggleAssignDivision()" style="margin-top: 0.25rem; accent-color: #4f46e5;">
                        <div style="flex: 1;">
                            <div style="font-weight: 800; color: #4338ca; font-size: 0.9rem;">
                                Admin Divisi (Pengurus Divisi)
                            </div>
                            <div style="font-size: 0.75rem; color: var(--slate-500); margin-top: 0.15rem;">
                                Otoritas moderasi karya, kelola presensi, dan artikel untuk divisi yang ditugaskan.
                            </div>
                        </div>
                    </label>

                    <!-- Option 3: Member -->
                    <label style="border-radius: var(--radius-md); padding: 0.85rem 1.15rem; display: flex; align-items: flex-start; gap: 0.75rem; cursor: pointer; background: #ffffff; box-shadow: var(--clay-pill);">
                        <input type="radio" name="role" value="member" id="roleMember" onchange="toggleAssignDivision()" style="margin-top: 0.25rem; accent-color: #10b981;">
                        <div>
                            <div style="font-weight: 800; color: #15803d; font-size: 0.9rem;">
                                Anggota Mahasiswa (Member Biasa)
                            </div>
                            <div style="font-size: 0.75rem; color: var(--slate-500); margin-top: 0.15rem;">
                                Hanya dapat mengakses dashboard mahasiswa (portofolio karya, e-sertifikat, rapor presensi).
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Division Picker when role is division_admin -->
            <div id="assignDivisionWrap" style="display: none; background: #e0e7ff30; border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1.25rem; box-shadow: var(--clay-pill);">
                <label for="assignDivisionSelect" style="font-weight: 800; color: #3730a3; margin-bottom: 0.35rem; display: block; font-size: 0.85rem;">
                    Divisi Penugasan *
                </label>
                <select name="division_id" id="assignDivisionSelect" class="admin-filter-input" style="width: 100%;">
                    <option value="">-- Pilih Divisi yang Dipegang --</option>
                    @foreach ($divisions as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
                <small style="color: #4338ca; font-size: 0.75rem; margin-top: 0.35rem; display: block;">
                    Admin ini hanya akan memiliki akses kelola pada divisi terpilih di atas.
                </small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid var(--slate-100);">
                <button type="button" onclick="closeAssignRoleModal()" class="btn btn-outline" style="background: #ffffff; box-shadow: var(--clay-btn);">Batal</button>
                <button type="submit" class="btn btn-primary" style="box-shadow: var(--clay-btn); font-weight: 800;">
                    Terapkan Peran Akun
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Tambah Pengguna Baru -->
<div id="modalCreateUser" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(6px); z-index: 999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="admin-clay-card" style="width: 100%; max-width: 520px; padding: 2rem; box-shadow: var(--clay-card-hover);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.75rem;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--slate-900);">
                Tambah Akun Pengguna Baru
            </h3>
            <button type="button" onclick="closeCreateUserModal()" style="background: none; border: none; font-size: 1.25rem; color: var(--slate-400); cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Nama Lengkap *</label>
                    <input type="text" name="name" required class="admin-filter-input" placeholder="Nama anggota / admin" style="width: 100%;">
                </div>
                <div>
                    <label style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">NIM (Opsional)</label>
                    <input type="text" name="nim" class="admin-filter-input" placeholder="Contoh: 210103001" style="width: 100%;">
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Alamat Email *</label>
                <input type="email" name="email" required class="admin-filter-input" placeholder="nama@kampus.ac.id" style="width: 100%;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Kata Sandi (Password) *</label>
                <input type="password" name="password" required minlength="6" class="admin-filter-input" placeholder="Minimal 6 karakter" style="width: 100%;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Peran (Role) *</label>
                    <select name="role" id="createRoleSelect" class="admin-filter-input" required onchange="toggleDivisionSelect('create')" style="width: 100%;">
                        <option value="member">Mahasiswa (Anggota Portal)</option>
                        <option value="division_admin">Admin Divisi (Pengurus)</option>
                        <option value="super_admin">Super Administrator</option>
                    </select>
                </div>
                <div id="createDivisionWrap" style="display: none;">
                    <label style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Penugasan Divisi</label>
                    <select name="division_id" class="admin-filter-input" style="width: 100%;">
                        <option value="">-- Pilih Divisi --</option>
                        @foreach ($divisions as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid var(--slate-100);">
                <button type="button" onclick="closeCreateUserModal()" class="btn btn-outline" style="background: #ffffff; box-shadow: var(--clay-btn);">Batal</button>
                <button type="submit" class="btn btn-primary" style="box-shadow: var(--clay-btn); font-weight: 700;">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 3: Edit Profil Pengguna -->
<div id="modalEditUser" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(6px); z-index: 999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="admin-clay-card" style="width: 100%; max-width: 520px; padding: 2rem; box-shadow: var(--clay-card-hover);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.75rem;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--slate-900);">
                Edit Data Pengguna
            </h3>
            <button type="button" onclick="closeEditUserModal()" style="background: none; border: none; font-size: 1.25rem; color: var(--slate-400); cursor: pointer;">&times;</button>
        </div>
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Nama Lengkap *</label>
                    <input type="text" name="name" id="editUserName" required class="admin-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">NIM (Opsional)</label>
                    <input type="text" name="nim" id="editUserNim" class="admin-filter-input" style="width: 100%;">
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Alamat Email *</label>
                <input type="email" name="email" id="editUserEmail" required class="admin-filter-input" style="width: 100%;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Peran (Role) *</label>
                    <select name="role" id="editRoleSelect" class="admin-filter-input" required onchange="toggleDivisionSelect('edit')" style="width: 100%;">
                        <option value="super_admin">Super Administrator</option>
                        <option value="division_admin">Admin Divisi (Pengurus)</option>
                        <option value="member">Mahasiswa (Anggota Portal)</option>
                    </select>
                </div>
                <div id="editDivisionWrap">
                    <label style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Penugasan Divisi</label>
                    <select name="division_id" id="editUserDivision" class="admin-filter-input" style="width: 100%;">
                        <option value="">-- Pilih Divisi --</option>
                        @foreach ($divisions as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid var(--slate-100);">
                <button type="button" onclick="closeEditUserModal()" class="btn btn-outline" style="background: #ffffff; box-shadow: var(--clay-btn);">Batal</button>
                <button type="submit" class="btn btn-primary" style="box-shadow: var(--clay-btn); font-weight: 700;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 4: Reset Kata Sandi -->
<div id="modalResetPassword" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(6px); z-index: 999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="admin-clay-card" style="width: 100%; max-width: 440px; padding: 2rem; box-shadow: var(--clay-card-hover);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.75rem;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--slate-900);">
                Reset Kata Sandi Akun
            </h3>
            <button type="button" onclick="closeResetPasswordModal()" style="background: none; border: none; font-size: 1.25rem; color: var(--slate-400); cursor: pointer;">&times;</button>
        </div>
        <form id="resetPasswordForm" method="POST">
            @csrf
            <p style="font-size: 0.85rem; color: var(--slate-600); margin: 0 0 1rem;">
                Setel ulang kata sandi untuk pengguna <strong id="resetUserNameText"></strong>.
            </p>
            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Kata Sandi Baru *</label>
                <input type="password" name="new_password" required minlength="6" class="admin-filter-input" placeholder="Minimal 6 karakter" style="width: 100%;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid var(--slate-100);">
                <button type="button" onclick="closeResetPasswordModal()" class="btn btn-outline" style="background: #ffffff; box-shadow: var(--clay-btn);">Batal</button>
                <button type="submit" class="btn btn-primary" style="box-shadow: var(--clay-btn); font-weight: 700;">Perbarui Kata Sandi</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAssignRoleModal(user) {
        const form = document.getElementById('assignRoleForm');
        form.action = `/admin/users/${user.id}/assign-role`;

        document.getElementById('assignRoleUserName').innerText = user.name || '-';
        document.getElementById('assignRoleUserEmail').innerText = user.email || '-';

        const badge = document.getElementById('assignRoleCurrentBadge');
        if (user.role === 'super_admin') {
            badge.className = 'badge';
            badge.style.background = '#fef3c7';
            badge.style.color = '#b45309';
            badge.innerText = 'Super Admin';
            document.getElementById('roleSuperAdmin').checked = true;
        } else if (user.role === 'division_admin') {
            badge.className = 'badge';
            badge.style.background = '#e0e7ff';
            badge.style.color = '#4338ca';
            badge.innerText = 'Admin Divisi';
            document.getElementById('roleDivisionAdmin').checked = true;
        } else {
            badge.className = 'badge';
            badge.style.background = '#dcfce7';
            badge.style.color = '#15803d';
            badge.innerText = 'Mahasiswa';
            document.getElementById('roleMember').checked = true;
        }

        document.getElementById('assignDivisionSelect').value = user.division_id || '';
        toggleAssignDivision();

        document.getElementById('modalAssignRole').style.display = 'flex';
    }

    function closeAssignRoleModal() {
        document.getElementById('modalAssignRole').style.display = 'none';
    }

    function toggleAssignDivision() {
        const isDivAdmin = document.getElementById('roleDivisionAdmin').checked;
        const wrap = document.getElementById('assignDivisionWrap');
        if (isDivAdmin) {
            wrap.style.display = 'block';
            document.getElementById('assignDivisionSelect').setAttribute('required', 'required');
        } else {
            wrap.style.display = 'none';
            document.getElementById('assignDivisionSelect').removeAttribute('required');
        }
    }

    function openCreateUserModal() {
        document.getElementById('modalCreateUser').style.display = 'flex';
        toggleDivisionSelect('create');
    }
    function closeCreateUserModal() {
        document.getElementById('modalCreateUser').style.display = 'none';
    }

    function openEditUserModal(user) {
        const form = document.getElementById('editUserForm');
        form.action = `/admin/users/${user.id}`;
        document.getElementById('editUserName').value = user.name || '';
        document.getElementById('editUserEmail').value = user.email || '';
        document.getElementById('editUserNim').value = user.nim || '';
        document.getElementById('editRoleSelect').value = user.role || 'member';
        document.getElementById('editUserDivision').value = user.division_id || '';
        
        toggleDivisionSelect('edit');
        document.getElementById('modalEditUser').style.display = 'flex';
    }
    function closeEditUserModal() {
        document.getElementById('modalEditUser').style.display = 'none';
    }

    function openResetPasswordModal(userId, userName) {
        document.getElementById('resetPasswordForm').action = `/admin/users/${userId}/reset-password`;
        document.getElementById('resetUserNameText').innerText = userName;
        document.getElementById('modalResetPassword').style.display = 'flex';
    }
    function closeResetPasswordModal() {
        document.getElementById('modalResetPassword').style.display = 'none';
    }

    function toggleDivisionSelect(mode) {
        const role = mode === 'create' ? document.getElementById('createRoleSelect').value : document.getElementById('editRoleSelect').value;
        const wrap = mode === 'create' ? document.getElementById('createDivisionWrap') : document.getElementById('editDivisionWrap');
        if (role === 'division_admin') {
            wrap.style.display = 'block';
        } else {
            wrap.style.display = 'none';
        }
    }
</script>
@endsection
