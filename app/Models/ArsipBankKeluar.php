<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipBankKeluar extends Model
{
    use HasFactory;

    // nama tabel (sesuaikan jika berbeda)
    protected $table = 'arsip_bank_keluar';

    // bila kolom timestamp bernama created_date / updated_date
    public $timestamps = true;

    protected $fillable = [
        'nama_transaksi',
        'tanggal_transaksi',
        'nomor_dokumen',
        'tujuan',
        'nominal',
        'penerima',
        'menyetujui',
        'mengetahui',
        'kategori_pembukuan',
        'dokumen_pendukung',
        'link_gdrive',
        'catatan',
    ];

    // casting
    protected $casts = [
        'dokumen_pendukung' => 'array',
        'tanggal_transaksi' => 'date',
        'nominal' => 'decimal:2',
    ];
}
