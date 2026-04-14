<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Element;
use App\Models\Material;
use Database\Seeders\ElementMaterialRelationshipSeeder;
use Database\Seeders\JsonCatalogPaths;
use Database\Seeders\RepairConnectionsSeeder;

/**
 * Diagnostic & repair command for the element ↔ material pivot table.
 *
 * Usage:
 *   # Check only (read-only):
 *   php artisan elements:check-materials
 *
 *   # Check and automatically repair if broken:
 *   php artisan elements:check-materials --repair
 *
 *   # Force-repair even if no breakage is detected:
 *   php artisan elements:check-materials --force
 */
class CheckElementMaterials extends Command
{
    protected $signature = 'elements:check-materials
                            {--repair : Automatically repair broken connections}
                            {--force  : Force repair even when connections look intact}';

    protected $description = 'Diagnose (and optionally repair) the element ↔ material pivot connections';

    public function handle(): int
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════');
        $this->info('  Element ↔ Material Connection Diagnostic');
        $this->info('═══════════════════════════════════════════════');

        // ── 1. Basic counts ──────────────────────────────────────
        $elementCount  = Element::count();
        $materialCount = Material::count();
        $pivotCount    = DB::table('element_material')->count();
        $connectedElements = DB::table('element_material')
            ->distinct('element_id')
            ->count('element_id');

        $coverage = $elementCount > 0 ? round($connectedElements / $elementCount * 100, 1) : 0;

        $this->line('');
        $this->line("  Elements in DB         : <fg=cyan>{$elementCount}</>");
        $this->line("  Materials in DB        : <fg=cyan>{$materialCount}</>");
        $this->line("  Rows in element_material: <fg=cyan>{$pivotCount}</>");
        $this->line("  Elements with materials: <fg=cyan>{$connectedElements} ({$coverage}%)</>");

        // ── 2. Health check ──────────────────────────────────────
        $this->line('');
        $problems = [];

        if ($elementCount === 0) {
            $problems[] = 'No elements found in DB';
        }
        if ($materialCount === 0) {
            $problems[] = 'No materials found in DB';
        }
        if ($pivotCount === 0 && $elementCount > 0 && $materialCount > 0) {
            $problems[] = 'element_material table is EMPTY despite elements and materials existing';
        }
        if ($coverage < 30 && $elementCount > 0) {
            $problems[] = "Very low coverage: only {$coverage}% of elements have materials";
        }

        // Check for duplicate pivot rows (composite PK violation if any got through)
        $duplicates = DB::table('element_material')
            ->select('element_id', 'material_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('element_id', 'material_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();
        if ($duplicates > 0) {
            $problems[] = "Found {$duplicates} duplicate (element_id, material_id) pairs";
        }

        // Check for orphan rows (referencing non-existent elements or materials)
        $orphanElements = DB::table('element_material as em')
            ->leftJoin('elements as e', 'e.id', '=', 'em.element_id')
            ->whereNull('e.id')
            ->count();
        $orphanMaterials = DB::table('element_material as em')
            ->leftJoin('materials as m', 'm.id', '=', 'em.material_id')
            ->whereNull('m.id')
            ->count();
        if ($orphanElements > 0) {
            $problems[] = "Found {$orphanElements} pivot rows referencing non-existent elements";
        }
        if ($orphanMaterials > 0) {
            $problems[] = "Found {$orphanMaterials} pivot rows referencing non-existent materials";
        }

        // Check if JSON file is available for repair
        $jsonAvailable = false;
        $jsonFoundAt   = null;
        foreach (JsonCatalogPaths::candidateFilePaths() as $path) {
            if ($path && File::exists($path)) {
                $jsonAvailable = true;
                $jsonFoundAt   = $path;
                break;
            }
        }

        $this->line('');
        if (empty($problems)) {
            $this->info('  ✅  All checks passed — connections look healthy.');
        } else {
            $this->error('  ❌  Problems detected:');
            foreach ($problems as $p) {
                $this->line("       • <fg=red>{$p}</>");
            }
        }

        $this->line('');
        $this->line('  JSON data file: ' . ($jsonAvailable
            ? "<fg=green>FOUND</> at {$jsonFoundAt}"
            : '<fg=yellow>NOT FOUND</> (fallback seeder will be used for repair)'));

        // ── 3. Per-element detail (first 10 with no materials) ───
        if ($pivotCount > 0) {
            $noMaterials = DB::table('elements as e')
                ->leftJoin('element_material as em', 'em.element_id', '=', 'e.id')
                ->whereNull('em.element_id')
                ->select('e.id', 'e.name')
                ->limit(10)
                ->get();

            if ($noMaterials->count() > 0) {
                $this->line('');
                $this->line('  Elements with no materials (first 10):');
                foreach ($noMaterials as $el) {
                    $this->line("    [{$el->id}] {$el->name}");
                }
            }
        }

        // ── 4. Sample connections (spot-check) ───────────────────
        if ($pivotCount > 0) {
            $this->line('');
            $this->line('  Sample connections (first 5 rows):');
            $samples = DB::table('element_material as em')
                ->join('elements as e',  'e.id',  '=', 'em.element_id')
                ->join('materials as m', 'm.id',  '=', 'em.material_id')
                ->select('e.name as element', 'm.name as material', 'em.quantity')
                ->limit(5)
                ->get();
            foreach ($samples as $row) {
                $this->line("    «{$row->element}» → «{$row->material}» × {$row->quantity}");
            }
        }

        // ── 5. Repair ────────────────────────────────────────────
        $shouldRepair = $this->option('force') || ($this->option('repair') && ! empty($problems));

        if ($shouldRepair) {
            $this->line('');
            $this->warn('  ⚙  Running repair…');

            if ($jsonAvailable) {
                $this->call('db:seed', ['--class' => RepairConnectionsSeeder::class, '--force' => true]);
            } else {
                $this->warn('  JSON not found — using ElementMaterialRelationshipSeeder as fallback.');
                $this->call('db:seed', ['--class' => ElementMaterialRelationshipSeeder::class, '--force' => true]);
            }

            // Re-check after repair
            $newPivotCount = DB::table('element_material')->count();
            $newConnected  = DB::table('element_material')
                ->distinct('element_id')->count('element_id');
            $newCoverage   = $elementCount > 0 ? round($newConnected / $elementCount * 100, 1) : 0;

            $this->line('');
            if ($newPivotCount > 0) {
                $this->info("  ✅  Repair complete: {$newPivotCount} pivot rows, {$newConnected}/{$elementCount} elements ({$newCoverage}%) now have materials.");
            } else {
                $this->error('  ❌  Repair ran but element_material is STILL empty. Manual intervention required.');
                return Command::FAILURE;
            }
        } elseif (! empty($problems)) {
            $this->line('');
            $this->warn('  ℹ  Run with --repair to automatically fix the above problems.');
            $this->line('     php artisan elements:check-materials --repair');
            return Command::FAILURE;
        }

        $this->line('');
        return Command::SUCCESS;
    }
}
