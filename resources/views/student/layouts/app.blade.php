<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Mahasiswa') - UKM Ilmu Komputer</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #ffffff;
            --sidebar-hover: #f8fafc;
            --sidebar-text: #475569;
            --sidebar-text-active: #0284c7;
        }

        body {
            background: #f1f5f9;
            color: #0f172a;
            font-family: var(--font-sans, system-ui, -apple-system, sans-serif);
            margin: 0;
            padding: 0;
        }

        .student-layout {
            display: flex;
            min-height: 100vh;
            transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Sidebar Styling - Compact & Sleek Fixed */
        .student-sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            height: 100vh;
            z-index: 50;
            box-shadow: 4px 0 20px rgba(15, 23, 42, 0.03);
            border-right: 1px solid rgba(226, 232, 240, 0.85);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        /* Desktop Collapse State */
        .student-layout.sidebar-closed .student-sidebar {
            transform: translateX(calc(-1 * var(--sidebar-width)));
        }

        .student-brand-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.95rem 1rem 0.95rem 1.15rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .student-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #0f172a;
            min-width: 0;
        }

        .sidebar-collapse-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            box-shadow: var(--clay-pill);
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .sidebar-collapse-btn:hover {
            color: #0284c7;
            background: #ffffff;
            transform: scale(1.05);
        }

        /* Compact Profile Badge */
        .student-profile-badge {
            padding: 0.75rem 0.95rem;
            margin: 0.75rem 0.85rem 0.25rem;
            background: #ffffff;
            box-shadow: var(--clay-card);
            border-radius: 14px;
            border: 1px solid rgba(226, 232, 240, 0.9);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .student-profile-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
            box-shadow: var(--clay-pill);
            flex-shrink: 0;
        }

        /* Nav List */
        .student-nav {
            list-style: none;
            padding: 0.5rem 0.75rem;
            margin: 0;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        .student-nav::-webkit-scrollbar {
            width: 4px;
        }
        .student-nav::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .student-nav-heading {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            font-weight: 800;
            padding: 0.55rem 0.65rem 0.2rem;
        }

        .student-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.58rem 0.85rem;
            border-radius: 11px;
            color: #475569;
            text-decoration: none;
            font-size: 0.835rem;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
        }

        .student-nav-link i {
            font-size: 0.95rem;
            width: 18px;
            text-align: center;
            color: #94a3b8;
            transition: all 0.2s ease;
        }

        .student-nav-link:hover {
            background: #f8fafc;
            color: #0284c7;
            transform: translateX(2px);
        }

        .student-nav-link:hover i {
            color: #0284c7;
        }

        .student-nav-link.active {
            background: #ffffff;
            color: #0284c7;
            box-shadow: var(--clay-pill);
            border: 1px solid rgba(226, 232, 240, 0.9);
            font-weight: 700;
            position: relative;
        }

        .student-nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 25%;
            bottom: 25%;
            width: 3.5px;
            border-radius: 0 4px 4px 0;
            background: #009688;
        }

        .student-nav-link.active i {
            color: #009688;
        }

        .student-sidebar-footer {
            padding: 0.75rem 0.85rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        /* Main Content Wrapper */
        .student-main-wrap {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: #f1f5f9;
            transition: margin-left 0.28s cubic-bezier(0.4, 0, 0.2, 1), width 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .student-layout.sidebar-closed .student-main-wrap {
            margin-left: 0;
            width: 100%;
        }

        /* Top Bar */
        .student-topbar {
            height: 70px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1.5px solid rgba(226, 232, 240, 0.8);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.02);
        }

        .student-content-area {
            padding: 2rem 2rem 3.5rem;
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }

        /* Toggle Button in Topbar (Accessible on both desktop and mobile) */
        .sidebar-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: var(--clay-pill);
            border-radius: 10px;
            color: #334155;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        .sidebar-toggle-btn:hover {
            color: #0284c7;
            transform: scale(1.05);
        }

        /* Backdrop for mobile drawer */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            z-index: 45;
            opacity: 0;
            transition: opacity 0.28s ease;
        }

        .student-layout.sidebar-mobile-open .sidebar-backdrop {
            display: block;
            opacity: 1;
        }

        @media (max-width: 992px) {
            .student-sidebar {
                transform: translateX(-100%);
                box-shadow: 0 20px 35px -5px rgba(0, 0, 0, 0.25);
            }
            .student-layout.sidebar-mobile-open .student-sidebar {
                transform: translateX(0);
            }
            .student-main-wrap {
                margin-left: 0 !important;
                width: 100% !important;
            }
            .student-topbar {
                padding: 0 1.25rem;
            }
            .student-content-area {
                padding: 1.25rem;
            }
        }

        @media print {
            .student-sidebar, .student-topbar, .no-print, .sidebar-backdrop {
                display: none !important;
            }
            .student-main-wrap, .student-content-area {
                padding: 0 !important;
                margin: 0 !important;
                background: #ffffff !important;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>
    <div class="student-layout">
        <!-- Sidebar Navigation Menu -->
        <aside class="student-sidebar" id="studentSidebar">
            <!-- Brand & Desktop Collapse Button -->
            <div class="student-brand-row">
                <a href="{{ route('student.dashboard') }}" class="student-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 34px; width: auto; max-width: 34px; object-fit: contain; flex-shrink: 0; filter: drop-shadow(0 2px 5px rgba(0,0,0,0.12));">
                    <div>
                        <div style="font-weight: 800; font-size: 0.88rem; letter-spacing: -0.01em; color: #0f172a; line-height: 1.2;">PORTAL MAHASISWA</div>
                        <div style="font-size: 0.68rem; color: #64748b; font-weight: 700;">UKM ILMU KOMPUTER</div>
                    </div>
                </a>
                <button type="button" class="sidebar-collapse-btn" onclick="toggleSidebar()" title="Tutup / Sembunyikan Sidebar">
                    <i class="fas fa-angles-left"></i>
                </button>
            </div>

            <!-- Mini Profile Card in Sidebar -->
            <div class="student-profile-badge">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="student-profile-avatar" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=100&background=0284c7&color=ffffff&bold=true';">
                <div style="min-width: 0; flex: 1;">
                    <div style="font-weight: 800; font-size: 0.825rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.25;">
                        {{ auth()->user()->name }}
                    </div>
                    <div style="font-size: 0.725rem; color: #0284c7; font-family: var(--font-mono); font-weight: 700; margin-top: 0.1rem;">
                        {{ auth()->user()->nim ?? 'Mahasiswa' }}
                    </div>
                    <div style="margin-top: 0.25rem;">
                        @if (auth()->user()->member || optional(auth()->user()->recruitment)->status === 'accepted')
                            <span style="display: inline-block; font-size: 0.65rem; background: #ecfdf5; color: #059669; box-shadow: var(--clay-pill); padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 700;">
                                <i class="fas fa-circle-check" style="margin-right: 0.2rem;"></i> Anggota Resmi
                            </span>
                        @else
                            <span style="display: inline-block; font-size: 0.65rem; background: #fffbeb; color: #d97706; box-shadow: var(--clay-pill); padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 700;">
                                <i class="fas fa-clock" style="margin-right: 0.2rem;"></i> Calon Anggota
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <ul class="student-nav">
                <li class="student-nav-heading">Menu Utama</li>
                <li>
                    <a href="{{ route('student.dashboard') }}" class="student-nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.kta') }}" class="student-nav-link {{ request()->routeIs('student.kta') ? 'active' : '' }}">
                        <i class="fas fa-id-card"></i>
                        <span>Kartu Anggota (KTA)</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.presensi') }}" class="student-nav-link {{ request()->routeIs('student.presensi') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Presensi Pertemuan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.silabus') }}" class="student-nav-link {{ request()->routeIs('student.silabus') ? 'active' : '' }}">
                        <i class="fas fa-book-open"></i>
                        <span>Silabus & Riset</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.projects.index') }}" class="student-nav-link {{ request()->routeIs('student.projects.*') ? 'active' : '' }}">
                        <i class="fas fa-laptop-code"></i>
                        <span>Karya & Proyek Saya</span>
                    </a>
                </li>

                <li class="student-nav-heading">Pengaturan Akun</li>
                <li>
                    <a href="{{ route('student.profile.edit') }}" class="student-nav-link {{ request()->routeIs('student.profile.edit') ? 'active' : '' }}">
                        <i class="fas fa-user-gear"></i>
                        <span>Edit Profil & Foto</span>
                    </a>
                </li>
            </ul>

            <!-- Sidebar Bottom Footer -->
            <div class="student-sidebar-footer">
                <a href="{{ route('home') }}" target="_blank" class="student-nav-link" style="color: #64748b;">
                    <i class="fas fa-arrow-up-right-from-square"></i>
                    <span>Portal Publik UKM</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="student-nav-link" style="width: 100%; background: none; border: none; cursor: pointer; color: #ef4444; text-align: left;">
                        <i class="fas fa-right-from-bracket" style="color: #ef4444;"></i>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace Area -->
        <div class="student-main-wrap">
            <!-- Topbar Header -->
            <header class="student-topbar">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <!-- Universal Toggle Button (Works on both desktop & mobile) -->
                    <button type="button" class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Buka / Tutup Sidebar Navigasi">
                        <i class="fas fa-bars-staggered"></i>
                    </button>
                    <div>
                        <div style="font-size: 0.72rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                            PORTAL MAHASISWA & ANGGOTA
                        </div>
                        <h2 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">
                            @yield('page_title', 'Dashboard Mahasiswa')
                        </h2>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="display: none; @media(min-width: 768px){display: flex;} align-items: center; gap: 0.5rem; background: #ffffff; box-shadow: var(--clay-pill); padding: 0.45rem 1rem; border-radius: 9999px; font-size: 0.8rem; color: #475569; font-weight: 700;">
                        <i class="fas fa-calendar-day" style="color: #0284c7;"></i>
                        <span>Periode Ganjil 2026/2027</span>
                    </div>

                    <a href="{{ route('student.profile.edit') }}" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: inherit; padding: 0.35rem 0.75rem 0.35rem 0.35rem; border-radius: 9999px; box-shadow: var(--clay-pill); background: #ffffff;">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=100&background=0284c7&color=ffffff&bold=true';">
                        <div style="font-size: 0.825rem; font-weight: 700; color: #1e293b; max-width: 140px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding-right: 0.35rem;">
                            {{ auth()->user()->name }}
                        </div>
                    </a>
                </div>
            </header>

            <!-- Content Container -->
            <main class="student-content-area">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible" style="margin-bottom: 1.5rem; border-radius: var(--radius-md); box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <i class="fas fa-circle-check" style="margin-right: 0.5rem; font-size: 1.1rem;"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error alert-dismissible" style="margin-bottom: 1.5rem; border-radius: var(--radius-md);">
                        <i class="fas fa-circle-exclamation" style="margin-right: 0.5rem; font-size: 1.1rem;"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible" style="margin-bottom: 1.5rem; border-radius: var(--radius-md);">
                        <i class="fas fa-circle-info" style="margin-right: 0.5rem; font-size: 1.1rem;"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const layout = document.querySelector('.student-layout');
            const isMobile = window.innerWidth <= 992;
            if (isMobile) {
                layout.classList.toggle('sidebar-mobile-open');
            } else {
                const isClosed = layout.classList.toggle('sidebar-closed');
                try {
                    localStorage.setItem('student_sidebar_closed', isClosed ? 'true' : 'false');
                } catch(e) {}
            }
        }

        // Restore saved desktop state on load
        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth > 992) {
                try {
                    if (localStorage.getItem('student_sidebar_closed') === 'true') {
                        document.querySelector('.student-layout')?.classList.add('sidebar-closed');
                    }
                } catch(e) {}
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
