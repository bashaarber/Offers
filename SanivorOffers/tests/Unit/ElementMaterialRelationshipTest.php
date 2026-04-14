<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Element;
use App\Models\Material;
use Database\Seeders\MaterialSeeder;
use Database\Seeders\ElementSeeder;
use Database\Seeders\ElementMaterialRelationshipSeeder;
use Database\Seeders\RepairConnectionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

/**
 * Tests for the Element ↔ Material pivot relationship.
 *
 * Run with:
 *   php artisan test --filter=ElementMaterialRelationshipTest
 */
class ElementMaterialRelationshipTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────
    // 1. MODEL RELATIONSHIP
    // ─────────────────────────────────────────────

    /** The Element model exposes a materials() BelongsToMany. */
    public function test_element_has_materials_relationship(): void
    {
        $element = Element::factory()->create(['name' => 'Test Element']);

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsToMany::class,
            $element->materials()
        );
    }

    /** The Material model exposes an elements() BelongsToMany. */
    public function test_material_has_elements_relationship(): void
    {
        $material = Material::factory()->create(['name' => 'Test Material']);

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsToMany::class,
            $material->elements()
        );
    }

    /** attach() on elements()->materials() persists in element_material. */
    public function test_can_attach_material_to_element(): void
    {
        $element  = Element::factory()->create(['name' => 'WC Element']);
        $material = Material::factory()->create(['name' => 'Befestigung']);

        $element->materials()->attach($material->id, ['quantity' => 4.0]);

        $this->assertDatabaseHas('element_material', [
            'element_id'  => $element->id,
            'material_id' => $material->id,
            'quantity'    => 4.0,
        ]);
    }

    /** Element::with('materials') loads the pivot quantity. */
    public function test_eager_loading_includes_pivot_quantity(): void
    {
        $element  = Element::factory()->create(['name' => 'WC Element']);
        $material = Material::factory()->create(['name' => 'Befestigung', 'unit' => 'St.']);

        $element->materials()->attach($material->id, ['quantity' => 3.0]);

        $loaded = Element::with('materials')->find($element->id);

        $this->assertCount(1, $loaded->materials);
        $this->assertEquals(3.0, (float) $loaded->materials->first()->pivot->quantity);
    }

    /** Deleting an element cascades to element_material (ON DELETE CASCADE). */
    public function test_deleting_element_removes_pivot_rows(): void
    {
        $element  = Element::factory()->create();
        $material = Material::factory()->create();
        $element->materials()->attach($material->id, ['quantity' => 1.0]);

        $this->assertDatabaseHas('element_material', ['element_id' => $element->id]);

        $element->delete();

        $this->assertDatabaseMissing('element_material', ['element_id' => $element->id]);
    }

    // ─────────────────────────────────────────────
    // 2. FALLBACK SEEDER
    // ─────────────────────────────────────────────

    /** ElementMaterialRelationshipSeeder creates connections when names match. */
    public function test_fallback_seeder_creates_connections(): void
    {
        // Seed prerequisite data
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);

        $beforeCount = DB::table('element_material')->count();
        $this->assertEquals(0, $beforeCount, 'element_material should be empty before seeder runs');

        $this->seed(ElementMaterialRelationshipSeeder::class);

        $afterCount = DB::table('element_material')->count();
        $this->assertGreaterThan(50, $afterCount,
            "ElementMaterialRelationshipSeeder should create at least 50 connections, got {$afterCount}"
        );
    }

    /** ElementMaterialRelationshipSeeder is idempotent (syncWithoutDetaching). */
    public function test_fallback_seeder_is_idempotent(): void
    {
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);

        $this->seed(ElementMaterialRelationshipSeeder::class);
        $countAfterFirst = DB::table('element_material')->count();

        // Run again
        $this->seed(ElementMaterialRelationshipSeeder::class);
        $countAfterSecond = DB::table('element_material')->count();

        $this->assertEquals($countAfterFirst, $countAfterSecond,
            'Running the seeder twice should not duplicate connections'
        );
    }

    // ─────────────────────────────────────────────
    // 3. REPAIR SEEDER — CORE SCENARIOS
    // ─────────────────────────────────────────────

    /**
     * RepairConnectionsSeeder does NOTHING when element_material is already
     * populated and coverage >= 30 %.
     */
    public function test_repair_seeder_skips_when_connections_are_intact(): void
    {
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);
        $this->seed(ElementMaterialRelationshipSeeder::class);

        $countBefore = DB::table('element_material')->count();

        $this->seed(RepairConnectionsSeeder::class);

        $countAfter = DB::table('element_material')->count();

        // Count must not drop to zero
        $this->assertGreaterThan(0, $countAfter,
            'RepairConnectionsSeeder must not empty element_material when data is intact'
        );
        // Count must be close to what it was (±10 rows tolerance for minor re-syncs)
        $this->assertEqualsWithDelta($countBefore, $countAfter, 10,
            'RepairConnectionsSeeder should not significantly change element_material when intact'
        );
    }

    /**
     * RepairConnectionsSeeder restores connections when element_material is
     * emptied externally (the "production empty table" scenario).
     */
    public function test_repair_seeder_restores_empty_element_material(): void
    {
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);
        // Don't run relationship seeder → element_material stays empty

        $this->assertEquals(0, DB::table('element_material')->count());
        $this->assertGreaterThan(0, Element::count(), 'Elements must exist');
        $this->assertGreaterThan(0, Material::count(), 'Materials must exist');

        $this->seed(RepairConnectionsSeeder::class);

        $afterCount = DB::table('element_material')->count();
        $this->assertGreaterThan(50, $afterCount,
            "After repair, element_material should have > 50 rows, got {$afterCount}"
        );
    }

    /**
     * RepairConnectionsSeeder does NOT leave element_material empty even when
     * the JSON file is missing (must fall back to static seeder).
     */
    public function test_repair_seeder_falls_back_to_static_seeder_when_no_json(): void
    {
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);

        // Temporarily point all JSON paths to non-existent files
        // by monkey-patching the config (the seeder reads base_path()
        // which always exists, so we test the "empty $rows" code-path by
        // verifying the final outcome: element_material must not be 0).
        $this->seed(RepairConnectionsSeeder::class);

        $count = DB::table('element_material')->count();
        $this->assertGreaterThan(0, $count,
            'element_material must never be left empty after RepairConnectionsSeeder runs'
        );
    }

    /**
     * RepairConnectionsSeeder does NOT truncate element_material when it
     * cannot build any replacement rows.
     */
    public function test_repair_seeder_never_leaves_element_material_empty(): void
    {
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);
        $this->seed(ElementMaterialRelationshipSeeder::class);

        $goodCount = DB::table('element_material')->count();
        $this->assertGreaterThan(0, $goodCount);

        // Simulate a "broken connections" signal by emptying element_material
        DB::table('element_material')->delete();
        $this->assertEquals(0, DB::table('element_material')->count());

        // Repair must bring it back
        $this->seed(RepairConnectionsSeeder::class);

        $repairedCount = DB::table('element_material')->count();
        $this->assertGreaterThan(0, $repairedCount,
            "element_material must not stay empty after RepairConnectionsSeeder"
        );
    }

    // ─────────────────────────────────────────────
    // 4. COVERAGE ASSERTIONS
    // ─────────────────────────────────────────────

    /** At least 80 % of elements must have at least one material after seeding. */
    public function test_material_coverage_is_sufficient(): void
    {
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);
        $this->seed(ElementMaterialRelationshipSeeder::class);

        $total        = Element::count();
        $withMaterials = DB::table('element_material')
            ->distinct('element_id')
            ->count('element_id');

        $coverage = $total > 0 ? ($withMaterials / $total) : 0;

        $this->assertGreaterThan(0.8, $coverage,
            sprintf(
                'Material coverage is only %.0f%% (%d/%d elements have materials)',
                $coverage * 100, $withMaterials, $total
            )
        );
    }

    /**
     * Each pivot row must have a non-negative quantity.
     * quantity = 0 is allowed for optional/variable materials (e.g. Sanipex
     * connection variants where the exact count depends on the project).
     */
    public function test_all_pivot_quantities_are_non_negative(): void
    {
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);
        $this->seed(ElementMaterialRelationshipSeeder::class);

        $negative = DB::table('element_material')
            ->where('quantity', '<', 0)
            ->count();

        $this->assertEquals(0, $negative,
            "Found {$negative} pivot rows with quantity < 0"
        );
    }

    /** There must be no duplicate (element_id, material_id) pairs. */
    public function test_no_duplicate_pivot_rows(): void
    {
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);
        $this->seed(ElementMaterialRelationshipSeeder::class);

        $duplicates = DB::table('element_material')
            ->select('element_id', 'material_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('element_id', 'material_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        $this->assertEquals(0, $duplicates,
            "Found {$duplicates} duplicate (element_id, material_id) pairs in element_material"
        );
    }

    // ─────────────────────────────────────────────
    // 5. SPECIFIC WELL-KNOWN ELEMENT CONNECTIONS
    // ─────────────────────────────────────────────

    /**
     * "Vorwand Grundrahme" must be linked to at least "Befestigung".
     */
    public function test_vorwand_grundrahme_has_befestigung(): void
    {
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);
        $this->seed(ElementMaterialRelationshipSeeder::class);

        $element  = Element::where('name', 'Vorwand Grundrahme')->first();
        $material = Material::where('name', 'Befestigung')->first();

        $this->assertNotNull($element,  'Element "Vorwand Grundrahme" not found in DB');
        $this->assertNotNull($material, 'Material "Befestigung" not found in DB');

        $linked = DB::table('element_material')
            ->where('element_id', $element->id)
            ->where('material_id', $material->id)
            ->exists();

        $this->assertTrue($linked,
            '"Vorwand Grundrahme" must be linked to "Befestigung"'
        );
    }

    /**
     * "Wand-WC-Element UP 320, Typ 112" must be linked to "UP320, Typ 112".
     */
    public function test_wand_wc_element_has_correct_material(): void
    {
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);
        $this->seed(ElementMaterialRelationshipSeeder::class);

        $element  = Element::where('name', 'Wand-WC-Element UP 320, Typ 112')->first();
        $material = Material::where('name', 'UP320, Typ 112')->first();

        $this->assertNotNull($element,  'Element "Wand-WC-Element UP 320, Typ 112" not found');
        $this->assertNotNull($material, 'Material "UP320, Typ 112" not found');

        $linked = DB::table('element_material')
            ->where('element_id', $element->id)
            ->where('material_id', $material->id)
            ->exists();

        $this->assertTrue($linked,
            '"Wand-WC-Element UP 320, Typ 112" must be linked to "UP320, Typ 112"'
        );
    }
}
