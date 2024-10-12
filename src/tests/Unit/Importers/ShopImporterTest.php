<?php

namespace Tests\Unit\Importers;

use Tests\TestCase;
use App\Models\Shop;
use Mockery\MockInterface;
use App\Importer\ShopImporter;
use App\Downloader\ShopDownloader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Downloader\Dtos\DownloadedShop;
use App\Services\Contracts\ShopService;
use App\Downloader\Parsers\ShopDownloaderParser;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @internal
 */
class ShopImporterTest extends TestCase
{
    use RefreshDatabase;

    public function testImportWithCreateShop(): void
    {
        DB::shouldReceive('transaction')->once()->andReturnUsing(function ($callback) {
            $callback();
        });

        Log::shouldReceive('info')->once()->with('Shop created.', ['external_id' => 789]);

        $downloadedDataset = [
            [
                'foo' => 'bar',
            ],
        ];

        $dto = new DownloadedShop(
            789,
            'Test Shop',
            '789 Test Street',
            'UK'
        );

        $downloadedParsedDataset = [
            $dto,
        ];

        $this->mock(ShopDownloader::class, function (MockInterface $mock) use ($downloadedDataset) {
            $mock->shouldReceive('download')->once()->andReturn($downloadedDataset);
        });

        $this->mock(ShopDownloaderParser::class, function (MockInterface $mock) use ($downloadedDataset, $downloadedParsedDataset) {
            $mock->shouldReceive('parse')->once()->with($downloadedDataset)->andReturn($downloadedParsedDataset);
        });

        $this->mock(ShopService::class, function (MockInterface $mock) use ($dto) {
            $mock->shouldReceive('findByExternalId')->once()->with(789)->andReturnNull();
            $mock->shouldReceive('create')->once()->with($dto->toArray())->andReturn($this->createStub(Shop::class));
        });

        /** @var ShopImporter $importer */
        $importer = $this->app->make(ShopImporter::class);
        $this->assertTrue($importer->import());
    }

    public function testImportWithUpdateShop(): void
    {
        DB::shouldReceive('transaction')->once()->andReturnUsing(function ($callback) {
            $callback();
        });

        Log::shouldReceive('info')->once()->with('Shop updated.', ['external_id' => 789]);

        /** @var Shop $shop */
        $shop = Shop::factory()->create(['external_id' => 789]);

        $downloadedDataset = [
            [
                'foo' => 'bar',
            ],
        ];

        $dto = new DownloadedShop(
            789,
            'Test Shop',
            '789 Test Street',
            'UK'
        );

        $downloadedParsedDataset = [
            $dto,
        ];

        $this->mock(ShopDownloader::class, function (MockInterface $mock) use ($downloadedDataset) {
            $mock->shouldReceive('download')->once()->andReturn($downloadedDataset);
        });

        $this->mock(ShopDownloaderParser::class, function (MockInterface $mock) use ($downloadedDataset, $downloadedParsedDataset) {
            $mock->shouldReceive('parse')->once()->with($downloadedDataset)->andReturn($downloadedParsedDataset);
        });

        $this->mock(ShopService::class, function (MockInterface $mock) use ($shop, $dto) {
            $mock->shouldReceive('findByExternalId')->once()->with(789)->andReturn($shop);
            $mock->shouldReceive('update')->once()->with($shop, $dto->toArray())->andReturn(true)();
        });

        /** @var ShopImporter $importer */
        $importer = $this->app->make(ShopImporter::class);
        $this->assertTrue($importer->import());
    }
}
