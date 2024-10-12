<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Shop;
use Mockery\MockInterface;
use App\Services\Contracts\ShopService;
use App\Repositories\Contracts\ShopRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @internal
 */
class ShopServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate(): void
    {
        $this->mock(ShopRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('create')->once()->with(['foo' => 'bar'])->andReturn($this->createStub(Shop::class));
        });

        /** @var ShopService $service */
        $service = $this->app->make(ShopService::class);
        $service->create(['foo' => 'bar']);
    }

    public function testUpdate(): void
    {
        /** @var Shop $shop */
        $shop = Shop::factory()->create();

        /** @var ShopService $service */
        $service = $this->app->make(ShopService::class);

        $updatedShop = $service->update($shop, [
            'external_id' => 10,
            'name' => 'Updated shop name',
            'address' => '123 Street',
            'country' => 'UK',
        ]);

        $this->assertTrue($updatedShop);

        $shop->refresh();
        $this->assertEquals(10, $shop->external_id);
        $this->assertEquals('Updated shop name', $shop->name);
        $this->assertEquals('123 Street', $shop->address);
        $this->assertEquals('UK', $shop->country);
    }

    public function testFindByExternalId(): void
    {
        $this->mock(ShopRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('findBy')->once()->with('external_id', 1, ['*'])->andReturn($this->createStub(Shop::class));
        });

        /** @var ShopService $service */
        $service = $this->app->make(ShopService::class);
        $service->findByExternalId(1);
    }

    public function testFindByExternalIdWithCustomColumns(): void
    {
        $this->mock(ShopRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('findBy')->once()->with('external_id', 1, ['foo', 'bar'])->andReturn($this->createStub(Shop::class));
        });

        /** @var ShopService $service */
        $service = $this->app->make(ShopService::class);
        $service->findByExternalId(1, ['foo', 'bar']);
    }

    public function testFindByExternalIdReturnNull(): void
    {
        $this->mock(ShopRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('findBy')->once()->with('external_id', 1, ['*'])->andThrow(ModelNotFoundException::class);
        });

        /** @var ShopService $service */
        $service = $this->app->make(ShopService::class);

        $this->assertNull($service->findByExternalId(1));
    }
}
