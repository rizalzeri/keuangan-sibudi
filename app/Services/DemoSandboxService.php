<?php

namespace App\Services;

use App\Models\User;
use App\Models\Profil;
use App\Models\Ekuit;
use App\Models\Rekonsiliasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoSandboxService
{
    /**
     * List of tables using `user_id`.
     */
    protected static array $userIdTables = [
        'akps',
        'aktivalains',
        'alokasis',
        'bangunans',
        'bdmuks',
        'buks',
        'dithns',
        'ekuits',
        'hutangs',
        'investasis',
        'kebutuhans',
        'kerjasamas',
        'lpjs',
        'modals',
        'orders',
        'penjualans',
        'persediaans',
        'pinjamans',
        'piutangs',
        'profils',
        'programs',
        'prokers',
        'rasios',
        'rekonsiliasis',
        'targets',
        'tutups',
        'units',
    ];

    /**
     * List of tables using `users_id`.
     */
    protected static array $usersIdTables = [
        'arsip_bank_keluar',
        'arsip_bank_masuk',
        'arsip_berita_acara',
        'arsip_dokumentasi_berkas_dokumen',
        'arsip_dokumentasi_foto',
        'arsip_dokumentasi_video',
        'arsip_kas_keluar',
        'arsip_kas_masuk',
        'arsip_klasifikasi_transaksi',
        'arsip_lembaga',
        'arsip_notulen_rapat',
        'arsip_otorisasi_mengetahui',
        'arsip_otorisasi_persetujuan',
        'arsip_perjalanan_dinas',
        'arsip_perjanjian_kerja',
        'arsip_personalisasi',
        'arsip_sop',
        'arsip_surat_keluar',
        'arsip_surat_masuk',
    ];

    /**
     * Authenticate or create a demo user using the portal token.
     */
    public function authenticateByToken(string $token): ?User
    {
        // 0. Pastikan migrasi kolom is_demo telah dijalankan di database server
        if (!Schema::hasColumn('users', 'is_demo')) {
            throw new \Exception("Kolom 'is_demo' belum ada di tabel users server. Harap jalankan migrasi di terminal server: php artisan migrate --path=database/migrations/2026_09_14_000002_add_demo_fields_to_users_table.php");
        }

        // 1. Cari user di database portal (dengan auto-discovery cPanel)
        $portalUser = $this->findPortalUser($token);

        if (!$portalUser) {
            return null;
        }

        // 2. Check if a demo user already exists for this token or portal_user_id
        $user = User::where('is_demo', true)
            ->where(function ($query) use ($token, $portalUser) {
                $query->where('demo_token', $token)
                      ->orWhere('portal_user_id', $portalUser->id);
            })
            ->first();

        if ($user) {
            // Check if previous session expired
            if ($user->demo_expires_at && now()->greaterThan($user->demo_expires_at)) {
                // Sesi sebelumnya sudah habis (> 1 jam), bersihkan data lama agar fresh
                $this->purgeUserData($user->id);
            }

            // Perbarui token, masa aktif 1 jam dari sekarang, dan aktifkan status langganan
            $user->update([
                'name' => '[Praktikum] ' . $portalUser->name,
                'demo_token' => $token,
                'portal_user_id' => $portalUser->id,
                'demo_expires_at' => now()->addHour(),
                'status' => 1,
                'tgl_langganan' => now()->addDays(30),
                'role' => 'bumdes',
                'user_roles_id' => $user->user_roles_id ?: 3, // Default 3 (SPJ) tapi bisa akses semua
            ]);
        } else {
            // 3. Buat user demo baru terisolasi untuk peserta ini
            $email = 'demo_' . $portalUser->id . '_' . Str::random(5) . '@academy.portal';

            $user = User::create([
                'name' => '[Praktikum] ' . $portalUser->name,
                'email' => $email,
                'password' => bcrypt(Str::random(16)),
                'role' => 'bumdes',
                'status' => 1,
                'tgl_langganan' => now()->addDays(30),
                'user_roles_id' => 3, // SPJ default, tapi is_demo bebas akses semua
                'is_demo' => true,
                'demo_token' => $token,
                'portal_user_id' => $portalUser->id,
                'demo_expires_at' => now()->addHour(),
                'nama_bumdes' => 'BUMDes Praktikum ' . $portalUser->name,
                'alamat_bumdes' => 'Desa Praktikum Academy',
                'nomor_hukum_bumdes' => 'AHU-0000.PRAKTIKUM.2026',
            ]);
        }

        // Pastikan inisialisasi awal ada (ekuit, profil, rekonsiliasi)
        $this->initializeBasicData($user);

        // Login pengguna ke session
        Auth::login($user);

        return $user;
    }

    /**
     * Inisialisasi data profil dan kas awal untuk praktikum.
     */
    public function initializeBasicData(User $user): void
    {
        $userId = $user->id;

        if (!Profil::where('user_id', $userId)->exists()) {
            Profil::create([
                'user_id' => $userId,
                'nama_bumdes' => $user->nama_bumdes ?: 'BUMDes Praktikum',
                'alamat_bumdes' => $user->alamat_bumdes ?: 'Desa Praktikum Academy',
                'nomor_hukum_bumdes' => $user->nomor_hukum_bumdes ?: 'AHU-0000.PRAKTIKUM',
            ]);
        }

        if (!Ekuit::where('user_id', $userId)->exists()) {
            Ekuit::create(['user_id' => $userId]);
        }

        if (!Rekonsiliasi::where('user_id', $userId)->exists()) {
            Rekonsiliasi::insert([
                ['posisi' => 'Kas di tangan', 'user_id' => $userId, 'jumlah' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['posisi' => 'Bank Jateng', 'user_id' => $userId, 'jumlah' => 0, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    /**
     * Bersihkan seluruh rekaman transaksi yang dibuat oleh user demo tertentu.
     */
    public function purgeUserData(int $userId): void
    {
        // 1. Hapus dari tabel user_id
        foreach (self::$userIdTables as $table) {
            try {
                if (Schema::hasTable($table) && Schema::hasColumn($table, 'user_id')) {
                    DB::table($table)->where('user_id', $userId)->delete();
                }
            } catch (\Throwable $e) {
                Log::warning("Gagal membersihkan tabel {$table} untuk user {$userId}: " . $e->getMessage());
            }
        }

        // 2. Hapus dari tabel users_id
        foreach (self::$usersIdTables as $table) {
            try {
                if (Schema::hasTable($table) && Schema::hasColumn($table, 'users_id')) {
                    DB::table($table)->where('users_id', $userId)->delete();
                }
            } catch (\Throwable $e) {
                Log::warning("Gagal membersihkan tabel {$table} untuk user {$userId}: " . $e->getMessage());
            }
        }
    }

    /**
     * Reset data praktikum dan mulai ulang sesi 1 jam.
     */
    public function resetPracticeData(User $user): void
    {
        $this->purgeUserData($user->id);
        $user->update([
            'demo_expires_at' => now()->addHour(),
        ]);
        $this->initializeBasicData($user);
    }

    /**
     * Cari dan bersihkan semua sesi demo yang sudah kedaluwarsa (> 1 jam).
     */
    public function cleanupExpiredSessions(): int
    {
        $expiredUsers = User::where('is_demo', true)
            ->where('demo_expires_at', '<', now())
            ->get();

        $count = 0;
        foreach ($expiredUsers as $user) {
            $this->purgeUserData($user->id);
            // Hapus user demo agar database tetap rapi dan tidak menumpuk
            $user->delete();
            $count++;
        }

        return $count;
    }

    /**
     * Mencari user dari database portal, mendukung koneksi sekunder dan auto-discovery prefix cPanel.
     */
    public function findPortalUser(string $token)
    {
        // 1. Coba koneksi sekunder 'portal' yang didefinisikan di config/database.php
        try {
            $portalUser = DB::connection('portal')
                ->table('users')
                ->where('bumdespro2_token', $token)
                ->first();

            if ($portalUser) {
                return $portalUser;
            }
        } catch (\Throwable $e) {
            Log::info('Koneksi portal eksplisit gagal, mencoba auto-discovery database: ' . $e->getMessage());
        }

        // 2. Auto-discovery schema di server MySQL yang sama (misal u110981049_portal_...)
        try {
            $databases = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME LIKE '%portal%'");
            foreach ($databases as $db) {
                $schemaName = $db->SCHEMA_NAME;
                try {
                    $portalUser = DB::table("{$schemaName}.users")
                        ->where('bumdespro2_token', $token)
                        ->first();
                    if ($portalUser) {
                        return $portalUser;
                    }
                } catch (\Throwable $e2) {
                    continue;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal auto-discovery schema portal: ' . $e->getMessage());
        }

        return null;
    }
}
