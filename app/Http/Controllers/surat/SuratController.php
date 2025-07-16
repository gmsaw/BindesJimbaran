<?php

namespace App\Http\Controllers\surat;

use App\Http\Controllers\Controller;
use App\Models\Banjar;
use App\Models\BendesaAdat;
use App\Models\MasterAdat;

use App\Models\NomorSuratCounterIlikitaUtsaha;
use App\Models\NomorSuratCounterPengumuman;
use App\Models\NomorSuratCounterKawin;
use App\Models\NomorSuratCounterCerai;

use App\Models\SuratIlikitaMautsaha;
use App\Models\SuratIlikitaPawiwahan;
use App\Models\SuratPengumumanKawin;
use App\Models\SuratKawin;
use App\Models\SuratCerai;

use Carbon\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;



class SuratController
{
 private function roman2number($roman){
    $conv = array(
      array("letter" => 'I', "number" => 1),
      array("letter" => 'V', "number" => 5),
      array("letter" => 'X', "number" => 10),
      array("letter" => 'L', "number" => 50),
      array("letter" => 'C', "number" => 100),
      array("letter" => 'D', "number" => 500),
      array("letter" => 'M', "number" => 1000),
      array("letter" => 0, "number" => 0)
    );
    $arabic = 0;
    $state = 0;
    $sidx = 0;
    $len = strlen($roman);

    while ($len >= 0) {
      $i = 0;
      $sidx = $len;

      while ($conv[$i]['number'] > 0) {
        if (strtoupper(@$roman[$sidx]) == $conv[$i]['letter']) {
          if ($state > $conv[$i]['number']) {
            $arabic -= $conv[$i]['number'];
          } else {
            $arabic += $conv[$i]['number'];
            $state = $conv[$i]['number'];
          }
        }
        $i++;
      }

      $len--;
    }

    return($arabic);
  }

  function number2roman($num,$isUpper=true) {
    $n = intval($num);
    $res = '';

    /*** roman_numerals array ***/
    $roman_numerals = array(
      'M' => 1000,
      'CM' => 900,
      'D' => 500,
      'CD' => 400,
      'C' => 100,
      'XC' => 90,
      'L' => 50,
      'XL' => 40,
      'X' => 10,
      'IX' => 9,
      'V' => 5,
      'IV' => 4,
      'I' => 1
    );

    foreach ($roman_numerals as $roman => $number)
    {
      /*** divide to get matches ***/
      $matches = intval($n / $number);

      /*** assign the roman char * $matches ***/
      $res .= str_repeat($roman, $matches);

      /*** substract from the number ***/
      $n = $n % $number;
    }

    /*** return the res ***/
    if($isUpper) return $res;
    else return strtolower($res);
  }

    public function suratIndex(){
      return view('pages.surat.index', [
        'title' => 'Layanan Surat & Ilikita Desa Adat'
      ]);
    }
    public function suratCerai(){
        return view("pages.surat.suratCerai");
    }

  public function suratKawin(){
    return view("pages.surat.suratKawin");
  }

  public function ilkitaKawin(){
    return view("pages.surat.ilikita-pawiwahan.ilkitaKawin");
  }

  public function suratPengumuman(){
    return view("pages.surat.pengumuman_kawin.suratPengumuman");
  }

  public function ilkitaMautsaha(){
    return view("pages.surat.ilikita-mautsaha.ilkitaMautsaha");
  }


  // =============================AWAL - PENGUMUMAN KAWIN CONTROLLER==============================
  public function arsipPengumumanKawin(Request $request)
  {
    $searchTerm = $request->input('search');

    $query = SuratPengumumanKawin::query();

    if ($searchTerm) {
      $query->where('nomor_surat', 'like', '%' . $searchTerm . '%')
        ->orWhere('purusa_nama', 'like', '%' . $searchTerm . '%')
        ->orWhere('pradana_nama', 'like', '%' . $searchTerm . '%');
    }

    $arsip_surat = $query->orderBy('tanggal_surat', 'desc')->paginate(20);

    return view('pages.surat.pengumuman_kawin.arsip_index_pengumuman', [
      'title' => 'Arsip Surat Desa Adat',
      'arsip_surat' => $arsip_surat,
      'searchTerm' => $searchTerm,
    ]);
  }

  public function createPengumumanKawin(Request $request)
  {
    $all_banjar = Banjar::orderBy('nama_banjar')->get();
    $bendesa_aktif = BendesaAdat::where('status_jabatan', 'aktif')->get();
    $all_kode_surat = NomorSuratCounterPengumuman::select('kode_surat')->distinct()->get();

    $nomor_berikutnya = null;
    $nomor_surat_otomatis = '';


    // Hanya generate nomor jika mode otomatis yang dipilih saat reload
    if ($request->query('penomoran') === 'otomatis') {
      $kode_surat_terpilih = $request->query('kode_surat');
      $tanggal_input = $request->query('tahun_surat_input');

      if ($kode_surat_terpilih && $tanggal_input) {
        $tahun_terpilih = Carbon::parse($tanggal_input)->year;
        $nomor_berikutnya = $this->generateNextSuratNumberPengumumanKawin($kode_surat_terpilih, $tahun_terpilih);
        $nomor_surat_otomatis = sprintf("%03d/%s/%d", $nomor_berikutnya, $kode_surat_terpilih, $tahun_terpilih);
      }
    }

    $suami = null;
    if ($request->has('search_suami')) {
      $search = $request->input('search_suami');
      $suami = MasterAdat::with('masterIndividu')
        ->where('nika', 'like', '%' . $search . '%')
        ->orWhereHas('masterIndividu', function ($query) use ($search) {
          $query->where('nama_lengkap', 'like', '%' . $search . '%');
        })->first();
    }

    // Logika Pencarian Data Istri (Pradana)
    $istri = null;
    if ($request->has('search_istri')) {
      $search = $request->input('search_istri');
      $istri = MasterAdat::with('masterIndividu')
        ->where('nika', 'like', '%' . $search . '%')
        ->orWhereHas('masterIndividu', function ($query) use ($search) {
          $query->where('nama_lengkap', 'like', '%' . $search . '%');
        })->first();
    }

    return view('pages.surat.pengumuman_kawin.create_pengumuman_kawin', [
      'title' => 'Buat Surat Pengumuman Kawin',
      'all_banjar' => $all_banjar,
      'bendesa_aktif' => $bendesa_aktif,
      'all_kode_surat' => $all_kode_surat,
      'nomor_surat_otomatis' => $nomor_surat_otomatis,
      'suami' => $suami,
      'istri' => $istri,
    ]);
  }

  public function deletePengumumanKawin(SuratPengumumanKawin $surat)
  {
    try {
      // Hapus record surat dari database
      $surat->delete();
      // Redirect kembali ke halaman arsip dengan pesan sukses
      return redirect()->route('surat.arsip.pengumuman')
        ->with('success', 'Surat dengan nomor ' . $surat->nomor_surat . ' berhasil dihapus.');
    } catch (\Exception $e) {
      // Jika terjadi error, kembali dengan pesan error
      return redirect()->route('surat.arsip.pengumuman')
        ->with('error', 'Gagal menghapus surat. Error: ' . $e->getMessage());
    }
  }

