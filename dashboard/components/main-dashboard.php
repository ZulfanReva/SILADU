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
                                    /* tweak jika masih terlihat turun */
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
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Ahmad Yani</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-sm">
                                    <span class="text-xs font-weight-bold">Pengaduan</span>
                                </td>
                                <td class="align-middle text-sm">
                                    <span class="text-xs font-weight-bold">Hama dan Penyakit</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">2025-06-10</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-success">Diterima</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Siti Aminah</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-sm">
                                    <span class="text-xs font-weight-bold">Permohonan</span>
                                </td>
                                <td class="align-middle text-sm">
                                    <span class="text-xs font-weight-bold">Alat Pertanian</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">2025-06-12</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-warning">Direview</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Budi Santoso</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-sm">
                                    <span class="text-xs font-weight-bold">Pengajuan</span>
                                </td>
                                <td class="align-middle text-sm">
                                    <span class="text-xs font-weight-bold">Izin</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">2025-06-15</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-success">Diterima</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Rina Sari</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-sm">
                                    <span class="text-xs font-weight-bold">Survey</span>
                                </td>
                                <td class="align-middle text-sm">
                                    <span class="text-xs font-weight-bold">Uji Tanaman</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">2025-06-16</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-secondary">Diproses</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Dedi Pratama</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-sm">
                                    <span class="text-xs font-weight-bold">Permohonan</span>
                                </td>
                                <td class="align-middle text-sm">
                                    <span class="text-xs font-weight-bold">Bantuan</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">2025-06-17</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-warning">Direview</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">Lina Wijaya</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-sm">
                                    <span class="text-xs font-weight-bold">Pengajuan</span>
                                </td>
                                <td class="align-middle text-sm">
                                    <span class="text-xs font-weight-bold">Vaksinasi Hewan</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">2025-06-17</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-danger">Ditolak</span>
                                </td>
                            </tr>
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
                        <option value="permohonanan">Permohonanan</option>
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

    <script>
        // Data dummy berdasarkan struktur dari gambar
        const chartData = {
            2023: {
                all: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                            label: 'Hama dan Penyakit',
                            data: [65, 59, 80, 81, 56, 55, 70, 65, 75, 85, 90, 95],
                            borderColor: '#e91e63',
                            backgroundColor: 'rgba(233, 30, 99, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Alat Perikanan',
                            data: [45, 49, 60, 71, 46, 45, 50, 55, 65, 75, 80, 85],
                            borderColor: '#9c27b0',
                            backgroundColor: 'rgba(156, 39, 176, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Alat Pertanian',
                            data: [35, 39, 50, 61, 36, 35, 40, 45, 55, 65, 70, 75],
                            borderColor: '#9966FF',
                            backgroundColor: 'rgba(103, 58, 183, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Alat Ketahanan Pangan',
                            data: [25, 29, 40, 51, 26, 25, 30, 35, 45, 55, 60, 65],
                            borderColor: '#FFCD56',
                            backgroundColor: 'rgba(63, 81, 181, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Bantuan',
                            data: [55, 69, 70, 61, 66, 75, 80, 75, 85, 95, 100, 105],
                            borderColor: '#2196f3',
                            backgroundColor: 'rgba(33, 150, 243, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Uji Tanaman',
                            data: [15, 19, 30, 41, 16, 15, 20, 25, 35, 45, 50, 55],
                            borderColor: '#00ACC1',
                            backgroundColor: 'rgba(3, 169, 244, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Vaksinasi Hewan',
                            data: [40, 44, 55, 66, 41, 40, 45, 50, 60, 70, 75, 80],
                            borderColor: '#FF6B6B',
                            backgroundColor: 'rgba(0, 188, 212, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Izin',
                            data: [30, 34, 45, 56, 31, 30, 35, 40, 50, 60, 65, 70],
                            borderColor: '#4bc0c0',
                            backgroundColor: 'rgba(0, 150, 136, 0.1)',
                            tension: 0.4
                        }
                    ]
                }
            },
            2024: {
                all: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                            label: 'Hama dan Penyakit',
                            data: [75, 69, 90, 91, 66, 65, 80, 75, 85, 95, 100, 105],
                            borderColor: '#e91e63',
                            backgroundColor: 'rgba(233, 30, 99, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Alat Perikanan',
                            data: [55, 59, 70, 81, 56, 55, 60, 65, 75, 85, 90, 95],
                            borderColor: '#9c27b0',
                            backgroundColor: 'rgba(156, 39, 176, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Alat Pertanian',
                            data: [45, 49, 60, 71, 46, 45, 50, 55, 65, 75, 80, 85],
                            borderColor: '#9966FF',
                            backgroundColor: 'rgba(103, 58, 183, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Alat Ketahanan Pangan',
                            data: [35, 39, 50, 61, 36, 35, 40, 45, 55, 65, 70, 75],
                            borderColor: '#FFCD56',
                            backgroundColor: 'rgba(63, 81, 181, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Bantuan',
                            data: [65, 79, 80, 71, 76, 85, 90, 85, 95, 105, 110, 115],
                            borderColor: '#2196f3',
                            backgroundColor: 'rgba(33, 150, 243, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Uji Tanaman',
                            data: [25, 29, 40, 51, 26, 25, 30, 35, 45, 55, 60, 65],
                            borderColor: '#00ACC1',
                            backgroundColor: 'rgba(3, 169, 244, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Vaksinasi Hewan',
                            data: [50, 54, 65, 76, 51, 50, 55, 60, 70, 80, 85, 90],
                            borderColor: '#FF6B6B',
                            backgroundColor: 'rgba(0, 188, 212, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Izin',
                            data: [40, 44, 55, 66, 41, 40, 45, 50, 60, 70, 75, 80],
                            borderColor: '#4bc0c0',
                            backgroundColor: 'rgba(0, 150, 136, 0.1)',
                            tension: 0.4
                        }
                    ]
                },
                pengaduan: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Hama dan Penyakit',
                        data: [75, 69, 90, 91, 66, 65, 80, 75, 85, 95, 100, 105],
                        borderColor: '#e91e63',
                        backgroundColor: 'rgba(233, 30, 99, 0.1)',
                        tension: 0.4
                    }]
                },
                permohonanan: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                            label: 'Alat Perikanan',
                            data: [55, 59, 70, 81, 56, 55, 60, 65, 75, 85, 90, 95],
                            borderColor: '#9c27b0',
                            backgroundColor: 'rgba(156, 39, 176, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Alat Pertanian',
                            data: [45, 49, 60, 71, 46, 45, 50, 55, 65, 75, 80, 85],
                            borderColor: '#9966FF',
                            backgroundColor: 'rgba(103, 58, 183, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Alat Ketahanan Pangan',
                            data: [35, 39, 50, 61, 36, 35, 40, 45, 55, 65, 70, 75],
                            borderColor: '#FFCD56',
                            backgroundColor: 'rgba(63, 81, 181, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Bantuan',
                            data: [65, 79, 80, 71, 76, 85, 90, 85, 95, 105, 110, 115],
                            borderColor: '#2196f3',
                            backgroundColor: 'rgba(33, 150, 243, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Uji Tanaman',
                            data: [25, 29, 40, 51, 26, 25, 30, 35, 45, 55, 60, 65],
                            borderColor: '#00ACC1',
                            backgroundColor: 'rgba(3, 169, 244, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Vaksinasi Hewan',
                            data: [50, 54, 65, 76, 51, 50, 55, 60, 70, 80, 85, 90],
                            borderColor: '#FF6B6B',
                            backgroundColor: 'rgba(0, 188, 212, 0.1)',
                            tension: 0.4
                        }
                    ]
                },
                pengajuan: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Izin',
                        data: [40, 44, 55, 66, 41, 40, 45, 50, 60, 70, 75, 80],
                        borderColor: '#4bc0c0',
                        backgroundColor: 'rgba(0, 150, 136, 0.1)',
                        tension: 0.4
                    }]
                },
                survey: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Survey Data',
                        data: [20, 25, 30, 35, 28, 32, 38, 42, 45, 48, 52, 55],
                        borderColor: '#ff5722',
                        backgroundColor: 'rgba(255, 87, 34, 0.1)',
                        tension: 0.4
                    }]
                }
            },
            2025: {
                all: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                            label: 'Hama dan Penyakit',
                            data: [85, 79, 100, 101, 76, 75],
                            borderColor: '#e91e63',
                            backgroundColor: 'rgba(233, 30, 99, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Alat Perikanan',
                            data: [65, 69, 80, 91, 66, 65],
                            borderColor: '#9c27b0',
                            backgroundColor: 'rgba(156, 39, 176, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Alat Pertanian',
                            data: [55, 59, 70, 81, 56, 55],
                            borderColor: '#9966FF',
                            backgroundColor: 'rgba(103, 58, 183, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Alat Ketahanan Pangan',
                            data: [45, 49, 60, 71, 46, 45],
                            borderColor: '#FFCD56',
                            backgroundColor: 'rgba(63, 81, 181, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Bantuan',
                            data: [75, 89, 90, 81, 86, 95],
                            borderColor: '#2196f3',
                            backgroundColor: 'rgba(33, 150, 243, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Uji Tanaman',
                            data: [35, 39, 50, 61, 36, 35],
                            borderColor: '#00ACC1',
                            backgroundColor: 'rgba(3, 169, 244, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Vaksinasi Hewan',
                            data: [60, 64, 75, 86, 61, 60],
                            borderColor: '#FF6B6B',
                            backgroundColor: 'rgba(0, 188, 212, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Izin',
                            data: [50, 54, 65, 76, 51, 50],
                            borderColor: '#4bc0c0',
                            backgroundColor: 'rgba(0, 150, 136, 0.1)',
                            tension: 0.4
                        }
                    ]
                }
            }
        };

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
                            ticks: {
                                color: '#ffffff'
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