<?php

namespace Tests\Unit\Downloader;

use Tests\TestCase;
use Mockery\MockInterface;
use App\Downloader\OfferDownloader;
use App\Downloader\Strategies\JsonDownloadStrategy;

/**
 * @internal
 */
class OfferDownloaderTest extends TestCase
{
    public function testDownload(): void
    {
        $this->mock(JsonDownloadStrategy::class, function (MockInterface $mock) {
            $mock->shouldReceive('download')->once()->with('api/v1/offers.json')->andReturn(['foo' => 'bar']);
        });

        /** @var OfferDownloader $downloader */
        $downloader = $this->app->make(OfferDownloader::class);

        $dataset = $downloader->download();

        $this->assertSame(['foo' => 'bar'], $dataset);
    }
}
