@extends('layouts.app')

@section('title', 'Biodata Developer & Software Architect - Gempur Budi Anarki')
@section('meta_description', 'Profil dan rekam jejak Gempur Budi Anarki, Software Architect dan Cybersecurity Researcher dari Universitas Bina Bangsa Getsempena (UBBG) peraih sertifikat apresiasi KOMDIGI-CSIRT.')

@section('content')
<div style="background: linear-gradient(180deg, #f0f7ff 0%, #f8fafc 100%); padding: 3.5rem 0 2rem; border-bottom: 1px solid #e2e8f0;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.5rem;">
            <a href="{{ route('home') }}" style="color: #0284c7; text-decoration: none; font-weight: 600;">Beranda</a>
            <span style="margin: 0 0.5rem; color: #cbd5e1;">/</span>
            <span style="color: #0c2340; font-weight: 700;">Tentang Developer</span>
        </nav>

        <!-- Hero Card -->
        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 24px; padding: 2.5rem; box-shadow: var(--clay-card); margin-bottom: 2.5rem;">
            <div style="display: flex; flex-direction: column; md-flex-direction: row; gap: 2rem; align-items: center;" class="dev-hero-flex">
                <div style="position: relative; flex-shrink: 0;">
                    <div style="width: 140px; height: 140px; border-radius: 50%; padding: 4px; background: linear-gradient(135deg, #0284c7, #38bdf8, #0369a1); box-shadow: 0 10px 25px rgba(2, 132, 199, 0.25);">
                        <img src="https://gempurbudianarki.space/storage/profil.jpeg" 
                             alt="Gempur Budi Anarki" 
                             onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';"
                             style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; background: #0c2340;">
                    </div>
                </div>

                <div style="flex: 1; text-align: left;" class="dev-hero-text">
                    <div style="display: inline-block; background: #e0f2fe; color: #0284c7; font-weight: 800; font-size: 0.75rem; letter-spacing: 0.05em; text-transform: uppercase; padding: 0.35rem 0.85rem; border-radius: 9999px; margin-bottom: 0.65rem; border: 1px solid #bae6fd;">
                        Software Architect &bull; Cybersecurity Researcher
                    </div>
                    <h1 style="font-size: 2.35rem; font-weight: 900; color: #0c2340; letter-spacing: -0.02em; margin: 0 0 0.5rem; line-height: 1.2;">
                        Gempur Budi Anarki
                    </h1>
                    <p style="font-size: 1.05rem; color: #475569; margin: 0 0 1rem; line-height: 1.6; max-width: 780px;">
                        Mahasiswa Program Studi Ilmu Komputer di <strong>Universitas Bina Bangsa Getsempena (UBBG)</strong>, Fakultas Sains, Teknologi, dan Ilmu Kesehatan (FSTIK). Berfokus pada perancangan arsitektur sistem perangkat lunak, ketahanan pertahanan siber, dan riset kerentanan informasi.
                    </p>

                    <!-- Quote Block -->
                    <div style="background: #f8fafc; border-left: 4px solid #0284c7; padding: 0.85rem 1.25rem; border-radius: 0 12px 12px 0; margin-bottom: 1.5rem; font-style: italic; color: #334155; font-size: 0.95rem;">
                        “Pendidikan bertujuan untuk mempertajam kecerdasan, memperkukuh kemauan, serta memperhalus perasaan.”
                        <span style="display: block; font-style: normal; font-weight: 700; color: #64748b; font-size: 0.825rem; margin-top: 0.25rem;">— Tan Malaka</span>
                    </div>

                    <!-- Direct Social & Portfolio Links -->
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <a href="https://gempurbudianarki.space/" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, #0284c7, #0369a1); border-radius: 9999px; padding: 0.55rem 1.25rem; font-weight: 800; text-decoration: none; color: #ffffff; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);">
                            Kunjungi Portfolio Resmi
                        </a>
                        <a href="https://github.com/gempurbudianarki" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm" style="border-radius: 9999px; padding: 0.55rem 1.15rem; font-weight: 700; text-decoration: none; color: #0c2340; border: 1.5px solid #cbd5e1; background: #ffffff;">
                            GitHub Profile
                        </a>
                        <a href="https://www.linkedin.com/in/gempur-budi-anarki-b8977b369/?isSelfProfile=true" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm" style="border-radius: 9999px; padding: 0.55rem 1.15rem; font-weight: 700; text-decoration: none; color: #0284c7; border: 1.5px solid #bae6fd; background: #ffffff;">
                            LinkedIn
                        </a>
                        <a href="https://www.instagram.com/gmprbdarki/" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm" style="border-radius: 9999px; padding: 0.55rem 1.15rem; font-weight: 700; text-decoration: none; color: #e11d48; border: 1.5px solid #fecdd3; background: #ffffff;">
                            Instagram @gmprbdarki
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding: 2.5rem 1.5rem 5rem;">
    <!-- Grid 2 Kolom: Penghargaan & Latar Belakang -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; margin-bottom: 2.5rem;">
        
        <!-- National Cyber Award Box -->
        <div style="background: #ffffff; border: 1.5px solid #fed7aa; border-radius: 20px; padding: 2rem; box-shadow: var(--clay-card); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #f97316, #ea580c, #c2410c);"></div>
            <div style="display: inline-block; background: #ffedd5; color: #c2410c; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.25rem 0.75rem; border-radius: 9999px; margin-bottom: 1rem; border: 1px solid #fed7aa;">
                Penghargaan Tingkat Nasional
            </div>
            <h2 style="font-size: 1.45rem; font-weight: 900; color: #0c2340; margin: 0 0 1rem; line-height: 1.3;">
                Sertifikat Apresiasi KOMDIGI-CSIRT
            </h2>
            <p style="font-size: 0.95rem; color: #475569; line-height: 1.7; margin-bottom: 1.25rem;">
                Pada Juli 2026, Gempur berhasil meraih Sertifikat Apresiasi dari <strong>KOMDIGI-CSIRT</strong> (<em>Computer Security Incident Response Team</em> Kementerian Komunikasi dan Digital Republik Indonesia).
            </p>
            <div style="background: #fffaf5; border: 1px solid #ffedd5; border-radius: 14px; padding: 1.15rem; margin-bottom: 1.25rem; font-size: 0.9rem; color: #7c2d12; line-height: 1.65;">
                Penghargaan ini diberikan atas kontribusi nyata dalam mendukung penguatan sistem pertahanan siber Indonesia. Gempur berhasil mengidentifikasi potensi celah kerentanan informasi berupa <strong>Exposure of Sensitive Information</strong> pada suatu sistem informasi, dan melaporkannya secara etis melalui mekanisme <em>Responsible Vulnerability Disclosure</em>.
            </div>
            <div>
                <a href="https://bbg.ac.id/mahasiswa-ilmu-komputer-ubbg-raih-sertifikat-apresiasi-komdigi-csirt-atas-kontribusi-di-bidang-keamanan-siber/" target="_blank" rel="noopener noreferrer" style="display: inline-block; font-size: 0.875rem; color: #0284c7; font-weight: 800; text-decoration: none; border-bottom: 1.5px dashed #0284c7; padding-bottom: 2px;">
                    Baca Rilis Berita Resmi Kampus UBBG &rarr;
                </a>
            </div>
        </div>

        <!-- Academic & Institution Background -->
        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 2rem; box-shadow: var(--clay-card); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #0284c7, #0369a1);"></div>
            <div style="display: inline-block; background: #e0f2fe; color: #0284c7; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.25rem 0.75rem; border-radius: 9999px; margin-bottom: 1rem; border: 1px solid #bae6fd;">
                Riwayat Akademik & Institusi
            </div>
            <h2 style="font-size: 1.45rem; font-weight: 900; color: #0c2340; margin: 0 0 1rem; line-height: 1.3;">
                Latar Belakang Pendidikan
            </h2>
            
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 1.15rem;">
                    <div style="font-size: 0.75rem; font-weight: 800; color: #0284c7; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                        Pendidikan Tinggi (Strata 1)
                    </div>
                    <div style="font-weight: 800; font-size: 1.05rem; color: #0c2340; margin-bottom: 0.25rem;">
                        Universitas Bina Bangsa Getsempena (UBBG)
                    </div>
                    <div style="font-size: 0.885rem; color: #64748b; line-height: 1.5;">
                        Program Studi Ilmu Komputer<br>
                        Fakultas Sains, Teknologi, dan Ilmu Kesehatan (FSTIK)<br>
                        Kota Banda Aceh
                    </div>
                </div>

                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 1.15rem;">
                    <div style="font-size: 0.75rem; font-weight: 800; color: #0284c7; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                        Pendidikan Menengah
                    </div>
                    <div style="font-weight: 800; font-size: 1.05rem; color: #0c2340; margin-bottom: 0.25rem;">
                        Madrasah Aliyah Swasta (MAS) Ashhabul Yamin
                    </div>
                    <div style="font-size: 0.885rem; color: #64748b; line-height: 1.5;">
                        Kabupaten Aceh Selatan
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Spesialisasi & Engineering Domain -->
    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 2.25rem; box-shadow: var(--clay-card); margin-bottom: 2.5rem;">
        <div style="margin-bottom: 1.5rem;">
            <div style="display: inline-block; background: #f1f5f9; color: #334155; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.25rem 0.75rem; border-radius: 9999px; margin-bottom: 0.5rem; border: 1px solid #e2e8f0;">
                Spesialisasi & Fokus Rekayasa
            </div>
            <h2 style="font-size: 1.6rem; font-weight: 900; color: #0c2340; margin: 0;">
                Keahlian & Pilar Rekayasa Teknologi
            </h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 1.35rem;">
                <div style="font-weight: 800; font-size: 1.05rem; color: #0c2340; margin-bottom: 0.5rem;">
                    Cyber Defense & Security Research
                </div>
                <p style="font-size: 0.885rem; color: #64748b; line-height: 1.6; margin: 0;">
                    Penyelidikan celah keamanan, mitigasi kebocoran data, audit kelemahan sistem, serta penerapan prinsip <em>Responsible Vulnerability Disclosure</em> berstandar nasional.
                </p>
            </div>

            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 1.35rem;">
                <div style="font-weight: 800; font-size: 1.05rem; color: #0c2340; margin-bottom: 0.5rem;">
                    Software Architecture
                </div>
                <p style="font-size: 0.885rem; color: #64748b; line-height: 1.6; margin: 0;">
                    Perancangan arsitektur perangkat lunak skala tinggi, desain modular, rekayasa backend kokoh, integrasi REST API, dan optimalisasi skalabilitas sistem.
                </p>
            </div>

            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 1.35rem;">
                <div style="font-weight: 800; font-size: 1.05rem; color: #0c2340; margin-bottom: 0.5rem;">
                    AI & Modern Systems Engineering
                </div>
                <p style="font-size: 0.885rem; color: #64748b; line-height: 1.6; margin: 0;">
                    Eksplorasi kecerdasan buatan, otomasi sistem terdistribusi, pengolahan pipeline data terpadu, dan pengembangan aplikasi berbasis platform web modern.
                </p>
            </div>
        </div>
    </div>

    <!-- Hubungan dengan Platform UKM -->
    <div style="background: linear-gradient(135deg, #0c2340, #0f172a); border-radius: 20px; padding: 2.25rem; color: #ffffff; box-shadow: 0 10px 25px rgba(12, 35, 64, 0.2);">
        <div style="max-width: 800px;">
            <div style="font-size: 0.75rem; font-weight: 800; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                Arsitektur Portal UKM
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 900; margin: 0 0 0.85rem; line-height: 1.3; color: #ffffff;">
                Pengembangan Ekosistem Digital UKM Ilmu Komputer
            </h3>
            <p style="font-size: 0.95rem; color: #cbd5e1; line-height: 1.7; margin-bottom: 1.5rem;">
                Portal UKM Ilmu Komputer UBBG dirancang sebagai pusat manajemen terpadu yang memadukan rekrutmen anggota, presensi berbasis kode akses harian, sertifikasi digital terverifikasi, etalase karya mahasiswa, serta kontrol berjenjang bagi pengurus divisi dan pembina kampus.
            </p>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('home') }}" class="btn btn-sm" style="background: #0284c7; color: #ffffff; border-radius: 9999px; font-weight: 800; text-decoration: none; padding: 0.55rem 1.25rem;">
                    Jelajahi Portal Utama
                </a>
                <a href="{{ route('divisions.index') }}" class="btn btn-sm" style="background: rgba(255, 255, 255, 0.1); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 9999px; font-weight: 700; text-decoration: none; padding: 0.55rem 1.25rem;">
                    Lihat 4 Bidang Divisi
                </a>
            </div>
        </div>
    </div>
</div>

<style>
@media (min-width: 768px) {
    .dev-hero-flex {
        flex-direction: row !important;
        align-items: flex-start !important;
    }
    .dev-hero-text {
        text-align: left !important;
    }
}
</style>
@endsection
