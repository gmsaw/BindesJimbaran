<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KartuKeluarga extends Model
{
    protected $table = 'kartu_keluarga';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'no_kk', 'kode_banjar', 'alamat', 'rt', 'rw',
        'kode_pos', 'no_telp', 'status_tinggal'
    ];

    public function banjar(): BelongsTo
    {
        return $this->belongsTo(Banjar::class, 'kode_banjar', 'kode_banjar');
    }

    public function anggotaKeluargas(): HasMany
    {
        return $this->hasMany(AnggotaKeluarga::class, 'no_kk', 'no_kk');
    }
}
