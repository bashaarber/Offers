<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Material;
use App\Models\MaterialPiece;
use App\Models\Element;
use App\Models\GroupElement;
use App\Models\Organigram;
use App\Models\Client;
use App\Models\Coefficient;
use Illuminate\Support\Facades\File;

class JsonImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production') && ! filter_var(env('RUN_JSON_IMPORT', false), FILTER_VALIDATE_BOOLEAN)) {
            $this->command?->info('JsonImportSeeder skipped in production (set RUN_JSON_IMPORT=true to run; truncates catalog when JSON is present).');

            return;
        }

        $jsonPaths = JsonCatalogPaths::candidateFilePaths();
        
        $jsonData = null;
        $jsonPath = null;
        
        foreach ($jsonPaths as $path) {
            if ($path && File::exists($path)) {
                $jsonPath = $path;
                $jsonData = json_decode(File::get($path), true);
                break;
            }
        }

        if (! $jsonData) {
            $this->command->warn('JSON file not found. Skipping JSON import without truncating existing catalog data.');
            return;
        }

        $this->command?->info("JSON file found at: {$jsonPath}");

        // Full rebuild mode is intended for one-time rebootstrap from JSON.
        // Keep it explicit so production runs are predictable.
        $fullRebuild = filter_var(env('JSON_IMPORT_FULL_REBUILD', true), FILTER_VALIDATE_BOOLEAN);
        if ($fullRebuild) {
            $this->clearAllData();
        } else {
            $this->command?->warn('JSON_IMPORT_FULL_REBUILD=false; skipping destructive truncate. Existing rows may cause partial/duplicate import.');
        }

        // Import Coefficients
        $this->importCoefficients($jsonData['coeff'] ?? []);

        // Import Clients
        $this->importClients($jsonData['kunde'] ?? []);

        // Import Materials (hardware)
        $materialsMap = $this->importMaterials($jsonData['hardware'] ?? []);

        // Create Material Pieces from Materials and connect them
        $this->createMaterialPiecesFromMaterials($materialsMap);

        // Import Elements
        $elementsMap = $this->importElements($jsonData['elemente'] ?? [], $materialsMap);

        // Import Organigrams, GroupElements and their relationships
        $this->importOrganigrams($jsonData['organigram'] ?? [], $elementsMap);

        $this->command->info('JSON import completed successfully!');
    }

    private function clearAllData()
    {
        $this->command->info('Clearing all existing data...');

        // Clear pivot tables first
        DB::table('material_material_piece')->truncate();
        DB::table('element_material')->truncate();
        DB::table('element_group_element')->truncate();
        DB::table('group_element_organigram')->truncate();
        DB::table('element_position')->truncate();
        DB::table('group_element_position')->truncate();
        DB::table('organigram_position')->truncate();
        DB::table('offert_position')->truncate();
        DB::table('position_materials')->truncate();

        // Clear main tables
        DB::table('materials')->truncate();
        DB::table('material_pieces')->truncate();
        DB::table('elements')->truncate();
        DB::table('group_elements')->truncate();
        DB::table('organigrams')->truncate();
        DB::table('clients')->truncate();
        DB::table('coefficients')->truncate();

        $this->command->info('All data cleared.');
    }

    private function importCoefficients(array $coeff)
    {
        if (empty($coeff)) {
            return;
        }

        Coefficient::create([
            'validity' => $coeff['Gültigkeit'] ?? '90 Tage',
            'labor_cost' => floatval($coeff['LaborCost'] ?? 50),
            'labor_price' => floatval($coeff['LaborPreis'] ?? 87.5),
            'service' => $coeff['Lieferung'] ?? '2 bis 4 Wochen nach GZA',
            'material' => floatval($coeff['Material'] ?? 1.3),
            'difficulty' => floatval($coeff['Schwierigkeits'] ?? 0.7),
            'payment_conditions' => $coeff['Zahlungskonditionen'] ?? '30 Tage Netto',
        ]);

        $this->command->info('Imported 1 coefficient');
    }

    private function importClients(array $kunde)
    {
        $count = 0;
        foreach ($kunde as $client) {
            if (empty($client['Name']) && empty($client['email'])) {
                continue;
            }

            Client::create([
                'name' => $client['Name'] ?? '',
                'email' => $client['email'] ?? '',
                'number' => $client['E-mail 1 - Type'] ?? '',
                'address' => $client['address'] ?? '',
            ]);
            $count++;
        }

        $this->command->info("Imported {$count} clients");
    }

    private function importMaterials(array $hardware): array
    {
        $materialsMap = [];
        $count = 0;

        $laborPrice = (float) (Coefficient::value('labor_price') ?? 0);

        foreach ($hardware as $key => $item) {
            $zTotal = floatval($item['timeLabor_schlos'] ?? 0)
                + floatval($item['timeLabor_pe'] ?? 0)
                + floatval($item['timeLabor_montag'] ?? 0);
            $totalArbeit = $zTotal * $laborPrice;

            $row = [
                'name' => $item['name'] ?? '',
                'unit' => $item['e'] ?? 'St.',
                'price_in' => floatval($item['preisIn'] ?? 0),
                'price_out' => floatval($item['preisOut'] ?? 0),
                'z_schlosserei' => floatval($item['timeLabor_schlos'] ?? 0),
                'z_pe' => strval($item['timeLabor_pe'] ?? 0),
                'z_montage' => strval($item['timeLabor_montag'] ?? 0),
                'z_total' => $zTotal,
                'zeit_cost' => floatval($item['preisIn'] ?? 0),
                'total' => floatval($item['preisOut'] ?? 0),
            ];
            if (Schema::hasColumn('materials', 'total_arbeit')) {
                $row['total_arbeit'] = $totalArbeit;
            }

            $material = Material::create($row);

            $materialsMap[$key] = $material->id;
            $count++;
        }

        $this->command->info("Imported {$count} materials");
        return $materialsMap;
    }

    private function createMaterialPiecesFromMaterials(array $materialsMap)
    {
        $count = 0;
        foreach ($materialsMap as $key => $materialId) {
            $material = Material::find($materialId);
            if ($material) {
                // Keep piece prices aligned with material prices.
                $materialPiece = MaterialPiece::updateOrCreate(
                    ['name' => $material->name . ' Piece'],
                    [
                        'price_in' => $material->price_in ?? 0,
                        'price_out' => $material->price_out ?? 0,
                    ]
                );

                // Allow many pieces per material; only ensure this one exists.
                $material->material_pieces()->syncWithoutDetaching([$materialPiece->id]);
                $count++;
            }
        }

        $this->command->info("Created and connected {$count} material pieces");
    }

    private function importElements(array $elemente, array $materialsMap): array
    {
        $elementsMap = [];
        $count = 0;

        foreach ($elemente as $key => $element) {
            $elementModel = Element::create([
                'name' => $element['name'] ?? '',
            ]);

            $elementsMap[$key] = $elementModel->id;

            // Connect materials to element
            if (isset($element['hardware_items']) && is_array($element['hardware_items'])) {
                foreach ($element['hardware_items'] as $materialKey => $hardwareItem) {
                    if (isset($materialsMap[$materialKey]) && isset($hardwareItem['selected']) && $hardwareItem['selected']) {
                        $quantity = floatval($hardwareItem['multiplier'] ?? 1);
                        $elementModel->materials()->attach($materialsMap[$materialKey], ['quantity' => $quantity]);
                    }
                }
            }

            $count++;
        }

        $this->command->info("Imported {$count} elements");
        return $elementsMap;
    }

    private function importOrganigrams(array $organigrams, array $elementsMap)
    {
        $organigramsMap = [];
        $groupElementsMap = [];
        $count = 0;

        foreach ($organigrams as $organigram) {
            $organigramModel = Organigram::create([
                'name' => $organigram['name'] ?? '',
            ]);

            $organigramsMap[$organigram['id']] = $organigramModel->id;
            $count++;

            // Process children (GroupElements)
            if (isset($organigram['children']) && is_array($organigram['children'])) {
                foreach ($organigram['children'] as $child) {
                    $groupElementModel = GroupElement::create([
                        'name' => $child['name'] ?? '',
                    ]);

                    $groupElementsMap[$child['id']] = $groupElementModel->id;

                    // Connect GroupElement to Organigram
                    $organigramModel->group_elements()->attach($groupElementModel->id);

                    // Process elements in this GroupElement
                    if (isset($child['elements']) && is_array($child['elements'])) {
                        foreach ($child['elements'] as $elementRef) {
                            $elementKey = $elementRef['name'] ?? null;
                            if ($elementKey && isset($elementsMap[$elementKey])) {
                                $groupElementModel->elements()->attach($elementsMap[$elementKey]);
                            }
                        }
                    }
                }
            }

            // Process direct elements in organigram
            if (isset($organigram['elements']) && is_array($organigram['elements'])) {
                foreach ($organigram['elements'] as $elementRef) {
                    $elementKey = is_array($elementRef) ? ($elementRef['name'] ?? null) : $elementRef;
                    if ($elementKey && isset($elementsMap[$elementKey])) {
                        // Create a default group element for direct elements
                        $defaultGroupElement = GroupElement::firstOrCreate(
                            ['name' => 'Default - ' . $organigramModel->name],
                            ['name' => 'Default - ' . $organigramModel->name]
                        );
                        $organigramModel->group_elements()->syncWithoutDetaching([$defaultGroupElement->id]);
                        $defaultGroupElement->elements()->syncWithoutDetaching([$elementsMap[$elementKey]]);
                    }
                }
            }
        }

        $this->command->info("Imported {$count} organigrams");
    }
}
