<?php
// session_start();
require_once __DIR__ . '/../config/koneksi.php';

// Cek sesi login admin (sesuaikan dengan variabel session admin Anda)
// if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

$pesan = "";
$tipe_pesan = "";

// 1. AMBIL DAFTAR TAHUN AJARAN
$q_ta = mysqli_query($koneksi, "SELECT * FROM tahun_ajaran ORDER BY tahun DESC, semester DESC");
$list_ta = [];
$default_ta_id = 0;

while ($row = mysqli_fetch_assoc($q_ta)) {
    $list_ta[] = $row;
    if ($row['is_aktif'] == 1 && $default_ta_id == 0) {
        $default_ta_id = $row['id'];
    }
}
if ($default_ta_id == 0 && count($list_ta) > 0) {
    $default_ta_id = $list_ta[0]['id'];
}

// Tahun ajaran yang sedang dipilih di dropdown
$selected_ta_id = isset($_GET['ta_id']) ? intval($_GET['ta_id']) : $default_ta_id;

// 2. PROSES SIMPAN PENGATURAN TEMPLATE KTS (DEPAN & BELAKANG)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_template'])) {
    $ta_id            = intval($_POST['tahun_ajaran_id']);
    $nama_sekolah     = mysqli_real_escape_string($koneksi, $_POST['nama_sekolah']);
    $judul_kartu      = mysqli_real_escape_string($koneksi, $_POST['judul_kartu']);
    $warna_tema       = mysqli_real_escape_string($koneksi, $_POST['warna_tema']);
    $ketentuan        = mysqli_real_escape_string($koneksi, $_POST['ketentuan_belakang']);
    $tempat_ttd       = mysqli_real_escape_string($koneksi, $_POST['tempat_ttd']);
    $jabatan_ttd      = mysqli_real_escape_string($koneksi, $_POST['jabatan_ttd']);
    $nama_kepsek      = mysqli_real_escape_string($koneksi, $_POST['nama_kepsek']);
    $nip_kepsek       = mysqli_real_escape_string($koneksi, $_POST['nip_kepsek']);

    // Cek data template lama untuk mempertahankan gambar jika tidak diupload ulang
    $q_old = mysqli_query($koneksi, "SELECT * FROM kts_template WHERE tahun_ajaran_id = $ta_id");
    $old_data = mysqli_fetch_assoc($q_old);

    $bg_depan    = $old_data['bg_depan'] ?? null;
    $bg_belakang = $old_data['bg_belakang'] ?? null;
    $file_ttd    = $old_data['file_ttd_stempel'] ?? null;

    // Folder upload template (disimpan di root/uploads/kts/templates/)
    $upload_dir = __DIR__ . '/../uploads/kts/templates/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Fungsi helper upload gambar
    function uploadTemplateImg($input_name, $prefix, $ta_id, $dir, $old_val) {
        if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $new_name = $prefix . "_ta" . $ta_id . "_" . time() . "." . $ext;
                if (move_uploaded_file($_FILES[$input_name]['tmp_name'], $dir . $new_name)) {
                    return "templates/" . $new_name;
                }
            }
        }
        return $old_val;
    }

    $bg_depan    = uploadTemplateImg('bg_depan', 'depan', $ta_id, $upload_dir, $bg_depan);
    $bg_belakang = uploadTemplateImg('bg_belakang', 'belakang', $ta_id, $upload_dir, $bg_belakang);
    $file_ttd    = uploadTemplateImg('file_ttd_stempel', 'ttd', $ta_id, $upload_dir, $file_ttd);

    // Insert atau Update ke tabel kts_template
    $sql_save = "
        INSERT INTO kts_template 
        (tahun_ajaran_id, bg_depan, bg_belakang, warna_tema, judul_kartu, nama_sekolah, ketentuan_belakang, tempat_ttd, jabatan_ttd, nama_kepsek, nip_kepsek, file_ttd_stempel)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            bg_depan = VALUES(bg_depan),
            bg_belakang = VALUES(bg_belakang),
            warna_tema = VALUES(warna_tema),
            judul_kartu = VALUES(judul_kartu),
            nama_sekolah = VALUES(nama_sekolah),
            ketentuan_belakang = VALUES(ketentuan_belakang),
            tempat_ttd = VALUES(tempat_ttd),
            jabatan_ttd = VALUES(jabatan_ttd),
            nama_kepsek = VALUES(nama_kepsek),
            nip_kepsek = VALUES(nip_kepsek),
            file_ttd_stempel = VALUES(file_ttd_stempel)
    ";

    $stmt = mysqli_prepare($koneksi, $sql_save);
    mysqli_stmt_bind_param($stmt, "isssssssssss", 
        $ta_id, $bg_depan, $bg_belakang, $warna_tema, $judul_kartu, $nama_sekolah, 
        $ketentuan, $tempat_ttd, $jabatan_ttd, $nama_kepsek, $nip_kepsek, $file_ttd
    );

    if (mysqli_stmt_execute($stmt)) {
        $pesan = "Template & pengaturan KTS berhasil disimpan!";
        $tipe_pesan = "success";
        $selected_ta_id = $ta_id;
    } else {
        $pesan = "Gagal menyimpan pengaturan: " . mysqli_error($koneksi);
        $tipe_pesan = "error";
    }
    mysqli_stmt_close($stmt);
}

