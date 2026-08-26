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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar_path',
        'referral_code',
        'parrain_id',
        'two_factor_enabled',
        // otp_code/otp_expires_at pilotes par le mecanisme OTP email des pages
        // id 25/29/30 (register/verify-email/two-factor-auth), voir app/Livewire/Auth/*.
        'otp_code',
        'otp_expires_at',
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
            'otp_expires_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Sans cette restriction, Filament v3 autorise par defaut tout utilisateur authentifie a
     * acceder a n'importe quel panel - n'importe quel client inscrit pouvait donc charger /admin
     * avec son propre compte. Voir migration add_is_admin_to_users_table.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    public function wallet(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function tradeHistories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TradeHistory::class);
    }

    public function walletTransactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function parrain(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'parrain_id');
    }

    public function filleuls(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'parrain_id');
    }

    public function tickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function kycDocuments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(KycDocument::class);
    }

    public function affiliateCommissionsGagnees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AffiliateCommission::class, 'parrain_id');
    }

    protected static function booted(): void
    {
        static::created(function (self $user) {
            $user->referral_code ??= strtoupper(\Illuminate\Support\Str::random(8));
            $user->saveQuietly();

            $user->wallet()->create([
                // Bonus de bienvenue MVP: permet de tester le mode "reel" (dashboard, Trade,
                // retrait...) sans devoir d'abord passer par le cycle complet depot + validation
                // admin. Un vrai depot via WalletTransaction reste le mecanisme normal ensuite.
                'solde_reel' => 100,
                'solde_demo' => 10000,
                'devise' => 'USD',
            ]);
        });
    }
}
