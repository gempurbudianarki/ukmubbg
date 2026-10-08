@extends('student.layouts.app')

@section('title', 'Karya & Proyek Saya - Portal Mahasiswa')
@section('page_title', 'Portofolio & Showcase Karya Inovasi')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.75rem;">

    <!-- Header Banner -->
    <div style="background: #ffffff; border-radius: var(--radius-xl); padding: 2rem 2.25rem; box-shadow: var(--clay-card); border: 1.5px solid rgba(226, 232, 240, 0.9); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.4rem; background: #e0f2fe; color: #0284c7; padding: 0.35rem 0.85rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 800; margin-bottom: 0.75rem; box-shadow: var(--clay-pill);">
                <i class="fas fa-rocket"></i> SHOWCASE & PORTOFOLIO MAHASISWA
            </div>
            <h2 style="font-size: 1.75rem; font-weight: 900; color: #0c2340; margin: 0 0 0.5rem; letter-spacing: -0.02em;">
                Karya & Proyek Inovasi Saya
            </h2>
            <p style="color: #64748b; font-size: 0.925rem; margin: 0; max-width: 680px; line-height: 1.6;">
                Unggah karya perangkat lunak, sistem IoT, desain multimedia, atau riset keamanan siber Anda. Setiap karya yang disetujui pengurus akan ditayangkan di halaman galeri publik UKM sebagai portofolio resmi.
            </p>
        </div>
        <div>
            <a href="{{ route('student.projects.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #0284c7, #0369a1); border: none; font-weight: 800; padding: 0.75rem 1.5rem; border-radius: 9999px; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35); display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-plus"></i> Ajukan Karya Baru
            </a>
        </div>
    </div>

    @if (session('success'))
        <div style="background: #ecfdf5; border-left: 4px solid #10b981; border-radius: var(--radius-md); padding: 1rem 1.25rem; color: #065f46; font-size: 0.875rem; display: flex; align-items: center; gap: 0.75rem; box-shadow: var(--clay-card);">
            <i class="fas fa-check-circle" style="font-size: 1.2rem; color: #10b981;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Projects Grid -->
    @if ($projects->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
            @foreach ($projects as $project)
                <div style="background: #ffffff; border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--clay-card); border: 1.5px solid #e2e8f0; display: flex; flex-direction: column; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    
                    <!-- Thumbnail Container -->
                    <div style="position: relative; height: 180px; background: #0f172a; overflow: hidden;">
                        <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        
                        <!-- Status Badge Overlay -->
                        <div style="position: absolute; top: 12px; left: 12px; z-index: 2;">
                            @if ($project->submission_status === 'published')
                                <span class="badge" style="background: #dcfce7; color: #15803d; border: 1px solid #86efac; font-weight: 800; font-size: 0.725rem; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                                    <i class="fas fa-check-circle"></i> Tayang di Publik
                                </span>
                            @elseif ($project->submission_status === 'pending_review')
                                <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-weight: 800; font-size: 0.725rem; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                                    <i class="fas fa-clock"></i> Menunggu Verifikasi
                                </span>
                            @elseif ($project->submission_status === 'rejected')
                                <span class="badge" style="background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; font-weight: 800; font-size: 0.725rem; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                                    <i class="fas fa-triangle-exclamation"></i> Perlu Revisi
                                </span>
                            @endif
                        </div>

                        <!-- Division Badge Overlay -->
                        @if ($project->division)
                            <div style="position: absolute; bottom: 12px; right: 12px; z-index: 2;">
                                <span class="badge" style="background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(8px); color: #ffffff; font-size: 0.7rem; font-weight: 700; border: 1px solid rgba(255,255,255,0.2);">
                                    {{ $project->division->name }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem; line-height: 1.35;">
                                {{ $project->title }}
                            </h3>
                            <p style="font-size: 0.85rem; color: #64748b; margin: 0 0 1rem; line-height: 1.5;">
                                {{ Str::limit($project->description, 110) }}
                            </p>

                            <!-- Tech Stack Pills -->
                            @if (!empty($project->tech_stack))
                                <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-bottom: 1rem;">
                                    @foreach ($project->tech_stack as $tech)
                                        <span style="font-size: 0.7rem; font-weight: 700; background: #f1f5f9; color: #475569; padding: 0.2rem 0.55rem; border-radius: 6px;">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            @if ($project->submission_status === 'rejected' && $project->admin_notes)
                                <div style="background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 10px; padding: 0.65rem 0.85rem; margin-bottom: 1rem; font-size: 0.8rem; color: #991b1b; line-height: 1.5;">
                                    <div style="font-weight: 800; display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.2rem;">
                                        <i class="fas fa-triangle-exclamation" style="color: #ef4444;"></i> Catatan Review Pengurus:
                                    </div>
                                    <div>{{ $project->admin_notes }}</div>
                                </div>
                            @endif

                            <div style="font-size: 0.775rem; color: #64748b; margin-bottom: 1.25rem;">
                                <i class="fas fa-users" style="color: #94a3b8; margin-right: 0.3rem;"></i> Tim: <strong>{{ $project->author_names }}</strong>
                            </div>
                        </div>

                        <!-- Actions & Links -->
                        <div style="border-top: 1px solid #f1f5f9; padding-top: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                @if ($project->demo_url)
                                    <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm" style="font-size: 0.75rem; padding: 0.35rem 0.7rem; border-radius: 6px;" title="Buka Demo Live">
                                        <i class="fas fa-external-link-alt"></i> Demo
                                    </a>
                                @endif
                                @if ($project->repo_url)
                                    <a href="{{ $project->repo_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-sm" style="font-size: 0.75rem; padding: 0.35rem 0.7rem; border-radius: 6px;" title="Lihat Source Code">
                                        <i class="fab fa-github"></i> Repo
                                    </a>
                                @endif
                            </div>

                            <div style="display: flex; gap: 0.4rem;">
                                <a href="{{ route('student.projects.edit', $project->id) }}" class="btn btn-outline btn-sm" style="font-size: 0.75rem; padding: 0.35rem 0.75rem; font-weight: 700; border-radius: 6px;">
                                    <i class="fas fa-pen-to-square"></i> Edit
                                </a>
                                <form action="{{ route('student.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?')" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="font-size: 0.75rem; padding: 0.35rem 0.75rem; border-radius: 6px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        @if ($projects->total() > 0)
            <div style="margin-top: 2rem;">
                {{ $projects->links('pagination.clay') }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div style="background: #ffffff; border-radius: var(--radius-xl); padding: 4rem 2rem; text-align: center; box-shadow: var(--clay-card); border: 1.5px solid #e2e8f0;">
            <div style="width: 80px; height: 80px; border-radius: 24px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 2.25rem; margin: 0 auto 1.5rem; box-shadow: var(--clay-pill);">
                <i class="fas fa-lightbulb"></i>
            </div>
            <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem;">
                Belum Ada Karya yang Diunggah
            </h3>
            <p style="color: #64748b; font-size: 0.925rem; max-width: 480px; margin: 0 auto 1.75rem; line-height: 1.6;">
                Jadikan keanggotaan Anda berdampak! Publikasikan aplikasi, robotika, karya seni digital, atau riset keamanan siber Anda ke ekosistem UKM.
            </p>
            <a href="{{ route('student.projects.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #0284c7, #0369a1); border: none; font-weight: 800; padding: 0.75rem 1.75rem; border-radius: 9999px; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);">
                <i class="fas fa-plus" style="margin-right: 0.4rem;"></i> Unggah Karya Pertama Anda
            </a>
        </div>
    @endif

</div>
@endsection
