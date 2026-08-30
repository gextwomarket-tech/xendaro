<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * Cree/met a jour les comptes de demonstration client (admin Filament + client wallet).
 * Le mot de passe est fourni en argument (jamais code en dur ici) pour eviter
 * qu'un secret ne finisse dans l'historique Git. Idempotent - peut etre relance
 * sans effet de bord (met juste a jour le mot de passe si les comptes existent deja).
 */
class SeedDemoAccounts extends Command
{
    protected $signature = 'demo:seed-accounts {password : Mot de passe a appliquer aux comptes de demo}';

    protected $description = 'Cree ou met a jour les comptes de demonstration (admin + client) avec le mot de passe fourni';

    public function handle(): int
    {
        $password = Hash::make((string) $this->argument('password'));

        $admin = User::where('email', 'admin@xendaro-trade.it.com')->first();
        if ($admin) {
            $admin->forceFill(['password' => $password])->save();
            $this->info('Compte admin mis a jour: admin@xendaro-trade.it.com');
        } else {
            $this->warn('Aucun compte admin@xendaro-trade.it.com trouve - non modifie.');
        }

        $client = User::firstOrCreate(
            ['email' => 'demo.client@xendaro-trade.it.com'],
            [
                'name' => 'Client Demo',
                'password' => $password,
                'email_verified_at' => now(),
            ]
        );

        if (! $client->wasRecentlyCreated) {
            $client->forceFill(['password' => $password, 'email_verified_at' => $client->email_verified_at ?? now()])->save();
        }

        $this->info('Compte client demo pret: demo.client@xendaro-trade.it.com');

        return self::SUCCESS;
    }
}
