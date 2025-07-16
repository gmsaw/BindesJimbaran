@extends('layouts.dasboard-layout')

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
          <p class="text-gray-600">Atur nomor urut terakhir untuk NPK di setiap banjar.</p>
        </div>
        <div class="mt-4 sm:mt-0">
          <a href="{{ route('kependudukan.create') }}" {{-- Sesuaikan dengan route dashboard Anda --}}
          class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
          </a>
        </div>
      </div>

      {{-- Notifikasi --}}
      @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p>{{ session('success') }}</p></div>
      @endif
      @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
          <p><strong>Terdapat kesalahan:</strong></p>
          <ul class="list-disc ml-5 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Banjar</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Banjar</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor Terakhir Digunakan</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor Berikutnya</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($banjars as $banjar)
              @php
                $nomor_terakhir = $banjar->nomor_terakhir ?? 0;
              @endphp
              <tr>
                <td class="px-4 py-3 whitespace-nowrap font-mono text-sm">{{ $banjar->kode_banjar }}</td>
                <td class="px-4 py-3 whitespace-nowrap font-semibold text-sm">{{ $banjar->nama_banjar }}</td>
                <td class="px-4 py-3">
                  <form action="{{ route('penomoran.npk.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="kode_banjar_fk" value="{{ $banjar->kode_banjar }}">
                    <div class="flex items-center gap-x-2">
                      <input type="number" name="nomor_terakhir" value="{{ $nomor_terakhir }}" class="w-24 px-2 py-1 border border-gray-300 rounded-md">
                      <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs rounded-md hover:bg-green-600">Update</button>
                    </div>
                  </form>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-lg font-bold text-blue-600">
                  {{ str_pad($nomor_terakhir + 1, 4, '0', STR_PAD_LEFT) }}
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Data banjar tidak ditemukan.</td></tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
@endsection
