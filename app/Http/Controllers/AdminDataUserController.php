<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ekuit;
use App\Models\Profil;
use App\Models\Langganan;
use App\Models\Rekonsiliasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminDataUserController extends Controller
{
    public function index($kecamatan)
    {

        $users = User::with('profil')
            ->whereHas('profil', function ($query) use ($kecamatan) {
                $query->where('kecamatan', $kecamatan);
            })
            ->latest()
            ->get();

        $langganans = Langganan::orderBy('jumlah_bulan', 'asc')->get();
        return view('admin.data_user.index', ['users' => $users, 'langganans' => $langganans]);
    }
    public function allUser()
    {

        $users = User::with('profil')
            ->latest()
            ->get();

        $langganans = Langganan::orderBy('jumlah_bulan', 'asc')->get();
        return view('admin.data_user.allUser', ['users' => $users, 'langganans' => $langganans]);
    }

    public function ubahPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required'
        ]);

        $validated['password'] = Hash::make($request->password);

        User::where('id', $user->id)->update($validated);

        return redirect('/admin/data-user')->with('success', $user->name . ' Berhasil dirubah passwordnya');
    }

    public function langganan(Request $request, User $user)
    {
        $validated = $request->validate([]);

        // Get the current date
        $currentDate = now();

        // Check if the user's subscription date (`tanggal_langganan`) is greater than the current date
        if ($user->tgl_langganan && $user->tgl_langganan > $currentDate) {
            // Add the subscription months to the existing `tanggal_langganan`
            $validated['tgl_langganan'] = date('Y-m-d', strtotime('+' . $request->langganan . ' months', strtotime($user->tgl_langganan)));
        } else {
            // Add the subscription months to today's date
            $validated['tgl_langganan'] = date('Y-m-d', strtotime('+' . $request->langganan . ' months', strtotime($currentDate)));
        }

        $validated['status'] = true;


        User::where('id', $user->id)->update($validated);

        return redirect('/admin/data-user')->with('success', $user->name . ' Berhasil diupdate Langganan');
    }

    public function create()
    {
        return view('admin.data_user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|min:3|max:100',
            'email'      => 'required|email|unique:users,email',
            'referral'   => 'required',
            'no_wa'      => 'required',
            'kabupaten'  => 'required',
            'kecamatan'  => 'required',
            'desa'       => 'required',
        ]);

        $validated['password'] = Hash::make($request->password);
        $validated['tgl_langganan'] = date('Y-m-d', strtotime('+' . $request->langganan . ' months'));
        $validated['status'] = true;



        if (User::create($validated)) {
            $userId = User::latest()->first()->id;

            $existingEkuit = Ekuit::where('user_id', $userId)->first();

            if (!$existingEkuit) {
                Ekuit::create(['user_id' => $userId]);
            }

            $rekonsiliasi = Rekonsiliasi::where('user_id', $userId)->first();

            if (!$rekonsiliasi) {
                Rekonsiliasi::insert([
                    ['posisi' => 'Kas di tangan', 'user_id' => $userId],
                    ['posisi' => 'Bank Jateng', 'user_id' => $userId]
                ]);
            }



            Profil::create([
                'user_id'   => User::latest()->first()->id,
                'no_wa'     => $validated['no_wa'],
                'kabupaten' => $validated['kabupaten'] ?? null, // hanya simpan nama
                'kecamatan' => $validated['kecamatan'] ?? null,
                'desa'      => $validated['desa'] ?? null,
            ]);
        }

        return redirect('/admin/wilayah/kecamatan/' . $validated['kecamatan'])->with('success', 'User Berhasil di tambah');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('error', 'User Berhasil di hapus');
    }
}
