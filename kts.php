<?php 
    $page_title = "Kartu Tanda Siswa Digital";
    $current_page = "kts"; 

    require_once __DIR__ . '/admin/config/koneksi.php';

    $upload_msg = "";
    $upload_status = "";

    // 2. PROSES UPLOAD FOTO SISWA (JIKA FORM UPLOAD DIKIRIM)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_foto'])) {
        $siswa_id   = intval($_POST['siswa_id']);
        $nisn_up    = $_POST['nisn_redirect'] ?? '';
        $tgl_up     = $_POST['tgl_redirect'] ?? '';
        $ta_up      = $_POST['ta_redirect'] ?? '';

        if (isset($_FILES['foto_siswa']) && $_FILES['foto_siswa']['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES['foto_siswa']['tmp_name'];
            $file_name = $_FILES['foto_siswa']['name'];
            $file_size = $_FILES['foto_siswa']['size'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed_ext = ['jpg', 'jpeg', 'png'];

            if (!in_array($file_ext, $allowed_ext)) {
                $upload_status = "error";
                $upload_msg = "Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.";
            } elseif ($file_size > 2 * 1024 * 1024) { // Maksimal 2MB
                $upload_status = "error";
                $upload_msg = "Ukuran file maksimal adalah 2 MB.";
            } else {
                // Pastikan folder tujuan tersedia (gunakan __DIR__ agar path upload juga selalu akurat)
                $target_dir = __DIR__ . "/uploads/kts/pas_foto/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                // Penamaan file berdasarkan NISN agar unik & rapi
                $new_filename = "pas_foto/" . preg_replace('/[^0-9]/', '', $nisn_up) . "_" . time() . "." . $file_ext;
                $destination  = __DIR__ . "/uploads/kts/" . $new_filename;

                if (move_uploaded_file($file_tmp, $destination)) {
                    // Update path foto ke database (Gaya Prosedural dengan $koneksi)
                    $stmt_up = mysqli_prepare($koneksi, "UPDATE kartu_siswa SET foto_kts = ? WHERE id = ?");
                    mysqli_stmt_bind_param($stmt_up, "si", $new_filename, $siswa_id);
                    if (mysqli_stmt_execute($stmt_up)) {
                        $upload_status = "success";
                        $upload_msg = "Foto berhasil diunggah dan kartu telah diperbarui!";
                    } else {
                        $upload_status = "error";
                        $upload_msg = "Gagal menyimpan data foto ke database.";
                    }
                    mysqli_stmt_close($stmt_up);
                } else {
                    $upload_status = "error";
                    $upload_msg = "Gagal memindahkan file ke folder server.";
                }
            }
        } else {
            $upload_status = "error";
            $upload_msg = "Silakan pilih file foto terlebih dahulu.";
        }

        // Pertahankan parameter pencarian agar data siswa tetap tampil setelah upload
        $_GET['nisn']            = $nisn_up;
        $_GET['tgl_lahir']       = $tgl_up;
        $_GET['tahun_ajaran_id'] = $ta_up;
    }

    // 3. DAFTAR TAHUN AJARAN UNTUK DROPDOWN (Diperbaiki menjadi $koneksi Prosedural)
    $query_ta = "SELECT * FROM tahun_ajaran ORDER BY tahun DESC, semester DESC";
    $result_ta = mysqli_query($koneksi, $query_ta);

    // 4. PROSES PENCARIAN SISWA (Diperbaiki menjadi $koneksi Prosedural)
    $siswa = null;
    $is_sample = true; // Penanda apakah menampilkan kartu contoh atau data asli
    $error_msg = "";
    
    $search_nisn = trim($_GET['nisn'] ?? '');
    $search_tgl  = trim($_GET['tgl_lahir'] ?? '');
    $search_ta   = trim($_GET['tahun_ajaran_id'] ?? '');

    if (!empty($search_nisn) && !empty($search_tgl)) {
        $sql_cari = "
            SELECT 
                ks.*, 
                sta.kelas, 
                sta.status_siswa,
                ta.tahun, 
                ta.semester
            FROM kartu_siswa ks
            LEFT JOIN siswa_tahun_ajaran sta ON ks.id = sta.kartu_siswa_id
            LEFT JOIN tahun_ajaran ta ON sta.tahun_ajaran_id = ta.id
            WHERE ks.nisn = ? AND ks.tanggal_lahir = ?
            " . (!empty($search_ta) ? "AND ta.id = ?" : "") . "
            LIMIT 1
        ";

        $stmt = mysqli_prepare($koneksi, $sql_cari);

        if (!empty($search_ta)) {
            mysqli_stmt_bind_param($stmt, "ssi", $search_nisn, $search_tgl, $search_ta);
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $search_nisn, $search_tgl);
        }

        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res && mysqli_num_rows($res) > 0) {
            $siswa = mysqli_fetch_assoc($res);
            $is_sample = false;
        } else {
            $error_msg = "Data siswa dengan NISN, Tanggal Lahir, dan Tahun Ajaran tersebut tidak ditemukan. Menampilkan kartu contoh.";
        }
        mysqli_stmt_close($stmt);
    }

    // 5. DATA CONTOH DEFAULT (TAMPIL OTOMATIS JIKA BELUM ADA PENCARIAN / DATA TIDAK DITEMUKAN)
    if ($is_sample) {
        $siswa = [
            'id'                  => 0,
            'nisn'                => '0012345678',
            'nis'                 => '2026001',
            'nama_lengkap'        => 'AHMAD RIZKY PRATAMA (CONTOH)',
            'tempat_lahir'        => 'Banda Aceh',
            'tanggal_lahir'       => '2008-05-14',
            'alamat'              => 'Jl. T. Nyak Arief No. 12',
            'kelas'               => 'XI IPA 1',
            'status_siswa'        => 'Aktif',
            'tahun'               => '2026/2027',
            'foto_kts'            => '',
            'username_belajar_id' => 'contoh.siswa@sma.belajar.id',
            'password_belajar_id' => '••••••••',
            'username_erapor'     => '0012345678',
            'password_erapor'     => '••••••••'
        ];
    }

    include 'includes/header.php'; 
