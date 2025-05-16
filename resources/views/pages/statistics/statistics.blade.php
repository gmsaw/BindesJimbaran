@extends('layouts.dasboard-layout')

@section('maincontent')

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">

        <!-- Di dalam div main content Anda -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Card 1 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-blue-100 px-4 py-3 border-b border-gray-200">
                    <h3 class="font-semibold text-blue-800">Krama Adat</h3>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 mb-4">Daftar penduduk Krama Adat dan cetak kartu.</p>
                    <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <a href="/statistics/krama-adat">Enter</a>
                    </button>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-green-100 px-4 py-3 border-b border-gray-200">
                    <h3 class="font-semibold text-green-800">Krama Tamiu</h3>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 mb-4">Daftar penduduk Krama Tamiu dan cetak kartu.</p>
{{--                    <button class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">--}}
                    <button class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition" disabled>
                        <a href="#">Enter (soon)</a>
                    </button>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-purple-100 px-4 py-3 border-b border-gray-200">
                    <h3 class="font-semibold text-purple-800">Tamiu</h3>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 mb-4">Daftar penduduk Tamiu dan cetak kartu</p>
                    {{--                    <button class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">--}}
                    <button class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition" disabled>
                        <a href="#">Enter (soon)</a>
                    </button>
                </div>
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