  public function storePengumumanKawin(Request $request)
  {

//    dd($request->all());

    $connectionName = 'db_kependudukan';

    $request->validate([
      'penomoran' => 'required|in:otomatis,manual',
      'nomor_surat_manual' => 'required_if:penomoran,manual|nullable|string|max:255',
      'kode_surat' => 'required_if:penomoran,otomatis|nullable|string',
      'tanggal_surat' => 'required|date',
      // ... validasi lainnya
    ]);

    $nomor_surat_final = '';

    if ($request->input('penomoran') == 'manual') {
      $nomor_surat_final = $request->input('nomor_surat_manual');

    } else {
      // Jika otomatis, kita gunakan nomor yang sudah disarankan dari form
      $nomor_surat_final = $request->input('nomor_surat_otomatis');
      // Dan kita perlu increment counter di database
      $kode = $request->input('kode_surat');
      $tahun = Carbon::parse($request->input('tanggal_surat'))->year;
      $this->generateNextSuratNumberPengumumanKawin($kode, $tahun, true); // true untuk increment
    }


    if ($request->input('penomoran') == 'manual') {
      $kode_surat_new = explode('/', $request->input('nomor_surat_manual'));
      $kode_surat_store_new = $kode_surat_new[1];
      $kode_surat_store_old = NomorSuratCounterPengumuman::query()->get('kode_surat');



      $toggle_kode_surat_new = NomorSuratCounterPengumuman::query()->where('kode_surat', $kode_surat_store_new)->first();

//      dd($kode_surat_new, $kode_surat_store_new, empty($kode_surat_store_old->kode_surat), $toggle_kode_surat_new, empty($toggle_kode_surat_new));

      if (empty($toggle_kode_surat_new)) {
        DB::connection($connectionName)->table('nomor_surat_counter_pengumuman')->insert(['kode_surat'=> $kode_surat_new[1], 'tahun'=>$kode_surat_new[2], 'nomor_terakhir'=>$kode_surat_new[0]]);
      } else {
        DB::connection($connectionName)->table('nomor_surat_counter_pengumuman')->where('kode_surat', $kode_surat_store_new)->update(['nomor_terakhir'=>$kode_surat_new[0]]);
      }

    }

    $request->validate(
      ['nomor_surat_final' => "unique:{$connectionName}.surat_pengumuman_kawin,nomor_surat"],
      ['nomor_surat_final' => $nomor_surat_final]
    );

    DB::connection($connectionName)->transaction(function () use ($request, $nomor_surat_final) {
      SuratPengumumanKawin::create([
        'nomor_surat' => $nomor_surat_final,
        'tanggal_surat' => $request->input('tanggal_surat'),
        'hari_surat' => $request->input('hari_surat'),
        'lokasi_surat_dibuat' => 'Jimbaran',
        'lingkungan_banjar' => Banjar::find($request->input('kode_banjar_lingkungan'))->nama_banjar,
        'purusa_nama' => $request->input('purusa.nama'),
        'purusa_nika' => $request->input('purusa.nika'),
        'purusa_ttl' => $request->input('purusa.tempat_lahir') . ', ' . Carbon::parse($request->input('purusa.tanggal_lahir'))->translatedFormat('d F Y'),
        'purusa_agama' => $request->input('purusa.agama'),
        'purusa_pekerjaan' => $request->input('purusa.pekerjaan'),
        'purusa_alamat' => $request->input('purusa.alamat'),
        'purusa_nama_ayah' => $request->input('purusa.nama_ayah'),
        'purusa_nama_ibu' => $request->input('purusa.nama_ibu'),
        'purusa_alamat_orang_tua' => $request->input('purusa.alamat_orang_tua'),
        'pradana_nama' => $request->input('pradana.nama'),
        'pradana_nika' => $request->input('pradana.nika'),
        'pradana_ttl' => $request->input('pradana.tempat_lahir') . ', ' . Carbon::parse($request->input('pradana.tanggal_lahir'))->translatedFormat('d F Y'),
        'pradana_agama' => $request->input('pradana.agama'),
        'pradana_pekerjaan' => $request->input('pradana.pekerjaan'),
        'pradana_alamat' => $request->input('pradana.alamat'),
        'pradana_nama_ayah' => $request->input('pradana.nama_ayah'),
        'pradana_nama_ibu' => $request->input('pradana.nama_ibu'),
        'pradana_alamat_orang_tua' => $request->input('pradana.alamat_orang_tua'),
        'pemuput_karya' => $request->input('pemuput_karya'),
        'id_bendesa_fk' => $request->input('id_bendesa_fk'),

        //tambahan

        'pradana_banjar' => (Banjar::find($request->input('pradana.banjar'))->nama_banjar ?? $request->input('pradana.nama_banjar_custom')),
        'purusa_banjar' => Banjar::find($request->input('purusa.banjar'))->nama_banjar ?? $request->input('purusa.nama_banjar_custom'),
        'pradana_banjar_orangtua' => Banjar::find($request->input('pradana.banjar_ortu'))->nama_banjar ?? $request->input('pradana.nama_banjar_custom_ortu'),
        'purusa_banjar_orangtua' => Banjar::find($request->input('purusa.banjar_ortu'))->nama_banjar ?? $request->input('purusa.nama_banjar_custom_ortu'),
      ]);
    });

    return back()->with('success', 'Surat Pengumuman Kawin berhasil dibuat dengan nomor ' . $nomor_surat_final);
  }

  public function previewPengumumanKawin($id_surat)
  {
    $query = SuratPengumumanKawin::query();
    $surat = $query->findOrFail($id_surat);
    $bendesa_aktif = BendesaAdat::query();
    $bendesa = $bendesa_aktif->where('status_jabatan', 'aktif')->first();
    $surat -> load('bendesa');

    return view('pages.surat.pengumuman_kawin.preview_pengumuman_kawin', [
      'title' => 'Preview Surat Pengumuman Kawin',
      'surat' => $surat,
      'query' =>  $query,
      'bendesa' => $bendesa,
    ]);
  }

  private function generateNextSuratNumberPengumumanKawin($kode, $tahun, $increment = false)
  {
    if (!$kode || !$tahun) return 1;

    $counter = NomorSuratCounterPengumuman::firstOrCreate(
      ['kode_surat' => $kode, 'tahun' => $tahun]
    );
    if ($increment) {
      $counter->increment('nomor_terakhir');
      return $counter->nomor_terakhir;
    }
    return $counter->nomor_terakhir + 1;
  }

  // =============================AKHIR - PENGUMUMAN KAWIN CONTROLLER==============================


  // =============================MULAI - KAWIN CONTROLLER==============================

  public function arsipKawin(Request $request)
  {
    $searchTerm = $request->input('search');

    $query = SuratKawin::query();

    if ($searchTerm) {
      $query->where('nomor_surat', 'like', '%' . $searchTerm . '%')
        ->orWhere('purusa_nama', 'like', '%' . $searchTerm . '%')
        ->orWhere('pradana_nama', 'like', '%' . $searchTerm . '%');
    }

    $arsip_surat = $query->orderBy('tanggal_surat', 'desc')->paginate(20);

    return view('pages.surat.kawin.arsip_index_kawin', [
      'title' => 'Arsip Surat Kawin Desa Adat',
      'arsip_surat' => $arsip_surat,
      'searchTerm' => $searchTerm,
    ]);
  }

