<header class="navbar-sticky">
    <div class="container navbar-inner">
        <a href="{{ route('home') }}" class="nav-brand">
            <img src="{{ asset('images/logo.png') }}" alt="UKM Ilmu Komputer Logo" class="brand-logo-img">
            <span>UKM ILMU KOMPUTER <small style="display:none">UKM ILKOM</small></span>
        </a>

        <!-- Desktop Menu -->
        <ul class="nav-menu">
            @if(\App\Models\Setting::get('nav_home', '1') === '1')
                <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_divisions', '1') === '1')
                <li><a href="{{ route('divisions.index') }}" class="nav-link {{ request()->routeIs('divisions.*') ? 'active' : '' }}">Divisi</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_projects', '1') === '1')
                <li><a href="{{ route('projects.index') }}" class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">Karya</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_posts', '1') === '1')
                <li><a href="{{ route('posts.index') }}" class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}">Berita</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_events', '1') === '1')
                <li><a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">Agenda</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_officers', '1') === '1')
                <li><a href="{{ route('officers.index') }}" class="nav-link {{ request()->routeIs('officers.*') ? 'active' : '' }}">Pengurus</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_galleries', '1') === '1')
                <li><a href="{{ route('galleries.index') }}" class="nav-link {{ request()->routeIs('galleries.*') ? 'active' : '' }}">Galeri</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_verify', '1') === '1')
                <li><a href="{{ route('certificates.verify') }}" class="nav-link {{ request()->routeIs('certificates.*') ? 'active' : '' }}">Verifikasi</a></li>
            @endif
        </ul>

        <!-- Action Buttons -->
        <div class="nav-actions">
            @if(\App\Models\Setting::get('nav_recruitment', '1') === '1')
                <a href="{{ route('recruitment.index') }}" class="btn btn-primary btn-sm">
                    <span class="badge-pulse"></span>
                    <span>Oprec</span>
                </a>
            @endif
            @auth
                @if(auth()->user()->isMember())
                    <a href="{{ route('student.dashboard') }}" class="btn btn-glass btn-sm">Portal Mahasiswa</a>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-glass btn-sm">Dashboard Admin</a>
                @endif
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
            @if(\App\Models\Setting::get('nav_home', '1') === '1')
                <li><a href="{{ route('home') }}">Beranda</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_divisions', '1') === '1')
                <li><a href="{{ route('divisions.index') }}">Divisi Keahlian</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_projects', '1') === '1')
                <li><a href="{{ route('projects.index') }}">Karya Mahasiswa</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_posts', '1') === '1')
                <li><a href="{{ route('posts.index') }}">Berita & Artikel</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_events', '1') === '1')
                <li><a href="{{ route('events.index') }}">Agenda & Workshop</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_officers', '1') === '1')
                <li><a href="{{ route('officers.index') }}">Struktur Pengurus</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_galleries', '1') === '1')
                <li><a href="{{ route('galleries.index') }}">Galeri Momen</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_verify', '1') === '1')
                <li><a href="{{ route('certificates.verify') }}">Verifikasi Sertifikat</a></li>
            @endif
            @if(\App\Models\Setting::get('nav_recruitment', '1') === '1')
                <li><a href="{{ route('recruitment.status') }}">Cek Status Oprec</a></li>
            @endif
            <li style="padding-top: 0.75rem; border-top: 1px solid var(--slate-100); display: flex; flex-direction: column; gap: 0.5rem;">
                @if(\App\Models\Setting::get('nav_recruitment', '1') === '1')
                    <a href="{{ route('recruitment.index') }}" class="btn btn-primary btn-sm" style="width: 100%; text-align: center; justify-content: center;">Daftar Anggota (Oprec)</a>
                @endif
                @auth
                    @if(auth()->user()->isMember())
                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline btn-sm" style="width: 100%; text-align: center; justify-content: center;">Buka Portal Mahasiswa</a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm" style="width: 100%; text-align: center; justify-content: center;">Buka Dashboard Admin</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm" style="width: 100%; text-align: center; justify-content: center;">Masuk Akun</a>
                @endauth
            </li>
        </ul>
    </div>
</header>
