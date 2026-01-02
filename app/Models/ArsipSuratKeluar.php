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
        'users_id',
    ];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }
}
