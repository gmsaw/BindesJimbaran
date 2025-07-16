<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>{{ $title }} - {{ $krama->masterIndividu->nama_lengkap }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      @page {
        size: 89.92mm 105mm; /* Ukuran custom sesuai contoh Anda */
        margin: 0;
      }
      body {
        margin: 0;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      .page-break {
        page-break-after: always;
      }
    }
    body {
      font-family: 'Arial', sans-serif;
    }
    .card-page {
      width: 89.92mm;
      height: 105mm;
      box-sizing: border-box;
    }
  </style>
</head>
<body class="bg-gray-200">

{{-- Halaman 1 (Depan) --}}
<div class="card-page bg-white mx-auto relative overflow-hidden flex flex-col">
  <!-- Header -->
  <div class="bg-red-600">
    <div class="flex gap-x-4 justify-between items-center p-1">
      <div class="ml-4">
        <img src="{{ asset('images/logo-mda.png') }}" alt="Logo MDA" class="h-10">
      </div>
      <div class="text-center flex-1 text-white">
        <h1 class="text-[14px] font-bold uppercase">Kartu Krama Adat</h1>
        <h2 class="text-[11px] font-bold uppercase">Desa Adat Jimbaran</h2>
        <p class="text-[8px] font-bold">Kecamatan Kuta Selatan, Kabupaten Badung</p>
      </div>
      <div class="mr-4">
        <img src="{{ asset('images/logo-desa.png') }}" alt="Logo Desa" class="h-10">
      </div>
    </div>
  </div>

  {{-- Body --}}
  <div class="flex-grow flex justify-between items-start mt-3 px-3">
    <div class="space-y-1.5 text-[10px] leading-tight">
      <div class="flex">
        <div class="w-20 text-left">NIKA</div>
        <div class="pl-0">: {{ $krama->nika ?? '..................' }}</div>
      </div>
      <div class="flex items-start">
        <div class="w-20 text-left shrink-0">Nama</div>
        <div class="pl-0 shrink-0 mr-1">:</div>
        <div class="break-words font-semibold">{{ ucwords($krama->masterIndividu->nama_lengkap) ?? '..................' }}</div>
      </div>
      <div class="flex">
        <div class="w-20 text-left">Klasifikasi</div>
        <div class="pl-0">: {{ ucwords($klasifikasi_krama) ?? '..................' }}</div>
      </div>
      <div class="flex">
        <div class="w-20 text-left">Jenis Kelamin</div>
        <div class="pl-0 uppercase">: {{ ucwords($krama->masterIndividu->jenis_kelamin) ?? '..................' }}</div>
      </div>
      <div class="flex">
        <div class="w-20 text-left">TTL</div>
        <div class="pl-0 shrink-0 mr-1">:</div>
        <div>
          {{ ucwords($krama->masterIndividu->tempat_lahir) ?? '.....' }},
        </div>
      </div>
      <div class="flex">
        <div class="w-20 text-left"></div>
        <div class="pl-0 shrink-0 mr-1">&nbsp</div>
        <div>
          {{ \Carbon\Carbon::parse($krama->masterIndividu->tanggal_lahir)->translatedFormat('d F Y') ?? '.....' }}
        </div>
      </div>
      <div class="flex">
        <div class="w-20 text-left">Pekerjaan</div>
        <div class="pl-0">: {{ ucwords($krama->masterIndividu->pekerjaan) ?? '..................' }}</div>
      </div>
      <div class="flex">
        <div class="w-20 text-left">Banjar</div>
        <div class="pl-0">: {{ ucwords($krama->banjar->nama_banjar) ?? '..................' }}</div>
      </div>
      <div class="flex">
        <div class="w-20 text-left pr-14">Alamat</div>
        <div>: {{ ucwords($krama->masterIndividu->alamat) ?? '..................' }}</div>
      </div>
      <div class="flex mt-2">
        <div class="w-20 text-left">Keterangan</div>
        <div class="pl-0">: ............................</div>
      </div>
    </div>

    <div class="flex-shrink-0 flex flex-col items-center space-y-4">
      <div class="w-[25mm] h-[30mm] border border-gray-700 flex items-center justify-center text-[10px] bg-gray-100">
        <img src="{{ asset($krama->masterIndividu->path_foto) }}" alt="Foto Profil">
      </div>
      <div class="text-[10px] text-center">
        <p>Berlaku S/D</p>
        <p>{{ \Carbon\Carbon::now()->addYears($berlaku)->translatedFormat('d F Y') }}</p>
      </div>
    </div>
  </div>
</div>

{{-- Pemisah halaman --}}
<div class="page-break"></div>

{{-- Halaman 2 (Belakang) --}}
<div class="card-page bg-white mx-auto relative overflow-hidden flex flex-col">
  <!-- HEADER -->
  <div class="h-[8mm] bg-red-600 mb-3 flex items-center justify-center">
    <img src="{{ asset('images/logo-desa.png') }}" alt="Logo Desa" class="h-6">
  </div>

  <!-- BODY -->
  <div class="flex-grow flex overflow-hidden px-3">
    <div class="w-1/3 flex-shrink-0 space-y-4">
      <div class="text-[9px] text-center">
        <p>Diterbitkan di Jimbaran</p>
        <p>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p class="font-semibold mt-2">Bendesa Adat</p>
        <div class="h-10"></div> {{-- Spasi untuk TTD --}}
        <p class="font-semibold underline">{{ $bendesa_adat->nama_bendesa ?? 'NAMA BENDESA' }}</p>
      </div>
      <div class="text-[9px] text-center font-bold">
        <p>Call Center Desa Adat</p>
        <p>081234567899</p>
      </div>
      <div class="text-[9px] text-center">
        <p class="font-semibold">Support by</p>
        <p>BINDES UDAYANA</p>
      </div>
    </div>

    <div class="w-2/3 ml-3 space-y-3">
      <div class="text-xl font-bold text-center mb-2">
        <p>Ketentuan</p>
      </div>
      <div class="flex items-start">
        <div class="w-4 text-[10px] text-left">1.</div>
        <div class="text-[10px] text-justify leading-tight">Kartu ini berlaku di wewidangan desa adat dan untuk kepentingan administrasi penataan krama desa adat dalam mewujudkan kasukretan krama.</div>
      </div>
      <div class="flex items-start">
        <div class="w-4 text-[10px] text-left">2.</div>
        <div class="text-[10px] text-justify leading-tight">Kartu ini diterbitkan berdasarkan pararem desa adat No. 01 Tahun 2024 tentang Kasukretan Krama di Wewidangan Desa Adat.</div>
      </div>
      <div class="flex items-start">
        <div class="w-4 text-[10px] text-left">3.</div>
        <div class="text-[10px] text-justify leading-tight">Pemilik Kartu ini wajib melaksanakan swadarma dan swadikara sesuai awig dan pararem desa adat.</div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="h-[5mm] bg-red-600 mt-auto"></div>
</div>

</body>
</html>
