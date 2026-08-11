<?php 
    $id_berita = isset($_GET['id']) ? $_GET['id'] : 1;
    
    // Nanti di sini ditarik query dari DB: "SELECT * FROM berita WHERE id = $id_berita"
    $page_title = "Detail Berita";
    $current_page = "informasi";

    include 'includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            <a href="berita.php">Berita</a>
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            <span>Detail</span>
        </div>
        <h1 class="page-title">Siswa SMAN 8 Banda Aceh Raih Medali Emas Olimpiade Sains</h1>
    </div>
</section>

<section class="berita-detail-section" style="padding: 60px 0;">
    <div class="container" style="max-width: 900px;">
        <div class="detail-meta" style="display: flex; gap: 20px; color: var(--gray-600); margin-bottom: 24px; font-size: 0.9rem;">
            <span><i data-lucide="calendar" style="width: 16px;"></i> 10 Agustus 2026</span>
            <span><i data-lucide="user" style="width: 16px;"></i> Admin Sekolah</span>
            <span><i data-lucide="tag" style="width: 16px;"></i> Prestasi</span>
        </div>

        <img src="assets/img/hero1.jpg" alt="Foto Berita" style="width: 100%; height: 420px; object-fit: cover; border-radius: var(--radius-md); margin-bottom: 30px;">

        <div class="detail-content" style="line-height: 1.8; color: var(--dark); font-size: 1.05rem;">
            <p style="margin-bottom: 20px;">
                BANDA ACEH - Siswa SMA Negeri 8 Banda Aceh kembali menorehkan prestasi gemilang di tingkat provinsi. Dalam ajang Olimpiade Sains Nasional (OSN) tingkat kota dan provinsi, kontingen SMAN 8 berhasil membawa pulang medali emas.
            </p>
            <p style="margin-bottom: 20px;">
                Kepala Sekolah SMAN 8 Banda Aceh menyampaikan apresiasi setinggi-tingginya kepada para siswa serta guru pembimbing yang telah bekerja keras melakukan persiapan secara matang.
            </p>
        </div>

        <div style="margin-top: 40px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
            <a href="index.php#berita" class="btn-outline"><i data-lucide="arrow-left"></i> Kembali ke Beranda</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>