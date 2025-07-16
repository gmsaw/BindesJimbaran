@extends('layouts.dasboard-layout')

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
      <!-- Header -->
      <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
        <p class="text-gray-600 mt-1">Pilih jenis data dan filter yang diinginkan, lalu klik tombol export.</p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-lg">
        <form action="{{ route('export.generate') }}" method="GET" x-data="{ jenisData: 'penduduk' }">
          <div class="space-y-6">

            <!-- Pilihan Jenis Data -->
            <div>
              <label class="block text-sm font-medium text-gray-700">1. Pilih Jenis Data yang Akan Diekspor</label>
              <select name="jenis_data" x-model="jenisData" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="penduduk">Data Penduduk (Perorangan)</option>
                <option value="kk_adat">Data Kartu Keluarga Adat</option>
              </select>
            </div>

            <!-- Filter Umum -->
            <div class="border-t pt-6 space-y-6">
              <h3 class="text-lg font-medium text-gray-900">Filter Data</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="banjar" class="block text-sm font-medium text-gray-700">Filter per Banjar</label>
                  <select name="banjar" id="banjar" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Semua Banjar</option>
                    @foreach ($allBanjar as $banjar)
                      <option value="{{ $banjar->kode_banjar }}">{{ $banjar->nama_banjar }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <label for="status_adat" class="block text-sm font-medium text-gray-700">Filter Status Adat</label>
                  <select name="status_adat" id="status_adat" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Semua Status</option>
                    <option value="krama_adat">Krama Adat</option>
                    <option value="krama_tamiu">Krama Tamiu</option>
                    <option value="tamiu">Tamiu</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Filter Khusus Penduduk -->
            <div x-show="jenisData === 'penduduk'" x-transition class="border-t pt-6">
              <div class="relative flex items-start">
                <div class="flex h-5 items-center">
                  <input id="hanya_kepala_keluarga" name="hanya_kepala_keluarga" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                </div>
                <div class="ml-3 text-sm">
                  <label for="hanya_kepala_keluarga" class="font-medium text-gray-700">Hanya ekspor Kepala Keluarga (Pengarep)</label>
                  <p class="text-gray-500">Centang untuk hanya menyertakan kepala keluarga di dalam file ekspor.</p>
                </div>
              </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end pt-6 border-t">
              <button type="submit" class="w-full sm:w-auto flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-file-csv mr-2"></i>
                Export ke CSV
              </button>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
