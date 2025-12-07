<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persediaan extends Model
{
    protected $table = 'persediaans';
    use HasFactory;


    protected $guarded = ['id'];

    public function scopeUser($query)
    {
        return $query->where('user_id', auth()->user()->id)->whereYear('created_at', '<=', session('selected_year', date('Y')));
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
