<?php
// Variabel konfigurasi halaman (Dapat diambil dari DB di masa depan)
$page_title = "Visi & Misi";
$current_page = "profil";

// Include Header
include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/visi-misi.css">

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
                Unggul dalam prestasi, berkarakter, berbudaya, peduli lingkungan, berwawasan global yang dilandasi iman
                dan takwa"
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
                    <h4></h4>
                    <p>Unggul Membina Peserta didik unggul dalam prestasi akademis dan non akademis di taraf nasional
                        maupun internasional.</p>
                </div>
            </div>
            <div class="misi-card">
                <div class="misi-number">02</div>
                <div class="misi-content">
                    <h4></h4>
                    <p>Membina peserta didik unggul dalam perolehan ujian sekolah dan ujian nasional serta berhasil
                        masuk perguruan tinggi di dalam maupun di luar negeri.</p>
                </div>
            </div>
            <div class="misi-card">
                <div class="misi-number">03</div>
                <div class="misi-content">
                    <h4></h4>
                    <p>Membudayakan disiplin, toleransi, saling menghargai, percaya diri sehingga terbentuk sikap sikap
                        peserta didik yang santun dan berbudi pekerti luhur.</p>
                </div>
            </div>
            <div class="misi-card">
                <div class="misi-number">04</div>
                <div class="misi-content">
                    <h4></h4>
                    <p>Mengembangkan semangat kebangsaan yang berakar pada nilai nilai budaya bangsa dengan tetap
                        mengikuti perkembangan ilmu pengetahuan dan teknologi.</p>
                </div>
            </div>
            <div class="misi-card">
                <div class="misi-number">05</div>
                <div class="misi-content">
                    <h4></h4>
                    <p>Menumbuhkembangkan budaya sekolah sehat dan peduli lingkungan.</p>
                </div>
            </div>
            <div class="misi-card">
                <div class="misi-number">06</div>
                <div class="misi-content">
                    <h4></h4>
                    <p>Melaksanakan pembelajaran dan penggunaan bahasa internasional.</p>
                </div>
            </div>
            <div class="misi-card">
                <div class="misi-number">07</div>
                <div class="misi-content">
                    <h4></h4>
                    <p>Menerapkan pengelolaan sekolah yang mengacu pada standar manajemen mutu dengan melibatkan seluruh
                        warga sekolah.</p>
                </div>
            </div>
            <div class="misi-card">
                <div class="misi-number">08</div>
                <div class="misi-content">
                    <h4></h4>
                    <p>Menumbuhkembangkan prilaku religius dalam peserta didik sehingga dapat menghayati dan mengamalkan
                        ajaran agama yang dianutnya dalam segala aspek kehidupan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include Footer
include 'includes/footer.php';
?>