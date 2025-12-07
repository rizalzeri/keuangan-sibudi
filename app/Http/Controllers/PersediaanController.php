<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\Unit;
use App\Models\Persediaan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PersediaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Persediaan::user()->get();

        $total_nilai_awal = 0;
        $total_nilai_akhir = 0;
        $nilai_akhir = 0;
        $total_laba = 0;
        foreach ($barangs as $barang) {
            $nilai_awal = $barang->jml_awl * $barang->hpp;
            $total_nilai_awal += $nilai_awal;

            $jumlah_akhir = $barang->jml_awl - ($barang->masuk - $barang->keluar);


            $nilai_akhir += $jumlah_akhir * $barang->hpp;
            $total_nilai_akhir += $nilai_akhir;

            $laba = ($barang->masuk) * ($barang->nilai_jual - $barang->hpp);
            $total_laba += $laba;
        }
        $unit = Unit::user()->where('kode', 'pd9876')->get()->first();
        if (!isset($unit->kode)) {
            return view('persediaan.unit');
        } else {
            return view('persediaan.index', [
                'barangs' => $barangs,
                'total_nilai_awal' => $total_nilai_awal,
                'total_nilai_akhir' => $total_nilai_akhir,
                'total_laba' => $total_laba,
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

        return redirect('/aset/persediaan')->with('success', 'Unit berhasil ditambahkan!');
    }


    public function exportPdf()
    {

        $barangs = Persediaan::user()->get();

        $total_nilai_awal = 0;
        $total_nilai_akhir = 0;
        $nilai_akhir = 0;
        $total_laba = 0;
        foreach ($barangs as $barang) {
            $nilai_awal = $barang->jml_awl * $barang->hpp;
            $total_nilai_awal += $nilai_awal;

            $jumlah_akhir = $barang->jml_awl - ($barang->masuk - $barang->keluar);


            $nilai_akhir += $jumlah_akhir * $barang->hpp;
            $total_nilai_akhir += $nilai_akhir;

            $laba = ($barang->masuk) * ($barang->nilai_jual - $barang->hpp);
            $total_laba += $laba;
        }
        $data =
            [
                'barangs' => $barangs,
                'total_nilai_awal' => $total_nilai_awal,
                'total_nilai_akhir' => $total_nilai_akhir,
                'total_laba' => $total_laba,
            ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('persediaan.pdf', $data)->setPaper([0, 0, 595.276, 935.433], 'portrait');

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('persediaan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'hpp' => 'required|numeric|min:0',
            'nilai_jual' => 'required|numeric|min:0',
            'jml_awl' => 'required|integer|min:0',
        ]);

        $validated['user_id'] = auth()->user()->id;
        $validated['created_at'] = $request->created_at;

        $total_harga = $validated['hpp'] * $validated['jml_awl'];

        $id = rendem();
        $persediaan = Persediaan::create($validated);

        if ($persediaan) {
            $buk =    bukuUmum('Persediaan ' . $request->item, 'kredit', 'kas', 'operasional', $total_harga, 'persediaan', Persediaan::latest()->first()->id, $request->created_at);

            histori($id, 'persediaans', $validated, 'create', $persediaan->id);
            histori($id, 'buks', $validated, 'create', $buk->id);
        };
        return redirect('/aset/persediaan')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Persediaan $persediaan)
    {
        //
    }



    /**
     * penjualan the specified resource in storage.
     */
    public function penjualan(Request $request, Persediaan $persediaan)
    {
        $id = rendem();
        $tanggal = created_at();

        if (isset($request->masuk) && $request->masuk != $persediaan->masuk) {
            $transasksi = 'Jual ' . $request->masuk - $persediaan->masuk . ' ' . $persediaan->item;
            $laba = ($request->masuk - $persediaan->masuk) * ($persediaan->nilai_jual - $persediaan->hpp);

            $masuk = $request->masuk - $persediaan->masuk;
            $omset = $persediaan->hpp * $masuk + $laba;



            Persediaan::where('id', $persediaan->id)->update(['masuk' => $request->masuk]);
            histori($id, 'persediaans', ['masuk' => $persediaan->masuk], 'update', $persediaan->id);

            if ($request->no_kas) {
                $bukJual = bukuUmum($transasksi, 'tetap', 'bopd9876', 'operasional', $persediaan->hpp * $masuk, null, null, $tanggal);
                histori($id, 'buks', $bukJual->id, 'create', $bukJual->id);
            } else {
                $bukOmset =  bukuUmum($transasksi, 'debit', 'pupd9876', 'operasional', $omset, null, null, $tanggal);
                $bukJual = bukuUmum($transasksi, 'tetap', 'bopd9876', 'operasional', $persediaan->hpp * $masuk, null, null, $tanggal);
                histori($id, 'buks', $bukOmset->id, 'create', $bukOmset->id);
                histori($id, 'buks', $bukJual->id, 'create', $bukJual->id);
            }
        }
        if (isset($request->keluar) && $request->keluar != $persediaan->keluar) {
            $transasksi = 'Pembelian Persedian ' . $request->keluar - $persediaan->keluar . ' ' . $persediaan->item;
            $laba = ($request->keluar - $persediaan->keluar) * ($persediaan->nilai_jual - $persediaan->hpp);
            $keluar = $request->keluar - $persediaan->keluar;
            $bukJual = bukuUmum($transasksi, 'kredit', 'kas', 'operasional', $persediaan->hpp * $keluar, null, null, $tanggal);
            Persediaan::where('id', $persediaan->id)->update(['keluar' => $request->keluar]);

            histori($id, 'persediaans', ['keluar' => $persediaan->keluar], 'update', $persediaan->id);
            histori($id, 'buks', $bukJual->id, 'create', $bukJual->id);
        }
        return redirect()->back()->with('success', 'Data Berhasil dirubah!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persediaan $persediaan)
    {
        return view('persediaan.edit', ['barang' => $persediaan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Persediaan $persediaan)
    {

        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'hpp' => 'required|numeric|min:0',
            'nilai_jual' => 'required|numeric|min:0',
            'jml_awl' => 'required|min:0',
            'masuk' => '',
            'keluar' => '',
        ]);

        $id = rendem();
        $buk = Buk::where('akun', 'persediaan')->firstWhere('id_akun', $persediaan->id);

        $year = session('selected_year', date('Y'));
        $tanggal = date('Y-m-d', strtotime($year . date('-m-d')));

        if (isset($request->masuk) && $request->masuk != $persediaan->masuk) {
            $transasksi = 'Jual ' . $request->masuk - $persediaan->masuk . ' ' . $persediaan->item;
            $laba = ($request->masuk - $persediaan->masuk) * ($persediaan->nilai_jual - $persediaan->hpp);

            $masuk = $request->masuk - $persediaan->masuk;
            $omset = $persediaan->hpp * $masuk + $laba;



            Persediaan::where('id', $persediaan->id)->update(['masuk' => $request->masuk]);
            histori($id, 'persediaans', ['masuk' => $persediaan->masuk], 'update', $persediaan->id);

            if ($request->no_kas) {
                $bukJual = bukuUmum($transasksi, 'tetap', 'bopd9876', 'operasional', $persediaan->hpp * $masuk, null, null, $tanggal);
                histori($id, 'buks', $bukJual->id, 'create', $bukJual->id);
            } else {
                $bukOmset =  bukuUmum($transasksi, 'debit', 'pupd9876', 'operasional', $omset, null, null, $tanggal);
                $bukJual = bukuUmum($transasksi, 'tetap', 'bopd9876', 'operasional', $persediaan->hpp * $masuk, null, null, $tanggal);
                histori($id, 'buks', $bukOmset->id, 'create', $bukOmset->id);
                histori($id, 'buks', $bukJual->id, 'create', $bukJual->id);
            }
        }
        if (isset($request->keluar) && $request->keluar != $persediaan->keluar) {
            $transasksi = 'Pembelian Persedian ' . $request->keluar - $persediaan->keluar . ' ' . $persediaan->item;
            $laba = ($request->keluar - $persediaan->keluar) * ($persediaan->nilai_jual - $persediaan->hpp);
            $keluar = $request->keluar - $persediaan->keluar;
            $bukJual = bukuUmum($transasksi, 'kredit', 'kas', 'operasional', $persediaan->hpp * $keluar, null, null, $tanggal);
            Persediaan::where('id', $persediaan->id)->update(['keluar' => $request->keluar]);

            histori($id, 'persediaans', ['keluar' => $persediaan->keluar], 'update', $persediaan->id);
            histori($id, 'buks', $bukJual->id, 'create', $bukJual->id);
        }

        if (Persediaan::where('id', $persediaan->id)->update($validated)) {
            histori($id, 'persediaans', $persediaan->toArray(), 'update', $persediaan->id);
            histori($id, 'buks', ['nilai' => $buk->nilai], 'update', $buk->id);

            updateBukuUmum('persediaan', $persediaan->id, $request->jml_awl * $request->hpp);
        };
        return redirect('/aset/persediaan')->with('success', 'Barang berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persediaan $persediaan)
    {
        // $nilai

        // Buk::create(['transaksi' => $persediaan->item, 'jenis' => 'kredit', 'nilai' => , 'user_id' => $user_id]);
        histori(rendem(), 'persediaans', $persediaan->toArray(), 'delete', $persediaan->id);
        $persediaan->delete();

        return redirect('/aset/persediaan')->with('error', 'Barang berhasil dihapus!');
    }

    public function reset()
    {
        $persediaans = Persediaan::user()->get();
        $id = rendem();

        foreach ($persediaans as $persediaan) {
            $jumlah_akhir = $persediaan->jml_awl - ($persediaan->masuk - $persediaan->keluar);
            $data = [
                'masuk' => 0,
                'keluar' => 0,
                'jml_awl' => $jumlah_akhir


            ];

            $data_lama = [
                'masuk' => $persediaan->masuk,
                'keluar' => $persediaan->keluar,
                'jml_awl' => $persediaan->jml_awl

            ];

            histori($id, 'persediaans', $data_lama, 'update', $persediaan->id);
            Persediaan::where('id', $persediaan->id)->update($data);
        }

        return redirect('/aset/persediaan')->with('success', 'Barang berhasil reset!');
    }
}
