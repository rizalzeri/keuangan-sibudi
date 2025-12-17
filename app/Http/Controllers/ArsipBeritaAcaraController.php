<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipBeritaAcara;
use Carbon\Carbon;

class ArsipBeritaAcaraController extends Controller
{
    public function index()
    {
        $items = ArsipBeritaAcara::orderBy('id', 'asc')->get();
        return view('spj.arsip_berita_acara.index', compact('items'));
    }

    public function store(Request $request)
    {
        $rules = [
            'judul_berita_acara' => 'required|string|max:255',
            'nomor_dokumen'      => 'nullable|string|max:255',
            'tanggal_peristiwa'  => 'required|date',
            'deskripsi'          => 'nullable|string',
            'link_gdrive'        => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // Jika nomor tidak dikirim, generate otomatis dengan format BA/{no}/{ROMAWI}/{tahun}
        if (empty($validated['nomor_dokumen'])) {
            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;
            // hitung jumlah record pada tahun yang sama -> nomor = count + 1
            $countThisYear = ArsipBeritaAcara::whereYear('created_at', $year)->count();
            $no = $countThisYear + 1;
            $monthRoman = $this->monthToRoman($month);
            $validated['nomor_dokumen'] = "BA/{$no}/{$monthRoman}/{$year}";
        }

        $ba = ArsipBeritaAcara::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Berita acara berhasil ditambahkan!',
                'data'    => $ba
            ], 201);
        }

        return back()->with('success', 'Berita acara berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'judul_berita_acara' => 'required|string|max:255',
            'nomor_dokumen'      => 'nullable|string|max:255',
            'tanggal_peristiwa'  => 'required|date',
            'deskripsi'          => 'nullable|string',
            'link_gdrive'        => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $ba = ArsipBeritaAcara::find($id);
        if (!$ba) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Berita acara dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Berita acara dengan ID $id tidak ditemukan");
        }

        // Jika nomor tidak dikirim (atau kosong) -> jangan overwrite nomor lama
        if (empty($validated['nomor_dokumen'])) {
            unset($validated['nomor_dokumen']);
        }

        $ba->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Berita acara berhasil diperbarui!',
                'data'    => $ba
            ]);
        }

        return back()->with('success', 'Berita acara berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $ba = ArsipBeritaAcara::find($id);
        if (!$ba) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Berita acara dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Berita acara dengan ID $id tidak ditemukan");
        }

        $ba->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Berita acara berhasil dihapus!']);
        }

        return back()->with('success', 'Berita acara berhasil dihapus!');
    }

    /**
     * Helper: konversi bulan (1-12) ke angka Romawi
     */
    private function monthToRoman($month)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $map[intval($month)] ?? (string)$month;
    }
}
