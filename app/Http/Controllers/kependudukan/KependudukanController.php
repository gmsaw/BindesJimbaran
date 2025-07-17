<?php

namespace App\Http\Controllers\kependudukan;

// Import semua model yang akan kita gunakan
use App\Http\Controllers\Controller;
use App\Models\AddForKramaTamiu;
use App\Models\AddForTamiu;
use App\Models\AnggotaKkAdat;
use App\Models\Banjar;
use App\Models\BendesaAdat;
use App\Models\Dadia;
use App\Models\DadiaPenatahan;
use App\Models\KartuKeluargaAdat;
use App\Models\KeteranganKeluarga;
use App\Models\KlasifikasiKrama;
use App\Models\NpkCounter;
use App\Models\MasterAdat;
use App\Models\MasterIndividu;
use App\Models\Penatahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

\Carbon\Carbon::setLocale('id');

class KependudukanController extends Controller
{
    public function bulkPrint($kode_banjar)
    {
      // 1. Ambil SEMUA data KK Adat dari banjar yang dipilih.
      // Eager load semua relasi yang dibutuhkan untuk setiap kartu agar efisien.
      $kk_list = KartuKeluargaAdat::where('kode_banjar_fk', $kode_banjar)
        ->with([
          'banjar',
          'klasifikasiKrama',
          'keteranganKeluarga.dadiaPenatahan',
          'anggota.masterAdat.masterIndividu' // Ini penting untuk data anggota
        ])
        ->get();

      // Jika tidak ada KK di banjar tersebut, tampilkan pesan.
      if ($kk_list->isEmpty()) {
        abort(404, 'Tidak ada data Kartu Keluarga yang ditemukan untuk banjar ini.');
      }

      // 2. Ambil data Bendesa Adat yang sedang aktif.
      $bendesa_adat_aktif = BendesaAdat::where('status_jabatan', 'aktif')->first();

      // 3. Kirim koleksi/daftar KK ke view baru untuk dicetak.

      // 4. Tambahan

      return view('pages.kependudukan.bulk.bulkPrint', [
        'title'              => 'Cetak Massal KK Adat',
        'kk_list'            => $kk_list,
        'bendesa_adat_aktif' => $bendesa_adat_aktif,
        'berlaku'            => 5,
      ]);
    }


    /**
     * Menampilkan daftar Kartu Keluarga Adat, difilter berdasarkan Banjar.
     */
    public function index(Request $request)
    {
        // 1. Dapatkan semua banjar untuk dropdown filter
        $allBanjar = Banjar::orderBy('kode_banjar')->get();

        // 2. Dapatkan kode banjar yang dipilih dari request, atau gunakan yang pertama sebagai default
        $selectedBanjarCode = $request->input('banjar', $allBanjar->first()->kode_banjar ?? null);

        // Dapatkan istilah pencarian
        $searchTerm = $request->input('search');

        // 3. Bangun query utama dari model KartuKeluargaAdat
        $query = KartuKeluargaAdat::query()
            // Eager load relasi untuk efisiensi: banjar, dan kepala keluarga beserta data individunya
            ->with(['banjar', 'kepalaKeluarga.masterAdat.masterIndividu'])
            // Filter berdasarkan banjar yang dipilih
            ->where('kode_banjar_fk', $selectedBanjarCode);

        // 4. Terapkan filter pencarian jika ada
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('npk', 'like', '%' . $searchTerm . '%') // Cari berdasarkan NPK
                ->orWhere('nkk', 'like', '%' . $searchTerm . '%') // Cari berdasarkan NKK Nasional
                // Cari di dalam relasi (nama atau NIK kepala keluarga)
                ->orWhereHas('kepalaKeluarga.masterAdat.masterIndividu', function ($subQuery) use ($searchTerm) {
                    $subQuery->where('nama_lengkap', 'like', '%' . $searchTerm . '%')
                        ->orWhere('nik_nasional', 'like', '%' . $searchTerm . '%');
                });
            });
        }

//        dd($request->all());

        // 5. Eksekusi query dengan paginasi agar halaman tidak berat
