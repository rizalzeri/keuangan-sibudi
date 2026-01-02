<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipNotulenRapat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ArsipNotulenRapatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = ArsipNotulenRapat::where('users_id', auth()->id())
            ->orderBy('id', 'desc')->get();
        return view('spj.arsip_notulen_rapat.index', compact('items'));
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal_notulen_rapat' => 'required|date',
            'waktu' => 'nullable|date_format:H:i',
            'tempat' => 'nullable|string|max:255',
            'agenda' => 'nullable|string|max:255',
            'penyelenggara' => 'nullable|string|max:255',
            'link_gdrive' => 'nullable|string',
        ];

        $validated = $request->validate($rules);
        $validated['users_id'] = auth()->id();

        $n = ArsipNotulenRapat::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notulen berhasil ditambahkan!',
                'data'    => $n
            ], 201);
        }

        return back()->with('success', 'Notulen berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'tanggal_notulen_rapat' => 'required|date',
            'waktu' => 'nullable|date_format:H:i',
            'tempat' => 'nullable|string|max:255',
            'agenda' => 'nullable|string|max:255',
            'penyelenggara' => 'nullable|string|max:255',
            'link_gdrive' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $n = ArsipNotulenRapat::where('id', $id)
            ->where('users_id', auth()->id())
            ->first();

        if (!$n) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Notulen dengan ID $id tidak ditemukan atau bukan milik Anda"], 404);
            }
            return back()->with('error', "Notulen dengan ID $id tidak ditemukan atau bukan milik Anda");
        }

        $n->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Notulen berhasil diperbarui!', 'data' => $n]);
        }

        return back()->with('success', 'Notulen berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $n = ArsipNotulenRapat::where('id', $id)
            ->where('users_id', auth()->id())
            ->first();

        if (!$n) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Notulen dengan ID $id tidak ditemukan atau bukan milik Anda"], 404);
            }
            return back()->with('error', "Notulen dengan ID $id tidak ditemukan atau bukan milik Anda");
        }

        $n->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Notulen berhasil dihapus!']);
        }

        return back()->with('success', 'Notulen berhasil dihapus!');
    }

    public function cetak($id)
    {
        $notulen = ArsipNotulenRapat::where('id', $id)
            ->where('users_id', auth()->id())
            ->firstOrFail();

        $hari = Carbon::parse($notulen->tanggal_notulen_rapat)->locale('id')->isoFormat('dddd');
        $tanggal = Carbon::parse($notulen->tanggal_notulen_rapat)->format('d/m/Y');

        $data = [
            'hari' => ucfirst($hari),
            'tanggal' => $tanggal,
            'waktu' => $notulen->waktu ?? '-',
            'tempat' => $notulen->tempat ?? '-',
            'acara' => $notulen->agenda ?? '-',
            'peserta' => [],
        ];

        $pdf = PDF::loadView('spj.arsip_notulen_rapat.cetak_pdf', $data);

        return $pdf->download('notulen-rapat-' . $notulen->id . '.pdf');
    }
}
