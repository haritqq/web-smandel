<?php 
    $page_title = "Layanan Akademik";
    $current_page = "akademik"; 

    // Data Pillar Utama Akademik
    $sub_menu = [
        [
            'title' => 'Kurikulum Pembelajaran',
            'desc'  => 'Informasi penerapan Kurikulum Merdeka, capaian pembelajaran, dan metode pengajaran interaktif.',
            'icon'  => 'book-open',
            'link'  => 'kurikulum.php'
        ],
        [
            'title' => 'Kalender Akademik',
            'desc'  => 'Jadwal KBM, ujian semester, agenda kegiatan sekolah, dan hari libur nasional.',
            'icon'  => 'calendar',
            'link'  => 'kalender.php'
        ],
        [
            'title' => 'Jadwal Pelajaran',
            'desc'  => 'Informasi alokasi waktu dan tautan unduhan dokumen jadwal KBM harian tiap kelas.',
            'icon'  => 'clock',
            'link'  => 'jadwal.php'
        ],
        [
            'title' => 'Guru & Staf Pendidik',
            'desc'  => 'Direktori tenaga pendidik profesional yang siap mendampingi proses belajar siswa.',
            'icon'  => 'users',
            'link'  => 'guru.php'
        ]
    ];

    // Data Pengumuman Terbaru
    $pengumuman = [
        [
            'tgl' => '25 Agu 2026',
            'judul' => 'Jadwal Penilaian Tengah Semester (PTS) Ganjil',
            'ringkasan' => 'Pelaksanaan PTS Ganjil akan dimulai pertengahan September. Silakan unduh jadwal resmi.'
        ],
        [
            'tgl' => '10 Agu 2026',
            'judul' => 'Panduan Akses Portal E-Learning Pembelajaran',
            'ringkasan' => 'Bagi siswa baru, silakan aktifkan akun E-Learning melalui panduan teknis berikut.'
        ]
    ];

    include 'includes/header.php'; 
?>

<!-- LINK CSS KHUSUS AKADEMIK -->
<link rel="stylesheet" href="assets/css/akademik.css">

<!-- PAGE BANNER (Sesuai gaya Banner Fasilitas) -->
<section class="page-banner">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            <span>Akademik</span>
        </div>
        <h1 class="page-title">Layanan Akademik</h1>
    </div>
</section>

<!-- MAIN CONTENT SECTION -->
<section class="academic-section">
    <div class="container">
        
        <!-- Header Section -->
        <div class="section-header" style="text-align: center; max-width: 700px; margin: 0 auto 50px auto;">
            <span class="section-tag">Pendidikan & Pembelajaran</span>
            <h2 class="section-title">Pilar Utama Akademik</h2>
            <p>Mewujudkan pendidikan berkualitas, inovatif, dan berkarakter melalui sistem pembelajaran terstruktur.</p>
        </div>

        <!-- 1. GRID PILAR UTAMA (Komposisi Pertama, Style Fasilitas) -->
        <div class="academic-grid">
            <?php foreach ($sub_menu as $item): ?>
                <div class="academic-card">
                    <div class="academic-icon">
                        <i data-lucide="<?= $item['icon']; ?>"></i>
                    </div>
                    <h3><?= $item['title']; ?></h3>
                    <p><?= $item['desc']; ?></p>
                    <a href="<?= $item['link']; ?>" class="btn-detail">
                        Lihat Selengkapnya <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- 2. LAYOUT 2 KOLOM (Pengumuman & Quick Download) -->
        <div class="two-column-layout">
            
            <!-- Kolom Kiri: Pengumuman Akademik -->
            <div class="kolom">
                <div class="column-header">
                    <span class="section-tag">Informasi Terkini</span>
                    <h3>Pengumuman Akademik</h3>
                </div>
                <div class="list-pengumuman">
                    <?php foreach ($pengumuman as $info): ?>
                        <article class="item-pengumuman">
                            <span class="tanggal-badge">
                                <i data-lucide="calendar" style="width: 14px; height: 14px;"></i> <?= $info['tgl']; ?>
                            </span>
                            <h4><a href="#"><?= $info['judul']; ?></a></h4>
                            <p><?= $info['ringkasan']; ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Kolom Kanan: Quick Download & Banner Portal -->
            <div class="kolom">
                <div class="column-header">
                    <span class="section-tag">Akses Cepat</span>
                    <h3>Unduh Dokumen & Portal</h3>
                </div>
                <div class="box-download">
                    <div class="download-item">
                        <div class="download-info">
                            <i data-lucide="file-text" class="icon-file"></i>
                            <span>Kalender Akademik 2026/2027 (PDF)</span>
                        </div>
                        <a href="#" class="btn-download">Unduh</a>
                    </div>
                    <div class="download-item">
                        <div class="download-info">
                            <i data-lucide="file-text" class="icon-file"></i>
                            <span>Jadwal KBM Semester Ganjil (PDF)</span>
                        </div>
                        <a href="#" class="btn-download">Unduh</a>
                    </div>

                    <!-- Banner E-Learning dengan Warna Tema -->
                    <div class="portal-banner">
                        <div class="portal-content">
                            <i data-lucide="laptop" class="portal-icon"></i>
                            <h4>Akses Portal E-Learning</h4>
                            <p>Masuk ke platform pembelajaran digital siswa dan guru.</p>
                            <a href="#" class="btn-portal">Buka E-Learning</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>