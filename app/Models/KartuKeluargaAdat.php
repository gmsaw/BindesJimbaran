<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KartuKeluargaAdat extends Model
{
  use HasFactory;

  protected $connection = 'db_kependudukan';
  protected $table = 'kartu_keluarga_adat';
  protected $primaryKey = 'npk';
  public $incrementing = false;
  protected $keyType = 'string';
  public $timestamps = false;

  protected $fillable = [
    'npk',
    'nkk',
    'kode_klasifikasi_krama_fk',
    'kode_banjar_fk',
    'no_telp',
    'status_adat',
    'alamat'
  ];

  /**
   * Relasi many-to-one ke banjar.
   * Sebuah KK Adat pasti milik satu banjar.
   */
  public function banjar(): BelongsTo
  {
    return $this->belongsTo(Banjar::class, 'kode_banjar_fk', 'kode_banjar');
  }

  /**
   * Relasi many-to-one ke klasifikasi_krama.
   * Sebuah KK Adat memiliki satu klasifikasi krama.
   */
  public function klasifikasiKrama(): BelongsTo
  {
    return $this->belongsTo(KlasifikasiKrama::class, 'kode_klasifikasi_krama_fk', 'kode_krama');
  }

  /**
   * Relasi one-to-many ke anggota_kk_adat.
   * Satu KK Adat memiliki banyak anggota.
   */
  public function anggota(): HasMany
  {
    return $this->hasMany(AnggotaKkAdat::class, 'npk_fk', 'npk');
  }

  /**
   * Mendapatkan record keanggotaan untuk kepala keluarga.
   */
  public function kepalaKeluarga(): HasOne
  {
    return $this->hasOne(AnggotaKkAdat::class, 'npk_fk', 'npk')
      ->where('status_hubungan_adat', 'kepala_keluarga');
  }

  /**
   * Relasi one-to-one ke keterangan_keluarga.
   * Satu KK Adat memiliki satu keterangan keluarga.
   */
  public function keteranganKeluarga(): HasOne
  {
    return $this->hasOne(KeteranganKeluarga::class, 'npk_fk', 'npk');
  }

}


