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
            --sidebar-width: 270px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: rgba(255, 255, 255, 0.08);
            --sidebar-active: #2563eb;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
        }

        body {
            background: #f8fafc;
            color: #1e293b;
            font-family: var(--font-sans, system-ui, -apple-system, sans-serif);
            margin: 0;
            padding: 0;
        }

        .student-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .student-sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            flex-shrink: 0;
            z-index: 50;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            transition: transform 0.3s ease;
        }

        .student-brand {
            padding: 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-decoration: none;
            color: #ffffff;
        }

        .student-brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2563eb, #0ea5e9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
        }

        .student-profile-badge {
            padding: 1.25rem;
            margin: 1rem 0.85rem 0.5rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .student-profile-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #38bdf8;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }

        .student-nav {
            list-style: none;
            padding: 0.75rem;
            margin: 0;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .student-nav-heading {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            font-weight: 700;
            padding: 0.75rem 0.75rem 0.35rem;
        }

        .student-nav-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-md);
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .student-nav-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            color: #64748b;
            transition: color 0.2s ease;
        }

        .student-nav-link:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
        }

        .student-nav-link:hover i {
            color: #38bdf8;
        }

        .student-nav-link.active {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.25), rgba(14, 165, 233, 0.15));
            color: #ffffff;
            border: 1px solid rgba(56, 189, 248, 0.3);
        }

        .student-nav-link.active i {
            color: #38bdf8;
        }

        .student-sidebar-footer {
            padding: 1rem 0.85rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        /* Main Content Wrapper */
        .student-main-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: #f8fafc;
        }

        /* Top Bar */
        .student-topbar {
            height: 68px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
        }

        .student-content-area {
            padding: 2rem;
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }

        .mobile-toggle-btn {
            display: none;
            background: none;
            border: 1px solid #cbd5e1;
            padding: 0.4rem 0.6rem;
            border-radius: var(--radius-sm);
            color: #334155;
            cursor: pointer;
            font-size: 1.1rem;
        }

        @media (max-width: 992px) {
            .student-sidebar {
                position: fixed;
                left: -270px;
                top: 0;
                bottom: 0;
            }
            .student-sidebar.open {
                left: 0;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
            }
            .mobile-toggle-btn {
                display: block;
            }
            .student-topbar {
                padding: 0 1.25rem;
            }
            .student-content-area {
                padding: 1.25rem;
            }
        }

        @media print {
            .student-sidebar, .student-topbar, .no-print {
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
    <div class="student-layout">
        <!-- Sidebar Navigation Menu -->
        <aside class="student-sidebar" id="studentSidebar">
            <!-- Brand -->
            <a href="{{ route('student.dashboard') }}" class="student-brand">
                <img src="{{ asset('images/logo.png') }}" alt="UKM Ilmu Komputer Logo" style="width: 42px; height: 42px; object-fit: contain; flex-shrink: 0; filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));">
                <div>
                    <div style="font-weight: 800; font-size: 1.05rem; letter-spacing: -0.01em; color: #ffffff;">PORTAL MAHASISWA</div>
                    <div style="font-size: 0.725rem; color: #94a3b8; font-weight: 500;">UKM ILMU KOMPUTER</div>
                </div>
            </a>

            <!-- Mini Profile Card in Sidebar -->
            <div class="student-profile-badge">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="student-profile-avatar">
                <div style="min-width: 0; flex: 1;">
                    <div style="font-weight: 700; font-size: 0.85rem; color: #f8fafc; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ auth()->user()->name }}
                    </div>
                    <div style="font-size: 0.75rem; color: #38bdf8; font-family: var(--font-mono); font-weight: 600;">
                        {{ auth()->user()->nim ?? 'Mahasiswa' }}
                    </div>
                    <div style="margin-top: 0.25rem;">
                        @if (auth()->user()->member || optional(auth()->user()->recruitment)->status === 'accepted')
                            <span style="display: inline-block; font-size: 0.675rem; background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 700;">
                                <i class="fas fa-circle-check" style="margin-right: 0.2rem;"></i> Anggota Resmi
                            </span>
                        @else
                            <span style="display: inline-block; font-size: 0.675rem; background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 700;">
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
                <a href="{{ route('home') }}" target="_blank" class="student-nav-link" style="color: #94a3b8;">
                    <i class="fas fa-arrow-up-right-from-square"></i>
                    <span>Portal Publik UKM</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="student-nav-link" style="width: 100%; background: none; border: none; cursor: pointer; color: #f87171; text-align: left;">
                        <i class="fas fa-right-from-bracket" style="color: #f87171;"></i>
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
                    <button type="button" class="mobile-toggle-btn" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <div style="font-size: 0.775rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em;">
                            PORTAL MAHASISWA & ANGGOTA
                        </div>
                        <h2 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">
                            @yield('page_title', 'Dashboard Mahasiswa')
                        </h2>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="display: none; @media(min-width: 768px){display: flex;} align-items: center; gap: 0.5rem; background: #f1f5f9; padding: 0.4rem 0.85rem; border-radius: 9999px; font-size: 0.775rem; color: #475569; font-weight: 600;">
                        <i class="fas fa-calendar-day" style="color: #0284c7;"></i>
                        <span>Periode Ganjil 2026/2027</span>
                    </div>

                    <a href="{{ route('student.profile.edit') }}" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: inherit; padding: 0.35rem 0.65rem 0.35rem 0.35rem; border-radius: 9999px; border: 1px solid #e2e8f0; background: #ffffff;">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: #1e293b; max-width: 140px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding-right: 0.35rem;">
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
            const sidebar = document.getElementById('studentSidebar');
            sidebar.classList.toggle('open');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('studentSidebar');
            const toggleBtn = document.querySelector('.mobile-toggle-btn');
            if (sidebar.classList.contains('open') && !sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
