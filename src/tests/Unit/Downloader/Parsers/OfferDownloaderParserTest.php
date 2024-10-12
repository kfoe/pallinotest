<?php

namespace Tests\Unit\Downloader\Parsers;

use Tests\TestCase;
use App\Downloader\Dtos\DownloadedOffer;
use App\Downloader\Parsers\OfferDownloaderParser;

/**
 * @internal
 */
class OfferDownloaderParserTest extends TestCase
{
    public function testParse(): void
    {
        $data = [
            [
                'id' => 1,
                'shop_id' => 101,
                'product' => 'Sample Product',
                'price' => 19.99,
                'currency' => 'USD',
                'description' => 'This is a sample description.',
            ],
            [
                'id' => 2,
                'shop_id' => 102,
                'product' => 'Another Product',
                'price' => 29.99,
                'currency' => 'EUR',
                'description' => 'Another sample description.',
            ],
        ];

        /** @var OfferDownloaderParser $parser */
        $parser = $this->app->make(OfferDownloaderParser::class);

        $parsedOffers = $parser->parse($data);

        $this->assertCount(2, $parsedOffers);

        collect($parsedOffers)->each(function ($offer) {
            $this->assertInstanceOf(DownloadedOffer::class, $offer);
        });
    }
}
