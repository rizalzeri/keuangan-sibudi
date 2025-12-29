<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutup extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeUser($query)
    {
        $userId = auth()->id(); // lebih aman: mengembalikan null jika guest
        if (! $userId) {
            // Tidak ada user -> kembalikan query tanpa filter (no-op)
            return $query;
        }

        return $query->where('user_id', $userId);
    }


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
