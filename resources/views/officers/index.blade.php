@extends('layouts.app')

@section('title', 'Struktur Kepengurusan - UKM Ilmu Komputer')

@section('content')
<div style="padding: 4rem 0 5rem;">
    <div class="container">
        <!-- Header -->
        <div class="section-header">
            <div class="section-tag">Struktur Kepengurusan</div>
            <h1 class="section-title">Susunan Organisasi & Pengurus</h1>
            <p class="section-desc">
                Mengenal nakhoda dan tim penggerak UKM Ilmu Komputer periode 2026/2027 yang berdedikasi memajukan riset, kompetensi, dan inovasi mahasiswa.
            </p>
        </div>

        <!-- 1. BPH (Badan Pengurus Harian & Pembina) -->
        <div style="margin-bottom: 4.5rem;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900);">
                    Dewan Pembina & Badan Pengurus Harian (BPH)
                </h2>
                <p style="color: var(--slate-500); font-size: 0.9rem;">
                    Penanggung jawab arah kebijakan, tata kelola administrasi, dan keuangan organisasi.
                </p>
            </div>

            <div class="officers-grid">
                @foreach ($bphOfficers as $officer)
                    <div class="officer-card">
                        <div class="officer-photo">
                            @if ($officer->photo)
                                <img src="{{ asset('storage/' . $officer->photo) }}" alt="{{ $officer->name }}">
                            @else
                                {{ strtoupper(substr($officer->name, 0, 2)) }}
                            @endif
                        </div>
                        <h3 class="officer-name">{{ $officer->name }}</h3>
                        <div class="officer-pos">{{ $officer->position }}</div>
                        <div class="officer-nim">NIM/NIP: {{ $officer->nim }}</div>

                        @if ($officer->social_links)
                            <div class="social-strip">
                                @if (isset($officer->social_links['linkedin']))
                                    <a href="{{ $officer->social_links['linkedin'] }}" target="_blank" title="LinkedIn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                    </a>
                                @endif
                                @if (isset($officer->social_links['github']))
                                    <a href="{{ $officer->social_links['github'] }}" target="_blank" title="GitHub">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 2. Division Teams: Pemrograman, Multimedia, IoT, Cyber Security -->
        <div>
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900);">
                    Struktur Kepengurusan 4 Divisi Spesialisasi
                </h2>
                <p style="color: var(--slate-500); font-size: 0.9rem;">
                    Dipandu oleh Dosen Pembina bidang keilmuan dan dipimpin oleh Koordinator Divisi mahasiswa.
                </p>
            </div>

            @php
                $divisionsData = [
                    [
                        'name' => 'Divisi Pemrograman',
                        'accent' => '#0284c7',
                        'icon' => 'fa-code',
                        'officers' => $pemrogramanOfficers,
                        'desc' => 'Rekayasa Perangkat Lunak, Fullstack Web, Mobile App & Algoritma'
                    ],
                    [
                        'name' => 'Divisi Multimedia',
                        'accent' => '#8b5cf6',
                        'icon' => 'fa-palette',
                        'officers' => $multimediaOfficers,
                        'desc' => 'UI/UX Design, Motion Graphic, Animasi, 3D Modelling & Audio Visual'
                    ],
                    [
                        'name' => 'Divisi Internet of Things (IoT)',
                        'accent' => '#d97706',
                        'icon' => 'fa-microchip',
                        'officers' => $iotOfficers,
                        'desc' => 'Embedded Systems, Smart Devices, Sensor Jaringan & Robotika'
                    ],
                    [
                        'name' => 'Divisi Cyber Security',
                        'accent' => '#059669',
                        'icon' => 'fa-shield-halved',
                        'officers' => $cyberOfficers,
                        'desc' => 'Keamanan Informasi, Ethical Hacking, Forensik Digital & Defense'
                    ],
                ];
            @endphp

            <div style="display: flex; flex-direction: column; gap: 3rem;">
                @foreach ($divisionsData as $divGroup)
                    <div class="glass-panel" style="padding: 1.75rem 2rem; border-top: 4px solid {{ $divGroup['accent'] }};">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                <div style="width: 42px; height: 42px; border-radius: var(--radius-md); background: {{ $divGroup['accent'] }}15; color: {{ $divGroup['accent'] }}; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; box-shadow: var(--clay-pill);">
                                    <i class="fa-solid {{ $divGroup['icon'] }}"></i>
                                </div>
                                <div>
                                    <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--slate-900); margin: 0;">
                                        {{ $divGroup['name'] }}
                                    </h3>
                                    <p style="font-size: 0.8rem; color: var(--slate-500); margin: 0.15rem 0 0;">
                                        {{ $divGroup['desc'] }}
                                    </p>
                                </div>
                            </div>
                            <span class="badge" style="background: {{ $divGroup['accent'] }}15; color: {{ $divGroup['accent'] }}; font-weight: 800; font-size: 0.75rem; padding: 0.4rem 0.85rem; border-radius: var(--radius-full);">
                                {{ $divGroup['officers']->count() }} Pengurus & Pembina
                            </span>
                        </div>

                        <div class="officers-grid">
                            @forelse ($divGroup['officers'] as $officer)
                                @php
                                    $posLower = strtolower($officer->position);
                                    $isDosen = str_contains($posLower, 'pembina') || str_contains($posLower, 'pembimbing') || str_contains($posLower, 'dosen');
                                @endphp
                                <div class="officer-card" style="{{ $isDosen ? 'border-top: 3px solid ' . $divGroup['accent'] . ';' : '' }}">
                                    <!-- Role Badge -->
                                    <div style="margin-bottom: 0.75rem;">
                                        @if ($isDosen)
                                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.7rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: var(--radius-full); background: #fef3c7; color: #b45309; border: 1px solid #fde68a;">
                                                <i class="fa-solid fa-chalkboard-user"></i> Dosen Pembimbing
                                            </span>
                                        @else
                                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.7rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: var(--radius-full); background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;">
                                                <i class="fa-solid fa-user-tie"></i> Koordinator Mahasiswa
                                            </span>
                                        @endif
                                    </div>

                                    <div class="officer-photo">
                                        @if ($officer->photo)
                                            <img src="{{ asset('storage/' . $officer->photo) }}" alt="{{ $officer->name }}">
                                        @else
                                            {{ strtoupper(substr($officer->name, 0, 2)) }}
                                        @endif
                                    </div>
                                    <h3 class="officer-name">{{ $officer->name }}</h3>
                                    <div class="officer-pos">{{ $officer->position }}</div>
                                    <div class="officer-nim">{{ $isDosen ? 'NIP' : 'NIM' }}: {{ $officer->nim }}</div>

                                    @if ($officer->social_links)
                                        <div class="social-strip">
                                            @if (isset($officer->social_links['linkedin']))
                                                <a href="{{ $officer->social_links['linkedin'] }}" target="_blank" title="LinkedIn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                                </a>
                                            @endif
                                            @if (isset($officer->social_links['github']))
                                                <a href="{{ $officer->social_links['github'] }}" target="_blank" title="GitHub">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                                </a>
                                            @endif
                                            @if (isset($officer->social_links['instagram']))
                                                <a href="{{ $officer->social_links['instagram'] }}" target="_blank" title="Instagram">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div style="color: var(--slate-400); font-style: italic; grid-column: 1 / -1; text-align: center; padding: 1.5rem;">
                                    Belum ada data pengurus untuk divisi ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
