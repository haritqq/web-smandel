<?php
require_once __DIR__ . '/../config/koneksi.php';

$success_msg = '';
$error_msg   = '';

// --- FUNGSI UPLOAD MEDIA ---
function uploadMultipleMedia($files, $postId, $koneksi) {
    $targetDir = __DIR__ . '/../uploads/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $count = count($files['name']);
    for ($i = 0; $i < $count; $i++) {
        if (!empty($files['name'][$i]) && $files['error'][$i] === UPLOAD_ERR_OK) {
            $fileName = time() . '_' . sprintf('%03d', $i) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($files['name'][$i]));
            $targetFilePath = $targetDir . $fileName;
            $ext = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            $jenis = 'gambar';
            if (in_array($ext, ['mp4', 'webm', 'ogg', 'mkv'])) $jenis = 'video';
            if (in_array($ext, ['mp3', 'wav', 'ogg', 'aac'])) $jenis = 'suara';

            if (move_uploaded_file($files['tmp_name'][$i], $targetFilePath)) {
                $filePathEscaped = mysqli_real_escape_string($koneksi, $fileName);
                mysqli_query($koneksi, "INSERT INTO post_media (post_id, tipe, jenis, url_atau_file) VALUES ($postId, 'file', '$jenis', '$filePathEscaped')");
            }
        }
    }
}

// --- FUNGSI SIMPAN LINK MEDIA ---
function saveMediaLinks($links, $types, $postId, $koneksi) {
    if (is_array($links)) {
        foreach ($links as $index => $link) {
            $linkTrim = trim($link);
            if (!empty($linkTrim)) {
                $jenis = mysqli_real_escape_string($koneksi, $types[$index] ?? 'gambar');
                $linkEscaped = mysqli_real_escape_string($koneksi, $linkTrim);
                mysqli_query($koneksi, "INSERT INTO post_media (post_id, tipe, jenis, url_atau_file) VALUES ($postId, 'link', '$jenis', '$linkEscaped')");
            }
        }
    }
}

