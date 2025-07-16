@extends('layouts.dasboard-layout')

@section('styles')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
  <style>
    #cropper-container { width: 100%; height: 350px; background-color: #f3f4f6; }
    #image-to-crop { display: block; max-width: 100%; max-height: 350px; }
  </style>
@endsection

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
      <form action="{{ route('penduduk.update', ['nika' => $krama->nika]) }}" method="POST" id="main-form">
        @csrf
        @method('PUT')

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
            <p class="text-gray-600">NIKA: {{ $krama->nika }} | Banjar: {{ $krama->banjar->nama_banjar }}</p>
          </div>
          <div class="mt-4 sm:mt-0 flex gap-x-2">
            <a href="{{ route('penduduk.index', ['krama_request'=> $krama_request, 'banjar' => $krama->kode_banjar_fk]) }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm">
              <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <button type="submit" name="action" value="update_data" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm">
              <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
          </div>
        </div>

        @if(session('success'))
          <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p>{{ session('success') }}</p></div>
        @endif
        @if ($errors->any())
          <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p><strong>Terdapat kesalahan validasi:</strong></p>
            <ul class="list-disc ml-5 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
          </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-lg">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
              <h3 class="font-semibold text-gray-700 mb-2">Foto Profil</h3>
              <div class="flex flex-col items-center">
                <div id="preview-area">
                  <img id="image-preview" src="{{ asset($krama->masterIndividu->path_foto ?? 'storage/photos/blank-space/blank-profile-picture.webp') }}" alt="Foto Profil" class="w-48 h-[225px] object-cover rounded-lg shadow-md mb-4">
                </div>

                <div id="cropper-ui-container" class="w-full hidden space-y-2">
                  <div id="cropper-container"><img id="image-to-crop"></div>
                  <div class="flex justify-center gap-x-2">
                    <button id="crop-btn" type="button" class="px-3 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700">Potong & Gunakan</button>
                    <button id="cancel-crop-btn" type="button" class="px-3 py-1 bg-gray-200 text-xs rounded-md hover:bg-gray-300">Batal</button>
                  </div>
                </div>

                <div id="upload-area" class="w-full">
                  <label for="file-input" class="w-full text-center block mt-4 px-4 py-2 bg-blue-50 text-blue-700 text-sm rounded-md hover:bg-blue-100 cursor-pointer">
                    <i class="fas fa-upload mr-1"></i> Ganti Foto
                  </label>
                  <input type="file" id="file-input" class="hidden" accept="image/*">
                  <input type="hidden" name="cropped_image" id="cropped_image">
                  <p class="text-xs text-gray-500 mt-1 text-center">PNG, JPG, GIF (maks. 2MB)</p>
                </div>

                @if($krama->masterIndividu->path_foto)
                  <div class="w-full mt-3 pt-3 border-t">
                    <button type="submit" name="action" value="delete_photo" class="w-full text-center px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition" onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?');">
                      <i class="fas fa-trash-alt mr-1"></i> Hapus Foto
                    </button>
                  </div>
                @endif
              </div>
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
              <h3 class="md:col-span-2 font-semibold text-gray-700 border-b pb-2">Data Pribadi</h3>
              <div><label class="block text-sm font-medium text-gray-700">Nama Lengkap</label><input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $krama->masterIndividu->nama_lengkap) }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
              <div><label class="block text-sm font-medium text-gray-700">NIK Nasional</label><input type="text" name="nik_nasional" value="{{ old('nik_nasional', $krama->masterIndividu->nik_nasional) }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
              <div><label class="block text-sm font-medium text-gray-700">Tempat Lahir</label><input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $krama->masterIndividu->tempat_lahir) }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
              <div><label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label><input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $krama->masterIndividu->tanggal_lahir) }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
              <div><label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label><select name="jenis_kelamin" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['perempuan','laki-laki','p','l','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('jenis_kelamin', $krama->masterIndividu->jenis_kelamin) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>
              <div><label class="block text-sm font-medium text-gray-700">Agama</label><select name="agama" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['islam','kristen protestan','katolik','hindu','buddha','khonghucu','kristen','lainnya'] as $val)<option value="{{$val}}" {{ old('agama', $krama->masterIndividu->agama) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>
              <div><label class="block text-sm font-medium text-gray-700">Pendidikan</label><select name="pendidikan" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['belum/tidak_sekolah','diploma_1','diploma_2','diploma_3','diploma_4','sarjana_terapan','strata_1','sarjana','strata_2','magister','strata_3','doktor','tk','sd','smp','sma','d1','d2','d3','d4','s1','s2','s3','lainnya','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('pendidikan', $krama->masterIndividu->pendidikan) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>
              <div><label class="block text-sm font-medium text-gray-700">Pekerjaan</label><select name="pekerjaan" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['tidak_bekerja','belum/tidak_sekolah','pelajar/mahasiswa','ibu_rumah_tangga','pensiunan','pegawai_negeri_sipil','pegawai_swasta','wiraswasta','pengusaha','petani','peternak','nelayan','buruh','tni/polri','karyawan_bumn','karyawan_bumd','profesional','tenaga_medis','guru/dosen','seniman/artis','ojol/driver_online','pekerja_lepas','pekerja_serabutan','sopir','lainnya','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('pekerjaan', $krama->masterIndividu->pekerjaan) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>
              <div><label class="block text-sm font-medium text-gray-700">Status Perkawinan</label><select name="status_perkawinan" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['belum_kawin','kawin_tercatat','kawin_tidak_tercatat','kawin_siri','cerai_hidup_tercatat','cerai_hidup_tidak_tercatat','cerai_mati_tidak_tercatat','duda','janda','hidup_berdampingan','kawin','cerai_hidup','cerai_mati','single','married','divorced','widowed','separated','cohabitation','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('status_perkawinan', $krama->masterIndividu->status_perkawinan) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>
              <div><label class="block text-sm font-medium text-gray-700">Golongan Darah</label><select name="golongan_darah" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach(['A','B','AB','O','A+','A-','B+','B-','AB+','AB-','O+','O-','tidak_diketahui'] as $val)<option value="{{$val}}" {{ old('golongan_darah', $krama->masterIndividu->golongan_darah) == $val ? 'selected' : '' }}>{{$val}}</option>@endforeach</select></div>
              <div><label class="block text-sm font-medium text-gray-700">Nama Ayah</label><input type="text" name="nama_ayah" value="{{ old('nama_ayah', $krama->masterIndividu->nama_ayah) }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
              <div><label class="block text-sm font-medium text-gray-700">Nama Ibu</label><input type="text" name="nama_ibu" value="{{ old('nama_ibu', $krama->masterIndividu->nama_ibu) }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
              <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700">Alamat (Nasional)</label><textarea name="alamat" rows="2" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('alamat', $krama->masterIndividu->alamat) }}</textarea></div>
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
    document.addEventListener('DOMContentLoaded', function () {
      const cropperUiContainer = document.getElementById('cropper-ui-container');
      const imageToCrop = document.getElementById('image-to-crop');
      const fileInput = document.getElementById('file-input');
      const cropBtn = document.getElementById('crop-btn');
      const cancelBtn = document.getElementById('cancel-crop-btn');
      const imagePreview = document.getElementById('image-preview');
      const previewArea = document.getElementById('preview-area');
      const uploadArea = document.getElementById('upload-area');
      const hiddenInput = document.getElementById('cropped_image');
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
              aspectRatio: 25 / 30, viewMode: 1, responsive: true, background: false
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
        const croppedCanvas = cropper.getCroppedCanvas({ width: 250, height: 300 });
        const croppedImageData = croppedCanvas.toDataURL('image/jpeg');
        imagePreview.src = croppedImageData;
        hiddenInput.value = croppedImageData;
        showPreview();
      });

      cancelBtn.addEventListener('click', function () {
        showPreview();
      });
    });
  </script>
@endpush
