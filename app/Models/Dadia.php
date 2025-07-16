<?php
// File: app/Models/Dadia.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dadia extends Model
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
  protected $table = 'dadia';

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
    'nama_dadia',
  ];

  /**
   * Mendefinisikan relasi one-to-many ke Penatahan.
   * Satu Dadia bisa memiliki banyak Penatahan.
   */
  public function penatahan(): HasMany
  {
    return $this->hasMany(Penatahan::class, 'id_dadia_fk', 'id');
  }
}
