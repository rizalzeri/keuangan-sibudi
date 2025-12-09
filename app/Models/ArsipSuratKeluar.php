<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipSuratKeluar extends Model
{
    protected $table = 'arsip_surat_keluar';

    protected $fillable = [
        'nomor_dokumen',
        'tujuan',
        'judul_surat',
        'isi',
        'link_gdrive',
    ];
    public $timestamps = true;
}
