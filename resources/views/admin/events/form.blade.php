@extends('admin.layouts.app')

@section('title', ($isEdit ? 'Edit Agenda' : 'Tambah Agenda Baru') . ' - UKM CMS')
@section('page_title', $isEdit ? 'Edit Agenda Kegiatan' : 'Tambah Agenda Kegiatan Baru')

@section('content')
<div class="glass-panel" style="padding: 2rem; max-width: 800px;">
    <form action="{{ $isEdit ? route('admin.events.update', $event->id) : route('admin.events.store') }}" method="POST">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="form-group">
            <label class="form-label" for="title">Judul Kegiatan / Workshop *</label>
            <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" class="form-control" required placeholder="Contoh: Hands-On Bootcamp: Web Pentesting">
            @error('title') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="division_id">Divisi Penyelenggara (Opsional)</label>
            <select id="division_id" name="division_id" class="form-control">
                <option value="">-- Agenda Umum / Terbuka --</option>
                @foreach ($divisions as $d)
                    <option value="{{ $d->id }}" {{ old('division_id', $event->division_id) == $d->id ? 'selected' : '' }}>
                        {{ $d->name }}
                    </option>
                @endforeach
            </select>
            @error('division_id') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label" for="event_date">Tanggal Pelaksanaan *</label>
                <input type="date" id="event_date" name="event_date" value="{{ old('event_date', optional($event->event_date)->format('Y-m-d')) }}" class="form-control" required>
                @error('event_date') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="time_start">Waktu Mulai *</label>
                <input type="time" id="time_start" name="time_start" value="{{ old('time_start', $event->time_start ? substr($event->time_start, 0, 5) : '09:00') }}" class="form-control" required>
                @error('time_start') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="time_end">Waktu Selesai (Opsional)</label>
                <input type="time" id="time_end" name="time_end" value="{{ old('time_end', $event->time_end ? substr($event->time_end, 0, 5) : '') }}" class="form-control">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label" for="location_type">Tipe Lokasi *</label>
                <select id="location_type" name="location_type" class="form-control" required>
                    <option value="offline" {{ old('location_type', $event->location_type) === 'offline' ? 'selected' : '' }}>Offline (Tatap Muka)</option>
                    <option value="online" {{ old('location_type', $event->location_type) === 'online' ? 'selected' : '' }}>Online (Daring)</option>
                    <option value="hybrid" {{ old('location_type', $event->location_type) === 'hybrid' ? 'selected' : '' }}>Hybrid (Kombinasi)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="location_venue">Nama Tempat / Link Meeting *</label>
                <input type="text" id="location_venue" name="location_venue" value="{{ old('location_venue', $event->location_venue) }}" class="form-control" required placeholder="Contoh: Lab Komputer Lt. 3 / Zoom Cloud Meeting">
                @error('location_venue') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Deskripsi & Rundown Kegiatan *</label>
            <textarea id="description" name="description" rows="4" class="form-control" required placeholder="Jelaskan materi yang akan dibahas, target peserta, dan benefit yang didapat...">{{ old('description', $event->description) }}</textarea>
            @error('description') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label" for="registration_link">Link Form Pendaftaran (Opsional)</label>
                <input type="url" id="registration_link" name="registration_link" value="{{ old('registration_link', $event->registration_link) }}" class="form-control" placeholder="https://bit.ly/daftar-workshop">
            </div>

            <div class="form-group">
                <label class="form-label" for="max_participants">Batas Kuota Peserta (Opsional)</label>
                <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}" class="form-control" placeholder="Contoh: 50">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 2rem;">
            <label class="form-label" for="status">Status Kegiatan *</label>
            <select id="status" name="status" class="form-control" required>
                <option value="upcoming" {{ old('status', $event->status) === 'upcoming' ? 'selected' : '' }}>Akan Datang (Upcoming)</option>
                <option value="completed" {{ old('status', $event->status) === 'completed' ? 'selected' : '' }}>Telah Selesai (Completed)</option>
                <option value="cancelled" {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
            </select>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">
                {{ $isEdit ? 'Simpan Perubahan' : 'Terbitkan Agenda' }}
            </button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
