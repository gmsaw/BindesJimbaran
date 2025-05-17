<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaKeluarga extends Model
{
    protected $table = 'anggota_keluarga';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'nik', 'no_kk', 'npk', 'no_urut_anggota', 'nama_lengkap',
        'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'agama',
        'pendidikan', 'pekerjaan', 'status_perkawinan', 'tanggal_catat_kawin',
        'status_hubungan', 'kewarganegaraan', 'no_akta_lahir', 'no_akta_kawin',
        'no_akta_cerai', 'golongan_darah', 'no_kartu_kesehatan', 'nama_ayah',
        'nama_ibu', 'keterangan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_catat_kawin' => 'date'
    ];

    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class, 'no_kk', 'no_kk');
    }
}
