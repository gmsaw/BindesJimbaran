<?php

namespace App\Http\Controllers;

use App\Models\Banjar;
use App\Models\BendesaAdat;
use App\Models\Dadia;
use App\Models\DadiaPenatahan;
use App\Models\KlasifikasiKrama;
use App\Models\Penatahan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\MasterAdat;

use Symfony\Component\HttpFoundation\StreamedResponse;

class MasterDataController extends Controller
{
  /**
   * Menampilkan halaman utama manajemen data master.
   */
//  public function index()
//  {
//    // Ambil semua Dadia dan eager load relasi 'penatahan' serta hitung jumlahnya
//    $dadias = Dadia::with('penatahan')->withCount('penatahan')->orderBy('nama_dadia')->get();
//
//    $data = [
//      'banjars' => Banjar::orderBy('kode_banjar')->get(),
//      'bendesa_adats' => BendesaAdat::orderBy('periode_mulai', 'desc')->get(),
//      'klasifikasi_kramas' => KlasifikasiKrama::orderBy('kode_krama')->get(),
//      'dadia_penatahans' => $dadias, // Ganti dengan data Dadia yang baru
//
//      // Data Statistik
//      'total_dadia' => $dadias->count(),
//      'total_penatahan' => Penatahan::count(),
//    ];
//
//    return view('pages.master_data.index', [
//      'title' => 'Manajemen Data Master',
//      'data' => $data,
//    ]);
//  }
//  public function index()
//  {
//    // Eager load relasi yang lebih dalam untuk efisiensi
//    $dadias = Dadia::with('penatahan.kelihanAdat.masterIndividu')->withCount('penatahan')->orderBy('nama_dadia')->get();
//
//    // Ambil semua krama adat untuk dropdown kelihan
//    $all_krama_adat = MasterAdat::with('masterIndividu')->get();
//
//    $data = [
//      'banjars' => Banjar::orderBy('kode_banjar')->get(),
//      'bendesa_adats' => BendesaAdat::orderBy('periode_mulai', 'desc')->get(),
//      'klasifikasi_kramas' => KlasifikasiKrama::orderBy('kode_krama')->get(),
//      'dadia_penatahans' => $dadias,
//      'all_krama_adat' => $all_krama_adat, // Kirim data krama ke view
//      'total_dadia' => $dadias->count(),
//      'total_penatahan' => Penatahan::count(),
//    ];
//
//    return view('pages.master_data.index', [
//      'title' => 'Manajemen Data Master',
//      'data' => $data,
//    ]);
//  }

//  public function index(Request $request)
//  {
//    $dadias = Dadia::with('penatahan.kelihanAdat.masterIndividu.masterAdat.banjar') // Muat relasi yang lebih dalam
//    ->withCount('penatahan')
//      ->orderBy('nama_dadia')
//      ->get();
//
//    // --- LOGIKA PENCARIAN KELIHAN NATAH ---
//    $kelihanSearchTerm = $request->input('kelihan_search');
//    $all_krama_adat_query = MasterAdat::with('masterIndividu');
//
//    if ($kelihanSearchTerm) {
//      $all_krama_adat_query->whereHas('masterIndividu', function ($query) use ($kelihanSearchTerm) {
//        $query->where('nama_lengkap', 'like', '%' . $kelihanSearchTerm . '%')
//          ->orWhere('nik_nasional', 'like', '%' . $kelihanSearchTerm . '%');
//      })->orWhere('nika', 'like', '%' . $kelihanSearchTerm . '%');
//    }
//
//    $all_krama_adat = $all_krama_adat_query->get();
//
//    $data = [
//      'banjars' => Banjar::orderBy('kode_banjar')->get(),
//      'bendesa_adats' => BendesaAdat::orderBy('periode_mulai', 'desc')->get(),
//      'klasifikasi_kramas' => KlasifikasiKrama::orderBy('kode_krama')->get(),
//      'dadia_penatahans' => $dadias,
//      'all_krama_adat' => $all_krama_adat,
//      'total_dadia' => $dadias->count(),
//      'total_penatahan' => Penatahan::count(),
//    ];
//
//    return view('pages.master_data.index', [
//      'title' => 'Manajemen Data Master',
//      'data' => $data,
//      'kelihanSearchTerm' => $kelihanSearchTerm, // Kirim search term ke view
//    ]);
//  }

  public function index(Request $request)
  {
    $dadias = Dadia::with('penatahan.kelihanAdat.masterIndividu.masterAdat.banjar')
      ->withCount('penatahan')
      ->orderBy('nama_dadia')
      ->get();

    // --- LOGIKA PENCARIAN KELIHAN NATAH ---
    $kelihanSearchTerm = $request->input('kelihan_search');
    // PERBAIKAN: Eager load relasi 'banjar' juga
    $all_krama_adat_query = MasterAdat::with(['masterIndividu', 'banjar']);

    if ($kelihanSearchTerm) {
      $all_krama_adat_query->where(function($query) use ($kelihanSearchTerm) {
        $query->where('nika', 'like', '%' . $kelihanSearchTerm . '%')
          ->orWhereHas('masterIndividu', function ($subQuery) use ($kelihanSearchTerm) {
            $subQuery->where('nama_lengkap', 'like', '%' . $kelihanSearchTerm . '%')
              ->orWhere('nik_nasional', 'like', '%' . $kelihanSearchTerm . '%');
          })
          // PERBAIKAN: Tambahkan pencarian berdasarkan nama banjar
          ->orWhereHas('banjar', function ($subQuery) use ($kelihanSearchTerm) {
            $subQuery->where('nama_banjar', 'like', '%' . $kelihanSearchTerm . '%');
          });
      });
    }

    $all_krama_adat = $all_krama_adat_query->limit(100)->get();

    $data = [
      'banjars' => Banjar::orderBy('kode_banjar')->get(),
      'bendesa_adats' => BendesaAdat::orderBy('periode_mulai', 'desc')->get(),
      'klasifikasi_kramas' => KlasifikasiKrama::orderBy('kode_krama')->get(),
      'dadia_penatahans' => $dadias,
      'all_krama_adat' => $all_krama_adat,
      'total_dadia' => $dadias->count(),
      'total_penatahan' => Penatahan::count(),
    ];

    return view('pages.master_data.index', [
      'title' => 'Manajemen Data Master',
      'data' => $data,
      'kelihanSearchTerm' => $kelihanSearchTerm,
    ]);
  }

//  public function index(Request $request)
//  {
//    $dadias = Dadia::with('penatahan.kelihanAdat.masterIndividu.masterAdat.banjar') // Muat relasi yang lebih dalam
//    ->withCount('penatahan')
//      ->orderBy('nama_dadia')
//      ->get();
//
//    // --- LOGIKA PENCARIAN KELIHAN NATAH ---
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
//    $all_krama_adat = $all_krama_adat_query->limit(100)->get(); // Batasi hasil pencarian agar tidak terlalu berat
//
//    $data = [
//      'banjars' => Banjar::orderBy('kode_banjar')->get(),
//      'bendesa_adats' => BendesaAdat::orderBy('periode_mulai', 'desc')->get(),
//      'klasifikasi_kramas' => KlasifikasiKrama::orderBy('kode_krama')->get(),
//      'dadia_penatahans' => $dadias,
//      'all_krama_adat' => $all_krama_adat,
//      'total_dadia' => $dadias->count(),
//      'total_penatahan' => Penatahan::count(),
//    ];
//
//    return view('pages.master_data.index', [
//      'title' => 'Manajemen Data Master',
//      'data' => $data,
//      'kelihanSearchTerm' => $kelihanSearchTerm, // Kirim search term ke view
//    ]);
//  }

  public function storeDadia(Request $request)
  {
    $request->validate(['nama_dadia' => 'required|string|max:255|unique:db_kependudukan.dadia,nama_dadia']);
    Dadia::create($request->all());
    return back()->with('success', 'Dadia baru berhasil ditambahkan.');
  }

//  public function storePenatahan(Request $request)
//  {
//    $request->validate([
//      'id_dadia_fk' => 'required|exists:db_kependudukan.dadia,id',
//      'nama_penatahan' => 'required|string|max:255',
//      'kelihan_natah' => 'nullable|string|max:255',
//    ]);
//    Penatahan::create($request->all());
//    return back()->with('success', 'Penatahan baru berhasil ditambahkan.');
//  }
//
//  public function updatePenatahan(Request $request, Penatahan $penatahan)
//  {
//    $request->validate([
//      'nama_penatahan' => 'required|string|max:255',
//      'kelihan_natah' => 'nullable|string|max:255',
//    ]);
//    $penatahan->update($request->only(['nama_penatahan', 'kelihan_natah']));
//    return back()->with('success', 'Data Penatahan berhasil diperbarui.');
//  }

  public function storePenatahan(Request $request)
  {
    $request->validate([
      'id_dadia_fk' => 'required|exists:db_kependudukan.dadia,id',
      'nama_penatahan' => 'required|string|max:255',
      'id_kelihan_adat_fk' => 'nullable|exists:db_kependudukan.master_adat,id_identitas_adat',
    ]);
    Penatahan::create($request->all());
    return back()->with('success', 'Penatahan baru berhasil ditambahkan.');
  }

  public function updatePenatahan(Request $request, Penatahan $penatahan)
  {
    $request->validate([
      'nama_penatahan' => 'required|string|max:255',
      'id_kelihan_adat_fk' => 'nullable|exists:db_kependudukan.master_adat,id_identitas_adat',
    ]);
    $penatahan->update($request->only(['nama_penatahan', 'id_kelihan_adat_fk']));
    return back()->with('success', 'Data Penatahan berhasil diperbarui.');
  }

  public function destroyPenatahan(Penatahan $penatahan)
  {
    $penatahan->delete();
    return back()->with('success', 'Data Penatahan berhasil dihapus.');
  }

  public function destroyDadia(Dadia $dadia)
  {
    // Karena foreign key di tabel penatahan tidak ON DELETE CASCADE,
    // kita hapus semua penatahan terkait secara manual terlebih dahulu.
    $dadia->penatahan()->delete();

    // Setelah itu baru hapus dadianya
    $dadia->delete();

    return back()->with('success', 'Dadia beserta semua penatahan di dalamnya berhasil dihapus.');
  }

  //EXPORT DADIA PENATAHAN
  public function exportDadia()
  {
    $dadias = Dadia::with('penatahan.kelihanAdat.masterIndividu', 'penatahan.kelihanAdat.banjar')
      ->orderBy('nama_dadia')
      ->get();

    $fileName = 'data_dadia_dan_penatahan_' . date('Y-m-d') . '.csv';

    $headers = [
      "Content-type"        => "text/csv",
      "Content-Disposition" => "attachment; filename=$fileName",
      "Pragma"              => "no-cache",
      "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
      "Expires"             => "0"
    ];

    $callback = function() use($dadias) {
      $file = fopen('php://output', 'w');

      fputcsv($file, ['No', 'Dadia', 'Penatahan', 'Kelihan Natah', 'Banjar Kelihan', 'Alamat Kelihan']);

      foreach ($dadias as $dadia) {
        if ($dadia->penatahan->isEmpty()) {
          fputcsv($file, ['', $dadia->nama_dadia, '-', '-', '-', '-']);
        } else {
          $penatahanCounter = 1; // Inisialisasi counter untuk setiap dadia
          foreach ($dadia->penatahan as $penatahan) {
            $kelihan = $penatahan->kelihanAdat;

            // Hanya tampilkan nama dadia di baris pertama penatahan
            $namaDadiaUntukBarisIni = ($penatahanCounter == 1) ? $dadia->nama_dadia : '';

            fputcsv($file, [
              $penatahanCounter,
              $namaDadiaUntukBarisIni,
              $penatahan->nama_penatahan,
              $kelihan->masterIndividu->nama_lengkap ?? '-',
              $kelihan->banjar->nama_banjar ?? '-',
              $kelihan->masterIndividu->alamat ?? '-'
            ]);

            $penatahanCounter++; // Increment counter
          }
        }
      }
      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }

  // --- Logika untuk BANJAR ---
  public function updateBanjar(Request $request, Banjar $banjar)
  {
    $request->validate(['kelihan_banjar' => 'nullable|string|max:255']);
    $banjar->update($request->only('kelihan_banjar'));
    return back()->with('success', 'Data Kelihan Banjar berhasil diperbarui.');
  }

  // --- Logika untuk KLASIFIKASI KRAMA ---
  public function storeKlasifikasiKrama(Request $request)
  {
    $request->validate([
      'kode_krama' => 'required|string|max:255|unique:db_kependudukan.klasifikasi_krama,kode_krama',
      'krama' => 'required|string|max:255|unique:db_kependudukan.klasifikasi_krama,krama',
    ]);
    KlasifikasiKrama::create($request->all());
    return back()->with('success', 'Klasifikasi Krama baru berhasil ditambahkan.');
  }

  public function updateKlasifikasiKrama(Request $request, KlasifikasiKrama $klasifikasiKrama)
  {
    $request->validate(['krama' => 'required|string|max:255']);
    $klasifikasiKrama->update($request->only('krama'));
    return back()->with('success', 'Klasifikasi Krama berhasil diperbarui.');
  }

  public function destroyKlasifikasiKrama(KlasifikasiKrama $klasifikasiKrama)
  {
    $klasifikasiKrama->delete();
    return back()->with('success', 'Klasifikasi Krama berhasil dihapus.');
  }

  // --- Logika untuk DADIA PENATAHAN ---
  public function storeDadiaPenatahan(Request $request)
  {
    $request->validate([
      'nama_dadia' => 'required|string|max:255',
      'nama_penatahan' => 'nullable|string|max:255',
      'kelihan_natah' => 'nullable|string|max:255',
    ]);

    { //DADIA
      $custom_dadia = new Dadia();
      $id_ada_dadia = null;
//      dd($request->input('nama_dadia'));
      $dadianame = $request->input('nama_dadia');
      $dadianameDB = Dadia::query()->where('nama_dadia', $request->input('nama_dadia'))->first()->nama_dadia ?? null;
      if( $dadianameDB == $dadianame){
        $id_ada_dadia = Dadia::query()->where('nama_dadia', $request->input('nama_dadia'))->first()->id;
      } else {
        $custom_dadia -> nama_dadia = $request->input('nama_dadia');
        $custom_dadia -> save();
      }

      { //Penatahan
        $custom_penatahan = new Penatahan();
        $custom_penatahan -> id_dadia_fk = $id_ada_dadia ?? $custom_dadia -> id;
        $custom_penatahan -> nama_penatahan = $request->input('nama_penatahan');
        $custom_penatahan -> kelihan_natah = $request->input('kelihan_natah');
        $custom_penatahan -> save();

        { //save all to table dadia_penatahan
          $store_full = new DadiaPenatahan();

          if($id_ada_dadia != null){
            $store_full -> nama_dadia = Dadia::query()->where('id', $id_ada_dadia)->first()->nama_dadia;
          } else {
            $store_full -> nama_dadia = Dadia::query()->where('id', $custom_penatahan->id_dadia_fk)->first()->nama_dadia;
          }
          $store_full -> nama_penatahan = Penatahan::query()->where('id', $custom_penatahan->id)->first()->nama_penatahan;
          $store_full -> kelihan_natah = Penatahan::query()->where('id', $custom_penatahan->id)->first()->kelihan_natah;
          $store_full -> save();
        }

      }

    }
//    Dadia::create($request->input('nama_dadia'));
//
//    Penatahan::create($request->input('nama_penatahan'));

//    DadiaPenatahan::create($request->all());
    return back()->with('success', 'Dadia/Penatahan baru berhasil ditambahkan.');
  }

  public function updateDadiaPenatahan(Request $request, DadiaPenatahan $dadiaPenatahan)
  {
    $request->validate([
      'nama_dadia' => 'required|string|max:255',
      'nama_penatahan' => 'nullable|string|max:255',
      'kelihan_natah' => 'nullable|string|max:255',
    ]);
    $dadiaPenatahan->update($request->all());
    return back()->with('success', 'Data Dadia/Penatahan berhasil diperbarui.');
  }

  public function destroyDadiaPenatahan(DadiaPenatahan $dadiaPenatahan)
  {
    $dadiaPenatahan->delete();
    return back()->with('success', 'Data Dadia/Penatahan berhasil dihapus.');
  }

  // --- Logika untuk BENDESA ADAT ---
  public function storeBendesaAdat(Request $request)
  {
    $request->validate([
      'nama_bendesa' => 'required|string|max:255',
      'periode_mulai' => 'nullable|date',
      'periode_selesai' => 'nullable|date|after_or_equal:periode_mulai',
      'status_jabatan' => 'required|in:aktif,nonaktif,selesai_jabatan',
    ]);
    BendesaAdat::create($request->all());
    return back()->with('success', 'Bendesa Adat baru berhasil ditambahkan.');
  }

  public function updateBendesaAdat(Request $request, BendesaAdat $bendesaAdat)
  {
    $request->validate([
      'nama_bendesa' => 'required|string|max:255',
      'periode_mulai' => 'nullable|date',
      'periode_selesai' => 'nullable|date|after_or_equal:periode_mulai',
      'status_jabatan' => 'required|in:aktif,nonaktif,selesai_jabatan',
    ]);
    $bendesaAdat->update($request->all());
    return back()->with('success', 'Data Bendesa Adat berhasil diperbarui.');
  }

  public function destroyBendesaAdat(BendesaAdat $bendesaAdat)
  {
    $bendesaAdat->delete();
    return back()->with('success', 'Data Bendesa Adat berhasil dihapus.');
  }
}
