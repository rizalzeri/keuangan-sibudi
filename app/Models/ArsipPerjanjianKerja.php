<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipPerjanjianKerja extends Model
{
    protected $table = 'arsip_perjanjian_kerja';

    protected $fillable = [
        'nama_kerjasama',
        'nomor_dokumen',
        'pihak',
        'bentuk_kerja_sama',
        'deskripsi',
        'durasi',
        'link_gdrive',
        'users_id',
    ];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }
}
