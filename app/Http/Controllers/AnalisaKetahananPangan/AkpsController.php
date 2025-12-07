<?php

namespace App\Http\Controllers\AnalisaKetahananPangan;

use App\Models\Akps;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Kebutuhan;
use App\Models\Penjualan;


class AkpsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selectedYear = session('selected_year', date('Y'));
        $now = Carbon::now()->setYear($selectedYear);

        // Cek apakah data AKP sudah ada
        $akp = Akps::user()->whereYear('created_at', $selectedYear)->first();

        // Jika tidak ada, buat baru
        if ($akp == null) {
            $akp = Akps::create([
                'user_id' => auth()->id(),
                'created_at' => $now,
            ]);
        }

        // Cek apakah kebutuhan sudah ada
        $kebutuhan_create = Kebutuhan::user()->whereYear('created_at', $selectedYear)->first();


        if ($kebutuhan_create == null) {
            $data = [
                ['user_id' => auth()->id(), 'created_at' => $now, 'uraian' => 'Biaya Sewa Tanah', 'kategori' => 'Sewa Tanah/Bangunan', 'jumlah' => 1],
                ['user_id' => auth()->id(), 'created_at' => $now, 'uraian' => 'Biaya Sewa Bangunan', 'kategori' => 'Sewa Tanah/Bangunan', 'jumlah' => 1],
                ['user_id' => auth()->id(), 'created_at' => $now, 'uraian' => 'Transportasi hasil panen', 'kategori' => 'Distribusi', 'jumlah' => null],
                ['user_id' => auth()->id(), 'created_at' => $now, 'uraian' => 'Perbaikan dan pemeliharaan', 'kategori' => 'Sarana Prasarana', 'jumlah' => null],
                ['user_id' => auth()->id(), 'created_at' => $now, 'uraian' => 'Pelatihan pemberdayaan masyarakat', 'kategori' => 'Pekerja', 'jumlah' => null],
                ['user_id' => auth()->id(), 'created_at' => $now, 'uraian' => 'Tenaga Kerja', 'kategori' => 'Pekerja', 'jumlah' => null],
                ['user_id' => auth()->id(), 'created_at' => $now, 'uraian' => 'Pembelian Pupuk', 'kategori' => 'Bahan Pemeliharaan', 'jumlah' => null],
            ];
            Kebutuhan::insert($data);
        }

        // Ambil data penjualan
        $penjualan = Penjualan::user()->tahun()->get();

        // Kategori kebutuhan yang akan diambil
        $kategori_kebutuhan = [
            'Sewa Tanah/Bangunan',
            'Sewa Alat',
            'Pengadaan Alat',
            'Sarana Prasarana',
            'Bibit/ Benih',
            'Bahan Pemeliharaan',
            'Pembiayaan-pembiayaan mingguan',
            'Pekerja',
            'Distribusi'
        ];

        // Ambil data kebutuhan berdasarkan kategori
        $kebutuhan = collect($kategori_kebutuhan)->mapWithKeys(function ($kategori) {
            return [$kategori => Kebutuhan::user()->kategori($kategori)->tahun()->get()];
        });

        // Return view dengan data
        return view('akps.index', [
            'title' => 'ANALISA KETAHANAN PANGAN',
            'akp' => $akp,
            'penjualans' => $penjualan,
            'kebutuhans' => $kebutuhan,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Akps $akps)
    {
        $validate = $request->validate([
            'status' => 'required|string',
            'dana' => 'required|numeric',
            'alokasi' => 'required|numeric',
            'tematik' => 'required|string',
            'pendapatan' => 'required|string',
            'pembiayaan' => 'required|string',
        ]);


        Akps::where('id', $akps->id)->update($validate);

        return redirect()->back()->with('success', 'Berhasil diupdate');
    }
}
