<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Offert;
use App\Models\Client;
use App\Models\Position;
use App\Models\Element;
use App\Models\Organigram;
use App\Models\GroupElement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

/**
 * Feature tests for the position auto-save endpoint (POST /position/auto-save).
 *
 * This covers the data-persistence path that the browser calls:
 *   - when the debounce timer fires after any input change
 *   - when the user navigates away (visibilitychange / pagehide with keepalive)
 *   - when the "Allgemeine Parameter" popup triggers doAutoSaveAndWait()
 *
 * Run: php artisan test --filter=PositionAutoSaveTest
 */
class PositionAutoSaveTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Offert $offert;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'username' => 'TestUser',
            'email'    => 'testuser@example.com',
            'role'     => 'admin',
            'password' => bcrypt('password'),
        ]);

        $client = Client::create([
            'name'    => 'Test Client',
            'email'   => 'client@test.com',
            'number'  => '000',
            'address' => 'Test Street 1',
        ]);

        $this->offert = Offert::create([
            'type'               => 'standard',
            'user_sign'          => 'T.U.',
            'status'             => 'active',
            'create_date'        => now()->toDateString(),
            'validity'           => '30 Tage',
            'client_sign'        => 'REF-001',
            'object'             => 'Test Object',
            'city'               => 'Zürich',
            'service'            => '2 Wochen',
            'payment_conditions' => '30 Tage',
            'difficulty'         => 1,
            'material'           => 1,
            'labor_price'        => 60,
            'user_id'            => $this->user->id,
            'client_id'          => $client->id,
        ]);
    }

    // -------------------------------------------------------------------------
    // Auth guard
    // -------------------------------------------------------------------------

    public function test_guest_cannot_auto_save(): void
    {
        $response = $this->postJson(route('position.auto-save'), [
            'offert_id' => $this->offert->id,
            'index'     => 0,
        ]);

        $response->assertStatus(401);
    }

    // -------------------------------------------------------------------------
    // Creating a new position via auto-save
    // -------------------------------------------------------------------------

    public function test_auto_save_creates_position_when_none_exists(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson(route('position.auto-save'), [
            'offert_id'        => $this->offert->id,
            'index'            => 0,
            'description'      => 'Vorwand Grundrahmen',
            'description2'     => '',
            'blocktype'        => 'Vorwand-Teilhoch',
            'b'                => 120,
            'h'                => 210,
            't'                => 15,
            'quantity'         => 2,
            'totalProTypPrice' => 800.00,
            'discountedTotal'  => 640.00,
            'percentage'       => 20,
            'price_out'        => 600.00,
            'zeit_cost'        => 200.00,
            'material_costo'   => 400.00,
            'material_profit'  => 200.00,
            'zeit_costo'       => 100.00,
            'zeit_profit'      => 100.00,
            'costo_total'      => 500.00,
            'profit_total'     => 140.00,
            'selected_elements'       => [],
            'selected_group_elements' => [],
            'selected_organigrams'    => [],
            'element_quantity'        => [],
            'element_optional'        => [],
            'material_quantity'       => [],
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertNotNull($response->json('position_id'));

        $position = Position::find($response->json('position_id'));
        $this->assertNotNull($position);
        $this->assertEquals('Vorwand Grundrahmen', $position->description);
        $this->assertEquals('Vorwand-Teilhoch', $position->blocktype);
        $this->assertEquals(2, $position->quantity);
        $this->assertEqualsWithDelta(800.00, $position->price_brutto, 0.01);
        $this->assertEqualsWithDelta(640.00, $position->price_discount, 0.01);
        $this->assertEqualsWithDelta(20.00, $position->discount, 0.01);

        // The position should be attached to the offert
        $this->assertTrue($this->offert->positions->contains($position->id));
    }

    // -------------------------------------------------------------------------
    // Updating an existing position via auto-save
    // -------------------------------------------------------------------------

    public function test_auto_save_updates_existing_position_by_id(): void
    {
        $this->actingAs($this->user);

        $position = Position::create([
            'description'   => 'Old Description',
            'blocktype'     => null,
            'b'             => 0,
            'h'             => 0,
            't'             => 0,
            'quantity'      => 1,
            'position_number' => 1,
            'price_brutto'  => 0,
            'price_discount' => 0,
            'discount'      => 0,
            'material_brutto' => 0,
            'zeit_brutto'   => 0,
            'material_costo' => 0,
            'material_profit' => 0,
            'ziet_costo'    => 0,
            'ziet_profit'   => 0,
            'costo_total'   => 0,
            'profit_total'  => 0,
        ]);
        $this->offert->positions()->attach($position->id);

        $response = $this->postJson(route('position.auto-save'), [
            'offert_id'        => $this->offert->id,
            'position_id'      => $position->id,
            'index'            => 0,
            'description'      => 'Updated Description',
            'description2'     => 'Note',
            'blocktype'        => 'Freistehend-Raumhoch',
            'b'                => 90,
            'h'                => 220,
            't'                => 12,
            'quantity'         => 3,
            'totalProTypPrice' => 1200.00,
            'discountedTotal'  => 960.00,
            'percentage'       => 20,
            'price_out'        => 900.00,
            'zeit_cost'        => 300.00,
            'material_costo'   => 600.00,
            'material_profit'  => 300.00,
            'zeit_costo'       => 150.00,
            'zeit_profit'      => 150.00,
            'costo_total'      => 750.00,
            'profit_total'     => 210.00,
            'selected_elements'       => [],
            'selected_group_elements' => [],
            'selected_organigrams'    => [],
            'element_quantity'        => [],
            'element_optional'        => [],
            'material_quantity'       => [],
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertEquals($position->id, $response->json('position_id'));

        $position->refresh();
        $this->assertEquals('Updated Description', $position->description);
        $this->assertEquals('Freistehend-Raumhoch', $position->blocktype);
        $this->assertEquals(3, $position->quantity);
        $this->assertEqualsWithDelta(1200.00, $position->price_brutto, 0.01);
        $this->assertEqualsWithDelta(960.00, $position->price_discount, 0.01);
    }

    // -------------------------------------------------------------------------
    // Element relationships are synced
    // -------------------------------------------------------------------------

    public function test_auto_save_syncs_selected_elements(): void
    {
        $this->actingAs($this->user);

        $organigram  = Organigram::create(['name' => 'Rahme']);
        $groupElement = GroupElement::create(['name' => 'Grundrahme']);
        $groupElement->organigrams()->attach($organigram->id);

        $element = Element::create(['name' => 'Vorwand Grundrahme']);
        $element->group_elements()->attach($groupElement->id);

        $position = Position::create([
            'description' => '', 'blocktype' => null, 'b' => 0, 'h' => 0, 't' => 0,
            'quantity' => 1, 'position_number' => 1,
            'price_brutto' => 0, 'price_discount' => 0, 'discount' => 0,
            'material_brutto' => 0, 'zeit_brutto' => 0, 'material_costo' => 0,
            'material_profit' => 0, 'ziet_costo' => 0, 'ziet_profit' => 0,
            'costo_total' => 0, 'profit_total' => 0,
        ]);
        $this->offert->positions()->attach($position->id);

        $response = $this->postJson(route('position.auto-save'), [
            'offert_id'   => $this->offert->id,
            'position_id' => $position->id,
            'index'       => 0,
            'description' => '', 'blocktype' => null, 'b' => 0, 'h' => 0, 't' => 0,
            'quantity'    => 1,
            'totalProTypPrice' => 0, 'discountedTotal' => 0, 'percentage' => 0,
            'price_out' => 0, 'zeit_cost' => 0, 'material_costo' => 0,
            'material_profit' => 0, 'zeit_costo' => 0, 'zeit_profit' => 0,
            'costo_total' => 0, 'profit_total' => 0,
            'selected_elements'       => [(string) $element->id],
            'selected_group_elements' => [(string) $groupElement->id],
            'selected_organigrams'    => [(string) $organigram->id],
            'element_quantity'        => [(string) $element->id => '2'],
            'element_optional'        => [(string) $element->id => 0],
            'material_quantity'       => [],
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $position->refresh();
        $this->assertTrue($position->elements->contains($element->id));
        $this->assertTrue($position->group_elements->contains($groupElement->id));
        $this->assertTrue($position->organigrams->contains($organigram->id));

        $pivot = $position->elements()->where('elements.id', $element->id)->first();
        $this->assertNotNull($pivot);
        $this->assertEquals(2, (int) $pivot->pivot->quantity);
    }

    // -------------------------------------------------------------------------
    // Material quantities are persisted
    // -------------------------------------------------------------------------

    public function test_auto_save_persists_material_quantities(): void
    {
        $this->actingAs($this->user);

        $element  = Element::create(['name' => 'Test Element']);
        $material = \App\Models\Material::create([
            'name'         => 'Test Material',
            'unit'         => 'St.',
            'price_in'     => 10.00,
            'price_out'    => 15.00,
            'z_schlosserei' => 0,
            'z_pe'         => 0,
            'z_montage'    => 0,
            'z_total'      => 0,
            'zeit_cost'    => 5.00,
            'total_arbeit' => 0,
            'total'        => 15.00,
        ]);
        $element->materials()->attach($material->id, ['quantity' => 1]);

        $position = Position::create([
            'description' => '', 'blocktype' => null, 'b' => 0, 'h' => 0, 't' => 0,
            'quantity' => 1, 'position_number' => 1,
            'price_brutto' => 0, 'price_discount' => 0, 'discount' => 0,
            'material_brutto' => 0, 'zeit_brutto' => 0, 'material_costo' => 0,
            'material_profit' => 0, 'ziet_costo' => 0, 'ziet_profit' => 0,
            'costo_total' => 0, 'profit_total' => 0,
        ]);
        $this->offert->positions()->attach($position->id);

        $response = $this->postJson(route('position.auto-save'), [
            'offert_id'   => $this->offert->id,
            'position_id' => $position->id,
            'index'       => 0,
            'description' => '', 'blocktype' => null, 'b' => 0, 'h' => 0, 't' => 0,
            'quantity'    => 1,
            'totalProTypPrice' => 0, 'discountedTotal' => 0, 'percentage' => 0,
            'price_out' => 0, 'zeit_cost' => 0, 'material_costo' => 0,
            'material_profit' => 0, 'zeit_costo' => 0, 'zeit_profit' => 0,
            'costo_total' => 0, 'profit_total' => 0,
            'selected_elements'       => [],
            'selected_group_elements' => [],
            'selected_organigrams'    => [],
            'element_quantity'        => [],
            'element_optional'        => [],
            'material_quantity'       => [
                (string) $element->id => [
                    (string) $material->id => '3.5',
                ],
            ],
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $pm = DB::table('position_materials')
            ->where('position_id', $position->id)
            ->where('element_id', $element->id)
            ->where('material_id', $material->id)
            ->first();

        $this->assertNotNull($pm, 'PositionMaterial row should have been created');
        $this->assertEqualsWithDelta(3.5, $pm->quantity, 0.001);
    }

    // -------------------------------------------------------------------------
    // Returns 404 when offert not found
    // -------------------------------------------------------------------------

    public function test_auto_save_returns_404_for_unknown_offert(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson(route('position.auto-save'), [
            'offert_id' => 999999,
            'index'     => 0,
        ]);

        $response->assertStatus(404)->assertJson(['success' => false]);
    }

    // -------------------------------------------------------------------------
    // Position ID from a different offert is not reused (isolation)
    // -------------------------------------------------------------------------

    public function test_auto_save_does_not_overwrite_position_from_different_offert(): void
    {
        $this->actingAs($this->user);

        $client2 = Client::create(['name' => 'Other Client', 'email' => 'other@test.com', 'number' => '001', 'address' => '']);
        $offert2 = Offert::create([
            'type' => 'standard', 'user_sign' => 'X', 'status' => 'active',
            'create_date' => now()->toDateString(), 'validity' => '30 Tage',
            'client_sign' => 'REF-002', 'object' => 'Other', 'city' => 'Bern',
            'service' => '1 Woche', 'payment_conditions' => '30 Tage',
            'difficulty' => 1, 'material' => 1, 'labor_price' => 60,
            'user_id' => $this->user->id, 'client_id' => $client2->id,
        ]);

        $foreignPosition = Position::create([
            'description' => 'Foreign', 'blocktype' => null, 'b' => 0, 'h' => 0, 't' => 0,
            'quantity' => 1, 'position_number' => 1,
            'price_brutto' => 0, 'price_discount' => 0, 'discount' => 0,
            'material_brutto' => 0, 'zeit_brutto' => 0, 'material_costo' => 0,
            'material_profit' => 0, 'ziet_costo' => 0, 'ziet_profit' => 0,
            'costo_total' => 0, 'profit_total' => 0,
        ]);
        $offert2->positions()->attach($foreignPosition->id);

        // Post with our offert_id but another offert's position_id
        $this->postJson(route('position.auto-save'), [
            'offert_id'   => $this->offert->id,
            'position_id' => $foreignPosition->id, // belongs to offert2, not $this->offert
            'index'       => 0,
            'description' => 'Attempted Overwrite',
            'blocktype' => null, 'b' => 0, 'h' => 0, 't' => 0, 'quantity' => 1,
            'totalProTypPrice' => 0, 'discountedTotal' => 0, 'percentage' => 0,
            'price_out' => 0, 'zeit_cost' => 0, 'material_costo' => 0,
            'material_profit' => 0, 'zeit_costo' => 0, 'zeit_profit' => 0,
            'costo_total' => 0, 'profit_total' => 0,
            'selected_elements' => [], 'selected_group_elements' => [],
            'selected_organigrams' => [], 'element_quantity' => [],
            'element_optional' => [], 'material_quantity' => [],
        ]);

        // The foreign position must not have been modified
        $foreignPosition->refresh();
        $this->assertEquals('Foreign', $foreignPosition->description,
            'Auto-save must not overwrite positions that belong to a different offert');
    }
}
