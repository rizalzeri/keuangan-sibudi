<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipPersonalisasi;
use App\Models\ArsipOtorisasiMengetahui;
use App\Models\ArsipOtorisasiPersetujuan;
use App\Models\ArsipKlasifikasiTransaksi;
use Illuminate\Support\Facades\Auth;

class ArsipKelolaAkunController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = auth()->id();

        // hanya ambil yang milik user saat ini
        $personalisasi = ArsipPersonalisasi::where('users_id', $userId)
            ->orderBy('id')->get();

        $klasifikasi = ArsipKlasifikasiTransaksi::with(['mengetahui.personalisasi','persetujuan.personalisasi'])
            ->where('users_id', $userId)
            ->orderBy('id')
            ->get();

        $user = Auth::user();

        return view('spj.arsip_kelola_akun.index', compact('personalisasi','klasifikasi','user'));
    }

    public function getAccount()
    {
        $user = Auth::user();
        return response()->json(['success' => true, 'data' => $user]);
    }

    public function updateAccount(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nama_bumdes' => 'nullable|string|max:255',
            'alamat_bumdes' => 'nullable|string|max:255',
            'nomor_hukum_bumdes' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update($data);

        return response()->json(['success' => true, 'data' => $user]);
    }

    // ---------------- Personalisasi CRUD ----------------
    public function storePersonalisasi(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        $data['users_id'] = auth()->id();

        $item = ArsipPersonalisasi::create($data);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function updatePersonalisasi(Request $request, $id)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        $item = ArsipPersonalisasi::where('id', $id)
            ->where('users_id', auth()->id())
            ->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Personalisasi tidak ditemukan atau bukan milik Anda'], 404);
        }

        $item->update($data);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroyPersonalisasi($id)
    {
        $item = ArsipPersonalisasi::where('id', $id)
            ->where('users_id', auth()->id())
            ->firstOrFail();

        $dipakaiMengetahui = ArsipOtorisasiMengetahui::where('arsip_personalisasi_id', $id)->exists();
        $dipakaiPersetujuan = ArsipOtorisasiPersetujuan::where('arsip_personalisasi_id', $id)->exists();

        if ($dipakaiMengetahui || $dipakaiPersetujuan) {
            return response()->json([
                'message' => 'Personalisasi tidak dapat dihapus karena masih digunakan pada klasifikasi transaksi sebagai Mengetahui atau Persetujuan.'
            ], 422);
        }

        $item->delete();

        return response()->json(['success' => true]);
    }

    // ---------------- Mengetahui / Persetujuan (CRUD) ----------------
    public function storeMengetahui(Request $request)
    {
        $data = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'arsip_personalisasi_id' => 'required|exists:arsip_personalisasi,id',
        ]);

        // pastikan personalisasi milik user
        $pp = ArsipPersonalisasi::where('id', $data['arsip_personalisasi_id'])
            ->where('users_id', auth()->id())
            ->first();

        if (!$pp) {
            return response()->json(['success'=>false,'message'=>'Personalisasi tidak ditemukan atau bukan milik Anda'], 404);
        }

        $item = ArsipOtorisasiMengetahui::firstOrCreate(
            [
                'arsip_personalisasi_id' => $data['arsip_personalisasi_id'],
                'users_id' => auth()->id()
            ],
            [
                'kategori' => $data['kategori'] ?? null
            ]
        );

        return response()->json(['success' => true, 'data' => $item]);
    }

    public function updateMengetahui(Request $request, $id)
    {
        $data = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'arsip_personalisasi_id' => 'required|exists:arsip_personalisasi,id',
        ]);

        $item = ArsipOtorisasiMengetahui::where('id', $id)
            ->where('users_id', auth()->id())
            ->first();

        if (!$item) return response()->json(['success'=>false,'message'=>'Data tidak ditemukan atau bukan milik Anda'], 404);

        // pastikan personalisasi milik user
        $pp = ArsipPersonalisasi::where('id', $data['arsip_personalisasi_id'])
            ->where('users_id', auth()->id())
            ->first();
        if (!$pp) return response()->json(['success'=>false,'message'=>'Personalisasi invalid'], 422);

        $item->update([
            'kategori' => $data['kategori'] ?? null,
            'arsip_personalisasi_id' => $data['arsip_personalisasi_id']
        ]);

        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroyMengetahui($id)
    {
        $item = ArsipOtorisasiMengetahui::where('id', $id)
            ->where('users_id', auth()->id())
            ->firstOrFail();

        $item->delete();
        return response()->json(['success' => true]);
    }

    public function storePersetujuan(Request $request)
    {
        $data = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'arsip_personalisasi_id' => 'required|exists:arsip_personalisasi,id',
        ]);

        $pp = ArsipPersonalisasi::where('id', $data['arsip_personalisasi_id'])
            ->where('users_id', auth()->id())
            ->first();
        if (!$pp) return response()->json(['success'=>false,'message'=>'Personalisasi invalid'], 422);

        $item = ArsipOtorisasiPersetujuan::firstOrCreate(
            [
                'arsip_personalisasi_id' => $data['arsip_personalisasi_id'],
                'users_id' => auth()->id()
            ],
            ['kategori' => $data['kategori'] ?? null]
        );

        return response()->json(['success' => true, 'data' => $item]);
    }

    public function updatePersetujuan(Request $request, $id)
    {
        $data = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'arsip_personalisasi_id' => 'required|exists:arsip_personalisasi,id',
        ]);

        $item = ArsipOtorisasiPersetujuan::where('id', $id)
            ->where('users_id', auth()->id())
            ->first();

        if (!$item) return response()->json(['success'=>false,'message'=>'Data tidak ditemukan atau bukan milik Anda'], 404);

        $pp = ArsipPersonalisasi::where('id', $data['arsip_personalisasi_id'])
            ->where('users_id', auth()->id())
            ->first();
        if (!$pp) return response()->json(['success'=>false,'message'=>'Personalisasi invalid'], 422);

        $item->update([
            'kategori' => $data['kategori'] ?? null,
            'arsip_personalisasi_id' => $data['arsip_personalisasi_id']
        ]);

        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroyPersetujuan($id)
    {
        $item = ArsipOtorisasiPersetujuan::where('id', $id)
            ->where('users_id', auth()->id())
            ->firstOrFail();

        $item->delete();
        return response()->json(['success' => true]);
    }

    // ---------------- Klasifikasi Transaksi CRUD ----------------
    public function storeKlasifikasi(Request $request)
    {
        $data = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'nominal' => 'required|numeric',
            'mengetahui_personalisasi_id' => 'nullable|exists:arsip_personalisasi,id',
            'persetujuan_personalisasi_id' => 'nullable|exists:arsip_personalisasi,id',
        ]);

        $userId = auth()->id();

        // create/reuse otorisasi mengetahui (scoped to user)
        $mengetahuiId = null;
        if (!empty($data['mengetahui_personalisasi_id'])) {
            $men = ArsipOtorisasiMengetahui::firstOrCreate(
                ['arsip_personalisasi_id' => $data['mengetahui_personalisasi_id'], 'users_id' => $userId],
                ['kategori' => null]
            );
            $mengetahuiId = $men->id;
        }

        // create/reuse otorisasi persetujuan (scoped to user)
        $persetujuanId = null;
        if (!empty($data['persetujuan_personalisasi_id'])) {
            $p = ArsipOtorisasiPersetujuan::firstOrCreate(
                ['arsip_personalisasi_id' => $data['persetujuan_personalisasi_id'], 'users_id' => $userId],
                ['kategori' => null]
            );
            $persetujuanId = $p->id;
        }

        $item = ArsipKlasifikasiTransaksi::create([
            'kategori' => $data['kategori'] ?? null,
            'nominal' => $data['nominal'],
            'arsip_otorisasi_mengetahui_id' => $mengetahuiId,
            'arsip_otorisasi_persetujuan_id' => $persetujuanId,
            'users_id' => $userId,
        ]);

        return response()->json(['success' => true, 'data' => $item]);
    }

    public function updateKlasifikasi(Request $request, $id)
    {
        $data = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'nominal' => 'required|numeric',
            'mengetahui_personalisasi_id' => 'nullable|exists:arsip_personalisasi,id',
            'persetujuan_personalisasi_id' => 'nullable|exists:arsip_personalisasi,id',
        ]);

        $userId = auth()->id();

        $item = ArsipKlasifikasiTransaksi::where('id', $id)
            ->where('users_id', $userId)
            ->first();

        if (!$item) return response()->json(['success'=>false,'message'=>'Data tidak ditemukan atau bukan milik Anda'], 404);

        // process mengetahui
        $mengetahuiId = $item->arsip_otorisasi_mengetahui_id;
        if (!empty($data['mengetahui_personalisasi_id'])) {
            $men = ArsipOtorisasiMengetahui::firstOrCreate(
                ['arsip_personalisasi_id' => $data['mengetahui_personalisasi_id'], 'users_id' => $userId],
                ['kategori' => null]
            );
            $mengetahuiId = $men->id;
        } else {
            $mengetahuiId = null;
        }

        // process persetujuan
        $persetujuanId = $item->arsip_otorisasi_persetujuan_id;
        if (!empty($data['persetujuan_personalisasi_id'])) {
            $p = ArsipOtorisasiPersetujuan::firstOrCreate(
                ['arsip_personalisasi_id' => $data['persetujuan_personalisasi_id'], 'users_id' => $userId],
                ['kategori' => null]
            );
            $persetujuanId = $p->id;
        } else {
            $persetujuanId = null;
        }

        $item->update([
            'kategori' => $data['kategori'] ?? null,
            'nominal' => $data['nominal'],
            'arsip_otorisasi_mengetahui_id' => $mengetahuiId,
            'arsip_otorisasi_persetujuan_id' => $persetujuanId,
        ]);

        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroyKlasifikasi($id)
    {
        $item = ArsipKlasifikasiTransaksi::where('id', $id)
            ->where('users_id', auth()->id())
            ->firstOrFail();

        $item->delete();
        return response()->json(['success' => true]);
    }

    // classifyByNominal tetap
    public function classifyByNominal(Request $request)
    {
        $nominal = (int) $request->query('nominal', 0);
        $userId = auth()->id();

        $klasList = ArsipKlasifikasiTransaksi::where('users_id', $userId)->orderBy('nominal', 'asc')->get();

        if ($klasList->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Belum ada data klasifikasi'], 404);
        }

        $selected = $klasList->first(function($k) use ($nominal) {
            return $nominal <= (float) $k->nominal;
        });

        if (!$selected) $selected = $klasList->last();

        $recommended_mengetahui = null;
        $recommended_persetujuan = null;

        if (!empty($selected->arsip_otorisasi_mengetahui_id)) {
            $m = ArsipOtorisasiMengetahui::with('personalisasi')->where('users_id', $userId)
                ->find($selected->arsip_otorisasi_mengetahui_id);
            if ($m) $recommended_mengetahui = [
                'id' => $m->id,
                'personalisasi_id' => $m->arsip_personalisasi_id,
                'nama' => optional($m->personalisasi)->nama,
                'jabatan' => optional($m->personalisasi)->jabatan,
            ];
        }

        if (!empty($selected->arsip_otorisasi_persetujuan_id)) {
            $p = ArsipOtorisasiPersetujuan::with('personalisasi')->where('users_id', $userId)
                ->find($selected->arsip_otorisasi_persetujuan_id);
            if ($p) $recommended_persetujuan = [
                'id' => $p->id,
                'personalisasi_id' => $p->arsip_personalisasi_id,
                'nama' => optional($p->personalisasi)->nama,
                'jabatan' => optional($p->personalisasi)->jabatan,
            ];
        }

        $mengetahui_options = ArsipOtorisasiMengetahui::with('personalisasi')->where('users_id', $userId)->get()->map(function($m) {
            return [
                'otorisasi_id' => $m->id,
                'personalisasi_id' => $m->arsip_personalisasi_id,
                'nama' => optional($m->personalisasi)->nama,
                'jabatan' => optional($m->personalisasi)->jabatan,
                'kategori' => $m->kategori
            ];
        })->values();

        $persetujuan_options = ArsipOtorisasiPersetujuan::with('personalisasi')->where('users_id', $userId)->get()->map(function($p) {
            return [
                'otorisasi_id' => $p->id,
                'personalisasi_id' => $p->arsip_personalisasi_id,
                'nama' => optional($p->personalisasi)->nama,
                'jabatan' => optional($p->personalisasi)->jabatan,
                'kategori' => $p->kategori
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'klasifikasi' => [
                    'id' => $selected->id,
                    'kategori' => $selected->kategori,
                    'nominal' => (float) $selected->nominal
                ],
                'recommended' => [
                    'mengetahui' => $recommended_mengetahui,
                    'persetujuan' => $recommended_persetujuan
                ],
                'mengetahui_options' => $mengetahui_options,
                'persetujuan_options' => $persetujuan_options
            ]
        ]);
    }
}
