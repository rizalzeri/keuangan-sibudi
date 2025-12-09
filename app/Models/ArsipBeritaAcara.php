<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipBeritaAcara extends Model
{
    protected $table = 'arsip_berita_acara';

    protected $fillable = [
        'judul_berita_acara',
        'tanggal_peristiwa',
        'deskripsi',
        'link_gdrive',
    ];
    public $timestamps = true;

    protected $casts = [
        'tanggal_peristiwa' => 'date',
    ];

}
