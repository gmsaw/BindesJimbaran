{{-- File: resources/views/components/widget/population-chart.blade.php --}}
@props(['data'])

<div class="bg-white p-6 rounded-2xl shadow-xl h-full">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Populasi berdasarkan Status Adat</h3>
    <div class="relative h-80">
        <canvas id="populationChart"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const populationData = @json($data);
    const populationCtx = document.getElementById('populationChart').getContext('2d');
    new Chart(populationCtx, {
        type: 'bar', // Tipe chart bar
        data: {
            labels: populationData.labels,
            datasets: [{
                label: 'Jumlah Penduduk',
                data: populationData.data,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(234, 179, 8, 0.7)',
                    'rgba(168, 85, 247, 0.7)',
                    'rgba(239, 68, 68, 0.7)',
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(234, 179, 8, 1)',
                    'rgba(168, 85, 247, 1)',
                    'rgba(239, 68, 68, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>