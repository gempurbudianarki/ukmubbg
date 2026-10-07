@extends('admin.layouts.app')

@section('title', 'Kelola Galeri Dokumentasi - UKM CMS')
@section('page_title', 'Kelola Galeri & Dokumentasi Aktivitas')

@section('content')
<!-- Header Box -->
<div class="admin-header-box">
    <div>
        <h1 class="admin-header-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#2563eb">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Daftar Momen & Dokumentasi</span>
        </h1>
        <p class="admin-header-desc">
            Arsip visual kegiatan, prestasi kejuaraan, dan momen kolaborasi riset 4 divisi UKM.
        </p>
    </div>
</div>

<!-- Layout 2 Kolom Symmetrical: Grid Foto & Form Upload -->
<div style="display: grid; grid-template-columns: 1fr minmax(320px, 390px); gap: 1.75rem; align-items: flex-start;">
    
    <!-- Gallery Photos List Column -->
    <div>
        <!-- Filter Toolbar -->
        <form method="GET" action="{{ route('admin.galleries.index') }}" class="admin-filter-bar">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari momen / caption..." class="admin-filter-input" style="flex: 2; min-width: 200px;">
            <select name="category" class="admin-filter-input" style="flex: 1; min-width: 170px;" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <option value="Kegiatan" {{ request('category') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan / Aktivitas</option>
                <option value="Workshop" {{ request('category') == 'Workshop' ? 'selected' : '' }}>Workshop & Pelatihan</option>
                <option value="Prestasi" {{ request('category') == 'Prestasi' ? 'selected' : '' }}>Prestasi & Kompetisi</option>
                <option value="Musyawarah" {{ request('category') == 'Musyawarah' ? 'selected' : '' }}>Musyawarah & Rapat</option>
                <option value="Kunjungan" {{ request('category') == 'Kunjungan' ? 'selected' : '' }}>Kunjungan Industri</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm" style="box-shadow: var(--clay-btn);">Filter</button>
            @if (request()->hasAny(['q', 'category']))
                <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">Reset</a>
            @endif
        </form>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
            @forelse ($galleries as $gallery)
                <div class="admin-clay-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="height: 160px; background: var(--slate-900); position: relative; overflow: hidden;">
                            <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            <span class="badge" style="position: absolute; top: 0.65rem; right: 0.65rem; font-size: 0.7rem; background: rgba(15, 23, 42, 0.8); color: #ffffff; backdrop-filter: blur(4px); box-shadow: var(--clay-pill);">
                                {{ $gallery->category }}
                            </span>
                        </div>

                        <div style="padding: 1.15rem 1.25rem;">
                            <h4 style="font-size: 1rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.35rem; line-height: 1.35;">
                                {{ $gallery->title }}
                            </h4>
                            @if ($gallery->caption)
                                <p style="font-size: 0.8rem; color: var(--slate-500); margin-bottom: 0.5rem; line-height: 1.45;">
                                    {{ Str::limit($gallery->caption, 85) }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--slate-100); padding: 0.75rem 1.25rem; background: #f8fafc;">
                        <span style="font-size: 0.75rem; color: var(--slate-400); font-family: var(--font-mono);">
                            {{ $gallery->event_date ? $gallery->event_date->format('d M Y') : '-' }}
                        </span>
                        <div style="display: inline-flex; gap: 0.35rem; align-items: center;">
                            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.55rem; font-size: 0.75rem; background: #ffffff; box-shadow: var(--clay-btn);">
                                Edit
                            </a>
                            <form action="{{ route('admin.galleries.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')" style="display: inline; margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.55rem; font-size: 0.75rem; color: var(--danger); background: #ffffff; box-shadow: var(--clay-btn);">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; color: var(--slate-400); padding: 3.5rem; background: #ffffff; border-radius: var(--radius-lg); box-shadow: var(--clay-card);">
                    Belum ada foto dokumentasi yang sesuai filter.
                </div>
            @endforelse
        </div>

        @if ($galleries->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $galleries->links() }}
            </div>
        @endif
    </div>

    <!-- Upload Gallery Form Column -->
    <div class="admin-clay-card">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.35rem;">
            + Unggah Dokumentasi Baru
        </h3>
        <p style="font-size: 0.8rem; color: var(--slate-500); margin-bottom: 1.25rem;">
            Foto akan tampil di galeri portofolio publik UKM.
        </p>

        <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label for="title" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Judul Momen / Kegiatan *</label>
                <input type="text" id="title" name="title" class="admin-filter-input" required placeholder="Contoh: Juara 1 CTF Regional 2026" style="width: 100%;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="category" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Kategori Momen *</label>
                <select id="category" name="category" class="admin-filter-input" required style="width: 100%;">
                    <option value="Kegiatan">Kegiatan / Aktivitas</option>
                    <option value="Workshop">Workshop & Pelatihan</option>
                    <option value="Prestasi">Prestasi & Kompetisi</option>
                    <option value="Musyawarah">Musyawarah & Rapat</option>
                    <option value="Kunjungan">Kunjungan Industri</option>
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="image" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">File Foto / Dokumentasi *</label>
                <input type="file" id="image" name="image" class="admin-filter-input" accept="image/*" required style="width: 100%;">
                <small style="color: var(--slate-400); font-size: 0.725rem;">Format JPG/PNG/WEBP, maksimal 3MB.</small>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="caption" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Keterangan / Caption Ringkas</label>
                <textarea id="caption" name="caption" rows="3" class="admin-filter-input" placeholder="Tuliskan catatan singkat terkait momen ini..." style="width: 100%;"></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="event_date" style="font-weight: 700; font-size: 0.8rem; color: var(--slate-700);">Tanggal Kegiatan</label>
                <input type="date" id="event_date" name="event_date" value="{{ date('Y-m-d') }}" class="admin-filter-input" style="width: 100%;">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; box-shadow: var(--clay-btn); font-weight: 700;">
                Unggah Foto Dokumentasi
            </button>
        </form>
    </div>
</div>
@endsection
