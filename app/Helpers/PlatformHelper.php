<?php

namespace App\Helpers;

use App\Models\PlatformSetting;

class PlatformHelper
{
    /**
     * Get all platform settings
     */
    public static function all()
    {
        return PlatformSetting::setting();
    }

    /**
     * Get platform name
     */
    public static function name()
    {
        return PlatformSetting::get('platform_name', 'Moon Trade');
    }

    /**
     * Get platform slogan
     */
    public static function slogan()
    {
        return PlatformSetting::get('platform_slogan', 'Professional Cryptocurrency Trading Platform');
    }

    /**
     * Get contact email
     */
    public static function contactEmail()
    {
        return PlatformSetting::get('contact_email', 'support@moontrade.com');
    }

    /**
     * Get contact phone
     */
    public static function contactPhone()
    {
        return PlatformSetting::get('contact_phone');
    }

    /**
     * Get support email
     */
    public static function supportEmail()
    {
        return PlatformSetting::get('support_email', 'support@moontrade.com');
    }

    /**
     * Get full address
     */
    public static function fullAddress()
    {
        return PlatformSetting::setting()?->full_address ?? '';
    }

    /**
     * Get social links
     */
    public static function socialLinks()
    {
        return PlatformSetting::setting()?->social_links ?? [];
    }

    /**
     * Get company mission
     */
    public static function mission()
    {
        return PlatformSetting::get('company_mission', '');
    }

    /**
     * Get company vision
     */
    public static function vision()
    {
        return PlatformSetting::get('company_vision', '');
    }

    /**
     * Get deposit limits
     */
    public static function depositLimits()
    {
        $settings = PlatformSetting::setting();
        return [
            'min' => $settings?->min_deposit ?? 10,
            'max' => $settings?->max_deposit ?? 100000,
        ];
    }

    /**
     * Get withdrawal limits
     */
    public static function withdrawalLimits()
    {
        $settings = PlatformSetting::setting();
        return [
            'min' => $settings?->min_withdrawal ?? 5,
            'max' => $settings?->max_withdrawal ?? 100000,
        ];
    }

    /**
     * Check if platform is in maintenance mode
     */
    public static function isMaintenanceMode()
    {
        return PlatformSetting::get('maintenance_mode', false);
    }

    /**
     * Get KYC level required
     */
    public static function kycLevelRequired()
    {
        return PlatformSetting::get('kyc_level_required', 1);
    }

    /**
     * Get default currency
     */
    public static function defaultCurrency()
    {
        return PlatformSetting::get('default_currency', 'USD');
    }

    /**
     * Get timezone
     */
    public static function timezone()
    {
        return PlatformSetting::get('timezone', 'UTC');
    }
}
