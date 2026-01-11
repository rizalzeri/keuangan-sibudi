<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipSubKategori extends Model
{
    protected $table = 'arsip_sub_kategori';

    protected $fillable = [
        'kategori_id',
        'sub_kategori',
        'link',
        'created_at',
        'updated_at'
    ];

    public $timestamps = false;

    public function kategori()
    {
        return $this->belongsTo(ArsipKategori::class, 'kategori_id');
    }
}
