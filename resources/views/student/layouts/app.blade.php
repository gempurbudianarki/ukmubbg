<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Mahasiswa') - UKM Ilmu Komputer</title>
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .student-navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--slate-200);
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }
        .student-nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 4.25rem;
        }
        .student-brand {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            text-decoration: none;
            color: inherit;
        }
        .student-logo-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, var(--primary-600), var(--accent-cyan));
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.25);
        }
        .student-nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .student-nav-link {
            text-decoration: none;
            color: var(--slate-600);
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.5rem 0.875rem;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .student-nav-link:hover, .student-nav-link.active {
            color: var(--primary-600);
            background: var(--primary-50);
        }
        .student-user-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.35rem 0.65rem 0.35rem 0.35rem;
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: 9999px;
        }
        .student-avatar {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 9999px;
            object-fit: cover;
            border: 2px solid #ffffff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .student-body {
            background: var(--slate-50);
            min-height: calc(100vh - 4.25rem);
            padding: 2.25rem 0 4rem;
        }
        @media print {
            .student-navbar, .no-print {
                display: none !important;
            }
            .student-body {
                background: #ffffff !important;
                padding: 0 !important;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <header class="student-navbar">
        <div class="container student-nav-inner">
            <a href="{{ route('student.dashboard') }}" class="student-brand">
                <div class="student-logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <div style="font-weight: 800; font-size: 1.05rem; color: var(--slate-900); line-height: 1.2;">PORTAL MAHASISWA</div>
                    <div style="font-size: 0.75rem; color: var(--slate-500); font-weight: 500;">UKM ILMU KOMPUTER</div>
                </div>
            </a>

            <nav style="display: flex; align-items: center; gap: 1.25rem;">
                <ul class="student-nav-links">
                    <li>
                        <a href="{{ route('student.dashboard') }}" class="student-nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-pie"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.profile.edit') }}" class="student-nav-link {{ request()->routeIs('student.profile.edit') ? 'active' : '' }}">
                            <i class="fas fa-user-gear"></i> Edit Profil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}" class="student-nav-link" target="_blank" title="Buka website publik UKM">
                            <i class="fas fa-arrow-up-right-from-square"></i> Portal Web
                        </a>
                    </li>
                </ul>

                <div class="student-user-badge">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="student-avatar">
                    <div style="line-height: 1.1; margin-right: 0.25rem;">
                        <div style="font-weight: 700; font-size: 0.825rem; color: var(--slate-800); max-width: 140px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ auth()->user()->name }}
                        </div>
                        <div style="font-size: 0.7rem; color: var(--primary-600); font-weight: 600;">
                            {{ auth()->user()->nim ?? 'Mahasiswa' }}
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline" style="border: none; padding: 0.35rem 0.5rem; color: var(--slate-400); font-size: 0.85rem;" title="Keluar">
                            <i class="fas fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </header>

    <div class="student-body">
        <div class="container">
            <!-- Flash notifications -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible" style="margin-bottom: 1.5rem;">
                    <i class="fas fa-circle-check" style="margin-right: 0.5rem; font-size: 1.1rem;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error alert-dismissible" style="margin-bottom: 1.5rem;">
                    <i class="fas fa-circle-exclamation" style="margin-right: 0.5rem; font-size: 1.1rem;"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible" style="margin-bottom: 1.5rem;">
                    <i class="fas fa-circle-info" style="margin-right: 0.5rem; font-size: 1.1rem;"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    @yield('scripts')
</body>
</html>
