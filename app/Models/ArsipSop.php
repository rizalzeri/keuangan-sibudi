<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipSop extends Model
{
    protected $table = 'arsip_sop';

    protected $fillable = [
        'nama_sop',
        'nomor_dokumen',
        'ruang_lingkup',
        'status',
        'link_gdrive',
        'users_id',
    ];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }
}
