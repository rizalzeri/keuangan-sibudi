<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\Aktivalain;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AktivalainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asets = Aktivalain::user()->get();

        $akumulasi = 0;
        $investasi = 0;
        $akumulasi = $akumulasi + akumulasiPenyusutan($asets)['akumu'];
        $investasi = $investasi + akumulasiPenyusutan($asets)['inven'];


        return view('aktiva.index', [
            "asets" => $asets,
            'akumulasi' => $akumulasi,
            'investasi' => $investasi
        ]);
    }


    public function exportPdf()
    {


        $asets = Aktivalain::user()->get();

        $akumulasi = 0;
        $investasi = 0;
        $akumulasi = $akumulasi + akumulasiPenyusutan($asets)['akumu'];
        $investasi = $investasi + akumulasiPenyusutan($asets)['inven'];

        $data = [
            "asets" => $asets,
            'akumulasi' => $akumulasi,
            'investasi' => $investasi
        ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('aktiva.pdf', $data)->setPaper([0, 0, 595.276, 935.433], 'portrait');

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('aktiva.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated =  $request->validate([
            'jenis' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'wkt_ekonomis' => 'required|min:1',
        ]);
        $validated['user_id'] = auth()->user()->id;
        $validated['masa_pakai'] = 1;
        $validated['created_at'] = $request->created_at;
        $id = rendem();
        $aktivalain = Aktivalain::create($validated);
        // Simpan data ke database
        if ($aktivalain && $request->has('no_kas')) {
            $buk =  bukuUmum('Aktiva Lain ' . $request->jenis, 'kredit', 'kas', 'operasional', $request->nilai, 'aktiva_lain', $aktivalain->id, $request->created_at);

            histori($id, 'aktivalains', $validated, 'create', $aktivalain->id);
            histori($id, 'buks', $buk, 'create', $buk->id);
        };

        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/aktivalain')->with('success', 'Aset berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aktivalain $aktivalain)
    {
        //
    }


    /**
     * pakai the specified resource in storage.
     */
    public function pakai(Request $request, Aktivalain $aktivalain)
    {
        if (isset($request->pakai)) {
            if ($request->pakai == 'tambah') {
                $masa_pakai = $aktivalain->masa_pakai + 1;
            } elseif ($request->pakai == 'kurang') {
                $masa_pakai = $aktivalain->masa_pakai - 1;
            }

            Aktivalain::where('id', $aktivalain->id)->update(['masa_pakai' => $masa_pakai]);
            // Redirect ke halaman daftar aset dengan pesan sukses
            return redirect('/aset/aktivalain')->with('success', 'Masa pakai berhasil ditambahkan.');
        }
        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/aktivalain')->with('error', 'Masa pakai gagal ditambahkan.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aktivalain $aktivalain)
    {
        return view('aktiva.edit', ['aset' => $aktivalain]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aktivalain $aktivalain)
    {
        // Validasi input
        $validated =  $request->validate([
            'jenis' => 'required|string|max:255',
            'nilai' => 'required|numeric',
            'wkt_ekonomis' => 'required|min:1',
            'masa_pakai' => '',
        ]);
        $validated['user_id'] = auth()->user()->id;
        $validated['created_at'] = $request->created_at;
        $id = rendem();
        $buk = Buk::where(
            'akun',
            'aktiva_lain'
        )->firstWhere('id_akun', $aktivalain->id);

        histori($id, 'aktivalains', $aktivalain->toArray(), 'update', $aktivalain->id);
        // Simpan data ke database
        if (Aktivalain::where('id', $aktivalain->id)->update($validated)) {
            histori($id, 'buks', ['nilai' => $buk->nilai], 'update', $buk->id);


            updateBukuUmum('aktiva_lain', $aktivalain->id, $request->nilai);
        };

        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/aktivalain')->with('success', 'Aset berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aktivalain $aktivalain)
    {
        histori(rendem(), 'aktivalains', $aktivalain->toArray(), 'delete', $aktivalain->id);
        $aktivalain->delete();

        // Redirect ke halaman daftar aset dengan pesan sukses
        return redirect('/aset/aktivalain')->with('success', 'Aset berhasil dihapus.');
    }
}
