<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipPerjanjianKerja;
use Carbon\Carbon;

class ArsipPerjanjianKerjaController extends Controller
{
    public function index()
    {
        $items = ArsipPerjanjianKerja::orderBy('id', 'asc')->get();
        return view('spj.arsip_perjanjian_kerja.index', compact('items'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_kerjasama'     => 'required|string|max:255',
            'nomor_dokumen'      => 'nullable|string|max:255',
            'pihak'              => 'nullable|string|max:255',
            'bentuk_kerja_sama'  => 'nullable|string|max:255',
            'deskripsi'          => 'nullable|string',
            'durasi'             => 'nullable|string|max:255',
            'link_gdrive'        => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // jika durasi kosong -> set default 'Berjalan'
        if (empty($validated['durasi'])) {
            $validated['durasi'] = 'Berjalan';
        }

        // jika nomor tidak dikirim, generate otomatis: PKS/{no}/{ROMAWI}/{tahun}
        if (empty($validated['nomor_dokumen'])) {
            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;
            $countThisYear = ArsipPerjanjianKerja::whereYear('created_at', $year)->count();
            $no = $countThisYear + 1;
            $monthRoman = $this->monthToRoman($month);
            $validated['nomor_dokumen'] = "PKS/{$no}/{$monthRoman}/{$year}";
        }

        $item = ArsipPerjanjianKerja::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Perjanjian kerja berhasil ditambahkan!', 'data' => $item], 201);
        }

        return back()->with('success', 'Perjanjian kerja berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nama_kerjasama'     => 'required|string|max:255',
            'nomor_dokumen'      => 'nullable|string|max:255',
            'pihak'              => 'nullable|string|max:255',
            'bentuk_kerja_sama'  => 'nullable|string|max:255',
            'deskripsi'          => 'nullable|string',
            'durasi'             => 'nullable|string|max:255',
            'link_gdrive'        => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $item = ArsipPerjanjianKerja::find($id);
        if (!$item) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => "Perjanjian kerja dengan ID $id tidak ditemukan"], 404);
            return back()->with('error', "Perjanjian kerja dengan ID $id tidak ditemukan");
        }

        // jangan overwrite nomor jika tidak dikirim
        if (empty($validated['nomor_dokumen'])) unset($validated['nomor_dokumen']);
        // jangan overwrite durasi jika tidak dikirim
        if (!array_key_exists('durasi', $validated) || empty($validated['durasi'])) unset($validated['durasi']);

        $item->update($validated);

        if ($request->ajax()) return response()->json(['success' => true, 'message' => 'Perjanjian kerja berhasil diperbarui!', 'data' => $item]);
        return back()->with('success', 'Perjanjian kerja berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $item = ArsipPerjanjianKerja::find($id);
        if (!$item) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => "Perjanjian kerja dengan ID $id tidak ditemukan"], 404);
            return back()->with('error', "Perjanjian kerja dengan ID $id tidak ditemukan");
        }

        $item->delete();

        if ($request->ajax()) return response()->json(['success' => true, 'message' => 'Perjanjian kerja berhasil dihapus!']);
        return back()->with('success', 'Perjanjian kerja berhasil dihapus!');
    }

    /**
     * Tandai selesai (aksi dari tombol checklist)
     */
    public function complete(Request $request, $id)
    {
        $item = ArsipPerjanjianKerja::find($id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => "Perjanjian kerja dengan ID $id tidak ditemukan"], 404);
        }

        $item->durasi = 'Selesai';
        $item->save();

        return response()->json(['success' => true, 'message' => 'Perjanjian kerja ditandai Selesai', 'data' => $item]);
    }

    /**
     * Helper: konversi bulan (1-12) ke angka Romawi
     */
    private function monthToRoman($month)
    {
        $map = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'];
        return $map[intval($month)] ?? (string)$month;
    }
}
