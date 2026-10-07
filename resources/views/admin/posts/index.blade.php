@extends('admin.layouts.app')

@section('title', 'Manajemen Artikel & Publikasi - UKM CMS')
@section('page_title', 'Kelola Artikel & Publikasi')

@section('content')
<!-- Header Box -->
<div class="admin-header-box">
    <div>
        <h1 class="admin-header-title">
            <i class="fas fa-newspaper" style="color: #2563eb; font-size: 1.5rem;"></i>
            <span>Daftar Publikasi Kanal</span>
        </h1>
        <p class="admin-header-desc">
            {{ $user->isSuperAdmin() ? 'Kelola seluruh artikel berita, tutorial, dan dokumentasi riset dari 4 divisi.' : 'Kelola artikel publikasi dan materi riset khusus divisi ' . ($user->division->name ?? '') }}
        </p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary" style="box-shadow: var(--clay-btn); display: inline-flex; align-items: center; gap: 0.45rem;">
        <i class="fas fa-plus"></i>
        <span>Tulis Artikel Baru</span>
    </a>
</div>

<!-- Filter Toolbar (Clay Debossed) -->
<form action="{{ route('admin.posts.index') }}" method="GET" class="admin-filter-bar">
    <div style="flex: 2; min-width: 220px;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul artikel..." class="admin-filter-input" style="width: 100%;">
    </div>

    @if ($user->isSuperAdmin())
        <select name="divisi" class="admin-filter-input" style="flex: 1; min-width: 170px;">
            <option value="">Semua Divisi</option>
            @foreach ($divisions as $d)
                <option value="{{ $d->id }}" {{ request('divisi') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
            @endforeach
        </select>
    @endif

    <select name="status" class="admin-filter-input" style="flex: 1; min-width: 150px;">
        <option value="">Semua Status</option>
        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm" style="box-shadow: var(--clay-btn); padding: 0.65rem 1.25rem;">
        Filter
    </button>
    @if (request()->hasAny(['q', 'divisi', 'status']))
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">
            Reset
        </a>
    @endif
</form>

<!-- Posts Table Card (Flat Symmetrical Clay) -->
<div class="admin-clay-card-flat">
    <div class="admin-table-container">
        <table class="admin-table admin-table-posts">
            <thead>
                <tr>
                    <th style="width: 70px;">Media</th>
                    <th>Judul Artikel</th>
                    <th>Divisi</th>
                    <th>Penulis</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Views</th>
                    <th>Tanggal</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>
                            <div style="width: 50px; height: 38px; border-radius: var(--radius-sm); overflow: hidden; background: var(--slate-100); box-shadow: var(--clay-pill); display: flex; align-items: center; justify-content: center;">
                                @if ($post->thumbnail)
                                    <img src="{{ asset($post->thumbnail) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="font-size: 0.65rem; color: var(--slate-400);">No Pic</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--slate-900);">
                                <a href="{{ route('admin.posts.edit', $post->id) }}" style="color: var(--slate-900);">
                                    {{ $post->title }}
                                </a>
                            </div>
                            <small style="color: var(--slate-400); text-transform: capitalize;">Kategori: {{ $post->category }}</small>
                        </td>
                        <td>
                            <span class="badge" style="background-color: {{ $post->division->color_accent }}15; color: {{ $post->division->color_accent }}; box-shadow: var(--clay-pill); font-size: 0.75rem;">
                                {{ $post->division->name }}
                            </span>
                        </td>
                        <td style="font-size: 0.85rem; font-weight: 600; color: var(--slate-700);">{{ $post->author->name }}</td>
                        <td style="text-align: center;">
                            <form action="{{ route('admin.posts.toggleStatus', $post->id) }}" method="POST" style="margin: 0; display: inline-block;">
                                @csrf
                                <button type="submit" class="badge {{ $post->status === 'published' ? 'badge-success' : 'badge-warning' }}" style="border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.3rem; box-shadow: var(--clay-pill);" title="Ubah status publikasi">
                                    {{ $post->status === 'published' ? 'Published' : 'Draft' }}
                                </button>
                            </form>
                        </td>
                        <td style="text-align: center; font-size: 0.85rem; font-weight: 700; font-family: var(--font-mono); color: var(--slate-700);">
                            {{ $post->views_count }}
                        </td>
                        <td style="font-size: 0.8rem; color: var(--slate-500); font-family: var(--font-mono);">{{ $post->created_at->format('d/m/Y') }}</td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 0.4rem; justify-content: flex-end;">
                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); font-size: 0.75rem;">
                                    Edit
                                </a>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); background: #ffffff; box-shadow: var(--clay-btn); font-size: 0.75rem;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3.5rem; color: var(--slate-400);">
                            Belum ada artikel publikasi. Klik tombol "Tulis Artikel Baru" di atas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($posts->total() > 0)
        <div style="padding: 1.25rem 1.75rem; border-top: 1px solid rgba(226, 232, 240, 0.8); background: #f8fafc;">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
