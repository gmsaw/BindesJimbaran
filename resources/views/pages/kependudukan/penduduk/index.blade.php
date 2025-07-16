@extends('layouts.dasboard-layout')

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
          @if($selectedBanjar)
            <p class="text-gray-600">Menampilkan data untuk Banjar: {{ $allBanjar->firstWhere('kode_banjar', $selectedBanjar)->nama_banjar ?? '' }}</p>
          @else
            <p class="text-gray-600">Menampilkan data dari semua banjar.</p>
          @endif
        </div>

        <!-- Filter Section -->
        <div class="w-full md:w-auto flex flex-col md:flex-row gap-3">
          <!-- Search Box -->
          <form method="GET" action="{{ route('penduduk.index') }}" class="flex flex-1">
            <input type="hidden" name="krama_request" value="{{ $krama_request }}">
            @if($selectedBanjar)
              <input type="hidden" name="banjar" value="{{ $selectedBanjar }}">
            @endif
            <div class="relative w-full md:w-[400px] lg:w-[500px]">
              <input
                type="text"
                name="search"
                placeholder="Cari NIKA/NIK/Nama..."
                value="{{ $searchTerm }}"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500"
              >
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
              </div>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-md">
              Cari
            </button>
            @if($searchTerm || $selectedBanjar)
              <a href="{{ route('penduduk.index', ['krama_request' => 'krama_tamiu']) }}" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md flex items-center">
                <i class="fas fa-times mr-1"></i> Reset
              </a>
            @endif
          </form>

          <!-- Banjar Dropdown -->
          <div class="relative flex-1 md:w-48">
            <select
              id="banjar-select"
              class="w-full appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              onchange="window.location.href = '{{ route('penduduk.index') }}?'+ 'krama_request=' + '{{$krama_request}}' + '&banjar=' + this.value"
            >
              <option value="">Semua Banjar</option>
              @foreach($allBanjar as $b)
                <option
                  value="{{ $b->kode_banjar }}"
                  {{ $b->kode_banjar == $selectedBanjar ? 'selected' : '' }}
                >
                  {{ $b->nama_banjar }}
                </option>
              @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
          <h3 class="text-gray-500 text-sm">Total Krama Adat</h3>
          <p class="text-2xl font-bold">{{ $daftar_penduduk->total() }}</p>
        </div>
      </div>

      <!-- Data Table -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        @if($searchTerm)
          <div class="px-4 py-2 bg-blue-50 border-b border-blue-100">
            <p class="text-sm text-blue-800">
              <i class="fas fa-info-circle mr-1"></i>
              Menampilkan hasil pencarian untuk: <strong>"{{ $searchTerm }}"</strong>
            </p>
          </div>
        @endif
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIKA</th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK Nasional</th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Banjar</th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status di Banjar</th>
              <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($daftar_penduduk as $penduduk)
              <tr>
                <td class="px-4 py-3 whitespace-nowrap text-gray-500 text-center">{{ $daftar_penduduk->firstItem() + $loop->index }}</td>
                <td class="px-4 py-3">
                  <div class="font-medium text-gray-900">{{ $penduduk->masterIndividu->nama_lengkap ?? 'N/A' }}</div>
                  <div class="text-gray-500 text-xs">{{ $penduduk->masterIndividu->jenis_kelamin ?? '' }}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap font-mono text-sm">{{ $penduduk->nika }}</td>
                <td class="px-4 py-3 whitespace-nowrap font-mono text-sm">{{ $penduduk->masterIndividu->nik_nasional ?? 'N/A' }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $penduduk->banjar->nama_banjar ?? 'N/A' }}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $penduduk->status_di_banjar == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ str_replace('_', ' ', $penduduk->status_di_banjar) }}
                                    </span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-right">
                  <div class="flex justify-end space-x-2">
                    {{-- Link ke route cetak kartu perorangan (akan dibuat) --}}
                    <a href="{{ route('penduduk.print.card', ['nika' => $penduduk->nika, 'krama_request'=>$krama_request]) }}" {{-- Contoh: route('penduduk.print.card', ['id_identitas_adat' => $penduduk->id_identitas_adat]) --}}
                    class="text-red-600 hover:text-red-900 p-1"
                       title="Cetak Kartu Tanda Krama">
                      <i class="fas fa-id-card"></i>
                    </a>
                    {{-- Link ke halaman edit --}}
                    {{-- Cara terbaik adalah mengedit melalui KK-nya --}}
                    <a href="{{ route('penduduk.edit', ['nika' => $penduduk->nika, 'krama_request'=>$krama_request]) }}" {{-- Contoh: route('kependudukan.edit', ['npk' => $penduduk->keanggotaan->first()->npk_fk ?? '']) --}}
                    class="text-green-600 hover:text-green-900 p-1"
                       title="Edit Data">
                      <i class="fas fa-edit"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-4 py-4 text-center text-gray-500">
                  Tidak ada data krama adat yang ditemukan.
                </td>
              </tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-4">
        {{ $daftar_penduduk->appends(request()->query())->links() }}
      </div>
    </div>
  </div>
@endsection
