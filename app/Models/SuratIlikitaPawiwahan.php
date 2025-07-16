<?php
// File: app/Models/SuratPengumumanKawin.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratIlikitaPawiwahan extends Model
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
  protected $table = 'surat_ilikita_kawin';

  /**
   * Atribut yang dapat diisi secara massal.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'nomor_surat',
    'tanggal_surat',
    'hari_surat',
    'lokasi_surat_dibuat',
    'lingkungan_banjar',
    'purusa_nama',
    'purusa_nika',
    'purusa_ttl',
    'purusa_agama',
    'purusa_pekerjaan',
    'purusa_alamat',
    'purusa_nama_ayah',
    'purusa_nama_ibu',
    'purusa_alamat_orang_tua',
    'pradana_nama',
    'pradana_nika',
    'pradana_ttl',
    'pradana_agama',
    'pradana_pekerjaan',
    'pradana_alamat',
    'pradana_nama_ayah',
    'pradana_nama_ibu',
    'pradana_alamat_orang_tua',
    'pemuput_karya',
    'saksi_1',
    'saksi_2',
    'kepala_lingkungan',
    'kelihan_adat_banjar',
    'lurah',
    'camat',
    'id_bendesa_fk',
    'path_foto_gandeng',

    //tambahan
    'tanggal_kawin',

    'pradana_banjar',
    'purusa_banjar',
    'pradana_banjar_orangtua',
    'purusa_banjar_orangtua',


  ];

  /**
   * Relasi many-to-one ke bendesa_adat.
   * Surat ini ditandatangani oleh satu bendesa.
   */
  public function bendesa(): BelongsTo
  {
    return $this->belongsTo(BendesaAdat::class, 'id_bendesa_fk', 'id');
  }
}
