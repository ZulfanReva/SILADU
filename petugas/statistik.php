<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Pengaduan</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .timeline {
      list-style: none;
      padding-left: 0;
    }
    .timeline li {
      padding: 10px 0;
      position: relative;
    }
    .timeline li::before {
      content: '\2022';
      color: grey;
      font-weight: bold;
      display: inline-block; 
      width: 1em;
      margin-left: -1em;
    }
    .completed::before {
      color: green;
    }
    .pending::before {
      color: orange;
    }
    .rejected::before {
      color: red;
    }
  </style>
</head>
<body class="p-4">
  <div class="container">
    <h2 class="mb-4">Tracking Pengaduan</h2>
    <ul class="timeline">
      <li class="completed">Diajukan: 2025-02-15</li>
      <li class="completed">Direspon Petugas: 2025-02-15</li>
      <li class="completed">Status: Disetujui</li>
      <li class="completed">Selesai: 2025-02-15</li>
    </ul>

    <h3 class="mt-5">Statistik Pengaduan</h3>
    <div class="row">
      <div class="col-md-6">
        <canvas id="statusChart"></canvas>
      </div>
      <div class="col-md-6">
        <canvas id="bulananChart"></canvas>
      </div>
    </div>
  </div>

  <script>
    // Contoh data dummy, ganti dengan fetch dari PHP nanti
    const statusData = {
      labels: ['Pending', 'Disetujui', 'Ditolak'],
      datasets: [{
        label: 'Status Pengaduan',
        data: [3, 6, 2],
        backgroundColor: ['orange', 'green', 'red']
      }]
    };

    const bulananData = {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'],
      datasets: [{
        label: 'Jumlah Pengaduan',
        data: [1, 8, 2, 5, 3],
        backgroundColor: 'blue'
      }]
    };

    const configStatus = {
      type: 'pie',
      data: statusData
    };

    const configBulanan = {
      type: 'bar',
      data: bulananData
    };

    new Chart(document.getElementById('statusChart'), configStatus);
    new Chart(document.getElementById('bulananChart'), configBulanan);
  </script>
</body>
</html>
