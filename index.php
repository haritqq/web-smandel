<?php 
    $page_title = "Beranda";
    $current_page = "home"; 

    // Load visitor counter
    include 'includes/visitor_counter.php';

    // Simulation Data Berita (Nanti bagian ini diganti dengan query MySQL dari Admin Panel)
    // Contoh query DB nantinya: $query = mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3");
    $data_berita = [
        [
            'id' => 1,
            'judul' => 'Siswa SMAN 8 Banda Aceh Raih Medali Emas Olimpiade Sains',
            'ringkasan' => 'Prestasi membanggakan kembali diukir oleh siswa SMAN 8 Banda Aceh dalam ajang kompetisi sains tingkat provinsi.',
            'kategori' => 'Prestasi',
            'tanggal' => '10 Agu 2026',
            'gambar' => 'assets/img/hero1.jpg'
        ],
        [
            'id' => 2,
            'judul' => 'Pelaksanaan Kegiatan Peringatan Hari Pendidikan Nasional',
            'ringkasan' => 'Seluruh warga sekolah antusias mengikuti upacara bendera dan pentas seni dalam memperingati Hardiknas.',
            'kategori' => 'Kegiatan',
            'tanggal' => '02 Mei 2026',
            'gambar' => 'assets/img/hero2.jpg'
        ],
        [
            'id' => 3,
            'judul' => 'Sosialisasi Penerimaan Mahasiswa Baru Bersama Alumni',
            'ringkasan' => 'Ikatan Alumni SMAN 8 Banda Aceh menggelar sesi berbagi pengalaman dan strategi masuk Perguruan Tinggi Negeri.',
            'kategori' => 'Pengumuman',
            'tanggal' => '15 Apr 2026',
            'gambar' => 'assets/img/hero3.jpg'
        ]
    ];

    include 'includes/header.php'; 
