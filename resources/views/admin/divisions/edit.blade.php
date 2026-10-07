@extends('admin.layouts.app')

@section('title', 'Edit Profil & Biodata ' . $division->name . ' - UKM CMS')
@section('page_title', 'Edit Profil Divisi: ' . $division->name)

@section('content')
<div style="max-width: 950px; margin: 0 auto;">
    <!-- Top Action Navigation -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 46px; height: 46px; border-radius: 14px; background: {{ $division->color_accent }}18; color: {{ $division->color_accent }}; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--clay-pill);">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <h1 style="font-size: 1.35rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.15rem 0;">
                    Pengaturan Profil: {{ $division->name }}
                </h1>
                <p style="color: var(--slate-500); font-size: 0.825rem; margin: 0;">
                    Perbarui profil publik, susunan pembina & ketua divisi, serta silabus riset pembelajaran.
                </p>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <a href="{{ route('divisions.show', $division->slug) }}" target="_blank" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn); display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="fas fa-arrow-up-right-from-square" style="color: #2563eb;"></i>
                <span>Lihat Web Publik</span>
            </a>
            <a href="{{ route('admin.divisions.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">
                &larr; Kembali
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-error" style="box-shadow: var(--clay-pill); border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.75rem;">
            <i class="fas fa-circle-exclamation" style="font-size: 1.15rem; color: #dc2626; margin-top: 0.15rem;"></i>
            <div>
                <strong style="display: block; margin-bottom: 0.25rem;">Terdapat kesalahan pada input:</strong>
                <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.85rem;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="admin-clay-card" style="padding: 2.25rem; border-top: 5px solid {{ $division->color_accent }};">
        <form action="{{ route('admin.divisions.update', $division->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Section 1: Informasi Umum & Visi Misi -->
            <div style="margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1.5px solid rgba(226, 232, 240, 0.8);">
                    <div style="width: 32px; height: 32px; border-radius: 9px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; box-shadow: var(--clay-pill);">
                        <i class="fas fa-info"></i>
                    </div>
                    <h2 style="font-size: 1.1rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                        1. Informasi Umum & Fokus Divisi
                    </h2>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label" for="tagline" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                        Slogan / Tagline Resmi Divisi *
                    </label>
                    <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $division->tagline) }}" class="admin-filter-input" style="width: 100%; box-sizing: border-box;" placeholder="Contoh: Menghubungkan dunia nyata ke internet cerdas" required>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label" for="description" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                        Deskripsi Lengkap Divisi *
                    </label>
                    <textarea id="description" name="description" class="admin-filter-input" style="width: 100%; min-height: 100px; box-sizing: border-box; line-height: 1.5;" required placeholder="Jelaskan peran divisi, teknologi yang dipelajari, dan kegiatan utama...">{{ old('description', $division->description) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="vision" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                            Visi Divisi *
                        </label>
                        <textarea id="vision" name="vision" class="admin-filter-input" style="width: 100%; min-height: 90px; box-sizing: border-box; line-height: 1.5;" required placeholder="Visi jangka panjang divisi...">{{ old('vision', $division->vision) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mission" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                            Misi Divisi (Poin-Poin) *
                        </label>
                        <textarea id="mission" name="mission" class="admin-filter-input" style="width: 100%; min-height: 90px; box-sizing: border-box; line-height: 1.5;" required placeholder="Misi implementasi riset divisi...">{{ old('mission', $division->mission) }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="focus_topics_raw" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                        Fokus Pembelajaran / Silabus Riset (1 Baris = 1 Topik) *
                    </label>
                    @php
                        $topicsText = implode("\n", $division->focus_topics_list);
                    @endphp
                    <textarea id="focus_topics_raw" name="focus_topics_raw" class="admin-filter-input" style="width: 100%; min-height: 110px; font-family: var(--font-mono); font-size: 0.85rem; box-sizing: border-box;" required placeholder="Contoh:&#10;Microcontroller & Sensor&#10;MQTT & IoT Protocols&#10;Cloud IoT Dashboard">{{ old('focus_topics_raw', $topicsText) }}</textarea>
                    <small style="color: var(--slate-400); font-size: 0.775rem; display: block; margin-top: 0.35rem;">
                        <i class="fas fa-circle-info"></i> Setiap baris teks otomatis dikonversi menjadi kartu kurikulum keahlian di halaman profil publik divisi.
                    </small>
                </div>
            </div>

            <!-- Section 2: Biodata Dosen Pembina -->
            <div style="margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1.5px solid rgba(226, 232, 240, 0.8);">
                    <div style="width: 32px; height: 32px; border-radius: 9px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; box-shadow: var(--clay-pill);">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h2 style="font-size: 1.1rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                        2. Biodata Dosen Pembina
                    </h2>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="adviser_name" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                            Nama Lengkap & Gelar Pembina *
                        </label>
                        <input type="text" id="adviser_name" name="adviser_name" value="{{ old('adviser_name', $division->adviser_name) }}" class="admin-filter-input" style="width: 100%; box-sizing: border-box;" placeholder="Contoh: Dr. Budi Santoso, M.Kom" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="adviser_title" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                            Jabatan / Bidang Keahlian Dosen *
                        </label>
                        <input type="text" id="adviser_title" name="adviser_title" value="{{ old('adviser_title', $division->adviser_title) }}" class="admin-filter-input" style="width: 100%; box-sizing: border-box;" placeholder="Contoh: Dosen Spesialisasi Sistem Tertanam" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="adviser_photo" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                        Foto Formal Dosen Pembina (Opsional)
                    </label>
                    <div style="display: flex; align-items: center; gap: 1.25rem;">
                        <input type="file" id="adviser_photo" name="adviser_photo" accept="image/*" class="admin-filter-input" style="flex: 1;" onchange="previewImage(this, 'adviser-prev')">
                        <div style="flex-shrink: 0;">
                            @if ($division->adviser_photo)
                                <img id="adviser-prev" src="{{ asset($division->adviser_photo) }}" alt="Foto Pembina" style="width: 54px; height: 54px; border-radius: 50%; object-fit: cover; box-shadow: var(--clay-pill); border: 2px solid #ffffff;">
                            @else
                                <img id="adviser-prev" src="#" alt="Preview" style="display: none; width: 54px; height: 54px; border-radius: 50%; object-fit: cover; box-shadow: var(--clay-pill); border: 2px solid #ffffff;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Biodata Ketua Divisi (Mahasiswa) -->
            <div style="margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1.5px solid rgba(226, 232, 240, 0.8);">
                    <div style="width: 32px; height: 32px; border-radius: 9px; background: #ede9fe; color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; box-shadow: var(--clay-pill);">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h2 style="font-size: 1.1rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                        3. Biodata Ketua Divisi Mahasiswa
                    </h2>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="leader_name" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                            Nama Lengkap Ketua Mahasiswa *
                        </label>
                        <input type="text" id="leader_name" name="leader_name" value="{{ old('leader_name', $division->leader_name) }}" class="admin-filter-input" style="width: 100%; box-sizing: border-box;" placeholder="Nama ketua divisi" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="leader_nim" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                            Nomor Induk Mahasiswa (NIM) *
                        </label>
                        <input type="text" id="leader_nim" name="leader_nim" value="{{ old('leader_nim', $division->leader_nim) }}" class="admin-filter-input" style="width: 100%; box-sizing: border-box;" placeholder="Contoh: 230103099" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label" for="leader_bio" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                        Kata Sambutan / Bio Singkat Ketua Divisi *
                    </label>
                    <textarea id="leader_bio" name="leader_bio" class="admin-filter-input" style="width: 100%; min-height: 80px; box-sizing: border-box; line-height: 1.5;" required placeholder="Pesan singkat ketua divisi untuk pendaftar dan anggota baru...">{{ old('leader_bio', $division->leader_bio) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="leader_photo" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                        Foto Profil Ketua Divisi (Opsional)
                    </label>
                    <div style="display: flex; align-items: center; gap: 1.25rem;">
                        <input type="file" id="leader_photo" name="leader_photo" accept="image/*" class="admin-filter-input" style="flex: 1;" onchange="previewImage(this, 'leader-prev')">
                        <div style="flex-shrink: 0;">
                            @if ($division->leader_photo)
                                <img id="leader-prev" src="{{ asset($division->leader_photo) }}" alt="Foto Ketua" style="width: 54px; height: 54px; border-radius: 50%; object-fit: cover; box-shadow: var(--clay-pill); border: 2px solid #ffffff;">
                            @else
                                <img id="leader-prev" src="#" alt="Preview" style="display: none; width: 54px; height: 54px; border-radius: 50%; object-fit: cover; box-shadow: var(--clay-pill); border: 2px solid #ffffff;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Media Sosial & Portofolio -->
            @php $socials = $division->social_links_list; @endphp
            <div style="margin-bottom: 2.25rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1.5px solid rgba(226, 232, 240, 0.8);">
                    <div style="width: 32px; height: 32px; border-radius: 9px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; box-shadow: var(--clay-pill);">
                        <i class="fas fa-share-nodes"></i>
                    </div>
                    <h2 style="font-size: 1.1rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                        4. Media Sosial & Tautan Eksternal Divisi
                    </h2>
                </div>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="ig_link" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                            <i class="fab fa-instagram" style="color: #e1306c;"></i> Instagram URL
                        </label>
                        <input type="text" id="ig_link" name="ig_link" value="{{ old('ig_link', $socials['instagram'] ?? '') }}" class="admin-filter-input" style="width: 100%; box-sizing: border-box;" placeholder="https://instagram.com/...">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="github_link" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                            <i class="fab fa-github" style="color: #1e293b;"></i> GitHub / Repository
                        </label>
                        <input type="text" id="github_link" name="github_link" value="{{ old('github_link', $socials['github'] ?? '') }}" class="admin-filter-input" style="width: 100%; box-sizing: border-box;" placeholder="https://github.com/...">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="linkedin_link" style="font-weight: 700; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 0.4rem; display: block;">
                            <i class="fab fa-linkedin" style="color: #0a66c2;"></i> LinkedIn URL
                        </label>
                        <input type="text" id="linkedin_link" name="linkedin_link" value="{{ old('linkedin_link', $socials['linkedin'] ?? '') }}" class="admin-filter-input" style="width: 100%; box-sizing: border-box;" placeholder="https://linkedin.com/in/...">
                    </div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 1rem; padding-top: 1.5rem; border-top: 1.5px solid rgba(226, 232, 240, 0.8);">
                <a href="{{ route('admin.divisions.index') }}" class="btn btn-outline" style="background: #ffffff; box-shadow: var(--clay-btn); border-radius: 9999px; padding: 0.7rem 1.5rem; font-weight: 700;">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary" style="box-shadow: var(--clay-btn); border-radius: 9999px; padding: 0.7rem 2rem; font-weight: 800; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check"></i>
                    <span>Simpan Perubahan Biodata</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input, targetId) {
        const preview = document.getElementById(targetId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
