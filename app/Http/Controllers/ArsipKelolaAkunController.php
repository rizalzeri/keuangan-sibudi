<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipPersonalisasi;
use App\Models\ArsipOtorisasiMengetahui;
use App\Models\ArsipOtorisasiPersetujuan;
use App\Models\ArsipKlasifikasiTransaksi;

class ArsipKelolaAkunController extends Controller
{
    public function index()
    {
        $personalisasi = ArsipPersonalisasi::orderBy('id')->get();
        $mengetahui = ArsipOtorisasiMengetahui::with('personalisasi')->orderBy('id')->get();
        $persetujuan = ArsipOtorisasiPersetujuan::with('personalisasi')->orderBy('id')->get();
        $klasifikasi = ArsipKlasifikasiTransaksi::orderBy('id')->get();

        return view('spj.arsip_kelola_akun.index', compact('personalisasi','mengetahui','persetujuan','klasifikasi'));
    }

    // ---------------- Personalisasi CRUD ----------------
    public function storePersonalisasi(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        $item = ArsipPersonalisasi::create($data);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function updatePersonalisasi(Request $request, $id)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        $item = ArsipPersonalisasi::findOrFail($id);
        $item->update($data);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroyPersonalisasi($id)
    {
        $item = ArsipPersonalisasi::findOrFail($id);
        $item->delete();
        return response()->json(['success' => true]);
    }

    // ---------------- Mengetahui CRUD ----------------
    public function storeMengetahui(Request $request)
    {
        $data = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'arsip_personalisasi_id' => 'required|exists:arsip_personalisasi,id',
        ]);

        $item = ArsipOtorisasiMengetahui::create($data);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function updateMengetahui(Request $request, $id)
    {
        $data = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'arsip_personalisasi_id' => 'required|exists:arsip_personalisasi,id',
        ]);

        $item = ArsipOtorisasiMengetahui::findOrFail($id);
        $item->update($data);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroyMengetahui($id)
    {
        $item = ArsipOtorisasiMengetahui::findOrFail($id);
        $item->delete();
        return response()->json(['success' => true]);
    }

    // ---------------- Persetujuan CRUD ----------------
    public function storePersetujuan(Request $request)
    {
        $data = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'arsip_personalisasi_id' => 'required|exists:arsip_personalisasi,id',
        ]);

        $item = ArsipOtorisasiPersetujuan::create($data);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function updatePersetujuan(Request $request, $id)
    {
        $data = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'arsip_personalisasi_id' => 'required|exists:arsip_personalisasi,id',
        ]);

        $item = ArsipOtorisasiPersetujuan::findOrFail($id);
        $item->update($data);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroyPersetujuan($id)
    {
        $item = ArsipOtorisasiPersetujuan::findOrFail($id);
        $item->delete();
        return response()->json(['success' => true]);
    }

    // ---------------- Klasifikasi Transaksi CRUD ----------------
    public function storeKlasifikasi(Request $request)
    {
        $data = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'nominal' => 'required|numeric',
        ]);

        $item = ArsipKlasifikasiTransaksi::create($data);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function updateKlasifikasi(Request $request, $id)
    {
        $data = $request->validate([
            'kategori' => 'nullable|string|max:255',
            'nominal' => 'required|numeric',
        ]);

        $item = ArsipKlasifikasiTransaksi::findOrFail($id);
        $item->update($data);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroyKlasifikasi($id)
    {
        $item = ArsipKlasifikasiTransaksi::findOrFail($id);
        $item->delete();
        return response()->json(['success' => true]);
    }

    public function classifyByNominal(Request $request)
    {
        $nominal = (int) $request->query('nominal', 0);

        // Ambil semua klasifikasi, urut ascending berdasarkan nominal
        $klasList = ArsipKlasifikasiTransaksi::orderBy('nominal', 'asc')->get();

        if ($klasList->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Belum ada data klasifikasi'], 404);
        }

        // Cari baris pertama yang nominal >= input; jika tidak ditemukan, pilih baris terakhir (max)
        $selected = $klasList->first(function($k) use ($nominal) {
            return $nominal <= (float) $k->nominal;
        });

        if (!$selected) {
            $selected = $klasList->last();
        }

        // --- rekomendasi mengetahui & persetujuan (dari FK di tabel klasifikasi) ---
        $recommended_mengetahui = null;
        $recommended_persetujuan = null;

        if (!empty($selected->arsip_otorisasi_mengetahui_id)) {
            $m = ArsipOtorisasiMengetahui::with('personalisasi')->find($selected->arsip_otorisasi_mengetahui_id);
            if ($m) {
                $recommended_mengetahui = [
                    'id' => $m->id,
                    'personalisasi_id' => $m->arsip_personalisasi_id,
                    'nama' => optional($m->personalisasi)->nama,
                    'jabatan' => optional($m->personalisasi)->jabatan,
                ];
            }
        }

        if (!empty($selected->arsip_otorisasi_persetujuan_id)) {
            $p = ArsipOtorisasiPersetujuan::with('personalisasi')->find($selected->arsip_otorisasi_persetujuan_id);
            if ($p) {
                $recommended_persetujuan = [
                    'id' => $p->id,
                    'personalisasi_id' => $p->arsip_personalisasi_id,
                    'nama' => optional($p->personalisasi)->nama,
                    'jabatan' => optional($p->personalisasi)->jabatan,
                ];
            }
        }

        // --- kumpulkan semua opsi untuk dropdown (so user bisa memilih alternatif) ---
        $mengetahui_options = ArsipOtorisasiMengetahui::with('personalisasi')->get()->map(function($m) {
            return [
                'otorisasi_id' => $m->id,
                'personalisasi_id' => $m->arsip_personalisasi_id,
                'nama' => optional($m->personalisasi)->nama,
                'jabatan' => optional($m->personalisasi)->jabatan,
                'kategori' => $m->kategori
            ];
        })->values();

        $persetujuan_options = ArsipOtorisasiPersetujuan::with('personalisasi')->get()->map(function($p) {
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
