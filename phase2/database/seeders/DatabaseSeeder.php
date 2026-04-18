<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Entry seeder that runs all project seeders.
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    // Seed demo customer, admin account, and product catalog.
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
        ]);

        // Admin account for /admin (password is "password" via factory default).
        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'RackRush',
            'email' => 'admin@rackrush.test',
            'is_admin' => true,
        ]);

        $this->call([
            ProductSeeder::class,
        ]);
    }
}