  public function createKawin(Request $request)
  {
    $all_banjar = Banjar::orderBy('nama_banjar')->get();
    $bendesa_aktif = BendesaAdat::where('status_jabatan', 'aktif')->get();
    $all_kode_surat = NomorSuratCounterKawin::select('kode_surat')->distinct()->get();

    $nomor_berikutnya = null;
    $nomor_surat_otomatis = '';


    // Hanya generate nomor jika mode otomatis yang dipilih saat reload
    if ($request->query('penomoran') === 'otomatis') {
      $kode_surat_terpilih = $request->query('kode_surat');
      $tanggal_input = $request->query('tahun_surat_input');

      if ($kode_surat_terpilih && $tanggal_input) {
        $tahun_terpilih = Carbon::parse($tanggal_input)->year;
        $bulan_terpilih = Carbon::parse($tanggal_input)->month;
        $nomor_berikutnya = $this->generateNextSuratNumberKawin($kode_surat_terpilih, $tahun_terpilih, $bulan_terpilih);
        $nomor_surat_otomatis = sprintf("%03d/%s/%s/%d", $nomor_berikutnya, $kode_surat_terpilih, $this->number2roman($bulan_terpilih) ,$tahun_terpilih);
      }
    }

    $suami = null;
    if ($request->has('search_suami')) {
      $search = $request->input('search_suami');
      $suami = MasterAdat::with('masterIndividu')
        ->where('nika', 'like', '%' . $search . '%')
        ->orWhereHas('masterIndividu', function ($query) use ($search) {
          $query->where('nama_lengkap', 'like', '%' . $search . '%');
        })->first();
    }

    // Logika Pencarian Data Istri (Pradana)
    $istri = null;
    if ($request->has('search_istri')) {
      $search = $request->input('search_istri');
      $istri = MasterAdat::with('masterIndividu')
        ->where('nika', 'like', '%' . $search . '%')
        ->orWhereHas('masterIndividu', function ($query) use ($search) {
          $query->where('nama_lengkap', 'like', '%' . $search . '%');
        })->first();
    }

    return view('pages.surat.kawin.create_kawin', [
      'title' => 'Buat Surat Kawin',
      'all_banjar' => $all_banjar,
      'bendesa_aktif' => $bendesa_aktif,
      'all_kode_surat' => $all_kode_surat,
      'nomor_surat_otomatis' => $nomor_surat_otomatis,
      'suami' => $suami,
      'istri' => $istri,
    ]);
  }

  public function deleteKawin(SuratKawin $surat)
  {

//    dd($surat);

    try {
      // Hapus record surat dari database
      $surat->delete();
      // Redirect kembali ke halaman arsip dengan pesan sukses
      return back()
        ->with('success', 'Surat dengan nomor ' . $surat->nomor_surat . ' berhasil dihapus.');
    } catch (\Exception $e) {
      // Jika terjadi error, kembali dengan pesan error
      return back()
        ->with('error', 'Gagal menghapus surat. Error: ' . $e->getMessage());
    }
  }

  public function storeKawin(Request $request)
  {

    $connectionName = 'db_kependudukan';
//    dd($request->input('pradana.banjar'));


    $request->validate([
      'penomoran' => 'required|in:otomatis,manual',
      'nomor_surat_manual' => 'required_if:penomoran,manual|nullable|string|max:255',
      'kode_surat' => 'required_if:penomoran,otomatis|nullable|string',
      'tanggal_surat' => 'required|date',
      // ... validasi lainnya
    ]);

    $nomor_surat_final = '';

    if ($request->input('penomoran') == 'manual') {
      $nomor_surat_final = $request->input('nomor_surat_manual');

    } else {
      // Jika otomatis, kita gunakan nomor yang sudah disarankan dari form
      $nomor_surat_final = $request->input('nomor_surat_otomatis');
      // Dan kita perlu increment counter di database
      $kode = $request->input('kode_surat');
      $tahun = Carbon::parse($request->input('tanggal_surat'))->year;
      $bulan = Carbon::parse($request->input('tanggal_surat'))->month;
      $this->generateNextSuratNumberKawin($kode, $tahun, $bulan, true); // true untuk increment
    }

    if ($request->input('penomoran') == 'manual') {
      $kode_surat_new = explode('/', $request->input('nomor_surat_manual'));
      $kode_surat_store_new = $kode_surat_new[1];

      $toggle_kode_surat_new = NomorSuratCounterKawin::query()->where('kode_surat', $kode_surat_store_new)->first();

      if (empty($toggle_kode_surat_new)) {
        DB::connection($connectionName)->table('nomor_surat_counter_kawin')->insert(['kode_surat'=> $kode_surat_new[1], 'bulan' => $this->roman2number($kode_surat_new[2]), 'tahun'=>$kode_surat_new[3], 'nomor_terakhir'=>$kode_surat_new[0]]);
      } else {
        DB::connection($connectionName)->table('nomor_surat_counter_kawin')->where('kode_surat', $kode_surat_store_new)->update(['nomor_terakhir'=>$kode_surat_new[0]]);
      }

    }

    $request->validate(
      ['nomor_surat_final' => "unique:{$connectionName}.surat_kawin,nomor_surat"],
      ['nomor_surat_final' => $nomor_surat_final]
    );

    DB::connection($connectionName)->transaction(function () use ($request, $nomor_surat_final) {
      SuratKawin::create([
        'nomor_surat' => $nomor_surat_final,
        'tanggal_surat' => $request->input('tanggal_surat'),
        'hari_surat' => (Carbon::parse($request->input('tanggal_surat'))->translatedFormat('l')),
        'lokasi_surat_dibuat' => 'Jimbaran',
        'lingkungan_banjar' => Banjar::find($request->input('kode_banjar_lingkungan'))->nama_banjar,
        'purusa_nama' => $request->input('purusa.nama'),
        'purusa_nika' => $request->input('purusa.nika'),
        'purusa_ttl' => $request->input('purusa.tempat_lahir') . ', ' . Carbon::parse($request->input('purusa.tanggal_lahir'))->translatedFormat('d F Y'),
        'purusa_agama' => $request->input('purusa.agama'),
        'purusa_pekerjaan' => $request->input('purusa.pekerjaan'),
        'purusa_alamat' => $request->input('purusa.alamat'),
        'purusa_nama_ayah' => $request->input('purusa.nama_ayah'),
        'purusa_nama_ibu' => $request->input('purusa.nama_ibu'),
        'purusa_alamat_orang_tua' => $request->input('purusa.alamat_orang_tua'),
        'pradana_nama' => $request->input('pradana.nama'),
        'pradana_nika' => $request->input('pradana.nika'),
        'pradana_ttl' => $request->input('pradana.tempat_lahir') . ', ' . Carbon::parse($request->input('pradana.tanggal_lahir'))->translatedFormat('d F Y'),
        'pradana_agama' => $request->input('pradana.agama'),
        'pradana_pekerjaan' => $request->input('pradana.pekerjaan'),
        'pradana_alamat' => $request->input('pradana.alamat'),
        'pradana_nama_ayah' => $request->input('pradana.nama_ayah'),
        'pradana_nama_ibu' => $request->input('pradana.nama_ibu'),
        'pradana_alamat_orang_tua' => $request->input('pradana.alamat_orang_tua'),
        'pemuput_karya' => $request->input('pemuput_karya'),
        'id_bendesa_fk' => $request->input('id_bendesa_fk'),
        'saksi_1' => $request->input('saksi_1'),
        'saksi_2' => $request->input('saksi_2'),
        //tambahan
        'tanggal_kawin' => $request->input('tanggal_kawin'),

        'pradana_banjar' => (Banjar::find($request->input('pradana.banjar'))->nama_banjar ?? $request->input('pradana.nama_banjar_custom')),
        'purusa_banjar' => Banjar::find($request->input('purusa.banjar'))->nama_banjar ?? $request->input('purusa.nama_banjar_custom'),
        'pradana_banjar_orangtua' => Banjar::find($request->input('pradana.banjar_ortu'))->nama_banjar ?? $request->input('pradana.nama_banjar_custom_ortu'),
        'purusa_banjar_orangtua' => Banjar::find($request->input('purusa.banjar_ortu'))->nama_banjar ?? $request->input('purusa.nama_banjar_custom_ortu'),
      ]);
    });

    return back()->with('success', 'Surat Pengumuman Kawin berhasil dibuat dengan nomor ' . $nomor_surat_final);
  }

