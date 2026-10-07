@extends('admin.layouts.app')

@section('title', 'Biodata Anggota: ' . $member->name . ' - UKM CMS')
@section('page_title', 'Dossier Biodata Anggota Mahasiswa')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.75rem;">

    <!-- Top Action Navigation -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <a href="{{ route('admin.members.index') }}" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 0.4rem; font-weight: 700;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Anggota
        </a>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('student.kta') }}?preview_member_id={{ $member->id }}" target="_blank" class="btn btn-outline btn-sm" style="font-weight: 700;">
                <i class="fas fa-id-card" style="color: #0284c7; margin-right: 0.3rem;"></i> Pratinjau KTA
            </a>
            <a href="{{ route('admin.members.edit', $member->id) }}" class="btn btn-primary btn-sm" style="font-weight: 700;">
                <i class="fas fa-pen-to-square" style="margin-right: 0.3rem;"></i> Edit Biodata
            </a>
            <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota {{ addslashes($member->name) }}?')" style="margin: 0; display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" style="font-weight: 700;">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Member Identity Header Banner -->
    <div class="glass-panel" style="padding: 2rem 2.25rem;">
        <div style="display: flex; align-items: center; gap: 1.75rem; flex-wrap: wrap;">
            <!-- Pas Foto Profil -->
            <div style="width: 100px; height: 125px; border-radius: 14px; overflow: hidden; background: #f1f5f9; border: 2px solid #e2e8f0; flex-shrink: 0; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <!-- Identitas Pokok -->
            <div style="flex: 1; min-width: 260px;">
                <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap; margin-bottom: 0.5rem;">
                    @if ($member->division)
                        <span class="badge" style="background: {{ $member->division->color_accent }}15; color: {{ $member->division->color_accent }}; font-weight: 800; font-size: 0.775rem;">
                            {{ $member->division->name }}
                        </span>
                    @endif
                    <span class="badge {{ $member->status === 'aktif' ? 'badge-success' : ($member->status === 'alumni' ? 'badge-neutral' : 'badge-danger') }}" style="font-weight: 800; font-size: 0.75rem;">
                        <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.25rem;"></i> STATUS: {{ strtoupper($member->status) }}
                    </span>
                    <span style="font-size: 0.775rem; color: var(--slate-400); font-weight: 600;">
                        Angkatan {{ $member->batch_year }}
                    </span>
                </div>

                <h2 style="font-size: 1.6rem; font-weight: 900; color: var(--slate-900); margin: 0 0 0.35rem; letter-spacing: -0.02em;">
                    {{ $member->name }}
                </h2>

                <div style="display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap; font-size: 0.85rem; color: var(--slate-600);">
                    <div>
                        NIM: <strong style="font-family: var(--font-mono); color: var(--slate-900);">{{ $member->nim }}</strong>
                    </div>
                    <div>&bull;</div>
                    <div>
                        Email: <strong style="color: var(--slate-900);">{{ $member->email }}</strong>
                    </div>
                    @if ($member->phone_number)
                        <div>&bull;</div>
                        <div>
                            WhatsApp: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $member->phone_number) }}" target="_blank" style="color: #16a34a; font-weight: 700; text-decoration: none;">
                                <i class="fab fa-whatsapp"></i> {{ $member->phone_number }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Ubah Cepat -->
            <div style="background: #f8fafc; padding: 1rem 1.25rem; border-radius: var(--radius-lg); border: 1px solid #e2e8f0; min-width: 200px;">
                <div style="font-size: 0.725rem; color: var(--slate-500); font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">
                    Ubah Status Keaktifan
                </div>
                <form action="{{ route('admin.members.updateStatus', $member->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div style="display: flex; gap: 0.4rem;">
                        <select name="status" class="form-control" style="padding: 0.4rem 0.6rem; font-size: 0.8rem;">
                            <option value="aktif" {{ $member->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="non_aktif" {{ $member->status === 'non_aktif' ? 'selected' : '' }}>Non Aktif</option>
                            <option value="alumni" {{ $member->status === 'alumni' ? 'selected' : '' }}>Alumni</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm" style="padding: 0.4rem 0.75rem;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2 Column Dossier Content -->
    <div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 1.75rem;">
        
        <!-- Kolom Kiri: Dokumen & Data Pribadi -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Kartu Kontak & Informasi Tambahan -->
            <div class="glass-panel" style="padding: 1.5rem;">
                <h4 style="font-size: 1.05rem; font-weight: 800; color: var(--slate-900); margin: 0 0 1rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.65rem;">
                    <i class="fas fa-address-card" style="color: #0284c7; margin-right: 0.4rem;"></i> Informasi Kontak & Akun
                </h4>
                <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.875rem;">
                    <div>
                        <div style="font-size: 0.75rem; color: var(--slate-400); font-weight: 600;">Tanggal Bergabung</div>
                        <div style="font-weight: 700; color: var(--slate-800);">
                            {{ $member->join_date ? $member->join_date->translatedFormat('d F Y') : '-' }}
                        </div>
                    </div>
                    <div style="background: #f8fafc; border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: 0.85rem; margin-top: 0.35rem;">
                        <div style="font-size: 0.75rem; color: var(--slate-500); font-weight: 700; text-transform: uppercase; margin-bottom: 0.35rem;">
                            Hak Akses & Otoritas Akun
                        </div>
                        @if ($member->user)
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; flex-wrap: wrap;">
                                <div>
                                    @if ($member->user->role === 'super_admin')
                                        <span class="badge" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a; font-weight: 800; font-size: 0.75rem;">
                                            <i class="fas fa-crown"></i> Super Admin
                                        </span>
                                    @elseif ($member->user->role === 'division_admin')
                                        <span class="badge" style="background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; font-weight: 800; font-size: 0.75rem;">
                                            <i class="fas fa-shield-halved"></i> Admin Divisi ({{ $member->user->division->name ?? 'Divisi' }})
                                        </span>
                                    @else
                                        <span class="badge" style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-weight: 700; font-size: 0.75rem;">
                                            <i class="fas fa-user-graduate"></i> Mahasiswa Biasa
                                        </span>
                                    @endif
                                </div>
                                @if (auth()->user()->isSuperAdmin())
                                    <a href="{{ route('admin.users.index', ['q' => $member->user->email]) }}" class="btn btn-primary btn-sm" style="font-size: 0.725rem; padding: 0.25rem 0.6rem; font-weight: 700;">
                                        <i class="fas fa-user-shield"></i> Tunjuk Role di User Management
                                    </a>
                                @endif
                            </div>
                        @else
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; flex-wrap: wrap;">
                                <span class="badge badge-neutral" style="font-size: 0.72rem;">
                                    Belum Memiliki Akun Login
                                </span>
                                @if (auth()->user()->isSuperAdmin())
                                    <a href="{{ route('admin.users.index', ['q' => $member->email]) }}" class="btn btn-outline btn-sm" style="font-size: 0.725rem; padding: 0.25rem 0.6rem; font-weight: 700;">
                                        <i class="fas fa-plus"></i> Buat Akun & Tunjuk Role
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                    @if ($member->notes)
                        <div>
                            <div style="font-size: 0.75rem; color: var(--slate-400); font-weight: 600;">Catatan Khusus Pengurus</div>
                            <div style="background: #f8fafc; padding: 0.6rem 0.85rem; border-radius: 8px; font-size: 0.825rem; color: var(--slate-700); margin-top: 0.25rem;">
                                {{ $member->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kartu Foto KTM Asli Pendaftaran -->
            <div class="glass-panel" style="padding: 1.5rem;">
                <h4 style="font-size: 1.05rem; font-weight: 800; color: var(--slate-900); margin: 0 0 1rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.65rem;">
                    <i class="fas fa-id-badge" style="color: #10b981; margin-right: 0.4rem;"></i> Kartu Tanda Mahasiswa (KTM)
                </h4>
                @if ($member->recruitment && $member->recruitment->ktm_photo)
                    <div style="border-radius: var(--radius-md); overflow: hidden; border: 1.5px solid #cbd5e1; background: #0f172a;">
                        <img src="{{ asset('storage/' . $member->recruitment->ktm_photo) }}" alt="Foto KTM" style="width: 100%; max-height: 200px; object-fit: contain; display: block;">
                    </div>
                    <div style="margin-top: 0.75rem; text-align: center;">
                        <a href="{{ asset('storage/' . $member->recruitment->ktm_photo) }}" target="_blank" class="btn btn-outline btn-sm" style="font-size: 0.75rem;">
                            <i class="fas fa-magnifying-glass-plus"></i> Buka Foto Asli Berkas
                        </a>
                    </div>
                @else
                    <div style="padding: 1.5rem; text-align: center; color: var(--slate-400); font-size: 0.85rem; background: #f8fafc; border-radius: 8px; border: 1px dashed #cbd5e1;">
                        <i class="fas fa-file-image" style="font-size: 1.75rem; margin-bottom: 0.5rem; display: block; color: var(--slate-300);"></i>
                        Tidak ada berkas KTM pendaftaran digital yang tersimpan.
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Rapor Kehadiran, Karya, dan Sertifikat -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Rapor Presensi Mahasiswa -->
            <div class="glass-panel" style="padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <h4 style="font-size: 1.05rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                        <i class="fas fa-clipboard-check" style="color: #16a34a; margin-right: 0.4rem;"></i>
                        Rapor Kehadiran & Rekap Presensi
                    </h4>
                    <span class="badge" style="background: #dcfce7; color: #15803d; font-weight: 800; font-size: 0.825rem; padding: 0.35rem 0.75rem;">
                        {{ $attendanceStats['percentage'] }}% Kehadiran
                    </span>
                </div>

                <!-- KPI Quick Bar -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 1.25rem;">
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 0.75rem; border-radius: 8px; text-align: center;">
                        <div style="font-size: 0.7rem; color: #166534; font-weight: 700;">HADIR</div>
                        <div style="font-size: 1.25rem; font-weight: 900; color: #15803d;">{{ $attendanceStats['attended_count'] }}</div>
                    </div>
                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 0.75rem; border-radius: 8px; text-align: center;">
                        <div style="font-size: 0.7rem; color: #1e40af; font-weight: 700;">IZIN</div>
                        <div style="font-size: 1.25rem; font-weight: 900; color: #1d4ed8;">{{ $attendanceStats['permission_count'] }}</div>
                    </div>
                    <div style="background: #fefce8; border: 1px solid #fef08a; padding: 0.75rem; border-radius: 8px; text-align: center;">
                        <div style="font-size: 0.7rem; color: #854d0e; font-weight: 700;">SAKIT</div>
                        <div style="font-size: 1.25rem; font-weight: 900; color: #a16207;">{{ $attendanceStats['sick_count'] }}</div>
                    </div>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 0.75rem; border-radius: 8px; text-align: center;">
                        <div style="font-size: 0.7rem; color: #991b1b; font-weight: 700;">ALPA</div>
                        <div style="font-size: 1.25rem; font-weight: 900; color: #b91c1c;">{{ $attendanceStats['absent_count'] }}</div>
                    </div>
                </div>

                <!-- Histori Presensi Table -->
                @if ($attendanceStats['logs']->count() > 0)
                    <div class="table-responsive">
                        <table class="table" style="font-size: 0.8rem; margin: 0;">
                            <thead>
                                <tr>
                                    <th>Topik Pertemuan Sesi</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Bukti Surat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendanceStats['logs']->take(5) as $log)
                                    <tr>
                                        <td>
                                            <strong style="color: var(--slate-900);">{{ $log->session?->title }}</strong>
                                        </td>
                                        <td>
                                            {{ $log->session?->session_date ? $log->session->session_date->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $log->status_badge }}" style="font-size: 0.7rem;">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($log->attachment)
                                                <a href="{{ asset('storage/' . $log->attachment) }}" target="_blank" class="btn btn-outline btn-sm" style="padding: 0.2rem 0.5rem; font-size: 0.7rem;">
                                                    <i class="fas fa-file"></i> Surat
                                                </a>
                                            @else
                                                <span style="color: var(--slate-300);">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="font-size: 0.825rem; color: var(--slate-400); text-align: center; margin: 1rem 0;">
                        Belum ada riwayat presensi tercatat untuk anggota ini.
                    </p>
                @endif
            </div>

            <!-- Karya & Proyek Inovasi -->
            <div class="glass-panel" style="padding: 1.5rem;">
                <h4 style="font-size: 1.05rem; font-weight: 800; color: var(--slate-900); margin: 0 0 1rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.65rem;">
                    <i class="fas fa-laptop-code" style="color: #0284c7; margin-right: 0.4rem;"></i>
                    Portofolio Karya & Riset Mahasiswa
                </h4>
                @if ($projects->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach ($projects as $proj)
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.85rem 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                                <div>
                                    <div style="font-weight: 800; font-size: 0.9rem; color: var(--slate-900);">
                                        {{ $proj->title }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--slate-500); margin-top: 0.2rem;">
                                        {{ Str::limit($proj->description, 80) }}
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    @if ($proj->submission_status === 'published')
                                        <span class="badge badge-success" style="font-size: 0.7rem;">Tayang</span>
                                    @elseif ($proj->submission_status === 'pending_review')
                                        <span class="badge badge-warning" style="font-size: 0.7rem;">Review</span>
                                    @else
                                        <span class="badge badge-danger" style="font-size: 0.7rem;">Ditolak</span>
                                    @endif
                                    <a href="{{ route('admin.projects.edit', $proj->id) }}" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.725rem;">
                                        Kelola
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="font-size: 0.825rem; color: var(--slate-400); text-align: center; margin: 1rem 0;">
                        Mahasiswa ini belum mengunggah karya atau proyek inovasi.
                    </p>
                @endif
            </div>

            <!-- E-Sertifikat Terbit -->
            <div class="glass-panel" style="padding: 1.5rem;">
                <h4 style="font-size: 1.05rem; font-weight: 800; color: var(--slate-900); margin: 0 0 1rem; border-bottom: 1px solid var(--slate-100); padding-bottom: 0.65rem;">
                    <i class="fas fa-certificate" style="color: #f59e0b; margin-right: 0.4rem;"></i>
                    Riwayat E-Sertifikat Resmi
                </h4>
                @if ($certificates->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach ($certificates as $cert)
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.85rem 1rem; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="color: var(--slate-900); font-size: 0.875rem;">{{ $cert->event_name }}</strong>
                                    <div style="font-size: 0.75rem; color: var(--slate-500); font-family: var(--font-mono);">
                                        {{ $cert->certificate_code }} &bull; Peran: {{ $cert->role_as }}
                                    </div>
                                </div>
                                <a href="{{ route('certificates.verify') }}?code={{ $cert->certificate_code }}" target="_blank" class="btn btn-outline btn-sm" style="font-size: 0.725rem;">
                                    Validasi &rarr;
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="font-size: 0.825rem; color: var(--slate-400); text-align: center; margin: 1rem 0;">
                        Belum ada sertifikat kegiatan yang diterbitkan atas nama mahasiswa ini.
                    </p>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
