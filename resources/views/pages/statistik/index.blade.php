@extends('layouts.dasboard-layout')

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
          <p class="text-gray-600">Lihat rekapitulasi data krama berdasarkan berbagai kategori.</p>
        </div>
        <div class="w-full md:w-auto flex flex-col md:flex-row gap-3">
          <!-- Filter Section -->
          <form method="GET" action="{{ route('statistik.index') }}" class="flex items-end gap-2">
            <div>
              <label for="status_adat" class="block text-sm font-medium text-gray-700">Filter Status Adat</label>
              <select name="status_adat" id="status_adat" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="">Semua Status</option>
                <option value="krama_adat" {{ $statusAdatFilter == 'krama_adat' ? 'selected' : '' }}>Krama Adat</option>
                <option value="krama_tamiu" {{ $statusAdatFilter == 'krama_tamiu' ? 'selected' : '' }}>Krama Tamiu</option>
                <option value="tamiu" {{ $statusAdatFilter == 'tamiu' ? 'selected' : '' }}>Tamiu</option>
              </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Terapkan</button>
          </form>
          <!-- Tombol Export -->
          <a href="{{ route('statistik.export', ['status_adat' => $statusAdatFilter]) }}" class="self-end px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
            <i class="fas fa-file-csv mr-2"></i>Export CSV
          </a>
        </div>
      </div>

      <!-- Looping untuk setiap tabel statistik -->
      @foreach ($statistik as $namaTabel => $dataTabel)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
          <h3 class="text-lg font-semibold text-gray-900 p-4 border-b">{{ $namaTabel }}</h3>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                @foreach ($banjars as $banjar)
                  <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $banjar->nama_banjar }}</th>
                @endforeach
                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200 text-sm">
              @foreach ($dataTabel as $kategori => $counts)
                <tr class="{{ $loop->last ? 'bg-gray-100 font-bold' : '' }}">
                  <td class="px-4 py-3 whitespace-nowrap font-medium">{{ ucfirst(str_replace('_', ' ', $kategori)) }}</td>
                  @foreach ($banjars as $banjar)
                    <td class="px-4 py-3 whitespace-nowrap text-center">{{ $counts[$banjar->kode_banjar] ?? 0 }}</td>
                  @endforeach
                  <td class="px-4 py-3 whitespace-nowrap text-center">{{ $counts['Total'] }}</td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endforeach

    </div>
  </div>
@endsection
