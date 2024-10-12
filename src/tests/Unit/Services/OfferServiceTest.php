<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\Offer;
use Mockery\MockInterface;
use App\Services\Contracts\OfferService;
use App\Repositories\Contracts\OfferRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @internal
 */
class OfferServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate(): void
    {
        /** @var Shop $shop */
        $shop = Shop::factory()->create();

        $this->mock(OfferRepository::class, function (MockInterface $mock) use ($shop) {
            $mock->shouldReceive('create')->once()->with($shop, ['foo' => 'bar'])->andReturn($this->createStub(Offer::class));
        });

        /** @var OfferService $service */
        $service = $this->app->make(OfferService::class);
        $service->create($shop, ['foo' => 'bar']);
    }

    public function testUpdate(): void
    {
        /** @var Offer $offer */
        $offer = Offer::factory()->create();

        /** @var OfferService $service */
        $service = $this->app->make(OfferService::class);

        $status = $service->update($offer, [
            'external_id' => 10,
            'product' => 'updated product',
            'price' => 100.40,
            'currency' => 'EUR',
            'description' => 'updated description',
        ]);

        $this->assertTrue($status);

        $offer->refresh();
        $this->assertSame(10, $offer->external_id);
        $this->assertSame('updated product', $offer->product);
        $this->assertSame(100.40, (float) $offer->price);
        $this->assertSame('EUR', $offer->currency);
        $this->assertSame('updated description', $offer->description);
    }

    public function testFindByExternalId(): void
    {
        $this->mock(OfferRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('findBy')->once()->with('external_id', 1, ['*'])->andReturn($this->createStub(Offer::class));
        });

        /** @var OfferService $service */
        $service = $this->app->make(OfferService::class);
        $service->findByExternalId(1);
    }

    public function testFindByExternalIdWithCustomColumns(): void
    {
        $this->mock(OfferRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('findBy')->once()->with('external_id', 1, ['foo', 'bar'])->andReturn($this->createStub(Offer::class));
        });

        /** @var OfferService $service */
        $service = $this->app->make(OfferService::class);
        $service->findByExternalId(1, ['foo', 'bar']);
    }

    public function testFindByExternalIdReturnNull(): void
    {
        $this->mock(OfferRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('findBy')->once()->with('external_id', 1, ['*'])->andThrow(ModelNotFoundException::class);
        });

        /** @var OfferService $service */
        $service = $this->app->make(OfferService::class);

        $this->assertNull($service->findByExternalId(1));
    }
}
