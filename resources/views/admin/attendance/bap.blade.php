<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAP Presensi: {{ $session->title }} - UKM Ilmu Komputer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 20mm 15mm;
        }

        * {
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: #e2e8f0;
            color: #000000;
            font-size: 11pt;
            line-height: 1.35;
        }

        .no-print-toolbar {
            background: #0f172a;
            color: #ffffff;
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .btn-print {
            background: #0284c7;
            color: #ffffff;
        }

        .btn-print:hover {
            background: #0369a1;
        }

        .btn-back {
            background: #334155;
            color: #f8fafc;
        }

        .btn-back:hover {
            background: #475569;
        }

        .page-sheet {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            margin: 1.5rem auto 3rem;
            padding: 20mm 20mm 20mm 20mm;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Kop Surat Resmi Kampus */
        .kop-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #000000;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }

        .kop-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
        }

        .kop-text {
            text-align: center;
            flex: 1;
            padding: 0 10px;
        }

        .kop-text h3 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .kop-text h2 {
            margin: 2px 0;
            font-size: 15pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .kop-text p {
            margin: 2px 0 0;
            font-size: 9.5pt;
            font-style: italic;
        }

        /* Judul Dokumen */
        .doc-title-box {
            text-align: center;
            margin-bottom: 18px;
        }

        .doc-title-box h1 {
            font-size: 13.5pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            letter-spacing: 0.04em;
        }

        .doc-title-box .doc-number {
            font-size: 10pt;
            margin-top: 3px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-weight: 600;
            color: #1e293b;
        }

        /* Tabel Rincian Kegiatan */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10.5pt;
        }

        .meta-table td {
            padding: 3px 6px;
            vertical-align: top;
        }

        .meta-table td.label {
            width: 25%;
            font-weight: bold;
        }

        .meta-table td.separator {
            width: 2%;
            text-align: center;
        }

        .meta-table td.value {
            width: 73%;
        }

        /* Box Kurikulum & Capaian */
        .curriculum-box {
            border: 1px solid #000000;
            padding: 8px 12px;
            margin-bottom: 14px;
            font-size: 10pt;
            background: #fafafa;
        }

        .curriculum-box .title {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
            font-size: 9.5pt;
        }

        /* Ringkasan Statistik */
        .summary-stats {
            display: flex;
            justify-content: space-between;
            border: 1px solid #000000;
            margin-bottom: 14px;
            font-size: 9.5pt;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .summary-item {
            flex: 1;
            text-align: center;
            padding: 6px;
            border-right: 1px solid #000000;
        }

        .summary-item:last-child {
            border-right: none;
        }

        .summary-item .num {
            font-size: 13pt;
            font-weight: bold;
            display: block;
        }

        /* Tabel Presensi Peserta */
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9.5pt;
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid #000000;
            padding: 5px 6px;
        }

        .attendance-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 9pt;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .badge-status {
            display: inline-block;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
            padding: 1px 5px;
            border-radius: 3px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .status-hadir {
            color: #047857;
        }

        .status-izin {
            color: #0369a1;
        }

        .status-sakit {
            color: #b45309;
        }

        .status-alpa {
            color: #b91c1c;
        }

        /* Lembar Pengesahan Tanda Tangan */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            page-break-inside: avoid;
            font-size: 10.5pt;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 0 15px;
        }

        .sign-spacer {
            height: 65px;
        }

        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 2px;
        }

        @media print {
            body {
                background: #ffffff;
            }

            .no-print-toolbar {
                display: none !important;
            }

            .page-sheet {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }

            .attendance-table th {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .curriculum-box {
                background: #ffffff !important;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Toolbar (Layar Monitor Saja) -->
    <div class="no-print-toolbar">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="{{ route('admin.attendance.show', $session) }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Detail Sesi
            </a>
            <span style="font-size: 0.9rem; font-weight: 500;">
                Format Berita Acara Presensi (BAP) Resmi &bull; {{ $session->title }}
            </span>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <button onclick="window.print()" class="btn btn-print">
                <i class="fas fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Lembar Halaman Cetak Dokumen A4 -->
    <div class="page-sheet">

        <!-- Kop Surat Resmi -->
        <div class="kop-wrapper">
            <img src="{{ asset('images/logo.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=UKM&background=0284c7&color=ffffff&bold=true'" alt="Logo UKM" class="kop-logo">
            <div class="kop-text">
                <h3>ORGANISASI KEMAHASISWAAN &bull; BIDANG PENALARAN DAN RISET</h3>
                <h2>UNIT KEGIATAN MAHASISWA ILMU KOMPUTER</h2>
                <p>Sekretariat: Gedung Graha Mahasiswa Lt. 2, Laboratorium Informatika & Sains Komputer<br>
                Email: ukm.ilkom@kampus.ac.id &bull; Website: {{ url('/') }} &bull; Periode 2026/2027</p>
            </div>
            <div style="width: 75px; text-align: right;">
                @if ($session->division)
                    <div style="font-size: 8pt; font-weight: bold; border: 1px solid #000; padding: 4px; text-transform: uppercase;">
                        DIVISI<br>{{ $session->division->name }}
                    </div>
                @else
                    <div style="font-size: 8pt; font-weight: bold; border: 1px solid #000; padding: 4px; text-transform: uppercase;">
                        AGENDA<br>PLENO
                    </div>
                @endif
            </div>
        </div>

        <!-- Judul Dokumen -->
        <div class="doc-title-box">
            <h1>BERITA ACARA PERTEMUAN & DAFTAR PRESENSI</h1>
            <div class="doc-number">
                Nomor: BAP/UKM-ILKOM/{{ $session->division ? strtoupper($session->division->slug) : 'PLENO' }}/{{ $session->session_date->format('Y/m') }}/{{ str_pad($session->id, 3, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        <!-- Tabel Informasi Kegiatan -->
        <table class="meta-table">
            <tr>
                <td class="label">Mata Agenda / Sesi</td>
                <td class="separator">:</td>
                <td class="value"><strong>{{ $session->title }}</strong></td>
            </tr>
            <tr>
                <td class="label">Divisi Penyelenggara</td>
                <td class="separator">:</td>
                <td class="value">{{ $session->division ? $session->division->name : 'Pleno Seluruh Anggota UKM' }}</td>
            </tr>
            <tr>
                <td class="label">Hari, Tanggal</td>
                <td class="separator">:</td>
                <td class="value">{{ $session->day_name }}, {{ $session->session_date->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Waktu Pelaksanaan</td>
                <td class="separator">:</td>
                <td class="value">{{ substr($session->time_start, 0, 5) }} {{ $session->time_end ? '- ' . substr($session->time_end, 0, 5) : '' }} WIB</td>
            </tr>
            <tr>
                <td class="label">Tempat / Ruangan</td>
                <td class="separator">:</td>
                <td class="value">{{ $session->location }}</td>
            </tr>
            <tr>
                <td class="label">Tipe Sesi Pertemuan</td>
                <td class="separator">:</td>
                <td class="value">{{ ucwords(str_replace('_', ' ', $session->session_type)) }}</td>
            </tr>
            <tr>
                <td class="label">Instruktur / Pemateri</td>
                <td class="separator">:</td>
                <td class="value">{{ $session->instructor_name }}</td>
            </tr>
        </table>

        <!-- Kurikulum & Capaian Pembelajaran -->
        <div class="curriculum-box">
            <div class="title">Pokok Bahasan Materi:</div>
            <div style="font-weight: bold; margin-bottom: 4px;">{{ $session->topic_material ?? $session->title }}</div>
            @if ($session->learning_outcomes)
                <div class="title" style="margin-top: 6px;">Target Capaian Riset / Pembelajaran (*Learning Outcomes*):</div>
                <div>{{ $session->learning_outcomes }}</div>
            @endif
        </div>

        <!-- Ringkasan Statistik Presensi -->
        <div class="summary-stats">
            <div class="summary-item">
                <span class="num">{{ $stats['total'] }}</span>
                <span>Total Anggota</span>
            </div>
            <div class="summary-item" style="color: #047857;">
                <span class="num">{{ $stats['hadir'] }}</span>
                <span>Hadir</span>
            </div>
            <div class="summary-item" style="color: #0369a1;">
                <span class="num">{{ $stats['izin'] }}</span>
                <span>Izin</span>
            </div>
            <div class="summary-item" style="color: #b45309;">
                <span class="num">{{ $stats['sakit'] }}</span>
                <span>Sakit</span>
            </div>
            <div class="summary-item" style="color: #b91c1c;">
                <span class="num">{{ $stats['alpa'] }}</span>
                <span>Alpa</span>
            </div>
            <div class="summary-item" style="background: #f8fafc; font-weight: bold;">
                <span class="num">{{ $stats['rate'] }}%</span>
                <span>Tingkat Kehadiran</span>
            </div>
        </div>

        <!-- Tabel Daftar Hadir -->
        <table class="attendance-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 18%;">NIM</th>
                    <th style="width: 32%;">Nama Anggota</th>
                    <th style="width: 14%;">Jam Masuk</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 16%;">Paraf / Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $idx => $log)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-weight: 600;">
                            {{ $log->member->nim ?? '-' }}
                        </td>
                        <td>
                            <strong>{{ $log->member->name ?? 'Anggota' }}</strong>
                            @if ($log->notes)
                                <div style="font-size: 8pt; color: #475569; font-style: italic;">
                                    Catatan: {{ Str::limit($log->notes, 40) }}
                                </div>
                            @endif
                        </td>
                        <td style="text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                            {{ $log->checked_in_at ? $log->checked_in_at->format('H:i') . ' WIB' : '-' }}
                        </td>
                        <td style="text-align: center;">
                            <span class="badge-status status-{{ $log->status }}">
                                {{ strtoupper($log->status) }}
                            </span>
                        </td>
                        <td style="text-align: center; font-size: 8pt; color: #64748b;">
                            {{ $log->checkin_type === 'self' ? '✓ Presensi Mandiri' : '✓ Pengurus' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 1.5rem;">
                            Tidak ada data peserta terdaftar untuk sesi ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Lembar Tanda Tangan & Pengesahan -->
        <table class="signature-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    <strong>Dosen Pembina UKM</strong>
                    <div class="sign-spacer"></div>
                    <div class="sign-name">{{ $session->division?->adviser_name ?? 'Dosen Pembina UKM' }}</div>
                    <div>NIP/NIDN. {{ $session->division?->adviser_title ?? '-' }}</div>
                </td>
                <td>
                    {{ $session->location }}, {{ $session->session_date->translatedFormat('d F Y') }}<br>
                    <strong>Instruktur / Ketua Divisi</strong>
                    <div class="sign-spacer"></div>
                    <div class="sign-name">{{ $session->instructor_name ?? ($session->division?->leader_name ?? 'Ketua Pelaksana') }}</div>
                    <div>NIM. {{ $session->division?->leader_nim ?? '-' }}</div>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>