  public function previewKawin($id_surat)
  {
    $query = SuratKawin::query();
    $surat = $query->findOrFail($id_surat);
    $bendesa_aktif = BendesaAdat::query();
    $bendesa = $bendesa_aktif->where('status_jabatan', 'aktif')->first();
    $surat -> load('bendesa');

    return view('pages.surat.kawin.preview_kawin', [
      'title' => 'Preview Surat Pengumuman Kawin',
      'surat' => $surat,
      'query' =>  $query,
      'bendesa' => $bendesa,
    ]);
  }

  private function generateNextSuratNumberKawin($kode, $tahun, $bulan, $increment = false)
  {
    if (!$kode || !$tahun) return 1;

    $counter = NomorSuratCounterKawin::firstOrCreate(
      ['kode_surat' => $kode, 'tahun' => $tahun, 'bulan' => $bulan]
    );
    if ($increment) {
      $counter->increment('nomor_terakhir');
      return $counter->nomor_terakhir;
    }
    return $counter->nomor_terakhir + 1;
  }

  // =============================AKHIR - KAWIN CONTROLLER==============================

  // =============================AWAL - CERAI CONTROLLER==============================

  public function arsipCerai(Request $request)
  {
    $searchTerm = $request->input('search');

    $query = SuratCerai::query();

    if ($searchTerm) {
      $query->where('nomor_surat', 'like', '%' . $searchTerm . '%')
        ->orWhere('purusa_nama', 'like', '%' . $searchTerm . '%')
        ->orWhere('pradana_nama', 'like', '%' . $searchTerm . '%');
    }

    $arsip_surat = $query->orderBy('tanggal_surat', 'desc')->paginate(20);

    return view('pages.surat.cerai.arsip_index_cerai', [
      'title' => 'Arsip Surat Cerai Desa Adat',
      'arsip_surat' => $arsip_surat,
      'searchTerm' => $searchTerm,
    ]);
  }

  public function createCerai(Request $request)
  {
    $all_banjar = Banjar::orderBy('nama_banjar')->get();

    $all_banjar = Banjar::orderBy('nama_banjar')->get();
    $bendesa_aktif = BendesaAdat::where('status_jabatan', 'aktif')->get();
    $all_kode_surat = NomorSuratCounterCerai::select('kode_surat')->distinct()->get();

    $nomor_berikutnya = null;
    $nomor_surat_otomatis = '';

    // Hanya generate nomor jika mode otomatis yang dipilih saat reload
    if ($request->query('penomoran') === 'otomatis') {
      $kode_surat_terpilih = $request->query('kode_surat');
      $tanggal_input = $request->query('tahun_surat_input');

      if ($kode_surat_terpilih && $tanggal_input) {
        $tahun_terpilih = Carbon::parse($tanggal_input)->year;
        $bulan_terpilih = Carbon::parse($tanggal_input)->month;
        $nomor_berikutnya = $this->generateNextSuratNumberCerai($kode_surat_terpilih, $tahun_terpilih, $bulan_terpilih);
        $nomor_surat_otomatis = sprintf("%03d/%s/%s/%d", $nomor_berikutnya, $kode_surat_terpilih, $this->number2roman($bulan_terpilih) ,$tahun_terpilih);
      }
    }

    $suami = null;
    if ($request->has('search_suami')) {
      $search = $request->input('search_suami');
      $suami = MasterAdat::with('masterIndividu')
        ->where('nika', 'like', '%' . $search . '%')
        ->orWhereHas('masterIndividu', function ($query) use ($search) {
          $query->where('nama_lengkap', 'like', '%' . $search . '%');
        })->first();
    }

    // Logika Pencarian Data Istri (Pradana)
    $istri = null;
    if ($request->has('search_istri')) {
      $search = $request->input('search_istri');
      $istri = MasterAdat::with('masterIndividu')
        ->where('nika', 'like', '%' . $search . '%')
        ->orWhereHas('masterIndividu', function ($query) use ($search) {
          $query->where('nama_lengkap', 'like', '%' . $search . '%');
        })->first();
    }

    return view('pages.surat.cerai.create_cerai', [
      'title' => 'Buat Surat Cerai',
      'all_banjar' => $all_banjar,
      'bendesa_aktif' => $bendesa_aktif,
      'all_kode_surat' => $all_kode_surat,
      'nomor_surat_otomatis' => $nomor_surat_otomatis,
      'suami' => $suami,
      'istri' => $istri,
    ]);
  }

  public function deleteCerai(SuratCerai $surat)
  {
//    dd($surat);

    try {
      // Hapus record surat dari database
      $surat->delete();
      // Redirect kembali ke halaman arsip dengan pesan sukses
      return back()
        ->with('success', 'Surat dengan nomor ' . $surat->nomor_surat . ' berhasil dihapus.');
    } catch (\Exception $e) {
      // Jika terjadi error, kembali dengan pesan error
      return back()
        ->with('error', 'Gagal menghapus surat. Error: ' . $e->getMessage());
    }
  }

