@extends('layouts.dasboard-layout')

@section('maincontent')

  <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8">

      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
        <p class="text-gray-600">Pilih salah satu layanan di bawah ini untuk memulai.</p>
      </div>

      {{-- Menggunakan grid dengan 5 kolom di layar besar, dan akan menyesuaikan di layar kecil --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300">
          <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800"><i class="fas fa-bullhorn mr-2 text-gray-500"></i>Surat Pengumuman</h3>
          </div>
          <div class="p-6 flex flex-col justify-between h-auto">
            <p class="text-gray-600 mb-4 text-sm">
              Buat surat pengumuman resmi Desa Adat.
            </p>
            <a href="{{ route('surat.pengumuman_kawin.create') }}" class="w-full text-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
              Buat Surat
            </a>
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300">
          <div class="bg-pink-50 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-pink-800"><i class="fas fa-ring mr-2 text-pink-500"></i>Surat Kawin</h3>
          </div>
          <div class="p-6 flex flex-col justify-between h-auto">
            <p class="text-gray-600 mb-4 text-sm">
              Membuat surat keterangan perkawinan secara adat.
            </p>
            <a href="{{ route('surat.kawin.create') }}" class="w-full text-center px-4 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 transition">
              Buat Surat
            </a>
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300">
          <div class="bg-red-50 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-red-800"><i class="fas fa-file-signature mr-2 text-red-500"></i>Surat Cerai</h3>
          </div>
          <div class="p-6 flex flex-col justify-between h-auto">
            <p class="text-gray-600 mb-4 text-sm">
              Membuat surat keterangan perceraian secara adat.
            </p>
            <a href="{{ route('surat.cerai.create') }}" class="w-full text-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
              Buat Surat
            </a>
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300">
          <div class="bg-green-50 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-green-800"><i class="fas fa-certificate mr-2 text-green-500"></i>Ilikita Pawiwahan</h3>
          </div>
          <div class="p-6 flex flex-col justify-between h-auto">
            <p class="text-gray-600 mb-4 text-sm">
              Menerbitkan ilikita (akta) sebagai bukti sah perkawinan secara adat.
            </p>
            <a href="{{route('surat.ilikita_pawiwahan.create')}}" class="w-full text-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
              Buat Ilikita
            </a>
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300">
          <div class="bg-blue-50 px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-blue-800"><i class="fas fa-store mr-2 text-blue-500"></i>Ilikita Mautsaha</h3>
          </div>
          <div class="p-6 flex flex-col justify-between h-auto">
            <p class="text-gray-600 mb-4 text-sm">
              Menerbitkan ilikita persetujuan untuk usaha di wewidangan Desa Adat.
            </p>
            <a href="{{route('surat.ilikita_mautsaha.create')}}" class="w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
              Buat Ilikita
            </a>
          </div>
        </div>

      </div>
    </div>
  </main>

@endsection
