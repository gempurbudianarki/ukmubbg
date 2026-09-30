<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand Column -->
            <div>
                <div class="footer-brand">
                    <div class="brand-badge-icon" style="width: 32px; height: 32px; border-radius: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span>UKM ILMU KOMPUTER</span>
                </div>
                <p style="font-size: 0.875rem; color: var(--slate-600); line-height: 1.65; margin-bottom: 1.25rem;">
                    Pusat riset, rekayasa teknologi, eksplorasi multimedia interaktif, sistem IoT, dan pertahanan siber bagi mahasiswa Program Studi Ilmu Komputer.
                </p>
                <div style="font-size: 0.8rem; color: var(--slate-500); line-height: 1.6;">
                    Gedung Laboratorium Komputer Terpadu Lt. 3<br>
                    Fakultas Ilmu Komputer & Teknologi Informasi
                </div>
            </div>

            <!-- Divisi Column -->
            <div>
                <h4 class="footer-heading">4 Bidang Divisi</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('divisions.show', 'pemrograman') }}">Pemrograman (Software)</a></li>
                    <li><a href="{{ route('divisions.show', 'multimedia') }}">Multimedia (Design/VFX)</a></li>
                    <li><a href="{{ route('divisions.show', 'iot') }}">Internet of Things (IoT)</a></li>
                    <li><a href="{{ route('divisions.show', 'cyber-security') }}">Cyber Security (Ethical Hack)</a></li>
                </ul>
            </div>

            <!-- Navigasi Column -->
            <div>
                <h4 class="footer-heading">Aktivitas & Ekosistem</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('projects.index') }}">Karya Mahasiswa</a></li>
                    <li><a href="{{ route('events.index') }}">Agenda & Workshop</a></li>
                    <li><a href="{{ route('officers.index') }}">Struktur Pengurus</a></li>
                    <li><a href="{{ route('galleries.index') }}">Galeri Momen</a></li>
                    <li><a href="{{ route('certificates.verify') }}">Verifikasi E-Sertifikat</a></li>
                    <li><a href="{{ route('posts.index') }}">Publikasi & Riset</a></li>
                </ul>
            </div>

            <!-- Oprec Column -->
            <div>
                <h4 class="footer-heading">Pendaftaran Terpadu</h4>
                <p style="font-size: 0.85rem; color: var(--slate-600); margin-bottom: 1rem; line-height: 1.6;">
                    Satu pintu pendaftaran untuk seluruh divisi spesialisasi. Kembangkan kompetensi teknologimu bersama para mentor berprestasi.
                </p>
                <a href="{{ route('recruitment.index') }}" class="btn btn-primary btn-sm" style="width: 100%; margin-bottom: 0.5rem;">
                    Daftar Sekarang &rarr;
                </a>
                <a href="{{ route('recruitment.status') }}" class="btn btn-glass btn-sm" style="width: 100%;">
                    Cek Status Pendaftaran
                </a>
            </div>
        </div>

        <div class="footer-bottom">
            <div>
                &copy; {{ date('Y') }} UKM Ilmu Komputer. Hak Cipta Dilindungi Undang-Undang.
            </div>
            <div style="display: flex; gap: 1.5rem; align-items: center;">
                <a href="{{ route('login') }}">Portal Pengurus CMS</a>
                <span>&bull;</span>
                <a href="{{ route('certificates.verify') }}">Cek Validitas Sertifikat</a>
                <span>&bull;</span>
                <span>Prodi Ilmu Komputer</span>
            </div>
        </div>
    </div>
</footer>
