<?php
// File: app/Models/Penatahan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penatahan extends Model
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
  protected $table = 'penatahan';

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
    'id_dadia_fk',
    'nama_penatahan',
    'kelihan_natah',
    'id_kelihan_adat_fk',
  ];


  /**
   * Mendefinisikan relasi many-to-one ke Dadia.
   * Sebuah Penatahan pasti milik satu Dadia.
   */
  public function dadia(): BelongsTo
  {
    return $this->belongsTo(Dadia::class, 'id_dadia_fk', 'id');
  }

  public function kelihanAdat(): BelongsTo
  {
    return $this->belongsTo(MasterAdat::class, 'id_kelihan_adat_fk', 'id_identitas_adat');
  }
}


