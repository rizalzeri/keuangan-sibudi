<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    protected $table = 'user_roles';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class, 'user_roles_id');
    }
}
