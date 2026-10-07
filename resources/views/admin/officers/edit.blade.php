@extends('admin.layouts.app')

@section('title', 'Edit Pengurus - ' . $officer->name)
@section('page_title', 'Edit Data Pejabat Pengurus')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding-bottom: 3rem;">
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.025em; margin-bottom: 0.25rem;">
                Edit Profil & Jabatan Pengurus
            </h1>
            <p style="color: var(--slate-500); font-size: 0.9rem;">
                Perbarui nama, jabatan resmi, divisi, atau foto pejabat kepengurusan UKM.
            </p>
        </div>
        <a href="{{ route('admin.officers.index') }}" class="btn btn-outline" style="background: #ffffff;">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="glass-panel" style="padding: 2rem;">
        <!-- Template Preset Selector -->
        <div style="background: #f8fafc; border: 1.5px dashed var(--slate-300); padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
            <label class="form-label" for="presetSelector" style="color: var(--primary); font-weight: 800; display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.35rem;">
                <i class="fas fa-wand-magic-sparkles"></i> Ganti Cepat dengan Template Jabatan
            </label>
            <select id="presetSelector" class="form-control" style="font-size: 0.85rem;" onchange="applyRolePreset(this.value)">
                <option value="">-- Pilih Rekomendasi Struktur untuk Update Cepat --</option>
                <optgroup label="1. Pimpinan & Pembina Utama UKM (BPH)">
                    <option value="pembina">Dosen Pembina Utama UKM</option>
                    <option value="ketua_umum">Ketua Umum UKM</option>
                    <option value="wakil_ketua">Wakil Ketua Umum UKM</option>
                    <option value="sekretaris">Sekretaris Umum</option>
                    <option value="bendahara">Bendahara Umum</option>
                </optgroup>
                <optgroup label="2. Dosen Pembimbing per Divisi">
                    <option value="pembina_pemrograman">Dosen Pembimbing Divisi Pemrograman</option>
                    <option value="pembina_multimedia">Dosen Pembimbing Divisi Multimedia</option>
                    <option value="pembina_iot">Dosen Pembimbing Divisi IoT</option>
                    <option value="pembina_cyber">Dosen Pembimbing Divisi Cyber Security</option>
                </optgroup>
                <optgroup label="3. Ketua / Koordinator Divisi (Mahasiswa)">
                    <option value="ketua_pemrograman">Koordinator Divisi Pemrograman</option>
                    <option value="ketua_multimedia">Koordinator Divisi Multimedia</option>
                    <option value="ketua_iot">Koordinator Divisi IoT</option>
                    <option value="ketua_cyber">Koordinator Divisi Cyber Security</option>
                </optgroup>
                <optgroup label="4. Pengurus / Staf Divisi">
                    <option value="staf_pemrograman">Staf Ahli Divisi Pemrograman</option>
                    <option value="staf_multimedia">Staf Ahli Divisi Multimedia</option>
                    <option value="staf_iot">Staf Ahli Divisi IoT</option>
                    <option value="staf_cyber">Staf Ahli Divisi Cyber Security</option>
                </optgroup>
            </select>
        </div>

        <form action="{{ route('admin.officers.update', $officer) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="name">Nama Lengkap & Gelar *</label>
                    <input type="text" id="name" name="name" class="form-control" required value="{{ old('name', $officer->name) }}">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="nim">NIM Mahasiswa / NIP Dosen *</label>
                    <input type="text" id="nim" name="nim" class="form-control" required value="{{ old('nim', $officer->nim) }}">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="period">Periode Jabatan *</label>
                    <input type="text" id="period" name="period" class="form-control" required value="{{ old('period', $officer->period) }}">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="department_level">Tingkat / Kategori Organisasi *</label>
                    <select id="department_level" name="department_level" class="form-control" required>
                        <option value="bph" {{ old('department_level', $officer->department_level) == 'bph' ? 'selected' : '' }}>BPH / Pimpinan & Pembina Utama UKM</option>
                        <option value="pemrograman" {{ old('department_level', $officer->department_level) == 'pemrograman' ? 'selected' : '' }}>Divisi Pemrograman</option>
                        <option value="multimedia" {{ old('department_level', $officer->department_level) == 'multimedia' ? 'selected' : '' }}>Divisi Multimedia</option>
                        <option value="iot" {{ old('department_level', $officer->department_level) == 'iot' ? 'selected' : '' }}>Divisi IoT</option>
                        <option value="cyber" {{ old('department_level', $officer->department_level) == 'cyber' ? 'selected' : '' }}>Divisi Cyber Security</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="position">Nama Jabatan Resmi *</label>
                    <input type="text" id="position" name="position" class="form-control" required value="{{ old('position', $officer->position) }}">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="sort_order">Nomor Urutan Tampil</label>
                    <input type="number" id="sort_order" name="sort_order" class="form-control" value="{{ old('sort_order', $officer->sort_order) }}">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" for="photo">Ganti Foto Profil (Opsional)</label>
                @if ($officer->photo)
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem; background: var(--slate-50); padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
                        <img src="{{ asset('storage/' . $officer->photo) }}" alt="{{ $officer->name }}" style="width: 54px; height: 54px; border-radius: var(--radius-sm); object-fit: cover;">
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 600; color: var(--slate-800);">Foto Saat Ini</div>
                            <div style="font-size: 0.75rem; color: var(--slate-500);">Unggah file baru jika ingin mengganti foto di atas.</div>
                        </div>
                    </div>
                @endif
                <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                <small style="color: var(--slate-400); font-size: 0.75rem; display: block; margin-top: 0.35rem;">Format JPG, PNG, WEBP. Maks 2MB.</small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid var(--slate-100);">
                <a href="{{ route('admin.officers.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function applyRolePreset(val) {
        if (!val) return;
        const level = document.getElementById('department_level');
        const pos = document.getElementById('position');
        const sort = document.getElementById('sort_order');

        switch(val) {
            case 'pembina':
                level.value = 'bph';
                pos.value = 'Dosen Pembina Utama UKM';
                sort.value = 1;
                break;
            case 'ketua_umum':
                level.value = 'bph';
                pos.value = 'Ketua Umum UKM';
                sort.value = 2;
                break;
            case 'wakil_ketua':
                level.value = 'bph';
                pos.value = 'Wakil Ketua Umum UKM';
                sort.value = 3;
                break;
            case 'sekretaris':
                level.value = 'bph';
                pos.value = 'Sekretaris Umum';
                sort.value = 4;
                break;
            case 'bendahara':
                level.value = 'bph';
                pos.value = 'Bendahara Umum';
                sort.value = 5;
                break;
            case 'pembina_pemrograman':
                level.value = 'pemrograman';
                pos.value = 'Dosen Pembimbing Divisi Pemrograman';
                sort.value = 5;
                break;
            case 'ketua_pemrograman':
                level.value = 'pemrograman';
                pos.value = 'Koordinator Divisi Pemrograman';
                sort.value = 6;
                break;
            case 'pembina_multimedia':
                level.value = 'multimedia';
                pos.value = 'Dosen Pembimbing Divisi Multimedia';
                sort.value = 7;
                break;
            case 'ketua_multimedia':
                level.value = 'multimedia';
                pos.value = 'Koordinator Divisi Multimedia';
                sort.value = 8;
                break;
            case 'pembina_iot':
                level.value = 'iot';
                pos.value = 'Dosen Pembimbing Divisi IoT';
                sort.value = 9;
                break;
            case 'ketua_iot':
                level.value = 'iot';
                pos.value = 'Koordinator Divisi IoT';
                sort.value = 10;
                break;
            case 'pembina_cyber':
                level.value = 'cyber';
                pos.value = 'Dosen Pembimbing Divisi Cyber Security';
                sort.value = 11;
                break;
            case 'ketua_cyber':
                level.value = 'cyber';
                pos.value = 'Koordinator Divisi Cyber Security';
                sort.value = 12;
                break;
            case 'staf_pemrograman':
                level.value = 'pemrograman';
                pos.value = 'Staf Divisi Pemrograman';
                sort.value = 15;
                break;
            case 'staf_multimedia':
                level.value = 'multimedia';
                pos.value = 'Staf Divisi Multimedia';
                sort.value = 16;
                break;
            case 'staf_iot':
                level.value = 'iot';
                pos.value = 'Staf Divisi IoT';
                sort.value = 17;
                break;
            case 'staf_cyber':
                level.value = 'cyber';
                pos.value = 'Staf Divisi Cyber Security';
                sort.value = 18;
                break;
        }
    }
</script>
@endsection
