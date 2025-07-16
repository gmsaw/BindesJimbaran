<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NpkCounter extends Model
{
  protected $connection = 'db_kependudukan';
  protected $table = 'npk_counters';
  protected $primaryKey = 'kode_banjar_fk';
  public $incrementing = false;
  protected $keyType = 'string';
  public $timestamps = false;

  protected $fillable = ['kode_banjar_fk', 'nomor_terakhir'];
}
