<?php

namespace App\Http\Controllers;

use App\Models\Lpj;
use App\Models\Unit;
use App\Models\Ekuit;
use App\Models\Modal;
use App\Models\Proker;
use App\Models\Target;
use App\Models\Program;
use App\Models\Kerjasama;
use Illuminate\Http\Request;

class ProkerController extends Controller
{
    public function proker()
    {

        $labaRugi = labaRugi(session('selected_year', date('Y')));

        $user_id = auth()->user()->id;
        if (Proker::user()->where('tahun', session('selected_year', date('Y')))->get()->first() == null) {
            Proker::create(['user_id' => $user_id, 'tahun' => session('selected_year', date('Y'))]);
        }
        $proker = Proker::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        if (Target::user()->where('tahun', session('selected_year', date('Y')))->get()->first() == null) {
            Target::create(['user_id' => $user_id, 'tahun' => session('selected_year', date('Y')), 'proker_id' => $proker->id]);
        }

        $ekuitas = Ekuit::user()->get()->first();
        $neraca =  neraca();
        $modal_desa = Modal::user()->get()->sum('mdl_desa');
        $modal_masyarakat = Modal::user()->get()->sum('mdl_masyarakat');
        $modal_bersama = Modal::user()->get()->sum('mdl_bersama');
        $tahun =  $ekuitas->tahun;

        $target = Target::user()->where('tahun', session('selected_year', date('Y')) - 1)->get()->first();
        $units = Unit::user()->get();
        $title = 'B. KINERJA BUMDES';
        $next = '/proker/kualititif';
        return view('proker.kuantitatif', [
            'title' => $title,
            'units' => $units,
            'pendapatan' => $labaRugi['pendapatan'],
            'pendapatanBulan' => $labaRugi['pendapatanBulan'],
            'pendapatanTahun' => $labaRugi['pendapatanTahun'],
            'tahun' => $labaRugi['tahun'],
            'totalBiaya' => $labaRugi['totalBiaya'],
            'akumulasiBiaya' => $labaRugi['akumulasiBiaya'],
            'labaRugi' => $labaRugi['labaRugi'],
            'totalLabaRugi' => $labaRugi['totalLabaRugi'],
            'akumulasi_penyusutan' => $labaRugi['akumulasi_penyusutan'],
            'next' => $next,
            'aktiva' => $neraca['aktiva'],
            'target' => $target,
            'modal_desa' => $modal_desa,
            'modal_masyarakat' => $modal_masyarakat,
            'modal_bersama' => $modal_bersama,
            'ekuitas' => $ekuitas,
            'ditahan' => $neraca['ditahan'],
            'aktiva' => $neraca['aktiva'],
            'laba_berjalan' => labaRugiTahun($tahun)['totalLabaRugi']
        ]);
    }

    public function kualititif()
    {

        proker();

        $user_id = auth()->user()->id;
        if (Lpj::user()->where('tahun', session('selected_year', date('Y')))->get()->first() == null) {
            Lpj::create(['user_id' => $user_id, 'tahun' => session('selected_year', date('Y'))]);
        }

        $lpj = Lpj::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $proker = Proker::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $title = 'B. KINERJA BUMDES';
        $back = '/proker';
        $next = '/proker/kualititif';
        return view('proker.kualititif', [
            'title' => $title,
            'next' => $next,
            'back' => $back,
            'proker' => $proker,
            'lpj' => $lpj
        ]);
    }

    public function kualititifUpdate(Request $request, Proker $proker)
    {
        proker();


        Proker::where('id', $proker->id)->update(['kualititif' => $request->kualititif]);

        return redirect('/proker/strategi');
    }

    public function strategi()
    {
        proker();
        $user_id = auth()->user()->id;
        if (Lpj::user()->where('tahun', session('selected_year', date('Y')))->get()->first() == null) {
            Lpj::create(['user_id' => $user_id, 'tahun' => session('selected_year', date('Y'))]);
        }

        $lpj = Lpj::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $proker = Proker::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $title = 'C. STRATEGI DAN KEBIJAKAN TAHUN BERIKUTNYA';
        $back = '/proker/kualititif';
        $next = '/proker/strategi';
        return view('proker.strategi', [
            'title' => $title,
            'next' => $next,
            'back' => $back,
            'proker' => $proker,
            'lpj' => $lpj
        ]);
    }

    public function strategiUpdate(Request $request, Proker $proker)
    {
        proker();

        Proker::where('id', $proker->id)->update(['strategi' => $request->strategi]);

        return redirect('/proker/sasaran');
    }

