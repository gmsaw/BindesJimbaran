{{--@dd($queryPenduduk)--}}
{{--@dd($banjar)--}}

  <!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>ilikita-mautsaha-example</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

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
  </style>
</head>
<body class="bg-white font-sans flex flex-col ">

<div class="w-[210mm] h-[297mm] mx-auto relative overflow-hidden">
  <!-- Header -->
  <div class="bg-[#17375e] p-2">
    <div class="flex gap-[1rem] justify-between items-center p-1">
      <div class="ml-1">
        <img src="{{ asset('images/logo-mda.png') }}" alt="Logo Desa" class="h-[7rem]">
      </div>

      <div class="text-center flex-1">
        <h1 class="text-[24px] text-white font-bold uppercase">Kartu Krama Adat</h1>
        <h2 class="text-[28px] text-white font-bold uppercase">Desa Adat Jimbaran</h2>
        <p class="text-[20px] text-white font-semibold">Kecamatan Kuta Selatan, Kabupaten Badung</p>
      </div>

      <div class="mr-2">
        <img src="{{ asset('images/logo-desa.png') }}" alt="Logo MDA" class="h-[6rem]">
      </div>
    </div>
  </div>

  {{--Body--}}
  <div>
    <div class="mt-2 text-center flex-1 font-bold">
      <p class="text-[20px]">ILIKITA PEMASIH MAUTSAHA ADAT</p>

      <p class="text-[14px]">[SURAT PENCATATAN USAHA DI WEWIDANGAN DESA ADAT]</p>

      <p class="text-[18px]">Nomor : ........../IPM-DAJ/I/2025</p>
    </div>

    <div class="mt-[1rem] ml-[4rem]">
      <div>
        <p class="text-[18px] font-bold italic">Om awighnam astu namo sidham</p>
        <p class="text-[18px] font-bold italic">Om sidhirastu tad astu swaha</p>
      </div>

      <div class="mt-[0.5rem] text-[14px] text-justify mr-[2rem]">
        <p>Berdasarkan Awig-awig Desa Adat Jimbaran Tahun .......... dan Pararem Desa Adat
          Jimbaran No. … Tahun .......... Tentang Kasukretan Krama di Wewidangan Desa Adat
          Jimbaran, maka dengan ini diterbitkan surat pencatatan usaha di wewidangan desa adat
          [ILIKITA PEMASIH MAUTSAHA] dan diberikan kepada :</p>
      </div>

      <div class="mt-[1rem] grid grid-cols-[auto_auto_1fr] gap-x-4 items-center leading-[1.30rem]">
        <span>Nama</span>
        <span>:</span>
        <span>...................................</span>

        <span>NIK/NIKA</span>
        <span>:</span>
        <span>...................................</span>

        <span>Tempat dan Tanggal Lahir</span>
        <span>:</span>
        <span>...................................</span>

        <span>Jenis Kelamin</span>
        <span>:</span>
        <span>...................................</span>

        <span>Agama</span>
        <span>:</span>
        <span>...................................</span>

        <span>Status Krama</span>
        <span>:</span>
        <span>...................................</span>

        <span>Pekerjaan</span>
        <span>:</span>
        <span>...................................</span>

        <span>Alamat Asal</span>
        <span>:</span>
        <span>...................................</span>

        <span>Alamat di Desa Adat</span>
        <span>:</span>
        <span>...................................</span>

        <span>&nbsp;</span>
        <span>&nbsp;</span>
        <span>&nbsp;</span>

        <span>Perusahaan/Lembaga Usaha</span>
        <span>:</span>
        <span>...................................</span>

        <span>Akta Pendirian (Hukum Negara)</span>
        <span>:</span>
        <span>...................................</span>

        <span>Bidang Usaha</span>
        <span>:</span>
        <span>...................................</span>

        <span>Alamat Usaha</span>
        <span>:</span>
        <span>...................................</span>
      </div>

      <div class="mt-[0.5rem] text-[14px] text-justify mr-[2rem]">
        <p>Pemilik usaha berkewajiban menaati kewajiban (swadharma) dan berhak mendapatkan
          (swadikara ) pasayuban secara adat sesuai ketentuan Awig-awig Desa Adat dan Pararem Desa
          Adat Nomor. ….. Tahun ……… tentang Kasukretan krama di wewidangan Desa Adat</p>
      </div>

      <div class="mt-[2rem] mb-[1rem] flex justify-center items-end gap-x-16">
        <!-- Kolom Kiri: Tempat Foto -->
        <div class="text-center">
          <div class="w-[3cm] h-[4cm] border-2 border-dashed border-gray-400 bg-gray-50 flex items-center justify-center">
            <div><span class="text-gray-500 text-xs">3x4</span></div>
            <div><span class="text-gray-500 text-xs">&nbsp; Pas Foto</span></div>
          </div>
        </div>

        <!-- Kolom Kanan: Tempat Tanda Tangan -->
        <div class="text-center">
          <p class="text-sm">Jimbaran, Tanggal Bulan Tahun,</p>
          <p class="text-sm">Bendesa</p>
          {{-- Memberi jarak vertikal untuk area tanda tangan --}}
          <div class="h-20"></div>

          <p class="font-bold">...................................</p>
        </div>

      </div>


      <div class="font-bold text-[13px] mt-2 space-y-1  leading-[1rem] mr-[4rem] text-justify">
        <p>Catatan:</p>
        <div class="flex items-start">
          <!-- Bagian nomor: diberi lebar tetap agar semua teks rata -->
          <div class="w-8 flex-shrink-0">
            <span>1.</span>
          </div>
          <!-- Bagian teks: mengambil sisa ruang yang ada -->
          <div class="flex-1">
            Ilikita Pemasih Mautsaha Adat ini BUKAN IJIN USAHA sebagaimana diatur hukum negara, namun
            sebatas pencatatan dan persetujuan melaksanakan usaha diwewidangan Desa Adat;
          </div>
        </div>

        <!-- Item Nomor 2 -->
        <div class="flex items-start">
          <!-- Bagian nomor -->
          <div class="w-8 flex-shrink-0">
            <span>2.</span>
          </div>
          <!-- Bagian teks -->
          <div class="flex-1">
            Ilikita ini wajib diperpanjang setiap tahun dan dinyatakan tidak berlaku lagi apabila perusahaannya
            tidak lagi operasional dan/atau pemegang hak tidak menaati ketentuan hukum negara dan hukum
            adat yang berlaku.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
