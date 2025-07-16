<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KlasifikasiKrama extends Model
{
  use HasFactory;

  protected $connection = 'db_kependudukan';
  protected $table = 'klasifikasi_krama';
  protected $primaryKey = 'kode_krama';
  public $incrementing = false;
  protected $keyType = 'string';
  public $timestamps = false;

  protected $fillable = [
    'kode_krama',
    'krama',
  ];

  /**
   * Relasi one-to-many ke kartu_keluarga_adat.
   * Satu klasifikasi krama bisa dimiliki oleh banyak KK Adat.
   */
  public function kartuKeluargaAdat(): HasMany
  {
    return $this->hasMany(KartuKeluargaAdat::class, 'kode_klasifikasi_krama_fk', 'kode_krama');
  }

}
