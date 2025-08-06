<?php
use App\Http\Controllers\DevTest;
use App\Http\Controllers\aset\AsetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Cetakkartu;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\kependudukan\KependudukanController;
use App\Http\Controllers\kependudukan\PendudukController;
use App\Http\Controllers\statistics\KKQController;
use App\Http\Controllers\statistics\ResidentController;
use App\Http\Controllers\statistics\Statistics;
use App\Http\Controllers\surat\SuratController;
use App\Http\Controllers\surat\PengumumanPenomoranController;
use App\Http\Controllers\surat\KawinPenomoranController;
use App\Http\Controllers\surat\CeraiPenomoranController;
use App\Http\Controllers\surat\IlikitaUtsahaPenomoranController;

use App\Http\Controllers\AlamatController;

use App\Http\Controllers\StatistikController;

use App\Http\Controllers\ExportController;

use App\Http\Controllers\kependudukan\NpkPenomoranController;

use App\Http\Controllers\MasterDataController;

use Illuminate\Support\Facades\Route;

{ //Export Data  tools
  Route::get('/export', [ExportController::class, 'index'])->name('export.index');
  Route::get('/export/download', [ExportController::class, 'export'])->name('export.download');
  Route::get('/export/generate', [ExportController::class, 'generateExport'])->name('export.generate');
}

{ //statistik route
  Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik.index');
  Route::get('/statistik/export', [StatistikController::class, 'export'])->name('statistik.export');
}

{//
  Route::get('/test-view' , [PendudukController::class, 'printKtpAdatTest'])->name('test-view');
}

{
  // --- ROUTE UNTUK MANAJEMEN DATA MASTER ---

  Route::get('/kependudukan/pilih-input', [KependudukanController::class, 'createOptions'])
    ->name('kependudukan.create.options');

  Route::prefix('master-data')->name('master-data.')->group(function () {
    // Halaman utama
    Route::get('/', [MasterDataController::class, 'index'])->name('index')->middleware('role:Admin');;

    // Routes untuk Banjar
    Route::put('/banjar/{banjar}', [MasterDataController::class, 'updateBanjar'])->name('banjar.update')->middleware('role:Admin');;

    // Routes untuk Klasifikasi Krama
    Route::post('/klasifikasi-krama', [MasterDataController::class, 'storeKlasifikasiKrama'])->name('klasifikasi-krama.store')->middleware('role:Admin');;
    Route::put('/klasifikasi-krama/{klasifikasiKrama}', [MasterDataController::class, 'updateKlasifikasiKrama'])->name('klasifikasi-krama.update')->middleware('role:Admin');;
    Route::delete('/klasifikasi-krama/{klasifikasiKrama}', [MasterDataController::class, 'destroyKlasifikasiKrama'])->name('klasifikasi-krama.destroy')->middleware('role:Admin');;

    // Routes untuk Dadia Penatahan
    Route::post('/dadia-penatahan', [MasterDataController::class, 'storeDadiaPenatahan'])->name('dadia-penatahan.store')->middleware('role:Admin');;
    Route::put('/dadia-penatahan/{dadiaPenatahan}', [MasterDataController::class, 'updateDadiaPenatahan'])->name('dadia-penatahan.update')->middleware('role:Admin');;
    Route::delete('/dadia-penatahan/{dadiaPenatahan}', [MasterDataController::class, 'destroyDadiaPenatahan'])->name('dadia-penatahan.destroy')->middleware('role:Admin');;

    // Routes untuk Bendesa Adat
    Route::post('/bendesa-adat', [MasterDataController::class, 'storeBendesaAdat'])->name('bendesa-adat.store')->middleware('role:Admin');;
    Route::put('/bendesa-adat/{bendesaAdat}', [MasterDataController::class, 'updateBendesaAdat'])->name('bendesa-adat.update')->middleware('role:Admin');;
    Route::delete('/bendesa-adat/{bendesaAdat}', [MasterDataController::class, 'destroyBendesaAdat'])->name('bendesa-adat.destroy')->middleware('role:Admin');;

    // Routes untuk Dadia
    Route::post('/dadia', [MasterDataController::class, 'storeDadia'])->name('dadia.store');
    // Tambahkan route update & delete untuk Dadia jika diperlukan
    // PERBARUI: Tambahkan route delete untuk Dadia
    Route::delete('/dadia/{dadia}', [MasterDataController::class, 'destroyDadia'])->name('dadia.destroy');
    Route::get('/dadia/export', [MasterDataController::class, 'exportDadia'])->name('dadia.export');


    // Routes untuk Penatahan
    Route::post('/penatahan', [MasterDataController::class, 'storePenatahan'])->name('penatahan.store');
    Route::put('/penatahan/{penatahan}', [MasterDataController::class, 'updatePenatahan'])->name('penatahan.update');
    Route::delete('/penatahan/{penatahan}', [MasterDataController::class, 'destroyPenatahan'])->name('penatahan.destroy');
  });
}

