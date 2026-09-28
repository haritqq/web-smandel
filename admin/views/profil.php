<?php
require_once __DIR__ . '/../config/koneksi.php';

$admin_id = $_SESSION['admin_id'] ?? 1; // Mengambil ID dari session login
$success_msg = '';
$error_msg   = '';

// Proses Simpan/Update Profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, trim($_POST['nama_lengkap'] ?? ''));
    $email        = mysqli_real_escape_string($koneksi, trim($_POST['email'] ?? ''));

    if (!empty($nama_lengkap)) {
        $queryUpdate = "UPDATE users SET nama_lengkap = '$nama_lengkap', email = '$email' WHERE id = $admin_id";
        if (mysqli_query($koneksi, $queryUpdate)) {
            $_SESSION['admin_nama'] = $nama_lengkap; // Update session nama
            $success_msg = "Detail akun berhasil diperbarui!";
        } else {
            $error_msg = "Gagal memperbarui profil: " . mysqli_error($koneksi);
        }
    } else {
        $error_msg = "Nama lengkap tidak boleh kosong!";
    }
}

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