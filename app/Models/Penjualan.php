<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function scopeUser($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }
    public function scopeTahun($query)
    {
        return $query->whereyear('created_at', session('selected_year', date('Y')));
    }
}
