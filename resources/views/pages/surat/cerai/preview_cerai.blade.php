{{\Carbon\Carbon::setLocale('id')}}

{{--@dd($surat)--}}
  <!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Surat Cerai - {{$surat->id}}</title>
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
      <p class="text-xl uppercase underline leading-none">Surat Keterangan Perceraian Umat Hindu</p>
      <p class="text-xl leading-2 uppercase">Nomor : {{$surat->nomor_surat}}</p>
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
        &emsp;&emsp;&emsp;Pada hari ini, {{\Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('l') ?? ".................." }} tanggal : {{\Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') ?? ".................." }}, hadir di hadapan saya : {{$surat->bendesa->nama_bendesa}}, Kelihan Desa Adat Jimbaran, Desa/Kelurahan Jimbaran, Kecamatan Kuta Selatan, Kabupaten Badung, Provinsi Bali.
      </p>
    </div>

    <div class="mt-[1rem] text-center flex-1 uppercase font-bold">
      <p>
        " {{$surat->purusa_nama}} "
      </p>
    </div>
    <div class="ml-[5rem] mr-[3rem]">
      <p class="text-justify leading-[1.25rem]">
        Agama {{$surat->purusa_agama ?? ".................."}}, lahir di {{$tempat_lahir_purusa ?? ".................."}}, pada tanggal  {{\Carbon\Carbon::parse($tanggal_lahir_purusa)->translatedFormat('d F Y') ?? ".................."}}, Pekerjaan {{$surat->purusa_pekerjaan ?? ".................."}}, alamat tempat tinggal di Banjar/Lingk: {{$surat->purusa_banjar ?? ".................."}}, {{$surat->purusa_alamat}}.  &emsp;&emsp;&emsp;
      </p>
      <p class="text-justify leading-[1.25rem]">
        Anak laki-laki dari: <strong>{{$surat->purusa_nama_ayah ?? ".................."}}  dan {{$surat->purusa_nama_ibu ?? ".................."}} </strong>  bertempat tinggal Banjar/Lingk : {{$surat->purusa_banjar_orangtua ?? ".................."}} , {{$surat->purusa_alamat_orang_tua}}.
      </p>
    </div>
    <div>
      <div class="mt-[0.5rem] text-center flex-1">
        <p>
          dengan
        </p>
      </div>
      <div class="text-center flex-1 uppercase font-bold">
        <p>
          " {{$surat->pradana_nama}} "
        </p>
      </div>
    </div>
    <div>
      <div class="ml-[5rem] mr-[3rem]">
        <p class="text-justify leading-[1.25rem]">
          Agama {{$surat->pradana_agama ?? ".................."}}, lahir di {{$tempat_lahir_pradana ?? ".................."}}, pada tanggal {{\Carbon\Carbon::parse($tanggal_lahir_pradana)->translatedFormat('d F Y') ?? ".................."}}, Pekerjaan {{$surat->pradana_pekerjaan ?? ".................."}}, alamat tempat tinggal di Banjar/Lingk: {{$surat->pradana_banjar ?? ".................."}}, {{$surat -> pradana_alamat}}.
        </p>
        <p class="text-justify leading-[1.25rem]">
          Anak perempuan dari: <strong> {{$surat->pradana_nama_ayah ?? ".................."}}  dan {{$surat->pradana_nama_ibu ?? ".................."}} </strong> bertempat tinggal Banjar/Lingk : {{$surat->pradana_banjar_orangtua ?? ".................."}} , {{$surat -> pradana_alamat_orang_tua}}.
        </p>
      </div>
      <div>
        <div class="ml-[5rem] mr-[3rem] mt-[0.75rem] leading-[1.25rem]">
          <p>Mereka telah melangsungkan tata cara perceraian secara agama : Hindu</p>
          <p class="text-justify">Pada tanggal : {{\Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') ?? ".................." }}, atas dasar kesepakatan kedua belah pihak disaksikan oleh pihak keluarga laki-laki dan pihak keluarga perempuan, beserta aparat Desa Adat dan Dinas.</p>
        </div>
      </div>

      <div class="flex ml-[5rem] mr-[3rem] mt-[0.75rem]">
        <p class="text-justify leading-[1.25rem]">
          &emsp;&emsp;&emsp;Selanjutnya karna syarat-syarat dan upacara agama perceraian telah dilaksanakan, maka saya nyatakan bahwa keduanya telah syah bercerai secara Agama Hindu dan mengakhiri hubungan sebagai pasangan suami istri.
        </p>
      </div>

      <div class="flex ml-[5rem] mr-[3rem] mt-[0.75rem]">
        <p class="text-justify leading-[1.25rem]">
          &emsp;&emsp;&emsp;Dari hal tersebut diterbitkanlah Surat Keterangan ini yang sesudah dibacakan dan di jelaskan, ditandatangani kedua belah pihak suami dan istri, saksi-saksi dan saya Kelihan Desa Adat Jimbaran.
        </p>
      </div>
    </div>
  </div>

  <div class="mt-10 mb-4 flex justify-center font-serif leading-snug">
    {{-- Container flex yang menampung kedua kolom tanda tangan --}}
    <div class="flex gap-x-20 text-sm">
      <!-- Kolom Kiri: Rohaniawan/Pemuput -->
      <div class="text-center w-64">

        @if(empty($surat->pemuput_karya))
          <p>&nbsp;</p>
        @else
          <p>Rohaniawan/Pemuput</p>
          <div class="h-20"></div>
          <p>
            <strong>{{ $surat->pemuput_karya }}</strong>
          </p>
        @endif


      </div>

      <!-- Kolom Kanan: Bendesa Adat -->
      <div class="text-center w-64]">
        {{--        <p>Jimbaran, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>--}}
        <p>Bendesa Adat</p>
        <div class="h-20"></div>
        <p>
          <strong> {{ $bendesa->nama_bendesa ?? '............................' }} </strong>
        </p>
      </div>

    </div>
  </div>

</div>

<div class="page-break"></div>

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

  {{--Tanda Tangan Lanjutan--}}

  {{--  Para Pihak--}}
  <div class="mt-[2rem] mb-2 flex justify-center font-serif leading-snug">
    <p class="font-bold">Para Pihak</p>
  </div>
  <div class="mb-4 flex justify-center font-serif leading-snug">

    {{-- Container flex yang menampung kedua kolom tanda tangan --}}
    <div class="flex gap-x-20 text-sm">
      <!-- Kolom Kiri: suami -->
      <div class="text-center w-64">
        {{--        <p>&nbsp;</p>--}}
        <p>Suami</p>
        <div class="h-20"></div>
        <p>
          @php
            $purusa_nama_lower = strtolower($surat -> purusa_nama);
            $purusa_nama_uc = ucwords($purusa_nama_lower);
          @endphp
          <strong> {{ $purusa_nama_uc ?? '............................'  }} </strong>
        </p>
      </div>

      <!-- Kolom Kanan: istri -->
      <div class="text-center w-64">
        {{--        <p>Jimbaran, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>--}}
        <p>Istri</p>
        <div class="h-20"></div>
        <p>
          @php
            $pradana_nama_lower = strtolower($surat -> pradana_nama);
            $pradana_nama_uc = ucwords($pradana_nama_lower);
          @endphp
          <strong> {{ $pradana_nama_uc ?? '............................'  }} </strong>
        </p>
      </div>
    </div>
  </div>

  {{--  Para Saksi--}}
  <div class="mt-8 mb-2 flex justify-center font-serif leading-snug">
    <p class="font-bold">Saksi</p>
  </div>
  <div class="mb-4 flex justify-center font-serif leading-snug">

    {{-- Container flex yang menampung kedua kolom tanda tangan --}}
    <div class="flex gap-x-20 text-sm">
      <!-- Kolom Kiri: Saksi 1 -->
      <div class="text-center w-64">
        {{--        <p>&nbsp;</p>--}}
        <p>Saksi I</p>
        <div class="h-20"></div>
        <p>
          <strong> {{ $surat->saksi_1 ?? '............................'  }} </strong>
        </p>
      </div>

      <!-- Kolom Kanan: Saksi 2 -->
      <div class="text-center w-64">
        {{--        <p>Jimbaran, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>--}}
        <p>Saksi II</p>
        <div class="h-20"></div>
        <p>
          <strong> {{ $surat->saksi_2 ?? '............................'  }} </strong>
        </p>
      </div>
    </div>
  </div>

  {{--  Mengtahui Lingkungan--}}
  <div class="mt-8 mb-2 flex justify-center font-serif leading-snug">
    <p class="font-bold">Mengetahui</p>
  </div>
  <div class="mb-4 flex justify-center font-serif leading-snug">

    {{-- Container flex yang menampung kedua kolom tanda tangan --}}
    <div class="flex gap-x-20 text-sm">
      <!-- Kolom Kiri: Saksi 1 -->
      <div class="text-center w-64">
        {{--        <p>&nbsp;</p>--}}
        <p>Kepala Lingkungan</p>
        <div class="h-20"></div>
        <p>
          <strong> {{ $surat->kepala_lingkungan ?? '............................'  }} </strong>
        </p>
      </div>
      <!-- Kolom Kanan: Saksi 2 -->
      <div class="text-center w-64">
        {{--        <p>Jimbaran, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>--}}
        @php
          $holder_lingkungan_banjar = strtolower($surat->lingkungan_banjar);
          $holder2_lingukngan_banjar = ucwords($holder_lingkungan_banjar);
        @endphp
        <p>Kelihan Adat Banjar {{$holder2_lingukngan_banjar}}</p>
        <div class="h-20"></div>
        <p>
          <strong> {{ $surat->kelihan_adat_banjar ?? '............................'  }} </strong>
        </p>
      </div>
    </div>
  </div>

  <div class="mt-8 mb-4 flex justify-center font-serif leading-snug">

    {{-- Container flex yang menampung kedua kolom tanda tangan --}}
    <div class="flex gap-x-20 text-sm">
      <!-- Kolom Kiri: Saksi 1 -->
      <div class="text-center w-64">
        {{--        <p>&nbsp;</p>--}}
        <p>Lurah</p>
        <div class="h-20"></div>
        <p>
          <strong> {{ $surat->lurah ?? '............................'  }} </strong>
        </p>
      </div>

      <!-- Kolom Kanan: Saksi 2 -->
      <div class="text-center w-64">
        {{--        <p>Jimbaran, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>--}}
        <p>Camat</p>
        <div class="h-20"></div>
        <p>
          <strong> {{ $surat->camat ?? '............................'  }} </strong>
        </p>
      </div>
    </div>
  </div>
</div>

</body>
</html>

