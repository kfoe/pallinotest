<?php

namespace App\Downloaders;

use App\Downloaders\Strategies\CsvDownloadStrategy;
use App\Downloaders\Strategies\JsonDownloadStrategy;

class ShopDownloader
{
    public function __construct(
        private readonly JsonDownloadStrategy $jsonDownloadStrategy,
        private readonly CsvDownloadStrategy $csvDownloadStrategy,
    ) {}

    public function download(): array
    {
        return array_merge(
            $this->jsonDownloadStrategy->download('api/v1/shops.json'),
            $this->csvDownloadStrategy->download('shops.csv')
        );
    }
}
