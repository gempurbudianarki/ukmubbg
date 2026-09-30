<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - UKM Ilmu Komputer')</title>
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
            background: var(--bg-body);
        }
        .admin-sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid var(--slate-200);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            flex-shrink: 0;
        }
        .admin-brand {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid var(--slate-100);
        }
        .admin-nav {
            list-style: none;
            padding: 1rem 0.75rem;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--slate-600);
            transition: var(--transition);
        }
        .admin-nav-link:hover, .admin-nav-link.active {
            background: var(--slate-100);
            color: var(--slate-900);
        }
        .admin-main-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .admin-header {
            height: 64px;
            background: #ffffff;
            border-bottom: 1px solid var(--slate-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }
        .admin-content-body {
            padding: 2rem;
            flex: 1;
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
            padding: 0.75rem 1rem;
            background: var(--slate-50);
            font-weight: 700;
            color: var(--slate-500);
            text-transform: uppercase;
            font-size: 0.75rem;
            border-bottom: 1px solid var(--slate-200);
        }
        .table td {
            padding: 1rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
        }
        .table tbody tr:hover {
            background: var(--slate-50);
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <div class="brand-badge-icon" style="width: 32px; height: 32px; border-radius: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <div style="font-weight: 800; font-size: 0.95rem; color: var(--slate-900);">UKM CMS</div>
                    <div style="font-size: 0.725rem; color: var(--slate-400); font-weight: 600;">
                        {{ auth()->user()->isSuperAdmin() ? 'Super Administrator' : auth()->user()->division->name }}
                    </div>
                </div>
            </div>

            <ul class="admin-nav">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Pusat Pendaftaran</span>
                    </a>
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

                    <li>
                        <a href="{{ route('admin.divisions.index') }}" class="admin-nav-link {{ request()->routeIs('admin.divisions.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>Biodata 4 Divisi</span>
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

        <!-- Main Content -->
        <div class="admin-main-wrap">
            <header class="admin-header">
                <div style="font-weight: 800; font-size: 1.1rem; color: var(--slate-900);">
                    @yield('page_title', 'Dashboard')
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
</body>
</html>
