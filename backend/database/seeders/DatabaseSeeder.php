<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Patient
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'patient',
            'password' => bcrypt('password123'),
        ]);

        // Doctor
        User::factory()->create([
            'name' => 'Test Doctor',
            'email' => 'testdoctor@example.com',
            'role' => 'doctor',
            'password' => bcrypt('password123'),
        ]);
    }
}
