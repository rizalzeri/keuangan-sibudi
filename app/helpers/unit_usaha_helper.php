<?php

use App\Models\Unit;
use App\Models\Proker;
use App\Models\Target;

if (!function_exists('unitUsaha')) {
    function unitUsaha()
    {
        return auth()->user()->profil;
    }
}

if (!function_exists('proker')) {
    function proker()
    {
        $user_id = auth()->user()->id;
        if (Proker::user()->where('tahun', session('selected_year', date('Y')))->get()->first() == null) {
            Proker::create(['user_id' => $user_id, 'tahun' => session('selected_year', date('Y'))]);
        }

        $proker = Proker::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        if (Target::user()->where('tahun', session('selected_year', date('Y')))->get()->first() == null) {
            Target::create(['user_id' => $user_id, 'tahun' => session('selected_year', date('Y')), 'proker_id' => $proker->id]);
        }
    }
}




if (!function_exists('namaUnitUsaha')) {
    function namaUnitUsaha()
    {
        $array_pendapatan = [
            'bno1' => 'BNO ' . 'Gaji Perusahaan',
            'bno2' => 'BNO ' . 'Atk',
            'bno3' => 'BNO ' . 'Rapat-rapat',
            'bno4' => 'BNO ' . 'Lain-lain',
            'kas' => 'kas',
        ];
        $units = Unit::user()->get();
        // Loop untuk setiap unit usaha
        foreach ($units as $unit) {
            $array_pendapatan['pu' . $unit->kode] = $unit->nm_unit;
        }
        // Loop untuk setiap unit usaha
        foreach ($units as $unit) {
            $array_pendapatan['bo' . $unit->kode] = $unit->nm_unit;
        }



        return $array_pendapatan;
    }
}
