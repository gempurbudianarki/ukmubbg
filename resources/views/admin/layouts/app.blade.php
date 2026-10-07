<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - UKM Ilmu Komputer')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
            margin: 0;
            padding: 0;
        }
        .admin-layout {
            display: flex;
            min-height: 100vh;
            background: var(--bg-body, #eef3f8);
            width: 100%;
            position: relative;
        }
        .admin-sidebar {
            width: 275px;
            background: #ffffff;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.04);
            border-right: 1px solid rgba(226, 232, 240, 0.85);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            height: 100vh;
            z-index: 50;
            overflow: hidden;
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .admin-layout.sidebar-closed .admin-sidebar {
            transform: translateX(-275px);
        }
        .admin-brand {
            padding: 1.15rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .admin-nav {
            list-style: none;
            padding: 0.65rem 0.85rem;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        .admin-nav::-webkit-scrollbar {
            width: 4px;
        }
        .admin-nav::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.65rem 0.95rem;
            border-radius: 12px;
            font-size: 0.835rem;
            font-weight: 600;
            color: var(--slate-600);
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            position: relative;
        }
        .admin-nav-link i {
            font-size: 0.95rem;
            width: 20px;
            text-align: center;
            color: var(--slate-400);
            transition: color 0.2s ease;
        }
        .admin-nav-link:hover {
            background: #f8fafc;
            color: #2563eb;
            transform: translateX(2px);
        }
        .admin-nav-link:hover i {
            color: #2563eb;
        }
        .admin-nav-link.active {
            background: #ffffff;
            color: #2563eb;
            box-shadow: var(--clay-pill);
            border: 1px solid rgba(226, 232, 240, 0.9);
            font-weight: 700;
        }
        .admin-nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 25%;
            bottom: 25%;
            width: 3.5px;
            border-radius: 0 4px 4px 0;
            background: #2563eb;
        }
        .admin-nav-link.active i {
            color: #2563eb;
        }
        .admin-main-wrap {
            margin-left: 275px;
            width: calc(100% - 275px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: var(--bg-body, #eef3f8);
            transition: margin-left 0.28s cubic-bezier(0.4, 0, 0.2, 1), width 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .admin-layout.sidebar-closed .admin-main-wrap {
            margin-left: 0;
            width: 100%;
        }
        .admin-sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            z-index: 45;
            transition: opacity 0.2s ease;
        }
        .admin-header {
            height: 70px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1.5px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .admin-content-body {
            padding: 2rem 2rem 4rem;
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
        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15) !important;
            }
            .admin-layout.sidebar-mobile-open .admin-sidebar {
                transform: translateX(0) !important;
            }
            .admin-sidebar-backdrop.show {
                display: block;
            }
            .admin-main-wrap {
                margin-left: 0 !important;
                width: 100% !important;
            }
            .admin-header {
                padding: 0 1.25rem;
            }
            .admin-content-body {
                padding: 1.25rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="admin-layout" id="adminLayout">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-brand">
                <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 0.85rem; text-decoration: none;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo-img" style="height: 36px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 5px rgba(0,0,0,0.1));">
                    <div>
                        <div style="font-weight: 800; font-size: 0.925rem; color: var(--slate-900); letter-spacing: -0.01em;">UKM ILKOM CMS</div>
                        <div style="font-size: 0.68rem; color: #2563eb; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                            {{ auth()->user()->isSuperAdmin() ? 'SUPER ADMINISTRATOR' : (auth()->user()->division->name ?? 'ADMIN DIVISI') }}
                        </div>
                    </div>
                </a>
                <button type="button" class="sidebar-collapse-btn" onclick="toggleAdminSidebar()" title="Sembunyikan Sidebar" style="background: #f8fafc; border: 1px solid #e2e8f0; width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; box-shadow: var(--clay-pill);">
                    <i class="fas fa-angles-left"></i>
                </button>
            </div>

            <!-- Mini Profile Badge -->
            <div class="admin-profile-badge">
                <div class="admin-profile-avatar">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    @endif
                </div>
                <div style="min-width: 0; flex: 1;">
                    <div style="font-weight: 800; font-size: 0.825rem; color: var(--slate-900); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.25;">
                        {{ auth()->user()->name }}
                    </div>
                    <div style="font-size: 0.72rem; color: var(--slate-500); font-family: var(--font-mono); margin-top: 0.1rem;">
                        {{ auth()->user()->email }}
                    </div>
                </div>
            </div>

            <!-- Navigation Links Structured in 3 Clusters -->
            <ul class="admin-nav">
                <!-- Cluster 1: Akademik & Keanggotaan -->
                <li class="admin-cluster-heading">
                    <i class="fas fa-graduation-cap" style="color: #0284c7;"></i>
                    <span>Akademik & Keanggotaan</span>
                </li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        <span>Ringkasan Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.recruitment.index') }}" class="admin-nav-link {{ request()->routeIs('admin.recruitment.*') && !request()->routeIs('admin.recruitment.settings') ? 'active' : '' }}">
                        <i class="fas fa-user-plus"></i>
                        <span>Pusat Pendaftaran</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.members.index') }}" class="admin-nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Anggota UKM</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.attendance.index') }}" class="admin-nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Presensi & BAP</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.announcements.index') }}" class="admin-nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                        <i class="fas fa-bullhorn"></i>
                        <span>Papan Pengumuman</span>
                    </a>
                </li>

                <!-- Cluster 2: Publikasi & Portofolio -->
                <li class="admin-cluster-heading" style="margin-top: 0.4rem;">
                    <i class="fas fa-rocket" style="color: #10b981;"></i>
                    <span>Publikasi & Portofolio</span>
                </li>
                <li>
                    <a href="{{ route('admin.projects.index') }}" class="admin-nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                        <i class="fas fa-laptop-code"></i>
                        <span>Karya Mahasiswa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.events.index') }}" class="admin-nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-days"></i>
                        <span>Agenda & Event</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.posts.index') }}" class="admin-nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i>
                        <span>Artikel & Publikasi</span>
                    </a>
                </li>
                @if (auth()->user()->isSuperAdmin())
                    <li>
                        <a href="{{ route('admin.certificates.index') }}" class="admin-nav-link {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                            <i class="fas fa-certificate"></i>
                            <span>E-Sertifikat</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.galleries.index') }}" class="admin-nav-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                            <i class="fas fa-images"></i>
                            <span>Galeri Dokumentasi</span>
                        </a>
                    </li>
                @endif

                <!-- Cluster 3: Tata Kelola & Pengaturan -->
                <li class="admin-cluster-heading" style="margin-top: 0.4rem;">
                    <i class="fas fa-sliders" style="color: #8b5cf6;"></i>
                    <span>Tata Kelola & Pengaturan</span>
                </li>
                <li>
                    <a href="{{ route('admin.divisions.index') }}" class="admin-nav-link {{ request()->routeIs('admin.divisions.*') ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i>
                        <span>Biodata 4 Divisi</span>
                    </a>
                </li>
                @if (auth()->user()->isSuperAdmin())
                    <li>
                        <a href="{{ route('admin.officers.index') }}" class="admin-nav-link {{ request()->routeIs('admin.officers.*') ? 'active' : '' }}">
                            <i class="fas fa-sitemap"></i>
                            <span>Struktur Pengurus</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.recruitment.settings') }}" class="admin-nav-link {{ request()->routeIs('admin.recruitment.settings') ? 'active' : '' }}">
                            <i class="fas fa-clock-rotate-left"></i>
                            <span>Pengaturan Gelombang</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-user-shield"></i>
                            <span>Manajemen Pengguna</span>
                        </a>
                    </li>
                @endif
            </ul>

            <!-- Footer Sidebar -->
            <div style="padding: 1rem 1.15rem; border-top: 1px solid rgba(226, 232, 240, 0.9); background: #ffffff; display: flex; flex-direction: column; gap: 0.4rem;">
                <a href="{{ route('home') }}" target="_blank" class="admin-quick-btn" style="width: 100%; justify-content: center; font-size: 0.8rem; padding: 0.55rem 1rem;">
                    <i class="fas fa-arrow-up-right-from-square"></i>
                    <span>Kunjungi Web Publik</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" style="width: 100%; border-radius: 9999px; box-shadow: var(--clay-btn); font-size: 0.8rem; padding: 0.55rem 1rem; display: flex; align-items: center; justify-content: center; gap: 0.4rem;">
                        <i class="fas fa-arrow-right-from-bracket"></i>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Sidebar Backdrop -->
        <div id="adminSidebarBackdrop" class="admin-sidebar-backdrop" onclick="toggleAdminSidebar()"></div>

        <!-- Main Content Area -->
        <div class="admin-main-wrap">
            <header class="admin-header">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button type="button" class="admin-sidebar-toggle-btn" onclick="toggleAdminSidebar()" aria-label="Toggle Navigasi Sidebar" title="Buka/Tutup Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <div class="admin-breadcrumb">
                            <a href="{{ route('admin.dashboard') }}"><i class="fas fa-house"></i> Panel</a>
                            <i class="fas fa-chevron-right" style="font-size: 0.65rem;"></i>
                            <span class="current">@yield('page_title', 'Dashboard')</span>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="text-align: right;">
                            <div style="font-size: 0.875rem; font-weight: 800; color: var(--slate-900);">
                                {{ auth()->user()->name }}
                            </div>
                            <div style="font-size: 0.725rem; color: #2563eb; font-weight: 700;">
                                {{ auth()->user()->isSuperAdmin() ? 'Super Admin' : (auth()->user()->division->name ?? 'Admin') }}
                            </div>
                        </div>
                        <div class="admin-profile-avatar" style="width: 38px; height: 38px; font-size: 0.875rem;">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            @endif
                        </div>
                    </div>
                </div>
            </header>

            <main class="admin-content-body">
                @if (session('success'))
                    <div class="alert alert-success" style="box-shadow: var(--clay-pill); border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-circle-check" style="font-size: 1.15rem; color: #059669;"></i>
                        <span style="font-weight: 600;">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error" style="box-shadow: var(--clay-pill); border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-circle-exclamation" style="font-size: 1.15rem; color: #dc2626;"></i>
                        <span style="font-weight: 600;">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleAdminSidebar() {
            const layout = document.getElementById('adminLayout');
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            
            if (window.innerWidth <= 992) {
                // Mobile Drawer Toggle
                layout.classList.toggle('sidebar-mobile-open');
                if (backdrop) {
                    backdrop.classList.toggle('show');
                }
            } else {
                // Desktop Collapse Toggle
                layout.classList.toggle('sidebar-closed');
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
