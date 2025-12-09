<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipPerjalananDinas extends Model
{
    protected $table = 'arsip_perjalanan_dinas';

    protected $fillable = [
        'nomor_dokumen',
        'tanggal_perjalanan_dinas',
        'kegiatan',
        'tempat',
        'transport',
        'link_gdrive',
    ];
    public $timestamps = true;

    protected $casts = [
        'tanggal_perjalanan_dinas' => 'date',
    ];

}
