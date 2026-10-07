@extends('student.layouts.app')

@section('title', 'Silabus & Riset Divisi')
@section('page_title', 'Silabus & Riset Divisi')

@section('styles')
<style>
    .syllabus-hero {
        background: #ffffff;
        border-radius: var(--radius-xl);
        padding: 2.25rem 2.5rem;
        color: #0f172a;
        margin-bottom: 2rem;
        border: 1.5px solid rgba(226, 232, 240, 0.9);
        box-shadow: var(--clay-card);
    }
    .syllabus-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    .topic-card {
        background: #ffffff;
        border-radius: var(--radius-xl);
        padding: 1.75rem;
        border: none;
        box-shadow: var(--clay-card);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .topic-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--clay-card-hover);
    }
    .leader-box {
        background: #ffffff;
        border-radius: var(--radius-xl);
        padding: 2rem;
        border: none;
        box-shadow: var(--clay-card);
    }
</style>
@endsection

@section('content')

<!-- Division Hero Banner -->
<div class="syllabus-hero">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 0.78rem; font-weight: 800; box-shadow: var(--clay-pill); padding: 0.45rem 1rem; border-radius: 9999px; margin-bottom: 0.85rem; display: inline-flex; align-items: center; gap: 0.35rem;">
                <i class="fas fa-microchip" style="color: #0284c7;"></i> KURIKULUM RESMI DIVISI
            </span>
            <h1 style="font-size: 1.95rem; font-weight: 900; margin: 0 0 0.5rem; color: #0c2340; letter-spacing: -0.02em;">
                {{ $division?->name ?? 'Divisi Pemrograman' }}
            </h1>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0 0 1rem; max-width: 620px; line-height: 1.6;">
                {{ $division?->tagline ?? 'Eksplorasi dan riset teknologi mutakhir bersama UKM Ilmu Komputer.' }}
            </p>
            <div style="font-size: 0.875rem; color: #475569; max-width: 620px; line-height: 1.5;">
                {{ $division?->description }}
            </div>
        </div>

        <div style="background: #f8fafc; padding: 1.35rem 1.65rem; border-radius: var(--radius-lg); border: 1.5px solid #e2e8f0; box-shadow: var(--clay-debossed); min-width: 250px;">
            <div style="font-size: 0.725rem; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                Dosen Pembina Divisi
            </div>
            <div style="font-weight: 800; font-size: 1.05rem; color: #0c2340; margin-bottom: 0.15rem;">
                {{ $division?->adviser_name ?? 'Dosen FSTIK UBBG' }}
            </div>
            <div style="font-size: 0.775rem; color: #009688; font-weight: 700;">
                {{ $division?->adviser_title ?? 'Dosen Pembimbing' }}
            </div>

            <div style="margin-top: 1rem; padding-top: 0.85rem; border-top: 1px solid #e2e8f0;">
                <div style="font-size: 0.725rem; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">
                    Ketua Divisi
                </div>
                <div style="font-weight: 800; font-size: 0.975rem; color: #0c2340;">
                    {{ $division?->leader_name }}
                </div>
                <div style="font-size: 0.775rem; color: #64748b; font-family: var(--font-mono);">
                    NIM: {{ $division?->leader_nim }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section: Topik & Modul Kompetensi -->
<div style="margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 0;">
            <i class="fas fa-layer-group" style="color: #0284c7; margin-right: 0.5rem;"></i>
            Daftar Modul & Kompetensi Inti
        </h3>
        <p style="font-size: 0.825rem; color: #64748b; margin: 0.25rem 0 0;">
            Materi yang dipelajari secara bertahap dalam sesi mingguan divisi.
        </p>
    </div>
</div>

<div class="syllabus-grid">
    @if (is_array($syllabus) && count($syllabus) > 0)
        @foreach ($syllabus as $index => $item)
            <div class="topic-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <span style="width: 32px; height: 32px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">
                        #0{{ $index + 1 }}
                    </span>
                    <span class="badge badge-success" style="font-size: 0.7rem;">Kurikulum Aktif</span>
                </div>
                <h4 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem;">
                    {{ $item }}
                </h4>
                <p style="font-size: 0.825rem; color: #64748b; line-height: 1.5; margin: 0 0 1rem;">
                    Penguasaan konsep mendalam, perancangan arsitektur, dan pembuatan proyek mandiri untuk portofolio anggota.
                </p>
                <div style="font-size: 0.775rem; color: #0284c7; font-weight: 600;">
                    <i class="fas fa-circle-check" style="margin-right: 0.3rem;"></i> Target Standar Industri
                </div>
            </div>
        @endforeach
    @else
        <div style="grid-column: 1 / -1; text-align: center; padding: 2rem; background: #ffffff; border-radius: var(--radius-lg); border: 1px dashed #cbd5e1; color: #64748b;">
            Topik pembelajaran belum diperbarui oleh ketua divisi.
        </div>
    @endif
</div>

<!-- Section: Visi & Misi Divisi -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <div class="leader-box">
        <h4 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-bullseye" style="color: #0284c7;"></i> Visi Divisi
        </h4>
        <p style="font-size: 0.875rem; color: #475569; line-height: 1.6; margin: 0;">
            {{ $division?->vision ?? 'Menjadi wadah unggulan mahasiswa dalam menghasilkan inovasi teknologi berdampak nyata.' }}
        </p>
    </div>

    <div class="leader-box">
        <h4 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-compass" style="color: #16a34a;"></i> Misi Divisi
        </h4>
        <div style="font-size: 0.85rem; color: #475569; line-height: 1.6; white-space: pre-line;">
            {{ $division?->mission ?? '1. Menyelenggarakan riset berkala seputar teknologi mutakhir.' }}
        </div>
    </div>
</div>

@endsection
