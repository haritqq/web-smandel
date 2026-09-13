<?php 
    $page_title = "Berita & Informasi Terbaru";
    $current_page = "berita"; 

    // 1. Koneksi Database & Visitor Counter
    require_once __DIR__ . '/admin/config/koneksi.php';
    include 'includes/visitor_counter.php';

    // 2. Query Data Berita dari Database
    $data_berita = [];
    $query_berita = "SELECT posts.*, users.nama_lengkap AS penulis 
                     FROM posts 
                     JOIN users ON posts.user_id = users.id 
                     WHERE posts.status = 'Diterbitkan' 
                     ORDER BY posts.id DESC";
    $result_berita = mysqli_query($koneksi, $query_berita);

    // Dynamic Kategori untuk Filter Tab
    $kategori_list = [];

    if ($result_berita && mysqli_num_rows($result_berita) > 0) {
        while ($row = mysqli_fetch_assoc($result_berita)) {
            $postId = $row['id'];
            
            // Ambil media gambar postingan
            $query_media = "SELECT url_atau_file, tipe FROM post_media WHERE post_id = $postId AND jenis = 'gambar' LIMIT 1";
            $res_media = mysqli_query($koneksi, $query_media);
            
            $gambar_url = 'assets/img/hero1.jpg'; // Gambar default
            if ($res_media && mysqli_num_rows($res_media) > 0) {
                $m = mysqli_fetch_assoc($res_media);
                $gambar_url = ($m['tipe'] === 'file') ? 'admin/uploads/' . $m['url_atau_file'] : $m['url_atau_file'];
            }

            $kat_clean = trim($row['kategori']);
            $kat_slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $kat_clean));

            if (!empty($kat_clean) && !isset($kategori_list[$kat_slug])) {
                $kategori_list[$kat_slug] = $kat_clean;
            }

            $data_berita[] = [
                'id'            => $row['id'],
                'slug'          => $row['slug'],
                'judul'         => $row['judul'],
                'ringkasan'     => substr(strip_tags($row['konten']), 0, 120) . '...',
                'kategori'      => $kat_clean,
                'kategori_slug' => $kat_slug,
                'tanggal'       => date('d M Y', strtotime($row['created_at'])),
                'penulis'       => $row['penulis'],
                'gambar'        => $gambar_url
            ];
        }
    }

    include 'includes/header.php'; 
?>

<!-- REUSE CSS GALERI UNTUK TAMPILAN HOMOGEN -->
<link rel="stylesheet" href="assets/css/galeri.css">

<style>
/* Penyesuaian Style Khusus Berita agar Serasi dengan Galeri */
.berita-search-bar {
    max-width: 500px;
    margin: 0 auto 30px auto;
    position: relative;
}
.berita-search-bar input {
    width: 100%;
    padding: 14px 20px 14px 45px;
    border-radius: 30px;
    border: 1px solid #E2E8F0;
    outline: none;
    font-size: 0.95rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
}
.berita-search-bar input:focus {
    border-color: var(--primary, #0284c7);
    box-shadow: 0 4px 16px rgba(2, 132, 199, 0.15);
}
.berita-search-bar i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    width: 18px;
    height: 18px;
}
.galeri-card .berita-meta-info {
    font-size: 0.82rem;
    color: #64748b;
    margin-bottom: 8px;
    display: flex;
    gap: 12px;
}
.galeri-card .berita-meta-info span {
    display: flex;
    align-items: center;
    gap: 4px;
}
.galeri-card .berita-link-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 14px;
    font-weight: 600;
    font-size: 0.88rem;
    color: var(--primary, #0284c7);
    text-decoration: none;
    transition: gap 0.2s ease;
}
.galeri-card .berita-link-btn:hover {
    gap: 10px;
}
</style>

<!-- PAGE BANNER (MENGIKUTI TEMA GALERI) -->
<section class="page-banner">
    <div class="container">
        <h1 class="page-title">Berita & Kabar Sekolah</h1>
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <span>/</span>
            <span>Berita</span>
        </div>
    </div>
