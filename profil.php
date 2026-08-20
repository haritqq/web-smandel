<?php 
    $page_title = "Profil Sekolah";
    $current_page = "profil"; 

    // Data Lengkap Identitas Sekolah
    $identitas_sekolah = [
        'Nama Sekolah' => 'SMA Negeri 8 Banda Aceh',
        'NPSN' => '10105340',
        'NSS' => '301116003008',
        'Bentuk Pendidikan' => 'SMA (Sekolah Menengah Atas)',
        'Status Sekolah' => 'Negeri',
        'Status Kepemilikan' => 'Pemerintah Daerah',
        'SK Pendirian Sekolah' => '425.1/045/1998',
        'Tanggal SK Pendirian' => '12 Juli 1998',
        'SK Izin Operasional' => '425.1/045/1998',
        'Tanggal SK Operasional' => '12 Juli 1998',
        'Akreditasi' => 'A (Unggul)',
        'SK Akreditasi' => '1347/BAN-SM/SK/2021',
        'Kepala Sekolah' => 'Dr. Erlawana, S.Pd., M.Pd'
    ];

    $alamat_sekolah = [
        'Alamat Jalan' => 'Jl. Twk. H. Hashim Banta Muda No. 8',
        'Desa / Kelurahan' => 'Gampong Mulia',
        'Kecamatan' => 'Kuta Alam',
        'Kota' => 'Kota Banda Aceh',
        'Provinsi' => 'Aceh',
        'Kode Pos' => '23123',
        'Email Resmi' => 'info@sma8bna.sch.id',
        'Situs Web' => 'https://sma8bna.sch.id'
    ];

    include 'includes/header.php'; 
?>

<!-- LINK CSS KHUSUS PROFIL -->
<link rel="stylesheet" href="assets/css/profil.css">

<!-- PAGE BANNER -->
<section class="page-banner">
    <div class="container">
        <h1 class="page-title">Profil Sekolah</h1>
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <span>/</span>
            <span>Profil</span>
            <span>/</span>
            <span>Identitas Sekolah</span>
        </div>
    </div>
</section>

<!-- MAIN CONTENT SECTION -->
<section class="profil-section">
    <div class="container">
        <div class="profil-grid">
            <!-- KOLOM KIRI: DATA LENGKAP SEKOLAH -->
            <div class="profil-main">
                <!-- KARTU IDENTITAS UTAMA -->
                <div class="profil-card">
                    <div class="profil-card-header">
                        <div class="profil-card-icon">
                            <i data-lucide="school"></i>
                        </div>
                        <h3>Identitas Satuan Pendidikan</h3>
                    </div>
                    <table class="data-table">
                        <tbody>
                            <?php foreach($identitas_sekolah as $label => $val): ?>
                            <tr>
                                <td class="label-col"><?php echo $label; ?></td>
                                <td class="colon-col">:</td>
                                <td class="value-col"><?php echo $val; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- KARTU ALAMAT & KONTAK -->
                <div class="profil-card">
                    <div class="profil-card-header">
                        <div class="profil-card-icon">
                            <i data-lucide="map-pin"></i>
                        </div>
                        <h3>Alamat & Informasi Kontak</h3>
                    </div>
                    <table class="data-table">
                        <tbody>
                            <?php foreach($alamat_sekolah as $label => $val): ?>
                            <tr>
                                <td class="label-col"><?php echo $label; ?></td>
                                <td class="colon-col">:</td>
                                <td class="value-col"><?php echo $val; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- KOLOM KANAN: RINGKASAN & LOKASI -->
            <aside class="profil-sidebar">
                <div class="summary-card">
                    <h4>Ringkasan Data</h4>
                    <div class="summary-item">
                        <div class="summary-icon"><i data-lucide="award"></i></div>
                        <div class="summary-text">
                            <span>Akreditasi</span>
                            <strong>A (Unggul)</strong>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-icon"><i data-lucide="hash"></i></div>
                        <div class="summary-text">
                            <span>NPSN</span>
                            <strong>10105340</strong>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-icon"><i data-lucide="users"></i></div>
                        <div class="summary-text">
                            <span>Siswa Aktif</span>
                            <strong>850+ Siswa</strong>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-icon"><i data-lucide="user-check"></i></div>
                        <div class="summary-text">
                            <span>Guru & Tendik</span>
                            <strong>78 Personel</strong>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>