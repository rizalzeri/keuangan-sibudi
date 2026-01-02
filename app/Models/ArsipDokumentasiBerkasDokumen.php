<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipDokumentasiBerkasDokumen extends Model
{
    use HasFactory;

    protected $table = 'arsip_dokumentasi_berkas_dokumen';

    public $timestamps = true;

    protected $fillable = [
        'tanggal_berkas_dokumen',
        'nama_dokumen',
        'link_gdrive',
        'users_id',
    ];

    protected $casts = [
        'tanggal_berkas_dokumen' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }
}
