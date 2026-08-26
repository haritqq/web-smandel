<?php 
    $page_title = "Fasilitas Sekolah";
    $current_page = "fasilitas"; 

    // Data simulasi Fasilitas Sekolah
    $data_fasilitas = [
        [
            'id' => 1,
            'nama' => 'Ruang Multimedia',
            'kategori' => 'Akademik',
            'icon' => 'monitor',
            'gambar' => 'assets/img/hero1.jpg',
            'deskripsi' => 'Ruang kelas interaktif yang didukung teknologi multimedia modern untuk pengalaman belajar yang optimal dan nyaman.',
            'fitur' => ['AC & Smart TV', 'Wi-Fi Area', 'Kapasitas 36 Siswa']
        ],
        [
            'id' => 2,
            'nama' => 'Laboratorium Komputer',
            'kategori' => 'Teknologi',
            'icon' => 'cpu',
            'gambar' => 'assets/img/hero2.jpg',
            'deskripsi' => 'Fasilitas komputer spesifikasi tinggi untuk mendukung kegiatan pembelajaran informatika, ANBK, dan simulasi ujian.',
            'fitur' => ['50 Unit Komputer', 'Full AC & Server', 'Akses Internet Cepat']
        ],
        [
            'id' => 3,
            'nama' => 'Laboratorium Biologi',
            'kategori' => 'Sains',
            'icon' => 'microscope',
            'gambar' => 'assets/img/hero3.jpg',
            'deskripsi' => 'Ruang laboratorium khusus untuk penelitian keanekaragaman hayati, pengamatan mikroskopis, dan eksperimen biologi.',
            'fitur' => ['Mikroskop Digital & Cahaya', 'Specimen & Prep Wet Lab', 'Peralatan Bedah Hewan']
        ],
        [
            'id' => 4,
            'nama' => 'Laboratorium Fisika',
            'kategori' => 'Sains',
            'icon' => 'zap',
            'gambar' => 'assets/img/hero1.jpg',
            'deskripsi' => 'Fasilitas praktikum fisika yang dilengkapi perangkat eksperimen mekanika, optik, termodinamika, dan kelistrikan.',
            'fitur' => ['Kit Praktikum Mekanika & Optik', 'Perangkat Uji Kelistrikan', 'Meja Praktikum Anti-Getar']
        ],
        [
            'id' => 5,
            'nama' => 'Laboratorium Kimia',
            'kategori' => 'Sains',
            'icon' => 'flask-conical',
            'gambar' => 'assets/img/hero2.jpg',
            'deskripsi' => 'Ruang laboratorium kimia dengan standar keamanan lengkap untuk pengujian larutan, reaksi kimia, dan analisis zat.',
            'fitur' => ['Lemari Asam (Fume Hood)', 'Bahan Kimia Murni & Alat Kaca', 'Wastafel & Safety Shower']
        ],
        [
            'id' => 6,
            'nama' => 'Ruang Seni & Budaya',
            'kategori' => 'Kreativitas',
            'icon' => 'palette',
            'gambar' => 'assets/img/hero3.jpg',
            'deskripsi' => 'Wadah ekspresi seni rupa, musik, dan tari yang dilengkapi dengan studio mini serta perlengkapan kesenian.',
            'fitur' => ['Alat Musik Tradisional & Modern', 'Easel & Perlengkapan Melukis', 'Area Display Karya']
        ],
        [
            'id' => 7,
            'nama' => 'Ruang PAI & Tahfidz',
            'kategori' => 'Ibadah',
            'icon' => 'book-open-check',
            'gambar' => 'assets/img/hero1.jpg',
            'deskripsi' => 'Ruang khusus pembelajaran Pendidikan Agama Islam, bimbingan baca Al-Qur\'an, dan program tahfidz siswa.',
            'fitur' => ['Full AC & Ambal Nyaman', 'Audio Pembelajaran Tajwid', 'Perpustakaan Mini PAI']
        ],
        [
            'id' => 8,
            'nama' => 'Kantin Sehat & Bersih',
            'kategori' => 'Fasilitas Umum',
            'icon' => 'utensils',
            'gambar' => 'assets/img/hero2.jpg',
            'deskripsi' => 'Area makan yang bersih dan higienis yang menyediakan pilihan makanan gizi seimbang untuk seluruh siswa dan staf.',
            'fitur' => ['Sistem Pembayaran Digital', 'Stand Makanan Terverifikasi', 'Area Duduk Luas']
        ],
        [
            'id' => 9,
            'nama' => 'Perpustakaan Digital',
            'kategori' => 'Literasi',
            'icon' => 'book-open',
            'gambar' => 'assets/img/hero1.jpg',
            'deskripsi' => 'Koleksi ribuan buku cetak dan e-book yang terintegrasi dengan e-library system untuk kenyamanan membaca siswa.',
            'fitur' => ['Mendukung Segala Perangkat', 'Akses E-Book 24/7', 'Katalog Online']
        ],
                [
            'id' => 10,
            'nama' => 'Ruang Perpustakaan',
            'kategori' => 'Literasi',
            'icon' => 'book-open',
            'gambar' => 'assets/img/hero1.jpg',
            'deskripsi' => 'Koleksi ribuan buku cetak dan e-book yang terintegrasi dengan e-library system untuk kenyamanan membaca siswa.',
            'fitur' => ['Mendukung Segala Perangkat', 'Akses E-Book 24/7', 'Katalog Online']
        ],
        [
            'id' => 11,
            'nama' => 'Lapangan Olahraga',
            'kategori' => 'Olahraga',
            'icon' => 'trophy',
            'gambar' => 'assets/img/hero2.jpg',
            'deskripsi' => 'Area serbaguna yang dapat digunakan untuk olahraga Basket, Futsal, Voli, serta kegiatan upacara bendera.',
            'fitur' => ['Lapangan Futsal & Basket', 'Tribun Penonton', 'Peralatan Olahraga']
        ],
        [
            'id' => 12,
            'nama' => 'Mushala & Sarana Ibadah',
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