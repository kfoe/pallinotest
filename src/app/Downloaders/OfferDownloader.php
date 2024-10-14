<?php

namespace App\Downloaders;

use App\Downloaders\Strategies\JsonDownloadStrategy;

class OfferDownloader
{
    public function __construct(private readonly JsonDownloadStrategy $jsonDownloadStrategy) {}

    public function download(): array
    {
        return $this->jsonDownloadStrategy->download('api/v1/offers.json');
    }
}
