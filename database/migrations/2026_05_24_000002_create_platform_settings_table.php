<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            
            // Platform Identity
            $table->string('platform_name', 255)->default('Moon Trade');
            $table->string('platform_slogan', 255)->nullable();
            $table->string('platform_logo')->nullable(); // URL to logo
            
            // Contact Information
            $table->string('contact_email', 255)->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->string('contact_whatsapp', 50)->nullable();
            $table->string('contact_telegram', 100)->nullable();
            
            // Location Information
            $table->string('address_line_1', 255)->nullable();
            $table->string('address_line_2', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state_province', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Business Information
            $table->string('business_registration_number', 100)->nullable(); // SIRET, SIREN, VAT, etc.
            $table->string('business_license', 255)->nullable();
            $table->text('business_description')->nullable();
            
            // Social Media
            $table->string('social_facebook', 255)->nullable();
            $table->string('social_twitter', 255)->nullable();
            $table->string('social_linkedin', 255)->nullable();
            $table->string('social_instagram', 255)->nullable();
            $table->string('social_youtube', 255)->nullable();
            
            // Company Details
            $table->string('company_name', 255)->nullable();
            $table->string('company_ceo', 255)->nullable();
            $table->string('company_cfo', 255)->nullable();
            $table->text('company_mission')->nullable();
            $table->text('company_vision')->nullable();
            
            // Trading Configuration
            $table->decimal('min_deposit', 10, 2)->default(10);
            $table->decimal('max_deposit', 10, 2)->default(100000);
            $table->decimal('min_withdrawal', 10, 2)->default(5);
            $table->decimal('max_withdrawal', 10, 2)->default(100000);
            $table->integer('kyc_level_required')->default(1); // 1, 2, or 3
            $table->boolean('maintenance_mode')->default(false);
            
            // Terms & Conditions
            $table->text('terms_and_conditions')->nullable();
            $table->text('privacy_policy')->nullable();
            $table->text('cookie_policy')->nullable();
            
            // System Configuration
            $table->string('support_email', 255)->nullable();
            $table->string('no_reply_email', 255)->default('noreply@moontrade.com');
            $table->string('default_currency', 3)->default('USD');
            $table->string('timezone', 50)->default('UTC');
            
            // Metadata
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
