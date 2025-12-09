<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipDokumentasiVideo extends Model
{
    use HasFactory;

    // nama tabel (sesuaikan jika berbeda)
    protected $table = 'arsip_dokumentasi_video';

    // bila kolom timestamp bernama created_date / updated_date
    public $timestamps = true;

    protected $fillable = [
        'tanggal_video',
        'kegiatan',
        'link_gdrive'
    ];

    // casting
    protected $casts = [
        'tanggal_video' => 'date'
    ];
}
