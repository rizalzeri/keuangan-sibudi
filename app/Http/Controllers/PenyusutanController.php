<?php

namespace App\Http\Controllers;

use App\Models\Bdmuk;
use App\Models\Bangunan;
use App\Models\Investasi;
use App\Models\Aktivalain;
use Illuminate\Http\Request;

class PenyusutanController extends Controller
{
    public function index()
    {

        $akumulasi_iven = 0;
        $investasi_iven = 0;

        $asets = Investasi::user()->get();
        $investasi_iven += akumulasiPenyusutanIventasi($asets)['inven'];
        $akumulasi_iven +=  akumulasiPenyusutanIventasi($asets)['akumu'];

        $akumulasi_bangunan = 0;
        $investasi_bangunan = 0;
        $asets = Bangunan::user()->get();
        $investasi_bangunan += akumulasiPenyusutan($asets)['inven'];
        $akumulasi_bangunan +=  akumulasiPenyusutan($asets)['akumu'];

        $akumulasi_bdmuk = 0;
        $investasi_bdmuk = 0;
        $asets = Bdmuk::user()->get();
        $investasi_bdmuk += akumulasiPenyusutan($asets)['inven'];
        $akumulasi_bdmuk +=  akumulasiPenyusutan($asets)['akumu'];

        $asets = Aktivalain::user()->get();

        $akumulasi_lain = 0;
        $investasi_lain = 0;
        $investasi_lain += akumulasiPenyusutan($asets)['inven'];
        $akumulasi_lain +=  akumulasiPenyusutan($asets)['akumu'];

        $total = $akumulasi_iven + $akumulasi_bangunan + $akumulasi_bdmuk + $akumulasi_lain;

        return view('penyusutan.index', [
            'investasi' => $akumulasi_iven,
            'bdmuk' => $akumulasi_bdmuk,
            'bangunan' => $akumulasi_bangunan,
            'aktiva' => $akumulasi_lain,
            'total' => $total
        ]);
    }
}
