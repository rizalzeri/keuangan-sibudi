<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipDokumentasiFoto extends Model
{
    use HasFactory;

    protected $table = 'arsip_dokumentasi_foto';

    public $timestamps = true;

    protected $fillable = [
        'tanggal_foto',
        'kegiatan',
        'link_gdrive',
        'users_id',
    ];

    protected $casts = [
        'tanggal_foto' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }
}
