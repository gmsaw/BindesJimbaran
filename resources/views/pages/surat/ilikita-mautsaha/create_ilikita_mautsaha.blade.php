{{\Carbon\Carbon::setLocale('id')}}

@extends('layouts.dasboard-layout')

@section('styles')
  {{-- Tambahkan CSS Cropper.js --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
  <style>
    #cropper-container-gandeng { width: 100%; height: 350px; background-color: #f3f4f6; }
    #image-to-crop-gandeng { display: block; max-width: 100%; max-height: 350px; }
  </style>
@endsection

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
      {{-- Form utama untuk menyimpan data --}}
      <form action="{{ route('surat.ilikita_mautsaha.store') }}" method="POST">
        @csrf

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
          <div><h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1></div>
          <div class="mt-4 sm:mt-0 flex gap-x-2">
            <a href="{{ route('surat.indexMain') }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm">Batal</a>
            <a href="{{ route('surat.ilikita_utsaha.penomoran.index') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm"><i class="fas fa-database mr-2"></i>Penomoran</a>
            <a href="{{ route('surat.arsip.ilikita_mautsaha') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm"><i class="fas fa-database mr-2"></i>Arsip</a>
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
                onchange="window.location.href = '{{ route('surat.ilikita_mautsaha.create') }}?penomoran=otomatis'">
                <span class="ml-2">Otomatis</span>
              </label>
              <label class="flex items-center">
                <input type="radio" name="penomoran" value="manual" {{ request('penomoran') == 'manual' ? 'checked' : '' }}
                onchange="window.location.href = '{{ route('surat.ilikita_mautsaha.create') }}?penomoran=manual'">
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
                  <label class="block text-sm font-medium">2. Pilih Tanggal Penomoran</label>
                  <input type="date" name="tahun_surat_input" value="{{ request('tahun_surat_input', date('Y-m-d')) }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                {{-- Tombol ini akan me-reload halaman dengan parameter kode dan tahun --}}
                <button type="submit" formaction="{{ route('surat.ilikita_mautsaha.create') }}" formmethod="GET" class="px-4 py-2 bg-gray-200 rounded-md text-sm">
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
            <div><label class="block text-sm font-medium">Tanggal Surat (Resmi)</label><input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', \Carbon\Carbon::now()->toDateString()) }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            <div><label class="block text-sm font-medium">Tahun Awig-awig</label><select name="tahun_awig" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@for($i=1990; $i <= \Carbon\Carbon::now()->translatedFormat('Y'); $i++) <option value="{{ $i }}">{{ $i }}</option>  @endfor</select></div>
            <div><label class="block text-sm font-medium">No Pararem</label><input type="text" name="nomor_pararem" value="{{ old('nomor_pararem') }}" placeholder="masukkan angka: 02 dll" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            <div><label class="block text-sm font-medium">Tahun Pararem</label><select name="tahun_pararem" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@for($i=1990; $i <= \Carbon\Carbon::now()->translatedFormat('Y'); $i++) <option value="{{ $i }}">{{ $i }}</option>  @endfor</select></div>
          </div>
        </div>

        {{-- Data Pengusaha --}}
        <div class="bg-white p-6 rounded-xl shadow-lg mt-8">
          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Pemohon</h2>
          <div class="space-y-4">
            @php
                $prefix = 'pemohon';
            @endphp

            <div><label class="block text-sm font-medium">Nama</label><input type="text" name="nama_pemohon" value="{{ old('nama_pemohon')}}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            <div><label class="block text-sm font-medium">NIK/NIKA</label><input type="text" name="nik_nika_pemohon" value="{{ old('nik_nika_pemohon') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            <div class="grid grid-cols-2 gap-x-2">
              <div><label class="block text-sm font-medium">Tempat Lahir</label><input type="text" name="tempat_lahir_pemohon" value="{{ old('tempat_lahir_pemohon') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
              <div><label class="block text-sm font-medium">Tanggal Lahir</label><input type="date" name="tanggal_lahir_pemohon" value="{{ old('tanggal_lahir_pemohon') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            </div>
{{--            <div><label class="block text-sm font-medium">Jenis Kelamin</label><input type="text" name="jenis_kelamin_pemohon" value="{{ old('jenis_kelamin_pemohon') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>--}}
            <div class="md:col-span-2">
              <label class="block text-sm font-medium">Jenis Kelamin</label>
              <select name="jenis_kelamin_pemohon" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="LAKI-LAKI">Laki-laki</option>
                <option value="PEREMPUAN">Perempuan</option>
              </select>
            </div>
            <div><label class="block text-sm font-medium">Agama</label><input type="text" name="agama_pemohon" value="{{ old('agama_pemohon') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            <div><label class="block text-sm font-medium">Status Krama</label><input type="text" name="status_krama_pemohon" value="{{ old('status_krama_pemohon') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            <div><label class="block text-sm font-medium">Pekerjaan</label><input type="text" name="pekerjaan_pemohon" value="{{ old('pekerjaan_pemohon') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>

            <h2 class="text-lg font-semibold text-gray-800 mt-4 mb-4 border-b pb-2">Alamat Asal</h2>
            <div class="space-y-4">
            <div x-data="addressForm()">
                @csrf
                <div>
                  <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
                  {{-- PERBAIKAN: value sekarang menggunakan $province->code --}}
                  <select id="provinsi" name="alamat_asal_provinsi" x-model="selectedProvinsi" @change="fetchKota()" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach ($provinces as $province)
                      <option value="{{ $province->code }}">{{ $province->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div>
                  <label for="kota" class="block text-sm font-medium text-gray-700">Kota / Kabupaten</label>
                  <div class="relative">
                    <select id="kota" name="alamat_asal_kota" x-model="selectedKota" @change="fetchKecamatan()" :disabled="kotaList.length === 0" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                      <option value="">-- Pilih Kota / Kabupaten --</option>
                      {{-- PERBAIKAN: :value sekarang diikat ke kota.code --}}
                      <template x-for="kota in kotaList" :key="kota.id">
                        <option :value="kota.code" x-text="kota.name"></option>
                      </template>
                    </select>
                    <div x-show="isLoadingKota" class="absolute inset-y-0 right-0 flex items-center pr-4"><i class="fas fa-spinner fa-spin text-gray-500"></i></div>
                  </div>
                </div>

                <div>
                  <label for="kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                  <div class="relative">
                    <select id="kecamatan" name="alamat_asal_kecamatan" x-model="selectedKecamatan" @change="fetchDesa()" :disabled="kecamatanList.length === 0" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                      <option value="">-- Pilih Kecamatan --</option>
                      <template x-for="kecamatan in kecamatanList" :key="kecamatan.id">
                        <option :value="kecamatan.code" x-text="kecamatan.name"></option>
                      </template>
                    </select>
                    <div x-show="isLoadingKecamatan" class="absolute inset-y-0 right-0 flex items-center pr-4"><i class="fas fa-spinner fa-spin text-gray-500"></i></div>
                  </div>
                </div>

                <div>
                  <label for="desa" class="block text-sm font-medium text-gray-700">Desa / Kelurahan</label>
                  <div class="relative">
                    <select id="desa" name="alamat_asal_desa" x-model="selectedDesa" :disabled="desaList.length === 0" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                      <option value="">-- Pilih Desa / Kelurahan --</option>
                      <template x-for="desa in desaList" :key="desa.id">
                        <option :value="desa.code" x-text="desa.name"></option>
                      </template>
                    </select>
                    <div x-show="isLoadingDesa" class="absolute inset-y-0 right-0 flex items-center pr-4"><i class="fas fa-spinner fa-spin text-gray-500"></i></div>
                  </div>
                </div>

                @include('pages.surat.partials.form_banjar_only', ['prefix' => 'asal'])

                <hr class="my-4">

                <div>
                  <label for="alamat_detail" class="block text-sm font-medium text-gray-700">Alamat Detail (Jalan, RT/RW, No. Rumah)</label>
                  <textarea id="alamat_detail" name="alamat_asal_alamat_detail" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div>
                  <label for="kode_pos" class="block text-sm font-medium text-gray-700">Kode Pos (Opsional)</label>
                  <input type="text" id="kode_pos" name="alamat_asal_kode_pos" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>

            </div>

              <h2 class="text-lg font-semibold text-gray-800 mt-4 mb-4 border-b pb-2">Alamat Desa Adat</h2>
              <div class="space-y-4">
                <div x-data="addressForm()">
                    @csrf
                    <div>
                      <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
                      {{-- PERBAIKAN: value sekarang menggunakan $province->code --}}
                      <select id="provinsi" name="alamat_adat_provinsi" x-model="selectedProvinsi" @change="fetchKota()" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach ($provinces as $province)
                          <option value="{{ $province->code }}">{{ $province->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div>
                      <label for="kota" class="block text-sm font-medium text-gray-700">Kota / Kabupaten</label>
                      <div class="relative">
                        <select id="kota" name="alamat_adat_kota" x-model="selectedKota" @change="fetchKecamatan()" :disabled="kotaList.length === 0" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                          <option value="">-- Pilih Kota / Kabupaten --</option>
                          {{-- PERBAIKAN: :value sekarang diikat ke kota.code --}}
                          <template x-for="kota in kotaList" :key="kota.id">
                            <option :value="kota.code" x-text="kota.name"></option>
                          </template>
                        </select>
                        <div x-show="isLoadingKota" class="absolute inset-y-0 right-0 flex items-center pr-4"><i class="fas fa-spinner fa-spin text-gray-500"></i></div>
                      </div>
                    </div>

                    <div>
                      <label for="kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                      <div class="relative">
                        <select id="kecamatan" name="alamat_adat_kecamatan" x-model="selectedKecamatan" @change="fetchDesa()" :disabled="kecamatanList.length === 0" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                          <option value="">-- Pilih Kecamatan --</option>
                          <template x-for="kecamatan in kecamatanList" :key="kecamatan.id">
                            <option :value="kecamatan.code" x-text="kecamatan.name"></option>
                          </template>
                        </select>
                        <div x-show="isLoadingKecamatan" class="absolute inset-y-0 right-0 flex items-center pr-4"><i class="fas fa-spinner fa-spin text-gray-500"></i></div>
                      </div>
                    </div>

                    <div>
                      <label for="desa" class="block text-sm font-medium text-gray-700">Desa / Kelurahan</label>
                      <div class="relative">
                        <select id="desa" name="alamat_adat_desa" x-model="selectedDesa" :disabled="desaList.length === 0" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                          <option value="">-- Pilih Desa / Kelurahan --</option>
                          <template x-for="desa in desaList" :key="desa.id">
                            <option :value="desa.code" x-text="desa.name"></option>
                          </template>
                        </select>
                        <div x-show="isLoadingDesa" class="absolute inset-y-0 right-0 flex items-center pr-4"><i class="fas fa-spinner fa-spin text-gray-500"></i></div>
                      </div>
                    </div>

                  @include('pages.surat.partials.form_banjar_only', ['prefix' => 'adat'])

                  <hr class="my-4">

                    <div>
                      <label for="alamat_detail" class="block text-sm font-medium text-gray-700">Alamat Detail (Jalan, RT/RW, No. Rumah)</label>
                      <textarea id="alamat_detail" name="alamat_adat_alamat_detail" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    <div>
                      <label for="kode_pos" class="block text-sm font-medium text-gray-700">Kode Pos (Opsional)</label>
                      <input type="text" id="kode_pos" name="alamat_adat_kode_pos" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Pemuput & Penanda Tangan -->
        <div class="bg-white p-6 rounded-xl shadow-lg mt-8">
          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Tambahan</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div><label class="block text-sm font-medium">Perusahaan/Lembaga Usaha</label><input type="text" name="lembaga_usaha" value="{{ old('lembaga_usaha') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>



            <div><label class="block text-sm font-medium">Akta Pendirian (Hukum Negara)</label><input type="text" name="akta_usaha" value="{{ old('akta_usaha') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            <div><label class="block text-sm font-medium">Bidang Usaha</label><input type="text" name="bidang_usaha" value="{{ old('bidang_usaha') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            <!-- Kolom Kanan: Penanda Tangan (Bendesa) -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Penanda Tangan (Bendesa)</label>
              <select name="id_bendesa_fk" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                @foreach ($bendesa_aktif as $bendesa)
                  <option value="{{ $bendesa->id }}">{{ $bendesa->nama_bendesa }}</option>
                @endforeach
              </select>
            </div>


          </div>

          <h2 class="text-lg font-semibold text-gray-800 mt-4 mb-4 border-b pb-2">Alamat Usaha</h2>
          <div class="space-y-4">
            <div x-data="addressForm()">
              @csrf
              <div>
                <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
                {{-- PERBAIKAN: value sekarang menggunakan $province->code --}}
                <select id="provinsi" name="alamat_usaha_provinsi" x-model="selectedProvinsi" @change="fetchKota()" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                  <option value="">-- Pilih Provinsi --</option>
                  @foreach ($provinces as $province)
                    <option value="{{ $province->code }}">{{ $province->name }}</option>
                  @endforeach
                </select>
              </div>

              <div>
                <label for="kota" class="block text-sm font-medium text-gray-700">Kota / Kabupaten</label>
                <div class="relative">
                  <select id="kota" name="alamat_usaha_kota" x-model="selectedKota" @change="fetchKecamatan()" :disabled="kotaList.length === 0" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                    <option value="">-- Pilih Kota / Kabupaten --</option>
                    {{-- PERBAIKAN: :value sekarang diikat ke kota.code --}}
                    <template x-for="kota in kotaList" :key="kota.id">
                      <option :value="kota.code" x-text="kota.name"></option>
                    </template>
                  </select>
                  <div x-show="isLoadingKota" class="absolute inset-y-0 right-0 flex items-center pr-4"><i class="fas fa-spinner fa-spin text-gray-500"></i></div>
                </div>
              </div>

              <div>
                <label for="kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                <div class="relative">
                  <select id="kecamatan" name="alamat_usaha_kecamatan" x-model="selectedKecamatan" @change="fetchDesa()" :disabled="kecamatanList.length === 0" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                    <option value="">-- Pilih Kecamatan --</option>
                    <template x-for="kecamatan in kecamatanList" :key="kecamatan.id">
                      <option :value="kecamatan.code" x-text="kecamatan.name"></option>
                    </template>
                  </select>
                  <div x-show="isLoadingKecamatan" class="absolute inset-y-0 right-0 flex items-center pr-4"><i class="fas fa-spinner fa-spin text-gray-500"></i></div>
                </div>
              </div>

              <div>
                <label for="desa" class="block text-sm font-medium text-gray-700">Desa / Kelurahan</label>
                <div class="relative">
                  <select id="desa" name="alamat_usaha_desa" x-model="selectedDesa" :disabled="desaList.length === 0" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                    <option value="">-- Pilih Desa / Kelurahan --</option>
                    <template x-for="desa in desaList" :key="desa.id">
                      <option :value="desa.code" x-text="desa.name"></option>
                    </template>
                  </select>
                  <div x-show="isLoadingDesa" class="absolute inset-y-0 right-0 flex items-center pr-4"><i class="fas fa-spinner fa-spin text-gray-500"></i></div>
                </div>
              </div>

              @include('pages.surat.partials.form_banjar_only', ['prefix' => 'usaha'])

              <hr class="my-4">

              <div>
                <label for="alamat_detail" class="block text-sm font-medium text-gray-700">Alamat Detail (Jalan, RT/RW, No. Rumah)</label>
                <textarea id="alamat_detail" name="alamat_usaha_alamat_detail" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></textarea>
              </div>
              <div>
                <label for="kode_pos" class="block text-sm font-medium text-gray-700">Kode Pos (Opsional)</label>
                <input type="text" id="kode_pos" name="alamat_usaha_kode_pos" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
              </div>
            </div>
          </div>

          <div class="md:col-span-2 mt-4">
            <label class="block text-sm font-medium mb-2">Pas Foto</label>
            <div class="flex flex-col items-center p-4 border rounded-lg">
              <div id="preview-area-gandeng">
                <img id="image-preview-gandeng" src="https://placehold.co/400x300/e2e8f0/e2e8f0?text=FOTO+PASANGAN" alt="Foto Gandeng" class="w-64 h-auto object-cover rounded-md shadow-sm mb-4">
              </div>

              <div id="cropper-ui-container-gandeng" class="w-full hidden space-y-2">
                <div id="cropper-container-gandeng"><img id="image-to-crop-gandeng"></div>
                <div class="flex justify-center gap-x-2">
                  <button id="crop-btn-gandeng" type="button" class="px-3 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700">Potong & Gunakan</button>
                  <button id="cancel-crop-btn-gandeng" type="button" class="px-3 py-1 bg-gray-200 text-xs rounded-md hover:bg-gray-300">Batal</button>
                </div>
              </div>

              <div id="upload-area-gandeng" class="w-full text-center">
                <label for="file-input-gandeng" class="w-1/2 mx-auto text-center block mt-2 px-4 py-2 bg-blue-50 text-blue-700 text-sm rounded-md hover:bg-blue-100 cursor-pointer">
                  <i class="fas fa-upload mr-1"></i> Pilih Foto
                </label>
                <input type="file" id="file-input-gandeng" class="hidden" accept="image/*">
                <input type="hidden" name="cropped_pas_foto" id="cropped_image_gandeng">
              </div>
            </div>
          </div>

        </div>



      </form>
    </div>
  </div>
@endsection


@push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

  <script>
    function addressForm() {
      return {
        kotaList: [], kecamatanList: [], desaList: [],
        isLoadingKota: false, isLoadingKecamatan: false, isLoadingDesa: false,
        selectedProvinsi: '{{ old('provinsi', '') }}',
        selectedKota: '{{ old('kota', '') }}',
        selectedKecamatan: '{{ old('kecamatan', '') }}',
        selectedDesa: '{{ old('desa', '') }}',

        async fetchKota() {
          this.kotaList = []; this.kecamatanList = []; this.desaList = [];
          this.selectedKota = ''; this.selectedKecamatan = ''; this.selectedDesa = '';
          if (!this.selectedProvinsi) return;

          this.isLoadingKota = true;
          try {
            // PERBAIKAN: Mengirim 'province_code' sebagai parameter
            const response = await fetch(`{{ route('api.kota') }}?province_code=${this.selectedProvinsi}`);
            this.kotaList = await response.json();
          } catch (error) { console.error('Gagal mengambil data kota:', error); }
          finally { this.isLoadingKota = false; }
        },
        async fetchKecamatan() {
          this.kecamatanList = []; this.desaList = [];
          this.selectedKecamatan = ''; this.selectedDesa = '';
          if (!this.selectedKota) return;

          this.isLoadingKecamatan = true;
          try {
            // PERBAIKAN: Mengirim 'city_code' sebagai parameter
            const response = await fetch(`{{ route('api.kecamatan') }}?city_code=${this.selectedKota}`);
            this.kecamatanList = await response.json();
          } catch (error) { console.error('Gagal mengambil data kecamatan:', error); }
          finally { this.isLoadingKecamatan = false; }
        },
        async fetchDesa() {
          this.desaList = [];
          this.selectedDesa = '';
          if (!this.selectedKecamatan) return;

          this.isLoadingDesa = true;
          try {
            // PERBAIKAN: Mengirim 'district_code' sebagai parameter
            const response = await fetch(`{{ route('api.desa') }}?district_code=${this.selectedKecamatan}`);
            this.desaList = await response.json();
          } catch (error) { console.error('Gagal mengambil data desa:', error); }
          finally { this.isLoadingDesa = false; }
        }
      }
    }
  </script>


  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Inisialisasi elemen untuk foto gandeng
      const cropperUiContainer = document.getElementById('cropper-ui-container-gandeng');
      const imageToCrop = document.getElementById('image-to-crop-gandeng');
      const fileInput = document.getElementById('file-input-gandeng');
      const cropBtn = document.getElementById('crop-btn-gandeng');
      const cancelBtn = document.getElementById('cancel-crop-btn-gandeng');
      const imagePreview = document.getElementById('image-preview-gandeng');
      const previewArea = document.getElementById('preview-area-gandeng');
      const uploadArea = document.getElementById('upload-area-gandeng');
      const hiddenInput = document.getElementById('cropped_image_gandeng');
      let cropper;

      fileInput.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
          previewArea.classList.add('hidden');
          uploadArea.classList.add('hidden');
          cropperUiContainer.classList.remove('hidden');

          const reader = new FileReader();
          reader.onload = function (event) {
            imageToCrop.src = event.target.result;
            if (cropper) cropper.destroy();
            cropper = new Cropper(imageToCrop, {
              aspectRatio: 3 / 4, // Bisa diatur jika perlu
              viewMode: 1,
              responsive: true,
              background: false
            });
          };
          reader.readAsDataURL(files[0]);
        }
      });

      const showPreview = () => {
        cropperUiContainer.classList.add('hidden');
        previewArea.classList.remove('hidden');
        uploadArea.classList.remove('hidden');
        if (cropper) cropper.destroy();
        fileInput.value = '';
      };

      cropBtn.addEventListener('click', function () {
        if (!cropper) return;
        const croppedCanvas = cropper.getCroppedCanvas({ width: 800, height: 600 }); // Resolusi contoh
        const croppedImageData = croppedCanvas.toDataURL('image/jpeg');
        imagePreview.src = croppedImageData;
        hiddenInput.value = croppedImageData;
        showPreview();
      });

      cancelBtn.addEventListener('click', showPreview);
    });
  </script>
@endpush

