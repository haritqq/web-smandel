<?php 
    $page_title = "Fasilitas Sekolah";
    $current_page = "fasilitas"; 

    // Data simulasi Fasilitas Sekolah
    $data_fasilitas = [
        [
            'id' => 1,
            'nama' => 'Ruang Kelas Digital',
            'kategori' => 'Akademik',
            'icon' => 'monitor',
            'gambar' => 'assets/img/hero1.jpg',
            'deskripsi' => 'Ruang kelas nyaman berbasis multimedia yang dilengkapi Smart TV/Proyektor, pendingin udara (AC), dan jaringan Wi-Fi cepat.',
            'fitur' => ['AC & Smart TV', 'Wi-Fi Area', 'Kapasitas 36 Siswa']
        ],
        [
            'id' => 2,
            'nama' => 'Laboratorium Komputer',
            'kategori' => 'Teknologi',
            'icon' => 'cpu',
            'gambar' => 'assets/img/hero2.jpg',
            'deskripsi' => 'Fasilitas komputer spesifikasi tinggi untuk mendukung kegiatan pembelajaran informatika, ANBK, dan simulasi ujian.',
            'fitur' => ['40 Unit Komputer', 'Full AC & Server', 'Akses Internet Cepat']
        ],
        [
            'id' => 3,
            'nama' => 'Laboratorium IPA Terpadu',
            'kategori' => 'Sains',
            'icon' => 'flask-conical',
            'gambar' => 'assets/img/hero3.jpg',
            'deskripsi' => 'Dilengkapi alat praktikum Fisika, Kimia, dan Biologi modern untuk menunjang penelitian dan kegiatan eksperimen siswa.',
            'fitur' => ['Alat Praktikum Lengkap', 'Mikroskop Digital', 'Bahan Uji Standard']
        ],
        [
            'id' => 4,
            'nama' => 'Perpustakaan Digital',
            'kategori' => 'Literasi',
            'icon' => 'book-open',
            'gambar' => 'assets/img/hero1.jpg',
            'deskripsi' => 'Koleksi ribuan buku cetak dan e-book yang terintegrasi dengan e-library system untuk kenyamanan membaca siswa.',
            'fitur' => ['Area Baca Lesehan & Meja', 'Akses E-Book 24/7', 'Katalog Online']
        ],
        [
            'id' => 5,
            'nama' => 'Lapangan Olahraga',
            'kategori' => 'Olahraga',
            'icon' => 'trophy',
            'gambar' => 'assets/img/hero2.jpg',
            'deskripsi' => 'Area serbaguna yang dapat digunakan untuk olahraga Basket, Futsal, Voli, serta kegiatan upacara bendera.',
            'fitur' => ['Lapangan Futsal & Basket', 'Tribun Penonton', 'Peralatan Olahraga']
        ],
        [
            'id' => 6,
            'nama' => 'Musala & Sarana Ibadah',
            'kategori' => 'Ibadah',
            'icon' => 'heart',
            'gambar' => 'assets/img/hero3.jpg',
            'deskripsi' => 'Musala bersih dan nyaman yang digunakan untuk pelaksanaan sholat berjemaah, kajian, dan kegiatan keagamaan siswa.',
            'fitur' => ['Tempat Wudu Berpisah', 'Perlengkapan Sholat', 'Full AC']
        ]
    ];

    include 'includes/header.php'; 
?>

<!-- LINK CSS KHUSUS FASILITAS -->
<link rel="stylesheet" href="assets/css/fasilitas.css">

<!-- PAGE BANNER -->
<section class="page-banner">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            <span>Profil</span>
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            <span>Fasilitas</span>
        </div>
        <h1 class="page-title">Fasilitas Sekolah</h1>
    </div>
</section>

<!-- MAIN CONTENT SECTION -->
<section class="facility-section">
    <div class="container">
        <div class="section-header" style="text-align: center; max-width: 700px; margin: 0 auto 50px auto;">
            <span class="section-tag">Sarana & Prasarana</span>
            <h2 class="section-title">Mendukung Pembelajaran Maksimal</h2>
            <p>SMA Negeri 8 Banda Aceh menyediakan berbagai sarana penunjang akademik dan non-akademik modern demi menciptakan suasana belajar yang kondusif.</p>
        </div>

        <div class="facility-grid">
            <?php foreach($data_fasilitas as $fasilitas): ?>
            <div class="facility-card">
                <div class="facility-thumb">
                    <img src="<?php echo $fasilitas['gambar']; ?>" alt="<?php echo $fasilitas['nama']; ?>">
                    <span class="facility-badge"><?php echo $fasilitas['kategori']; ?></span>
                </div>
                <div class="facility-body">
                    <div class="facility-icon-title">
                        <div class="facility-icon">
                            <i data-lucide="<?php echo $fasilitas['icon']; ?>"></i>
                        </div>
                        <h3><?php echo $fasilitas['nama']; ?></h3>
                    </div>
                    <p class="facility-desc"><?php echo $fasilitas['deskripsi']; ?></p>
                    
                    <ul class="facility-features">
                        <?php foreach($fasilitas['fitur'] as $fitur): ?>
                        <li><i data-lucide="check-circle-2"></i> <?php echo $fitur; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>