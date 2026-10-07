@extends('layouts.app')

@section('title', '404 - Halaman Tidak Ditemukan | UKM Ilmu Komputer')

@section('content')
<div style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 4rem 1.5rem;">
    <div style="max-width: 580px; width: 100%; text-align: center; background: #ffffff; border-radius: var(--radius-xl); padding: 3.5rem 2.5rem; box-shadow: var(--clay-card); position: relative;">
        <!-- 404 Badge -->
        <div style="width: 90px; height: 90px; margin: 0 auto 1.75rem; border-radius: 28px; background: #ffffff; box-shadow: var(--clay-pill); display: flex; align-items: center; justify-content: center; color: var(--accent-blue);">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <span class="badge badge-neutral" style="font-size: 0.85rem; font-weight: 800; letter-spacing: 0.05em; margin-bottom: 1rem;">
            ERROR 404 &bull; TIDAK DITEMUKAN
        </span>

        <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em; margin-bottom: 1rem; line-height: 1.25;">
            Halaman Telah Dihapus atau Tidak Tersedia
        </h1>

        <p style="font-size: 1rem; color: var(--slate-600); line-height: 1.65; margin-bottom: 2.5rem;">
            Fitur atau tautan yang Anda tuju telah dinonaktifkan, dihapus dari portal, atau alamat URL yang Anda masukkan salah.
        </p>

        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                &larr; Kembali ke Beranda
            </a>
            <a href="{{ route('projects.index') }}" class="btn btn-outline btn-lg">
                Lihat Karya Mahasiswa
            </a>
        </div>
    </div>
</div>
@endsection
