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
        'transport',
        'link_gdrive',
        'dasar_perjalanan_tugas',
        'pejabat_pemberi_tugas',
        'jabatan_pejabat',
        'pegawai_personil',
        'maksud_perjalanan_tugas',
        'tujuan_1',
        'tujuan_2',
        'lama_perjalanan_hari',
        'dasar_pembebanan_anggaran',
        'pembiayaan',
        'keterangan',
        'tempat_dikeluarkan',
        'users_id',
    ];

    public $timestamps = true;

    protected $casts = [
        'tanggal_perjalanan_dinas' => 'date',
        'pegawai_personil' => 'array',
        'transport' => 'array',
        'pembiayaan' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }
}
