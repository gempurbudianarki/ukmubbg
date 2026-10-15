@extends('admin.layouts.app')

@section('title', 'Manajemen Navigasi & Menu Publik - UKM CMS')
@section('page_title', 'Navigasi & Menu Publik')

@section('content')
<div class="admin-container" style="max-width: 1200px; margin: 0 auto;">
    <!-- Hero Header Banner -->
    <div style="background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%); border-radius: 24px; padding: 2rem 2.25rem; border: 1.5px solid #dbeafe; box-shadow: var(--clay-card); margin-bottom: 2rem; position: relative; overflow: hidden;">
        <div style="position: absolute; right: -25px; bottom: -25px; opacity: 0.08; font-size: 11rem; color: #0284c7; pointer-events: none;">
            <i class="fas fa-bars-staggered"></i>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; position: relative; z-index: 1;">
            <div style="max-width: 720px;">
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 0.95rem; background: #e0f2fe; color: #0369a1; border-radius: 9999px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.85rem; border: 1px solid #bae6fd;">
                    <i class="fas fa-sliders"></i>
                    <span>KONTROL VISIBILITAS KONTEN PUBLIK</span>
                </div>
                <h1 style="font-size: 1.85rem; font-weight: 900; color: #0f172a; margin: 0 0 0.65rem; letter-spacing: -0.02em; line-height: 1.25;">
                    Pengaturan Menu & Tombol Header Web
                </h1>
                <p style="font-size: 0.95rem; color: #475569; margin: 0; line-height: 1.6;">
                    Aktifkan atau sembunyikan tombol navigasi utama pada header portal publik (desktop &amp; mobile drawer) secara real-time. Jika dinonaktifkan, tombol akan langsung hilang tanpa merusak struktur database.
                </p>
            </div>

            <!-- Stats Metric Pill -->
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; padding: 1.15rem 1.6rem; border-radius: 18px; box-shadow: var(--clay-pill); text-align: center;">
                    <div style="font-size: 1.85rem; font-weight: 900; color: #0284c7; line-height: 1;">
                        {{ $activeCount }} <span style="font-size: 1rem; color: #64748b; font-weight: 700;">/ {{ $totalCount }}</span>
                    </div>
                    <div style="font-size: 0.725rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-top: 0.35rem; letter-spacing: 0.04em;">
                        Menu Tampil Aktif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Message -->
    @if(session('success'))
        <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 16px; padding: 1rem 1.25rem; color: #065f46; font-size: 0.875rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.75rem; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.08);">
            <i class="fas fa-circle-check" style="font-size: 1.2rem; color: #10b981;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Live Preview Bar -->
    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 1.25rem 1.75rem; margin-bottom: 2rem; box-shadow: var(--clay-card);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">
                <i class="fas fa-eye" style="color: #0284c7;"></i>
                <span>Simulasi Header Publik Saat Ini:</span>
            </div>
            <a href="{{ route('home') }}" target="_blank" style="font-size: 0.75rem; font-weight: 700; color: #0284c7; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                <span>Lihat Web Langsung</span>
                <i class="fas fa-external-link-alt"></i>
            </a>
        </div>

        <div style="background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 14px; padding: 0.85rem 1.25rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 24px; width: auto;">
                <span style="font-weight: 800; font-size: 0.85rem; color: #0f172a;">UKM ILKOM</span>
            </div>

            <!-- Preview Link Badges -->
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                @foreach($items as $item)
                    @if($item['key'] !== 'nav_recruitment')
                        <span style="padding: 0.35rem 0.7rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; transition: all 0.2s ease; {{ $item['is_active'] ? 'background: #ffffff; color: #0f172a; border: 1px solid #cbd5e1; box-shadow: var(--clay-pill);' : 'background: #f1f5f9; color: #94a3b8; border: 1px dashed #cbd5e1; text-decoration: line-through; opacity: 0.6;' }}">
                            {{ $item['label'] }}
                        </span>
                    @endif
                @endforeach

                @php $oprec = $items['nav_recruitment'] ?? null; @endphp
                @if($oprec)
                    <span style="padding: 0.35rem 0.85rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 800; transition: all 0.2s ease; {{ $oprec['is_active'] ? 'background: linear-gradient(135deg, #0284c7, #0369a1); color: #ffffff; box-shadow: 0 2px 8px rgba(2, 132, 199, 0.35);' : 'background: #f1f5f9; color: #94a3b8; border: 1px dashed #cbd5e1; text-decoration: line-through; opacity: 0.6;' }}">
                        <i class="fas fa-user-plus" style="font-size: 0.65rem; margin-right: 0.25rem;"></i> Oprec
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Navigation Management Form -->
    <form action="{{ route('admin.navigation.update') }}" method="POST" id="navControlForm">
        @csrf

        <!-- Quick Action Toolbar -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="font-size: 0.875rem; font-weight: 800; color: #334155; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-list-check" style="color: #0284c7;"></i>
                <span>Daftar 9 Tombol &amp; Tautan Header</span>
            </div>

            <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                <button type="submit" name="action" value="enable_all" class="btn btn-outline btn-sm" style="background: #ffffff; border: 1.5px solid #cbd5e1; color: #0284c7; font-weight: 700; border-radius: 9999px; padding: 0.5rem 1rem; box-shadow: var(--clay-pill);">
                    <i class="fas fa-check-double" style="margin-right: 0.35rem;"></i>
                    <span>Aktifkan Semua</span>
                </button>
                <button type="submit" name="action" value="reset" class="btn btn-outline btn-sm" style="background: #ffffff; border: 1.5px solid #cbd5e1; color: #475569; font-weight: 700; border-radius: 9999px; padding: 0.5rem 1rem; box-shadow: var(--clay-pill);">
                    <i class="fas fa-rotate-left" style="margin-right: 0.35rem;"></i>
                    <span>Reset Default</span>
                </button>
                <button type="submit" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, #0284c7, #0369a1); border: none; color: #ffffff; font-weight: 800; border-radius: 9999px; padding: 0.55rem 1.4rem; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);">
                    <i class="fas fa-floppy-disk" style="margin-right: 0.4rem;"></i>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </div>

        <!-- 9 Items Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.25rem; margin-bottom: 2.5rem;">
            @foreach($items as $item)
                <div class="nav-control-card" style="background: #ffffff; border-radius: 20px; padding: 1.5rem; border: 1.5px solid {{ $item['is_active'] ? '#dbeafe' : '#e2e8f0' }}; box-shadow: var(--clay-card); display: flex; flex-direction: column; justify-content: space-between; transition: all 0.25s ease; position: relative;">
                    <div>
                        <!-- Card Header -->
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                <div style="width: 44px; height: 44px; border-radius: 12px; background: {{ $item['is_active'] ? '#f0f9ff' : '#f8fafc' }}; color: {{ $item['is_active'] ? '#0284c7' : '#94a3b8' }}; border: 1.5px solid {{ $item['is_active'] ? '#bae6fd' : '#e2e8f0' }}; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; box-shadow: var(--clay-pill);">
                                    <i class="{{ $item['icon'] }}"></i>
                                </div>
                                <div>
                                    <h3 style="margin: 0; font-size: 1rem; font-weight: 800; color: #0f172a; line-height: 1.25;">
                                        {{ $item['label'] }}
                                    </h3>
                                    <div style="font-size: 0.725rem; font-weight: 700; color: #64748b; margin-top: 0.2rem; font-family: var(--font-mono);">
                                        {{ $item['url'] }} &bull; {{ $item['category'] }}
                                    </div>
                                </div>
                            </div>

                            <!-- iOS Style Clay Toggle Switch -->
                            <label style="position: relative; display: inline-block; width: 50px; height: 28px; cursor: pointer; flex-shrink: 0;">
                                <input type="hidden" name="{{ $item['key'] }}" value="0">
                                <input type="checkbox" name="{{ $item['key'] }}" value="1" {{ $item['is_active'] ? 'checked' : '' }} onchange="updateCardStatus(this)" style="opacity: 0; width: 0; height: 0;">
                                <span class="toggle-slider" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: {{ $item['is_active'] ? '#0284c7' : '#cbd5e1' }}; transition: .3s; border-radius: 34px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.15);">
                                    <span class="toggle-knob" style="position: absolute; content: ''; height: 22px; width: 22px; left: {{ $item['is_active'] ? '24px' : '3px' }}; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; box-shadow: 0 2px 6px rgba(0,0,0,0.25);"></span>
                                </span>
                            </label>
                        </div>

                        <!-- Description -->
                        <p style="font-size: 0.835rem; color: #475569; margin: 0 0 1.25rem; line-height: 1.55;">
                            {{ $item['desc'] }}
                        </p>
                    </div>

                    <!-- Footer Status Pill -->
                    <div style="padding-top: 0.85rem; border-top: 1px dashed #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                        <span class="status-indicator-text" style="font-size: 0.75rem; font-weight: 800; color: {{ $item['is_active'] ? '#16a34a' : '#64748b' }}; display: inline-flex; align-items: center; gap: 0.35rem;">
                            <i class="fas {{ $item['is_active'] ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                            <span>{{ $item['is_active'] ? 'AKTIF (Tampil di Header)' : 'NONAKTIF (Disembunyikan)' }}</span>
                        </span>

                        <span style="font-size: 0.72rem; color: #94a3b8; font-family: var(--font-mono);">
                            {{ $item['key'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sticky Footer Save Floating Bar -->
        <div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border: 1.5px solid #cbd5e1; border-radius: 20px; padding: 1.15rem 1.75rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; position: sticky; bottom: 1.5rem; z-index: 30;">
            <div style="display: flex; align-items: center; gap: 0.65rem;">
                <i class="fas fa-circle-info" style="color: #0284c7; font-size: 1.1rem;"></i>
                <span style="font-size: 0.85rem; color: #334155; font-weight: 600;">
                    Perubahan status akan langsung diterapkan ke seluruh halaman publik UKM begitu tombol ditekan.
                </span>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #0284c7, #0369a1); border: none; color: #ffffff; font-weight: 800; border-radius: 9999px; padding: 0.7rem 2rem; box-shadow: 0 4px 16px rgba(2, 132, 199, 0.4); display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <i class="fas fa-check"></i>
                    <span>Terapkan &amp; Simpan Perubahan</span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function updateCardStatus(checkbox) {
    const card = checkbox.closest('.nav-control-card');
    const slider = checkbox.nextElementSibling;
    const knob = slider.querySelector('.toggle-knob');
    const statusText = card.querySelector('.status-indicator-text');
    
    if (checkbox.checked) {
        slider.style.backgroundColor = '#0284c7';
        knob.style.left = '24px';
        card.style.borderColor = '#dbeafe';
        statusText.style.color = '#16a34a';
        statusText.innerHTML = '<i class="fas fa-circle-check"></i> <span>AKTIF (Tampil di Header)</span>';
    } else {
        slider.style.backgroundColor = '#cbd5e1';
        knob.style.left = '3px';
        card.style.borderColor = '#e2e8f0';
        statusText.style.color = '#64748b';
        statusText.innerHTML = '<i class="fas fa-circle-xmark"></i> <span>NONAKTIF (Disembunyikan)</span>';
    }
}
</script>
@endsection
