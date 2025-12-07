<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investasi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeUser($query)
    {
        // dd(session('selected_year', date('Y')));
        return $query->where('user_id', auth()->user()->id)->whereYear('tgl_beli', '<=', session('selected_year', date('Y')));
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
