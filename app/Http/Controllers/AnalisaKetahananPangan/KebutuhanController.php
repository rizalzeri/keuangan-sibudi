<?php

namespace App\Http\Controllers\AnalisaKetahananPangan;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Kebutuhan;

class KebutuhanController extends Controller
{
    public function update(Request $request)
    {

        $request->validate([
            'data.*.uraian' => 'nullable',
            'data.*.satuan' => 'nullable',
            'data.*.harga' => 'nullable',
            'data.*.valume' => 'nullable',
            'data.*.jumlah' => 'nullable',
        ]);

        $created_at = Carbon::now()->year(session('selected_year', date('Y')));
        $user_id = auth()->id();

        // Ambil semua ID yang dikirim dari request
        $ids = collect($request->data)->pluck('id')->filter()->toArray();

        $array_ids = array_map('intval', $ids);

        $array_id = Kebutuhan::user()->tahun()->kategori($request->kategori)->pluck('id')->toArray();

        $banding = array_diff($array_id, $array_ids);
        $hasil = array_values($banding);

        // Simpan atau update data
        foreach ($request->data as $value) {
            $value['created_at'] = $created_at;
            $value['user_id'] = $user_id;
            $value['kategori'] = $request->kategori;

            Kebutuhan::updateOrCreate(
                ['id' => $value['id'] ?? null],
                $value
            );
        }

        Kebutuhan::whereIn('id', $hasil)->delete();

        return redirect()->back()->with('success', 'Data penjulan berhasil di update');
    }
}
