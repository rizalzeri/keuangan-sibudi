<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function visiMisi()
    {
        $profile = auth()->user()->profil;
        return view('visiMisi.index', ['profil' => $profile]);
    }

    public function index()
    {

        session('selected_year', date('Y'));
        $profile = auth()->user()->profil;
        return view('profile.index', ['profil' => $profile]);
    }

    // Update BUMDes data
    public function update(Request $request, Profil $profil)
    {
        // Validate the request data
        $validated =  $request->validate([
            'nm_bumdes' => 'nullable|string|max:100',
            'desa' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'nm_direktur' => 'nullable|string|max:50',
            'nm_serkertaris' => 'nullable|string|max:50',
            'nm_bendahara' => 'nullable|string|max:50',
            'nm_pengawas' => 'nullable|string|max:50',
            'nm_penasehat' => 'nullable|string|max:50',
            'unt_usaha1' => 'nullable|string|max:50',
            'nm_kepyun1' => 'nullable|string|max:50',
            'unt_usaha2' => 'nullable|string|max:50',
            'nm_kepyun2' => 'nullable|string|max:50',
            'unt_usaha3' => 'nullable|string|max:50',
            'nm_kepyun3' => 'nullable|string|max:50',
            'unt_usaha4' => 'nullable|string|max:50',
            'nm_kepyun4' => 'nullable|string|max:50',
            'no_badan' => 'nullable|string|max:50',
            'no_perdes' => 'nullable|string|max:50',
            'no_sk' => 'nullable|string|max:50',
            'no_wa' => 'nullable|string|max:50',
            'kabupaten' => 'nullable|string|max:50',
            'unt_usaha1' => 'nullable|string|max:50',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
        ]);


        // Find the Bumdes by its ID
        $bumdes = Profil::findOrFail($profil->id);

        histori(rendem(), 'profils', $profil->toArray(), 'update', $profil->id);

        // Update the data in the database
        $bumdes->update($validated);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Data BUMDes berhasil diperbarui.');
    }
}
