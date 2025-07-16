<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MasterIndividu extends Model
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
    protected $table = 'master_individu';

    /**
     * Primary key untuk model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Menonaktifkan timestamps (created_at dan updated_at).
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atribut yang dapat diisi secara massal.
     * PERBAIKAN: Ditambahkan untuk mengatasi MassAssignmentException.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik_nasional',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'pendidikan',
        'pekerjaan',
        'status_perkawinan',
        'tanggal_catat_kawin',
        'kewarganegaraan',
        'status_hubungan',
        'golongan_darah',
        'nama_ayah',
        'nama_ibu',
        'alamat',
        'path_foto',
    ];

    /**
     * Relasi one-to-many ke master_adat.
     * Satu individu bisa memiliki banyak identitas adat (jika pindah banjar).
     */
    public function masterAdat(): HasMany
    {
        return $this->hasMany(MasterAdat::class, 'id_individu_fk', 'id');
    }

    /**
     * Relasi one-to-one ke bendesa_adat.
     * Satu individu bisa menjadi bendesa.
     */
    public function bendesaAdat(): HasOne
    {
        return $this->hasOne(BendesaAdat::class, 'id_individu_fk', 'id');
    }
}
