@extends('admin.layouts.app')

@section('title', 'Edit Biodata Anggota: ' . $member->name . ' - UKM CMS')
@section('page_title', 'Edit Biodata & Status Anggota')

@section('content')
<div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

    <div>
        <a href="{{ route('admin.members.show', $member->id) }}" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 0.4rem; font-weight: 700;">
            <i class="fas fa-arrow-left"></i> Kembali ke Biodata Mahasiswa
        </a>
    </div>

    <div class="glass-panel" style="padding: 2rem 2.25rem;">
        <div style="border-bottom: 1px solid var(--slate-100); padding-bottom: 1.25rem; margin-bottom: 1.75rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.35rem;">
                Edit Biodata Mahasiswa: {{ $member->name }}
            </h3>
            <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                Perbarui data identitas keanggotaan, penugasan divisi, atau status keaktifan mahasiswa.
            </p>
        </div>

        @if ($errors->any())
            <div style="background: #fee2e2; border-left: 4px solid #ef4444; border-radius: var(--radius-md); padding: 1rem 1.25rem; color: #991b1b; font-size: 0.875rem; margin-bottom: 1.5rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.members.update', $member->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div>
                        <label class="form-label">Nomor Induk Mahasiswa (NIM) *</label>
                        <input type="text" name="nim" value="{{ old('nim', $member->nim) }}" required class="form-control">
                    </div>
                    <div>
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" value="{{ old('name', $member->name) }}" required class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div>
                        <label class="form-label">Alamat Email *</label>
                        <input type="email" name="email" value="{{ old('email', $member->email) }}" required class="form-control">
                    </div>
                    <div>
                        <label class="form-label">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $member->phone_number) }}" class="form-control" placeholder="Contoh: 081234567890">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div>
                        <label class="form-label">Divisi Resmi UKM *</label>
                        @if ($user->isSuperAdmin())
                            <select name="division_id" required class="form-control">
                                @foreach ($divisions as $d)
                                    <option value="{{ $d->id }}" {{ old('division_id', $member->division_id) == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" value="{{ $member->division?->name }}" readonly class="form-control" style="background: var(--slate-100);">
                            <input type="hidden" name="division_id" value="{{ $member->division_id }}">
                        @endif
                    </div>
                    <div>
                        <label class="form-label">Tahun Angkatan (Batch Year) *</label>
                        <input type="text" name="batch_year" value="{{ old('batch_year', $member->batch_year) }}" required class="form-control" placeholder="Contoh: 2026">
                    </div>
                </div>

                <div>
                    <label class="form-label">Status Keaktifan *</label>
                    <select name="status" required class="form-control">
                        <option value="aktif" {{ old('status', $member->status) === 'aktif' ? 'selected' : '' }}>Aktif (Mengikuti Kegiatan)</option>
                        <option value="non_aktif" {{ old('status', $member->status) === 'non_aktif' ? 'selected' : '' }}>Non-Aktif (Cuti / Berhalangan)</option>
                        <option value="alumni" {{ old('status', $member->status) === 'alumni' ? 'selected' : '' }}>Alumni UKM</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Catatan Pengurus (Opsional)</label>
                    <textarea name="notes" rows="3" class="form-control" placeholder="Catatan internal pengurus mengenai rekam jejak anggota...">{{ old('notes', $member->notes) }}</textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1rem; padding-top: 1.25rem; border-top: 1px solid var(--slate-100);">
                    <a href="{{ route('admin.members.show', $member->id) }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </div>
        </form>
    </div>

</div>
@endsection
