<?php

namespace Tests\Feature;

use App\Models\Element;
use App\Models\Material;
use App\Models\MaterialPiece;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Feature tests: changing a Material or MaterialPiece price must invalidate the
 * `elements_with_materials` cache that the new-offer form reads from, so newly
 * created offers immediately reflect the new price. Existing offers keep their
 * own stored Position snapshots and are unaffected by this cache.
 *
 * Run with:
 *   php artisan test --filter=MaterialPriceCacheTest
 */
class MaterialPriceCacheTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'TestAdmin',
            'email'    => 'testadmin@example.com',
            'role'     => 'admin',
            'password' => bcrypt('password'),
        ]);
    }

    /** Warm the cache exactly the way PositionController does. */
    private function warmElementsCache(): void
    {
        Cache::remember('elements_with_materials', 600, function () {
            return Element::with('materials')->get();
        });
    }

    private function cachedMaterialPriceOut(int $materialId): ?float
    {
        $elements = Cache::get('elements_with_materials');
        if ($elements === null) {
            return null;
        }
        foreach ($elements as $element) {
            $material = $element->materials->firstWhere('id', $materialId);
            if ($material) {
                return (float) $material->price_out;
            }
        }

        return null;
    }

    /** Editing a piece price recomputes the parent Material (sum of its pieces). */
    public function test_piece_price_update_propagates_to_parent_material(): void
    {
        $material = Material::factory()->create(['price_in' => 0, 'price_out' => 0, 'total' => 0]);
        $piece    = MaterialPiece::create(['name' => 'Piece A', 'price_in' => 10, 'price_out' => 20]);
        $material->material_pieces()->attach($piece->id);

        $this->actingAs($this->admin)->put(route('material_piece.update', $piece->id), [
            'name'      => 'Piece A',
            'price_in'  => 30,
            'price_out' => 50,
        ]);

        $material->refresh();
        $this->assertSame(30.0, (float) $material->price_in);
        $this->assertSame(50.0, (float) $material->price_out);
        $this->assertSame(50.0, (float) $material->total);
    }

    /** A piece price update clears the cache so new offers see the new price. */
    public function test_piece_price_update_clears_elements_cache(): void
    {
        $element  = Element::factory()->create();
        $material = Material::factory()->create(['price_in' => 0, 'price_out' => 20, 'total' => 20]);
        $piece    = MaterialPiece::create(['name' => 'Piece A', 'price_in' => 0, 'price_out' => 20]);
        $material->material_pieces()->attach($piece->id);
        $element->materials()->attach($material->id, ['quantity' => 1.0]);

        $this->warmElementsCache();
        $this->assertSame(20.0, $this->cachedMaterialPriceOut($material->id), 'precondition: cache holds old price');

        $this->actingAs($this->admin)->put(route('material_piece.update', $piece->id), [
            'name'      => 'Piece A',
            'price_in'  => 0,
            'price_out' => 99,
        ]);

        $this->assertFalse(Cache::has('elements_with_materials'), 'cache must be cleared after a piece price change');

        // A fresh read (what the next new offer does) must show the new price.
        $this->warmElementsCache();
        $this->assertSame(99.0, $this->cachedMaterialPriceOut($material->id));
    }

    /** A material price update clears the cache so new offers see the new price. */
    public function test_material_price_update_clears_elements_cache(): void
    {
        $element  = Element::factory()->create();
        $material = Material::factory()->create(['price_in' => 0, 'price_out' => 20, 'total' => 20]);
        $piece    = MaterialPiece::create(['name' => 'Piece A', 'price_in' => 5, 'price_out' => 40]);
        $material->material_pieces()->attach($piece->id);
        $element->materials()->attach($material->id, ['quantity' => 1.0]);

        $this->warmElementsCache();
        $this->assertSame(20.0, $this->cachedMaterialPriceOut($material->id), 'precondition: cache holds old price');

        $this->actingAs($this->admin)->put(route('material.update', $material->id), [
            'name'          => $material->name,
            'unit'          => 'St.',
            'z_schlosserei' => 0,
            'z_pe'          => 0,
            'z_montage'     => 0,
            'materials'     => [$piece->id],
        ]);

        $this->assertFalse(Cache::has('elements_with_materials'), 'cache must be cleared after a material price change');

        $this->warmElementsCache();
        // Material price_out is recomputed as the sum of its pieces (40).
        $this->assertSame(40.0, $this->cachedMaterialPriceOut($material->id));
    }

    /** Deleting a material clears the cache so it can't linger for new offers. */
    public function test_material_delete_clears_elements_cache(): void
    {
        $element  = Element::factory()->create();
        $material = Material::factory()->create(['price_out' => 20, 'total' => 20]);
        $element->materials()->attach($material->id, ['quantity' => 1.0]);

        $this->warmElementsCache();
        $this->assertSame(20.0, $this->cachedMaterialPriceOut($material->id), 'precondition: cache holds the material');

        $this->actingAs($this->admin)->delete(route('material.destroy', $material->id));

        $this->assertFalse(Cache::has('elements_with_materials'), 'cache must be cleared after a material is deleted');

        // A fresh read (what the next new offer does) must no longer contain it.
        $this->warmElementsCache();
        $this->assertNull($this->cachedMaterialPriceOut($material->id));
    }

    /** Inline AJAX piece edit still returns JSON and clears the cache. */
    public function test_piece_price_ajax_update_returns_json_and_clears_cache(): void
    {
        $material = Material::factory()->create(['price_in' => 0, 'price_out' => 0, 'total' => 0]);
        $piece    = MaterialPiece::create(['name' => 'Piece A', 'price_in' => 1, 'price_out' => 2]);
        $material->material_pieces()->attach($piece->id);

        $this->warmElementsCache();

        $response = $this->actingAs($this->admin)->putJson(route('material_piece.update', $piece->id), [
            'name'      => 'Piece A',
            'price_in'  => 7,
            'price_out' => 8,
        ]);

        $response->assertStatus(200)->assertJson(['status' => 'ok']);
        $this->assertFalse(Cache::has('elements_with_materials'));
    }
}
