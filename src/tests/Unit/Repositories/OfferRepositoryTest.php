<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\Offer;
use App\Repositories\Contracts\OfferRepository;
use App\Repositories\Abstracts\AbstractRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @internal
 */
class OfferRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testHasRightContract(): void
    {
        /** @var OfferRepository $repository */
        $repository = $this->app->make(OfferRepository::class);

        $this->assertInstanceOf(AbstractRepository::class, $repository);
    }

    public function testCreate(): void
    {
        /** @var Shop $shop */
        $shop = Shop::factory()->create();

        /** @var OfferRepository $repository */
        $repository = $this->app->make(OfferRepository::class);

        $offer = $repository->create($shop, [
            'external_id' => 1,
            'product' => 'Sample Product',
            'price' => 19.99,
            'currency' => 'USD',
            'description' => 'This is a sample description.',
        ]);

        $this->assertNotNull($offer->id);
        $this->assertSame(1, $offer->external_id);
        $this->assertSame('Sample Product', $offer->product);
        $this->assertSame(19.99, (float) $offer->price);
        $this->assertSame('USD', $offer->currency);
        $this->assertSame('This is a sample description.', $offer->description);
        $this->assertSame($shop->id, $offer->shop->id);
    }

    public function testFindBy(): void
    {
        /** @var Offer $dummyOffer */
        $dummyOffer = Offer::factory()->create([
            'external_id' => 1,
        ]);

        /** @var OfferRepository $repository */
        $repository = $this->app->make(OfferRepository::class);

        $retrievedOffer = $repository->findBy('external_id', 1);
        $this->assertSame(1, $retrievedOffer->external_id);
        $this->assertSame($dummyOffer->id, $retrievedOffer->id);
    }

    public function testFindByWithCustomColumns(): void
    {
        Offer::factory()->create([
            'external_id' => 1,
        ]);

        /** @var OfferRepository $repository */
        $repository = $this->app->make(OfferRepository::class);

        $retrievedOffer = $repository->findBy('external_id', 1, ['external_id']);

        $this->assertSame([
            'external_id' => 1,
        ], $retrievedOffer->toArray());
    }

    public function testFindByThrowModelNotFoundException(): void
    {
        $this->expectException(ModelNotFoundException::class);

        /** @var OfferRepository $repository */
        $repository = $this->app->make(OfferRepository::class);

        $repository->findBy('external_id', 1);
    }
}
