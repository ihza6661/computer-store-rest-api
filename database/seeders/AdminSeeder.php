<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@rtech.test',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create a sample editor
        User::create([
            'name' => 'Editor User',
            'email' => 'editor@rtech.test',
            'password' => bcrypt('password123'),
            'role' => 'editor',
            'email_verified_at' => now(),
        ]);
    }
}
