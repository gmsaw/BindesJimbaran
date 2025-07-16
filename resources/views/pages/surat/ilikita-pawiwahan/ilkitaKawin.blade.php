<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kartu Adat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  {{--    <link rel="stylesheet" href="{{ public_path('css/app.css') }}">--}}

  <!-- PERBAIKAN: Menambahkan link Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@400;700&display=swap" rel="stylesheet">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            'fuzzy-bubbles': ['"Fuzzy Bubbles"', 'cursive']
          }
        }
      }
    }
  </script>

  <style>
    @media print {
      @page {
        size: A4 portrait;
        margin: 0;
      }
      body {
        margin: 0;
      }
      .page-break {
        page-break-after: always;
      }
    }

    /* CSS untuk Watermark 1*/
    .watermark-container1 {
      position: relative; /* Diperlukan agar pseudo-element ::before bisa diposisikan */
      z-index: 0; /* Menciptakan stacking context baru */
    }

    .watermark-container1::before {
      content: ''; /* Pseudo-element membutuhkan properti content */
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      /* Ganti URL ini dengan path ke logo Anda menggunakan helper asset Laravel */
      background-image: url('{{ asset('images/border-ilkita-kawin.png') }}');
      background-repeat: no-repeat;
      background-position: center;
      background-size: 800px; /* Sesuaikan ukuran watermark sesuai kebutuhan */
      opacity: 0.8; /* Sesuaikan tingkat transparansi (0.05 - 0.2 biasanya bagus) */
      z-index: -1; /* Menempatkan watermark di belakang konten */
    }

    /* CSS untuk Watermark 1*/
    .watermark-container2 {
      position: relative; /* Diperlukan agar pseudo-element ::before bisa diposisikan */
      z-index: 0; /* Menciptakan stacking context baru */
    }

    .watermark-container2 {
      content: ''; /* Pseudo-element membutuhkan properti content */
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      /* Ganti URL ini dengan path ke logo Anda menggunakan helper asset Laravel */
      background-image: url('{{ asset('images/logo-desa-bw-ilkita.png') }}');
      background-repeat: no-repeat;
      background-position: center;
      background-size: 550px; /* Sesuaikan ukuran watermark sesuai kebutuhan */
      opacity: 1; /* Sesuaikan tingkat transparansi (0.05 - 0.2 biasanya bagus) */
      z-index: -1; /* Menempatkan watermark di belakang konten */
    }
  </style>
</head>
<body class="bg-white">

<div class="watermark-container1 watermark-container2 w-[210mm] h-[297mm] border mx-auto relative overflow-hidden">
  <!-- Header -->
  <div class="text-center font-fuzzy-bubbles">

    <div class="flex justify-center mb-4 mt-[4rem]">
      <img src="{{ asset('images/logo-desa.png') }}" alt="Logo Desa" class="h-24">
    </div>

    <div>
      <h1 class="text-3xl font-bold uppercase">ILIKITA WIWAHA</h1>
      <div class="flex justify-center">

        <div class="inline-block">
          <h2 class="text-3xl font-bold uppercase">DESA ADAT JIMBARAN</h2>
          <div class="h-1 bg-black mt-1"></div>  {{--garis bawah--}}
        </div>
      </div>
    </div>
  </div>
{{--  body surat--}}

  <div class="font-fuzzy-bubbles mt-4 text-[0.75rem] ml-[4.5rem]">
    <div class="flex mr-[3rem] font-bold">
      <p class="text-justify leading-[1.25rem]">OM SWASTYASTU,</p>
    </div>

    <div class="flex mr-[3rem] font-bold">
      <p class="text-justify leading-[1.25rem]">OM AWIGNAM ASTU NAMA SIDHAM</p>
    </div>

    <div class="flex mr-[3rem] text-[1.10rem] mt-4">
      <p class="text-justify leading-[1.25rem]">
        &emsp;&emsp;Maduluran antuk asung kertha wara nugraha Ida Sang Hyang Widhi Wasa ngenenin daging atur piuning parab I WAYAN MUDITA ( KELIHAN BR. ADAT TEGAL )
      </p>
    </div>

    <div class="flex mr-[3rem] text-[1.10rem]">
      <p class="text-justify leading-[1.25rem]">
        Desa Adat Jimbaran nyihnayang pastika lumaksana Pawiwahan Sang Alaki Rabi :
      </p>
    </div>