?>

    <!-- 3. HERO SECTION WITH SLIDER -->
    <section class="hero-section">
        <div class="hero-slider">
            <div class="slide active" style="background-image: url('assets/img/hero1.jpg');"></div>
            <div class="slide" style="background-image: url('assets/img/hero2.jpg');"></div>
            <div class="slide" style="background-image: url('assets/img/hero3.jpg');"></div>
        </div>
        <div class="hero-overlay"></div>

        <div class="container">
            <div class="hero-content">
                <span class="hero-badge"><i data-lucide="sparkles"></i> Official School Portal</span>
                <h2 class="hero-title">SMA Negeri 8 <br><span>Banda Aceh</span></h2>
                <p class="hero-subtitle">Mewujudkan Generasi Unggul, Berkarakter, Berprestasi, dan Berwawasan Global</p>
                <div class="hero-buttons">
                    <a href="#berita" class="btn btn-primary">Berita dan Pengumuman <i data-lucide="arrow-right"></i></a>
                    <a href="#profil" class="btn btn-secondary">Galeri Kegiatan</a>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. FLOATING STATISTIK OVERLAY -->
    <div class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i data-lucide="users"></i></div>
                    <div class="stat-info">
                        <span class="stat-number" data-target="830">0</span>
                        <span class="stat-label">Siswa Aktif</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i data-lucide="user-check"></i></div>
                    <div class="stat-info">
                        <span class="stat-number" data-target="78">0</span>
                        <span class="stat-label">Guru & Tendik</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i data-lucide="award"></i></div>
                    <div class="stat-info">
                        <span class="stat-number" data-target="128">0</span>
                        <span class="stat-label">Prestasi Diraih</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i data-lucide="graduation-cap"></i></div>
                    <div class="stat-info">
                        <span class="stat-number" data-target="4120">0</span>
                        <span class="stat-label">Alumni Tersebar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. SECTION PROFIL SEKOLAH -->
    <section class="section-profile" id="profil">
        <div class="container">
            <div class="profile-grid">
                <div class="principal-card">
                    <img src="assets/img/kepsek.jpeg" alt="Kepala Sekolah" class="principal-img">
                    <div class="principal-details">
                        <h3>Dr. Erlawana, S.Pd., M.Pd</h3>
                        <p>Kepala SMA Negeri 8 Banda Aceh</p>
                    </div>
                </div>

                <div class="profile-content">
                    <span class="section-tag">Sambutan Kepala Sekolah</span>
                    <h2 class="section-title">Membentuk Generasi Cerdas, Unggul & Berkarakter</h2>
                    <p class="profile-description">
                        Selamat datang di portal resmi SMA Negeri 8 Banda Aceh. Kami berkomitmen menyelenggarakan pendidikan berkualitas tinggi yang memadukan keunggulan akademik, pengembangan potensi minat bakat, serta penguatan karakter berpijak pada nilai kebangsaan.
                    </p>

                    <div class="profile-meta">
                        <div class="meta-card">
                            <span class="meta-title">Akreditasi A</span>
                            <span class="meta-sub">Unggul Terverifikasi</span>
                        </div>
                        <div class="meta-card">
                            <span class="meta-title">NPSN 10105340</span>
                            <span class="meta-sub">Kemendikbudristek</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. QUICK ACCESS GRID (10 MENU KUNCI) -->
    <section class="section-quickaccess">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Akses Cepat</span>
                <h2 class="section-title">Layanan Digital Sekolah</h2>
                <p>Portofolio aplikasi & sistem informasi terpadu untuk siswa, guru, dan wali murid.</p>
            </div>

            <div class="quick-grid">
                <a href="#ppdb" target="_blank" class="quick-card">
                    <div class="quick-icon"><i data-lucide="user-plus"></i></div>
                    <h4>PPDB / SPMB</h4>
                    <span class="quick-badge">Pendaftaran</span>
                </a>
                <a href="http://id2.tunnel.my.id:4015/" target="_blank" class="quick-card">
                    <div class="quick-icon"><i data-lucide="file-text"></i></div>
                    <h4>E-Rapor</h4>
                    <span class="quick-badge">Siswa</span>
                </a>
                <a href="https://perpus.sma8bna.sch.id/" target="_blank" class="quick-card">
                    <div class="quick-icon"><i data-lucide="book-open"></i></div>
                    <h4>Perpustakaan Digital</h4>
                    <span class="quick-badge">E-Library</span>
                </a>
                <a href="#elearning" target="_blank" class="quick-card">
                    <div class="quick-icon"><i data-lucide="monitor"></i></div>
                    <h4>E-Learning</h4>
                    <span class="quick-badge">LMS</span>
                </a>
                <a href="#kalender" target="_blank" class="quick-card">
                    <div class="quick-icon"><i data-lucide="calendar"></i></div>
                    <h4>Kalender Akademik</h4>
                    <span class="quick-badge">Agenda</span>
                </a>
                <a href="https://cetakabsen.sma8bna.sch.id/" target="_blank" class="quick-card">
                    <div class="quick-icon"><i data-lucide="clock"></i></div>
                    <h4>Presensi</h4>
                    <span class="quick-badge">Absensi Sekolah</span>
                </a>
                <a href="#pengumuman" target="_blank" class="quick-card">
                    <div class="quick-icon"><i data-lucide="bell"></i></div>
                    <h4>Pengumuman</h4>
                    <span class="quick-badge">Informasi</span>
                </a>
                <a href="#download" target="_blank" class="quick-card">
                    <div class="quick-icon"><i data-lucide="download"></i></div>
                    <h4>Download Area</h4>
                    <span class="quick-badge">Berkas</span>
                </a>
                <!-- <a href="#galeri" target="_blank" class="quick-card">
                    <div class="quick-icon"><i data-lucide="image"></i></div>
                    <h4>Galeri Kegiatan</h4>
                    <span class="quick-badge">Foto/Video</span>
                </a>
                <a href="#kontak" target="_blank" class="quick-card">
                    <div class="quick-icon"><i data-lucide="phone-call"></i></div>
                    <h4>Hubungi Kami</h4>
                    <span class="quick-badge">Kontak</span>
                </a> -->
            </div>
        </div>
    </section>

    <!-- 7. SECTION BERITA & ARTIKEL TERBARU DENGAN SIDEBAR -->
    <section class="section-berita" id="berita">
        <div class="container">
            <div class="home-layout">
                <!-- Kolom Kiri: Berita & Informasi -->
                <div class="home-main">
                    <div class="section-header-flex">
                        <div>
                            <span class="section-tag">Kabar Sekolah</span>
                            <h2 class="section-title">Berita & Informasi Terbaru</h2>
                        </div>
                        <a href="berita.php" class="btn-outline">Lihat Semua <i data-lucide="arrow-right"></i></a>
                    </div>

                    <div class="berita-grid-two-col">
                        <?php foreach($data_berita as $berita): ?>
                        <article class="berita-card">
                            <div class="berita-thumb">
                                <img src="<?php echo $berita['gambar']; ?>" alt="<?php echo $berita['judul']; ?>">
                                <span class="berita-category"><?php echo $berita['kategori']; ?></span>
                            </div>
                            <div class="berita-body">
                                <div class="berita-meta">
                                    <span><i data-lucide="calendar"></i> <?php echo $berita['tanggal']; ?></span>
                                    <span><i data-lucide="user"></i> Humas SMAN 8</span>
                                </div>
                                <h3 class="berita-title">
                                    <a href="berita-detail.php?id=<?php echo $berita['id']; ?>"><?php echo $berita['judul']; ?></a>
                                </h3>
                                <p class="berita-excerpt"><?php echo $berita['ringkasan']; ?></p>
                                <a href="berita-detail.php?id=<?php echo $berita['id']; ?>" class="berita-link">
                                    Lihat Selengkapnya <i data-lucide="chevron-right"></i>
                                </a>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Kolom Kanan: Sidebar Widgets -->
                <aside class="home-sidebar">
                    <!-- Widget 1: Tanggal & Waktu -->
                    <div class="sidebar-widget widget-datetime">
                        <div class="widget-header">
                            <i data-lucide="calendar-days"></i>
                            <h3>Tanggal & Waktu</h3>
                        </div>
                        <div class="calendar-card">
                            <div class="calendar-header" id="calendarDayName">Senin</div>
                            <div class="calendar-body">
                                <div class="calendar-date" id="calendarDate">17</div>
                                <div class="calendar-month-year" id="calendarMonthYear">Agustus 2026</div>
                            </div>
                        </div>
                        <div class="digital-clock">
                            <span class="clock-time" id="clockTime">00:00:00</span>
                            <span class="clock-label">WIB</span>
                        </div>
                    </div>

                    <!-- Widget 2: Cuaca Banda Aceh -->
                    <div class="sidebar-widget widget-weather">
                        <div class="widget-header">
                            <i data-lucide="cloud-sun"></i>
                            <h3>Cuaca Banda Aceh</h3>
                        </div>
                        <div class="weather-card" id="weatherCard">
                            <div class="weather-loading">
                                <div class="spinner"></div>
                                <span>Mengambil info cuaca...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Widget 3: Statistik Pengunjung -->
                    <div class="sidebar-widget widget-visitor">
                        <div class="widget-header">
                            <i data-lucide="bar-chart-3"></i>
                            <h3>Statistik Pengunjung</h3>
                        </div>
                        <div class="visitor-stats">
                            <div class="visitor-item">
                                <div class="visitor-icon"><i data-lucide="users"></i></div>
                                <div class="visitor-info">
                                    <span class="visitor-count"><?php echo number_format($visitor_stats['daily']); ?></span>
                                    <span class="visitor-label">Hari Ini</span>
                                </div>
                            </div>
                            <div class="visitor-item">
                                <div class="visitor-icon"><i data-lucide="calendar"></i></div>
                                <div class="visitor-info">
                                    <span class="visitor-count"><?php echo number_format($visitor_stats['monthly']); ?></span>
                                    <span class="visitor-label">Bulan Ini</span>
                                </div>
                            </div>
                            <div class="visitor-item">
                                <div class="visitor-icon"><i data-lucide="trending-up"></i></div>
                                <div class="visitor-info">
                                    <span class="visitor-count"><?php echo number_format($visitor_stats['yearly']); ?></span>
                                    <span class="visitor-label">Tahun Ini</span>
                                </div>
                            </div>
                            <div class="visitor-item total">
                                <div class="visitor-icon"><i data-lucide="globe"></i></div>
                                <div class="visitor-info">
                                    <span class="visitor-count"><?php echo number_format($visitor_stats['all_time']); ?></span>
                                    <span class="visitor-label">Total Pengunjung</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Widget 4: Fitur / Agenda Sekolah -->
                    <div class="sidebar-widget widget-features">
                        <div class="widget-header">
                            <i data-lucide="bookmark"></i>
                            <h3>Agenda & Tautan</h3>
                        </div>
                        <div class="sidebar-agenda">
                            <div class="agenda-item">
                                <div class="agenda-date">
                                    <span class="agenda-day">24</span>
                                    <span class="agenda-month">Agt</span>
                                </div>
                                <div class="agenda-detail">
                                    <h4>Simulasi ANBK Tahap I</h4>
                                    <p><i data-lucide="clock"></i> 08:00 - Selesai</p>
                                </div>
                            </div>
                            <div class="agenda-item">
                                <div class="agenda-date">
                                    <span class="agenda-day">17</span>
                                    <span class="agenda-month">Sep</span>
                                </div>
                                <div class="agenda-detail">
                                    <h4>Ujian Tengah Semester</h4>
                                    <p><i data-lucide="clock"></i> 07:30 - 13:00</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="social-links">
                            <h4>Ikuti Media Sosial Kami</h4>
                            <div class="social-grid">
                                <a href="https://instagram.com" target="_blank" class="social-btn instagram" title="Instagram">
                                    <i data-lucide="instagram"></i>
                                </a>
                                <a href="https://youtube.com" target="_blank" class="social-btn youtube" title="YouTube">
                                    <i data-lucide="youtube"></i>
                                </a>
                                <a href="https://facebook.com" target="_blank" class="social-btn facebook" title="Facebook">
                                    <i data-lucide="facebook"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>