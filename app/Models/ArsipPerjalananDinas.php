<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipPerjalananDinas extends Model
{
    protected $table = 'arsip_perjalanan_dinas';

    protected $fillable = [
        'nomor_dokumen',
        'tanggal_perjalanan_dinas',
        'kegiatan',
        'tempat',
        'tempat_2',
        'transport', // disimpan sebagai JSON (array)
        'link_gdrive',

        // new fields
        'dasar_perjalanan_tugas',
        'pejabat_pemberi_tugas',
        'jabatan_pejabat',
        'pegawai_personil', // JSON
        'maksud_perjalanan_tugas',
        'tujuan_1',
        'tujuan_2',
        'lama_perjalanan_hari',
        'dasar_pembebanan_anggaran',
        'pembiayaan',
        'keterangan',
        'tempat_dikeluarkan',
    ];

    public $timestamps = true;

    protected $casts = [
        'tanggal_perjalanan_dinas' => 'date',
        'pegawai_personil' => 'array',
        'transport' => 'array',
        // pembiayaan sebagai float agar mudah formatting; atur sesuai kebutuhan
        'pembiayaan' => 'float',
    ];
}
