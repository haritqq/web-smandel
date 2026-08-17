<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Menentukan letak file JSON di root direktori
$counter_file = __DIR__ . '/../visitor_counter.json';

// Nilai awal default untuk data statistik realistis
$default_stats = [
    'last_reset_day' => date('Y-m-d'),
    'last_reset_month' => date('Y-m'),
    'last_reset_year' => date('Y'),
    'daily' => 45,
    'monthly' => 1250,
    'yearly' => 8420,
    'all_time' => 32450
];

// Buat berkas baru dengan nilai default jika berkas belum ada
if (!file_exists($counter_file)) {
    file_put_contents($counter_file, json_encode($default_stats, JSON_PRETTY_PRINT));
}

// Baca data saat ini
$stats = json_decode(file_get_contents($counter_file), true);
if (!$stats) {
    $stats = $default_stats;
}

$today = date('Y-m-d');
$this_month = date('Y-m');
$this_year = date('Y');

$changed = false;

// Cek apakah tanggal hari ini sudah berganti, jika ya reset hitungan harian
if ($stats['last_reset_day'] !== $today) {
    $stats['daily'] = 0;
    $stats['last_reset_day'] = $today;
    $changed = true;
}

// Cek apakah bulan saat ini sudah berganti, jika ya reset hitungan bulanan
if ($stats['last_reset_month'] !== $this_month) {
    $stats['monthly'] = 0;
    $stats['last_reset_month'] = $this_month;
    $changed = true;
}

// Cek apakah tahun saat ini sudah berganti, jika ya reset hitungan tahunan
if ($stats['last_reset_year'] !== $this_year) {
    $stats['yearly'] = 0;
    $stats['last_reset_year'] = $this_year;
    $changed = true;
}

// Tambah hitungan jika pengunjung belum tercatat mengunjungi halaman ini dalam sesi saat ini
if (!isset($_SESSION['has_counted_visit'])) {
    $_SESSION['has_counted_visit'] = true;
    
    $stats['daily']++;
    $stats['monthly']++;
    $stats['yearly']++;
    $stats['all_time']++;
    $changed = true;
}

// Simpan kembali data jika ada perubahan data statistik
if ($changed) {
    file_put_contents($counter_file, json_encode($stats, JSON_PRETTY_PRINT));
}

// Sediakan variabel global agar bisa langsung diakses di halaman index.php
$visitor_stats = $stats;
?>
