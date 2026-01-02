<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipBeritaAcara extends Model
{
    protected $table = 'arsip_berita_acara';

    protected $fillable = [
        'judul_berita_acara',
        'nomor_dokumen',
        'tanggal_peristiwa',
        'deskripsi',
        'link_gdrive',
        'users_id',
    ];
    public $timestamps = true;

    protected $casts = [
        'tanggal_peristiwa' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }
}
