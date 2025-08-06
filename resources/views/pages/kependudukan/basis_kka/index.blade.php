{{-- File: resources/views/pages/kependudukan/index.blade.php --}}

{{--@dd($krama_request)--}}

@extends('layouts.dasboard-layout')

@section('maincontent')
    <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    {{-- Mengambil nama banjar dari koleksi $allBanjar berdasarkan kode yang dipilih --}}
                    <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
                    <p class="text-gray-600">{{ $allBanjar->firstWhere('kode_banjar', $selectedBanjar)->alamat_sekretariat ?? 'Alamat tidak tersedia' }}</p>
                </div>

                <!-- Filter Section -->
                <div class="w-full md:w-auto flex flex-col md:flex-row gap-3">
                    <!-- Search Box -->
                    {{-- Menggunakan route baru, misal: 'kependudukan.index' --}}
                    <form method="GET" action="{{ route('kependudukan.index') }}" class="flex flex-1">
                        <input type="hidden" name="krama_request" value="{{ $krama_request }}">
                        <input type="hidden" name="banjar" value="{{ $selectedBanjar }}">
                        <div class="relative w-full md:w-[400px] lg:w-[500px]">
                            <input
                                type="text"
                                name="search"
                                placeholder="Cari NPK/NKK/Nama Kepala Keluarga..."
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
                        @if($searchTerm)
                            {{-- Link reset sekarang menargetkan banjar yang sedang aktif --}}
                            <a href="{{ route('kependudukan.index', ['krama_request' => $krama_request, 'banjar' => $selectedBanjar]) }}" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md flex items-center">
                              <i class="fas fa-times mr-1"></i> Reset
                            </a>
                        @endif
                    </form>

                    <!-- Banjar Dropdown -->
                    <div class="relative flex-1 md:w-48">
                        <select
                            id="banjar-select"
                            class="w-full appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            onchange="window.location.href = '{{ route('kependudukan.index') }}?'+ 'krama_request=' + '{{$krama_request}}' + '&banjar=' + this.value"
                        >
                            @foreach($allBanjar as $b)
                                <option
                                    value="{{ $b->kode_banjar }}"
                                    {{ $b->kode_banjar == $selectedBanjar ? 'selected' : '' }}
                                >
                                    {{ $b->kode_banjar }} - {{ $b->nama_banjar }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Export Button (Fungsionalitas belum diimplementasikan) -->
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center justify-center">
                      @if($selectedBanjar)
                        <a href="{{ route('kependudukan.bulk.print', ['kode_banjar' => $selectedBanjar]) }}"
                           target="_blank" {{-- Membuka di tab baru --}}
                           class="bg-green-600 hover:bg-green-700 text-white rounded-md flex items-center justify-center">
                          <i class="fas fa-print mr-2"></i> Cetak Massal
                        </a>
                      @else
                        <button class="bg-gray-400 text-white rounded-md flex items-center justify-center cursor-not-allowed" disabled>
                          <i class="fas fa-print mr-2"></i> Pilih Banjar
                        </button>
                      @endif


                    </button>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Total KK di Banjar Ini</h3>
                    {{-- Menggunakan total dari objek paginasi, lebih efisien --}}
                    <p class="text-2xl font-bold">{{ $daftar_kk->total() }}</p>
                </div>
                {{-- Catatan: Stat Total Anggota, Laki-laki, dan Perempuan sengaja dihilangkan dari sini --}}
                {{-- karena akan sangat berat untuk dihitung di halaman index. Sebaiknya --}}
                {{-- ditampilkan di dashboard terpisah atau dihitung per halaman. --}}
            </div>

            <!-- Data Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                @if($searchTerm)
                    <div class="px-4 py-2 bg-blue-50 border-b border-blue-100">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Menampilkan hasil pencarian untuk: <strong>"{{ $searchTerm }}"</strong>
                            <span class="text-gray-600 ml-2">({{ $daftar_kk->total() }} hasil ditemukan)</span>
                        </p>
                    </div>
                @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NPK</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepala Keluarga (Pengarep)</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NKK Nasional</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Tinggal</th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Looping menggunakan variabel $daftar_kk --}}
                        @forelse($daftar_kk as $kk)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-500 text-center">{{ $daftar_kk->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-3 whitespace-nowrap font-mono text-sm">{{ $kk->npk }}</td>
                                <td class="px-4 py-3">
                                    {{-- Mengakses nama kepala keluarga melalui relasi yang sudah di-load --}}
                                    <div class="font-medium text-gray-900">{{ $kk->kepalaKeluarga->masterAdat->masterIndividu->nama_lengkap ?? 'Data Kepala Keluarga tidak ada' }}</div>
                                    <div class="text-gray-500 text-xs">{{ $kk->kepalaKeluarga->masterAdat->nika ?? '' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap font-mono text-sm">{{ $kk->nkk }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ str_replace('_', ' ', $kk->status_tinggal) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <div class="flex justify-end space-x-2">
                                        {{-- Link ke route cetak kartu keluarga dengan parameter NPK --}}
                                        <a href="{{ route('kependudukan.print.family', ['npk' => $kk->npk, 'krama_request'=>$krama_request]) }}"
                                           class="text-red-600 hover:text-red-900 p-1"
                                           title="Cetak Kartu Keluarga">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        {{-- Link ke halaman detail (jika ada) --}}
                                        <a href="{{ route('kependudukan.show', ['npk' => $kk->npk, 'krama_request'=>$krama_request]) }}" {{-- Contoh: route('kependudukan.show', $kk->npk) --}}
                                        class="text-blue-600 hover:text-blue-900 p-1"
                                           title="Lihat Detail Anggota">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        {{-- Link ke halaman edit (jika ada) --}}
                                        <a href="{{ route('kependudukan.edit', ['npk' => $kk->npk, 'krama_request'=>$krama_request]) }}" {{-- Contoh: route('kependudukan.edit', $kk->npk) --}}
                                        class="text-green-600 hover:text-green-900 p-1"
                                           title="Edit KK">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                    @if($searchTerm)
                                        Tidak ditemukan data dengan pencarian "{{ $searchTerm }}" di banjar ini.
                                    @else
                                        Tidak ada data KK Adat yang tersedia di banjar ini.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{-- Menampilkan link paginasi untuk $daftar_kk --}}
                {{ $daftar_kk->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
