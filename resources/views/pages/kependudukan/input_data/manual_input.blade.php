@extends('layouts.dasboard-layout')

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">

      <!-- Form Pencarian Kelihan Natah (Terpisah) -->
      <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border">
        <h3 class="font-medium text-gray-700 mb-2">Pencarian Kelihan Natah</h3>
        <p class="text-xs text-gray-500 mb-2">Gunakan form ini untuk memfilter pilihan pada dropdown "Kelihan Natah" di dalam form utama.</p>
        <form action="{{ route('kependudukan.create') }}" method="GET">
          @if(request('anggota')) <input type="hidden" name="anggota" value="{{ request('anggota') }}"> @endif
          <div class="flex items-end gap-2">
            <div class="flex-1">
              <label for="kelihan_search" class="text-sm">Cari berdasarkan Nama/NIK/NIKA/Banjar</label>
              <input type="text" id="kelihan_search" name="kelihan_search" value="{{ $kelihanSearchTerm }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md h-fit">Cari</button>
            @if($kelihanSearchTerm)
              <a href="{{ route('kependudukan.create', ['anggota' => request('anggota')]) }}" class="px-4 py-2 bg-gray-200 rounded-md h-fit">Reset</a>
            @endif
          </div>
        </form>
        @if($kelihanSearchTerm)
          <p class="text-xs text-blue-700 mt-2">Menampilkan {{ $all_krama_adat->count() }} hasil pencarian untuk "{{ $kelihanSearchTerm }}".</p>
        @endif
      </div>

      <!-- FORM UTAMA UNTUK MENYIMPAN DATA -->
      <form action="{{ route('kependudukan.store', ['krama_input_request' => 'krama_adat']) }}" method="POST">
        @csrf

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
          <div><h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1></div>
          <div class="mt-4 sm:mt-0 flex gap-x-2">
            <a href="{{ route('penomoran.npk.index') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center"><i class="fas fa-database mr-2"></i>Penomoran NPK</a>
            <a href="{{ route('kependudukan.create.options') }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm flex items-center"><i class="fas fa-times mr-2"></i>Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center"><i class="fas fa-save mr-2"></i>Simpan KK Baru</button>
          </div>
        </div>

        @if ($errors->any())
          <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">Terdapat kesalahan validasi:</p>
            <ul class="list-disc ml-5 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
          </div>
        @endif

        <!-- Kartu 1: Data KK Adat -->
        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-id-card mr-2 text-blue-500"></i>Data Kartu Keluarga Adat</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div class="md:col-span-2 bg-blue-50 p-3 rounded-md text-center"><p class="text-sm text-blue-800"><i class="fas fa-info-circle mr-1"></i> NPK akan dibuat secara otomatis.</p></div>
            <div><label for="nkk" class="block text-sm font-medium text-gray-700">NKK Nasional</label><input type="text" id="nkk" name="nkk" value="{{ old('nkk') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></div>
            <div><label for="no_telp" class="block text-sm font-medium text-gray-700">No. Telepon</label><input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></div>
            <div><label for="kode_banjar_fk" class="block text-sm font-medium text-gray-700">Banjar Adat <span class="text-red-500">*</span></label><select id="kode_banjar_fk" name="kode_banjar_fk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach ($all_banjar as $banjar)<option value="{{ $banjar->kode_banjar }}" {{ old('kode_banjar_fk') == $banjar->kode_banjar ? 'selected' : '' }}>{{ $banjar->nama_banjar }}</option>@endforeach</select></div>
            <div><label for="kode_klasifikasi_krama_fk" class="block text-sm font-medium text-gray-700">Klasifikasi Krama <span class="text-red-500">*</span></label><select id="kode_klasifikasi_krama_fk" name="kode_klasifikasi_krama_fk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach ($all_krama as $krama)<option value="{{ $krama->kode_krama }}" {{ old('kode_klasifikasi_krama_fk') == $krama->kode_krama ? 'selected' : '' }}>{{ $krama->krama }}</option>@endforeach</select></div>
            <div><label for="status_adat" class="block text-sm font-medium text-gray-700">Status Adat <span class="text-red-500">*</span></label><select id="status_adat" name="status_adat" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach(['krama_adat','krama_tamiu','tamiu','pindah_keluar_adat','lainnya','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('status_adat') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>@endforeach</select></div>
            <div class="md:col-span-2"><label for="alamat" class="block text-sm font-medium text-gray-700">Alamat KK Adat</label><textarea id="alamat" name="alamat" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">{{ old('alamat') }}</textarea></div>
          </div>
        </div>

        {{-- PERBAIKAN: Bagian Dadia & Penatahan sekarang di dalam kartunya sendiri --}}
        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
          @include('pages.kependudukan.partials.form_dadia_penatahan', ['all_dadia' => $all_dadia, 'all_krama_adat' => $all_krama_adat])
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-user-shield mr-2 text-blue-500"></i>Data Kepala Keluarga (Pengarep)</h2>
          @include('pages.kependudukan.partials.form_individu', ['prefix' => 'kk'])
        </div>

        @for ($i = 0; $i < $jumlah_anggota; $i++)
          <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
              <h2 class="text-lg font-semibold text-gray-800"><i class="fas fa-user-friends mr-2 text-green-500"></i>Data Anggota Keluarga Tambahan #{{ $i + 1 }}</h2>
              <p class="text-xs text-gray-500">Kosongkan NIK jika tidak ingin menambahkan anggota ini.</p>
            </div>
            @include('pages.kependudukan.partials.form_individu', ['prefix' => "anggota[$i]"])
          </div>
        @endfor

        <div class="bg-gray-100 p-4 rounded-lg flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <p class="text-sm font-medium text-gray-700">Jumlah Anggota Tambahan:</p>
            <div class="flex items-center gap-2">
              <a href="{{ route('kependudukan.create', array_merge(request()->query(), ['anggota' => max(0, $jumlah_anggota - 1)])) }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-200 {{ $jumlah_anggota <= 0 ? 'pointer-events-none opacity-50' : '' }}"><i class="fas fa-minus"></i></a>
              <span class="font-bold text-lg text-gray-800 w-8 text-center">{{ $jumlah_anggota }}</span>
              <a href="{{ route('kependudukan.create', array_merge(request()->query(), ['anggota' => min(15, $jumlah_anggota + 1)])) }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-200 {{ $jumlah_anggota >= 15 ? 'pointer-events-none opacity-50' : '' }}"><i class="fas fa-plus"></i></a>
            </div>
          </div>
          <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-2 text-xs rounded max-w-md text-center sm:text-left"><p><strong>Perhatian:</strong> Menambah/mengurangi anggota akan memuat ulang halaman. Gunakan ini sebelum mengisi data.</p></div>
        </div>

        <div class="flex justify-end mt-8"><button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg flex items-center"><i class="fas fa-save mr-2"></i>Simpan Semua Data KK Baru</button></div>
      </form>
    </div>
  </div>
@endsection


{{--@extends('layouts.dasboard-layout')--}}

{{--@section('maincontent')--}}
{{--  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">--}}
{{--    <div class="container mx-auto px-4 py-8 max-w-4xl">--}}

{{--      <!-- Form Pencarian Kelihan Natah (Terpisah) -->--}}
{{--      <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border">--}}
{{--        <h3 class="font-medium text-gray-700 mb-2">Pencarian Kelihan Natah</h3>--}}
{{--        <p class="text-xs text-gray-500 mb-2">Gunakan form ini untuk memfilter pilihan pada dropdown "Kelihan Natah" di dalam form utama.</p>--}}
{{--        <form action="{{ route('kependudukan.create') }}" method="GET">--}}
{{--          @if(request('anggota')) <input type="hidden" name="anggota" value="{{ request('anggota') }}"> @endif--}}
{{--          <div class="flex items-end gap-2">--}}
{{--            <div class="flex-1">--}}
{{--              <label for="kelihan_search" class="text-sm">Cari berdasarkan Nama/NIK/NIKA/Banjar</label>--}}
{{--              <input type="text" id="kelihan_search" name="kelihan_search" value="{{ $kelihanSearchTerm }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">--}}
{{--            </div>--}}
{{--            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md h-fit">Cari</button>--}}
{{--            @if($kelihanSearchTerm)--}}
{{--              <a href="{{ route('kependudukan.create', ['anggota' => request('anggota')]) }}" class="px-4 py-2 bg-gray-200 rounded-md h-fit">Reset</a>--}}
{{--            @endif--}}
{{--          </div>--}}
{{--        </form>--}}
{{--        @if($kelihanSearchTerm)--}}
{{--          <p class="text-xs text-blue-700 mt-2">Menampilkan {{ $all_krama_adat->count() }} hasil pencarian untuk "{{ $kelihanSearchTerm }}".</p>--}}
{{--        @endif--}}
{{--      </div>--}}

{{--      <!-- FORM UTAMA UNTUK MENYIMPAN DATA -->--}}
{{--      <form action="{{ route('kependudukan.store') }}" method="POST">--}}
{{--        @csrf--}}

{{--        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">--}}
{{--          <div><h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1></div>--}}
{{--          <div class="mt-4 sm:mt-0 flex gap-x-2">--}}
{{--            <a href="{{ route('penomoran.npk.index') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center"><i class="fas fa-database mr-2"></i>Penomoran NPK</a>--}}
{{--            <a href="{{ route('kependudukan.index') }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm flex items-center"><i class="fas fa-times mr-2"></i>Batal</a>--}}
{{--            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center"><i class="fas fa-save mr-2"></i>Simpan KK Baru</button>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        @if ($errors->any())--}}
{{--          <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">--}}
{{--            <p class="font-bold">Terdapat kesalahan validasi:</p>--}}
{{--            <ul class="list-disc ml-5 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>--}}
{{--          </div>--}}
{{--        @endif--}}

{{--        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">--}}
{{--          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-id-card mr-2 text-blue-500"></i>Data Kartu Keluarga Adat</h2>--}}
{{--          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">--}}
{{--            <div class="md:col-span-2 bg-blue-50 p-3 rounded-md text-center"><p class="text-sm text-blue-800"><i class="fas fa-info-circle mr-1"></i> NPK akan dibuat secara otomatis.</p></div>--}}
{{--            <div><label for="nkk" class="block text-sm font-medium text-gray-700">NKK Nasional</label><input type="text" id="nkk" name="nkk" value="{{ old('nkk') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></div>--}}
{{--            <div><label for="no_telp" class="block text-sm font-medium text-gray-700">No. Telepon</label><input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></div>--}}
{{--            <div><label for="kode_banjar_fk" class="block text-sm font-medium text-gray-700">Banjar Adat <span class="text-red-500">*</span></label><select id="kode_banjar_fk" name="kode_banjar_fk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach ($all_banjar as $banjar)<option value="{{ $banjar->kode_banjar }}" {{ old('kode_banjar_fk') == $banjar->kode_banjar ? 'selected' : '' }}>{{ $banjar->nama_banjar }}</option>@endforeach</select></div>--}}
{{--            <div><label for="kode_klasifikasi_krama_fk" class="block text-sm font-medium text-gray-700">Klasifikasi Krama <span class="text-red-500">*</span></label><select id="kode_klasifikasi_krama_fk" name="kode_klasifikasi_krama_fk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach ($all_krama as $krama)<option value="{{ $krama->kode_krama }}" {{ old('kode_klasifikasi_krama_fk') == $krama->kode_krama ? 'selected' : '' }}>{{ $krama->krama }}</option>@endforeach</select></div>--}}
{{--            <div><label for="status_adat" class="block text-sm font-medium text-gray-700">Status Adat <span class="text-red-500">*</span></label><select id="status_adat" name="status_adat" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach(['krama_adat','krama_tamiu','tamiu','pindah_keluar_adat','lainnya','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('status_adat') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>@endforeach</select></div>--}}
{{--            <div class="md:col-span-2"><label for="alamat" class="block text-sm font-medium text-gray-700">Alamat KK Adat</label><textarea id="alamat" name="alamat" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">{{ old('alamat') }}</textarea></div>--}}

{{--            @include('pages.kependudukan.partials.form_dadia_penatahan', ['all_dadia' => $all_dadia, 'all_krama_adat' => $all_krama_adat])--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">--}}
{{--          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-user-shield mr-2 text-blue-500"></i>Data Kepala Keluarga (Pengarep)</h2>--}}
{{--          @include('pages.kependudukan.partials.form_individu')--}}
{{--        </div>--}}

{{--        @for ($i = 0; $i < $jumlah_anggota; $i++)--}}
{{--          <div class="bg-white p-6 rounded-xl shadow-lg mb-6">--}}
{{--            <div class="flex justify-between items-center mb-4 border-b pb-2">--}}
{{--              <h2 class="text-lg font-semibold text-gray-800"><i class="fas fa-user-friends mr-2 text-green-500"></i>Data Anggota Keluarga Tambahan #{{ $i + 1 }}</h2>--}}
{{--              <p class="text-xs text-gray-500">Kosongkan NIK jika tidak ingin menambahkan anggota ini.</p>--}}
{{--            </div>--}}
{{--            @include('pages.kependudukan.partials.form_individu', ['prefix' => "anggota[$i]"])--}}
{{--          </div>--}}
{{--        @endfor--}}

{{--        <div class="bg-gray-100 p-4 rounded-lg flex flex-col sm:flex-row items-center justify-between gap-4">--}}
{{--          <div class="flex items-center gap-3">--}}
{{--            <p class="text-sm font-medium text-gray-700">Jumlah Anggota Tambahan:</p>--}}
{{--            <div class="flex items-center gap-2">--}}
{{--              <a href="{{ route('kependudukan.create', array_merge(request()->query(), ['anggota' => max(0, $jumlah_anggota - 1)])) }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-200 {{ $jumlah_anggota <= 0 ? 'pointer-events-none opacity-50' : '' }}"><i class="fas fa-minus"></i></a>--}}
{{--              <span class="font-bold text-lg text-gray-800 w-8 text-center">{{ $jumlah_anggota }}</span>--}}
{{--              <a href="{{ route('kependudukan.create', array_merge(request()->query(), ['anggota' => min(15, $jumlah_anggota + 1)])) }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-200 {{ $jumlah_anggota >= 15 ? 'pointer-events-none opacity-50' : '' }}"><i class="fas fa-plus"></i></a>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--          <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-2 text-xs rounded max-w-md text-center sm:text-left"><p><strong>Perhatian:</strong> Menambah/mengurangi anggota akan memuat ulang halaman. Gunakan ini sebelum mengisi data.</p></div>--}}
{{--        </div>--}}

{{--        <div class="flex justify-end mt-8"><button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg flex items-center"><i class="fas fa-save mr-2"></i>Simpan Semua Data KK Baru</button></div>--}}
{{--      </form>--}}
{{--    </div>--}}
{{--  </div>--}}
{{--@endsection--}}


{{--@extends('layouts.dasboard-layout')--}}

{{--@section('maincontent')--}}
{{--  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">--}}
{{--    <div class="container mx-auto px-4 py-8 max-w-4xl">--}}

{{--      <!-- Form Pencarian Kelihan Natah (Terpisah) -->--}}
{{--      <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border">--}}
{{--        <h3 class="font-medium text-gray-700 mb-2">Pencarian Kelihan Natah</h3>--}}
{{--        <p class="text-xs text-gray-500 mb-2">Gunakan form ini untuk memfilter pilihan pada dropdown "Kelihan Natah" di dalam form utama.</p>--}}
{{--        <form action="{{ route('kependudukan.create') }}" method="GET">--}}
{{--          @if(request('anggota')) <input type="hidden" name="anggota" value="{{ request('anggota') }}"> @endif--}}
{{--          <div class="flex items-end gap-2">--}}
{{--            <div class="flex-1">--}}
{{--              <label for="kelihan_search" class="text-sm">Cari berdasarkan Nama/NIK/NIKA/Banjar</label>--}}
{{--              <input type="text" id="kelihan_search" name="kelihan_search" value="{{ $kelihanSearchTerm }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">--}}
{{--            </div>--}}
{{--            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md h-fit">Cari</button>--}}
{{--            @if($kelihanSearchTerm)--}}
{{--              <a href="{{ route('kependudukan.create', ['anggota' => request('anggota')]) }}" class="px-4 py-2 bg-gray-200 rounded-md h-fit">Reset</a>--}}
{{--            @endif--}}
{{--          </div>--}}
{{--        </form>--}}
{{--        @if($kelihanSearchTerm)--}}
{{--          <p class="text-xs text-blue-700 mt-2">Menampilkan {{ $all_krama_adat->count() }} hasil pencarian untuk "{{ $kelihanSearchTerm }}".</p>--}}
{{--        @endif--}}
{{--      </div>--}}

{{--      <!-- FORM UTAMA UNTUK MENYIMPAN DATA -->--}}
{{--      <form action="{{ route('kependudukan.store') }}" method="POST">--}}
{{--        @csrf--}}

{{--        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">--}}
{{--          <div><h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1></div>--}}
{{--          <div class="mt-4 sm:mt-0 flex gap-x-2">--}}
{{--            <a href="{{ route('penomoran.npk.index') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center"><i class="fas fa-database mr-2"></i>Penomoran NPK</a>--}}
{{--            <a href="{{ route('kependudukan.index') }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm flex items-center"><i class="fas fa-times mr-2"></i>Batal</a>--}}
{{--            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center"><i class="fas fa-save mr-2"></i>Simpan KK Baru</button>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        @if ($errors->any())--}}
{{--          <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">--}}
{{--            <p class="font-bold">Terdapat kesalahan validasi:</p>--}}
{{--            <ul class="list-disc ml-5 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>--}}
{{--          </div>--}}
{{--        @endif--}}

{{--        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">--}}
{{--          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-id-card mr-2 text-blue-500"></i>Data Kartu Keluarga Adat</h2>--}}
{{--          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">--}}
{{--            <div class="md:col-span-2 bg-blue-50 p-3 rounded-md text-center"><p class="text-sm text-blue-800"><i class="fas fa-info-circle mr-1"></i> NPK akan dibuat secara otomatis.</p></div>--}}
{{--            <div><label for="nkk" class="block text-sm font-medium text-gray-700">NKK Nasional</label><input type="text" id="nkk" name="nkk" value="{{ old('nkk') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></div>--}}
{{--            <div><label for="no_telp" class="block text-sm font-medium text-gray-700">No. Telepon</label><input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></div>--}}
{{--            <div><label for="kode_banjar_fk" class="block text-sm font-medium text-gray-700">Banjar Adat <span class="text-red-500">*</span></label><select id="kode_banjar_fk" name="kode_banjar_fk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach ($all_banjar as $banjar)<option value="{{ $banjar->kode_banjar }}" {{ old('kode_banjar_fk') == $banjar->kode_banjar ? 'selected' : '' }}>{{ $banjar->nama_banjar }}</option>@endforeach</select></div>--}}
{{--            <div><label for="kode_klasifikasi_krama_fk" class="block text-sm font-medium text-gray-700">Klasifikasi Krama <span class="text-red-500">*</span></label><select id="kode_klasifikasi_krama_fk" name="kode_klasifikasi_krama_fk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach ($all_krama as $krama)<option value="{{ $krama->kode_krama }}" {{ old('kode_klasifikasi_krama_fk') == $krama->kode_krama ? 'selected' : '' }}>{{ $krama->krama }}</option>@endforeach</select></div>--}}
{{--            <div><label for="status_adat" class="block text-sm font-medium text-gray-700">Status Adat <span class="text-red-500">*</span></label><select id="status_adat" name="status_adat" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach(['krama_adat','krama_tamiu','tamiu','pindah_keluar_adat','lainnya','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('status_adat') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>@endforeach</select></div>--}}
{{--            <div class="md:col-span-2"><label for="alamat" class="block text-sm font-medium text-gray-700">Alamat KK Adat</label><textarea id="alamat" name="alamat" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">{{ old('alamat') }}</textarea></div>--}}

{{--            @include('pages.kependudukan.partials.form_dadia_penatahan', ['all_dadia' => $all_dadia, 'all_krama_adat' => $all_krama_adat])--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">--}}
{{--          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-user-shield mr-2 text-blue-500"></i>Data Kepala Keluarga (Pengarep)</h2>--}}
{{--          @include('pages.kependudukan.partials.form_individu', ['prefix' => 'kk'])--}}
{{--        </div>--}}

{{--        @for ($i = 0; $i < $jumlah_anggota; $i++)--}}
{{--          <div class="bg-white p-6 rounded-xl shadow-lg mb-6">--}}
{{--            <div class="flex justify-between items-center mb-4 border-b pb-2">--}}
{{--              <h2 class="text-lg font-semibold text-gray-800"><i class="fas fa-user-friends mr-2 text-green-500"></i>Data Anggota Keluarga Tambahan #{{ $i + 1 }}</h2>--}}
{{--              <p class="text-xs text-gray-500">Kosongkan NIK jika tidak ingin menambahkan anggota ini.</p>--}}
{{--            </div>--}}
{{--            @include('pages.kependudukan.partials.form_individu', ['prefix' => "anggota[$i]"])--}}
{{--          </div>--}}
{{--        @endfor--}}

{{--        <div class="bg-gray-100 p-4 rounded-lg flex flex-col sm:flex-row items-center justify-between gap-4">--}}
{{--          <div class="flex items-center gap-3">--}}
{{--            <p class="text-sm font-medium text-gray-700">Jumlah Anggota Tambahan:</p>--}}
{{--            <div class="flex items-center gap-2">--}}
{{--              <a href="{{ route('kependudukan.create', array_merge(request()->query(), ['anggota' => max(0, $jumlah_anggota - 1)])) }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-200 {{ $jumlah_anggota <= 0 ? 'pointer-events-none opacity-50' : '' }}"><i class="fas fa-minus"></i></a>--}}
{{--              <span class="font-bold text-lg text-gray-800 w-8 text-center">{{ $jumlah_anggota }}</span>--}}
{{--              <a href="{{ route('kependudukan.create', array_merge(request()->query(), ['anggota' => min(15, $jumlah_anggota + 1)])) }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-200 {{ $jumlah_anggota >= 15 ? 'pointer-events-none opacity-50' : '' }}"><i class="fas fa-plus"></i></a>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--          <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-2 text-xs rounded max-w-md text-center sm:text-left"><p><strong>Perhatian:</strong> Menambah/mengurangi anggota akan memuat ulang halaman. Gunakan ini sebelum mengisi data.</p></div>--}}
{{--        </div>--}}

{{--        <div class="flex justify-end mt-8"><button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg flex items-center"><i class="fas fa-save mr-2"></i>Simpan Semua Data KK Baru</button></div>--}}
{{--      </form>--}}
{{--    </div>--}}
{{--  </div>--}}
{{--@endsection--}}


{{--@extends('layouts.dasboard-layout')--}}

{{--@section('maincontent')--}}
{{--  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">--}}
{{--    <div class="container mx-auto px-4 py-8 max-w-4xl">--}}

{{--      <!-- Header -->--}}
{{--      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">--}}
{{--        <div><h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1><p class="text-gray-600">Isi semua data yang diperlukan di bawah ini.</p></div>--}}
{{--      </div>--}}

{{--       PERBAIKAN: Form Pencarian Kelihan Natah dipindahkan ke sini (di luar form utama)--}}
{{--      <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border">--}}
{{--        <h3 class="font-medium text-gray-700 mb-2">Pencarian Kelihan Natah</h3>--}}
{{--        <p class="text-xs text-gray-500 mb-2">Gunakan form ini untuk memfilter pilihan pada dropdown "Kelihan Natah" di dalam form utama.</p>--}}
{{--        <form action="{{ route('kependudukan.create') }}" method="GET">--}}
{{--           Pertahankan parameter lain seperti jumlah anggota agar tidak hilang--}}
{{--          @if(request('anggota')) <input type="hidden" name="anggota" value="{{ request('anggota') }}"> @endif--}}
{{--          <div class="flex items-end gap-2">--}}
{{--            <div class="flex-1">--}}
{{--              <label for="kelihan_search" class="text-sm">Cari berdasarkan Nama/NIK/NIKA/Banjar</label>--}}
{{--              <input type="text" id="kelihan_search" name="kelihan_search" value="{{ $kelihanSearchTerm }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">--}}
{{--            </div>--}}
{{--            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md h-fit">Cari</button>--}}
{{--            @if($kelihanSearchTerm)--}}
{{--              <a href="{{ route('kependudukan.create', ['anggota' => request('anggota')]) }}" class="px-4 py-2 bg-gray-200 rounded-md h-fit">Reset</a>--}}
{{--            @endif--}}
{{--          </div>--}}
{{--        </form>--}}
{{--        @if($kelihanSearchTerm)--}}
{{--          <p class="text-xs text-blue-700 mt-2">Menampilkan hasil pencarian untuk "{{ $kelihanSearchTerm }}". Dropdown di bawah sudah terfilter.</p>--}}
{{--        @endif--}}
{{--      </div>--}}

{{--       FORM UTAMA UNTUK MENYIMPAN DATA--}}
{{--      <form action="{{ route('kependudukan.store') }}" method="POST">--}}
{{--        @csrf--}}

{{--        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">--}}
{{--          <div></div>  Spacer--}}
{{--          <div class="mt-4 sm:mt-0 flex gap-x-2">--}}
{{--            <a href="{{ route('penomoran.npk.index') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center"><i class="fas fa-database mr-2"></i>Penomoran NPK</a>--}}
{{--            <a href="{{ route('kependudukan.index') }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm flex items-center"><i class="fas fa-times mr-2"></i>Batal</a>--}}
{{--            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center"><i class="fas fa-save mr-2"></i>Simpan KK Baru</button>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        @if ($errors->any())--}}
{{--          <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">--}}
{{--            <p class="font-bold">Terdapat kesalahan validasi:</p>--}}
{{--            <ul class="list-disc ml-5 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>--}}
{{--          </div>--}}
{{--        @endif--}}

{{--        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">--}}
{{--          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-id-card mr-2 text-blue-500"></i>Data Kartu Keluarga Adat</h2>--}}
{{--          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">--}}
{{--            <div class="md:col-span-2 bg-blue-50 p-3 rounded-md text-center"><p class="text-sm text-blue-800"><i class="fas fa-info-circle mr-1"></i> NPK akan dibuat secara otomatis.</p></div>--}}
{{--            <div><label for="nkk" class="block text-sm font-medium text-gray-700">NKK Nasional</label><input type="text" id="nkk" name="nkk" value="{{ old('nkk') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></div>--}}
{{--            <div><label for="no_telp" class="block text-sm font-medium text-gray-700">No. Telepon</label><input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></div>--}}
{{--            <div><label for="kode_banjar_fk" class="block text-sm font-medium text-gray-700">Banjar Adat <span class="text-red-500">*</span></label><select id="kode_banjar_fk" name="kode_banjar_fk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach ($all_banjar as $banjar)<option value="{{ $banjar->kode_banjar }}" {{ old('kode_banjar_fk') == $banjar->kode_banjar ? 'selected' : '' }}>{{ $banjar->nama_banjar }}</option>@endforeach</select></div>--}}
{{--            <div><label for="kode_klasifikasi_krama_fk" class="block text-sm font-medium text-gray-700">Klasifikasi Krama <span class="text-red-500">*</span></label><select id="kode_klasifikasi_krama_fk" name="kode_klasifikasi_krama_fk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach ($all_krama as $krama)<option value="{{ $krama->kode_krama }}" {{ old('kode_klasifikasi_krama_fk') == $krama->kode_krama ? 'selected' : '' }}>{{ $krama->krama }}</option>@endforeach</select></div>--}}
{{--            <div><label for="status_adat" class="block text-sm font-medium text-gray-700">Status Adat <span class="text-red-500">*</span></label><select id="status_adat" name="status_adat" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach(['krama_adat','krama_tamiu','tamiu','pindah_keluar_adat','lainnya','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('status_adat') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>@endforeach</select></div>--}}
{{--            <div class="md:col-span-2"><label for="alamat" class="block text-sm font-medium text-gray-700">Alamat KK Adat</label><textarea id="alamat" name="alamat" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">{{ old('alamat') }}</textarea></div>--}}

{{--            @include('pages.kependudukan.partials.form_dadia_penatahan', ['all_dadia' => $all_dadia, 'all_krama_adat' => $all_krama_adat])--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">--}}
{{--          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-user-shield mr-2 text-blue-500"></i>Data Kepala Keluarga (Pengarep)</h2>--}}
{{--          @include('pages.kependudukan.partials.form_individu', ['prefix' => 'kk', 'data' => old('kk', [])])--}}
{{--        </div>--}}

{{--        @for ($i = 0; $i < $jumlah_anggota; $i++)--}}
{{--          <div class="bg-white p-6 rounded-xl shadow-lg mb-6">--}}
{{--            <div class="flex justify-between items-center mb-4 border-b pb-2">--}}
{{--              <h2 class="text-lg font-semibold text-gray-800"><i class="fas fa-user-friends mr-2 text-green-500"></i>Data Anggota Keluarga Tambahan #{{ $i + 1 }}</h2>--}}
{{--              <p class="text-xs text-gray-500">Kosongkan nama dan NIK jika tidak ingin menambahkan anggota ini.</p>--}}
{{--            </div>--}}
{{--            @include('pages.kependudukan.partials.form_individu', ['prefix' => "anggota[$i]", 'data' => old("anggota.$i", [])])--}}
{{--          </div>--}}
{{--        @endfor--}}

{{--        <div class="bg-gray-100 p-4 rounded-lg flex flex-col sm:flex-row items-center justify-between gap-4">--}}
{{--          <div class="flex items-center gap-3">--}}
{{--            <p class="text-sm font-medium text-gray-700">Jumlah Anggota Tambahan:</p>--}}
{{--            <div class="flex items-center gap-2">--}}
{{--              <a href="{{ route('kependudukan.create', array_merge(request()->query(), ['anggota' => max(0, $jumlah_anggota - 1)])) }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-200 {{ $jumlah_anggota <= 0 ? 'pointer-events-none opacity-50' : '' }}"><i class="fas fa-minus"></i></a>--}}
{{--              <span class="font-bold text-lg text-gray-800 w-8 text-center">{{ $jumlah_anggota }}</span>--}}
{{--              <a href="{{ route('kependudukan.create', array_merge(request()->query(), ['anggota' => min(15, $jumlah_anggota + 1)])) }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-200 {{ $jumlah_anggota >= 15 ? 'pointer-events-none opacity-50' : '' }}"><i class="fas fa-plus"></i></a>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--          <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-2 text-xs rounded max-w-md text-center sm:text-left"><p><strong>Perhatian:</strong> Menambah/mengurangi anggota akan memuat ulang halaman. Gunakan ini sebelum mengisi data anggota.</p></div>--}}
{{--        </div>--}}

{{--        <div class="flex justify-end mt-8"><button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg flex items-center"><i class="fas fa-save mr-2"></i>Simpan Semua Data KK Baru</button></div>--}}
{{--      </form>--}}
{{--    </div>--}}
{{--  </div>--}}
{{--@endsection--}}


{{--@extends('layouts.dasboard-layout')--}}

{{--@section('maincontent')--}}
{{--  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">--}}
{{--    <div class="container mx-auto px-4 py-8 max-w-4xl">--}}
{{--      <form action="{{ route('kependudukan.store') }}" method="POST">--}}
{{--        @csrf--}}
{{--        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">--}}
{{--          <div><h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1><p class="text-gray-600">Isi semua data yang diperlukan di bawah ini.</p></div>--}}
{{--          <div class="mt-4 sm:mt-0 flex gap-x-2">--}}
{{--            <a href="{{ route('penomoran.npk.index') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center"><i class="fas fa-database mr-2"></i>Penomoran NPK</a>--}}
{{--            <a href="{{ route('kependudukan.index') }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm flex items-center"><i class="fas fa-times mr-2"></i>Batal</a>--}}
{{--            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center"><i class="fas fa-save mr-2"></i>Simpan KK Baru</button>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--         Notifikasi Error--}}
{{--        @if ($errors->any())--}}
{{--          <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">--}}
{{--            <p class="font-bold">Terdapat Kesalahan</p>--}}
{{--            <p class="text-sm">Mohon periksa kembali semua data yang wajib diisi.</p>--}}
{{--             Uncomment jika ingin menampilkan daftar error detail--}}
{{--             <ul class="list-disc ml-5 mt-2 text-sm">--}}
{{--                @foreach ($errors->all() as $error)--}}
{{--                    <li>{{ $error }}</li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
{{--          </div>--}}
{{--        @endif--}}


{{--        <!-- Data KK Adat -->--}}
{{--        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">--}}
{{--          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-id-card mr-2 text-blue-500"></i>Data Kartu Keluarga Adat</h2>--}}
{{--          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">--}}
{{--            <div class="md:col-span-2 bg-blue-50 p-3 rounded-md text-center"><p class="text-sm text-blue-800"><i class="fas fa-info-circle mr-1"></i> NPK akan dibuat secara otomatis.</p></div>--}}
{{--            <div><label for="nkk" class="block text-sm font-medium text-gray-700">NKK Nasional</label><input type="text" id="nkk" name="nkk" value="{{ old('nkk') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></div>--}}
{{--            <div><label for="no_telp" class="block text-sm font-medium text-gray-700">No. Telepon</label><input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></div>--}}
{{--            <div><label for="kode_banjar_fk" class="block text-sm font-medium text-gray-700">Banjar Adat <span class="text-red-500">*</span></label><select id="kode_banjar_fk" name="kode_banjar_fk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach ($all_banjar as $banjar)<option value="{{ $banjar->kode_banjar }}" {{ old('kode_banjar_fk') == $banjar->kode_banjar ? 'selected' : '' }}>{{ $banjar->nama_banjar }}</option>@endforeach</select></div>--}}
{{--            <div><label for="kode_klasifikasi_krama_fk" class="block text-sm font-medium text-gray-700">Klasifikasi Krama <span class="text-red-500">*</span></label><select id="kode_klasifikasi_krama_fk" name="kode_klasifikasi_krama_fk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach ($all_krama as $krama)<option value="{{ $krama->kode_krama }}" {{ old('kode_klasifikasi_krama_fk') == $krama->kode_krama ? 'selected' : '' }}>{{ $krama->krama }}</option>@endforeach</select></div>--}}
{{--            <div><label for="status_adat" class="block text-sm font-medium text-gray-700">Status Adat <span class="text-red-500">*</span></label><select id="status_adat" name="status_adat" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>@foreach(['krama_adat','krama_tamiu','tamiu','pindah_keluar_adat','lainnya','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('status_adat') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>@endforeach</select></div>--}}
{{--            <div class="md:col-span-2"><label for="alamat" class="block text-sm font-medium text-gray-700">Alamat KK Adat</label><textarea id="alamat" name="alamat" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">{{ old('alamat') }}</textarea></div>--}}

{{--             Bagian Dadia & Penatahan yang Dinamis--}}
{{--            @include('pages.kependudukan.partials.form_dadia_penatahan', ['all_dadia' => $all_dadia, 'all_krama' => $all_krama, 'kelihanSearchTerm' => $kelihanSearchTerm])--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">--}}
{{--          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-user-shield mr-2 text-blue-500"></i>Data Kepala Keluarga (Pengarep)</h2>--}}
{{--          @include('pages.kependudukan.partials.form_individu', ['prefix' => 'kk', 'data' => old('kk', [])])--}}
{{--        </div>--}}

{{--        @for ($i = 0; $i < $jumlah_anggota; $i++)--}}
{{--          <div class="bg-white p-6 rounded-xl shadow-lg mb-6">--}}
{{--            <div class="flex justify-between items-center mb-4 border-b pb-2">--}}
{{--              <h2 class="text-lg font-semibold text-gray-800"><i class="fas fa-user-friends mr-2 text-green-500"></i>Data Anggota Keluarga Tambahan #{{ $i + 1 }}</h2>--}}
{{--              <p class="text-xs text-gray-500">Kosongkan nama dan NIK jika tidak ingin menambahkan anggota ini.</p>--}}
{{--            </div>--}}
{{--            @include('pages.kependudukan.partials.form_individu', ['prefix' => "anggota[$i]", 'data' => old("anggota.$i", [])])--}}
{{--          </div>--}}
{{--        @endfor--}}

{{--        <div class="bg-gray-100 p-4 rounded-lg flex flex-col sm:flex-row items-center justify-between gap-4">--}}
{{--          <div class="flex items-center gap-3">--}}
{{--            <p class="text-sm font-medium text-gray-700">Jumlah Anggota Tambahan:</p>--}}
{{--            <div class="flex items-center gap-2">--}}
{{--              <a href="{{ route('kependudukan.create', array_merge(request()->query(), ['anggota' => max(0, $jumlah_anggota - 1)])) }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-200 {{ $jumlah_anggota <= 0 ? 'pointer-events-none opacity-50' : '' }}"><i class="fas fa-minus"></i></a>--}}
{{--              <span class="font-bold text-lg text-gray-800 w-8 text-center">{{ $jumlah_anggota }}</span>--}}
{{--              <a href="{{ route('kependudukan.create', array_merge(request()->query(), ['anggota' => min(15, $jumlah_anggota + 1)])) }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-200 {{ $jumlah_anggota >= 15 ? 'pointer-events-none opacity-50' : '' }}"><i class="fas fa-plus"></i></a>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--          <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-2 text-xs rounded max-w-md text-center sm:text-left"><p><strong>Perhatian:</strong> Menambah/mengurangi anggota akan memuat ulang halaman. Gunakan ini sebelum mengisi data anggota.</p></div>--}}
{{--        </div>--}}

{{--        <div class="flex justify-end mt-8"><button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg flex items-center"><i class="fas fa-save mr-2"></i>Simpan Semua Data KK Baru</button></div>--}}
{{--      </form>--}}
{{--    </div>--}}
{{--  </div>--}}
{{--@endsection--}}


{{--@extends('layouts.dasboard-layout')--}}

{{--@section('maincontent')--}}
{{--  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">--}}
{{--    <div class="container mx-auto px-4 py-8 max-w-4xl">--}}
{{--      <form action="{{ route('kependudukan.store') }}" method="POST">--}}
{{--        @csrf--}}

{{--        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">--}}
{{--          <div>--}}
{{--            <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>--}}
{{--            <p class="text-gray-600">Isi semua data yang diperlukan di bawah ini.</p>--}}
{{--          </div>--}}
{{--          <div class="mt-4 sm:mt-0 flex gap-x-2">--}}
{{--            <a href="{{ route('penomoran.npk.index') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center">--}}
{{--              <i class="fas fa-database mr-2"></i>Penomoran NPK--}}
{{--            </a>--}}
{{--            <a href="{{ route('kependudukan.index') }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm flex items-center">--}}
{{--              <i class="fas fa-times mr-2"></i>Batal--}}
{{--            </a>--}}
{{--            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center">--}}
{{--              <i class="fas fa-save mr-2"></i>Simpan KK Baru--}}
{{--            </button>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        --}}{{----}}{{----}}{{----}}{{----}}{{----}}{{----}}{{----}}{{-- Notifikasi Error --}}
{{--        @if ($errors->any())--}}
{{--          <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">--}}
{{--            <p class="font-bold">Terdapat kesalahan validasi, mohon periksa kembali isian Anda:</p>--}}
{{--            <ul class="list-disc ml-5 mt-2">--}}
{{--              @foreach ($errors->all() as $error)--}}
{{--                <li>{{ $error }}</li>--}}
{{--              @endforeach--}}
{{--            </ul>--}}
{{--          </div>--}}
{{--        @endif--}}

{{--        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">--}}
{{--          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-id-card mr-2 text-blue-500"></i>Data Kartu Keluarga Adat</h2>--}}
{{--          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">--}}

{{--            <div class="md:col-span-2 bg-blue-50 p-3 rounded-md text-center">--}}
{{--              <p class="text-sm text-blue-800"><i class="fas fa-info-circle mr-1"></i> NPK akan dibuat secara otomatis setelah memilih Banjar Adat dan menyimpan data.</p>--}}
{{--            </div>--}}

{{--            <div>--}}
{{--              <label for="nkk" class="block text-sm font-medium text-gray-700">NKK Nasional</label>--}}
{{--              <input type="text" id="nkk" name="nkk" value="{{ old('nkk') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">--}}
{{--            </div>--}}
{{--            <div>--}}
{{--              <label for="no_telp" class="block text-sm font-medium text-gray-700">No. Telepon</label>--}}
{{--              <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">--}}
{{--            </div>--}}
{{--            <div>--}}
{{--              <label for="kode_banjar_fk" class="block text-sm font-medium text-gray-700">Banjar Adat <span class="text-red-500">*</span></label>--}}
{{--              <select id="kode_banjar_fk" name="kode_banjar_fk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>--}}
{{--                @foreach ($all_banjar as $banjar)--}}
{{--                  <option value="{{ $banjar->kode_banjar }}" {{ old('kode_banjar_fk') == $banjar->kode_banjar ? 'selected' : '' }}>{{ $banjar->nama_banjar }}</option>--}}
{{--                @endforeach--}}
{{--              </select>--}}
{{--            </div>--}}
{{--            <div>--}}
{{--              <label for="kode_klasifikasi_krama_fk" class="block text-sm font-medium text-gray-700">Klasifikasi Krama <span class="text-red-500">*</span></label>--}}
{{--              <select id="kode_klasifikasi_krama_fk" name="kode_klasifikasi_krama_fk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>--}}
{{--                @foreach ($all_krama as $krama)--}}
{{--                  <option value="{{ $krama->kode_krama }}" {{ old('kode_klasifikasi_krama_fk') == $krama->kode_krama ? 'selected' : '' }}>{{ $krama->krama }}</option>--}}
{{--                @endforeach--}}
{{--              </select>--}}
{{--            </div>--}}
{{--            <div>--}}
{{--              <label for="status_adat" class="block text-sm font-medium text-gray-700">Status Adat <span class="text-red-500">*</span></label>--}}
{{--              <select id="status_adat" name="status_adat" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>--}}
{{--                @foreach(['krama_adat','krama_tamiu','tamiu','pindah_keluar_adat','lainnya','tidak_diketahui'] as $val)--}}
{{--                  <option value="{{$val}}" {{ old('status_adat') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>--}}
{{--                @endforeach--}}
{{--              </select>--}}
{{--            </div>--}}
{{--            <div class="md:col-span-2">--}}
{{--              <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat KK Adat</label>--}}
{{--              <textarea id="alamat" name="alamat" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('alamat') }}</textarea>--}}
{{--            </div>--}}

{{--            <div class="md:col-span-2 border-t pt-4 mt-4" x-data="dynamicDadiaForm()">--}}
{{--              <h3 class="text-md font-semibold text-gray-700 mb-3">Keterangan Dadia & Penatahan</h3>--}}
{{--              <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">--}}
{{--                <div>--}}
{{--                  <label for="selected_dadia_id" class="block text-sm font-medium text-gray-700">Dadia</label>--}}
{{--                  <select id="selected_dadia_id" name="selected_dadia_id" x-model="selectedDadiaId" @change="onDadiaChange()" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">--}}
{{--                    <option value="">-- Pilih Dadia --</option>--}}
{{--                    @foreach ($all_dadia as $dadia)--}}
{{--                      <option value="{{ $dadia->id }}">{{ $dadia->nama_dadia }}</option>--}}
{{--                    @endforeach--}}
{{--                    <option value="custom">-- Isi Dadia Baru --</option>--}}
{{--                  </select>--}}
{{--                  <input type="hidden" name="dadia_mode" :value="selectedDadiaId === 'custom' ? 'custom' : 'select'">--}}
{{--                </div>--}}
{{--                <div x-show="selectedDadiaId && selectedDadiaId !== 'custom'">--}}
{{--                  <label for="selected_penatahan_id" class="block text-sm font-medium text-gray-700">Penatahan</label>--}}
{{--                  <select id="selected_penatahan_id" name="selected_penatahan_id" x-model="selectedPenatahanId" @change="onPenatahanChange()" :disabled="!selectedDadiaId || selectedDadiaId === 'custom'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">--}}
{{--                    <option value="">-- Pilih Penatahan --</option>--}}
{{--                    <template x-for="penatahan in penatahanList" :key="penatahan.id">--}}
{{--                      <option :value="penatahan.id" x-text="penatahan.nama_penatahan"></option>--}}
{{--                    </template>--}}
{{--                    <option value="custom">-- Isi Penatahan Baru --</option>--}}
{{--                  </select>--}}
{{--                  <input type="hidden" name="penatahan_mode" :value="selectedPenatahanId === 'custom' ? 'custom' : 'select'">--}}
{{--                </div>--}}

{{--                <div x-show="selectedDadiaId === 'custom'" x-transition class="md:col-span-2 p-4 bg-blue-50 rounded-md border border-blue-200 space-y-4">--}}
{{--                  <div>--}}
{{--                    <label for="custom_dadia_nama" class="block text-sm font-medium text-gray-700">Nama Dadia Baru <span class="text-red-500">*</span></label>--}}
{{--                    <input type="text" id="custom_dadia_nama" name="custom_dadia_nama" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Ketik nama dadia baru...">--}}
{{--                  </div>--}}
{{--                  <div>--}}
{{--                    <label for="custom_penatahan_nama_for_dadia" class="block text-sm font-medium text-gray-700">Nama Penatahan Baru <span class="text-red-500">*</span></label>--}}
{{--                    <input type="text" id="custom_penatahan_nama_for_dadia" name="custom_penatahan_nama" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Ketik nama penatahan untuk dadia baru...">--}}
{{--                  </div>--}}
{{--                  <div>--}}
{{--                    <label for="custom_kelihan_natah_for_dadia" class="block text-sm font-medium text-gray-700">Kelihan Natah</label>--}}
{{--                    <input type="text" id="custom_kelihan_natah_for_dadia" name="custom_kelihan_natah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Ketik nama kelihan natah...">--}}
{{--                  </div>--}}
{{--                </div>--}}

{{--                <div x-show="isCreatingNewPenatahan()" x-transition class="md:col-span-2 p-4 bg-green-50 rounded-md border border-green-200 space-y-4">--}}
{{--                  <div>--}}
{{--                    <label for="custom_penatahan_nama_for_existing_dadia" class="block text-sm font-medium text-gray-700">Nama Penatahan Baru <span class="text-red-500">*</span></label>--}}
{{--                    <input type="text" id="custom_penatahan_nama_for_existing_dadia" name="custom_penatahan_nama" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Ketik nama penatahan...">--}}
{{--                  </div>--}}
{{--                  <div>--}}
{{--                    <label for="custom_kelihan_natah_for_penatahan" class="block text-sm font-medium text-gray-700">Kelihan Natah</label>--}}
{{--                    <input type="text" id="custom_kelihan_natah_for_penatahan" name="custom_kelihan_natah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Ketik nama kelihan natah...">--}}
{{--                  </div>--}}
{{--                </div>--}}

{{--                <div x-show="isViewingExistingKelihan()" x-transition class="md:col-span-2">--}}
{{--                  <label for="kelihan_natah_readonly" class="block text-sm font-medium text-gray-700">Kelihan Natah</label>--}}
{{--                  <input type="text" id="kelihan_natah_readonly" x-model="kelihanNatah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" readonly>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--            </div>--}}

{{--            <div class="md:col-span-2 pt-4">--}}
{{--              <label for="keterangan_tambahan" class="block text-sm font-medium text-gray-700">Keterangan Tambahan</label>--}}
{{--              <textarea id="keterangan_tambahan" name="keterangan_tambahan" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('keterangan_tambahan') }}</textarea>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">--}}
{{--          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-user-shield mr-2 text-blue-500"></i>Data Kepala Keluarga (Pengarep)</h2>--}}
{{--          @include('pages.kependudukan.partials.form_individu', ['prefix' => 'kk', 'data' => old('kk', [])])--}}
{{--        </div>--}}

{{--        @for ($i = 0; $i < $jumlah_anggota; $i++)--}}
{{--          <div class="bg-white p-6 rounded-xl shadow-lg mb-6">--}}
{{--            <div class="flex justify-between items-center mb-4 border-b pb-2">--}}
{{--              <h2 class="text-lg font-semibold text-gray-800"><i class="fas fa-user-friends mr-2 text-green-500"></i>Data Anggota Keluarga Tambahan #{{ $i + 1 }}</h2>--}}
{{--              <p class="text-xs text-gray-500">Kosongkan nama dan NIK jika tidak ingin menambahkan anggota ini.</p>--}}
{{--            </div>--}}
{{--            @include('pages.kependudukan.partials.form_individu', ['prefix' => "anggota[$i]", 'data' => old("anggota.$i", [])])--}}
{{--          </div>--}}
{{--        @endfor--}}

{{--        <div class="bg-gray-100 p-4 rounded-lg flex flex-col sm:flex-row items-center justify-between gap-4">--}}
{{--          <div class="flex items-center gap-3">--}}
{{--            <p class="text-sm font-medium text-gray-700">Jumlah Anggota Tambahan:</p>--}}
{{--            <div class="flex items-center gap-2">--}}
{{--              <a href="{{ route('kependudukan.create', ['anggota' => max(0, $jumlah_anggota - 1)]) }}"--}}
{{--                 class="px-3 py-1 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-200 {{ $jumlah_anggota <= 0 ? 'pointer-events-none opacity-50' : '' }}">--}}
{{--                <i class="fas fa-minus"></i>--}}
{{--              </a>--}}
{{--              <span class="font-bold text-lg text-gray-800 w-8 text-center">{{ $jumlah_anggota }}</span>--}}
{{--              <a href="{{ route('kependudukan.create', ['anggota' => min(15, $jumlah_anggota + 1)]) }}"--}}
{{--                 class="px-3 py-1 bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-200 {{ $jumlah_anggota >= 15 ? 'pointer-events-none opacity-50' : '' }}">--}}
{{--                <i class="fas fa-plus"></i>--}}
{{--              </a>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--          <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-2 text-xs rounded max-w-md text-center sm:text-left">--}}
{{--            <p><strong>Perhatian:</strong> Menambah/mengurangi anggota akan memuat ulang halaman dan data yang sudah Anda ketik di atas tidak akan tersimpan.</p>--}}
{{--          </div>--}}
{{--        </div>--}}


{{--        <div class="flex justify-end mt-8">--}}
{{--          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg flex items-center">--}}
{{--            <i class="fas fa-save mr-2"></i>Simpan Semua Data KK Baru--}}
{{--          </button>--}}
{{--        </div>--}}

{{--      </form>--}}
{{--    </div>--}}
{{--  </div>--}}
{{--@endsection--}}

{{--@push('scripts')--}}
{{--  <script>--}}
{{--    function dynamicDadiaForm() {--}}
{{--      return {--}}
{{--        allDadiaData: @json($all_dadia),--}}
{{--        selectedDadiaId: '{{ old('selected_dadia_id', '') }}',--}}
{{--        penatahanList: [],--}}
{{--        selectedPenatahanId: '{{ old('selected_penatahan_id', '') }}',--}}
{{--        kelihanNatah: '{{ old('custom_kelihan_natah', '') }}',--}}

{{--        init() {--}}
{{--          if (this.selectedDadiaId) {--}}
{{--            this.updatePenatahanList();--}}
{{--            this.$nextTick(() => {--}}
{{--              this.selectedPenatahanId = '{{ old('selected_penatahan_id', '') }}';--}}
{{--              this.updateKelihanNatah();--}}
{{--            });--}}
{{--          }--}}
{{--        },--}}

{{--        onDadiaChange() {--}}
{{--          this.selectedPenatahanId = '';--}}
{{--          this.kelihanNatah = '';--}}
{{--          this.updatePenatahanList();--}}

{{--          // Ketika memilih custom dadia, nonaktifkan input penatahan baru lainnya--}}
{{--          if (this.selectedDadiaId === 'custom') {--}}
{{--            document.getElementById('custom_penatahan_nama_for_existing_dadia').name = 'ignore_this_field_1';--}}
{{--            document.getElementById('custom_kelihan_natah_for_penatahan').name = 'ignore_this_field_2';--}}
{{--            document.getElementById('custom_penatahan_nama_for_dadia').name = 'custom_penatahan_nama';--}}
{{--            document.getElementById('custom_kelihan_natah_for_dadia').name = 'custom_kelihan_natah';--}}
{{--          } else {--}}
{{--            document.getElementById('custom_penatahan_nama_for_dadia').name = 'ignore_this_field_3';--}}
{{--            document.getElementById('custom_kelihan_natah_for_dadia').name = 'ignore_this_field_4';--}}
{{--          }--}}
{{--        },--}}

{{--        onPenatahanChange() {--}}
{{--          this.updateKelihanNatah();--}}

{{--          // Logika untuk menonaktifkan input yang tidak relevan--}}
{{--          if (this.selectedPenatahanId === 'custom') {--}}
{{--            document.getElementById('custom_penatahan_nama_for_existing_dadia').name = 'custom_penatahan_nama';--}}
{{--            document.getElementById('custom_kelihan_natah_for_penatahan').name = 'custom_kelihan_natah';--}}
{{--          } else {--}}
{{--            document.getElementById('custom_penatahan_nama_for_existing_dadia').name = 'ignore_this_field_5';--}}
{{--            document.getElementById('custom_kelihan_natah_for_penatahan').name = 'ignore_this_field_6';--}}
{{--          }--}}
{{--        },--}}

{{--        updatePenatahanList() {--}}
{{--          this.penatahanList = [];--}}
{{--          if (this.selectedDadiaId && this.selectedDadiaId !== 'custom') {--}}
{{--            const selectedDadia = this.allDadiaData.find(d => d.id == this.selectedDadiaId);--}}
{{--            this.penatahanList = selectedDadia ? selectedDadia.penatahan : [];--}}
{{--          }--}}
{{--        },--}}

{{--        updateKelihanNatah() {--}}
{{--          this.kelihanNatah = '';--}}
{{--          if (this.selectedDadiaId && this.selectedDadiaId !== 'custom' && this.selectedPenatahanId && this.selectedPenatahanId !== 'custom') {--}}
{{--            const selectedPenatahan = this.penatahanList.find(p => p.id == this.selectedPenatahanId);--}}
{{--            this.kelihanNatah = selectedPenatahan ? (selectedPenatahan.kelihan_natah || '') : '';--}}
{{--          }--}}
{{--        },--}}

{{--        // -- Fungsi Logika untuk Tampilan (x-show) ----}}
{{--        isCreatingNewPenatahan() {--}}
{{--          return this.selectedDadiaId && this.selectedDadiaId !== 'custom' && this.selectedPenatahanId === 'custom';--}}
{{--        },--}}

{{--        isViewingExistingKelihan() {--}}
{{--          return this.selectedDadiaId && this.selectedDadiaId !== 'custom' && this.selectedPenatahanId && this.selectedPenatahanId !== 'custom';--}}
{{--        }--}}
{{--      }--}}
{{--    }--}}
{{--  </script>--}}
{{--@endpush--}}
