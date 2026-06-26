<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlatformSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'platform_settings';
    protected $guarded = ['id'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'min_deposit' => 'decimal:2',
        'max_deposit' => 'decimal:2',
        'min_withdrawal' => 'decimal:2',
        'max_withdrawal' => 'decimal:2',
        'maintenance_mode' => 'boolean',
        'bot_profit_rate_per_hour' => 'decimal:2',
        'kyc_level_required' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ═══════════════════════════════════════════════════════════════
    // STATIC METHODS FOR EASY ACCESS
    // ═══════════════════════════════════════════════════════════════

    /**
     * Get first (or only) platform settings record
     */
    public static function setting(): self
    {
        return self::first() ?? self::create([
            'platform_name' => 'Purprime Fox',
            'platform_slogan' => 'Professional Cryptocurrency Trading Platform',
        ]);
    }

    /**
     * Get a specific setting value
     */
    public static function get(string $key, $default = null)
    {
        return self::setting()?->{$key} ?? $default;
    }

    /**
     * Set a specific setting value
     */
    public static function set(string $key, $value): bool
    {
        return (bool) self::setting()->update([$key => $value]);
    }

    // ═══════════════════════════════════════════════════════════════
    // GETTERS - IDENTITY
    // ═══════════════════════════════════════════════════════════════

    public function getPlatformNameAttribute($value)
    {
        return $value ?: 'Purprime Fox';
    }

    public function getPlatformSloganAttribute($value)
    {
        return $value ?: 'Professional Cryptocurrency Trading Platform';
    }

    // ═══════════════════════════════════════════════════════════════
    // GETTERS - CONTACT
    // ═══════════════════════════════════════════════════════════════

    public function getFullAddressAttribute(): string
    {
        $parts = [];
        if ($this->address_line_1) $parts[] = $this->address_line_1;
        if ($this->address_line_2) $parts[] = $this->address_line_2;
        if ($this->city) $parts[] = $this->city;
        if ($this->state_province) $parts[] = $this->state_province;
        if ($this->postal_code) $parts[] = $this->postal_code;
        if ($this->country) $parts[] = $this->country;
        
        return implode(', ', $parts);
    }

    public function getContactNumbersAttribute(): array
    {
        return [
            'phone' => $this->contact_phone,
            'whatsapp' => $this->contact_whatsapp,
            'telegram' => $this->contact_telegram,
        ];
    }

    public function getSocialLinksAttribute(): array
    {
        return [
            'facebook' => $this->social_facebook,
            'twitter' => $this->social_twitter,
            'linkedin' => $this->social_linkedin,
            'instagram' => $this->social_instagram,
            'youtube' => $this->social_youtube,
        ];
    }

    // ═══════════════════════════════════════════════════════════════
    // SCOPES
    // ═══════════════════════════════════════════════════════════════

    /**
     * Check if platform is in maintenance mode
     */
    public function scopeIsMaintenanceMode($query)
    {
        return static::get('maintenance_mode', false);
    }

    /**
     * Get deposit limits
     */
    public function getDepositLimitsAttribute(): array
    {
        return [
            'min' => $this->min_deposit,
            'max' => $this->max_deposit,
        ];
    }

    /**
     * Get withdrawal limits
     */
    public function getWithdrawalLimitsAttribute(): array
    {
        return [
            'min' => $this->min_withdrawal,
            'max' => $this->max_withdrawal,
        ];
    }
}
