<?php
require_once __DIR__ . '/admin/config/koneksi.php';
header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

// 1. HANDLER FITUR LIKE
if ($action === 'like') {
    $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    
    if ($postId > 0) {
        // Update jumlah like
        $query = "UPDATE posts SET jumlah_like = jumlah_like + 1 WHERE id = $postId";
        if (mysqli_query($koneksi, $query)) {
            // Ambil jumlah like terbaru
            $res = mysqli_query($koneksi, "SELECT jumlah_like FROM posts WHERE id = $postId");
            $data = mysqli_fetch_assoc($res);
            echo json_encode(['success' => true, 'likes' => $data['jumlah_like']]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => 'Gagal menyukai artikel.']);
    exit;
}

// 2. HANDLER FITUR KOMENTAR
if ($action === 'comment') {
    $postId   = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $nama     = isset($_POST['nama']) ? trim(mysqli_real_escape_string($koneksi, $_POST['nama'])) : '';
    $email    = isset($_POST['email']) ? trim(mysqli_real_escape_string($koneksi, $_POST['email'])) : '';
    $komentar = isset($_POST['komentar']) ? trim(mysqli_real_escape_string($koneksi, $_POST['komentar'])) : '';

    if ($postId > 0 && !empty($nama) && !empty($email) && !empty($komentar)) {
        $query = "INSERT INTO post_comments (post_id, nama, email, komentar, status) VALUES ($postId, '$nama', '$email', '$komentar', 'Disetujui')";
        if (mysqli_query($koneksi, $query)) {
            echo json_encode(['success' => true, 'message' => 'Komentar berhasil dikirim!']);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => 'Lengkapi semua kolom formulir!']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);