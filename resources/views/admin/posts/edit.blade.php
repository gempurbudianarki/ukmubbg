@extends('admin.layouts.app')

@section('title', 'Edit Artikel - UKM CMS')
@section('page_title', 'Edit Artikel')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--slate-900);">Edit Artikel</h2>
            <p style="color: var(--slate-500); font-size: 0.85rem;">Perbarui konten atau ubah status publikasi artikel.</p>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline btn-sm">&larr; Kembali ke Daftar</a>
    </div>

    <div class="card">
        <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="title">Judul Artikel *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                @error('title') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                @if ($user->isSuperAdmin())
                    <div class="form-group">
                        <label class="form-label" for="division_id">Divisi *</label>
                        <select id="division_id" name="division_id" class="form-select @error('division_id') is-invalid @enderror" required>
                            @foreach ($divisions as $d)
                                <option value="{{ $d->id }}" {{ old('division_id', $post->division_id) == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('division_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                @else
                    <div class="form-group">
                        <label class="form-label">Divisi</label>
                        <input type="text" class="form-control" value="{{ $post->division->name }}" readonly style="background-color: var(--slate-100);">
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label" for="category">Kategori Artikel *</label>
                    <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="kegiatan" {{ old('category', $post->category) === 'kegiatan' ? 'selected' : '' }}>Dokumentasi Kegiatan</option>
                        <option value="tutorial" {{ old('category', $post->category) === 'tutorial' ? 'selected' : '' }}>Tutorial & Edukasi</option>
                        <option value="berita" {{ old('category', $post->category) === 'berita' ? 'selected' : '' }}>Berita & Pengumuman</option>
                        <option value="proyek" {{ old('category', $post->category) === 'proyek' ? 'selected' : '' }}>Proyek & Riset</option>
                    </select>
                    @error('category') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="excerpt">Ringkasan Singkat (Excerpt) *</label>
                <textarea id="excerpt" name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" style="min-height: 80px;" required>{{ old('excerpt', $post->excerpt) }}</textarea>
                @error('excerpt') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="content">Isi Lengkap Artikel *</label>
                <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" style="min-height: 300px; font-family: inherit;" required>{{ old('content', $post->content) }}</textarea>
                @error('content') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; align-items: start;">
                <div class="form-group">
                    <label class="form-label" for="thumbnail">Ganti Thumbnail Gambar (Opsional)</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="form-control @error('thumbnail') is-invalid @enderror" data-preview="#thumb-preview">
                    <small style="color: var(--slate-500); font-size: 0.775rem;">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                    @error('thumbnail') <div class="form-error">{{ $message }}</div> @enderror

                    <div style="margin-top: 0.75rem;">
                        @if ($post->thumbnail)
                            <div style="font-size: 0.75rem; color: var(--slate-500); margin-bottom: 0.25rem;">Gambar Saat Ini:</div>
                            <img id="thumb-preview" src="{{ asset($post->thumbnail) }}" alt="Thumbnail" style="max-height: 120px; border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
                        @else
                            <img id="thumb-preview" src="#" alt="Preview" style="display: none; max-height: 120px; border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Status Publikasi *</label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published (Tampil ke Publik)</option>
                        <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft (Sembunyikan)</option>
                    </select>
                    @error('status') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-100); display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    Perbarui Artikel &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
