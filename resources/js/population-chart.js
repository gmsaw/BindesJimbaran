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