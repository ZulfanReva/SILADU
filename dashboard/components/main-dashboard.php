<?php
// koneksi ke database
$conn = new mysqli("localhost", "root", "", "siladu2");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query gabungan untuk 6 data layanan terbaru
$sql = "
(SELECT u.nama, 'Pengaduan' AS kategori, p.jenis_hama_penyakit AS subkategori, p.tgl_pengaduan AS tanggal, p.status
 FROM pengaduan_hama_penyakit_tanaman p
 JOIN user u ON u.id_user = p.id_user)

UNION

(SELECT u.nama, 'Permohonan' AS kategori, 'Perikanan' AS subkategori, p.tgl_permohonan AS tanggal, p.status
 FROM permohonan_alat_perikanan p
 JOIN user u ON u.id_user = p.id_user)

UNION

(SELECT u.nama, 'Permohonan' AS kategori, 'Pertanian' AS subkategori, p.tgl_permohonan AS tanggal, p.status
 FROM permohonan_alat_pertanian p
 JOIN user u ON u.id_user = p.id_user)

UNION

(SELECT u.nama, 'Permohonan' AS kategori, 'Ketahanan Pangan' AS subkategori, p.tgl_permohonan AS tanggal, p.status
 FROM permohonan_alat_ketahananpangan p
 JOIN user u ON u.id_user = p.id_user)

UNION

(SELECT u.nama, 'Permohonan' AS kategori, 'Bantuan' AS subkategori, p.tgl_permohonan AS tanggal, p.status_pemohon AS status
 FROM permohonan_bantuan p
 JOIN user u ON u.id_user = p.id_user)

UNION

(SELECT u.nama, 'Permohonan' AS kategori, 'Uji Tanaman' AS subkategori, p.tgl_permohonan AS tanggal, p.status
 FROM permohonan_uji_tanaman p
 JOIN user u ON u.id_user = p.id_user)

UNION

(SELECT u.nama, 'Permohonan' AS kategori, 'Vaksinasi Hewan' AS subkategori, p.tgl_permohonan AS tanggal, p.status
 FROM permohonan_vaksinasi_hewan p
 JOIN user u ON u.id_user = p.id_user)

UNION

(SELECT u.nama, 'Pengajuan' AS kategori, 'Izin' AS subkategori, p.tgl_pengajuan AS tanggal, p.status
 FROM pengajuan_izin p
 JOIN user u ON u.id_user = p.id_user)

ORDER BY tanggal DESC
LIMIT 7
";

$result = $conn->query($sql);
?>

