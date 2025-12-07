<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\Piutang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $piutangs = Piutang::user()->get();

        $sisa = 0;
        foreach ($piutangs as $piutang) {
            $sisa = $sisa + ($piutang->nilai - $piutang->pembayaran);
        }
        return view('piutang.index', ['piutangs' => $piutangs, 'sisa' => $sisa]);
    }


    public function exportPdf()
    {

        $piutangs = Piutang::user()->get();

        $sisa = 0;
        foreach ($piutangs as $piutang) {
            $sisa = $sisa + ($piutang->nilai - $piutang->pembayaran);
        }
        $data = ['piutangs' => $piutangs, 'sisa' => $sisa];

        // Gunakan facade PDF
        $pdf = PDF::loadView('piutang.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('piutang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kreditur' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'nilai' => 'required|numeric',
        ]);
        $validated['user_id'] = auth()->user()->id;
        $validated['created_at'] = $request->created_at;
        $piutang = Piutang::create($validated);
        $id = rendem();
        if ($piutang && $request->has('no_kas')) {
            $buk = bukuUmum('Piutang', 'kredit', 'kas', 'operasional', $request->nilai, 'piutang', $piutang->id, $request->created_at);
            histori($id, 'piutangs', $validated, 'create', $piutang->id);
            histori($id, 'buks', ['nilai' => $validated['nilai']], 'create', $buk->id);
        };

        // Redirect with success message
        return redirect()->route('piutang.index')->with('success', 'piutang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Piutang $piutang)
    {
        //
    }

    /**
     * Bayar the specified resource in storage.
     */
    public function bayar(Request $request, Piutang $piutang)
    {

        $input_realisasi = str_replace('.', '', $request->pembayaran);

        if ($request->aksi == '+') {
            $pembayaran = $piutang->pembayaran +  $input_realisasi;
            $jenis = 'debit';
        } elseif ($request->aksi == '-') {
            $pembayaran = $piutang->pembayaran -  $input_realisasi;
            $jenis = 'kredit';
        }

        $year = session('selected_year', date('Y'));
        $tanggal = date('Y-m-d', strtotime($year . date('-m-d')));

        $id = rendem();
        if (Piutang::where('id', $piutang->id)->update(['pembayaran' => $pembayaran])) {
            $buk =  bukuUmum('Setor ' . $piutang->kreditur, $jenis, 'kas', 'pendanaan', $input_realisasi, null, null, $tanggal);
            histori($id, 'piutangs', $piutang->toArray(), 'update', $piutang->id);
            histori($id, 'buks', ['nilai' => $piutang->nilai], 'create', $buk->id);
        };
        // Redirect with success message
        return redirect()->route('piutang.index')->with('success', 'piutang berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Piutang $piutang)
    {
        return view('piutang.edit', ['piutang' => $piutang]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Piutang $piutang)
    {
        $validated = $request->validate([
            'kreditur' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'nilai' => 'required|numeric',
        ]);
        $validated['user_id'] = auth()->user()->id;
        $Buk = Buk::where('akun', 'piutang')->firstWhere('id_akun', $piutang->id);
        $id = rendem();

        if (Piutang::where('id', $piutang->id)->update($validated)) {
            updateBukuUmum('piutang', $piutang->id, $request->nilai);
            histori($id, 'piutangs', $piutang->toArray(), 'update', $piutang->id);
            histori($id, 'buks', ['nilai' => $Buk->nilai], 'update', $Buk->id);
        };

        // Redirect with success message
        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Piutang $piutang)
    {
        Piutang::where('id', $piutang->id)->delete();
        histori(rendem(), 'piutangs', $piutang->toArray(), 'delete', $piutang->id);
        return redirect()->route('piutang.index')->with('error', 'Piutang berhasil dihapus.');
    }
}