</section>

<!-- MAIN BERITA SECTION -->
<section class="galeri-section">
    <div class="container">

        <!-- SEARCH BAR -->
        <div class="berita-search-bar">
            <i data-lucide="search"></i>
            <input type="text" id="beritaSearchInput" placeholder="Cari berita atau pengumuman...">
        </div>
        
        <!-- FILTER TAB (SAMA SEPERTI GALERI) -->
        <div class="galeri-filter">
            <button class="filter-btn active" data-filter="all">Semua</button>
            <?php foreach($kategori_list as $slug => $nama): ?>
                <button class="filter-btn" data-filter="<?= htmlspecialchars($slug); ?>"><?= htmlspecialchars($nama); ?></button>
            <?php endforeach; ?>
        </div>

        <!-- GRID BERITA (DENGAN LAYOUT CARD GALERI) -->
        <div class="galeri-grid" id="beritaContainer">
            <?php if (!empty($data_berita)): ?>
                <?php foreach($data_berita as $item): ?>
                    <div class="galeri-item berita-item-card" data-category="<?= htmlspecialchars($item['kategori_slug']); ?>">
                        <div class="galeri-card">
                            <div class="galeri-thumb">
                                <img src="<?= htmlspecialchars($item['gambar']); ?>" alt="<?= htmlspecialchars($item['judul']); ?>">
                                <a href="berita-detail.php?id=<?= $item['id']; ?>" class="zoom-btn-overlay">
                                    <i data-lucide="arrow-up-right"></i>
                                </a>
                                <span class="galeri-tag"><?= htmlspecialchars($item['kategori']); ?></span>
                            </div>
                            <div class="galeri-info">
                                <div class="berita-meta-info">
                                    <span><i data-lucide="calendar" style="width: 14px;"></i> <?= htmlspecialchars($item['tanggal']); ?></span>
                                    <span><i data-lucide="user" style="width: 14px;"></i> <?= htmlspecialchars($item['penulis']); ?></span>
                                </div>
                                <h3 style="font-size: 1.1rem; line-height: 1.4; margin-bottom: 8px;">
                                    <a href="berita-detail.php?id=<?= $item['id']; ?>" style="text-decoration: none; color: inherit;">
                                        <?= htmlspecialchars($item['judul']); ?>
                                    </a>
                                </h3>
                                <p><?= htmlspecialchars($item['ringkasan']); ?></p>
                                <a href="berita-detail.php?id=<?= $item['id']; ?>" class="berita-link-btn">
                                    Baca Selengkapnya <i data-lucide="chevron-right" style="width: 16px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #64748b;">
                    <p>Belum ada berita yang diterbitkan saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- JAVASCRIPT UNTUK INTERAKSI FILTER & PENCARIAN -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const filterBtns = document.querySelectorAll(".filter-btn");
    const items = document.querySelectorAll(".berita-item-card");
    const searchInput = document.getElementById("beritaSearchInput");

    // Fitur Filter Tab Kategori
    filterBtns.forEach(btn => {
        btn.addEventListener("click", function () {
            filterBtns.forEach(b => b.classList.remove("active"));
            this.classList.add("active");

            const filterValue = this.getAttribute("data-filter");
            filterBerita();
        });
    });

    // Fitur Live Search
    searchInput.addEventListener("keyup", filterBerita);

    function filterBerita() {
        const activeFilter = document.querySelector(".filter-btn.active").getAttribute("data-filter");
        const searchText = searchInput.value.toLowerCase().trim();

        items.forEach(item => {
            const itemCategory = item.getAttribute("data-category");
            const titleText = item.querySelector("h3").innerText.toLowerCase();
            const descText = item.querySelector("p").innerText.toLowerCase();

            const matchCategory = (activeFilter === "all" || itemCategory === activeFilter);
            const matchSearch = (titleText.includes(searchText) || descText.includes(searchText));

            if (matchCategory && matchSearch) {
                item.style.display = "block";
            } else {
                item.style.display = "none";
            }
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>