    public function sasaran()
    {
        proker();
        $units = Unit::user()->get();
        $proker = Proker::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $target = Target::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $title = 'D. SASARAN KINERJA';
        $back = '/proker/strategi';
        $labaRugi = labaRugi(session('selected_year', date('Y')));
        $ekuitas = Ekuit::user()->get()->first();
        $neraca =  neraca();
        $modal_desa = Modal::user()->get()->sum('mdl_desa');
        $modal_masyarakat = Modal::user()->get()->sum('mdl_masyarakat');
        $modal_bersama = Modal::user()->get()->sum('mdl_bersama');
        $tahun =  $ekuitas->tahun;
        return view('proker.sasaran', [
            'title' => $title,
            'back' => $back,
            'proker' => $proker,
            'units' => $units,
            'pendapatan' => $labaRugi['pendapatan'],
            'pendapatanBulan' => $labaRugi['pendapatanBulan'],
            'pendapatanTahun' => $labaRugi['pendapatanTahun'],
            'tahun' => $labaRugi['tahun'],
            'totalBiaya' => $labaRugi['totalBiaya'],
            'akumulasiBiaya' => $labaRugi['akumulasiBiaya'],
            'labaRugi' => $labaRugi['labaRugi'],
            'totalLabaRugi' => $labaRugi['totalLabaRugi'],
            'akumulasi_penyusutan' => $labaRugi['akumulasi_penyusutan'],
            'modal_desa' => $modal_desa,
            'modal_masyarakat' => $modal_masyarakat,
            'modal_bersama' => $modal_bersama,
            'ekuitas' => $ekuitas,
            'ditahan' => $neraca['ditahan'],
            'aktiva' => $neraca['aktiva'],
            'laba_berjalan' => labaRugiTahun($tahun)['totalLabaRugi'],
            'target' => $target
        ]);
    }

    public function sasaranUpdate(Request $request, Proker $proker)
    {
        proker();
        // Validasi input
        $validated = $request->validate([
            'laba' => 'nullable|numeric',
            'pades' => 'nullable|numeric',
            'aset' => 'nullable|numeric',
            'gaji' => 'nullable|numeric',
            'atk' => 'nullable|numeric',
            'penyusutan' => 'nullable|numeric',
            'lain' => 'nullable|numeric',
        ]);

        // Ambil unit terkait pengguna
        $units = Unit::user()->get();

        // Proses omset
        $omset = $units->mapWithKeys(function ($unit) use ($request) {
            return ['pu' . $unit->kode => $request->input('pu' . $unit->kode)];
        });

        // Proses pembiayaan
        $pembiayaan = $units->mapWithKeys(function ($unit) use ($request) {
            return ['bo' . $unit->kode => $request->input('bo' . $unit->kode)];
        });

        // Siapkan data untuk diperbarui
        $data = [
            'omset' => json_encode($omset),
            'pembiayaan' => json_encode($pembiayaan),
            'laba' => $validated['laba'],
            'pades' => $validated['pades'],
            'aset' => $validated['aset'],
            'gaji' => $validated['gaji'],
            'atk' => $validated['atk'],
            'penyusutan' => $validated['penyusutan'],
            'lain' => $request->input('lain'), // Tetap nullable
        ];

        Target::where('proker_id', $proker->id)->where('tahun', session('selected_year', date('Y')))->update($data);

        // Redirect dengan pesan sukses
        return redirect('/proker/rencana/kegiatan');
    }

    public function rencanaKegiatan()
    {
        proker();
        $proker = Proker::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $title = 'E. RENCANA KEGIATAN/PROGRAM';
        $back = '/proker/sasaran';
        $programs = Program::user()->where('tahun', session('selected_year', date('Y')))->get();
        $kerjasamas = Kerjasama::user()->where('tahun', session('selected_year', date('Y')))->get();



        return view('proker.rencana_kegiatan', [
            'title' => $title,
            'back' => $back,
            'proker' => $proker,
            'programs' => $programs,
            'kerjasamas' => $kerjasamas
        ]);
    }

    public function kegiatanStore(Request $request)
    {
        proker();

        $request->validate([
            'input.*.kegiatan' => 'nullable|string',
            'input.*.alokasi' => 'nullable|string',
            'input.*.sumber' => 'nullable|string',
        ]);

        $tahun = session('selected_year', date('Y'));
        $user_id = auth()->id();

        foreach ($request->input as $key => $value) {
            $value['tahun'] = $tahun;
            $value['user_id'] = $user_id;
            Program::create($value);
        }

        return back();
    }

    public function KerjasamaStore(Request $request)
    {
        proker();

        $request->validate([
            'input.*.pihak' => 'nullable|string',
            'input.*.deskrisi' => 'nullable|string',
            'input.*.output' => 'nullable|string',
        ]);

        $tahun = session('selected_year', date('Y'));
        $user_id = auth()->id();

        foreach ($request->input as $key => $value) {
            $value['tahun'] = $tahun;
            $value['user_id'] = $user_id;
            Kerjasama::create($value);
        }

        return back();
    }

    public function kegiatanDestroy(Request $request, Program $program)
    {
        proker();
        $program->delete();

        return back();
    }

    public function kerjasamaDestroy(Request $request, Kerjasama $kerjasama)
    {
        proker();
        $kerjasama->delete();

        return back();
    }
}
