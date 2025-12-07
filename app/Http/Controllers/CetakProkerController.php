<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Ekuit;
use App\Models\Modal;

use App\Models\Rasio;
use App\Models\Profil;
use App\Models\Proker;
use App\Models\Target;
use App\Models\Alokasi;
use App\Models\Program;
use App\Models\Kerjasama;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakProkerController extends Controller
{
    public function exportPdf()
    {

        $profil = Profil::user()->get()->first();
        $neraca = neraca();
        $units = Unit::user()->get();
        $modals = Modal::user()->get();
        $labaRugi = labaRugi(session('selected_year', date('Y')));
        $target_lalu = Target::user()->where('tahun', session('selected_year', date('Y')) - 1)->get()->first();
        $target = Target::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $units = Unit::user()->get();
        $proker = Proker::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $programs = Program::user()->where('tahun', session('selected_year', date('Y')))->get();
        $kerjasamas = Kerjasama::user()->where('tahun', session('selected_year', date('Y')))->get();
        $alokasis = Alokasi::user()->where('tahun', session('selected_year', date('Y')))->get();
        $rasios = Rasio::user()->where('tahun', session('selected_year', date('Y')))->get();
        $ekuitas = Ekuit::user()->get()->first();

        $modal_desa = Modal::user()->get()->sum('mdl_desa');
        $modal_masyarakat = Modal::user()->get()->sum('mdl_masyarakat');
        $modal_bersama = Modal::user()->get()->sum('mdl_bersama');
        $tahun = session('selected_year') ?? date('Y');

        $data = [
            'profil' => $profil,
            'units' => $units,
            'modals'  => $modals,
            'units' => $units,
            'pendapatan' => $labaRugi['pendapatan'],
            'pendapatanBulan' => $labaRugi['pendapatanBulan'],
            'pendapatanTahun' => $labaRugi['pendapatanTahun'],
            'tahun' => $labaRugi['tahun'],
            'totalBiaya' => $labaRugi['totalBiaya'],
            'akumulasiBiaya' => $labaRugi['akumulasiBiaya'],
            'labaRugi' => $labaRugi['labaRugi'],
            'totalLabaRugi' => $labaRugi['totalLabaRugi'],
            'akumulasi_penyusutan' => $labaRugi['akumulasi_penyusutan'],
            'target_lalu' => $target_lalu,
            'target' => $target,
            'proker' => $proker,
            'programs' => $programs,
            'kerjasamas' => $kerjasamas,
            'alokasis' => $alokasis,
            'aktiva' => $neraca['aktiva'],
            'rasios' => $rasios,
            'modal_desa' => $modal_desa,
            'modal_masyarakat' => $modal_masyarakat,
            'modal_bersama' => $modal_bersama,
            'ekuitas' => $ekuitas,
            'ditahan' => $neraca['ditahan'],
            'laba_berjalan' => labaRugiTahun($tahun)['totalLabaRugi']
        ];

        // Generate PDF
        $pdf = PDF::loadView('proker.pdf.index', $data)->setPaper([0, 0, 595.276, 935.433], 'portrait');

        // Stream atau unduh PDF
        return $pdf->stream('proker.pdf');
    }
}
