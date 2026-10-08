@extends('admin.layouts.app')

@section('title', 'Kelola Karya Mahasiswa - UKM CMS')
@section('page_title', 'Kelola Karya & Portofolio Mahasiswa')

@section('content')
<!-- Header Box -->
<div class="admin-header-box">
    <div>
        <h1 class="admin-header-title">
            <i class="fas fa-laptop-code" style="color: #2563eb; font-size: 1.5rem;"></i>
            <span>Daftar Proyek & Riset Mahasiswa</span>
        </h1>
        <p class="admin-header-desc">
            {{ $user->isSuperAdmin() ? 'Moderasi dan kurasi karya inovasi mahasiswa dari seluruh 4 divisi yang dipublikasikan di galeri showcase.' : 'Kurasi dan moderasi karya inovasi mahasiswa khusus divisi ' . ($user->division->name ?? '') . ' untuk showcase UKM.' }}
        </p>
    </div>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary" style="box-shadow: var(--clay-btn); display: inline-flex; align-items: center; gap: 0.45rem;">
        <i class="fas fa-plus"></i>
        <span>Tambah Karya Baru</span>
    </a>
</div>

<!-- Filter Toolbar (Clay Debossed) -->
<form method="GET" action="{{ route('admin.projects.index') }}" class="admin-filter-bar">
    <div style="flex: 2; min-width: 220px;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari karya / inovator..." class="admin-filter-input" style="width: 100%;">
    </div>
    
    @if ($user->isSuperAdmin())
        <select name="division_id" class="admin-filter-input" style="flex: 1; min-width: 170px;" onchange="this.form.submit()">
            <option value="">Semua Divisi</option>
            @foreach ($divisions as $div)
                <option value="{{ $div->id }}" {{ request('division_id') == $div->id ? 'selected' : '' }}>
                    {{ $div->name }}
                </option>
            @endforeach
        </select>
    @endif

    <select name="status" class="admin-filter-input" style="flex: 1; min-width: 160px;" onchange="this.form.submit()">
        <option value="">Semua Status Moderasi</option>
        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Tayang (Approved)</option>
        <option value="pending_review" {{ request('status') == 'pending_review' ? 'selected' : '' }}>Menunggu Review</option>
        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm" style="box-shadow: var(--clay-btn); padding: 0.65rem 1.25rem;">
        Filter
    </button>
    @if (request()->hasAny(['q', 'division_id', 'status']))
        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">
            Reset
        </a>
    @endif
</form>

<!-- Symmetrical Projects Table Card -->
<div class="admin-clay-card-flat">
    <div class="admin-table-container">
        <table class="admin-table admin-table-projects">
            <thead>
                <tr>
                    <th>Judul Karya & Deskripsi</th>
                    <th>Divisi</th>
                    <th>Inovator / Pengunggah</th>
                    <th style="text-align: center;">Status Moderasi</th>
                    <th style="text-align: center;">Unggulan</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: var(--slate-900); font-size: 0.95rem;">
                                <a href="{{ route('admin.projects.edit', $project->id) }}" style="color: var(--slate-900);">
                                    {{ $project->title }}
                                </a>
                            </div>
                            <div style="font-size: 0.775rem; color: var(--slate-400); margin-top: 0.2rem;">
                                {{ Str::limit($project->description, 65) }}
                            </div>
                        </td>
                        <td>
                            @if ($project->division)
                                <span class="badge" style="background: {{ $project->division->color_accent }}15; color: {{ $project->division->color_accent }}; font-size: 0.75rem; box-shadow: var(--clay-pill);">
                                    {{ $project->division->name }}
                                </span>
                            @else
                                <span class="badge badge-neutral" style="font-size: 0.75rem; box-shadow: var(--clay-pill);">Umum</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 0.85rem; font-weight: 700; color: var(--slate-800);">
                                {{ $project->author_names }}
                            </div>
                            @if ($project->user)
                                <div style="font-size: 0.725rem; color: #0284c7; font-weight: 600;">
                                    Akun: {{ $project->user->name }}
                                </div>
                            @else
                                <div style="font-size: 0.725rem; color: var(--slate-400);">
                                    Diunggah Pengurus
                                </div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if ($project->submission_status === 'published')
                                <span class="badge badge-success" style="font-size: 0.725rem; box-shadow: var(--clay-pill);">
                                    Tayang
                                </span>
                            @elseif ($project->submission_status === 'pending_review')
                                <span class="badge badge-warning" style="font-size: 0.725rem; box-shadow: var(--clay-pill);">
                                    Review
                                </span>
                            @elseif ($project->submission_status === 'rejected')
                                <span class="badge badge-danger" style="font-size: 0.725rem; box-shadow: var(--clay-pill);">
                                    Ditolak
                                </span>
                            @else
                                <span class="badge badge-neutral" style="font-size: 0.725rem; box-shadow: var(--clay-pill);">-</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ route('admin.projects.toggleFeatured', $project->id) }}" method="POST" style="margin: 0; display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background: #ffffff; border: 1px solid {{ $project->is_featured ? '#eab308' : 'var(--slate-200)' }}; border-radius: var(--radius-sm); padding: 0.25rem 0.6rem; font-size: 0.75rem; color: {{ $project->is_featured ? '#ca8a04' : 'var(--slate-400)' }}; cursor: pointer; box-shadow: var(--clay-pill);" title="Ubah status unggulan">
                                    {{ $project->is_featured ? '★ Unggulan' : '☆ Biasa' }}
                                </button>
                            </form>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 0.35rem; align-items: center; justify-content: flex-end;">
                                @if ($project->submission_status === 'pending_review' || $project->submission_status === 'rejected')
                                    <form action="{{ route('admin.projects.moderate', $project->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="published">
                                        <button type="submit" class="btn btn-sm" style="background: #10b981; color: #fff; padding: 0.3rem 0.6rem; font-size: 0.75rem; box-shadow: var(--clay-btn);" title="Setujui dan Tayangkan">
                                            Setujui
                                        </button>
                                    </form>
                                @endif

                                @if ($project->submission_status === 'pending_review')
                                    <button type="button" onclick="openRejectModal({{ $project->id }}, '{{ addslashes($project->title) }}')" class="btn btn-sm" style="background: #ef4444; color: #fff; padding: 0.3rem 0.6rem; font-size: 0.75rem; box-shadow: var(--clay-btn); cursor: pointer;" title="Tolak dan beri catatan revisi">
                                        Tolak
                                    </button>
                                @endif

                                <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); padding: 0.3rem 0.6rem; font-size: 0.75rem;">
                                    Edit
                                </a>
                                <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Hapus karya ini?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); background: #ffffff; box-shadow: var(--clay-btn); padding: 0.3rem 0.6rem; font-size: 0.75rem;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--slate-400); padding: 3.5rem;">
                            Belum ada karya yang diunggah.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($projects->total() > 0)
        <div style="padding: 1.25rem 1.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8); background: #f8fafc;">
            {{ $projects->links() }}
        </div>
    @endif
