<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipPersonalisasi extends Model
{
    protected $table = 'arsip_personalisasi';
    protected $fillable = ['jabatan', 'nama', 'users_id'];
    public $timestamps = true;

    public function otorisasiMengetahui()
    {
        return $this->hasMany(ArsipOtorisasiMengetahui::class, 'arsip_personalisasi_id');
    }

    public function otorisasiPersetujuan()
    {
        return $this->hasMany(ArsipOtorisasiPersetujuan::class, 'arsip_personalisasi_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }
}
