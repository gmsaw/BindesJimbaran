<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratIlikitaMautsaha extends Model
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
  protected $table = 'surat_ilikita_mautsaha';

  /**
   * Atribut yang dapat diisi secara massal.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'no_surat_final',
    'tanggal_surat',
    'nomor_pararem',
    'nama',
    'nik_nika',
    'ttl',
    'jenis_kelamin',
    'agama',
    'status_krama',
    'pekerjaan',
    'alamat_asal',
    'alamat_adat',
    'nama_perusahaan',
    'akta_pendirian',
    'bidang_usaha',
    'alamat_usaha',
    'id_bendesa_fk',
    'path_foto_mautsaha',
    //tambahan
    'tahun_awig',
    'tahun_pararem',
  ];

  /**
   * Mendefinisikan relasi ke Bendesa Adat yang menandatangani surat.
   */
  public function bendesa(): BelongsTo
  {
    return $this->belongsTo(BendesaAdat::class, 'id_bendesa_fk', 'id');
  }
}