//        $daftar_kk = $query->orderBy('npk')->where('status_adat', '=', 'krama_adat')->paginate(20);

        $daftar_kk = $query->orderBy('npk')->where('status_adat', '=', $request->input('krama_request'))->paginate(20);

        // 6. Kirim semua data yang diperlukan ke view
        return view('pages.kependudukan.basis_kka.index', [ // Sesuaikan dengan path view Anda
            'title' => 'Data KK Adat Banjar ' . ($allBanjar->firstWhere('kode_banjar', $selectedBanjarCode)->nama_banjar ?? ''),
            'allBanjar' => $allBanjar,
            'selectedBanjar' => $selectedBanjarCode,
            'daftar_kk' => $daftar_kk,
            'searchTerm' => $searchTerm,
            'krama_request' => $request->input('krama_request'),
        ]);
    }

  public function indexKramaTamiu(Request $request)
  {
    // 1. Dapatkan semua banjar untuk dropdown filter
    $allBanjar = Banjar::orderBy('kode_banjar')->get();

    // 2. Dapatkan kode banjar yang dipilih dari request, atau gunakan yang pertama sebagai default
    $selectedBanjarCode = $request->input('banjar', $allBanjar->first()->kode_banjar ?? null);

    // Dapatkan istilah pencarian
    $searchTerm = $request->input('search');

    // 3. Bangun query utama dari model KartuKeluargaAdat
    $query = KartuKeluargaAdat::query()
      // Eager load relasi untuk efisiensi: banjar, dan kepala keluarga beserta data individunya
      ->with(['banjar', 'kepalaKeluarga.masterAdat.masterIndividu'])
      // Filter berdasarkan banjar yang dipilih
      ->where('kode_banjar_fk', $selectedBanjarCode);

    // 4. Terapkan filter pencarian jika ada
    if ($searchTerm) {
      $query->where(function ($q) use ($searchTerm) {
        $q->where('npk', 'like', '%' . $searchTerm . '%') // Cari berdasarkan NPK
        ->orWhere('nkk', 'like', '%' . $searchTerm . '%') // Cari berdasarkan NKK Nasional
        // Cari di dalam relasi (nama atau NIK kepala keluarga)
        ->orWhereHas('kepalaKeluarga.masterAdat.masterIndividu', function ($subQuery) use ($searchTerm) {
          $subQuery->where('nama_lengkap', 'like', '%' . $searchTerm . '%')
            ->orWhere('nik_nasional', 'like', '%' . $searchTerm . '%');
        });
      });
    }

//        dd($request->all());

    // 5. Eksekusi query dengan paginasi agar halaman tidak berat
    $daftar_kk = $query->orderBy('npk')->where('status_adat', '=', 'krama_tamiu')->paginate(20);

    // 6. Kirim semua data yang diperlukan ke view
    return view('pages.kependudukan.basis_kka.index', [ // Sesuaikan dengan path view Anda
      'title' => 'Data KK Adat Banjar ' . ($allBanjar->firstWhere('kode_banjar', $selectedBanjarCode)->nama_banjar ?? ''),
      'allBanjar' => $allBanjar,
      'selectedBanjar' => $selectedBanjarCode,
      'daftar_kk' => $daftar_kk,
      'searchTerm' => $searchTerm
    ]);
  }

    public function show($npk)
    {
      // Query utama: Cari KK Adat berdasarkan NPK.
      // Eager load semua relasi yang dibutuhkan untuk efisiensi.
      $kk_adat = KartuKeluargaAdat::where('npk', $npk)
        ->with([
          'banjar', // Untuk menampilkan nama banjar
          'kepalaKeluarga.masterAdat.masterIndividu', // Untuk menampilkan nama kepala keluarga
          // Ini adalah query untuk anggota:
          'anggota' => function ($query) {
            // Urutkan anggota, misalnya berdasarkan id
            $query->orderBy('id_keanggotaan');
          },
          // Muat juga data lengkap individu untuk setiap anggota
          'anggota.masterAdat.masterIndividu'
        ])
        ->firstOrFail(); // Akan menampilkan 404 Not Found jika NPK tidak ada

      // Kirim data yang ditemukan ke view detail
      return view('pages.kependudukan.basis_kka.show', [
        'title' => 'Detail Anggota KK: ' . $kk_adat->npk,
        'kk_adat' => $kk_adat
      ]);
    }

    public function printCardFamilyTest($npk='04190406010021')
    {
      // 1. Cari KK Adat berdasarkan NPK dan eager load semua relasi yang dibutuhkan di view
      $kk_adat = KartuKeluargaAdat::with([
        'banjar',
        'klasifikasiKrama',
        'keteranganKeluarga.Penatahan'
      ])->findOrFail($npk);



      $keterangan_keluarga = KeteranganKeluarga::where ('npk_fk', $npk)->first();


      // 2. Dapatkan semua anggota dari KK Adat tersebut, dan eager load data individu mereka
      $anggota_list = AnggotaKkAdat::where('npk_fk', $npk)
        ->with('masterAdat.masterIndividu') // Ini akan mengambil NIKA dan data lengkap individu
        ->orderBy('id_keanggotaan') // Urutkan berdasarkan urutan masuk atau kriteria lain
        ->get();

      // 3. Temukan siapa kepala keluarga dari daftar anggota
      $kepala_keluarga = $anggota_list->firstWhere('status_hubungan_adat', 'kepala_keluarga');

      // Penanganan jika kepala keluarga tidak ditemukan
      if (!$kepala_keluarga) {
        abort(404, 'Data kepala keluarga tidak ditemukan untuk NPK ini.');
      }

      $bendesa_adat_aktif = BendesaAdat::where('status_jabatan', 'aktif')->first();

  //      $anggota->masterAdat->masterIndividu->nama_lengkap

      $kode_krama_fk = $kk_adat->kode_klasifikasi_krama_fk;
      $call_krama = KlasifikasiKrama::query()->where('kode_krama', $kode_krama_fk)->first()->krama ?? null;

  //        dd($call_krama);

      // 4. Kirim data yang sudah terstruktur rapi ke view

      $adderTamiuorKrama = AddForTamiu::query()->where('master_individu_fk', '=', $kepala_keluarga->masterAdat->masterIndividu->id)->first();


//      dd($kepala_keluarga->masterAdat->masterIndividu->id , $adderTamiuorKrama);

      return view('pages.kependudukan.basis_kka.print.family_card_tamiu', [ // Sesuaikan path view Anda
        'bendesa_adat_aktif' => $bendesa_adat_aktif,
        'kk_adat'         => $kk_adat,
        'anggota_list'    => $anggota_list,
        'kepala_keluarga' => $kepala_keluarga,
        'keterangan_keluarga' => $keterangan_keluarga,
        'berlaku'         => 5, // Contoh data tambahan
        'call_krama_fk' => $call_krama,
        'adder' => $adderTamiuorKrama,
      ]);


    }
    public function printCardFamily(Request $request, $npk)
    {
//      dd($request->all(), $npk);
      $krama_request = $request->get('krama_request');

        // 1. Cari KK Adat berdasarkan NPK dan eager load semua relasi yang dibutuhkan di view
        $kk_adat = KartuKeluargaAdat::with([
            'banjar',
            'klasifikasiKrama',
            'keteranganKeluarga.Penatahan'
        ])->findOrFail($npk);

        $keterangan_keluarga = KeteranganKeluarga::where ('npk_fk', $npk)->first();


        // 2. Dapatkan semua anggota dari KK Adat tersebut, dan eager load data individu mereka
        $anggota_list = AnggotaKkAdat::where('npk_fk', $npk)
            ->with('masterAdat.masterIndividu') // Ini akan mengambil NIKA dan data lengkap individu
            ->orderBy('id_keanggotaan') // Urutkan berdasarkan urutan masuk atau kriteria lain
            ->get();

        // 3. Temukan siapa kepala keluarga dari daftar anggota
        $kepala_keluarga = $anggota_list->firstWhere('status_hubungan_adat', 'kepala_keluarga');

        // Penanganan jika kepala keluarga tidak ditemukan
        if (!$kepala_keluarga) {
            abort(404, 'Data kepala keluarga tidak ditemukan untuk NPK ini.');
        }

        $bendesa_adat_aktif = BendesaAdat::where('status_jabatan', 'aktif')->first();

//      $anggota->masterAdat->masterIndividu->nama_lengkap

        $kode_krama_fk = $kk_adat->kode_klasifikasi_krama_fk;
        $call_krama = KlasifikasiKrama::query()->where('kode_krama', $kode_krama_fk)->first()->krama ?? null;

//        dd($call_krama);

        // 4. Kirim data yang sudah terstruktur rapi ke view

        $adderKramaTamiu = AddForKramaTamiu::query()->where('master_individu_fk', '=', $kepala_keluarga->masterAdat->masterIndividu->id)->first();
        $adderTamiu = AddForTamiu::query()->where('master_individu_fk', '=', $kepala_keluarga->masterAdat->masterIndividu->id)->first();

        if ($krama_request == 'krama_adat'){
          return view('pages.kependudukan.basis_kka.print.family_card', [ // Sesuaikan path view Anda
            'bendesa_adat_aktif' => $bendesa_adat_aktif,
            'kk_adat'         => $kk_adat,
            'anggota_list'    => $anggota_list,
            'kepala_keluarga' => $kepala_keluarga,
            'keterangan_keluarga' => $keterangan_keluarga,
            'berlaku'         => 5, // Contoh data tambahan
            'call_krama_fk' => $call_krama,
          ]);
        } else if ($krama_request == 'krama_tamiu'){
          return view('pages.kependudukan.basis_kka.print.family_card_krama_tamiu', [ // Sesuaikan path view Anda
            'bendesa_adat_aktif' => $bendesa_adat_aktif,
            'kk_adat'         => $kk_adat,
            'anggota_list'    => $anggota_list,
            'kepala_keluarga' => $kepala_keluarga,
            'keterangan_keluarga' => $keterangan_keluarga,
            'berlaku'         => 5, // Contoh data tambahan
            'call_krama_fk' => $call_krama,
            'adder' => $adderKramaTamiu,
          ]);

        } else if ($krama_request == 'tamiu'){
          return view('pages.kependudukan.basis_kka.print.family_card_tamiu', [ // Sesuaikan path view Anda
            'bendesa_adat_aktif' => $bendesa_adat_aktif,
            'kk_adat'         => $kk_adat,
            'anggota_list'    => $anggota_list,
            'kepala_keluarga' => $kepala_keluarga,
            'keterangan_keluarga' => $keterangan_keluarga,
            'berlaku'         => 5, // Contoh data tambahan
            'call_krama_fk' => $call_krama,
            'adder' => $adderTamiu,
          ]);
        }



    }

    /**
     * Menampilkan form untuk mengedit data KK Adat dan anggotanya.
     */
    public function edit(Request $request, $npk)
    {
        $krama_request = $request->input('krama_request');
        
        // Eager load relasi yang dibutuhkan di seluruh halaman
        $kk_adat = KartuKeluargaAdat::with(['banjar', 'keteranganKeluarga.penatahan'])->findOrFail($npk);

        $anggota_list = AnggotaKkAdat::where('npk_fk', $npk)
            ->with('masterAdat.masterIndividu')
            ->orderBy('id_keanggotaan')
            ->get();

        // Ambil ID anggota yang ingin diedit dari URL (?edit_anggota=...)
        $anggota_to_edit_id = $request->query('edit_anggota');
        $anggota_to_edit = $anggota_list->firstWhere('id_keanggotaan', $anggota_to_edit_id);

        // --- PERBAIKAN UTAMA DI SINI ---
        // Ambil data dari model Dadia yang benar dan muat relasi penatahan-nya
        $all_dadia = Dadia::with('penatahan')->orderBy('nama_dadia')->get();
        
        // Ambil juga data krama untuk dropdown kelihan natah
        $all_krama_adat = MasterAdat::with('masterIndividu', 'banjar')->limit(100)->get();

        return view('pages.kependudukan.basis_kka.edit', [
            'title' => 'Edit Data KK: ' . $kk_adat->npk,
            'kk_adat' => $kk_adat,
            'anggota_list' => $anggota_list,
            'anggota_to_edit' => $anggota_to_edit,
            'all_dadia' => $all_dadia, // Mengirim data yang benar
            'all_krama_adat' => $all_krama_adat, // Mengirim data krama
            'krama_request' => $krama_request,
        ]);
    }

      /**
       * Memproses pembaruan data dari form edit.
       */
      public function update(Request $request, $npk)
      {
        $action = $request->input('action');
        $connectionName = 'db_kependudukan'; // Tentukan nama koneksi di sini untuk kemudahan

        if ($action === 'change_kepala_keluarga') {

          $request->validate([
            'new_kepala_keluarga_id' => ['required', "exists:{$connectionName}.anggota_kk_adat,id_keanggotaan"]
          ]);

          DB::connection($connectionName)->transaction(function () use ($request, $npk) {
            $new_kk_id = $request->input('new_kepala_keluarga_id');

            $old_kk = AnggotaKkAdat::where('npk_fk', $npk)->where('status_hubungan_adat', 'kepala_keluarga')->first();
            $new_kk = AnggotaKkAdat::find($new_kk_id);

            if ($old_kk) {
              $old_kk->status_hubungan_adat = 'famili_lain';
              $old_kk->save();
            }

            $new_kk->status_hubungan_adat = 'kepala_keluarga';
            $new_kk->save();
          });

          return back()->with('success', 'Kepala Keluarga berhasil diubah.');

        } elseif ($action === 'update_anggota') {

          $request->validate([
            'id_keanggotaan' => ['required', "exists:{$connectionName}.anggota_kk_adat,id_keanggotaan"],
            'id_individu' => ['required', "exists:{$connectionName}.master_individu,id"],
            'id_identitas_adat' => ['required', "exists:{$connectionName}.master_adat,id_identitas_adat"],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            // PERBAIKAN: Menggabungkan nama koneksi dan tabel di dalam Rule::unique()
            'nik_nasional' => ['required', 'string', 'max:25', Rule::unique($connectionName . '.master_individu')->ignore($request->input('id_individu'))],
            'nika' => ['required', 'string', 'max:255', Rule::unique($connectionName . '.master_adat')->ignore($request->input('id_identitas_adat'), 'id_identitas_adat')],
            'status_di_banjar' => ['required', 'string'],
          ]);

          DB::connection($connectionName)->transaction(function () use ($request) {
            // Update data di tabel master_individu
            $individu = MasterIndividu::find($request->input('id_individu'));
            $individu->update($request->only([
              'nama_lengkap', 'nik_nasional', 'tempat_lahir', 'tanggal_lahir',
              'jenis_kelamin', 'agama', 'pendidikan', 'pekerjaan',
              'status_perkawinan', 'tanggal_catat_kawin', 'kewarganegaraan',
              'status_hubungan', 'golongan_darah', 'nama_ayah', 'nama_ibu', 'alamat'
            ]));

            // Update data di tabel master_adat
            $master_adat = MasterAdat::find($request->input('id_identitas_adat'));
            $master_adat->update($request->only(['nika', 'status_di_banjar']));
          });

          return redirect()->route('kependudukan.edit', ['npk' => $npk])->with('success', 'Data anggota berhasil diperbarui.');

        } elseif ($action === 'update_keterangan_keluarga') {

          $request->validate([
            'id_dadia_penatahan_fk' => ['nullable', "exists:{$connectionName}.dadia_penatahan,id"],
            'keterangan_tambahan' => ['nullable', 'string'],
          ]);

          KeteranganKeluarga::updateOrCreate(
            ['npk_fk' => $npk],
            [
              'id_dadia_penatahan_fk' => $request->input('id_dadia_penatahan_fk'),
              'keterangan_tambahan' => $request->input('keterangan_tambahan'),
            ]
          );

          return back()->with('success', 'Keterangan keluarga berhasil diperbarui.');
        }

        return back()->with('error', 'Aksi tidak valid.');
      }


    /**
     * Menampilkan form untuk membuat KK Adat baru.
     */
