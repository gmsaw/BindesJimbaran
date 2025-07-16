<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddForKramaTamiu extends Model
{
  use HasFactory;

  /**
   * Nama koneksi database yang digunakan oleh model.
   *
   * @var string
   */
  protected $connection = 'db_kependudukan';

  /**
   * Nama tabel yang terkait dengan model.
   *
   * @var string
   */
  protected $table = 'add_for_krama_tamiu';

  /**
   * Menonaktifkan timestamps (created_at dan updated_at).
   *
   * @var bool
   */
  public $timestamps = false;

  /**
   * Atribut yang dapat diisi secara massal.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'master_individu_fk',
    'desa_adat',
    'kecamatan',
    'kabupaten',
  ];

  /**
   * Mendefinisikan relasi many-to-one ke MasterIndividu.
   */
  public function masterIndividu(): BelongsTo
  {
    return $this->belongsTo(MasterIndividu::class, 'master_individu_fk', 'id');
  }
}
