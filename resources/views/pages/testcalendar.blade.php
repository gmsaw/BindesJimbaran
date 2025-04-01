<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Kependudukan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    [x-cloak] { display: none !important; }
    .chart-container {
      position: relative;
      height: 300px;
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen p-6">
  <div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Dashboard Data Kependudukan</h1>
      <p class="text-gray-600">Statistik Demografi Terkini</p>
    </div>

    <!-- Grid Container -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      
      <!-- Card 1: Grafik Jenis Kelamin -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Distribusi Jenis Kelamin</h2>
          <div class="chart-container">
            <canvas id="genderChart"></canvas>
          </div>
          <div class="mt-6 flex justify-center space-x-6">
            <div class="flex items-center">
              <div class="w-4 h-4 bg-blue-500 rounded-full mr-2"></div>
              <span>Laki-laki (52%)</span>
            </div>
            <div class="flex items-center">
              <div class="w-4 h-4 bg-pink-500 rounded-full mr-2"></div>
              <span>Perempuan (48%)</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2: Grafik Pertumbuhan Penduduk -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Pertumbuhan Penduduk</h2>
          <div class="chart-container">
            <canvas id="growthChart"></canvas>
          </div>
          <div class="mt-6 flex justify-center space-x-6">
            <div class="flex items-center">
              <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
              <span>Jumlah Penduduk (juta)</span>
            </div>
            <div class="flex items-center">
              <div class="w-4 h-4 bg-purple-500 rounded-full mr-2"></div>
              <span>Pertumbuhan Tahunan (%)</span>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Tabel Data -->
    <div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden">
      <div class="p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Data Kependudukan 2019-2023</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Penduduk (juta)</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laki-laki</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perempuan</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertumbuhan</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">2023</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">275.6</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">143.3</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">132.3</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-green-500 font-medium">1.12%</td>
              </tr>
              <!-- Data lainnya -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Inisialisasi Chart setelah DOM siap
    document.addEventListener('DOMContentLoaded', function() {
      // Grafik Jenis Kelamin
      const genderCtx = document.getElementById('genderChart').getContext('2d');
      new Chart(genderCtx, {
        type: 'doughnut',
        data: {
          labels: ['Laki-laki', 'Perempuan'],
          datasets: [{
            data: [52, 48],
            backgroundColor: ['#3b82f6', '#ec4899'],
            borderWidth: 0,
            cutout: '70%'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return `${context.label}: ${context.raw}%`;
                }
              }
            }
          }
        }
      });

      // Grafik Pertumbuhan Penduduk
      const growthCtx = document.getElementById('growthChart').getContext('2d');
      new Chart(growthCtx, {
        type: 'line',
        data: {
          labels: ['2019', '2020', '2021', '2022', '2023'],
          datasets: [
            {
              label: 'Jumlah Penduduk',
              data: [266.8, 269.6, 271.3, 272.5, 275.6],
              borderColor: '#10b981',
              backgroundColor: 'rgba(16, 185, 129, 0.1)',
              borderWidth: 3,
              tension: 0.3,
              yAxisID: 'y'
            },
            {
              label: 'Pertumbuhan',
              data: [0.98, 1.05, 0.63, 1.15, 1.12],
              borderColor: '#8b5cf6',
              backgroundColor: 'rgba(139, 92, 246, 0.1)',
              borderWidth: 3,
              borderDash: [5, 5],
              tension: 0.3,
              yAxisID: 'y1'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            mode: 'index',
            intersect: false
          },
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: function(context) {
                  let label = context.dataset.label || '';
                  if (label) label += ': ';
                  if (context.datasetIndex === 0) {
                    label += context.raw.toFixed(1) + ' juta';
                  } else {
                    label += context.raw.toFixed(2) + '%';
                  }
                  return label;
                }
              }
            }
          },
          scales: {
            y: {
              type: 'linear',
              display: true,
              position: 'left',
              title: {
                display: true,
                text: 'Jumlah Penduduk (juta)',
                color: '#10b981'
              },
              grid: { drawOnChartArea: false }
            },
            y1: {
              type: 'linear',
              display: true,
              position: 'right',
              title: {
                display: true,
                text: 'Pertumbuhan (%)',
                color: '#8b5cf6'
              },
              grid: { drawOnChartArea: false },
              min: 0
            },
            x: { grid: { display: false } }
          }
        }
      });
    });
  </script>
</body>
</html>