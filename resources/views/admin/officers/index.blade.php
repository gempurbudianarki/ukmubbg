@extends('admin.layouts.app')

@section('title', 'Kelola Struktur Pengurus - UKM CMS')
@section('page_title', 'Kelola Struktur Organisasi & Pengurus')

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Officers List -->
    <div class="glass-panel" style="padding: 1.75rem;">
        <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1.25rem;">
            Daftar Pejabat & Pengurus UKM
        </h3>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama & NIM</th>
                        <th>Level / Divisi</th>
                        <th>Jabatan</th>
                        <th>Urutan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($officers as $officer)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: var(--slate-900);">
                                    {{ $officer->name }}
                                </div>
                                <div style="font-family: var(--font-mono); font-size: 0.75rem; color: var(--slate-400);">
                                    {{ $officer->nim }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-neutral" style="text-transform: uppercase; font-size: 0.7rem;">
                                    {{ $officer->department_level }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; font-weight: 600; color: var(--accent-blue);">
                                    {{ $officer->position }}
                                </span>
                            </td>
                            <td>
                                <span style="font-family: var(--font-mono); font-size: 0.8rem;">
                                    {{ $officer->sort_order }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.officers.destroy', $officer->id) }}" method="POST" onsubmit="return confirm('Hapus pengurus ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--slate-400); padding: 2rem;">
                                Belum ada data pengurus.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($officers->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $officers->links() }}
            </div>
        @endif
    </div>

    <!-- Add Officer Form -->
    <div class="glass-panel" style="padding: 1.75rem;">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1.25rem;">
            + Tambah Pengurus Baru
        </h3>

        <form action="{{ route('admin.officers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap *</label>
                <input type="text" id="name" name="name" class="form-control" required placeholder="Contoh: Fathan Al-Ghifari">
            </div>

            <div class="form-group">
                <label class="form-label" for="nim">NIM / NIP *</label>
                <input type="text" id="nim" name="nim" class="form-control" required placeholder="Contoh: 210103001">
            </div>

            <div class="form-group">
                <label class="form-label" for="period">Periode Jabatan *</label>
                <input type="text" id="period" name="period" value="2026/2027" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="department_level">Tingkat / Divisi *</label>
                <select id="department_level" name="department_level" class="form-control" required>
                    <option value="bph">BPH / Pimpinan UKM</option>
                    <option value="pemrograman">Divisi Pemrograman</option>
                    <option value="multimedia">Divisi Multimedia</option>
                    <option value="iot">Divisi IoT</option>
                    <option value="cyber">Divisi Cyber Security</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="position">Nama Jabatan *</label>
                <input type="text" id="position" name="position" class="form-control" required placeholder="Contoh: Ketua Umum / Koordinator Divisi">
            </div>

            <div class="form-group">
                <label class="form-label" for="sort_order">Nomor Urutan Tampil</label>
                <input type="number" id="sort_order" name="sort_order" value="10" class="form-control">
                <small style="color: var(--slate-400); font-size: 0.75rem;">Semakin kecil nomor, semakin di atas urutannya.</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="photo">Foto Profil (Opsional)</label>
                <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Simpan Pengurus
            </button>
        </form>
    </div>
</div>
@endsection
