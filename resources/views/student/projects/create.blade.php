@extends('student.layouts.app')

@section('title', ($isEdit ? 'Edit Karya: ' . $project->title : 'Ajukan Karya Baru') . ' - Portal Mahasiswa')
@section('page_title', $isEdit ? 'Perbarui Data Karya Mahasiswa' : 'Form Pengajuan Karya Mahasiswa')

@section('content')
<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.75rem;">

    <!-- Back Navigation -->
    <div>
        <a href="{{ route('student.projects.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #64748b; font-weight: 700; text-decoration: none; font-size: 0.875rem;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Karya
        </a>
    </div>

    <!-- Main Form Card -->
    <div style="background: #ffffff; border-radius: var(--radius-xl); padding: 2.25rem; box-shadow: var(--clay-card); border: 1.5px solid rgba(226, 232, 240, 0.9);">
        
        <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 1.25rem; margin-bottom: 1.75rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; border-radius: 14px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--clay-pill);">
                <i class="fas {{ $isEdit ? 'fa-pen-to-square' : 'fa-lightbulb' }}"></i>
            </div>
            <div>
                <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem;">
                    {{ $isEdit ? 'Edit & Perbarui Karya' : 'Form Pengajuan Karya & Proyek Inovasi' }}
                </h2>
                <p style="color: #64748b; font-size: 0.85rem; margin: 0;">
                    {{ $isEdit ? 'Karya yang diperbarui akan ditinjau kembali oleh pengurus divisi sebelum tayang.' : 'Karya Anda akan ditinjau oleh pengurus UKM sebelum ditampilkan di showcase publik.' }}
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div style="background: #fee2e2; border-left: 4px solid #ef4444; border-radius: var(--radius-md); padding: 1rem 1.25rem; color: #991b1b; font-size: 0.875rem; margin-bottom: 1.75rem;">
                <div style="font-weight: 700; margin-bottom: 0.35rem;">Terdapat kesalahan pengisian:</div>
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $isEdit ? route('student.projects.update', $project->id) : route('student.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div style="display: flex; flex-direction: column; gap: 1.35rem;">
                
                <!-- Judul Proyek -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.45rem;">
                        Judul Karya / Nama Proyek <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $project->title) }}" required placeholder="Contoh: Sistem Monitoring IoT Smart Green House" style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #cbd5e1; border-radius: var(--radius-md); font-size: 0.95rem; box-sizing: border-box; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#0284c7';" onblur="this.style.borderColor='#cbd5e1';">
                </div>

                <!-- Divisi & Inovator -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.45rem;">
                            Kategori Divisi <span style="color: #ef4444;">*</span>
                        </label>
                        @if (isset($studentDivision) && $studentDivision)
                            <div style="padding: 0.65rem 0.95rem; border: 1.5px solid #e2e8f0; border-radius: var(--radius-md); font-size: 0.9rem; background: #f8fafc; color: #0f172a; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--clay-debossed);">
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 800; color: #0c2340;">
                                    <i class="fas fa-layer-group" style="color: #0284c7;"></i>
                                    <span>{{ $studentDivision->name }}</span>
                                </div>
                                <span style="font-size: 0.725rem; color: #64748b; font-weight: 700; display: inline-flex; align-items: center; gap: 0.3rem; background: #ffffff; padding: 0.25rem 0.65rem; border-radius: 9999px; border: 1px solid #cbd5e1; box-shadow: var(--clay-pill);">
                                    <i class="fas fa-lock" style="color: #0284c7;"></i> Terkunci Resmi
                                </span>
                            </div>
                            <input type="hidden" name="division_id" value="{{ $studentDivision->id }}">
                        @else
                            <select name="division_id" required style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #cbd5e1; border-radius: var(--radius-md); font-size: 0.95rem; box-sizing: border-box; background: #ffffff; outline: none;">
                                <option value="">-- Pilih Divisi Terkait --</option>
                                @foreach ($divisions as $div)
                                    <option value="{{ $div->id }}" {{ old('division_id', $project->division_id ?? $defaultDivisionId) == $div->id ? 'selected' : '' }}>
                                        {{ $div->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.45rem;">
                            Nama Inovator / Anggota Tim <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="author_names" value="{{ old('author_names', $project->author_names ?? auth()->user()->name) }}" required placeholder="Contoh: {{ auth()->user()->name }}, Ahmad Dahlan" style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #cbd5e1; border-radius: var(--radius-md); font-size: 0.95rem; box-sizing: border-box; outline: none;">
                    </div>
                </div>

                <!-- Tech Stack -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.45rem;">
                        Teknologi yang Digunakan (Tech Stack)
                    </label>
                    <input type="text" name="tech_stack_input" value="{{ old('tech_stack_input', is_array($project->tech_stack) ? implode(', ', $project->tech_stack) : '') }}" placeholder="Pisahkan dengan koma. Contoh: Laravel, React, TailwindCSS, MySQL" style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #cbd5e1; border-radius: var(--radius-md); font-size: 0.95rem; box-sizing: border-box; outline: none;">
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.35rem;">
                        Tuliskan bahasa pemrograman, framework, hardware, atau tools desain yang digunakan.
                    </div>
                </div>

                <!-- URLs: Demo & Repo -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.45rem;">
                            URL Demo Live / Video Demo (Opsional)
                        </label>
                        <input type="url" name="demo_url" value="{{ old('demo_url', $project->demo_url) }}" placeholder="https://proyek-saya.com atau YouTube URL" style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #cbd5e1; border-radius: var(--radius-md); font-size: 0.95rem; box-sizing: border-box; outline: none;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.45rem;">
                            URL Repository / Source Code (Opsional)
                        </label>
                        <input type="url" name="repo_url" value="{{ old('repo_url', $project->repo_url) }}" placeholder="https://github.com/username/project" style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #cbd5e1; border-radius: var(--radius-md); font-size: 0.95rem; box-sizing: border-box; outline: none;">
                    </div>
                </div>

                <!-- Thumbnail Upload with Live Preview -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.45rem;">
                        Gambar / Tangkapan Layar Proyek (Thumbnail)
                    </label>
                    <div style="background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: var(--radius-lg); padding: 1.5rem; text-align: center; position: relative; transition: all 0.2s ease;" id="thumbnailDropZone">
                        <div id="thumbnailPreviewContainer" style="{{ ($isEdit && $project->thumbnail) ? '' : 'display: none;' }} margin-bottom: 1rem;">
                            <img id="thumbnailPreviewImg" src="{{ $project->thumbnail_url ?? '' }}" alt="Preview" style="max-height: 220px; max-width: 100%; border-radius: 12px; object-fit: cover; box-shadow: var(--clay-card); border: 2px solid #ffffff;">
                            <div style="font-size: 0.775rem; color: #64748b; margin-top: 0.5rem;" id="thumbnailFileName">
                                {{ ($isEdit && $project->thumbnail) ? 'Thumbnail saat ini terpasang' : '' }}
                            </div>
                        </div>

                        <div id="thumbnailUploadPrompt" style="{{ ($isEdit && $project->thumbnail) ? 'display: none;' : '' }}">
                            <div style="width: 54px; height: 54px; border-radius: 16px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 0.75rem; box-shadow: var(--clay-pill);">
                                <i class="fas fa-cloud-arrow-up"></i>
                            </div>
                            <div style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">
                                Pilih gambar thumbnail proyek Anda
                            </div>
                            <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 1rem;">
                                Format PNG, JPG, JPEG, WEBP (Maksimal 2MB). Rekomendasi rasio 16:9.
                            </div>
                        </div>

                        <label for="thumbnailInput" class="btn btn-outline btn-sm" style="background: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.25rem; border-radius: 9999px;">
                            <i class="fas fa-image"></i>
                            <span id="thumbnailBtnText">{{ ($isEdit && $project->thumbnail) ? 'Ganti File Thumbnail' : 'Cari Gambar di Perangkat' }}</span>
                        </label>
                        <input type="file" id="thumbnailInput" name="thumbnail" accept="image/*" style="display: none;" onchange="previewThumbnail(event)">
                    </div>
                    @error('thumbnail')
                        <div style="color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Deskripsi Proyek -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.45rem;">
                        Deskripsi Karya & Manfaat Inovasi <span style="color: #ef4444;">*</span>
                    </label>
                    <textarea name="description" rows="5" required placeholder="Jelaskan latar belakang masalah yang diselesaikan, fitur-fitur utama, dan implementasinya..." style="width: 100%; padding: 0.85rem 1rem; border: 1.5px solid #cbd5e1; border-radius: var(--radius-md); font-size: 0.95rem; box-sizing: border-box; font-family: inherit; line-height: 1.5; outline: none; transition: all 0.2s ease; box-shadow: var(--clay-debossed);" onfocus="this.style.borderColor='#0284c7'; this.style.boxShadow='0 0 0 3px rgba(2, 132, 199, 0.15)';" onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='var(--clay-debossed)';">{{ old('description', $project->description) }}</textarea>
                </div>

                <!-- Action Button -->
                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem; padding-top: 1.25rem; border-top: 1px solid #f1f5f9;">
                    <a href="{{ route('student.projects.index') }}" class="btn btn-outline" style="border-radius: 9999px; font-weight: 700; padding: 0.75rem 1.5rem; background: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill);">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #0284c7, #0369a1); border: none; font-weight: 800; padding: 0.75rem 2rem; border-radius: 9999px; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);">
                        <i class="fas {{ $isEdit ? 'fa-check' : 'fa-paper-plane' }}" style="margin-right: 0.4rem;"></i>
                        {{ $isEdit ? 'Simpan Perubahan' : 'Kirim untuk Ditinjau' }}
                    </button>
                </div>

            </div>
        </form>

    </div>

</div>
@endsection

@section('scripts')
<script>
    function previewThumbnail(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('thumbnailPreviewImg');
                const container = document.getElementById('thumbnailPreviewContainer');
                const prompt = document.getElementById('thumbnailUploadPrompt');
                const btnText = document.getElementById('thumbnailBtnText');
                const fileName = document.getElementById('thumbnailFileName');

                previewImg.src = e.target.result;
                container.style.display = 'block';
                prompt.style.display = 'none';
                btnText.textContent = 'Ganti Gambar Lain';
                if (fileName) {
                    fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                }
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
