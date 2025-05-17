@extends('layouts.dasboard-layout')

@section('maincontent')
    <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Data Banjar {{ $banjar->nama_banjar }}</h1>
                    <p class="text-gray-600">{{ $banjar->alamat_sekretariat }}</p>
                </div>

                <!-- Filter Section -->
                <div class="w-full md:w-auto flex flex-col md:flex-row gap-3">
                    <!-- Search Box -->
                    <form method="GET" action="{{ route('KKQ.index') }}" class="flex flex-1">
                        <input type="hidden" name="banjar" value="{{ $banjar->kode_banjar }}">
                        <div class="relative w-full md:w-[400px] lg:w-[500px]">  <!-- Sesuaikan ukuran sesuai kebutuhan -->
                            <input
                                type="text"
                                name="search"
                                placeholder="Cari No KK/Nama/NIK..."
                                value="{{ $searchTerm }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500"
                            >

                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-md"
                        >
                            Cari
                        </button>
                        @if($searchTerm)
                            <a
                                href="{{ route('KKQ.index', ['banjar' => $banjar->kode_banjar]) }}"
                                class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md flex items-center"
                            >
                                <i class="fas fa-times mr-1"></i> Reset
                            </a>
                        @endif
                    </form>

                    <!-- Banjar Dropdown -->
                    <div class="relative flex-1 md:w-48">
                        <select
                            id="banjar-select"
                            class="w-full appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            onchange="window.location.href = '{{ route('KKQ.index') }}?banjar=' + this.value + '&search={{ $searchTerm }}'"
                        >
                            @foreach($allBanjar as $b)
                                <option
                                    value="{{ $b->kode_banjar }}"
                                    {{ $b->kode_banjar == $banjar->kode_banjar ? 'selected' : '' }}
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

                    <!-- Export Button -->
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center justify-center">
                        <i class="fas fa-file-export mr-2"></i> Export
                    </button>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Total KK</h3>
                    <p class="text-2xl font-bold">{{ $banjar->kartuKeluargas->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Total Anggota</h3>
                    <p class="text-2xl font-bold">{{ $data->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Laki-laki</h3>
                    <p class="text-2xl font-bold">{{ $data->where('jenis_kelamin', 'L')->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Perempuan</h3>
                    <p class="text-2xl font-bold">{{ $data->where('jenis_kelamin', 'P')->count() }}</p>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                @if($searchTerm)
                    <div class="px-4 py-2 bg-blue-50 border-b border-blue-100">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Menampilkan hasil pencarian: <strong>"{{ $searchTerm }}"</strong>
                            <span class="text-gray-600 ml-2">({{ $data->count() }} hasil ditemukan)</span>
                        </p>
                    </div>
                @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No KK</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">JK</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TTL</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($data as $index => $item)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-500 text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 whitespace-nowrap font-mono text-sm">{{ $item->nik }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $item->nama_lengkap }}</div>
                                    <div class="text-gray-500 text-xs">{{ $item->pekerjaan }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap font-mono text-sm">{{ $item->no_kk }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-500 text-center">
                                    {{ $item->jenis_kelamin == 'L' ? 'L' : 'P' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm">
                                        <div>{{ $item->tempat_lahir }}</div>
                                        <div class="text-gray-500">{{ $item->tanggal_lahir->format('d-m-Y') }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm">
                                        <div>{{ $item->status_hubungan }}</div>
                                        <div class="text-gray-500">{{ $item->status_perkawinan }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <div class="flex justify-end space-x-2">
                                        @if($item->status_hubungan == "Kepala Keluarga")
                                            <a href="{{ route('KKQFam.print', $item->no_kk) }}"
                                               class="text-red-600 hover:text-red-900 p-1"
                                               title="Cetak Kartu Keluarga">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('KKQ.print', $item->nik) }}"
                                           class="text-blue-600 hover:text-blue-900 p-1"
                                           title="Cetak Kartu">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <a href="#"
                                           class="text-green-600 hover:text-green-900 p-1"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-4 text-center text-gray-500">
                                    @if($searchTerm)
                                        Tidak ditemukan data dengan pencarian "{{ $searchTerm }}"
                                    @else
                                        Tidak ada data yang tersedia
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
                {{-- $data->links() --}}
            </div>
        </div>
    </div>
@endsection

{{--@extends('layouts.dasboard-layout')--}}

{{--@section('maincontent')--}}


{{--    <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">--}}
{{--        <!-- Header Section -->--}}
{{--        <div class="flex justify-between items-center mb-6">--}}
{{--            <div>--}}
{{--                <p class="text-gray-600">{{ $banjar->alamat_sekretariat }}</p>--}}
{{--            </div>--}}
{{--            <div class="flex space-x-2">--}}
{{--                <!-- Search Box -->--}}
{{--                <form method="GET" action="{{ route('banjar.index') }}" class="flex">--}}
{{--                    <input type="hidden" name="banjar" value="{{ $banjar->kode_banjar }}">--}}
{{--                    <div class="relative">--}}
{{--                        <input--}}
{{--                            type="text"--}}
{{--                            name="search"--}}
{{--                            placeholder="Cari No KK..."--}}
{{--                            value="{{ request('search') }}"--}}
{{--                            class="pl-8 pr-4 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500"--}}
{{--                        >--}}
{{--                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">--}}
{{--                            <i class="fas fa-search text-gray-400"></i>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <button--}}
{{--                        type="submit"--}}
{{--                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-md"--}}
{{--                    >--}}
{{--                        Cari--}}
{{--                    </button>--}}
{{--                    @if(request('search'))--}}
{{--                        <a--}}
{{--                            href="{{ route('banjar.index', ['banjar' => $banjar->kode_banjar]) }}"--}}
{{--                            class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md flex items-center"--}}
{{--                        >--}}
{{--                            <i class="fas fa-times mr-1"></i> Reset--}}
{{--                        </a>--}}
{{--                    @endif--}}
{{--                </form>--}}

{{--                <!-- Dropdown Banjar -->--}}
{{--                <div class="relative">--}}
{{--                    <!-- ... (kode dropdown banjar yang sudah ada) ... -->--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="container mx-auto px-4 py-8">--}}
{{--            <!-- Header -->--}}
{{--            <div class="flex justify-between items-center mb-6">--}}
{{--                <div>--}}
{{--                    <h1 class="text-2xl font-bold text-gray-800">Data Banjar {{ $banjar->nama_banjar }}</h1>--}}
{{--                    <p class="text-gray-600">{{ $banjar->alamat_sekretariat }}</p>--}}
{{--                </div>--}}
{{--                <div class="flex space-x-2">--}}
{{--                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">--}}
{{--                        <i class="fas fa-file-export mr-2"></i> Export--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <!-- Stats -->--}}
{{--            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">--}}
{{--                <div class="bg-white p-4 rounded-lg shadow">--}}
{{--                    <h3 class="text-gray-500 text-sm">Total KK</h3>--}}
{{--                    <p class="text-2xl font-bold">{{ $banjar->kartuKeluargas->count() }}</p>--}}
{{--                </div>--}}
{{--                <div class="bg-white p-4 rounded-lg shadow">--}}
{{--                    <h3 class="text-gray-500 text-sm">Total Anggota</h3>--}}
{{--                    <p class="text-2xl font-bold">{{ $data->count() }}</p>--}}
{{--                </div>--}}
{{--                <div class="bg-white p-4 rounded-lg shadow">--}}
{{--                    <h3 class="text-gray-500 text-sm">Laki-laki</h3>--}}
{{--                    <p class="text-2xl font-bold">{{ $data->where('jenis_kelamin', 'L')->count() }}</p>--}}
{{--                </div>--}}
{{--                <div class="bg-white p-4 rounded-lg shadow">--}}
{{--                    <h3 class="text-gray-500 text-sm">Perempuan</h3>--}}
{{--                    <p class="text-2xl font-bold">{{ $data->where('jenis_kelamin', 'P')->count() }}</p>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <!-- Data Table -->--}}
{{--            <div class="bg-white rounded-xl shadow-lg overflow-hidden">--}}
{{--                <div class="overflow-x-auto">--}}
{{--                    <table class="min-w-full divide-y divide-gray-200 text-sm">--}}
{{--                        <thead class="bg-gray-50">--}}
{{--                        <tr>--}}
{{--                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No</th>--}}
{{--                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">NIK</th>--}}
{{--                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Nama Lengkap</th>--}}
{{--                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">No KK</th>--}}
{{--                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">JK</th>--}}
{{--                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">TTL</th>--}}
{{--                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Status</th>--}}
{{--                            <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody class="bg-white divide-y divide-gray-200">--}}
{{--                        @foreach($data as $index => $item)--}}
{{--                            <tr>--}}
{{--                                <td class="px-3 py-2 whitespace-nowrap text-gray-500 text-center">{{ $index + 1 }}</td>--}}
{{--                                <td class="px-3 py-2 whitespace-nowrap font-mono text-xs">{{ $item->nik }}</td>--}}
{{--                                <td class="px-3 py-2">--}}
{{--                                    <div class="font-medium text-gray-900 truncate">{{ $item->nama_lengkap }}</div>--}}
{{--                                    <div class="text-gray-500 text-xs truncate">{{ $item->pekerjaan }}</div>--}}
{{--                                </td>--}}
{{--                                <td class="px-3 py-2 whitespace-nowrap font-mono text-xs">{{ $item->no_kk }}</td>--}}
{{--                                <td class="px-3 py-2 whitespace-nowrap text-gray-500 text-center">--}}
{{--                                    {{ $item->jenis_kelamin == 'L' ? 'L' : 'P' }}--}}
{{--                                </td>--}}
{{--                                <td class="px-3 py-2 whitespace-nowrap">--}}
{{--                                    <div class="text-xs">--}}
{{--                                        <div class="truncate">{{ $item->tempat_lahir }}</div>--}}
{{--                                        <div>{{ $item->tanggal_lahir->format('d-m-Y') }}</div>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                <td class="px-3 py-2 whitespace-nowrap">--}}
{{--                                    <div class="text-xs">--}}
{{--                                        <div class="truncate">{{ $item->status_hubungan }}</div>--}}
{{--                                        <div class="text-gray-500">{{ $item->status_perkawinan }}</div>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                <td class="px-3 py-2 whitespace-nowrap text-right">--}}
{{--                                    <div class="flex space-x-1 justify-end">--}}
{{--                                        <a href="{{ route('banjar.print', $item->nik) }}"--}}
{{--                                           class="text-blue-600 hover:text-blue-900 p-1"--}}
{{--                                           title="Cetak">--}}
{{--                                            <i class="fas fa-print text-xs"></i>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <!-- Pagination -->--}}
{{--            <div class="mt-4">--}}
{{--                --}}{{-- $data->links() --}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}
