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
                                    <form action="{{ route('admin.projects.moderate', $project->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm" style="background: #ef4444; color: #fff; padding: 0.3rem 0.6rem; font-size: 0.75rem; box-shadow: var(--clay-btn);" title="Tolak">
                                            Tolak
                                        </button>
                                    </form>
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
@endsection
