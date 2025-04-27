<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index(){
        $residents = Resident::all();

        return view('pages.database.index', [
            'residents' => $residents,
        ]);
    }
}
