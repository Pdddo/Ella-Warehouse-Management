<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
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
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('pass123'),
        ]);

        User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'role' => 'manager',
            'password' => Hash::make('pass123'),
        ]);

        User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'role' => 'staff',
            'password' => Hash::make('pass123'),
        ]);

        User::factory()->create([
            'name' => 'Supplier User',
            'email' => 'supplier@example.com',
            'role' => 'supplier',
            'password' => Hash::make('pass123'),
            'approved_at' => now(),
        ]);

        // Category::factory(5)->create();

        // Product::factory(50)->create();

        $this->call([
            WeaponSeeder::class,
        ]);
    }
}
