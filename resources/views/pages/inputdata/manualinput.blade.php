<div id="manualContent" class="tab-content">
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
                  <i class="fas fa-user mr-2" :class="memberColors[index % memberColors.length]"></i>
                  Anggota Keluarga <span x-text="index + 1"></span>
                </h3>
                <div>
                  <button @click.stop="showDeleteConfirmation(index)" class="text-red-500 hover:text-red-700 mr-3">
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
        <button type="button" @click="validateForm" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
          <i class="fas fa-save mr-1"></i> Simpan Data
        </button>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div x-cloak>
      <!-- Delete Member Modal -->
      <div x-show="showDeleteModal" 
           class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0">
           
        <!-- Modal Content -->
        <div x-show="showDeleteModal"
             class="bg-white rounded-lg shadow-xl max-w-md w-full"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            
           <div class="p-6">
              <div class="flex items-center mb-4">
                  <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                      <i class="fas fa-exclamation text-red-600"></i>
                  </div>
                  <div class="ml-4">
                      <h3 class="text-lg font-medium text-gray-900">Hapus Anggota Keluarga</h3>
                  </div>
              </div>
              
              <div class="mt-2">
                  <p class="text-sm text-gray-500">
                      Apakah Anda yakin ingin menghapus anggota keluarga ini?
                  </p>
              </div>
              
              <div class="mt-4 flex justify-end space-x-3">
                  <button @click="showDeleteModal = false" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                      Batal
                  </button>
                  <button @click="confirmDelete" type="button" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                      Ya, Hapus
                  </button>
              </div>
          </div>
        </div>
      </div>

      <!-- Validation Error Modal -->
      <div x-show="showErrorModal" 
           class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0">
           
        <!-- Modal Content -->
        <div x-show="showErrorModal"
             class="bg-white rounded-lg shadow-xl max-w-md w-full"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            
           <div class="p-6">
              <div class="flex items-center mb-4">
                  <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                      <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                  </div>
                  <div class="ml-4">
                      <h3 class="text-lg font-medium text-gray-900">Data Belum Lengkap</h3>
                  </div>
              </div>
              
              <div class="mt-2">
                  <p class="text-sm text-gray-500" x-text="errorMessage">
                  </p>
              </div>
              
              <div class="mt-4 flex justify-end">
                  <button @click="showErrorModal = false" type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                      OK
                  </button>
              </div>
          </div>
        </div>
      </div>

      <!-- Success Modal -->
      <div x-show="showSuccessModal" 
           class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0">
           
        <!-- Modal Content -->
        <div x-show="showSuccessModal"
             class="bg-white rounded-lg shadow-xl max-w-md w-full"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            
           <div class="p-6">
              <div class="flex items-center mb-4">
                  <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                      <i class="fas fa-check text-green-600"></i>
                  </div>
                  <div class="ml-4">
                      <h3 class="text-lg font-medium text-gray-900">Data Tersimpan</h3>
                  </div>
              </div>
              
              <div class="mt-2">
                  <p class="text-sm text-gray-500">
                      Data keluarga berhasil disimpan!
                  </p>
              </div>
              
              <div class="mt-4 flex justify-end">
                  <button @click="showSuccessModal = false" type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                      OK
                  </button>
              </div>
          </div>
        </div>
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
      showDeleteModal: false,
      showErrorModal: false,
      showSuccessModal: false,
      errorMessage: '',
      memberToDelete: null,

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
      showDeleteConfirmation(index) {
        this.memberToDelete = index;
        this.showDeleteModal = true;
      },
      confirmDelete() {
        this.members.splice(this.memberToDelete, 1);
        this.showDeleteModal = false;
        this.memberToDelete = null;
      },
      validateForm() {
        // Validasi minimal
        if (!this.head.name || !this.head.nik) {
          this.errorMessage = "Mohon isi data Kepala Keluarga minimal nama dan NIK.";
          this.showErrorModal = true;
          return;
        }

        // Check if any member has empty name or NIK
        const invalidMember = this.members.find(member => !member.name || !member.nik);
        if (invalidMember) {
          this.errorMessage = "Mohon lengkapi nama dan NIK untuk semua anggota keluarga.";
          this.showErrorModal = true;
          return;
        }

        this.submitForm();
      },
      submitForm() {
        // Here you would typically send the data to a server
        console.log('Data submitted:', {
          kepalaKeluarga: this.head,
          anggota: this.members
        });
        
        // Show success message
        this.showSuccessModal = true;
      }
    }
  }
</script>