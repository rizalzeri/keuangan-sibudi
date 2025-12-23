<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipPerjalananDinas;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Auth;

class ArsipPerjalananDinasController extends Controller
{
    public function index()
    {
        $items = ArsipPerjalananDinas::orderBy('id', 'asc')->get();
        return view('spj.arsip_perjalanan_dinas.index', compact('items'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nomor_dokumen' => 'nullable|string|max:255',
            'tanggal_perjalanan_dinas' => 'nullable|date',
            'kegiatan' => 'nullable|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'tempat_2' => 'nullable|string|max:255',
            'transport' => 'nullable', // will be JSON/array from client
            'link_gdrive' => 'nullable|string',
            // new fields
            'dasar_perjalanan_tugas' => 'nullable|string',
            'pejabat_pemberi_tugas' => 'nullable|string|max:255',
            'jabatan_pejabat' => 'nullable|string|max:255',
            'pegawai_personil' => 'nullable', // json/array
            'maksud_perjalanan_tugas' => 'nullable|string',
            'tujuan_1' => 'nullable|string|max:255',
            'tujuan_2' => 'nullable|string|max:255',
            'lama_perjalanan_hari' => 'nullable|integer',
            'dasar_pembebanan_anggaran' => 'nullable|string|max:255',
            'pembiayaan' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'tempat_dikeluarkan' => 'nullable|string|max:255',
        ];

        $validated = $request->validate($rules);

        // nomor generate jika tidak dikirim
        if (empty($validated['nomor_dokumen'])) {
            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;
            $countThisYear = ArsipPerjalananDinas::whereYear('created_at', $year)->count();
            $no = $countThisYear + 1;
            $monthRoman = $this->monthToRoman($month);
            $validated['nomor_dokumen'] = "SPPD/{$no}/{$monthRoman}/{$year}";
        }

        // Normalisasi pegawai_personil dan transport: jika datang sebagai JSON string, decode; jika array, keep.
        if (isset($validated['pegawai_personil']) && is_string($validated['pegawai_personil'])) {
            $decoded = json_decode($validated['pegawai_personil'], true);
            $validated['pegawai_personil'] = $decoded ?: null;
        }

        if (isset($validated['transport']) && is_string($validated['transport'])) {
            $decoded = json_decode($validated['transport'], true);
            $validated['transport'] = $decoded ?: null;
        }

        // pembiayaan sudah numeric via validation

        $pd = ArsipPerjalananDinas::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Perjalanan dinas berhasil ditambahkan!',
                'data'    => $pd
            ], 201);
        }

        return back()->with('success', 'Perjalanan dinas berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nomor_dokumen' => 'required|string|max:255',
            'tanggal_perjalanan_dinas' => 'nullable|date',
            'kegiatan' => 'nullable|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'tempat_2' => 'nullable|string|max:255',
            'transport' => 'nullable',
            'link_gdrive' => 'nullable|string',
            // new fields
            'dasar_perjalanan_tugas' => 'nullable|string',
            'pejabat_pemberi_tugas' => 'nullable|string|max:255',
            'jabatan_pejabat' => 'nullable|string|max:255',
            'pegawai_personil' => 'nullable',
            'maksud_perjalanan_tugas' => 'nullable|string',
            'tujuan_1' => 'nullable|string|max:255',
            'tujuan_2' => 'nullable|string|max:255',
            'lama_perjalanan_hari' => 'nullable|integer',
            'dasar_pembebanan_anggaran' => 'nullable|string|max:255',
            'pembiayaan' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'tempat_dikeluarkan' => 'nullable|string|max:255',
        ];

        $validated = $request->validate($rules);

        $pd = ArsipPerjalananDinas::find($id);
        if (!$pd) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Perjalanan dinas dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Perjalanan dinas dengan ID $id tidak ditemukan");
        }

        if (isset($validated['pegawai_personil']) && is_string($validated['pegawai_personil'])) {
            $decoded = json_decode($validated['pegawai_personil'], true);
            $validated['pegawai_personil'] = $decoded ?: null;
        }

        if (isset($validated['transport']) && is_string($validated['transport'])) {
            $decoded = json_decode($validated['transport'], true);
            $validated['transport'] = $decoded ?: null;
        }

        $pd->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Perjalanan dinas berhasil diperbarui!',
                'data'    => $pd
            ]);
        }

        return back()->with('success', 'Perjalanan dinas berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $pd = ArsipPerjalananDinas::find($id);
        if (!$pd) {
            // gunakan expectsJson/wantsJson untuk mendeteksi permintaan AJAX/JSON
            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => "Perjalanan dinas dengan ID $id tidak ditemukan"], 404);
            }
            return back()->with('error', "Perjalanan dinas dengan ID $id tidak ditemukan");
        }

        $pd->delete();

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Perjalanan dinas berhasil dihapus!']);
        }

        return back()->with('success', 'Perjalanan dinas berhasil dihapus!');
    }


    private function monthToRoman($month)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $map[intval($month)] ?? (string)$month;
    }

    public function generateDoc($id)
    {   
        $pd = ArsipPerjalananDinas::find($id);
        if (!$pd) {
            abort(404, 'Data tidak ditemukan');
        }

        $templatePath = public_path('assets/word/FORM SURAT PERINTAH PERJALANAN TUGAS.docx');
        if (!file_exists($templatePath)) {
            abort(500, 'Template dokumen tidak ditemukan: ' . $templatePath);
        }

        try {
            $template = new TemplateProcessor($templatePath);

            // tanggal format Indonesia: 01 Januari 2025
            $tanggal = $pd->tanggal_perjalanan_dinas
                ? Carbon::parse($pd->tanggal_perjalanan_dinas)->locale('id')->translatedFormat('d F Y')
                : '';

            // ambil nama_bumdes dari user yang sedang login (fallback ke kosong bila tidak ada)
            $user = Auth::user();
            $nama_bumdes = $user ? ($user->nama_bumdes ?? '') : '';

            // pegawai_personil: gunakan normalizePersonil untuk memastikan pasangan nama/jabatan
            $pegawaiLines = '';
            if (!empty($pd->pegawai_personil)) {
                $normalized = $this->normalizePersonil($pd->pegawai_personil);
                $lines = [];
                foreach ($normalized as $p) {
                    $nama = $p['nama'] ?? (isset($p[0]) ? $p[0] : '');
                    $jabatan = $p['jabatan'] ?? (isset($p[1]) ? $p[1] : '');
                    if ($nama || $jabatan) {
                        $lines[] = trim("{$nama} - {$jabatan}", " -");
                    }
                }
                $pegawaiLines = implode("\n", $lines);
            }

            // transport: jika array -> gabungkan
            $transportText = '';
            if (!empty($pd->transport) && is_array($pd->transport)) {
                $items = [];
                foreach ($pd->transport as $t) {
                    if (is_array($t)) {
                        if (!empty($t['other'])) $items[] = $t['other'];
                        elseif (!empty($t['label'])) $items[] = $t['label'];
                        else $items[] = json_encode($t);
                    } else {
                        $items[] = $t;
                    }
                }
                $transportText = implode(', ', array_filter($items));
            }

            // mapping semua placeholder yang mungkin ada di template
            $map = [
                'nomor_dokumen' => $pd->nomor_dokumen ?? '',
                'kegiatan' => $pd->kegiatan ?? '',
                'tanggal' => $tanggal,
                'tempat' => $pd->tempat ?? '',
                'tempat_2' => $pd->tempat_2 ?? '',
                'transport' => $transportText,
                'dasar_perjalanan_tugas' => $pd->dasar_perjalanan_tugas ?? '',
                'pejabat_pemberi_tugas' => $pd->pejabat_pemberi_tugas ?? '',
                'jabatan_pejabat' => $pd->jabatan_pejabat ?? '',
                'pegawai_personil' => $pegawaiLines,
                'maksud_perjalanan_tugas' => $pd->maksud_perjalanan_tugas ?? '',
                'tujuan_1' => $pd->tujuan_1 ?? '',
                'tujuan_2' => $pd->tujuan_2 ?? '',
                'lama_perjalanan_hari' => $pd->lama_perjalanan_hari ?? '',
                'dasar_pembebanan_anggaran' => $pd->dasar_pembebanan_anggaran ?? '',
                'pembiayaan' => $pd->pembiayaan !== null ? number_format($pd->pembiayaan, 2, ',', '.') : '',
                'keterangan' => $pd->keterangan ?? '',
                'tempat_dikeluarkan' => $pd->tempat_dikeluarkan ?? '',
                // tambahan: nama_bumdes dari users
                'nama_bumdes' => $nama_bumdes,
            ];

            foreach ($map as $k => $v) {
                $template->setValue($k, $v);
            }

            $tmpFile = tempnam(sys_get_temp_dir(), 'sppd_') . '.docx';
            $template->saveAs($tmpFile);

            $filename = 'SPPD_' . ($pd->id) . '.docx';
            return response()->download($tmpFile, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // \Log::error($e);
            abort(500, 'Gagal meng-generate dokumen');
        }
    }

    public function show($id)
    {
        $pd = ArsipPerjalananDinas::find($id);
        if (!$pd) {
            return response()->json(['success'=>false, 'message'=>'Not found'], 404);
        }

        // pastikan pegawai_personil sudah dinormalisasi sebelum dikirim ke client
        $pd->pegawai_personil = $this->normalizePersonil($pd->pegawai_personil);

        return response()->json($pd);
    }

    /**
     * Normalisasi pegawai_personil yang mungkin disimpan dalam berbagai bentuk:
     * - already [ {nama:.., jabatan:..}, {...} ]
     * - or [ {nama:..}, {jabatan:..}, {nama:..}, {jabatan:..} ]
     * - or numeric-indexed [ ['Rizal','Direktur'], ... ]
     */
    private function normalizePersonil($arr)
    {
        if (empty($arr) || !is_array($arr)) return [];

        $out = [];
        $temp = [];

        foreach ($arr as $it) {
            if ($it === null) continue;

            // case: numeric indexed row like ['Rizal','Direktur']
            if (is_array($it) && array_key_exists(0, $it) && array_key_exists(1, $it)) {
                $out[] = ['nama' => $it[0], 'jabatan' => $it[1]];
                $temp = [];
                continue;
            }

            // case: object-like associative ['nama'=>.., 'jabatan'=>..]
            if (is_array($it) && (isset($it['nama']) || isset($it['jabatan']) || isset($it[0]) || isset($it[1]))) {
                $nama = isset($it['nama']) ? $it['nama'] : (isset($it[0]) ? $it[0] : null);
                $jabatan = isset($it['jabatan']) ? $it['jabatan'] : (isset($it[1]) ? $it[1] : null);

                if ($nama !== null && $jabatan !== null) {
                    $out[] = ['nama' => $nama, 'jabatan' => $jabatan];
                    $temp = [];
                    continue;
                }

                // if only nama or only jabatan
                if ($nama !== null && $jabatan === null) {
                    if (!empty($temp) && !isset($temp['nama']) && isset($temp['jabatan'])) {
                        // previous had jabatan only, combine
                        $out[] = ['nama' => $nama, 'jabatan' => $temp['jabatan']];
                        $temp = [];
                    } else {
                        $temp['nama'] = $nama;
                        if (isset($temp['nama']) && isset($temp['jabatan'])) {
                            $out[] = $temp;
                            $temp = [];
                        }
                    }
                    continue;
                }

                if ($jabatan !== null && $nama === null) {
                    if (!empty($temp) && !isset($temp['jabatan']) && isset($temp['nama'])) {
                        // previous had nama only, combine
                        $out[] = ['nama' => $temp['nama'], 'jabatan' => $jabatan];
                        $temp = [];
                    } else {
                        $temp['jabatan'] = $jabatan;
                        if (isset($temp['nama']) && isset($temp['jabatan'])) {
                            $out[] = $temp;
                            $temp = [];
                        }
                    }
                    continue;
                }
            }

            // fallback: if scalar string, treat as name or job depending on temp
            if (is_string($it)) {
                if (!isset($temp['nama'])) {
                    $temp['nama'] = $it;
                } else if (!isset($temp['jabatan'])) {
                    $temp['jabatan'] = $it;
                    $out[] = $temp;
                    $temp = [];
                } else {
                    // push and reset
                    $out[] = $temp;
                    $temp = ['nama' => $it];
                }
            }
        }

        if (!empty($temp)) {
            // push remaining pair or single
            $out[] = $temp;
        }

        // Normalize keys: ensure both nama and jabatan exist (jabatan may be empty)
        $out = array_map(function($p){
            return [
                'nama' => isset($p['nama']) ? $p['nama'] : (isset($p[0]) ? $p[0] : null),
                'jabatan' => isset($p['jabatan']) ? $p['jabatan'] : (isset($p[1]) ? $p[1] : null)
            ];
        }, $out);

        return $out;
    }

}
