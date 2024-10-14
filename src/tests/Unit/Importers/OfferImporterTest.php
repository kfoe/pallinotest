<?php

namespace Tests\Unit\Importers;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\Offer;
use Mockery\MockInterface;
use App\Importers\OfferImporter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Downloaders\OfferDownloader;
use App\Services\Contracts\ShopService;
use App\Downloaders\Dto\DownloadedOffer;
use App\Services\Contracts\OfferService;
use App\Downloaders\Parsers\OfferDownloaderParser;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @internal
 */
class OfferImporterTest extends TestCase
{
    use RefreshDatabase;

    public function testImportWithCreateOffer(): void
    {
        DB::shouldReceive('transaction')->once()->andReturnUsing(function ($callback) {
            $callback();
        });

        Log::shouldReceive('info')->once()->with('Offer created.', ['external_id' => 123]);

        /** @var Shop $shop */
        $shop = Shop::factory()->create();

        $downloadedDataset = [
            [
                'foo' => 'bar',
            ],
        ];

        $dto = new DownloadedOffer(
            123,
            456,
            'Test Product',
            99.99,
            'USD',
            'This is a test product description.'
        );

        $downloadedParsedDataset = [
            $dto,
        ];

        $this->mock(OfferDownloader::class, function (MockInterface $mock) use ($downloadedDataset) {
            $mock->shouldReceive('download')->once()->andReturn($downloadedDataset);
        });

        $this->mock(OfferDownloaderParser::class, function (MockInterface $mock) use ($downloadedDataset, $downloadedParsedDataset) {
            $mock->shouldReceive('parse')->once()->with($downloadedDataset)->andReturn($downloadedParsedDataset);
        });

        $this->mock(OfferService::class, function (MockInterface $mock) use ($shop, $dto) {
            $mock->shouldReceive('findByExternalId')->once()->with(123)->andReturnNull();
            $mock->shouldReceive('create')->once()->with($shop, $dto->toArray())->andReturn($this->createStub(Offer::class));
        });

        $this->mock(ShopService::class, function (MockInterface $mock) use ($shop) {
            $mock->shouldReceive('findByExternalId')->once()->with(456)->andReturn($shop);
        });

        /** @var OfferImporter $importer */
        $importer = $this->app->make(OfferImporter::class);
        $this->assertTrue($importer->import());
    }

    public function testImportWithUpdateOffer(): void
    {
        DB::shouldReceive('transaction')->once()->andReturnUsing(function ($callback) {
            $callback();
        });

        Log::shouldReceive('info')->once()->with('Offer updated.', ['external_id' => 123]);

        /** @var Shop $shop */
        $shop = Shop::factory()->create();

        /** @var Offer $offer */
        $offer = Offer::factory()->for($shop, 'shop')->create(['external_id' => 123]);

        $downloadedDataset = [
            [
                'foo' => 'bar',
            ],
        ];

        $dto = new DownloadedOffer(
            123,
            456,
            'Test Product',
            99.99,
            'USD',
            'This is a test product description.'
        );

        $downloadedParsedDataset = [
            $dto,
        ];

        $this->mock(OfferDownloader::class, function (MockInterface $mock) use ($downloadedDataset) {
            $mock->shouldReceive('download')->once()->andReturn($downloadedDataset);
        });

        $this->mock(OfferDownloaderParser::class, function (MockInterface $mock) use ($downloadedDataset, $downloadedParsedDataset) {
            $mock->shouldReceive('parse')->once()->with($downloadedDataset)->andReturn($downloadedParsedDataset);
        });

        $this->mock(OfferService::class, function (MockInterface $mock) use ($offer, $dto) {
            $mock->shouldReceive('findByExternalId')->once()->with(123)->andReturn($offer);
            $mock->shouldReceive('update')->once()->with($offer, $dto->toArray())->andReturn(true);
        });

        /** @var OfferImporter $importer */
        $importer = $this->app->make(OfferImporter::class);
        $this->assertTrue($importer->import());
    }

    public function testImportWithUpdateOfferLogErrorMessage(): void
    {
        DB::shouldReceive('transaction')->once()->andReturnUsing(function ($callback) {
            $callback();
        });

        Log::shouldReceive('error')->once()->with('Unable to update Offer.', ['external_id' => 123]);

        /** @var Shop $shop */
        $shop = Shop::factory()->create();

        /** @var Offer $offer */
        $offer = Offer::factory()->for($shop, 'shop')->create(['external_id' => 123]);

        $downloadedDataset = [
            [
                'foo' => 'bar',
            ],
        ];

        $dto = new DownloadedOffer(
            123,
            456,
            'Test Product',
            99.99,
            'USD',
            'This is a test product description.'
        );

        $downloadedParsedDataset = [
            $dto,
        ];

        $this->mock(OfferDownloader::class, function (MockInterface $mock) use ($downloadedDataset) {
            $mock->shouldReceive('download')->once()->andReturn($downloadedDataset);
        });

        $this->mock(OfferDownloaderParser::class, function (MockInterface $mock) use ($downloadedDataset, $downloadedParsedDataset) {
            $mock->shouldReceive('parse')->once()->with($downloadedDataset)->andReturn($downloadedParsedDataset);
        });

        $this->mock(OfferService::class, function (MockInterface $mock) use ($offer, $dto) {
            $mock->shouldReceive('findByExternalId')->once()->with(123)->andReturn($offer);
            $mock->shouldReceive('update')->once()->with($offer, $dto->toArray())->andReturn(false);
        });

        /** @var OfferImporter $importer */
        $importer = $this->app->make(OfferImporter::class);
        $this->assertTrue($importer->import());
    }

    public function testImportWithoutShopWriteWarningLog(): void
    {
        DB::shouldReceive('transaction')->once()->andReturnUsing(function ($callback) {
            $callback();
        });

        Log::shouldReceive('warning')->once()->with('Shop not found for offer.', ['external_shop_id' => 456]);

        $downloadedDataset = [
            [
                'foo' => 'bar',
            ],
        ];

        $dto = new DownloadedOffer(
            123,
            456,
            'Test Product',
            99.99,
            'USD',
            'This is a test product description.'
        );

        $downloadedParsedDataset = [
            $dto,
        ];

        $this->mock(OfferDownloader::class, function (MockInterface $mock) use ($downloadedDataset) {
            $mock->shouldReceive('download')->once()->andReturn($downloadedDataset);
        });

        $this->mock(OfferDownloaderParser::class, function (MockInterface $mock) use ($downloadedDataset, $downloadedParsedDataset) {
            $mock->shouldReceive('parse')->once()->with($downloadedDataset)->andReturn($downloadedParsedDataset);
        });

        $this->mock(OfferService::class, function (MockInterface $mock) {
            $mock->shouldReceive('findByExternalId')->once()->with(123)->andReturnNull();
            $mock->shouldReceive('create')->never();
            $mock->shouldReceive('update')->never();
        });

        $this->mock(ShopService::class, function (MockInterface $mock) {
            $mock->shouldReceive('findByExternalId')->once()->with(456)->andReturnNull();
        });

        /** @var OfferImporter $importer */
        $importer = $this->app->make(OfferImporter::class);
        $this->assertTrue($importer->import());
    }
}
