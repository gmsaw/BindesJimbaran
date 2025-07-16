@extends('layouts.dasboard-layout')

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
          <p class="text-gray-600">Lihat dan kelola semua surat yang pernah diterbitkan.</p>
        </div>
        <div class="w-full md:w-auto flex flex-col md:flex-row gap-3">
          <a href="{{ route('surat.cerai.create') }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
          </a>
          <form method="GET" action="{{ route('surat.arsip.cerai') }}" class="flex flex-1">
            <div class="relative w-full md:w-80">
              <input type="text" name="search" placeholder="Cari No. Surat, Kode, atau Nama..." value="{{ $searchTerm }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-l-md">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-search text-gray-400"></i></div>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-md">Cari</button>
            @if($searchTerm)
              <a href="{{ route('surat.arsip.cerai') }}" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md flex items-center"><i class="fas fa-times mr-1"></i> Reset</a>
            @endif
          </form>
        </div>
      </div>

      <!-- Data Table -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Surat</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pasangan (Purusa & Pradana)</th>
              <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($arsip_surat as $surat)
              <tr>
                <td class="px-4 py-3 whitespace-nowrap font-mono text-sm">{{ $surat->nomor_surat }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</td>
                <td class="px-4 py-3">
                  <div class="font-medium text-gray-900">{{ $surat->purusa_nama }}</div>
                  <div class="text-gray-500 text-xs">& {{ $surat->pradana_nama }}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-right">
                  <a href="{{ route('surat.cerai.preview', $surat->id) }}" target="_blank" class="text-blue-600 hover:text-blue-900 p-1" title="Lihat & Cetak Surat">
                    <i class="fas fa-print"></i> Lihat
                  </a>

                  <a href="{{ route('surat.cerai.delete', $surat->id) }}" class="text-red-600 hover:text-red-900 p-1" title="Hapus Surat">
                    <i class="fas fa-trash"></i> Hapus
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada data surat yang ditemukan.</td></tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-4">
        {{ $arsip_surat->appends(request()->query())->links() }}
      </div>
    </div>
  </div>
@endsection