{

  // Route untuk menampilkan form
  Route::get('/alamat/create', [AlamatController::class, 'create'])->name('alamat.create')->middleware('role:Admin');;
  Route::post('/api/post', [AlamatController::class, 'store'])->name('api.post')->middleware('role:Admin');;

// Route API untuk data dinamis
  Route::get('/api/kota', [AlamatController::class, 'getKota'])->name('api.kota')->middleware('role:Admin');;
  Route::get('/api/kecamatan', [AlamatController::class, 'getKecamatan'])->name('api.kecamatan')->middleware('role:Admin');;
  Route::get('/api/desa', [AlamatController::class, 'getDesa'])->name('api.desa')->middleware('role:Admin');;


}

{ //Developing Test
  Route::get('/dev-test-cropperjs', [DevTest::class, 'cropperjs'])
    ->name('dev-test')->middleware('role:Admin');;
}

{ //KEPENDUDUKAN
    //kependudukan ver3
    Route::get('/kependudukan', [KependudukanController::class, 'index'])
        ->name('kependudukan.index')->middleware('role:Admin');;

    Route::get('/kependudukan-krama-tamiu', [KependudukanController::class, 'indexKramaTamiu'])
      ->name('kependudukan.index-krama-tamiu')->middleware('role:Admin');;

    Route::get('/kependudukan-tamiu', [KependudukanController::class, 'indexTamiu'])
      ->name('kependudukan.index-tamiu')->middleware('role:Admin');;



  Route::get('/kependudukan/detail/{npk}', [KependudukanController::class, 'show'])
      ->name('kependudukan.show')->middleware('role:Admin');; //anggota kka

    //print kependudukan ver3
    Route::get('/kependudukan/print/family/{npk}', [KependudukanController::class, 'printCardFamily'])
        ->name('kependudukan.print.family')->middleware('role:Admin');;

    Route::get('/kependudukan/print/by-nik/{nik}', [KependudukanController::class, 'printCardByNik'])
        ->name('kependudukan.print.by_nik')->middleware('role:Admin');;

    //edit kependudukan ver3
    Route::get('/kependudukan/edit/{npk}', [KependudukanController::class, 'edit'])
      ->name('kependudukan.edit')->middleware('role:Admin');;

    Route::put('/kependudukan/update/{npk}', [KependudukanController::class, 'update'])
      ->name('kependudukan.update')->middleware('role:Admin');;

    //store kependudukan ver3 --- input manual input krama_adat

    Route::get('/kependudukan/pilih-input', [KependudukanController::class, 'createOptions'])
      ->name('kependudukan.create.options');

    Route::get('/kependudukan/create', [KependudukanController::class, 'create'])
      ->name('kependudukan.create')->middleware('role:Admin');;

    Route::post('/kependudukan', [KependudukanController::class, 'store'])
      ->name('kependudukan.store')->middleware('role:Admin');;

    Route::get('/kependudukan/penomoran-npk', [NpkPenomoranController::class, 'index'])->name('penomoran.npk.index')->middleware('role:Admin');;
    Route::put('/kependudukan/penomoran-npk/update', [NpkPenomoranController::class, 'update'])->name('penomoran.npk.update')->middleware('role:Admin');;


  Route::prefix('kependudukan/nika-npk/penomoran')->name('kependudukan.nika-npk.penomoran.')->group(function () {
    // Menampilkan halaman utama manajemen penomoran
    Route::get('/', [KependudukanController::class, 'index'])->name('indexPenomoran')->middleware('role:Admin');;

    // Menyimpan kode surat baru
    Route::post('/', [KependudukanController::class, 'store'])->name('storePenoroman')->middleware('role:Admin');;

    // Mengupdate nomor urut terakhir
    Route::put('/update', [KependudukanController::class, 'update'])->name('updatePenomoran')->middleware('role:Admin');;

    // Menghapus kode surat
    Route::delete('/delete', [KependudukanController::class, 'destroy'])->name('destroyPenomoran')->middleware('role:Admin');;
  });

    // BULK PRINTER KK ADAT

      Route::get('/kependudukan/bulk-print/{kode_banjar}', [KependudukanController::class, 'bulkPrint'])
        ->name('kependudukan.bulk.print')->middleware('role:Admin');;

    //NIKA NIKA NIKA NIKA
    Route::get('/penduduk', [PendudukController::class, 'pendudukIndex'])
      ->name('penduduk.index')->middleware('role:Admin');;

    Route::get('/penduduk/print/{nika}', [PendudukController::class, 'printKtpAdat'])
      ->name('penduduk.print.card')->middleware('role:Admin');;

    // Route untuk menampilkan form edit perorangan berdasarkan NIKA
    Route::get('/penduduk/edit/{nika}', [PendudukController::class, 'pendudukEdit'])
      ->name('penduduk.edit')->middleware('role:Admin');;

  // Route untuk memproses pembaruan data dari form edit perorangan
    Route::put('/penduduk/update/{nika}', [PendudukController::class, 'pendudukUpdate'])
      ->name('penduduk.update')->middleware('role:Admin');;

    Route::get('/penduduk/pilih-input', [PendudukController::class, 'pendudukOptionsCreate'])
      ->name('penduduk.create.options')->middleware('role:Admin');

    Route::get('/penduduk/create/domisili-bali', [PendudukController::class, 'pendudukCreateBali'])
      ->name('penduduk.create.bali')->middleware('role:Admin');

    Route::get('/penduduk/create/domisili-luar-bali', [PendudukController::class, 'pendudukCreateLuarBali'])
      ->name('penduduk.create.luar-bali')->middleware('role:Admin');

    Route::post('/penduduk', [PendudukController::class, 'store'])
      ->name('penduduk.store');

}

