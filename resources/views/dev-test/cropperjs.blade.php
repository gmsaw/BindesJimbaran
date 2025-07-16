<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contoh Sederhana Cropper.js</title>

  <!-- 1. Muat Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- 2. Muat CSS Cropper.js -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

  <style>
    /* Pastikan gambar di dalam cropper tidak melebihi containernya */
    .cropper-container img {
      max-width: 100%;
    }
  </style>
</head>
<body class="bg-gray-100 p-4 sm:p-8">

<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
  <h1 class="text-2xl font-bold text-center mb-6">Contoh Cropper.js</h1>

  <!-- Input untuk memilih file -->
  <div class="mb-6">
    <label for="file-input" class="block mb-2 text-sm font-medium text-gray-700">Pilih Gambar:</label>
    <input type="file" id="file-input" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
  </div>

  <!-- Area Cropper dan Pratinjau -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

    <!-- Kolom Kiri: Tempat Cropper Bekerja -->
    <div>
      <h2 class="text-lg font-semibold mb-2">Area Pemotongan</h2>
      <div class="w-full h-80 bg-gray-200 border border-dashed rounded-md cropper-container">
        <img id="image-to-crop" class="hidden">
      </div>
    </div>

    <!-- Kolom Kanan: Pratinjau Hasil -->
    <div>
      <h2 class="text-lg font-semibold mb-2">Pratinjau Hasil</h2>
      <div id="preview-container" class="w-48 h-48 border border-gray-300 rounded-md overflow-hidden mx-auto bg-gray-100 bg-cover bg-center">
      </div>
    </div>
  </div>

  <!-- Tombol Aksi -->
  <div class="mt-6 text-center">
    <button id="crop-button" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
      <i class="fas fa-crop-alt"></i> Potong Gambar
    </button>
  </div>

</div>

<!-- 3. Muat JS Cropper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Definisikan semua elemen yang kita butuhkan
    const imageToCrop = document.getElementById('image-to-crop');
    const fileInput = document.getElementById('file-input');
    const cropButton = document.getElementById('crop-button');
    const previewContainer = document.getElementById('preview-container');
    let cropper; // Variabel untuk menyimpan instance Cropper

    // 1. Event listener saat pengguna memilih file
    fileInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (!file) {
        return;
      }

      // Gunakan FileReader untuk membaca file sebagai URL
      const reader = new FileReader();
      reader.onload = (event) => {
        // Tampilkan gambar di area pemotongan
        imageToCrop.src = event.target.result;
        imageToCrop.classList.remove('hidden');

        // Hancurkan instance cropper lama jika ada
        if (cropper) {
          cropper.destroy();
        }

        // 2. Inisialisasi Cropper.js pada gambar
        cropper = new Cropper(imageToCrop, {
          aspectRatio: 1 / 1, // Rasio 1:1 (kotak). Ganti sesuai kebutuhan, misal: 16 / 9
          viewMode: 1, // Batasi area potong agar tidak keluar dari gambar
          preview: previewContainer, // Tautkan dengan div pratinjau
        });

        // Aktifkan tombol potong
        cropButton.disabled = false;
      };
      reader.readAsDataURL(file);
    });

    // 3. Event listener saat tombol "Potong Gambar" diklik
    cropButton.addEventListener('click', () => {
      if (!cropper) {
        return;
      }

      // Dapatkan hasil potongan dalam bentuk canvas
      const canvas = cropper.getCroppedCanvas({
        width: 256,
        height: 256,
      });

      // Anda bisa melakukan sesuatu dengan canvas ini,
      // misalnya mengubahnya menjadi data URL atau Blob untuk dikirim ke server.
      canvas.toBlob((blob) => {
        const formData = new FormData();

        // Simulasikan pengiriman ke server
        formData.append('croppedImage', blob, 'cropped-image.jpg');

        // Di sini Anda bisa menggunakan fetch() untuk mengirim formData ke backend Laravel Anda
        console.log('Data Blob siap dikirim:', blob);
        alert('Gambar berhasil dipotong! Lihat console log untuk data Blob-nya.');

        // Contoh menampilkan hasil akhir di pratinjau secara eksplisit (opsional, karena preview sudah otomatis)
        const url = URL.createObjectURL(blob);
        previewContainer.style.backgroundImage = `url('${url}')`;

      }, 'image/jpeg');
    });
  });
</script>
</body>
</html>
