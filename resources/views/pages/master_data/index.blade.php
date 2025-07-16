@extends('layouts.dasboard-layout')

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8" x-data="{ tab: 'banjar' }">

      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
        <p class="text-gray-600">Kelola data inti yang digunakan di seluruh sistem.</p>
      </div>

      @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p>{{ session('success') }}</p></div>
      @endif
      @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
          <p><strong>Terdapat kesalahan:</strong></p>
          <ul class="list-disc ml-5 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
      @endif

      <!-- Tab Buttons -->
      <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-6">
          <button @click="tab = 'banjar'" :class="{ 'border-blue-500 text-blue-600': tab === 'banjar', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'banjar' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Banjar</button>
          <button @click="tab = 'bendesa'" :class="{ 'border-blue-500 text-blue-600': tab === 'bendesa', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'bendesa' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Bendesa Adat</button>
          <button @click="tab = 'krama'" :class="{ 'border-blue-500 text-blue-600': tab === 'krama', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'krama' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Klasifikasi Krama</button>
          <button @click="tab = 'dadia_penatahan'" :class="{ 'border-blue-500 text-blue-600': tab === 'dadia_penatahan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'dadia_penatahan' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Dadia & Penatahan</button>
{{--          <button @click="tab = 'dadia'" :class="{ 'border-blue-500 text-blue-600': tab === 'dadia', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'dadia' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Dadia</button>--}}
{{--          <button @click="tab = 'penatahan'" :class="{ 'border-blue-500 text-blue-600': tab === 'penatahan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'penatahan' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Penatahan</button>--}}
        </nav>
      </div>

      <!-- Tab Content Panels -->
      <div class="bg-white p-6 rounded-xl shadow-lg">

        {{-- Panel untuk Banjar --}}
        <div x-show="tab === 'banjar'" x-transition>
          @include('pages.master_data.partials.banjar_table', ['banjars' => $data['banjars']])
        </div>

        {{-- Panel untuk Bendesa Adat --}}
        <div x-show="tab === 'bendesa'" x-transition>
          @include('pages.master_data.partials.bendesa_adat_table', ['bendesa_adats' => $data['bendesa_adats']])
        </div>

        {{-- Panel untuk Klasifikasi Krama --}}
        <div x-show="tab === 'krama'" x-transition>
          @include('pages.master_data.partials.klasifikasi_krama_table', ['klasifikasi_kramas' => $data['klasifikasi_kramas']])
        </div>

        {{-- Panel untuk Dadia Penatahan --}}
        <div x-show="tab === 'dadia_penatahan'" x-transition>
          @include('pages.master_data.partials.dadia_penatahan_table', ['dadia_penatahans' => $data['dadia_penatahans']])
        </div>

{{--        <div x-show="tab === 'dadia'" x-transition>--}}
{{--          @include('pages.master_data.partials.dadia_table', ['dadias' => $data['dadias']])--}}
{{--        </div>--}}

{{--        <div x-show="tab === 'penatahan'" x-transition>--}}
{{--          @include('pages.master_data.partials.penatahan_table', ['penatahans' => $data['penatahans']])--}}
        </div>

      </div>
    </div>
  </div>
@endsection
