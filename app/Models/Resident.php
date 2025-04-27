<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Resident extends Model
{
    protected $fillable = [
        'name',
        'nik',
        'no_kk',
        'gender',
        'birth_date',
        'birth_place',
        'religion',
        'education',
        'job',
        'blood_type',
        'marital_status',
        'marital_date',
        'family_status',
        'nationality',
        'father_id',
        'mother_id',
        'status'
    ];

    protected $hidden = [
        // Tidak perlu menyembunyikan karena data sudah terenkripsi
    ];

    /**
     * Mutator untuk enkripsi NIK sebelum disimpan
     */
    public function setNikAttribute($value)
    {
        $this->attributes['nik'] = Crypt::encryptString($value);
    }

    /**
     * Mutator untuk enkripsi No KK sebelum disimpan
     */
    public function setNoKkAttribute($value)
    {
        $this->attributes['no_kk'] = Crypt::encryptString($value);
    }

    /**
     * Accessor untuk dekripsi NIK saat diakses
     */
    public function getNikAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Accessor untuk dekripsi No KK saat diakses
     */
    public function getNoKkAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Method khusus untuk pencarian berdasarkan NIK (menggunakan whereRaw)
     */
    public static function findByEncryptedNik($nik)
    {
        $encryptedNik = Crypt::encryptString($nik);
        return self::whereRaw("nik = ?", [$encryptedNik])->first();
    }

    // ... (relasi dan method lainnya tetap sama)
}