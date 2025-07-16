@extends('layouts.dasboard-layout')

@section('maincontent')
  <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
      <div class="bg-white p-8 rounded-xl shadow-lg font-sans">

        <div class="text-center mb-8">
          <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
          <p class="text-gray-600 mt-1">Pilih wilayah dari dropdown untuk mengisi data secara otomatis.</p>
        </div>

        <div x-data="addressForm()">
          <form action="{{route('api.post')}}" method="POST" class="space-y-6">
            @csrf
            <div>
              <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
              {{-- PERBAIKAN: value sekarang menggunakan $province->code --}}
              <select id="provinsi" name="provinsi" x-model="selectedProvinsi" @change="fetchKota()" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                <option value="">-- Pilih Provinsi --</option>
                @foreach ($provinces as $province)
                  <option value="{{ $province->code }}">{{ $province->name }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label for="kota" class="block text-sm font-medium text-gray-700">Kota / Kabupaten</label>
              <div class="relative">
                <select id="kota" name="kota" x-model="selectedKota" @change="fetchKecamatan()" :disabled="kotaList.length === 0" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                  <option value="">-- Pilih Kota / Kabupaten --</option>
                  {{-- PERBAIKAN: :value sekarang diikat ke kota.code --}}
                  <template x-for="kota in kotaList" :key="kota.id">
                    <option :value="kota.code" x-text="kota.name"></option>
                  </template>
                </select>
                <div x-show="isLoadingKota" class="absolute inset-y-0 right-0 flex items-center pr-4"><i class="fas fa-spinner fa-spin text-gray-500"></i></div>
              </div>
            </div>

            <div>
              <label for="kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan</label>
              <div class="relative">
                <select id="kecamatan" name="kecamatan" x-model="selectedKecamatan" @change="fetchDesa()" :disabled="kecamatanList.length === 0" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                  <option value="">-- Pilih Kecamatan --</option>
                  <template x-for="kecamatan in kecamatanList" :key="kecamatan.id">
                    <option :value="kecamatan.code" x-text="kecamatan.name"></option>
                  </template>
                </select>
                <div x-show="isLoadingKecamatan" class="absolute inset-y-0 right-0 flex items-center pr-4"><i class="fas fa-spinner fa-spin text-gray-500"></i></div>
              </div>
            </div>

            <div>
              <label for="desa" class="block text-sm font-medium text-gray-700">Desa / Kelurahan</label>
              <div class="relative">
                <select id="desa" name="desa" x-model="selectedDesa" :disabled="desaList.length === 0" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                  <option value="">-- Pilih Desa / Kelurahan --</option>
                  <template x-for="desa in desaList" :key="desa.id">
                    <option :value="desa.code" x-text="desa.name"></option>
                  </template>
                </select>
                <div x-show="isLoadingDesa" class="absolute inset-y-0 right-0 flex items-center pr-4"><i class="fas fa-spinner fa-spin text-gray-500"></i></div>
              </div>
            </div>

            <hr class="my-4">

            <div>
              <label for="alamat_detail" class="block text-sm font-medium text-gray-700">Alamat Detail (Jalan, RT/RW, No. Rumah)</label>
              <textarea id="alamat_detail" name="alamat_detail" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></textarea>
            </div>
            <div>
              <label for="kode_pos" class="block text-sm font-medium text-gray-700">Kode Pos</label>
              <input type="text" id="kode_pos" name="kode_pos" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="flex justify-end pt-4">
              <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700">Simpan Alamat</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    function addressForm() {
      return {
        kotaList: [], kecamatanList: [], desaList: [],
        isLoadingKota: false, isLoadingKecamatan: false, isLoadingDesa: false,
        selectedProvinsi: '{{ old('provinsi', '') }}',
        selectedKota: '{{ old('kota', '') }}',
        selectedKecamatan: '{{ old('kecamatan', '') }}',
        selectedDesa: '{{ old('desa', '') }}',

        async fetchKota() {
          this.kotaList = []; this.kecamatanList = []; this.desaList = [];
          this.selectedKota = ''; this.selectedKecamatan = ''; this.selectedDesa = '';
          if (!this.selectedProvinsi) return;

          this.isLoadingKota = true;
          try {
            // PERBAIKAN: Mengirim 'province_code' sebagai parameter
            const response = await fetch(`{{ route('api.kota') }}?province_code=${this.selectedProvinsi}`);
            this.kotaList = await response.json();
          } catch (error) { console.error('Gagal mengambil data kota:', error); }
          finally { this.isLoadingKota = false; }
        },
        async fetchKecamatan() {
          this.kecamatanList = []; this.desaList = [];
          this.selectedKecamatan = ''; this.selectedDesa = '';
          if (!this.selectedKota) return;

          this.isLoadingKecamatan = true;
          try {
            // PERBAIKAN: Mengirim 'city_code' sebagai parameter
            const response = await fetch(`{{ route('api.kecamatan') }}?city_code=${this.selectedKota}`);
            this.kecamatanList = await response.json();
          } catch (error) { console.error('Gagal mengambil data kecamatan:', error); }
          finally { this.isLoadingKecamatan = false; }
        },
        async fetchDesa() {
          this.desaList = [];
          this.selectedDesa = '';
          if (!this.selectedKecamatan) return;

          this.isLoadingDesa = true;
          try {
            // PERBAIKAN: Mengirim 'district_code' sebagai parameter
            const response = await fetch(`{{ route('api.desa') }}?district_code=${this.selectedKecamatan}`);
            this.desaList = await response.json();
          } catch (error) { console.error('Gagal mengambil data desa:', error); }
          finally { this.isLoadingDesa = false; }
        }
      }
    }
  </script>
@endpush
