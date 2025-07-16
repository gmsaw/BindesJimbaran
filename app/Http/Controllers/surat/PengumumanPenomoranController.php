<?php

namespace App\Http\Controllers\surat;

use App\Http\Controllers\Controller;
use App\Models\NomorSuratCounterPengumuman;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengumumanPenomoranController extends Controller
{
  /**
   * Menampilkan halaman manajemen penomoran.
   */
  public function index()
  {
    // Anda bisa memfilter kode surat di sini jika perlu,
    // misalnya hanya yang mengandung 'SPK' (Surat Pengumuman Kawin)
    $counters = NomorSuratCounterPengumuman::orderBy('kode_surat')->orderBy('tahun', 'desc')->paginate(15);

    return view('pages.surat.penomoran.pengumuman_index', [
      'title' => 'Manajemen Penomoran Surat Pengumuman',
      'counters' => $counters,
    ]);
  }

  /**
   * Menyimpan kode surat baru.
   */
  public function store(Request $request)
  {
    $connectionName = 'db_kependudukan';

    $request->validate([
      'kode_surat' => [
        'required', 'string', 'max:20',
        Rule::unique($connectionName . '.nomor_surat_counter_pengumuman')->where(function ($query) use ($request) {
          return $query->where('tahun', $request->input('tahun'));
        }),
      ],
      'tahun' => 'required|digits:4',
      'nomor_terakhir' => 'required|integer|min:0',
    ]);

    NomorSuratCounterPengumuman::create($request->all());

    return back()->with('success', 'Kode surat baru berhasil ditambahkan.');
  }

  /**
   * Mengupdate nomor urut terakhir.
   */
  public function update(Request $request)
  {
    $request->validate([
      'kode_surat' => 'required|string',
      'tahun' => 'required|digits:4',
      'nomor_terakhir' => 'required|integer|min:0',
    ]);

    $counter = NomorSuratCounterPengumuman::where('kode_surat', $request->input('kode_surat'))
      ->where('tahun', $request->input('tahun'))
      ->firstOrFail();

    $counter->nomor_terakhir = $request->input('nomor_terakhir');
    $counter->save();

    return back()->with('success', 'Nomor urut berhasil diperbarui.');
  }

  /**
   * Menghapus sebuah counter penomoran.
   */
  public function destroy(Request $request)
  {
    $request->validate([
      'kode_surat' => 'required|string',
      'tahun' => 'required|digits:4',
    ]);

    $counter = NomorSuratCounterPengumuman::where('kode_surat', $request->input('kode_surat'))
      ->where('tahun', $request->input('tahun'))
      ->firstOrFail();

    $counter->delete();

    return back()->with('success', 'Kode surat berhasil dihapus.');
  }
}
