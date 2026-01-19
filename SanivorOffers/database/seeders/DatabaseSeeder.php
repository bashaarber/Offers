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
        
        // Seed coefficient
        $this->call(CoefficientSeeder::class);
        
        // Try to import from JSON file (if available)
        // If JSON file not found, it will skip gracefully
        try {
            $this->call(JsonImportSeeder::class);
        } catch (\Exception $e) {
            // If JSON import fails, use default seeders
            $this->command->warn('JSON import failed, using default seeders: ' . $e->getMessage());
            $this->call(ClientSeeder::class);
            $this->call(MaterialPieceSeeder::class);
            $this->call(MaterialSeeder::class);
            $this->call(ElementSeeder::class);
            $this->call(GroupElementSeeder::class);
            $this->call(OrganigramSeeder::class);
            $this->call(PositionSeeder::class);
            $this->call(MaterialMaterialPieceRelationshipSeeder::class);
            $this->call(ElementMaterialRelationshipSeeder::class);
            $this->call(ElementGroupElementRelationshipSeeder::class);
            $this->call(GroupElementOrganigramRelationshipSeeder::class);
            $this->call(ElementPositionRelationshipSeeder::class);
            $this->call(GroupElementPositionRelationshipSeeder::class);
            $this->call(OrganigramPositionRelationshipSeeder::class);
        }
    }
}
