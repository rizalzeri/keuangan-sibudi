<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\Modal;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class ModalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modals = Modal::user()->get();
        return view('modal.index', ['modals'  => $modals]);
    }

    public function exportPdf()
    {

        $modals = Modal::user()->get();
        $data = [
            'modals'  => $modals,
        ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('modal.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required',
            'sumber' => 'required|string|max:100',
            'mdl_desa' => 'max:11',
            'mdl_masyarakat' => 'max:11',
            'mdl_bersama' => 'max:11',
        ]);

        $validated['created_at'] = created_at();
        $validated['user_id'] = auth()->user()->id;
        $modalCreate = Modal::create($validated);
        if ($modalCreate && $request->has('no_kas')) {


            // Array untuk mapping jenis modal
            foreach (['mdl_desa' => 'desa', 'mdl_masyarakat' => 'masyarakat', 'mdl_bersama' => 'BUMDesa bersama'] as $key => $source) {
                if (isset($validated[$key])) {
                    $bukuUmum =  bukuUmum(
                        "Modal Tambah dari $source",
                        'debit',
                        'kas',
                        'pendanaan',
                        $request->$key,
                        'modal',
                        $modalCreate->id,
                        created_at()
                    );

                    $id = rendem();

                    histori($id, 'modals', $validated, 'create', $modalCreate->id);
                    histori($id, 'buks', $request->$key, 'create', $bukuUmum->id);

                    break; // Menghentikan loop setelah kondisi terpenuhi
                }
            }
        }

        return redirect('/modal');
    }


    /**
     * Display the specified resource.
     */
    public function show(modal $modal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(modal $modal)
    {
        return view('modal.edit', [
            'modal' => $modal
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, modal $modal)
    {
        $modalArray = $modal->toArray();

        $validated = $request->validate([
            'tahun' => 'required',
            'sumber' => 'required|string|max:100',
            'mdl_desa' => 'max:11',
            'mdl_masyarakat' => 'max:11',
            'mdl_bersama' => 'max:11',
        ]);

        $validated['user_id'] = auth()->user()->id;

        // Update modal
        $modalUpdate = Modal::where('id', $modal->id)->update($validated);
        $idBuk = Buk::where('akun', 'modal')->where('id_akun', $modal->id)->first()->id;

        if ($modalUpdate) {
            $modalData = [
                'mdl_desa' => $request->mdl_desa,
                'mdl_masyarakat' => $request->mdl_masyarakat,
                'mdl_bersama' => $request->mdl_bersama,
            ];

            foreach ($modalData as $key => $value) {
                if (!is_null($modal->$key)) {
                    // Ambil nilai lama dari $modalArray
                    $value_dulu = $modalArray[$key];

                    // Update buku umum
                    updateBukuUmum('modal', $modal->id, $value);

                    // Catat histori
                    $id = rendem();
                    histori($id, 'modals', $modalArray, 'update', $modal->id);
                    histori($id, 'buks', ['nilai' => $value_dulu], 'update', $idBuk);

                    break; // Berhenti setelah menemukan dan memperbarui yang pertama
                }
            }
        }

        return redirect('/modal');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(modal $modal)
    {
        histori(rendem(), 'modals', $modal->toArray(), 'delete', $modal->id);

        Modal::where('id', $modal->id)->delete();
        return redirect()->back();
    }
}
