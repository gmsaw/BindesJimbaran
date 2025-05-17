<?php

namespace App\Http\Controllers;

use App\Models\Banjar;
use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

use Barryvdh\DomPDF\Facade\Pdf;

\Carbon\Carbon::setLocale('id');


class KKQController extends Controller
{
    /**
     * Display data for selected banjar
     */
    public function index(Request $request)
    {
        // Get all banjar for dropdown
        $allBanjar = Banjar::orderBy('kode_banjar')->get();

        // Get selected banjar from request or default to B01
        $selectedBanjarCode = $request->input('banjar', 'B01');
        $searchTerm = $request->input('search');

        $printallkk = $request->input('printallkk');

        // Get the selected banjar
        $banjar = Banjar::where('kode_banjar', $selectedBanjarCode)
            ->firstOrFail();

        // Build query for kepala keluarga
        $query = AnggotaKeluarga::with(['kartuKeluarga'])
            ->whereHas('kartuKeluarga', function($query) use ($selectedBanjarCode) {
                $query->where('kode_banjar', $selectedBanjarCode);
            })
//            ->where('status_hubungan', 'Kepala Keluarga')
            ->orderBy('no_urut_anggota');

        // Apply search filter if term exists
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('no_kk', 'like', '%'.$searchTerm.'%')
                    ->orWhere('nama_lengkap', 'like', '%'.$searchTerm.'%')
                    ->orWhere('nik', 'like', '%'.$searchTerm.'%');
            });
        }

        $data = $query->get();

        return view('pages.statistics.KKQ.index', [
            'title' => 'Data Banjar ' . $banjar->nama_banjar,
            'banjar' => $banjar,
            'allBanjar' => $allBanjar,
            'data' => $data,
            'searchTerm' => $searchTerm
        ]);
    }

    public function printCard($nik){
        $queryPenduduk = AnggotaKeluarga::with('kartuKeluarga')
            ->where('nik', $nik)->first();



        $query = kartuKeluarga::where('no_kk', $queryPenduduk->no_kk)
            ->first();

        $banjar = Banjar::where('kode_banjar', $query->kode_banjar)
            ->first();

//        dd($query);

        return view('pages.statistics.KKQ.printCard', [
            'queryPenduduk' => $queryPenduduk,
            'banjar' => $banjar,
            'berlaku' => 5,
        ]);
    }

    public function printCardFamily($no_kk)
    {
        $query = kartuKeluarga::where('no_kk', $no_kk)
            ->get();
        $banjar = Banjar::where('kode_banjar', $query->first()->kode_banjar)
            ->get();

        $queryKK = AnggotaKeluarga::with('kartuKeluarga')
            ->where('no_kk', $no_kk);

        $data = $queryKK->get();

//        dd($data->first());


        return view('pages.statistics.KKQ.printFam', [
            'banjar' => $banjar,
            'data' => $data,
            'query' => $query,
            'berlaku' => 5,
            'kepala' => 0,
        ]);

    }


}




//
//namespace App\Http\Controllers;
//
//use App\Models\Banjar;
//use App\Models\AnggotaKeluarga;
//use Illuminate\Http\Request;
//
//
//namespace App\Http\Controllers;
//
//use App\Models\Banjar;
//use App\Models\AnggotaKeluarga;
//use Illuminate\Http\Request;
//
//class BanjarController extends Controller
//{
//    /**
//     * Display data for selected banjar (dynamic)
//     */
//    public function index(Request $request)
//    {
//        // Get all banjar for dropdown
//        $allBanjar = Banjar::all();
//
//        // Get selected banjar from request or default to B01
//        $selectedBanjarCode = $request->input('banjar', 'B01');
//        $searchTerm = $request->input('search');
//
//        // Get the selected banjar with relationships
//        $banjar = Banjar::with(['kartuKeluargas'])
//            ->where('kode_banjar', $selectedBanjarCode)
//            ->firstOrFail();
//
//        // Build query for kepala keluarga
//        $query = AnggotaKeluarga::whereHas('kartuKeluarga', function ($query) use ($selectedBanjarCode) {
//            $query->where('kode_banjar', $selectedBanjarCode)
//                ->where('status_hubungan', 'Kepala Keluarga');
//        })
//            ->orderBy('no_urut_anggota');
//
//        // Apply search filter if term exists
//        if ($searchTerm) {
//            $query->where(function ($q) use ($searchTerm) {
//                $q->where('no_kk', 'like', '%' . $searchTerm . '%')
//                    ->orWhere('nama_lengkap', 'like', '%' . $searchTerm . '%')
//                    ->orWhere('nik', 'like', '%' . $searchTerm . '%');
//            });
//        }
//
//        $kepalaKeluarga = $query->get();
//
//        return view('pages.statistics.banjar.index', [
//            'title' => 'Data Banjar ' . $banjar->nama_banjar,
//            'banjar' => $banjar,
//            'allBanjar' => $allBanjar,
//            'data' => $kepalaKeluarga,
//            'searchTerm' => $searchTerm
//        ]);
//    }
//
//    /**
//     * Print card for individual
//     */
//    public function printCard($nik)
//    {
//        $data = AnggotaKeluarga::with('kartuKeluarga.banjar')
//            ->findOrFail($nik);
//
//        return view('pages.statistics.banjar.card', [
//            'title' => 'Cetak Kartu - ' . $data->nama_lengkap,
//            'data' => $data
//        ]);
//    }
//}
//
////class BanjarController extends Controller
////{
////    public function index()
////    {
////
////    }
////    /**
////     * Display Banjar B01 data
////     */
////    public function showBanjarB01()
////    {
////        $banjar = Banjar::with(['kartuKeluargas.anggotaKeluargas'])
////            ->where('kode_banjar', 'B01')
////            ->firstOrFail();
////
////        $kepalaKeluarga = AnggotaKeluarga::whereHas('kartuKeluarga', function($query) {
////            $query->where('kode_banjar', 'B01')->where('status_hubungan', 'Kepala Keluarga');
////        })
////            ->orderBy('no_urut_anggota')
////            ->get();
////
////        return view('pages.statistics.banjar.b01', [
////            'title' => 'Data Banjar ' . $banjar->nama_banjar,
////            'banjar' => $banjar,
////            'data' => $kepalaKeluarga
////        ]);
////    }
////
////    /**
////     * Print card for individual
////     */
////    public function printCard($nik)
////    {
////        $data = AnggotaKeluarga::with('kartuKeluarga.banjar')
////            ->findOrFail($nik);
////
////        return view('pages.statistics.banjar.card', [
////            'title' => 'Cetak Kartu - ' . $data->nama_lengkap,
////            'data' => $data
////        ]);
////    }
////}
