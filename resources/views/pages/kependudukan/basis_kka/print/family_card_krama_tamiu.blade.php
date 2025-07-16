{{--{{--}}
{{--  $test = $keterangan_keluarga--}}
{{--  @dd($kk_adat->kode_klasifikasi_krama_fk ?-> klasifikasi_krama -> krama)--}}
{{--}}--}}
{{--  @dd($keterangan_keluarga->id_dadia_penatahan_fk)--}}
  <!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kartu Adat-{{$kk_adat -> npk}}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  {{--    <link rel="stylesheet" href="{{ public_path('css/app.css') }}">--}}

  <style>
    @media print {
      @page {
        size: A4 landscape;
        margin: 0;
      }
      body {
        margin: 0;
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
      background-image: url('{{ asset('images/logo-desa.png') }}');
      background-repeat: no-repeat;
      background-position: center;
      background-size: 600px; /* Sesuaikan ukuran watermark sesuai kebutuhan */
      opacity: 0.05; /* Sesuaikan tingkat transparansi (0.05 - 0.2 biasanya bagus) */
      z-index: -1; /* Menempatkan watermark di belakang konten */
    }

  </style>
</head>
<body class="bg-white font-sans">


</body>
</html>

<div class="watermark-container w-[297mm] h-[210mm] border border-gray-400 mx-auto relative overflow-hidden">

  <!-- Header -->
  <div class="bg-yellow-400">
    <div class="flex gap-[8rem] justify-between items-center">
      <div>
        <img src="{{ asset('images/logo-mda.png') }}" alt="Logo Desa" class="h-30">
      </div>

      <div class="text-center flex-1">
        <h1 class="text-3xl font-bold uppercase">Majelis Desa Adat (MDA) Bali</h1>
        <h2 class="text-3xl font-bold uppercase">Desa Adat Jimbaran</h2>
        <p class="text-xl font-bold">Kecamatan Kuta Selatan, Kabupaten Badung</p>
      </div>

      <div class="mr-4">
        <img src="{{ asset('images/logo-desa.png') }}" alt="Logo MDA" class="w-[8.5rem]">
      </div>

    </div>
  </div>


  <!-- Judul -->
  <div class="text-center mt-2">
    <h3 class="text-3xl font-bold uppercase underline">Ilikita Pemasih Krama Tamiu</h3>
    <p class="text-2xl font-bold uppercase mt-1">NPKT: {{ $kk_adat->npk ?? '............................' }}</p>
  </div>

  <!-- Informasi Tambahan -->
  <div class="mt-2 mb-2 flex justify-center">
    <div class="flex gap-[5.5rem] text-sm leading-snug">
      <!-- Kolom kiri -->
      <div class="text-left space-y-1">
        <div class="flex">
          <div class="w-40 text-left font-bold">Nama Pengajeng</div>
          <div class="pl-0">: {{ $kepala_keluarga->masterAdat?->masterIndividu?->nama_lengkap ?? '............................' }}</div>
        </div>

        <div class="flex">
          <div class="w-40 text-left font-bold">Klasifikasi Krama</div>
          <div class="pl-0">: {{ $call_krama_fk ?? '............................' }}</div>
        </div>

        <div class="flex">
          <div class="w-40 text-left font-bold">Jenis Kelamin</div>
          @if($kepala_keluarga->masterAdat?->masterIndividu?->jenis_kelamin == 'l')
            <div class="pl-0">: Laki-laki</div>
          @elseif($kepala_keluarga->masterAdat?->masterIndividu?->jenis_kelamin == 'p' )
            <div class="pl-0">: Perempuan</div>
          @elseif($kepala_keluarga->masterAdat?->masterIndividu?->jenis_kelamin == 'laki-laki' )
            <div class="pl-0">: Perempuan</div>
          @elseif($kepala_keluarga->masterAdat?->masterIndividu?->jenis_kelamin == 'perempuan' )
            <div class="pl-0">: Perempuan</div>
          @else
            <div class="pl-0">: ............................</div>
          @endif
        </div>

        <div class="flex">
          <div class="w-40 text-left font-bold">TTL</div>
          <div class="pl-0 uppercase">: {{ $kepala_keluarga->masterAdat?->masterIndividu?->tempat_lahir . ', ' . \Carbon\Carbon::parse($kepala_keluarga->masterAdat?->masterIndividu?->tanggal_lahir)->translatedFormat('d F Y') ?? '............................' }}</div>
        </div>
      </div>

      <!-- Kolom Tengah -->
      <div class="text-left space-y-1">
        <div class="flex">
          <div class="w-40 text-left font-bold">Banjar Adat</div>
          <div class="pl-0">: {{ $kk_adat->banjar?->nama_banjar ?? '............................' }}</div>
        </div>

        <div class="flex">
          <div class="w-40 text-left font-bold">Dadia/Penatahan</div>
          @if($keterangan_keluarga?->penatahan->nama_penatahan != null && $keterangan_keluarga?->penatahan?->dadia->nama_dadia != null ?? false)
            <div class="pl-0">: {{ $keterangan_keluarga?->penatahan?->dadia->nama_dadia .'/'. $keterangan_keluarga?->penatahan->nama_penatahan ?? '............................' }} </div>
          @else
            <div class="pl-0">: ............................ </div>
          @endif

        </div>

        <div class="flex">
          <div class="w-40 text-left font-bold">Kelihan Natah</div>
          {{--          @dd($keterangan_keluarga?->penatahan->kelihanAdat->masterIndividu->nama_lengkap)--}}
          @if($keterangan_keluarga?->penatahan->kelihan_natah != null or $keterangan_keluarga?->penatahan->kelihanAdat->masterIndividu->nama_lengkap != null)
            <div class="pl-0">: {{ $keterangan_keluarga?->penatahan->kelihanAdat->masterIndividu->nama_lengkap ?? '............................' }}</div>
          @else
            <div class="pl-0">: ............................</div>
          @endif

        </div>

        <div class="flex">
          <div class="w-40 text-left font-bold">Keterangan</div>
          <div class="pl-0">: {{ $keterangan_keluarga->keterangan_tambahan ?? '............................' }}</div>
        </div>
      </div>

      <!-- Kolom Kanan -->
      <div class="text-left space-y-1">
        <div class="flex">
          <div class="w-40 text-left font-bold">Asal</div>
          <div class="pl-0"></div>
        </div>

        <div class="flex">
          <div class="w-40 text-left font-bold">Desa Adat</div>
          <div class="pl-0">: {{ $adder->desa_adat ?? '............................' }}</div>
        </div>

        <div class="flex">
          <div class="w-40 text-left font-bold">Kecamatan</div>
          <div class="pl-0">: {{ $adder->kecamatan ?? '............................' }}</div>
        </div>

        <div class="flex">
          <div class="w-40 text-left font-bold">Kabupaten</div>
          <div class="pl-0">: {{ $adder->kabupaten ?? '............................' }}</div>

        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Anggota Keluarga -->
  <div class="mt-[0.25rem] mb-1 flex justify-center">
    <table class="ml-[3rem] mr-[3rem] text-[9px] border border-gray-700 border-collapse w-[100%]">
      <thead class="bg-gray-200">
      <tr>
        <th class="border border-gray-700 px-1 py-1">No</th>
        <th class="border border-gray-700 px-1 py-1">Nama</th>
        <th class="border border-gray-700 px-1 py-1">L/P</th>
        <th class="border border-gray-700 px-1 py-1">NIKA</th>
        <th class="border border-gray-700 px-1 py-1">TTL</th>
        <th class="border border-gray-700 px-1 py-1">Status Perkawinan</th>
        <th class="border border-gray-700 px-1 py-1">Pekerjaan</th>
        <th class="border border-gray-700 px-1 py-1">Status Keluarga</th>
        <th class="border border-gray-700 px-1 py-1">Nama Ibu</th>
        <th class="border border-gray-700 px-1 py-1">Nama Bapak</th>
      </tr>
      </thead>
      <tbody>
      @for ($i = 0; $i < 10; $i++)
        <tr>
          <td class="border border-gray-700 px-1 py-1 text-center">{{ $i + 1 }}</td>

          @if(!empty($anggota_list[$i]))
            <td class="border border-gray-700 px-1 py-1">{{ $anggota_list[$i]->masterAdat?->masterIndividu->nama_lengkap }}</td>
            <td class="border border-gray-700 px-1 py-1 text-center uppercase">{{ $anggota_list[$i]->masterAdat?->masterIndividu->jenis_kelamin ?? '' }}</td>
            <td class="border border-gray-700 px-1 py-1">{{ $anggota_list[$i]->masterAdat->nika ?? '' }}</td>
            <td class="border border-gray-700 px-1 py-1 uppercase">{{ $anggota_list[$i]->masterAdat?->masterIndividu->tempat_lahir . ', ' . \Carbon\Carbon::parse($anggota_list[$i]->masterAdat?->masterIndividu->tanggal_lahir)->translatedFormat('d F Y') }}</td>
            <td class="border border-gray-700 px-1 py-1">{{ $anggota_list[$i]->masterAdat?->masterIndividu->status_perkawinan ?? '' }}</td>
            <td class="border border-gray-700 px-1 py-1">{{ $anggota_list[$i]->masterAdat?->masterIndividu->pekerjaan ?? '' }}</td>
            <td class="border border-gray-700 px-1 py-1">{{ $anggota_list[$i]->masterAdat?->masterIndividu->status_hubungan ?? '' }}</td>
            <td class="border border-gray-700 px-1 py-1">{{ $anggota_list[$i]->masterAdat?->masterIndividu->nama_ayah ?? '' }}</td>
            <td class="border border-gray-700 px-1 py-1">{{ $anggota_list[$i]->masterAdat?->masterIndividu->nama_ibu ?? '' }}</td>
          @else
            <td class="border border-gray-700 px-1 py-1"></td>
            <td class="border border-gray-700 px-1 py-1 text-center"></td>
            <td class="border border-gray-700 px-1 py-1"></td>
            <td class="border border-gray-700 px-1 py-1"></td>
            <td class="border border-gray-700 px-1 py-1"></td>
            <td class="border border-gray-700 px-1 py-1"></td>
            <td class="border border-gray-700 px-1 py-1"></td>
            <td class="border border-gray-700 px-1 py-1"></td>
            <td class="border border-gray-700 px-1 py-1"></td>
          @endif
        </tr>
      @endfor

      </tbody>
    </table>
  </div>


  <div class="ml-[3rem] mr-[3rem] mt-2 grid grid-cols-2 gap-4 text-xs">
    <div class="text-left space-y-1">
      <div class="flex">
        <div class="w-40 text-left font-bold">Diterbitkan Pada</div>
        <div class="pl-0">: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
      </div>

      <div class="flex">
        <div class="w-40 text-left font-bold">Berlaku Sampai</div>
        <div class="pl-0">: {{ \Carbon\Carbon::now()->addYears($berlaku)->translatedFormat('d F Y') }}
        </div>
      </div>
    </div>
  </div>
  {{--Kolom tanda tangan--}}
  <div class="mt-[0.25rem] mb-2 flex justify-center ml-15">
    <div class="flex gap-[12rem] text-sm">

      <!-- Kolom Pengarep -->
      <div class="text-left space-y-1/2">
        <div class="flex">
          <!-- w-40 dihapus dari sini, dan ml-[10rem] mungkin perlu Anda tinjau lagi -->
          <div class="text-left font-bold ml-[10rem]">
            <p>&nbsp;</p>
            <p class="mt-6 font-semibold">Pengajeng</p>
            <p class="mt-2 mb-2">&nbsp;</p>
            <!-- Ditambahkan whitespace-nowrap -->
            <p class="mt-8 whitespace-nowrap">{{ $kepala_keluarga->masterAdat?->masterIndividu?->nama_lengkap ?? '............................' }}</p>
          </div>
        </div>
      </div>

      <!-- Kolom Kelihan Banjar -->
      <div class="text-left space-y-1/2">
        <div class="flex">
          <!-- w-40 dihapus dari sini -->
          <div class="text-left font-bold">
            <p>&nbsp;</p>
            <p class="mt-6 font-semibold">Kelihan Banjar</p>
            <p class="mt-2 mb-2">&nbsp;</p>
            <!-- Ditambahkan whitespace-nowrap -->
            <p class="mt-8 whitespace-nowrap">{{ $kk_adat->banjar?->kelihan_banjar ?? '............................' }}</p>
          </div>
        </div>
      </div>

      <!-- Kolom Bendesa Adat -->
      <div class="text-left space-y-1/2">
        <div class="flex">
          <!-- w-40 dihapus dari sini, nama variabel disesuaikan -->
          <div class="text-left font-bold">
            <p class="mt-4">Jimbaran, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p class="mt-2 font-semibold">Bendesa Adat</p>
            <p class="mt-2 mb-2">&nbsp;</p>
            <!-- Ditambahkan whitespace-nowrap, nama variabel disesuaikan -->
            <p class="mt-8 whitespace-nowrap">{{ $bendesa_adat_aktif->nama_bendesa ?? '............................' }}</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
