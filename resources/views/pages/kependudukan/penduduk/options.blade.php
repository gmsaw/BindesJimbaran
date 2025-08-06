@extends('layouts.dasboard-layout')

@section('maincontent')

  <!-- Main Content Area -->
  <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8">

      <!-- Header Halaman -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Pilih Jenis Input Penduduk</h1>
        <p class="text-gray-600">Pilih salah satu menu di bawah ini untuk input data penduduk.</p>
      </div>

      <!-- Grid Kartu Menu -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Card 1: Pipil Kulawarga (KK Adat) -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300">
          <div class="bg-blue-50 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-blue-800"><i class="fas fa-users mr-2"></i>Domisili Bali</h3>
          </div>
          {{-- PERBAIKAN: Padding diubah dari p-4 menjadi p-6 --}}
          <div class="p-6 flex flex-col justify-between h-auto">
            <p class="text-gray-600 mb-4 text-sm">
              Input data penduduk untuk yang berdomisili di Bali.
            </p>
            <a href="{{ route('penduduk.create.bali') }}" class="w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
              Masuk
            </a>
          </div>
        </div>

        <!-- Card 2: Kartu Tanda Krama (Perorangan) -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
          <div class="bg-green-50 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-green-800"><i class="fas fa-id-card mr-2"></i>Domisili Luar Bali</h3>
          </div>
          {{-- PERBAIKAN: Padding diubah dari p-4 menjadi p-6 --}}
          <div class="p-6 flex flex-col justify-between h-auto">
            <p class="text-gray-600 mb-4 text-sm">
              Input data penduduk untuk yang berdomisili di luar Bali.
            </p>
            <a href="{{ route('penduduk.create.luar-bali') }}" class="w-full text-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
              Masuk
            </a>
          </div>
        </div>
      </div>
    </div>
  </main>

@endsection
