@extends('admin.layouts.app')

@section('title', 'Edit Dokumentasi - UKM CMS')
@section('page_title', 'Edit Dokumentasi & Momen')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding-bottom: 3rem;">
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.025em; margin-bottom: 0.25rem;">
                Edit Dokumentasi
            </h1>
            <p style="color: var(--slate-500); font-size: 0.9rem;">
                Perbarui judul, kategori, caption, atau ganti foto dokumentasi kegiatan.
            </p>
        </div>
        <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline" style="background: #ffffff;">
            &larr; Kembali ke Galeri
        </a>
    </div>

    <div class="glass-panel" style="padding: 2rem;">
        <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="title">Judul Momen / Kegiatan *</label>
                <input type="text" id="title" name="title" class="form-control" required value="{{ old('title', $gallery->title) }}">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="category">Kategori Momen *</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="Kegiatan" {{ old('category', $gallery->category) == 'Kegiatan' ? 'selected' : '' }}>Kegiatan / Aktivitas</option>
                        <option value="Workshop" {{ old('category', $gallery->category) == 'Workshop' ? 'selected' : '' }}>Workshop & Pelatihan</option>
                        <option value="Prestasi" {{ old('category', $gallery->category) == 'Prestasi' ? 'selected' : '' }}>Prestasi & Kompetisi</option>
                        <option value="Musyawarah" {{ old('category', $gallery->category) == 'Musyawarah' ? 'selected' : '' }}>Musyawarah & Rapat</option>
                        <option value="Kunjungan" {{ old('category', $gallery->category) == 'Kunjungan' ? 'selected' : '' }}>Kunjungan Industri</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="event_date">Tanggal Kegiatan</label>
                    <input type="date" id="event_date" name="event_date" class="form-control" value="{{ old('event_date', optional($gallery->event_date)->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="caption">Keterangan / Caption Ringkas</label>
                <textarea id="caption" name="caption" rows="3" class="form-control">{{ old('caption', $gallery->caption) }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" for="image">Ganti File Foto (Opsional)</label>
                <div style="margin-bottom: 0.75rem; background: var(--slate-50); padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200); display: flex; align-items: center; gap: 1rem;">
                    <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" style="width: 100px; height: 65px; border-radius: var(--radius-sm); object-fit: cover;">
                    <div>
                        <div style="font-size: 0.85rem; font-weight: 600; color: var(--slate-800);">Foto Saat Ini</div>
                        <div style="font-size: 0.75rem; color: var(--slate-500);">Kosongkan input file jika tidak ingin mengganti foto ini.</div>
                    </div>
                </div>
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                <small style="color: var(--slate-400); font-size: 0.75rem;">Maksimal 3MB (JPG/PNG/WEBP).</small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid var(--slate-100);">
                <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
