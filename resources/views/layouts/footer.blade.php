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
                    <div style="display: flex; align-items: flex-start; gap: 0.65rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-university" style="color: #0284c7; font-size: 1rem; margin-top: 0.15rem;"></i>
                        <div>
                            <strong style="color: #0c2340; display: block; font-size: 0.875rem;">Fakultas Sains, Teknologi, dan Ilmu Kesehatan (FSTIK)</strong>
                            <span style="color: #64748b; font-weight: 600;">Universitas Bina Bangsa Getsempena (UBBG)</span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 0.65rem; padding-top: 0.5rem; border-top: 1px dashed #cbd5e1;">
                        <i class="fas fa-location-dot" style="color: #ef4444; font-size: 0.95rem; margin-top: 0.15rem;"></i>
                        <span style="color: #64748b;">
                            Gedung Laboratorium Komputer Terpadu, Kampus UBBG.<br>
                            Jl. Tanggul Krueng Lamnyong No. 34, Rukoh, Kec. Syiah Kuala, Kota Banda Aceh, 23112
                        </span>
                    </div>
                </div>
            </div>

            <!-- Divisi Column -->
            <div>
                <h4 class="footer-heading">
                    <i class="fas fa-layer-group" style="color: #0284c7; margin-right: 0.35rem;"></i>
                    4 Bidang Divisi
                </h4>
                <ul class="footer-links">
                    <li>
                        <a href="{{ route('divisions.show', 'pemrograman') }}">
                            <i class="fas fa-code" style="color: #0284c7; font-size: 0.75rem; margin-right: 0.35rem;"></i>
                            Pemrograman (Software)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('divisions.show', 'multimedia') }}">
                            <i class="fas fa-palette" style="color: #7c3aed; font-size: 0.75rem; margin-right: 0.35rem;"></i>
                            Multimedia (Design/VFX)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('divisions.show', 'iot') }}">
                            <i class="fas fa-microchip" style="color: #d97706; font-size: 0.75rem; margin-right: 0.35rem;"></i>
                            Internet of Things (IoT)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('divisions.show', 'cyber-security') }}">
                            <i class="fas fa-shield-halved" style="color: #059669; font-size: 0.75rem; margin-right: 0.35rem;"></i>
                            Cyber Security (Defense)
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Navigasi Column -->
            <div>
                <h4 class="footer-heading">
                    <i class="fas fa-compass" style="color: #0284c7; margin-right: 0.35rem;"></i>
                    Aktivitas & Ekosistem
                </h4>
                <ul class="footer-links">
                    <li>
                        <a href="{{ route('projects.index') }}">
                            <i class="fas fa-rocket" style="color: #0284c7; font-size: 0.75rem; margin-right: 0.35rem;"></i>
                            Karya Mahasiswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('events.index') }}">
                            <i class="fas fa-calendar-check" style="color: #10b981; font-size: 0.75rem; margin-right: 0.35rem;"></i>
                            Agenda & Workshop
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('officers.index') }}">
                            <i class="fas fa-sitemap" style="color: #6366f1; font-size: 0.75rem; margin-right: 0.35rem;"></i>
                            Struktur Pengurus
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('galleries.index') }}">
                            <i class="fas fa-images" style="color: #f59e0b; font-size: 0.75rem; margin-right: 0.35rem;"></i>
                            Galeri Momen
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('certificates.verify') }}">
                            <i class="fas fa-certificate" style="color: #0284c7; font-size: 0.75rem; margin-right: 0.35rem;"></i>
                            Verifikasi E-Sertifikat
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Oprec Column -->
            <div>
                <h4 class="footer-heading">
                    <i class="fas fa-user-plus" style="color: #0284c7; margin-right: 0.35rem;"></i>
                    Pendaftaran Terpadu
                </h4>
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 1.25rem; box-shadow: var(--clay-card);">
                    <p style="font-size: 0.825rem; color: #64748b; margin: 0 0 1rem; line-height: 1.55;">
                        Satu pintu pendaftaran untuk seluruh divisi spesialisasi UKM. Asah potensimu bersama mentor berpengalaman di kampus UBBG.
                    </p>
                    <a href="{{ route('recruitment.index') }}" class="btn btn-primary btn-sm" style="width: 100%; border-radius: 9999px; font-weight: 800; padding: 0.65rem 1rem; margin-bottom: 0.6rem; display: flex; align-items: center; justify-content: center; gap: 0.4rem; background: linear-gradient(135deg, #0284c7, #0369a1); border: none; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);">
                        <span>Daftar Sekarang</span>
                        <i class="fas fa-arrow-right" style="font-size: 0.75rem;"></i>
                    </a>
                    <a href="{{ route('recruitment.status') }}" class="btn btn-outline btn-sm" style="width: 100%; border-radius: 9999px; font-weight: 700; padding: 0.55rem 1rem; display: flex; align-items: center; justify-content: center; gap: 0.4rem; background: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: var(--clay-pill); color: #334155;">
                        <i class="fas fa-magnifying-glass" style="font-size: 0.75rem; color: #0284c7;"></i>
                        <span>Cek Status Pendaftaran</span>
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
                    <i class="fas fa-arrow-right-to-bracket" style="margin-right: 0.25rem;"></i> Portal Pengurus CMS
                </a>
                <span style="color: #cbd5e1;">&bull;</span>
                <a href="{{ route('certificates.verify') }}" style="color: #475569; text-decoration: none;">
                    Cek Validitas Sertifikat
                </a>
                <span style="color: #cbd5e1;">&bull;</span>
                <span style="color: #0c2340; font-weight: 700;">
                    Prodi Ilmu Komputer &bull; FSTIK UBBG
                </span>
            </div>
        </div>
    </div>
</footer>
