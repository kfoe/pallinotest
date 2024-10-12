<?php

namespace Tests\Unit\Downloader\Parsers;

use Tests\TestCase;
use App\Downloader\Dtos\DownloadedShop;
use App\Downloader\Parsers\ShopDownloaderParser;

/**
 * @internal
 */
class ShopDownloaderParserTest extends TestCase
{
    public function testParse(): void
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Shop One',
                'address' => '123 Main St',
                'country' => 'UK',
            ],
            [
                'id' => 2,
                'name' => 'Shop Two',
                'address' => '456 Side Ave',
                'country' => 'UK',
            ],
        ];

        /** @var ShopDownloaderParser $parser */
        $parser = $this->app->make(ShopDownloaderParser::class);

        $parsedShops = $parser->parse($data);

        $this->assertCount(2, $parsedShops);

        collect($parsedShops)->each(function ($offer) {
            $this->assertInstanceOf(DownloadedShop::class, $offer);
        });
    }
}
