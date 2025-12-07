<?php

namespace App\Http\Controllers;

use App\Models\Rekonsiliasi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RekonsiliasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kas = neraca()['kas'];
        $rekonsiliasi = Rekonsiliasi::user()->get();
        return view('rekonsiliasi.index', ['kas' => $kas, 'rekonsiliasis' => $rekonsiliasi]);
    }


    public function exportPdf()
    {

        $kas = neraca()['kas'];
        $rekonsiliasi = Rekonsiliasi::user()->get();
        $data = ['kas' => $kas, 'rekonsiliasis' => $rekonsiliasi];

        // Gunakan facade PDF
        $pdf = PDF::loadView('rekonsiliasi.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rekonsiliasi = Rekonsiliasi::user()->get();
        return view('rekonsiliasi.tambah',  ['rekonsiliasis' => $rekonsiliasi]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'posisi' => 'required|string'
        ]);
        $validated['user_id'] = auth()->user()->id;

        $rekon =   Rekonsiliasi::create($validated);

        if ($rekon) {
            histori(rendem(), 'rekonsiliasis', $validated, 'create', $rekon->id);
        }

        return redirect()->back()->with('success', 'Berhasil menambahakan posisi');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rekonsiliasi $rekonsiliasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rekonsiliasi $rekonsiliasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rekonsiliasi $rekonsiliasi)
    {
        $validated = $request->validate([
            'posisi' => 'required|string',
        ]);
        $validated['user_id'] = auth()->user()->id;
        $validated['jumlah'] = str_replace('.', '', $request->jumlah);

        Rekonsiliasi::where('id', $rekonsiliasi->id)->update($validated);
        histori(rendem(), 'rekonsiliasis', $rekonsiliasi->toArray(), 'update', $rekonsiliasi->id);
        return redirect()->back()->with('success', 'Rekonsiliasi berhasil diupdate.');
    }
    /**
     * UpdateJumlah the specified resource in storage.
     */
    public function updateJumlah(Request $request, Rekonsiliasi $rekonsiliasi)
    {
        $rekonsiliasiData = $request->input('rekonsiliasi');

        $id = rendem();

        foreach ($rekonsiliasiData as $data) {
            $dataRekon = Rekonsiliasi::find($data['id']);
            $rekon = Rekonsiliasi::where('id', $data['id'])->update(['jumlah' => $data['jumlah']]);

            if ($rekon) {
                histori($id, 'rekonsiliasis', ['jumlah' => $dataRekon->jumlah], 'update', $data['id']);
            }
        }

        return redirect()->back()->with('success', 'Rekonsiliasi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekonsiliasi $rekonsiliasi)
    {
        histori(rendem(), 'rekonsiliasis', $rekonsiliasi->toArray(), 'delete', $rekonsiliasi->id);
        $rekonsiliasi->delete();

        return redirect()->back()->with('error', 'Rekonsiliasi berhasil dihapus.');
    }
}
