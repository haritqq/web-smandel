<?php
// 1. Validasi session login
require_once __DIR__ . '/config/koneksi.php'; // Koneksi Database
require_once __DIR__ . '/config/auth.php';

// 2. Ambil parameter 'page' dari URL (Default: 'dashboard')
$page = $_GET['page'] ?? 'dashboard';

// 3. Whitelist halaman yang diizinkan beserta jalurnya di folder views/
// Jika buat menu/fitur baru (misal: cetak absen), cukup tambahkan ke array ini
$pages = [
    'dashboard'   => 'views/dashboard.php',
    'siswa'       => 'views/siswa.php',
    'posts'       => 'views/posts.php', // Menu Postingan
    'guru'        => 'views/guru.php',
    'cetak_absen' => 'views/cetak_absen.php', // Contoh menu baru kedepannya
    'pengaturan'  => 'views/pengaturan.php'
];

// 4. Load Layout Atas (Header & Sidebar)
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<!-- Bagian Konten Utama (Memuat File Dinamis Sesuai Parameter Page) -->
<div class="main-content">
    <!-- Navbar Atas -->
    <!-- Topbar Header dengan Tombol Toggle -->
    <header class="topbar">
        <div style="display: flex; align-items: center; gap: 16px;">
            <button type="button" class="btn-toggle-sidebar" onclick="toggleSidebar()" title="Kecilkan/Buka Sidebar">
                <i data-lucide="menu"></i>
            </button>
            <h2 class="topbar-title">Portal Informasi & Kesiswaan</h2>
        </div>

        <div class="user-profile">
            <div class="user-info">
                <i data-lucide="user-circle"></i>
                <span>Halo, <strong><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></strong></span>
            </div>
            <a href="logout.php" class="btn-logout">
                <i data-lucide="log-out" style="width: 16px; height: 16px;"></i>
                <span>Keluar</span>
            </a>
        </div>
    </header>

    <!-- Area Konten Dinamis -->
    <main class="content">
        <?php
        // Cek apakah halaman yang diminta ada dalam whitelist dan file fisik-nya wujud
        if (array_key_exists($page, $pages) && file_exists(__DIR__ . '/' . $pages[$page])) {
            require_once __DIR__ . '/' . $pages[$page];
        } else {
            // Tampilan jika file/halaman tidak ditemukan (Error 404)
            echo '
            <div style="background: #fff; padding: 40px; border-radius: 8px; text-align: center;">
                <h2>404 - Halaman Tidak Ditemukan</h2>
                <p style="color: #64748b; margin-top: 8px;">Menu yang Anda pilih tidak tersedia atau filenya belum dibuat di folder <code>views/</code>.</p>
                <a href="index.php?page=dashboard" style="display: inline-block; margin-top: 15px; color: #0284c7; text-decoration: none;">← Kembali ke Dashboard</a>
            </div>';
        }
        ?>
    </main>

<?php
// 5. Load Footer
require_once __DIR__ . '/includes/footer.php';
?>