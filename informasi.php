<?php 
    $page_title = "Pusat Informasi & Prestasi";
    $current_page = "informasi"; 

    // Data 4 Pilar Utama Informasi (Termasuk Prestasi Siswa)
    $sub_menu = [
        [
            'title' => 'Berita & Artikel',
            'desc'  => 'Liputan kegiatan sekolah, artikel edukatif, dan publikasi acara terkini.',
            'icon'  => 'newspaper',
            'link'  => 'berita.php'
        ],
        [
            'title' => 'Pengumuman Resmi',
            'desc'  => 'Informasi penting seputar edaran sekolah, agenda libur, dan pemberitahuan.',
            'icon'  => 'megaphone',
            'link'  => 'pengumuman.php'
        ],
        [
            'title' => 'Prestasi Akademik & Non-Akademik',
            'desc'  => 'Rekapitulasi pencapaian kejuaraan sains, seni, dan olahraga tingkat daerah hingga internasional.',
            'icon'  => 'award',
            'link'  => 'prestasi-informasi.php'
        ],
        [
            'title' => 'Penerimaan Siswa Baru (PPDB)',
            'desc'  => 'Informasi alur pendaftaran, syarat berkas, dan jadwal seleksi calon siswa baru.',
            'icon'  => 'user-plus',
            'link'  => 'ppdb.php'
        ]
    ];

    // Data Berita / Pengumuman Informasi Terbaru
    $informasi_terbaru = [
        [
            'tgl' => '26 Agu 2026',
            'judul' => 'Juara 1 Olimpiade Sains Nasional (OSN) Bidang Matematika',
            'ringkasan' => 'Selamat kepada siswa yang meraih medali emas pada OSN tingkat provinsi tahun ini.'
        ],
        [
            'tgl' => '18 Agu 2026',
            'judul' => 'Surat Edaran Pelaksanaan Rapat Orang Tua/Wali Murid',
            'ringkasan' => 'Pemberitahuan rapat komite sekolah mengenai sosialisasi program semester ganjil.'
        ]
    ];

    include 'includes/header.php'; 
?>

<!-- LINK CSS KHUSUS INFORMASI -->
<link rel="stylesheet" href="assets/css/informasi.css">

<!-- PAGE BANNER -->
<section class="page-banner">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            <span>Informasi</span>
        </div>
        <h1 class="page-title">Pusat Informasi & Prestasi</h1>
    </div>
</section>

<!-- MAIN CONTENT SECTION -->
<section class="academic-section">
    <div class="container">
        
        <!-- Header Section -->
        <div class="section-header" style="text-align: center; max-width: 700px; margin: 0 auto 50px auto;">
            <span class="section-tag">Publikasi & Layanan</span>
            <h2 class="section-title">Pilar Utama Informasi</h2>
            <p>Menyajikan berita terkini, pengumuman resmi, catatan prestasi, dan layanan publik secara transparan.</p>
        </div>

        <!-- 1. GRID PILAR UTAMA -->
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

        <!-- 2. LAYOUT 2 KOLOM (Berita/Prestasi & Quick Download) -->
        <div class="two-column-layout">
            
            <!-- Kolom Kiri: Berita & Pengumuman Informasi -->
            <div class="kolom">
                <div class="column-header">
                    <span class="section-tag">Kabar Terkini</span>
                    <h3>Berita & Pengumuman Terbaru</h3>
                </div>
                <div class="list-pengumuman">
                    <?php foreach ($informasi_terbaru as $info): ?>
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

            <!-- Kolom Kanan: Download Dokumen & Banner PPDB -->
            <div class="kolom">
                <div class="column-header">
                    <span class="section-tag">Download & Layanan</span>
                    <h3>Pusat Unduhan & Portal PPDB</h3>
                </div>
                <div class="box-download">
                    <div class="download-item">
                        <div class="download-info">
                            <i data-lucide="file-text" class="icon-file"></i>
                            <span>Brosur Profil & Rekap Prestasi (PDF)</span>
                        </div>
                        <a href="#" class="btn-download">Unduh</a>
                    </div>
                    <div class="download-item">
                        <div class="download-info">
                            <i data-lucide="file-text" class="icon-file"></i>
                            <span>Panduan Alur Pendaftaran PPDB (PDF)</span>
                        </div>
                        <a href="#" class="btn-download">Unduh</a>
                    </div>

                    <!-- Banner Informasi PPDB Online -->
                    <div class="portal-banner">
                        <div class="portal-content">
                            <i data-lucide="user-plus" class="portal-icon"></i>
                            <h4>Pendaftaran Siswa Baru (PPDB)</h4>
                            <p>Dapatkan informasi alur seleksi dan daftarkan diri secara online.</p>
                            <a href="ppdb.php" class="btn-portal">Buka Portal PPDB</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>