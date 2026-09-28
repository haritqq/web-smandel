<?php
require_once __DIR__ . '/../config/koneksi.php';

$admin_id = $_SESSION['admin_id'] ?? 1;
$success_msg = '';
$error_msg   = '';

// Proses Ubah Password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $password_lama    = $_POST['password_lama'] ?? '';
    $password_baru    = $_POST['password_baru'] ?? '';
    $konfirmasi_pass  = $_POST['konfirmasi_password'] ?? '';

    // Ambil password tersimpan di database
    $queryUser = mysqli_query($koneksi, "SELECT password FROM users WHERE id = $admin_id");
    $user = mysqli_fetch_assoc($queryUser);

    if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_pass)) {
        $error_msg = "Semua kolom form wajib diisi!";
    } elseif ($password_baru !== $konfirmasi_pass) {
        $error_msg = "Konfirmasi password baru tidak cocok!";
    } elseif (strlen($password_baru) < 6) {
        $error_msg = "Password baru minimal harus 6 karakter!";
    } else {
        // Cek verifikasi password lama (Mendukung password_verify atau plain text MD5)
        $is_valid_old = false;
        if (password_verify($password_lama, $user['password'])) {
            $is_valid_old = true;
        } elseif (md5($password_lama) === $user['password'] || $password_lama === $user['password']) {
            $is_valid_old = true;
        }

        if ($is_valid_old) {
            // Hash password baru dengan BCRYPT
            $new_hash = password_hash($password_baru, PASSWORD_BCRYPT);
            $queryUpdate = "UPDATE users SET password = '$new_hash' WHERE id = $admin_id";

            if (mysqli_query($koneksi, $queryUpdate)) {
                $success_msg = "Password berhasil diperbarui!";
            } else {
                $error_msg = "Gagal mengubah password: " . mysqli_error($koneksi);
            }
        } else {
            $error_msg = "Password lama yang Anda masukkan salah!";
        }
    }
}
?>

<div class="page-header">
    <h1>Ubah Password</h1>
    <p>Perbarui kata sandi akun Anda secara berkala untuk menjaga keamanan data.</p>
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

<div class="welcome-card" style="max-width: 500px;">
    <form action="index.php?page=ubah_password" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
        <div>
            <label style="display: block; font-weight: 500; margin-bottom: 6px;">Password Saat Ini</label>
            <input type="password" name="password_lama" required placeholder="Masukkan password saat ini" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
        </div>

        <div>
            <label style="display: block; font-weight: 500; margin-bottom: 6px;">Password Baru</label>
            <input type="password" name="password_baru" required placeholder="Minimal 6 karakter" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
        </div>

        <div>
            <label style="display: block; font-weight: 500; margin-bottom: 6px;">Konfirmasi Password Baru</label>
            <input type="password" name="konfirmasi_password" required placeholder="Ulangi password baru" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
            <button type="submit" name="update_password" style="background: var(--primary); color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 500; cursor: pointer;">Update Password</button>
        </div>
    </form>
</div>