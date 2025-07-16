<?php

namespace App\Http\Controllers\aset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AsetController extends Controller
{
    public function index() {
        return view('pages.cetak-kartu.index', [

        ]);
    }
    public function storeTanah(Request $request)
    {
        return "Hello";
    }

    public function storeBarang(Request $request)
    {
        return "Hello";
    }
}
