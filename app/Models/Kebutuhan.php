<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kebutuhan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeUser($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function scopeKategori($query, $kategori = null)
    {
        if (!empty($kategori)) {
            return $query->where('kategori', $kategori);
        }

        return $query;
    }

    public function scopeTahun($query)
    {
        return $query->whereYear('created_at', session('selected_year', date('Y')));
    }
}
