<?php

namespace Database\Seeders;

use App\Models\Element;
use App\Models\Organigram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(CoefficientSeeder::class);

        $allowJsonImport = ! app()->environment('production')
            || filter_var(env('RUN_JSON_IMPORT', false), FILTER_VALIDATE_BOOLEAN);

        if ($allowJsonImport) {
            try {
                $this->call(JsonImportSeeder::class);
            } catch (\Exception $e) {
                $this->command?->warn('JSON import failed, using default seeders: '.$e->getMessage());
                $this->resetAndRunFallbackCatalogSeeders();
            }
        }

        // If catalog is empty OR has wrong/old placeholder data, re-seed with correct data.
        // We detect wrong data by checking for a known correct organigram name ('Rahme').
        $hasCorrectCatalog = Organigram::where('name', 'Rahme')->exists();
        if (! $hasCorrectCatalog) {
            $this->command?->warn('Catalog missing or outdated; re-seeding with correct catalog data.');
            $this->resetAndRunFallbackCatalogSeeders();
        }
    }

    private function resetAndRunFallbackCatalogSeeders(): void
    {
        // Wipe catalog tables with CASCADE to handle FK constraints (PostgreSQL-safe).
        // Positions table is preserved; only catalog and catalog-pivot rows are removed.
        DB::statement('TRUNCATE TABLE
            group_element_organigram,
            element_group_element,
            element_position,
            group_element_position,
            organigram_position,
            organigrams,
            group_elements,
            elements
            RESTART IDENTITY CASCADE');

        $this->call(ElementSeeder::class);
        $this->call(GroupElementSeeder::class);
        $this->call(OrganigramSeeder::class);
        $this->call(ElementGroupElementRelationshipSeeder::class);
        $this->call(GroupElementOrganigramRelationshipSeeder::class);
    }
}
