<?php

namespace App\Http\Controllers\kependudukan;

use App\Http\Controllers\Controller;
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

\Carbon\Carbon::setLocale('id');

class PendudukController extends Controller
{
  /**
   * Menampilkan daftar semua penduduk (krama adat) perorangan.
   */
  public function pendudukIndex(Request $request)
  {
    // ... (kode Anda tidak berubah)
  }

  /**
   * Menyiapkan data dan menampilkan halaman untuk mencetak Kartu Tanda Krama (KTP Adat).
   */
  public function printKtpAdat($nika)
  {
    // ... (kode Anda tidak berubah)
  }

  //======================================================//
  //                 MULAI PERUBAHAN DI SINI              //
  //======================================================//

  /**
   * Menampilkan form untuk mengedit data individu perorangan.
   */
  public function pendudukEdit($nika)
  {
    // Cari data krama berdasarkan NIKA, muat relasi yang diperlukan
    $krama = MasterAdat::where('nika', $nika)
      ->with('masterIndividu', 'banjar')
      ->firstOrFail();

    return view('pages.kependudukan.penduduk.edit', [
      'title' => 'Edit Data Krama: ' . $krama->masterIndividu->nama_lengkap,
      'krama' => $krama,
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

    // Aksi untuk menghapus foto
    if ($action === 'delete_photo') {
      if ($individu->path_foto) {
        // Hapus file fisik dari storage
        if (Storage::disk('public')->exists(str_replace('storage/', '', $individu->path_foto))) {
          Storage::disk('public')->delete(str_replace('storage/', '', $individu->path_foto));
        }
        // Update path di database menjadi NULL
        $individu->path_foto = null;
        $individu->save();
        return back()->with('success', 'Foto profil berhasil dihapus.');
      }
      return back()->with('error', 'Tidak ada foto untuk dihapus.');
    }
    // Aksi untuk mengupdate data utama
    elseif ($action === 'update_data') {
      // Validasi data
      $request->validate([
        'nama_lengkap' => ['required', 'string', 'max:100'],
        'nik_nasional' => ['required', 'string', 'max:25', Rule::unique($connectionName . '.master_individu')->ignore($individu->id)],
        'cropped_image' => ['nullable', 'string'], // Validasi untuk data Base64
      ]);

      DB::connection($connectionName)->transaction(function () use ($request, $individu) {
        // Proses gambar yang sudah di-crop (Base64)
        if ($request->filled('cropped_image')) {
          // 1. Hapus foto lama jika ada
          if ($individu->path_foto && Storage::disk('public')->exists(str_replace('storage/', '', $individu->path_foto))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $individu->path_foto));
          }

          // 2. Decode data Base64 dan siapkan nama file
          $imageData = $request->input('cropped_image');
          list($type, $imageData) = explode(';', $imageData);
          list(, $imageData)      = explode(',', $imageData);
          $imageData = base64_decode($imageData);
          $imageName = 'photos/penduduk/' . Str::random(40) . '.jpg';

          // 3. Simpan file baru ke storage
          Storage::disk('public')->put($imageName, $imageData);

          // 4. Simpan path yang web-accessible ke database
          $individu->path_foto = 'storage/' . $imageName;
        }

        // Update data lainnya di tabel master_individu
        $individu->fill($request->except(['path_foto', 'cropped_image', '_token', '_method', 'action']));
        $individu->save();
      });

      return redirect()->route('penduduk.edit', ['nika' => $krama->nika])
        ->with('success', 'Data krama berhasil diperbarui!');
    }

    return back()->with('error', 'Aksi tidak diketahui.');
  }
}
