<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand & Campus Affiliation Column -->
            <div>
                <div class="footer-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="UKM Ilmu Komputer Logo" class="brand-logo-img" style="height: 46px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 6px rgba(0,0,0,0.1));">
                    <div>
                        <div style="font-weight: 900; font-size: 1.15rem; color: #0c2340; letter-spacing: -0.01em; line-height: 1.2;">
                            UKM ILMU KOMPUTER
                        </div>
                        <div style="font-size: 0.725rem; color: #0284c7; font-weight: 800; letter-spacing: 0.04em;">
                            UNIVERSITAS BINA BANGSA GETSEMPENA
                        </div>
                    </div>
                </div>

                <p style="font-size: 0.885rem; color: #475569; line-height: 1.65; margin-bottom: 1.25rem;">
                    Pusat riset terapan, rekayasa teknologi perangkat lunak, eksplorasi multimedia kreatif, sistem otomasi IoT, dan ketahanan siber bagi mahasiswa <strong>Program Studi Ilmu Komputer</strong>.
                </p>

                <!-- Campus Identity Card (Debossed Clay) -->
                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 1.15rem; box-shadow: var(--clay-debossed); font-size: 0.825rem; color: #475569; line-height: 1.6;">
                    <div style="margin-bottom: 0.5rem;">
                        <strong style="color: #0c2340; display: block; font-size: 0.875rem;">Fakultas Sains, Teknologi, dan Ilmu Kesehatan (FSTIK)</strong>
                        <span style="color: #64748b; font-weight: 600;">Universitas Bina Bangsa Getsempena (UBBG)</span>
                    </div>
                    <div style="padding-top: 0.5rem; border-top: 1px dashed #cbd5e1; color: #64748b;">
                        Gedung Laboratorium Komputer Terpadu, Kampus UBBG.<br>
                        Jl. Tanggul Krueng Lamnyong No. 34, Rukoh, Kec. Syiah Kuala, Kota Banda Aceh, 23112
                    </div>
                </div>
            </div>

            <!-- Divisi Column -->
            <div>
                <h4 class="footer-heading">
                    4 Bidang Divisi
                </h4>
                <ul class="footer-links">
                    <li>
                        <a href="{{ route('divisions.show', 'pemrograman') }}">
                            Pemrograman (Software)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('divisions.show', 'multimedia') }}">
                            Multimedia (Design/VFX)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('divisions.show', 'iot') }}">
                            Internet of Things (IoT)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('divisions.show', 'cyber-security') }}">
                            Cyber Security (Defense)
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Navigasi Column -->
            <div>
                <h4 class="footer-heading">
                    Aktivitas & Ekosistem
                </h4>
                <ul class="footer-links">
                    <li>
                        <a href="{{ route('projects.index') }}">
                            Karya Mahasiswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('events.index') }}">
                            Agenda & Workshop
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('officers.index') }}">
                            Struktur Pengurus
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('galleries.index') }}">
                            Galeri Momen
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('certificates.verify') }}">
                            Verifikasi E-Sertifikat
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Oprec Column -->
            <div>
                <h4 class="footer-heading">
                    Pendaftaran Terpadu
                </h4>
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 1.25rem; box-shadow: var(--clay-card);">
                    <p style="font-size: 0.825rem; color: #64748b; margin: 0 0 1rem; line-height: 1.55;">
                        Satu pintu pendaftaran untuk seluruh divisi spesialisasi UKM. Asah potensimu bersama mentor berpengalaman di kampus UBBG.
                    </p>
                    <a href="{{ route('recruitment.index') }}" class="btn btn-primary btn-sm" style="width: 100%; border-radius: 9999px; font-weight: 800; padding: 0.65rem 1rem; margin-bottom: 0.6rem; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0284c7, #0369a1); border: none; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('recruitment.status') }}" class="btn btn-outline btn-sm" style="width: 100%; border-radius: 9999px; font-weight: 700; padding: 0.55rem 1rem; display: flex; align-items: center; justify-content: center; background: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); color: #334155;">
                        Cek Status Pendaftaran
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="footer-bottom">
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <span>&copy; {{ date('Y') }} <strong>UKM Ilmu Komputer</strong>. Hak Cipta Dilindungi Undang-Undang.</span>
            </div>
            <div style="display: flex; gap: 1.25rem; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('login') }}" style="color: #0284c7; font-weight: 700; text-decoration: none;">
                    Portal Pengurus CMS
                </a>
                <span style="color: #cbd5e1;">|</span>
                <a href="{{ route('certificates.verify') }}" style="color: #475569; text-decoration: none;">
                    Cek Validitas Sertifikat
                </a>
                <span style="color: #cbd5e1;">|</span>
                <span style="color: #0c2340; font-weight: 700;">
                    Prodi Ilmu Komputer &bull; FSTIK UBBG
                </span>
            </div>
        </div>
    </div>
</footer>
