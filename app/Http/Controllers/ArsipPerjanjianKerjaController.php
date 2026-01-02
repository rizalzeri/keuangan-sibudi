<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipPerjanjianKerja;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ArsipPerjanjianKerjaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = ArsipPerjanjianKerja::where('users_id', auth()->id())
            ->orderBy('id', 'asc')->get();
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

        if (empty($validated['durasi'])) {
            $validated['durasi'] = 'Berjalan';
        }

        if (empty($validated['nomor_dokumen'])) {
            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;
            $countThisYear = ArsipPerjanjianKerja::where('users_id', auth()->id())
                ->whereYear('created_at', $year)->count();
            $no = $countThisYear + 1;
            $monthRoman = $this->monthToRoman($month);
            $validated['nomor_dokumen'] = "PKS/{$no}/{$monthRoman}/{$year}";
        }

        $validated['users_id'] = auth()->id();

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

        $item = ArsipPerjanjianKerja::where('id', $id)
            ->where('users_id', auth()->id())
            ->first();

        if (!$item) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => "Perjanjian kerja dengan ID $id tidak ditemukan atau bukan milik Anda"], 404);
            return back()->with('error', "Perjanjian kerja dengan ID $id tidak ditemukan atau bukan milik Anda");
        }

        if (empty($validated['nomor_dokumen'])) unset($validated['nomor_dokumen']);
        if (!array_key_exists('durasi', $validated) || empty($validated['durasi'])) unset($validated['durasi']);

        $item->update($validated);

        if ($request->ajax()) return response()->json(['success' => true, 'message' => 'Perjanjian kerja berhasil diperbarui!', 'data' => $item]);
        return back()->with('success', 'Perjanjian kerja berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $item = ArsipPerjanjianKerja::where('id', $id)
            ->where('users_id', auth()->id())
            ->first();

        if (!$item) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => "Perjanjian kerja dengan ID $id tidak ditemukan atau bukan milik Anda"], 404);
            return back()->with('error', "Perjanjian kerja dengan ID $id tidak ditemukan atau bukan milik Anda");
        }

        $item->delete();

        if ($request->ajax()) return response()->json(['success' => true, 'message' => 'Perjanjian kerja berhasil dihapus!']);
        return back()->with('success', 'Perjanjian kerja berhasil dihapus!');
    }

    public function complete(Request $request, $id)
    {
        $item = ArsipPerjanjianKerja::where('id', $id)
            ->where('users_id', auth()->id())
            ->first();

        if (!$item) return response()->json(['success' => false, 'message' => "Perjanjian kerja dengan ID $id tidak ditemukan atau bukan milik Anda"], 404);

        $item->durasi = 'Selesai';
        $item->save();

        return response()->json(['success' => true, 'message' => 'Perjanjian kerja ditandai Selesai', 'data' => $item]);
    }

    private function monthToRoman($month)
    {
        $map = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'];
        return $map[intval($month)] ?? (string)$month;
    }
}
