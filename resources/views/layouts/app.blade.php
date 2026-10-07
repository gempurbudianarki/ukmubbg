<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UKM Ilmu Komputer - Wadah Riset & Inovasi Teknologi')</title>
    <meta name="description" content="@yield('meta_description', 'Portal publikasi resmi UKM Ilmu Komputer: Pemrograman, Multimedia, IoT, dan Cyber Security. Informasi pendaftaran anggota baru dan dokumentasi kegiatan.')">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    @yield('styles')
</head>
<body>
    @include('layouts.navbar')

    <!-- Flash Notifications -->
    @if (session('success') || session('error') || session('info'))
        <div class="container" style="margin-top: 1rem;">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error alert-dismissible">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Scripts -->
    <script src="{{ asset('js/portal.js') }}"></script>
    @yield('scripts')
</body>
</html>
