<?php

namespace App\Http\Controllers;

use App\Models\Rasio;
use App\Models\Proker;
use App\Models\Alokasi;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProgramRequest;

class PenambahanModalController extends Controller
{
    public function penambahanModal()
    {
        $title = 'G. RENCANA PENAMBAHAN MODAL';
        $back = '/proker/rencana/kegiatan';
        $proker = Proker::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $alokasis = Alokasi::user()->where('tahun', session('selected_year', date('Y')))->get();
        $rasios = Rasio::user()->where('tahun', session('selected_year', date('Y')))->get();
        return view('proker.penambahan_modal', [
            'title' => $title,
            'back' => $back,
            'alokasis' => $alokasis,
            'rasios' => $rasios,
            'proker' => $proker,
        ]);
    }

    public function penambahanModalUpdate(Request $request, Proker $proker)
    {

        // Validasi input dari request untuk memastikan data yang dibutuhkan ada
        $validated = $request->validate([
            'kualititif' => 'nullable|string',
            'strategi' => 'nullable|string',
            'unit_usaha' => 'nullable|string',
            'status_unit' => 'nullable|string',
            'jumlah' => 'nullable|numeric',
            'aspek_pasar' => 'nullable|string',
            'aspek_keuangan' => 'nullable',
            'aspek_lainya' => 'nullable|string',
            'strategi_pemasaran' => 'nullable|string',
            'kesimpulan' => 'nullable|string',
        ]);

        $validated['aspek_keuangan'] = json_encode($request->aspek_keuangan);

        // Update hanya data yang diterima dan valid
        $proker->update($validated);

        return back();
    }

    public function updateStatus(Request $request, Proker $proker)
    {
        // Validasi input
        $validated = $request->validate([
            'status' => 'required|string',
        ]);

        // Temukan data berdasarkan ID
        $proker = Proker::where('id', $proker->id)->update($validated);

        // Kembalikan respon sukses
        return response()->json(['message' => 'Status berhasil diperbarui!']);
    }

    public function alokasiStore(StoreProgramRequest $request)
    {
        $validated = $request->validated();
        $tahun = session('selected_year', date('Y'));
        $user_id = auth()->id();
        foreach ($validated['data'] as $program => $value) {
            $value['tahun'] = $tahun;
            $value['user_id'] = $user_id;
            Alokasi::create($value);
        }

        return redirect()->back()->with('success', 'Data program berhasil disimpan!');
    }


    public function update(Request $request, $id)
    {
        $alokasi = Alokasi::findOrFail($id);
        $alokasi->update([
            'item' => $request->item,
            'jenis_biaya' => $request->jenis_biaya,
            'nilai' => $request->nilai,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $alokasi = Alokasi::findOrFail($id);
        $alokasi->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }


    public function resikoStore(Request $request)
    {
        $validated = $request->all();
        $tahun = session('selected_year', date('Y'));
        $user_id = auth()->id();
        foreach ($validated['data'] as $program => $value) {
            $value['tahun'] = $tahun;
            $value['user_id'] = $user_id;
            Rasio::create($value);
        }

        return redirect()->back()->with('success', 'Data program berhasil disimpan!');
    }


    public function resikoUpdate(Request $request, $id)
    {
        $request->validate([
            'resiko' => 'required|string',
            'penyebab' => 'required|string',
            'antisipasi' => 'required|string',
        ]);

        $resiko = Rasio::findOrFail($id);
        $resiko->update([
            'resiko' => $request->resiko,
            'penyebab' => $request->penyebab,
            'antisipasi' => $request->antisipasi,
        ]);

        return redirect()->back()->with('success', 'Resiko usaha berhasil diperbarui.');
    }

    public function resikoDelete($id)
    {
        $resiko = Rasio::findOrFail($id);
        $resiko->delete();

        return redirect()->back()->with('success', 'Resiko usaha berhasil dihapus.');
    }
}
