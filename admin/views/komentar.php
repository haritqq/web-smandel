<?php
require_once __DIR__ . '/../config/koneksi.php';

// Fitur Hapus Komentar
if (isset($_GET['hapus'])) {
    $id_komen = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM post_comments WHERE id = $id_komen");
    header("Location: komentar.php");
    exit;
}

// Ambil semua komentar beserta judul berita
$query = "SELECT post_comments.*, posts.judul 
          FROM post_comments 
          JOIN posts ON post_comments.post_id = posts.id 
          ORDER BY post_comments.created_at DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Komentar Berita</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; }
        th { background: #f1f5f9; }
        .btn-delete { color: red; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body style="font-family: sans-serif; padding: 20px;">

    <h2>Daftar Komentar Berita</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Berita</th>
                <th>Pengirim</th>
                <th>Komentar</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><strong><?= htmlspecialchars($row['judul']); ?></strong></td>
                <td><?= htmlspecialchars($row['nama']); ?><br><small><?= htmlspecialchars($row['email']); ?></small></td>
                <td><?= htmlspecialchars($row['komentar']); ?></td>
                <td><?= date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                <td>
                    <a href="komentar.php?hapus=<?= $row['id']; ?>" class="btn-delete" onclick="return confirm('Hapus komentar ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>