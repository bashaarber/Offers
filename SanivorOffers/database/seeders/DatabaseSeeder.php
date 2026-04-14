<?php

namespace Database\Seeders;

use App\Models\Element;
use App\Models\Material;
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

        try {
            $allowJsonImport = ! app()->environment('production')
                || filter_var(env('RUN_JSON_IMPORT', false), FILTER_VALIDATE_BOOLEAN);

            if ($allowJsonImport) {
                try {
                    $this->call(JsonImportSeeder::class);
                } catch (\Throwable $e) {
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
        } finally {
            // Final safety net: always attempt pivot repair, even if earlier seeding failed.
            try {
                $this->call(RepairConnectionsSeeder::class);
            } catch (\Throwable $e) {
                $this->command?->warn('RepairConnectionsSeeder failed: '.$e->getMessage());
            }

            // Last-resort guarantee: if element_material is STILL empty after all the
            // above, run the static relationship seeder directly. This covers cases
            // where JSON is unavailable and RepairConnectionsSeeder also skipped.
            try {
                $hasElements  = Element::query()->exists();
                $hasMaterials = Material::query()->exists();
                $hasPivot     = DB::table('element_material')->count() > 0;

                if ($hasElements && $hasMaterials && ! $hasPivot) {
                    $this->command?->warn('element_material still empty after repair — running ElementMaterialRelationshipSeeder directly.');
                    $this->call(ElementMaterialRelationshipSeeder::class);
                }
            } catch (\Throwable $e) {
                $this->command?->warn('ElementMaterialRelationshipSeeder fallback failed: '.$e->getMessage());
            }
        }
    }

    private function resetAndRunFallbackCatalogSeeders(): void
    {
        // Wipe all catalog tables (CASCADE handles FK constraints on PostgreSQL).
        // Positions rows are preserved; only catalog + pivot rows are removed.
        DB::statement('TRUNCATE TABLE
            material_material_piece,
            element_material,
            element_position,
            group_element_position,
            organigram_position,
            group_element_organigram,
            element_group_element,
            position_materials,
            material_pieces,
            materials,
            organigrams,
            group_elements,
            elements
            RESTART IDENTITY CASCADE');

        // Seed materials (pieces first, then materials, then connect them)
        $this->call(MaterialPieceSeeder::class);
        $this->call(MaterialSeeder::class);
        $this->call(MaterialMaterialPieceRelationshipSeeder::class);

        // Seed catalog structure
        $this->call(ElementSeeder::class);
        $this->call(GroupElementSeeder::class);
        $this->call(OrganigramSeeder::class);

        // Seed relationships
        $this->call(ElementMaterialRelationshipSeeder::class);
        $this->call(ElementGroupElementRelationshipSeeder::class);
        $this->call(GroupElementOrganigramRelationshipSeeder::class);
    }
}
