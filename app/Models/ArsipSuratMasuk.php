<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipSuratMasuk extends Model
{
    protected $table = 'arsip_surat_masuk';

    protected $fillable = [
        'pengirim',
        'judul_surat',
        'isi',
        'link_gdrive',
    ];
    public $timestamps = true;
}
