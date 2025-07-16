<?php

namespace App\Http\Controllers\kependudukan;

use App\Http\Controllers\Controller;
use App\Models\Banjar;
use App\Models\NpkCounter;
use Illuminate\Http\Request;

class NpkPenomoranController extends Controller
{
  public function index()
  {
    $banjars = Banjar::leftJoin('npk_counters', 'banjar.kode_banjar', '=', 'npk_counters.kode_banjar_fk')
      ->select('banjar.*', 'npk_counters.nomor_terakhir')
      ->orderBy('banjar.kode_banjar')
      ->get();

    return view('pages.kependudukan.penomoran_npk.index', [
      'title' => 'Manajemen Penomoran NPK',
      'banjars' => $banjars,
    ]);
  }

  public function update(Request $request)
  {
    $request->validate([
      'kode_banjar_fk' => 'required|string|exists:db_kependudukan.banjar,kode_banjar',
      'nomor_terakhir' => 'required|integer|min:0',
    ]);

    NpkCounter::updateOrCreate(
      ['kode_banjar_fk' => $request->input('kode_banjar_fk')],
      ['nomor_terakhir' => $request->input('nomor_terakhir')]
    );

    return back()->with('success', 'Nomor terakhir untuk banjar berhasil diperbarui.');
  }
}
