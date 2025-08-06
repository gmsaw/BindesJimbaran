@extends('layouts.dasboard-layout')

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
      <form action="{{ route('penduduk.store') }}" method="POST">
        @csrf

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
          <div><h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1></div>
          <div class="mt-4 sm:mt-0 flex gap-x-2">
            <a href="{{ route('kependudukan.create.options') }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm flex items-center"><i class="fas fa-times mr-2"></i>Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center"><i class="fas fa-save mr-2"></i>Simpan Penduduk</button>
          </div>
        </div>

        @if ($errors->any())
          <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">Terdapat kesalahan validasi:</p>
            <ul class="list-disc ml-5 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
          </div>
        @endif

        <!-- Form Data Individu -->
        <div class="bg-white p-6 rounded-xl shadow-lg">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="col-span-1 md:col-span-2 lg:col-span-3"><h4 class="font-semibold text-gray-600 border-b pb-1">Data Pribadi</h4></div>

            <div>
              <label class="block text-sm font-medium">Nama Lengkap <span class="text-red-500">*</span></label>
              <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
              <label class="block text-sm font-medium">NIK Nasional <span class="text-red-500">*</span></label>
              <input type="text" name="nik_nasional" value="{{ old('nik_nasional') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
              <label class="block text-sm font-medium">Tempat Lahir</label>
              <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
              <label class="block text-sm font-medium">Tanggal Lahir</label>
              <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
              <label class="block text-sm font-medium">Jenis Kelamin</label>
              <select name="jenis_kelamin" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="laki-laki" {{ old('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium">Agama</label>
              <select name="agama" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                @foreach(['hindu','islam','kristen protestan','katolik','buddha','khonghucu','lainnya'] as $val)<option value="{{$val}}" {{ old('agama') == $val ? 'selected' : '' }}>{{ ucfirst($val) }}</option>@endforeach
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium">Pendidikan</label>
              <select name="pendidikan" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                @foreach(['tidak_sekolah','tk','sd','smp','sma','diploma_1','diploma_2','diploma_3','strata_1','strata_2','strata_3','lainnya'] as $val)<option value="{{$val}}" {{ old('pendidikan') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>@endforeach
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium">Pekerjaan</label>
              <select name="pekerjaan" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                @foreach(['tidak_bekerja','pelajar/mahasiswa','pegawai_negeri_sipil','pegawai_swasta','wiraswasta','petani','nelayan','lainnya'] as $val)<option value="{{$val}}" {{ old('pekerjaan') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>@endforeach
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium">Status Perkawinan</label>
              <select name="status_perkawinan" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                @foreach(['belum_kawin','kawin_tercatat','kawin_tidak_tercatat','cerai_hidup','cerai_mati'] as $val)<option value="{{$val}}" {{ old('status_perkawinan') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>@endforeach
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium">Golongan Darah</label>
              <select name="golongan_darah" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                @foreach(['A','B','AB','O','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('golongan_darah') == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium">Nama Ayah</label>
              <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
              <label class="block text-sm font-medium">Nama Ibu</label>
              <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div class="md:col-span-3">
              <label class="block text-sm font-medium">Alamat (Sesuai KTP)</label>
              <textarea name="alamat" rows="2" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('alamat') }}</textarea>
            </div>

            <div>
              <label class="block text-sm font-medium">Kewarganegaraan</label>
              <select name="kewarganegaraan" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                @foreach(['wni', 'wna', 'wni_keturunan', 'wni_naturalisasi', 'wna_tinggal_tetap', 'wna_kerja', 'wna_diplomatik', 'bipatride', 'apatride', 'stateless', 'tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('kewarganegaraan') == $val ? 'selected' : '' }}>{{ (str_replace('_', ' ', $val)) }}</option>@endforeach
              </select>
            </div>

            <div class="col-span-1 md:col-span-2 lg:col-span-3"><h4 class="font-semibold text-gray-600 border-b pb-1 mt-4">Data Adat</h4></div>

            <div class="md:col-span-3 bg-blue-50 p-3 rounded-md text-center">
              <p class="text-sm text-blue-800"><i class="fas fa-info-circle mr-1"></i> NIKA akan dibuat secara otomatis.</p>
            </div>

            <div>
              <label class="block text-sm font-medium">Banjar Adat <span class="text-red-500">*</span></label>
              <select name="kode_banjar_fk" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                @foreach($all_banjar as $banjar)
                  <option value="{{ $banjar->kode_banjar }}">{{ $banjar->nama_banjar }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium">Status di Banjar</label>
              <select name="status_di_banjar" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
                @foreach(['aktif', 'pindah_keluar_banjar', 'meninggal', 'nonaktif_sementara', 'lainnya'] as $val)
                  <option value="{{$val}}" {{ old('status_di_banjar', 'aktif') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
