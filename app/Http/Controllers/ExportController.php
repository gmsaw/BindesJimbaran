<?php

namespace App\Http\Controllers;

use App\Models\Banjar;
use App\Models\KartuKeluargaAdat;
use App\Models\MasterAdat;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
  /**
   * Menampilkan halaman dengan pilihan filter untuk ekspor.
   */
  public function index()
  {
    $allBanjar = Banjar::orderBy('nama_banjar')->get();

    return view('pages.export.index', [
      'title' => 'Ekspor Data Kependudukan',
      'allBanjar' => $allBanjar,
    ]);
  }

  /**
   * Memproses filter dan men-generate file CSV untuk diunduh.
   */
  public function generateExport(Request $request)
  {
    // Validasi input filter
    $request->validate([
      'jenis_data' => 'required|in:penduduk,kk_adat',
      'banjar' => 'nullable|exists:db_kependudukan.banjar,kode_banjar',
      'status_adat' => 'nullable|string',
    ]);

    $jenisData = $request->input('jenis_data');
    $fileName = $jenisData . '_' . date('Y-m-d_H-i-s') . '.csv';

    $headers = [
      "Content-type"        => "text/csv",
      "Content-Disposition" => "attachment; filename=$fileName",
      "Pragma"              => "no-cache",
      "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
      "Expires"             => "0"
    ];

    // Callback function yang akan dieksekusi untuk membuat file CSV
    $callback = function() use ($request, $jenisData) {
      $file = fopen('php://output', 'w');

      if ($jenisData === 'penduduk') {
        $this->exportDataPenduduk($file, $request);
      } elseif ($jenisData === 'kk_adat') {
        $this->exportDataKkAdat($file, $request);
      }

      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }

  /**
   * Logika untuk mengekspor data perorangan (penduduk).
   */
  private function exportDataPenduduk($file, Request $request)
  {
    // Header untuk file CSV penduduk
    fputcsv($file, [
      'NIKA', 'NIK Nasional', 'Nama Lengkap', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir',
      'Agama', 'Pendidikan', 'Pekerjaan', 'Status Perkawinan', 'Status Hubungan Adat',
      'Banjar Adat', 'Status di Banjar', 'Alamat KTP', 'NPK Saat Ini'
    ]);

    // Query dasar
    $query = MasterAdat::query()->with([
      'masterIndividu',
      'banjar',
      'keanggotaan' => function($q) {
        $q->where('status_keanggotaan', 'aktif')->latest('tanggal_bergabung_kk');
      }
    ]);

    // Terapkan filter
    if ($request->filled('banjar')) {
      $query->where('kode_banjar_fk', $request->input('banjar'));
    }
    if ($request->filled('status_adat')) {
      $query->whereHas('keanggotaan.kartuKeluargaAdat', function ($q) use ($request) {
        $q->where('status_adat', $request->input('status_adat'));
      });
    }
    if ($request->has('hanya_kepala_keluarga')) {
      $query->whereHas('keanggotaan', function ($q) {
        $q->where('status_hubungan_adat', 'kepala_keluarga');
      });
    }

    // Ambil data dalam chunk agar efisien
    $query->chunk(500, function($penduduks) use ($file) {
      foreach ($penduduks as $penduduk) {
        // PERBAIKAN: Ambil record keanggotaan pertama dari koleksi
        $keanggotaan_terkini = $penduduk->keanggotaan->first();

        fputcsv($file, [
          $penduduk->nika,
          $penduduk->masterIndividu?->nik_nasional,
          $penduduk->masterIndividu?->nama_lengkap,
          $penduduk->masterIndividu?->jenis_kelamin,
          $penduduk->masterIndividu?->tempat_lahir,
          $penduduk->masterIndividu?->tanggal_lahir,
          $penduduk->masterIndividu?->agama,
          $penduduk->masterIndividu?->pendidikan,
          $penduduk->masterIndividu?->pekerjaan,
          $penduduk->masterIndividu?->status_perkawinan,
          $keanggotaan_terkini?->status_hubungan_adat, // Gunakan variabel baru
          $penduduk->banjar?->nama_banjar,
          $penduduk->status_di_banjar,
          $penduduk->masterIndividu?->alamat,
          $keanggotaan_terkini?->npk_fk, // Gunakan variabel baru
        ]);
      }
    });
  }

  /**
   * Logika untuk mengekspor data Kartu Keluarga.
   */
  private function exportDataKkAdat($file, Request $request)
  {
    // Header untuk file CSV KK
    fputcsv($file, ['NPK', 'NKK Nasional', 'Nama Kepala Keluarga', 'Banjar Adat', 'Status Adat', 'Alamat KK']);

    $query = KartuKeluargaAdat::query()->with('kepalaKeluarga.masterAdat.masterIndividu', 'banjar');

    // Terapkan filter
    if ($request->filled('banjar')) {
      $query->where('kode_banjar_fk', $request->input('banjar'));
    }
    if ($request->filled('status_adat')) {
      $query->where('status_adat', $request->input('status_adat'));
    }

    $query->chunk(500, function($kks) use ($file) {
      foreach ($kks as $kk) {
        fputcsv($file, [
          $kk->npk,
          $kk->nkk,
          $kk->kepalaKeluarga?->masterAdat?->masterIndividu?->nama_lengkap ?? 'N/A',
          $kk->banjar?->nama_banjar,
          $kk->status_adat,
          $kk->alamat,
        ]);
      }
    });
  }
}
