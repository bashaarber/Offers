<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use App\Models\Material;
use App\Models\MaterialPiece;
use App\Models\Element;
use App\Models\GroupElement;
use App\Models\Organigram;
use App\Models\Coefficient;

class RepairConnectionsSeeder extends Seeder
{
    public function run(): void
    {
        $needsRepair = $this->detectBrokenConnections();
        if (! $needsRepair) {
            $this->command?->info('RepairConnectionsSeeder: all connections intact, nothing to repair.');
            return;
        }

        $this->command?->warn('RepairConnectionsSeeder: broken connections detected — repairing...');

        $jsonData = $this->loadJsonData();
        if (! $jsonData) {
            $this->command?->error('RepairConnectionsSeeder: JSON file not found, cannot repair.');
            return;
        }

        $hardware = $jsonData['hardware'] ?? [];
        $elemente = $jsonData['elemente'] ?? [];
        $organigrams = $jsonData['organigram'] ?? [];

        $this->repairMaterialMaterialPiece($hardware);
        $this->repairElementMaterial($hardware, $elemente);
        $this->repairElementGroupElementAndOrganigram($elemente, $organigrams);
        $this->recalculateMaterialPrices();

        $this->command?->info('RepairConnectionsSeeder: all connections repaired.');
    }

    private function detectBrokenConnections(): bool
    {
        $hasElements = Element::query()->exists();
        $hasMaterials = Material::query()->exists();

        if (! $hasElements || ! $hasMaterials) {
            return false;
        }

        $elementMaterialCount = DB::table('element_material')->count();
        $materialPieceCount = DB::table('material_material_piece')->count();
        $elementGroupCount = DB::table('element_group_element')->count();
        $groupOrgCount = DB::table('group_element_organigram')->count();

        if ($elementMaterialCount === 0
            || $materialPieceCount === 0
            || $elementGroupCount === 0
            || $groupOrgCount === 0) {
            return true;
        }

        // Check if connections are CORRECT: most elements should have at least one material.
        // If fewer than 50% of elements have materials, the connections are likely wrong.
        $totalElements = Element::count();
        $elementsWithMaterials = DB::table('element_material')
            ->distinct('element_id')
            ->count('element_id');

        if ($totalElements > 0 && $elementsWithMaterials < ($totalElements * 0.3)) {
            return true;
        }

        return false;
    }

    private function loadJsonData(): ?array
    {
        $jsonPaths = [
            base_path('database/seeders/DB___proj_98_2026-01-19 18_03_10.json'),
            '/Users/arberbasha/Downloads/DB___proj_98_2026-01-19 18_03_10.json',
            storage_path('app/DB___proj_98_2026-01-19 18_03_10.json'),
        ];

        foreach ($jsonPaths as $path) {
            if (File::exists($path)) {
                return json_decode(File::get($path), true);
            }
        }

        return null;
    }

    private function repairMaterialMaterialPiece(array $hardware): void
    {
        $this->command?->info('  Repairing material ↔ material_piece connections...');

        DB::table('material_material_piece')->truncate();

        $materials = Material::all();
        $count = 0;

        foreach ($materials as $material) {
            $piece = MaterialPiece::firstOrCreate(
                ['name' => $material->name . ' Piece'],
                [
                    'price_in' => $material->price_in ?? 0,
                    'price_out' => $material->price_out ?? 0,
                ]
            );

            $material->material_pieces()->attach($piece->id);
            $count++;
        }

        $this->command?->info("  → Repaired {$count} material ↔ material_piece connections.");
    }