  public function storeCerai(Request $request)
  {
    $connectionName = 'db_kependudukan';

    $request->validate([
      'penomoran' => 'required|in:otomatis,manual',
      'nomor_surat_manual' => 'required_if:penomoran,manual|nullable|string|max:255',
      'kode_surat' => 'required_if:penomoran,otomatis|nullable|string',
      'tanggal_surat' => 'required|date',
      // ... validasi lainnya
    ]);

    $nomor_surat_final = '';

    if ($request->input('penomoran') == 'manual') {
      $nomor_surat_final = $request->input('nomor_surat_manual');

    } else {
      // Jika otomatis, kita gunakan nomor yang sudah disarankan dari form
      $nomor_surat_final = $request->input('nomor_surat_otomatis');
      // Dan kita perlu increment counter di database
      $kode = $request->input('kode_surat');
      $tahun = Carbon::parse($request->input('tanggal_surat'))->year;
      $bulan = Carbon::parse($request->input('tanggal_surat'))->month;
      $this->generateNextSuratNumberCerai($kode, $tahun, $bulan, true); // true untuk increment
    }

    if ($request->input('penomoran') == 'manual') {
      $kode_surat_new = explode('/', $request->input('nomor_surat_manual'));
      $kode_surat_store_new = $kode_surat_new[1];

      $toggle_kode_surat_new = NomorSuratCounterCerai::query()->where('kode_surat', $kode_surat_store_new)->first();

      if (empty($toggle_kode_surat_new)) {
        DB::connection($connectionName)->table('nomor_surat_counter_cerai')->insert(['kode_surat'=> $kode_surat_new[1], 'bulan' => $this->roman2number($kode_surat_new[2]), 'tahun'=>$kode_surat_new[3], 'nomor_terakhir'=>$kode_surat_new[0]]);
      } else {
        DB::connection($connectionName)->table('nomor_surat_counter_cerai')->where('kode_surat', $kode_surat_store_new)->update(['nomor_terakhir'=>$kode_surat_new[0]]);
      }

    }

    $hold_pemuput_karya =  null;

    if (! empty($request->input('pemuput_karya'))){
      $hold_pemuput_karya = $request->input('pemuput_karya');
    }

    DB::connection($connectionName)->transaction(function () use ($request, $nomor_surat_final, $hold_pemuput_karya) {
      SuratCerai::create([
        'nomor_surat' => $nomor_surat_final,
        'tanggal_surat' => $request->input('tanggal_surat'),
        'hari_surat' => (Carbon::parse($request->input('tanggal_surat'))->translatedFormat('l')),
        'lokasi_surat_dibuat' => 'Jimbaran',
        'lingkungan_banjar' => Banjar::find($request->input('kode_banjar_lingkungan'))->nama_banjar,
        'purusa_nama' => $request->input('purusa.nama'),
        'purusa_nika' => $request->input('purusa.nika'),
        'purusa_ttl' => $request->input('purusa.tempat_lahir') . ', ' . Carbon::parse($request->input('purusa.tanggal_lahir'))->translatedFormat('d F Y'),
        'purusa_agama' => $request->input('purusa.agama'),
        'purusa_pekerjaan' => $request->input('purusa.pekerjaan'),
        'purusa_alamat' => $request->input('purusa.alamat'),
        'purusa_nama_ayah' => $request->input('purusa.nama_ayah'),
        'purusa_nama_ibu' => $request->input('purusa.nama_ibu'),
        'purusa_alamat_orang_tua' => $request->input('purusa.alamat_orang_tua'),
        'pradana_nama' => $request->input('pradana.nama'),
        'pradana_nika' => $request->input('pradana.nika'),
        'pradana_ttl' => $request->input('pradana.tempat_lahir') . ', ' . Carbon::parse($request->input('pradana.tanggal_lahir'))->translatedFormat('d F Y'),
        'pradana_agama' => $request->input('pradana.agama'),
        'pradana_pekerjaan' => $request->input('pradana.pekerjaan'),
        'pradana_alamat' => $request->input('pradana.alamat'),
        'pradana_nama_ayah' => $request->input('pradana.nama_ayah'),
        'pradana_nama_ibu' => $request->input('pradana.nama_ibu'),
        'pradana_alamat_orang_tua' => $request->input('pradana.alamat_orang_tua'),

        'pemuput_karya' => $hold_pemuput_karya,

        'id_bendesa_fk' => $request->input('id_bendesa_fk'),
        'saksi_1' => $request->input('saksi_1'),
        'saksi_2' => $request->input('saksi_2'),

        'kepala_lingkungan' => $request->input('kepala_lingkungan'),
        'kelihan_adat_banjar' => $request->input('kelihan_adat_banjar'),
        'lurah' => $request->input('lurah'),
        'camat' => $request->input('camat'),

        //tambahan

        'tanggal_cerai' => $request->input('tanggal_cerai'),

        'pradana_banjar' => (Banjar::find($request->input('pradana.banjar'))->nama_banjar ?? $request->input('pradana.nama_banjar_custom')),
        'purusa_banjar' => Banjar::find($request->input('purusa.banjar'))->nama_banjar ?? $request->input('purusa.nama_banjar_custom'),
        'pradana_banjar_orangtua' => Banjar::find($request->input('pradana.banjar_ortu'))->nama_banjar ?? $request->input('pradana.nama_banjar_custom_ortu'),
        'purusa_banjar_orangtua' => Banjar::find($request->input('purusa.banjar_ortu'))->nama_banjar ?? $request->input('purusa.nama_banjar_custom_ortu'),
      ]);
    });

//    dd($request->all());

    return back()->with('success', 'Surat Pengumuman Kawin berhasil dibuat dengan nomor ' . $nomor_surat_final);
  }

  public function previewCerai($id_surat)
  {
    $query = SuratCerai::query();
    $surat = $query->findOrFail($id_surat);
    $bendesa_aktif = BendesaAdat::query();
    $bendesa = $bendesa_aktif->where('status_jabatan', 'aktif')->first();
    $surat -> load('bendesa');

    return view('pages.surat.cerai.preview_cerai', [
      'title' => 'Preview Surat Cerai',
      'surat' => $surat,
      'query' =>  $query,
      'bendesa' => $bendesa,
    ]);
  }

  private function generateNextSuratNumberCerai($kode, $tahun, $bulan, $increment = false)
  {
    if (!$kode || !$tahun) return 1;

    $counter = NomorSuratCounterCerai::firstOrCreate(
      ['kode_surat' => $kode, 'tahun' => $tahun, 'bulan' => $bulan]
    );
    if ($increment) {
      $counter->increment('nomor_terakhir');
      return $counter->nomor_terakhir;
    }
    return $counter->nomor_terakhir + 1;
  }

  // =============================AKHIR - CERAI CONTROLLER==============================


  // =============================AWAL - ILIKITA KAWIN CONTROLLER==============================

  public function arsipIlikitaPawiwahan(Request $request)
  {
    $searchTerm = $request->input('search');

    $query = SuratIlikitaPawiwahan::query();

    if ($searchTerm) {
      $query->where('nomor_surat', 'like', '%' . $searchTerm . '%')
        ->orWhere('purusa_nama', 'like', '%' . $searchTerm . '%')
        ->orWhere('pradana_nama', 'like', '%' . $searchTerm . '%');
    }

    $arsip_surat = $query->orderBy('tanggal_surat', 'desc')->paginate(20);

    return view('pages.surat.ilikita-pawiwahan.arsip_index_ilikita_pawiwahan', [
      'title' => 'Arsip Ilikita Pawiwahan Desa Adat',
      'arsip_surat' => $arsip_surat,
      'searchTerm' => $searchTerm,
    ]);
  }

  public function createIlikitaPawiwahan(Request $request)
  {
    $all_banjar = Banjar::orderBy('nama_banjar')->get();

    $all_banjar = Banjar::orderBy('nama_banjar')->get();
    $bendesa_aktif = BendesaAdat::where('status_jabatan', 'aktif')->get();
    $all_kode_surat = NomorSuratCounterCerai::select('kode_surat')->distinct()->get();

    $nomor_berikutnya = null;
    $nomor_surat_otomatis = '';

    // Hanya generate nomor jika mode otomatis yang dipilih saat reload
    if ($request->query('penomoran') === 'otomatis') {
      $kode_surat_terpilih = $request->query('kode_surat');
      $tanggal_input = $request->query('tahun_surat_input');

      if ($kode_surat_terpilih && $tanggal_input) {
        $tahun_terpilih = Carbon::parse($tanggal_input)->year;
        $bulan_terpilih = Carbon::parse($tanggal_input)->month;
        $nomor_berikutnya = $this->generateNextSuratNumberCerai($kode_surat_terpilih, $tahun_terpilih, $bulan_terpilih);
        $nomor_surat_otomatis = sprintf("%03d/%s/%s/%d", $nomor_berikutnya, $kode_surat_terpilih, $this->number2roman($bulan_terpilih) ,$tahun_terpilih);
      }
    }

    $suami = null;
    if ($request->has('search_suami')) {
      $search = $request->input('search_suami');
      $suami = MasterAdat::with('masterIndividu')
        ->where('nika', 'like', '%' . $search . '%')
        ->orWhereHas('masterIndividu', function ($query) use ($search) {
          $query->where('nama_lengkap', 'like', '%' . $search . '%');
        })->first();
    }

    // Logika Pencarian Data Istri (Pradana)
    $istri = null;
    if ($request->has('search_istri')) {
      $search = $request->input('search_istri');
      $istri = MasterAdat::with('masterIndividu')
        ->where('nika', 'like', '%' . $search . '%')
        ->orWhereHas('masterIndividu', function ($query) use ($search) {
          $query->where('nama_lengkap', 'like', '%' . $search . '%');
        })->first();
    }

    return view('pages.surat.ilikita-pawiwahan.create_ilikita_pawiwahan', [
      'title' => 'Buat Surat Ilikita Pawiwahan',
      'all_banjar' => $all_banjar,
      'bendesa_aktif' => $bendesa_aktif,
      'all_kode_surat' => $all_kode_surat,
      'nomor_surat_otomatis' => $nomor_surat_otomatis,
      'suami' => $suami,
      'istri' => $istri,
    ]);
  }

