<?php 
    $page_title = "Data Guru & Tendik";
    $current_page = "guru"; 

    // Data simulasi Guru & Tenaga Kependidikan
    $data_guru = [
        [
            'nama' => 'Dr. Erlawana, S.Pd., M.Pd',
            'jabatan' => 'Kepala Sekolah',
            'mapel' => 'Bahasa Indonesia',
            'nip' => 'NIP. 19720315 199702 2 001',
            'foto' => 'assets/img/kepsek.jpeg'
        ],
        [
            'nama' => 'Ahmad Farisi, S.Pd., M.Si',
            'jabatan' => 'Wakil Kepala Kurikulum',
            'mapel' => 'Matematika',
            'nip' => 'NIP. 19800512 200501 1 004',
            'foto' => 'assets/img/hero1.jpg'
        ],
        [
            'nama' => 'Siti Rahmah, S.Pd',
            'jabatan' => 'Wakil Kesiswaan',
            'mapel' => 'Biologi',
            'nip' => 'NIP. 19830822 200804 2 002',
            'foto' => 'assets/img/hero2.jpg'
        ],
        [
            'nama' => 'Budi Santoso, S.Kom',
            'jabatan' => 'Guru / Kaprog IT',
            'mapel' => 'Informatika & TIK',
            'nip' => 'NIP. 19890110 201402 1 003',
            'foto' => 'assets/img/hero3.jpg'
        ],
        [
            'nama' => 'Cut Nurlaila, M.Pd',
            'jabatan' => 'Guru Senior',
            'mapel' => 'Fisika',
            'nip' => 'NIP. 19781105 200312 2 001',
            'foto' => 'assets/img/hero1.jpg'
        ],
        [
            'nama' => 'Dedi Iskandar, S.Or',
            'jabatan' => 'Guru',
            'mapel' => 'PJOK / Penjas',
            'nip' => 'NIP. 19910418 201903 1 008',
            'foto' => 'assets/img/hero2.jpg'
        ],
        [
            'nama' => 'Nurul Hidayah, S.Pd',
            'jabatan' => 'Guru',
            'mapel' => 'Bahasa Inggris',
            'nip' => 'NIP. 19930725 202012 2 011',
            'foto' => 'assets/img/hero3.jpg'
        ],
        [
            'nama' => 'Muhammad Rizky, S.T',
            'jabatan' => 'Kepala Laboratorium',
            'mapel' => 'Kimia',
            'nip' => 'NIP. 19860930 201001 1 005',
            'foto' => 'assets/img/hero1.jpg'
        ]
    ];

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
            <?php foreach($data_guru as $guru): ?>
            <div class="guru-card">
                <div class="guru-thumb">
                    <img src="<?php echo $guru['foto']; ?>" alt="<?php echo $guru['nama']; ?>">
                    <span class="guru-badge"><?php echo $guru['jabatan']; ?></span>
                </div>
                <div class="guru-body">
                    <div>
                        <h3 class="guru-name"><?php echo $guru['nama']; ?></h3>
                        <div class="guru-mapel"><?php echo $guru['mapel']; ?></div>
                    </div>
                    <p class="guru-nip"><?php echo $guru['nip']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>