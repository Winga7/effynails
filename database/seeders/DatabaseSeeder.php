<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Création de l'admin
        User::create([
            'name' => 'Admin',
            'email' => 'andywinga@hotmail.be',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Création du propriétaire
        User::create([
            'name' => 'EFFYNAILS',
            'email' => 'contact@effynails.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        // Création d'un compte jury
        User::create([
            'name' => 'Jury',
            'email' => 'jury@ifosupwavre.be',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Seed des tarifs
        $this->call(TarifSeeder::class);
    }
}
