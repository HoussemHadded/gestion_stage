<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ne créer un admin que s'il n'en existe aucun (is_admin == 3)
        if (User::where('is_admin', 3)->doesntExist()) {
            User::create([
                'name'     => 'Super Admin',
                'email'    => 'admin@example.com',
                'password' => Hash::make('password'), // à changer en prod
                'is_admin' => 3,
            ]);
        }
    }
}