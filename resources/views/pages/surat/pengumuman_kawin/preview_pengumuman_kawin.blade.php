{{\Carbon\Carbon::setLocale('id')}}

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Surat Pengumuman Kawin - {{$surat->id}}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  {{--    <link rel="stylesheet" href="{{ public_path('css/app.css') }}">--}}

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

    /* CSS untuk Watermark */
    .watermark-container {
      position: relative; /* Diperlukan agar pseudo-element ::before bisa diposisikan */
      z-index: 0; /* Menciptakan stacking context baru */
    }

    .watermark-container::before {
      content: ''; /* Pseudo-element membutuhkan properti content */
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      /* Ganti URL ini dengan path ke logo Anda menggunakan helper asset Laravel */
      background-image: url('{{ asset('images/logo-desa-bw.png') }}');
      background-repeat: no-repeat;
      background-position: center;
      background-size: 600px; /* Sesuaikan ukuran watermark sesuai kebutuhan */
      opacity: 0.5; /* Sesuaikan tingkat transparansi (0.05 - 0.2 biasanya bagus) */
      z-index: -1; /* Menempatkan watermark di belakang konten */
    }
  </style>
</head>
<body class="bg-white">

<div class="watermark-container w-[210mm] h-[297mm] border mx-auto relative overflow-hidden">

  <!-- Header -->
  <div class="bg-white">
    <div class="flex items-center justify center">
      <div class="ml-[4rem]"></div>
      <div>
        <img src="{{ asset('images/header-adat.png') }}" alt="Logo Desa" class="h-40">
      </div>
      <div></div>
    </div>
  </div>

  {{--  Penomoran--}}
  <div class="font-serif">
    <div class="text-center flex-1">
      <p class="text-xl uppercase underline leading-none">Pengumuman</p>
      <p class="text-xl leading-2 uppercase">Nomor : {{$surat->nomor_surat ?? ".................."}}</p>
    </div>
  </div>

  {{--  Body Surat--}}

  @php
    // Memecah string TTL menjadi array [Tempat, Tanggal]
    $ttl_purusa_parts = explode(', ', $surat->purusa_ttl);
    $tempat_lahir_purusa = $ttl_purusa_parts[0] ?? ''; // Ambil bagian pertama (Tempat)
    $tanggal_lahir_purusa = $ttl_purusa_parts[1] ?? ''; // Ambil bagian kedua (Tanggal)

    $ttl_pradana_parts = explode(', ', $surat->pradana_ttl);
    $tempat_lahir_pradana = $ttl_pradana_parts[0] ?? ''; // Ambil bagian pertama (Tempat)
    $tanggal_lahir_pradana = $ttl_pradana_parts[1] ?? ''; // Ambil bagian kedua (Tanggal)
  @endphp

  <div class="font-serif mt-4">
    <div class="flex ml-[5rem] mr-[3rem]">
      <p class="text-justify leading-[1.25rem]">
        &emsp;&emsp;&emsp;Pada hari ini {{$surat->hari_surat ?? ".................."}} tanggal : {{$surat->tanggal_surat ?? ".................."}}. Saya: Pembantu Pegawai Pencatat Perkawinan Umat Hindu Desa Adat Jimbaran.
      </p>
    </div>

    <div class="flex ml-[5rem] mr-[3rem]">
      <p class="text-justify leading-[1.25rem]">
        &emsp;&emsp;&emsp;Mengumumkan di Kantor tempat penyelenggara daftar-daftar Pencatatan Sipil, bahwa Tempat Lingk. {{$surat->lingkungan_banjar ?? ".................."}} , Desa / Kelurahan Jimbaran, Kecamatan Kuta Selatan, Kabupaten Badung bermaksud hendak melangsungkan perkawinan ; Antara
      </p>
    </div>

    {{--    Suami--}}
    <div class="flex ml-[5rem] mr-[3rem]">
      <p class="text-justify leading-[1.25rem] mt-2">
        Nama Suami : <strong>{{$surat->purusa_nama ?? ".................."}}</strong>
      </p>
    </div>

    <div class="flex ml-[5rem] mr-[3rem] mt-2">
      <p class="text-justify leading-[1.25rem]">
        Agama {{$surat->purusa_agama ?? ".................."}}, lahir di {{$tempat_lahir_purusa ?? ".................."}}, pada tanggal  {{\Carbon\Carbon::parse($tanggal_lahir_purusa)->translatedFormat('d F Y') ?? ".................."}}, Pekerjaan {{$surat->purusa_pekerjaan ?? ".................."}}, alamat tempat tinggal di Banjar/Lingk: {{$surat->purusa_banjar ?? ".................."}}, {{$surat->purusa_alamat}}.  &emsp;&emsp;&emsp;
      </p>
    </div>

    <div class="flex ml-[5rem] mr-[3rem]">
      <p class="text-justify leading-[1.25rem] mt-2">
        Anak laki-laki dari: {{$surat->purusa_nama_ayah ?? ".................."}}  dan {{$surat->purusa_nama_ibu ?? ".................."}}   bertempat tinggal Banjar/Lingk : {{$surat->purusa_banjar_orangtua ?? ".................."}}, {{$surat->purusa_alamat_orang_tua}}.
      </p>
    </div>

    <div class="mt-2 text-center flex-1">
      <p>
        Dengan
      </p>
    </div>

    {{--    Istri --}}
    <div class="flex ml-[5rem] mr-[3rem]">
      <p class="text-justify leading-[1.25rem] mt-2">
        Nama Istri : <strong>{{$surat->pradana_nama ?? ".................."}}</strong>
      </p>
    </div>

    <div class="flex ml-[5rem] mr-[3rem] mt-2">
      <p class="text-justify leading-[1.25rem]">
        Agama {{$surat->pradana_agama ?? ".................."}}, lahir di {{$tempat_lahir_pradana ?? ".................."}}, pada tanggal {{\Carbon\Carbon::parse($tanggal_lahir_pradana)->translatedFormat('d F Y') ?? ".................."}}, Pekerjaan {{$surat->pradana_pekerjaan ?? ".................."}}, alamat tempat tinggal di Banjar/Lingk: {{$surat->pradana_banjar ?? ".................."}}, {{$surat->pradana_alamat}}.
      </p>
    </div>

    <div class="flex ml-[5rem] mr-[3rem]">
      <p class="text-justify leading-[1.25rem] mt-2">
        Anak perempuan dari: {{$surat->pradana_nama_ayah ?? ".................."}}  dan {{$surat->pradana_nama_ibu ?? ".................."}}   bertempat tinggal Banjar/Lingk : {{$surat->pradana_banjar_orangtua ?? ".................."}}, {{$surat->pradana_alamat_orang_tua}}.
      </p>
    </div>

    <div class="flex ml-[5rem] mr-[3rem]">
      <p class="text-justify leading-[1.25rem] mt-2">
        Kapuput oleh : {{$surat->pemuput_karya ?? ".................."}}
      </p>
    </div>

    <div class="flex ml-[5rem] mr-[3rem]">
      <p class="text-justify leading-[1.25rem] mt-2">
        Demikian pengumuman ini yang telah saya buat dan saya tanda tangani.
      </p>
    </div>

    {{-- Container Flexbox untuk mendorong blok ke kanan --}}
    <div class="flex justify-end mt-6">

      {{-- Blok tanda tangan --}}
      {{-- Lebar tetap (w-72) agar konsisten --}}
      <div class="text-left w-120 mr-8">
        {{-- Ganti dengan variabel tanggal Anda --}}
        <p>Pembantu Pegawai Pencatata</p>
        <p>Perkawinan Umat Hindu</p>
        <p>Desa Adat Jimbaran,</p>

        {{-- Memberi jarak vertikal untuk area tanda tangan --}}
        <div class="h-24"></div>

        <p class="font-bold">
          {{-- Ganti dengan variabel nama bendesa Anda --}}
          {{$bendesa->nama_bendesa ?? ".................."}}
        </p>

      </div>
    </div>
  </div>
</div>
</body>
</html>


