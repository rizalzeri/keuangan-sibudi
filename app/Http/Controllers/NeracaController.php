<?php

namespace App\Http\Controllers;

use App\Models\Tutup;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class NeracaController extends Controller
{
    public function index()
    {
        $tutup =  Tutup::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $neraca =  neraca();

        if (isset($tutup)) {
            $data = json_decode($tutup->data, true);
            $setatus = true;
            $data_tutup = $tutup;


            return view('neraca.index', $data, ['setatus' => $setatus, 'data_tutup' => $data_tutup]);
        } else {
            return view('neraca.index', [
                'piutang' => $neraca['piutang'],
                'saldo_pinjam' => $neraca['saldo_pinjam'],
                'persediaan_dagang' => $neraca['persediaan_dagang'],
                'bayar_dimuka' => $neraca['bayar_dimuka'],
                'investasi' => $neraca['investasi'],
                'bangunan' => $neraca['bangunan'],
                'aktiva_lain' => $neraca['aktiva_lain'],
                'total_aktiva' => $neraca['total_aktiva'],
                'kas' => $neraca['kas'],
                'hutang' => $neraca['hutang'],
                'modal_desa' => $neraca['modal_desa'],
                'modal_masyarakat' => $neraca['modal_masyarakat'],
                'modal_bersama' => $neraca['modal_bersama'],
                'ditahan' => $neraca['ditahan'],
                'laba_rugi_berjalan' => labaRugi(session('selected_year', date('Y')))['totalLabaRugi'],
                'passiva' => $neraca['passiva'],
                'bank' => $neraca['bank'],
                'setatus' => false
            ]);
        }
    }

    public function delete(Tutup $tutup)
    {
        histori(rendem(), 'tutups', $tutup->toArray(), 'delete', $tutup->id);
        $tutup->delete();

        return redirect()->back()->with('success', 'Buku telah di buka kembali');
    }

    public function exportPdf()
    {


        $tutup =  Tutup::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $neraca =  neraca();

        if (isset($tutup)) {
            $data_neraca = json_decode($tutup->data, true);
            $data_neraca['tutup'] = true;
            $data = $data_neraca;
        } else {
            $data = [
                'piutang' => $neraca['piutang'],
                'saldo_pinjam' => $neraca['saldo_pinjam'],
                'persediaan_dagang' => $neraca['persediaan_dagang'],
                'bayar_dimuka' => $neraca['bayar_dimuka'],
                'investasi' => $neraca['investasi'],
                'bangunan' => $neraca['bangunan'],
                'aktiva_lain' => $neraca['aktiva_lain'],
                'total_aktiva' => $neraca['total_aktiva'],
                'kas' => $neraca['kas'],
                'hutang' => $neraca['hutang'],
                'modal_desa' => $neraca['modal_desa'],
                'modal_masyarakat' => $neraca['modal_masyarakat'],
                'modal_bersama' => $neraca['modal_bersama'],
                'ditahan' => $neraca['ditahan'],
                'laba_rugi_berjalan' => labaRugi(session('selected_year', date('Y')))['totalLabaRugi'],
                'passiva' => $neraca['passiva'],
                'bank' => $neraca['bank'],
                'tutup' => false
            ];
        }

        // Gunakan facade PDF
        $pdf = PDF::loadView('neraca.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }

    public function tutup(Request $request)
    {
        // Ambil data neraca
        $neraca = neraca();

        // Ambil total laba rugi berjalan berdasarkan tahun yang dipilih atau tahun sekarang
        $selectedYear = session('selected_year', date('Y'));
        $labaRugi = labaRugi($selectedYear)['totalLabaRugi'];

        // Susun data neraca ke dalam array
        $data = [
            'aktiva' => $neraca['total_aktiva'] ?? 0,
            'piutang' => $neraca['piutang'] ?? 0,
            'saldo_pinjam' => $neraca['saldo_pinjam'] ?? 0,
            'persediaan_dagang' => $neraca['persediaan_dagang'] ?? 0,
            'bayar_dimuka' => $neraca['bayar_dimuka'] ?? 0,
            'investasi' => $neraca['investasi'] ?? 0,
            'bangunan' => $neraca['bangunan'] ?? 0,
            'aktiva_lain' => $neraca['aktiva_lain'] ?? 0,
            'total_aktiva' => $neraca['total_aktiva'] ?? 0,
            'kas' => $neraca['kas'] ?? 0,
            'bank' => $neraca['bank'] ?? 0,
            'hutang' => $neraca['hutang'] ?? 0,
            'modal_desa' => $neraca['modal_desa'] ?? 0,
            'modal_masyarakat' => $neraca['modal_masyarakat'] ?? 0,
            'modal_bersama' => $neraca['modal_bersama'] ?? 0,
            'ditahan' => $neraca['ditahan'] ?? 0,
            'laba_rugi_berjalan' => $labaRugi,
            'passiva' => $neraca['passiva'] ?? 0,
        ];

        // Simpan data neraca ke dalam tabel 'tutup'
        $tutup =  Tutup::create([
            'data' => json_encode($data), // Pastikan data diubah ke JSON jika tipe kolom adalah teks JSON
            'laporan' => 'neraca',
            'tahun' => $selectedYear,
            'user_id' => auth()->user()->id,
        ]);

        if ($tutup->id) {
            histori(rendem(), 'tutups', $data, 'create', $tutup->id);
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data neraca berhasil disimpan');
    }
}
