<?php

namespace App\Downloader;

use App\Downloader\Strategies\JsonDownloadStrategy;

class OfferDownloader
{
    public function __construct(private readonly JsonDownloadStrategy $jsonDownloadStrategy) {}

    public function download(): array
    {
        return $this->jsonDownloadStrategy->download('api/v1/offers.json');
    }
}
