@extends('admin.layouts.app')

@section('title', 'Buat Sesi Absensi Baru')

@section('content')
<div style="max-width: 680px; margin: 0 auto; padding-bottom: 3rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.025em; margin-bottom: 0.25rem;">
                Buat Sesi Absensi Kegiatan
            </h1>
            <p style="color: var(--slate-500); font-size: 0.875rem;">
                Sistem akan otomatis mengompilasi daftar hadir untuk seluruh anggota aktif yang relevan.
            </p>
        </div>
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline btn-sm">&larr; Kembali</a>
    </div>

    <div style="background: #ffffff; border: 1px solid var(--slate-200); border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow-card);">
        <form action="{{ route('admin.attendance.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.25rem;">
                <label class="form-label">Nama Agenda / Kegiatan *</label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="Contoh: Rapat Pleno Bulanan, Workshop Pemrograman Web" class="form-control">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label class="form-label">Target Anggota / Divisi *</label>
                <select name="division_id" class="form-control">
                    @if ($user->isSuperAdmin())
                        <option value="">Seluruh Anggota UKM (Agenda Pleno / Umum)</option>
                    @endif
                    @foreach ($divisions as $div)
                        @if ($user->isSuperAdmin() || (int)$div->id === (int)$user->division_id)
                            <option value="{{ $div->id }}" {{ old('division_id') == $div->id ? 'selected' : '' }}>
                                Khusus {{ $div->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
                <small style="color: var(--slate-500); font-size: 0.775rem; display: block; margin-top: 0.35rem;">
                    Daftar hadir akan diisi otomatis berdasarkan anggota yang berstatus Aktif di divisi terpilih.
                </small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label class="form-label">Tanggal Pelaksanaan *</label>
                    <input type="date" name="session_date" required value="{{ old('session_date', date('Y-m-d')) }}" class="form-control">
                </div>
                <div>
                    <label class="form-label">Lokasi / Ruang *</label>
                    <input type="text" name="location" required value="{{ old('location') }}" placeholder="Contoh: Lab Komputer 3 / Auditorium" class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label class="form-label">Waktu Mulai *</label>
                    <input type="time" name="time_start" required value="{{ old('time_start', '14:00') }}" class="form-control">
                </div>
                <div>
                    <label class="form-label">Waktu Selesai (Opsional)</label>
                    <input type="time" name="time_end" value="{{ old('time_end', '16:00') }}" class="form-control">
                </div>
            </div>

            <div style="margin-bottom: 1.75rem;">
                <label class="form-label">Catatan Tambahan / Agenda Rapat</label>
                <textarea name="notes" rows="3" placeholder="Poin pembahasan atau perlengkapan yang perlu dibawa..." class="form-control">{{ old('notes') }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary" style="box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                    Simpan & Buat Lembar Presensi &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
