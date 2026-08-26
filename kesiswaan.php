<?php 
    $page_title = "Kesiswaan & Ekstrakurikuler";
    $current_page = "kesiswaan"; 

    // Data Pilar Utama Kesiswaan
    $sub_menu = [
        [
            'title' => 'Organisasi Siswa (OSIS)',
            'desc'  => 'Wadah kepemimpinan dan penyaluran aspirasi siswa dalam mengelola program kerja sekolah.',
            'icon'  => 'users',
            'link'  => 'osis.php'
        ],
        [
            'title' => 'Ekstrakurikuler',
            'desc'  => 'Beragam kegiatan pengembangan bakat di bidang Olahraga, Seni, Sains, dan Keagamaan.',
            'icon'  => 'trophy',
            'link'  => 'ekstrakurikuler.php'
        ],
        [
            'title' => 'Tata Tertib & Kedisiplinan',
            'desc'  => 'Panduan aturan, aturan seragam, serta kode etik kedisiplinan siswa di sekolah.',
            'icon'  => 'shield-check',
            'link'  => 'tata-tertib.php'
        ],
        [
            'title' => 'Prestasi Siswa',
            'desc'  => 'Rekapitulasi pencapaian kejuaraan dan penghargaan siswa di berbagai tingkatan.',
            'icon'  => 'award',
            'link'  => 'prestasi.php'
        ]
    ];

    // Data Berita / Agenda Kesiswaan Terbaru
    $berita_kesiswaan = [
        [
            'tgl' => '20 Agu 2026',
            'judul' => 'Pelantikan Pengurus OSIS & MPK Masa Bakti 2026/2027',
            'ringkasan' => 'Pelantikan pengurus OSIS baru berjalan khidmat disaksikan oleh seluruh warga sekolah.'
        ],
        [
            'tgl' => '05 Agu 2026',
            'judul' => 'Seleksi Terbuka Anggota Tim Futsal & Basket Sekolah',
            'ringkasan' => 'Pendaftaran tryout terbuka bagi siswa kelas X dan XI yang berminat bergabung tim utama.'
        ]
    ];

    include 'includes/header.php'; 
?>

<!-- LINK CSS KHUSUS KESISWAAN -->
<link rel="stylesheet" href="assets/css/kesiswaan.css">

<!-- PAGE BANNER -->
<section class="page-banner">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            <span>Kesiswaan</span>
        </div>
        <h1 class="page-title">Bidang Kesiswaan</h1>
    </div>
</section>

<!-- MAIN CONTENT SECTION -->
<section class="academic-section">
    <div class="container">
        
        <!-- Header Section -->
        <div class="section-header" style="text-align: center; max-width: 700px; margin: 0 auto 50px auto;">
            <span class="section-tag">Pengembangan Karakter</span>
            <h2 class="section-title">Pilar Utama Kesiswaan</h2>
            <p>Membentuk pribadi siswa yang berakhlak mulia, berprestasi, kreatif, dan berjiwa kepemimpinan.</p>
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

        <!-- 2. LAYOUT 2 KOLOM (Berita Kegiatan & Quick Download) -->
        <div class="two-column-layout">
            
            <!-- Kolom Kiri: Berita / Kegiatan Kesiswaan -->
            <div class="kolom">
                <div class="column-header">
                    <span class="section-tag">Kegiatan Siswa</span>
                    <h3>Berita & Agenda Kesiswaan</h3>
                </div>
                <div class="list-pengumuman">
                    <?php foreach ($berita_kesiswaan as $info): ?>
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

            <!-- Kolom Kanan: Quick Download & Banner Registrasi Ekskul -->
            <div class="kolom">
                <div class="column-header">
                    <span class="section-tag">Pusat Layanan</span>
                    <h3>Dokumen & Formulir Siswa</h3>
                </div>
                <div class="box-download">
                    <div class="download-item">
                        <div class="download-info">
                            <i data-lucide="file-text" class="icon-file"></i>
                            <span>Buku Saku Tata Tertib Siswa (PDF)</span>
                        </div>
                        <a href="#" class="btn-download">Unduh</a>
                    </div>
                    <div class="download-item">
                        <div class="download-info">
                            <i data-lucide="file-text" class="icon-file"></i>
                            <span>Formulir Pendaftaran Ekskul (PDF)</span>
                        </div>
                        <a href="#" class="btn-download">Unduh</a>
                    </div>

                    <!-- Banner Pendaftaran Ekskul -->
                    <div class="portal-banner">
                        <div class="portal-content">
                            <i data-lucide="activity" class="portal-icon"></i>
                            <h4>Pendaftaran Ekstrakurikuler</h4>
                            <p>Bergabunglah dengan klub minat dan bakat pilihanmu semester ini.</p>
                            <a href="#" class="btn-portal">Daftar Ekskul Online</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>