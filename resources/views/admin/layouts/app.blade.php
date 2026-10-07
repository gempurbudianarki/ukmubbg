<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - UKM Ilmu Komputer')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
            background: var(--bg-body, #eef3f8);
        }
        .admin-sidebar {
            width: 275px;
            background: #ffffff;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.04);
            border-right: none;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            flex-shrink: 0;
            z-index: 50;
        }
        .admin-brand {
            padding: 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .admin-nav {
            list-style: none;
            padding: 1rem 0.85rem;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }
        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--slate-600);
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .admin-nav-link:hover {
            background: #f1f5f9;
            color: #2563eb;
            transform: translateX(3px);
        }
        .admin-nav-link.active {
            background: #eff6ff;
            color: #2563eb;
            box-shadow: var(--clay-pill);
            font-weight: 700;
        }
        .admin-main-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: var(--bg-body, #eef3f8);
        }
        .admin-header {
            height: 72px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .admin-content-body {
            padding: 2.25rem 2rem 4rem;
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.875rem;
        }
        .table th {
            padding: 1rem 1.25rem;
            background: #f8fafc;
            font-weight: 800;
            color: var(--slate-500);
            text-transform: uppercase;
            font-size: 0.725rem;
            letter-spacing: 0.05em;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .table td {
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid rgba(241, 245, 249, 0.8);
            vertical-align: middle;
        }
        .table tbody tr:hover {
            background: #f8fafc;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo-img" style="height: 38px; width: auto; object-fit: contain;">
                <div>
                    <div style="font-weight: 800; font-size: 0.95rem; color: var(--slate-900);">UKM ILKOM CMS</div>
                    <div style="font-size: 0.725rem; color: var(--slate-400); font-weight: 600;">
                        {{ auth()->user()->isSuperAdmin() ? 'Super Administrator' : (auth()->user()->division->name ?? 'Admin Divisi') }}
                    </div>
                </div>
            </div>

            <ul class="admin-nav">
                <li style="padding: 0.75rem 1rem 0.35rem; font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--slate-400); display: flex; align-items: center; gap: 0.4rem;">
                    <i class="fas fa-graduation-cap" style="color: #0284c7;"></i>
                    <span>Akademik & Keanggotaan</span>
                </li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Ringkasan Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.recruitment.index') }}" class="admin-nav-link {{ request()->routeIs('admin.recruitment.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>Pusat Pendaftaran</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.members.index') }}" class="admin-nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Anggota UKM</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.attendance.index') }}" class="admin-nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span>Presensi & BAP</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.announcements.index') }}" class="admin-nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        <span>Papan Pengumuman</span>
                    </a>
                </li>

                <!-- Kategori 2: Publikasi & Portofolio -->
                <li style="padding: 1.15rem 1rem 0.35rem; font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--slate-400); display: flex; align-items: center; gap: 0.4rem;">
                    <i class="fas fa-rocket" style="color: #10b981;"></i>
                    <span>Publikasi & Portofolio</span>
                </li>

                <li>
                    <a href="{{ route('admin.projects.index') }}" class="admin-nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                        <span>Karya Mahasiswa</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.events.index') }}" class="admin-nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Agenda & Event</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.posts.index') }}" class="admin-nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <span>Artikel & Publikasi</span>
                    </a>
                </li>

                @if (auth()->user()->isSuperAdmin())
                    <li>
                        <a href="{{ route('admin.officers.index') }}" class="admin-nav-link {{ request()->routeIs('admin.officers.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Struktur Pengurus</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.certificates.index') }}" class="admin-nav-link {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            <span>E-Sertifikat</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.galleries.index') }}" class="admin-nav-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Galeri Dokumentasi</span>
                        </a>
                    </li>
                @endif

                <!-- Kategori 3: Organisasi & Pengaturan -->
                <li style="padding: 1.15rem 1rem 0.35rem; font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--slate-400); display: flex; align-items: center; gap: 0.4rem;">
                    <i class="fas fa-sitemap" style="color: #8b5cf6;"></i>
                    <span>Organisasi & Pengaturan</span>
                </li>

                <li>
                    <a href="{{ route('admin.divisions.index') }}" class="admin-nav-link {{ request()->routeIs('admin.divisions.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Biodata 4 Divisi</span>
                    </a>
                </li>

                @if (auth()->user()->isSuperAdmin())
                    <li>
                        <a href="{{ route('admin.recruitment.settings') }}" class="admin-nav-link {{ request()->routeIs('admin.recruitment.settings') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Pengaturan Gelombang</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Manajemen Pengguna</span>
                        </a>
                    </li>
                @endif
            </ul>

            <div style="padding: 1.25rem; border-top: 1px solid var(--slate-100);">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-outline btn-sm" style="width: 100%; margin-bottom: 0.5rem;">
                    Lihat Web Publik &rarr;
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">
                        Keluar (Logout)
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Sidebar Backdrop -->
        <div id="adminSidebarBackdrop" class="admin-sidebar-backdrop" onclick="toggleAdminSidebar()"></div>

        <!-- Main Content -->
        <div class="admin-main-wrap">
            <header class="admin-header">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <button type="button" class="admin-mobile-toggle" onclick="toggleAdminSidebar()" aria-label="Buka Navigasi">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div style="font-weight: 800; font-size: 1.1rem; color: var(--slate-900);">
                        @yield('page_title', 'Dashboard')
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div style="text-align: right;">
                        <div style="font-size: 0.875rem; font-weight: 700; color: var(--slate-900);">
                            {{ auth()->user()->name }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--slate-400);">
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                    <div class="avatar-round" style="width: 36px; height: 36px; background: var(--slate-900); color: #ffffff;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                </div>
            </header>

            <main class="admin-content-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.querySelector('.admin-sidebar');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            if (sidebar && backdrop) {
                sidebar.classList.toggle('open');
                backdrop.classList.toggle('show');
            }
        }
    </script>
</body>
</html>
