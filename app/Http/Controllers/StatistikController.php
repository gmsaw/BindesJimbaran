<?php

namespace App\Http\Controllers;

use App\Models\Banjar;
use App\Models\MasterIndividu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StatistikController extends Controller
{
  /**
   * Menampilkan halaman utama statistik.
   */
  public function index(Request $request)
  {
    $statusAdatFilter = $request->input('status_adat');
    $banjars = Banjar::orderBy('kode_banjar')->get();

    // Daftar kolom yang akan kita buat statistiknya
    $kategori = [
      'Jenis Kelamin' => 'jenis_kelamin',
      'Agama' => 'agama',
      'Pekerjaan' => 'pekerjaan',
      'Pendidikan' => 'pendidikan',
      'Status Perkawinan' => 'status_perkawinan',
      'Status Hubungan Keluarga' => 'status_hubungan',
      'Golongan Darah' => 'golongan_darah',
    ];

    $statistik = [];
    foreach ($kategori as $nama => $kolom) {
      $statistik[$nama] = $this->formatDataForView(
        $this->getStatistikData($kolom, $statusAdatFilter),
        $banjars
      );
    }

    // Statistik khusus untuk Status Adat
    $statistik['Status Adat'] = $this->formatDataForView(
      $this->getStatistikData('kka.status_adat', null), // Tidak difilter oleh dirinya sendiri
      $banjars
    );

    return view('pages.statistik.index', [
      'title' => 'Statistik Kependudukan Desa Adat',
      'banjars' => $banjars,
      'statistik' => $statistik,
      'statusAdatFilter' => $statusAdatFilter,
    ]);
  }

  /**
   * Fungsi inti untuk mengambil data statistik dari database.
   */
  private function getStatistikData($column, $statusAdatFilter)
  {
    $query = DB::connection('db_kependudukan')
      ->table('master_individu as mi')
      ->join('master_adat as ma', 'mi.id', '=', 'ma.id_individu_fk')
      ->join('anggota_kk_adat as aka', 'ma.id_identitas_adat', '=', 'aka.id_identitas_adat_fk')
      ->join('kartu_keluarga_adat as kka', 'aka.npk_fk', '=', 'kka.npk');

    if ($statusAdatFilter) {
      $query->where('kka.status_adat', $statusAdatFilter);
    }

    return $query->select($column, 'ma.kode_banjar_fk', DB::raw('count(*) as total'))
      ->groupBy($column, 'ma.kode_banjar_fk')
      ->get();
  }

  /**
   * Memformat data mentah dari DB menjadi array yang siap ditampilkan di view.
   */
  private function formatDataForView($data, $banjars)
  {
    $result = [];
    $grandTotal = 0;

    // Kelompokkan data berdasarkan kategori (misal: 'Hindu', 'Islam', 'Laki-laki')
    $grouped = $data->groupBy(function($item) {
      // Mendapatkan nama kolom pertama yang dipilih (misal: 'agama', 'jenis_kelamin')
      $keys = array_keys((array)$item);
      return $item->{$keys[0]};
    });

    foreach ($grouped as $kategori => $items) {
      $row = [];
      $rowTotal = 0;
      foreach ($banjars as $banjar) {
        $count = $items->firstWhere('kode_banjar_fk', $banjar->kode_banjar)->total ?? 0;
        $row[$banjar->kode_banjar] = $count;
        $rowTotal += $count;
      }
      $row['Total'] = $rowTotal;
      $result[$kategori] = $row;
      $grandTotal += $rowTotal;
    }

    // Hitung total per banjar
    $totalPerBanjar = [];
    foreach($banjars as $banjar) {
      $totalPerBanjar[$banjar->kode_banjar] = array_sum(array_column($result, $banjar->kode_banjar));
    }
    $result['Total Keseluruhan'] = array_merge($totalPerBanjar, ['Total' => $grandTotal]);

    return $result;
  }

  /**
   * Menangani permintaan ekspor data ke CSV.
   */
  public function export(Request $request)
  {
    $statusAdatFilter = $request->input('status_adat');
    $banjars = Banjar::orderBy('kode_banjar')->get();
    $fileName = 'statistik_kependudukan_' . ($statusAdatFilter ?? 'keseluruhan') . '_' . date('Y-m-d') . '.csv';

    $headers = [
      "Content-type"        => "text/csv",
      "Content-Disposition" => "attachment; filename=$fileName",
      "Pragma"              => "no-cache",
      "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
      "Expires"             => "0"
    ];

    $callback = function() use ($banjars, $statusAdatFilter) {
      $file = fopen('php://output', 'w');

      // Header utama
      fputcsv($file, ['Statistik Kependudukan Desa Adat - ' . ucfirst(str_replace('_', ' ', $statusAdatFilter) ?? 'Keseluruhan')]);
      fputcsv($file, []); // Baris kosong

      $kategori = [
        'Status Adat' => 'kka.status_adat',
        'Jenis Kelamin' => 'jenis_kelamin',
        'Agama' => 'agama',
        'Pekerjaan' => 'pekerjaan',
        'Pendidikan' => 'pendidikan',
        'Status Perkawinan' => 'status_perkawinan',
        'Status Hubungan Keluarga' => 'status_hubungan',
        'Golongan Darah' => 'golongan_darah',
      ];

      foreach ($kategori as $nama => $kolom) {
        // Tulis judul kategori
        fputcsv($file, [$nama]);

        // Buat header tabel
        $tableHeader = ['Kategori'];
        foreach ($banjars as $banjar) {
          $tableHeader[] = $banjar->nama_banjar;
        }
        $tableHeader[] = 'Total';
        fputcsv($file, $tableHeader);

        // Ambil dan format data
        $filter = ($nama === 'Status Adat') ? null : $statusAdatFilter;
        $data = $this->formatDataForView($this->getStatistikData($kolom, $filter), $banjars);

        // Tulis data ke CSV
        foreach ($data as $kat => $counts) {
          $row = [$kat];
          foreach ($banjars as $banjar) {
            $row[] = $counts[$banjar->kode_banjar] ?? 0;
          }
          $row[] = $counts['Total'];
          fputcsv($file, $row);
        }
        fputcsv($file, []); // Baris kosong antar tabel
      }

      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }
}
