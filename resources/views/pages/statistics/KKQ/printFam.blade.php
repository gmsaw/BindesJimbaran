<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Adat</title>
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
    </style>
</head>
<body class="bg-white font-sans">

<div class="w-[297mm] h-[210mm] border border-gray-400 mx-auto relative overflow-hidden">

    <!-- Header -->
    <div class="bg-red-600">
        <div class="flex gap-[8rem] justify-between items-center">
            <div>
                <img src="{{ asset('images/logo-mda.png') }}" alt="Logo Desa" class="h-30">
            </div>

            <div class="text-center flex-1">
                <h1 class="text-3xl font-bold uppercase">Majelis Desa Adat (MDA) Bali</h1>
                <h2 class="text-3xl font-bold uppercase">Desa Adat Jimbaran</h2>
                <p class="text-xl font-bold">Kecamatan Kuta Selatan, Kabupaten Badung</p>
            </div>

            <div class="mr-2">
                <img src="{{ asset('images/logo-desa.png') }}" alt="Logo MDA" class="h-30">
            </div>

        </div>
    </div>


    <!-- Judul -->
    <div class="text-center my-4">
        <h3 class="text-3xl font-bold uppercase underline">Pipil Kulawarga Krama Desa Adat</h3>
        <p class="text-2xl font-bold uppercase mt-1">NPK: .............................</p>
    </div>

    <!-- Informasi Tambahan -->
    <div class="mt-2 mb-2 flex justify-center">
        <div class="flex gap-[12rem] text-sm">
            <!-- Kolom kiri -->
            <div class="text-left space-y-1">
                <div class="flex">
                    <div class="w-40 text-left font-bold">Nama Pengarep</div>
                    <div class="pl-0">: {{ $data[$kepala]->nama_lengkap ?? '............................' }}</div>
                </div>

                <div class="flex">
                    <div class="w-40 text-left font-bold">Klasifikasi Krama</div>
                    <div class="pl-0">: ............................</div>
                </div>

                <div class="flex">
                    <div class="w-40 text-left font-bold">Jenis Kelamin</div>
                        @if($data[$kepala]->jenis_kelamin == 'L')
                            <div class="pl-0">: Laki-laki</div>
                        @elseif($data[$kepala]->jenis_kelamin == 'P')
                            <div class="pl-0">: Perempuan</div>
                        @else
                            <div class="pl-0">: ............................</div>
                        @endif
                </div>

                <div class="flex">
                    <div class="w-40 text-left font-bold">TTL</div>
                    <div class="pl-0 uppercase">: {{ $data[$kepala]->tempat_lahir . ', ' . \Carbon\Carbon::parse($data[$kepala]->tanggal_lahir)->translatedFormat('d F Y') ?? '............................' }}</div>
                </div>
            </div>

            <!-- Kolom kanan -->
            <div class="text-left space-y-1">
                <div class="flex">
                    <div class="w-40 text-left font-bold">Banjar Adat</div>
                    <div class="pl-0">: {{ $banjar[$kepala]->nama_banjar ?? '............................' }}</div>
                </div>

                <div class="flex">
                    <div class="w-40 text-left font-bold">Tempekan</div>
                    <div class="pl-0">: ............................</div>
                </div>

                <div class="flex">
                    <div class="w-40 text-left font-bold">Dadia</div>
                    <div class="pl-0">: ............................</div>
                </div>

                <div class="flex">
                    <div class="w-40 text-left font-bold">Keterangan</div>
                    <div class="pl-0">: ............................</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Anggota Keluarga -->
    <div class="mt-1 mb-1 flex justify-center">
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
            @for ($i = 0; $i < 8; $i++)
                <tr>
                    <td class="border border-gray-700 px-1 py-1 text-center">{{ $i + 1 }}</td>

                    @if(!empty($data[$i]))
                        <td class="border border-gray-700 px-1 py-1">{{ $data[$i]->nama_lengkap }}</td>
                        <td class="border border-gray-700 px-1 py-1 text-center">{{ $data[$i]->jenis_kelamin ?? '' }}</td>
                        <td class="border border-gray-700 px-1 py-1">{{ $data[$i]->nika ?? '1234567890' }}</td>
                        <td class="border border-gray-700 px-1 py-1 uppercase">{{ $data[$i]->tempat_lahir . ', ' . \Carbon\Carbon::parse($data[$i]->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                        <td class="border border-gray-700 px-1 py-1">{{ $data[$i]->status_perkawinan ?? '' }}</td>
                        <td class="border border-gray-700 px-1 py-1">{{ $data[$i]->pekerjaan ?? '' }}</td>
                        <td class="border border-gray-700 px-1 py-1">{{ $data[$i]->status_hubungan ?? '' }}</td>
                        <td class="border border-gray-700 px-1 py-1">{{ $data[$i]->nama_ayah ?? '' }}</td>
                        <td class="border border-gray-700 px-1 py-1">{{ $data[$i]->nama_ibu ?? '' }}</td>
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

    <div class="mt-2 mb-2 flex justify-center ml-15">
        <div class="flex gap-[12rem] text-sm">
            <div class="text-left space-y-1/2">
                <div class="flex">
                    <div class="w-40 text-left font-bold ml-[10rem]">
                        <p>&nbsp;</p>
                        <p class="mt-6 font-semibold">Pengarep</p>
                        <p class="mt-2 mb-2">&nbsp;</p>
                        <p class="mt-8">{{ $data[$kepala]->nama_lengkap ?? '............................' }}</p>
                    </div>
                </div>
            </div>

            <div class="text-left space-y-1/2">
                <div class="flex">
                    <div class="w-40 text-left font-bold">
                        <p class="mt-4">Jimbaran, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        <p class="mt-2 font-semibold">Bendesa Adat</p>
                        <p class="mt-2 mb-2">&nbsp;</p>
                        <p class="mt-8">I Gusti Made Rai Dirga</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

