<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Ihza Mahendra',
            'email' => 'ihza@rtech.com',
            'password' => bcrypt('ihza123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Admin - Operations Manager
        User::create([
            'name' => 'Sarah Chen',
            'email' => 'sarah.chen@rtech.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Editor - Content Manager
        User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi.lestari@rtech.com',
            'password' => bcrypt('editor123'),
            'role' => 'editor',
            'email_verified_at' => now(),
        ]);

        // Editor - Product Specialist
        User::create([
            'name' => 'Michael Wijaya',
            'email' => 'michael.wijaya@rtech.com',
            'password' => bcrypt('editor123'),
            'role' => 'editor',
            'email_verified_at' => now(),
        ]);

        // Test Admin Account (for development)
        User::create([
            'name' => 'Test Admin',
            'email' => 'admin@rtech.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Test Editor Account (for development)
        User::create([
            'name' => 'Test Editor',
            'email' => 'editor@rtech.test',
            'password' => bcrypt('password'),
            'role' => 'editor',
            'email_verified_at' => now(),
        ]);
    }
}
