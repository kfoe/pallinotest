<?php

namespace App\Importers;

use Psr\Log\LoggerInterface;
use Illuminate\Support\Facades\DB;
use App\Downloaders\ShopDownloader;
use App\Importers\Contracts\Importer;
use App\Downloaders\Dto\DownloadedShop;
use App\Services\Contracts\ShopService;
use App\Downloaders\Parsers\ShopDownloaderParser;

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
            if ($this->shopService->update($shop, $downloadedShop->toArray())) {
                $this->logger->info(__('Shop updated.'), ['external_id' => $downloadedShop->externalId]);
            } else {
                $this->logger->error(__('Unable to update Shop.'), ['external_id' => $downloadedShop->externalId]);
            }
        } else {
            $this->shopService->create($downloadedShop->toArray());
            $this->logger->info(__('Shop created.'), ['external_id' => $downloadedShop->externalId]);
        }
    }
}
