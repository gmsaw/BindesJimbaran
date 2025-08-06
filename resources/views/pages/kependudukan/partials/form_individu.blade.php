@php
  // PERBAIKAN: Mengubah format prefix untuk helper old()
  // Contoh: mengubah 'anggota[0]' menjadi 'anggota.0'
$oldPrefix = str_replace(['[', ']'], ['.', ''], $prefix);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  {{-- Data master_individu --}}
  <div class="col-span-1 md:col-span-2 lg:col-span-3"><h4 class="font-semibold text-gray-600 border-b pb-1">Data Pribadi</h4></div>

  <div>
    <label class="block text-sm font-medium">Nama Lengkap</label>
    <input type="text" name="{{$prefix}}[nama_lengkap]" value="{{ old($oldPrefix.'.nama_lengkap') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
  </div>
  <div>
    <label class="block text-sm font-medium">NIK Nasional</label>
    <input type="text" name="{{$prefix}}[nik_nasional]" value="{{ old($oldPrefix.'.nik_nasional') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
  </div>
  <div>
    <label class="block text-sm font-medium">Tempat Lahir</label>
    <input type="text" name="{{$prefix}}[tempat_lahir]" value="{{ old($oldPrefix.'.tempat_lahir') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
  </div>
  <div>
    <label class="block text-sm font-medium">Tanggal Lahir</label>
    <input type="date" name="{{$prefix}}[tanggal_lahir]" value="{{ old($oldPrefix.'.tanggal_lahir') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
  </div>
  <div>
    <label class="block text-sm font-medium">Jenis Kelamin</label>
    <select name="{{$prefix}}[jenis_kelamin]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
      @foreach(['perempuan','laki-laki','p','l','tidak_diketahui'] as $val)
        <option value="{{$val}}" {{ old($oldPrefix.'.jenis_kelamin') == $val ? 'selected' : '' }}>{{ ucfirst($val) }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm font-medium">Agama</label>
    <select name="{{$prefix}}[agama]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
      @foreach(['islam','kristen protestan','katolik','hindu','buddha','khonghucu','kristen','lainnya'] as $val)
        <option value="{{$val}}" {{ old($oldPrefix.'.agama') == $val ? 'selected' : '' }}>{{ ucfirst($val) }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm font-medium">Pendidikan</label>
    <select name="{{$prefix}}[pendidikan]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
      @foreach(['belum/tidak_sekolah','diploma_1','diploma_2','diploma_3','diploma_4','sarjana_terapan','strata_1','sarjana','strata_2','magister','strata_3','doktor','tk','sd','smp','sma','d1','d2','d3','d4','s1','s2','s3','lainnya','tidak_diketahui'] as $val)
        <option value="{{$val}}" {{ old($oldPrefix.'.pendidikan') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm font-medium">Pekerjaan</label>
    <select name="{{$prefix}}[pekerjaan]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
      @foreach(['tidak_bekerja','belum/tidak_sekolah','pelajar/mahasiswa','ibu_rumah_tangga','pensiunan','pegawai_negeri_sipil','pegawai_swasta','wiraswasta','pengusaha','petani','peternak','nelayan','buruh','tni/polri','karyawan_bumn','karyawan_bumd','profesional','tenaga_medis','guru/dosen','seniman/artis','ojol/driver_online','pekerja_lepas','pekerja_serabutan','sopir','lainnya','tidak_diketahui'] as $val)
        <option value="{{$val}}" {{ old($oldPrefix.'.pekerjaan') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm font-medium">Status Perkawinan</label>
    <select name="{{$prefix}}[status_perkawinan]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
      @foreach(['belum_kawin','kawin_tercatat','kawin_tidak_tercatat','kawin_siri','cerai_hidup_tercatat','cerai_hidup_tidak_tercatat','cerai_mati_tidak_tercatat','duda','janda','hidup_berdampingan','kawin','cerai_hidup','cerai_mati','single','married','divorced','widowed','separated','cohabitation','tidak_diketahui'] as $val)
        <option value="{{$val}}" {{ old($oldPrefix.'.status_perkawinan') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm font-medium">Tgl Catat Kawin</label>
    <input type="date" name="{{$prefix}}[tanggal_catat_kawin]" value="{{ old($oldPrefix.'.tanggal_catat_kawin') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
  </div>
  <div>
    <label class="block text-sm font-medium">Kewarganegaraan</label>
    <select name="{{$prefix}}[kewarganegaraan]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
      @foreach(['wni','wna','wni_keturunan','wni_naturalisasi','wna_tinggal_tetap','wna_kerja','wna_diplomatik','bipatride','apatride','stateless','tidak_diketahui'] as $val)
        <option value="{{$val}}" {{ old($oldPrefix.'.kewarganegaraan') == $val ? 'selected' : '' }}>{{ strtoupper($val) }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm font-medium">Golongan Darah</label>
    <select name="{{$prefix}}[golongan_darah]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
      @foreach(['A','B','AB','O','A+','A-','B+','B-','AB+','AB-','O+','O-','tidak_diketahui'] as $val)
        <option value="{{$val}}" {{ old($oldPrefix.'.golongan_darah') == $val ? 'selected' : '' }}>{{$val}}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm font-medium">Nama Ayah</label>
    <input type="text" name="{{$prefix}}[nama_ayah]" value="{{ old($oldPrefix.'.nama_ayah') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
  </div>
  <div>
    <label class="block text-sm font-medium">Nama Ibu</label>
    <input type="text" name="{{$prefix}}[nama_ibu]" value="{{ old($oldPrefix.'.nama_ibu') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
  </div>
  <div class="md:col-span-3">
    <label class="block text-sm font-medium">Alamat</label>
    <textarea name="{{$prefix}}[alamat]" rows="2" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">{{ old($oldPrefix.'.alamat') }}</textarea>
  </div>

  @if(!empty($krama_input_request))
    @if($krama_input_request !== "krama_adat")
      <div class="md:col-span-3">
        @if($krama_input_request === "krama_tamiu")
          <label class="block text-sm font-medium">Desa Adat</label>
        @else
          <label class="block text-sm font-medium">Desa</label>
        @endif
        <textarea name="{{$prefix}}[{{$krama_input_request}}][desa]" rows="1" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">{{ old($oldPrefix.'.desa') }}</textarea>
      </div>

      <div class="md:col-span-3">
        <label class="block text-sm font-medium">Kecamatan</label>
        <textarea name="{{$prefix}}[{{$krama_input_request}}][kecamatan]" rows="1" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">{{ old($oldPrefix.'.kecamatan') }}</textarea>
      </div>

      <div class="md:col-span-3">
        <label class="block text-sm font-medium">Kabupaten</label>
        <textarea name="{{$prefix}}[{{$krama_input_request}}][kabupaten]" rows="1" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">{{ old($oldPrefix.'.kabupaten') }}</textarea>
      </div>

      @if($krama_input_request === "tamiu")
        <div class="md:col-span-3">
          <label class="block text-sm font-medium">Provinsi</label>
          <textarea name="{{$prefix}}[{{$krama_input_request}}][provinsi]" rows="1" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">{{ old($oldPrefix.'.provinsi') }}</textarea>
        </div>
      @endif
    @endif
  @endif



  {{-- Data Adat --}}
  <div class="col-span-1 md:col-span-2 lg:col-span-3"><h4 class="font-semibold text-gray-600 border-b pb-1 mt-4">Data Adat</h4></div>

  <div class="md:col-span-3 bg-blue-50 p-3 rounded-md text-center">
    <p class="text-sm text-blue-800"><i class="fas fa-info-circle mr-1"></i> NIKA akan dibuat secara otomatis berdasarkan NIK Nasional.</p>
  </div>

  @if($prefix !== 'kk')
    <div>
      <label class="block text-sm font-medium">Status Hubungan Adat</label>
      <select name="{{$prefix}}[status_hubungan_adat]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
        @foreach(['suami','istri','anak','orang_tua','cucu','saudara','famili_lain','lainnya','tidak_diketahui'] as $val)
          <option value="{{$val}}" {{ old($oldPrefix.'.status_hubungan_adat') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>
        @endforeach
      </select>
    </div>
  @else
    <input type="hidden" name="{{$prefix}}[status_hubungan]" value="kepala_keluarga">
  @endif

  <div>
    <label class="block text-sm font-medium">Status di Banjar</label>
    <select name="{{$prefix}}[status_di_banjar]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
      @foreach(['aktif', 'pindah_keluar_banjar', 'meninggal', 'nonaktif_sementara', 'lainnya'] as $val)
        <option value="{{$val}}" {{ old($oldPrefix.'.status_di_banjar', 'aktif') == $val ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $val)) }}</option>
      @endforeach
    </select>
  </div>
</div>


{{--<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">--}}
{{--  --}}{{-- Data master_individu --}}
{{--  <div class="col-span-1 md:col-span-2 lg:col-span-3"><h4 class="font-semibold text-gray-600 border-b pb-1">Data Pribadi</h4></div>--}}
{{--  <div><label class="block text-sm font-medium">Nama Lengkap</label><input type="text" name="{{$prefix}}[nama_lengkap]" value="{{ $data['nama_lengkap'] ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>--}}
{{--  <div><label class="block text-sm font-medium">NIK Nasional</label><input type="text" name="{{$prefix}}[nik_nasional]" value="{{ $data['nik_nasional'] ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>--}}
{{--  <div><label class="block text-sm font-medium">Tempat Lahir</label><input type="text" name="{{$prefix}}[tempat_lahir]" value="{{ $data['tempat_lahir'] ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>--}}
{{--  <div><label class="block text-sm font-medium">Tanggal Lahir</label><input type="date" name="{{$prefix}}[tanggal_lahir]" value="{{ $data['tanggal_lahir'] ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>--}}
{{--  <div><label class="block text-sm font-medium">Jenis Kelamin</label><select name="{{$prefix}}[jenis_kelamin]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['perempuan','laki-laki','p','l','tidak_diketahui'] as $val)<option value="{{$val}}" {{ ($data['jenis_kelamin'] ?? '') == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>--}}
{{--  <div><label class="block text-sm font-medium">Agama</label><select name="{{$prefix}}[agama]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['islam','kristen protestan','katolik','hindu','buddha','khonghucu','kristen','lainnya'] as $val)<option value="{{$val}}" {{ ($data['agama'] ?? '') == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>--}}
{{--  <div><label class="block text-sm font-medium">Pendidikan</label><select name="{{$prefix}}[pendidikan]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['belum/tidak_sekolah','diploma_1','diploma_2','diploma_3','diploma_4','sarjana_terapan','strata_1','sarjana','strata_2','magister','strata_3','doktor','tk','sd','smp','sma','d1','d2','d3','d4','s1','s2','s3','lainnya','tidak_diketahui'] as $val)<option value="{{$val}}" {{ ($data['pendidikan'] ?? '') == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>--}}
{{--  <div><label class="block text-sm font-medium">Pekerjaan</label><select name="{{$prefix}}[pekerjaan]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['tidak_bekerja','belum/tidak_sekolah','pelajar/mahasiswa','ibu_rumah_tangga','pensiunan','pegawai_negeri_sipil','pegawai_swasta','wiraswasta','pengusaha','petani','peternak','nelayan','buruh','tni/polri','karyawan_bumn','karyawan_bumd','profesional','tenaga_medis','guru/dosen','seniman/artis','ojol/driver_online','pekerja_lepas','pekerja_serabutan','sopir','lainnya','tidak_diketahui'] as $val)<option value="{{$val}}" {{ ($data['pekerjaan'] ?? '') == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>--}}
{{--  <div><label class="block text-sm font-medium">Status Perkawinan</label><select name="{{$prefix}}[status_perkawinan]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['belum_kawin','kawin_tercatat','kawin_tidak_tercatat','kawin_siri','cerai_hidup_tercatat','cerai_hidup_tidak_tercatat','cerai_mati_tidak_tercatat','duda','janda','hidup_berdampingan','kawin','cerai_hidup','cerai_mati','single','married','divorced','widowed','separated','cohabitation','tidak_diketahui'] as $val)<option value="{{$val}}" {{ ($data['status_perkawinan'] ?? '') == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>--}}
{{--  <div><label class="block text-sm font-medium">Tgl Catat Kawin</label><input type="date" name="{{$prefix}}[tanggal_catat_kawin]" value="{{ $data['tanggal_catat_kawin'] ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>--}}
{{--  <div><label class="block text-sm font-medium">Kewarganegaraan</label><select name="{{$prefix}}[kewarganegaraan]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['wni','wna','wni_keturunan','wni_naturalisasi','wna_tinggal_tetap','wna_kerja','wna_diplomatik','bipatride','apatride','stateless','tidak_diketahui'] as $val)<option value="{{$val}}" {{ ($data['kewarganegaraan'] ?? '') == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>--}}
{{--  <div><label class="block text-sm font-medium">Golongan Darah</label><select name="{{$prefix}}[golongan_darah]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['A','B','AB','O','A+','A-','B+','B-','AB+','AB-','O+','O-','tidak_diketahui'] as $val)<option value="{{$val}}" {{ ($data['golongan_darah'] ?? '') == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>--}}
{{--  <div><label class="block text-sm font-medium">Nama Ayah</label><input type="text" name="{{$prefix}}[nama_ayah]" value="{{ $data['nama_ayah'] ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>--}}
{{--  <div><label class="block text-sm font-medium">Nama Ibu</label><input type="text" name="{{$prefix}}[nama_ibu]" value="{{ $data['nama_ibu'] ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>--}}
{{--  <div class="md:col-span-3"><label class="block text-sm font-medium">Alamat (Nasional)</label><textarea name="{{$prefix}}[alamat]" rows="2" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">{{ $data['alamat'] ?? '' }}</textarea></div>--}}

{{--  --}}{{-- Data master_adat --}}

{{--  <div><label class="block text-sm font-medium">NIKA</label><input type="text" name="{{$prefix}}[nika]" value="{{ $data['nika'] ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>--}}
{{--  <div class="col-span-1 md:col-span-2 lg:col-span-3"><h4 class="font-semibold text-gray-600 border-b pb-1 mt-4">Data Adat</h4></div>--}}
{{--  @if($prefix !== 'kk') --}}{{-- Hanya tampilkan untuk anggota, bukan kepala keluarga --}}
{{--  <div><label class="block text-sm font-medium">Status Hubungan</label><select name="{{$prefix}}[status_hubungan_adat]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['istri','anak','orang_tua','cucu','saudara','famili_lain','lainnya','tidak_diketahui'] as $val)<option value="{{$val}}" {{ ($data['status_hubungan_adat'] ?? '') == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>--}}
{{--  @endif--}}

{{--  <div><label class="block text-sm font-medium">Status di Banjar</label><select name="{{$prefix}}[status_di_banjar]" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['aktif', 'pindah_keluar_banjar', 'meninggal', 'nonaktif_sementara', 'lainnya'] as $val)<option value="{{$val}}" {{ ($data['status_di_banjar'] ?? '') == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>--}}
{{--</div>--}}


