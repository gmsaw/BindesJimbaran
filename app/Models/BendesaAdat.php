<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BendesaAdat extends Model
{
  use HasFactory;

  protected $connection = 'db_kependudukan';
  protected $table = 'bendesa_adat';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'id',
    'nama_bendesa',
    'id_individu_fk',
    'periode_mulai',
    'periode_selesai',
    'status_jabatan',


  ];

// Jika Anda berencana mengupdate data bendesa secara massal,
// tambahkan properti $fillable di sini juga.
// protected $fillable = ['nama_bendesa', 'id_individu_fk', ...];

  /**
   * Relasi many-to-one ke master_individu.
   * Seorang bendesa adat adalah seorang individu.
   */
  public function masterIndividu(): BelongsTo
  {
    return $this->belongsTo(MasterIndividu::class, 'id_individu_fk', 'id');
  }

}
