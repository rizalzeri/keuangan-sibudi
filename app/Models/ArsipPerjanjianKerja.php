<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipPerjanjianKerja extends Model
{
    protected $table = 'arsip_perjanjian_kerja';

    protected $fillable = [
        'nomor_dokumen',
        'pihak',
        'bentuk_kerja_sama',
        'deskripsi',
        'durasi',
        'link_gdrive',
    ];
    public $timestamps = true;

}
