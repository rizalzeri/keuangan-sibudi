<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipKasMasuk;
use App\Models\ArsipKasKeluar;
use App\Models\ArsipBankMasuk;
use App\Models\ArsipBankKeluar;
class ProsedurTransaksiController extends Controller
{
    public function bukti_kas_masuk()
    {
        $data_kas_masuk = ArsipKasMasuk::orderBy('tanggal_transaksi', 'desc')->get();
        return view('spj.prosedur_transaksi.bukti_kas_masuk.index', compact('data_kas_masuk'));
    }

    // STORE - simpan data baru (AJAX friendly)
    public function store_kas_masuk(Request $request)
    {
        $rules = [
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'sumber' => 'nullable|string',
            'nominal' => 'nullable|numeric',
            'penerima' => 'nullable|string',
            'menyetujui' => 'nullable|string',
            'mengetahui' => 'nullable|string',
            'kategori_pembukuan' => 'required|in:1,2',
            'dokumen_pendukung' => 'nullable',
            'link_gdrive' => 'nullable|url|max:2048',
        ];

        $validated = $request->validate($rules);

        $payload = [
            'tanggal_transaksi'   => $validated['tanggal'],
            'nama_transaksi'      => $validated['nama_transaksi'],
            'sumber'              => $validated['sumber'] ?? null,
            'nominal'             => $validated['nominal'] ?? 0,
            'penerima'            => $validated['penerima'] ?? null,
            'menyetujui'          => $validated['menyetujui'] ?? null,
            'mengetahui'          => $validated['mengetahui'] ?? null,
            'kategori_pembukuan'  => (string) ($validated['kategori_pembukuan'] ?? '1'),
            'link_gdrive'         => $validated['link_gdrive'] ?? null,
        ];

        // handle dokumen_pendukung: accept array or comma separated string or JSON string
        $docs = $request->input('dokumen_pendukung', null);
        if (!is_null($docs)) {
            if (is_array($docs)) {
                $payload['dokumen_pendukung'] = $docs;
            } else {
                $try = json_decode($docs, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
                    $payload['dokumen_pendukung'] = $try;
                } else {
                    $payload['dokumen_pendukung'] = array_values(array_filter(array_map('trim', explode(',', (string)$docs))));
                }
            }
        }

        $record = ArsipKasMasuk::create($payload);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data kas masuk berhasil ditambahkan',
                'data' => $record
            ], 201);
        }

        return back()->with('success', 'Data kas masuk berhasil ditambahkan');
    }

    // UPDATE
    public function update_kas_masuk(Request $request, $id)
    {
        $rules = [
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'sumber' => 'nullable|string',
            'nominal' => 'nullable|numeric',
            'penerima' => 'nullable|string',
            'menyetujui' => 'nullable|string',
            'mengetahui' => 'nullable|string',
            'kategori_pembukuan' => 'required|in:1,2',
            'dokumen_pendukung' => 'nullable',
            'link_gdrive' => 'nullable|url|max:2048',
        ];

        $validated = $request->validate($rules);

        $record = ArsipKasMasuk::where('id', $id)->first();
        if (!$record) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Data ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Data ID $id tidak ditemukan");
        }

        $payload = [
            'tanggal_transaksi'   => $validated['tanggal'],
            'nama_transaksi'      => $validated['nama_transaksi'],
            'sumber'              => $validated['sumber'] ?? null,
            'nominal'             => $validated['nominal'] ?? 0,
            'penerima'            => $validated['penerima'] ?? null,
            'menyetujui'          => $validated['menyetujui'] ?? null,
            'mengetahui'          => $validated['mengetahui'] ?? null,
            'kategori_pembukuan'  => (string) ($validated['kategori_pembukuan'] ?? '1'),
            'link_gdrive'         => $validated['link_gdrive'] ?? null,
        ];

        $docs = $request->input('dokumen_pendukung', null);
        if (!is_null($docs)) {
            if (is_array($docs)) {
                $payload['dokumen_pendukung'] = $docs;
            } else {
                $try = json_decode($docs, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
                    $payload['dokumen_pendukung'] = $try;
                } else {
                    $payload['dokumen_pendukung'] = array_values(array_filter(array_map('trim', explode(',', (string)$docs))));
                }
            }
        }

        $record->update($payload);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data kas masuk berhasil diperbarui',
                'data' => $record
            ]);
        }

        return back()->with('success', 'Data kas masuk berhasil diperbarui');
    }

    // DESTROY
    public function destroy_kas_masuk(Request $request, $id)
    {
        $record = ArsipKasMasuk::where('id', $id)->first();
        if (!$record) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Data ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Data ID $id tidak ditemukan");
        }

        $record->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
        }

        return back()->with('success', 'Data berhasil dihapus');
    }

    // PRINT (bisa menerima ?id=xx atau query params)
    public function print_kas_masuk(Request $request)
    {
        if ($request->has('id')) {
            $id = $request->query('id');
            $record = ArsipKasMasuk::find($id);
            if (!$record) {
                abort(404, "Data ID $id tidak ditemukan");
            }
            return view('spj.prosedur_transaksi.bukti_kas_masuk.cetak', compact('record'));
        }

        // fallback: menggunakan query params jika tidak ada id
        $data = $request->all();
        return view('spj.prosedur_transaksi.bukti_kas_masuk.cetak', compact('data'));
    }

    // kas keluar
    public function bukti_kas_keluar()
    {
        $data_kas_keluar = ArsipKasKeluar::orderBy('tanggal_transaksi', 'desc')->get();
        return view('spj.prosedur_transaksi.bukti_kas_keluar.index', compact('data_kas_keluar'));
    }

    // =============================
    // STORE - Simpan Data Baru
    // =============================
    public function store_kas_keluar(Request $request)
    {
        $rules = [
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'tujuan' => 'nullable|string',
            'nominal' => 'nullable|numeric',
            'penerima' => 'nullable|string',
            'menyetujui' => 'nullable|string',
            'mengetahui' => 'nullable|string',
            'kategori_pembukuan' => 'required|in:1,2',
            'dokumen_pendukung' => 'nullable',
            'link_gdrive' => 'nullable|url|max:2048',
        ];

        $validated = $request->validate($rules);

        $payload = [
            'tanggal_transaksi'   => $validated['tanggal'],
            'nama_transaksi'      => $validated['nama_transaksi'],
            'tujuan'              => $validated['tujuan'] ?? null,
            'nominal'             => $validated['nominal'] ?? 0,
            'penerima'            => $validated['penerima'] ?? null,
            'menyetujui'          => $validated['menyetujui'] ?? null,
            'mengetahui'          => $validated['mengetahui'] ?? null,
            'kategori_pembukuan'  => (string) ($validated['kategori_pembukuan'] ?? '1'),
            'link_gdrive'         => $validated['link_gdrive'] ?? null,
        ];

        // handle dokumen pendukung
        $docs = $request->input('dokumen_pendukung', null);
        if (!is_null($docs)) {
            if (is_array($docs)) {
                $payload['dokumen_pendukung'] = $docs;
            } else {
                $try = json_decode($docs, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
                    $payload['dokumen_pendukung'] = $try;
                } else {
                    $payload['dokumen_pendukung'] = array_values(array_filter(array_map('trim', explode(',', (string)$docs))));
                }
            }
        }

        $record = ArsipKasKeluar::create($payload);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data kas keluar berhasil ditambahkan',
                'data'    => $record
            ], 201);
        }

        return back()->with('success', 'Data kas keluar berhasil ditambahkan');
    }


    // =============================
    // UPDATE
    // =============================
    public function update_kas_keluar(Request $request, $id)
    {
        $rules = [
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'tujuan' => 'nullable|string',
            'nominal' => 'nullable|numeric',
            'penerima' => 'nullable|string',
            'menyetujui' => 'nullable|string',
            'mengetahui' => 'nullable|string',
            'kategori_pembukuan' => 'required|in:1,2',
            'dokumen_pendukung' => 'nullable',
            'link_gdrive' => 'nullable|url|max:2048',
        ];

        $validated = $request->validate($rules);

        $record = ArsipKasKeluar::find($id);
        if (!$record) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Data ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Data ID $id tidak ditemukan");
        }

        $payload = [
            'tanggal_transaksi'   => $validated['tanggal'],
            'nama_transaksi'      => $validated['nama_transaksi'],
            'tujuan'              => $validated['tujuan'] ?? null,
            'nominal'             => $validated['nominal'] ?? 0,
            'penerima'            => $validated['penerima'] ?? null,
            'menyetujui'          => $validated['menyetujui'] ?? null,
            'mengetahui'          => $validated['mengetahui'] ?? null,
            'kategori_pembukuan'  => (string) ($validated['kategori_pembukuan'] ?? '1'),
            'link_gdrive'         => $validated['link_gdrive'] ?? null,
        ];

        $docs = $request->input('dokumen_pendukung', null);
        if (!is_null($docs)) {
            if (is_array($docs)) {
                $payload['dokumen_pendukung'] = $docs;
            } else {
                $try = json_decode($docs, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
                    $payload['dokumen_pendukung'] = $try;
                } else {
                    $payload['dokumen_pendukung'] =
                        array_values(array_filter(array_map('trim', explode(',', (string)$docs))));
                }
            }
        }

        $record->update($payload);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data kas keluar berhasil diperbarui',
                'data' => $record
            ]);
        }

        return back()->with('success', 'Data kas keluar berhasil diperbarui');
    }


    // =============================
    // DELETE
    // =============================
    public function destroy_kas_keluar(Request $request, $id)
    {
        $record = ArsipKasKeluar::find($id);
        if (!$record) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Data ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Data ID $id tidak ditemukan");
        }

        $record->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
        }

        return back()->with('success', 'Data berhasil dihapus');
    }


    // =============================
    // PRINT
    // =============================
    public function print_kas_keluar(Request $request)
    {
        if ($request->has('id')) {
            $id = $request->query('id');
            $record = ArsipKasKeluar::find($id);
            if (!$record) {
                abort(404, "Data ID $id tidak ditemukan");
            }
            return view('spj.prosedur_transaksi.bukti_kas_keluar.cetak', compact('record'));
        }

        $data = $request->all();
        return view('spj.prosedur_transaksi.bukti_kas_keluar.cetak', compact('data'));
    }

    // kas keluar
    public function bukti_bank_keluar()
    {
        $data_bank_keluar = ArsipBankKeluar::orderBy('tanggal_transaksi', 'desc')->get();
        return view('spj.prosedur_transaksi.bukti_bank_keluar.index', compact('data_bank_keluar'));
    }

    // =============================
    // STORE - Simpan Data Baru
    // =============================
    public function store_bank_keluar(Request $request)
    {
        $rules = [
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'tujuan' => 'nullable|string',
            'nominal' => 'nullable|numeric',
            'penerima' => 'nullable|string',
            'menyetujui' => 'nullable|string',
            'mengetahui' => 'nullable|string',
            'kategori_pembukuan' => 'required|in:1,2',
            'dokumen_pendukung' => 'nullable',
            'link_gdrive' => 'nullable|url|max:2048',
        ];

        $validated = $request->validate($rules);

        $payload = [
            'tanggal_transaksi'   => $validated['tanggal'],
            'nama_transaksi'      => $validated['nama_transaksi'],
            'tujuan'              => $validated['tujuan'] ?? null,
            'nominal'             => $validated['nominal'] ?? 0,
            'penerima'            => $validated['penerima'] ?? null,
            'menyetujui'          => $validated['menyetujui'] ?? null,
            'mengetahui'          => $validated['mengetahui'] ?? null,
            'kategori_pembukuan'  => (string) ($validated['kategori_pembukuan'] ?? '1'),
            'link_gdrive'         => $validated['link_gdrive'] ?? null,
        ];

        // handle dokumen pendukung
        $docs = $request->input('dokumen_pendukung', null);
        if (!is_null($docs)) {
            if (is_array($docs)) {
                $payload['dokumen_pendukung'] = $docs;
            } else {
                $try = json_decode($docs, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
                    $payload['dokumen_pendukung'] = $try;
                } else {
                    $payload['dokumen_pendukung'] = array_values(array_filter(array_map('trim', explode(',', (string)$docs))));
                }
            }
        }

        $record = ArsipBankKeluar::create($payload);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data bank keluar berhasil ditambahkan',
                'data'    => $record
            ], 201);
        }

        return back()->with('success', 'Data bank keluar berhasil ditambahkan');
    }


    // =============================
    // UPDATE
    // =============================
    public function update_bank_keluar(Request $request, $id)
    {
        $rules = [
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'tujuan' => 'nullable|string',
            'nominal' => 'nullable|numeric',
            'penerima' => 'nullable|string',
            'menyetujui' => 'nullable|string',
            'mengetahui' => 'nullable|string',
            'kategori_pembukuan' => 'required|in:1,2',
            'dokumen_pendukung' => 'nullable',
            'link_gdrive' => 'nullable|url|max:2048',
        ];

        $validated = $request->validate($rules);

        $record = ArsipBankKeluar::find($id);
        if (!$record) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Data ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Data ID $id tidak ditemukan");
        }

        $payload = [
            'tanggal_transaksi'   => $validated['tanggal'],
            'nama_transaksi'      => $validated['nama_transaksi'],
            'tujuan'              => $validated['tujuan'] ?? null,
            'nominal'             => $validated['nominal'] ?? 0,
            'penerima'            => $validated['penerima'] ?? null,
            'menyetujui'          => $validated['menyetujui'] ?? null,
            'mengetahui'          => $validated['mengetahui'] ?? null,
            'kategori_pembukuan'  => (string) ($validated['kategori_pembukuan'] ?? '1'),
            'link_gdrive' => 'nullable|url|max:2048',
        ];

        $docs = $request->input('dokumen_pendukung', null);
        if (!is_null($docs)) {
            if (is_array($docs)) {
                $payload['dokumen_pendukung'] = $docs;
            } else {
                $try = json_decode($docs, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
                    $payload['dokumen_pendukung'] = $try;
                } else {
                    $payload['dokumen_pendukung'] =
                        array_values(array_filter(array_map('trim', explode(',', (string)$docs))));
                }
            }
        }

        $record->update($payload);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data bank keluar berhasil diperbarui',
                'data' => $record
            ]);
        }

        return back()->with('success', 'Data bank keluar berhasil diperbarui');
    }


    // =============================
    // DELETE
    // =============================
    public function destroy_bank_keluar(Request $request, $id)
    {
        $record = ArsipBankKeluar::find($id);
        if (!$record) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Data ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Data ID $id tidak ditemukan");
        }

        $record->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
        }

        return back()->with('success', 'Data berhasil dihapus');
    }


    // =============================
    // PRINT
    // =============================
    public function print_bank_keluar(Request $request)
    {
        if ($request->has('id')) {
            $id = $request->query('id');
            $record = ArsipBankKeluar::find($id);
            if (!$record) {
                abort(404, "Data ID $id tidak ditemukan");
            }
            return view('spj.prosedur_transaksi.bukti_bank_keluar.cetak', compact('record'));
        }

        $data = $request->all();
        return view('spj.prosedur_transaksi.bukti_bank_keluar.cetak', compact('data'));
    }

    // bank masuk
    public function bukti_bank_masuk()
    {
        $data_bank_masuk = ArsipBankMasuk::orderBy('tanggal_transaksi', 'desc')->get();
        return view('spj.prosedur_transaksi.bukti_bank_masuk.index', compact('data_bank_masuk'));
    }

    // STORE - simpan data baru (AJAX friendly)
    public function store_bank_masuk(Request $request)
    {
        $rules = [
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'sumber' => 'nullable|string',
            'nominal' => 'nullable|numeric',
            'penerima' => 'nullable|string',
            'menyetujui' => 'nullable|string',
            'mengetahui' => 'nullable|string',
            'kategori_pembukuan' => 'required|in:1,2',
            'dokumen_pendukung' => 'nullable', // can be array or json/string
            'link_gdrive' => 'nullable|url|max:2048',
        ];

        $validated = $request->validate($rules);

        // map field names from form -> table column names
        $payload = [
            'tanggal_transaksi'   => $validated['tanggal'],
            'nama_transaksi'      => $validated['nama_transaksi'],
            'sumber'              => $validated['sumber'] ?? null,
            'nominal'             => $validated['nominal'] ?? 0,
            'penerima'            => $validated['penerima'] ?? null,
            'menyetujui'          => $validated['menyetujui'] ?? null,
            'mengetahui'          => $validated['mengetahui'] ?? null,
            'kategori_pembukuan'  => (string) ($validated['kategori_pembukuan'] ?? '1'),
            'link_gdrive'         => $validated['link_gdrive'] ?? null,
        ];

        // handle dokumen_pendukung: accept array or comma separated string or JSON string
        $docs = $request->input('dokumen_pendukung', null);
        if (!is_null($docs)) {
            if (is_array($docs)) {
                $payload['dokumen_pendukung'] = $docs;
            } else {
                // try decode json, otherwise split by comma
                $try = json_decode($docs, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
                    $payload['dokumen_pendukung'] = $try;
                } else {
                    // split by comma and trim
                    $payload['dokumen_pendukung'] = array_values(array_filter(array_map('trim', explode(',', (string)$docs))));
                }
            }
        }

        $record = ArsipBankMasuk::create($payload);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data bank masuk berhasil ditambahkan',
                'data' => $record
            ], 201);
        }

        return back()->with('success', 'Data bank masuk berhasil ditambahkan');
    }

    // UPDATE
    public function update_bank_masuk(Request $request, $id)
    {
        $rules = [
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'sumber' => 'nullable|string',
            'nominal' => 'nullable|numeric',
            'penerima' => 'nullable|string',
            'menyetujui' => 'nullable|string',
            'mengetahui' => 'nullable|string',
            'kategori_pembukuan' => 'required|in:1,2',
            'dokumen_pendukung' => 'nullable',
            'link_gdrive' => 'nullable|url|max:2048',
        ];

        $validated = $request->validate($rules);

        $record = ArsipBankMasuk::where('id', $id)->first();
        if (!$record) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Data ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Data ID $id tidak ditemukan");
        }

        $payload = [
            'tanggal_transaksi'   => $validated['tanggal'],
            'nama_transaksi'      => $validated['nama_transaksi'],
            'sumber'              => $validated['sumber'] ?? null,
            'nominal'             => $validated['nominal'] ?? 0,
            'penerima'            => $validated['penerima'] ?? null,
            'menyetujui'          => $validated['menyetujui'] ?? null,
            'mengetahui'          => $validated['mengetahui'] ?? null,
            'kategori_pembukuan'  => (string) ($validated['kategori_pembukuan'] ?? '1'),

        ];

        $docs = $request->input('dokumen_pendukung', null);
        if (!is_null($docs)) {
            if (is_array($docs)) {
                $payload['dokumen_pendukung'] = $docs;
            } else {
                $try = json_decode($docs, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
                    $payload['dokumen_pendukung'] = $try;
                } else {
                    $payload['dokumen_pendukung'] = array_values(array_filter(array_map('trim', explode(',', (string)$docs))));
                }
            }
        }

        $record->update($payload);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data bank masuk berhasil diperbarui',
                'data' => $record
            ]);
        }

        return back()->with('success', 'Data bank masuk berhasil diperbarui');
    }

    // DESTROY
    public function destroy_bank_masuk(Request $request, $id)
    {
        $record = ArsipBankMasuk::where('id', $id)->first();
        if (!$record) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Data ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Data ID $id tidak ditemukan");
        }

        $record->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
        }

        return back()->with('success', 'Data berhasil dihapus');
    }

    // PRINT (bisa menerima ?id=xx atau query params)
    public function print_bank_masuk(Request $request)
    {
        if ($request->has('id')) {
            $id = $request->query('id');
            $record = ArsipBankMasuk::find($id);
            if (!$record) {
                abort(404, "Data ID $id tidak ditemukan");
            }
            return view('spj.prosedur_transaksi.bukti_bank_masuk.cetak', compact('record'));
        }

        // fallback: menggunakan query params jika tidak ada id
        $data = $request->all();
        return view('spj.prosedur_transaksi.bukti_bank_masuk.cetak', compact('data'));
    }
}
