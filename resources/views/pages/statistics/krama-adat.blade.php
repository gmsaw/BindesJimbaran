@extends('layouts.dasboard-layout')

@section('maincontent')

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">


        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <!-- Card 1 - KK-Q -->
                <div class="border border-blue-200 rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="bg-blue-50 px-4 py-3 border-b border-blue-100">
                        <h3 class="font-semibold text-blue-800">KK-Q</h3>
                    </div>
                    <div class="p-4">
                        <p class="text-gray-600 mb-4">Query data basis Kartu Keluarga</p>
                        <div class="text-3xl font-bold text-blue-600 mb-4"><br><br></div>
                        <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            <a href="/KKQ/">Lihat Detail</a>
                        </button>
                    </div>
                </div>

                <!-- Card 2 - KTP-Q -->
                <div class="border border-green-200 rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="bg-green-50 px-4 py-3 border-b border-green-100">
                        <h3 class="font-semibold text-green-800">NIK-Q</h3>
                    </div>
                    <div class="p-4">
                        <p class="text-gray-600 mb-4">Query data basis NIK</p>
                        <div class="text-3xl font-bold text-green-600 mb-4"><br><br></div>
                        <button class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                            Lihat Detail
                        </button>
                    </div>
                </div>

                <!-- Card 3 - Br-Q-->
                <div class="border border-purple-200 rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="bg-purple-50 px-4 py-3 border-b border-purple-100">
                        <h3 class="font-semibold text-purple-800">Br-Q</h3>
                    </div>
                    <div class="p-4">
                        <p class="text-gray-600 mb-4">Query data basis Banjar-banjar</p>
                        <div class="text-3xl font-bold text-purple-600 mb-4"><br><br></div>
                        <button class="w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                            Lihat Detail
                        </button>
                    </div>
                </div>

                <!-- Card 4 - Desa-Q -->
                <div class="border border-purple-200 rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="bg-purple-50 px-4 py-3 border-b border-purple-100">
                        <h3 class="font-semibold text-orange-800">Desa-Q</h3>
                    </div>
                    <div class="p-4">
                        <p class="text-gray-600 mb-4">Query data basis Desa</p>
                        <div class="text-3xl font-bold text-orange-600 mb-4"><br><br></div>
                        <button class="w-full px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition">
                            Lihat Detail
                        </button>
                    </div>
                </div>

                <!-- Card 5 - Coming Soon -->
                <div class="border border-gray-200 rounded-lg bg-white shadow-sm">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">Fitur Baru</h3>
                        <span class="text-xs bg-white text-gray-600 px-2 py-1 rounded">Coming Soon</span>
                    </div>
                    <div class="p-4">
                        <p class="text-gray-600 mb-4">Fitur sedang dalam pengembangan</p>
                        <div class="text-3xl font-bold text-gray-400 mb-4">-</div>
                        <button class="w-full px-4 py-2 bg-gray-400 text-white rounded-md cursor-not-allowed" disabled>
                            Segera Hadir
                        </button>
                    </div>
                </div>
            </div>

            <!-- Konten lain di dashboard -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Statistik Pendaftaran</h2>
                <br><br>
                    <div class="flex justify-between items-center">
                        <h1 class=" text-gray-200">COMING SOON</h1>
                    </div>
                <br><br>
            </div>
        </div>



    </main>

    <script>
        // Jika butuh interaksi sederhana
        document.addEventListener('DOMContentLoaded', function() {
            // Contoh interaksi klik
            document.querySelectorAll('button').forEach(button => {
                button.addEventListener('click', function() {
                    if (this.disabled) {
                        alert('Fitur akan segera hadir!');
                        return;
                    }
                    const cardTitle = this.closest('.border').querySelector('h3').textContent;
                    console.log(`Klik card: ${cardTitle}`);
                    // Tambahkan aksi sesuai kebutuhan
                });
            });
        });
    </script>
@endsection
