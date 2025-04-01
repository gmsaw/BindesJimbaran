<div class="bg-white rounded-2xl shadow-xl overflow-hidden min-w-[350px] max-w-4xl">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
      <div class="flex justify-between items-start">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Pertumbuhan Penduduk</h1>
          <p class="text-gray-600">Data jumlah penduduk 2015-2023</p>
        </div>
        <div class="flex space-x-2">
          <button class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium">Tahunan</button>
          <button class="px-3 py-1 hover:bg-gray-100 rounded-lg text-sm font-medium">5 Tahun</button>
          <button class="px-3 py-1 hover:bg-gray-100 rounded-lg text-sm font-medium">10 Tahun</button>
        </div>
      </div>
    </div>
    
    <!-- Chart Container -->
    <div class="p-6">
      <div class="relative h-80">
        <canvas id="populationChart"></canvas>
      </div>
      
      <!-- Legend -->
      <div class="mt-6 flex justify-center space-x-8">
        <div class="flex items-center">
          <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
          <span class="text-sm font-medium">Jumlah Penduduk (ribu)</span>
        </div>
        <div class="flex items-center">
          <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
          <span class="text-sm font-medium">Pertumbuhan (%)</span>
        </div>
      </div>
    </div>
    
    <!-- Data Table -->
    <div class="px-6 pb-6">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Penduduk</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertumbuhan</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepadatan</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr>
              <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">2023</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">275.6 ribu</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-green-500 font-medium">1.12%</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">145/km²</td>
            </tr>
            <tr>
              <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">2022</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">272.5 ribu</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-green-500 font-medium">1.15%</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">143/km²</td>
            </tr>
            <!-- Data lainnya bisa ditambahkan di sini -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    // Data untuk grafik
    const years = ['2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023'];
    const populationData = [255.7, 259.1, 262.0, 264.2, 266.8, 269.6, 271.3, 272.5, 275.6];
    const growthData = [1.25, 1.33, 1.12, 0.84, 0.98, 1.05, 0.63, 1.15, 1.12];

    const populationctx = document.getElementById('populationChart').getContext('2d');
    const populationChart = new Chart(populationctx, {
      type: 'line',
      data: {
        labels: years,
        datasets: [
          {
            label: 'Jumlah Penduduk (juta)',
            data: populationData,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.05)',
            borderWidth: 3,
            tension: 0.3,
            fill: true,
            yAxisID: 'y'
          },
          {
            label: 'Pertumbuhan (%)',
            data: growthData,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.05)',
            borderWidth: 3,
            tension: 0.3,
            borderDash: [5, 5],
            yAxisID: 'y1'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index',
          intersect: false,
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: function(context) {
                let label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                if (context.datasetIndex === 0) {
                  label += context.raw.toFixed(1) + ' juta';
                } else {
                  label += context.raw.toFixed(2) + '%';
                }
                return label;
              }
            }
          },
          legend: {
            display: false
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
              color: '#3b82f6'
            },
            grid: {
              drawOnChartArea: false
            }
          },
          y1: {
            type: 'linear',
            display: true,
            position: 'right',
            title: {
              display: true,
              text: 'Pertumbuhan (%)',
              color: '#10b981'
            },
            grid: {
              drawOnChartArea: false
            },
            min: 0,
            max: 2
          },
          x: {
            grid: {
              display: false
            }
          }
        },
        elements: {
          point: {
            radius: 4,
            hoverRadius: 6,
            backgroundColor: '#fff',
            borderWidth: 2
          }
        }
      }
    })
    ;
  </script>