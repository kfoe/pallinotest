<?php

namespace App\Importer;

use Psr\Log\LoggerInterface;
use App\Downloader\ShopDownloader;
use Illuminate\Support\Facades\DB;
use App\Importer\Contracts\Importer;
use App\Downloader\Dtos\DownloadedShop;
use App\Services\Contracts\ShopService;
use App\Downloader\Parsers\ShopDownloaderParser;

class ShopImporter implements Importer
{
    public function __construct(
        private readonly ShopDownloader $shopDownloader,
        private readonly ShopDownloaderParser $shopDownloaderParser,
        private readonly ShopService $shopService,
        private readonly LoggerInterface $logger
    ) {}

    public function import(): bool
    {
        $downloadedShops = $this->shopDownloader->download();
        $parsedDownloadedShops = $this->shopDownloaderParser->parse($downloadedShops);

        DB::transaction(function () use ($parsedDownloadedShops) {
            /* @var DownloadedShop $parsedDownloadedShop */
            foreach ($parsedDownloadedShops as $parsedDownloadedShop) {
                $this->upsert($parsedDownloadedShop);
            }
        });

        return true;
    }

    private function upsert(DownloadedShop $downloadedShop): void
    {
        if ($shop = $this->shopService->findByExternalId($downloadedShop->externalId)) {
            $this->shopService->update($shop, $downloadedShop->toArray());
            $this->logger->info(__('Shop updated.'), ['external_id' => $downloadedShop->externalId]);
        } else {
            $this->shopService->create($downloadedShop->toArray());
            $this->logger->info(__('Shop created.'), ['external_id' => $downloadedShop->externalId]);
        }
    }
}