// 3. AMBIL DATA TEMPLATE UNTUK TAHUN AJARAN TERPILIH
$q_tpl = mysqli_query($koneksi, "SELECT * FROM kts_template WHERE tahun_ajaran_id = $selected_ta_id");
$tpl = mysqli_fetch_assoc($q_tpl) ?: [
    'bg_depan'           => '',
    'bg_belakang'        => '',
    'warna_tema'         => '#0284c7',
    'judul_kartu'        => 'KARTU TANDA SISWA',
    'nama_sekolah'       => 'SMAN 8 BANDA ACEH',
    'ketentuan_belakang' => "1. Kartu ini wajib dibawa selama berada di lingkungan sekolah.\n2. Berlaku sebagai Kartu Perpustakaan & Layanan Digital.\n3. Apabila kartu ini hilang, segera laporkan ke Bagian Kesiswaan.",
    'tempat_ttd'         => 'Banda Aceh',
    'jabatan_ttd'        => 'Kepala Sekolah',
    'nama_kepsek'        => 'Nama Kepala Sekolah, M.Pd',
    'nip_kepsek'         => '19780101 200501 1 002',
    'file_ttd_stempel'   => ''
];

// 4. AMBIL DAFTAR SISWA PADA TAHUN AJARAN / ANGKATAN TERPILIH
$q_siswa = mysqli_query($koneksi, "
    SELECT ks.*, sta.kelas, sta.status_siswa 
    FROM siswa_tahun_ajaran sta
    JOIN kartu_siswa ks ON sta.kartu_siswa_id = ks.id
    WHERE sta.tahun_ajaran_id = $selected_ta_id
    ORDER BY sta.kelas ASC, ks.nama_lengkap ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola KTS & Template - Admin</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f1f5f9; color: #1e293b; margin: 0; padding: 24px; } */
        .container { max-width: 1200px; margin: 0 auto; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 20px 28px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 24px; flex-wrap: wrap; gap: 15px; }
        .header-bar h1 { margin: 0; font-size: 22px; color: #0f172a; }
        .card { background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 24px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        @media (max-width: 900px) { .grid-2 { grid-template-columns: 1fr; } }
        
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box; }
        .btn { padding: 10px 20px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 14px; text-decoration: none; }
        .btn-primary { background: #0284c7; color: #fff; }
        .btn-primary:hover { background: #0369a1; }
        .btn-outline { background: #f8fafc; border: 1px solid #cbd5e1; color: #334155; }

        /* LIVE PREVIEW KARTU DEPAN & BELAKANG */
        .preview-box { background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 14px; padding: 20px; display: flex; flex-direction: column; align-items: center; gap: 20px; }
        .kts-mock { width: 380px; height: 235px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.12); overflow: hidden; position: relative; box-sizing: border-box; }
        
        .mock-front {
            background-color: <?= htmlspecialchars($tpl['warna_tema']); ?>;
            <?php if (!empty($tpl['bg_depan']) && file_exists(__DIR__ . '/../uploads/kts/' . $tpl['bg_depan'])): ?>
                background-image: url('../uploads/kts/<?= htmlspecialchars($tpl['bg_depan']); ?>');
                background-size: cover;
                background-position: center;
            <?php endif; ?>
            color: #fff; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;
        }
        .mock-front-overlay { position: absolute; inset: 0; background: rgba(15, 23, 42, 0.35); z-index: 1; }
        .mock-content { position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; justify-content: space-between; }

        .mock-back {
            background-color: #ffffff;
            <?php if (!empty($tpl['bg_belakang']) && file_exists(__DIR__ . '/../uploads/kts/' . $tpl['bg_belakang'])): ?>
                background-image: url('../uploads/kts/<?= htmlspecialchars($tpl['bg_belakang']); ?>');
                background-size: cover;
                background-position: center;
            <?php endif; ?>
            color: #1e293b; padding: 18px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; justify-content: space-between;
        }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f8fafc; color: #475569; font-weight: 700; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warn { background: #fef3c7; color: #b45309; }
    </style>
</head>
<body>

<div class="container">
    
    <!-- HEADER & FILTER TAHUN AJARAN -->
    <div class="header-bar">
        <div>
            <h1>Manajemen Kartu Tanda Siswa (KTS)</h1>
            <small style="color: #64748b;">Atur template kartu depan & belakang serta kelola data siswa per tahun ajaran</small>
        </div>

        <form method="GET" style="display: flex; align-items: center; gap: 10px;">
            <label style="font-weight: 600; font-size: 14px;">Tahun Ajaran / Angkatan:</label>
            <select name="ta_id" onchange="this.form.submit()" class="form-control" style="width: auto; min-width: 220px; font-weight: 600;">
                <?php foreach ($list_ta as $ta): ?>
                    <option value="<?= $ta['id']; ?>" <?= ($selected_ta_id == $ta['id']) ? 'selected' : ''; ?>>
                        <?= $ta['tahun']; ?> - <?= $ta['semester']; ?> <?= $ta['is_aktif'] ? '(Aktif)' : ''; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if (!empty($pesan)): ?>
        <div style="padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; background: <?= $tipe_pesan === 'success' ? '#dcfce7' : '#fee2e2'; ?>; color: <?= $tipe_pesan === 'success' ? '#166534' : '#991b1b'; ?>;">
            <?= $pesan; ?>
        </div>
    <?php endif; ?>

    <!-- FORM EDITOR TEMPLATE DEPAN & BELAKANG -->
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="tahun_ajaran_id" value="<?= $selected_ta_id; ?>">

        <div class="grid-2">
            <!-- KOLOM KIRI: FORM PENGATURAN -->
            <div class="card">
                <h3 style="margin-top: 0; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="layout-template" style="color: #0284c7;"></i> Desain
                </h3>

                <div class="grid-2" style="gap: 12px;">
                    <div class="form-group">
                        <label>Nama Sekolah (Header Depan)</label>
                        <input type="text" name="nama_sekolah" id="in_sekolah" value="<?= htmlspecialchars($tpl['nama_sekolah']); ?>" class="form-control" oninput="updateLivePreview()">
                    </div>
                    <div class="form-group">
                        <label>Judul Kartu</label>
                        <input type="text" name="judul_kartu" id="in_judul" value="<?= htmlspecialchars($tpl['judul_kartu']); ?>" class="form-control" oninput="updateLivePreview()">
                    </div>
                </div>

                <div class="grid-2" style="gap: 12px;">
                    <div class="form-group">
                        <label>Upload Background Depan (JPG/PNG)</label>
                        <input type="file" name="bg_depan" accept="image/*" class="form-control" onchange="previewBg(event, 'mockFront')">
                    </div>
                    <div class="form-group">
                        <label>Upload Background Belakang (JPG/PNG)</label>
                        <input type="file" name="bg_belakang" accept="image/*" class="form-control" onchange="previewBg(event, 'mockBack')">
                    </div>
                </div>

                <div class="form-group">
                    <label>Warna Tema Utama (Jika tanpa background gambar)</label>
                    <input type="color" name="warna_tema" id="in_warna" value="<?= htmlspecialchars($tpl['warna_tema']); ?>" style="height: 40px; width: 100px; border: 1px solid #cbd5e1; border-radius: 6px; cursor: pointer;" oninput="updateLivePreview()">
                </div>

                <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 18px 0;">

                <div class="form-group">
                    <label>Teks Tata Tertib / Ketentuan (Sisi Belakang)</label>
                    <textarea name="ketentuan_belakang" id="in_ketentuan" rows="4" class="form-control" oninput="updateLivePreview()"><?= htmlspecialchars($tpl['ketentuan_belakang']); ?></textarea>
                </div>

                <div class="grid-2" style="gap: 12px;">
                    <div class="form-group">
                        <label>Tempat & Jabatan Penandatangan</label>
                        <input type="text" name="tempat_ttd" id="in_tempat" value="<?= htmlspecialchars($tpl['tempat_ttd']); ?>" placeholder="Banda Aceh" class="form-control" style="margin-bottom: 6px;" oninput="updateLivePreview()">
                        <input type="text" name="jabatan_ttd" id="in_jabatan" value="<?= htmlspecialchars($tpl['jabatan_ttd']); ?>" placeholder="Kepala Sekolah" class="form-control" oninput="updateLivePreview()">
                    </div>
                    <div class="form-group">
                        <label>Nama & NIP Kepala Sekolah</label>
                        <input type="text" name="nama_kepsek" id="in_kepsek" value="<?= htmlspecialchars($tpl['nama_kepsek']); ?>" placeholder="Nama Kepala Sekolah" class="form-control" style="margin-bottom: 6px;" oninput="updateLivePreview()">
                        <input type="text" name="nip_kepsek" id="in_nip" value="<?= htmlspecialchars($tpl['nip_kepsek']); ?>" placeholder="NIP" class="form-control" oninput="updateLivePreview()">
                    </div>
                </div>

                <div class="form-group">
                    <label>Upload Gambar Tanda Tangan & Stempel (PNG Transparan)</label>
                    <input type="file" name="file_ttd_stempel" accept="image/png, image/jpeg" class="form-control">
                </div>

                <button type="submit" name="simpan_template" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px; margin-top: 8px;">
                    <i data-lucide="save"></i> Simpan Perubahan Template Tahun Ajaran Ini
                </button>
            </div>

            <!-- KOLOM KANAN: LIVE PREVIEW DEPAN & BELAKANG -->
            <div class="card">
                <h3 style="margin-top: 0; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="eye" style="color: #0284c7;"></i> Pratinjau
                </h3>

                <div class="preview-box">
                    <!-- KARTU DEPAN -->
                    <div>
                        <div style="font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 6px; text-align: center;">SISI DEPAN KARTU</div>
                        <div class="kts-mock mock-front" id="mockFront">
                            <div class="mock-front-overlay"></div>
                            <div class="mock-content">
                                <div style="border-bottom: 1px solid rgba(255,255,255,0.3); padding-bottom: 6px; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div id="prev_sekolah" style="font-weight: 800; font-size: 14px;"><?= htmlspecialchars($tpl['nama_sekolah']); ?></div>
                                        <div id="prev_judul" style="font-size: 10px; opacity: 0.9;"><?= htmlspecialchars($tpl['judul_kartu']); ?></div>
                                    </div>
                                    <i data-lucide="graduation-cap"></i>
                                </div>
                                <div style="display: flex; gap: 14px; align-items: center;">
                                    <div style="width: 72px; height: 90px; background: rgba(255,255,255,0.85); border-radius: 8px; color: #334155; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700;">FOTO 3x4</div>
                                    <div style="font-size: 11px; line-height: 1.4;">
                                        <div style="font-size: 14px; font-weight: 800; margin-bottom: 3px;">NAMA LENGKAP SISWA</div>
                                        <div><strong>NISN:</strong> 0012345678</div>
                                        <div><strong>Kelas:</strong> XI IPA 1</div>
                                        <div><strong>TTL:</strong> Banda Aceh, 14-05-2008</div>
                                    </div>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                                    <div style="background: #fff; color: #000; padding: 2px 12px; border-radius: 4px; font-family: monospace; font-size: 10px; letter-spacing: 2px;">|| ||| | |||| |||</div>
                                    <span style="font-size: 9px; opacity: 0.85;">Berlaku Selama Menjadi Siswa</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KARTU BELAKANG -->
                    <div>
                        <div style="font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 6px; text-align: center;">SISI BELAKANG KARTU</div>
                        <div class="kts-mock mock-back" id="mockBack">
                            <div style="text-align: center; border-bottom: 1px solid #cbd5e1; padding-bottom: 6px;">
                                <strong style="font-size: 11px; color: #0284c7;">KETENTUAN PENGGUNAAN KARTU</strong>
                            </div>
                            <div id="prev_ketentuan" style="font-size: 10px; line-height: 1.45; white-space: pre-line; margin: 6px 0; color: #334155;">
                                <?= htmlspecialchars($tpl['ketentuan_belakang']); ?>
                            </div>
                            <div style="display: flex; justify-content: flex-end;">
                                <div style="text-align: center; font-size: 9.5px; line-height: 1.3;">
                                    <div><span id="prev_tempat"><?= htmlspecialchars($tpl['tempat_ttd']); ?></span>, <span id="prev_jabatan"><?= htmlspecialchars($tpl['jabatan_ttd']); ?></span></div>
                                    <?php if (!empty($tpl['file_ttd_stempel']) && file_exists(__DIR__ . '/../uploads/kts/' . $tpl['file_ttd_stempel'])): ?>
                                        <img src="../uploads/kts/<?= htmlspecialchars($tpl['file_ttd_stempel']); ?>" style="height: 32px; margin: 2px auto; display: block;">
                                    <?php else: ?>
                                        <div style="height: 26px; color: #94a3b8; line-height: 26px;">[ TTD & Stempel ]</div>
                                    <?php endif; ?>
                                    <strong id="prev_kepsek" style="text-decoration: underline;"><?= htmlspecialchars($tpl['nama_kepsek']); ?></strong><br>
                                    <span>NIP. <span id="prev_nip"><?= htmlspecialchars($tpl['nip_kepsek']); ?></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- TABEL DAFTAR SISWA PADA TAHUN AJARAN / ANGKATAN TERPILIH -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="margin: 0; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="users" style="color: #0284c7;"></i> Daftar Siswa pada Tahun Ajaran Ini
            </h3>
            <span style="font-size: 13px; color: #64748b; font-weight: 600;">Total: <?= mysqli_num_rows($q_siswa); ?> Siswa</span>
        </div>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Kelas</th>
                        <th>Status Foto KTS</th>
                        <th>Akun Belajar.id</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($q_siswa && mysqli_num_rows($q_siswa) > 0): ?>
                        <?php $no = 1; while ($s = mysqli_fetch_assoc($q_siswa)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><strong><?= htmlspecialchars($s['nisn']); ?></strong></td>
                                <td><?= htmlspecialchars($s['nama_lengkap']); ?></td>
                                <td><?= htmlspecialchars($s['kelas'] ?? '-'); ?></td>
                                <td>
                                    <?php if (!empty($s['foto_kts'])): ?>
                                        <span class="badge badge-success">Tersedia</span>
                                    <?php else: ?>
                                        <span class="badge badge-warn">Kosong</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($s['username_belajar_id'] ?? '-'); ?></td>
                                <td>
                                    <a href="../kts.php?nisn=<?= urlencode($s['nisn']); ?>&tgl_lahir=<?= urlencode($s['tanggal_lahir']); ?>&tahun_ajaran_id=<?= $selected_ta_id; ?>" target="_blank" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;">
                                        <i data-lucide="external-link" style="width: 14px; height: 14px;"></i> Lihat Kartu
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b; padding: 24px;">
                                Belum ada data siswa yang terdaftar pada tahun ajaran ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    lucide.createIcons();

    function updateLivePreview() {
        document.getElementById('prev_sekolah').innerText   = document.getElementById('in_sekolah').value;
        document.getElementById('prev_judul').innerText     = document.getElementById('in_judul').value;
        document.getElementById('mockFront').style.backgroundColor = document.getElementById('in_warna').value;
        document.getElementById('prev_ketentuan').innerText = document.getElementById('in_ketentuan').value;
        document.getElementById('prev_tempat').innerText    = document.getElementById('in_tempat').value;
        document.getElementById('prev_jabatan').innerText   = document.getElementById('in_jabatan').value;
        document.getElementById('prev_kepsek').innerText    = document.getElementById('in_kepsek').value;
        document.getElementById('prev_nip').innerText       = document.getElementById('in_nip').value;
    }

    function previewBg(event, targetId) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const el = document.getElementById(targetId);
                el.style.backgroundImage = `url('${e.target.result}')`;
                el.style.backgroundSize = 'cover';
                el.style.backgroundPosition = 'center';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
</body>
</html>