{   //SURAT-SURAT
    Route::get('/surat-menyurat', [SuratController::class, 'suratIndex']) ->name('surat.indexMain')->middleware('role:Admin');;
    Route::get('/surat-cerai', [SuratController::class, 'suratCerai']) ->name('surat.cerai')->middleware('role:Admin');;
    Route::get('/surat-kawin', [SuratController::class, 'suratKawin']) ->name('surat.kawin')->middleware('role:Admin');;
    Route::get('/ilkita-kawin', [SuratController::class, 'ilkitaKawin']) ->name('ilkita.kawin')->middleware('role:Admin');;
    Route::get('/surat-pengumuman', [SuratController::class, 'suratPengumuman']) ->name('surat.pengumuman')->middleware('role:Admin');;
    Route::get('/ilikita-mautsaha', [SuratController::class, 'ilkitaMautsaha']) ->name('ilkita.mautsaha')->middleware('role:Admin');;

  { //=============SURAT PENGUMUMAN KAWIN=====================
    // Halaman arsip untuk melihat semua surat yang telah dibuat
    Route::get('/surat/arsip-pengumuman', [SuratController::class, 'arsipPengumumanKawin'])
      ->name('surat.arsip.pengumuman')->middleware('role:Admin');;

    // Route untuk form pembuatan Surat Pengumuman Kawin
    Route::get('/surat/pengumuman-kawin/create', [SuratController::class, 'createPengumumanKawin'])
      ->name('surat.pengumuman_kawin.create')->middleware('role:Admin');;

    Route::get('/surat/pengumuman-kawin/delete/{surat}', [SuratController::class, 'deletePengumumanKawin'])
      ->name('surat.pengumuman_kawin.delete')->middleware('role:Admin');;

    // Route untuk menyimpan data surat baru
    Route::post('/surat/pengumuman-kawin', [SuratController::class, 'storePengumumanKawin'])
      ->name('surat.pengumuman_kawin.store')->middleware('role:Admin');;

    // Route untuk preview/cetak surat
    Route::get('/surat/pengumuman-kawin/preview/{surat}', [SuratController::class, 'previewPengumumanKawin'])
      ->name('surat.pengumuman_kawin.preview')->middleware('role:Admin');;

    Route::prefix('surat/pengumuman/penomoran')->name('surat.pengumuman.penomoran.')->group(function () {
      // Menampilkan halaman utama manajemen penomoran
      Route::get('/', [PengumumanPenomoranController::class, 'index'])->name('index')->middleware('role:Admin');;

      // Menyimpan kode surat baru
      Route::post('/', [PengumumanPenomoranController::class, 'store'])->name('store')->middleware('role:Admin');;

      // Mengupdate nomor urut terakhir
      Route::put('/update', [PengumumanPenomoranController::class, 'update'])->name('update')->middleware('role:Admin');;

      // Menghapus kode surat
      Route::delete('/delete', [PengumumanPenomoranController::class, 'destroy'])->name('destroy')->middleware('role:Admin');;
    });
  }

  { //=============SURAT KAWIN=====================
    // Halaman arsip untuk melihat semua surat yang telah dibuat
    Route::get('/surat/arsip-kawin', [SuratController::class, 'arsipKawin'])
      ->name('surat.arsip.kawin')->middleware('role:Admin');;

    // Route untuk form pembuatan Surat Kawin
    Route::get('/surat/kawin/create', [SuratController::class, 'createKawin'])
      ->name('surat.kawin.create')->middleware('role:Admin');;

    Route::get('/surat/kawin/delete/{surat}', [SuratController::class, 'deleteKawin'])
      ->name('surat.kawin.delete')->middleware('role:Admin');;

    // Route untuk menyimpan data surat baru
    Route::post('/surat/kawin', [SuratController::class, 'storeKawin'])
      ->name('surat.kawin.store')->middleware('role:Admin');;

    // Route untuk preview/cetak surat
    Route::get('/surat/kawin/preview/{surat}', [SuratController::class, 'previewKawin'])
      ->name('surat.kawin.preview')->middleware('role:Admin');;

    Route::prefix('surat/kawin/penomoran')->name('surat.kawin.penomoran.')->group(function () {
      // Menampilkan halaman utama manajemen penomoran
      Route::get('/', [KawinPenomoranController::class, 'index'])->name('index')->middleware('role:Admin');;

      // Menyimpan kode surat baru
      Route::post('/', [KawinPenomoranController::class, 'store'])->name('store')->middleware('role:Admin');;

      // Mengupdate nomor urut terakhir
      Route::put('/update', [KawinPenomoranController::class, 'update'])->name('update')->middleware('role:Admin');;

      // Menghapus kode surat
      Route::delete('/delete', [KawinPenomoranController::class, 'destroy'])->name('destroy')->middleware('role:Admin');;
    });
  }

  { //=============SURAT CERAI=====================
    // Halaman arsip untuk melihat semua surat yang telah dibuat
    Route::get('/surat/arsip-cerai', [SuratController::class, 'arsipCerai'])
      ->name('surat.arsip.cerai')->middleware('role:Admin');;

    // Route untuk form pembuatan Surat Kawin
    Route::get('/surat/cerai/create', [SuratController::class, 'createCerai'])
      ->name('surat.cerai.create')->middleware('role:Admin');;

    Route::get('/surat/cerai/delete/{surat}', [SuratController::class, 'deleteCerai'])
      ->name('surat.cerai.delete')->middleware('role:Admin');;

    // Route untuk menyimpan data surat baru
    Route::post('/surat/cerai', [SuratController::class, 'storeCerai'])
      ->name('surat.cerai.store')->middleware('role:Admin');;

    // Route untuk preview/cetak surat
    Route::get('/surat/cerai/preview/{surat}', [SuratController::class, 'previewCerai'])
      ->name('surat.cerai.preview')->middleware('role:Admin');;

    Route::prefix('surat/cerai/penomoran')->name('surat.cerai.penomoran.')->group(function () {
      // Menampilkan halaman utama manajemen penomoran
      Route::get('/', [CeraiPenomoranController::class, 'index'])->name('index')->middleware('role:Admin');;

      // Menyimpan kode surat baru
      Route::post('/', [CeraiPenomoranController::class, 'store'])->name('store')->middleware('role:Admin');;

      // Mengupdate nomor urut terakhir
      Route::put('/update', [CeraiPenomoranController::class, 'update'])->name('update')->middleware('role:Admin');;

      // Menghapus kode surat
      Route::delete('/delete', [CeraiPenomoranController::class, 'destroy'])->name('destroy')->middleware('role:Admin');;
    });
  }

  { //=============Ilkita Pawiwahan=====================
    // Halaman arsip untuk melihat semua surat yang telah dibuat
    Route::get('/surat/arsip-ilikita-pawiwahan', [SuratController::class, 'arsipIlikitaPawiwahan'])
      ->name('surat.arsip.ilikita_pawiwahan')->middleware('role:Admin');;

    // Route untuk form pembuatan Surat Kawin
    Route::get('/surat/ilikita-pawiwahan/create', [SuratController::class, 'createIlikitaPawiwahan'])
      ->name('surat.ilikita_pawiwahan.create')->middleware('role:Admin');;

    Route::get('/surat/ilikita-pawiwahan/delete/{surat}', [SuratController::class, 'deleteIlikitaPawiwahan'])
      ->name('surat.ilikita_pawiwahan.delete')->middleware('role:Admin');;

    // Route untuk menyimpan data surat baru
    Route::post('/surat/ilikita-pawiwahan', [SuratController::class, 'storeIlikitaPawiwahan'])
      ->name('surat.ilikita_pawiwahan.store')->middleware('role:Admin');;

    // Route untuk preview/cetak surat
    Route::get('/surat/ilikita-pawiwahan/preview/{surat}', [SuratController::class, 'previewIlikitaPawiwahan'])
      ->name('surat.ilikita_pawiwahan.preview')->middleware('role:Admin');;

    Route::prefix('surat/ilikita-pawiwahan/penomoran')->name('surat.ilikita_pawiwahan.penomoran.')->group(function () {
      // Menampilkan halaman utama manajemen penomoran
      Route::get('/', [IlikitaPawiwahanPenomoranController::class, 'index'])->name('index')->middleware('role:Admin');;

      // Menyimpan kode surat baru
      Route::post('/', [IlikitaPawiwahanPenomoranController::class, 'store'])->name('store')->middleware('role:Admin');;

      // Mengupdate nomor urut terakhir
      Route::put('/update', [IlikitaPawiwahanPenomoranController::class, 'update'])->name('update')->middleware('role:Admin');

      // Menghapus kode surat
      Route::delete('/delete', [IlikitaPawiwahanPenomoranController::class, 'destroy'])->name('destroy')->middleware('role:Admin');;
    });
  }

  { //=============Ilkita Utsaha=====================
    // Halaman arsip untuk melihat semua surat yang telah dibuat
    Route::get('/surat/arsip-ilikita-mautsaha', [SuratController::class, 'arsipIlikitaMautsaha'])
      ->name('surat.arsip.ilikita_mautsaha')->middleware('role:Admin');;

    // Route untuk form pembuatan Surat Kawin
    Route::get('/surat/ilikita-mautsaha/create', [SuratController::class, 'createIlikitaMautsaha'])
      ->name('surat.ilikita_mautsaha.create')->middleware('role:Admin');;

    Route::get('/surat/ilkita-mautsaha/delete/{surat}', [SuratController::class, 'deleteIlikitaMautsaha'])
      ->name('surat.ilikita_mautsaha.delete')->middleware('role:Admin');;

    // Route untuk menyimpan data surat baru
    Route::post('/surat/ilikita-mautsaha', [SuratController::class, 'storeIlikitaMautsaha'])
      ->name('surat.ilikita_mautsaha.store')->middleware('role:Admin');;

    // Route untuk preview/cetak surat
    Route::get('/surat/ilikita-mautsaha/preview/{surat}', [SuratController::class, 'previewIlikitaMautsaha'])
      ->name('surat.ilikita_mautsaha.preview')->middleware('role:Admin');;

    Route::prefix('surat/ilikita-utsaha/penomoran')->name('surat.ilikita_utsaha.penomoran.')->group(function () {
      // Menampilkan halaman utama manajemen penomoran
      Route::get('/', [IlikitaUtsahaPenomoranController::class, 'index'])->name('index')->middleware('role:Admin');;

      // Menyimpan kode surat baru
      Route::post('/', [IlikitaUtsahaPenomoranController::class, 'store'])->name('store')->middleware('role:Admin');;

      // Mengupdate nomor urut terakhir
      Route::put('/update', [IlikitaUtsahaPenomoranController::class, 'update'])->name('update')->middleware('role:Admin');;

      // Menghapus kode surat
      Route::delete('/delete', [IlikitaUtsahaPenomoranController::class, 'destroy'])->name('destroy')->middleware('role:Admin');;
    });
  }

}


