@extends('admin.layouts.app')

@section('title', 'Papan Pengumuman Organisasi & Divisi')
@section('page_title', 'Papan Pengumuman Anggota')

@section('content')
<!-- Header Box -->
<div class="admin-header-box">
    <div>
        <h1 class="admin-header-title">
            <i class="fas fa-bullhorn" style="color: #2563eb; font-size: 1.5rem;"></i>
            <span>Papan Pengumuman & Notifikasi</span>
        </h1>
        <p class="admin-header-desc">
            {{ $user->isSuperAdmin() ? 'Terbitkan informasi penting, reminder agenda riset, atau pengumuman berkala yang langsung tampil di Portal Mahasiswa.' : 'Terbitkan pengumuman resmi dan reminder agenda khusus anggota divisi ' . ($user->division->name ?? '') . '.' }}
        </p>
    </div>
</div>

<!-- Layout 2 Kolom: Form Buat Pengumuman & Feed Pengumuman -->
<div class="admin-split-layout reverse">
    
    <!-- Form Buat Pengumuman Baru -->
    <div class="admin-clay-card">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin: 0 0 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#0284c7">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Terbitkan Pengumuman</span>
        </h3>

        <form action="{{ route('admin.announcements.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8rem;">Judul Pengumuman *</label>
                <input type="text" name="title" required placeholder="Contoh: Jadwal Booting Divisi Pekan Ini" class="admin-filter-input" style="width: 100%;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8rem;">Kategori *</label>
                <select name="category" class="admin-filter-input" style="width: 100%;">
                    <option value="info">Informasi Umum</option>
                    <option value="penting">Penting / Urgent</option>
                    <option value="agenda">Agenda / Acara</option>
                </select>
            </div>

            @if ($user->isSuperAdmin())
                <div style="margin-bottom: 1rem;">
                    <label class="form-label" style="font-weight: 700; font-size: 0.8rem;">Target Sasaran Divisi</label>
                    <select name="division_id" class="admin-filter-input" style="width: 100%;">
                        <option value="">Semua Divisi (Umum UKM)</option>
                        @foreach ($divisions as $div)
                            <option value="{{ $div->id }}">{{ $div->name }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" name="division_id" value="{{ $user->division_id }}">
            @endif

            <div style="margin-bottom: 1rem;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8rem;">Isi Pesan Pengumuman *</label>
                <textarea name="content" required rows="5" placeholder="Tulis rincian pesan pengumuman..." class="admin-filter-input" style="width: 100%; line-height: 1.5;"></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.825rem; color: #475569;">
                    <input type="checkbox" name="is_pinned" value="1" style="accent-color: #2563eb; width: 16px; height: 16px;">
                    <strong>Sematkan di Atas (Pin Pengumuman)</strong>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; font-weight: 700; box-shadow: var(--clay-btn);">
                Terbitkan Sekarang &rarr;
            </button>
        </form>
    </div>

    <!-- Feed Pengumuman Aktif -->
    <div>
        <!-- Filter Toolbar -->
        <form method="GET" action="{{ route('admin.announcements.index') }}" class="admin-filter-bar">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul atau isi pengumuman..." class="admin-filter-input" style="flex: 2; min-width: 200px;">
            @if ($user->isSuperAdmin())
                <select name="division" class="admin-filter-input" style="flex: 1; min-width: 160px;" onchange="this.form.submit()">
                    <option value="">Semua Sasaran</option>
                    <option value="umum" {{ request('division') === 'umum' ? 'selected' : '' }}>Umum (Semua Divisi)</option>
                    @foreach ($divisions as $div)
                        <option value="{{ $div->id }}" {{ request('division') == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                    @endforeach
                </select>
            @endif
            <button type="submit" class="btn btn-primary btn-sm" style="box-shadow: var(--clay-btn);">Filter</button>
            @if (request()->hasAny(['q', 'division']))
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">Reset</a>
            @endif
        </form>

        <!-- List Cards Pengumuman -->
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            @forelse ($announcements as $ann)
                <div class="admin-clay-card" style="padding: 1.5rem 1.75rem;">
                    @if ($ann->is_pinned)
                        <div style="position: absolute; top: 1.25rem; right: 1.5rem; font-size: 0.75rem; color: #d97706; font-weight: 700; display: flex; align-items: center; gap: 0.35rem; background: #fffbeb; padding: 0.25rem 0.6rem; border-radius: var(--radius-xs); box-shadow: var(--clay-pill);">
                            <span>📌 Disematkan</span>
                        </div>
                    @endif

                    <div style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.65rem; flex-wrap: wrap;">
                        <span class="badge {{ $ann->category_badge }}" style="font-size: 0.7rem; text-transform: uppercase; box-shadow: var(--clay-pill);">
                            {{ $ann->category }}
                        </span>
                        @if ($ann->division)
                            <span class="badge" style="background: {{ $ann->division->color_accent }}15; color: {{ $ann->division->color_accent }}; font-size: 0.7rem; box-shadow: var(--clay-pill);">
                                {{ $ann->division->name }}
                            </span>
                        @else
                            <span class="badge badge-neutral" style="font-size: 0.7rem; background: #0f172a; color: #ffffff; box-shadow: var(--clay-pill);">
                                Seluruh Anggota UKM
                            </span>
                        @endif
                        <span style="font-size: 0.75rem; color: var(--slate-400);">
                            &bull; {{ $ann->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <h4 style="font-size: 1.2rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.5rem;">
                        {{ $ann->title }}
                    </h4>

                    <p style="font-size: 0.9rem; color: var(--slate-600); line-height: 1.6; margin: 0 0 1.25rem; white-space: pre-line;">
                        {{ $ann->content }}
                    </p>

                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--slate-100); padding-top: 0.85rem; font-size: 0.8rem; color: var(--slate-500);">
                        <div>
                            Diterbitkan oleh: <strong style="color: var(--slate-800);">{{ $ann->author->name ?? 'Pengurus' }}</strong>
                        </div>

                        <form action="{{ route('admin.announcements.destroy', $ann) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?');" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline btn-xs" style="color: var(--danger); background: #ffffff; box-shadow: var(--clay-btn); font-size: 0.75rem;">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="admin-clay-card" style="padding: 3.5rem 2rem; text-align: center; color: var(--slate-500);">
                    <h4 style="font-size: 1.1rem; font-weight: 800; color: var(--slate-800); margin: 0 0 0.25rem;">Belum Ada Pengumuman</h4>
                    <p style="font-size: 0.875rem; color: var(--slate-400); margin: 0;">Gunakan formulir di sebelah kiri untuk menerbitkan pengumuman pertama Anda.</p>
                </div>
            @endforelse
        </div>

        @if ($announcements->total() > 0)
            <div style="margin-top: 1.5rem;">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
