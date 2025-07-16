<?php

namespace App\Http\Controllers;

use App\Models\KartuKeluargaAdat;
use App\Models\MasterIndividu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Data untuk Stats Cards (Sudah Ada) ---
        $totalPenduduk = MasterIndividu::count();
        $kramaAdat = KartuKeluargaAdat::where('status_adat', 'krama_adat')->count();
        $kramaTamiu = KartuKeluargaAdat::where('status_adat', 'krama_tamiu')->count(); // Data dummy
        $tamiu = KartuKeluargaAdat::where('status_adat', 'tamiu')->count();     // Data dummy

        // --- Data Baru untuk Chart Jenis Kelamin ---
        $genderData = MasterIndividu::select('jenis_kelamin', DB::raw('count(*) as total'))
            ->whereIn('jenis_kelamin', ['laki-laki', 'perempuan']) // Hanya ambil data yang jelas
            ->groupBy('jenis_kelamin')
            ->get();
        
        // Format data agar siap digunakan oleh Chart.js
        $genderChartData = [
            'labels' => $genderData->pluck('jenis_kelamin')->map(fn($item) => ucfirst($item)),
            'data' => $genderData->pluck('total'),
        ];


        // --- Data Baru untuk Chart Populasi per Status Adat ---
        $statusAdatData = KartuKeluargaAdat::select('status_adat', DB::raw('count(*) as total'))
            ->whereNotNull('status_adat')
            ->groupBy('status_adat')
            ->get();
            
        $populationChartData = [
            'labels' => $statusAdatData->pluck('status_adat')->map(fn($item) => ucfirst(str_replace('_', ' ', $item))),
            'data' => $statusAdatData->pluck('total'),
        ];


        return view('pages.dashboard', [
            'totalPenduduk' => $totalPenduduk,
            'kramaAdat' => $kramaAdat,
            'kramaTamiu' => $kramaTamiu,
            'tamiu' => $tamiu,
            'genderChartData' => $genderChartData,
            'populationChartData' => $populationChartData
        ]);
    }
}