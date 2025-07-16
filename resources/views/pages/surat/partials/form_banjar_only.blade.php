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
