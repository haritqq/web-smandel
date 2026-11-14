<?php
session_start();

// 1. Hubungkan ke database
require_once __DIR__ . '/config/koneksi.php';

// Jika admin sudah login, langsung arahkan ke index.php
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        // Safe Query untuk mencegah SQL Injection
        $username_clean = mysqli_real_escape_string($koneksi, $username);
        $queryUser = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username_clean' LIMIT 1");

        if ($queryUser && mysqli_num_rows($queryUser) > 0) {
            $user = mysqli_fetch_assoc($queryUser);
            $db_password = $user['password'];

            // Verifikasi Password (Mendukung password_hash, MD5, SHA1, atau plain text)
            $is_password_valid = false;
            if (password_verify($password, $db_password)) {
                $is_password_valid = true;
            } elseif (md5($password) === $db_password || sha1($password) === $db_password || $password === $db_password) {
                $is_password_valid = true;
            }

            if ($is_password_valid) {
                session_regenerate_id(true);

                // SIMPAN SEMUA DATA USER KE SESSION
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id']        = $user['id'];
                $_SESSION['admin_username']  = $user['username'];
                $_SESSION['admin_nama']      = $user['nama_lengkap'] ?? $user['username'];
                $_SESSION['admin_role']      = $user['role'] ?? 'Administrator';

                header("Location: index.php");
                exit;
            } else {
                $error_message = "Password yang Anda masukkan salah!";
            }
        } else {
            $error_message = "Username tidak ditemukan di database!";
        }
    } else {
        $error_message = "Harap isi username dan password!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator | Panel Kontrol</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="assets/css/admin-login.css">
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="brand-icon">
                <i data-lucide="shield-check"></i>
            </div>
            <h2>Portal Admin</h2>
            <p>Silakan masuk untuk mengelola portal informasi & kesiswaan</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="alert-error">
                <i data-lucide="alert-circle" style="width: 18px; height: 18px;"></i>
                <span><?= htmlspecialchars($error_message); ?></span>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <i data-lucide="user" class="input-icon"></i>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="off">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i data-lucide="lock" class="input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <span>Masuk ke Dashboard</span>
                <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
            </button>
        </form>

        <div class="login-footer">
            <a href="../index.php">← Kembali ke Halaman Utama</a>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>