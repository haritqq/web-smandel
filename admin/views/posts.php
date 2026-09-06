<?php
// Panggil koneksi agar text editor (VS Code) mengenali tipe data $koneksi
require_once __DIR__ . '/../config/koneksi.php';

$success_msg = '';
$error_msg   = '';

// --- FUNGSI UPLOAD MEDIA ---
function uploadMedia($file) {
    if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $targetDir = __DIR__ . '/../uploads/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($file['name']));
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Validasi Ekstensi Gambar & Video
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'webm', 'ogg'];
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            return $fileName;
        }
    }
    return false;
}

// Proses Simpan Postingan Baru ke Database
// --- PROSES ACTION (TAMBAH / EDIT / HAPUS) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
// 1. TAMBAH POSTINGAN
    if (isset($_POST['submit_post'])) {
        $judul    = mysqli_real_escape_string($koneksi, trim($_POST['judul'] ?? ''));
        $kategori = mysqli_real_escape_string($koneksi, trim($_POST['kategori'] ?? 'Berita'));
        $konten   = mysqli_real_escape_string($koneksi, trim($_POST['konten'] ?? ''));
        $status   = mysqli_real_escape_string($koneksi, trim($_POST['status'] ?? 'Diterbitkan'));
        $user_id  = $_SESSION['admin_id'] ?? 1;
    
    // Buat URL slug ramah SEO dari judul
    // 1. Buat Slug Dasar dari Judul
    $base_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
    $slug      = $base_slug;

    // 2. Cek apakah Slug sudah ada di Database
    $checkSlug = mysqli_query($koneksi, "SELECT id FROM posts WHERE slug = '$slug'");

    // 3. Jika Slug sudah ada, tambahkan penanda unik (unik berdasarkan waktu/angka)
    if (mysqli_num_rows($checkSlug) > 0) {
        $slug = $base_slug . '-' . time(); // Contoh hasil: info-aplikasi-1712345678
    }

    // Upload File Media
        $media = NULL;
        if (!empty($_FILES['media']['name'])) {
            $uploadResult = uploadMedia($_FILES['media']);
            if ($uploadResult === false) {
                $error_msg = "Format file media tidak didukung! (Gunakan JPG, PNG, GIF, MP4, WEBM).";
            } else {
                $media = $uploadResult;
            }
        }

        if (empty($error_msg) && !empty($judul) && !empty($konten)) {
            $queryInsert = "INSERT INTO posts (judul, slug, kategori, konten, media, user_id, status) 
                            VALUES ('$judul', '$slug', '$kategori', '$konten', '$media', '$user_id', '$status')";
            if (mysqli_query($koneksi, $queryInsert)) {
                $success_msg = "Postingan berhasil ditambahkan!";
            }
        }
    }
// 2. EDIT POSTINGAN
    if (isset($_POST['update_post'])) {
        $id_post  = (int)$_POST['post_id'];
        $judul    = mysqli_real_escape_string($koneksi, trim($_POST['judul'] ?? ''));
        $kategori = mysqli_real_escape_string($koneksi, trim($_POST['kategori'] ?? 'Berita'));
        $konten   = mysqli_real_escape_string($koneksi, trim($_POST['konten'] ?? ''));
        $status   = mysqli_real_escape_string($koneksi, trim($_POST['status'] ?? 'Diterbitkan'));
        
        $mediaQuery = "";
        if (!empty($_FILES['media']['name'])) {
            $uploadResult = uploadMedia($_FILES['media']);
            if ($uploadResult) {
                $mediaQuery = ", media = '$uploadResult'";
            }
        }

        $queryUpdate = "UPDATE posts SET judul = '$judul', kategori = '$kategori', konten = '$konten', status = '$status' $mediaQuery WHERE id = $id_post";
        if (mysqli_query($koneksi, $queryUpdate)) {
            $success_msg = "Postingan berhasil diperbarui!";
        }
    }
