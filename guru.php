<?php 
    require_once __DIR__ . '/admin/config/koneksi.php';

    $page_title = "Data Guru & Tendik";
    $current_page = "guru"; 

    // Query untuk mengambil seluruh data guru dari database[cite: 1]
    $query = "SELECT * FROM guru_tendik ORDER BY id ASC";
    $result = mysqli_query($koneksi, $query);

    include 'includes/header.php'; 
?>

<!-- LINK CSS KHUSUS GURU -->
<link rel="stylesheet" href="assets/css/guru.css">

<!-- PAGE BANNER -->
<section class="page-banner">
    <div class="container">
        <h1 class="page-title">Guru & Tenaga Kependidikan</h1>
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <span>/</span>
            <span>Profil</span>
            <span>/</span>
            <span>Data Guru</span>
        </div>
    </div>
</section>

<!-- MAIN CONTENT SECTION -->
<section class="guru-section">
    <div class="container">
        <div class="section-header" style="text-align: center; max-width: 700px; margin: 0 auto 50px auto;">
            <span class="section-tag">Tenaga Pendidik</span>
            <h2 class="section-title">Guru & Tenaga Kependidikan</h2>
            <p>Tenaga pendidik profesional dan berpengalaman yang siap membimbing siswa SMAN 8 Banda Aceh menuju keunggulan akademik dan karakter.</p>
        </div>

        <div class="guru-grid">
            <?php while($guru = mysqli_fetch_assoc($result)): ?>
            <div class="guru-card">
                <div class="guru-thumb">
                    <img src="<?php echo htmlspecialchars($guru['foto']); ?>" alt="<?php echo htmlspecialchars($guru['nama']); ?>">
                    <span class="guru-badge"><?php echo htmlspecialchars($guru['jabatan']); ?></span>
                </div>
                <div class="guru-body">
                    <div>
                        <h3 class="guru-name"><?php echo htmlspecialchars($guru['nama']); ?></h3>
                        <div class="guru-mapel"><?php echo htmlspecialchars($guru['mapel']); ?></div>
                    </div>
                    <p class="guru-nip"><?php echo htmlspecialchars($guru['nip']); ?></p>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>