?>

<!-- STYLESHEET KESISWAAN -->
<link rel="stylesheet" href="assets/css/kesiswaan.css">

<!-- CDN JS BARCODE & PDF GENERATOR -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<!-- STYLESHEET TEMA CERAH & FLIP CARD 3D -->
<style>
.kts-theme-bright {
    background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
    border-radius: 24px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.04);
}

.search-card-box {
    background: #ffffff;
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 10px 25px rgba(37, 99, 235, 0.08);
    border: 1px solid #e0f2fe;
    max-width: 850px;
    margin: 0 auto 30px auto;
}

.input-group label {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
    margin-bottom: 6px;
}

.custom-input {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    font-size: 14px;
    outline: none;
    transition: all 0.2s ease;
    background: #f8fafc;
}

.custom-input:focus {
    border-color: #0284c7;
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.15);
}

/* BADGE SAMPLE */
.sample-badge {
    display: inline-block;
    background: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 14px;
}

.real-badge {
    display: inline-block;
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 14px;
}

/* FLIP CARD 3D STYLES */
.kts-card-wrapper {
    perspective: 1000px;
    width: 100%;
    max-width: 420px;
    height: 260px;
    margin: 0 auto;
}

.kts-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: left;
    transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    transform-style: preserve-3d;
    cursor: pointer;
}

.kts-card-wrapper.flipped .kts-card-inner {
    transform: rotateY(180deg);
}

.kts-face {
    position: absolute;
    width: 100%;
    height: 100%;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(2, 132, 199, 0.18);
    overflow: hidden;
    border: 2px solid #ffffff;
}

