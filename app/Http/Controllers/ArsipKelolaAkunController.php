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
        // Semua personalisasi (untuk dropdown)
        $personalisasi = ArsipPersonalisasi::orderBy('id')->get();

        // Klasifikasi (dengan relasi otorisasi untuk menampilkan nama personalisasi)
        $klasifikasi = ArsipKlasifikasiTransaksi::with(['mengetahui','persetujuan'])
            ->orderBy('id')
            ->get();

        return view('spj.arsip_kelola_akun.index', compact('personalisasi','klasifikasi'));
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

    // ---------------- Mengetahui / Persetujuan (CRUD tetap ada jika perlu) ----------------
    // (kamu bisa tetap pakai endpoint ini, tapi UI sekarang menggunakan personalisasi langsung)
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
            // these are personalisasi ids chosen from dropdown
            'mengetahui_personalisasi_id' => 'nullable|exists:arsip_personalisasi,id',
            'persetujuan_personalisasi_id' => 'nullable|exists:arsip_personalisasi,id',
        ]);

        // buat / reuse Ot. Mengetahui
        $mengetahuiId = null;
        if (!empty($data['mengetahui_personalisasi_id'])) {
            $men = ArsipOtorisasiMengetahui::firstOrCreate(
                ['arsip_personalisasi_id' => $data['mengetahui_personalisasi_id']],
                ['kategori' => null] // atau set kategori khusus jika mau
            );
            $mengetahuiId = $men->id;
        }

        // buat / reuse Ot. Persetujuan
        $persetujuanId = null;
        if (!empty($data['persetujuan_personalisasi_id'])) {
            $p = ArsipOtorisasiPersetujuan::firstOrCreate(
                ['arsip_personalisasi_id' => $data['persetujuan_personalisasi_id']],
                ['kategori' => null]
            );
            $persetujuanId = $p->id;
        }

        // simpan klasifikasi, simpan FK ke otorisasi yang telah dibuat/diambil
        $item = ArsipKlasifikasiTransaksi::create([
            'kategori' => $data['kategori'] ?? null,
            'nominal' => $data['nominal'],
            'arsip_otorisasi_mengetahui_id' => $mengetahuiId,
            'arsip_otorisasi_persetujuan_id' => $persetujuanId,
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

        $item = ArsipKlasifikasiTransaksi::findOrFail($id);

        // process mengetahui
        $mengetahuiId = $item->arsip_otorisasi_mengetahui_id;
        if (!empty($data['mengetahui_personalisasi_id'])) {
            $men = ArsipOtorisasiMengetahui::firstOrCreate(
                ['arsip_personalisasi_id' => $data['mengetahui_personalisasi_id']],
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
                ['arsip_personalisasi_id' => $data['persetujuan_personalisasi_id']],
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
        $item = ArsipKlasifikasiTransaksi::findOrFail($id);
        $item->delete();
        return response()->json(['success' => true]);
    }

    // --- classifyByNominal tetap jika masih diperlukan ---
    public function classifyByNominal(Request $request)
    {
        $nominal = (int) $request->query('nominal', 0);
        $klasList = ArsipKlasifikasiTransaksi::orderBy('nominal', 'asc')->get();

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
            $m = ArsipOtorisasiMengetahui::with('personalisasi')->find($selected->arsip_otorisasi_mengetahui_id);
            if ($m) $recommended_mengetahui = [
                'id' => $m->id,
                'personalisasi_id' => $m->arsip_personalisasi_id,
                'nama' => optional($m->personalisasi)->nama,
                'jabatan' => optional($m->personalisasi)->jabatan,
            ];
        }

        if (!empty($selected->arsip_otorisasi_persetujuan_id)) {
            $p = ArsipOtorisasiPersetujuan::with('personalisasi')->find($selected->arsip_otorisasi_persetujuan_id);
            if ($p) $recommended_persetujuan = [
                'id' => $p->id,
                'personalisasi_id' => $p->arsip_personalisasi_id,
                'nama' => optional($p->personalisasi)->nama,
                'jabatan' => optional($p->personalisasi)->jabatan,
            ];
        }

        // options untuk dropdown (sekarang tidak dipakai karena kita gunakan personalisasi)
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
