@extends('layouts.app')

@section('title', 'Pendaftaran Anggota Baru - UKM Ilmu Komputer')

@section('content')
<div style="padding: 4rem 0 2rem;">
    <div class="container-narrow" style="text-align: center;">
        <span class="badge badge-success" style="margin-bottom: 0.75rem;">
            <span class="badge-pulse"></span>
            <span>Open Recruitment &bull; {{ $batch }}</span>
        </span>
        <h1 style="font-size: 2.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.03em; margin-bottom: 0.75rem;">
            Formulir Pendaftaran Anggota Baru
        </h1>
        <p style="color: var(--slate-600); font-size: 1.1rem; line-height: 1.6;">
            Satu pintu pendaftaran resmi untuk mahasiswa Fakultas Ilmu Komputer. Silakan lengkapi biodata, pilihan divisi, dan lampirkan berkas pendukung Anda.
        </p>
    </div>
</div>

<div class="container-narrow" style="padding: 1rem 1.5rem 5rem;">
    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #991b1b; display: flex; align-items: center; gap: 0.75rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span style="font-weight: 500; font-size: 0.95rem;">{{ session('error') }}</span>
        </div>
    @endif

    @if (!$isOpen)
        <div class="glass-panel" style="text-align: center; padding: 3.5rem 2rem; border-top: 4px solid var(--warning); border-radius: 16px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--warning-bg); color: var(--warning); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="badge badge-neutral" style="margin-bottom: 0.75rem;">
                Status: Pendaftaran Sedang Ditutup
            </div>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--slate-900); margin-bottom: 0.75rem; letter-spacing: -0.02em;">
                Pendaftaran Sedang Ditutup
            </h2>
            <p style="color: var(--slate-600); margin-bottom: 1.75rem; max-width: 540px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                {{ $closedMessage ?? 'Periode pendaftaran anggota baru saat ini sedang tidak aktif atau batas waktu gelombang telah berakhir.' }}
            </p>
            @if(!empty($startDate) || !empty($endDate))
                <div style="background: var(--bg-muted); border-radius: 10px; padding: 0.75rem 1.25rem; display: inline-flex; gap: 1.5rem; font-size: 0.85rem; color: var(--slate-700); margin-bottom: 2rem;">
                    @if(!empty($startDate))
                        <div><strong>Mulai:</strong> {{ $startDate }}</div>
                    @endif
                    @if(!empty($endDate))
                        <div><strong>Batas Akhir:</strong> {{ $endDate }}</div>
                    @endif
                </div>
                <br>
            @endif
            <a href="{{ route('recruitment.status') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <span>Cek Status Seleksi Anda</span>
                <span>&rarr;</span>
            </a>
        </div>
    @else
        <div class="glass-panel" style="padding: 2.5rem; border-top: 4px solid var(--accent-blue); border-radius: 16px;">
            <form action="{{ route('recruitment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Section 1 -->
                <div style="margin-bottom: 1.75rem;">
                    <div class="section-tag" style="margin-bottom: 0.35rem;">Tahap 1 dari 3</div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900);">
                        Informasi Biodata Mahasiswa
                    </h3>
                    <p style="font-size: 0.85rem; color: var(--slate-500);">Pastikan data sesuai dengan kartu tanda mahasiswa aktif Anda.</p>
                </div>

                <div class="form-group">
                    <label class="form-label" for="full_name">Nama Lengkap *</label>
                    <input type="text" id="full_name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" placeholder="Contoh: Bintang Ramadhan" required>
                    @error('full_name') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="nim">Nomor Induk Mahasiswa (NIM) *</label>
                        <input type="text" id="nim" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" placeholder="Contoh: 220104012" required>
                        @error('nim') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="semester">Semester Saat Ini *</label>
                        <select id="semester" name="semester" class="form-control @error('semester') is-invalid @enderror" required>
                            <option value="">Pilih Semester</option>
                            @for ($s = 1; $s <= 8; $s++)
                                <option value="{{ $s }}" {{ old('semester') == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
                            @endfor
                        </select>
                        @error('semester') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone_whatsapp">Nomor WhatsApp Aktif *</label>
                    <input type="text" id="phone_whatsapp" name="phone_whatsapp" class="form-control @error('phone_whatsapp') is-invalid @enderror" value="{{ old('phone_whatsapp') }}" placeholder="Contoh: 081298765432" required>
                    @error('phone_whatsapp') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>

                <!-- Pas Foto Profil Mahasiswa -->
                <div class="form-group" style="background: var(--slate-50); border: 1px dashed var(--slate-300); border-radius: var(--radius-lg); padding: 1.25rem; margin-bottom: 1.5rem;">
                    <label class="form-label" for="profile_photo" style="display: flex; align-items: center; justify-content: space-between;">
                        <span><i class="fas fa-camera" style="color: var(--primary-600); margin-right: 0.5rem;"></i> Pas Foto Resmi / Foto Profil Mahasiswa</span>
                        <span style="font-size: 0.75rem; font-weight: 500; color: var(--slate-400);">JPG, PNG, WEBP (Maks 2MB)</span>
                    </label>
                    <input type="file" id="profile_photo" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                    <p style="font-size: 0.775rem; color: var(--slate-500); margin-top: 0.4rem; margin-bottom: 0;">
                        Foto setengah badan dengan pakaian rapi. Foto ini otomatis tampil di <strong>KTA Digital Mahasiswa</strong> dan buku data anggota UKM.
                    </p>
                    @error('profile_photo') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>

                <!-- Akun & Keamanan Akses Portal Mahasiswa -->
                <div style="background: linear-gradient(135deg, rgba(37,99,235,0.04), rgba(14,165,233,0.04)); border: 1px solid rgba(37,99,235,0.15); border-radius: var(--radius-lg); padding: 1.25rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <span style="display: inline-flex; width: 24px; height: 24px; border-radius: 6px; background: var(--primary-600); color: #fff; align-items: center; justify-content: center; font-size: 0.75rem;"><i class="fas fa-lock"></i></span>
                        <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--slate-900); margin: 0;">Akun & Akses Portal Mahasiswa</h4>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--slate-600); margin-bottom: 1rem;">
                        Akun ini akan langsung aktif agar Anda dapat login ke <strong>Portal Mahasiswa</strong>, memantau pengumuman kelulusan, jadwal wawancara, serta mengunduh KTA Digital.
                    </p>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label class="form-label" for="email">Alamat Email Aktif (ID Login) *</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()?->email) }}" placeholder="Contoh: nama@student.ac.id" required {{ auth()->check() ? 'readonly' : '' }}>
                        @error('email') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    @guest
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="password">Kata Sandi Baru *</label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                            @error('password') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="password_confirmation">Ulangi Kata Sandi *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi kata sandi" required>
                        </div>
                    </div>
                    @endguest
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="class_group">Kelas / Rombel Kuliah (Opsional)</label>
                    <input type="text" id="class_group" name="class_group" class="form-control @error('class_group') is-invalid @enderror" value="{{ old('class_group') }}" placeholder="Contoh: IF-22A atau Reguler Pagi">
                    @error('class_group') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>

                <!-- Section 2 -->
                <div style="margin: 3rem 0 1.75rem; padding-top: 2rem; border-top: 1px solid var(--slate-200);">
                    <div class="section-tag" style="margin-bottom: 0.35rem;">Tahap 2 dari 3</div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900);">
                        Pilihan Bidang Divisi & Motivasi
                    </h3>
                    <p style="font-size: 0.85rem; color: var(--slate-500);">Tentukan fokus bidang yang ingin Anda kembangkan bersama tim riset UKM.</p>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="first_choice_division_id">Divisi Pilihan Utama *</label>
                        <select id="first_choice_division_id" name="first_choice_division_id" class="form-control @error('first_choice_division_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Divisi Utama --</option>
                            @foreach ($divisions as $d)
                                <option value="{{ $d->id }}" {{ (old('first_choice_division_id', optional($preselectedDivision)->id) == $d->id) ? 'selected' : '' }}>
                                    {{ $d->name }}{{ $d->recruitment_quota ? ' (Kuota: ' . $d->recruitment_quota . ')' : '' }}{{ $d->recruitment_notes ? ' • ' . $d->recruitment_notes : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('first_choice_division_id') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="second_choice_division_id">Divisi Pilihan Kedua (Opsional)</label>
                        <select id="second_choice_division_id" name="second_choice_division_id" class="form-control @error('second_choice_division_id') is-invalid @enderror">
                            <option value="">-- Bebas / Tidak Memilih --</option>
                            @foreach ($divisions as $d)
                                <option value="{{ $d->id }}" {{ old('second_choice_division_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }}{{ $d->recruitment_quota ? ' (Kuota: ' . $d->recruitment_quota . ')' : '' }}{{ $d->recruitment_notes ? ' • ' . $d->recruitment_notes : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('second_choice_division_id') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="reason_to_join">Motivasi & Komitmen Bergabung *</label>
                    <textarea id="reason_to_join" name="reason_to_join" rows="4" class="form-control @error('reason_to_join') is-invalid @enderror" placeholder="Ceritakan ketertarikan Anda pada divisi yang dipilih, pengalaman relevan sebelumnya, dan apa yang ingin Anda capai bersama UKM..." required>{{ old('reason_to_join') }}</textarea>
                    <small style="color: var(--slate-400); font-size: 0.775rem;">Minimal 20 karakter.</small>
                    @error('reason_to_join') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>

                <!-- Section 3 -->
                <div style="margin: 3rem 0 1.75rem; padding-top: 2rem; border-top: 1px solid var(--slate-200);">
                    <div class="section-tag" style="margin-bottom: 0.35rem;">Tahap 3 dari 3</div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900);">
                        Unggah Berkas Pendukung & Portofolio
                    </h3>
                    <p style="font-size: 0.85rem; color: var(--slate-500);">Lampirkan kartu tanda mahasiswa aktif dan link portofolio (jika ada).</p>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="file_ktm">Upload Foto KTM (JPG, PNG, atau PDF)</label>
                        <input type="file" id="file_ktm" name="file_ktm" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        <small style="color: var(--slate-400); font-size: 0.75rem;">Maksimal 2MB.</small>
                        @error('file_ktm') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="file_cv">Upload CV / Resume Singkat (Opsional, PDF)</label>
                        <input type="file" id="file_cv" name="file_cv" class="form-control" accept=".pdf">
                        <small style="color: var(--slate-400); font-size: 0.75rem;">Format PDF, maksimal 3MB.</small>
                        @error('file_cv') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="github_url">Tautan Profil GitHub (Opsional)</label>
                        <input type="url" id="github_url" name="github_url" class="form-control @error('github_url') is-invalid @enderror" value="{{ old('github_url', auth()->user()?->github_url) }}" placeholder="https://github.com/username">
                        <small style="color: var(--slate-400); font-size: 0.75rem;">Sangat disukai untuk divisi teknologi & pemrograman.</small>
                        @error('github_url') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="portfolio_url">Link Portofolio / Karya Lainnya (Opsional)</label>
                        <input type="url" id="portfolio_url" name="portfolio_url" class="form-control @error('portfolio_url') is-invalid @enderror" value="{{ old('portfolio_url') }}" placeholder="https://dribbble.com, Behance, atau Drive">
                        <small style="color: var(--slate-400); font-size: 0.75rem;">Link karya nyata atau sertifikat pendukung.</small>
                        @error('portfolio_url') <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="margin-top: 2.5rem;">
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                        Kirim Formulir Pendaftaran Sekarang &rarr;
                    </button>
                    <p style="text-align: center; color: var(--slate-400); font-size: 0.8rem; margin-top: 1rem;">
                        Dengan mengirimkan formulir, Anda menyatakan data yang diberikan adalah benar dan siap mengikuti alur seleksi UKM.
                    </p>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
