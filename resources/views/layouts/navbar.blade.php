<header class="navbar-sticky">
    <div class="container navbar-inner">
        <a href="{{ route('home') }}" class="nav-brand">
            <div class="brand-badge-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <span>UKM ILMU KOMPUTER</span>
        </a>

        <!-- Desktop Menu -->
        <ul class="nav-menu">
            <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
            <li><a href="{{ route('divisions.index') }}" class="nav-link {{ request()->routeIs('divisions.*') ? 'active' : '' }}">Divisi</a></li>
            <li><a href="{{ route('projects.index') }}" class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">Karya</a></li>
            <li><a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">Agenda</a></li>
            <li><a href="{{ route('officers.index') }}" class="nav-link {{ request()->routeIs('officers.*') ? 'active' : '' }}">Pengurus</a></li>
            <li><a href="{{ route('galleries.index') }}" class="nav-link {{ request()->routeIs('galleries.*') ? 'active' : '' }}">Galeri</a></li>
            <li><a href="{{ route('certificates.verify') }}" class="nav-link {{ request()->routeIs('certificates.*') ? 'active' : '' }}">Verifikasi</a></li>
            <li><a href="{{ route('posts.index') }}" class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}">Riset</a></li>
        </ul>

        <!-- Action Buttons -->
        <div class="nav-actions">
            <a href="{{ route('recruitment.index') }}" class="btn btn-primary btn-sm">
                <span class="badge-pulse"></span>
                <span>Oprec</span>
            </a>
            @auth
                <a href="{{ route('admin.dashboard') }}" class="btn btn-glass btn-sm">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-glass btn-sm">Masuk</a>
            @endauth

            <!-- Mobile Toggle -->
            <button class="nav-mobile-toggle" aria-label="Buka Menu" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer Navigation -->
    <div class="mobile-nav">
        <ul class="mobile-nav-links">
            <li><a href="{{ route('home') }}">Beranda</a></li>
            <li><a href="{{ route('divisions.index') }}">Divisi Keahlian</a></li>
            <li><a href="{{ route('projects.index') }}">Karya Mahasiswa</a></li>
            <li><a href="{{ route('events.index') }}">Agenda & Workshop</a></li>
            <li><a href="{{ route('officers.index') }}">Struktur Pengurus</a></li>
            <li><a href="{{ route('galleries.index') }}">Galeri Momen</a></li>
            <li><a href="{{ route('certificates.verify') }}">Verifikasi Sertifikat</a></li>
            <li><a href="{{ route('posts.index') }}">Publikasi & Riset</a></li>
            <li><a href="{{ route('recruitment.status') }}">Cek Status Oprec</a></li>
            <li style="padding-top: 0.5rem; border-top: 1px solid var(--slate-100); display: flex; gap: 0.5rem;">
                <a href="{{ route('recruitment.index') }}" class="btn btn-primary btn-sm" style="flex: 1; text-align: center;">Daftar Anggota</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm" style="flex: 1; text-align: center;">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm" style="flex: 1; text-align: center;">Masuk</a>
                @endauth
            </li>
        </ul>
    </div>
</header>
