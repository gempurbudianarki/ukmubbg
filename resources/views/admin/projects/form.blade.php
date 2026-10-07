@extends('admin.layouts.app')

@section('title', ($isEdit ? 'Edit Karya' : 'Tambah Karya Baru') . ' - UKM CMS')
@section('page_title', $isEdit ? 'Edit Karya Mahasiswa' : 'Tambah Karya Mahasiswa Baru')

@section('content')
<div class="glass-panel" style="padding: 2rem; max-width: 800px;">
    <form action="{{ $isEdit ? route('admin.projects.update', $project->id) : route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="form-group">
            <label class="form-label" for="title">Judul Karya / Proyek *</label>
            <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}" class="form-control" required placeholder="Contoh: EduClass LMS Platform">
            @error('title') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        @if (auth()->user()->isSuperAdmin())
            <div class="form-group">
                <label class="form-label" for="division_id">Divisi Terkait (Opsional)</label>
                <select id="division_id" name="division_id" class="form-control">
                    <option value="">-- Proyek Umum / Multi-Divisi --</option>
                    @foreach ($divisions as $d)
                        <option value="{{ $d->id }}" {{ old('division_id', $project->division_id) == $d->id ? 'selected' : '' }}>
                            {{ $d->name }}
                        </option>
                    @endforeach
                </select>
                @error('division_id') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
            </div>
        @else
            <div class="form-group">
                <label class="form-label">Divisi Pengelola (Terkunci)</label>
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: var(--slate-100); border: 1px solid var(--slate-200); border-radius: var(--radius-md);">
                    <div style="font-weight: 700; color: var(--slate-900); display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-layer-group" style="color: #0284c7;"></i>
                        <span>{{ auth()->user()->division?->name }}</span>
                    </div>
                    <span style="font-size: 0.75rem; color: var(--slate-500); font-weight: 600;">
                        <i class="fas fa-lock"></i> Terkunci Divisi Anda
                    </span>
                </div>
                <input type="hidden" name="division_id" value="{{ auth()->user()->division_id }}">
            </div>
        @endif

        <div class="form-group">
            <label class="form-label" for="author_names">Nama Inovator / Tim Pembuat *</label>
            <input type="text" id="author_names" name="author_names" value="{{ old('author_names', $project->author_names) }}" class="form-control" required placeholder="Contoh: Muhammad Rayhan, Fikri Haikal">
            @error('author_names') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="tech_stack_input">Tech Stack (Pisahkan dengan koma)</label>
            @php
                $currentStack = is_array($project->tech_stack) ? implode(', ', $project->tech_stack) : '';
            @endphp
            <input type="text" id="tech_stack_input" name="tech_stack_input" value="{{ old('tech_stack_input', $currentStack) }}" class="form-control" placeholder="Contoh: Laravel 10, Vue 3, Docker, Tailwind">
            <small style="color: var(--slate-400); font-size: 0.775rem;">Masukkan daftar teknologi yang digunakan.</small>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Deskripsi Ringkas Karya *</label>
            <textarea id="description" name="description" rows="4" class="form-control" required placeholder="Jelaskan fungsionalitas, latar belakang, dan keunggulan proyek...">{{ old('description', $project->description) }}</textarea>
            @error('description') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label" for="demo_url">Link Live Demo (Opsional)</label>
                <input type="url" id="demo_url" name="demo_url" value="{{ old('demo_url', $project->demo_url) }}" class="form-control" placeholder="https://demo.domain.com">
            </div>

            <div class="form-group">
                <label class="form-label" for="repo_url">Link Repository GitHub (Opsional)</label>
                <input type="url" id="repo_url" name="repo_url" value="{{ old('repo_url', $project->repo_url) }}" class="form-control" placeholder="https://github.com/ukm/repo">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="thumbnail">Thumbnail / Screenshot Karya</label>
            <input type="file" id="thumbnail" name="thumbnail" class="form-control" accept="image/*">
            <small style="color: var(--slate-400); font-size: 0.775rem;">Format JPG/PNG, maksimal 2MB.</small>
            @if ($project->thumbnail)
                <div style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--slate-600);">
                    Thumbnail saat ini: <a href="{{ asset('storage/' . $project->thumbnail) }}" target="_blank">Lihat Gambar</a>
                </div>
            @endif
        </div>

        <div class="form-group" style="margin-bottom: 2rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $project->is_featured) ? 'checked' : '' }}>
                <span style="font-size: 0.9rem; font-weight: 600; color: var(--slate-800);">Tampilkan sebagai Featured di Halaman Utama (Beranda)</span>
            </label>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">
                {{ $isEdit ? 'Simpan Perubahan' : 'Publikasikan Karya' }}
            </button>
            <a href="{{ route('admin.projects.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
