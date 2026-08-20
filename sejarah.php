<?php 
    $page_title = "Sejarah Sekolah";
    $current_page = "sejarah"; 

    // Data simulasi Timeline Sejarah Sekolah
    $timeline_sejarah = [
        [
            'tahun' => '1998',
            'judul' => 'Pendirian Pertama Sekolah',
            'deskripsi' => 'SMA Negeri 8 Banda Aceh resmi didirikan oleh Pemerintah melalui Kementerian Pendidikan dan Kebudayaan guna merespons kebutuhan pendidikan menengah yang berkualitas di Kota Banda Aceh.'
        ],
        [
            'tahun' => '2004',
            'judul' => 'Bangkit Pasca Bencana Tsunami',
            'deskripsi' => 'Pasca musibah gempa dan tsunami Aceh, sekolah mengalami pembenahan infrastruktur secara besar-besaran dengan dukungan dari pemerintah pusat serta bantuan lembaga internasional.'
        ],
        [
            'tahun' => '2012',
            'judul' => 'Peningkatan Akreditasi Sekolah',
            'deskripsi' => 'Melalui peningkatan kualitas manajemen, mutu pengajaran, serta kelengkapan sarana prasarana, SMAN 8 Banda Aceh berhasil meraih Akreditasi A dari Badan Akreditasi Nasional Sekolah/Madrasah (BAN-S/M).'
        ],
        [
            'tahun' => '2018',
            'judul' => 'Transformasi Digital Pembelajaran',
            'deskripsi' => 'Mulai menerapkan sarana pembelajaran berbasis teknologi digital, e-learning, perpustakaan online, serta modernisasi sarana laboratorium sains dan komputer.'
        ],
        [
            'tahun' => '2023 - Sekarang',
            'judul' => 'Sekolah Penggerak & Berprestasi',
            'deskripsi' => 'Kini SMAN 8 Banda Aceh berkembang pesat sebagai salah satu sekolah unggulan yang terus menorehkan prestasi baik di tingkat daerah, nasional, maupun berwawasan global.'
        ]
    ];

    include 'includes/header.php'; 
?>

<!-- LINK CSS KHUSUS SEJARAH -->
<link rel="stylesheet" href="assets/css/sejarah.css">

<!-- PAGE BANNER -->
<section class="page-banner">
    <div class="container">
        <h1 class="page-title">Sejarah Sekolah</h1>
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <span>/</span>
            <span>Profil</span>
            <span>/</span>
            <span>Sejarah</span>
        </div>
    </div>
</section>

<!-- SEJARAH MAIN CONTENT -->
<section class="sejarah-section">
    <div class="container">
        <!-- HERO CARD SEJARAH -->
        <div class="sejarah-hero-card">
            <img src="assets/img/hero1.jpg" alt="Gedung SMAN 8 Banda Aceh" class="sejarah-hero-img">
            <div class="sejarah-hero-text">
                <span class="section-tag" style="display:inline-block; margin-bottom:10px;">Jejak Langkah</span>
                <h2>Perjalanan Panjang Menuju Keunggulan</h2>
                <p>
                    SMA Negeri 8 Banda Aceh berdiri sebagai salah satu pilar pendidikan menengah di Kota Banda Aceh. Sejak awal berdirinya, sekolah ini terus berkomitmen penuh untuk mencetak generasi muda yang cerdas, unggul, berkarakter mulia, serta siap bersaing di era global.
                </p>
                <p>
                    Dengan semangat dedikasi yang tinggi dari para guru, staf, alumni, dan dukungan masyarakat, SMAN 8 Banda Aceh senantiasa bertransformasi dan beradaptasi mengikuti perkembangan zaman tanpa mengesampingkan nilai-nilai kebangsaan dan kearifan lokal.
                </p>
            </div>
        </div>

        <!-- TIMELINE PERJALANAN SEKOLAH -->
        <div class="timeline-title-area">
            <span class="section-tag">Kilas Balik</span>
            <h2 class="section-title">Timeline Perkembangan</h2>
            <p>Tahapan penting dan rekam jejak perjalanan SMA Negeri 8 Banda Aceh dari waktu ke waktu.</p>
        </div>

        <div class="timeline">
            <?php foreach($timeline_sejarah as $index => $item): ?>
                <?php $position_class = ($index % 2 == 0) ? 'left' : 'right'; ?>
                <div class="timeline-item <?php echo $position_class; ?>">
                    <div class="timeline-content">
                        <span class="timeline-year"><?php echo $item['tahun']; ?></span>
                        <h3><?php echo $item['judul']; ?></h3>
                        <p><?php echo $item['deskripsi']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>