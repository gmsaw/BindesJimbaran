<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaKkAdat extends Model
{
  use HasFactory;

  protected $connection = 'db_kependudukan';
  protected $table = 'anggota_kk_adat';
  protected $primaryKey = 'id_keanggotaan';
  public $timestamps = false;

  /**
   * Atribut yang dapat diisi secara massal.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'id_identitas_adat_fk',
    'npk_fk',
    'status_hubungan_adat',
    'tanggal_bergabung_kk',
    'tanggal_keluar_kk',
    'status_keanggotaan',

  ];

  /**
   * Relasi many-to-one ke master_adat.
   * Sebuah record keanggotaan terikat pada satu identitas adat.
   */
  public function masterAdat(): BelongsTo
  {
    return $this->belongsTo(MasterAdat::class, 'id_identitas_adat_fk', 'id_identitas_adat');
  }

  /**
   * Relasi many-to-one ke kartu_keluarga_adat.
   * Sebuah record keanggotaan terikat pada satu KK Adat (NPK).
   */
  public function kartuKeluargaAdat(): BelongsTo
  {
    return $this->belongsTo(KartuKeluargaAdat::class, 'npk_fk', 'npk');
  }

}
