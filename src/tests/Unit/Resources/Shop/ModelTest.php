<?php

namespace Tests\Unit\Resources\Shop;

use Tests\TestCase;
use App\Models\Shop;
use App\Http\Resources\Shop\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @internal
 */
class ModelTest extends TestCase
{
    use RefreshDatabase;

    public function testToArray(): void
    {
        $createdAt = now()->toIso8601String();
        $updatedAt = now()->addDay()->toIso8601String();

        /** @var Shop $shop */
        $shop = Shop::factory()->create([
            'external_id' => 789,
            'name' => 'Test Shop',
            'address' => '123 Test Street',
            'country' => 'UK',
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ]);

        $resource = new Model($shop);
        $result = $resource->response()->getData(true);

        $this->assertSame([
            'data' => [
                'id' => 789,
                'name' => 'Test Shop',
                'address' => '123 Test Street',
                'country' => 'UK',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ],
        ], $result);
    }
}
