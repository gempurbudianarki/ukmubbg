@extends('admin.layouts.app')

@section('title', 'Kelola Galeri Dokumentasi - UKM CMS')
@section('page_title', 'Kelola Galeri & Dokumentasi Aktivitas')

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Gallery Photos List -->
    <div class="glass-panel" style="padding: 1.75rem;">
        <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1.25rem;">
            Daftar Momen & Dokumentasi
        </h3>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
            @forelse ($galleries as $gallery)
                <div style="border: 1px solid var(--slate-200); border-radius: var(--radius-md); overflow: hidden; background: #ffffff;">
                    <div style="height: 140px; background: var(--slate-800); display: flex; align-items: center; justify-content: center; position: relative;">
                        @if ($gallery->image_path && file_exists(public_path('storage/' . $gallery->image_path)))
                            <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="color: var(--slate-400); font-size: 0.8rem; font-weight: 600;">{{ $gallery->category }}</span>
                        @endif
                        <span class="badge badge-neutral" style="position: absolute; top: 0.5rem; right: 0.5rem; font-size: 0.7rem;">
                            {{ $gallery->category }}
                        </span>
                    </div>

                    <div style="padding: 1rem;">
                        <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.35rem; line-height: 1.35;">
                            {{ $gallery->title }}
                        </h4>
                        @if ($gallery->caption)
                            <p style="font-size: 0.8rem; color: var(--slate-500); margin-bottom: 0.75rem;">
                                {{ Str::limit($gallery->caption, 60) }}
                            </p>
                        @endif
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--slate-100); padding-top: 0.5rem;">
                            <span style="font-size: 0.75rem; color: var(--slate-400);">
                                {{ $gallery->event_date ? $gallery->event_date->format('d M Y') : '-' }}
                            </span>
                            <form action="{{ route('admin.galleries.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; color: var(--slate-400); padding: 3rem;">
                    Belum ada foto dokumentasi.
                </div>
            @endforelse
        </div>

        @if ($galleries->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $galleries->links() }}
            </div>
        @endif
    </div>

    <!-- Upload Gallery Form -->
    <div class="glass-panel" style="padding: 1.75rem;">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1.25rem;">
            + Unggah Dokumentasi Baru
        </h3>

        <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label" for="title">Judul Momen / Kegiatan *</label>
                <input type="text" id="title" name="title" class="form-control" required placeholder="Contoh: Juara 1 CTF Regional 2026">
            </div>

            <div class="form-group">
                <label class="form-label" for="category">Kategori Momen *</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="Kegiatan">Kegiatan / Aktivitas</option>
                    <option value="Workshop">Workshop & Pelatihan</option>
                    <option value="Prestasi">Prestasi & Kompetisi</option>
                    <option value="Musyawarah">Musyawarah & Rapat</option>
                    <option value="Kunjungan">Kunjungan Industri</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="image">File Foto / Dokumentasi *</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                <small style="color: var(--slate-400); font-size: 0.75rem;">Format JPG/PNG, maksimal 3MB.</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="caption">Keterangan / Caption Ringkas</label>
                <textarea id="caption" name="caption" rows="3" class="form-control" placeholder="Tuliskan catatan singkat terkait momen ini..."></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" for="event_date">Tanggal Kegiatan</label>
                <input type="date" id="event_date" name="event_date" value="{{ date('Y-m-d') }}" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Unggah Foto
            </button>
        </form>
    </div>
</div>
@endsection
