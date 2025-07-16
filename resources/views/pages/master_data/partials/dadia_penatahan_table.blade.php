<div x-data="{ showAddDadiaForm: false, showAddPenatahanForm: null }">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-lg font-semibold text-gray-800">Data Dadia & Penatahan</h2>
    <div class="flex items-center gap-x-2">
      <a href="{{ route('master-data.dadia.export') }}" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors">
        <i class="fas fa-file-csv mr-1"></i> Export CSV
      </a>
      <button @click="showAddDadiaForm = !showAddDadiaForm" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
        <span x-show="!showAddDadiaForm"><i class="fas fa-plus mr-1"></i> Tambah Dadia</span>
        <span x-show="showAddDadiaForm"><i class="fas fa-times mr-1"></i> Batal Tambah</span>
      </button>
    </div>
  </div>

  <!-- Form Tambah Dadia Baru -->
  <div x-show="showAddDadiaForm" x-transition class="bg-gray-50 p-4 rounded-lg mb-6 border">
    <h3 class="font-medium mb-2 text-gray-700">Form Tambah Dadia Baru</h3>
    <form action="{{ route('master-data.dadia.store') }}" method="POST">
      @csrf
      <div class="flex items-end gap-4">
        <div class="flex-1"><label class="block text-sm font-medium">Nama Dadia</label><input type="text" name="nama_dadia" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" required></div>
        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md h-fit text-sm">Simpan Dadia</button>
      </div>
    </form>
  </div>

  <!-- Statistik -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200"><h3 class="text-gray-500 text-sm">Total Dadia</h3><p class="text-2xl font-bold text-blue-800">{{ $data['total_dadia'] }}</p></div>
    <div class="bg-green-50 p-4 rounded-lg border border-green-200"><h3 class="text-gray-500 text-sm">Total Penatahan</h3><p class="text-2xl font-bold text-green-800">{{ $data['total_penatahan'] }}</p></div>
  </div>

  <!-- Form Pencarian Kelihan Natah -->
  <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border">
    <h3 class="font-medium text-gray-700 mb-2">Pencarian Kelihan Natah</h3>
    <p class="text-xs text-gray-500 mb-2">Gunakan form ini untuk memfilter pilihan pada dropdown "Kelihan Natah" di bawah.</p>
    <form action="{{ route('master-data.index') }}" method="GET">
      <input type="hidden" name="tab" value="dadia"> {{-- Agar tab tetap aktif --}}
      <div class="flex items-end gap-2">
        <div class="flex-1">
          <label for="kelihan_search" class="text-sm">Cari berdasarkan Nama/NIK/NIKA/Banjar Kelihan</label>
          <input type="text" id="kelihan_search" name="kelihan_search" value="{{ $kelihanSearchTerm }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md h-fit">Cari</button>
        @if($kelihanSearchTerm)
          <a href="{{ route('master-data.index', ['tab' => 'dadia']) }}" class="px-4 py-2 bg-gray-200 rounded-md h-fit">Reset</a>
        @endif
      </div>
    </form>
    @if($kelihanSearchTerm)
      <p class="text-xs text-blue-700 mt-2">Menampilkan hasil pencarian untuk "{{ $kelihanSearchTerm }}". Dropdown di bawah sudah terfilter.</p>
    @endif
  </div>

  <!-- Daftar Dadia dan Penatahan (Grouped) -->
  <div class="space-y-4">
    @forelse($dadia_penatahans as $dadia)
      <div class="border border-gray-200 rounded-lg">
        <!-- Header Dadia -->
        <div class="bg-gray-100 px-4 py-3 flex justify-between items-center">
          <div>
            <h3 class="font-bold text-gray-800">{{ $dadia->nama_dadia }}</h3>
            <p class="text-xs text-gray-500">Total Penatahan: {{ $dadia->penatahan_count }}</p>
          </div>
          <div class="flex items-center gap-x-4">
            <button @click="showAddPenatahanForm = (showAddPenatahanForm === {{ $dadia->id }} ? null : {{ $dadia->id }})" class="text-blue-600 hover:text-blue-900 text-xs font-medium"><i class="fas fa-plus"></i> Tambah Penatahan</button>
            <form action="{{ route('master-data.dadia.destroy', $dadia->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Dadia ini beserta semua Penatahannya?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Dadia"><i class="fas fa-trash"></i></button></form>
          </div>
        </div>

        <!-- Form Tambah Penatahan -->
        <div x-show="showAddPenatahanForm === {{ $dadia->id }}" x-transition class="p-4 border-b">
          <form action="{{ route('master-data.penatahan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_dadia_fk" value="{{ $dadia->id }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
              <div><label class="block text-sm font-medium">Nama Penatahan</label><input type="text" name="nama_penatahan" class="mt-1 w-full px-2 py-1 border rounded" required></div>
              <div>
                <label class="block text-sm font-medium">Kelihan Natah</label>
                <select name="id_kelihan_adat_fk" class="mt-1 w-full px-2 py-1 border rounded">
                  <option value="">-- Pilih Krama --</option>
                  @foreach($data['all_krama_adat'] as $krama)
                    <option value="{{ $krama->id_identitas_adat }}">{{ $krama->masterIndividu->nama_lengkap }} ({{ $krama->banjar->nama_banjar }})</option>
                  @endforeach
                </select>
              </div>
              <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs rounded h-fit">Simpan</button>
            </div>
          </form>
        </div>

        <!-- Tabel Penatahan -->
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr><th class="px-4 py-2 text-left">Nama Penatahan</th><th class="px-4 py-2 text-left">Kelihan Natah</th><th class="px-4 py-2 text-right">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
            @forelse ($dadia->penatahan as $penatahan)
              <tr x-data="{ editing: false }">
                <td class="px-4 py-2"><span x-show="!editing">{{ $penatahan->nama_penatahan }}</span><div x-show="editing"><input form="editPenatahan-{{ $penatahan->id }}" type="text" name="nama_penatahan" value="{{ $penatahan->nama_penatahan }}" class="w-full px-2 py-1 border rounded"></div></td>
                <td class="px-4 py-2">
                                        <span x-show="!editing">
                                            @if($penatahan->kelihanAdat)
                                            <div>{{ $penatahan->kelihanAdat->masterIndividu->nama_lengkap }}</div>
                                            <div class="text-xs text-gray-500">Banjar: {{ $penatahan->kelihanAdat->banjar->nama_banjar }}</div>
                                            <div class="text-xs text-gray-500">Alamat: {{ $penatahan->kelihanAdat->masterIndividu->alamat }}</div>
                                          @else
                                            Belum diatur
                                          @endif
                                        </span>
                  <div x-show="editing">
                    <select form="editPenatahan-{{ $penatahan->id }}" name="id_kelihan_adat_fk" class="w-full px-2 py-1 border rounded">
                      <option value="">-- Kosongkan --</option>
                      @foreach($data['all_krama_adat'] as $krama)
                        <option value="{{ $krama->id_identitas_adat }}" {{ $penatahan->id_kelihan_adat_fk == $krama->id_identitas_adat ? 'selected' : '' }}>{{ $krama->masterIndividu->nama_lengkap }} ({{ $krama->banjar->nama_banjar }})</option>
                      @endforeach
                    </select>
                  </div>
                </td>
                <td class="px-4 py-2 text-right">
                  <div x-show="!editing" class="flex justify-end items-center gap-x-2"><button @click="editing = true" class="text-blue-600 hover:text-blue-900" title="Edit"><i class="fas fa-edit"></i></button><form action="{{ route('master-data.penatahan.destroy', $penatahan->id) }}" method="POST" onsubmit="return confirm('Yakin?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" title="Hapus"><i class="fas fa-trash"></i></button></form></div>
                  <div x-show="editing" class="flex justify-end items-center gap-x-2"><form id="editPenatahan-{{ $penatahan->id }}" action="{{ route('master-data.penatahan.update', $penatahan->id) }}" method="POST">@csrf @method('PUT')</form><button type="submit" form="editPenatahan-{{ $penatahan->id }}" class="text-green-600 hover:text-green-900" title="Simpan"><i class="fas fa-save"></i></button><button type="button" @click="editing = false" class="text-gray-600 hover:text-gray-900" title="Batal"><i class="fas fa-times"></i></button></div>
                </td>
              </tr>
            @empty
              <tr><td colspan="3" class="px-4 py-3 text-center text-gray-500 text-xs italic">Belum ada data penatahan untuk dadia ini.</td></tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>
    @empty
      <div class="text-center py-8 text-gray-500">Tidak ada data dadia yang ditemukan.</div>
    @endforelse
  </div>
