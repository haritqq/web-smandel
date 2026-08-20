<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . " - SMA Negeri 8 Banda Aceh" : "SMA Negeri 8 Banda Aceh"; ?></title>

    <!-- FAVICON (Tab Icon Browser) -->
    <link rel="shortcut icon" href="assets/img/Logo_Smandel.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/img/Logo_Smandel.png">
    
    <!-- Google Fonts & Lucide Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- TOPBAR UTILITY -->
    <div class="topbar">
        <div class="container">
            <div class="topbar-contact">
                <span><i data-lucide="phone"></i> (+62) 878-8389-1042</span>
                <span><i data-lucide="mail"></i> sman8bandaaceh01.@gmail.com</span>
                <!-- <span><i data-lucide="map-pin"></i> Banda Aceh, Aceh</span> -->
            </div>
            <div class="topbar-actions">
                <a href="index.php#ppdb" class="btn-ppdb">PPDB / SPMB ONLINE</a>
                <a href="#elearning" class="btn-link">E-Learning</a>
                <a href="#kontak" class="btn-link">Kontak</a>
            </div>
        </div>
    </div>

    <!-- MAIN HEADER & NAV -->
    <header class="main-header">
        <div class="container">
            <a href="index.php" class="brand-logo">
                <img src="assets/img/Logo_Smandel.png" alt="Logo SMAN 8 Banda Aceh" class="logo-img">
                <div class="brand-text">
                    <h1>SMA NEGERI 8</h1>
                    <p>BANDA ACEH</p>
                </div>
            </a>

            <nav class="nav-menu" id="navMenu">
                <ul>
                    <li class="nav-item <?php echo ($current_page == 'home') ? 'active' : ''; ?>">
                        <a href="index.php">Beranda</a>
                    </li>
                    
                    <li class="nav-item has-dropdown <?php echo ($current_page == 'profil') ? 'active' : ''; ?>">
                        <a href="#">Profil <i data-lucide="chevron-down"></i></a>
                        <div class="mega-menu">
                            <div class="mega-menu-grid">
                                <a href="index.php#profil" class="mega-item">
                                    <i data-lucide="user-check"></i>
                                    <div>
                                        <h4>Sambutan Kepsek</h4>
                                        <p>Pesan dan visi Kepala Sekolah</p>
                                    </div>
                                </a>
                                <a href="index.php#sejarah" class="mega-item">
                                    <i data-lucide="history"></i>
                                    <div>
                                        <h4>Sejarah Sekolah</h4>
                                        <p>Perjalanan pendirian SMAN 8</p>
                                    </div>
                                </a>
                                <a href="visi-misi.php" class="mega-item">
                                    <i data-lucide="target"></i>
                                    <div>
                                        <h4>Visi & Misi</h4>
                                        <p>Tujuan dan nilai utama sekolah</p>
                                    </div>
                                </a>
                                <a href="fasilitas.php" class="mega-item">
                                    <i data-lucide="building"></i>
                                    <div>
                                        <h4>Fasilitas</h4>
                                        <p>Laboratorium, Perpustakaan, dll.</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item"><a href="index.php#akademik">Akademik</a></li>
                    <li class="nav-item"><a href="index.php#kesiswaan">Kesiswaan</a></li>
                    <li class="nav-item"><a href="index.php#informasi">Informasi</a></li>
                    <li class="nav-item"><a href="index.php#galeri">Galeri</a></li>
                    <li class="nav-item"><a href="index.php#kontak">Kontak</a></li>
                </ul>
            </nav>

            <button class="mobile-toggle" id="mobileToggle">
                <i data-lucide="menu"></i>
            </button>
        </div>
    </header>