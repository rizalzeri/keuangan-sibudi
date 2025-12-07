<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\Unit;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(session('histori'));
        $pinjamans = Pinjaman::user()->get();
        $tunggakan = $pinjamans->sum('alokasi') - $pinjamans->sum('realisasi');
        $saldo_bunga = 0;
        foreach ($pinjamans as $pinjaman) {
            $bunga = $pinjaman->alokasi * ($pinjaman->bunga / 100);
            $total_bunga = $bunga * $pinjaman->angsuran;
            $saldo_bunga = $saldo_bunga + $total_bunga;
        }
        $unit = Unit::user()->where('kode', 'pj2345')->get()->first();
        if (!isset($unit->kode)) {
            return view('pinjaman.unit');
        } else {
            return view('pinjaman.index', [
                'pinjamans' => $pinjamans,
                'tunggakan' => $tunggakan,
                'saldo_bunga' => $saldo_bunga
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeUnit(Request $request)
    {
        $validated =  $request->validate([
            'nm_unit' => 'required|string|max:255',
            'kepala_unit' => 'required|string|max:255',
            'kode' => 'required',
        ]);

        $validated['user_id'] = auth()->user()->id;


        $unit = Unit::create($validated);

        if ($unit) {
            histori(rendem(), 'units', $validated, 'create', $unit->id);
        }
        return redirect('/aset/pinjaman')->with('success', 'Unit berhasil ditambahkan!');
    }


    public function exportPdf()
    {

        $pinjamans = Pinjaman::user()->get();
        $tunggakan = $pinjamans->sum('alokasi') - $pinjamans->sum('realisasi');
        $saldo_bunga = 0;
        foreach ($pinjamans as $pinjaman) {
            $bunga = $pinjaman->alokasi * ($pinjaman->bunga / 100);
            $total_bunga = $bunga * $pinjaman->angsuran;
            $saldo_bunga = $saldo_bunga + $total_bunga;
        }
        $data =
            [
                'pinjamans' => $pinjamans,
                'tunggakan' => $tunggakan,
                'saldo_bunga' => $saldo_bunga
            ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('pinjaman.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pinjaman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated  = $request->validate([
            'nasabah' => 'required|string|max:255',
            'tgl_pinjam' => 'required|date',
            'alokasi' => 'required|string|max:255',
            'bunga' => 'required|min:0|max:100',
        ]);

        $validated['user_id'] = auth()->user()->id;
        $validated['created_at'] = $request->tgl_pinjam;
        $validated['bunga'] = str_replace(',', '.', $request->bunga);


        $pinjaman = Pinjaman::create($validated);
        $id = rendem();
        if ($pinjaman && $request->has('no_kas')) {
            $buk =  bukuUmum('Pinjaman ' . $request->nasabah, 'kredit', 'kas', 'operasional', $request->alokasi, 'pinjaman', $pinjaman->id, $request->tgl_pinjam);
            histori($id, 'pinjamans', $validated, 'create', $pinjaman->id);
            histori($id, 'buks', $validated, 'create', $buk->id);
        };

        return redirect('/aset/pinjaman')->with('success', 'Pinjaman berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pinjaman $pinjaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pinjaman $pinjaman)
    {
        return view('pinjaman.edit', ['pinjaman' => $pinjaman]);
    }


    public function bayar(Request $request, Pinjaman $pinjaman)
    {
        $input_realisasi = str_replace('.', '', $request->realisasi);

        if ($request->aksi == '+') {
            $realisasi = $pinjaman->realisasi +  $input_realisasi;
            $angsuran = $pinjaman->angsuran + 1;
        } elseif ($request->aksi == '-') {
            $realisasi = $pinjaman->realisasi -  $input_realisasi;
            $angsuran = $pinjaman->angsuran - 1;
        }

        $bunga = $pinjaman->alokasi * ($pinjaman->bunga / 100);

        $year = session('selected_year', date('Y'));
        $tanggal = date('Y-m-d', strtotime($year . date('-m-d')));

        $bukBunga =  bukuUmum('Bunga Storan ' . $pinjaman->nasabah, 'debit', 'pupj2345', 'operasional', $bunga, null, null, $tanggal);
        $bukStoran = bukuUmum('Storan ' . $pinjaman->nasabah, 'debit', 'kas', 'operasional', $input_realisasi, null, null, $tanggal);
        $pinjamanUpdate = Pinjaman::where('id', $pinjaman->id)->update(['realisasi' => $realisasi, 'angsuran' => $angsuran]);
        $id = rendem();
        if ($pinjamanUpdate) {
            histori($id, 'buks', $bukBunga, 'create', $bukBunga->id);
            histori($id, 'buks', $bukStoran, 'create', $bukStoran->id);
            histori($id, 'pinjamans', ['realisasi' => $pinjaman->realisasi, 'angsuran' => $pinjaman->angsuran], 'update', $pinjaman->id);
        }

        // Redirect with success message
        return redirect()->route('pinjaman.index')->with('success', 'pinjaman berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pinjaman $pinjaman)
    {
        $validated = $request->validate([
            'nasabah' => 'required|string|max:255',
            'tgl_pinjam' => 'required|date',
            'alokasi' => 'required|string|max:255',
            'bunga' => 'required|min:0|max:100',
        ]);

        $validated['bunga'] = str_replace(',', '.', $request->bunga);
        $id = rendem();
        $Buk = Buk::where('akun', 'pinjaman')->firstWhere('id_akun', $pinjaman->id);

        // dd($pinjaman->id);
        if (Pinjaman::where('id', $pinjaman->id)->update($validated)) {
            updateBukuUmum('pinjaman', $pinjaman->id, $request->alokasi);

            histori($id, 'pinjamans', $pinjaman->toArray(), 'update', $pinjaman->id);
            histori($id, 'buks', ['nilai' => $pinjaman->alokasi], 'update', $Buk->id);
        };

        return redirect('/aset/pinjaman')->with('success', 'Pinjaman berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pinjaman $pinjaman)
    {
        histori(rendem(), 'pinjamans', $pinjaman->toArray(), 'delete', $pinjaman->id);
        $pinjaman->delete();
        return redirect('/aset/pinjaman')->with('error', 'Pinjaman berhasil dihapus');
    }
}