<div class="row mt-4">
    <div class="col-lg-7">
        <!-- Welcome Card -->
        <div class="card mb-4">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="d-flex flex-column h-100">
                            <p class="mb-1 pt-2 text-bold">Dashboard <?php echo htmlspecialchars($level); ?></p>
                            <h5 class="font-weight-bolder">Selamat Datang <?php echo htmlspecialchars($nama); ?>!</h5>
                            <p class="mb-5" style="text-align: justify;">
                                Jelajahi fitur dan kelola aktivitas Anda di sini dengan mudah dari perizinan, bantuan,
                                hingga pengaduan masyarakat dengan cepat dan Efektif.
                            </p>
                            <a href="/dashboard/pages/survey.php" class="text-sm text-weserve-green text-decoration-none">Beri rating disini</a>
                        </div>
                    </div>
                    <div class="col-lg-5 ms-auto text-center mt-5 mt-lg-0">
                        <div class="border-radius-xl h-100">
                            <div class="position-relative d-flex align-items-center justify-content h-100">
                                <img class="w-100 position-relative" src="../assets/img/thumbnail-dashboard.png" alt="rocket">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Layanan Card -->
        <div class="card">
            <div class="card-header pb-0">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-7">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-dashboard">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>

                            <style>
                                .icon-dashboard {
                                    width: 40px;
                                    height: 40px;
                                    background: linear-gradient(310deg, #4e7a3a, #8cd968);
                                    border-radius: 0.5rem;
                                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
                                    margin-right: 10px;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                }

                                .icon-dashboard i {
                                    font-size: 20px;
                                    color: white;
                                    line-height: 1;
                                    display: inline-block;
                                    transform: translateY(1px);
                                }
                            </style>

                            <h6 class="text-sm mb-0">Data Layanan</h6>
                        </div>
                        <p class="text-sm mb-0">
                            <i class="fa fa-check text-success" aria-hidden="true"></i>
                            <span class="font-weight-bold ms-1">30+ layanan selesai</span> bulan ini
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kategori</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sub Kategori</th>
                                <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Pengaduan</th>
                                <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <?php
                                $badge = match ($row['status']) {
                                    'Diterima' => 'bg-gradient-success',
                                    'Ditolak' => 'bg-gradient-danger',
                                    'Direview' => 'bg-gradient-warning',
                                    default => 'bg-gradient-secondary'
                                };
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm"><?= $row['nama'] ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <span class="text-xs font-weight-bold"><?= $row['kategori'] ?></span>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <span class="text-xs font-weight-bold"><?= $row['subkategori'] ?></span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold"><?= $row['tanggal'] ?></span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm <?= $badge ?>"><?= $row['status'] ?></span>
                                    </td>
                                </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Layanan Warga Card -->
    <div class="col-lg-5">
        <div class="card h-100 p-3">
            <h5 class="font-weight-bolder pt-2">Statistik Layanan Warga</h5>

            <!-- Controls -->
            <div class="controls" style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="flex: 1;">
                    <label for="yearSelect" class="" style="font-size: 0.85rem;">Tahun:</label>
                    <select id="yearSelect" class="form-select" style="font-size: 0.8rem; padding: 0.25rem 0.5rem; height: 1.8rem; transition: border-color 0.2s ease-in-out;">
                        <option value="2023">2023</option>
                        <option value="2024" selected>2024</option>
                        <option value="2025">2025</option>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label for="categorySelect" class="" style="font-size: 0.85rem;">Kategori:</label>
                    <select id="categorySelect" class="form-select" style="font-size: 0.8rem; padding: 0.25rem 0.5rem; height: 1.8rem; transition: border-color 0.2s ease-in-out;">
                        <option value="all">Semua Kategori</option>
                        <option value="pengaduan">Pengaduan</option>
                        <option value="permohonan">permohonan</option>
                        <option value="pengajuan">Pengajuan</option>
                        <option value="survey">Survey</option>
                    </select>
                </div>
            </div>
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100" style="background-image: url('../assets/img/thumbnail-chart.png');">
                <span class="mask"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <!-- Chart Container -->
                    <div class="chart-container">
                        <canvas id="wargaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <?php include '../components/footer.php'; ?>

    <?php
    // --- Statistik Layanan Warga ---
    // Ambil data statistik per tahun, bulan, dan kategori/subkategori
    $statistik = [
        '2023' => [],
        '2024' => [],
        '2025' => []
    ];
    $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

    // Helper: inisialisasi array 12 bulan dengan 0
    function arr12() { return array_fill(0, 12, 0); }

    // 1. Hama dan Penyakit (Pengaduan)
    foreach ([2023,2024,2025] as $thn) {
        $q = $conn->query("SELECT YEAR(tgl_pengaduan) th, MONTH(tgl_pengaduan) bln, COUNT(*) jml FROM pengaduan_hama_penyakit_tanaman WHERE YEAR(tgl_pengaduan)=$thn GROUP BY bln");
        $arr = arr12();
        while($r = $q->fetch_assoc()) $arr[$r['bln']-1] = (int)$r['jml'];
        $statistik[$thn]['hama'] = $arr;
    }
    // 2. Alat Perikanan
    foreach ([2023,2024,2025] as $thn) {
        $q = $conn->query("SELECT YEAR(tgl_permohonan) th, MONTH(tgl_permohonan) bln, COUNT(*) jml FROM permohonan_alat_perikanan WHERE YEAR(tgl_permohonan)=$thn GROUP BY bln");
        $arr = arr12();
        while($r = $q->fetch_assoc()) $arr[$r['bln']-1] = (int)$r['jml'];
        $statistik[$thn]['perikanan'] = $arr;
    }
    // 3. Alat Pertanian
    foreach ([2023,2024,2025] as $thn) {
        $q = $conn->query("SELECT YEAR(tgl_permohonan) th, MONTH(tgl_permohonan) bln, COUNT(*) jml FROM permohonan_alat_pertanian WHERE YEAR(tgl_permohonan)=$thn GROUP BY bln");
        $arr = arr12();
        while($r = $q->fetch_assoc()) $arr[$r['bln']-1] = (int)$r['jml'];
        $statistik[$thn]['pertanian'] = $arr;
    }
    // 4. Alat Ketahanan Pangan
    foreach ([2023,2024,2025] as $thn) {
        $q = $conn->query("SELECT YEAR(tgl_permohonan) th, MONTH(tgl_permohonan) bln, COUNT(*) jml FROM permohonan_alat_ketahananpangan WHERE YEAR(tgl_permohonan)=$thn GROUP BY bln");
        $arr = arr12();
        while($r = $q->fetch_assoc()) $arr[$r['bln']-1] = (int)$r['jml'];
        $statistik[$thn]['ketahanan'] = $arr;
    }
    // 5. Bantuan
    foreach ([2023,2024,2025] as $thn) {
        $q = $conn->query("SELECT YEAR(tgl_permohonan) th, MONTH(tgl_permohonan) bln, COUNT(*) jml FROM permohonan_bantuan WHERE YEAR(tgl_permohonan)=$thn GROUP BY bln");
        $arr = arr12();
        while($r = $q->fetch_assoc()) $arr[$r['bln']-1] = (int)$r['jml'];
        $statistik[$thn]['bantuan'] = $arr;
    }
    // 6. Uji Tanaman
    foreach ([2023,2024,2025] as $thn) {
        $q = $conn->query("SELECT YEAR(tgl_permohonan) th, MONTH(tgl_permohonan) bln, COUNT(*) jml FROM permohonan_uji_tanaman WHERE YEAR(tgl_permohonan)=$thn GROUP BY bln");
        $arr = arr12();
        while($r = $q->fetch_assoc()) $arr[$r['bln']-1] = (int)$r['jml'];
        $statistik[$thn]['uji'] = $arr;
    }
    // 7. Vaksinasi Hewan
    foreach ([2023,2024,2025] as $thn) {
        $q = $conn->query("SELECT YEAR(tgl_permohonan) th, MONTH(tgl_permohonan) bln, COUNT(*) jml FROM permohonan_vaksinasi_hewan WHERE YEAR(tgl_permohonan)=$thn GROUP BY bln");
        $arr = arr12();
        while($r = $q->fetch_assoc()) $arr[$r['bln']-1] = (int)$r['jml'];
        $statistik[$thn]['vaksinasi'] = $arr;
    }
    // 8. Izin
    foreach ([2023,2024,2025] as $thn) {
        $q = $conn->query("SELECT YEAR(tgl_pengajuan) th, MONTH(tgl_pengajuan) bln, COUNT(*) jml FROM pengajuan_izin WHERE YEAR(tgl_pengajuan)=$thn GROUP BY bln");
        $arr = arr12();
        while($r = $q->fetch_assoc()) $arr[$r['bln']-1] = (int)$r['jml'];
        $statistik[$thn]['izin'] = $arr;
    }
    // 9. Survey
    foreach ([2023,2024,2025] as $thn) {
        $q = $conn->query("SELECT YEAR(tgl_survei) th, MONTH(tgl_survei) bln, COUNT(*) jml FROM survei_kepuasan WHERE YEAR(tgl_survei)=$thn GROUP BY bln");
        $arr = arr12();
        while($r = $q->fetch_assoc()) $arr[$r['bln']-1] = (int)$r['jml'];
        $statistik[$thn]['survey'] = $arr;
    }

    // Format chartData untuk JS
    function chartDataJS($statistik, $tahun) {
        global $bulanLabels;
        return [
            'all' => [
                'labels' => $bulanLabels,
                'datasets' => [
                    [ 'label'=>'Hama dan Penyakit', 'data'=>$statistik[$tahun]['hama'], 'borderColor'=>'#e91e63', 'backgroundColor'=>'rgba(233,30,99,0.1)', 'tension'=>0.4 ],
                    [ 'label'=>'Alat Perikanan', 'data'=>$statistik[$tahun]['perikanan'], 'borderColor'=>'#9c27b0', 'backgroundColor'=>'rgba(156,39,176,0.1)', 'tension'=>0.4 ],
                    [ 'label'=>'Alat Pertanian', 'data'=>$statistik[$tahun]['pertanian'], 'borderColor'=>'#9966FF', 'backgroundColor'=>'rgba(103,58,183,0.1)', 'tension'=>0.4 ],
                    [ 'label'=>'Alat Ketahanan Pangan', 'data'=>$statistik[$tahun]['ketahanan'], 'borderColor'=>'#FFCD56', 'backgroundColor'=>'rgba(63,81,181,0.1)', 'tension'=>0.4 ],
                    [ 'label'=>'Bantuan', 'data'=>$statistik[$tahun]['bantuan'], 'borderColor'=>'#2196f3', 'backgroundColor'=>'rgba(33,150,243,0.1)', 'tension'=>0.4 ],
                    [ 'label'=>'Uji Tanaman', 'data'=>$statistik[$tahun]['uji'], 'borderColor'=>'#00ACC1', 'backgroundColor'=>'rgba(3,169,244,0.1)', 'tension'=>0.4 ],
                    [ 'label'=>'Vaksinasi Hewan', 'data'=>$statistik[$tahun]['vaksinasi'], 'borderColor'=>'#FF6B6B', 'backgroundColor'=>'rgba(0,188,212,0.1)', 'tension'=>0.4 ],
                    [ 'label'=>'Izin', 'data'=>$statistik[$tahun]['izin'], 'borderColor'=>'#4bc0c0', 'backgroundColor'=>'rgba(0,150,136,0.1)', 'tension'=>0.4 ]
                ]
            ],
            'pengaduan' => [
                'labels' => $bulanLabels,
                'datasets' => [
                    [ 'label'=>'Hama dan Penyakit', 'data'=>$statistik[$tahun]['hama'], 'borderColor'=>'#e91e63', 'backgroundColor'=>'rgba(233,30,99,0.1)', 'tension'=>0.4 ]
                ]
            ],
            'permohonan' => [
                'labels' => $bulanLabels,
                'datasets' => [
                    [ 'label'=>'Alat Perikanan', 'data'=>$statistik[$tahun]['perikanan'], 'borderColor'=>'#9c27b0', 'backgroundColor'=>'rgba(156,39,176,0.1)', 'tension'=>0.4 ],
                    [ 'label'=>'Alat Pertanian', 'data'=>$statistik[$tahun]['pertanian'], 'borderColor'=>'#9966FF', 'backgroundColor'=>'rgba(103,58,183,0.1)', 'tension'=>0.4 ],
                    [ 'label'=>'Alat Ketahanan Pangan', 'data'=>$statistik[$tahun]['ketahanan'], 'borderColor'=>'#FFCD56', 'backgroundColor'=>'rgba(63,81,181,0.1)', 'tension'=>0.4 ],
                    [ 'label'=>'Bantuan', 'data'=>$statistik[$tahun]['bantuan'], 'borderColor'=>'#2196f3', 'backgroundColor'=>'rgba(33,150,243,0.1)', 'tension'=>0.4 ],
                    [ 'label'=>'Uji Tanaman', 'data'=>$statistik[$tahun]['uji'], 'borderColor'=>'#00ACC1', 'backgroundColor'=>'rgba(3,169,244,0.1)', 'tension'=>0.4 ],
                    [ 'label'=>'Vaksinasi Hewan', 'data'=>$statistik[$tahun]['vaksinasi'], 'borderColor'=>'#FF6B6B', 'backgroundColor'=>'rgba(0,188,212,0.1)', 'tension'=>0.4 ]
                ]
            ],
            'pengajuan' => [
                'labels' => $bulanLabels,
                'datasets' => [
                    [ 'label'=>'Izin', 'data'=>$statistik[$tahun]['izin'], 'borderColor'=>'#4bc0c0', 'backgroundColor'=>'rgba(0,150,136,0.1)', 'tension'=>0.4 ]
                ]
            ],
            'survey' => [
                'labels' => $bulanLabels,
                'datasets' => [
                    [ 'label'=>'Survey Data', 'data'=>$statistik[$tahun]['survey'], 'borderColor'=>'#ff5722', 'backgroundColor'=>'rgba(255,87,34,0.1)', 'tension'=>0.4 ]
                ]
            ]
        ];
    }

    $chartData = [
        '2023' => chartDataJS($statistik, 2023),
        '2024' => chartDataJS($statistik, 2024),
        '2025' => chartDataJS($statistik, 2025)
    ];
    ?>

    <script>
        // Data dummy berdasarkan struktur dari gambar
        const chartData = <?php echo json_encode($chartData, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); ?>;

        let chart;

        function initChart() {
            const ctx = document.getElementById('wargaChart').getContext('2d');

            chart = new Chart(ctx, {
                type: 'line',
                data: chartData[2024].all,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#ffffff',
                                font: {
                                    size: 10
                                },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            max: 100,
                            ticks: {
                                color: '#ffffff',
                                stepSize: 20
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.2)'
                            },
                            title: {
                                display: true,
                                text: 'Jumlah',
                                color: '#ffffff'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#ffffff'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.2)'
                            },
                            title: {
                                display: true,
                                text: 'Periode',
                                color: '#ffffff'
                            }
                        }
                    },
                    elements: {
                        point: {
                            radius: 3,
                            hoverRadius: 6
                        }
                    }
                }
            });
        }

        function updateChart() {
            const year = document.getElementById('yearSelect').value;
            const category = document.getElementById('categorySelect').value;

            if (chartData[year] && chartData[year][category]) {
                chart.data = chartData[year][category];
                chart.update();
            }
        }

        // Event listeners
        document.getElementById('yearSelect').addEventListener('change', updateChart);
        document.getElementById('categorySelect').addEventListener('change', updateChart);

        // Initialize chart when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initChart();
        });
    </script>

</div>