<?php
// Pastikan kredensial session sudah tervalidasi
require_once 'includes/auth.php'; // Atau sesuaikan nama file middleware session kamu
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Panel Kontrol</title>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f6f9;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            background-color: #1e293b;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar-brand {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.2rem;
            font-weight: bold;
            border-bottom: 1px solid #334155;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu li a:hover, 
        .sidebar-menu li.active a {
            background-color: #334155;
            color: #fff;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Header Bar */
        .topbar {
            background-color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #ef4444;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 6px 12px;
            border: 1px solid #ef4444;
            border-radius: 6px;
            transition: 0.2s;
        }

        .btn-logout:hover {
            background-color: #ef4444;
            color: #fff;
        }

        /* Dashboard Content Area */
        .content {
            padding: 30px;
            flex: 1;
        }

        .page-title {
            margin-bottom: 20px;
        }

        /* Cards Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-info h3 {
            font-size: 1.8rem;
            margin-top: 5px;
            color: #0f172a;
        }

        .stat-info p {
            color: #64748b;
            font-size: 0.85rem;
        }

        .stat-icon {
            background-color: #e0f2fe;
            color: #0284c7;
            padding: 12px;
            border-radius: 8px;
            display: flex;
        }

        .welcome-card {
            background: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div>
            <div class="sidebar-brand">
                <i data-lucide="shield-check"></i>
                <span>Panel Admin</span>
            </div>
            <ul class="sidebar-menu">
                <li class="active">
                    <a href="dashboard.php">
                        <i data-lucide="layout-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i data-lucide="users"></i>
                        <span>Data Siswa</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i data-lucide="newspaper"></i>
                        <span>Informasi & Berita</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i data-lucide="settings"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Top Navbar -->
        <header class="topbar">
            <h2>Portal Informasi & Kesiswaan</h2>
            <div class="user-profile">
                <i data-lucide="user-circle"></i>
                <span>Halo, <strong><?= htmlspecialchars($_SESSION['admin_username']); ?></strong></span>
                <a href="logout.php" class="btn-logout" style="margin-left: 15px;">
                    <i data-lucide="log-out" style="width: 16px; height: 16px;"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </header>

        <!-- Dynamic Content -->
        <main class="content">
            <div class="page-title">
                <h1>Dashboard Utama</h1>
            </div>

            <!-- Ringkasan Statik (Dapat Disesuaikan dengan Query Database) -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <p>Total Siswa</p>
                        <h3>1.240</h3>
                    </div>
                    <div class="stat-icon">
                        <i data-lucide="graduation-cap"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <p>Total Pengumuman</p>
                        <h3>18</h3>
                    </div>
                    <div class="stat-icon">
                        <i data-lucide="megaphone"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <p>Pesan Masuk</p>
                        <h3>5</h3>
                    </div>
                    <div class="stat-icon">
                        <i data-lucide="mail"></i>
                    </div>
                </div>
            </div>

            <!-- Pesan Selamat Datang -->
            <div class="welcome-card">
                <h3>Selamat Datang di Halaman Kontrol Administrator</h3>
                <p style="margin-top: 8px; color: #475569; line-height: 1.5;">
                    Anda berhasil masuk sebagai <strong><?= htmlspecialchars($_SESSION['admin_username']); ?></strong>. Gunakan menu navigasi di sebelah kiri untuk mengelola data kesiswaan dan informasi portal sekolah.
                </p>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>