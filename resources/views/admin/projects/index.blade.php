@extends('admin.layouts.app')

@section('title', 'Kelola Karya Mahasiswa - UKM CMS')
@section('page_title', 'Kelola Karya & Portofolio Mahasiswa')

@section('content')
<div class="glass-panel" style="padding: 1.75rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900);">
                Daftar Proyek & Riset Mahasiswa
            </h3>
            <p style="font-size: 0.85rem; color: var(--slate-500);">
                Kelola karya inovasi yang dipublikasikan di halaman depan dan galeri portofolio.
            </p>
        </div>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">
            + Tambah Karya Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Judul Karya</th>
                    <th>Divisi</th>
                    <th>Inovator / Tim</th>
                    <th>Featured</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: var(--slate-900);">
                                {{ $project->title }}
                            </div>
                            <div style="font-size: 0.775rem; color: var(--slate-400);">
                                {{ Str::limit($project->description, 60) }}
                            </div>
                        </td>
                        <td>
                            @if ($project->division)
                                <span class="badge" style="background: {{ $project->division->color_accent }}15; color: {{ $project->division->color_accent }}; font-size: 0.75rem;">
                                    {{ $project->division->name }}
                                </span>
                            @else
                                <span class="badge badge-neutral" style="font-size: 0.75rem;">Umum</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-size: 0.85rem; color: var(--slate-700);">
                                {{ $project->author_names }}
                            </span>
                        </td>
                        <td>
                            @if ($project->is_featured)
                                <span class="badge badge-success" style="font-size: 0.7rem;">Featured</span>
                            @else
                                <span style="font-size: 0.75rem; color: var(--slate-400);">-</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-outline btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Hapus karya ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--slate-400); padding: 2rem;">
                            Belum ada karya yang diunggah.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($projects->hasPages())
        <div style="margin-top: 1.5rem;">
            {{ $projects->links() }}
        </div>
    @endif
</div>
@endsection