  public function deleteIlikitaPawiwahan(SuratIlikitaPawiwahan $surat)
  {
//    dd($surat);
    try {
      // Hapus record surat dari database
      $surat->delete();
      // Redirect kembali ke halaman arsip dengan pesan sukses
      return back()
        ->with('success', 'Surat dengan nomor ' . $surat->nomor_surat . ' berhasil dihapus.');
    } catch (\Exception $e) {
      // Jika terjadi error, kembali dengan pesan error
      return back()
        ->with('error', 'Gagal menghapus surat. Error: ' . $e->getMessage());
    }
  }

  public function storeIlikitaPawiwahan(Request $request){
//    dd($request -> all());

    $connectionName = 'db_kependudukan';

//    $request->validate([
//      'penomoran' => 'required|in:otomatis,manual',
//      'nomor_surat_manual' => 'required_if:penomoran,manual|nullable|string|max:255',
//      'kode_surat' => 'required_if:penomoran,otomatis|nullable|string',
//      'tanggal_surat' => 'required|date',
//      // ... validasi lainnya
//    ]);
////
    $nomor_surat_final = null;
//
    $hold_pemuput_karya =  null;
//
//    if (! empty($request->input('pemuput_karya'))){
//      $hold_pemuput_karya = $request->input('pemuput_karya');
//    }

    DB::connection($connectionName)->transaction(function () use ($request, $nomor_surat_final) {
      $path_foto_gandeng = null;
      // Proses gambar yang sudah di-crop (Base64)
      if ($request->filled('cropped_image_gandeng')) {
        $imageData = $request->input('cropped_image_gandeng');
        @list($type, $imageData) = explode(';', $imageData);
        @list(, $imageData) = explode(',', $imageData);

        if ($imageData) {
          $imageData = base64_decode($imageData);
          $imageName = 'photos/ilikita-pawiwahan-gandeng/' . Str::random(40) . '.jpg';
          Storage::disk('public')->put($imageName, $imageData);
          $path_foto_gandeng = 'storage/' . $imageName;
        }
      }

      SuratIlikitaPawiwahan::create([
        'path_foto_gandeng' => $path_foto_gandeng,
        'nomor_surat' => $nomor_surat_final,
        'tanggal_surat' => $request->input('tanggal_surat'),
        'hari_surat' => (Carbon::parse($request->input('tanggal_surat'))->translatedFormat('l')),
        'lokasi_surat_dibuat' => 'Jimbaran',
        'lingkungan_banjar' => Banjar::find($request->input('kode_banjar_lingkungan'))->nama_banjar,
        'purusa_nama' => $request->input('purusa.nama'),
        'purusa_nika' => $request->input('purusa.nika'),
        'purusa_ttl' => $request->input('purusa.tempat_lahir') . ', ' . Carbon::parse($request->input('purusa.tanggal_lahir'))->translatedFormat('d F Y'),
        'purusa_agama' => $request->input('purusa.agama'),
        'purusa_pekerjaan' => $request->input('purusa.pekerjaan'),
        'purusa_alamat' => $request->input('purusa.alamat'),
        'purusa_nama_ayah' => $request->input('purusa.nama_ayah'),
        'purusa_nama_ibu' => $request->input('purusa.nama_ibu'),
        'purusa_alamat_orang_tua' => $request->input('purusa.alamat_orang_tua'),
        'pradana_nama' => $request->input('pradana.nama'),
        'pradana_nika' => $request->input('pradana.nika'),
        'pradana_ttl' => $request->input('pradana.tempat_lahir') . ', ' . Carbon::parse($request->input('pradana.tanggal_lahir'))->translatedFormat('d F Y'),
        'pradana_agama' => $request->input('pradana.agama'),
        'pradana_pekerjaan' => $request->input('pradana.pekerjaan'),
        'pradana_alamat' => $request->input('pradana.alamat'),
        'pradana_nama_ayah' => $request->input('pradana.nama_ayah'),
        'pradana_nama_ibu' => $request->input('pradana.nama_ibu'),
        'pradana_alamat_orang_tua' => $request->input('pradana.alamat_orang_tua'),

        'pemuput_karya' => $request->input('pemuput_karya'),

        'id_bendesa_fk' => $request->input('id_bendesa_fk'),
        'saksi_1' => $request->input('saksi_1'),
        'saksi_2' => $request->input('saksi_2'),

        'kepala_lingkungan' => $request->input('kepala_lingkungan'),
        'kelihan_adat_banjar' => $request->input('kelihan_adat_banjar'),
        'lurah' => $request->input('lurah'),
        'camat' => $request->input('camat'),

        //tambahan

        'tanggal_kawin' => $request->input('tanggal_kawin'),

        'pradana_banjar' => (Banjar::find($request->input('pradana.banjar'))->nama_banjar ?? $request->input('pradana.nama_banjar_custom')),
        'purusa_banjar' => Banjar::find($request->input('purusa.banjar'))->nama_banjar ?? $request->input('purusa.nama_banjar_custom'),
        'pradana_banjar_orangtua' => Banjar::find($request->input('pradana.banjar_ortu'))->nama_banjar ?? $request->input('pradana.nama_banjar_custom_ortu'),
        'purusa_banjar_orangtua' => Banjar::find($request->input('purusa.banjar_ortu'))->nama_banjar ?? $request->input('purusa.nama_banjar_custom_ortu'),
      ]);
    });

//    dd($request->all());

    return redirect(route('surat.ilikita_pawiwahan.create'))->with('success', 'Ilikita pawiwahan berhasil dibuat.');
  }

  public function previewIlikitaPawiwahan($id_surat)
  {
    $query = SuratIlikitaPawiwahan::query();
    $surat = $query->findOrFail($id_surat);
    $bendesa_aktif = BendesaAdat::query();
    $bendesa = $bendesa_aktif->where('status_jabatan', 'aktif')->first();
    $banjar = Banjar::query()->get();
    $nama_kelihan_banjar = $banjar->where('nama_banjar', $surat->lingkungan_banjar)->first()->kelihan_banjar;
    $surat -> load('bendesa');


//    dd($bendesa);

    return view('pages.surat.ilikita-pawiwahan.preview_ilikita_pawiwahan', [
      'title' => 'Preview Ilikita Pawiwahan',
      'surat' => $surat,
      'query' =>  $query,
      'nama_kelihan_banjar' => $nama_kelihan_banjar,
      'bendesa' => $bendesa
    ]);
  }



  // =============================AKHIR - ILIKITA KAWIN CONTROLLER==============================

  // =============================AKHIR - ILIKITA MAUTSAHA CONTROLLER==============================

  public function arsipIlikitaMautsaha(Request $request)
  {
    $searchTerm = $request->input('search');

    $query = SuratIlikitaMautsaha::query();

    if ($searchTerm) {
      $query->where('nomor_surat', 'like', '%' . $searchTerm . '%')
        ->orWhere('purusa_nama', 'like', '%' . $searchTerm . '%')
        ->orWhere('pradana_nama', 'like', '%' . $searchTerm . '%');
    }

    $arsip_surat = $query->orderBy('tanggal_surat', 'desc')->paginate(20);

    return view('pages.surat.ilikita-mautsaha.arsip_index_ilikita_mautsaha', [
      'title' => 'Arsip Ilikita Mautsaha Desa Adat',
      'arsip_surat' => $arsip_surat,
      'searchTerm' => $searchTerm,
    ]);
  }

