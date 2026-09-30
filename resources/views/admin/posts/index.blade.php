@extends('admin.layouts.app')

@section('title', 'Manajemen Artikel & Publikasi - UKM CMS')
@section('page_title', 'Kelola Artikel & Publikasi')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--slate-900);">
            Daftar Publikasi Kanal
        </h2>
        <p style="color: var(--slate-500); font-size: 0.875rem;">
            {{ $user->isSuperAdmin() ? 'Kelola seluruh postingan dari 4 divisi UKM' : 'Kelola postingan khusus ' . $user->division->name }}
        </p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span>Tulis Artikel Baru</span>
    </a>
</div>

<!-- Search & Filter Filter -->
<div class="card" style="margin-bottom: 1.5rem; padding: 1rem 1.5rem;">
    <form action="{{ route('admin.posts.index') }}" method="GET" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul artikel..." class="form-control" style="flex: 2; min-width: 200px;">

        @if ($user->isSuperAdmin())
            <select name="divisi" class="form-select" style="flex: 1; min-width: 160px;">
                <option value="">Semua Divisi</option>
                @foreach ($divisions as $d)
                    <option value="{{ $d->id }}" {{ request('divisi') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                @endforeach
            </select>
        @endif

        <select name="status" class="form-select" style="flex: 1; min-width: 140px;">
            <option value="">Semua Status</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
        </select>

        <button type="submit" class="btn btn-outline">Filter</button>
    </form>
</div>

<!-- Table Card -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 70px;">Media</th>
                    <th>Judul Artikel</th>
                    <th>Divisi</th>
                    <th>Penulis</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Tanggal</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>
                            <div style="width: 50px; height: 38px; border-radius: var(--radius-sm); overflow: hidden; background: var(--slate-100); display: flex; align-items: center; justify-content: center;">
                                @if ($post->thumbnail)
                                    <img src="{{ asset($post->thumbnail) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="font-size: 0.65rem; color: var(--slate-400);">No Pic</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--slate-900);">
                                <a href="{{ route('posts.show', $post->slug) }}" target="_blank" style="color: var(--slate-900);">
                                    {{ $post->title }}
                                </a>
                            </div>
                            <small style="color: var(--slate-500); text-transform: capitalize;">Kategori: {{ $post->category }}</small>
                        </td>
                        <td>
                            <span class="badge" style="background-color: {{ $post->division->color_accent }}15; color: {{ $post->division->color_accent }};">
                                {{ $post->division->name }}
                            </span>
                        </td>
                        <td style="font-size: 0.85rem;">{{ $post->author->name }}</td>
                        <td>
                            <span class="badge {{ $post->status === 'published' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td style="font-size: 0.85rem; font-weight: 600;">{{ $post->views_count }}</td>
                        <td style="font-size: 0.8rem; color: var(--slate-500);">{{ $post->created_at->format('d/m/Y') }}</td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.5rem;">
                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-outline btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem; color: var(--slate-500);">
                            Belum ada artikel publikasi. Klik tombol "Tulis Artikel Baru" di atas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($posts->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--slate-200);">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
