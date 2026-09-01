<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password', 'nis', 'kelas', 'alamat', 'tanggal_lahir', 'nip', 'phone', 'jurusan', 'jabatan', 'sipintu_synced_at', 'data_source', 'classroom_id', 'foto_profil'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable, HasRoles;

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->password)) {
                if (!empty($user->nip)) {
                    $user->password = Hash::make('guru123');
                } elseif (!empty($user->nis)) {
                    $user->password = Hash::make('siswa123');
                }
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }

    /**
     * Get the user's profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->foto_profil) {
            return Storage::url($this->foto_profil);
        }

        return '';
    }

    /**
     * Check if user has a profile photo
     */
    public function hasProfilePhoto(): bool
    {
        return !empty($this->foto_profil) && Storage::exists($this->foto_profil);
    }

    /**
     * Ekstrak kode tanggal lahir (YYYYMMDD / 8 digit pertama NIP) dari NIP atau tanggal lahir guru.
     */
    public static function extractBirthDateCode(?string $nip, ?string $tanggalLahir = null): string
    {
        $nip = trim((string)$nip);

        // 1. Cek dari 8 digit pertama NIP jika diawali tahun 19xx atau 20xx (format standar NIP: YYYYMMDD...)
        if (preg_match('/^(19\d{2}|20\d{2})(\d{2})(\d{2})/', $nip, $matches)) {
            $year = $matches[1];
            $month = $matches[2];
            $day = $matches[3];

            // Format YYYYMMDD (8 digit pertama NIP persis, misal: 19840514)
            return $year . $month . $day;
        }

        // 2. Cek jika ada tanggal_lahir terisi (format Y-m-d atau d-m-Y atau format tanggal lainnya)
        if (!empty($tanggalLahir)) {
            try {
                $tgl = \Illuminate\Support\Carbon::parse($tanggalLahir);
                return $tgl->format('Ymd'); // YYYYMMDD
            } catch (\Exception $e) {}
        }

        // 3. Cek jika NIK 16 digit (format: 6 digit wilayah, 6 digit DDMMYY, 4 digit urut)
        if (preg_match('/^\d{6}(\d{2})(\d{2})(\d{2})\d{4}$/', $nip, $matches)) {
            $dd = (int)$matches[1];
            if ($dd > 40) { // NIK perempuan tanggal lahir +40
                $dd -= 40;
            }
            $day = str_pad($dd, 2, '0', STR_PAD_LEFT);
            $month = $matches[2];
            $yy = (int)$matches[3];
            $year = ($yy > 30 ? '19' : '20') . str_pad($yy, 2, '0', STR_PAD_LEFT);

            return $year . $month . $day;
        }

        // 4. Fallback jika NIP minimal 8 digit angka (ambil 8 digit pertama)
        if (preg_match('/^\d{8}/', $nip)) {
            return substr($nip, 0, 8);
        }

        // 5. Fallback terakhir jika NIP pendek
        return !empty($nip) ? $nip : 'guru' . uniqid();
    }

    /**
     * Generate email login unik untuk Guru berdasarkan Tanggal Lahir (YYYYMMDD@sipbar.sch.id).
     * Jika terjadi duplikat tanggal lahir dengan guru lain, otomatis ditambahkan suffix (-2, -3, dst).
     */
    public static function generateTeacherEmail(?string $nip, ?string $tanggalLahir = null, ?int $excludeUserId = null): string
    {
        $baseCode = static::extractBirthDateCode($nip, $tanggalLahir);
        $email = $baseCode . '@sipbar.sch.id';

        // Cek apakah email sudah dipakai oleh user lain
        $query = static::where('email', $email);
        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        if ($query->exists()) {
            $suffix = 2;
            while (true) {
                $candidate = "{$baseCode}-{$suffix}@sipbar.sch.id";
                $chk = static::where('email', $candidate);
                if ($excludeUserId) {
                    $chk->where('id', '!=', $excludeUserId);
                }
                if (!$chk->exists()) {
                    return $candidate;
                }
                $suffix++;
            }
        }

        return $email;
    }
}
