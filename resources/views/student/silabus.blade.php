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

<!-- Section: Arsip Sesi Pertemuan & Rangkuman Materi -->
<div style="margin-bottom: 2.5rem;">
    <div style="margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
        <div>
            <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-chalkboard-user" style="color: #0284c7;"></i>
                <span>Arsip Sesi Pertemuan & Rangkuman Materi Divisi</span>
            </h3>
            <p style="font-size: 0.825rem; color: #64748b; margin: 0.25rem 0 0;">
                Catatan topik pembelajaran, instruktur, dan capaian kompetensi yang diisi oleh admin divisi pada setiap pertemuan.
            </p>
        </div>
        <span class="badge" style="background: #e0f2fe; color: #0284c7; font-weight: 700; font-size: 0.75rem; border: 1px solid #bae6fd; border-radius: 9999px; padding: 0.35rem 0.85rem;">
            {{ $divisionSessions->count() }} Sesi Terjadwal
        </span>
    </div>

    @if ($divisionSessions->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.25rem;">
            @foreach ($divisionSessions as $sess)
                <div style="background: #ffffff; border-radius: var(--radius-xl); padding: 1.5rem; border: 1.5px solid #e2e8f0; box-shadow: var(--clay-card); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.65rem;">
                            <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;">
                                {{ ucwords(str_replace('_', ' ', $sess->session_type)) }}
                            </span>
                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">
                                {{ \Carbon\Carbon::parse($sess->session_date)->translatedFormat('d M Y') }}
                            </span>
                        </div>
                        <h4 style="font-size: 1.05rem; font-weight: 800; color: #0c2340; margin: 0 0 0.4rem; line-height: 1.35;">
                            {{ $sess->title }}
                        </h4>
                        <div style="background: #f0f9ff; border-radius: 10px; padding: 0.65rem 0.85rem; margin-bottom: 0.75rem; border: 1px solid #bae6fd;">
                            <div style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: #0284c7;">
                                Pokok Materi:
                            </div>
                            <div style="font-weight: 700; font-size: 0.875rem; color: #0c2340; margin-top: 0.15rem;">
                                {{ $sess->topic_material ?? $sess->title }}
                            </div>
                        </div>
                        @if ($sess->learning_outcomes)
                            <div style="font-size: 0.825rem; color: #475569; line-height: 1.6; margin-bottom: 0.85rem;">
                                <strong style="color: #0c2340;">Capaian Belajar:</strong> {{ $sess->learning_outcomes }}
                            </div>
                        @endif
                    </div>

                    <div style="padding-top: 0.85rem; border-top: 1px dashed #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.775rem; color: #64748b;">
                            <i class="fas fa-user-tie" style="color: #0284c7; margin-right: 0.25rem;"></i>
                            {{ $sess->instructor_name ?? 'Pengurus Divisi' }}
                        </span>
                        <button type="button" 
                                onclick="openSilabusMateriModal({{ json_encode([
                                    'title' => $sess->title,
                                    'topic' => $sess->topic_material,
                                    'outcomes' => $sess->learning_outcomes,
                                    'instructor' => $sess->instructor_name,
                                    'notes' => $sess->notes,
                                    'date' => \Carbon\Carbon::parse($sess->session_date)->translatedFormat('l, d F Y'),
                                    'time' => substr($sess->time_start, 0, 5) . ' - ' . substr($sess->time_end, 0, 5) . ' WIB',
                                    'location' => $sess->location,
                                    'type' => ucwords(str_replace('_', ' ', $sess->session_type)),
                                ]) }})"
                                class="btn btn-outline btn-xs"
                                style="font-size: 0.75rem; padding: 0.35rem 0.75rem; border-radius: 8px; font-weight: 700; color: #0284c7; border: 1.5px solid #bae6fd; background: #ffffff; cursor: pointer;">
                            <i class="fas fa-book-open"></i> Rangkuman Penuh
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 2.5rem 1.5rem; background: #ffffff; border-radius: var(--radius-xl); border: 1px dashed #cbd5e1; color: #64748b;">
            <i class="fas fa-calendar-xmark" style="font-size: 1.75rem; color: #94a3b8; margin-bottom: 0.5rem; display: block;"></i>
            Belum ada catatan materi pertemuan divisi yang diarsipkan.
        </div>
    @endif
</div>

<!-- Section: Visi & Misi Divisi -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
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

