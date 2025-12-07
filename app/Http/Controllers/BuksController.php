<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BuksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $transaksis_lalu = Buk::user()->whereYear('tanggal', '<', session('selected_year', date('Y')))->get();
        $debit_lalu = $transaksis_lalu->where('jenis', 'debit')->sum('nilai');
        $kredit_lalu = $transaksis_lalu->where('jenis', 'kredit')->sum('nilai');
        $saldo_lalu = $debit_lalu - $kredit_lalu;

        $transaksis = Buk::user()->whereYear('tanggal', session('selected_year', date('Y')))->get();
        $debit = $transaksis->where('jenis', 'debit')->sum('nilai');
        $kredit = $transaksis->where('jenis', 'kredit')->sum('nilai');
        $saldo = $debit - $kredit;
        $saldo = $saldo + $saldo_lalu;

        $units = Unit::user()->get();

        return view('buku_kas.index', [
            'transaksis' => $transaksis,
            'saldo' => $saldo,
            'saldo_lalu' => $saldo_lalu,
            'units' => $units

        ]);
    }

    public function exportPdf()
    {

        // Ambil transaksi sebelum tahun yang dipilih
        $transaksis_lalu = Buk::user()
            ->whereYear('tanggal', '<', session('selected_year', date('Y')))
            ->get()
            ->filter(function ($item) {
                return $item->jenis !== 'tetap'; // Hapus transaksi dengan jenis 'tetap'
            });

        $debit_lalu = $transaksis_lalu->where('jenis', 'debit')->sum('nilai');
        $kredit_lalu = $transaksis_lalu->where('jenis', 'kredit')->sum('nilai');
        $saldo_lalu = $debit_lalu - $kredit_lalu;

        // Ambil transaksi untuk tahun yang dipilih
        $transaksis = Buk::user()
            ->whereYear('tanggal', session('selected_year', date('Y')))
            ->get()
            ->filter(function ($item) {
                return $item->jenis !== 'tetap'; // Hapus transaksi dengan jenis 'tetap'
            });

        $debit = $transaksis->where('jenis', 'debit')->sum('nilai');
        $kredit = $transaksis->where('jenis', 'kredit')->sum('nilai');
        $saldo = $debit - $kredit;

        // Gabungkan saldo dengan saldo sebelumnya
        $saldo += $saldo_lalu;

        $data = [
            'transaksis' => $transaksis,
            'saldo' => $saldo,
            'saldo_lalu' => $saldo_lalu,

        ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('buku_kas.pdf', $data)->setPaper([0, 0, 595.276, 935.433], 'portrait');

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $units = Unit::user()->get();
        return view('buku_kas.create', ['units' => $units]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated  = $request->validate([
            'tanggal' => 'required|date',
            'transaksi' => 'required|string|max:255',
            'jenis' => 'required|string|in:debit,kredit,tetap', // e.g., debit or credit
            'jenis_lr' => 'required|string', // e.g., debit or credit
            'jenis_dana' => 'required|string|in:operasional,iventasi,pendanaan', // e.g., debit or credit
            'nilai' => 'required|numeric',
        ]);
        $validated['user_id'] = auth()->user()->id;
        $buk =  Buk::create($validated);

        if ($buk) {
            histori(rendem(), 'buks', $validated, 'create', $buk->id);
        }

        return redirect('/aset/buk')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Buk $buk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buk $buk)
    {
        $units = Unit::user()->get();
        return view('buku_kas.edit', ['transaksi' => $buk, 'units' => $units]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Buk $buk)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'transaksi' => 'required|string|max:255',
            'jenis' => 'required|string|in:debit,kredit,tetap', // e.g., debit or credit
            'jenis_lr' => 'required|string', // e.g., debit or credit
            'jenis_dana' => 'required|string|in:operasional,iventasi,pendanaan', // e.g., debit or credit
            'nilai' => 'required|numeric',
        ]);
        $validated['user_id'] = auth()->user()->id;
        Buk::where('id', $buk->id)->update($validated);

        histori(rendem(), 'buks', $buk->toArray(), 'update', $buk->id);

        return redirect('/aset/buk')->with('success', 'Transaksi berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buk $buk)
    {
        Buk::where('id', $buk->id)->delete();

        histori(rendem(), 'buks', $buk->toArray(), 'delete', $buk->id);

        return redirect('/aset/buk')->with('error', 'Hutang berhasil dihapus.');
    }
}
