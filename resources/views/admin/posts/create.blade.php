@extends('admin.layouts.app')

@section('title', 'Tulis Artikel Baru - UKM CMS')
@section('page_title', 'Tulis Artikel Baru')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--slate-900);">Tulis Artikel Baru</h2>
            <p style="color: var(--slate-500); font-size: 0.85rem;">Publikasikan berita, tutorial, atau riset divisi Anda ke kanal web.</p>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline btn-sm">&larr; Kembali ke Daftar</a>
    </div>

    <div class="card">
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label" for="title">Judul Artikel *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: Workshop Pengenalan IoT dengan ESP32..." required>
                @error('title') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                @if ($user->isSuperAdmin())
                    <div class="form-group">
                        <label class="form-label" for="division_id">Pilih Divisi *</label>
                        <select id="division_id" name="division_id" class="form-select @error('division_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Divisi --</option>
                            @foreach ($divisions as $d)
                                <option value="{{ $d->id }}" {{ old('division_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                        @error('division_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                @else
                    <div class="form-group">
                        <label class="form-label">Divisi Penulis</label>
                        <input type="text" class="form-control" value="{{ $user->division->name }}" readonly style="background-color: var(--slate-100);">
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label" for="category">Kategori Artikel *</label>
                    <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="kegiatan" {{ old('category') === 'kegiatan' ? 'selected' : '' }}>Dokumentasi Kegiatan</option>
                        <option value="tutorial" {{ old('category') === 'tutorial' ? 'selected' : '' }}>Tutorial & Edukasi</option>
                        <option value="berita" {{ old('category') === 'berita' ? 'selected' : '' }}>Berita & Pengumuman</option>
                        <option value="proyek" {{ old('category') === 'proyek' ? 'selected' : '' }}>Proyek & Riset</option>
                    </select>
                    @error('category') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="excerpt">Ringkasan Singkat (Excerpt) *</label>
                <textarea id="excerpt" name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" style="min-height: 80px;" placeholder="Cuplikan 1-2 kalimat pengantar artikel yang akan tampil pada kartu preview..." required>{{ old('excerpt') }}</textarea>
                @error('excerpt') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="content">Isi Lengkap Artikel (Mendukung HTML & Paragraf) *</label>
                <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" style="min-height: 300px; font-family: inherit;" placeholder="Tuliskan isi artikel Anda di sini... Anda bisa menggunakan tag paragraf <p>, <h3>, <ul>, <li>, atau format teks biasa." required>{{ old('content') }}</textarea>
                @error('content') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; align-items: start;">
                <div class="form-group">
                    <label class="form-label" for="thumbnail">Thumbnail Gambar (Opsional)</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="form-control @error('thumbnail') is-invalid @enderror" data-preview="#thumb-preview">
                    <small style="color: var(--slate-500); font-size: 0.775rem;">Format JPG, PNG, WEBP. Maks 3MB.</small>
                    @error('thumbnail') <div class="form-error">{{ $message }}</div> @enderror

                    <div style="margin-top: 0.75rem;">
                        <img id="thumb-preview" src="#" alt="Preview" style="display: none; max-height: 140px; border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Status Publikasi *</label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published (Langsung Terbit ke Publik)</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Simpan Sementara)</option>
                    </select>
                    @error('status') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-100); display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    Simpan & Terbitkan Artikel &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