//AUTH
Route::get('/', [AuthController::class, 'login'])->name('loginpage');
Route::post('/login', [AuthController::class, 'authenticate'])->name('loginpage');
Route::post('/logout', [AuthController::class, 'logout'])->name('loginpage');
Route::get('/register', [AuthController::class, 'registerView'])->name('registerpage');
Route::post('/register', [AuthController::class, 'register'])->name('registerpage');

//DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('role:Admin');



//ANALYTICS
Route::get('/statistics', [Statistics::class, 'index'])->name('statistics')->middleware('role:Admin');
Route::get('/statistics/krama-adat' , [Statistics::class, 'kramaAdat'])->name('statistics.krama-adat')->middleware('role:Admin');

Route::middleware(['auth'])->group(function () {
    Route::get('/KKQ', [KKQController::class, 'index'])
        ->name('KKQ.index')
        ->middleware('role:Admin');

    Route::get('/NIKQ/{nik}/print', [KKQController::class, 'printCard'])
        ->name('KKQ.print')
        ->middleware('role:Admin');

    Route::get('/KKQ/{no_kk}/print', [KKQController::class, 'printCardFamily'])
        ->name('KKQFam.print')
        ->middleware('role:Admin');
});


//cetak-kartu sidebar
Route::get('/cetak-kartu' , [Cetakkartu::class, 'index'])->name('cetakkartu')->middleware('role:Admin');