    private function repairElementMaterial(array $hardware, array $elemente): void
    {
        $this->command?->info('  Repairing element ↔ material connections...');

        DB::table('element_material')->truncate();

        $hwKeyToName = [];
        foreach ($hardware as $key => $item) {
            $hwKeyToName[$key] = $item['name'] ?? '';
        }

        $materialsByName = Material::all()->keyBy('name');
        $elementsByName = Element::all()->keyBy('name');

        $count = 0;

        foreach ($elemente as $elemKey => $elemData) {
            $elemName = $elemData['name'] ?? '';
            $elementModel = $elementsByName->get($elemName);

            if (! $elementModel) {
                continue;
            }

            $hwItems = $elemData['hardware_items'] ?? [];
            foreach ($hwItems as $hwKey => $hwItem) {
                if (! isset($hwItem['selected']) || ! $hwItem['selected']) {
                    continue;
                }

                $materialName = $hwKeyToName[$hwKey] ?? null;
                if (! $materialName) {
                    continue;
                }

                $materialModel = $materialsByName->get($materialName);
                if (! $materialModel) {
                    continue;
                }

                $quantity = floatval($hwItem['multiplier'] ?? 1);

                if (! $elementModel->materials()->where('material_id', $materialModel->id)->exists()) {
                    $elementModel->materials()->attach($materialModel->id, ['quantity' => $quantity]);
                    $count++;
                }
            }
        }

        $this->command?->info("  → Repaired {$count} element ↔ material connections.");
    }

    private function repairElementGroupElementAndOrganigram(array $elemente, array $organigrams): void
    {
        $this->command?->info('  Repairing organigram tree connections...');

        DB::table('element_group_element')->truncate();
        DB::table('group_element_organigram')->truncate();

        $elemKeyToName = [];
        foreach ($elemente as $key => $elem) {
            $elemKeyToName[$key] = $elem['name'] ?? '';
        }

        $elementsByName = Element::all()->keyBy('name');
        $groupsByName = GroupElement::all()->keyBy('name');
        $orgsByName = Organigram::all()->keyBy('name');

        $elemGroupCount = 0;
        $groupOrgCount = 0;

        foreach ($organigrams as $orgData) {
            $orgModel = $orgsByName->get($orgData['name'] ?? '');
            if (! $orgModel) {
                continue;
            }

            $children = $orgData['children'] ?? [];
            foreach ($children as $child) {
                $groupModel = $groupsByName->get($child['name'] ?? '');
                if (! $groupModel) {
                    continue;
                }

                if (! $orgModel->group_elements()->where('group_element_id', $groupModel->id)->exists()) {
                    $orgModel->group_elements()->attach($groupModel->id);
                    $groupOrgCount++;
                }

                $childElements = $child['elements'] ?? [];
                foreach ($childElements as $elemRef) {
                    $elemRefName = is_array($elemRef) ? ($elemRef['name'] ?? null) : $elemRef;
                    if (! $elemRefName) {
                        continue;
                    }

                    $elemRealName = $elemKeyToName[$elemRefName] ?? null;
                    if (! $elemRealName) {
                        continue;
                    }

                    $elementModel = $elementsByName->get($elemRealName);
                    if (! $elementModel) {
                        continue;
                    }

                    if (! $groupModel->elements()->where('element_id', $elementModel->id)->exists()) {
                        $groupModel->elements()->attach($elementModel->id);
                        $elemGroupCount++;
                    }
                }
            }
        }

        $this->command?->info("  → Repaired {$groupOrgCount} group ↔ organigram and {$elemGroupCount} element ↔ group connections.");
    }

    private function recalculateMaterialPrices(): void
    {
        $this->command?->info('  Recalculating material prices...');

        $coefficient = Coefficient::first();
        $laborPrice = (float) ($coefficient->labor_price ?? 0);

        $updated = 0;
        $hasTotalArbeit = Schema::hasColumn('materials', 'total_arbeit');

        foreach (Material::cursor() as $material) {
            $zTotal = (float) $material->z_schlosserei + (float) $material->z_pe + (float) $material->z_montage;
            $totalArbeit = $zTotal * $laborPrice;

            $updateData = [
                'z_total' => $zTotal,
                'zeit_cost' => $totalArbeit,
            ];

            if ($hasTotalArbeit) {
                $updateData['total_arbeit'] = $totalArbeit;
            }

            if ((float) $material->total === 0.0 || $material->total === null) {
                $updateData['total'] = (float) $material->price_out;
            }

            $material->update($updateData);
            $updated++;
        }

        $this->command?->info("  → Recalculated {$updated} material prices.");
    }
}