{{--    FORM PASANGAN--}}
    <!-- Bagian Purusa (Pria) -->
    <div class="mb-6 mt-4 ml-[1.5rem] text-[1.10rem] leading-[1.25rem]">
      <h3 class="font-bold">A.&nbsp;&nbsp;Purusa</h3>
      <div class="tml-8">
        <div class="grid grid-cols-[auto_1fr] gap-x-4 items-start">
          <!-- Baris Parab -->
          <span class="">Parab</span>
          <span class="font-bold">: I KADEK RIOS SATRIA DARMA</span>

          <!-- Baris Genah/Tg. Embas -->
          <span class="">Genah/Tg.Embas</span>
          <span>: Denpasar, 28-11-2005</span>

          <!-- Baris Yusa -->
          <span class="">Yusa</span>
          <span>: 19 Tahun</span>

          <!-- Baris Jenek Ring -->
          <span class="">Jenek Ring</span>
          <span>: Banjar/Lingk. Tegal Jimbaran</span>

        </div>
      </div>
    </div>

    <!-- Bagian Pradana (Wanita) -->
    <div class="text-[1.10rem] ml-[1.5rem] leading-[1.25rem]">
      <h3 class="font-bold ">B.&nbsp;&nbsp;Pradana</h3>
      <div class="tml-8">
        <div class="grid grid-cols-[auto_1fr] gap-x-4 items-start">

          <!-- Baris Parab -->
          <span class="">Parab</span>
          <span class="font-bold">: NI KETUT TRISNAWATI</span>

          <!-- Baris Genah/Tg. Embas -->
          <span class="">Genah/Tg.Embas</span>
          <span>: Denpasar, 11-05-2006</span>

          <!-- Baris Yusa -->
          <span class="">Yusa</span>
          <span>: 18 Tahun</span>

          <!-- Baris Jenek Ring -->
          <span class="">Jenek Ring</span>
          <span>: Banjar/Lingk. Babakan Sukawati</span>
        </div>
      </div>
    </div>


    <div class="flex mr-[3rem] text-[1.10rem] mt-2">
      <p class="text-justify leading-[1.25rem]">
        Kapuput oleh : <strong>Mangku I Wayan Pugir</strong>
      </p>
    </div>

    <div class="flex mr-[3rem] text-[1.10rem] mt-2">
      <p class="text-justify leading-[1.15rem]">
        Sang kalih ka Ilikitayang pastika alaki rabi ring pinanggal masehi 12 Januari 2025 antuk ngaturang pinunas yasa kerti marupa Pamijian sampun kaatur ring Desa Adat Jimbaran.
      </p>
    </div>

    <div class="flex mr-[3rem] font-bold mt-4">
      <p class="text-justify leading-[1.25rem]">OM SANTIH, SANTIH, SANTIH, OM.</p>
    </div>

    {{--  kolom foto dan tanda tangan--}}

    <div class="flex justify-between items-end ml-[2rem] mr-[5rem] mt-[1.5rem] text-[1rem]">

      <!-- Kolom Kiri: Tempat Foto -->
      <div>

        <div class="w-[6cm] h-[4cm] border-2 border-black flex items-center justify-center">
          <span class=" text-sm">Foto Gandeng</span>
        </div>
      </div>

      <!-- Kolom Kanan: Tempat Tanda Tangan -->
      {{-- 'text-center' untuk menengahkan teks di dalam kolom ini --}}
      <div class="text-center">
        <p>Jimbaran, 12 Januari 2025</p>
        <p>Kelihan Desa Adat Jimbaran,</p>

        {{-- Memberi jarak vertikal untuk area tanda tangan --}}
        <div class="h-20"></div>

        {{-- Garis bawah untuk nama --}}
        <p class="font-bold underline">I Gusti Made Rai Dirga Arsana Putra</p>
      </div>
    </div>
  </div>
</div>

</body>
</html>


