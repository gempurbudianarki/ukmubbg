@extends('admin.layouts.app')

@section('title', 'Manajemen Anggota UKM')

@section('content')
<div style="padding-bottom: 3rem;">
    <!-- Top Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.025em; margin-bottom: 0.35rem;">
                Direktori Anggota UKM
            </h1>
            <p style="color: var(--slate-500); font-size: 0.925rem;">
                Kelola basis data anggota aktif, alumni, dan pembagian divisi spesialisasi mahasiswa.
            </p>
        </div>

        <button type="button" onclick="openMemberModal()" class="btn btn-primary" style="box-shadow: 0 4px 14px rgba(37,99,235,0.25);">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Anggota Baru</span>
        </button>
    </div>

    <!-- Metric Cards Strip -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <div style="background: #ffffff; border: 1px solid var(--slate-200); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; box-shadow: var(--shadow-card);">
            <div style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--slate-500); letter-spacing: 0.05em; margin-bottom: 0.35rem;">
                Total Terdata
            </div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--slate-900); line-height: 1;">
                {{ $totalMembers }}
            </div>
            <div style="font-size: 0.775rem; color: var(--slate-400); margin-top: 0.4rem;">Mahasiswa Ilmu Komputer</div>
        </div>

        <div style="background: #ffffff; border: 1px solid rgba(16, 185, 129, 0.3); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; box-shadow: 0 4px 18px rgba(16, 185, 129, 0.08);">
            <div style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: #059669; letter-spacing: 0.05em; margin-bottom: 0.35rem;">
                Anggota Aktif
            </div>
            <div style="font-size: 2rem; font-weight: 800; color: #059669; line-height: 1;">
                {{ $activeMembers }}
            </div>
            <div style="font-size: 0.775rem; color: var(--slate-500); margin-top: 0.4rem;">Siap agenda & kepengurusan</div>
        </div>

        <div style="background: #ffffff; border: 1px solid rgba(14, 165, 233, 0.3); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; box-shadow: 0 4px 18px rgba(14, 165, 233, 0.08);">
            <div style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: #0284c7; letter-spacing: 0.05em; margin-bottom: 0.35rem;">
                Alumni UKM
            </div>
            <div style="font-size: 2rem; font-weight: 800; color: #0284c7; line-height: 1;">
                {{ $alumniMembers }}
            </div>
            <div style="font-size: 0.775rem; color: var(--slate-500); margin-top: 0.4rem;">Lulus / Demisioner</div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div style="background: #ffffff; border: 1px solid var(--slate-200); border-radius: var(--radius-lg); padding: 1.25rem; margin-bottom: 1.5rem; box-shadow: var(--shadow-subtle);">
        <form method="GET" action="{{ route('admin.members.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; justify-content: space-between;">
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; flex: 1;">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIM, atau email..." class="form-control" style="max-width: 280px; font-size: 0.875rem;">

                @if ($user->isSuperAdmin())
                    <select name="division" class="form-control" style="max-width: 200px; font-size: 0.875rem;" onchange="this.form.submit()">
                        <option value="">Semua Divisi</option>
                        @foreach ($divisions as $div)
                            <option value="{{ $div->id }}" {{ request('division') == $div->id ? 'selected' : '' }}>
                                {{ $div->name }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <select name="status" class="form-control" style="max-width: 160px; font-size: 0.875rem;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="non_aktif" {{ request('status') == 'non_aktif' ? 'selected' : '' }}>Non-Aktif</option>
                    <option value="alumni" {{ request('status') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                </select>

                <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                @if (request()->hasAny(['q', 'division', 'status']))
                    <a href="{{ route('admin.members.index') }}" class="btn btn-outline btn-sm">Reset Filter</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Members Table Card -->
    <div style="background: #ffffff; border: 1px solid var(--slate-200); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-card);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="background: #0f172a; color: #f8fafc; border-bottom: 1px solid var(--slate-800);">
                        <th style="padding: 1rem 1.25rem; font-weight: 600;">Anggota</th>
                        <th style="padding: 1rem 1.25rem; font-weight: 600;">NIM</th>
                        <th style="padding: 1rem 1.25rem; font-weight: 600;">Divisi</th>
                        <th style="padding: 1rem 1.25rem; font-weight: 600;">Angkatan</th>
                        <th style="padding: 1rem 1.25rem; font-weight: 600;">Status</th>
                        <th style="padding: 1rem 1.25rem; font-weight: 600; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="divide-y: 1px solid var(--slate-100);">
                    @forelse ($members as $member)
                        <tr style="border-bottom: 1px solid var(--slate-100); transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 1rem 1.25rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div class="avatar-round" style="background: {{ $member->division->color_accent ?? '#0284c7' }}15; color: {{ $member->division->color_accent ?? '#0284c7' }}; width: 38px; height: 38px; font-size: 0.825rem; font-weight: 700;">
                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: var(--slate-900);">{{ $member->name }}</div>
                                        <div style="font-size: 0.775rem; color: var(--slate-500);">
                                            {{ $member->email }} 
                                            @if ($member->phone_number) &bull; {{ $member->phone_number }} @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 1rem 1.25rem; font-family: var(--font-mono); font-weight: 600; color: var(--slate-700);">
                                {{ $member->nim }}
                            </td>
                            <td style="padding: 1rem 1.25rem;">
                                <span class="badge" style="background: {{ $member->division->color_accent ?? '#0f172a' }}15; color: {{ $member->division->color_accent ?? '#0f172a' }}; font-size: 0.75rem;">
                                    {{ $member->division->name ?? '-' }}
                                </span>
                            </td>
                            <td style="padding: 1rem 1.25rem; color: var(--slate-600); font-weight: 500;">
                                {{ $member->batch_year }}
                            </td>
                            <td style="padding: 1rem 1.25rem;">
                                <form action="{{ route('admin.members.updateStatus', $member) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" style="font-size: 0.775rem; font-weight: 600; padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--slate-200); cursor: pointer; background: {{ $member->status === 'aktif' ? 'var(--success-bg)' : ($member->status === 'alumni' ? 'var(--info-bg)' : 'var(--slate-100)') }}; color: {{ $member->status === 'aktif' ? '#059669' : ($member->status === 'alumni' ? '#0284c7' : 'var(--slate-700)') }};">
                                        <option value="aktif" {{ $member->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="non_aktif" {{ $member->status === 'non_aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                        <option value="alumni" {{ $member->status === 'alumni' ? 'selected' : '' }}>Alumni</option>
                                    </select>
                                </form>
                            </td>
                            <td style="padding: 1rem 1.25rem; text-align: right;">
                                <form action="{{ route('admin.members.destroy', $member) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Hapus data anggota {{ $member->name }} secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); border-color: rgba(239, 68, 68, 0.3);" title="Hapus Anggota">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 3rem; text-align: center; color: var(--slate-500);">
                                Belum ada data anggota yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($members->hasPages())
            <div style="padding: 1.25rem; border-top: 1px solid var(--slate-200); display: flex; justify-content: center;">
                {{ $members->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Anggota -->
<div id="memberModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div style="background: #ffffff; border-radius: var(--radius-lg); width: 100%; max-width: 520px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); animation: modalIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
        <div style="background: #0f172a; color: #ffffff; padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.15rem; font-weight: 700;">Tambah Anggota Baru</h3>
            <button type="button" onclick="closeMemberModal()" style="background: none; border: none; color: #94a3b8; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ route('admin.members.store') }}" method="POST" style="padding: 1.5rem;">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label class="form-label">NIM Mahasiswa *</label>
                    <input type="text" name="nim" required placeholder="Contoh: 230103045" class="form-control">
                </div>
                <div>
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" required placeholder="Nama anggota" class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label class="form-label">Email Kampus *</label>
                    <input type="email" name="email" required placeholder="nama@kampus.ac.id" class="form-control">
                </div>
                <div>
                    <label class="form-label">No. WhatsApp</label>
                    <input type="text" name="phone_number" placeholder="08xxxxxxxx" class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label class="form-label">Divisi *</label>
                    <select name="division_id" required class="form-control">
                        @foreach ($divisions as $div)
                            @if ($user->isSuperAdmin() || (int)$div->id === (int)$user->division_id)
                                <option value="{{ $div->id }}">{{ $div->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Tahun Angkatan *</label>
                    <input type="text" name="batch_year" required value="{{ date('Y') }}" class="form-control">
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label class="form-label">Status Keanggotaan *</label>
                <select name="status" required class="form-control">
                    <option value="aktif">Aktif</option>
                    <option value="non_aktif">Non-Aktif</option>
                    <option value="alumni">Alumni</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Catatan keanggotaan atau prestasi..." class="form-control"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="closeMemberModal()" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Anggota</button>
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
