<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\Dithn;
use App\Models\Ekuit;
use Illuminate\Http\Request;

class BagiHasilController extends Controller
{
    public function index()
    {
        $ditahan = Ekuit::user()->get()->first();




        return view('bagi_hasil.index', ['labaBerjalan' => labaRugi(session('selected_year', date('Y')))['totalLabaRugi'], 'ditahan' => $ditahan]);
    }

    public function update(Request $request, Ekuit $ekuit)
    {
        $validated = $request->validate([
            'pades' => 'required|numeric',
            'lainya' => 'required|numeric',
            'akumulasi' => 'required|numeric',
        ]);

        $validated['user_id'] = auth()->user()->id;

        Ekuit::where('id', $ekuit->id)->update($validated);

        return redirect()->back()->with('success', 'Berhasil di rubah');
    }
}
