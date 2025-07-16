@extends('layouts.dasboard-layout')

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8">
      <!-- Header Halaman Detail -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
          <div class="text-sm text-gray-600 mt-1 space-x-4">
                        <span>
                            <i class="fas fa-user-friends mr-1"></i>
                            Kepala Keluarga: <strong>{{ $kk_adat->kepalaKeluarga?->masterAdat?->masterIndividu?->nama_lengkap ?? 'N/A' }}</strong>
                        </span>
            <span>
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Banjar: <strong>{{ $kk_adat->banjar?->nama_banjar ?? 'N/A' }}</strong>
                        </span>
          </div>
        </div>
        <div class="mt-4 sm:mt-0">
          <a href="{{ route('kependudukan.index', ['banjar' => $kk_adat->kode_banjar_fk]) }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar KK
          </a>
        </div>
      </div>

      <!-- Tabel Anggota Keluarga -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIKA</th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Hubungan Adat</th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pekerjaan</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            {{-- Looping melalui relasi 'anggota' dari objek $kk_adat --}}
            @forelse($kk_adat->anggota as $anggota)
              <tr>
                <td class="px-4 py-3 whitespace-nowrap text-gray-500 text-center">{{ $loop->iteration }}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div class="font-medium text-gray-900">{{ $anggota->masterAdat?->masterIndividu?->nama_lengkap ?? 'N/A' }}</div>
                  <div class="text-gray-500 text-xs">{{ $anggota->masterAdat?->masterIndividu?->nik_nasional ?? 'NIK tidak ada' }}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap font-mono text-sm">{{ $anggota->masterAdat?->nika ?? 'N/A' }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-800">{{ $anggota->masterAdat?->masterIndividu?->jenis_kelamin ?? 'N/A' }}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $anggota->status_hubungan_adat == 'kepala_keluarga' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ str_replace('_', ' ', $anggota->status_hubungan_adat) }}
                                    </span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-800">{{ $anggota->masterAdat?->masterIndividu?->pekerjaan ?? 'N/A' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                  Tidak ada data anggota keluarga yang ditemukan untuk KK ini.
                  t/d>
              </tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
