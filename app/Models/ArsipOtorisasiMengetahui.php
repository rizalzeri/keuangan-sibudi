<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipOtorisasiMengetahui extends Model
{
    protected $table = 'arsip_otorisasi_mengetahui';
    protected $fillable = ['kategori', 'arsip_personalisasi_id'];
    public $timestamps = true;

    public function personalisasi()
    {
        return $this->belongsTo(ArsipPersonalisasi::class, 'arsip_personalisasi_id');
    }
}
