<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Models\Shop;
use App\Repositories\Contracts\ShopRepository;
use App\Repositories\Abstracts\AbstractRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @internal
 */
class ShopRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testHasRightContract(): void
    {
        /** @var ShopRepository $repository */
        $repository = $this->app->make(ShopRepository::class);

        $this->assertInstanceOf(AbstractRepository::class, $repository);
    }

    public function testCreate(): void
    {
        /** @var ShopRepository $repository */
        $repository = $this->app->make(ShopRepository::class);

        $shop = $repository->create([
            'external_id' => 2,
            'name' => 'Shop Two',
            'address' => '456 Side Ave',
            'country' => 'UK',
        ]);

        $this->assertNotNull($shop->id);
        $this->assertSame(2, $shop->external_id);
        $this->assertSame('Shop Two', $shop->name);
        $this->assertSame('456 Side Ave', $shop->address);
        $this->assertSame('UK', $shop->country);
    }

    public function testFindBy(): void
    {
        /** @var Shop $dummyShop */
        $dummyShop = Shop::factory()->create([
            'external_id' => 1,
        ]);

        /** @var ShopRepository $repository */
        $repository = $this->app->make(ShopRepository::class);

        $retrievedShop = $repository->findBy('external_id', 1);
        $this->assertSame(1, $retrievedShop->external_id);
        $this->assertSame($dummyShop->id, $retrievedShop->id);
    }

    public function testFindByWithCustomColumns(): void
    {
        Shop::factory()->create([
            'external_id' => 1,
        ]);

        /** @var ShopRepository $repository */
        $repository = $this->app->make(ShopRepository::class);

        $retrievedShop = $repository->findBy('external_id', 1, ['external_id']);

        $this->assertSame([
            'external_id' => 1,
        ], $retrievedShop->toArray());
    }

    public function testFindByThrowModelNotFoundException(): void
    {
        $this->expectException(ModelNotFoundException::class);

        /** @var ShopRepository $repository */
        $repository = $this->app->make(ShopRepository::class);

        $repository->findBy('external_id', 1);
    }
}