Route::get('/cetak-kartu-opsi' , [Cetakkartu::class, 'opsi'])->name('cetakkartuopsi')->middleware('role:Admin');
Route::get('/cetak-kartu-opsi-krama-tamiu' , [Cetakkartu::class, 'opsiKramaTamiu'])->name('cetakkartuopsikramatamiu')->middleware('role:Admin');
Route::get('/cetak-kartu-opsi-tamiu' , [Cetakkartu::class, 'opsiTamiu'])->name('cetakkartuopsitamiu')->middleware('role:Admin');

//Aset dan Inventaris
Route::get('/aset', [AsetController::class, 'index'])->name('aset.index');
Route::post('/aset/tanah', [AsetController::class, 'storeTanah'])->name('aset.tanah.store');
Route::post('/aset/barang', [AsetController::class, 'storeBarang'])->name('aset.barang.store');


Route::get('/settings', function () {
    return view('pages.settings.index');
})->name('settings')->middleware('role:Admin');

//Route::get('/input', function () {
//    return view('pages.inputdata.index');
//})->name('input')->middleware('role:Admin');

Route::get('/reports', function () {
    return view('pages.dashboard');
})->name('reports')->middleware('role:Admin');

Route::get('/database', [ResidentController::class, 'index'])->name('database')->middleware('role:Admin');

Route::get('/hosting', function () {
    return view('pages.dashboard');
})->name('hosting')->middleware('role:Admin');
