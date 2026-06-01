<?php

namespace Tests\Feature;

use App\Models\Element;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests: the order materials are arranged in the Edit Element form is
 * persisted (element_material.sort_order) and is the order Element::materials
 * returns them in — which is the order offers render them in.
 *
 * Run with:
 *   php artisan test --filter=ElementMaterialOrderTest
 */
class ElementMaterialOrderTest extends TestCase
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

    /** Submitting materials in a given row order persists that order. */
    public function test_update_persists_submitted_material_order(): void
    {
        $element = Element::factory()->create();
        $a = Material::factory()->create(['name' => 'Befestigung']);
        $b = Material::factory()->create(['name' => 'WC AP 95']);
        $c = Material::factory()->create(['name' => 'Div. Holz einlagen']);

        // Start in A, B, C order.
        $element->materials()->attach($a->id, ['quantity' => 4, 'sort_order' => 0]);
        $element->materials()->attach($b->id, ['quantity' => 1, 'sort_order' => 1]);
        $element->materials()->attach($c->id, ['quantity' => 0.5, 'sort_order' => 2]);

        // User drags C to the top: submit order is C, A, B.
        $this->actingAs($this->admin)->put(route('element.update', $element->id), [
            'name'       => $element->name,
            'materials'  => [$c->id, $a->id, $b->id],
            'quantities' => [0.5, 4, 1],
        ]);

        $ordered = Element::find($element->id)->materials;

        $this->assertSame(
            [$c->id, $a->id, $b->id],
            $ordered->pluck('id')->all(),
            'materials must come back in the submitted (dragged) order'
        );
        $this->assertSame([0, 1, 2], $ordered->pluck('pivot.sort_order')->map(fn ($v) => (int) $v)->all());
        // Quantities must stay paired with their material after reordering.
        $this->assertSame(0.5, (float) $ordered->firstWhere('id', $c->id)->pivot->quantity);
        $this->assertSame(4.0, (float) $ordered->firstWhere('id', $a->id)->pivot->quantity);
    }

    /** A second reorder overwrites the previous order. */
    public function test_reorder_again_updates_order(): void
    {
        $element = Element::factory()->create();
        $a = Material::factory()->create();
        $b = Material::factory()->create();

        $this->actingAs($this->admin)->put(route('element.update', $element->id), [
            'name'       => $element->name,
            'materials'  => [$a->id, $b->id],
            'quantities' => [1, 1],
        ]);
        $this->assertSame([$a->id, $b->id], Element::find($element->id)->materials->pluck('id')->all());

        // Flip them.
        $this->actingAs($this->admin)->put(route('element.update', $element->id), [
            'name'       => $element->name,
            'materials'  => [$b->id, $a->id],
            'quantities' => [1, 1],
        ]);
        $this->assertSame([$b->id, $a->id], Element::find($element->id)->materials->pluck('id')->all());
    }
}