// --- PROSES ACTION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. TAMBAH POSTINGAN
    if (isset($_POST['submit_post'])) {
        $judul    = mysqli_real_escape_string($koneksi, trim($_POST['judul'] ?? ''));
        $kategori = mysqli_real_escape_string($koneksi, trim($_POST['kategori'] ?? 'Berita'));
        $konten   = mysqli_real_escape_string($koneksi, trim($_POST['konten'] ?? ''));
        $status   = mysqli_real_escape_string($koneksi, trim($_POST['status'] ?? 'Diterbitkan'));
        $user_id  = $_SESSION['admin_id'] ?? 1;

        $base_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
        $slug      = $base_slug;
        $checkSlug = mysqli_query($koneksi, "SELECT id FROM posts WHERE slug = '$slug'");
        if (mysqli_num_rows($checkSlug) > 0) {
            $slug = $base_slug . '-' . time();
        }

        if (!empty($judul) && !empty($konten)) {
            $queryInsert = "INSERT INTO posts (judul, slug, kategori, konten, user_id, status) 
                            VALUES ('$judul', '$slug', '$kategori', '$konten', '$user_id', '$status')";
            if (mysqli_query($koneksi, $queryInsert)) {
                $newPostId = mysqli_insert_id($koneksi);

                // Upload File Lokal
                if (!empty($_FILES['media_files']['name'][0])) {
                    uploadMultipleMedia($_FILES['media_files'], $newPostId, $koneksi);
                }
                // Simpan Link Ekstrenal
                if (!empty($_POST['media_links'])) {
                    saveMediaLinks($_POST['media_links'], $_POST['media_link_types'] ?? [], $newPostId, $koneksi);
                }

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

        $queryUpdate = "UPDATE posts SET judul = '$judul', kategori = '$kategori', konten = '$konten', status = '$status' WHERE id = $id_post";
        if (mysqli_query($koneksi, $queryUpdate)) {
            // Tambah File Baru (jika ada)
            if (!empty($_FILES['media_files']['name'][0])) {
                uploadMultipleMedia($_FILES['media_files'], $id_post, $koneksi);
            }
            // Tambah Link Baru (jika ada)
            if (!empty($_POST['media_links'])) {
                saveMediaLinks($_POST['media_links'], $_POST['media_link_types'] ?? [], $id_post, $koneksi);
            }
            $success_msg = "Postingan berhasil diperbarui!";
        }
    }

    // 3. HAPUS MEDIA SPESIFIK
    if (isset($_POST['delete_media_item'])) {
        $mediaId = (int)$_POST['media_id'];
        $resMedia = mysqli_query($koneksi, "SELECT * FROM post_media WHERE id = $mediaId");
        if ($rowM = mysqli_fetch_assoc($resMedia)) {
            if ($rowM['tipe'] === 'file' && file_exists(__DIR__ . '/../uploads/' . $rowM['url_atau_file'])) {
                unlink(__DIR__ . '/../uploads/' . $rowM['url_atau_file']);
            }
            mysqli_query($koneksi, "DELETE FROM post_media WHERE id = $mediaId");
            $success_msg = "Media berhasil dihapus!";
        }
    }

    // 4. HAPUS POSTINGAN UTAMA
    if (isset($_POST['delete_post'])) {
        $id_post = (int)$_POST['post_id'];
        
        $resMedia = mysqli_query($koneksi, "SELECT * FROM post_media WHERE post_id = $id_post");
        while ($rowM = mysqli_fetch_assoc($resMedia)) {
            if ($rowM['tipe'] === 'file' && file_exists(__DIR__ . '/../uploads/' . $rowM['url_atau_file'])) {
                unlink(__DIR__ . '/../uploads/' . $rowM['url_atau_file']);
            }
        }
        if (mysqli_query($koneksi, "DELETE FROM posts WHERE id = $id_post")) {
            $success_msg = "Postingan berhasil dihapus!";
        }
    }
}

// Ambil Data Postingan
$queryFetch  = "SELECT posts.*, users.nama_lengkap AS penulis FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.id DESC";
$resultPosts = mysqli_query($koneksi, $queryFetch);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.0/ui/trumbowyg.min.css">

<style>
    .media-modal { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 999; align-items: center; justify-content: center; padding: 20px; }
    .media-modal-content { background: #fff; border-radius: 12px; max-width: 800px; width: 100%; max-height: 90vh; overflow: hidden; display: flex; flex-direction: column; }
    .media-modal-header { padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
    .media-modal-body { padding: 20px; display: flex; flex-direction: column; gap: 16px; align-items: center; justify-content: center; background: #0f172a; max-height: 70vh; overflow-y: auto; color: #fff; }
    .media-modal-body img, .media-modal-body video { max-width: 100%; max-height: 50vh; border-radius: 6px; }
    .media-modal-body audio { width: 100%; }
    .link-input-group { display: flex; gap: 8px; margin-bottom: 8px; }
</style>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1>Kelola Postingan</h1>
        <p>Kelola berita & pengumuman dengan lampiran multi-media / link.</p>
    </div>
    <button type="button" onclick="openAddForm()" style="display: inline-flex; align-items: center; gap: 8px; background: var(--primary); color: #fff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 500; cursor: pointer;">
        <i data-lucide="plus-circle" style="width: 18px; height: 18px;"></i>
        <span>Buat Postingan Baru</span>
    </button>
</div>

<?php if (!empty($success_msg)): ?>
    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;"><?= htmlspecialchars($success_msg); ?></div>
<?php endif; ?>

<!-- FORM PANEL -->
<div id="formPostPanel" class="welcome-card" style="display: none; margin-bottom: 28px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 id="formTitle">Tambah Postingan Baru</h3>
        <button type="button" onclick="closeForm()" style="background: transparent; border: none; color: var(--text-muted); cursor: pointer;"><i data-lucide="x"></i></button>
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

        <!-- UPLOAD LOKAL LEBIH DARI SATU -->
        <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid var(--border-color);">
            <label style="display: block; font-weight: 600; margin-bottom: 6px;">1. Upload File (Gambar/Video/Suara - Bisa Pilih Banyak)</label>
            <input type="file" name="media_files[]" multiple accept="image/*,video/*,audio/*" style="width: 100%; padding: 8px; background: #fff; border: 1px solid var(--border-color); border-radius: 6px;">
            <small style="color: var(--text-muted); display: block; margin-top: 4px;">Tahan tombol Ctrl (atau Shift) saat memilih file untuk mengunggah lebih dari satu.</small>
        </div>

        <!-- LINK EKSTERNAL (GOOGLE DRIVE / PHOTOS / CDN) -->
        <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label style="font-weight: 600;">2. Tambah Dari Link/URL (Hemat Penyimpanan Hosting)</label>
                <button type="button" onclick="addLinkInput()" style="background: var(--primary); color: #fff; border: none; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; cursor: pointer;">+ Tambah Link</button>
            </div>
            <div id="linkContainer"></div>
            <small style="color: var(--text-muted);">Masukkan link dari Google Drive, Google Photos, YouTube, atau Cloud Storage lain.</small>
        </div>

        <!-- LIST MEDIA YANG SUDAH TER-UPLOAD (UNTUK EDIT FORM) -->
        <div id="existingMediaContainer" style="display: none; background: #fff; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color);">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Media Terpasang Saat Ini:</label>
            <div id="existingMediaList" style="display: flex; flex-direction: column; gap: 6px;"></div>
        </div>

        <div>
            <label style="display: block; font-weight: 500; margin-bottom: 6px;">Isi Konten</label>
            <textarea name="konten" id="editor" required></textarea>
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
                        <?php 
                            // Fetch Media Terkait Post
                            $pId = $row['id'];
                            $getMedia = mysqli_query($koneksi, "SELECT * FROM post_media WHERE post_id = $pId");
                            $mediaList = [];
                            while ($m = mysqli_fetch_assoc($getMedia)) {
                                $mediaList[] = $m;
                            }
                        ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 16px 24px; font-weight: 600; color: var(--text-main); max-width: 300px;">
                                <?= htmlspecialchars($row['judul']); ?>
                                <?php if (!empty($mediaList)): ?>
                                    <div style="margin-top: 6px; font-size: 0.75rem;">
                                        <button type="button" onclick='showMediaPopup(<?= json_encode($mediaList); ?>, "<?= htmlspecialchars(addslashes($row['judul'])); ?>")' style="background: transparent; border: none; color: #2563eb; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; padding: 0; font-size: 0.75rem; text-decoration: underline;">
                                            <i data-lucide="paperclip" style="width: 14px; height: 14px;"></i>
                                            <span>Lihat Lampiran Media (<?= count($mediaList); ?>)</span>
                                        </button>
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
                            <td style="padding: 16px 20px; text-align: center;">
                                <div style="display: flex; justify-content: center; gap: 8px;">
                                    <button type="button" onclick='openEditForm(<?= json_encode($row); ?>, <?= json_encode($mediaList); ?>)' style="background: #eff6ff; color: #2563eb; border: none; padding: 6px 10px; border-radius: 6px; cursor: pointer;" title="Edit">
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

<!-- POPUP MODAL MULTI-MEDIA -->
<div id="mediaModal" class="media-modal" onclick="closeMediaPopup(event)">
    <div class="media-modal-content" onclick="event.stopPropagation()">
        <div class="media-modal-header">
            <h3 id="mediaModalTitle" style="font-size: 1rem; font-weight: 600;">Lampiran Media</h3>
            <button type="button" onclick="closeMediaPopup()" style="background: transparent; border: none; cursor: pointer; color: var(--text-muted);">
                <i data-lucide="x"></i>
            </button>
        </div>
        <div class="media-modal-body" id="mediaModalBody"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.0/trumbowyg.min.js"></script>

<script>
    $(document).ready(function() {
        $('#editor').trumbowyg({
            btns: [
                ['viewHTML'], ['formatting'], ['strong', 'em', 'del'], ['link'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'], ['horizontalRule'], ['removeformat']
            ]
        });
    });

    // Tambah Dinamis Field Link
    function addLinkInput(value = '', type = 'gambar') {
        const container = document.getElementById('linkContainer');
        const div = document.createElement('div');
        div.className = 'link-input-group';
        div.innerHTML = `
            <select name="media_link_types[]" style="padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; background: #fff;">
                <option value="gambar" ${type === 'gambar' ? 'selected' : ''}>Gambar</option>
                <option value="video" ${type === 'video' ? 'selected' : ''}>Video / Youtube</option>
                <option value="suara" ${type === 'suara' ? 'selected' : ''}>Suara / Audio</option>
            </select>
            <input type="url" name="media_links[]" value="${value}" placeholder="https://..." style="flex: 1; padding: 8px; border: 1px solid var(--border-color); border-radius: 6px;">
            <button type="button" onclick="this.parentElement.remove()" style="background: #fef2f2; color: #ef4444; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer;">Hapus</button>
        `;
        container.appendChild(div);
    }

    function openAddForm() {
        document.getElementById('formTitle').innerText = 'Tambah Postingan Baru';
        document.getElementById('postId').value = '';
        document.getElementById('postJudul').value = '';
        document.getElementById('postKategori').value = 'Berita';
        document.getElementById('postStatus').value = 'Diterbitkan';
        document.getElementById('linkContainer').innerHTML = '';
        document.getElementById('existingMediaContainer').style.display = 'none';
        $('#editor').trumbowyg('html', '');
        
        document.getElementById('btnSubmit').name = 'submit_post';
        document.getElementById('btnSubmit').innerText = 'Simpan & Publikasikan';
        document.getElementById('formPostPanel').style.display = 'block';
    }

    function openEditForm(data, mediaList) {
        document.getElementById('formTitle').innerText = 'Edit Postingan';
        document.getElementById('postId').value = data.id;
        document.getElementById('postJudul').value = data.judul;
        document.getElementById('postKategori').value = data.kategori;
        document.getElementById('postStatus').value = data.status;
        document.getElementById('linkContainer').innerHTML = '';
        $('#editor').trumbowyg('html', data.konten);

        // Render Media Terpasang
        const existContainer = document.getElementById('existingMediaContainer');
        const existList = document.getElementById('existingMediaList');
        existList.innerHTML = '';
        
        if (mediaList && mediaList.length > 0) {
            existContainer.style.display = 'block';
            mediaList.forEach(m => {
                const item = document.createElement('div');
                item.style.cssText = 'display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 6px 10px; border-radius: 6px; font-size: 0.8rem;';
                item.innerHTML = `
                    <span>[${m.tipe.toUpperCase()} - ${m.jenis}] ${m.url_atau_file}</span>
                    <form action="index.php?page=posts" method="POST" style="display:inline;">
                        <input type="hidden" name="media_id" value="${m.id}">
                        <button type="submit" name="delete_media_item" style="color: #ef4444; background: none; border: none; cursor: pointer; text-decoration: underline;">Hapus Media Ini</button>
                    </form>
                `;
                existList.appendChild(item);
            });
        } else {
            existContainer.style.display = 'none';
        }

        document.getElementById('btnSubmit').name = 'update_post';
        document.getElementById('btnSubmit').innerText = 'Perbarui Postingan';
        document.getElementById('formPostPanel').style.display = 'block';
    }

    function closeForm() {
        document.getElementById('formPostPanel').style.display = 'none';
    }

    // Modal Display Handler
    function showMediaPopup(mediaList, title) {
        const modal = document.getElementById('mediaModal');
        const modalTitle = document.getElementById('mediaModalTitle');
        const modalBody = document.getElementById('mediaModalBody');
        
        modalTitle.innerText = title;
        modalBody.innerHTML = '';

        mediaList.forEach(m => {
            const src = m.tipe === 'file' ? 'uploads/' + m.url_atau_file : m.url_atau_file;
            const container = document.createElement('div');
            container.style.cssText = 'width: 100%; text-align: center; margin-bottom: 15px;';

            if (m.jenis === 'gambar') {
                container.innerHTML = `<img src="${src}" alt="Gambar"><br><small>${m.url_atau_file}</small>`;
            } else if (m.jenis === 'video') {
                container.innerHTML = `<video src="${src}" controls></video><br><small>${m.url_atau_file}</small>`;
            } else if (m.jenis === 'suara') {
                container.innerHTML = `<audio src="${src}" controls></audio><br><small>${m.url_atau_file}</small>`;
            }
            modalBody.appendChild(container);
        });

        modal.style.display = 'flex';
    }

    function closeMediaPopup() {
        const modal = document.getElementById('mediaModal');
        document.getElementById('mediaModalBody').innerHTML = '';
        modal.style.display = 'none';
    }
</script>