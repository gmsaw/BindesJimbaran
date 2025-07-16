{{-- File: resources/views/pages/master_data/partials/bendesa_table.blade.php --}}

<div x-data="{ showAddForm: false }">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-lg font-semibold text-gray-800">Data Bendesa Adat</h2>
    <button @click="showAddForm = !showAddForm" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
      <span x-show="!showAddForm"><i class="fas fa-plus mr-1"></i> Tambah Bendesa</span>
      <span x-show="showAddForm"><i class="fas fa-times mr-1"></i> Batal Tambah</span>
    </button>
  </div>

  <!-- Form Tambah Bendesa Baru -->
  <div x-show="showAddForm" x-transition class="bg-gray-50 p-4 rounded-lg mb-6 border">
    <h3 class="font-medium mb-2 text-gray-700">Form Tambah Bendesa Baru</h3>
    <form action="{{ route('master-data.bendesa-adat.store') }}" method="POST">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
        <div>
          <label class="block text-sm font-medium">Nama Bendesa</label>
          <input type="text" name="nama_bendesa" value="{{ old('nama_bendesa') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" required>
        </div>
        <div>
          <label class="block text-sm font-medium">Status Jabatan</label>
          <select name="status_jabatan" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
            <option value="selesai_jabatan">Selesai Jabatan</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium">Periode Mulai</label>
          <input type="date" name="periode_mulai" value="{{ old('periode_mulai') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <div>
          <label class="block text-sm font-medium">Periode Selesai</label>
          <input type="date" name="periode_selesai" value="{{ old('periode_selesai') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
      </div>
      <div class="flex justify-end mt-4">
        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md text-sm">Simpan Bendesa</button>
      </div>
    </form>
  </div>

  <!-- Tabel yang menampilkan data Bendesa yang sudah ada -->
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
      <tr>
        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bendesa</th>
        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
      </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200 text-sm">
      @forelse($bendesa_adats as $item)
        <tr x-data="{ editing: false }">
          <td class="px-4 py-3 align-top">
            <div x-show="!editing">{{ $item->nama_bendesa }}</div>
            <div x-show="editing">
              <input form="editForm-{{ $item->id }}" type="text" name="nama_bendesa" value="{{ $item->nama_bendesa }}" class="w-full px-2 py-1 border border-gray-300 rounded-md">
            </div>
          </td>
          <td class="px-4 py-3 align-top">
            <div x-show="!editing" class="text-gray-600">
              {{ $item->periode_mulai ? \Carbon\Carbon::parse($item->periode_mulai)->format('d M Y') : 'N/A' }} -
              {{ $item->periode_selesai ? \Carbon\Carbon::parse($item->periode_selesai)->format('d M Y') : 'N/A' }}
            </div>
            <div x-show="editing" class="flex flex-col gap-y-2">
              <input form="editForm-{{ $item->id }}" type="date" name="periode_mulai" value="{{ $item->periode_mulai }}" class="w-full px-2 py-1 border border-gray-300 rounded-md">
              <input form="editForm-{{ $item->id }}" type="date" name="periode_selesai" value="{{ $item->periode_selesai }}" class="w-full px-2 py-1 border border-gray-300 rounded-md">
            </div>
          </td>
          <td class="px-4 py-3 align-top">
            <div x-show="!editing">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status_jabatan == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $item->status_jabatan }}
                                </span>
            </div>
            <div x-show="editing">
              <select form="editForm-{{ $item->id }}" name="status_jabatan" class="w-full px-2 py-1 border border-gray-300 rounded-md">
                <option value="aktif" {{ $item->status_jabatan == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $item->status_jabatan == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                <option value="selesai_jabatan" {{ $item->status_jabatan == 'selesai_jabatan' ? 'selected' : '' }}>Selesai Jabatan</option>
              </select>
            </div>
          </td>
          <td class="px-4 py-3 align-top text-right">
            <div x-show="!editing" class="flex justify-end items-center gap-x-2">
              <button @click="editing = true" class="text-blue-600 hover:text-blue-900" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <form action="{{ route('master-data.bendesa-adat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
            <div x-show="editing" class="flex justify-end items-center gap-x-2">
              {{-- PERBAIKAN DI SINI --}}
              <form id="editForm-{{ $item->id }}" action="{{ route('master-data.bendesa-adat.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- <-- Ini adalah perbaikan kuncinya --}}
              </form>
              <button type="submit" form="editForm-{{ $item->id }}" class="text-green-600 hover:text-green-900" title="Simpan">
                <i class="fas fa-save"></i>
              </button>
              <button type="button" @click="editing = false" class="text-gray-600 hover:text-gray-900" title="Batal">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data bendesa.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>
