@extends('layouts.dasboard-layout')

@section('maincontent')

  <!-- Main Content Area -->
  <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">

    <!-- Di dalam div main content Anda -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <!-- Card 1 -->
      <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div class="bg-red-100 px-4 py-3 border-b border-gray-200">
          <h3 class="font-semibold text-red-800">Krama Adat</h3>
        </div>
        <div class="p-6 flex flex-col justify-between h-auto">
          <p class="text-gray-600 mb-4">
            Daftar penduduk Krama Adat dan cetak kartu.
          </p>
          <a href="{{ route('cetakkartuopsi') }}" class="w-full text-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
            Masuk
          </a>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div class="bg-yellow-100 px-4 py-3 border-b border-gray-200">
          <h3 class="font-semibold text-yellow-800">Krama Tamiu</h3>
        </div>
        <div class="p-6 flex flex-col justify-between h-auto">
          <p class="text-gray-600 mb-4">
            Daftar penduduk Krama Tamiu dan cetak kartu.
          </p>
          <a href="{{ route('cetakkartuopsikramatamiu') }}" class="w-full text-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
            Masuk
          </a>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div class="bg-gray-300 px-4 py-3 border-b border-gray-200">
          <h3 class="font-semibold text-white-800">Tamiu</h3>
        </div>
        <div class="p-6 flex flex-col justify-between h-auto">
          <p class="text-gray-600 mb-4">
            Daftar penduduk Tamiu dan cetak kartu.
          </p>
          <a href="{{ route('cetakkartuopsitamiu') }}" class="w-full text-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
            Masuk
          </a>
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
