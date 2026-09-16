<?php 
    require_once __DIR__ . '/admin/config/koneksi.php';

    // 1. Tangkap parameter ID atau Slug dari URL
    $id_berita = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $slug_berita = isset($_GET['slug']) ? mysqli_real_escape_string($koneksi, $_GET['slug']) : '';

    // 2. Kueri data berita dari database berdasarkan ID atau Slug
    $queryWhere = "";
    if ($id_berita > 0) {
        $queryWhere = "WHERE posts.id = $id_berita";
    } elseif (!empty($slug_berita)) {
        $queryWhere = "WHERE posts.slug = '$slug_berita'";
    } else {
        header("Location: berita.php");
        exit;
    }

    $query = "SELECT posts.*, users.nama_lengkap AS penulis 
              FROM posts 
              JOIN users ON posts.user_id = users.id 
              $queryWhere AND posts.status = 'Diterbitkan' 
              LIMIT 1";

    $result = mysqli_query($koneksi, $query);
    $berita = mysqli_fetch_assoc($result);

    if (!$berita) {
        header("Location: berita.php");
        exit;
    }

    // 3. Ambil SELURUH media/gambar postingan
    $postId = $berita['id'];
    $query_media = "SELECT url_atau_file, tipe FROM post_media WHERE post_id = $postId AND jenis = 'gambar'";
    $res_media = mysqli_query($koneksi, $query_media);

    $daftar_gambar = [];
    if ($res_media && mysqli_num_rows($res_media) > 0) {
        while ($m = mysqli_fetch_assoc($res_media)) {
            $daftar_gambar[] = ($m['tipe'] === 'file') ? 'admin/uploads/' . $m['url_atau_file'] : $m['url_atau_file'];
        }
    } else {
        $daftar_gambar[] = 'assets/img/hero1.jpg';
    }

    // Hitung jumlah gambar untuk menentukan layout grid
    $total_gambar = count($daftar_gambar);

    // 4. Set variabel meta & header
    $page_title = $berita['judul'];
    $current_page = "informasi";

    include 'includes/header.php';
?>

<!-- CDN CSS GLightbox untuk Popup Gambar -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

<!-- STYLING GALERI & KONTEN -->
<style>
    /* Grid Galeri Adaptif */
    .berita-galeri-grid {
        display: grid;
        gap: 16px;
        margin-bottom: 32px;
        width: 100%;
    }

    /* Layout berdasarkan jumlah gambar */
    .galeri-count-1 {
        grid-template-columns: 1fr;
    }
    
    .galeri-count-2 {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .galeri-count-3 {
        grid-template-columns: repeat(2, 1fr);
    }
    .galeri-count-3 .galeri-item:nth-child(1) {
        grid-column: span 2; /* Gambar pertama penuh di atas */
    }

    .galeri-count-4,
    .galeri-count-more {
        grid-template-columns: repeat(2, 1fr);
    }

    /* Card Item Gambar */
    .galeri-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        aspect-ratio: 16 / 9; /* Rasio proporsional modern */
        cursor: pointer;
        display: block;
    }

    /* Untuk gambar tunggal agar tampil lebih luas */
    .galeri-count-1 .galeri-item {
        aspect-ratio: 16 / 9;
        max-height: 480px;
    }

    .galeri-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease, filter 0.3s ease;
    }

    /* Overlay Icon Zoom saat Hover */
    .galeri-item::after {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.25);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .galeri-item:hover::after {
        opacity: 1;
    }

    .galeri-item:hover img {
        transform: scale(1.04);
    }

    /* Styling khusus Konten Editor */
    .detail-content {
        line-height: 1.8;
        color: #1e293b;
        font-size: 1.05rem;
    }
    .detail-content p {
        margin-bottom: 1.5rem;
    }
    .detail-content a {
        color: #2563eb;
        text-decoration: underline;
        font-weight: 500;
    }
    .detail-content a:hover {
        color: #1d4ed8;
    }
    .detail-content ul, .detail-content ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }
    .detail-content li {
        margin-bottom: 0.5rem;
    }
    .detail-content strong {
        font-weight: 700;
    }

    /* Responsive untuk Layar HP */
    @media (max-width: 640px) {
        .galeri-count-2,
        .galeri-count-3,
        .galeri-count-4,
        .galeri-count-more {
            grid-template-columns: 1fr;
        }
        
        .galeri-count-3 .galeri-item:nth-child(1) {
            grid-column: span 1;
        }
    }
</style>

<!-- BANNER BREADCRUMB -->
<section class="page-banner">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            <a href="berita.php">Berita</a>
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            <span>Detail</span>
        </div>
        <h1 class="page-title"><?= htmlspecialchars($berita['judul']); ?></h1>
    </div>
</section>

<!-- KONTEN DETAIL BERITA -->
<section class="berita-detail-section" style="padding: 60px 0;">
    <div class="container" style="max-width: 900px;">
        <!-- Metadata Berita -->
        <div class="detail-meta" style="display: flex; gap: 20px; color: var(--gray-600); margin-bottom: 24px; font-size: 0.9rem; flex-wrap: wrap;">
            <span><i data-lucide="calendar" style="width: 16px;"></i> <?= date('d F Y', strtotime($berita['created_at'])); ?></span>
            <span><i data-lucide="user" style="width: 16px;"></i> <?= htmlspecialchars($berita['penulis']); ?></span>
            <span><i data-lucide="tag" style="width: 16px;"></i> <?= htmlspecialchars($berita['kategori']); ?></span>
        </div>

        <!-- GALERI GAMBAR BERITA (POPUP GLIGHTBOX ADAPTIF) -->
        <?php 
            $grid_class = 'galeri-count-' . ($total_gambar > 4 ? 'more' : $total_gambar);
        ?>
        <div class="berita-galeri-grid <?= $grid_class; ?>">
            <?php foreach ($daftar_gambar as $img_src): ?>
                <!-- Tag <a> dengan class glightbox membuat gambar bisa di-klik untuk popup -->
                <a href="<?= htmlspecialchars($img_src); ?>" class="galeri-item glightbox" data-gallery="berita-gallery">
                    <img src="<?= htmlspecialchars($img_src); ?>" alt="<?= htmlspecialchars($berita['judul']); ?>" loading="lazy">
                </a>
            <?php endforeach; ?>
        </div>

        <!-- ISI KONTEN BERITA -->
        <div class="detail-content">
            <?= $berita['konten']; ?>
        </div>

        <!-- Navigasi Kembali -->
        <div style="margin-top: 40px; border-top: 1px solid #E2E8F0; padding-top: 20px; display: flex; gap: 12px;">
            <a href="berita.php" class="btn-outline"><i data-lucide="arrow-left"></i> Kembali ke Berita</a>
            <a href="index.php" class="btn-outline">Ke Beranda</a>
        </div>
    </div>
</section>

<!-- CDN JS GLightbox -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

<script>
    // Inisialisasi Popup GLightbox
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
        zoomable: true
    });
</script>

<?php include 'includes/footer.php'; ?>