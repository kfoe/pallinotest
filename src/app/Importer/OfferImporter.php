<?php

namespace App\Importer;

use Psr\Log\LoggerInterface;
use Illuminate\Support\Facades\DB;
use App\Downloader\OfferDownloader;
use App\Importer\Contracts\Importer;
use App\Services\Contracts\ShopService;
use App\Downloader\Dtos\DownloadedOffer;
use App\Services\Contracts\OfferService;
use App\Downloader\Parsers\OfferDownloaderParser;

class OfferImporter implements Importer
{
    public function __construct(
        private readonly OfferDownloader $offerDownloader,
        private readonly OfferDownloaderParser $offerDownloaderParser,
        private readonly OfferService $offerService,
        private readonly ShopService $shopService,
        private readonly LoggerInterface $logger
    ) {}

    public function import(): bool
    {
        $downloadedOffers = $this->offerDownloader->download();
        $parsedDownloadedOffers = $this->offerDownloaderParser->parse($downloadedOffers);

        DB::transaction(function () use ($parsedDownloadedOffers) {
            /* @var DownloadedOffer $parsedDownloadedOffer */
            foreach ($parsedDownloadedOffers as $parsedDownloadedOffer) {
                $this->upsert($parsedDownloadedOffer);
            }
        });

        return true;
    }

    private function upsert(DownloadedOffer $downloadedOffer): void
    {
        if ($offer = $this->offerService->findByExternalId($downloadedOffer->externalId)) {
            $this->offerService->update($offer, $downloadedOffer->toArray());

            $this->logger->info(__('Offer updated.'), ['external_id' => $downloadedOffer->externalId]);
        } elseif ($shop = $this->shopService->findByExternalId($downloadedOffer->externalShopId)) {
            $this->offerService->create($shop, $downloadedOffer->toArray());

            $this->logger->info(__('Offer created.'), ['external_id' => $downloadedOffer->externalId]);
        } else {
            $this->logger->warning(__('Shop not found for offer.'), ['external_shop_id' => $downloadedOffer->externalShopId]);
        }
    }
}
