<?php
// Panggil koneksi agar text editor (VS Code) mengenali tipe data $koneksi
require_once __DIR__ . '/../config/koneksi.php';

$success_msg = '';

// Proses Simpan Postingan Baru ke Database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_post'])) {
    $judul    = mysqli_real_escape_string($koneksi, trim($_POST['judul'] ?? ''));
    $kategori = mysqli_real_escape_string($koneksi, trim($_POST['kategori'] ?? 'Berita'));
    $konten   = mysqli_real_escape_string($koneksi, trim($_POST['konten'] ?? ''));
    $status   = mysqli_real_escape_string($koneksi, trim($_POST['status'] ?? 'Diterbitkan'));
    
    // Buat URL slug ramah SEO dari judul
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
    
    // ID User admin dari session (default: 1 jika belum set ID)
    $user_id = $_SESSION['admin_id'] ?? 1;

    if (!empty($judul) && !empty($konten)) {
        $queryInsert = "INSERT INTO posts (judul, slug, kategori, konten, user_id, status) 
                        VALUES ('$judul', '$slug', '$kategori', '$konten', '$user_id', '$status')";
        
        if (mysqli_query($koneksi, $queryInsert)) {
            $success_msg = "Postingan berhasil ditambahkan ke database!";
        }
    }
}

// Ambil Data Postingan dari Database
$queryFetch = "SELECT posts.*, users.nama_lengkap AS penulis 
               FROM posts 
               JOIN users ON posts.user_id = users.id 
               ORDER BY posts.id DESC";
$resultPosts = mysqli_query($koneksi, $queryFetch);
?>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1>Kelola Postingan</h1>
        <p>Buat dan publikasikan berita atau pengumuman sekolah.</p>
    </div>
    <button type="button" onclick="toggleFormPost()" style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary); color: #fff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 500; cursor: pointer;">
        <i data-lucide="plus-circle" style="width: 18px; height: 18px;"></i>
        <span>Buat Postingan Baru</span>
    </button>
</div>

<?php if (!empty($success_msg)): ?>
    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
        <span><?= htmlspecialchars($success_msg); ?></span>
    </div>
<?php endif; ?>

<!-- Form Input Postingan Baru -->
<div id="formPostPanel" class="welcome-card" style="display: none; margin-bottom: 28px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="font-size: 1.1rem; font-weight: 600;">Tambah Postingan Baru</h3>
        <button type="button" onclick="toggleFormPost()" style="background: transparent; border: none; color: var(--text-muted); cursor: pointer;">
            <i data-lucide="x" style="width: 20px; height: 20px;"></i>
        </button>
    </div>

    <form action="index.php?page=posts" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
        <div>
            <label style="display: block; font-weight: 500; margin-bottom: 6px; color: var(--text-main);">Judul Postingan</label>
            <input type="text" name="judul" required placeholder="Masukkan judul berita atau pengumuman..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.9rem; outline: none;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div>
                <label style="display: block; font-weight: 500; margin-bottom: 6px; color: var(--text-main);">Kategori</label>
                <select name="kategori" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.9rem; background: #fff; outline: none;">
                    <option value="Berita">Berita</option>
                    <option value="Pengumuman">Pengumuman</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-weight: 500; margin-bottom: 6px; color: var(--text-main);">Status Publikasi</label>
                <select name="status" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.9rem; background: #fff; outline: none;">
                    <option value="Diterbitkan">Diterbitkan</option>
                    <option value="Draf">Draf</option>
                </select>
            </div>
        </div>

        <div>
            <label style="display: block; font-weight: 500; margin-bottom: 6px; color: var(--text-main);">Isi Konten</label>
            <textarea name="konten" rows="5" required placeholder="Tuliskan isi berita atau pengumuman secara lengkap..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.9rem; outline: none; resize: vertical;"></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" onclick="toggleFormPost()" style="padding: 10px 18px; border: 1px solid var(--border-color); background: #fff; border-radius: 8px; font-weight: 500; cursor: pointer; color: var(--text-muted);">Batal</button>
            <button type="submit" name="submit_post" style="padding: 10px 18px; border: none; background: var(--primary); color: #fff; border-radius: 8px; font-weight: 500; cursor: pointer;">Simpan & Publikasikan</button>
        </div>
    </form>
</div>

<!-- Tabel Menampilkan Data Dari MySQL -->
<div class="welcome-card" style="padding: 0; overflow: hidden;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 1rem; font-weight: 600;">Daftar Berita & Pengumuman</h3>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid var(--border-color); color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">
                    <th style="padding: 14px 24px;">Judul</th>
                    <th style="padding: 14px 20px;">Kategori</th>
                    <th style="padding: 14px 20px;">Penulis</th>
                    <th style="padding: 14px 20px;">Tanggal</th>
                    <th style="padding: 14px 20px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultPosts && mysqli_num_rows($resultPosts) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($resultPosts)): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 16px 24px; font-weight: 600; color: var(--text-main); max-width: 320px;">
                                <?= htmlspecialchars($row['judul']); ?>
                                <p style="font-weight: 400; color: var(--text-muted); font-size: 0.8rem; margin-top: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <?= htmlspecialchars($row['konten']); ?>
                                </p>
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
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding: 32px; text-align: center; color: var(--text-muted);">Belum ada postingan di database.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleFormPost() {
        const panel = document.getElementById('formPostPanel');
        panel.style.display = (panel.style.display === 'none' || panel.style.display === '') ? 'block' : 'none';
    }
</script>