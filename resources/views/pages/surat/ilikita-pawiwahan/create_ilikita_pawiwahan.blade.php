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
      <form action="{{ route('surat.ilikita_pawiwahan.store') }}" method="POST">
        @csrf

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
          <div><h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1></div>
          <div class="mt-4 sm:mt-0 flex gap-x-2">
            <a href="{{ route('surat.indexMain') }}" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm">Batal</a>
            <a href="{{ route('surat.ilikita_pawiwahan.penomoran.index') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm"><i class="fas fa-database mr-2"></i>Penomoran</a>
            <a href="{{ route('surat.arsip.ilikita_pawiwahan') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm"><i class="fas fa-database mr-2"></i>Arsip</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm"><i class="fas fa-save mr-2"></i>Simpan Surat</button>
          </div>
        </div>

        @if(session('success'))
          <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p>{{ session('success') }}</p></div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">

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
                <button type="submit" formaction="{{ route('surat.ilikita_pawiwahan.create') }}" formmethod="GET" class="mt-1 px-4 py-2 bg-gray-200 rounded-md">Cari</button>
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
                <button type="submit" formaction="{{ route('surat.ilikita_pawiwahan.create') }}" formmethod="GET" class="mt-1 px-4 py-2 bg-gray-200 rounded-md">Cari</button>
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

          </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg mt-8">
          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Tambahan & Lampiran</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="block text-sm font-medium">Pemuput Karya</label><input type="text" name="pemuput_karya" value="{{ old('pemuput_karya') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
            <div><label class="block text-sm font-medium">Penanda Tangan (Bendesa)</label><select name="id_bendesa_fk" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">@foreach ($bendesa_aktif as $bendesa)<option value="{{ $bendesa->id }}">{{ $bendesa->nama_bendesa }}</option>@endforeach</select></div>

            {{-- PERUBAHAN: Tambahkan blok upload foto di sini --}}
            <div class="md:col-span-2">
              <label class="block text-sm font-medium mb-2">Foto Gandeng</label>
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
                  <input type="hidden" name="cropped_image_gandeng" id="cropped_image_gandeng">
                </div>
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
              aspectRatio: 6 / 4, // Bisa diatur jika perlu
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
