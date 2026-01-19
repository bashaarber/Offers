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
        // Seed admin user first (always needed)
        $this->call(UserSeeder::class);
        
        // Import all data from JSON file
        // This will clear existing data and import from JSON
        $this->call(JsonImportSeeder::class);
    }
}
