<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterAdat extends Model
{
  use HasFactory;

  protected $connection = 'db_kependudukan';
  protected $table = 'master_adat';
  protected $primaryKey = 'id_identitas_adat';
  public $timestamps = false;

  /**
   * Atribut yang dapat diisi secara massal.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'nika',
    'status_di_banjar',
  ];

  /**
   * Relasi many-to-one ke master_individu.
   * Sebuah identitas adat pasti milik satu individu.
   */
  public function masterIndividu(): BelongsTo
  {
    return $this->belongsTo(MasterIndividu::class, 'id_individu_fk', 'id');
  }

  /**
   * Relasi many-to-one ke banjar.
   * Sebuah identitas adat terikat pada satu banjar.
   */
  public function banjar(): BelongsTo
  {
    return $this->belongsTo(Banjar::class, 'kode_banjar_fk', 'kode_banjar');
  }

  /**
   * Relasi one-to-many ke anggota_kk_adat.
   * Satu identitas adat bisa memiliki banyak catatan keanggotaan KK (jika pindah KK dalam banjar yang sama).
   */
  public function keanggotaan(): HasMany
  {
    return $this->hasMany(AnggotaKkAdat::class, 'id_identitas_adat_fk', 'id_identitas_adat');
  }
}
