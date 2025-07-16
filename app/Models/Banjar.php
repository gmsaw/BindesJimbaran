<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Banjar extends Model
{
    use HasFactory;

    protected $connection = 'db_kependudukan';
    protected $table = 'banjar';
    protected $primaryKey = 'kode_banjar';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
      'kode_banjar',
      'nama_banjar',
      'kelihan_banjar',
      'alamat_sekretariat',


    ];

    /**
     * Relasi one-to-many ke master_adat.
     * Satu banjar memiliki banyak identitas adat.
     */
    public function masterAdat(): HasMany
    {
        return $this->hasMany(MasterAdat::class, 'kode_banjar_fk', 'kode_banjar');
    }

    /**
     * Relasi one-to-many ke kartu_keluarga_adat.
     * Satu banjar memiliki banyak KK Adat.
     */
    public function kartuKeluargaAdat(): HasMany
    {
        return $this->hasMany(KartuKeluargaAdat::class, 'kode_banjar_fk', 'kode_banjar');
    }
}
