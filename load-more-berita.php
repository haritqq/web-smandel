<?php
require_once __DIR__ . '/admin/config/koneksi.php';

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 6; // Jumlah berita yang dimuat setiap kali klik

$query_berita = "SELECT posts.*, users.nama_lengkap AS penulis 
                 FROM posts 
                 JOIN users ON posts.user_id = users.id 
                 WHERE posts.status = 'Diterbitkan' 
                 ORDER BY posts.created_at DESC 
                 LIMIT $limit OFFSET $offset";

$result_berita = mysqli_query($koneksi, $query_berita);

if ($result_berita && mysqli_num_rows($result_berita) > 0) {
    while ($row = mysqli_fetch_assoc($result_berita)) {
        $postId = $row['id'];
        $query_media = "SELECT url_atau_file, tipe FROM post_media WHERE post_id = $postId AND jenis = 'gambar' LIMIT 1";
        $res_media = mysqli_query($koneksi, $query_media);
        
        $gambar_url = 'assets/img/noimage-v2.jpg';
        if ($res_media && mysqli_num_rows($res_media) > 0) {
            $m = mysqli_fetch_assoc($res_media);
            $gambar_url = ($m['tipe'] === 'file') ? 'admin/uploads/' . $m['url_atau_file'] : $m['url_atau_file'];
        }

        $judul = htmlspecialchars($row['judul']);
        $kategori = htmlspecialchars($row['kategori']);
        $penulis = htmlspecialchars($row['penulis']);
        $tanggal = date('d M Y', strtotime($row['created_at']));
        $ringkasan = htmlspecialchars(substr(strip_tags($row['konten']), 0, 120)) . '...';
        ?>
        <article class="berita-card">
            <div class="berita-thumb">
                <img src="<?= $gambar_url; ?>" alt="<?= $judul; ?>">
                <span class="berita-category"><?= $kategori; ?></span>
            </div>
            <div class="berita-body">
                <div class="berita-meta">
                    <span><i data-lucide="calendar"></i> <?= $tanggal; ?></span>
                    <span><i data-lucide="user"></i> <?= $penulis; ?></span>
                </div>
                <h3 class="berita-title">
                    <a href="berita-detail.php?id=<?= $row['id']; ?>"><?= $judul; ?></a>
                </h3>
                <p class="berita-excerpt"><?= $ringkasan; ?></p>
                <a href="berita-detail.php?id=<?= $row['id']; ?>" class="berita-link">
                    Lihat Selengkapnya <i data-lucide="chevron-right"></i>
                </a>
            </div>
        </article>
        <?php
    }
}