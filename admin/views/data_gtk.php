<?php
require_once __DIR__ . '/../config/koneksi.php';

$pesan = "";

// Proses Tambah Data
if (isset($_POST['tambah'])) {
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
    $mapel   = mysqli_real_escape_string($koneksi, $_POST['mapel']);
    $nip     = mysqli_real_escape_string($koneksi, $_POST['nip']);

    // Upload Foto
    $foto_name = $_FILES['foto']['name'];
    $tmp_name  = $_FILES['foto']['tmp_name'];
    $folder    = "assets/img/";

    if (!empty($foto_name)) {
        // Beri nama unik agar foto tidak saling timpa
        $nama_foto_baru = time() . '_' . $foto_name;
        $path_foto = $folder . $nama_foto_baru;

        if (move_uploaded_file($tmp_name, $path_foto)) {
            $sql = "INSERT INTO guru_tendik (nama, jabatan, mapel, nip, foto) VALUES ('$nama', '$jabatan', '$mapel', '$nip', '$path_foto')";
        } else {
            $pesan = "<div class='alert error'>Gagal mengunggah foto!</div>";
        }
    } else {
        // Foto default jika tidak mengunggah
        $path_foto = "assets/img/default.jpg";
        $sql = "INSERT INTO guru_tendik (nama, jabatan, mapel, nip, foto) VALUES ('$nama', '$jabatan', '$mapel', '$nip', '$path_foto')";
    }

    if (isset($sql) && mysqli_query($koneksi, $sql)) {
        $pesan = "<div class='alert success'>Data berhasil ditambahkan!</div>";
    }
}

// Proses Hapus Data
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    
    // Ambil data foto lama untuk dihapus dari direktori
    $res = mysqli_query($koneksi, "SELECT foto FROM guru_tendik WHERE id = $id");
    $data = mysqli_fetch_assoc($res);
    if ($data && file_exists($data['foto']) && $data['foto'] != 'assets/img/default.jpg') {
        unlink($data['foto']);
    }

    mysqli_query($koneksi, "DELETE FROM guru_tendik WHERE id = $id");
    // Gunakan JavaScript Redirect agar tidak terjadi 'Headers already sent'
    echo "<script>window.location.href='index.php?page=data_guru';</script>";
    exit();
}

// Ambil semua data
$result = mysqli_query($koneksi, "SELECT * FROM guru_tendik ORDER BY id DESC");
?>

<style>
    .card-container { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
    .btn-submit { padding: 10px 15px; background: #28a745; color: #fff; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; }
    .btn-danger { background: #dc3545; color: #fff; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; display: inline-block; }
    .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    .success { background: #d4edda; color: #155724; }
    .error { background: #f8d7da; color: #721c24; }
    .table-data { width: 100%; border-collapse: collapse; margin-top: 25px; }
    .table-data, .table-data th, .table-data td { border: 1px solid #ddd; }
    .table-data th, .table-data td { padding: 10px; text-align: left; }
    .table-data th { background-color: #0284c7; color: white; }
    img.thumb { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; }
</style>

<div class="card-container">
    <h2>Form Input Data Guru & Tendik</h2>
    <?= $pesan; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Lengkap & Gelar</label>
            <input type="text" name="nama" required placeholder="Contoh: Dr. Erlawana, S.Pd., M.Pd">
        </div>
        <div class="form-group">
            <label>Jabatan</label>
            <input type="text" name="jabatan" required placeholder="Contoh: Kepala Sekolah / Guru Senior">
        </div>
        <div class="form-group">
            <label>Mata Pelajaran</label>
            <input type="text" name="mapel" required placeholder="Contoh: Bahasa Indonesia">
        </div>
        <div class="form-group">
            <label>NIP</label>
            <input type="text" name="nip" required placeholder="Contoh: NIP. 19720315 199702 2 001">
        </div>
        <div class="form-group">
            <label>Foto Formal</label>
            <input type="file" name="foto" accept="image/*">
        </div>
        <button type="submit" name="tambah" class="btn-submit">Simpan Data</button>
    </form>

    <hr style="margin: 40px 0 20px 0; border: 0; border-top: 1px solid #eee;">

    <h2>Daftar Guru & Tendik</h2>
    <table class="table-data">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Mapel</th>
                <th>NIP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($row['foto']); ?>" class="thumb" alt="Foto"></td>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['jabatan']); ?></td>
                    <td><?= htmlspecialchars($row['mapel']); ?></td>
                    <td><?= htmlspecialchars($row['nip']); ?></td>
                    <td>
                        <a href="index.php?page=data_guru&hapus=<?= $row['id']; ?>" class="btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #888;">Belum ada data guru.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>