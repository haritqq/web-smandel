<?php 
    $page_title = "Galeri Kegiatan & Fasilitas";
    $current_page = "galeri"; 

    // Data Simulasi Galeri
    $galeri_items = [
        [
            'judul' => 'Upacara Bendera Hari Senin',
            'kategori' => 'kegiatan',
            'tipe' => 'foto',
            'media' => 'assets/img/hero1.jpg',
            'deskripsi' => 'Pelaksanaan upacara rutin mingguan siswa SMAN 8 Banda Aceh.'
        ],
        [
            'judul' => 'Laboratorium Komputer',
            'kategori' => 'fasilitas',
            'tipe' => 'foto',
            'media' => 'assets/img/hero2.jpg',
            'deskripsi' => 'Fasilitas lab komputer modern pendukung kegiatan TIK.'
        ],
        [
            'judul' => 'Juara 1 Lomba Cerdas Cermat',
            'kategori' => 'prestasi',
            'tipe' => 'foto',
            'media' => 'assets/img/hero3.jpg',
            'deskripsi' => 'Penerimaan piala oleh tim olimpiade sekolah.'
        ],
        [
            'judul' => 'Kegiatan Ekstrakurikuler Pramuka',
            'kategori' => 'kegiatan',
            'tipe' => 'foto',
            'media' => 'assets/img/hero1.jpg',
            'deskripsi' => 'Latihan kepemimpinan dan ketangkasan anggota Pramuka.'
        ],
        [
            'judul' => 'Perpustakaan Digital',
            'kategori' => 'fasilitas',
            'tipe' => 'foto',
            'media' => 'assets/img/hero2.jpg',
            'deskripsi' => 'Ruang baca nyaman dan tenang ber-AC dengan koleksi lengkap.'
        ],
        [
            'judul' => 'Profil Singkat SMAN 8',
            'kategori' => 'video',
            'tipe' => 'video',
            'media' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', // Link Embed Video YouTube
            'thumb' => 'assets/img/hero3.jpg',
            'deskripsi' => 'Video dokumenter suasana pembelajaran dan fasilitas sekolah.'
        ],
    ];

    include 'includes/header.php'; 
?>

<!-- LINK CSS KHUSUS GALERI -->
<link rel="stylesheet" href="assets/css/galeri.css">

<!-- PAGE BANNER -->
<section class="page-banner">
    <div class="container">
        <h1 class="page-title">Galeri Sekolah</h1>
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <span>/</span>
            <span>Galeri</span>
        </div>
    </div>
</section>

<!-- MAIN GALERI SECTION -->
<section class="galeri-section">
    <div class="container">
        <!-- FILTER TAB -->
        <div class="galeri-filter">
            <button class="filter-btn active" data-filter="all">Semua</button>
            <button class="filter-btn" data-filter="kegiatan">Kegiatan</button>
            <button class="filter-btn" data-filter="fasilitas">Fasilitas</button>
            <button class="filter-btn" data-filter="prestasi">Prestasi</button>
            <button class="filter-btn" data-filter="video">Video</button>
        </div>

        <!-- GRID GALERI -->
        <div class="galeri-grid">
            <?php foreach($galeri_items as $item): ?>
                <div class="galeri-item" data-category="<?php echo $item['kategori']; ?>">
                    <div class="galeri-card">
                        <div class="galeri-thumb">
                            <?php if($item['tipe'] === 'video'): ?>
                                <img src="<?php echo $item['thumb']; ?>" alt="<?php echo $item['judul']; ?>">
                                <div class="play-btn-overlay">
                                    <i data-lucide="play-circle"></i>
                                </div>
                            <?php else: ?>
                                <img src="<?php echo $item['media']; ?>" alt="<?php echo $item['judul']; ?>">
                                <div class="zoom-btn-overlay">
                                    <i data-lucide="maximize-2"></i>
                                </div>
                            <?php endif; ?>
                            <span class="galeri-tag"><?php echo ucfirst($item['kategori']); ?></span>
                        </div>
                        <div class="galeri-info">
                            <h3><?php echo $item['judul']; ?></h3>
                            <p><?php echo $item['deskripsi']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- MODAL / LIGHTBOX OVERLAY -->
<div class="galeri-modal" id="galeriModal">
    <span class="modal-close">&times;</span>
    <div class="modal-content">
        <img id="modalImg" src="" alt="" style="display: none;">
        <iframe id="modalVideo" src="" frameborder="0" allowfullscreen style="display: none;"></iframe>
        <h3 id="modalTitle"></h3>
        <p id="modalDesc"></p>
    </div>
</div>

<script src="assets/js/galeri.js"></script>

<?php include 'includes/footer.php'; ?>