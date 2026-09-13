<?php
require_once __DIR__ . '/admin/config/koneksi.php';

// Filter Kategori
$kategoriFilter = isset($_GET['kategori']) ? mysqli_real_escape_string($koneksi, $_GET['kategori']) : '';

$queryWhere = "WHERE posts.status = 'Diterbitkan'";
if (!empty($kategoriFilter)) {
    $queryWhere .= " AND posts.kategori = '$kategoriFilter'";
}

$queryAll = "SELECT posts.*, users.nama_lengkap AS penulis 
             FROM posts 
             JOIN users ON posts.user_id = users.id 
             $queryWhere 
             ORDER BY posts.id DESC";
$resultAll = mysqli_query($koneksi, $queryAll);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita & Pengumuman - SMAN 8 Banda Aceh</title>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary-orange: #f97316;
            --primary-orange-hover: #ea580c;
            --bg-orange-light: #fff7ed;
            --border-orange: #ffedd5;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: system-ui, -apple-system, sans-serif; }
        body { background-color: #f8fafc; color: var(--text-main); line-height: 1.6; }

        /* Header / Banner Nuansa Oranye */
        .hero-banner {
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
            color: #ffffff;
            padding: 48px 20px;
            text-align: center;
        }
        .hero-banner h1 { font-size: 2.25rem; font-weight: 700; margin-bottom: 8px; }
        .hero-banner p { font-size: 1rem; opacity: 0.9; max-width: 600px; margin: 0 auto; }

        .container { max-width: 1100px; margin: -28px auto 60px; padding: 0 20px; }

        /* Filter Kategori */
        .filter-container {
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }
        .filter-label { font-size: 0.875rem; font-weight: 600; color: var(--text-muted); margin-right: 6px; display: flex; align-items: center; gap: 6px; }
        .filter-btn {
            padding: 8px 18px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-main);
            background: #f1f5f9;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .filter-btn:hover { background: var(--bg-orange-light); color: var(--primary-orange); }
        .filter-btn.active { background: var(--primary-orange); color: #ffffff; font-weight: 600; }

        /* Grid Berita */
        .news-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px; }
        .news-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .news-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(249, 115, 22, 0.1);
            border-color: var(--border-orange);
        }
        
        .card-body { padding: 24px; display: flex; flex-direction: column; flex-grow: 1; }
        
        .badge {
            display: inline-block;
            align-self: flex-start;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .badge-berita { background: var(--bg-orange-light); color: var(--primary-orange); border: 1px solid var(--border-orange); }
        .badge-pengumuman { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }

        .card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 10px;
            line-height: 1.4;
        }
        .card-title a { color: inherit; text-decoration: none; transition: color 0.2s; }
        .card-title a:hover { color: var(--primary-orange); }

        .card-meta {
            font-size: 0.8rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 14px;
        }
        .meta-item { display: flex; align-items: center; gap: 4px; }

        .card-excerpt {
            font-size: 0.9rem;
            color: #475569;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .card-footer {
            border-top: 1px solid #f1f5f9;
            padding-top: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .btn-read {
            color: var(--primary-orange);
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: gap 0.2s ease;
        }
        .btn-read:hover { color: var(--primary-orange-hover); gap: 8px; }
    </style>
</head>
<body>

    <!-- HERO BANNER BERWARNA ORANYE -->
    <header class="hero-banner">
        <h1>Berita & Pengumuman</h1>
        <p>Informasi terbaru, artikel, dan pengumuman resmi dari SMAN 8 Banda Aceh.</p>
    </header>

    <div class="container">
        <!-- FILTER KATEGORI -->
        <div class="filter-container">
            <span class="filter-label">
                <i data-lucide="filter" style="width: 16px; height: 16px;"></i> Filter:
            </span>
            <a href="berita.php" class="filter-btn <?= empty($kategoriFilter) ? 'active' : ''; ?>">Semua</a>
            <a href="berita.php?kategori=Berita" class="filter-btn <?= $kategoriFilter === 'Berita' ? 'active' : ''; ?>">Berita</a>
            <a href="berita.php?kategori=Pengumuman" class="filter-btn <?= $kategoriFilter === 'Pengumuman' ? 'active' : ''; ?>">Pengumuman</a>
        </div>

        <!-- LIST DAFTAR BERITA -->
        <div class="news-grid">
            <?php if ($resultAll && mysqli_num_rows($resultAll) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($resultAll)): ?>
                    <article class="news-card">
                        <div class="card-body">
                            <!-- Badge Kategori -->
                            <span class="badge <?= $row['kategori'] === 'Pengumuman' ? 'badge-pengumuman' : 'badge-berita'; ?>">
                                <?= htmlspecialchars($row['kategori']); ?>
                            </span>

                            <!-- Judul Berita -->
                            <h2 class="card-title">
                                <a href="detail-berita.php?slug=<?= $row['slug']; ?>">
                                    <?= htmlspecialchars($row['judul']); ?>
                                </a>
                            </h2>

                            <!-- Metadata (Tanggal & Penulis) -->
                            <div class="card-meta">
                                <div class="meta-item">
                                    <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
                                    <span><?= date('d M Y', strtotime($row['created_at'])); ?></span>
                                </div>
                                <div class="meta-item">
                                    <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                                    <span><?= htmlspecialchars($row['penulis']); ?></span>
                                </div>
                            </div>

                            <!-- Ringkasan Konten -->
                            <p class="card-excerpt">
                                <?= htmlspecialchars(substr(strip_tags($row['konten']), 0, 130)) . '...'; ?>
                            </p>

                            <!-- Tombol Selengkapnya -->
                            <div class="card-footer">
                                <a href="detail-berita.php?slug=<?= $row['slug']; ?>" class="btn-read">
                                    <span>Baca Selengkapnya</span>
                                    <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; background: #ffffff; padding: 40px; text-align: center; border-radius: 12px; border: 1px solid #e2e8f0; color: var(--text-muted);">
                    <i data-lucide="newspaper" style="width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 12px;"></i>
                    <p style="font-size: 1.1rem; font-weight: 500;">Belum ada berita atau pengumuman yang dapat ditampilkan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>