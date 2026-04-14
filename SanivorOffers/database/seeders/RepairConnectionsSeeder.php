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
            // JSON file not available — fall back to static seeders for element_material
            $this->command?->warn('RepairConnectionsSeeder: JSON file not found — falling back to static seeders.');
            if (DB::table('element_material')->count() === 0) {
                $this->call(ElementMaterialRelationshipSeeder::class);
            }
            return;
        }

        $hardware    = $jsonData['hardware'] ?? [];
        $elemente    = $jsonData['elemente'] ?? [];
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

        // Always repair if element_material is empty — this is the most critical table
        // and must never be left empty when elements and materials exist.
        $elementMaterialCount = DB::table('element_material')->count();
        if ($elementMaterialCount === 0) {
            return true;
        }

        // Check if connections are CORRECT: most elements should have at least one material.
        // If fewer than 30% of elements have materials, the connections are likely wrong.
        $totalElements    = Element::count();
        $elementsWithMats = DB::table('element_material')
            ->distinct('element_id')
            ->count('element_id');

        if ($totalElements > 0 && $elementsWithMats < ($totalElements * 0.3)) {
            return true;
        }

        // Also check auxiliary tables — if any are missing, repair the whole tree.
        $materialPieceCount = DB::table('material_material_piece')->count();
        $elementGroupCount  = DB::table('element_group_element')->count();
        $groupOrgCount      = DB::table('group_element_organigram')->count();

        if ($materialPieceCount === 0 || $elementGroupCount === 0 || $groupOrgCount === 0) {
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
            // Keep piece prices aligned with material prices.
            $piece = MaterialPiece::updateOrCreate(
                ['name' => $material->name . ' Piece'],
                [
                    'price_in' => $material->price_in ?? 0,
                    'price_out' => $material->price_out ?? 0,
                ]
            );

            // Allow many pieces per material; only ensure this one exists.
            $material->material_pieces()->syncWithoutDetaching([$piece->id]);
            $count++;
        }

        $this->command?->info("  → Repaired {$count} material ↔ material_piece connections.");
    }

    private function repairElementMaterial(array $hardware, array $elemente): void
    {
        $this->command?->info('  Repairing element ↔ material connections...');

        $hwKeyToName = [];
        foreach ($hardware as $key => $item) {
            $hwKeyToName[$key] = $item['name'] ?? '';
        }

        $materialsByName = Material::all()->keyBy('name');
        $elementsByName = Element::all()->keyBy('name');

        // Build all connections FIRST — do NOT truncate until we know we have data
        $rows = [];
        $now  = now();

        foreach ($elemente as $elemData) {
            $elemName     = $elemData['name'] ?? '';
            $elementModel = $elementsByName->get($elemName);

            if (! $elementModel) {
                continue;
            }

            $hwItems = $elemData['hardware_items'] ?? [];
            foreach ($hwItems as $hwKey => $hwItem) {
                if (! isset($hwItem['selected']) || ! $hwItem['selected']) {
                    continue;
                }

                $materialName  = $hwKeyToName[$hwKey] ?? null;
                if (! $materialName) {
                    continue;
                }

                $materialModel = $materialsByName->get($materialName);
                if (! $materialModel) {
                    continue;
                }

                $rows[] = [
                    'element_id'  => $elementModel->id,
                    'material_id' => $materialModel->id,
                    'quantity'    => floatval($hwItem['multiplier'] ?? 1),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
        }

        if (empty($rows)) {
            // JSON produced 0 connections — fall back to the static seeder so we
            // never leave element_material empty after a repair attempt.
            $this->command?->warn('  JSON produced 0 element-material connections — running fallback ElementMaterialRelationshipSeeder.');
            $this->call(ElementMaterialRelationshipSeeder::class);
            return;
        }

        // Safe to truncate now that we have replacement data
        DB::table('element_material')->truncate();

        // Bulk-insert in chunks (avoids parameter-limit issues on large sets)
        foreach (array_chunk($rows, 500) as $chunk) {
            // Deduplicate by (element_id, material_id) — pivot PK is composite
            $unique = [];
            foreach ($chunk as $row) {
                $key = $row['element_id'] . '_' . $row['material_id'];
                $unique[$key] = $row;
            }
            DB::table('element_material')->insertOrIgnore(array_values($unique));
        }

        $this->command?->info('  → Repaired ' . count($rows) . ' element ↔ material connections.');
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