// 3. HAPUS POSTINGAN
    if (isset($_POST['delete_post'])) {
        $id_post = (int)$_POST['post_id'];
        
        // Hapus file media dari server jika ada
        $resMedia = mysqli_query($koneksi, "SELECT media FROM posts WHERE id = $id_post");
        if ($rowMedia = mysqli_fetch_assoc($resMedia)) {
            if (!empty($rowMedia['media']) && file_exists(__DIR__ . '/../uploads/' . $rowMedia['media'])) {
                unlink(__DIR__ . '/../uploads/' . $rowMedia['media']);
            }
        }

        if (mysqli_query($koneksi, "DELETE FROM posts WHERE id = $id_post")) {
            $success_msg = "Postingan berhasil dihapus!";
        }
    }
}

// Ambil Data Postingan dari Database
$queryFetch  = "SELECT posts.*, users.nama_lengkap AS penulis FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.id DESC";
$resultPosts = mysqli_query($koneksi, $queryFetch);
?>

<!-- Import CSS Trumbowyg (Editor ala Word) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.0/ui/trumbowyg.min.css">

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1>Kelola Postingan</h1>
        <p>Buat, edit, dan kelola berita atau pengumuman sekolah.</p>
    </div>
    <button type="button" onclick="openAddForm()" style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary); color: #fff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 500; cursor: pointer;">
        <i data-lucide="plus-circle" style="width: 18px; height: 18px;"></i>
        <span>Buat Postingan Baru</span>
    </button>
</div>

<!-- Alert Notifikasi -->
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

<!-- FORM PANEL (TAMBAH / EDIT) -->
<div id="formPostPanel" class="welcome-card" style="display: none; margin-bottom: 28px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 id="formTitle">Tambah Postingan Baru</h3>
        <button type="button" onclick="closeForm()" style="background: transparent; border: none; color: var(--text-muted); cursor: pointer;">
            <i data-lucide="x" style="width: 20px; height: 20px;"></i>
        </button>
    </div>

    <form action="index.php?page=posts" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">
        <input type="hidden" name="post_id" id="postId">

        <div>
            <label style="display: block; font-weight: 500; margin-bottom: 6px;">Judul Postingan</label>
            <input type="text" name="judul" id="postJudul" required placeholder="Masukkan judul..." style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div>
                <label style="display: block; font-weight: 500; margin-bottom: 6px;">Kategori</label>
                <select name="kategori" id="postKategori" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: #fff;">
                    <option value="Berita">Berita</option>
                    <option value="Pengumuman">Pengumuman</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-weight: 500; margin-bottom: 6px;">Status Publikasi</label>
                <select name="status" id="postStatus" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: #fff;">
                    <option value="Diterbitkan">Diterbitkan</option>
                    <option value="Draf">Draf</option>
                </select>
            </div>
        </div>

        <div>
            <label style="display: block; font-weight: 500; margin-bottom: 6px;">Upload Gambar / Video (Opsional)</label>
            <input type="file" name="media" accept="image/*,video/*" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 8px; background: #fff;">
            <small style="color: var(--text-muted); font-size: 0.75rem;">Mendukung format gambar (JPG, PNG) & video (MP4).</small>
        </div>

        <div>
            <label style="display: block; font-weight: 500; margin-bottom: 6px;">Isi Konten</label>
            <!-- Textarea Editor ala Word -->
            <textarea name="konten" id="editor" required placeholder="Tuliskan isi postingan..."></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" onclick="closeForm()" style="padding: 10px 18px; border: 1px solid var(--border-color); background: #fff; border-radius: 8px; cursor: pointer;">Batal</button>
            <button type="submit" name="submit_post" id="btnSubmit" style="padding: 10px 18px; border: none; background: var(--primary); color: #fff; border-radius: 8px; cursor: pointer;">Simpan & Publikasikan</button>
        </div>
    </form>
</div>