<!-- Modal Rangkuman Silabus Materi Pertemuan -->
<div id="silabusMateriModal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 1.25rem;">
    <div style="background: #ffffff; border-radius: 20px; max-width: 680px; width: 100%; border: 1.5px solid #e2e8f0; box-shadow: 0 25px 50px rgba(0,0,0,0.25); overflow: hidden; animation: modalFadeIn 0.2s ease;">
        <div style="background: linear-gradient(135deg, #f8fafc, #f1f5f9); padding: 1.35rem 1.75rem; border-bottom: 1.5px solid #e2e8f0; display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
            <div>
                <span id="silabusModalType" class="badge" style="background: #e0f2fe; color: #0284c7; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; margin-bottom: 0.45rem; display: inline-block; border: 1px solid #bae6fd; border-radius: 9999px; padding: 0.25rem 0.75rem;">
                    Workshop Teknis
                </span>
                <h3 id="silabusModalTitle" style="font-size: 1.3rem; font-weight: 900; color: #0c2340; margin: 0; line-height: 1.3;">
                    Judul Sesi
                </h3>
                <div style="font-size: 0.825rem; color: #64748b; margin-top: 0.35rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <span><i class="fas fa-calendar" style="color: #0284c7; margin-right: 0.25rem;"></i><span id="silabusModalDate">Tanggal</span></span>
                    <span><i class="fas fa-clock" style="color: #0284c7; margin-right: 0.25rem;"></i><span id="silabusModalTime">Waktu</span></span>
                    <span><i class="fas fa-location-dot" style="color: #0284c7; margin-right: 0.25rem;"></i><span id="silabusModalLocation">Lokasi</span></span>
                </div>
            </div>
            <button type="button" onclick="closeSilabusMateriModal()" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 50%; width: 34px; height: 34px; font-size: 1.25rem; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; line-height: 1;">
                &times;
            </button>
        </div>

        <div style="padding: 1.5rem 1.75rem; max-height: 70vh; overflow-y: auto;">
            <div style="margin-bottom: 1.25rem; background: #f0f9ff; border: 1.5px solid #bae6fd; border-radius: 14px; padding: 1.15rem 1.35rem;">
                <div style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #0284c7; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                    Pokok Bahasan / Topik Pertemuan:
                </div>
                <div id="silabusModalTopic" style="font-weight: 800; font-size: 1.1rem; color: #0c2340; line-height: 1.45;">
                    Topik
                </div>
                <div style="font-size: 0.825rem; color: #0369a1; margin-top: 0.45rem; font-weight: 600;">
                    Instruktur / Pemateri: <span id="silabusModalInstructor" style="color: #0c2340; font-weight: 800;">-</span>
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem; display: flex; align-items: gap: 0.4rem;">
                    <i class="fas fa-graduation-cap" style="color: #0284c7;"></i> Target & Capaian Pembelajaran:
                </h4>
                <div id="silabusModalOutcomes" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 1.1rem 1.25rem; font-size: 0.885rem; color: #334155; line-height: 1.7; white-space: pre-line;">
                    Capaian
                </div>
            </div>

            <div id="silabusModalNotesContainer" style="margin-bottom: 0.5rem;">
                <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                    <i class="fas fa-clipboard-list" style="color: #10b981;"></i> Catatan Khusus & Arahan Divisi:
                </h4>
                <div id="silabusModalNotes" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 1rem 1.25rem; font-size: 0.865rem; color: #475569; line-height: 1.65;">
                    Catatan
                </div>
            </div>
        </div>

        <div style="background: #f8fafc; padding: 1rem 1.75rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
            <button type="button" onclick="closeSilabusMateriModal()" class="btn btn-primary btn-sm" style="border-radius: 9999px; padding: 0.55rem 1.75rem; font-weight: 800; background: linear-gradient(135deg, #0284c7, #0369a1); border: none; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);">
                Tutup Jendela
            </button>
        </div>
    </div>
</div>

<script>
function openSilabusMateriModal(data) {
    document.getElementById('silabusModalType').innerText = data.type || 'Sesi Pertemuan';
    document.getElementById('silabusModalTitle').innerText = data.title || 'Detail Sesi Pertemuan';
    document.getElementById('silabusModalDate').innerText = data.date || '-';
    document.getElementById('silabusModalTime').innerText = data.time || '-';
    document.getElementById('silabusModalLocation').innerText = data.location || '-';
    document.getElementById('silabusModalTopic').innerText = data.topic || data.title || '-';
    document.getElementById('silabusModalInstructor').innerText = data.instructor || 'Pengurus Divisi';
    document.getElementById('silabusModalOutcomes').innerText = data.outcomes || 'Peserta mempelajari topik bahasan tertera secara mendalam.';
    
    const notesElem = document.getElementById('silabusModalNotes');
    const notesContainer = document.getElementById('silabusModalNotesContainer');
    if (data.notes && data.notes.trim() !== '') {
        notesElem.innerText = data.notes;
        notesContainer.style.display = 'block';
    } else {
        notesContainer.style.display = 'none';
    }

    const modal = document.getElementById('silabusMateriModal');
    modal.style.display = 'flex';
}

function closeSilabusMateriModal() {
    document.getElementById('silabusMateriModal').style.display = 'none';
}

window.addEventListener('click', function(e) {
    const modal = document.getElementById('silabusMateriModal');
    if (e.target === modal) {
        closeSilabusMateriModal();
    }
});
</script>

@endsection
