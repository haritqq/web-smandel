<?php 
    // Variabel konfigurasi halaman (Dapat diambil dari DB di masa depan)
    $page_title = "Visi & Misi";
    $current_page = "profil"; 

    // Include Header
    include 'includes/header.php'; 
?>

<!-- BANNER HALAMAN -->
<section class="page-banner">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            <span>Profil</span>
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            <span>Visi & Misi</span>
        </div>
        <h1 class="page-title">Visi & Misi Sekolah</h1>
    </div>
</section>

<!-- KONTEN VISI MISI -->
<section class="visi-misi-section">
    <div class="container">
        <div class="vision-card">
            <span class="vision-badge"><i data-lucide="eye"></i> Visi Sekolah</span>
            <p class="vision-text">
                "Terwujudnya Lulusan yang Bertaqwa, Berkarakter Unggul, Berprestasi Akademik dan Non-Akademik, Berwawasan Lingkungan, serta Mampu Bersaing di Era Global."
            </p>
        </div>

        <div class="section-heading">
            <span class="section-tag">Arah & Strategi</span>
            <h2 class="section-title">Misi SMA Negeri 8 Banda Aceh</h2>
        </div>

        <div class="misi-grid">
            <div class="misi-card">
                <div class="misi-number">01</div>
                <div class="misi-content">
                    <h4>Pendidikan Karakter & Religius</h4>
                    <p>Menumbuhkembangkan penghayatan dan pengamalan nilai-nilai keagamaan serta budi pekerti luhur dalam kehidupan sehari-hari.</p>
                </div>
            </div>
            <div class="misi-card">
                <div class="misi-number">02</div>
                <div class="misi-content">
                    <h4>Kualitas Pembelajaran Modern</h4>
                    <p>Menyelenggarakan proses pembelajaran yang inovatif, berbasis teknologi informasi, dan berpusat pada peserta didik.</p>
                </div>
            </div>
            <!-- Misi Lainnya... -->
        </div>
    </div>
</section>

<?php 
    // Include Footer
    include 'includes/footer.php'; 
?>