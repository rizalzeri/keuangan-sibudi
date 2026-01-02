<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Modal;
use App\Models\Pinjaman;
use App\Models\ArsipLembaga;
use App\Models\ArsipBankMasuk;
use App\Models\ArsipBankKeluar;
use App\Models\ArsipKasMasuk;
use App\Models\ArsipKasKeluar;
use App\Models\ArsipKlasifikasiTransaksi;
use App\Models\ArsipOtorisasiMengetahui;
use App\Models\ArsipOtorisasiPersetujuan;
use App\Models\ArsipPersonalisasi;
use App\Models\ArsipSuratKeluar;
use App\Models\ArsipSuratMasuk;
use App\Models\ArsipSop;
use App\Models\ArsipPerjanjianKerja;
use App\Models\ArsipPerjalananDinas;
use App\Models\ArsipBeritaAcara;
use App\Models\ArsipNotulenRapat;
use App\Models\ArsipDokumentasiBerkasDokumen;
use App\Models\ArsipDokumentasiFoto;
use App\Models\ArsipDokumentasiVideo;
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
    protected $appends = ['bumdes_info_complete'];
     public function getBumdesInfoCompleteAttribute()
    {
        return !empty($this->nama_bumdes) && !empty($this->alamat_bumdes) && !empty($this->nomor_hukum_bumdes);
    }

    public function isBumdesInfoComplete()
    {
        return $this->bumdes_info_complete;
    }
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

        // di dalam class User
    public function arsip_lembaga()
    {
        return $this->hasMany(ArsipLembaga::class, 'users_id');
    }

        // Relasi ke arsip bank/kas
    public function arsipBankMasuk()
    {
        return $this->hasMany(ArsipBankMasuk::class, 'users_id');
    }

    public function arsipBankKeluar()
    {
        return $this->hasMany(ArsipBankKeluar::class, 'users_id');
    }

    public function arsipKasMasuk()
    {
        return $this->hasMany(ArsipKasMasuk::class, 'users_id');
    }

    public function arsipKasKeluar()
    {
        return $this->hasMany(ArsipKasKeluar::class, 'users_id');
    }

    // Relasi ke klasifikasi & otorisasi & personalisasi
    public function arsipKlasifikasiTransaksi()
    {
        return $this->hasMany(ArsipKlasifikasiTransaksi::class, 'users_id');
    }

    public function arsipOtorisasiMengetahui()
    {
        return $this->hasMany(ArsipOtorisasiMengetahui::class, 'users_id');
    }

    public function arsipOtorisasiPersetujuan()
    {
        return $this->hasMany(ArsipOtorisasiPersetujuan::class, 'users_id');
    }

    public function arsipPersonalisasi()
    {
        return $this->hasMany(ArsipPersonalisasi::class, 'users_id');
    }

    public function arsipSuratKeluar()
    {
        return $this->hasMany(ArsipSuratKeluar::class, 'users_id');
    }

    public function arsipSuratMasuk()
    {
        return $this->hasMany(ArsipSuratMasuk::class, 'users_id');
    }

    public function arsipSop()
    {
        return $this->hasMany(ArsipSop::class, 'users_id');
    }

        public function arsipBeritaAcara()
    {
        return $this->hasMany(ArsipBeritaAcara::class, 'users_id');
    }

    public function arsipPerjanjianKerja()
    {
        return $this->hasMany(ArsipPerjanjianKerja::class, 'users_id');
    }

    public function arsipPerjalananDinas()
    {
        return $this->hasMany(ArsipPerjalananDinas::class, 'users_id');
    }

    public function arsipNotulenRapat()
    {
        return $this->hasMany(ArsipNotulenRapat::class, 'users_id');
    }
        public function arsipDokumentasiBerkasDokumen()
    {
        return $this->hasMany(ArsipDokumentasiBerkasDokumen::class, 'users_id');
    }

    public function arsipDokumentasiFoto()
    {
        return $this->hasMany(ArsipDokumentasiFoto::class, 'users_id');
    }

    public function arsipDokumentasiVideo()
    {
        return $this->hasMany(ArsipDokumentasiVideo::class, 'users_id');
    }


}
