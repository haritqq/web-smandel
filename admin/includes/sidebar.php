<?php
// Ambil parameter halaman aktif dari URL
$currentPage = $_GET['page'] ?? 'dashboard';
?>

<aside class="sidebar" style="width: 250px; background-color: #1e293b; color: #fff; display: flex; flex-direction: column;">
    <div class="sidebar-brand" style="padding: 20px; display: flex; align-items: center; gap: 10px; font-size: 1.2rem; font-weight: bold; border-bottom: 1px solid #334155;">
        <i data-lucide="shield-check"></i>
        <span>Panel Admin</span>
    </div>
    
    <ul class="sidebar-menu" style="list-style: none; padding: 20px 0; margin: 0;">
        <!-- Menu Dashboard -->
        <li>
            <a href="index.php?page=dashboard" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: <?= $currentPage === 'dashboard' ? '#fff' : '#94a3b8'; ?>; text-decoration: none; background-color: <?= $currentPage === 'dashboard' ? '#334155' : 'transparent'; ?>;">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Contoh Menu Data Siswa -->
        <li>
            <a href="index.php?page=siswa" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: <?= $currentPage === 'siswa' ? '#fff' : '#94a3b8'; ?>; text-decoration: none; background-color: <?= $currentPage === 'siswa' ? '#334155' : 'transparent'; ?>;">
                <i data-lucide="users"></i>
                <span>Data Siswa</span>
            </a>
        </li>

        <!-- Contoh Menu Cetak Absen -->
        <li>
            <a href="index.php?page=cetak_absen" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: <?= $currentPage === 'cetak_absen' ? '#fff' : '#94a3b8'; ?>; text-decoration: none; background-color: <?= $currentPage === 'cetak_absen' ? '#334155' : 'transparent'; ?>;">
                <i data-lucide="printer"></i>
                <span>Cetak Absen</span>
            </a>
        </li>
    </ul>
</aside>