//    public function create(Request $request)
//    {
//      // Ambil data master untuk dropdown
//      $all_banjar = Banjar::orderBy('nama_banjar')->get();
//      $all_krama = KlasifikasiKrama::orderBy('krama')->get();
////      $all_dadia = DadiaPenatahan::orderBy('nama_dadia')->get();
//
//      // Tentukan jumlah anggota berdasarkan parameter URL, default 1, maks 15.
//      $jumlah_anggota = (int) $request->query('anggota', 1);
//      if ($jumlah_anggota < 1) {
//        $jumlah_anggota = 1;
//      }
//      if ($jumlah_anggota > 15) {
//        $jumlah_anggota = 15;
//      }
//
//      $all_dadia = Dadia::with('penatahan')->orderBy('nama_dadia')->get();
//
//      return view('pages.kependudukan.input_data.manual_input', [
//        'title' => 'Tambah Kartu Keluarga Adat Baru',
//        'all_banjar' => $all_banjar,
//        'all_krama' => $all_krama,
//        'all_dadia' => $all_dadia,
//        'jumlah_anggota' => $jumlah_anggota,
//      ]);
//    }

    /**
     * Menyimpan data KK Adat baru ke database.
     */
//  public function store(Request $request)
//  {
//
//    $connectionName = 'db_kependudukan';
//
//
//    $npk_final = ''; // Inisialisasi variabel
//      DB::connection($connectionName)->transaction(function () use ($request, &$npk_final) {
//
//        $selectedDadia = $request -> input('dadia_mode');
//        $selectedPenatahan =  $request -> input('penatahan_mode');
//
//        // Jika pengguna memilih untuk mengisi sendiri
//        if ($selectedDadia === 'custom') {
//          // Buat atau temukan Dadia baru
//          $dadia = Dadia::firstOrCreate(
//            ['nama_dadia' => $request->input('custom_dadia_nama')]
//          );
//          // Buat Penatahan baru di bawah Dadia tersebut
//          $penatahan = Penatahan::create([
//            'id_dadia_fk' => $dadia->id,
//            'nama_penatahan' => $request->input('custom_penatahan_nama'),
//            'kelihan_natah' => $request->input('custom_kelihan_natah'),
//          ]);
//          $id_penatahan_final = $penatahan->id;
//        }
//        // Jika pengguna memilih dari dropdown
//        else {
//          // Jika mereka juga memilih Penatahan dari dropdown
//          if ($selectedPenatahan === 'select') {
//            $id_penatahan_final = $request->input('selected_penatahan_id');
//          }
//          // Jika mereka memilih Dadia, tapi mengisi Penatahan sendiri
//          elseif ($selectedPenatahan === 'custom') {
//            $penatahan = Penatahan::create([
//              'id_dadia_fk' => $request->input('selected_dadia_id'),
//              'nama_penatahan' => $request->input('custom_penatahan_nama'),
//              'kelihan_natah' => $request->input('custom_kelihan_natah'),
//            ]);
//            $id_penatahan_final = $penatahan->id;
//          }
//        }
//
////        dd($id_penatahan_final);
//
//        $LastNumNPK =  NpkCounter::query()->where('kode_banjar_fk', '=', $request->input('kode_banjar_fk'))->first()->nomor_terakhir;
//        NpkCounter::query()->where('kode_banjar_fk', '=', $request->input('kode_banjar_fk'))->update(['nomor_terakhir' => $LastNumNPK+1]);
//
////    0419040406 B P A == 13 0001 03
//        $extraxBanjar = preg_replace('/[^0-9]/', '', $request->input('kode_banjar_fk')) ;
//
//        $LastNumPIPILnPaddingDigit = str_pad($LastNumNPK, 4, '0', STR_PAD_LEFT);
//
//        $fullNPK = "04190406".$extraxBanjar.$LastNumPIPILnPaddingDigit;
//        $counterNika = 1;
//        { //KK ADAT
//          $KkAdat = new KartuKeluargaAdat();
//          $KkAdat -> npk = $fullNPK;
//          $KkAdat -> nkk = $request->input('nkk');
//          $KkAdat -> kode_klasifikasi_krama_fk = $request->input('kode_klasifikasi_krama_fk');
//          $KkAdat -> kode_banjar_fk = $request->input("kode_banjar_fk");
//          $KkAdat -> no_telp = $request->input("no_telp");
//          $KkAdat -> status_adat = $request->input("status_adat");
//          $KkAdat -> alamat = $request->input("alamat");
//          $KkAdat -> save();
//          { //Keterangan KK
//            $KeterenganKK = new KeteranganKeluarga();
//            $KeterenganKK -> npk_fk = $KkAdat->npk;
//            $KeterenganKK -> id_dadia_penatahan_fk = $id_penatahan_final;
//            $KeterenganKK -> keterangan_tambahan = $request->input('keterangan_tambahan');
//            $KeterenganKK -> save();
//          }
//        }
////
//        { // KK MASTER INDIVIDU
//
//          $individuKK = new MasterIndividu();
//          $individuKK->nik_nasional = $request->input('kk.nik_nasional');
//          $individuKK->nama_lengkap = $request->input('kk.nama_lengkap');
//          $individuKK->tempat_lahir = $request->input('kk.tempat_lahir');
//          $individuKK->tanggal_lahir = $request->input('kk.tanggal_lahir');
//          $individuKK->jenis_kelamin = $request->input('kk.jenis_kelamin');
//          $individuKK->agama = $request->input('kk.agama');
//          $individuKK->pendidikan = $request->input('kk.pendidikan');
//          $individuKK->pekerjaan = $request->input('kk.pekerjaan');
//          $individuKK->status_perkawinan = $request->input('kk.status_perkawinan');
//          $individuKK->tanggal_catat_kawin = $request->input('kk.tanggal_catat_kawin');
//          $individuKK->kewarganegaraan = $request->input('kk.kewarganegaraan');
//          $individuKK->status_hubungan = $request->input('kk.status_hubungan') ?? "kepala_keluarga";
//          $individuKK->golongan_darah = $request->input('kk.golongan_darah');
//          $individuKK->nama_ayah = $request->input('kk.nama_ayah');
//          $individuKK->nama_ibu = $request->input('kk.nama_ibu');
//          $individuKK->alamat = $request->input('kk.alamat');
//          $individuKK->save();
//
//          { // MASTER ADAT
//            $individuAdatKK = new MasterAdat();
//            $individuAdatKK->id_individu_fk = $individuKK->id;
//            $individuAdatKK->kode_banjar_fk = $request->input('kode_banjar_fk');
//            $individuAdatKK->nika = $KkAdat->npk . str_pad($counterNika, 2, '0', STR_PAD_LEFT); //TEST
//            $individuAdatKK->tanggal_catat_nika = null;
//            $individuAdatKK->status_di_banjar = $request->input('kk.status_di_banjar');
//            $individuAdatKK->save();
//
//            { // ANGGOTA ADAT
//
//              $anggotaAdatKK = new AnggotaKkAdat();
//              $anggotaAdatKK->id_identitas_adat_fk = $individuAdatKK -> id_identitas_adat;
//              $anggotaAdatKK->npk_fk = $KkAdat->npk;
//              $anggotaAdatKK->status_hubungan_adat = $individuKK->status_hubungan;
//              $anggotaAdatKK->status_keanggotaan = "aktif";
//              $anggotaAdatKK->save();
//
//
//            }
//          }
//
//
//        }
//        foreach ($request->input('anggota') as $anggota) {
//          if($anggota['nik_nasional'] != null && $anggota['nama_lengkap'] != null){
//            $counterNika++;
//            { //Anggota Master Individu
//              $individuAnggota = new MasterIndividu();
//              $individuAnggota->nik_nasional = $anggota['nik_nasional'];
//              $individuAnggota->nama_lengkap = $anggota['nama_lengkap'];
//              $individuAnggota->tempat_lahir = $anggota['tempat_lahir'];
//              $individuAnggota->tanggal_lahir = $anggota['tanggal_lahir'];
//              $individuAnggota->jenis_kelamin = $anggota['jenis_kelamin'];
//              $individuAnggota->agama = $anggota['agama'];
//              $individuAnggota->pendidikan = $anggota['pendidikan'];
//              $individuAnggota->pekerjaan = $anggota['pekerjaan'];
//              $individuAnggota->status_perkawinan = $anggota['status_perkawinan'];
//              $individuAnggota->tanggal_catat_kawin = $anggota['tanggal_catat_kawin'];
//              $individuAnggota->kewarganegaraan = $anggota['kewarganegaraan'];
//              $individuAnggota->status_hubungan = $anggota['status_hubungan_adat'];
//              $individuAnggota->golongan_darah = $anggota['golongan_darah'];
//              $individuAnggota->nama_ayah = $anggota['nama_ayah'];
//              $individuAnggota->nama_ibu = $anggota['nama_ibu'];
//              $individuAnggota->alamat = $anggota['alamat'];
//              $individuAnggota->save();
//              { // MASTER ADAT
//                $individuAdatAnggota = new MasterAdat();
//                $individuAdatAnggota->id_individu_fk = $individuAnggota->id;
//                $individuAdatAnggota->kode_banjar_fk = $request->input('kode_banjar_fk');
//                $individuAdatAnggota->nika = $KkAdat->npk . str_pad($counterNika, 2, '0', STR_PAD_LEFT); //TEST
//                $individuAdatAnggota->tanggal_catat_nika = null;
//                $individuAdatAnggota->status_di_banjar = $request->input('kk.status_di_banjar');
//                $individuAdatAnggota->save();
//
//                { // ANGGOTA ADAT
//
//                  $anggotaAdatAnggota = new AnggotaKkAdat();
//                  $anggotaAdatAnggota->id_identitas_adat_fk = $individuAdatAnggota -> id_identitas_adat;
//                  $anggotaAdatAnggota->npk_fk = $KkAdat->npk;
//                  $anggotaAdatAnggota->status_hubungan_adat = $anggota['status_hubungan_adat'];
//                  $anggotaAdatAnggota->status_keanggotaan = "aktif";
//                  $anggotaAdatAnggota->save();
//                }
//              }
//            }
//          }
//
//        }
//
////            dd($individuKK);
//      });
////    } catch (\Exception $e) {
////      return back()->withInput()->with('error', 'Gagal menyimpan data. Error: ' . $e->getMessage());
////    }
//
//    return redirect()->route('kependudukan.create', ['banjar' => $request->input('kode_banjar_fk')])
//      ->with('success', 'KK Adat baru berhasil dibuat dengan NPK: ' . $npk_final);
//  }

