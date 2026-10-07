@extends('admin.layouts.app')

@section('title', 'Tulis Artikel Baru - UKM CMS')
@section('page_title', 'Tulis Artikel Baru')

@section('content')
<div style="max-width: 950px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 46px; height: 46px; border-radius: 14px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--clay-pill);">
                <i class="fas fa-pen-nib"></i>
            </div>
            <div>
                <h1 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.15rem 0;">
                    Tulis Artikel & Riset Baru
                </h1>
                <p style="color: var(--slate-500); font-size: 0.825rem; margin: 0;">
                    Publikasikan berita, tutorial, atau riset divisi Anda ke kanal web publik UKM.
                </p>
            </div>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">
            &larr; Kembali ke Daftar
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-error" style="box-shadow: var(--clay-pill); border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.75rem;">
            <i class="fas fa-circle-exclamation" style="font-size: 1.15rem; color: #dc2626; margin-top: 0.15rem;"></i>
            <div>
                <strong style="display: block; margin-bottom: 0.25rem;">Terdapat kesalahan pada formulir:</strong>
                <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.85rem;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="admin-clay-card" style="padding: 2.25rem;">
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label class="form-label" for="title" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                    Judul Artikel / Publikasi *
                </label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" class="admin-filter-input" style="width: 100%; box-sizing: border-box;" placeholder="Contoh: Workshop Pengenalan IoT dengan ESP32..." required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                @if ($user->isSuperAdmin())
                    <div class="form-group">
                        <label class="form-label" for="division_id" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                            Pilih Divisi Penulis *
                        </label>
                        <select id="division_id" name="division_id" class="admin-filter-input" style="width: 100%; box-sizing: border-box;" required>
                            <option value="">-- Pilih Divisi --</option>
                            @foreach ($divisions as $d)
                                <option value="{{ $d->id }}" {{ old('division_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                            Divisi Penulis Terikat
                        </label>
                        <input type="text" class="admin-filter-input" value="{{ $user->division->name }}" readonly style="background: #f1f5f9; color: var(--slate-600); width: 100%; box-sizing: border-box;">
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label" for="category" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                        Kategori Publikasi *
                    </label>
                    <select id="category" name="category" class="admin-filter-input" style="width: 100%; box-sizing: border-box;" required>
                        <option value="kegiatan" {{ old('category') === 'kegiatan' ? 'selected' : '' }}>Dokumentasi Kegiatan</option>
                        <option value="tutorial" {{ old('category') === 'tutorial' ? 'selected' : '' }}>Tutorial & Edukasi</option>
                        <option value="berita" {{ old('category') === 'berita' ? 'selected' : '' }}>Berita & Pengumuman</option>
                        <option value="proyek" {{ old('category') === 'proyek' ? 'selected' : '' }}>Proyek & Riset</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label class="form-label" for="excerpt" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                    Ringkasan Singkat (Excerpt Preview) *
                </label>
                <textarea id="excerpt" name="excerpt" class="admin-filter-input" style="width: 100%; min-height: 80px; box-sizing: border-box; line-height: 1.5;" placeholder="Cuplikan 1-2 kalimat pengantar artikel yang akan tampil pada kartu preview..." required>{{ old('excerpt') }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label class="form-label" for="content" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                    Isi Lengkap Artikel (Mendukung Format Teks & HTML) *
                </label>
                <textarea id="content" name="content" class="admin-filter-input" style="width: 100%; min-height: 320px; font-family: inherit; box-sizing: border-box; line-height: 1.6;" placeholder="Tuliskan isi artikel Anda di sini..." required>{{ old('content') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; align-items: start; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label class="form-label" for="thumbnail" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                        Thumbnail Gambar Artikel (Opsional)
                    </label>
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="admin-filter-input" style="width: 100%; box-sizing: border-box;" onchange="previewThumbnail(this)">
                    <small style="color: var(--slate-400); font-size: 0.775rem; display: block; margin-top: 0.35rem;">Format JPG, PNG, WEBP. Maks 3MB.</small>

                    <div style="margin-top: 0.75rem;">
                        <img id="thumb-preview" src="#" alt="Preview" style="display: none; max-height: 150px; border-radius: var(--radius-md); border: 2px solid #ffffff; box-shadow: var(--clay-pill);">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="status" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                        Status Publikasi *
                    </label>
                    <select id="status" name="status" class="admin-filter-input" style="width: 100%; box-sizing: border-box;" required>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published (Langsung Terbit ke Web)</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Simpan Sebagai Konsep)</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 1rem; padding-top: 1.5rem; border-top: 1.5px solid rgba(226, 232, 240, 0.8);">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline" style="background: #ffffff; box-shadow: var(--clay-btn); border-radius: 9999px; padding: 0.7rem 1.5rem; font-weight: 700;">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary" style="box-shadow: var(--clay-btn); border-radius: 9999px; padding: 0.7rem 2rem; font-weight: 800; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-paper-plane"></i>
                    <span>Simpan & Terbitkan Artikel</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewThumbnail(input) {
        const preview = document.getElementById('thumb-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
