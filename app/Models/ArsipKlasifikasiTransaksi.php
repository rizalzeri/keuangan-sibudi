<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipKlasifikasiTransaksi extends Model
{
    protected $table = 'arsip_klasifikasi_transaksi';

    protected $fillable = [
        'kategori',
        'nominal',
        'arsip_otorisasi_mengetahui_id',
        'arsip_otorisasi_persetujuan_id'
    ];

    public $timestamps = true;

    /* ================= RELATIONS ================= */

    public function mengetahui()
    {
        return $this->belongsTo(
            ArsipOtorisasiMengetahui::class,
            'arsip_otorisasi_mengetahui_id'
        );
    }

    public function persetujuan()
    {
        return $this->belongsTo(
            ArsipOtorisasiPersetujuan::class,
            'arsip_otorisasi_persetujuan_id'
        );
    }
}
