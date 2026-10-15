@extends('admin.layouts.app')

@section('title', 'Tambah Divisi Baru - UKM CMS')
@section('page_title', 'Bentuk Divisi Spesialisasi Baru')

@section('content')
<div style="max-width: 1100px; margin: 0 auto 3rem;">
    <!-- Breadcrumb -->
    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; font-size: 0.85rem; color: var(--slate-500);">
        <a href="{{ route('admin.divisions.index') }}" style="color: #2563eb; text-decoration: none; font-weight: 600;">
            <i class="fas fa-layer-group"></i> Kelola Divisi
        </a>
        <span>/</span>
        <span style="color: var(--slate-700); font-weight: 700;">Tambah Divisi Baru</span>
    </div>

    <!-- Page Header Banner -->
    <div class="admin-welcome-banner" style="margin-bottom: 2rem; padding: 1.5rem 2rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 48px; height: 48px; border-radius: 14px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; box-shadow: var(--clay-pill); flex-shrink: 0;">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h1 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.2rem 0;">
                        Bentuk Divisi Spesialisasi Baru
                    </h1>
                    <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">
                        Tentukan identitas divisi, warna aksen branding, ikon representasi, serta kuota pendaftar anggota baru.
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.divisions.index') }}" class="btn btn-outline btn-sm" style="background: #ffffff; box-shadow: var(--clay-btn);">
                <i class="fas fa-arrow-left" style="margin-right: 0.35rem;"></i> Kembali
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 14px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; color: #991b1b; box-shadow: var(--clay-card-sm);">
            <div style="font-weight: 800; font-size: 0.95rem; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-triangle-exclamation"></i> Mohon lengkapi formulir dengan benar:
            </div>
            <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.85rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.divisions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="display: grid; grid-template-columns: 1.3fr 0.9fr; gap: 1.75rem; align-items: flex-start;">
            <!-- Left Column: Form Inputs -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <!-- 1. Identitas Utama & Branding -->
                <div class="admin-clay-card" style="padding: 1.75rem;">
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.75rem;">
                        <span style="display: inline-flex; width: 30px; height: 30px; border-radius: 8px; background: #e0f2fe; color: #0284c7; align-items: center; justify-content: center; font-size: 0.9rem;">
                            <i class="fas fa-fingerprint"></i>
                        </span>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--slate-900); margin: 0;">Identitas & Tema Branding</h3>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label class="form-label" for="name">Nama Divisi Baru *</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Artificial Intelligence, Data Science, Game Dev" required oninput="updateLivePreview()">
                        <small style="color: var(--slate-400); font-size: 0.775rem;">Slug URL akan otomatis dibuatkan dari nama ini (misal: /divisi/artificial-intelligence).</small>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label class="form-label" for="tagline">Tagline Singkat *</label>
                        <input type="text" id="tagline" name="tagline" class="form-control" value="{{ old('tagline') }}" placeholder="Contoh: Riset Kecerdasan Buatan & Pembelajaran Mesin Terapan" oninput="updateLivePreview()">
                    </div>

                    <!-- Color Accent & Icon Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label" for="color_accent">Warna Aksen Tema *</label>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <input type="color" id="colorPicker" value="{{ old('color_accent', '#0284c7') }}" style="width: 44px; height: 44px; border-radius: 12px; border: 2px solid #e2e8f0; cursor: pointer; padding: 2px; background: #fff;" oninput="syncColorInput(this.value)">
                                <input type="text" id="color_accent" name="color_accent" class="form-control font-mono" value="{{ old('color_accent', '#0284c7') }}" placeholder="#0284c7" style="font-weight: 700; text-transform: uppercase;" oninput="syncColorPicker(this.value)">
                            </div>
                            <!-- Preset Palette Chips -->
                            <div style="display: flex; gap: 6px; margin-top: 0.5rem; flex-wrap: wrap;">
                                @php
                                    $presets = ['#0284c7', '#8b5cf6', '#10b981', '#f43f5e', '#ea580c', '#06b6d4', '#6366f1', '#eab308'];
                                @endphp
                                @foreach($presets as $p)
                                    <span onclick="setColorPreset('{{ $p }}')" style="width: 22px; height: 22px; border-radius: 6px; background: {{ $p }}; cursor: pointer; border: 1.5px solid #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.2);" title="{{ $p }}"></span>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="icon_class">Ikon FontAwesome *</label>
                            <div style="position: relative;">
                                <input type="text" id="icon_class" name="icon_class" class="form-control" value="{{ old('icon_class', 'fa-robot') }}" placeholder="fa-robot" oninput="updateLivePreview()">
                            </div>
                            <small style="color: var(--slate-400); font-size: 0.775rem;">Contoh: fa-robot, fa-brain, fa-gamepad, fa-code, fa-database</small>
                        </div>
                    </div>

                    <!-- Quick Icon Picker Chips -->
                    <div style="background: #f8fafc; border-radius: 12px; padding: 0.85rem; border: 1px solid #e2e8f0; margin-bottom: 1.25rem;">
                        <span style="font-size: 0.725rem; font-weight: 800; color: var(--slate-500); text-transform: uppercase; display: block; margin-bottom: 0.45rem;">
                            Pilihan Ikon Cepat:
                        </span>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            @php
                                $iconList = [
                                    ['fa-robot', 'AI & Otomasi'],
                                    ['fa-brain', 'Neural & Cerdas'],
                                    ['fa-database', 'Data Science'],
                                    ['fa-gamepad', 'Game Dev'],
                                    ['fa-laptop-code', 'Software'],
                                    ['fa-palette', 'Multimedia'],
                                    ['fa-microchip', 'Hardware IoT'],
                                    ['fa-shield-halved', 'Keamanan'],
                                    ['fa-network-wired', 'Jaringan'],
                                    ['fa-vr-cardboard', 'AR / VR'],
                                    ['fa-satellite-dish', 'Telemetri'],
                                    ['fa-cloud', 'Cloud & DevOps'],
                                ];
                            @endphp
                            @foreach($iconList as $ic)
                                <button type="button" class="btn btn-outline btn-sm" onclick="setIconPreset('{{ $ic[0] }}')" style="font-size: 0.75rem; padding: 0.35rem 0.65rem; border-radius: 8px; background: #ffffff;">
                                    <i class="fas {{ $ic[0] }}" style="margin-right: 0.3rem; color: #2563eb;"></i> {{ $ic[1] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.25rem;">
                        <label class="form-label" for="description">Deskripsi Lengkap Divisi</label>
                        <textarea id="description" name="description" rows="3" class="form-control" placeholder="Jelaskan fokus keilmuan, tujuan pembelajaran, dan aktivitas mahasiswa di divisi ini..." oninput="updateLivePreview()">{{ old('description') }}</textarea>
                    </div>

                    <!-- Topik Fokus Riset -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="focus_topics_raw">Topik Fokus Riset / Capaian Belajar (1 Topik per Baris)</label>
                        <textarea id="focus_topics_raw" name="focus_topics_raw" rows="3" class="form-control font-mono" placeholder="Large Language Models (LLM)&#10;Computer Vision & OCR&#10;Autonomous Robotics Framework">{{ old('focus_topics_raw') }}</textarea>
                        <small style="color: var(--slate-400); font-size: 0.775rem;">Ketikkan 1 topik materi per baris. Otomatis menjadi silabus divisi.</small>
                    </div>
                </div>

                <!-- 2. Kuota & Kontrol Rekrutmen -->
                <div class="admin-clay-card" style="padding: 1.75rem;">
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.75rem;">
                        <span style="display: inline-flex; width: 30px; height: 30px; border-radius: 8px; background: #dcfce7; color: #16a34a; align-items: center; justify-content: center; font-size: 0.9rem;">
                            <i class="fas fa-users-gear"></i>
                        </span>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--slate-900); margin: 0;">Penerimaan Anggota & Kuota</h3>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="recruitment_quota">Kuota Maksimal Calon Pendaftar *</label>
                            <input type="number" id="recruitment_quota" name="recruitment_quota" class="form-control" value="{{ old('recruitment_quota', 25) }}" min="1" max="500" required>
                            <small style="color: var(--slate-400); font-size: 0.775rem;">Jika kuota terpenuhi, sistem otomatis mengunci kartu divisi ini.</small>
                        </div>

                        <div class="form-group" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: center;">
                            <label class="form-label" style="margin-bottom: 0.65rem;">Status Buka Pendaftaran</label>
                            <label style="display: flex; align-items: center; gap: 0.65rem; cursor: pointer; font-size: 0.9rem; font-weight: 700; color: var(--slate-700);">
                                <input type="checkbox" name="is_recruitment_open" value="1" {{ old('is_recruitment_open', '1') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #2563eb;">
                                Buka Rekrutmen untuk Divisi Ini
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 3. Biodata Pembina & Ketua Divisi -->
                <div class="admin-clay-card" style="padding: 1.75rem;">
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.75rem;">
                        <span style="display: inline-flex; width: 30px; height: 30px; border-radius: 8px; background: #ede9fe; color: #8b5cf6; align-items: center; justify-content: center; font-size: 0.9rem;">
                            <i class="fas fa-user-shield"></i>
                        </span>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--slate-900); margin: 0;">Pimpinan & Pembina Divisi (Opsional)</h3>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label" for="adviser_name">Nama Dosen Pembina</label>
                            <input type="text" id="adviser_name" name="adviser_name" class="form-control" value="{{ old('adviser_name') }}" placeholder="Dr. Pembina, M.Kom.">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="adviser_title">Gelar / Bidang Kepakaran</label>
                            <input type="text" id="adviser_title" name="adviser_title" class="form-control" value="{{ old('adviser_title') }}" placeholder="Dosen Komputasi Cerdas & Jaringan">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div class="form-group">
                            <label class="form-label" for="leader_name">Nama Ketua Divisi Mahasiswa</label>
                            <input type="text" id="leader_name" name="leader_name" class="form-control" value="{{ old('leader_name') }}" placeholder="Nama Mahasiswa Ketua">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="leader_nim">NIM Ketua Divisi</label>
                            <input type="text" id="leader_nim" name="leader_nim" class="form-control font-mono" value="{{ old('leader_nim') }}" placeholder="Contoh: 2301010045">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="leader_bio">Sambutan / Bio Singkat Ketua</label>
                        <textarea id="leader_bio" name="leader_bio" rows="2" class="form-control" placeholder="Kata sambutan ketua untuk calon anggota baru...">{{ old('leader_bio') }}</textarea>
                    </div>
                </div>

                <!-- Submit Button Bar -->
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 0.5rem;">
                    <a href="{{ route('admin.divisions.index') }}" class="btn btn-outline" style="background: #ffffff; box-shadow: var(--clay-btn); padding: 0.8rem 1.5rem;">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem; font-weight: 800; box-shadow: 0 4px 14px rgba(37,99,235,0.35);">
                        <i class="fas fa-check" style="margin-right: 0.45rem;"></i> Simpan & Bentuk Divisi
                    </button>
                </div>
            </div>

            <!-- Right Column: Live Tactile Clay Preview Card -->
            <div style="position: sticky; top: 1.5rem;">
                <div style="font-size: 0.8rem; font-weight: 800; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.65rem;">
                    <i class="fas fa-eye" style="margin-right: 0.3rem;"></i> Pratinjau Tampilan Kartu Pendaftaran
                </div>

                <!-- Real-time Preview Card -->
                <div id="previewCard" class="admin-clay-card" style="border-top: 5px solid #0284c7; padding: 1.75rem; transition: all 0.3s ease;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div id="previewIconBox" style="width: 50px; height: 50px; border-radius: 14px; background: rgba(2, 132, 199, 0.12); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; box-shadow: var(--clay-pill); transition: all 0.3s ease;">
                            <i id="previewIcon" class="fas fa-robot"></i>
                        </div>
                        <span class="badge badge-success" style="font-size: 0.725rem; font-weight: 700;">
                            <i class="fas fa-circle-check"></i> Divisi Aktif
                        </span>
                    </div>

                    <h4 id="previewTitle" style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin: 0 0 0.35rem 0;">
                        Nama Divisi Baru
                    </h4>
                    <p id="previewTagline" style="font-size: 0.85rem; color: #0284c7; font-weight: 700; margin: 0 0 0.75rem 0;">
                        Tagline spesialisasi divisi
                    </p>
                    <p id="previewDesc" style="font-size: 0.825rem; color: var(--slate-500); line-height: 1.5; margin: 0 0 1.25rem 0;">
                        Deskripsi profil divisi akan muncul di kartu pendaftaran dan showcase beranda publik.
                    </p>

                    <!-- Preview Quota Bar -->
                    <div style="background: #f8fafc; border-radius: 10px; padding: 0.75rem; border: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 700; color: var(--slate-600); margin-bottom: 0.3rem;">
                            <span>Kuota Penerimaan:</span>
                            <span id="previewQuota" style="color: #0284c7; font-weight: 800;">25 Kursi</span>
                        </div>
                        <div style="height: 6px; border-radius: 9999px; background: #e2e8f0; overflow: hidden;">
                            <div style="width: 35%; height: 100%; background: #0284c7; border-radius: 9999px;"></div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 1rem; background: #eff6ff; border-radius: 12px; padding: 1rem; border: 1px solid #bfdbfe; font-size: 0.8rem; color: #1e40af; line-height: 1.5;">
                    <i class="fas fa-circle-info" style="margin-right: 0.35rem;"></i>
                    Warna aksen dan ikon yang Anda tetapkan akan langsung disinkronkan ke seluruh sistem, termasuk form seleksi mahasiswa, beranda publik, dan KTA digital.
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function updateLivePreview() {
        const name = document.getElementById('name').value.trim();
        const tagline = document.getElementById('tagline').value.trim();
        const desc = document.getElementById('description').value.trim();
        const color = document.getElementById('color_accent').value.trim() || '#0284c7';
        const icon = document.getElementById('icon_class').value.trim() || 'fa-laptop-code';

        document.getElementById('previewTitle').innerText = name || 'Nama Divisi Baru';
        document.getElementById('previewTagline').innerText = tagline || 'Tagline spesialisasi divisi';
        document.getElementById('previewTagline').style.color = color;
        document.getElementById('previewDesc').innerText = desc || 'Deskripsi profil divisi akan muncul di kartu pendaftaran dan showcase beranda publik.';

        // Update card styles
        const card = document.getElementById('previewCard');
        card.style.borderTopColor = color;

        const iconBox = document.getElementById('previewIconBox');
        iconBox.style.color = color;
        iconBox.style.background = color + '18';

        const iconEl = document.getElementById('previewIcon');
        iconEl.className = 'fas ' + icon;

        const quota = document.getElementById('recruitment_quota').value || 25;
        document.getElementById('previewQuota').innerText = quota + ' Kursi';
        document.getElementById('previewQuota').style.color = color;
    }

    function syncColorInput(val) {
        document.getElementById('color_accent').value = val.toUpperCase();
        updateLivePreview();
    }

    function syncColorPicker(val) {
        if (/^#[0-9A-F]{6}$/i.test(val)) {
            document.getElementById('colorPicker').value = val;
            updateLivePreview();
        }
    }

    function setColorPreset(hex) {
        document.getElementById('colorPicker').value = hex;
        document.getElementById('color_accent').value = hex.toUpperCase();
        updateLivePreview();
    }

    function setIconPreset(iconClass) {
        document.getElementById('icon_class').value = iconClass;
        updateLivePreview();
    }

    document.addEventListener('DOMContentLoaded', updateLivePreview);
</script>
@endsection
