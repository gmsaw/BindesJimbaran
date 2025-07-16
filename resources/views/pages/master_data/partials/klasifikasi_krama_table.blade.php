{{-- File: resources/views/pages/master_data/partials/klasifikasi_krama_table.blade.php --}}

<div x-data="{ showAddForm: false }">
  {{-- Header dengan tombol Tambah/Batal --}}
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-lg font-semibold text-gray-800">Data Klasifikasi Krama</h2>
    <button @click="showAddForm = !showAddForm" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
      <span x-show="!showAddForm"><i class="fas fa-plus mr-1"></i> Tambah Krama</span>
      <span x-show="showAddForm"><i class="fas fa-times mr-1"></i> Batal Tambah</span>
    </button>
  </div>

  <!-- Form Tambah Klasifikasi Baru -->
  <div x-show="showAddForm" x-transition class="bg-gray-50 p-4 rounded-lg mb-6 border">
    <h3 class="font-medium mb-2 text-gray-700">Form Tambah Klasifikasi Baru</h3>
    <form action="{{ route('master-data.klasifikasi-krama.store') }}" method="POST">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
          <label class="block text-sm font-medium">Kode Krama</label>
          <input type="text" name="kode_krama" value="{{ old('kode_krama') }}" placeholder="Contoh: KRM04" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" required>
        </div>
        <div>
          <label class="block text-sm font-medium">Nama Klasifikasi</label>
          <input type="text" name="krama" value="{{ old('krama') }}" placeholder="Contoh: Krama Tamiu" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" required>
        </div>
        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md h-fit text-sm">Simpan</button>
      </div>
    </form>
  </div>

  <!-- Tabel yang menampilkan data Klasifikasi Krama yang sudah ada -->
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
      <tr>
        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Klasifikasi</th>
        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
      </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200 text-sm">
      @forelse($klasifikasi_kramas as $item)
        <tr x-data="{ editing: false }">
          <td class="px-4 py-3 font-mono align-top">{{ $item->kode_krama }}</td>
          <td class="px-4 py-3 align-top">
            <div x-show="!editing">{{ $item->krama }}</div>
            <div x-show="editing">
              {{-- Input ini adalah bagian dari form di kolom Aksi --}}
              <input form="editForm-{{ $item->kode_krama }}" type="text" name="krama" value="{{ $item->krama }}" class="w-full px-2 py-1 border border-gray-300 rounded-md">
            </div>
          </td>
          <td class="px-4 py-3 align-top text-right">
            <div x-show="!editing" class="flex justify-end items-center gap-x-2">
              <button @click="editing = true" class="text-blue-600 hover:text-blue-900" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <form action="{{ route('master-data.klasifikasi-krama.destroy', $item->kode_krama) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
            <div x-show="editing" class="flex justify-end items-center gap-x-2">
              {{-- Form kosong untuk menampung input dari kolom lain --}}
              <form id="editForm-{{ $item->kode_krama }}" action="{{ route('master-data.klasifikasi-krama.update', $item->kode_krama) }}" method="POST"></form>
              @csrf
              @method('PUT')
              <button type="submit" form="editForm-{{ $item->kode_krama }}" class="text-green-600 hover:text-green-900" title="Simpan">
                <i class="fas fa-save"></i>
              </button>
              <button @click="editing = false" class="text-gray-600 hover:text-gray-900" title="Batal">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="3" class="text-center py-4 text-gray-500">Tidak ada data klasifikasi krama.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>
