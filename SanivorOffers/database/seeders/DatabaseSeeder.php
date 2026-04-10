<?php

namespace Database\Seeders;

use App\Models\Element;
use Illuminate\Database\Seeder;

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
                $this->runFallbackCatalogSeeders();
            }
        }

        if (! Element::query()->exists()) {
            $this->command?->warn('Catalog is empty; running default catalog seeders.');
            $this->runFallbackCatalogSeeders();
        }
    }

    private function runFallbackCatalogSeeders(): void
    {
        if (Element::query()->exists()) {
            return;
        }

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
