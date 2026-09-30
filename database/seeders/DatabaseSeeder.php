<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Divisions
        $divisionsData = [
            [
                'slug' => 'pemrograman',
                'name' => 'Divisi Pemrograman',
                'tagline' => 'Membangun Perangkat Lunak, Solusi Web & Mobile Modern',
                'description' => 'Divisi yang berfokus pada rekayasa perangkat lunak, perancangan web modern, arsitektur backend, aplikasi mobile, dan penguasaan algoritma komputasi untuk menciptakan solusi digital berdampak nyata.',
                'focus_topics' => json_encode([
                    'Fullstack Web Development (Laravel, Vue, React)',
                    'Mobile Application Development (Flutter, Kotlin)',
                    'Algoritma & Competitive Programming (ICPC)',
                    'Database Engineering & Scalable Backend APIs'
                ]),
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>',
                'color_accent' => '#0284c7',
                'vision' => 'Menjadi wadah unggulan mahasiswa Ilmu Komputer dalam menghasilkan inovasi perangkat lunak berskala nasional dan global.',
                'mission' => "1. Menyelenggarakan bootcamp intensif dan mentoring berkala seputar clean code.\n2. Mendorong mahasiswa mengikuti kompetisi hackathon dan gemastik.\n3. Mengembangkan produk aplikasi terbuka untuk kebutuhan kampus dan masyarakat.",
                'adviser_name' => 'Dr. Ir. Hendra Saputra, M.Kom.',
                'adviser_title' => 'Dosen Rekayasa Perangkat Lunak & Sistem Terdistribusi',
                'adviser_photo' => null,
                'leader_name' => 'Muhammad Rayhan Fajar',
                'leader_nim' => '210103045',
                'leader_photo' => null,
                'leader_bio' => 'Salam rekan-rekan pengembang! Di Divisi Pemrograman, kami mengasah logika dan kemampuan problem solving melalui kolaborasi proyek nyata. Jangan ragu untuk bergabung dan memulai karya pertamamu.',
                'social_links' => json_encode([
                    'instagram' => 'https://instagram.com/rayhanfajar.dev',
                    'github' => 'https://github.com/rayhanfajar',
                    'linkedin' => 'https://linkedin.com/in/rayhanfajar'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'multimedia',
                'name' => 'Divisi Multimedia',
                'tagline' => 'Eksplorasi Kreatif, UI/UX Design, Visual Effects & Audio Visual',
                'description' => 'Divisi yang memadukan daya cipta estetika dengan teknologi grafis interaktif. Berfokus pada riset pengalaman pengguna (UI/UX), animasi komputer, sinematografi digital, 3D modelling, dan branding visual.',
                'focus_topics' => json_encode([
                    'UI/UX Research & Interface Prototyping (Figma)',
                    '2D Motion Graphics & Animation (After Effects)',
                    'Cinematography & Audio-Visual Production',
                    '3D Hard Surface & Environment Modelling (Blender)'
                ]),
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>',
                'color_accent' => '#8b5cf6',
                'vision' => 'Mencetak kreator digital yang adaptif, inovatif, dan mampu menyampaikan pesan bermakna melalui visual interaktif berkualitas tinggi.',
                'mission' => "1. Melatih kepekaan visual dan metodologi Human-Centered Design.\n2. Memproduksi konten edukasi dan dokumentasi berkualitas bagi sivitas akademika.\n3. Menjuarai kompetisi desain grafis, animasi, dan festival film pendek.",
                'adviser_name' => 'Rina Anggraini, S.Sn., M.Ds.',
                'adviser_title' => 'Dosen Desain Komunikasi Visual & Media Digital',
                'adviser_photo' => null,
                'leader_name' => 'Aulia Rahma Putri',
                'leader_nim' => '210103082',
                'leader_photo' => null,
                'leader_bio' => 'Kreativitas tidak memiliki batas. Di divisi ini, kita membedah bagaimana desain dapat menyentuh emosi manusia dan mempermudah interaksi digital.',
                'social_links' => json_encode([
                    'instagram' => 'https://instagram.com/auliarahma.art',
                    'behance' => 'https://behance.net/auliarahmaputri',
                    'linkedin' => 'https://linkedin.com/in/auliarahmaputri'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'iot',
                'name' => 'Divisi Internet of Things (IoT)',
                'tagline' => 'Menghubungkan Perangkat Fisik, Mikrokontroler & Komputasi Cerdas',
                'description' => 'Divisi yang mendalami integrasi hardware, mikrokontroler, aktuator, dan protokol jaringan nirkabel. Meneliti otomasi rumah pintar, monitoring sensor telemetri, dan robotika industri.',
                'focus_topics' => json_encode([
                    'Microcontroller Interfacing (ESP32, Arduino, STM32)',
                    'Smart Farming & Smart City Environmental Sensing',
                    'Industrial MQTT Protocol & Real-time WebSockets',
                    'Low-Power Edge Computing & Sensor Hardware Soldering'
                ]),
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>',
                'color_accent' => '#10b981',
                'vision' => 'Menjadi pusat riset dan inovasi perangkat keras cerdas terdepan di lingkungan fakultas Ilmu Komputer.',
                'mission' => "1. Mengasah pemahaman rancang bangun skematik dan PCB hardware.\n2. Mengintegrasikan teknologi cloud dengan sensor fisik.\n3. Berkontribusi aktif pada proyek otomasi dan pameran riset teknologi.",
                'adviser_name' => 'Budi Wicaksono, S.T., M.T.',
                'adviser_title' => 'Dosen Sistem Tertanam, Jaringan & Robotika',
                'adviser_photo' => null,
                'leader_name' => 'Dimas Bagus Nugroho',
                'leader_nim' => '210103112',
                'leader_photo' => null,
                'leader_bio' => 'Dunia fisik dan dunia komputasi saling membutuhkan. Bergabunglah dengan kami untuk mengubah kabel dan sensor sederhana menjadi mesin cerdas yang mampu berpikir!',
                'social_links' => json_encode([
                    'instagram' => 'https://instagram.com/dimasbagus.iot',
                    'github' => 'https://github.com/dimasbagus-tech',
                    'linkedin' => 'https://linkedin.com/in/dimas-bagus-nugroho'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'cyber-security',
                'name' => 'Divisi Cyber Security',
                'tagline' => 'Ketahanan Informasi, Ethical Hacking & Pertahanan Siber',
                'description' => 'Divisi riset keamanan siber yang mengkaji pengujian penetrasi (penetration testing), keamanan jaringan, analisis malware, audit keamanan web, dan pelatihan kompetisi Capture The Flag (CTF).',
                'focus_topics' => json_encode([
                    'Web Application Penetration Testing (OWASP Top 10)',
                    'Network Packet Forensics & Hardening (Wireshark)',
                    'Cryptography & Binary Reverse Engineering',
                    'Competitive CTF (Jeopardy & Attack-Defense)'
                ]),
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>',
                'color_accent' => '#f43f5e',
                'vision' => 'Mencetak praktisi keamanan siber yang berintegritas tinggi, berwawasan mendalam, dan memiliki standar etika profesional pertahanan informasi.',
                'mission' => "1. Menumbuhkan kesadaran keamanan digital di kalangan akademisi.\n2. Melatih ketajaman investigasi insiden dan eksploitasi etis.\n3. Mewakili universitas di panggung kompetisi keamanan siber bergengsi.",
                'adviser_name' => 'Faisal Akbar, M.Cs., CEH',
                'adviser_title' => 'Dosen Keamanan Informasi & Certified Ethical Hacker',
                'adviser_photo' => null,
                'leader_name' => 'Kevin Danuarta',
                'leader_nim' => '210103019',
                'leader_photo' => null,
                'leader_bio' => 'Keamanan bukanlah ilusi, melainkan benteng yang harus kita perkuat setiap hari. Mari pelajari bagaimana sistem bekerja dari dalam untuk melindunginya dari ancaman nyata.',
                'social_links' => json_encode([
                    'instagram' => 'https://instagram.com/kevindanuarta.sec',
                    'github' => 'https://github.com/kevindanuarta',
                    'linkedin' => 'https://linkedin.com/in/kevin-danuarta'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($divisionsData as $div) {
            DB::table('divisions')->insert($div);
        }

        $divPemrogramanId = DB::table('divisions')->where('slug', 'pemrograman')->value('id');
        $divMultimediaId = DB::table('divisions')->where('slug', 'multimedia')->value('id');
        $divIotId = DB::table('divisions')->where('slug', 'iot')->value('id');
        $divCyberId = DB::table('divisions')->where('slug', 'cyber-security')->value('id');

        // 2. Seed Users (Super Admin + 4 Division Admins)
        $users = [
            [
                'name' => 'Super Administrator UKM',
                'email' => 'admin@ukmilkom.id',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
                'division_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Divisi Pemrograman',
                'email' => 'pemrograman@ukmilkom.id',
                'password' => Hash::make('pemrograman123'),
                'role' => 'division_admin',
                'division_id' => $divPemrogramanId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Divisi Multimedia',
                'email' => 'multimedia@ukmilkom.id',
                'password' => Hash::make('multimedia123'),
                'role' => 'division_admin',
                'division_id' => $divMultimediaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Divisi IoT',
                'email' => 'iot@ukmilkom.id',
                'password' => Hash::make('iot123'),
                'role' => 'division_admin',
                'division_id' => $divIotId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Divisi Cyber Security',
                'email' => 'cyber@ukmilkom.id',
                'password' => Hash::make('cyber123'),
                'role' => 'division_admin',
                'division_id' => $divCyberId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);

        $superAdminId = DB::table('users')->where('role', 'super_admin')->value('id');

        // 3. Seed Posts (Publikasi per divisi)
        $samplePosts = [
            [
                'division_id' => $divPemrogramanId,
                'author_id' => $superAdminId,
                'title' => 'Workshop Fullstack Web Modern: Membangun Aplikasi Skalabel dengan Clean Architecture',
                'slug' => 'workshop-fullstack-web-modern-clean-architecture',
                'excerpt' => 'Divisi Pemrograman sukses menggelar pelatihan arsitektur perangkat lunak modern bagi mahasiswa angkatan baru.',
                'content' => '<p>Pada akhir pekan lalu, Divisi Pemrograman menyelenggarakan workshop intensif seputar perancangan backend modern dan implementasi RESTful API. Acara ini dihadiri oleh puluhan mahasiswa yang antusias mempraktikkan Clean Code dan Design Pattern.</p><p>Melalui workshop ini, peserta dibekali kemampuan dasar refactoring kode, penggunaan ORM secara efisien, serta automated testing untuk memastikan keandalan sistem.</p>',
                'thumbnail' => null,
                'category' => 'kegiatan',
                'status' => 'published',
                'views_count' => 142,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'division_id' => $divMultimediaId,
                'author_id' => $superAdminId,
                'title' => 'Eksplorasi Design System: Memadukan Konsistensi & Estetika dalam Produk Digital',
                'slug' => 'eksplorasi-design-system-konsistensi-estetika',
                'excerpt' => 'Pelajari bagaimana Divisi Multimedia menyusun panduan desain antarmuka komprehensif menggunakan Figma Token.',
                'content' => '<p>Desain yang baik bukan hanya tentang estetika visual, melainkan juga tentang konsistensi interaksi pengguna. Divisi Multimedia merilis kajian komparatif tentang penerapan Atomic Design dalam membangun antarmuka web modern.</p><p>Artikel ini merangkum dasar-dasar hierarki tipografi, grid system, dan accessibility ratio yang wajib dipahami oleh setiap desainer grafis.</p>',
                'thumbnail' => null,
                'category' => 'tutorial',
                'status' => 'published',
                'views_count' => 98,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'division_id' => $divIotId,
                'author_id' => $superAdminId,
                'title' => 'Implementasi Sensor Lingkungan Berbasis ESP32 dan Dashboard Telemetri Real-Time',
                'slug' => 'sensor-lingkungan-esp32-telemetri-realtime',
                'excerpt' => 'Riset kolaboratif Divisi IoT dalam merancang stasiun cuaca mini yang terhubung langsung ke server cloud.',
                'content' => '<p>Tim riset Divisi IoT berhasil menyelesaikan prototipe sistem pemantauan kualitas udara dan suhu ruang laboratorium kampus. Sistem ini ditenagai mikrokontroler ESP32 dengan protokol MQTT berdaya rendah.</p><p>Data sensor dikirimkan setiap 5 detik dan dapat dipantau langsung melalui antarmuka web interaktif oleh seluruh pengurus laboratorium.</p>',
                'thumbnail' => null,
                'category' => 'proyek',
                'status' => 'published',
                'views_count' => 210,
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ],
            [
                'division_id' => $divCyberId,
                'author_id' => $superAdminId,
                'title' => 'Analisis Kerentanan Web Application: Mengenal OWASP Top 10 dan Mitigasi Dini',
                'slug' => 'analisis-kerentanan-owasp-top-10-mitigasi',
                'excerpt' => 'Panduan pengujian penetrasi etis dari Divisi Cyber Security untuk mengamankan data pengguna di aplikasi web.',
                'content' => '<p>Dalam rangka memperingati Bulan Kesadaran Keamanan Siber, Divisi Cyber Security mempublikasikan artikel edukatif seputar celah keamanan paling umum seperti SQL Injection, Cross-Site Scripting (XSS), dan Broken Access Control.</p><p>Melalui artikel ini, pembaca diajak memahami teknik pertahanan berlapis (defense in depth) yang harus diterapkan sejak tahap penulisan kode.</p>',
                'thumbnail' => null,
                'category' => 'tutorial',
                'status' => 'published',
                'views_count' => 315,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ];

        DB::table('posts')->insert($samplePosts);

        // 4. Seed Settings
        $settings = [
            ['key_name' => 'site_name', 'value' => 'UKM Ilmu Komputer', 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'site_tagline', 'value' => 'Wadah Riset, Kreativitas, & Inovasi Teknologi Mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'recruitment_status', 'value' => 'open', 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'recruitment_batch', 'value' => 'Gelombang I (2026/2027)', 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'recruitment_deadline', 'value' => '31 Oktober 2026', 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'contact_email', 'value' => 'sekretariat@ukmilkom.id', 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'contact_whatsapp', 'value' => '081234567890', 'created_at' => now(), 'updated_at' => now()],
            ['key_name' => 'contact_address', 'value' => 'Gedung Laboratorium Komputer Terpadu Lt. 3, Kampus Ilmu Komputer', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('settings')->insert($settings);

        // 5. Seed Projects (Showcase Karya)
        $sampleProjects = [
            [
                'division_id' => $divPemrogramanId,
                'title' => 'EduClass: Platform LMS & Evaluasi Otomatis Mahasiswa',
                'slug' => 'educlass-lms-evaluasi-otomatis',
                'description' => 'Sistem manajemen pembelajaran daring terintegrasi dengan online code judge (sandbox) untuk evaluasi praktikum pemrograman mahasiswa secara real-time.',
                'author_names' => 'Muhammad Rayhan, Fikri Haikal, Nadya Safira',
                'tech_stack' => json_encode(['Laravel 10', 'Vue 3', 'Docker', 'PostgreSQL']),
                'demo_url' => 'https://educlass.ukmilkom.id',
                'repo_url' => 'https://github.com/ukmilkom/educlass-lms',
                'thumbnail' => 'images/project_web.jpg',
                'is_featured' => true,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'division_id' => $divMultimediaId,
                'title' => 'Nusantara Heritage: Virtual Museum & Interactive 3D Artifacts',
                'slug' => 'nusantara-heritage-virtual-3d',
                'description' => 'Riset eksplorasi antarmuka WebGL dan 3D environment modelling cagar budaya nasional, dilengkapi spatial audio dan UI interaktif.',
                'author_names' => 'Aulia Rahma Putri, Bima Arya, Cindy Permata',
                'tech_stack' => json_encode(['Three.js', 'Blender', 'Figma', 'Web Audio API']),
                'demo_url' => 'https://nusantara3d.ukmilkom.id',
                'repo_url' => 'https://github.com/ukmilkom/nusantara-3d',
                'thumbnail' => 'images/project_multimedia.jpg',
                'is_featured' => true,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'division_id' => $divIotId,
                'title' => 'Smart GreenHouse & Telemetry Environment Node',
                'slug' => 'smart-greenhouse-telemetry-node',
                'description' => 'Sistem monitoring mikroklimat rumah kaca otomatis berbasis ESP32, mengukur kelembapan tanah, suhu, dan intensitas cahaya dengan kontrol pompa air pintar.',
                'author_names' => 'Dimas Bagus Nugroho, Hendri Kurniawan',
                'tech_stack' => json_encode(['ESP32', 'FreeRTOS', 'MQTT Protocol', 'Chart.js']),
                'demo_url' => 'https://greenhouse.ukmilkom.id',
                'repo_url' => 'https://github.com/ukmilkom/smart-greenhouse',
                'thumbnail' => 'images/project_iot.jpg',
                'is_featured' => true,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            [
                'division_id' => $divCyberId,
                'title' => 'VulneraScan: Automated Web Vulnerability & Header Auditor',
                'slug' => 'vulnerascan-automated-web-auditor',
                'description' => 'Alat audit keamanan aplikasi web otomatis untuk mendeteksi miskonfigurasi CORS, CSP, security headers, dan endpoint exposed sensitif.',
                'author_names' => 'Kevin Danuarta, Aditya Nugraha',
                'tech_stack' => json_encode(['Python 3', 'Go', 'OWASP ZAP Core', 'FastAPI']),
                'demo_url' => null,
                'repo_url' => 'https://github.com/ukmilkom/vulnerascan-engine',
                'thumbnail' => 'images/project_cyber.jpg',
                'is_featured' => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
        ];

        DB::table('projects')->insert($sampleProjects);

        // 6. Seed Events (Agenda & Workshop)
        $sampleEvents = [
            [
                'division_id' => $divPemrogramanId,
                'title' => 'National Hackathon & Code Fest 2026: AI & Scalable Web',
                'slug' => 'national-hackathon-code-fest-2026',
                'description' => 'Ajang kompetisi coding 48 jam antar mahasiswa untuk melahirkan inovasi digital solusi permasalahan kampus.',
                'banner_image' => 'images/event_hackathon.jpg',
                'event_date' => now()->addDays(14)->format('Y-m-d'),
                'time_start' => '08:30:00',
                'time_end' => '17:00:00',
                'location_type' => 'hybrid',
                'location_venue' => 'Auditorium Utama & Zoom Online',
                'registration_link' => 'https://bit.ly/hackathon-ilkom-2026',
                'max_participants' => 120,
                'status' => 'upcoming',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'division_id' => $divCyberId,
                'title' => 'Hands-On Bootcamp: Web Penetration Testing & CTF Strategy',
                'slug' => 'hands-on-bootcamp-web-pentesting',
                'description' => 'Pelatihan intensif praktik langsung membedah eksploitasi web keamanan informasi dan tips juara kompetisi CTF.',
                'banner_image' => 'images/project_cyber.jpg',
                'event_date' => now()->addDays(21)->format('Y-m-d'),
                'time_start' => '09:00:00',
                'time_end' => '15:30:00',
                'location_type' => 'offline',
                'location_venue' => 'Lab Jaringan & Cyber Security Lt. 2',
                'registration_link' => 'https://bit.ly/bootcamp-cyber-2026',
                'max_participants' => 40,
                'status' => 'upcoming',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'division_id' => $divMultimediaId,
                'title' => 'Masterclass UI/UX Design System: Dari Wireframe ke High-Fidelity',
                'slug' => 'masterclass-ui-ux-design-system',
                'description' => 'Kupas tuntas metodologi desain antarmuka profesional menggunakan Figma Variables, Auto Layout, dan Interactive Prototyping.',
                'banner_image' => 'images/project_multimedia.jpg',
                'event_date' => now()->subDays(12)->format('Y-m-d'),
                'time_start' => '13:00:00',
                'time_end' => '16:00:00',
                'location_type' => 'offline',
                'location_venue' => 'Ruang Multimedia Kreatif',
                'registration_link' => null,
                'max_participants' => 50,
                'status' => 'completed',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(12),
            ],
        ];

        DB::table('events')->insert($sampleEvents);

        // 7. Seed Officers (Struktur Organisasi)
        $sampleOfficers = [
            [
                'name' => 'Dr. Ir. Hendra Saputra, M.Kom.',
                'nim' => '198004122005011002',
                'period' => '2026/2027',
                'department_level' => 'bph',
                'position' => 'Dosen Pembina UKM',
                'photo' => null,
                'social_links' => json_encode(['linkedin' => 'https://linkedin.com']),
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fathan Al-Ghifari',
                'nim' => '210103001',
                'period' => '2026/2027',
                'department_level' => 'bph',
                'position' => 'Ketua Umum UKM',
                'photo' => null,
                'social_links' => json_encode(['linkedin' => 'https://linkedin.com', 'github' => 'https://github.com']),
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sarah Amanda',
                'nim' => '210103014',
                'period' => '2026/2027',
                'department_level' => 'bph',
                'position' => 'Sekretaris Umum',
                'photo' => null,
                'social_links' => json_encode(['linkedin' => 'https://linkedin.com']),
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reza Pratama',
                'nim' => '210103025',
                'period' => '2026/2027',
                'department_level' => 'bph',
                'position' => 'Bendahara Umum',
                'photo' => null,
                'social_links' => json_encode(['linkedin' => 'https://linkedin.com']),
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Muhammad Rayhan Fajar',
                'nim' => '210103045',
                'period' => '2026/2027',
                'department_level' => 'pemrograman',
                'position' => 'Koordinator Divisi Pemrograman',
                'photo' => null,
                'social_links' => json_encode(['github' => 'https://github.com/rayhanfajar']),
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aulia Rahma Putri',
                'nim' => '210103082',
                'period' => '2026/2027',
                'department_level' => 'multimedia',
                'position' => 'Koordinator Divisi Multimedia',
                'photo' => null,
                'social_links' => json_encode(['instagram' => 'https://instagram.com/auliarahma.art']),
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dimas Bagus Nugroho',
                'nim' => '210103112',
                'period' => '2026/2027',
                'department_level' => 'iot',
                'position' => 'Koordinator Divisi IoT',
                'photo' => null,
                'social_links' => json_encode(['github' => 'https://github.com/dimasbagus-tech']),
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kevin Danuarta',
                'nim' => '210103019',
                'period' => '2026/2027',
                'department_level' => 'cyber',
                'position' => 'Koordinator Divisi Cyber Security',
                'photo' => null,
                'social_links' => json_encode(['linkedin' => 'https://linkedin.com/in/kevin-danuarta']),
                'sort_order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('officers')->insert($sampleOfficers);

        // 8. Seed Galleries (Momen & Dokumentasi)
        $sampleGalleries = [
            [
                'title' => 'Pelaksanaan Musyawarah Besar & Pelantikan Pengurus Periode 2026/2027',
                'category' => 'Organisasi',
                'image_path' => 'images/gallery_students.jpg',
                'caption' => 'Penetapan program kerja tahunan dan serah terima jabatan ketua umum UKM.',
                'event_date' => now()->subMonths(1)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Juara 1 Kompetisi CTF Cyber Defense Tingkat Regional',
                'category' => 'Prestasi',
                'image_path' => 'images/project_cyber.jpg',
                'caption' => 'Tim Divisi Cyber Security berhasil meraih podium utama dalam kompetisi jeopardy CTF.',
                'event_date' => now()->subDays(20)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sesi Praktik Solder & Perakitan Node Sensor Divisi IoT',
                'category' => 'Workshop',
                'image_path' => 'images/project_iot.jpg',
                'caption' => 'Antusiasme mahasiswa baru dalam merangkai sensor lingkungan mandiri.',
                'event_date' => now()->subDays(8)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Studi Banding & Kunjungan Industri ke Tech Hub Digital',
                'category' => 'Kunjungan',
                'image_path' => 'images/event_hackathon.jpg',
                'caption' => 'Mengenal ekosistem pengembangan perangkat lunak skala enterprise.',
                'event_date' => now()->subMonths(2)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('galleries')->insert($sampleGalleries);

        // 9. Seed Certificates (Verifikasi E-Sertifikat)
        $sampleCerts = [
            [
                'certificate_code' => 'CERT-ILKOM-2026-0812',
                'recipient_name' => 'Bintang Ramadhan',
                'recipient_nim' => '220104012',
                'recipient_email' => 'bintang@kampus.ac.id',
                'event_name' => 'Workshop Fullstack Web Modern: Clean Architecture',
                'role_as' => 'Peserta Aktif',
                'issue_date' => now()->subDays(2)->format('Y-m-d'),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'certificate_code' => 'CERT-ILKOM-2026-0815',
                'recipient_name' => 'Siti Nurhaliza',
                'recipient_nim' => '220104055',
                'recipient_email' => 'siti@kampus.ac.id',
                'event_name' => 'Masterclass UI/UX Design System',
                'role_as' => 'Peserta Aktif',
                'issue_date' => now()->subDays(12)->format('Y-m-d'),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'certificate_code' => 'CERT-ILKOM-2026-0820',
                'recipient_name' => 'Muhammad Rayhan Fajar',
                'recipient_nim' => '210103045',
                'recipient_email' => 'rayhanfajar.dev@gmail.com',
                'event_name' => 'Workshop Fullstack Web Modern: Clean Architecture',
                'role_as' => 'Pemateri Utama',
                'issue_date' => now()->subDays(2)->format('Y-m-d'),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('certificates')->insert($sampleCerts);
    }
}
