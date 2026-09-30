@extends('admin.layouts.app')

@section('title', 'Edit Profil & Biodata ' . $division->name . ' - UKM CMS')
@section('page_title', 'Edit Profil Divisi: ' . $division->name)

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--slate-900);">
                Edit Biodata & Profil: {{ $division->name }}
            </h2>
            <p style="color: var(--slate-500); font-size: 0.85rem;">Perbarui biodata pembina, ketua bidang, dan materi kurikulum divisi.</p>
        </div>
        <a href="{{ route('admin.divisions.index') }}" class="btn btn-outline btn-sm">&larr; Kembali</a>
    </div>

    <div class="card">
        <form action="{{ route('admin.divisions.update', $division->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- General Info -->
            <div style="margin-bottom: 2rem;">
                <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.25rem;">
                    1. Informasi Umum Divisi
                </h3>
            </div>

            <div class="form-group">
                <label class="form-label" for="tagline">Slogan / Tagline Divisi *</label>
                <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $division->tagline) }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Deskripsi Lengkap Divisi *</label>
                <textarea id="description" name="description" class="form-control" style="min-height: 100px;" required>{{ old('description', $division->description) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="vision">Visi Divisi *</label>
                    <textarea id="vision" name="vision" class="form-control" style="min-height: 90px;" required>{{ old('vision', $division->vision) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="mission">Misi Divisi (Poin-Poin) *</label>
                    <textarea id="mission" name="mission" class="form-control" style="min-height: 90px;" required>{{ old('mission', $division->mission) }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="focus_topics_raw">Fokus Pembelajaran / Topik Riset (1 Baris = 1 Topik) *</label>
                @php
                    $topicsText = implode("\n", $division->focus_topics_list);
                @endphp
                <textarea id="focus_topics_raw" name="focus_topics_raw" class="form-control" style="min-height: 100px; font-family: var(--font-mono); font-size: 0.85rem;" required>{{ old('focus_topics_raw', $topicsText) }}</textarea>
                <small style="color: var(--slate-500); font-size: 0.775rem;">Setiap baris teks akan dikonversi menjadi kartu/badge kurikulum keahlian divisi.</small>
            </div>

            <!-- Adviser Bio -->
            <div style="margin: 2.5rem 0 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-200);">
                <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.25rem;">
                    2. Biodata Dosen Pembina
                </h3>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="adviser_name">Nama Lengkap & Gelar Pembina *</label>
                    <input type="text" id="adviser_name" name="adviser_name" value="{{ old('adviser_name', $division->adviser_name) }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="adviser_title">Jabatan / Keahlian Dosen *</label>
                    <input type="text" id="adviser_title" name="adviser_title" value="{{ old('adviser_title', $division->adviser_title) }}" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="adviser_photo">Foto Dosen Pembina (Opsional)</label>
                <input type="file" id="adviser_photo" name="adviser_photo" accept="image/*" class="form-control" data-preview="#adviser-prev">
                @if ($division->adviser_photo)
                    <div style="margin-top: 0.5rem;">
                        <img id="adviser-prev" src="{{ asset($division->adviser_photo) }}" alt="Foto Pembina" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                    </div>
                @else
                    <img id="adviser-prev" src="#" alt="" style="display: none; width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-top: 0.5rem;">
                @endif
            </div>

            <!-- Leader Bio -->
            <div style="margin: 2.5rem 0 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-200);">
                <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.25rem;">
                    3. Biodata Ketua Divisi (Mahasiswa)
                </h3>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="leader_name">Nama Lengkap Ketua *</label>
                    <input type="text" id="leader_name" name="leader_name" value="{{ old('leader_name', $division->leader_name) }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="leader_nim">NIM Ketua Divisi *</label>
                    <input type="text" id="leader_nim" name="leader_nim" value="{{ old('leader_nim', $division->leader_nim) }}" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="leader_bio">Bio / Kata Sambutan Ketua Divisi *</label>
                <textarea id="leader_bio" name="leader_bio" class="form-control" style="min-height: 80px;" required>{{ old('leader_bio', $division->leader_bio) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="leader_photo">Foto Ketua Divisi (Opsional)</label>
                <input type="file" id="leader_photo" name="leader_photo" accept="image/*" class="form-control" data-preview="#leader-prev">
                @if ($division->leader_photo)
                    <div style="margin-top: 0.5rem;">
                        <img id="leader-prev" src="{{ asset($division->leader_photo) }}" alt="Foto Ketua" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                    </div>
                @else
                    <img id="leader-prev" src="#" alt="" style="display: none; width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-top: 0.5rem;">
                @endif
            </div>

            <!-- Social Links -->
            @php $socials = $division->social_links_list; @endphp
            <div style="margin: 2.5rem 0 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-200);">
                <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.25rem;">
                    4. Media Sosial Divisi & Ketua
                </h3>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="ig_link">Instagram URL</label>
                    <input type="text" id="ig_link" name="ig_link" value="{{ old('ig_link', $socials['instagram'] ?? '') }}" class="form-control" placeholder="https://instagram.com/...">
                </div>

                <div class="form-group">
                    <label class="form-label" for="github_link">GitHub / Portfolio URL</label>
                    <input type="text" id="github_link" name="github_link" value="{{ old('github_link', $socials['github'] ?? '') }}" class="form-control" placeholder="https://github.com/...">
                </div>

                <div class="form-group">
                    <label class="form-label" for="linkedin_link">LinkedIn URL</label>
                    <input type="text" id="linkedin_link" name="linkedin_link" value="{{ old('linkedin_link', $socials['linkedin'] ?? '') }}" class="form-control" placeholder="https://linkedin.com/in/...">
                </div>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-100); display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('admin.divisions.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan Biodata &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
