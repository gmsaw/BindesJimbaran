{{\Carbon\Carbon::setLocale('id')}}

@extends('layouts.dasboard-layout')

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-5xl">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
          <p class="text-gray-600">Tambah, edit, atau hapus kode dan nomor urut untuk kategori Surat Kawin.</p>
        </div>
        <div class="mt-4 sm:mt-0">
          <a href="{{ route('surat.indexMain') }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Menu Surat
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

      <!-- Form Tambah Kode Surat Baru -->
      <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Tambah Kode Surat Baru</h2>
        <form action="{{ route('surat.ilikita_utsaha.penomoran.store') }}" method="POST">
          @csrf
          <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
              <label class="block text-sm font-medium">Kode Surat</label>
              <input type="text" name="kode_surat" value="{{ old('kode_surat') }}" placeholder="Contoh: SPK-DAJ" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div>
              <label class="block text-sm font-medium">Tahun</label>
              <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" placeholder="Contoh: 2025" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            {{-- PERBAIKAN: Menambahkan input bulan --}}
            <div>
              <label class="block text-sm font-medium">Bulan</label>
              <select name="bulan" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" required>
                @for ($i = 1; $i <= 12; $i++)
                  <option value="{{ $i }}" {{ old('bulan', date('n')) == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                @endfor
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium">Nomor Terakhir</label>
              <input type="number" name="nomor_terakhir" value="{{ old('nomor_terakhir', 0) }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 h-fit">
              <i class="fas fa-plus mr-1"></i> Tambah
            </button>
          </div>
        </form>
      </div>

      <!-- Tabel Daftar Kode Surat -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Surat</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor Terakhir</th>
              <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($counters as $counter)
              <tr>
                <td class="px-4 py-3 whitespace-nowrap font-mono text-sm">{{ $counter->kode_surat }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $counter->tahun }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $counter->bulan }}</td>
                <td class="px-4 py-3">
                  <form action="{{ route('surat.ilikita_utsaha.penomoran.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="kode_surat" value="{{ $counter->kode_surat }}">
                    <input type="hidden" name="tahun" value="{{ $counter->tahun }}">
                    <input type="hidden" name="bulan" value="{{ $counter->bulan }}">
                    <div class="flex items-center gap-x-2">
                      <input type="number" name="nomor_terakhir" value="{{ $counter->nomor_terakhir }}" class="w-24 px-2 py-1 border border-gray-300 rounded-md">
                      <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs rounded-md hover:bg-green-600">Update</button>
                    </div>
                  </form>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-right">
                  <form action="{{ route('surat.ilikita_utsaha.penomoran.destroy') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kode surat ini?');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="kode_surat" value="{{ $counter->kode_surat }}">
                    <input type="hidden" name="tahun" value="{{ $counter->tahun }}">
                    <input type="hidden" name="bulan" value="{{ $counter->bulan }}">
                    <button type="submit" class="text-red-600 hover:text-red-900 p-1" title="Hapus">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada data penomoran.</td></tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-4">
        {{ $counters->links() }}
      </div>
    </div>
  </div>
@endsection