//  public function create(Request $request)
//  {
//    // Ambil data master untuk dropdown
//    $all_banjar = Banjar::orderBy('nama_banjar')->get();
//    $all_krama = KlasifikasiKrama::orderBy('krama')->get();
//    $all_dadia = Dadia::with('penatahan')->orderBy('nama_dadia')->get();
//
//    // Logika Pencarian Kelihan Natah
//    $kelihanSearchTerm = $request->input('kelihan_search');
//    $all_krama_adat_query = MasterAdat::with('masterIndividu.masterAdat.banjar');
//
//    if ($kelihanSearchTerm) {
//      $all_krama_adat_query->whereHas('masterIndividu', function ($query) use ($kelihanSearchTerm) {
//        $query->where('nama_lengkap', 'like', '%' . $kelihanSearchTerm . '%')
//          ->orWhere('nik_nasional', 'like', '%' . $kelihanSearchTerm . '%');
//      })->orWhere('nika', 'like', '%' . $kelihanSearchTerm . '%');
//    }
//
//    // Batasi hasil pencarian agar tidak terlalu berat, atau jangan tampilkan sama sekali jika tidak ada pencarian
//    $all_krama_adat = $kelihanSearchTerm ? $all_krama_adat_query->limit(100)->get() : collect([]);
//
//    // Tentukan jumlah anggota berdasarkan parameter URL
//    $jumlah_anggota = (int) $request->query('anggota', 0);
//    if ($jumlah_anggota < 0) $jumlah_anggota = 0;
//    if ($jumlah_anggota > 15) $jumlah_anggota = 15;
//
//    return view('pages.kependudukan.input_data.manual_input', [
//      'title' => 'Tambah Kartu Keluarga Adat Baru',
//      'all_banjar' => $all_banjar,
//      'all_krama' => $all_krama,
//      'all_dadia' => $all_dadia,
//      'all_krama_adat' => $all_krama_adat,
//      'kelihanSearchTerm' => $kelihanSearchTerm,
//      'jumlah_anggota' => $jumlah_anggota,
//    ]);
//  }
//
//  /**
//   * Menyimpan data KK Adat baru ke database.
//   */
//  public function store(Request $request)
//  {
//    $connectionName = 'db_kependudukan';
//
//    // Validasi Data Lengkap
//    $request->validate([
//      'nkk' => ['nullable', 'string', 'max:255'],
//      'kode_banjar_fk' => ['required', "exists:{$connectionName}.banjar,kode_banjar"],
//      'kode_klasifikasi_krama_fk' => ['required', "exists:{$connectionName}.klasifikasi_krama,kode_krama"],
//      'dadia_id' => 'required',
//      'custom_dadia_nama' => 'required_if:dadia_id,custom|nullable|string|max:255',
//      'custom_penatahan_nama' => 'required_if:penatahan_id,custom|required_if:dadia_id,custom|nullable|string|max:255',
//      'kk.nama_lengkap' => 'required|string|max:100',
//      'kk.nik_nasional' => ['required', 'string', 'max:25', Rule::unique($connectionName . '.master_individu', 'nik_nasional')],
//      'anggota.*.nama_lengkap' => 'required_with:anggota.*.nik_nasional|nullable|string|max:100',
//      'anggota.*.nik_nasional' => ['nullable', 'string', 'max:25', Rule::unique($connectionName . '.master_individu', 'nik_nasional')],
//    ], [
//      'kk.nama_lengkap.required' => 'Nama Kepala Keluarga wajib diisi.',
//      'kk.nik_nasional.required' => 'NIK Kepala Keluarga wajib diisi.',
//      'custom_dadia_nama.required_if' => 'Nama Dadia Baru wajib diisi jika Anda memilih opsi "Isi Dadia Baru".',
//      'custom_penatahan_nama.required_if' => 'Nama Penatahan Baru wajib diisi jika Anda memilih opsi "Isi Penatahan Baru".',
//    ]);
//
//    $npk_final = '';
//
//    try {
//      $kode_banjar = $request->input('kode_banjar_fk');
//
//      DB::connection($connectionName)->transaction(function () use ($request, &$npk_final, $kode_banjar) {
//
//        $penatahan_id_final = null;
//        if ($request->input('dadia_id') === 'custom') {
//          $dadia = Dadia::firstOrCreate(['nama_dadia' => $request->input('custom_dadia_nama')]);
//          $penatahan = Penatahan::create([
//            'id_dadia_fk' => $dadia->id,
//            'nama_penatahan' => $request->input('custom_penatahan_nama'),
//            'id_kelihan_adat_fk' => $request->input('custom_kelihan_natah_id'),
//          ]);
//          $penatahan_id_final = $penatahan->id;
//        } elseif ($request->input('penatahan_id') === 'custom') {
//          $penatahan = Penatahan::create([
//            'id_dadia_fk' => $request->input('dadia_id'),
//            'nama_penatahan' => $request->input('custom_penatahan_nama'),
//            'id_kelihan_adat_fk' => $request->input('custom_kelihan_natah_id'),
//          ]);
//          $penatahan_id_final = $penatahan->id;
//        } else {
//          $penatahan_id_final = $request->input('penatahan_id');
//        }
//
//        $counter = NpkCounter::firstOrCreate(['kode_banjar_fk' => $kode_banjar]);
//        $counter->increment('nomor_terakhir');
//        $nomor_urut_kk = str_pad($counter->nomor_terakhir, 4, '0', STR_PAD_LEFT);
//        $kode_banjar_numerik = str_pad(preg_replace('/[^0-9]/', '', $kode_banjar), 2, '0', STR_PAD_LEFT);
//        $npk_final = '04190406' . $kode_banjar_numerik . $nomor_urut_kk;
//        $nika_base = $npk_final;
//
//        $kk_adat = new KartuKeluargaAdat();
//        $kk_adat->npk = $npk_final;
//        $kk_adat->fill($request->only(['nkk', 'kode_klasifikasi_krama_fk', 'kode_banjar_fk', 'no_telp', 'status_adat', 'alamat']));
//        $kk_adat->save();
//
//        if ($penatahan_id_final) {
//          KeteranganKeluarga::create([
//            'npk_fk' => $kk_adat->npk,
//            'id_penatahan_fk' => $penatahan_id_final,
//            'keterangan_tambahan' => $request->input('keterangan_tambahan'),
//          ]);
//        }
//
//        $anggota_counter = 1;
//
//        $createOrUpdateAnggota = function ($data, $isKepalaKeluarga = false) use ($kk_adat, $nika_base, &$anggota_counter) {
//          if (empty($data['nik_nasional'])) return;
//
//          $individuData = collect($data)->except(['status_hubungan_adat', 'status_di_banjar'])->all();
//
//          $individu = MasterIndividu::updateOrCreate(
//            ['nik_nasional' => $data['nik_nasional']],
//            $individuData
//          );
//
//          $nomor_anggota = str_pad($anggota_counter, 2, '0', STR_PAD_LEFT);
//          $nika_otomatis = $nika_base . $nomor_anggota;
//
//          $master_adat = MasterAdat::updateOrCreate(
//            ['id_individu_fk' => $individu->id, 'kode_banjar_fk' => $kk_adat->kode_banjar_fk],
//            ['nika' => $nika_otomatis, 'status_di_banjar' => $data['status_di_banjar'] ?? 'aktif', 'tanggal_catat_nika' => now()]
//          );
//          $anggota_counter++;
//
//          AnggotaKkAdat::create([
//            'id_identitas_adat_fk' => $master_adat->id_identitas_adat,
//            'npk_fk' => $kk_adat->npk,
//            'status_hubungan_adat' => $isKepalaKeluarga ? 'kepala_keluarga' : ($data['status_hubungan_adat'] ?? 'anak'),
//            'status_keanggotaan' => 'aktif',
//            'tanggal_bergabung_kk' => now(),
//          ]);
//        };
//
//        $createOrUpdateAnggota($request->input('kk'), true);
//
//        if ($request->has('anggota')) {
//          foreach ($request->input('anggota') as $dataAnggota) {
//            if (!empty($dataAnggota['nama_lengkap']) && !empty($dataAnggota['nik_nasional'])) {
//              $createOrUpdateAnggota($dataAnggota, false);
//            }
//          }
//        }
//      });
//    } catch (\Exception $e) {
//      return back()->withInput()->with('error', 'Gagal menyimpan data. Error: ' . $e->getMessage());
//    }
//
//    return redirect()->route('kependudukan.index', ['banjar' => $request->input('kode_banjar_fk')])
//      ->with('success', 'KK Adat baru berhasil dibuat dengan NPK: ' . $npk_final);
//  }

  public function createOptions()
  {
    return view('pages.kependudukan.input_data.input', [
      'title' => 'Pilih Jenis Input Data'
    ]);
  }



  public function create(Request $request)
  {
//    dd($request, $request->all());
    $krama_input_request = $request->data_input;
    // Ambil data master untuk dropdown
    $all_banjar = Banjar::orderBy('nama_banjar')->get();
    $all_krama = KlasifikasiKrama::orderBy('krama')->get();
    $all_dadia = Dadia::with('penatahan')->orderBy('nama_dadia')->get();

    // Logika Pencarian Kelihan Natah
    $kelihanSearchTerm = $request->input('kelihan_search');
    $all_krama_adat_query = MasterAdat::with('masterIndividu.masterAdat.banjar');

    if ($kelihanSearchTerm) {
      $all_krama_adat_query->whereHas('masterIndividu', function ($query) use ($kelihanSearchTerm) {
        $query->where('nama_lengkap', 'like', '%' . $kelihanSearchTerm . '%')
          ->orWhere('nik_nasional', 'like', '%' . $kelihanSearchTerm . '%');
      })->orWhere('nika', 'like', '%' . $kelihanSearchTerm . '%');
    }

    // Batasi hasil pencarian agar tidak terlalu berat, atau jangan tampilkan sama sekali jika tidak ada pencarian
    $all_krama_adat = $kelihanSearchTerm ? $all_krama_adat_query->limit(100)->get() : collect([]);

    // Tentukan jumlah anggota berdasarkan parameter URL
    $jumlah_anggota = (int) $request->query('anggota', 0);
    if ($jumlah_anggota < 0) $jumlah_anggota = 0;
    if ($jumlah_anggota > 15) $jumlah_anggota = 15;

    if ($krama_input_request == 'krama_tamiu') {
      return view('pages.kependudukan.input_data.manual_input_krama_tamiu', [
        'title' => 'Tambah Kartu Keluarga Adat Baru',
        'all_banjar' => $all_banjar,
        'all_krama' => $all_krama,
        'all_dadia' => $all_dadia,
        'all_krama_adat' => $all_krama_adat,
        'kelihanSearchTerm' => $kelihanSearchTerm,
        'jumlah_anggota' => $jumlah_anggota,
        'krama_input_request' => $krama_input_request,
      ]);
    } else if ($krama_input_request == 'tamiu') {
      return view('pages.kependudukan.input_data.manual_input_tamiu', [
        'title' => 'Tambah Kartu Keluarga Adat Baru',
        'all_banjar' => $all_banjar,
        'all_krama' => $all_krama,
        'all_dadia' => $all_dadia,
        'all_krama_adat' => $all_krama_adat,
        'kelihanSearchTerm' => $kelihanSearchTerm,
        'jumlah_anggota' => $jumlah_anggota,
        'krama_input_request' => $krama_input_request,
      ]);
    } else {
      return view('pages.kependudukan.input_data.manual_input', [
        'title' => 'Tambah Kartu Keluarga Adat Baru',
        'all_banjar' => $all_banjar,
        'all_krama' => $all_krama,
        'all_dadia' => $all_dadia,
        'all_krama_adat' => $all_krama_adat,
        'kelihanSearchTerm' => $kelihanSearchTerm,
        'jumlah_anggota' => $jumlah_anggota,
        'krama_input_request' => $krama_input_request,
      ]);
    }

  }

  /**
   * Menyimpan data KK Adat baru ke database.
   */
  public function store(Request $request)
  {
//    @dd($request->all());



//    $selectedDadia = $request->input('dadia_id');
//    $selectedPenatahan = $request->input('penatahan_id');
//    $final_d_p = null;
//
//    if ($selectedDadia === 'custom') {
//      $final_d_p = "Dadia Custom -> Penatahan Custom (Otomatis)";
//    }
//    // Jika pengguna memilih dari dropdown
//    else {
//      // Jika mereka juga memilih Penatahan dari dropdown
//      if ($selectedPenatahan !== 'custom' && $selectedDadia !== null && $selectedDadia !== 'custom' && $selectedPenatahan !== null) {
//        $final_d_p = "Dadia Pilih & Penatahan Pilih";
//      }
//      // Jika mereka memilih Dadia, tapi mengisi Penatahan sendiri
//      elseif ($selectedPenatahan === 'custom' && $selectedDadia !== null && $selectedDadia !== 'custom' && $selectedPenatahan !== null) {
//        $final_d_p = "Dadia Pilih tapi -> Penatahan Custom";
//      }
//
//      else {
//        $final_d_p = "Tolong Pilih Dadia dan Penatahan Dengan Benar";
//      }
//    }
//
//    dd($request->all());
//
    $connectionName = 'db_kependudukan';

    // Validasi Data Lengkap
//    $request->validate([
//      'nkk' => ['nullable', 'string', 'max:255'],
//      'kode_banjar_fk' => ['required', "exists:{$connectionName}.banjar,kode_banjar"],
//      'kode_klasifikasi_krama_fk' => ['required', "exists:{$connectionName}.klasifikasi_krama,kode_krama"],
//      'dadia_id' => 'required',
//      'custom_dadia_nama' => 'required_if:dadia_id,custom|nullable|string|max:255',
//      'custom_penatahan_nama' => 'required_if:penatahan_id,custom|required_if:dadia_id,custom|nullable|string|max:255',
//      'kk.nama_lengkap' => 'required|string|max:100',
//      'kk.nik_nasional' => ['required', 'string', 'max:25', Rule::unique($connectionName . '.master_individu', 'nik_nasional')],
//      'anggota.*.nama_lengkap' => 'required_with:anggota.*.nik_nasional|nullable|string|max:100',
//      'anggota.*.nik_nasional' => ['nullable', 'string', 'max:25', Rule::unique($connectionName . '.master_individu', 'nik_nasional')],
//    ], [
//      'kk.nama_lengkap.required' => 'Nama Kepala Keluarga wajib diisi.',
//      'kk.nik_nasional.required' => 'NIK Kepala Keluarga wajib diisi.',
//      'custom_dadia_nama.required_if' => 'Nama Dadia Baru wajib diisi jika Anda memilih opsi "Isi Dadia Baru".',
//      'custom_penatahan_nama.required_if' => 'Nama Penatahan Baru wajib diisi jika Anda memilih opsi "Isi Penatahan Baru".',
//    ]);

    $npk_final = '';

//    try {
      $kode_banjar = $request->input('kode_banjar_fk');

      DB::connection($connectionName)->transaction(function () use ($request, &$npk_final, $kode_banjar) {

        $selectedDadia = $request -> input('dadia_id');
        $selectedPenatahan =  $request -> input('penatahan_id');
        $id_penatahan_final = null;

        // Jika pengguna memilih untuk mengisi sendiri
        if ($selectedDadia === 'custom') {
          // Buat atau temukan Dadia baru
          $dadia = Dadia::firstOrCreate(
            ['nama_dadia' => $request->input('custom_dadia_nama')]
          );
          // Buat Penatahan baru di bawah Dadia tersebut
          $penatahan = Penatahan::create([
            'id_dadia_fk' => $dadia->id,
            'nama_penatahan' => $request->input('custom_penatahan_nama'),
            'kelihan_natah' => $request->input('custom_kelihan_natah'),
            'id_kelihan_adat_fk' => $request->input('custom_kelihan_natah_id'),
          ]);
          $id_penatahan_final = $penatahan->id;
        }
        // Jika pengguna memilih dari dropdown
        else {
          // Jika mereka juga memilih Penatahan dari dropdown
          if ($selectedPenatahan !== 'custom' && $selectedDadia !== null && $selectedDadia !== 'custom' && $selectedPenatahan !== null) {
            $id_penatahan_final = $request->input('penatahan_id');
          }
          // Jika mereka memilih Dadia, tapi mengisi Penatahan sendiri
          elseif ($selectedPenatahan === 'custom' && $selectedDadia !== null && $selectedDadia !== 'custom' && $selectedPenatahan !== null) {
            $penatahan = Penatahan::create([
              'id_dadia_fk' => $request->input('selected_dadia_id'),
              'nama_penatahan' => $request->input('custom_penatahan_nama'),
              'kelihan_natah' => $request->input('custom_kelihan_natah'),
            ]);
            $id_penatahan_final = $penatahan->id;
          }
        }

//        dd($request->all());


        $LastNumNPK =  NpkCounter::query()->where('kode_banjar_fk', '=', $request->input('kode_banjar_fk'))->first()->nomor_terakhir;
        NpkCounter::query()->where('kode_banjar_fk', '=', $request->input('kode_banjar_fk'))->update(['nomor_terakhir' => $LastNumNPK+1]);

//    0419040406 B P A == 13 0001 03
        $extraxBanjar = preg_replace('/[^0-9]/', '', $request->input('kode_banjar_fk')) ;

        $LastNumPIPILnPaddingDigit = str_pad($LastNumNPK, 4, '0', STR_PAD_LEFT);

        $fullNPK = "04190406".$extraxBanjar.$LastNumPIPILnPaddingDigit;
        $counterNika = 1;
        { //KK ADAT
          $KkAdat = new KartuKeluargaAdat();
          $KkAdat -> npk = $fullNPK;
          $KkAdat -> nkk = $request->input('nkk');
          $KkAdat -> kode_klasifikasi_krama_fk = $request->input('kode_klasifikasi_krama_fk');
          $KkAdat -> kode_banjar_fk = $request->input("kode_banjar_fk");
          $KkAdat -> no_telp = $request->input("no_telp");
          $KkAdat -> status_adat = $request->input("status_adat");
          $KkAdat -> alamat = $request->input("alamat");
          $KkAdat -> save();
          { //Keterangan KK
            $KeterenganKK = new KeteranganKeluarga();
            $KeterenganKK -> npk_fk = $KkAdat->npk;
            $KeterenganKK -> id_dadia_penatahan_fk = $id_penatahan_final;
            $KeterenganKK -> keterangan_tambahan = $request->input('keterangan_tambahan');
            $KeterenganKK -> save();
          }
        }
//
        { // KK MASTER INDIVIDU

          $queryKUnique = MasterIndividu::query()->where('nik_nasional', '=', $request->input('kk.nik_nasional'));
          $checkerUniqueIDKK = $queryKUnique->exists();
//          dd($checkerUniqueIDKK);
          if ($checkerUniqueIDKK) {
            $successKkUnique = $queryKUnique->first();
            $MasterAdatUnique = MasterAdat::query()->where('id_individu_fk', '=', $successKkUnique->id)->first();
//            dd($request->all(), $MasterAdatUnique, $KkAdat);

            $anggotaAdatKK = new AnggotaKkAdat();
            $anggotaAdatKK->id_identitas_adat_fk = $MasterAdatUnique->id_identitas_adat;
            $anggotaAdatKK->npk_fk = $KkAdat->npk;
            $anggotaAdatKK->status_hubungan_adat = $request->input('kk.status_hubungan');
            $anggotaAdatKK->status_keanggotaan = "aktif";
            $anggotaAdatKK->save();
          } else {
//            dd('kosong atau error atau belum di set');

            $individuKK = new MasterIndividu();
            $individuKK->nik_nasional = $request->input('kk.nik_nasional');
            $individuKK->nama_lengkap = $request->input('kk.nama_lengkap');
            $individuKK->tempat_lahir = $request->input('kk.tempat_lahir');
            $individuKK->tanggal_lahir = $request->input('kk.tanggal_lahir');
            $individuKK->jenis_kelamin = $request->input('kk.jenis_kelamin');
            $individuKK->agama = $request->input('kk.agama');
            $individuKK->pendidikan = $request->input('kk.pendidikan');
            $individuKK->pekerjaan = $request->input('kk.pekerjaan');
            $individuKK->status_perkawinan = $request->input('kk.status_perkawinan');
            $individuKK->tanggal_catat_kawin = $request->input('kk.tanggal_catat_kawin');
            $individuKK->kewarganegaraan = $request->input('kk.kewarganegaraan');
            $individuKK->status_hubungan = $request->input('kk.status_hubungan') ?? "kepala_keluarga";
            $individuKK->golongan_darah = $request->input('kk.golongan_darah');
            $individuKK->nama_ayah = $request->input('kk.nama_ayah');
            $individuKK->nama_ibu = $request->input('kk.nama_ibu');
            $individuKK->alamat = $request->input('kk.alamat');
            $individuKK->save();

            { // MASTER ADAT
              $individuAdatKK = new MasterAdat();
              $individuAdatKK->id_individu_fk = $individuKK->id;
              $individuAdatKK->kode_banjar_fk = $request->input('kode_banjar_fk');
              $individuAdatKK->nika = $KkAdat->npk . str_pad($counterNika, 2, '0', STR_PAD_LEFT); //TEST
              $individuAdatKK->tanggal_catat_nika = null;
              $individuAdatKK->status_di_banjar = $request->input('kk.status_di_banjar');
              $individuAdatKK->save();

              { // ANGGOTA ADAT

                $anggotaAdatKK = new AnggotaKkAdat();
                $anggotaAdatKK->id_identitas_adat_fk = $individuAdatKK -> id_identitas_adat;
                $anggotaAdatKK->npk_fk = $KkAdat->npk;
                $anggotaAdatKK->status_hubungan_adat = $individuKK->status_hubungan;
                $anggotaAdatKK->status_keanggotaan = "aktif";
                $anggotaAdatKK->save();

              }
            }

            { //Adder Tamiu or Krama Tamiu
              if( ! empty ($request->input('kk.krama_tamiu'))){
                $addForKramaTamiuKK = new AddForKramaTamiu();
                $addForKramaTamiuKK->master_individu_fk = $individuKK->id;
                $addForKramaTamiuKK->desa_adat = $request->input('kk.krama_tamiu.desa');
                $addForKramaTamiuKK->kecamatan = $request->input('kk.krama_tamiu.kecamatan');
                $addForKramaTamiuKK->kabupaten = $request->input('kk.krama_tamiu.kabupaten');
                $addForKramaTamiuKK->save();
//                dd($request->input('kk.krama_tamiu'),'from krama tamiu', $request->input('krama_input_request'));
              } else if (! empty ($request->input('kk.tamiu'))) {
                $addForTamiuKK = new AddForTamiu();
                $addForTamiuKK->master_individu_fk = $individuKK->id;
                $addForTamiuKK->desa = $request->input('kk.tamiu.desa');
                $addForTamiuKK->kecamatan = $request->input('kk.tamiu.kecamatan');
                $addForTamiuKK->kabupaten = $request->input('kk.tamiu.kabupaten');
                $addForTamiuKK->provinsi = $request->input('kk.tamiu.provinsi');
                $addForTamiuKK->save();
              }
            }
          }


        }

        if(!empty($request->input('anggota'))){
          foreach ($request->input('anggota') as $anggota) {
            $queryAnggotaUnique = MasterIndividu::query()->where('nik_nasional', '=', $anggota['nik_nasional']);
            $checkerUniqueIDAnggota = $queryAnggotaUnique->exists();

            if($checkerUniqueIDAnggota){
              $successAnggotaUnique = $queryAnggotaUnique->first();
              $MasterAdatUniqueAnggota = MasterAdat::query()->where('id_individu_fk', '=', $successAnggotaUnique->id)->first();
//            dd($request->all(), $MasterAdatUnique, $KkAdat);
              $anggotaAdatAnggota = new AnggotaKkAdat();
              $anggotaAdatAnggota->id_identitas_adat_fk = $MasterAdatUniqueAnggota -> id_identitas_adat;
              $anggotaAdatAnggota->npk_fk = $KkAdat->npk;
              $anggotaAdatAnggota->status_hubungan_adat = $anggota['status_hubungan_adat'];
              $anggotaAdatAnggota->status_keanggotaan = "aktif";
              $anggotaAdatAnggota->save();
            } else {
              if($anggota['nik_nasional'] != null && $anggota['nama_lengkap'] != null){
                $counterNika++;
                { //Anggota Master Individu
                  $individuAnggota = new MasterIndividu();
                  $individuAnggota->nik_nasional = $anggota['nik_nasional'];
                  $individuAnggota->nama_lengkap = $anggota['nama_lengkap'];
                  $individuAnggota->tempat_lahir = $anggota['tempat_lahir'];
                  $individuAnggota->tanggal_lahir = $anggota['tanggal_lahir'];
                  $individuAnggota->jenis_kelamin = $anggota['jenis_kelamin'];
                  $individuAnggota->agama = $anggota['agama'];
                  $individuAnggota->pendidikan = $anggota['pendidikan'];
                  $individuAnggota->pekerjaan = $anggota['pekerjaan'];
                  $individuAnggota->status_perkawinan = $anggota['status_perkawinan'];
                  $individuAnggota->tanggal_catat_kawin = $anggota['tanggal_catat_kawin'];
                  $individuAnggota->kewarganegaraan = $anggota['kewarganegaraan'];
                  $individuAnggota->status_hubungan = $anggota['status_hubungan_adat'];
                  $individuAnggota->golongan_darah = $anggota['golongan_darah'];
                  $individuAnggota->nama_ayah = $anggota['nama_ayah'];
                  $individuAnggota->nama_ibu = $anggota['nama_ibu'];
                  $individuAnggota->alamat = $anggota['alamat'];
                  $individuAnggota->save();
                  { // MASTER ADAT
                    $individuAdatAnggota = new MasterAdat();
                    $individuAdatAnggota->id_individu_fk = $individuAnggota->id;
                    $individuAdatAnggota->kode_banjar_fk = $request->input('kode_banjar_fk');
                    $individuAdatAnggota->nika = $KkAdat->npk . str_pad($counterNika, 2, '0', STR_PAD_LEFT); //TEST
                    $individuAdatAnggota->tanggal_catat_nika = null;
                    $individuAdatAnggota->status_di_banjar = $request->input('kk.status_di_banjar');
                    $individuAdatAnggota->save();

                    { // ANGGOTA ADAT

                      $anggotaAdatAnggota = new AnggotaKkAdat();
                      $anggotaAdatAnggota->id_identitas_adat_fk = $individuAdatAnggota -> id_identitas_adat;
                      $anggotaAdatAnggota->npk_fk = $KkAdat->npk;
                      $anggotaAdatAnggota->status_hubungan_adat = $anggota['status_hubungan_adat'];
                      $anggotaAdatAnggota->status_keanggotaan = "aktif";
                      $anggotaAdatAnggota->save();
                    }
                  }
                }
              }
            }
          }
        }

        ////////TETSTST
        ///
//        $counter = NpkCounter::firstOrCreate(['kode_banjar_fk' => $kode_banjar]);
//        $counter->increment('nomor_terakhir');
//        $nomor_urut_kk = str_pad($counter->nomor_terakhir, 4, '0', STR_PAD_LEFT);
//        $kode_banjar_numerik = str_pad(preg_replace('/[^0-9]/', '', $kode_banjar), 2, '0', STR_PAD_LEFT);
//        $npk_final = '04190406' . $kode_banjar_numerik . $nomor_urut_kk;
//        $nika_base = $npk_final;
//
//        $kk_adat = new KartuKeluargaAdat();
//        $kk_adat->npk = $npk_final;
//        $kk_adat->fill($request->only(['nkk', 'kode_klasifikasi_krama_fk', 'kode_banjar_fk', 'no_telp', 'status_adat', 'alamat']));
//        $kk_adat->save();
//
//        if ($penatahan_id_final) {
//          KeteranganKeluarga::create([
//            'npk_fk' => $kk_adat->npk,
//            'id_penatahan_fk' => $penatahan_id_final,
//            'keterangan_tambahan' => $request->input('keterangan_tambahan'),
//          ]);
//        }
//
//        $anggota_counter = 1;
//
//        $createOrUpdateAnggota = function ($data, $isKepalaKeluarga = false) use ($kk_adat, $nika_base, &$anggota_counter) {
//          if (empty($data['nik_nasional'])) return;
//
//          $individuData = collect($data)->except(['status_hubungan_adat', 'status_di_banjar'])->all();
//
//          $individu = MasterIndividu::updateOrCreate(
//            ['nik_nasional' => $data['nik_nasional']],
//            $individuData
//          );
//
//          $nomor_anggota = str_pad($anggota_counter, 2, '0', STR_PAD_LEFT);
//          $nika_otomatis = $nika_base . $nomor_anggota;
//
//          $master_adat = MasterAdat::updateOrCreate(
//            ['id_individu_fk' => $individu->id, 'kode_banjar_fk' => $kk_adat->kode_banjar_fk],
//            ['nika' => $nika_otomatis, 'status_di_banjar' => $data['status_di_banjar'] ?? 'aktif', 'tanggal_catat_nika' => now()]
//          );
//          $anggota_counter++;
//
//          AnggotaKkAdat::create([
//            'id_identitas_adat_fk' => $master_adat->id_identitas_adat,
//            'npk_fk' => $kk_adat->npk,
//            'status_hubungan_adat' => $isKepalaKeluarga ? 'kepala_keluarga' : ($data['status_hubungan_adat'] ?? 'anak'),
//            'status_keanggotaan' => 'aktif',
//            'tanggal_bergabung_kk' => now(),
//          ]);
//        };
//
//        $createOrUpdateAnggota($request->input('kk'), true);
//
//        if ($request->has('anggota')) {
//          foreach ($request->input('anggota') as $dataAnggota) {
//            if (!empty($dataAnggota['nama_lengkap']) && !empty($dataAnggota['nik_nasional'])) {
//              $createOrUpdateAnggota($dataAnggota, false);
//            }
//          }
//        }
      });
//    } catch (\Exception $e) {
//      @dd($request->all());
//      return back()->withInput()->with('error', 'Gagal menyimpan data. Error: ' . $e->getMessage());
//    }
//
    return redirect()->route('kependudukan.index', ['banjar' => $request->input('kode_banjar_fk')])
      ->with('success', 'KK Adat baru berhasil dibuat dengan NPK: ' . $npk_final);

    ///TETSTST
  }

}
