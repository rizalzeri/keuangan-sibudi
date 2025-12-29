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
            'no_wa'      => 'required',
            'kabupaten'  => 'required',
            'kecamatan'  => 'required',
            'desa'       => 'required',
            'password'   => 'required|string|min:8|confirmed',
            'langganan'  => 'nullable|integer'
        ]);

        // hash password
        $validated['password'] = Hash::make($request->password);

        // tanggal langganan berdasarkan pilihan (default 0 => akun baru = sekarang)
        $months = intval($request->langganan ?: 0);
        $validated['tgl_langganan'] = date('Y-m-d', strtotime('+' . $months . ' months'));

        // status awal: false (akun belum aktif) — ubah jika ingin auto aktif
        $validated['status'] = false;

        // Set otomatis: role dan user_roles_id
        $validated['role'] = 'bumdes';
        $validated['user_roles_id'] = 3;

        // referral default (tidak ditampilkan di form)
        $validated['referral'] = 1;

        // create user
        $user = User::create($validated);

        if ($user) {
            $userId = $user->id;

            // Buat Ekuit kalau belum ada
            if (!Ekuit::where('user_id', $userId)->exists()) {
                Ekuit::create(['user_id' => $userId]);
            }

            // Buat Rekonsiliasi default kalau belum ada
            if (!Rekonsiliasi::where('user_id', $userId)->exists()) {
                Rekonsiliasi::insert([
                    ['posisi' => 'Kas di tangan', 'user_id' => $userId],
                    ['posisi' => 'Bank Jateng', 'user_id' => $userId]
                ]);
            }

            // buat profil
            Profil::create([
                'user_id'   => $userId,
                'no_wa'     => $validated['no_wa'],
                'kabupaten' => $validated['kabupaten'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'desa'      => $validated['desa'] ?? null,
            ]);
        }

        // redirect ke halaman login dengan pesan sukses
        return redirect('/login')->with('success', 'Akun berhasil dibuat. Silakan login.');
    }


    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('error', 'User Berhasil di hapus');
    }
}