  public function createIlikitaMautsaha(Request $request)
  {
    $all_banjar = Banjar::orderBy('nama_banjar')->get();

    $all_banjar = Banjar::orderBy('nama_banjar')->get();
    $bendesa_aktif = BendesaAdat::where('status_jabatan', 'aktif')->get();
    $all_kode_surat = NomorSuratCounterIlikitaUtsaha::select('kode_surat')->distinct()->get();

    $nomor_berikutnya = null;
    $nomor_surat_otomatis = '';

    // Hanya generate nomor jika mode otomatis yang dipilih saat reload
    if ($request->query('penomoran') === 'otomatis') {
      $kode_surat_terpilih = $request->query('kode_surat');
      $tanggal_input = $request->query('tahun_surat_input');

      if ($kode_surat_terpilih && $tanggal_input) {
        $tahun_terpilih = Carbon::parse($tanggal_input)->year;
        $bulan_terpilih = Carbon::parse($tanggal_input)->month;
        $nomor_berikutnya = $this->generateNextSuratNumberMautsaha($kode_surat_terpilih, $tahun_terpilih, $bulan_terpilih);
        $nomor_surat_otomatis = sprintf("%03d/%s/%s/%d", $nomor_berikutnya, $kode_surat_terpilih, $this->number2roman($bulan_terpilih) ,$tahun_terpilih);
      }
    }

    $suami = null;
    if ($request->has('search_suami')) {
      $search = $request->input('search_suami');
      $suami = MasterAdat::with('masterIndividu')
        ->where('nika', 'like', '%' . $search . '%')
        ->orWhereHas('masterIndividu', function ($query) use ($search) {
          $query->where('nama_lengkap', 'like', '%' . $search . '%');
        })->first();
    }

    // Logika Pencarian Data Istri (Pradana)
    $istri = null;
    if ($request->has('search_istri')) {
      $search = $request->input('search_istri');
      $istri = MasterAdat::with('masterIndividu')
        ->where('nika', 'like', '%' . $search . '%')
        ->orWhereHas('masterIndividu', function ($query) use ($search) {
          $query->where('nama_lengkap', 'like', '%' . $search . '%');
        })->first();
    }

    $provinces = DB::table('indonesia_provinces')->orderBy('name')->get();
//    return view('pages.alamat.create', [
//      'title' => 'Formulir Alamat Lengkap',
//
//    ]);

    return view('pages.surat.ilikita-mautsaha.create_ilikita_mautsaha', [
      'title' => 'Buat Surat Ilikita Mautsaha',
      'all_banjar' => $all_banjar,
      'bendesa_aktif' => $bendesa_aktif,
      'all_kode_surat' => $all_kode_surat,
      'nomor_surat_otomatis' => $nomor_surat_otomatis,
      'suami' => $suami,
      'istri' => $istri,

      'provinces' => $provinces
    ]);
  }

  public function deleteIlikitaMautsaha(SuratIlikitaMautsaha $surat)
  {
//    dd($surat);
    try {
      // Hapus record surat dari database
      $surat->delete();
      // Redirect kembali ke halaman arsip dengan pesan sukses
      return back()
        ->with('success', 'Surat dengan nomor ' . $surat->nomor_surat . ' berhasil dihapus.');
    } catch (\Exception $e) {
      // Jika terjadi error, kembali dengan pesan error
      return back()
        ->with('error', 'Gagal menghapus surat. Error: ' . $e->getMessage());
    }
  }