<!-- TABEL DAFTAR POSTINGAN -->
<div class="welcome-card" style="padding: 0; overflow: hidden;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-color);">
        <h3 style="font-size: 1rem; font-weight: 600;">Daftar Berita & Pengumuman</h3>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid var(--border-color); color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">
                    <th style="padding: 14px 24px;">Judul & Media</th>
                    <th style="padding: 14px 20px;">Kategori</th>
                    <th style="padding: 14px 20px;">Penulis</th>
                    <th style="padding: 14px 20px;">Tanggal</th>
                    <th style="padding: 14px 20px;">Status</th>
                    <th style="padding: 14px 20px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultPosts && mysqli_num_rows($resultPosts) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($resultPosts)): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 16px 24px; font-weight: 600; color: var(--text-main); max-width: 300px;">
                                <?= htmlspecialchars($row['judul']); ?>
                                <?php if (!empty($row['media'])): ?>
                                    <div style="margin-top: 6px; font-size: 0.75rem; color: #2563eb; display: flex; align-items: center; gap: 4px;">
                                        <i data-lucide="paperclip" style="width: 14px; height: 14px;"></i>
                                        <a href="uploads/<?= $row['media']; ?>" target="_blank" style="color: inherit; text-decoration: underline;">Lihat Lampiran Media</a>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 16px 20px;">
                                <span style="background: <?= $row['kategori'] === 'Pengumuman' ? '#eff6ff' : '#f0fdf4'; ?>; color: <?= $row['kategori'] === 'Pengumuman' ? '#2563eb' : '#16a34a'; ?>; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
                                    <?= htmlspecialchars($row['kategori']); ?>
                                </span>
                            </td>
                            <td style="padding: 16px 20px; color: var(--text-muted);"><?= htmlspecialchars($row['penulis']); ?></td>
                            <td style="padding: 16px 20px; color: var(--text-muted);"><?= date('d M Y', strtotime($row['created_at'])); ?></td>
                            <td style="padding: 16px 20px;">
                                <span style="color: <?= $row['status'] === 'Diterbitkan' ? '#16a34a' : '#ea580c'; ?>; font-weight: 500;">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <!-- Tombol Aksi Edit & Hapus -->
                            <td style="padding: 16px 20px; text-align: center;">
                                <div style="display: flex; justify-content: center; gap: 8px;">
                                    <button type="button" onclick='openEditForm(<?= json_encode($row); ?>)' style="background: #eff6ff; color: #2563eb; border: none; padding: 6px 10px; border-radius: 6px; cursor: pointer;" title="Edit">
                                        <i data-lucide="edit-3" style="width: 16px; height: 16px;"></i>
                                    </button>
                                    
                                    <form action="index.php?page=posts" method="POST" onsubmit="return confirm('Yakin ingin menghapus postingan ini?');" style="display: inline;">
                                        <input type="hidden" name="post_id" value="<?= $row['id']; ?>">
                                        <button type="submit" name="delete_post" style="background: #fef2f2; color: #ef4444; border: none; padding: 6px 10px; border-radius: 6px; cursor: pointer;" title="Hapus">
                                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 32px; text-align: center; color: var(--text-muted);">Belum ada postingan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JS JQuery & Trumbowyg Editor -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.0/trumbowyg.min.css"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.0/trumbowyg.min.js"></script>

<script>
    // Inisialisasi Rich Text Editor ALA WORD
    $(document).ready(function() {
        $('#editor').trumbowyg({
            btns: [
                ['viewHTML'],
                ['formatting'],
                ['strong', 'em', 'del'],
                ['link'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['removeformat']
            ]
        });
    });

    function openAddForm() {
        document.getElementById('formTitle').innerText = 'Tambah Postingan Baru';
        document.getElementById('postId').value = '';
        document.getElementById('postJudul').value = '';
        document.getElementById('postKategori').value = 'Berita';
        document.getElementById('postStatus').value = 'Diterbitkan';
        $('#editor').trumbowyg('html', '');
        
        document.getElementById('btnSubmit').name = 'submit_post';
        document.getElementById('btnSubmit').innerText = 'Simpan & Publikasikan';
        document.getElementById('formPostPanel').style.display = 'block';
    }

    function openEditForm(data) {
        document.getElementById('formTitle').innerText = 'Edit Postingan';
        document.getElementById('postId').value = data.id;
        document.getElementById('postJudul').value = data.judul;
        document.getElementById('postKategori').value = data.kategori;
        document.getElementById('postStatus').value = data.status;
        $('#editor').trumbowyg('html', data.konten);

        document.getElementById('btnSubmit').name = 'update_post';
        document.getElementById('btnSubmit').innerText = 'Perbarui Postingan';
        document.getElementById('formPostPanel').style.display = 'block';
    }

    function closeForm() {
        document.getElementById('formPostPanel').style.display = 'none';
    }
</script>