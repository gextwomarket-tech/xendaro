<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Cree le seul compte pouvant acceder au panel Filament /admin (voir migration
 * add_is_admin_to_users_table + App\Models\User::canAccessPanel()). Idempotent
 * (firstOrCreate) : ne recree jamais le compte ni n'ecrase son mot de passe si
 * relance (ex. redeploiement). A executer manuellement, separement du
 * DatabaseSeeder normal, pour ne jamais regenerer/exposer accidentellement un
 * mot de passe admin lors d'un seed applicatif classique :
 *
 *   php artisan db:seed --class=AdminUserSeeder --force
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@xendaro-trade.it.com'],
            [
                'name' => 'Administrateur Xendaro Fox',
                // Mot de passe initial genere aleatoirement, communique separement (voir
                // docummentations.md) - a changer via le formulaire de securite (page id 32)
                // des la premiere connexion au panel /admin.
                'password' => Hash::make('.t65%7#fVuAe_zL3de-K'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