  public function storeIlikitaMautsaha(Request $request)
  {
    //holder alamat

//    $alamat_asal[0] = DB::table('indonesia_provinces')->where('code', $request->input('alamat_asal_provinsi'))->first()->name . ', ' ?? null;
//    $alamat_asal[1] = DB::table('indonesia_cities')->where('code', $request->input('alamat_asal_kota'))->first()->name . ', ' ?? null;
//    $alamat_asal[2] = DB::table('indonesia_districts')->where('code', $request->input('alamat_asal_kecamatan'))->first()->name . ', ' ?? null;
//    $alamat_asal[3] = DB::table('indonesia_villages')->where('code', $request->input('alamat_asal_desa'))->first()->name . ', '  ?? null ;
//    $alamat_asal[4] = ($request->input('alamat_asal_alamat_detail') . ', ') ?? null ;
//    $alamat_asal[5] = $request->input('alamat_asal_kode_pos') ?? null ;
//
//    $alamat_adat[0] = DB::table('indonesia_provinces')->where('code', $request->input('alamat_adat_provinsi'))->first()->name ?? null ;
//    $alamat_adat[1] = DB::table('indonesia_cities')->where('code', $request->input('alamat_adat_kota'))->first()->name ?? null ;
//    $alamat_adat[2] = DB::table('indonesia_districts')->where('code', $request->input('alamat_adat_kecamatan'))->first()->name ?? null ;
//    $alamat_adat[3] = DB::table('indonesia_villages')->where('code', $request->input('alamat_adat_desa'))->first()->name ?? null ;
//    $alamat_adat[4] = $request->input('kode_banjar_lingkungan') ?? null ;
//
//    $all_asal =  $alamat_asal[0]  . $alamat_asal[1]  . $alamat_asal[2] . $alamat_asal [3] . $alamat_asal[4] . $alamat_asal [5];
//    $all_adat =  $alamat_adat[0] . ', ' . $alamat_adat[1] . ', ' . $alamat_adat[2] . ', ' . $alamat_adat[3] . ', ' . $alamat_adat[4];
//
//      dd($request->all());

    $connectionName = 'db_kependudukan';

    $request->validate([
      'penomoran' => 'required|in:otomatis,manual',
      'nomor_surat_manual' => 'required_if:penomoran,manual|nullable|string|max:255',
      'kode_surat' => 'required_if:penomoran,otomatis|nullable|string',
      'tanggal_surat' => 'required|date',
      // ... validasi lainnya
    ]);

    $nomor_surat_final = '';

    if ($request->input('penomoran') == 'manual') {
      $nomor_surat_final = $request->input('nomor_surat_manual');

    } else {
      // Jika otomatis, kita gunakan nomor yang sudah disarankan dari form
      $nomor_surat_final = $request->input('nomor_surat_otomatis');
      // Dan kita perlu increment counter di database
      $kode = $request->input('kode_surat');
      $tahun = Carbon::parse($request->input('tanggal_surat'))->year;
      $bulan = Carbon::parse($request->input('tanggal_surat'))->month;
      $this->generateNextSuratNumberMautsaha($kode, $tahun, $bulan, true); // true untuk increment
    }

    if ($request->input('penomoran') == 'manual') {
      $kode_surat_new = explode('/', $request->input('nomor_surat_manual'));
      $kode_surat_store_new = $kode_surat_new[1];

      $toggle_kode_surat_new = NomorSuratCounterIlikitaUtsaha::query()->where('kode_surat', $kode_surat_store_new)->first();

      if (empty($toggle_kode_surat_new)) {
        DB::connection($connectionName)->table('nomor_surat_counter_ilikita_utsaha')->insert(['kode_surat'=> $kode_surat_new[1], 'bulan' => $this->roman2number($kode_surat_new[2]), 'tahun'=>$kode_surat_new[3], 'nomor_terakhir'=>$kode_surat_new[0]]);
      } else {
        DB::connection($connectionName)->table('nomor_surat_counter_ilikita_utsaha')->where('kode_surat', $kode_surat_store_new)->update(['nomor_terakhir'=>$kode_surat_new[0]]);
      }

    }

    $hold_pemuput_karya =  null;

    if (! empty($request->input('pemuput_karya'))){
      $hold_pemuput_karya = $request->input('pemuput_karya');
    }

    DB::connection($connectionName)->transaction(function () use ($request, $nomor_surat_final, $hold_pemuput_karya) {

      $path_pas_foto = null;
      // Proses gambar yang sudah di-crop (Base64)
      if ($request->filled('cropped_pas_foto')) {
        $imageData = $request->input('cropped_pas_foto');
        @list($type, $imageData) = explode(';', $imageData);
        @list(, $imageData) = explode(',', $imageData);

        if ($imageData) {
          $imageData = base64_decode($imageData);
          $imageName = 'photos/ilikita-mautsaha/' . Str::random(40) . '.jpg';
          Storage::disk('public')->put($imageName, $imageData);
          $path_pas_foto = 'storage/' . $imageName;
        }
      }

      $alamat_asal[0] = DB::table('indonesia_provinces')->where('code', $request->input('alamat_asal_provinsi'))->first()->name ?? null;
      $alamat_asal[1] = DB::table('indonesia_cities')->where('code', $request->input('alamat_asal_kota'))->first()->name ?? null;
      $alamat_asal[2] = DB::table('indonesia_districts')->where('code', $request->input('alamat_asal_kecamatan'))->first()->name ?? null;
      $alamat_asal[3] = DB::table('indonesia_villages')->where('code', $request->input('alamat_asal_desa'))->first()->name ?? null ;
      $alamat_asal[4] = $request->input('kode_banjar_lingkungan') ?? null ;
      $alamat_asal[5] = (Banjar::find($request->input('asal.banjar')))->nama_banjar ?? $request->input('asal.nama_banjar_custom') ?? null;
      $alamat_asal[6] = $request->input('alamat_asal_kode_pos') ?? null ;

      $alamat_adat[0] = DB::table('indonesia_provinces')->where('code', $request->input('alamat_adat_provinsi'))->first()->name ?? null ;
      $alamat_adat[1] = DB::table('indonesia_cities')->where('code', $request->input('alamat_adat_kota'))->first()->name ?? null ;
      $alamat_adat[2] = DB::table('indonesia_districts')->where('code', $request->input('alamat_adat_kecamatan'))->first()->name ?? null ;
      $alamat_adat[3] = DB::table('indonesia_villages')->where('code', $request->input('alamat_adat_desa'))->first()->name ?? null ;
      $alamat_adat[4] = (Banjar::find($request->input('adat.banjar')))->nama_banjar  ?? $request->input('adat.nama_banjar_custom') ?? null;
      $alamat_adat[5] = $request->input('alamat_adat_alamat_detail') ?? null ;
      $alamat_adat[6] = $request->input('alamat_adat_kode_pos') ?? null ;

      $alamat_usaha[0] = DB::table('indonesia_provinces')->where('code', $request->input('alamat_usaha_provinsi'))->first()->name ?? null ;
      $alamat_usaha[1] = DB::table('indonesia_cities')->where('code', $request->input('alamat_usaha_kota'))->first()->name ?? null ;
      $alamat_usaha[2] = DB::table('indonesia_districts')->where('code', $request->input('alamat_usaha_kecamatan'))->first()->name ?? null ;
      $alamat_usaha[3] = DB::table('indonesia_villages')->where('code', $request->input('alamat_usaha_desa'))->first()->name ?? null ;
      $alamat_usaha[4] = (Banjar::find($request->input('usaha.banjar')))->nama_banjar  ?? $request->input('usaha.nama_banjar_custom') ?? null;
      $alamat_usaha[5] = $request->input('alamat_usaha_alamat_detail') ?? null ;
      $alamat_usaha[6] = $request->input('alamat_usaha_kode_pos') ?? null ;

      SuratIlikitaMautsaha::create([
        'path_foto_mautsaha' => $path_pas_foto,
        'no_surat_final' => $nomor_surat_final,
        'tanggal_surat' => $request->input('tanggal_surat'),
        'nomor_pararem' => $request->input('nomor_pararem'),
        'nik_nika' => $request->input('nik_nika_pemohon'),
        'nama' => $request->input('nama_pemohon'),
        'ttl' => $request->input('tempat_lahir_pemohon'). ', ' . Carbon::parse($request->input('tanggal_lahir_pemohon'))->translatedFormat('d F Y'),
        'jenis_kelamin' => $request->input('jenis_kelamin_pemohon'),
        'agama' => $request->input('agama_pemohon'),
        'status_krama' => $request->input('status_krama_pemohon'),
        'pekerjaan' => $request->input('pekerjaan_pemohon'),
        'alamat_asal' => $alamat_asal[0] . ', ' . $alamat_asal[1] . ', ' . $alamat_asal[2] . ', ' . $alamat_asal [3] . ', ' . $alamat_asal[4] . ', ' . $alamat_asal [5] . ', ' . $alamat_asal[6],
        'alamat_adat' => $alamat_adat[0] . ', ' . $alamat_adat[1] . ', ' . $alamat_adat[2] . ', ' . $alamat_adat[3] . ', ' . $alamat_adat[4] . ', ' . $alamat_adat[5] . ', ' . $alamat_adat[6],
        'nama_perusahaan' => $request->input('lembaga_usaha'),
        'alamat_usaha' => $alamat_usaha[0] . ', ' . $alamat_usaha[1] . ', ' . $alamat_usaha[2] . ', ' . $alamat_usaha[3] . ', ' . $alamat_usaha[4] . ', ' . $alamat_usaha[5] . ', ' . $alamat_usaha[6],
        'akta_pendirian' => $request->input('akta_usaha'),
        'bidang_usaha' => $request->input('bidang_usaha'),
        'kepala_lingkungan' => $request->input('kepala_lingkungan'),
        'id_bendesa_fk' => $request->input('id_bendesa_fk'),

        //Tambahan
        'tahun_awig' => $request->input('tahun_awig'),
        'tahun_pararem' => $request->input('tahun_pararem'),
      ]);
    });

//    dd($request->all());

    return back()->with('success', 'Surat Pengumuman Kawin berhasil dibuat dengan nomor ' . $nomor_surat_final);
  }

  private function generateNextSuratNumberMautsaha($kode, $tahun, $bulan, $increment = false)
  {
    if (!$kode || !$tahun) return 1;

    $counter = NomorSuratCounterIlikitaUtsaha::firstOrCreate(
      ['kode_surat' => $kode, 'tahun' => $tahun, 'bulan' => $bulan]
    );
    if ($increment) {
      $counter->increment('nomor_terakhir');
      return $counter->nomor_terakhir;
    }
    return $counter->nomor_terakhir + 1;

    // =============================AKHIR - ILIKITA MAUTSAHA CONTROLLER==============================

  }

  public function previewIlikitaMautsaha($id_surat)
  {
    $query = SuratIlikitaMautsaha::query();
    $surat = $query->findOrFail($id_surat);
    $bendesa_aktif = BendesaAdat::query();
    $bendesa = $bendesa_aktif->where('status_jabatan', 'aktif')->first();
    $banjar = Banjar::query()->get();
    $surat -> load('bendesa');

//    dd($surat->lingkungan_banjar, $banjar, $nama_kelihan_banjar);

    return view('pages.surat.ilikita-mautsaha.preview_ilikita_mautsaha', [
      'title' => 'Preview Ilikita Pawiwahan',
      'surat' => $surat,
      'query' =>  $query,
      'bendesa' => $bendesa,
    ]);
  }
}
