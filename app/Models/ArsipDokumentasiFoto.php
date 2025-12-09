<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipDokumentasiFoto extends Model
{
    use HasFactory;

    // nama tabel (sesuaikan jika berbeda)
    protected $table = 'arsip_dokumentasi_foto';

    // bila kolom timestamp bernama created_date / updated_date
    public $timestamps = true;

    protected $fillable = [
        'tanggal_foto',
        'kegiatan',
        'link_gdrive'
    ];

    // casting
    protected $casts = [
        'tanggal_foto' => 'date'
    ];
}