</div>

{{--<div x-data="{ showAddDadiaForm: false, showAddPenatahanForm: null }">--}}
{{--  <div class="flex justify-between items-center mb-4">--}}
{{--    <h2 class="text-lg font-semibold text-gray-800">Data Dadia & Penatahan</h2>--}}
{{--    <div class="flex items-center gap-x-2">--}}
{{--      <a href="{{ route('master-data.dadia.export') }}" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors">--}}
{{--        <i class="fas fa-file-csv mr-1"></i> Export CSV--}}
{{--      </a>--}}
{{--      <button @click="showAddDadiaForm = !showAddDadiaForm" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">--}}
{{--        <span x-show="!showAddDadiaForm"><i class="fas fa-plus mr-1"></i> Tambah Dadia</span>--}}
{{--        <span x-show="showAddDadiaForm"><i class="fas fa-times mr-1"></i> Batal Tambah</span>--}}
{{--      </button>--}}
{{--    </div>--}}
{{--  </div>--}}

{{--  <!-- Form Tambah Dadia Baru -->--}}
{{--  <div x-show="showAddDadiaForm" x-transition class="bg-gray-50 p-4 rounded-lg mb-6 border">--}}
{{--    <h3 class="font-medium mb-2 text-gray-700">Form Tambah Dadia Baru</h3>--}}
{{--    <form action="{{ route('master-data.dadia.store') }}" method="POST">--}}
{{--      @csrf--}}
{{--      <div class="flex items-end gap-4">--}}
{{--        <div class="flex-1"><label class="block text-sm font-medium">Nama Dadia</label><input type="text" name="nama_dadia" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" required></div>--}}
{{--        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md h-fit text-sm">Simpan Dadia</button>--}}
{{--      </div>--}}
{{--    </form>--}}
{{--  </div>--}}

{{--  <!-- Statistik -->--}}
{{--  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">--}}
{{--    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200"><h3 class="text-gray-500 text-sm">Total Dadia</h3><p class="text-2xl font-bold text-blue-800">{{ $data['total_dadia'] }}</p></div>--}}
{{--    <div class="bg-green-50 p-4 rounded-lg border border-green-200"><h3 class="text-gray-500 text-sm">Total Penatahan</h3><p class="text-2xl font-bold text-green-800">{{ $data['total_penatahan'] }}</p></div>--}}
{{--  </div>--}}

{{--  <!-- Form Pencarian Kelihan Natah -->--}}
{{--  <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border">--}}
{{--    <h3 class="font-medium text-gray-700 mb-2">Pencarian Kelihan Natah</h3>--}}
{{--    <p class="text-xs text-gray-500 mb-2">Gunakan form ini untuk memfilter pilihan pada dropdown "Kelihan Natah" di bawah.</p>--}}
{{--    <form action="{{ route('master-data.index') }}" method="GET">--}}
{{--      <input type="hidden" name="tab" value="dadia"> --}}{{-- Agar tab tetap aktif --}}
{{--      <div class="flex items-end gap-2">--}}
{{--        <div class="flex-1">--}}
{{--          <label for="kelihan_search" class="text-sm">Cari berdasarkan Nama/NIK/NIKA/Banjar Kelihan</label>--}}
{{--          <input type="text" id="kelihan_search" name="kelihan_search" value="{{ $kelihanSearchTerm }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">--}}
{{--        </div>--}}
{{--        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md h-fit">Cari</button>--}}
{{--        @if($kelihanSearchTerm)--}}
{{--          <a href="{{ route('master-data.index', ['tab' => 'dadia']) }}" class="px-4 py-2 bg-gray-200 rounded-md h-fit">Reset</a>--}}
{{--        @endif--}}
{{--      </div>--}}
{{--    </form>--}}
{{--    @if($kelihanSearchTerm)--}}
{{--      <p class="text-xs text-blue-700 mt-2">Menampilkan hasil pencarian untuk "{{ $kelihanSearchTerm }}". Dropdown di bawah sudah terfilter.</p>--}}
{{--    @endif--}}
{{--  </div>--}}

{{--  <!-- Daftar Dadia dan Penatahan (Grouped) -->--}}
{{--  <div class="space-y-4">--}}
{{--    @forelse($dadia_penatahans as $dadia)--}}
{{--      <div class="border border-gray-200 rounded-lg">--}}
{{--        <!-- Header Dadia -->--}}
{{--        <div class="bg-gray-100 px-4 py-3 flex justify-between items-center">--}}
{{--          <div>--}}
{{--            <h3 class="font-bold text-gray-800">{{ $dadia->nama_dadia }}</h3>--}}
{{--            <p class="text-xs text-gray-500">Total Penatahan: {{ $dadia->penatahan_count }}</p>--}}
{{--          </div>--}}
{{--          <div class="flex items-center gap-x-4">--}}
{{--            <button @click="showAddPenatahanForm = (showAddPenatahanForm === {{ $dadia->id }} ? null : {{ $dadia->id }})" class="text-blue-600 hover:text-blue-900 text-xs font-medium"><i class="fas fa-plus"></i> Tambah Penatahan</button>--}}
{{--            <form action="{{ route('master-data.dadia.destroy', $dadia->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Dadia ini beserta semua Penatahannya?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Dadia"><i class="fas fa-trash"></i></button></form>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <!-- Form Tambah Penatahan -->--}}
{{--        <div x-show="showAddPenatahanForm === {{ $dadia->id }}" x-transition class="p-4 border-b">--}}
{{--          <form action="{{ route('master-data.penatahan.store') }}" method="POST">--}}
{{--            @csrf--}}
{{--            <input type="hidden" name="id_dadia_fk" value="{{ $dadia->id }}">--}}
{{--            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">--}}
{{--              <div><label class="block text-sm font-medium">Nama Penatahan</label><input type="text" name="nama_penatahan" class="mt-1 w-full px-2 py-1 border rounded" required></div>--}}
{{--              <div>--}}
{{--                <label class="block text-sm font-medium">Kelihan Natah</label>--}}
{{--                <select name="id_kelihan_adat_fk" class="mt-1 w-full px-2 py-1 border rounded">--}}
{{--                  <option value="">-- Pilih Krama --</option>--}}
{{--                  @foreach($data['all_krama_adat'] as $krama)--}}
{{--                    --}}{{-- PERBAIKAN: Menampilkan Nama Banjar --}}
{{--                    <option value="{{ $krama->id_identitas_adat }}">{{ $krama->masterIndividu->nama_lengkap }} ({{ $krama->banjar->nama_banjar }})</option>--}}
{{--                  @endforeach--}}
{{--                </select>--}}
{{--              </div>--}}
{{--              <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs rounded h-fit">Simpan</button>--}}
{{--            </div>--}}
{{--          </form>--}}
{{--        </div>--}}

{{--        <!-- Tabel Penatahan -->--}}
{{--        <div class="overflow-x-auto">--}}
{{--          <table class="min-w-full">--}}
{{--            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">--}}
{{--            <tr><th class="px-4 py-2 text-left">Nama Penatahan</th><th class="px-4 py-2 text-left">Kelihan Natah</th><th class="px-4 py-2 text-right">Aksi</th></tr>--}}
{{--            </thead>--}}
{{--            <tbody class="divide-y divide-gray-200 text-sm">--}}
{{--            @forelse ($dadia->penatahan as $penatahan)--}}
{{--              <tr x-data="{ editing: false }">--}}
{{--                <td class="px-4 py-2"><span x-show="!editing">{{ $penatahan->nama_penatahan }}</span><div x-show="editing"><input form="editPenatahan-{{ $penatahan->id }}" type="text" name="nama_penatahan" value="{{ $penatahan->nama_penatahan }}" class="w-full px-2 py-1 border rounded"></div></td>--}}
{{--                <td class="px-4 py-2">--}}
{{--                                        <span x-show="!editing">--}}
{{--                                            @if($penatahan->kelihanAdat)--}}
{{--                                            <div>{{ $penatahan->kelihanAdat->masterIndividu->nama_lengkap }}</div>--}}
{{--                                            <div class="text-xs text-gray-500">Banjar: {{ $penatahan->kelihanAdat->banjar->nama_banjar }}</div>--}}
{{--                                            <div class="text-xs text-gray-500">Alamat: {{ $penatahan->kelihanAdat->masterIndividu->alamat }}</div>--}}
{{--                                          @else--}}
{{--                                            Belum diatur--}}
{{--                                          @endif--}}
{{--                                        </span>--}}
{{--                  <div x-show="editing">--}}
{{--                    <select form="editPenatahan-{{ $penatahan->id }}" name="id_kelihan_adat_fk" class="w-full px-2 py-1 border rounded">--}}
{{--                      <option value="">-- Kosongkan --</option>--}}
{{--                      @foreach($data['all_krama_adat'] as $krama)--}}
{{--                        --}}{{-- PERBAIKAN: Menampilkan Nama Banjar --}}
{{--                        <option value="{{ $krama->id_identitas_adat }}" {{ $penatahan->id_kelihan_adat_fk == $krama->id_identitas_adat ? 'selected' : '' }}>{{ $krama->masterIndividu->nama_lengkap }} ({{ $krama->banjar->nama_banjar }})</option>--}}
{{--                      @endforeach--}}
{{--                    </select>--}}
{{--                  </div>--}}
{{--                </td>--}}
{{--                <td class="px-4 py-2 text-right">--}}
{{--                  <div x-show="!editing" class="flex justify-end items-center gap-x-2"><button @click="editing = true" class="text-blue-600 hover:text-blue-900" title="Edit"><i class="fas fa-edit"></i></button><form action="{{ route('master-data.penatahan.destroy', $penatahan->id) }}" method="POST" onsubmit="return confirm('Yakin?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" title="Hapus"><i class="fas fa-trash"></i></button></form></div>--}}
{{--                  <div x-show="editing" class="flex justify-end items-center gap-x-2"><form id="editPenatahan-{{ $penatahan->id }}" action="{{ route('master-data.penatahan.update', $penatahan->id) }}" method="POST">@csrf @method('PUT')</form><button type="submit" form="editPenatahan-{{ $penatahan->id }}" class="text-green-600 hover:text-green-900" title="Simpan"><i class="fas fa-save"></i></button><button type="button" @click="editing = false" class="text-gray-600 hover:text-gray-900" title="Batal"><i class="fas fa-times"></i></button></div>--}}
{{--                </td>--}}
{{--              </tr>--}}
{{--            @empty--}}
{{--              <tr><td colspan="3" class="px-4 py-3 text-center text-gray-500 text-xs italic">Belum ada data penatahan untuk dadia ini.</td></tr>--}}
{{--            @endforelse--}}
{{--            </tbody>--}}
{{--          </table>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    @empty--}}
{{--      <div class="text-center py-8 text-gray-500">Tidak ada data dadia yang ditemukan.</div>--}}
{{--    @endforelse--}}
{{--  </div>--}}
{{--</div>--}}

{{--<div x-data="{ showAddDadiaForm: false, showAddPenatahanForm: null }">--}}
{{--  <div class="flex justify-between items-center mb-4">--}}
{{--    <h2 class="text-lg font-semibold text-gray-800">Data Dadia & Penatahan</h2>--}}
{{--    <div class="flex items-center gap-x-2">--}}
{{--      <a href="{{ route('master-data.dadia.export') }}" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors">--}}
{{--        <i class="fas fa-file-csv mr-1"></i> Export CSV--}}
{{--      </a>--}}
{{--      <button @click="showAddDadiaForm = !showAddDadiaForm" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">--}}
{{--        <span x-show="!showAddDadiaForm"><i class="fas fa-plus mr-1"></i> Tambah Dadia</span>--}}
{{--        <span x-show="showAddDadiaForm"><i class="fas fa-times mr-1"></i> Batal Tambah</span>--}}
{{--      </button>--}}
{{--    </div>--}}
{{--  </div>--}}

{{--  <!-- Form Tambah Dadia Baru -->--}}
{{--  <div x-show="showAddDadiaForm" x-transition class="bg-gray-50 p-4 rounded-lg mb-6 border">--}}
{{--    <h3 class="font-medium mb-2 text-gray-700">Form Tambah Dadia Baru</h3>--}}
{{--    <form action="{{ route('master-data.dadia.store') }}" method="POST">--}}
{{--      @csrf--}}
{{--      <div class="flex items-end gap-4">--}}
{{--        <div class="flex-1"><label class="block text-sm font-medium">Nama Dadia</label><input type="text" name="nama_dadia" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" required></div>--}}
{{--        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md h-fit text-sm">Simpan Dadia</button>--}}
{{--      </div>--}}
{{--    </form>--}}
{{--  </div>--}}

{{--  <!-- Statistik -->--}}
{{--  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">--}}
{{--    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200"><h3 class="text-gray-500 text-sm">Total Dadia</h3><p class="text-2xl font-bold text-blue-800">{{ $data['total_dadia'] }}</p></div>--}}
{{--    <div class="bg-green-50 p-4 rounded-lg border border-green-200"><h3 class="text-gray-500 text-sm">Total Penatahan</h3><p class="text-2xl font-bold text-green-800">{{ $data['total_penatahan'] }}</p></div>--}}
{{--  </div>--}}

{{--  <!-- Form Pencarian Kelihan Natah -->--}}
{{--  <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border">--}}
{{--    <h3 class="font-medium text-gray-700 mb-2">Pencarian Kelihan Natah</h3>--}}
{{--    <p class="text-xs text-gray-500 mb-2">Gunakan form ini untuk memfilter pilihan pada dropdown "Kelihan Natah" di bawah.</p>--}}
{{--    <form action="{{ route('master-data.index') }}" method="GET">--}}
{{--      <input type="hidden" name="tab" value="dadia"> --}}{{----}}{{-- Agar tab tetap aktif --}}
{{--      <div class="flex items-end gap-2">--}}
{{--        <div class="flex-1">--}}
{{--          <label for="kelihan_search" class="text-sm">Cari berdasarkan Nama/NIK/NIKA Kelihan</label>--}}
{{--          <input type="text" id="kelihan_search" name="kelihan_search" value="{{ $kelihanSearchTerm }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">--}}
{{--        </div>--}}
{{--        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md h-fit">Cari</button>--}}
{{--        @if($kelihanSearchTerm)--}}
{{--          <a href="{{ route('master-data.index', ['tab' => 'dadia']) }}" class="px-4 py-2 bg-gray-200 rounded-md h-fit">Reset</a>--}}
{{--        @endif--}}
{{--      </div>--}}
{{--    </form>--}}
{{--    @if($kelihanSearchTerm)--}}
{{--      <p class="text-xs text-blue-700 mt-2">Menampilkan hasil pencarian untuk "{{ $kelihanSearchTerm }}". Dropdown di bawah sudah terfilter.</p>--}}
{{--    @endif--}}
{{--  </div>--}}

{{--  <!-- Daftar Dadia dan Penatahan (Grouped) -->--}}
{{--  <div class="space-y-4">--}}
{{--    @forelse($dadia_penatahans as $dadia)--}}
{{--      <div class="border border-gray-200 rounded-lg">--}}
{{--        <!-- Header Dadia -->--}}
{{--        <div class="bg-gray-100 px-4 py-3 flex justify-between items-center">--}}
{{--          <div>--}}
{{--            <h3 class="font-bold text-gray-800">{{ $dadia->nama_dadia }}</h3>--}}
{{--            <p class="text-xs text-gray-500">Total Penatahan: {{ $dadia->penatahan_count }}</p>--}}
{{--          </div>--}}
{{--          <div class="flex items-center gap-x-4">--}}
{{--            <button @click="showAddPenatahanForm = (showAddPenatahanForm === {{ $dadia->id }} ? null : {{ $dadia->id }})" class="text-blue-600 hover:text-blue-900 text-xs font-medium"><i class="fas fa-plus"></i> Tambah Penatahan</button>--}}
{{--            <form action="{{ route('master-data.dadia.destroy', $dadia->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Dadia ini beserta semua Penatahannya?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Dadia"><i class="fas fa-trash"></i></button></form>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <!-- Form Tambah Penatahan -->--}}
{{--        <div x-show="showAddPenatahanForm === {{ $dadia->id }}" x-transition class="p-4 border-b">--}}
{{--          <form action="{{ route('master-data.penatahan.store') }}" method="POST">--}}
{{--            @csrf--}}
{{--            <input type="hidden" name="id_dadia_fk" value="{{ $dadia->id }}">--}}
{{--            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">--}}
{{--              <div><label class="block text-sm font-medium">Nama Penatahan</label><input type="text" name="nama_penatahan" class="mt-1 w-full px-2 py-1 border rounded" required></div>--}}
{{--              <div>--}}
{{--                <label class="block text-sm font-medium">Kelihan Natah</label>--}}
{{--                <select name="id_kelihan_adat_fk" class="mt-1 w-full px-2 py-1 border rounded">--}}
{{--                  <option value="">-- Pilih Krama --</option>--}}

{{--                  @foreach($data['all_krama_adat'] as $krama)--}}
{{--                    <option value="{{ $krama->id_identitas_adat }}">{{ $krama->masterIndividu->nama_lengkap }} ({{ $krama->masterAdat->kode_banjar_fk}})</option>--}}
{{--                  @endforeach--}}
{{--                </select>--}}
{{--              </div>--}}
{{--              <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs rounded h-fit">Simpan</button>--}}
{{--            </div>--}}
{{--          </form>--}}
{{--        </div>--}}

{{--        <!-- Tabel Penatahan -->--}}
{{--        <div class="overflow-x-auto">--}}
{{--          <table class="min-w-full">--}}
{{--            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">--}}
{{--            <tr><th class="px-4 py-2 text-left">Nama Penatahan</th><th class="px-4 py-2 text-left">Kelihan Natah</th><th class="px-4 py-2 text-right">Aksi</th></tr>--}}
{{--            </thead>--}}
{{--            <tbody class="divide-y divide-gray-200 text-sm">--}}
{{--            @forelse ($dadia->penatahan as $penatahan)--}}
{{--              <tr x-data="{ editing: false }">--}}
{{--                <td class="px-4 py-2"><span x-show="!editing">{{ $penatahan->nama_penatahan }}</span><div x-show="editing"><input form="editPenatahan-{{ $penatahan->id }}" type="text" name="nama_penatahan" value="{{ $penatahan->nama_penatahan }}" class="w-full px-2 py-1 border rounded"></div></td>--}}
{{--                <td class="px-4 py-2">--}}
{{--                                        <span x-show="!editing">--}}
{{--                                            @if($penatahan->kelihanAdat)--}}
{{--                                            <div>{{ $penatahan->kelihanAdat->masterIndividu->nama_lengkap }}</div>--}}
{{--                                            <div class="text-xs text-gray-500">Banjar: {{ $penatahan->kelihanAdat->banjar->nama_banjar }}</div>--}}
{{--                                            <div class="text-xs text-gray-500">Alamat: {{ $penatahan->kelihanAdat->masterIndividu->alamat }}</div>--}}
{{--                                          @else--}}
{{--                                            Belum diatur--}}
{{--                                          @endif--}}
{{--                                        </span>--}}
{{--                  <div x-show="editing">--}}
{{--                    <select form="editPenatahan-{{ $penatahan->id }}" name="id_kelihan_adat_fk" class="w-full px-2 py-1 border rounded">--}}
{{--                      <option value="">-- Kosongkan --</option>--}}
{{--                      @foreach($data['all_krama_adat'] as $krama)--}}
{{--                        <option value="{{ $krama->id_identitas_adat }}" {{ $penatahan->id_kelihan_adat_fk == $krama->id_identitas_adat ? 'selected' : '' }}>{{ $krama->masterIndividu->nama_lengkap }}</option>--}}
{{--                      @endforeach--}}
{{--                    </select>--}}
{{--                  </div>--}}
{{--                </td>--}}
{{--                <td class="px-4 py-2 text-right">--}}
{{--                  <div x-show="!editing" class="flex justify-end items-center gap-x-2"><button @click="editing = true" class="text-blue-600 hover:text-blue-900" title="Edit"><i class="fas fa-edit"></i></button><form action="{{ route('master-data.penatahan.destroy', $penatahan->id) }}" method="POST" onsubmit="return confirm('Yakin?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" title="Hapus"><i class="fas fa-trash"></i></button></form></div>--}}
{{--                  <div x-show="editing" class="flex justify-end items-center gap-x-2"><form id="editPenatahan-{{ $penatahan->id }}" action="{{ route('master-data.penatahan.update', $penatahan->id) }}" method="POST">@csrf @method('PUT')</form><button type="submit" form="editPenatahan-{{ $penatahan->id }}" class="text-green-600 hover:text-green-900" title="Simpan"><i class="fas fa-save"></i></button><button type="button" @click="editing = false" class="text-gray-600 hover:text-gray-900" title="Batal"><i class="fas fa-times"></i></button></div>--}}
{{--                </td>--}}
{{--              </tr>--}}
{{--            @empty--}}
{{--              <tr><td colspan="3" class="px-4 py-3 text-center text-gray-500 text-xs italic">Belum ada data penatahan untuk dadia ini.</td></tr>--}}
{{--            @endforelse--}}
{{--            </tbody>--}}
{{--          </table>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    @empty--}}
{{--      <div class="text-center py-8 text-gray-500">Tidak ada data dadia yang ditemukan.</div>--}}
{{--    @endforelse--}}
{{--  </div>--}}
{{--</div>--}}


{{--<div x-data="{ showAddDadiaForm: false, showAddPenatahanForm: null }">--}}
{{--  <div class="flex justify-between items-center mb-4">--}}
{{--    <h2 class="text-lg font-semibold text-gray-800">Data Dadia & Penatahan</h2>--}}
{{--    <div class="flex items-center gap-x-2">--}}
{{--      <a href="{{ route('master-data.dadia.export') }}" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors">--}}
{{--        <i class="fas fa-file-csv mr-1"></i> Export CSV--}}
{{--      </a>--}}
{{--      <button @click="showAddDadiaForm = !showAddDadiaForm" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">--}}
{{--        <span x-show="!showAddDadiaForm"><i class="fas fa-plus mr-1"></i> Tambah Dadia</span>--}}
{{--        <span x-show="showAddDadiaForm"><i class="fas fa-times mr-1"></i> Batal Tambah</span>--}}
{{--      </button>--}}
{{--    </div>--}}
{{--  </div>--}}

{{--  <!-- Form Tambah Dadia Baru -->--}}
{{--  <div x-show="showAddDadiaForm" x-transition class="bg-gray-50 p-4 rounded-lg mb-6 border">--}}
{{--    <h3 class="font-medium mb-2 text-gray-700">Form Tambah Dadia Baru</h3>--}}
{{--    <form action="{{ route('master-data.dadia.store') }}" method="POST">--}}
{{--      @csrf--}}
{{--      <div class="flex items-end gap-4">--}}
{{--        <div class="flex-1"><label class="block text-sm font-medium">Nama Dadia</label><input type="text" name="nama_dadia" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" required></div>--}}
{{--        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md h-fit text-sm">Simpan Dadia</button>--}}
{{--      </div>--}}
{{--    </form>--}}
{{--  </div>--}}

{{--  <!-- Statistik -->--}}
{{--  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">--}}
{{--    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200"><h3 class="text-gray-500 text-sm">Total Dadia</h3><p class="text-2xl font-bold text-blue-800">{{ $data['total_dadia'] }}</p></div>--}}
{{--    <div class="bg-green-50 p-4 rounded-lg border border-green-200"><h3 class="text-gray-500 text-sm">Total Penatahan</h3><p class="text-2xl font-bold text-green-800">{{ $data['total_penatahan'] }}</p></div>--}}
{{--  </div>--}}

{{--  <!-- Form Pencarian Kelihan Natah -->--}}
{{--  <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border">--}}
{{--    <h3 class="font-medium text-gray-700 mb-2">Pencarian Kelihan Natah</h3>--}}
{{--    <form action="{{ route('master-data.index') }}" method="GET">--}}
{{--      <input type="hidden" name="tab" value="dadia"> --}}{{----}}{{----}}{{----}}{{-- Agar tab tetap aktif --}}
{{--      <div class="flex items-end gap-2">--}}
{{--        <div class="flex-1">--}}
{{--          <label for="kelihan_search" class="text-sm">Cari berdasarkan Nama/NIK/NIKA Kelihan</label>--}}
{{--          <input type="text" id="kelihan_search" name="kelihan_search" value="{{ $kelihanSearchTerm }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md">--}}
{{--        </div>--}}
{{--        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md h-fit">Cari</button>--}}
{{--        @if($kelihanSearchTerm)--}}
{{--          <a href="{{ route('master-data.index', ['tab' => 'dadia']) }}" class="px-4 py-2 bg-gray-200 rounded-md h-fit">Reset</a>--}}
{{--        @endif--}}
{{--      </div>--}}
{{--    </form>--}}
{{--    @if($kelihanSearchTerm)--}}
{{--      <p class="text-xs text-blue-700 mt-2">Menampilkan hasil pencarian untuk "{{ $kelihanSearchTerm }}". Dropdown di bawah sudah terfilter.</p>--}}
{{--    @endif--}}
{{--  </div>--}}

{{--  <!-- Daftar Dadia dan Penatahan (Grouped) -->--}}
{{--  <div class="space-y-4">--}}
{{--    @forelse($dadia_penatahans as $dadia)--}}
{{--      <div class="border border-gray-200 rounded-lg">--}}
{{--        <!-- Header Dadia -->--}}
{{--        <div class="bg-gray-100 px-4 py-3 flex justify-between items-center">--}}
{{--          <div>--}}
{{--            <h3 class="font-bold text-gray-800">{{ $dadia->nama_dadia }}</h3>--}}
{{--            <p class="text-xs text-gray-500">Total Penatahan: {{ $dadia->penatahan_count }}</p>--}}
{{--          </div>--}}
{{--          <div class="flex items-center gap-x-4">--}}
{{--            <button @click="showAddPenatahanForm = (showAddPenatahanForm === {{ $dadia->id }} ? null : {{ $dadia->id }})" class="text-blue-600 hover:text-blue-900 text-xs font-medium"><i class="fas fa-plus"></i> Tambah Penatahan</button>--}}
{{--            <form action="{{ route('master-data.dadia.destroy', $dadia->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Dadia ini beserta semua Penatahannya?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Dadia"><i class="fas fa-trash"></i></button></form>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <!-- Form Tambah Penatahan -->--}}
{{--        <div x-show="showAddPenatahanForm === {{ $dadia->id }}" x-transition class="p-4 border-b">--}}
{{--          <form action="{{ route('master-data.penatahan.store') }}" method="POST">--}}
{{--            @csrf--}}
{{--            <input type="hidden" name="id_dadia_fk" value="{{ $dadia->id }}">--}}
{{--            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">--}}
{{--              <div><label class="block text-sm font-medium">Nama Penatahan</label><input type="text" name="nama_penatahan" class="mt-1 w-full px-2 py-1 border rounded" required></div>--}}
{{--              <div>--}}
{{--                <label class="block text-sm font-medium">Kelihan Natah</label>--}}
{{--                <select name="id_kelihan_adat_fk" class="mt-1 w-full px-2 py-1 border rounded">--}}
{{--                  <option value="">-- Pilih Krama --</option>--}}
{{--                  @foreach($data['all_krama_adat'] as $krama)--}}
{{--                    <option value="{{ $krama->id_identitas_adat }}">{{ $krama->masterIndividu->nama_lengkap }} ({{ $krama->nika }})</option>--}}
{{--                  @endforeach--}}
{{--                </select>--}}
{{--              </div>--}}
{{--              <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs rounded h-fit">Simpan</button>--}}
{{--            </div>--}}
{{--          </form>--}}
{{--        </div>--}}

{{--        <!-- Tabel Penatahan -->--}}
{{--        <div class="overflow-x-auto">--}}
{{--          <table class="min-w-full">--}}
{{--            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">--}}
{{--            <tr><th class="px-4 py-2 text-left">Nama Penatahan</th><th class="px-4 py-2 text-left">Kelihan Natah</th><th class="px-4 py-2 text-right">Aksi</th></tr>--}}
{{--            </thead>--}}
{{--            <tbody class="divide-y divide-gray-200 text-sm">--}}
{{--            @forelse ($dadia->penatahan as $penatahan)--}}
{{--              <tr x-data="{ editing: false }">--}}
{{--                <td class="px-4 py-2"><span x-show="!editing">{{ $penatahan->nama_penatahan }}</span><div x-show="editing"><input form="editPenatahan-{{ $penatahan->id }}" type="text" name="nama_penatahan" value="{{ $penatahan->nama_penatahan }}" class="w-full px-2 py-1 border rounded"></div></td>--}}
{{--                <td class="px-4 py-2">--}}
{{--                                        <span x-show="!editing">--}}
{{--                                            @if($penatahan->kelihanAdat)--}}
{{--                                            <div>{{ $penatahan->kelihanAdat->masterIndividu->nama_lengkap }}</div>--}}
{{--                                            <div class="text-xs text-gray-500">Banjar: {{ $penatahan->kelihanAdat->banjar->nama_banjar }}</div>--}}
{{--                                            <div class="text-xs text-gray-500">Alamat: {{ $penatahan->kelihanAdat->masterIndividu->alamat }}</div>--}}
{{--                                          @else--}}
{{--                                            Belum diatur--}}
{{--                                          @endif--}}
{{--                                        </span>--}}
{{--                  <div x-show="editing">--}}
{{--                    <select form="editPenatahan-{{ $penatahan->id }}" name="id_kelihan_adat_fk" class="w-full px-2 py-1 border rounded">--}}
{{--                      <option value="">-- Kosongkan --</option>--}}
{{--                      @foreach($data['all_krama_adat'] as $krama)--}}
{{--                        <option value="{{ $krama->id_identitas_adat }}" {{ $penatahan->id_kelihan_adat_fk == $krama->id_identitas_adat ? 'selected' : '' }}>{{ $krama->masterIndividu->nama_lengkap }}</option>--}}
{{--                      @endforeach--}}
{{--                    </select>--}}
{{--                  </div>--}}
{{--                </td>--}}
{{--                <td class="px-4 py-2 text-right">--}}
{{--                  <div x-show="!editing" class="flex justify-end items-center gap-x-2"><button @click="editing = true" class="text-blue-600 hover:text-blue-900" title="Edit"><i class="fas fa-edit"></i></button><form action="{{ route('master-data.penatahan.destroy', $penatahan->id) }}" method="POST" onsubmit="return confirm('Yakin?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" title="Hapus"><i class="fas fa-trash"></i></button></form></div>--}}
{{--                  <div x-show="editing" class="flex justify-end items-center gap-x-2"><form id="editPenatahan-{{ $penatahan->id }}" action="{{ route('master-data.penatahan.update', $penatahan->id) }}" method="POST">@csrf @method('PUT')</form><button type="submit" form="editPenatahan-{{ $penatahan->id }}" class="text-green-600 hover:text-green-900" title="Simpan"><i class="fas fa-save"></i></button><button type="button" @click="editing = false" class="text-gray-600 hover:text-gray-900" title="Batal"><i class="fas fa-times"></i></button></div>--}}
{{--                </td>--}}
{{--              </tr>--}}
{{--            @empty--}}
{{--              <tr><td colspan="3" class="px-4 py-3 text-center text-gray-500 text-xs italic">Belum ada data penatahan untuk dadia ini.</td></tr>--}}
{{--            @endforelse--}}
{{--            </tbody>--}}
{{--          </table>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    @empty--}}
{{--      <div class="text-center py-8 text-gray-500">Tidak ada data dadia yang ditemukan.</div>--}}
{{--    @endforelse--}}
{{--  </div>--}}
{{--</div>--}}





{{--<div x-data="{ showAddDadiaForm: false, showAddPenatahanForm: null }">--}}
{{--  <div class="flex justify-between items-center mb-4">--}}
{{--    <h2 class="text-lg font-semibold text-gray-800">Data Dadia & Penatahan</h2>--}}
{{--    <div class="flex items-center gap-x-2">--}}
{{--      <a href="{{ route('master-data.dadia.export') }}" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors">--}}
{{--        <i class="fas fa-file-csv mr-1"></i> Export CSV--}}
{{--      </a>--}}
{{--      <button @click="showAddDadiaForm = !showAddDadiaForm" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">--}}
{{--        <span x-show="!showAddDadiaForm"><i class="fas fa-plus mr-1"></i> Tambah Dadia</span>--}}
{{--        <span x-show="showAddDadiaForm"><i class="fas fa-times mr-1"></i> Batal Tambah</span>--}}
{{--      </button>--}}
{{--    </div>--}}
{{--  </div>--}}

{{--  <!-- Form Tambah Dadia Baru -->--}}
{{--  <div x-show="showAddDadiaForm" x-transition class="bg-gray-50 p-4 rounded-lg mb-6 border">--}}
{{--    <h3 class="font-medium mb-2 text-gray-700">Form Tambah Dadia Baru</h3>--}}
{{--    <form action="{{ route('master-data.dadia.store') }}" method="POST">--}}
{{--      @csrf--}}
{{--      <div class="flex items-end gap-4">--}}
{{--        <div class="flex-1"><label class="block text-sm font-medium">Nama Dadia</label><input type="text" name="nama_dadia" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" required></div>--}}
{{--        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md h-fit text-sm">Simpan Dadia</button>--}}
{{--      </div>--}}
{{--    </form>--}}
{{--  </div>--}}

{{--  <!-- Statistik -->--}}
{{--  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">--}}
{{--    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200"><h3 class="text-gray-500 text-sm">Total Dadia</h3><p class="text-2xl font-bold text-blue-800">{{ $data['total_dadia'] }}</p></div>--}}
{{--    <div class="bg-green-50 p-4 rounded-lg border border-green-200"><h3 class="text-gray-500 text-sm">Total Penatahan</h3><p class="text-2xl font-bold text-green-800">{{ $data['total_penatahan'] }}</p></div>--}}
{{--  </div>--}}

{{--  <!-- Daftar Dadia dan Penatahan (Grouped) -->--}}
{{--  <div class="space-y-4">--}}
{{--    @forelse($dadia_penatahans as $dadia)--}}
{{--      <div class="border border-gray-200 rounded-lg">--}}
{{--        <!-- Header Dadia -->--}}
{{--        <div class="bg-gray-100 px-4 py-3 flex justify-between items-center">--}}
{{--          <div>--}}
{{--            <h3 class="font-bold text-gray-800">{{ $dadia->nama_dadia }}</h3>--}}
{{--            <p class="text-xs text-gray-500">Total Penatahan: {{ $dadia->penatahan_count }}</p>--}}
{{--          </div>--}}
{{--          <div class="flex items-center gap-x-4">--}}
{{--            <button @click="showAddPenatahanForm = (showAddPenatahanForm === {{ $dadia->id }} ? null : {{ $dadia->id }})" class="text-blue-600 hover:text-blue-900 text-xs font-medium"><i class="fas fa-plus"></i> Tambah Penatahan</button>--}}
{{--            <form action="{{ route('master-data.dadia.destroy', $dadia->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Dadia ini beserta semua Penatahannya?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Dadia"><i class="fas fa-trash"></i></button></form>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <!-- Form Tambah Penatahan -->--}}
{{--        <div x-show="showAddPenatahanForm === {{ $dadia->id }}" x-transition class="p-4 border-b">--}}
{{--          <form action="{{ route('master-data.penatahan.store') }}" method="POST">--}}
{{--            @csrf--}}
{{--            <input type="hidden" name="id_dadia_fk" value="{{ $dadia->id }}">--}}
{{--            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">--}}
{{--              <div><label class="block text-sm font-medium">Nama Penatahan</label><input type="text" name="nama_penatahan" class="mt-1 w-full px-2 py-1 border rounded" required></div>--}}
{{--              <div>--}}
{{--                <label class="block text-sm font-medium">Kelihan Natah</label>--}}
{{--                <select name="id_kelihan_adat_fk" class="mt-1 w-full px-2 py-1 border rounded">--}}
{{--                  <option value="">-- Pilih Krama --</option>--}}
{{--                  @foreach($data['all_krama_adat'] as $krama)--}}
{{--                    <option value="{{ $krama->id_identitas_adat }}">{{ $krama->masterIndividu->nama_lengkap }} ({{ $krama->nika }})</option>--}}
{{--                  @endforeach--}}
{{--                </select>--}}
{{--              </div>--}}
{{--              <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs rounded h-fit">Simpan</button>--}}
{{--            </div>--}}
{{--          </form>--}}
{{--        </div>--}}

{{--        <!-- Tabel Penatahan -->--}}
{{--        <div class="overflow-x-auto">--}}
{{--          <table class="min-w-full">--}}
{{--            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">--}}
{{--            <tr><th class="px-4 py-2 text-left">Nama Penatahan</th><th class="px-4 py-2 text-left">Kelihan Natah</th><th class="px-4 py-2 text-right">Aksi</th></tr>--}}
{{--            </thead>--}}
{{--            <tbody class="divide-y divide-gray-200 text-sm">--}}
{{--            @forelse ($dadia->penatahan as $penatahan)--}}
{{--              <tr x-data="{ editing: false }">--}}
{{--                <td class="px-4 py-2"><span x-show="!editing">{{ $penatahan->nama_penatahan }}</span><div x-show="editing"><input form="editPenatahan-{{ $penatahan->id }}" type="text" name="nama_penatahan" value="{{ $penatahan->nama_penatahan }}" class="w-full px-2 py-1 border rounded"></div></td>--}}
{{--                <td class="px-4 py-2">--}}
{{--                  <span x-show="!editing">{{ $penatahan->kelihanAdat->masterIndividu->nama_lengkap ?? $penatahan->kelihan_natah ?? 'Belum diatur' }}</span>--}}
{{--                  <div x-show="editing">--}}
{{--                    <select form="editPenatahan-{{ $penatahan->id }}" name="id_kelihan_adat_fk" class="w-full px-2 py-1 border rounded">--}}
{{--                      <option value="">-- Kosongkan --</option>--}}
{{--                      @foreach($data['all_krama_adat'] as $krama)--}}
{{--                        <option value="{{ $krama->id_identitas_adat }}" {{ $penatahan->id_kelihan_adat_fk == $krama->id_identitas_adat ? 'selected' : '' }}>{{ $krama->masterIndividu->nama_lengkap }}</option>--}}
{{--                      @endforeach--}}
{{--                    </select>--}}
{{--                  </div>--}}
{{--                </td>--}}
{{--                <td class="px-4 py-2 text-right">--}}
{{--                  <div x-show="!editing" class="flex justify-end items-center gap-x-2"><button @click="editing = true" class="text-blue-600 hover:text-blue-900" title="Edit"><i class="fas fa-edit"></i></button><form action="{{ route('master-data.penatahan.destroy', $penatahan->id) }}" method="POST" onsubmit="return confirm('Yakin?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" title="Hapus"><i class="fas fa-trash"></i></button></form></div>--}}
{{--                  <div x-show="editing" class="flex justify-end items-center gap-x-2"><form id="editPenatahan-{{ $penatahan->id }}" action="{{ route('master-data.penatahan.update', $penatahan->id) }}" method="POST">@csrf @method('PUT')</form><button type="submit" form="editPenatahan-{{ $penatahan->id }}" class="text-green-600 hover:text-green-900" title="Simpan"><i class="fas fa-save"></i></button><button type="button" @click="editing = false" class="text-gray-600 hover:text-gray-900" title="Batal"><i class="fas fa-times"></i></button></div>--}}
{{--                </td>--}}
{{--              </tr>--}}
{{--            @empty--}}
{{--              <tr><td colspan="3" class="px-4 py-3 text-center text-gray-500 text-xs italic">Belum ada data penatahan untuk dadia ini.</td></tr>--}}
{{--            @endforelse--}}
{{--            </tbody>--}}
{{--          </table>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    @empty--}}
{{--      <div class="text-center py-8 text-gray-500">Tidak ada data dadia yang ditemukan.</div>--}}
{{--    @endforelse--}}
{{--  </div>--}}
{{--</div>--}}



{{--<div x-data="{ showAddDadiaForm: false, showAddPenatahanForm: null }">--}}
{{--  <div class="flex justify-between items-center mb-4">--}}
{{--    <h2 class="text-lg font-semibold text-gray-800">Data Dadia & Penatahan</h2>--}}
{{--    <div class="flex items-center gap-x-2">--}}
{{--      --}}{{----}}{{----}}{{----}}{{----}}{{----}}{{----}}{{----}}{{----}}{{----}}{{----}}{{----}}{{----}}{{----}}{{----}}{{----}}{{-- Tombol Export Baru --}}
{{--      <a href="{{ route('master-data.dadia.export') }}" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors">--}}
{{--        <i class="fas fa-file-csv mr-1"></i> Export CSV--}}
{{--      </a>--}}
{{--      <button @click="showAddDadiaForm = !showAddDadiaForm" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">--}}
{{--        <span x-show="!showAddDadiaForm"><i class="fas fa-plus mr-1"></i> Tambah Dadia</span>--}}
{{--        <span x-show="showAddDadiaForm"><i class="fas fa-times mr-1"></i> Batal Tambah</span>--}}
{{--      </button>--}}
{{--    </div>--}}
{{--  </div>--}}

{{--  <!-- Form Tambah Dadia Baru -->--}}
{{--  <div x-show="showAddDadiaForm" x-transition class="bg-gray-50 p-4 rounded-lg mb-6 border">--}}
{{--    <h3 class="font-medium mb-2 text-gray-700">Form Tambah Dadia Baru</h3>--}}
{{--    <form action="{{ route('master-data.dadia.store') }}" method="POST">--}}
{{--      @csrf--}}
{{--      <div class="flex items-end gap-4">--}}
{{--        <div class="flex-1"><label class="block text-sm font-medium">Nama Dadia</label><input type="text" name="nama_dadia" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md" required></div>--}}
{{--        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md h-fit text-sm">Simpan Dadia</button>--}}
{{--      </div>--}}
{{--    </form>--}}
{{--  </div>--}}

{{--  <!-- Statistik -->--}}
{{--  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">--}}
{{--    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">--}}
{{--      <h3 class="text-gray-500 text-sm">Total Dadia</h3>--}}
{{--      <p class="text-2xl font-bold text-blue-800">{{ $data['total_dadia'] }}</p>--}}
{{--    </div>--}}
{{--    <div class="bg-green-50 p-4 rounded-lg border border-green-200">--}}
{{--      <h3 class="text-gray-500 text-sm">Total Penatahan</h3>--}}
{{--      <p class="text-2xl font-bold text-green-800">{{ $data['total_penatahan'] }}</p>--}}
{{--    </div>--}}
{{--  </div>--}}

{{--  <!-- Daftar Dadia dan Penatahan (Grouped) -->--}}
{{--  <div class="space-y-4">--}}
{{--    @forelse($dadia_penatahans as $dadia)--}}
{{--      <div class="border border-gray-200 rounded-lg">--}}
{{--        <!-- Header Dadia -->--}}
{{--        <div class="bg-gray-100 px-4 py-3 flex justify-between items-center">--}}
{{--          <div>--}}
{{--            <h3 class="font-bold text-gray-800">{{ $dadia->nama_dadia }}</h3>--}}
{{--            <p class="text-xs text-gray-500">Total Penatahan: {{ $dadia->penatahan_count }}</p>--}}
{{--          </div>--}}
{{--          <div class="flex items-center gap-x-4">--}}
{{--            <button @click="showAddPenatahanForm = (showAddPenatahanForm === {{ $dadia->id }} ? null : {{ $dadia->id }})" class="text-blue-600 hover:text-blue-900 text-xs font-medium">--}}
{{--              <i class="fas fa-plus"></i> Tambah Penatahan--}}
{{--            </button>--}}
{{--            <form action="{{ route('master-data.dadia.destroy', $dadia->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Dadia ini beserta semua Penatahannya?');">--}}
{{--              @csrf--}}
{{--              @method('DELETE')--}}
{{--              <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Dadia">--}}
{{--                <i class="fas fa-trash"></i>--}}
{{--              </button>--}}
{{--            </form>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <!-- Form Tambah Penatahan (muncul di bawah dadia yang sesuai) -->--}}
{{--        <div x-show="showAddPenatahanForm === {{ $dadia->id }}" x-transition class="p-4 border-b">--}}
{{--          <form action="{{ route('master-data.penatahan.store') }}" method="POST">--}}
{{--            @csrf--}}
{{--            <input type="hidden" name="id_dadia_fk" value="{{ $dadia->id }}">--}}
{{--            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">--}}
{{--              <div><label class="block text-sm font-medium">Nama Penatahan</label><input type="text" name="nama_penatahan" class="mt-1 w-full px-2 py-1 border rounded" required></div>--}}
{{--              <div><label class="block text-sm font-medium">Kelihan Natah</label><input type="text" name="kelihan_natah" class="mt-1 w-full px-2 py-1 border rounded"></div>--}}
{{--              <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs rounded h-fit">Simpan</button>--}}
{{--            </div>--}}
{{--          </form>--}}
{{--        </div>--}}

{{--        <!-- Tabel Penatahan -->--}}
{{--        <div class="overflow-x-auto">--}}
{{--          <table class="min-w-full">--}}
{{--            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">--}}
{{--            <tr>--}}
{{--              <th class="px-4 py-2 text-left">Nama Penatahan</th>--}}
{{--              <th class="px-4 py-2 text-left">Kelihan Natah</th>--}}
{{--              <th class="px-4 py-2 text-right">Aksi</th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody class="divide-y divide-gray-200 text-sm">--}}
{{--            @forelse ($dadia->penatahan as $penatahan)--}}
{{--              <tr x-data="{ editing: false }">--}}
{{--                <td class="px-4 py-2">--}}
{{--                  <span x-show="!editing">{{ $penatahan->nama_penatahan }}</span>--}}
{{--                  <div x-show="editing"><input form="editPenatahan-{{ $penatahan->id }}" type="text" name="nama_penatahan" value="{{ $penatahan->nama_penatahan }}" class="w-full px-2 py-1 border rounded"></div>--}}
{{--                </td>--}}
{{--                <td class="px-4 py-2">--}}
{{--                  <span x-show="!editing">{{ $penatahan->kelihan_natah }}</span>--}}
{{--                  <div x-show="editing"><input form="editPenatahan-{{ $penatahan->id }}" type="text" name="kelihan_natah" value="{{ $penatahan->kelihan_natah }}" class="w-full px-2 py-1 border rounded"></div>--}}
{{--                </td>--}}
{{--                <td class="px-4 py-2 text-right">--}}
{{--                  <div x-show="!editing" class="flex justify-end items-center gap-x-2">--}}
{{--                    <button @click="editing = true" class="text-blue-600 hover:text-blue-900" title="Edit"><i class="fas fa-edit"></i></button>--}}
{{--                    <form action="{{ route('master-data.penatahan.destroy', $penatahan->id) }}" method="POST" onsubmit="return confirm('Yakin?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" title="Hapus"><i class="fas fa-trash"></i></button></form>--}}
{{--                  </div>--}}
{{--                  <div x-show="editing" class="flex justify-end items-center gap-x-2">--}}
{{--                    <form id="editPenatahan-{{ $penatahan->id }}" action="{{ route('master-data.penatahan.update', $penatahan->id) }}" method="POST">@csrf @method('PUT')</form>--}}
{{--                    <button type="submit" form="editPenatahan-{{ $penatahan->id }}" class="text-green-600 hover:text-green-900" title="Simpan"><i class="fas fa-save"></i></button>--}}
{{--                    <button type="button" @click="editing = false" class="text-gray-600 hover:text-gray-900" title="Batal"><i class="fas fa-times"></i></button>--}}
{{--                  </div>--}}
{{--                </td>--}}
{{--              </tr>--}}
{{--            @empty--}}
{{--              <tr><td colspan="3" class="px-4 py-3 text-center text-gray-500 text-xs italic">Belum ada data penatahan untuk dadia ini.</td></tr>--}}
{{--            @endforelse--}}
{{--            </tbody>--}}
{{--          </table>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    @empty--}}
{{--      <div class="text-center py-8 text-gray-500">Tidak ada data dadia yang ditemukan.</div>--}}
{{--    @endforelse--}}
{{--  </div>--}}
{{--</div>--}}
