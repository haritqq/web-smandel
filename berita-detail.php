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
        // Jika tidak ada ID atau Slug, redirect kembali ke halaman berita
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

    // Jika berita tidak ditemukan di database
    if (!$berita) {
        header("Location: berita.php");
        exit;
    }

    // 3. Ambil media/gambar postingan (jika ada)
    $postId = $berita['id'];
    $query_media = "SELECT url_atau_file, tipe FROM post_media WHERE post_id = $postId AND jenis = 'gambar' LIMIT 1";
    $res_media = mysqli_query($koneksi, $query_media);

    $gambar_url = 'assets/img/hero1.jpg'; // Gambar default jika berita tidak memiliki gambar
    if ($res_media && mysqli_num_rows($res_media) > 0) {
        $m = mysqli_fetch_assoc($res_media);
        $gambar_url = ($m['tipe'] === 'file') ? 'admin/uploads/' . $m['url_atau_file'] : $m['url_atau_file'];
    }

    // 4. Set variabel meta & header
    $page_title = $berita['judul'];
    $current_page = "informasi";

    include 'includes/header.php';
?>

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
        <!-- Metadata Berita (Tanggal, Penulis, Kategori) -->
        <div class="detail-meta" style="display: flex; gap: 20px; color: var(--gray-600); margin-bottom: 24px; font-size: 0.9rem; flex-wrap: wrap;">
            <span><i data-lucide="calendar" style="width: 16px;"></i> <?= date('d F Y', strtotime($berita['created_at'])); ?></span>
            <span><i data-lucide="user" style="width: 16px;"></i> <?= htmlspecialchars($berita['penulis']); ?></span>
            <span><i data-lucide="tag" style="width: 16px;"></i> <?= htmlspecialchars($berita['kategori']); ?></span>
        </div>

        <!-- Gambar Utama -->
        <img src="<?= htmlspecialchars($gambar_url); ?>" alt="<?= htmlspecialchars($berita['judul']); ?>" style="width: 100%; height: auto; max-height: 480px; object-fit: cover; border-radius: var(--radius-md, 12px); margin-bottom: 30px;">

        <!-- Isi Konten Berita -->
        <div class="detail-content" style="line-height: 1.8; color: var(--dark, #1e293b); font-size: 1.05rem;">
            <?= nl2br($berita['konten']); ?>
        </div>

        <!-- Navigasi Kembali -->
        <div style="margin-top: 40px; border-top: 1px solid #E2E8F0; padding-top: 20px; display: flex; gap: 12px;">
            <a href="berita.php" class="btn-outline"><i data-lucide="arrow-left"></i> Kembali ke Berita</a>
            <a href="index.php" class="btn-outline">Ke Beranda</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>