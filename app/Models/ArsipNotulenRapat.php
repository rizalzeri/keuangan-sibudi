<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipNotulenRapat extends Model
{
    protected $table = 'arsip_notulen_rapat';

    protected $fillable = [
        'tanggal_notulen_rapat',
        'waktu',
        'tempat',
        'agenda',
        'penyelenggara',
        'link_gdrive',
        'users_id',
    ];
    public $timestamps = true;

    protected $casts = [
        'tanggal_notulen_rapat' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }
}
