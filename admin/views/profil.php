<?php
require_once __DIR__ . '/../config/koneksi.php';

// 1. Ambil ID dari berbagai kemungkinan nama session login
$admin_id = $_SESSION['admin_id'] 
         ?? $_SESSION['user_id'] 
         ?? $_SESSION['id'] 
         ?? $_SESSION['user']['id'] 
         ?? null;

// 2. Jika session ID masih kosong, ambil ID berdasarkan username yang sedang tertera di session
if (!$admin_id && !empty($_SESSION['admin_username'])) {
    $uname = mysqli_real_escape_string($koneksi, $_SESSION['admin_username']);
    $qUser = mysqli_query($koneksi, "SELECT id FROM users WHERE username = '$uname' LIMIT 1");
    if ($rUser = mysqli_fetch_assoc($qUser)) {
        $admin_id = $rUser['id'];
        $_SESSION['admin_id'] = $admin_id; // Simpan ke session agar selanjutnya otomatis terdeteksi
    }
}

// 3. Jika tetap tidak ditemukan, baru tampilkan pesan peringatan
if (!$admin_id) {
    echo '<div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 16px; border-radius: 8px; margin: 20px;">
            <strong>Akses Ditolak:</strong> Session login ID tidak ditemukan. Silakan lakukan <a href="logout.php" style="color: #dc2626; text-decoration: underline;">Login Ulang</a>.
          </div>';
    exit;
}

$success_msg = '';
$error_msg   = '';

// Ambil Data Admin Saat Ini
$queryUser = mysqli_query($koneksi, "SELECT * FROM users WHERE id = $admin_id");
$user = mysqli_fetch_assoc($queryUser);
?>

<div class="page-header">
    <h1>Detail Akun</h1>
    <p>Kelola dan perbarui informasi profil akun administrator Anda.</p>
</div>

<?php if (!empty($success_msg)): ?>
    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
        <?= htmlspecialchars($success_msg); ?>
    </div>
<?php endif; ?>

<?php if (!empty($error_msg)): ?>
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
        <?= htmlspecialchars($error_msg); ?>
    </div>
<?php endif; ?>

<div class="welcome-card" style="max-width: 600px;">
    <form action="index.php?page=profil" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
        <div>
            <label style="display: block; font-weight: 500; margin-bottom: 6px;">Username</label>
            <input type="text" value="<?= htmlspecialchars($user['username'] ?? ''); ?>" disabled style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: #f1f5f9; color: #64748b; cursor: not-allowed;">
            <small style="color: var(--text-muted); margin-top: 4px; display: block;">Username tidak dapat diubah.</small>
        </div>

        <div>
            <label style="display: block; font-weight: 500; margin-bottom: 6px;">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap'] ?? ''); ?>" required style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
        </div>

        <div>
            <label style="display: block; font-weight: 500; margin-bottom: 6px;">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>" placeholder="admin@sekolah.sch.id" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
        </div>

        <div>
            <label style="display: block; font-weight: 500; margin-bottom: 6px;">Role / Hak Akses</label>
            <input type="text" value="<?= htmlspecialchars(ucfirst($user['role'] ?? 'Administrator')); ?>" disabled style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: #f1f5f9; color: #64748b; cursor: not-allowed;">
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
            <button type="submit" name="update_profile" style="background: var(--primary); color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 500; cursor: pointer;">Simpan Perubahan</button>
        </div>
    </form>
</div>