<div class="bg-white rounded-2xl shadow-xl overflow-hidden w-[420px] ml-5 max-w-md">
    <!-- Header -->
    <div class="p-5 text-black">
        <h1 class="text-2xl font-bold">Distribusi Jenis Kelamin</h1>
        </div>
        
        <!-- Chart Container -->
        <div class="p-6">
        <div class="relative h-64">
            <canvas id="genderChart"></canvas>
        </div>
        
        <!-- Legend -->
        <div class="mt-6 flex justify-center space-x-6">
            <div class="flex items-center">
            <div class="w-4 h-4 bg-[#3b82f6] rounded-full mr-2"></div>
            <span>Laki-laki (52%)</span>
            </div>
            <div class="flex items-center">
            <div class="w-4 h-4 bg-[#ec4899] rounded-full mr-2"></div>
            <span>Perempuan (48%)</span>
            </div>
        </div>
    </div>
</div>    

    <script>
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
</script>