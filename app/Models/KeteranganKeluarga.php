<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeteranganKeluarga extends Model
{
  use HasFactory;

  protected $connection = 'db_kependudukan';
  protected $table = 'keterangan_keluarga';
  protected $primaryKey = 'npk_fk';
  public $incrementing = false;
  protected $keyType = 'string';
  public $timestamps = false;

  /**
   * Atribut yang dapat diisi secara massal.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'npk_fk',
    'id_dadia_penatahan_fk',
    'keterangan_tambahan',
  ];

  /**
   * Relasi one-to-one (inverse) ke kartu_keluarga_adat.
   * Sebuah keterangan keluarga milik satu KK Adat.
   */
  public function kartuKeluargaAdat(): BelongsTo
  {
    return $this->belongsTo(KartuKeluargaAdat::class, 'npk_fk', 'npk');
  }

  /**
   * Relasi many-to-one ke dadia_penatahan.
   * Sebuah keterangan keluarga bisa memiliki satu dadia/penatahan.
   */
  public function Penatahan(): BelongsTo
  {
    return $this->belongsTo(Penatahan::class, 'id_dadia_penatahan_fk', 'id');
  }
}
