<?php

namespace App\Http\Controllers;

class cetakkartu
{
  public function index(){
      return view('pages.cetak-kartu.cetak-kartu');
  }

  public function opsi(){
    return view('pages.cetak-kartu.cetak-kartu-opsi');
  }

  public function opsiKramaTamiu(){
    return view('pages.cetak-kartu.cetak-kartu-opsi-krama-tamiu');
  }

  public function opsiTamiu(){
    return view('pages.cetak-kartu.cetak-kartu-opsi-tamiu');
  }
}
