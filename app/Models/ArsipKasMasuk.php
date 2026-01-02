<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipKasMasuk extends Model
{
    use HasFactory;

    protected $table = 'arsip_kas_masuk';
    public $timestamps = true;

    protected $fillable = [
        'nama_transaksi',
        'tanggal_transaksi',
        'nomor_dokumen',
        'sumber',
        'nominal',
        'penerima',
        'menyetujui',
        'mengetahui',
        'kategori_pembukuan',
        'dokumen_pendukung',
        'link_gdrive',
        'catatan',
        'users_id',
    ];

    protected $casts = [
        'dokumen_pendukung' => 'array',
        'tanggal_transaksi' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
