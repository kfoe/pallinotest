<?php

namespace Tests\Unit\Resources\Offer;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\Offer;
use App\Http\Resources\Offer\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @internal
 */
class ModelTest extends TestCase
{
    use RefreshDatabase;

    public function testToArray(): void
    {
        $offer = Offer::factory()->create([
            'external_id' => 100,
            'product' => 'Sample Product',
            'price' => 50.30,
            'currency' => 'USD',
            'description' => 'Sample Description',
            'created_at' => $createdAt = now()->toIso8601String(),
            'updated_at' => $updatedAt = now()->addDay()->toIso8601String(),
        ]);

        $resource = new Model($offer);
        $result = $resource->response()->getData(true);

        $this->assertSame([
            'data' => [
                'id' => 100,
                'product' => 'Sample Product',
                'price' => 50.30,
                'currency' => 'USD',
                'description' => 'Sample Description',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ],
        ], $result);
    }

    public function testToArrayWithShopLoaded(): void
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

        /** @var Offer $offer */
        $offer = Offer::factory()->for($shop, 'shop')->create([
            'external_id' => 100,
            'product' => 'Sample Product',
            'price' => 50.30,
            'currency' => 'USD',
            'description' => 'Sample Description',
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ])->load('shop');

        $resource = new Model($offer);
        $result = $resource->response()->getData(true);

        $this->assertSame([
            'data' => [
                'id' => 100,
                'product' => 'Sample Product',
                'price' => 50.30,
                'currency' => 'USD',
                'description' => 'Sample Description',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
                'shop' => [
                    'id' => 789,
                    'name' => 'Test Shop',
                    'address' => '123 Test Street',
                    'country' => 'UK',
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ],
            ],
        ], $result);
    }
}
