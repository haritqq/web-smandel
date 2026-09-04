<?php
session_start();

// Jika admin sudah login, langsung arahkan ke index.php
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Kredensial contoh
    if ($username === 'admin' && $password === 'admin123') {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username']  = $username;

        header("Location: index.php");
        exit;
    } else {
        $error_message = "Username atau password salah!";
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