<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @internal
 */
class OfferTest extends TestCase
{
    use RefreshDatabase;

    public function testPriceAttribute(): void
    {
        /** @var Offer $offer */
        $offer = Offer::factory()->create([
            'price' => 10.40,
        ]);

        $this->assertEquals(1040, $offer->getRawOriginal('price'));
        $this->assertEquals(10.40, $offer->price);
    }
}
