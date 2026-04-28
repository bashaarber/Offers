<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Offert;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PositionFlowTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(): User
    {
        return User::create([
            'username' => 'user_' . uniqid(),
            'email' => uniqid('user_', true) . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'seller',
        ]);
    }

    private function createOffert(User $user): Offert
    {
        $client = Client::create([
            'name' => 'Test Client',
            'email' => 'client@example.com',
            'number' => '12345',
            'address' => 'Test Street 1',
        ]);

        return Offert::create([
            'type' => 'client',
            'user_sign' => 'QA',
            'status' => 'Neu',
            'create_date' => now()->toDateString(),
            'validity' => '30 Tage',
            'client_sign' => 'REF',
            'finish_date' => now()->toDateString(),
            'object' => 'Obj',
            'city' => 'City',
            'service' => 'Service',
            'payment_conditions' => 'Netto',
            'difficulty' => 1,
            'material' => 1,
            'labor_price' => '80',
            'default_rabatt' => 0,
            'client_id' => $client->id,
            'user_id' => $user->id,
        ]);
    }

    private function autoSavePayload(int $offertId, int $index): array
    {
        return [
            'offert_id' => $offertId,
            'index' => $index,
            'description' => 'Pos',
            'description2' => '',
            'blocktype' => null,
            'b' => null,
            'h' => null,
            't' => null,
            'quantity' => 1,
            'totalProTypPrice' => 100,
            'discountedTotal' => 100,
            'percentage' => 0,
            'price_out' => 50,
            'zeit_cost' => 50,
            'material_costo' => 25,
            'material_profit' => 25,
            'zeit_costo' => 20,
            'zeit_profit' => 30,
            'costo_total' => 45,
            'profit_total' => 55,
        ];
    }

    public function test_add_position_once_creates_exactly_one_position(): void
    {
        $user = $this->createUser();
        $offert = $this->createOffert($user);

        $this->actingAs($user)
            ->postJson(route('position.auto-save'), $this->autoSavePayload($offert->id, 0))
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseCount('positions', 1);
        $this->assertDatabaseHas('offert_position', [
            'offert_id' => $offert->id,
            'position_id' => Position::first()->id,
        ]);
    }

    public function test_rapid_double_add_updates_same_position_instead_of_creating_duplicate(): void
    {
        $user = $this->createUser();
        $offert = $this->createOffert($user);
        $payload = $this->autoSavePayload($offert->id, 0);

        $this->actingAs($user)->postJson(route('position.auto-save'), $payload)->assertOk();
        $this->actingAs($user)->postJson(route('position.auto-save'), $payload)->assertOk();

        $this->assertDatabaseCount('positions', 1);
        $this->assertDatabaseCount('offert_position', 1);
    }

    public function test_remove_position_keeps_numbering_sequential(): void
    {
        $user = $this->createUser();
        $offert = $this->createOffert($user);

        $positions = collect([1, 2, 3])->map(function (int $number) use ($offert) {
            $position = Position::create([
                'description' => 'Pos ' . $number,
                'description2' => '',
                'blocktype' => null,
                'b' => null,
                'h' => null,
                't' => null,
                'quantity' => 1,
                'price_brutto' => 100,
                'price_discount' => 100,
                'discount' => 0,
                'material_brutto' => 50,
                'zeit_brutto' => 50,
                'material_costo' => 25,
                'material_profit' => 25,
                'ziet_costo' => 20,
                'ziet_profit' => 30,
                'costo_total' => 45,
                'profit_total' => 55,
                'position_number' => $number,
                'is_optional' => false,
            ]);
            $position->offerts()->attach($offert->id);
            return $position;
        });

        $this->actingAs($user)
            ->delete(route('position.destroy', $positions[1]->id))
            ->assertRedirect();

        $remainingNumbers = Position::whereHas('offerts', fn ($q) => $q->where('id', $offert->id))
            ->orderBy('position_number')
            ->pluck('position_number')
            ->all();

        $this->assertSame([1, 2], $remainingNumbers);
    }

    public function test_reorder_positions_updates_all_orders_atomically(): void
    {
        $user = $this->createUser();
        $offert = $this->createOffert($user);

        $p1 = Position::create([
            'description' => 'Pos 1',
            'description2' => '',
            'blocktype' => null,
            'b' => null,
            'h' => null,
            't' => null,
            'quantity' => 1,
            'price_brutto' => 100,
            'price_discount' => 100,
            'discount' => 0,
            'material_brutto' => 50,
            'zeit_brutto' => 50,
            'material_costo' => 25,
            'material_profit' => 25,
            'ziet_costo' => 20,
            'ziet_profit' => 30,
            'costo_total' => 45,
            'profit_total' => 55,
            'position_number' => 1,
            'is_optional' => false,
        ]);
        $p2 = Position::create([
            'description' => 'Pos 2',
            'description2' => '',
            'blocktype' => null,
            'b' => null,
            'h' => null,
            't' => null,
            'quantity' => 1,
            'price_brutto' => 100,
            'price_discount' => 100,
            'discount' => 0,
            'material_brutto' => 50,
            'zeit_brutto' => 50,
            'material_costo' => 25,
            'material_profit' => 25,
            'ziet_costo' => 20,
            'ziet_profit' => 30,
            'costo_total' => 45,
            'profit_total' => 55,
            'position_number' => 2,
            'is_optional' => false,
        ]);
        $p3 = Position::create([
            'description' => 'Pos 3',
            'description2' => '',
            'blocktype' => null,
            'b' => null,
            'h' => null,
            't' => null,
            'quantity' => 1,
            'price_brutto' => 100,
            'price_discount' => 100,
            'discount' => 0,
            'material_brutto' => 50,
            'zeit_brutto' => 50,
            'material_costo' => 25,
            'material_profit' => 25,
            'ziet_costo' => 20,
            'ziet_profit' => 30,
            'costo_total' => 45,
            'profit_total' => 55,
            'position_number' => 3,
            'is_optional' => false,
        ]);

        $p1->offerts()->attach($offert->id);
        $p2->offerts()->attach($offert->id);
        $p3->offerts()->attach($offert->id);

        $this->actingAs($user)
            ->postJson(route('position.updateOrder'), [
                'orders' => [
                    ['position_id' => $p3->id, 'order' => 1],
                    ['position_id' => $p1->id, 'order' => 2],
                    ['position_id' => $p2->id, 'order' => 3],
                ],
            ])->assertOk()->assertJson(['success' => true]);

        $this->assertSame(1, (int) $p3->fresh()->position_number);
        $this->assertSame(2, (int) $p1->fresh()->position_number);
        $this->assertSame(3, (int) $p2->fresh()->position_number);
    }

    public function test_add_new_query_creates_empty_position_and_redirects_to_edit(): void
    {
        $user = $this->createUser();
        $offert = $this->createOffert($user);

        $response = $this->actingAs($user)->get(route('position.create', ['index' => 0, 'offert_id' => $offert->id, 'add_new' => 1]));

        $response->assertRedirect();

        $position = Position::whereHas('offerts', fn ($q) => $q->where('id', $offert->id))
            ->orderByDesc('id')
            ->first();

        $this->assertNotNull($position);
        $this->assertSame('', (string) $position->description);
        $this->assertSame(0.0, (float) $position->price_brutto);
        $this->assertSame(1, (int) $position->quantity);
    }

    public function test_create_empty_returns_payload_contract_and_creates_position(): void
    {
        $user = $this->createUser();
        $offert = $this->createOffert($user);

        $response = $this->actingAs($user)->postJson(route('position.create-empty'), [
            'offert_id' => $offert->id,
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'position_id',
                'position_number',
                'edit_url',
                'request_id',
            ])
            ->assertJson([
                'success' => true,
                'position_number' => 1,
            ]);

        $positionId = (int) $response->json('position_id');
        $this->assertDatabaseHas('positions', ['id' => $positionId, 'position_number' => 1]);
        $this->assertDatabaseHas('offert_position', [
            'offert_id' => $offert->id,
            'position_id' => $positionId,
        ]);
    }

    public function test_create_empty_returns_422_for_invalid_offert_id(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->postJson(route('position.create-empty'), ['offert_id' => 0])
            ->assertStatus(422)
            ->assertJsonStructure(['success', 'message', 'request_id'])
            ->assertJson(['success' => false, 'message' => 'Invalid offert_id']);
    }

    public function test_create_empty_returns_404_when_offert_does_not_exist(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->postJson(route('position.create-empty'), ['offert_id' => 999999])
            ->assertStatus(404)
            ->assertJsonStructure(['success', 'message', 'request_id'])
            ->assertJson(['success' => false, 'message' => 'Offer not found']);
    }

    public function test_create_empty_returns_423_when_offert_locked_by_other_user(): void
    {
        if (! Schema::hasColumn('offerts', 'locked_by') || ! Schema::hasColumn('offerts', 'locked_at')) {
            $this->markTestSkipped('Lock columns are not present in this test schema.');
        }

        $user = $this->createUser();
        $otherUser = $this->createUser();
        $offert = $this->createOffert($otherUser);
        $offert->update([
            'locked_by' => $otherUser->id,
            'locked_at' => now(),
        ]);

        $this->actingAs($user)
            ->postJson(route('position.create-empty'), ['offert_id' => $offert->id])
            ->assertStatus(423)
            ->assertJsonStructure(['success', 'message', 'request_id'])
            ->assertJson(['success' => false]);
    }

    public function test_edit_redirects_with_error_when_position_has_no_offert_link(): void
    {
        $user = $this->createUser();
        $position = Position::create([
            'description' => 'Orphan',
            'description2' => '',
            'blocktype' => null,
            'b' => null,
            'h' => null,
            't' => null,
            'quantity' => 1,
            'price_brutto' => 0,
            'price_discount' => 0,
            'discount' => 0,
            'material_brutto' => 0,
            'zeit_brutto' => 0,
            'material_costo' => 0,
            'material_profit' => 0,
            'ziet_costo' => 0,
            'ziet_profit' => 0,
            'costo_total' => 0,
            'profit_total' => 0,
            'position_number' => 1,
            'is_optional' => false,
        ]);

        $this->actingAs($user)
            ->get(route('position.edit', $position->id))
            ->assertRedirect(route('offert.index'));
    }
}

