{{--@dd($queryPenduduk)--}}
{{--@dd($banjar)--}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Adat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            @page {
                size: 89.92mm 105mm;
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

{{-- Halaman 1 --}}
<div class="w-[89.92mm] h-[105mm] mx-auto relative overflow-hidden">
    <!-- Header -->
    <div class="bg-red-600">
        <div class="flex gap-[1rem] justify-between items-center p-1">
            <div class="ml-4">
                <img src="{{ asset('images/logo-mda.png') }}" alt="Logo Desa" class="h-10">
            </div>

            <div class="text-center flex-1">
                <h1 class="text-[14px] font-bold uppercase">Kartu Krama Adat</h1>
                <h2 class="text-[11px] font-bold uppercase">Desa Adat Jimbaran</h2>
                <p class="text-[8px] font-bold">Kecamatan Kuta Selatan, Kabupaten Badung</p>
            </div>

            <div class="mr-4">
                <img src="{{ asset('images/logo-desa.png') }}" alt="Logo MDA" class="h-10">
            </div>
        </div>
    </div>

    {{--Body--}}
    <div class="flex-grow flex justify-between items-center mt-3 flex min-w-0">
        <div class="ml-3 space-y-2">
            <div class="flex">
                <div class="w-24 text-[11px] text-left">NIKA</div>
                <div class="text-[11px] pl-0">: {{ $queryPenduduk->nik ?? '............................' }}</div>
            </div>

            <div class="flex items-start">
                <div class="w-24 text-[11px] text-left shrink-0">Nama</div>
                <div class="text-[11px] pl-0 shrink-0 mr-1">:</div>
                <div class="text-[11px] break-words">{{ $queryPenduduk->nama_lengkap ?? '............................' }}</div>
            </div>

            <div class="flex">
                <div class="w-24 text-[11px] text-left">Klasifikasi</div>
                <div class="text-[11px] pl-0">: ............................</div>
            </div>

            <div class="flex">
                <div class="w-24 text-[11px] text-left">Jenis Kelamin</div>
                @if($queryPenduduk->jenis_kelamin == 'L')
                    <div class="text-[11px] pl-0 uppercase">: Laki-laki</div>
                @elseif($queryPenduduk->jenis_kelamin == 'P')
                    <div class="text-[11px] pl-0 uppercase">: Perempuan</div>
                @else
                    <div class="text-[11px] pl-0">: ............................</div>
                @endif
            </div>

            <div class="flex">
                <div class="w-24 text-[11px] text-left">TTL</div>
                <div class="text-[11px] pl-0">
                    <p>: {{ $queryPenduduk->tempat_lahir ?? '............................' }}</p>
                    <p class="ml-1">&nbsp;{{ \Carbon\Carbon::parse($queryPenduduk->tanggal_lahir)->translatedFormat('d F Y') ?? '............................' }}</p>
                </div>
            </div>

            <div class="flex">
                <div class="w-24 text-[11px] text-left">Pekerjaan</div>
                <div class="text-[11px] pl-0">: {{ $queryPenduduk->pekerjaan ?? '............................' }}</div>
            </div>

            <div class="flex">
                <div class="w-24 text-[11px] text-left">Alamat</div>
            </div>

                <div class="flex ml-6">
                    <div class="w-[4.5rem] text-[11px] text-left">Tempek/Jln</div>
                    <div class="text-[11px] pl-0">: ............................</div>
                </div>

                <div class="flex ml-6">
                    <div class="w-[4.5rem] text-[11px] text-left">Banjar</div>
                    <div class="text-[11px] pl-0">: {{ $banjar->nama_banjar ?? '............................' }}</div>
                </div>

            <div class="flex">
                <div class="w-24 text-[11px] text-left">Keterangan</div>
                <div class="text-[11px] pl-0">: ............................</div>
            </div>
        </div>


        <div class="mt-8 space-y-6">
            <div class="w-[25mm] h-[30mm] border border-gray-700 flex items-center justify-center text-[10px] mr-3">
                PAS FOTO
            </div>

            <div class="mt-6 mr-4 text-[11px] text-center">
                <p>Berlaku S/D</p>
                <p>{{ \Carbon\Carbon::now()->addYears($berlaku)->translatedFormat('d F Y') }}</p>
            </div>
        </div>

    </div>

</div>

{{-- Pemisah halaman --}}
<div class="page-break"></div>

<div class="flex flex-col w-[89.92mm] h-[105mm] mx-auto relative overflow-hidden">
    <!-- HEADER -->
    <div class="h-[8mm] bg-red-600 mb-3">
        <div class="flex gap-[1rem] justify-between items-center p-1">
            <div></div>
            <div><img src="{{ asset('images/logo-desa.png') }}" alt="Logo MDA" class="h-6"></div>
            <div></div>
        </div>
    </div>

    <!-- BODY -->
    <div class="flex-grow flex overflow-hidden">
        <div class="ml-3 space-y-6">
            <div class="mt-3 mr-4 text-[10px] text-center">
                <p>Diterbitkan di</p>
                <p>Jimbaran</p>
            </div>

            <div class="mt-3 mr-4 text-[10px] text-center">
                <p><p>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p></p>
                <p>Bendesa Adat</p>
            </div>

            <div class="mt-3 mr-4 text-[10px] text-center font-bold">
                <p>Call Center Desa Asat</p>
                <p>081234567899</p>
            </div>

            <div class="mt-3 mr-4 text-[10px] text-center">
                <p>Support by</p>
                <p>BINDES UDAYANA</p>
            </div>
        </div>

        <div class="mt-1 ml-6 mr-3 space-y-3">
            <div class="text-xl font-bold ml-[3rem]">
                <p>Ketentuan</p>
            </div>

            <div class="flex">
                <div class="w-24 text-[11px] text-left">1.</div>
                <div class="text-[11px] text-justify">Kartu ini berlaku di wewidangan desa adat dan untuk kepentingan administrasi penataan krama desa  adat dalam mewujudkan kasukretan krama</div>
            </div>

            <div class="flex">
                <div class="w-20 text-[11px] text-left">2.</div>
                <div class="text-[11px] text-justify">Kartu ini diterbitkan berdasarkan pararem desa adat No. 01 Tahun 2024 tentang Kasukretan Krama di Wewidangan Desa Adat</div>
            </div>

            <div class="flex">
                <div class="w-[4rem] text-[11px] text-left">3.</div>
                <div class="text-[11px] text-justify">Pemilik Kartu ini wajib melaksanakan swadarma dan swadikara sesuai awig dan pararem desa adat</div>
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="h-[5mm] bg-red-600">
        <!-- footer tetap di bawah -->
    </div>
</div>

</body>
</html>
