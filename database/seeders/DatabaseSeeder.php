<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Owner
        User::factory()->create([
            'name' => 'Owner User',
            'email' => 'owner@sembako.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        // Warehouse Admin
        User::factory()->create([
            'name' => 'Admin Gudang',
            'email' => 'admin@sembako.com',
            'password' => bcrypt('password'),
            'role' => 'warehouse_admin',
        ]);

        // Cashier
        User::factory()->create([
            'name' => 'Cashier User',
            'email' => 'cashier@sembako.com',
            'password' => bcrypt('password'),
            'role' => 'cashier',
        ]);
    }
}
