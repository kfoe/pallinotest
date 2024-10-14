<?php

namespace Tests\Unit\Downloader;

use Tests\TestCase;
use Mockery\MockInterface;
use App\Downloaders\ShopDownloader;
use App\Downloaders\Strategies\CsvDownloadStrategy;
use App\Downloaders\Strategies\JsonDownloadStrategy;

/**
 * @internal
 */
class ShopDownloaderTest extends TestCase
{
    public function testDownload(): void
    {
        $this->mock(JsonDownloadStrategy::class, function (MockInterface $mock) {
            $mock->shouldReceive('download')->once()->with('api/v1/shops.json')->andReturn(['foo' => 'bar']);
        });

        $this->mock(CsvDownloadStrategy::class, function (MockInterface $mock) {
            $mock->shouldReceive('download')->once()->with('shops.csv')->andReturn(['baz' => 'fooBar']);
        });

        /** @var ShopDownloader $downloader */
        $downloader = $this->app->make(ShopDownloader::class);

        $dataset = $downloader->download();

        $this->assertSame(['foo' => 'bar', 'baz' => 'fooBar'], $dataset);
    }
}
