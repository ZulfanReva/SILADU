<?php
include_once '../../koneksi.php';
function getTotalAndGrowth($koneksi, $table, $dateField) {
    $now = date('Y-m-01');
    $lastMonth = date('Y-m-01', strtotime('-1 month'));
    $nextMonth = date('Y-m-01', strtotime('+1 month'));
    $total = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM $table"))[0];
    $bulan_ini = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM $table WHERE $dateField >= '$now' AND $dateField < '$nextMonth'"))[0];
    $bulan_lalu = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM $table WHERE $dateField >= '$lastMonth' AND $dateField < '$now'"))[0];
    $growth = ($bulan_ini > 0) ? ($bulan_lalu / $bulan_ini) * 100 : 0;
    return [
        'total' => $total,
        'growth' => $growth
    ];
}
// Pengaduan
$pengaduan = getTotalAndGrowth($koneksi, 'pengaduan_hama_penyakit_tanaman', 'tgl_pengaduan');
// Permohonan (gabungan)
$permohonan_tables = [
    ['permohonan_alat_perikanan', 'tgl_permohonan'],
    ['permohonan_alat_pertanian', 'tgl_permohonan'],
    ['permohonan_alat_ketahananpangan', 'tgl_permohonan'],
    ['permohonan_bantuan', 'tgl_permohonan'],
    ['permohonan_uji_tanaman', 'tgl_permohonan'],
    ['permohonan_vaksinasi_hewan', 'tgl_permohonan'],
];
$permohonan_total = 0; $permohonan_bulan_ini = 0; $permohonan_bulan_lalu = 0;
$now = date('Y-m-01');
$lastMonth = date('Y-m-01', strtotime('-1 month'));
$nextMonth = date('Y-m-01', strtotime('+1 month'));
foreach ($permohonan_tables as $tbl) {
    $permohonan_total += mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM {$tbl[0]}"))[0];
    $permohonan_bulan_ini += mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM {$tbl[0]} WHERE {$tbl[1]} >= '$now' AND {$tbl[1]} < '$nextMonth'"))[0];
    $permohonan_bulan_lalu += mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM {$tbl[0]} WHERE {$tbl[1]} >= '$lastMonth' AND {$tbl[1]} < '$now'"))[0];
}
$permohonan_growth = ($permohonan_bulan_ini > 0) ? ($permohonan_bulan_lalu / $permohonan_bulan_ini) * 100 : 0;
// Pengajuan
$pengajuan = getTotalAndGrowth($koneksi, 'pengajuan_izin', 'tgl_pengajuan');
// Survey
$survey = getTotalAndGrowth($koneksi, 'survei_kepuasan', 'tgl_survei');
function getGrowthClass($growth) {
    if ($growth > 0) return 'text-success';
    if ($growth < 0) return 'text-danger';
    return 'text-secondary';
}
function getGrowthSign($growth) {
    if ($growth > 0) return '+';
    if ($growth < 0) return '-';
    return '';
}
?>
<div class="row">
    <!-- PENGADUAN -->
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Pengaduan</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $pengaduan['total']; ?>
                                <span class="<?php echo getGrowthClass($pengaduan['growth']); ?> text-sm font-weight-bolder">
                                    <?php echo getGrowthSign($pengaduan['growth']) . round(abs($pengaduan['growth']), 2) . '%'; ?>
                            </span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-green shadow text-center border-radius-md">
                            <i class="bi bi-headset text-lg opacity-10" aria-hidden="true"></i>
                            <!-- Bootstrap icon: Headset -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PERMOHONAN -->
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Permohonan</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $permohonan_total; ?>
                                <span class="<?php echo getGrowthClass($permohonan_growth); ?> text-sm font-weight-bolder">
                                    <?php echo getGrowthSign($permohonan_growth) . round(abs($permohonan_growth), 2) . '%'; ?>
                            </span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-green shadow text-center border-radius-md">
                            <i class="bi bi-send text-lg opacity-10" aria-hidden="true"></i>
                            <!-- Bootstrap icon: Handshake -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PENGAJUAN -->
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Pengajuan</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $pengajuan['total']; ?>
                                <span class="<?php echo getGrowthClass($pengajuan['growth']); ?> text-sm font-weight-bolder">
                                    <?php echo getGrowthSign($pengajuan['growth']) . round(abs($pengajuan['growth']), 2) . '%'; ?>
                            </span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-green shadow text-center border-radius-md">
                            <i class="bi bi-file-earmark-text  text-lg opacity-10" aria-hidden="true"></i>
                            <!-- Bootstrap icon: Dokumen -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SURVEY -->
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Survey</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $survey['total']; ?>
                                <span class="<?php echo getGrowthClass($survey['growth']); ?> text-sm font-weight-bolder">
                                    <?php echo getGrowthSign($survey['growth']) . round(abs($survey['growth']), 2) . '%'; ?>
                            </span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-green shadow text-center border-radius-md">
                            <i class="bi bi-star text-lg opacity-10" aria-hidden="true"></i>
                            <!-- Bootstrap icon: Formulir/survey -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>