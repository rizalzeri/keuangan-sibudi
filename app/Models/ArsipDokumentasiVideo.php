<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipDokumentasiVideo extends Model
{
    use HasFactory;

    protected $table = 'arsip_dokumentasi_video';

    public $timestamps = true;

    protected $fillable = [
        'tanggal_video',
        'kegiatan',
        'link_gdrive',
        'users_id',
    ];

    protected $casts = [
        'tanggal_video' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }
}
