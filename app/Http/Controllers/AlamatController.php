<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlamatController extends Controller
{
  // Menggunakan koneksi default Laravel

  public function create()
  {
    $provinces = DB::table('indonesia_provinces')->orderBy('name')->get();
    return view('pages.alamat.create', [
      'title' => 'Formulir Alamat Lengkap',
      'provinces' => $provinces
    ]);
  }

  public function store(Request $request){
    dd($request->all());
  }

  // --- API Endpoints untuk Dropdown Dinamis (SUDAH DISESUAIKAN) ---

  public function getKota(Request $request)
  {
    // Menggunakan 'province_code' dan tabel 'cities'
    $kota = DB::table('indonesia_cities')
      ->where('province_code', $request->province_code)
      ->orderBy('name')
      ->get();
    return response()->json($kota);
  }

  public function getKecamatan(Request $request)
  {
    // Menggunakan 'city_code' dan tabel 'districts'
    $kecamatan = DB::table('indonesia_districts')
      ->where('city_code', $request->city_code)
      ->orderBy('name')
      ->get();
    return response()->json($kecamatan);
  }

  public function getDesa(Request $request)
  {
    // Menggunakan 'district_code' dan tabel 'villages'
    $desa = DB::table('indonesia_villages')
      ->where('district_code', $request->district_code)
      ->orderBy('name')
      ->get();
    return response()->json($desa);
  }
}
