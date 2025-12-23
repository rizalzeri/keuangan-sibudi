<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ArsipKasMasuk;
use App\Models\ArsipKasKeluar;
use App\Models\ArsipBankMasuk;
use App\Models\ArsipBankKeluar;
use App\Models\ArsipPersonalisasi;
use Illuminate\Support\Facades\Auth;
class ProsedurTransaksiController extends Controller
{
    public function bukti_kas_masuk(Request $request)
    {
        $id = $request->query('id');
        $record = null;
        if ($id) {
            $record = ArsipKasMasuk::find($id);
            if (!$record) {
                // kalau tidak ditemukan, redirect kembali dengan pesan
                return redirect('/spj/arsip_pembukuan_1')
                    ->with('error', 'Data tidak ditemukan untuk edit.');
            }
        }

        // Anda mungkin perlu menyiapkan data lain (opsi dropdown, rekomendasi penandatangan, dll)
        return view('spj.prosedur_transaksi.bukti_kas_masuk.index', [
            'record' => $record,
            // 'approvers' => $this->getApprovers() // jika perlu
        ]);
    }

    // PUT update
    public function update_kas_masuk(Request $request, $id)
    {
        $rec = ArsipKasMasuk::find($id);
        if (!$rec) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string|max:255',
            'sumber' => 'nullable|string|max:255',
            'nominal' => 'required|numeric',
            'penerima' => 'nullable|string|max:255',
            'kategori_pembukuan' => 'required|in:1,2',
            'link_gdrive' => 'nullable|url',
            'catatan' => 'nullable|string|max:255',
        ]);

        $rec->tanggal_transaksi = $validated['tanggal'];
        $rec->nama_transaksi     = $validated['nama_transaksi'];
        $rec->sumber             = $validated['sumber'] ?? null;
        $rec->nominal            = $validated['nominal'];
        $rec->penerima           = $validated['penerima'] ?? null;
        $rec->kategori_pembukuan = $validated['kategori_pembukuan'];
        $rec->link_gdrive        = $validated['link_gdrive'] ?? null;
        $rec->catatan            = $validated['catatan'] ?? null;

        $dok = $request->input('dokumen_pendukung', null);
        $rec->dokumen_pendukung = $dok ? json_encode($dok) : null;

        $rec->menyetujui = $request->input('menyetujui') ?? $rec->menyetujui;
        $rec->mengetahui = $request->input('mengetahui') ?? $rec->mengetahui;

        $rec->save();

        // ambil return param (decode) dan validasi sederhana (whitelist)
        $returnRaw = $request->input('return', '/spj/arsip_pembukuan_1');
        $returnPath = rawurldecode($returnRaw);

        // whitelist supaya aman (hindari open redirect)
        $allowed = [
            url('/spj/arsip_pembukuan_1'),
            url('/spj/arsip_pembukuan_2'),
            '/spj/arsip_pembukuan_1',
            '/spj/arsip_pembukuan_2'
        ];
        // jika $returnPath belum absolute, buat absolute
        $returnUrlCandidate = (strpos($returnPath, 'http') === 0) ? $returnPath : url($returnPath);

        if (!in_array($returnUrlCandidate, $allowed) && !in_array($returnPath, $allowed)) {
            $returnUrl = url('/spj/arsip_pembukuan_1'); // fallback aman
        } else {
            // gunakan versi absolute
            $returnUrl = $returnUrlCandidate;
        }

        // jika AJAX/json expected -> kembalikan JSON termasuk redirect target
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => ['id' => $rec->id],
                'redirect' => $returnUrl
            ]);
        }

        // non-AJAX fallback
        return redirect($returnUrl)->with('success', 'Data berhasil diperbarui.');
    }


    // STORE - simpan data baru (AJAX friendly)
    public function store_kas_masuk(Request $request)
    {
        $validated = $request->validate([
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
            'catatan' => 'nullable|string|max:255', // <-- tambahkan
        ]);

        $payload = [
            'tanggal_transaksi'  => $validated['tanggal'],
            'nama_transaksi'     => $validated['nama_transaksi'],
            'sumber'             => $validated['sumber'] ?? null,
            'nominal'            => $validated['nominal'] ?? 0,
            'penerima'           => $validated['penerima'] ?? null,
            'menyetujui'         => $validated['menyetujui'] ?? null,
            'mengetahui'         => $validated['mengetahui'] ?? null,
            'kategori_pembukuan' => (string)$validated['kategori_pembukuan'],
            'link_gdrive'        => $validated['link_gdrive'] ?? null,
            'catatan'            => $validated['catatan'] ?? null, // <-- sertakan
        ];

        $this->handleDokumenPendukung($request, $payload);

        $tahun = Carbon::parse($validated['tanggal'])->year;
        $urut = ArsipKasMasuk::whereYear('tanggal_transaksi', $tahun)
            ->where('kategori_pembukuan', $payload['kategori_pembukuan'])
            ->count() + 1;

        $payload['nomor_dokumen'] = $this->generateNomorDokumen('BKM', $validated['tanggal'], $urut);

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
            
            if (!$record) abort(404, "Data ID $id tidak ditemukan");
            
            // Cari jabatan dari tabel personalisasi berdasarkan nama yang mengetahui
            $jabatanMengetahui = null;
            if ($record->mengetahui) {
                $personalisasi = ArsipPersonalisasi::where('nama', $record->mengetahui)->first();
                $jabatanMengetahui = $personalisasi ? $personalisasi->jabatan : null;
            }
            return view('spj.prosedur_transaksi.bukti_kas_masuk.cetak', [
                'record' => $record,
                'jabatan_mengetahui' => $jabatanMengetahui
            ]);
        }
        
        $data = $request->all();
        return view('spj.prosedur_transaksi.bukti_kas_masuk.cetak', compact('data'));
    }

    // kas keluar
    public function bukti_kas_keluar(Request $request)
    {
        $id = $request->query('id');
        $record = null;
        if ($id) {
            $record = ArsipKasKeluar::find($id);
            if (!$record) {
                // kalau tidak ditemukan, redirect kembali dengan pesan
                return redirect('/spj/arsip_pembukuan_1')
                    ->with('error', 'Data tidak ditemukan untuk edit.');
            }
        }

        // Anda mungkin perlu menyiapkan data lain (opsi dropdown, rekomendasi penandatangan, dll)
        return view('spj.prosedur_transaksi.bukti_kas_keluar.index', [
            'record' => $record,
            // 'approvers' => $this->getApprovers() // jika perlu
        ]);
    }

    // =============================
    // STORE - Simpan Data Baru
    // =============================
    public function store_kas_keluar(Request $request)
    {
        $validated = $request->validate([
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
            'catatan' => 'nullable|string|max:255', // <-- tambahkan
        ]);

        $payload = [
            'tanggal_transaksi'  => $validated['tanggal'],
            'nama_transaksi'     => $validated['nama_transaksi'],
            'tujuan'             => $validated['tujuan'] ?? null,
            'nominal'            => $validated['nominal'] ?? 0,
            'penerima'           => $validated['penerima'] ?? null,
            'menyetujui'         => $validated['menyetujui'] ?? null,
            'mengetahui'         => $validated['mengetahui'] ?? null,
            'kategori_pembukuan' => (string)$validated['kategori_pembukuan'],
            'link_gdrive'        => $validated['link_gdrive'] ?? null,
            'catatan'            => $validated['catatan'] ?? null, // <-- sertakan
        ];

        $this->handleDokumenPendukung($request, $payload);

        $tahun = Carbon::parse($validated['tanggal'])->year;
        $urut = ArsipKasKeluar::whereYear('tanggal_transaksi', $tahun)
            ->where('kategori_pembukuan', $payload['kategori_pembukuan'])
            ->count() + 1;

        $payload['nomor_dokumen'] = $this->generateNomorDokumen('BKK', $validated['tanggal'], $urut);

        $record = ArsipKasKeluar::create($payload);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data kas keluar berhasil ditambahkan',
                'data' => $record
            ], 201);
        }

        return back()->with('success', 'Data kas keluar berhasil ditambahkan');
    }



    // =============================
    // UPDATE
    // =============================
    public function update_kas_keluar(Request $request, $id)
    {
        $rec = ArsipKasKeluar::find($id);
        if (!$rec) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string|max:255',
            'tujuan' => 'nullable|string|max:255',
            'nominal' => 'required|numeric',
            'penerima' => 'nullable|string|max:255',
            'kategori_pembukuan' => 'required|in:1,2',
            'link_gdrive' => 'nullable|url',
            'catatan' => 'nullable|string|max:255',
        ]);

        $rec->tanggal_transaksi = $validated['tanggal'];
        $rec->nama_transaksi     = $validated['nama_transaksi'];
        $rec->tujuan             = $validated['tujuan'] ?? null;
        $rec->nominal            = $validated['nominal'];
        $rec->penerima           = $validated['penerima'] ?? null;
        $rec->kategori_pembukuan = $validated['kategori_pembukuan'];
        $rec->link_gdrive        = $validated['link_gdrive'] ?? null;
        $rec->catatan            = $validated['catatan'] ?? null;

        $dok = $request->input('dokumen_pendukung', null);
        $rec->dokumen_pendukung = $dok ? json_encode($dok) : null;

        $rec->menyetujui = $request->input('menyetujui') ?? $rec->menyetujui;
        $rec->mengetahui = $request->input('mengetahui') ?? $rec->mengetahui;

        $rec->save();

        // ambil return param (decode) dan validasi sederhana (whitelist)
        $returnRaw = $request->input('return', '/spj/arsip_pembukuan_1');
        $returnPath = rawurldecode($returnRaw);

        // whitelist supaya aman (hindari open redirect)
        $allowed = [
            url('/spj/arsip_pembukuan_1'),
            url('/spj/arsip_pembukuan_2'),
            '/spj/arsip_pembukuan_1',
            '/spj/arsip_pembukuan_2'
        ];
        // jika $returnPath belum absolute, buat absolute
        $returnUrlCandidate = (strpos($returnPath, 'http') === 0) ? $returnPath : url($returnPath);

        if (!in_array($returnUrlCandidate, $allowed) && !in_array($returnPath, $allowed)) {
            $returnUrl = url('/spj/arsip_pembukuan_1'); // fallback aman
        } else {
            // gunakan versi absolute
            $returnUrl = $returnUrlCandidate;
        }

        // jika AJAX/json expected -> kembalikan JSON termasuk redirect target
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => ['id' => $rec->id],
                'redirect' => $returnUrl
            ]);
        }

        // non-AJAX fallback
        return redirect($returnUrl)->with('success', 'Data berhasil diperbarui.');
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
            if (!$record) abort(404, "Data ID $id tidak ditemukan");
            // Cari jabatan dari tabel personalisasi berdasarkan nama yang mengetahui
            $jabatanMengetahui = null;
            if ($record->mengetahui) {
                $personalisasi = ArsipPersonalisasi::where('nama', $record->mengetahui)->first();
                $jabatanMengetahui = $personalisasi ? $personalisasi->jabatan : null;
            }
            return view('spj.prosedur_transaksi.bukti_kas_masuk.cetak', [
                'record' => $record,
                'jabatan_mengetahui' => $jabatanMengetahui
            ]);
        }
        $data = $request->all();
        return view('spj.prosedur_transaksi.bukti_kas_keluar.cetak', compact('data'));
    }

    // kas keluar
    public function bukti_bank_keluar(Request $request)
    {
        $id = $request->query('id');
        $record = null;
        if ($id) {
            $record = ArsipBankKeluar::find($id);
            if (!$record) {
                // kalau tidak ditemukan, redirect kembali dengan pesan
                return redirect('/spj/arsip_pembukuan_1')
                    ->with('error', 'Data tidak ditemukan untuk edit.');
            }
        }

        // Anda mungkin perlu menyiapkan data lain (opsi dropdown, rekomendasi penandatangan, dll)
        return view('spj.prosedur_transaksi.bukti_bank_keluar.index', [
            'record' => $record,
            // 'approvers' => $this->getApprovers() // jika perlu
        ]);
    }

    // =============================
    // STORE - Simpan Data Baru
    // =============================
    public function store_bank_keluar(Request $request)
    {
        $validated = $request->validate([
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
            'catatan' => 'nullable|string|max:255', // <-- tambahkan
        ]);

        $payload = [
            'tanggal_transaksi'  => $validated['tanggal'],
            'nama_transaksi'     => $validated['nama_transaksi'],
            'tujuan'             => $validated['tujuan'] ?? null,
            'nominal'            => $validated['nominal'] ?? 0,
            'penerima'           => $validated['penerima'] ?? null,
            'menyetujui'         => $validated['menyetujui'] ?? null,
            'mengetahui'         => $validated['mengetahui'] ?? null,
            'kategori_pembukuan' => (string)$validated['kategori_pembukuan'],
            'link_gdrive'        => $validated['link_gdrive'] ?? null,
            'catatan'            => $validated['catatan'] ?? null, // <-- sertakan
        ];

        $this->handleDokumenPendukung($request, $payload);

        $tahun = Carbon::parse($validated['tanggal'])->year;
        $urut = ArsipBankKeluar::whereYear('tanggal_transaksi', $tahun)
            ->where('kategori_pembukuan', $payload['kategori_pembukuan'])
            ->count() + 1;

        $payload['nomor_dokumen'] = $this->generateNomorDokumen('BBK', $validated['tanggal'], $urut);

        $record = ArsipBankKeluar::create($payload);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data bank keluar berhasil ditambahkan',
                'data' => $record
            ], 201);
        }

        return back()->with('success', 'Data bank keluar berhasil ditambahkan');
    }



    // =============================
    // UPDATE
    // =============================
    public function update_bank_keluar(Request $request, $id)
    {
        $rec = ArsipBankKeluar::find($id);
        if (!$rec) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string|max:255',
            'tujuan' => 'nullable|string|max:255',
            'nominal' => 'required|numeric',
            'penerima' => 'nullable|string|max:255',
            'kategori_pembukuan' => 'required|in:1,2',
            'link_gdrive' => 'nullable|url',
            'catatan' => 'nullable|string|max:255',
        ]);

        $rec->tanggal_transaksi = $validated['tanggal'];
        $rec->nama_transaksi     = $validated['nama_transaksi'];
        $rec->tujuan             = $validated['tujuan'] ?? null;
        $rec->nominal            = $validated['nominal'];
        $rec->penerima           = $validated['penerima'] ?? null;
        $rec->kategori_pembukuan = $validated['kategori_pembukuan'];
        $rec->link_gdrive        = $validated['link_gdrive'] ?? null;
        $rec->catatan            = $validated['catatan'] ?? null;

        $dok = $request->input('dokumen_pendukung', null);
        $rec->dokumen_pendukung = $dok ? json_encode($dok) : null;

        $rec->menyetujui = $request->input('menyetujui') ?? $rec->menyetujui;
        $rec->mengetahui = $request->input('mengetahui') ?? $rec->mengetahui;

        $rec->save();

        // ambil return param (decode) dan validasi sederhana (whitelist)
        $returnRaw = $request->input('return', '/spj/arsip_pembukuan_1');
        $returnPath = rawurldecode($returnRaw);

        // whitelist supaya aman (hindari open redirect)
        $allowed = [
            url('/spj/arsip_pembukuan_1'),
            url('/spj/arsip_pembukuan_2'),
            '/spj/arsip_pembukuan_1',
            '/spj/arsip_pembukuan_2'
        ];
        // jika $returnPath belum absolute, buat absolute
        $returnUrlCandidate = (strpos($returnPath, 'http') === 0) ? $returnPath : url($returnPath);

        if (!in_array($returnUrlCandidate, $allowed) && !in_array($returnPath, $allowed)) {
            $returnUrl = url('/spj/arsip_pembukuan_1'); // fallback aman
        } else {
            // gunakan versi absolute
            $returnUrl = $returnUrlCandidate;
        }

        // jika AJAX/json expected -> kembalikan JSON termasuk redirect target
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => ['id' => $rec->id],
                'redirect' => $returnUrl
            ]);
        }

        // non-AJAX fallback
        return redirect($returnUrl)->with('success', 'Data berhasil diperbarui.');
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
            $jabatanMengetahui = null;
            if ($record->mengetahui) {
                $personalisasi = ArsipPersonalisasi::where('nama', $record->mengetahui)->first();
                $jabatanMengetahui = $personalisasi ? $personalisasi->jabatan : null;
            }
            return view('spj.prosedur_transaksi.bukti_bank_keluar.cetak', [
                'record' => $record,
                'jabatan_mengetahui' => $jabatanMengetahui
            ]);
        }

        $data = $request->all();
        return view('spj.prosedur_transaksi.bukti_bank_keluar.cetak', compact('data'));
    }

    // bank masuk
    public function bukti_bank_masuk(Request $request)
    {
        $id = $request->query('id');
        $record = null;
        if ($id) {
            $record = ArsipBankMasuk::find($id);
            if (!$record) {
                // kalau tidak ditemukan, redirect kembali dengan pesan
                return redirect('/spj/arsip_pembukuan_1')
                    ->with('error', 'Data tidak ditemukan untuk edit.');
            }
        }

        // Anda mungkin perlu menyiapkan data lain (opsi dropdown, rekomendasi penandatangan, dll)
        return view('spj.prosedur_transaksi.bukti_bank_masuk.index', [
            'record' => $record,
            // 'approvers' => $this->getApprovers() // jika perlu
        ]);
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
            'dokumen_pendukung' => 'nullable',
            'link_gdrive' => 'nullable|url|max:2048',            
            'catatan' => 'nullable|string|max:255', // <-- tambahkan
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
            'catatan'            => $validated['catatan'] ?? null, // <-- sertakan
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

        // ===== generate nomor dokumen (BBM) =====
        $abbr = 'BBM';
        // hitung berapa record di tahun yang sama & kategori yang sama -> urutan berikutnya
        $tahun = Carbon::parse($validated['tanggal'])->format('Y');

        // count existing records in same year & same kategori_pembukuan
        $count = ArsipBankMasuk::whereYear('tanggal_transaksi', $tahun)
            ->where('kategori_pembukuan', (string)$payload['kategori_pembukuan'])
            ->count();

        $urut = $count + 1;
        $payload['nomor_dokumen'] = $this->generateNomorDokumen($abbr, $validated['tanggal'], $urut);

        // simpan
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
        $rec = ArsipBankMasuk::find($id);
        if (!$rec) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string|max:255',
            'sumber' => 'nullable|string|max:255',
            'nominal' => 'required|numeric',
            'penerima' => 'nullable|string|max:255',
            'kategori_pembukuan' => 'required|in:1,2',
            'link_gdrive' => 'nullable|url',
            'catatan' => 'nullable|string|max:255',
        ]);

        $rec->tanggal_transaksi = $validated['tanggal'];
        $rec->nama_transaksi     = $validated['nama_transaksi'];
        $rec->sumber             = $validated['sumber'] ?? null;
        $rec->nominal            = $validated['nominal'];
        $rec->penerima           = $validated['penerima'] ?? null;
        $rec->kategori_pembukuan = $validated['kategori_pembukuan'];
        $rec->link_gdrive        = $validated['link_gdrive'] ?? null;
        $rec->catatan            = $validated['catatan'] ?? null;

        $dok = $request->input('dokumen_pendukung', null);
        $rec->dokumen_pendukung = $dok ? json_encode($dok) : null;

        $rec->menyetujui = $request->input('menyetujui') ?? $rec->menyetujui;
        $rec->mengetahui = $request->input('mengetahui') ?? $rec->mengetahui;

        $rec->save();

        // ambil return param (decode) dan validasi sederhana (whitelist)
        $returnRaw = $request->input('return', '/spj/arsip_pembukuan_1');
        $returnPath = rawurldecode($returnRaw);

        // whitelist supaya aman (hindari open redirect)
        $allowed = [
            url('/spj/arsip_pembukuan_1'),
            url('/spj/arsip_pembukuan_2'),
            '/spj/arsip_pembukuan_1',
            '/spj/arsip_pembukuan_2'
        ];
        // jika $returnPath belum absolute, buat absolute
        $returnUrlCandidate = (strpos($returnPath, 'http') === 0) ? $returnPath : url($returnPath);

        if (!in_array($returnUrlCandidate, $allowed) && !in_array($returnPath, $allowed)) {
            $returnUrl = url('/spj/arsip_pembukuan_1'); // fallback aman
        } else {
            // gunakan versi absolute
            $returnUrl = $returnUrlCandidate;
        }

        // jika AJAX/json expected -> kembalikan JSON termasuk redirect target
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => ['id' => $rec->id],
                'redirect' => $returnUrl
            ]);
        }

        // non-AJAX fallback
        return redirect($returnUrl)->with('success', 'Data berhasil diperbarui.');
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
        // ambil user saat ini (bisa jadi null jika route tidak protected)
        $user = Auth::user();
        $nama_bumdes = $user ? $user->nama_bumdes ?? '' : '';
        $alamat_bumdes = $user ? $user->alamat_bumdes ?? '' : '';

        if ($request->has('id')) {
            $id = $request->query('id');
            $record = ArsipBankMasuk::find($id);
            if (!$record) abort(404, "Data ID $id tidak ditemukan");

            $jabatanMengetahui = null;
            if ($record->mengetahui) {
                $personalisasi = ArsipPersonalisasi::where('nama', $record->mengetahui)->first();
                $jabatanMengetahui = $personalisasi ? $personalisasi->jabatan : null;
            }
            return view('spj.prosedur_transaksi.bukti_bank_masuk.cetak', [
                'record' => $record,
                'jabatan_mengetahui' => $jabatanMengetahui,
                'nama_bumdes' => $nama_bumdes,
                'alamat_bumdes' => $alamat_bumdes,
            ]);
        }

        // fallback: jika belum ada id, kirim semua query params ke view sebagai $data
        $data = $request->all();
        return view('spj.prosedur_transaksi.bukti_bank_masuk.cetak', compact('data', 'nama_bumdes', 'alamat_bumdes'));
    }

    private function monthToRoman(int $month): string
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $map[$month] ?? (string)$month;
    }

    /**
     * generate nomor dokumen sesuai format: ABBR/NNN/ROMAN/YYYY
     * example: BBM/001/XII/2025
     *
     * @param string $abbr   // contoh 'BBM'
     * @param string|Carbon $tanggal // date string 'Y-m-d' atau Carbon
     * @param int $urut      // nomor urut (1..n)
     * @return string
     */
    private function generateNomorDokumen(string $abbr, $tanggal, int $urut): string
    {
        $dt = $tanggal instanceof Carbon ? $tanggal : Carbon::parse($tanggal);
        $seqStr = str_pad($urut, 3, '0', STR_PAD_LEFT);
        $bulan = (int) $dt->format('n'); // 1..12
        $roman = $this->monthToRoman($bulan);
        $tahun = $dt->format('Y');

        return sprintf('%s/%s/%s/%s', strtoupper($abbr), $seqStr, $roman, $tahun);
    }

    private function handleDokumenPendukung(Request $request, array &$payload): void
    {
        $docs = $request->input('dokumen_pendukung', null);
        if (is_null($docs)) return;

        if (is_array($docs)) {
            $payload['dokumen_pendukung'] = $docs;
            return;
        }

        $try = json_decode($docs, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
            $payload['dokumen_pendukung'] = $try;
        } else {
            $payload['dokumen_pendukung'] = array_values(array_filter(array_map('trim', explode(',', (string)$docs))));
        }
    }

}