/* FRONT FACE: LIGHT & VIBRANT */
.kts-front {
    background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
    color: #ffffff;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.kts-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255, 255, 255, 0.25);
    padding-bottom: 10px;
}

.kts-header h4 {
    margin: 0;
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.5px;
    color: #ffffff;
}

.kts-header span {
    font-size: 10px;
    color: #bae6fd;
    font-weight: 600;
}

.kts-body {
    display: flex;
    gap: 16px;
    align-items: center;
}

.kts-avatar {
    width: 85px;
    height: 105px;
    border-radius: 12px;
    object-fit: cover;
    border: 3px solid #ffffff;
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
    background: #e2e8f0;
    flex-shrink: 0;
}

.kts-info h3 {
    margin: 0 0 6px 0;
    font-size: 16px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.25;
}

.kts-info p {
    margin: 2px 0;
    font-size: 12px;
    color: #f0f9ff;
}

.kts-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}

.barcode-container svg {
    height: 32px;
    background: #ffffff;
    padding: 3px 8px;
    border-radius: 6px;
}

/* BACK FACE */
.kts-back {
    background: #ffffff;
    color: #1e293b;
    padding: 20px;
    transform: rotateY(180deg);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border: 2px solid #e0f2fe;
}

.kts-back-header {
    border-bottom: 2px solid #f1f5f9;
    padding-bottom: 8px;
    text-align: center;
}

.kts-back-header h5 {
    margin: 0;
    font-size: 12px;
    color: #0284c7;
    font-weight: 800;
    letter-spacing: 0.5px;
}

.kts-rules {
    font-size: 10px;
    color: #475569;
    padding-left: 14px;
    margin: 8px 0;
    line-height: 1.5;
}

.kts-signature {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}

/* UPLOAD PHOTO BOX */
.upload-photo-box {
    margin-top: 22px;
    background: #f0f9ff;
    border: 1.5px dashed #38bdf8;
    border-radius: 14px;
    padding: 16px;
    text-align: left;
}

.upload-photo-box h4 {
    margin: 0 0 6px 0;
    font-size: 14px;
    color: #0369a1;
    display: flex;
    align-items: center;
    gap: 6px;
}

.upload-photo-box p {
    margin: 0 0 12px 0;
    font-size: 12px;
    color: #475569;
}

/* INTEGRATION CARDS */
.account-card {
    background: #ffffff;
    border: 1px solid #e0f2fe;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 16px;
    box-shadow: 0 4px 15px rgba(2, 132, 199, 0.05);
    transition: all 0.2s ease;
}

.account-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(2, 132, 199, 0.1);
}

.account-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.code-box {
    background: #f0f9ff;
    padding: 6px 12px;
    border-radius: 8px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    font-weight: 700;
    color: #0369a1;
    border: 1px solid #bae6fd;
}
</style>

<!-- PAGE BANNER -->
<section class="page-banner">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            <a href="kesiswaan.php">Kesiswaan</a>
            <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            <span>Kartu Tanda Siswa</span>
        </div>
        <h1 class="page-title">Kartu Tanda Siswa Digital</h1>
    </div>
</section>

