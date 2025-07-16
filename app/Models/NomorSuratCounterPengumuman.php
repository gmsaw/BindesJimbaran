<?php
// File: app/Models/NomorSuratCounter.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NomorSuratCounterPengumuman extends Model
{
  use HasFactory;

  protected $connection = 'db_kependudukan';
  protected $table = 'nomor_surat_counter_pengumuman';
  protected $primaryKey = ['kode_surat', 'tahun']; // Kunci utama gabungan
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'kode_surat',
    'tahun',
    'bulan',
    'nomor_terakhir',
  ];

  /**
   * Override sebuah method inti untuk menangani composite primary keys.
   * Ini memberitahu Eloquent bagaimana cara membangun 'WHERE' clause saat menyimpan/memperbarui record.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  protected function setKeysForSaveQuery($query)
  {
    $keys = $this->getKeyName();
    foreach ($keys as $key) {
      // Menambahkan 'where' clause untuk setiap bagian dari kunci utama.
      // Contoh: WHERE `kode_surat` = 'IW-DAJ' AND `tahun` = 2025
      $query->where($key, '=', $this->getAttribute($key));
    }

    return $query;
  }
}
