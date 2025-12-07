<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipLembaga extends Model
{
    use HasFactory;

    protected $table = 'arsip_lembaga';

    protected $fillable = [
        'nama_dokumen',
        'nomor',
        'status',
        'link_gdrive'
    ];

    // Laravel otomatis pakai created_at & updated_at
    public $timestamps = true;

    protected $primaryKey = 'id'; // default, tapi bagus ditambahkan
}
