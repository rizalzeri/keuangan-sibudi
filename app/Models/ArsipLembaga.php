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
        'link_gdrive',
        'users_id'   // tambahkan di fillable
    ];

    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * Relasi ke user (owner)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
