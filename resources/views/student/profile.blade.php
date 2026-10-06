@extends('student.layouts.app')

@section('title', 'Edit Profil Mahasiswa')
@section('page_title', 'Pengaturan & Edit Profil')

@section('styles')
<style>
    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 2rem;
    }
    @media (max-width: 900px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
    }
    .profile-card {
        background: #ffffff;
        border-radius: var(--radius-xl);
        padding: 2rem;
        border: 1px solid var(--slate-200);
        box-shadow: 0 1px 3px 0 rgba(0,0,0,0.05);
    }
    .avatar-preview-wrapper {
        position: relative;
        width: 140px;
        height: 140px;
        margin: 0 auto 1.5rem;
    }
    .avatar-preview-img {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--primary-100);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .avatar-upload-btn {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 38px;
        height: 38px;
        background: var(--primary-600);
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        transition: all 0.2s;
    }
    .avatar-upload-btn:hover {
        background: var(--primary-700);
        transform: scale(1.05);
    }
    .info-badge {
        background: var(--slate-50);
        border: 1px solid var(--slate-200);
        border-radius: var(--radius-md);
        padding: 0.875rem 1rem;
        margin-bottom: 0.75rem;
    }
    .info-badge-label {
        font-size: 0.725rem;
        color: var(--slate-400);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.05em;
    }
    .info-badge-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--slate-800);
        margin-top: 0.15rem;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div style="font-size: 0.85rem; color: var(--slate-500); margin-bottom: 0.25rem;">
            <a href="{{ route('student.dashboard') }}" style="color: var(--primary-600); text-decoration: none;">
                <i class="fas fa-arrow-left" style="margin-right: 0.25rem;"></i> Kembali ke Dashboard
            </a>
        </div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); margin: 0; letter-spacing: -0.02em;">
            Pengaturan & Edit Profil Mahasiswa
        </h1>
    </div>
</div>

<form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="profile-grid">
        <!-- Left: Avatar & Identity Cards -->
        <div>
            <div class="profile-card" style="text-align: center;">
                <div class="avatar-preview-wrapper">
                    <img id="avatarPreviewImg" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="avatar-preview-img">
                    <label for="avatarInput" class="avatar-upload-btn" title="Ganti Foto Profil">
                        <i class="fas fa-camera"></i>
                    </label>
                </div>
                <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/webp" style="display: none;" onchange="previewAvatar(event)">

                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.25rem;">
                    {{ $user->name }}
                </h3>
                <p style="font-size: 0.825rem; color: var(--slate-500); margin-bottom: 1.25rem;">
                    {{ $user->nim ?? 'Mahasiswa UKM' }}
                </p>

                <label for="avatarInput" class="btn btn-outline btn-sm" style="cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; margin-bottom: 1.5rem;">
                    <i class="fas fa-upload"></i> Unggah Foto Baru
                </label>
                @error('avatar') <div style="color: var(--danger); font-size: 0.8rem; margin-top: -1rem; margin-bottom: 1rem;">{{ $message }}</div> @enderror

                <div style="text-align: left; border-top: 1px solid var(--slate-100); padding-top: 1.5rem;">
                    <div class="info-badge">
                        <div class="info-badge-label">Nomor Induk Mahasiswa (NIM)</div>
                        <div class="info-badge-value">{{ $user->nim ?? '-' }}</div>
                        <small style="color: var(--slate-400); font-size: 0.7rem;">Terkunci sesuai data verifikasi kampus.</small>
                    </div>

                    <div class="info-badge">
                        <div class="info-badge-label">Alamat Email (ID Login)</div>
                        <div class="info-badge-value">{{ $user->email }}</div>
                        <small style="color: var(--slate-400); font-size: 0.7rem;">Digunakan untuk masuk ke sistem.</small>
                    </div>

                    @if ($user->recruitment)
                        <div class="info-badge">
                            <div class="info-badge-label">Kode Pendaftaran</div>
                            <div class="info-badge-value" style="color: var(--primary-600);">
                                {{ $user->recruitment->registration_code }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Edit Form Details & Password -->
        <div>
            <div class="profile-card">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.35rem;">
                    Informasi Data Diri
                </h3>
                <p style="font-size: 0.85rem; color: var(--slate-500); margin-bottom: 1.5rem;">
                    Perbarui nama lengkap, nomor WhatsApp, serta tautan repositori proyek Anda.
                </p>

                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap Mahasiswa *</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone_number">Nomor WhatsApp Aktif *</label>
                    <input type="text" id="phone_number" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $user->phone_number) }}" placeholder="Contoh: 081298765432" required>
                    <small style="color: var(--slate-400); font-size: 0.75rem;">Digunakan oleh pengurus divisi untuk koordinasi kegiatan.</small>
                    @error('phone_number') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="github_url">Tautan Profil GitHub / Portofolio (Opsional)</label>
                    <input type="url" id="github_url" name="github_url" class="form-control @error('github_url') is-invalid @enderror" value="{{ old('github_url', $user->github_url) }}" placeholder="https://github.com/username">
                    <small style="color: var(--slate-400); font-size: 0.75rem;">Tautan profil GitHub pribadi Anda.</small>
                    @error('github_url') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>

                <!-- Password change section -->
                <div style="margin: 2.25rem 0 1.5rem; padding-top: 1.75rem; border-top: 1px solid var(--slate-200);">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <span style="display: inline-flex; width: 24px; height: 24px; border-radius: 6px; background: var(--slate-700); color: #fff; align-items: center; justify-content: center; font-size: 0.75rem;"><i class="fas fa-key"></i></span>
                        <h4 style="font-size: 1rem; font-weight: 700; color: var(--slate-900); margin: 0;">Ganti Kata Sandi (Opsional)</h4>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--slate-500); margin-bottom: 1.25rem;">
                        Kosongkan bagian ini jika Anda tidak ingin mengubah kata sandi login saat ini.
                    </p>

                    <div class="form-group">
                        <label class="form-label" for="current_password">Kata Sandi Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Masukkan kata sandi saat ini">
                        @error('current_password') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="password">Kata Sandi Baru</label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter">
                            @error('password') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi baru">
                        </div>
                    </div>
                </div>

                <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="{{ route('student.dashboard') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary" style="padding-left: 2rem; padding-right: 2rem;">
                        <i class="fas fa-check" style="margin-right: 0.4rem;"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    function previewAvatar(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreviewImg').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
