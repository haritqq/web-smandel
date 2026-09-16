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

    $total_gambar = count($daftar_gambar);

    // 4. Ambil Daftar Komentar Terpublikasi
    $query_komen = "SELECT * FROM post_comments WHERE post_id = $postId AND status = 'Disetujui' ORDER BY created_at DESC";
    $res_komen = mysqli_query($koneksi, $query_komen);
    $daftar_komen = [];
    if ($res_komen) {
        while ($k = mysqli_fetch_assoc($res_komen)) {
            $daftar_komen[] = $k;
        }
    }

    // 5. URL Saat ini untuk Fitur Share
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $current_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // Meta & Header
    $page_title = $berita['judul'];
    $current_page = "informasi";

    include 'includes/header.php';
?>

<!-- CDN CSS GLightbox -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

<style>
    /* Galeri Grid Adaptif */
    .berita-galeri-grid {
        display: grid;
        gap: 16px;
        margin-bottom: 32px;
        width: 100%;
    }
    .galeri-count-1 { grid-template-columns: 1fr; }
    .galeri-count-2 { grid-template-columns: repeat(2, 1fr); }
    .galeri-count-3 { grid-template-columns: repeat(2, 1fr); }
    .galeri-count-3 .galeri-item:nth-child(1) { grid-column: span 2; }
    .galeri-count-4, .galeri-count-more { grid-template-columns: repeat(2, 1fr); }

    .galeri-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        aspect-ratio: 16 / 9;
        cursor: pointer;
        display: block;
    }
    .galeri-count-1 .galeri-item { aspect-ratio: 16 / 9; max-height: 480px; }
    .galeri-item img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform 0.3s ease;
    }
    .galeri-item:hover img { transform: scale(1.04); }

    /* Detail Content */
    .detail-content { line-height: 1.8; color: #1e293b; font-size: 1.05rem; }
    .detail-content p { margin-bottom: 1.5rem; }

    /* Baris Interaksi (Like & Share) */
    .post-interaction-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        padding: 20px 0;
        margin: 30px 0;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
    }

    .btn-like {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fef2f2;
        color: #ef4444;
        border: 1px solid #fca5a5;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-like:hover, .btn-like.liked {
        background: #ef4444;
        color: #ffffff;
    }

    .share-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        color: #fff;
        text-decoration: none;
        transition: opacity 0.2s ease;
    }
    .share-btn:hover { opacity: 0.85; }
    .share-wa { background-color: #25D366; }
    .share-fb { background-color: #1877F2; }
    .share-tw { background-color: #000000; }
    .share-copy { background-color: #64748b; cursor: pointer; border: none; }

    /* Section Komentar */
    .comments-section { margin-top: 40px; }
    .comment-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 16px;
    }
    .comment-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .comment-author { font-weight: 700; color: #0f172a; }
    .comment-date { font-size: 0.8rem; color: #64748b; }

    /* Form Komentar */
    .comment-form {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        border-radius: 12px;
        margin-top: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
    }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.9rem; }
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.95rem;
    }
    .form-control:focus { outline: none; border-color: #2563eb; }
    .btn-submit {
        background: #2563eb;
        color: #fff;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-submit:hover { background: #1d4ed8; }

    @media (max-width: 640px) {
        .galeri-count-2, .galeri-count-3, .galeri-count-4, .galeri-count-more { grid-template-columns: 1fr; }
        .galeri-count-3 .galeri-item:nth-child(1) { grid-column: span 1; }
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

        <!-- GALERI GAMBAR BERITA -->
        <?php $grid_class = 'galeri-count-' . ($total_gambar > 4 ? 'more' : $total_gambar); ?>
        <div class="berita-galeri-grid <?= $grid_class; ?>">
            <?php foreach ($daftar_gambar as $img_src): ?>
                <a href="<?= htmlspecialchars($img_src); ?>" class="galeri-item glightbox" data-gallery="berita-gallery">
                    <img src="<?= htmlspecialchars($img_src); ?>" alt="<?= htmlspecialchars($berita['judul']); ?>" loading="lazy">
                </a>
            <?php endforeach; ?>
        </div>

        <!-- ISI KONTEN BERITA -->
        <div class="detail-content">
            <?= $berita['konten']; ?>
        </div>

        <!-- BARIS INTERAKSI (LIKE & SHARE) -->
        <div class="post-interaction-bar">
            <!-- Tombol Like -->
            <button class="btn-like" id="btnLike" data-id="<?= $postId; ?>">
                <i data-lucide="heart" style="width: 18px; height: 18px;"></i>
                <span id="likeCount"><?= (int)($berita['jumlah_like'] ?? 0); ?></span> Suka
            </button>

            <!-- Tombol Share -->
            <div class="share-buttons">
                <span style="font-size: 0.9rem; font-weight: 600; color: #64748b; margin-right: 6px;">Bagikan:</span>
                <a href="https://api.whatsapp.com/send?text=<?= urlencode($berita['judul'] . ' - ' . $current_url); ?>" target="_blank" class="share-btn share-wa" title="Bagikan ke WhatsApp">
                    <i data-lucide="message-circle" style="width: 18px;"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($current_url); ?>" target="_blank" class="share-btn share-fb" title="Bagikan ke Facebook">
                    <i data-lucide="facebook" style="width: 18px;"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode($current_url); ?>&text=<?= urlencode($berita['judul']); ?>" target="_blank" class="share-btn share-tw" title="Bagikan ke Twitter/X">
                    <i data-lucide="twitter" style="width: 18px;"></i>
                </a>
                <button class="share-btn share-copy" onclick="copyToClipboard('<?= $current_url; ?>')" title="Salin Tautan">
                    <i data-lucide="link" style="width: 18px;"></i>
                </button>
            </div>
        </div>

        <!-- DAFTAR KOMENTAR -->
        <div class="comments-section">
            <h3 style="font-size: 1.3rem; margin-bottom: 20px;">Komentar (<?= count($daftar_komen); ?>)</h3>
            
            <div id="commentsList">
                <?php if (empty($daftar_komen)): ?>
                    <p style="color: #64748b; font-style: italic;">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                <?php else: ?>
                    <?php foreach ($daftar_komen as $komen): ?>
                        <div class="comment-card">
                            <div class="comment-header">
                                <span class="comment-author"><?= htmlspecialchars($komen['nama']); ?></span>
                                <span class="comment-date"><?= date('d M Y, H:i', strtotime($komen['created_at'])); ?></span>
                            </div>
                            <p style="margin: 0; color: #334155; font-size: 0.95rem;"><?= nl2br(htmlspecialchars($komen['komentar'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- FORMULIR UMPAN BALIK / KOMENTAR -->
            <form class="comment-form" id="formComment">
                <h4 style="margin-top: 0; margin-bottom: 16px;">Tinggalkan Komentar</h4>
                <input type="hidden" name="post_id" value="<?= $postId; ?>">
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Anda" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email (Tidak dipublikasikan)</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="komentar">Komentar</label>
                    <textarea id="komentar" name="komentar" class="form-control" rows="4" placeholder="Tulis komentar Anda..." required></textarea>
                </div>

                <button type="submit" class="btn-submit">Kirim Komentar</button>
            </form>
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
    // 1. Popup Lightbox Gambar
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true
    });

    // 2. Fungsi Salin Link
    function copyToClipboard(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert('Tautan berita berhasil disalin!');
        });
    }

    // 3. AJAX Fitur Like
    document.getElementById('btnLike').addEventListener('click', function() {
        const btn = this;
        const postId = btn.getAttribute('data-id');

        fetch('post_action.php?action=like', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'post_id=' + postId
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('likeCount').innerText = data.likes;
                btn.classList.add('liked');
            } else {
                alert(data.message);
            }
        });
    });

    // 4. AJAX Fitur Kirim Komentar
    document.getElementById('formComment').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('post_action.php?action=comment', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload(); // Refresh halaman agar komentar baru tampil
            } else {
                alert(data.message);
            }
        });
    });
</script>

<?php include 'includes/footer.php'; ?>