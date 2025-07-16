<h2 class="text-lg font-semibold text-gray-800 mb-4">Data Banjar</h2>
<p class="text-sm text-gray-500 mb-4">Hanya nama Kelihan Banjar yang dapat diubah di halaman ini.</p>
<div class="overflow-x-auto">
  <table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
    <tr>
      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Banjar</th>
      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelihan Banjar</th>
    </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
    @foreach($banjars as $banjar)
      <tr x-data="{ editing: false }">
        <td class="px-4 py-3 whitespace-nowrap font-semibold text-sm align-top">{{ $banjar->nama_banjar }}</td>
        <td class="px-4 py-3">
          {{-- Tampilan Edit --}}
          <div x-show="editing" x-transition>
            <form action="{{ route('master-data.banjar.update', $banjar->kode_banjar) }}" method="POST" class="flex items-center gap-x-2">
              @csrf
              @method('PUT')
              <input type="text" name="kelihan_banjar" value="{{ $banjar->kelihan_banjar }}" class="flex-1 px-2 py-1 border border-gray-300 rounded-md text-sm">
              <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs rounded-md hover:bg-green-600">Simpan</button>
              <button type="button" @click="editing = false" class="px-3 py-1 bg-gray-200 text-xs rounded-md hover:bg-gray-300">Batal</button>
            </form>
          </div>
          {{-- Tampilan Default --}}
          <div x-show="!editing" class="flex items-center justify-between">
            <span>{{ $banjar->kelihan_banjar ?? 'Belum diatur' }}</span>
            <button @click="editing = true" class="text-blue-600 hover:text-blue-900 p-1 text-xs font-medium">
              <i class="fas fa-edit"></i> Edit
            </button>
          </div>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
