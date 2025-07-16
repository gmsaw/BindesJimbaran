@extends('layouts.dasboard-layout')

@section('maincontent')

  <!-- Main Content Area -->
  <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8">

      <!-- Header Halaman -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
        <p class="text-gray-600">Pilih salah satu jenis krama di bawah ini untuk memulai proses input data.</p>
      </div>

      <!-- Grid Kartu Menu -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Card 1: Krama Adat (Merah) -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300">
          <div class="bg-red-50 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-red-800"><i class="fas fa-user-tag mr-2"></i>Input Krama Adat</h3>
          </div>
          <div class="p-6 flex flex-col justify-between h-auto">
            <p class="text-gray-600 mb-4 text-sm">
              Gunakan menu ini untuk menambah data baru beserta seluruh anggotanya yang berstatus sebagai Krama Adat Menetap.
            </p>
            {{-- Link ini mengarah ke form input KK yang sudah Anda buat --}}
              <a href="{{ route('kependudukan.create', ['data_input' => "krama_adat"]) }}" class="w-full text-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                Mulai Input
              </a>

          </div>
        </div>

        <!-- Card 2: Krama Tamiu (Kuning) -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
          <div class="bg-yellow-50 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-yellow-800"><i class="fas fa-user-tag mr-2"></i>Input Krama Tamiu</h3>
          </div>
          <div class="p-6 flex flex-col justify-between h-auto">
            <p class="text-gray-600 mb-4 text-sm">
              Untuk mendaftarkan individu atau keluarga yang tinggal sementara namun memiliki keterikatan adat (Krama Tamiu).
            </p>
              <a href="{{ route('kependudukan.create', ['data_input' => "krama_tamiu"]) }}" class="w-full text-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-red-yellow transition">
                Mulai Input
              </a>

          </div>
        </div>

        <!-- Card 3: Tamiu (Hitam/Abu-abu) -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
          <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800"><i class="fas fa-user-clock mr-2"></i>Input Tamiu</h3>
          </div>
          <div class="p-6 flex flex-col justify-between h-auto">
            <p class="text-gray-600 mb-4 text-sm">
              Untuk mendaftarkan individu atau tamu yang tinggal sementara di wewidangan Desa Adat tanpa ikatan krama.
            </p>
            <a href="{{ route('kependudukan.create', ['data_input' => "tamiu"]) }}" class="w-full text-center px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-red-gray transition">
              Mulai Input
            </a>
          </div>
        </div>

      </div>
    </div>
  </main>

@endsection