</div>

<!-- Modal Tolak Karya & Catatan Revisi -->
<div id="rejectModal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 1.25rem;">
    <div style="background: #ffffff; border-radius: 20px; max-width: 520px; width: 100%; box-shadow: 0 20px 45px rgba(0,0,0,0.2); overflow: hidden; border: 1.5px solid #e2e8f0;">
        <form id="rejectForm" method="POST" action="">
            @csrf
            <input type="hidden" name="status" value="rejected">
            <div style="padding: 1.5rem 1.75rem; border-bottom: 1px solid #f1f5f9;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-triangle-exclamation" style="color: #ef4444;"></i> Tolak Karya Mahasiswa
                </h3>
                <p id="rejectModalProjectTitle" style="font-size: 0.825rem; color: #64748b; margin: 0; line-height: 1.5;"></p>
            </div>
            <div style="padding: 1.5rem 1.75rem;">
                <label for="admin_notes" style="font-size: 0.825rem; font-weight: 700; color: #334155; display: block; margin-bottom: 0.5rem;">
                    Alasan Penolakan / Catatan Revisi untuk Mahasiswa:
                </label>
                <textarea id="admin_notes" name="admin_notes" rows="4" class="form-control" style="width: 100%; border-radius: 12px; font-size: 0.85rem; padding: 0.75rem; border: 1.5px solid #cbd5e1; box-sizing: border-box;" placeholder="Misal: Mohon tambahkan link demo yang aktif dan lengkapi deskripsi karya sebelum diajukan kembali..."></textarea>
                <small style="display: block; font-size: 0.75rem; color: #64748b; margin-top: 0.4rem;">
                    Catatan ini akan langsung terbaca oleh mahasiswa di portal akun mereka.
                </small>
            </div>
            <div style="padding: 1rem 1.75rem; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="closeRejectModal()" class="btn btn-outline btn-sm" style="border-radius: 9999px; padding: 0.5rem 1.25rem; background: #ffffff;">Batal</button>
                <button type="submit" class="btn btn-sm" style="border-radius: 9999px; padding: 0.5rem 1.25rem; background: #ef4444; color: #ffffff; font-weight: 700; border: none; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);">Konfirmasi Tolak</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(projectId, projectTitle) {
    const form = document.getElementById('rejectForm');
    form.action = `/admin/projects/${projectId}/moderate`;
    document.getElementById('rejectModalProjectTitle').innerText = `Karya: "${projectTitle}"`;
    document.getElementById('rejectModal').style.display = 'flex';
}
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
window.addEventListener('click', function(e) {
    const modal = document.getElementById('rejectModal');
    if (e.target === modal) {
        closeRejectModal();
    }
});
</script>
@endsection
