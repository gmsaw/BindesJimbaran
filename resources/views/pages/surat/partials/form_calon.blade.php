{{-- resources/views/pages/surat/partials/form_calon.blade.php --}}

<div class="space-y-4">
  {{-- DATA DIRI PRIBADI --}}
  <input type="hidden" name="{{$prefix}}[nika]" value="{{ $data->nika ?? '' }}">
  <div><label class="block text-sm font-medium">Nama</label><input type="text" name="{{$prefix}}[nama]" value="{{ $data->masterIndividu->nama_lengkap ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
  <div><label class="block text-sm font-medium">Agama</label><input type="text" name="{{$prefix}}[agama]" value="{{ $data->masterIndividu->agama ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
  <div class="grid grid-cols-2 gap-x-2">
    <div><label class="block text-sm font-medium">Tempat Lahir</label><input type="text" name="{{$prefix}}[tempat_lahir]" value="{{ $data->masterIndividu->tempat_lahir ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
    <div><label class="block text-sm font-medium">Tanggal Lahir</label><input type="date" name="{{$prefix}}[tanggal_lahir]" value="{{ $data->masterIndividu->tanggal_lahir ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
  </div>
  <div><label class="block text-sm font-medium">Pekerjaan</label><input type="text" name="{{$prefix}}[pekerjaan]" value="{{ $data->masterIndividu->pekerjaan ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
  <div><label class="block text-sm font-medium">Alamat Tinggal</label><input type="text" name="{{$prefix}}[alamat]" value="{{ $data->masterIndividu->alamat ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>

  {{-- BLOK BANJAR UNTUK DATA DIRI PRIBADI --}}
  <div class="md:col-span-2">
    <label for="banjar-select-{{$prefix}}" class="block text-sm font-medium">Banjar {{ ucwords($prefix) }}</label>
    <select id="banjar-select-{{$prefix}}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
      <option value="">-- Pilih Banjar --</option>
      @foreach ($all_banjar as $banjar)
        <option value="{{ $banjar->kode_banjar }}">{{ $banjar->nama_banjar }}</option>
      @endforeach
      <option value="custom">-- Isi Sendiri --</option>
    </select>
    <input type="text" name="{{$prefix}}[nama_banjar_custom]" id="banjar-custom-input-{{$prefix}}" class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Ketik nama banjar di sini..." hidden>
    <input type="hidden" name="{{$prefix}}[banjar]" id="banjar-kode-hidden-{{$prefix}}">
  </div>

  {{-- DATA ORANG TUA --}}
  <div class="pt-4 mt-4 border-t">
    <p class="text-sm font-semibold mb-2">Anak dari:</p>
    <div class="space-y-4">
      <div><label class="block text-sm font-medium">Nama Ayah</label><input type="text" name="{{$prefix}}[nama_ayah]" value="{{ $data->masterIndividu->nama_ayah ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
      <div><label class="block text-sm font-medium">Nama Ibu</label><input type="text" name="{{$prefix}}[nama_ibu]" value="{{ $data->masterIndividu->nama_ibu ?? '' }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md"></div>
      <div><label class="block text-sm font-medium">Alamat Orang Tua</label><textarea name="{{$prefix}}[alamat_orang_tua]" rows="2" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">{{ $data->masterIndividu->alamat_orang_tua ?? '' }}</textarea></div>

      {{-- BLOK BANJAR UNTUK ORANG TUA --}}
      <div class="md:col-span-2">
        <label for="banjar-select-{{$prefix}}_ortu" class="block text-sm font-medium">Banjar Orang Tua</label>
        <select id="banjar-select-{{$prefix}}_ortu" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
          <option value="">-- Pilih Banjar --</option>
          @foreach ($all_banjar as $banjar)
            <option value="{{ $banjar->kode_banjar }}">{{ $banjar->nama_banjar }}</option>
          @endforeach
          <option value="custom">-- Isi Sendiri --</option>
        </select>
        <input type="text" name="{{$prefix}}[nama_banjar_custom_ortu]" id="banjar-custom-input-{{$prefix}}_ortu" class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Ketik nama banjar di sini..." hidden>
        <input type="hidden" name="{{$prefix}}[banjar_ortu]" id="banjar-kode-hidden-{{$prefix}}_ortu">
      </div>
    </div>
  </div>
</div>

{{-- SKRIP YANG DIPERBARUI UNTUK MENANGANI KEDUA BLOK BANJAR --}}
@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      /**
       * Fungsi reusable untuk mengatur logika dropdown banjar.
       * @param {string} idSuffix - Sufiks unik untuk ID elemen ('purusa', 'purusa_ortu', dll).
       */
      function setupBanjarSelector(idSuffix) {
        if (!idSuffix) return;

        const selectBanjar = document.getElementById(`banjar-select-${idSuffix}`);
        const customInput = document.getElementById(`banjar-custom-input-${idSuffix}`);
        const hiddenKodeInput = document.getElementById(`banjar-kode-hidden-${idSuffix}`);

        if (selectBanjar && customInput && hiddenKodeInput) {
          selectBanjar.addEventListener('change', function() {
            const selectedValue = this.value;
            if (selectedValue === 'custom') {
              customInput.hidden = false;
              customInput.required = true;
              hiddenKodeInput.value = 'B00';
            } else {
              customInput.hidden = true;
              customInput.required = false;
              customInput.value = '';
              hiddenKodeInput.value = selectedValue;
            }
          });
          selectBanjar.dispatchEvent(new Event('change'));
        }
      }

      // Panggil fungsi untuk setiap set dropdown yang ada di dalam partial ini
      setupBanjarSelector('{{ $prefix }}');
      setupBanjarSelector('{{ $prefix }}_ortu');
    });
  </script>
@endpush
