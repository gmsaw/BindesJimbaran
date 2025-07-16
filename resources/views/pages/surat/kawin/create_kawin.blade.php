{{\Carbon\Carbon::setLocale('id')}}

@extends('layouts.dasboard-layout')

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
      {{-- Form utama untuk menyimpan data --}}
      <form action="{{ route('surat.kawin.store') }}" method="POST">
        @csrf

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
          <div><h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1></div>
          <div class="mt-4 sm:mt-0 flex gap-x-2">
            <a href="{{ route('surat.indexMain') }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm">Batal</a>
            <a href="{{ route('surat.kawin.penomoran.index') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm"><i class="fas fa-database mr-2"></i>Penomoran</a>
            <a href="{{ route('surat.arsip.kawin') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm"><i class="fas fa-database mr-2"></i>Arsip</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm"><i class="fas fa-save mr-2"></i>Simpan Surat</button>
          </div>
        </div>

        @if(session('success'))
          <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p>{{ session('success') }}</p></div>
        @endif
        {{-- ... notifikasi error lainnya ... --}}

        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Surat</h2>

          {{-- Toggle Penomoran --}}
          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Jenis Penomoran</label>
            <div class="flex items-center space-x-4">
              <label class="flex items-center">
                {{-- PERBAIKAN: Menggunakan window.location.href untuk navigasi GET --}}
                <input type="radio" name="penomoran" value="otomatis" {{ request('penomoran', 'otomatis') == 'otomatis' ? 'checked' : '' }}
                onchange="window.location.href = '{{ route('surat.kawin.create') }}?penomoran=otomatis'">
                <span class="ml-2">Otomatis</span>
              </label>
              <label class="flex items-center">
                <input type="radio" name="penomoran" value="manual" {{ request('penomoran') == 'manual' ? 'checked' : '' }}
                onchange="window.location.href = '{{ route('surat.kawin.create') }}?penomoran=manual'">
                <span class="ml-2">Manual</span>
              </label>
            </div>
          </div>

          {{-- Opsi Otomatis --}}
          @if(request('penomoran', 'otomatis') == 'otomatis')
            <div class="bg-gray-50 p-4 rounded-md border">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                  <label class="block text-sm font-medium">1. Pilih Kode Surat</label>
                  <select name="kode_surat" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                    @foreach ($all_kode_surat as $kode)
                      <option value="{{ $kode->kode_surat }}" {{ request('kode_surat') == $kode->kode_surat ? 'selected' : '' }}>{{ $kode->kode_surat }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium">2. Pilih Tanggal</label>
                  <input type="date" name="tahun_surat_input" value="{{ request('tahun_surat_input', date('Y-m-d')) }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                {{-- Tombol ini akan me-reload halaman dengan parameter kode dan tahun --}}
                <button type="submit" formaction="{{ route('surat.kawin.create') }}" formmethod="GET" class="px-4 py-2 bg-gray-200 rounded-md text-sm">
                  Cek Nomor Berikutnya
                </button>
              </div>

              @if($nomor_surat_otomatis)
                <div class="mt-4">
                  <label class="block text-sm font-medium">Nomor Surat Otomatis yang Disarankan</label>
                  <input type="text" name="nomor_surat_otomatis" value="{{ $nomor_surat_otomatis }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md bg-yellow-50">
                </div>
              @endif
            </div>
          @endif

          {{-- Opsi Manual --}}
          @if(request('penomoran') == 'manual')
            <div class="mt-4">
              <label class="block text-sm font-medium">Nomor Surat Lengkap (Manual)</label>
              <input type="text" name="nomor_surat_manual" value="{{ old('nomor_surat_manual') }}" placeholder="Contoh: 001/IW-DAJ/IV/2025" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
          @endif


          <hr class="my-4">

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium">Tanggal Kawin</label><input type="date" name="tanggal_kawin" value="{{ old('tanggal_kawin', \Carbon\Carbon::now()->toDateString()) }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            <div><label class="block text-sm font-medium">Tanggal Surat (Resmi)</label><input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', \Carbon\Carbon::now()->toDateString()) }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            <div class="md:col-span-2"><label class="block text-sm font-medium">Lingkungan (Tempat Pengumuman)</label><select name="kode_banjar_lingkungan" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach ($all_banjar as $banjar)<option value="{{ $banjar->kode_banjar }}">{{ $banjar->nama_banjar }}</option>@endforeach</select></div>
          </div>
        </div>

        {{-- Data Calon Pasangan --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Bagian Purusa (Suami) -->
          <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">A. Purusa (Suami)</h2>
            <div class="mb-4">
              <label class="block text-sm font-medium">Cari Data (Nama/NIKA)</label>
              <div class="flex gap-x-2">
                <input type="text" name="search_suami" value="{{ request('search_suami') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                {{-- Tombol ini akan submit form dengan method GET ke route 'create' --}}
                <button type="submit" formaction="{{ route('surat.kawin.create') }}" formmethod="GET" class="mt-1 px-4 py-2 bg-gray-200 rounded-md">Cari</button>
              </div>
            </div>
            @include('pages.surat.partials.form_calon', ['prefix' => 'purusa', 'data' => $suami])
          </div>

          <!-- Bagian Pradana (Istri) -->
          <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">B. Pradana (Istri)</h2>
            <div class="mb-4">
              <label class="block text-sm font-medium">Cari Data (Nama/NIKA)</label>
              <div class="flex gap-x-2">
                <input type="text" name="search_istri" value="{{ request('search_istri') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                <button type="submit" formaction="{{ route('surat.kawin.create') }}" formmethod="GET" class="mt-1 px-4 py-2 bg-gray-200 rounded-md">Cari</button>
              </div>
            </div>
            @include('pages.surat.partials.form_calon', ['prefix' => 'pradana', 'data' => $istri])
          </div>
        </div>

        <!-- Form Pemuput & Penanda Tangan -->
        <div class="bg-white p-6 rounded-xl shadow-lg mt-8">
          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Tambahan</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium">Pemuput Karya</label><input type="text" name="pemuput_karya" value="{{ old('pemuput_karya') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            <div><label class="block text-sm font-medium">Penanda Tangan (Bendesa)</label><select name="id_bendesa_fk" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach ($bendesa_aktif as $bendesa)<option value="{{ $bendesa->id }}">{{ $bendesa->nama_bendesa }}</option>@endforeach</select></div>
            <div><label class="block text-sm font-medium">Saksi 1</label><input type="text" name="saksi_1" value="{{ old('saksi_1') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            <div><label class="block text-sm font-medium">Saksi 2</label><input type="text" name="saksi_2" value="{{ old('saksi_2') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
