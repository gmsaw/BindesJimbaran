@extends('layouts.dasboard-layout')

@section('maincontent')
<div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
                <p class="text-gray-600">Banjar: {{ $kk_adat->banjar->nama_banjar }}</p>
            </div>
            <div class="mt-4 sm:mt-0">
                {{-- PERBAIKAN: Kesalahan sintaks pada route() diperbaiki --}}
                <a href="{{ route('kependudukan.index', ['banjar' => $kk_adat->kode_banjar_fk]) }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar KK
                </a>
            </div>
        </div>

        {{-- Notifikasi Sukses atau Error --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p>{{ session('success') }}</p></div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><strong>Terdapat kesalahan validasi:</strong></p>
                <ul class="list-disc ml-5 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <!-- Form Ubah Keterangan Keluarga -->
        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-book-open mr-2 text-blue-500"></i>Keterangan Keluarga</h2>
            <form action="{{ route('kependudukan.update', ['npk' => $kk_adat->npk]) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="action" value="update_keterangan_keluarga">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="id_penatahan_fk" class="block text-sm font-medium text-gray-700 mb-1">Dadia / Penatahan</label>
                        <select name="id_penatahan_fk" id="id_penatahan_fk" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">-- Tidak Ada --</option>
                            @foreach ($all_dadia as $dadia)
                                @foreach($dadia->penatahan as $penatahan)
                                    <option value="{{ $penatahan->id }}" {{ old('id_penatahan_fk', $kk_adat->keteranganKeluarga->id_penatahan_fk ?? '') == $penatahan->id ? 'selected' : '' }}>
                                        {{ $dadia->nama_dadia }} / {{ $penatahan->nama_penatahan }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="keterangan_tambahan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
                        <textarea name="keterangan_tambahan" id="keterangan_tambahan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('keterangan_tambahan', $kk_adat->keteranganKeluarga->keterangan_tambahan ?? '') }}</textarea>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"><i class="fas fa-save mr-1"></i> Simpan Keterangan</button>
                </div>
            </form>
        </div>

        <!-- Form Ubah Kepala Keluarga -->
        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-user-shield mr-2 text-blue-500"></i>Ubah Kepala Keluarga (Pengarep)</h2>
            <form action="{{ route('kependudukan.update', ['npk' => $kk_adat->npk]) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="action" value="change_kepala_keluarga">
                <div class="flex items-end gap-4">
                    <div class="flex-1">
                        <label for="new_kepala_keluarga_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kepala Keluarga Baru</label>
                        <select name="new_kepala_keluarga_id" id="new_kepala_keluarga_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            @foreach ($anggota_list as $anggota)
                                <option value="{{ $anggota->id_keanggotaan }}" {{ $anggota->status_hubungan_adat == 'kepala_keluarga' ? 'selected' : '' }}>
                                    {{ $anggota->masterAdat->masterIndividu->nama_lengkap }} ({{ $anggota->masterAdat->nika }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition h-fit"><i class="fas fa-save mr-1"></i> Ganti Kepala Keluarga</button>
                </div>
            </form>
        </div>

        <!-- Daftar Anggota Keluarga -->
        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-2"><i class="fas fa-user-friends mr-2 text-blue-500"></i> Edit Anggota Keluarga</h2>
            @foreach ($anggota_list as $anggota)
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-100 px-4 py-3 flex justify-between items-center">
                        <h3 class="font-medium text-gray-800"><i class="fas fa-user mr-2"></i>{{ $anggota->masterAdat->masterIndividu->nama_lengkap }}@if($anggota->status_hubungan_adat == 'kepala_keluarga')<span class="ml-2 text-xs font-semibold text-blue-800 bg-blue-100 px-2 py-0.5 rounded-full">Kepala Keluarga</span>@endif</h3>
                        <a href="{{ route('kependudukan.edit', ['npk' => $kk_adat->npk, 'edit_anggota' => $anggota->id_keanggotaan]) }}" class="px-4 py-1 bg-white border border-gray-300 text-sm rounded-md hover:bg-gray-50 transition"><i class="fas fa-edit mr-1"></i> Edit</a>
                    </div>
                    @if($anggota_to_edit && $anggota_to_edit->id_keanggotaan == $anggota->id_keanggotaan)

              <div class="p-4 bg-gray-50">

                <form action="{{ route('kependudukan.update', ['npk' => $kk_adat->npk]) }}" method="POST">

                  @csrf

                  @method('PUT')

                  <input type="hidden" name="action" value="update_anggota">

                  <input type="hidden" name="id_keanggotaan" value="{{ $anggota_to_edit->id_keanggotaan }}">

                  <input type="hidden" name="id_individu" value="{{ $anggota_to_edit->masterAdat->masterIndividu->id }}">

                  <input type="hidden" name="id_identitas_adat" value="{{ $anggota_to_edit->masterAdat->id_identitas_adat }}">


                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                    {{-- Data master_individu --}}

                    <div class="col-span-1 md:col-span-2 lg:col-span-3"><h4 class="font-semibold text-gray-600 border-b pb-1">Data Pribadi</h4></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label><input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $anggota_to_edit->masterAdat->masterIndividu->nama_lengkap) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md"></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">NIK Nasional</label><input type="text" name="nik_nasional" value="{{ old('nik_nasional', $anggota_to_edit->masterAdat->masterIndividu->nik_nasional) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md"></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label><input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $anggota_to_edit->masterAdat->masterIndividu->tempat_lahir) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md"></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label><input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $anggota_to_edit->masterAdat->masterIndividu->tanggal_lahir) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md"></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label><select name="jenis_kelamin" class="w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['perempuan','laki-laki','p','l','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('jenis_kelamin', $anggota_to_edit->masterAdat->masterIndividu->jenis_kelamin) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Agama</label><select name="agama" class="w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['islam','kristen protestan','katolik','hindu','buddha','khonghucu','kristen','lainnya'] as $val)<option value="{{$val}}" {{ old('agama', $anggota_to_edit->masterAdat->masterIndividu->agama) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan</label><select name="pendidikan" class="w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['belum/tidak_sekolah','diploma_1','diploma_2','diploma_3','diploma_4','sarjana_terapan','strata_1','sarjana','strata_2','magister','strata_3','doktor','tk','sd','smp','sma','d1','d2','d3','d4','s1','s2','s3','lainnya','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('pendidikan', $anggota_to_edit->masterAdat->masterIndividu->pendidikan) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label><select name="pekerjaan" class="w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['tidak_bekerja','belum/tidak_sekolah','pelajar/mahasiswa','ibu_rumah_tangga','pensiunan','pegawai_negeri_sipil','pegawai_swasta','wiraswasta','pengusaha','petani','peternak','nelayan','buruh','tni/polri','karyawan_bumn','karyawan_bumd','profesional','tenaga_medis','guru/dosen','seniman/artis','ojol/driver_online','pekerja_lepas','pekerja_serabutan','sopir','lainnya','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('pekerjaan', $anggota_to_edit->masterAdat->masterIndividu->pekerjaan) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Status Perkawinan</label><select name="status_perkawinan" class="w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['belum_kawin','kawin_tercatat','kawin_tidak_tercatat','kawin_siri','cerai_hidup_tercatat','cerai_hidup_tidak_tercatat','cerai_mati_tidak_tercatat','duda','janda','hidup_berdampingan','kawin','cerai_hidup','cerai_mati','single','married','divorced','widowed','separated','cohabitation','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('status_perkawinan', $anggota_to_edit->masterAdat->masterIndividu->status_perkawinan) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Tgl Catat Kawin</label><input type="date" name="tanggal_catat_kawin" value="{{ old('tanggal_catat_kawin', $anggota_to_edit->masterAdat->masterIndividu->tanggal_catat_kawin) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md"></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Kewarganegaraan</label><select name="kewarganegaraan" class="w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['wni','wna','wni_keturunan','wni_naturalisasi','wna_tinggal_tetap','wna_kerja','wna_diplomatik','bipatride','apatride','stateless','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('kewarganegaraan', $anggota_to_edit->masterAdat->masterIndividu->kewarganegaraan) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Status Hubungan (Nasional)</label><select name="status_hubungan" class="w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['kepala_keluarga','istri','anak','orang_tua','cucu','saudara','famili_lain','lainnya','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('status_hubungan', $anggota_to_edit->masterAdat->masterIndividu->status_hubungan) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Golongan Darah</label><select name="golongan_darah" class="w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['A','B','AB','O','A+','A-','B+','B-','AB+','AB-','O+','O-','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('golongan_darah', $anggota_to_edit->masterAdat->masterIndividu->golongan_darah) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah</label><input type="text" name="nama_ayah" value="{{ old('nama_ayah', $anggota_to_edit->masterAdat->masterIndividu->nama_ayah) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md"></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu</label><input type="text" name="nama_ibu" value="{{ old('nama_ibu', $anggota_to_edit->masterAdat->masterIndividu->nama_ibu) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md"></div>

                    <div class="md:col-span-3"><label class="block text-sm font-medium text-gray-700 mb-1">Alamat (Nasional)</label><textarea name="alamat" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('alamat', $anggota_to_edit->masterAdat->masterIndividu->alamat) }}</textarea></div>


                    {{-- Data master_adat --}}

                    <div class="col-span-1 md:col-span-2 lg:col-span-3"><h4 class="font-semibold text-gray-600 border-b pb-1 mt-4">Data Adat</h4></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">NIKA</label><input type="text" name="nika" value="{{ old('nika', $anggota_to_edit->masterAdat->nika) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md"></div>

                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Status di Banjar</label><select name="status_di_banjar" class="w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['aktif','pindah_keluar_banjar','meninggal','nonaktif_sementara','lainnya'] as $val)<option value="{{$val}}" {{ old('status_di_banjar', $anggota_to_edit->masterAdat->status_di_banjar) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>


                  </div>

                  <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">

                    <a href="{{ route('kependudukan.edit', ['npk' => $kk_adat->npk]) }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition">

                      Batal

                    </a>

                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">

                      <i class="fas fa-save mr-1"></i> Simpan Anggota

                    </button>

                  </div>

                </form>

              </div>

            @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection