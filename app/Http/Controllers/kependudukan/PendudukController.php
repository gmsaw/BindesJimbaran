<?php

namespace App\Http\Controllers\kependudukan;

use App\Http\Controllers\Controller;
use App\Models\AddForKramaTamiu;
use App\Models\AddForTamiu;
use App\Models\AnggotaKkAdat;
use App\Models\Banjar;
use App\Models\BendesaAdat;
use App\Models\DadiaPenatahan;
use App\Models\KartuKeluargaAdat;
use App\Models\KeteranganKeluarga;
use App\Models\KlasifikasiKrama;
use App\Models\MasterAdat;
use App\Models\MasterIndividu;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\UidValueResolver;

\Carbon\Carbon::setLocale('id');
class PendudukController extends Controller
{
    /**
     * Menampilkan daftar semua penduduk (krama adat) perorangan.
     * Ini adalah dasar untuk fitur Kartu Tanda Krama.
     */

  // app/Http/Controllers/kependudukan/PendudukController.php

    public function pendudukIndex(Request $request)
    {
      // 1. Dapatkan semua banjar untuk dropdown filter
      $allBanjar = Banjar::orderBy('kode_banjar')->get();

      // 2. Dapatkan semua parameter filter dari request
      $selectedBanjarCode = $request->input('banjar');
      $searchTerm = $request->input('search');
      $krama_request = $request->input('krama_request'); // <-- Ambil parameter baru

      // 3. Bangun query utama dari model MasterAdat
      $query = MasterAdat::query()->with(['masterIndividu', 'banjar']);

      // 4. Terapkan filter berdasarkan banjar jika dipilih
      if ($selectedBanjarCode) {
        $query->where('kode_banjar_fk', $selectedBanjarCode);
      }

      // 5. Terapkan filter berdasarkan status adat jika dipilih
      if ($krama_request) {
        // Ini adalah logika kuncinya.
        // Ia akan memfilter MasterAdat yang memiliki relasi ke KartuKeluargaAdat
        // di mana status_adat-nya cocok dengan yang diminta.
        $query->whereHas('keanggotaan.kartuKeluargaAdat', function ($q) use ($krama_request) {
          $q->where('status_adat', $krama_request);
        });
      }

      // 6. Terapkan filter pencarian jika ada
      if ($searchTerm) {
        $query->where(function ($q) use ($searchTerm) {
          $q->where('nika', 'like', '%' . $searchTerm . '%')
            ->orWhereHas('masterIndividu', function ($subQuery) use ($searchTerm) {
              $subQuery->where('nama_lengkap', 'like', '%' . $searchTerm . '%')
                ->orWhere('nik_nasional', 'like', '%' . $searchTerm . '%');
            });
        });
      }

      // 7. Urutkan dan eksekusi dengan paginasi
      // Kita tidak perlu join manual lagi karena whereHas sudah menangani relasinya
      $daftar_penduduk = $query->orderBy('kode_banjar_fk')->paginate(20);

      // 8. Kirim semua data yang diperlukan ke view baru
      return view('pages.kependudukan.penduduk.index', [
        'title' => 'Data Krama Adat (Perorangan)',
        'allBanjar' => $allBanjar,
        'selectedBanjar' => $selectedBanjarCode,
        'daftar_penduduk' => $daftar_penduduk,
        'searchTerm' => $searchTerm,
        'krama_request' => $krama_request, // <-- Kirim parameter ini ke view
      ]);
    }
//    public function pendudukIndex(Request $request)
//    {
//      // 1. Dapatkan semua banjar untuk dropdown filter
//      $allBanjar = Banjar::orderBy('kode_banjar')->get();
//
//      // 2. Dapatkan kode banjar yang dipilih dari request, atau ambil semua jika tidak ada
//      $selectedBanjarCode = $request->input('banjar');
//      $searchTerm = $request->input('search');
//
//      $krama_request = $request->input('krama_request');
//
//      // 3. Bangun query utama dari model MasterAdat
//      // Model ini merepresentasikan setiap individu dalam konteks adatnya
//      $query = MasterAdat::query()
//        ->with(['masterIndividu', 'banjar', 'keanggotaan']); // Eager load data individu dan banjar
//
//      // 4. Terapkan filter berdasarkan banjar jika dipilih
//      if ($selectedBanjarCode) {
//        $query->where('kode_banjar_fk', $selectedBanjarCode);
//      }
//
//      // 5. Terapkan filter pencarian jika ada
//      if ($searchTerm) {
//        $query->where(function ($q) use ($searchTerm) {
//          // Cari di tabel master_adat
//          $q->where('nika', 'like', '%' . $searchTerm . '%')
//            // Atau cari di tabel master_individu melalui relasi
//            ->orWhereHas('masterIndividu', function ($subQuery) use ($searchTerm) {
//              $subQuery->where('nama_lengkap', 'like', '%' . $searchTerm . '%')
//                ->orWhere('nik_nasional', 'like', '%' . $searchTerm . '%');
//            });
//        });
//      }

//
//
//      // 6. Urutkan berdasarkan kode banjar, lalu nama, dan eksekusi dengan paginasi
//      $daftar_penduduk = $query->join('master_individu', 'master_adat.id_individu_fk', '=', 'master_individu.id')
//        ->orderBy('master_adat.kode_banjar_fk')
//        ->orderBy('master_individu.nama_lengkap')
//        ->select('master_adat.*') // Pastikan hanya kolom dari master_adat yang dipilih untuk menghindari konflik 'id'
//        ->paginate(20);
//
//      // 7. Kirim semua data yang diperlukan ke view baru
//      return view('pages.kependudukan.penduduk.index', [
//        'title' => 'Data Krama Adat (Perorangan)',
//        'allBanjar' => $allBanjar,
//        'selectedBanjar' => $selectedBanjarCode,
//        'daftar_penduduk' => $daftar_penduduk,
//        'searchTerm' => $searchTerm,
//        'krama_request' => $krama_request,
//      ]);
//    }

    /**
     * Menyiapkan data dan menampilkan halaman untuk mencetak Kartu Tanda Krama (KTP Adat).
     *
     *
     */

  public function printKtpAdat(Request $request, $nika) // Parameter diubah menjadi $nika
  {
//    dd($request->all());
    // 1. Cari data identitas adat berdasarkan NIKA dan eager load semua relasi yang dibutuhkan.
    $krama = MasterAdat::where('nika', $nika)
      ->with([
        'masterIndividu', // Data lengkap dari master_individu
        'banjar',         // Data banjar dari master_adat
        'keanggotaan',
      ])
      ->firstOrFail(); // Menggunakan firstOrFail() karena NIKA unik

    // 2. Ambil data Bendesa Adat yang sedang aktif.
    $bendesa_adat_aktif = BendesaAdat::where('status_jabatan', 'aktif')->first();

    $adderKramaTamiu = AddForKramaTamiu::query()->where('master_individu_fk', '=', $krama->masterIndividu->id)->first();
    $adderTamiu = AddForTamiu::query()->where('master_individu_fk', '=', $krama->masterIndividu->id)->first();

    $keluarga_krama = KartuKeluargaAdat::where('npk', '=',$krama->keanggotaan->first()->npk_fk)->first();
    $klasifikasi_krama = KlasifikasiKrama::where('kode_krama', '=', $keluarga_krama->kode_klasifikasi_krama_fk)->first()->krama ?? null;

//    dd($krama->keanggotaan, $keluarga_krama, $klasifikasi_krama);

//    dd($krama, $adderKramaTamiu, $adderTamiu);

    // 3. Kirim semua data yang diperlukan ke view baru untuk dicetak.

    $krama_request = $request->input('krama_request');

    if($krama_request == 'krama_adat'){
      return view('pages.kependudukan.penduduk.print.print-nika', [
        'title' => 'Cetak Kartu Tanda Krama',
        'krama' => $krama,
        'bendesa_adat' => $bendesa_adat_aktif,
        'berlaku' => 5, // Contoh masa berlaku
        'klasifikasi_krama' =>$klasifikasi_krama,
      ]);
    } else if($krama_request == 'krama_tamiu'){
      return view('pages.kependudukan.penduduk.print.print-nika-krama-tamiu', [
        'title' => 'Cetak Kartu Tanda Krama',
        'krama' => $krama,
        'bendesa_adat' => $bendesa_adat_aktif,
        'berlaku' => 5, // Contoh masa berlaku
        'klasifikasi_krama' => $klasifikasi_krama,
        'adder' => $adderKramaTamiu,
      ]);
    } else if ($krama_request == 'tamiu'){
      return view('pages.kependudukan.penduduk.print.print-nika-tamiu', [
        'title' => 'Cetak Kartu Tanda Krama',
        'krama' => $krama,
        'bendesa_adat' => $bendesa_adat_aktif,
        'berlaku' => 5, // Contoh masa berlaku
        'klasifikasi_krama' =>$klasifikasi_krama,
        'adder' => $adderTamiu
      ]);
    }


  }
//    public function printKtpAdat($nika) // Parameter diubah menjadi $nika
//    {
//      // 1. Cari data identitas adat berdasarkan NIKA dan eager load semua relasi yang dibutuhkan.
//      $krama = MasterAdat::where('nika', $nika)
//        ->with([
//          'masterIndividu', // Data lengkap dari master_individu
//          'banjar',         // Data banjar dari master_adat
//        ])
//        ->firstOrFail(); // Menggunakan firstOrFail() karena NIKA unik
//
//      // 2. Ambil data Bendesa Adat yang sedang aktif.
//      $bendesa_adat_aktif = BendesaAdat::where('status_jabatan', 'aktif')->first();
//
//      // 3. Kirim semua data yang diperlukan ke view baru untuk dicetak.
//      return view('pages.kependudukan.penduduk.print.print-nika', [
//        'title' => 'Cetak Kartu Tanda Krama',
//        'krama' => $krama,
//        'bendesa_adat' => $bendesa_adat_aktif,
//        'berlaku' => 5, // Contoh masa berlaku
//      ]);
//    }
  /**
   * Menampilkan form untuk mengedit data individu perorangan.
   */
  public function pendudukEdit(Request $request, $nika )
  {
    $krama_request = $request->input('krama_request');
    $krama = MasterAdat::where('nika', $nika)->with('masterIndividu', 'banjar')->firstOrFail();
    return view('pages.kependudukan.penduduk.edit', [
      'title' => 'Edit Data Krama: ' . $krama->masterIndividu->nama_lengkap,
      'krama' => $krama,
      'krama_request' => $krama_request,
    ]);
  }

  /**
   * Memproses pembaruan data individu perorangan.
   */
  public function pendudukUpdate(Request $request, $nika)
  {
    $action = $request->input('action');
    $connectionName = 'db_kependudukan';
    $krama = MasterAdat::where('nika', $nika)->firstOrFail();
    $individu = $krama->masterIndividu;

    if ($action === 'delete_photo') {
      if ($individu->path_foto) {
        if (Storage::disk('public')->exists(str_replace('storage/', '', $individu->path_foto))) {
          Storage::disk('public')->delete(str_replace('storage/', '', $individu->path_foto));
        }
        $individu->path_foto = null;
        $individu->save();
        return back()->with('success', 'Foto profil berhasil dihapus.');
      }
      return back()->with('error', 'Tidak ada foto untuk dihapus.');

    } elseif ($action === 'update_data') {

      $request->validate([
        'nama_lengkap' => ['required', 'string', 'max:100'],
        'nik_nasional' => ['required', 'string', 'max:25', Rule::unique($connectionName . '.master_individu')->ignore($individu->id)],
        'cropped_image' => ['nullable', 'string'], // Validasi untuk data Base64
      ]);

      DB::connection($connectionName)->transaction(function () use ($request, $individu) {
        // Proses gambar yang sudah di-crop (Base64)
        if ($request->filled('cropped_image')) {
          if ($individu->path_foto && Storage::disk('public')->exists(str_replace('storage/', '', $individu->path_foto))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $individu->path_foto));
          }

          $imageData = $request->input('cropped_image');
          @list($type, $imageData) = explode(';', $imageData);
          @list(, $imageData)      = explode(',', $imageData);

          if ($imageData) {
            $imageData = base64_decode($imageData);
            $imageName = 'photos/penduduk/' . Str::random(40) . '.jpg';

            Storage::disk('public')->put($imageName, $imageData);
            $individu->path_foto = 'storage/' . $imageName;
          }
        }

        // Update data lainnya di tabel master_individu
        // Pastikan model Anda memiliki properti $fillable yang sesuai!
        $individu->fill($request->except(['path_foto', 'cropped_image', '_token', '_method', 'action']));
        $individu->save();
      });

      return redirect()->route('penduduk.edit', ['nika' => $krama->nika])
        ->with('success', 'Data krama berhasil diperbarui!');
    }

    return back()->with('error', 'Aksi tidak diketahui.');
  }

}
