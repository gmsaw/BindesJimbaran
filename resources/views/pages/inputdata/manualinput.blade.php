<div id="manualContent" class="tab-content ">
                 
  <div class="max-w-4xl mx-auto py-2 px-4" x-data="familyForm()" x-init="init()">

      <!-- Main Form -->
      <div class="p-0.5">
        <!-- Kepala Keluarga -->
        <div class="mb-8 border border-gray-200 rounded-lg overflow-hidden">
          <div class="bg-gray-100 px-4 py-3 flex justify-between items-center cursor-pointer" 
               @click="toggleSection('head')">
            <h2 class="font-semibold text-gray-800">
              <i class="fas fa-user-shield mr-2 text-blue-500"></i> Kepala Keluarga
            </h2>
            <i class="fas" :class="sections['head'] ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
          </div>
          <div x-show="sections['head']" x-transition class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" x-model="head.name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No KK</label>
                <input type="text" x-model="head.name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                <input type="text" x-model="head.nik" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NPK</label>
                <input type="text" x-model="head.nik" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIKA</label>
                <input type="text" x-model="head.nik" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                <input type="text" x-model="head.birthPlace" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                <input type="date" x-model="head.birthPlace" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <input type="text" x-model="head.birthPlace" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan</label>
                <input type="text" x-model="head.birthPlace" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                <input type="text" x-model="head.birthPlace" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                <input type="text" x-model="head.birthPlace" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Kawin</label>
                <input type="text" x-model="head.birthPlace" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kawin</label>
                <input type="date" x-model="head.birthPlace" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kewarganegaraan</label>
                <input type="text" x-model="head.birthPlace" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                      <div class="flex space-x-4 mt-2">
                        <label class="inline-flex items-center">
                          <input type="radio" x-model="member.gender" value="Laki-laki" class="text-blue-500">
                          <span class="ml-2">Laki-laki</span>
                        </label>
                        <label class="inline-flex items-center">
                          <input type="radio" x-model="member.gender" value="Perempuan" class="text-blue-500">
                          <span class="ml-2">Perempuan</span>
                        </label>
                      </div>
                </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                <input type="date" x-model="head.birthDate" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
              </div>
            </div>
          </div>
        </div>

        <!-- Anggota Keluarga -->
        <div class="mb-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">
              <i class="fas fa-user-friends mr-2 text-blue-500"></i> Anggota Keluarga
            </h2>
            <button @click="addMember" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">
              <i class="fas fa-plus mr-1"></i> Tambah Anggota
            </button>
          </div>

          <div class="space-y-4">
            <template x-for="(member, index) in members" :key="index">
              <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-100 px-4 py-3 flex justify-between items-center cursor-pointer" 
                     @click="toggleMember(index)">
                  <h3 class="font-medium text-gray-800">
                    <i class="fas fa-user mr-2" :class="memberColors['index % memberColors.length']"></i>
                    Anggota Keluarga <span x-text="index + 1"></span>
                  </h3>
                  <div>
                    <button @click.stop="removeMember(index)" class="text-red-500 hover:text-red-700 mr-3">
                      <i class="fas fa-trash"></i>
                    </button>
                    <i class="fas" :class="member['expanded'] ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                  </div>
                </div>
                <div x-show="member.expanded" x-transition class="p-4">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                      <input type="text" x-model="member.name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                      <input type="text" x-model="member.nik" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Hubungan</label>
                      <select x-model="member.relationship" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        <option value="Istri">Istri</option>
                        <option value="Anak">Anak</option>
                        <option value="Orang Tua">Orang Tua</option>
                        <option value="Lainnya">Lainnya</option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                      <div class="flex space-x-4 mt-2">
                        <label class="inline-flex items-center">
                          <input type="radio" x-model="member.gender" value="Laki-laki" class="text-blue-500">
                          <span class="ml-2">Laki-laki</span>
                        </label>
                        <label class="inline-flex items-center">
                          <input type="radio" x-model="member.gender" value="Perempuan" class="text-blue-500">
                          <span class="ml-2">Perempuan</span>
                        </label>
                      </div>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                      <input type="text" x-model="member.birthPlace" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                      <input type="date" x-model="member.birthDate" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>

        <!-- Tombol Submit -->
        <div class="flex justify-end space-x-3 mt-8 pt-4 border-t">
          <button type="button" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition">
            <i class="fas fa-times mr-1"></i> Batal
          </button>
          <button type="button" @click="submitForm" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
            <i class="fas fa-save mr-1"></i> Simpan Data
          </button>
        </div>
      </div>
  </div>

</div>

  <script>
    function familyForm() {
      return {
        sections: {
          head: true
        },
        head: {
          name: '',
          nik: '',
          birthPlace: '',
          birthDate: ''
        },
        members: [],
        memberColors: ['text-green-500', 'text-purple-500', 'text-yellow-500', 'text-pink-500'],

        init() {
          this.addMember();
        },
        toggleSection(section) {
          this.sections[section] = !this.sections[section];
        },
        toggleMember(index) {
          this.members[index].expanded = !this.members[index].expanded;
        },
        addMember() {
          this.members.push({
            name: '',
            nik: '',
            relationship: 'Anak',
            gender: 'Laki-laki',
            birthPlace: '',
            birthDate: '',
            expanded: true
          });
        },
        removeMember(index) {
          if (confirm('Apakah Anda yakin ingin menghapus anggota ini?')) {
            this.members.splice(index, 1);
          }
        },
        submitForm() {
          // Validasi minimal
          if (!this.head.name || !this.head.nik) {
            alert("Mohon isi data Kepala Keluarga minimal nama dan NIK.");
            return;
          }

          alert('Data keluarga berhasil disimpan!\n' + JSON.stringify({
            kepalaKeluarga: this.head,
            anggota: this.members
          }, null, 2));
        }
      }
    }
  </script>