@extends('layouts.app')

@section('title', 'Tentang UKM Ilmu Komputer - Wadah Riset & Inovasi')

@section('content')
<div style="padding: 4rem 0 2rem;">
    <div class="container text-center" style="text-align: center;">
        <span class="badge badge-primary" style="margin-bottom: 0.75rem;">Profil Organisasi</span>
        <h1 style="font-size: 2.75rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em; margin-bottom: 0.75rem;">
            Unit Kegiatan Mahasiswa Ilmu Komputer
        </h1>
        <p style="color: var(--slate-600); max-width: 680px; margin: 0 auto; font-size: 1.15rem; line-height: 1.6;">
            Organisasi kemahasiswaan tingkat program studi yang bergerak di bidang riset terapan teknologi informasi, rekayasa perangkat lunak, desain multimedia, otomasi IoT, dan ketahanan siber.
        </p>
    </div>
</div>

<div class="container" style="padding: 2rem 1.5rem 5rem;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 4rem;">
        <div class="card">
            <div class="section-tag">Landasan Utama</div>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1rem;">
                Visi UKM
            </h2>
            <p style="color: var(--slate-700); line-height: 1.8; font-size: 1.05rem;">
                Menjadi episentrum inovasi digital dan laboratorium inkubasi talenta teknologi terkemuka yang mampu mencetak lulusan berdaya saing global, berintegritas tinggi, dan berkontribusi nyata bagi kemajuan bangsa.
            </p>
        </div>

        <div class="card">
            <div class="section-tag">Arah Gerak</div>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900); margin-bottom: 1rem;">
                Misi UKM
            </h2>
            <ul style="color: var(--slate-700); line-height: 1.8; font-size: 0.95rem; padding-left: 1.25rem;">
                <li>Menyelenggarakan pelatihan dan riset komputasi mutakhir berbasis proyek nyata.</li>
                <li>Mewadahi mahasiswa berkompetisi dalam ajang bergengsi nasional dan internasional.</li>
                <li>Membangun jejaring kolaborasi antara sivitas akademika, industri teknologi, dan alumni.</li>
                <li>Mempublikasikan riset terbuka dan solusi perangkat lunak yang bermanfaat bagi masyarakat.</li>
            </ul>
        </div>
    </div>

    <!-- 4 Divisions Structure -->
    <div class="section-header">
        <div class="section-tag">4 Pilar Divisi</div>
        <h2 class="section-title">Bidang Keahlian Spesifik</h2>
    </div>

    <div class="division-grid" style="margin-bottom: 4rem;">
        @foreach ($divisions as $div)
            <div class="card" style="border-top: 6px solid {{ $div->color_accent }};">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <div style="color: {{ $div->color_accent }};">
                        {!! $div->icon_svg !!}
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900);">
                        {{ $div->name }}
                    </h3>
                </div>
                <p style="color: var(--slate-600); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1rem;">
                    {{ $div->tagline }}
                </p>
                <div style="font-size: 0.85rem; color: var(--slate-700); background: #f6f9fc; padding: 0.85rem; border-radius: var(--radius-md); box-shadow: var(--clay-input); margin-bottom: 1.25rem;">
                    <strong>Pembina:</strong> {{ $div->adviser_name }}<br>
                    <strong>Ketua:</strong> {{ $div->leader_name }} ({{ $div->leader_nim }})
                </div>
                <a href="{{ route('divisions.show', $div->slug) }}" class="btn btn-outline btn-sm">
                    Kunjungi Kanal Divisi &rarr;
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
