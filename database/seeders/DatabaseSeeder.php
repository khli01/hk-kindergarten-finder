<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@hk-kindergarten.com',
            'password' => Hash::make('password'),
            'email_verified' => true,
            'email_verified_at' => now(),
            'is_admin' => true,
            'preferred_language' => 'zh-TW',
        ]);

        // Seed districts
        $this->call([
            DistrictSeeder::class,
        ]);
    }
}
