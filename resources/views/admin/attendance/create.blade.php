@extends('admin.layouts.app')

@section('title', 'Buka Sesi Presensi Formal Divisi - UKM CMS')

@section('content')
<div style="max-width: 780px; margin: 0 auto; padding-bottom: 3rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <div style="display: flex; gap: 0.5rem; align-items: center; font-size: 0.85rem; color: var(--slate-500); margin-bottom: 0.25rem;">
                <a href="{{ route('admin.dashboard') }}" style="color: var(--slate-600); text-decoration: none;">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.attendance.index') }}" style="color: var(--slate-600); text-decoration: none;">Presensi</a>
                <span>/</span>
                <span style="color: var(--slate-800); font-weight: 600;">Buka Sesi Baru</span>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.025em;">
                Buka Sesi Presensi & Silabus Kegiatan
            </h1>
            <p style="color: var(--slate-500); font-size: 0.875rem;">
                Setiap pertemuan resmi wajib mencatat waktu, pemateri, dan silabus pokok bahasan yang dipelajari.
            </p>
        </div>
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali</a>
    </div>

    @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #991b1b;">
            <div style="font-weight: 700; margin-bottom: 0.25rem;">Terdapat kesalahan pada isian form:</div>
            <ul style="margin-left: 1.25rem; font-size: 0.85rem;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="glass-card" style="padding: 2.25rem; border-radius: 18px;">
        <form action="{{ route('admin.attendance.store') }}" method="POST">
            @csrf

            <!-- Section 1: Informasi Dasar & Divisi -->
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.75rem;">
                <span style="font-weight: 700; color: var(--slate-800); font-size: 1rem;">1. Agenda & Sasaran Divisi</span>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--slate-700);">Nama Agenda / Judul Pertemuan *</label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="Contoh: Pertemuan Rutin #4: Arsitektur RESTful API & Clean Code" class="form-control" style="font-size: 0.95rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--slate-700);">Divisi Pelaksana *</label>
                    <select name="division_id" class="form-control" style="font-size: 0.9rem;">
                        @if ($user->isSuperAdmin())
                            <option value="">Seluruh Anggota UKM (Agenda Pleno / Bersama)</option>
                        @endif
                        @foreach ($divisions as $div)
                            @if ($user->isSuperAdmin() || (int)$div->id === (int)$user->division_id)
                                <option value="{{ $div->id }}" {{ old('division_id') == $div->id ? 'selected' : '' }}>
                                    {{ $div->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--slate-700);">Tipe Sesi Pertemuan *</label>
                    <select name="session_type" class="form-control" style="font-size: 0.9rem;" required>
                        <option value="riset_rutin" {{ old('session_type') === 'riset_rutin' ? 'selected' : '' }}>Riset & Eksplorasi Rutin</option>
                        <option value="workshop_teknis" {{ old('session_type') === 'workshop_teknis' ? 'selected' : '' }}>Workshop & Pelatihan Teknis</option>
                        <option value="mentoring_proyek" {{ old('session_type') === 'mentoring_proyek' ? 'selected' : '' }}>Mentoring & Review Proyek</option>
                        <option value="evaluasi_bulanan" {{ old('session_type') === 'evaluasi_bulanan' ? 'selected' : '' }}>Evaluasi Bulanan & Raker</option>
                        <option value="sidang_pleno" {{ old('session_type') === 'sidang_pleno' ? 'selected' : '' }}>Sidang Pleno Bersama</option>
                    </select>
                </div>
            </div>

            <!-- Section 2: Waktu & Lokasi -->
            <div style="display: flex; align-items: center; gap: 0.5rem; margin: 1.75rem 0 1.25rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.75rem;">
                <span style="font-weight: 700; color: var(--slate-800); font-size: 1rem;">2. Waktu, Hari & Lokasi Kegiatan</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--slate-700);">Tanggal Pelaksanaan *</label>
                    <input type="date" id="session_date" name="session_date" required value="{{ old('session_date', date('Y-m-d')) }}" class="form-control">
                </div>
                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--slate-700);">Hari (Terdeteksi Otomatis) *</label>
                    <input type="text" id="day_name" name="day_name" required value="{{ old('day_name', 'Senin') }}" class="form-control" style="background: #f8fafc; font-weight: 600; color: var(--slate-800);" readonly>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1.5fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--slate-700);">Jam Mulai *</label>
                    <input type="time" name="time_start" required value="{{ old('time_start', '14:00') }}" class="form-control">
                </div>
                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--slate-700);">Jam Selesai</label>
                    <input type="time" name="time_end" value="{{ old('time_end', '16:30') }}" class="form-control">
                </div>
                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--slate-700);">Ruang / Media *</label>
                    <input type="text" name="location" required value="{{ old('location') }}" placeholder="Lab Komputer 3 / Google Meet" class="form-control">
                </div>
            </div>

            <!-- Section 3: Silabus Materi & Capaian Pembelajaran -->
            <div style="display: flex; align-items: center; gap: 0.5rem; margin: 1.75rem 0 1.25rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.75rem;">
                <span style="font-weight: 700; color: var(--slate-800); font-size: 1rem;">3. Silabus Pokok Bahasan & Pemateri</span>
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--slate-700);">Pokok Bahasan / Silabus Materi yang Dipelajari *</label>
                    <input type="text" name="topic_material" required value="{{ old('topic_material') }}" placeholder="Contoh: Laravel Sanctum Authentication & API Resources" class="form-control" style="font-size: 0.95rem;">
                </div>
                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--slate-700);">Nama Pemateri / Instruktur / PIC *</label>
                    <input type="text" name="instructor_name" required value="{{ old('instructor_name', $user->name) }}" placeholder="Contoh: Muhammad Rayhan Fajar" class="form-control">
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--slate-700);">Capaian Pembelajaran / Rangkuman Materi</label>
                <textarea name="learning_outcomes" rows="3" placeholder="Rangkuman apa saja yang dipelajari anggota serta target capaian kompetensi..." class="form-control">{{ old('learning_outcomes') }}</textarea>
            </div>

            <div style="margin-bottom: 2rem;">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--slate-700);">Catatan Tambahan / Perlengkapan</label>
                <textarea name="notes" rows="2" placeholder="Catatan khusus, software/library yang wajib diinstall sebelumnya..." class="form-control">{{ old('notes') }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--slate-100); padding-top: 1.5rem;">
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.75rem; font-weight: 700; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                    Buka Sesi & Isi Presensi &rarr;
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const daysIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const dateInput = document.getElementById('session_date');
    const dayInput = document.getElementById('day_name');

    function updateDayName() {
        if (!dateInput.value) return;
        const d = new Date(dateInput.value + 'T00:00:00');
        if (!isNaN(d)) {
            dayInput.value = daysIndo[d.getDay()];
        }
    }

    dateInput.addEventListener('change', updateDayName);
    // Initialize day name immediately
    updateDayName();
</script>
@endsection
