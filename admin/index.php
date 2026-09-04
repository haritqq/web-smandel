<?php
// Validasi session login
require_once __DIR__ . '/includes/auth.php';

// Load komponen tampilan atas & navigasi
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<!-- Bagian Konten Utama -->
<div class="main-content">
    <!-- Navbar Atas -->
    <header class="topbar">
        <h2>Portal Informasi & Kesiswaan</h2>
        <div style="display: flex; align-items: center; gap: 10px;">
            <i data-lucide="user-circle"></i>
            <span>Halo, <strong><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></strong></span>
            <a href="logout.php" class="btn-logout" style="margin-left: 15px;">
                <i data-lucide="log-out" style="width: 16px; height: 16px;"></i>
                <span>Keluar</span>
            </a>
        </div>
    </header>

    <!-- Isi Dashboard -->
    <main class="content">
        <h1 style="margin-bottom: 20px;">Dashboard Utama</h1>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: #fff; padding: 20px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="color: #64748b; font-size: 0.85rem;">Total Siswa</p>
                    <h3 style="font-size: 1.8rem; margin-top: 5px;">1.240</h3>
                </div>
                <div style="background-color: #e0f2fe; color: #0284c7; padding: 12px; border-radius: 8px;">
                    <i data-lucide="graduation-cap"></i>
                </div>
            </div>
            
            <div style="background: #fff; padding: 20px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="color: #64748b; font-size: 0.85rem;">Total Pengumuman</p>
                    <h3 style="font-size: 1.8rem; margin-top: 5px;">18</h3>
                </div>
                <div style="background-color: #e0f2fe; color: #0284c7; padding: 12px; border-radius: 8px;">
                    <i data-lucide="megaphone"></i>
                </div>
            </div>
        </div>

        <div style="background: #fff; padding: 24px; border-radius: 8px;">
            <h3>Selamat Datang di Halaman Kontrol Administrator</h3>
            <p style="margin-top: 8px; color: #475569;">
                Anda berhasil masuk sebagai <strong><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></strong>.
            </p>
        </div>
    </main>

<?php
// Load footer
require_once __DIR__ . '/includes/footer.php';
?>