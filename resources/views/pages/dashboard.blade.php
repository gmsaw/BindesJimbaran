

@extends('layouts.dasboard-layout')

@section('maincontent')
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
        <x-widget.stats-cards :total-penduduk="$totalPenduduk"
                              :krama-adat="$kramaAdat"
                              :krama-tamiu="$kramaTamiu"
                              :tamiu="$tamiu"/>

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- PERUBAHAN: Teruskan data ke komponen chart --}}
            <div class="lg:col-span-2">
                <x-widget.population-chart :data="$populationChartData" />
            </div>
            <div class="flex flex-col space-y-6">
                {{-- PERUBAHAN: Uncomment dan teruskan data --}}
                <x-widget.gender-chart :data="$genderChartData" />
            </div>
        </div>
    </main>
@endsection