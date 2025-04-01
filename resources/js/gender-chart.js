// Data untuk diagram
const genderctx = document.getElementById('genderChart').getContext('2d');
const genderChart = new Chart(genderctx, {
type: 'doughnut',
data: {
    labels: ['Laki-laki', 'Perempuan'],
    datasets: [{
    data: [52, 48],
    backgroundColor: [
        '#3b82f6', // Biru untuk laki-laki
        '#ec4899'  // Pink untuk perempuan
    ],
    borderWidth: 0,
    cutout: '70%',
    radius: '90%'
    }]
},
options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
    legend: {
        display: false // Nonaktifkan legend default
    },
    tooltip: {
        callbacks: {
        label: function(context) {
            return `${context.label}: ${context.raw}%`;
        }
        }
    }
    },
    animation: {
    animateScale: true,
    animateRotate: true
    }
}
});