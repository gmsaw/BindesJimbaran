<?php

namespace App\Http\Controllers\statistics;

use App\Http\Controllers\Controller;
use App\Models\Resident;

class ResidentController extends Controller
{
    public function index(){
        $residents = Resident::all();

        return view('pages.database.index', [
            'residents' => $residents,
        ]);
    }
}
