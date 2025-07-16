<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DadiaPenatahan extends Model
{
  use HasFactory;

  protected $connection = 'db_kependudukan';
  protected $table = 'dadia_penatahan';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'id',
    'nama_penatahan',
    'nama_dadia',
    'kelihan_natah',


  ];

  /**
   * Relasi one-to-many ke keterangan_keluarga.
   * Satu dadia/penatahan bisa terkait dengan banyak keterangan keluarga.
   */
  public function keteranganKeluarga(): HasMany
  {
    return $this->hasMany(KeteranganKeluarga::class, 'id_dadia_penatahan_fk', 'id');
  }
}