<!-- MAIN CONTENT SECTION -->
<section class="academic-section">
    <div class="container kts-theme-bright">
        
        <div class="section-header" style="text-align: center; max-width: 700px; margin: 0 auto 35px auto;">
            <span class="section-tag" style="background: #e0f2fe; color: #0284c7;">Layanan Digital Siswa</span>
            <h2 class="section-title">Pencarian Kartu Tanda Siswa</h2>
            <p>Masukkan NISN, Tanggal Lahir, dan Tahun Ajaran Anda untuk mengubah kartu contoh di bawah menjadi kartu resmi Anda.</p>
        </div>

        <!-- FORM PENCARIAN SISWA -->
        <div class="search-card-box">
            <form method="GET" action="kts.php">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px;">
                    <div class="input-group">
                        <label>NISN Siswa *</label>
                        <input type="text" name="nisn" value="<?= htmlspecialchars($search_nisn); ?>" placeholder="Contoh: 0012345678" required class="custom-input">
                    </div>
                    <div class="input-group">
                        <label>Tanggal Lahir *</label>
                        <input type="date" name="tgl_lahir" value="<?= htmlspecialchars($search_tgl); ?>" required class="custom-input">
                    </div>
                    <div class="input-group">
                        <label>Tahun Ajaran *</label>
                        <select name="tahun_ajaran_id" required class="custom-input">
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            <?php if ($result_ta && mysqli_num_rows($result_ta) > 0): ?>
                                <?php while($ta = mysqli_fetch_assoc($result_ta)): ?>
                                    <option value="<?= $ta['id']; ?>" <?= ($search_ta == $ta['id']) ? 'selected' : ''; ?>>
                                        <?= $ta['tahun']; ?> (<?= $ta['semester']; ?>) <?= $ta['is_aktif'] ? '★ Aktif' : ''; ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div style="margin-top: 24px; text-align: center;">
                    <button type="submit" class="btn-detail" style="border: none; cursor: pointer; padding: 12px 36px; font-size: 15px; border-radius: 10px; background: linear-gradient(135deg, #0284c7, #2563eb); color: white; box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);">
                        <i data-lucide="search" style="width: 18px; height: 18px; vertical-align: middle;"></i> Tampilkan Kartu
                    </button>
                    <?php if (!$is_sample): ?>
                        <a href="kts.php" style="display: inline-block; margin-left: 10px; padding: 12px 20px; font-size: 14px; color: #64748b; text-decoration: none; font-weight: 600;">Reset</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- PESAN NOTIFIKASI PENCARIAN / UPLOAD -->
        <?php if (!empty($error_msg)): ?>
            <div style="max-width: 850px; margin: 0 auto 30px auto; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 12px; text-align: center; font-weight: 600; font-size: 14px;">
                <i data-lucide="alert-circle" style="vertical-align: middle; margin-right: 6px; width: 18px;"></i> <?= $error_msg; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($upload_msg)): ?>
            <div style="max-width: 850px; margin: 0 auto 30px auto; background: <?= $upload_status === 'success' ? '#f0fdf4' : '#fef2f2'; ?>; border: 1px solid <?= $upload_status === 'success' ? '#bbf7d0' : '#fecaca'; ?>; color: <?= $upload_status === 'success' ? '#166534' : '#991b1b'; ?>; padding: 14px 18px; border-radius: 12px; text-align: center; font-weight: 600; font-size: 14px;">
                <i data-lucide="<?= $upload_status === 'success' ? 'check-circle' : 'alert-circle'; ?>" style="vertical-align: middle; margin-right: 6px; width: 18px;"></i> <?= $upload_msg; ?>
            </div>
        <?php endif; ?>

        <!-- AREA PREVIEW KARTU (OTOMATIS TAMPIL SEBAGAI CONTOH ATAU DATA ASLI SISWA) -->
        <div class="two-column-layout" style="align-items: flex-start;">
            
            <!-- KOLOM KIRI: PREVIEW KARTU SISWA INTERAKTIF -->
            <div class="kolom">
                <div class="column-header">
                    <span class="section-tag" style="background: #e0f2fe; color: #0284c7;">Pratinjau KTS</span>
                    <h3>Kartu Siswa (Klik Kartu untuk Membalik)</h3>
                </div>

                <div style="background: #ffffff; padding: 28px 20px; border-radius: 20px; border: 2px dashed #bae6fd; text-align: center; box-shadow: 0 10px 25px rgba(2, 132, 199, 0.05);">
                    
                    <?php if ($is_sample): ?>
                        <div class="sample-badge">
                            <i data-lucide="eye" style="width: 13px; height: 13px; vertical-align: middle;"></i> Mode Pratinjau Contoh Kartu
                        </div>
                    <?php else: ?>
                        <div class="real-badge">
                            <i data-lucide="check-circle-2" style="width: 13px; height: 13px; vertical-align: middle;"></i> Data Resmi Terverifikasi
                        </div>
                    <?php endif; ?>

                    <div id="kts-printable">
                        <?php 
                            // Cek apakah siswa punya gambar KTS utuh dari Canva atau pas foto unggahan
                            $has_file = (!$is_sample && !empty($siswa['foto_kts']) && file_exists(__DIR__ . '/uploads/kts/' . $siswa['foto_kts']));
                            $is_canva_card = ($has_file && strpos($siswa['foto_kts'], 'pas_foto/') === false);
                            
                            // URL Pas Foto untuk kartu dinamis
                            if ($has_file && !$is_canva_card) {
                                $avatar_url = 'uploads/kts/' . htmlspecialchars($siswa['foto_kts']);
                            } else {
                                $avatar_url = 'assets/img/default-avatar.png';
                            }
                        ?>

                        <?php if ($is_canva_card): ?>
                            <!-- JIKA MEMAKAI HASIL EKSPOR JPG CANVA UTUH -->
                            <div style="text-align: center;">
                                <img src="uploads/kts/<?= htmlspecialchars($siswa['foto_kts']); ?>" alt="Kartu Tanda Siswa" style="width: 100%; max-width: 420px; border-radius: 16px; box-shadow: 0 12px 30px rgba(0,0,0,0.15);">
                            </div>
                        <?php else: ?>
                            <!-- PREVIEW KARTU FLIP 3D CERAH -->
                            <div class="kts-card-wrapper" onclick="this.classList.toggle('flipped')">
                                <div class="kts-card-inner">
                                    <!-- SISI DEPAN -->
                                    <div class="kts-face kts-front">
                                        <div class="kts-header">
                                            <div>
                                                <h4>SMAN 8 BANDA ACEH</h4>
                                                <span>KARTU TANDA SISWA • TA <?= htmlspecialchars($siswa['tahun'] ?? '2026/2027'); ?></span>
                                            </div>
                                            <i data-lucide="graduation-cap" style="width: 26px; height: 26px; color: #ffffff;"></i>
                                        </div>

                                        <div class="kts-body">
                                            <img src="<?= $avatar_url; ?>" alt="Foto Siswa" class="kts-avatar" id="preview-avatar" onerror="this.src='https://via.placeholder.com/100x120/e2e8f0/475569?text=FOTO+3x4'">
                                            <div class="kts-info">
                                                <h3><?= htmlspecialchars($siswa['nama_lengkap']); ?></h3>
                                                <p><strong>NISN :</strong> <?= htmlspecialchars($siswa['nisn']); ?></p>
                                                <p><strong>Kelas :</strong> <?= htmlspecialchars($siswa['kelas'] ?? 'Siswa Aktif'); ?></p>
                                                <p><strong>TTL :</strong> <?= htmlspecialchars($siswa['tempat_lahir'] . ', ' . date('d-m-Y', strtotime($siswa['tanggal_lahir']))); ?></p>
                                                <p><strong>Alamat :</strong> <?= htmlspecialchars($siswa['tempat_lahir'] . ', ' . date('d-m-Y', strtotime($siswa['tanggal_lahir']))); ?></p>
                                            </div>
                                        </div>

                                        <div class="kts-footer">
                                            <div class="barcode-container">
                                                <svg id="barcode-siswa"></svg>
                                            </div>
                                            <span style="font-size: 11px; font-weight: 600; opacity: 0.9; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px;">Klik untuk membalik ↺</span>
                                        </div>
                                    </div>

                                    <!-- SISI BELAKANG -->
                                    <div class="kts-face kts-back">
                                        <div class="kts-back-header">
                                            <h5>KETENTUAN KARTU TANDA SISWA</h5>
                                        </div>
                                        <ol class="kts-rules">
                                            <li>Kartu ini wajib dibawa selama berada di lingkungan sekolah.</li>
                                            <li>Berlaku sebagai Kartu Perpustakaan & Layanan Digital.</li>
                                            <li>Apabila kartu ini hilang, segera laporkan ke Bagian Kesiswaan.</li>
                                        </ol>
                                        <div class="kts-signature">
                                            <div style="font-size: 9px; color: #64748b;">
                                                <p style="margin:0;">Status: <strong style="color:#16a34a;"><?= htmlspecialchars($siswa['status_siswa'] ?? 'Aktif'); ?></strong></p>
                                                <p style="margin:0;">TA: <?= htmlspecialchars($siswa['tahun'] ?? '2026/2027'); ?></p>
                                            </div>
                                            <div style="text-align: center; font-size: 10px;">
                                                <p style="margin: 0;">Kepala Sekolah</p>
                                                <div style="height: 28px; font-weight: bold; line-height: 28px; color: #0284c7;">[ TTD & STEMPEL ]</div>
                                                <strong>NIP. 19780101 200501 1 002</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- TOMBOL AKSES DOWNLOAD & CETAK (HANYA AKTIF JIKA DATA SISWA ASLI DITEMUKAN) -->
                    <?php if (!$is_sample): ?>
                        <div style="margin-top: 24px; display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                            <button onclick="downloadPDF()" class="btn-detail" style="border: none; cursor: pointer; padding: 10px 24px; font-size: 14px; border-radius: 8px; background: #0284c7; color: white;">
                                <i data-lucide="download" style="width: 16px; height: 16px; vertical-align: middle;"></i> Unduh PDF
                            </button>
                            <button onclick="window.print()" class="btn-detail" style="background: #475569; border: none; cursor: pointer; padding: 10px 24px; font-size: 14px; border-radius: 8px; color: white;">
                                <i data-lucide="printer" style="width: 16px; height: 16px; vertical-align: middle;"></i> Cetak Kartu
                            </button>
                        </div>

                        <!-- FORM UPLOAD PAS FOTO SISWA (MUNCUL KETIKA DATA SISWA SUDAH DIISI & DITEMUKAN) -->
                        <div class="upload-photo-box">
                            <h4>
                                <i data-lucide="camera" style="width: 18px; height: 18px;"></i> 
                                <?= empty($siswa['foto_kts']) ? 'Lengkapi Pas Foto Kartu Anda' : 'Perbarui Foto Kartu Anda'; ?>
                            </h4>
                            <p>
                                <?= empty($siswa['foto_kts']) 
                                    ? 'Foto kartu Anda masih kosong. Unggah pas foto formal (3x4) berformat JPG/PNG (maks. 2MB) agar tampil di kartu.' 
                                    : 'Ingin mengganti foto pada kartu? Pilih file foto baru di bawah ini.'; ?>
                            </p>
                            <form method="POST" action="kts.php?nisn=<?= urlencode($search_nisn); ?>&tgl_lahir=<?= urlencode($search_tgl); ?>&tahun_ajaran_id=<?= urlencode($search_ta); ?>" enctype="multipart/form-data" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                                <input type="hidden" name="siswa_id" value="<?= $siswa['id']; ?>">
                                <input type="hidden" name="nisn_redirect" value="<?= htmlspecialchars($search_nisn); ?>">
                                <input type="hidden" name="tgl_redirect" value="<?= htmlspecialchars($search_tgl); ?>">
                                <input type="hidden" name="ta_redirect" value="<?= htmlspecialchars($search_ta); ?>">
                                
                                <input type="file" name="foto_siswa" accept="image/jpeg, image/png" required onchange="previewLocalImage(event)" style="flex: 1; min-width: 180px; font-size: 13px; background: #ffffff; padding: 8px; border-radius: 8px; border: 1px solid #bae6fd;">
                                
                                <button type="submit" name="upload_foto" style="background: #0284c7; color: #ffffff; border: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                    <i data-lucide="upload" style="width: 15px; height: 15px;"></i> Simpan Foto
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <p style="margin-top: 18px; font-size: 12px; color: #64748b;">
                            Isi <strong>NISN</strong>, <strong>Tanggal Lahir</strong>, dan <strong>Tahun Ajaran</strong> di atas untuk mengaktifkan fitur Unduh PDF dan Upload Foto.
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- KOLOM KANAN: AKUN INTEGRASI SISWA -->
            <div class="kolom">
                <div class="column-header">
                    <span class="section-tag" style="background: #e0f2fe; color: #0284c7;">Akses Integrasi</span>
                    <h3>Informasi Akun Belajar & e-Rapor</h3>
                </div>

                <!-- AKUN BELAJAR.ID -->
                <div class="account-card">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div class="account-icon" style="background: #e0f2fe; color: #0284c7;">
                            <i data-lucide="mail" style="width: 22px; height: 22px;"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; font-size: 15px; color: #0f172a; font-weight: 700;">Akun Belajar.id</h4>
                            <small style="color: #64748b;">Akses Google Classroom & Drive Sekolah</small>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 10px; font-size: 14px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #64748b; font-weight: 500;">Username:</span>
                            <span class="code-box"><?= htmlspecialchars($siswa['username_belajar_id'] ?? 'Belum Diatur'); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #64748b; font-weight: 500;">Password:</span>
                            <span class="code-box"><?= htmlspecialchars($siswa['password_belajar_id'] ?? 'Belum Diatur'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- AKUN E-RAPOR -->
                <div class="account-card">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div class="account-icon" style="background: #dcfce7; color: #16a34a;">
                            <i data-lucide="file-check-2" style="width: 22px; height: 22px;"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; font-size: 15px; color: #0f172a; font-weight: 700;">Akun Portal e-Rapor</h4>
                            <small style="color: #64748b;">Akses Nilai Semester Siswa & Orang Tua</small>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 10px; font-size: 14px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #64748b; font-weight: 500;">Username:</span>
                            <span class="code-box"><?= htmlspecialchars($siswa['username_erapor'] ?? 'Belum Diatur'); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #64748b; font-weight: 500;">Password:</span>
                            <span class="code-box"><?= htmlspecialchars($siswa['password_erapor'] ?? 'Belum Diatur'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- BANNER PERINGATAN -->
                <div class="portal-banner" style="border-radius: 16px; background: linear-gradient(135deg, #0284c7, #1e3a8a); border: none;">
                    <div class="portal-content">
                        <i data-lucide="shield-alert" class="portal-icon" style="color: #bae6fd;"></i>
                        <h4 style="color: #ffffff;">Penting Bagi Siswa & Orang Tua</h4>
                        <p style="color: #e0f2fe;">Simpan dan lindungi kerahasiaan username & password ini. Gunakan kredensial di atas untuk memantau hasil belajar semester siswa.</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- SCRIPT BARCODE, LIVE PREVIEW FOTO & EXPORT PDF -->
<script>
// Generate Barcode untuk kartu (baik contoh maupun data asli)
if (document.getElementById("barcode-siswa")) {
    JsBarcode("#barcode-siswa", "<?= htmlspecialchars($siswa['nisn']); ?>", {
        format: "CODE128",
        width: 1.5,
        height: 28,
        displayValue: false
    });
}

// Live preview foto saat siswa memilih file sebelum klik Simpan
function previewLocalImage(event) {
    const file = event.target.files[0];
    const avatarImg = document.getElementById('preview-avatar');
    if (file && avatarImg) {
        const reader = new FileReader();
        reader.onload = function(e) {
            avatarImg.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function downloadPDF() {
    const element = document.getElementById('kts-printable');
    const opt = {
      margin:       0.3,
      filename:     'KTS_<?= htmlspecialchars($siswa['nisn']); ?>.pdf',
      image:        { type: 'jpeg', quality: 0.98 },
      html2canvas:  { scale: 2, useCORS: true },
      jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).save();
}
</script>

<?php include 'includes/footer.php'; ?>