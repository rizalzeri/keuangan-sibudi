<?php

namespace App\Http\Controllers;

use App\Models\Dithn;
use App\Models\Ekuit;
use App\Models\Hutang;
use App\Models\Modal;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPerubahanModalController extends Controller
{
    public function index()
    {
        $ekuitas = Ekuit::user()->get()->first();
        $neraca =  neraca();
        $modal_desa = Modal::user()->get()->sum('mdl_desa');
        $modal_masyarakat = Modal::user()->get()->sum('mdl_masyarakat');
        $modal_bersama = Modal::user()->get()->sum('mdl_bersama');
        $tahun = $ekuitas->tahun ?? session('selected_year');


        // dd($tahun);

        // dd(labaRugiTahun($tahun)['totalLabaRugi']);


        return view('laporan_perubahan_modal.index', [
            'modal_desa' => $modal_desa,
            'modal_masyarakat' => $modal_masyarakat,
            'modal_bersama' => $modal_bersama,
            'ekuitas' => $ekuitas,
            'ditahan' => $neraca['ditahan'],
            'laba_berjalan' => labaRugiTahun($tahun)['totalLabaRugi']
        ]);
    }

    public function exportPdf()
    {

        $ekuitas = Ekuit::user()->get()->first();

        $neraca =  neraca();
        $modal_desa = Modal::user()->get()->sum('mdl_desa');
        $modal_bersama = Modal::user()->get()->sum('mdl_bersama');
        $modal_masyarakat = Modal::user()->get()->sum('mdl_masyarakat');
        $tahun = $ekuitas->tahun ?? session('selected_year') ?? date('Y');



        $data = [
            'modal_desa' => $modal_desa,
            'modal_masyarakat' => $modal_masyarakat,
            'modal_bersama' => $modal_bersama,
            'ekuitas' => $ekuitas,
            'ditahan' => $neraca['ditahan'],
            'laba_berjalan' => labaRugiTahun($tahun)['totalLabaRugi']
        ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('laporan_perubahan_modal.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|numeric|digits:4',
            'pades' => 'required|numeric',
            'lainya' => 'required|numeric',
            'akumulasi' => 'required|numeric',
        ]);

        $validated['user_id'] = auth()->user()->id;

        Ekuit::create($validated);

        return redirect()->back()->with('success', 'Berhasil di tambah');
    }
    public function update(Request $request, Ekuit $ekuit)
    {

        $validated = $request->validate([
            'tahun' => 'required|numeric|digits:4',
            'pades' => 'required|numeric',
            'lainya' => 'required|numeric',
            'akumulasi' => 'required|numeric',
        ]);

        $validated['user_id'] = auth()->user()->id;

        Ekuit::where('id', $ekuit->id)->update($validated);

        histori(rendem(), 'ekuits', $ekuit->toArray(), 'update', $ekuit->id);

        return redirect()->back()->with('success', 'Berhasil di rubah');
    }

    public function ditahan(Ekuit $ekuit)
    {

        $id = rendem();
        $hasil = labaRugiTahun($ekuit->tahun)['totalLabaRugi'] > 0 ? 'Untung' : 'Rugi';
        $labaRugi =   labaRugiTahun($ekuit->tahun)['totalLabaRugi'];


        $dataDitahan = [
            'tahun' => $ekuit->tahun,
            'hasil' => $hasil,
            'nilai' => $labaRugi,
            'pades' => $labaRugi * ($ekuit->pades / 100),
            'lainya' => $labaRugi * ($ekuit->lainya / 100),
            'akumulasi' => $labaRugi * ($ekuit->akumulasi / 100),
            'user_id' => auth()->user()->id,
            'created_at' => created_at()
        ];
        $ditahan = Dithn::create($dataDitahan);
        if ($ditahan) {
            histori($id, 'dithns', $dataDitahan, 'create', $ditahan->id);
        }

        $dataHutang = [
            'kreditur' => 'pemdes',
            'keterangan' => 'PADes',
            'nilai' => $labaRugi * ($ekuit->pades / 100),
            'user_id' => auth()->user()->id,
            'created_at' => created_at()
        ];

        $dataBumdes =
            [
                'kreditur' => 'pengelola BUMDes',
                'keterangan' => 'SHU',
                'nilai' => $labaRugi * ($ekuit->lainya / 100),
                'user_id' => auth()->user()->id,
                'created_at' => created_at()
            ];

        // Insert data hutang
        $hutangPemdes = Hutang::create($dataHutang);
        $hutangBumdes = Hutang::create($dataBumdes);

        // Ambil data terakhir yang berhasil dimasukkan
        if ($hutangBumdes && $hutangBumdes) {
            histori($id, 'hutangs', $dataBumdes, 'create', $hutangBumdes->id);
            histori($id, 'hutangs', $dataHutang, 'create', $hutangPemdes->id);
        }


        return redirect()->back()->with('success', 'Berhasil menambahkan laba di tahan');
    }
}
