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
        'users_id',
    ];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }
}
