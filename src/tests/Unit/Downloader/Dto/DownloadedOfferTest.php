<?php

namespace Tests\Unit\Downloader\Dto;

use Tests\TestCase;
use App\Downloaders\Dto\DownloadedOffer;

/**
 * @internal
 */
class DownloadedOfferTest extends TestCase
{
    public function testNewInstance(): void
    {
        $downloadedOffer = new DownloadedOffer(
            123,
            456,
            'Test Product',
            99.99,
            'USD',
            'This is a test product description.'
        );

        $this->assertSame(123, $downloadedOffer->externalId);
        $this->assertSame(456, $downloadedOffer->externalShopId);
        $this->assertSame('Test Product', $downloadedOffer->product);
        $this->assertSame(99.99, $downloadedOffer->price);
        $this->assertSame('USD', $downloadedOffer->currency);
        $this->assertSame('This is a test product description.', $downloadedOffer->description);
    }

    public function testToArray(): void
    {
        $downloadedOffer = new DownloadedOffer(
            123,
            456,
            'Test Product',
            99.99,
            'USD',
            'This is a test product description.'
        );

        $expectedArray = [
            'external_id' => 123,
            'external_shop_id' => 456,
            'product' => 'Test Product',
            'price' => 99.99,
            'currency' => 'USD',
            'description' => 'This is a test product description.',
        ];

        $this->assertSame($expectedArray, $downloadedOffer->toArray());
    }
}
