<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call([
            RolePermissionSeeder::class, // Seed roles and permissions first
            StatusSeeder::class, // Seed statuses before users so they are available during user creation
            UserSeeder::class, // Seed users with roles
        ]);
    }
}
