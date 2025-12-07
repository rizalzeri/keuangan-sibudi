<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Modal;
use App\Models\Pinjaman;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @method \Illuminate\Database\Eloquent\Collection pinjaman() Get all the pinjaman for the user.
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];


    public function modal()
    {
        return $this->hasMany(Modal::class);
    }

    public function hutang()
    {
        return $this->hasMany(Hutang::class);
    }

    public function piutang()
    {
        return $this->hasMany(Piutang::class);
    }

    public function buk()
    {
        return $this->hasMany(Buk::class);
    }


    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class);
    }

    public function persediaan()
    {
        return $this->hasMany(Persediaan::class);
    }

    public function bdmuk()
    {
        return $this->hasMany(Bdmuk::class);
    }

    public function investasi()
    {
        return $this->hasMany(Investasi::class);
    }

    public function bangunan()
    {
        return $this->hasMany(Bangunan::class);
    }

    public function aktiva()
    {
        return $this->hasMany(Aktivalain::class);
    }

    public function profil()
    {
        return $this->hasOne(Profil::class);
    }

    public function role_user()
    {
        return $this->belongsTo(UserRoles::class, 'user_roles_id');
    }

}
