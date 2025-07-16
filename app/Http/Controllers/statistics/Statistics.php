<?php

namespace App\Http\Controllers\statistics;

use App\Http\Controllers\Controller;

class Statistics extends Controller
{
    public function index(){

        return view('pages.statistics.statistics');
    }

    public function kramaAdat(){
        return view('pages.statistics.krama-adat');
    }
}
