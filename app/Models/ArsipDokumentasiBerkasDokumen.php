<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipDokumentasiBerkasDokumen extends Model
{
    use HasFactory;

    // nama tabel (sesuaikan jika berbeda)
    protected $table = 'arsip_dokumentasi_berkas_dokumen';

    // bila kolom timestamp bernama created_date / updated_date
    public $timestamps = true;

    protected $fillable = [
        'tanggal_berkas_dokumen',
        'nama_dokumen',
        'link_gdrive'
    ];

    // casting
    protected $casts = [
        'tanggal_berkas_dokumen' => 'date'
    ];
}
