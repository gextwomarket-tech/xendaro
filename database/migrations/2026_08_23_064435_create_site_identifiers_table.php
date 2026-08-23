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
        Schema::create('site_identifiers', function (Blueprint $table) {
            $table->id();
            $table->string('nom_plateforme')->default('Xendaro Fox');
            $table->string('slogan')->nullable();
            $table->string('langue_par_defaut', 5)->default('fr');
            $table->string('couleur_principale', 7)->default('#F5A623');
            $table->string('couleur_secondaire', 7)->default('#5B8CFF');
            $table->longText('about_us')->nullable();
            $table->string('path_light_logo')->nullable();
            $table->string('path_dark_logo')->nullable();
            $table->string('path_favicon_png')->nullable();
            $table->string('phone_contact_1')->nullable();
            $table->string('phone_contact_2')->nullable();
            $table->string('email_pro_1')->nullable();
            $table->string('email_pro_2')->nullable();
            $table->string('location_adresse')->nullable();
            $table->longText('cvg')->nullable();
            $table->longText('policies')->nullable();
            $table->longText('cookies')->nullable();
            $table->longText('nos_services')->nullable();
            $table->longText('contact')->nullable();
            $table->json('reseaux_sociaux')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_identifiers');
    }
};
