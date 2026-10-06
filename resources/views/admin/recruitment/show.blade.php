@extends('admin.layouts.app')

@section('title', 'Detail Pendaftar: ' . $recruitment->full_name . ' - UKM CMS')
@section('page_title', 'Detail Calon Anggota Baru')

@section('content')
<div style="max-width: 950px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--slate-900);">
                Review Berkas Pendaftaran Calon Anggota
            </h2>
            <p style="color: var(--slate-500); font-size: 0.85rem;">Periksa alasan motivasi, berkas KTM/CV, jadwalkan wawancara, dan tentukan status seleksi.</p>
        </div>
        <a href="{{ route('admin.recruitment.index') }}" class="btn btn-outline btn-sm">&larr; Kembali ke Daftar</a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1.2fr; gap: 2rem;">
        <!-- Left: Applicant Profile & Motivation -->
        <div class="glass-panel" style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--slate-100); gap: 1.5rem;">
                <div style="display: flex; gap: 1.25rem; align-items: center;">
                    <img src="{{ $recruitment->avatar_url }}" alt="{{ $recruitment->full_name }}" style="width: 76px; height: 96px; border-radius: var(--radius-md); object-fit: cover; border: 2px solid var(--slate-200); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                    <div>
                        <h3 style="font-size: 1.45rem; font-weight: 800; color: var(--slate-900);">
                            {{ $recruitment->full_name }}
                        </h3>
                        <div style="font-family: var(--font-mono); font-size: 0.875rem; color: var(--slate-500); margin-top: 0.25rem;">
                            NIM: <strong>{{ $recruitment->nim }}</strong> &bull; Semester {{ $recruitment->semester }} {{ $recruitment->class_group ? '(Kelas ' . $recruitment->class_group . ')' : '' }}
                        </div>
                    </div>
                </div>
                <div style="text-align: right;">
                    <span class="badge badge-info" style="font-family: var(--font-mono); font-size: 0.8rem;">
                        {{ $recruitment->registration_code }}
                    </span>
                    <div style="font-size: 0.75rem; color: var(--slate-400); margin-top: 0.35rem;">
                        {{ $recruitment->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            <!-- Contacts Strip -->
            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $recruitment->phone_whatsapp);
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }
            @endphp
            <div style="display: flex; gap: 0.75rem; margin-bottom: 1.75rem; flex-wrap: wrap;">
                <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($recruitment->full_name) }},%20kami%20dari%20Pengurus%20UKM%20Ilmu%20Komputer..." target="_blank" class="btn btn-outline btn-sm" style="color: #059669; border-color: #a7f3d0; background: #ecfdf5;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>WhatsApp ({{ $recruitment->phone_whatsapp }})</span>
                </a>
                <a href="mailto:{{ $recruitment->email }}" class="btn btn-outline btn-sm">
                    Kirim Email ({{ $recruitment->email }})
                </a>
                @if ($recruitment->github_url)
                    <a href="{{ $recruitment->github_url }}" target="_blank" class="btn btn-outline btn-sm" style="color: var(--slate-800);">
                        <i class="fab fa-github" style="margin-right: 0.3rem;"></i> GitHub Profil
                    </a>
                @endif
            </div>

            <!-- Division Choices -->
            <div style="background-color: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.75rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--slate-500);">Divisi Pilihan 1:</span>
                        <div style="font-weight: 800; font-size: 1.05rem; color: {{ $recruitment->firstChoiceDivision->color_accent }}; margin-top: 0.25rem;">
                            {{ $recruitment->firstChoiceDivision->name }}
                        </div>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--slate-500);">Divisi Pilihan 2:</span>
                        <div style="font-weight: 700; font-size: 1rem; color: var(--slate-700); margin-top: 0.25rem;">
                            {{ $recruitment->secondChoiceDivision?->name ?? 'Tidak Ada / Bebas' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Motivation -->
            <div style="margin-bottom: 1.75rem;">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--slate-800); margin-bottom: 0.5rem;">
                    Alasan & Motivasi Ingin Bergabung:
                </h4>
                <div style="background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: 1.25rem; font-size: 0.95rem; line-height: 1.7; color: var(--slate-800); white-space: pre-line;">
                    {{ $recruitment->reason_to_join }}
                </div>
            </div>

            <!-- Uploaded Files & Portfolio -->
            <div style="border-top: 1px solid var(--slate-100); padding-top: 1.25rem;">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--slate-800); margin-bottom: 0.75rem;">
                    Berkas & Dokumen Pendukung:
                </h4>
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    @if ($recruitment->file_ktm)
                        <a href="{{ asset('storage/' . $recruitment->file_ktm) }}" target="_blank" class="btn btn-outline btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                            <span>Lihat Berkas KTM</span>
                        </a>
                    @else
                        <span style="font-size: 0.8rem; color: var(--slate-400);">Tidak ada file KTM dilampirkan.</span>
                    @endif

                    @if ($recruitment->file_cv)
                        <a href="{{ asset('storage/' . $recruitment->file_cv) }}" target="_blank" class="btn btn-outline btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Download Resume/CV</span>
                        </a>
                    @endif

                    @if ($recruitment->portfolio_url)
                        <a href="{{ $recruitment->portfolio_url }}" target="_blank" class="btn btn-outline btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            <span>Buka Portofolio Online</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Status Decision Box -->
        <div>
            <div class="glass-panel" style="padding: 2rem; border-top: 4px solid var(--accent-blue);">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1.25rem;">
                    Progres & Keputusan Seleksi
                </h3>

                <form action="{{ route('admin.recruitment.updateStatus', $recruitment->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label" for="status">Status Penerimaan *</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="pending" {{ $recruitment->status === 'pending' ? 'selected' : '' }}>
                                Pending (Menunggu Verifikasi)
                            </option>
                            <option value="interview" {{ $recruitment->status === 'interview' ? 'selected' : '' }}>
                                Tahap Interview / Wawancara
                            </option>
                            <option value="accepted" {{ $recruitment->status === 'accepted' ? 'selected' : '' }}>
                                Diterima Sebagai Anggota Baru
                            </option>
                            <option value="rejected" {{ $recruitment->status === 'rejected' ? 'selected' : '' }}>
                                Ditolak / Belum Lolos
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="selection_stage">Tahapan Berjalan</label>
                        <select id="selection_stage" name="selection_stage" class="form-control">
                            <option value="administrasi" {{ $recruitment->selection_stage === 'administrasi' ? 'selected' : '' }}>Tahap 1: Administrasi & Berkas</option>
                            <option value="wawancara" {{ $recruitment->selection_stage === 'wawancara' ? 'selected' : '' }}>Tahap 2: Sesi Wawancara</option>
                            <option value="diterima" {{ $recruitment->selection_stage === 'diterima' ? 'selected' : '' }}>Tahap 3: Diterima</option>
                            <option value="ditolak" {{ $recruitment->selection_stage === 'ditolak' ? 'selected' : '' }}>Tahap 3: Tidak Lolos</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="interview_schedule">Jadwal Sesi Wawancara (Jika Ada)</label>
                        <input type="datetime-local" id="interview_schedule" name="interview_schedule" value="{{ $recruitment->interview_schedule ? \Carbon\Carbon::parse($recruitment->interview_schedule)->format('Y-m-d\TH:i') : '' }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="interview_location">Tempat / Ruang Wawancara</label>
                        <input type="text" id="interview_location" name="interview_location" value="{{ old('interview_location', $recruitment->interview_location) }}" class="form-control" placeholder="Contoh: Lab Komputer 3 / Link Zoom">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="admin_notes">Catatan Resmi untuk Pelamar</label>
                        <textarea id="admin_notes" name="admin_notes" class="form-control" rows="3" placeholder="Pesan ini akan terbaca oleh calon anggota pada halaman status seleksi...">{{ old('admin_notes', $recruitment->admin_notes) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Simpan Keputusan &rarr;
                    </button>
                </form>

                @if ($recruitment->status === 'accepted')
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-200);">
                        @if ($recruitment->member)
                            <div style="background: var(--success-bg); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: var(--radius-md); padding: 1rem; text-align: center;">
                                <div style="color: #059669; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.25rem;">
                                    &#10003; Resmi Terdaftar Sebagai Anggota
                                </div>
                                <div style="font-size: 0.775rem; color: var(--slate-600); margin-bottom: 0.75rem;">
                                    Data pendaftar telah tersinkronisasi di direktori anggota UKM.
                                </div>
                                <a href="{{ route('admin.members.index', ['q' => $recruitment->nim]) }}" class="btn btn-outline btn-sm" style="font-size: 0.775rem;">
                                    Lihat di Direktori Anggota &rarr;
                                </a>
                            </div>
                        @else
                            <div style="background: #eff6ff; border: 1px solid rgba(37, 99, 235, 0.3); border-radius: var(--radius-md); padding: 1.25rem; text-align: center;">
                                <div style="color: var(--accent-blue); font-weight: 800; font-size: 0.95rem; margin-bottom: 0.35rem;">
                                    Angkat Menjadi Anggota Resmi
                                </div>
                                <p style="font-size: 0.8rem; color: var(--slate-600); line-height: 1.5; margin-bottom: 1rem;">
                                    Pendaftar ini telah diterima. Klik di bawah untuk langsung menerbitkan status Anggota UKM aktif tanpa input ulang.
                                </p>
                                <form action="{{ route('admin.recruitment.convertToMember', $recruitment->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                                        + Jadikan Anggota UKM
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
