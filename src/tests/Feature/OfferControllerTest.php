<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @internal
 */
class OfferControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testInvalidInputReturnEmptyDataCollection(): void
    {
        $response = $this->get(route('api.v1.offers', 'foo'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [],
        ]);
    }

    public function testInputIsShopIdReturnOrderedOfferCollectionByPriceAsc(): void
    {
        /** @var Shop $shop */
        $shop = Shop::factory()->has(Offer::factory(10))->create(['external_id' => 10]);

        $anotherShop = Shop::factory()->has(Offer::factory(2))->create(['external_id' => 11]);

        $response = $this->get(route('api.v1.offers', [
            'shopIdOrShopCountryCode' => '10',
            'perPage' => 2,
        ]));

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'product',
                    'price',
                    'currency',
                    'description',
                    'created_at',
                    'updated_at',
                ],
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'active',
                    ],
                ],
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);

        $this->assertSame(1, $response->json('meta.current_page'));
        $this->assertSame(5, $response->json('meta.last_page'));
        $this->assertSame(2, $response->json('meta.per_page'));
        $this->assertSame(10, $response->json('meta.total'));

        $orderedByPrice = $shop->offers()->orderBy('price')->pluck('external_id')->take(2);
        $this->assertEquals($orderedByPrice->toArray(), $response->json('data.*.id'));
    }

    public function testInputIsCountryCodeReturnOfferCollectionWithShop(): void
    {
        /** @var Shop $validShop */
        $validShop = Shop::factory()->create(['country' => 'IT']);

        Offer::factory(10)->for($validShop, 'shop')->create();

        Offer::factory(2)->create();

        $response = $this->get(route('api.v1.offers', [
            'shopIdOrShopCountryCode' => 'IT',
            'perPage' => 2,
        ]));

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'product',
                    'price',
                    'currency',
                    'description',
                    'shop' => [
                        'id',
                        'name',
                        'address',
                        'country',
                        'created_at',
                        'updated_at',
                    ],
                    'created_at',
                    'updated_at',
                ],
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'active',
                    ],
                ],
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);

        $this->assertSame(1, $response->json('meta.current_page'));
        $this->assertSame(5, $response->json('meta.last_page'));
        $this->assertSame(2, $response->json('meta.per_page'));
        $this->assertSame(10, $response->json('meta.total'));
    }

    public function testDefaultPagination(): void
    {
        $response = $this->get(route('api.v1.offers', [
            'shopIdOrShopCountryCode' => '10',
        ]));

        $this->assertSame(15, $response->json('meta.per_page'));
        $this->assertSame(1, $response->json('meta.current_page'));
    }
}
