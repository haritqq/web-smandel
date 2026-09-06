<div class="page-header">
    <h1>Dashboard Utama</h1>
    <p>Ringkasan informasi dan aktivitas sistem terkini.</p>
</div>

<!-- Grid Cards Minimalis -->
<div class="stats-grid">
    <div class="stat-card">
        <div>
            <p>Total Siswa</p>
            <h3>1.240</h3>
        </div>
        <div class="stat-icon blue">
            <i data-lucide="graduation-cap"></i>
        </div>
    </div>
    
    <div class="stat-card">
        <div>
            <p>Kehadiran Hari Ini</p>
            <h3>98%</h3>
        </div>
        <div class="stat-icon green">
            <i data-lucide="check-circle-2"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <p>Total Pengumuman</p>
            <h3>18</h3>
        </div>
        <div class="stat-icon orange">
            <i data-lucide="megaphone"></i>
        </div>
    </div>
</div>

<!-- Welcome Panel -->
<div class="welcome-card">
    <h3>Selamat Datang kembali, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?> 👋</h3>
    <p>
        Sistem kontrol administrator berjalan optimal. Anda dapat mengelola data kesiswaan, jadwal cetak absensi, serta memperbarui pengumuman melalui menu navigasi di sebelah kiri.
    </p>
</div>