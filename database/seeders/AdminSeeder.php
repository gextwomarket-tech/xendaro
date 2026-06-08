<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer l'utilisateur admin
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'name' => 'Admin User',
                'password' => bcrypt('Admin2026@'),
                'email_verified_at' => now(),
                'is_admin' => true,
                'kyc_status' => 'verified',
                'kyc_level' => 2,
            ]
        );

        $this->command->info('✅ Admin user created: admin@admin.com / Admin2026@');
    }
}
