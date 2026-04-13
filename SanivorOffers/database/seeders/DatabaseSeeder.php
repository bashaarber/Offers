<?php

namespace Database\Seeders;

use App\Models\Element;
use App\Models\Organigram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
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
                $this->command?->warn('JSON import failed: '.$e->getMessage());
            }
        }

        if (! Element::query()->exists()) {
            $this->command?->warn('Catalog is empty; running fallback catalog seeders.');
            $this->runFallbackCatalogSeeders();
        }

        $this->call(RepairConnectionsSeeder::class);
    }

    private function runFallbackCatalogSeeders(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('TRUNCATE TABLE
                material_material_piece,
                element_material,
                element_group_element,
                group_element_organigram,
                position_materials,
                material_pieces,
                materials,
                organigrams,
                group_elements,
                elements
                RESTART IDENTITY CASCADE');
        } else {
            DB::statement('PRAGMA foreign_keys = OFF');
            foreach ([
                'material_material_piece', 'element_material',
                'element_group_element', 'group_element_organigram',
                'position_materials', 'material_pieces', 'materials',
                'organigrams', 'group_elements', 'elements',
            ] as $table) {
                DB::table($table)->truncate();
            }
            DB::statement('PRAGMA foreign_keys = ON');
        }

        $this->call(MaterialSeeder::class);
        $this->call(MaterialPieceSeeder::class);
        $this->call(MaterialMaterialPieceRelationshipSeeder::class);

        $this->call(ElementSeeder::class);
        $this->call(GroupElementSeeder::class);
        $this->call(OrganigramSeeder::class);

        $this->call(ElementMaterialRelationshipSeeder::class);
        $this->call(ElementGroupElementRelationshipSeeder::class);
        $this->call(GroupElementOrganigramRelationshipSeeder::class);
    }
}
