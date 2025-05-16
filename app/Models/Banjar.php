<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Banjar extends Model
{
    protected $table = 'banjar';
    protected $primaryKey = 'kode_banjar';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'kode_banjar', 'nama_banjar', 'alamat_sekretariat',
        'latitude', 'longitude'
    ];

    public function kartuKeluargas(): HasMany
    {
        return $this->hasMany(KartuKeluarga::class, 'kode_banjar', 'kode_banjar');
    }
}
