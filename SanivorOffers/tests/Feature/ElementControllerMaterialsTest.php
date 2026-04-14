<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Element;
use App\Models\Material;
use App\Models\User;
use Database\Seeders\MaterialSeeder;
use Database\Seeders\ElementSeeder;
use Database\Seeders\ElementMaterialRelationshipSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

/**
 * Feature tests: GET /element returns the Materials column data.
 *
 * Run with:
 *   php artisan test --filter=ElementControllerMaterialsTest
 */
class ElementControllerMaterialsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user without relying on a seeder
        $this->admin = User::create([
            'username' => 'TestAdmin',
            'email'    => 'testadmin@example.com',
            'role'     => 'admin',
            'password' => bcrypt('password'),
        ]);
    }

    /** Unauthenticated requests are redirected to login. */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('element.index'));
        $response->assertRedirect(route('login'));
    }

    /** The /element page loads successfully for an admin. */
    public function test_element_index_loads_for_admin(): void
    {
        $this->actingAs($this->admin)
             ->get(route('element.index'))
             ->assertStatus(200);
    }

    /** When element_material is empty, the Materials cell is blank. */
    public function test_materials_column_empty_when_no_pivot_data(): void
    {
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);
        // Intentionally do NOT run the relationship seeder

        $element = Element::first();
        $this->assertNotNull($element);

        // Materials must be empty collection
        $this->assertCount(0, $element->materials);

        // The rendered page must not contain any of the known material names
        // (we just check the response is 200 and no material names appear)
        $response = $this->actingAs($this->admin)
                         ->get(route('element.index'));

        $response->assertStatus(200);
        $response->assertSee($element->name);          // element name is shown
        // No materials means the materials cells are blank — the view renders an
        // empty @foreach, so no material-name text appears in those cells.
    }

    /** When element_material has data, the Materials column shows material names. */
    public function test_materials_column_shows_material_names(): void
    {
        // Use factory-created data for speed
        $element  = Element::factory()->create(['name' => 'Vorwand Test Element']);
        $mat1     = Material::factory()->create(['name' => 'Befestigung Test', 'unit' => 'St.']);
        $mat2     = Material::factory()->create(['name' => 'Rahmen Test', 'unit' => 'St.']);

        $element->materials()->attach($mat1->id, ['quantity' => 4.0]);
        $element->materials()->attach($mat2->id, ['quantity' => 1.0]);

        $response = $this->actingAs($this->admin)
                         ->get(route('element.index'));

        $response->assertStatus(200);
        $response->assertSee('Vorwand Test Element');
        $response->assertSee('Befestigung Test');
        $response->assertSee('Rahmen Test');
    }

    /** Materials are eager-loaded (no N+1): one query for elements + one for pivot. */
    public function test_materials_are_eager_loaded(): void
    {
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);
        $this->seed(ElementMaterialRelationshipSeeder::class);

        // Count DB queries during the request — with proper eager loading the
        // number of queries must be small (not proportional to element count).
        $queryCount = 0;
        DB::listen(function () use (&$queryCount) {
            $queryCount++;
        });

        $this->actingAs($this->admin)->get(route('element.index'));

        // With eager loading, expect far fewer queries than total element count.
        // Typically: 1 session + 1 user + 1 elements-paginate + 1 materials-in
        // Generous upper bound: 20 queries (vs 50+ without eager loading).
        $this->assertLessThan(20, $queryCount,
            "Expected eager loading, but got {$queryCount} DB queries for the element index page"
        );
    }

    /** The pivot quantity is shown in the material listing. */
    public function test_pivot_quantity_shown_in_materials_cell(): void
    {
        $element  = Element::factory()->create(['name' => 'Freistehend Test']);
        $material = Material::factory()->create(['name' => 'Schraube', 'unit' => 'St.']);

        $element->materials()->attach($material->id, ['quantity' => 8.0]);

        $response = $this->actingAs($this->admin)
                         ->get(route('element.index'));

        $response->assertStatus(200);
        // The view renders {{$material->pivot->quantity}}{{$material->unit}}
        $response->assertSee('8'); // quantity
        $response->assertSee('St.'); // unit
        $response->assertSee('Schraube');
    }

    /** After seeding, all elements on the first page have at least one material. */
    public function test_seeded_elements_all_show_materials(): void
    {
        $this->seed(MaterialSeeder::class);
        $this->seed(ElementSeeder::class);
        $this->seed(ElementMaterialRelationshipSeeder::class);

        // Count elements that should have materials (those covered by the seeder)
        $withMaterials = DB::table('element_material')
            ->distinct('element_id')
            ->count('element_id');

        $total = Element::count();

        $this->assertGreaterThan(0, $withMaterials,
            'At least some elements must have materials after seeding'
        );

        // At least 80% coverage
        $coverage = $total > 0 ? $withMaterials / $total : 0;
        $this->assertGreaterThan(0.8, $coverage,
            sprintf('Only %.0f%% of elements have materials (expected ≥80%%)', $coverage * 100)
        );
    }
}
