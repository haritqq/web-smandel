<?php
$currentPage = $_GET['page'] ?? 'dashboard';
?>

<aside class="sidebar">
    <div class="sidebar-brand">
        <i data-lucide="shield"></i>
        <span>Portal Admin</span>
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="index.php?page=dashboard" class="<?= $currentPage === 'dashboard' ? 'active' : ''; ?>">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=posts" class="<?= $currentPage === 'posts' ? 'active' : ''; ?>">
                <i data-lucide="newspaper"></i>
                <span>Postingan</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=siswa" class="<?= $currentPage === 'siswa' ? 'active' : ''; ?>">
                <i data-lucide="users"></i>
                <span>Data Siswa</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=cetak_absen" class="<?= $currentPage === 'cetak_absen' ? 'active' : ''; ?>">
                <i data-lucide="printer"></i>
                <span>Cetak Absen</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=pengaturan" class="<?= $currentPage === 'pengaturan' ? 'active' : ''; ?>">
                <i data-lucide="settings"></i>
                <span>Pengaturan</span>
            </a>
        </li>
    </ul>
</aside>