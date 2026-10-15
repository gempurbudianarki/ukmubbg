<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NavigationAdminController extends Controller
{
    public const NAV_ITEMS = [
        'nav_home' => [
            'label' => 'Beranda',
            'route' => 'home',
            'url' => '/',
            'icon' => 'fas fa-house',
            'desc' => 'Tautan ke halaman utama portal UKM Ilmu Komputer.',
            'category' => 'Navigasi Inti',
        ],
        'nav_divisions' => [
            'label' => 'Divisi Keahlian',
            'route' => 'divisions.index',
            'url' => '/divisi',
            'icon' => 'fas fa-layer-group',
            'desc' => 'Katalog bidang spesialisasi teknologi dan profil divisi.',
            'category' => 'Navigasi Inti',
        ],
        'nav_projects' => [
            'label' => 'Karya Mahasiswa',
            'route' => 'projects.index',
            'url' => '/proyek',
            'icon' => 'fas fa-laptop-code',
            'desc' => 'Etalase portofolio produk dan aplikasi ciptaan mahasiswa UKM.',
            'category' => 'Eksplorasi Karya',
        ],
        'nav_posts' => [
            'label' => 'Berita & Artikel',
            'route' => 'posts.index',
            'url' => '/berita',
            'icon' => 'fas fa-newspaper',
            'desc' => 'Publikasi artikel teknologi, tutorial, dan kabar rilis.',
            'category' => 'Media Publikasi',
        ],
        'nav_events' => [
            'label' => 'Agenda & Event',
            'route' => 'events.index',
            'url' => '/events',
            'icon' => 'fas fa-calendar-days',
            'desc' => 'Jadwal seminar, workshop mingguan, dan bootcamp komunitas.',
            'category' => 'Aktivitas Kampus',
        ],
        'nav_officers' => [
            'label' => 'Struktur Pengurus',
            'route' => 'officers.index',
            'url' => '/pengurus',
            'icon' => 'fas fa-sitemap',
            'desc' => 'Bagan struktur kepengurusan UKM dan pembina fakultas.',
            'category' => 'Organisasi',
        ],
        'nav_galleries' => [
            'label' => 'Galeri Dokumentasi',
            'route' => 'galleries.index',
            'url' => '/galeri',
            'icon' => 'fas fa-images',
            'desc' => 'Dokumentasi visual potret kegiatan dan momen UKM.',
            'category' => 'Media Publikasi',
        ],
        'nav_verify' => [
            'label' => 'Verifikasi Sertifikat & Anggota',
            'route' => 'certificates.verify',
            'url' => '/verifikasi',
            'icon' => 'fas fa-shield-halved',
            'desc' => 'Mesin pemindai validitas QR Code e-sertifikat dan status keanggotaan.',
            'category' => 'Layanan & Validasi',
        ],
        'nav_recruitment' => [
            'label' => 'Tombol Open Recruitment (Oprec)',
            'route' => 'recruitment.index',
            'url' => '/pendaftaran',
            'icon' => 'fas fa-user-plus',
            'desc' => 'Tombol pulse CTA pendaftaran anggota baru di navbar desktop dan mobile.',
            'category' => 'Tombol Aksi Utama',
        ],
    ];

    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Akses terbatas untuk Super Admin.');
        }

        $items = [];
        foreach (self::NAV_ITEMS as $key => $meta) {
            $items[$key] = array_merge($meta, [
                'key' => $key,
                'is_active' => Setting::get($key, '1') === '1',
            ]);
        }

        $activeCount = count(array_filter($items, fn($i) => $i['is_active']));
        $totalCount = count($items);

        return view('admin.navigation.index', compact('items', 'activeCount', 'totalCount'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Akses terbatas untuk Super Admin.');
        }

        // Quick action: enable all or reset
        if ($request->input('action') === 'enable_all' || $request->input('action') === 'reset') {
            foreach (array_keys(self::NAV_ITEMS) as $key) {
                Setting::set($key, '1');
            }
            return redirect()->route('admin.navigation.index')->with('success', 'Semua menu navigasi publik berhasil diaktifkan kembali!');
        }

        if ($request->input('action') === 'disable_all') {
            foreach (array_keys(self::NAV_ITEMS) as $key) {
                // Keep home enabled for safety, disable rest
                Setting::set($key, $key === 'nav_home' ? '1' : '0');
            }
            return redirect()->route('admin.navigation.index')->with('success', 'Semua menu opsional berhasil dinonaktifkan (Beranda tetap aktif).');
        }

        // Standard save
        foreach (array_keys(self::NAV_ITEMS) as $key) {
            $val = $request->input($key, '0');
            $isActive = ($val === '1' || $val === 'on' || $val === true);
            Setting::set($key, $isActive ? '1' : '0');
        }

        return redirect()->route('admin.navigation.index')->with('success', 'Pengaturan keterlihatan menu navigasi publik berhasil disimpan!');
    }
}
