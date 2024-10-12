<?php

namespace App\Downloader;

use App\Downloader\Strategies\CsvDownloadStrategy;
use App\Downloader\Strategies\JsonDownloadStrategy;

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
