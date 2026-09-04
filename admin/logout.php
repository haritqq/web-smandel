<?php
// 1. Inisialisasi/aktifkan sesi
session_start();

// 2. Kosongkan semua variabel sesi
$_SESSION = array();

// 3. Hapus cookie sesi jika ada (untuk keamanan tambahan)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4. Hancurkan sesi di server
session_destroy();

// 5. Alihkan kembali ke halaman login
header("Location: login.php");
exit;
?>