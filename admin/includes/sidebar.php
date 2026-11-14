<?php
$currentPage = $_GET['page'] ?? 'dashboard';

// Daftar halaman yang termasuk dalam sub-menu Kelola KTS
$ktsPages = ['kelola-kts', 'kts-template', 'kts-siswa', 'kts-tahun-ajaran'];
$isKtsActive = in_array($currentPage, $ktsPages);
?>

<style>
/* Style tambahan untuk Sub-Menu Sidebar */
.sidebar-menu .has-submenu > a {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.sidebar-menu .menu-label {
    display: flex;
    align-items: center;
    gap: 10px;
}
.sidebar-menu .arrow-icon {
    width: 16px;
    height: 16px;
    transition: transform 0.25s ease;
}
.sidebar-menu .has-submenu.open .arrow-icon {
    transform: rotate(180deg);
}
.sidebar-submenu {
    list-style: none;
    padding-left: 28px;
    margin: 4px 0;
    display: none;
}
.sidebar-menu .has-submenu.open .sidebar-submenu {
    display: block;
}
.sidebar-submenu li a {
    font-size: 0.88rem;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    border-radius: 6px;
    opacity: 0.85;
}
.sidebar-submenu li a:hover,
.sidebar-submenu li a.active {
    opacity: 1;
    font-weight: 600;
}
</style>

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
                <i data-lucide="tags"></i>
                <span>Tags & Mentions</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=cetak_absen" class="<?= $currentPage === 'cetak_absen' ? 'active' : ''; ?>">
                <i data-lucide="images"></i>
                <span>Media</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=data_gtk" class="<?= $currentPage === 'data_gtk' ? 'active' : ''; ?>">
                <i data-lucide="users"></i>
                <span>Guru & Tendik</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=komentar" class="<?= $currentPage === 'komentar' ? 'active' : ''; ?>">
                <i data-lucide="messages-square"></i>
                <span>Komentar</span>
            </a>
        </li>

        <!-- MENU KELOLA KTS DENGAN SUB-MENU -->
        <li class="has-submenu <?= $isKtsActive ? 'open' : ''; ?>">
            <a href="javascript:void(0)" onclick="toggleSubmenu(this)" class="<?= $isKtsActive ? 'active' : ''; ?>">
                <div class="menu-label">
                    <i data-lucide="id-card"></i>
                    <span>Kartu Pelajar</span>
                </div>
                <i data-lucide="chevron-down" class="arrow-icon"></i>
            </a>
            <ul class="sidebar-submenu">
                <li>
                    <a href="index.php?page=kelola-kts" class="<?= $currentPage === 'kelola-kts' ? 'active' : ''; ?>">
                        <i data-lucide="layout-template" style="width: 15px; height: 15px;"></i>
                        <span>Pengaturan Template</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=kts-siswa" class="<?= $currentPage === 'kts-siswa' ? 'active' : ''; ?>">
                        <i data-lucide="user-check" style="width: 15px; height: 15px;"></i>
                        <span>Data Siswa & Akun</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=kts-tahun-ajaran" class="<?= $currentPage === 'kts-tahun-ajaran' ? 'active' : ''; ?>">
                        <i data-lucide="calendar" style="width: 15px; height: 15px;"></i>
                        <span>Tahun Ajaran</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- <li>
            <a href="index.php?page=pengaturan" class="<?= $currentPage === 'pengaturan' ? 'active' : ''; ?>">
                <i data-lucide="settings"></i>
                <span>Pengaturan</span>
            </a>
        </li> -->
    </ul>
</aside>

<script>
function toggleSubmenu(el) {
    const parentLi = el.parentElement;
    parentLi.classList.toggle('open');
}
</script>