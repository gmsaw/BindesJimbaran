@extends('layouts.dasboard-layout')

@section('maincontent')

  <!-- Main Content Area -->
  <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8">

      <!-- Header Halaman -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Menu Utama Kependudukan Krama Adat</h1>
        <p class="text-gray-600">Pilih salah satu menu di bawah ini untuk memulai.</p>
      </div>

      <!-- Grid Kartu Menu -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Card 1: Pipil Kulawarga (KK Adat) -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300">
          <div class="bg-blue-50 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-blue-800"><i class="fas fa-users mr-2"></i>Pipil Kulawarga (KK Adat)</h3>
          </div>
          {{-- PERBAIKAN: Padding diubah dari p-4 menjadi p-6 --}}
          <div class="p-6 flex flex-col justify-between h-auto">
            <p class="text-gray-600 mb-4 text-sm">
              Kelola daftar Kartu Keluarga Adat, lihat detail anggota, edit data, dan cetak Pipil Kulawarga lengkap.
            </p>
            <a href="{{ route('kependudukan.index', ['krama_request' => 'krama_adat']) }}" class="w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
              Masuk
            </a>
          </div>
        </div>

        <!-- Card 2: Kartu Tanda Krama (Perorangan) -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
          <div class="bg-green-50 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-green-800"><i class="fas fa-id-card mr-2"></i>Kartu Tanda Krama</h3>
          </div>
          {{-- PERBAIKAN: Padding diubah dari p-4 menjadi p-6 --}}
          <div class="p-6 flex flex-col justify-between h-auto">
            <p class="text-gray-600 mb-4 text-sm">
              Fitur untuk mencetak kartu tanda krama perorangan (seperti KTP Adat) untuk setiap individu yang terdaftar.
            </p>
            <a href="{{ route('penduduk.index', ['krama_request' => 'krama_adat']) }}" class="w-full text-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
              Masuk
            </a>
          </div>
        </div>

        <!-- Card 3: Fitur Baru (Laporan) -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
          <div class="bg-purple-50 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-purple-800"><i class="fas fa-chart-pie mr-2"></i>Laporan & Statistik</h3>
          </div>
          {{-- PERBAIKAN: Padding diubah dari p-4 menjadi p-6 --}}
          <div class="p-6 flex flex-col justify-between h-auto">
            <p class="text-gray-600 mb-4 text-sm">
              Menampilkan laporan demografi, rekapitulasi jumlah penduduk, dan statistik kependudukan adat lainnya.
            </p>
            <button class="w-full px-4 py-2 bg-gray-400 text-white rounded-md cursor-not-allowed" disabled>
              Segera Hadir
            </button>
          </div>
        </div>

      </div>
    </div>
  </main>

@endsection
