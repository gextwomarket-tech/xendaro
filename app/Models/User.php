<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin === true;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'is_admin',
        'phone',
        'date_of_birth',
        'country',
        'city',
        'address',
        'preferred_currency',
        'avatar',
        'kyc_status',
        'kyc_level',
        'two_factor_secret',
        'two_factor_enabled',
        'two_factor_recovery_codes',
        'referral_code',
        'referred_by',
        'status',
        'email_verified_at',
        'email_verification_token',
        'email_verification_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'date_of_birth' => 'date',
            'two_factor_enabled' => 'boolean',
            'two_factor_recovery_codes' => 'array',
            'kyc_level' => 'integer',
            'is_admin' => 'boolean',
            'email_verification_expires_at' => 'datetime',
        ];
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function kycDocuments()
    {
        return $this->hasMany(KycDocument::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referralCommissions()
    {
        return $this->hasMany(ReferralCommission::class, 'referrer_id');
    }

    public function notificationPreferences()
    {
        return $this->hasMany(NotificationPreference::class);
    }

    public static function generateReferralCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }
}
