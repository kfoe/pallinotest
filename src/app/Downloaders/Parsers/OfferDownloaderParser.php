<?php

namespace App\Downloaders\Parsers;

use Illuminate\Support\Arr;
use App\Downloaders\Dto\DownloadedOffer;
use App\Downloaders\Contracts\DownloadParser;

class OfferDownloaderParser implements DownloadParser
{
    public function parse(array $data): array
    {
        return array_map(static function (array $data) {
            return new DownloadedOffer(
                Arr::get($data, 'id'),
                Arr::get($data, 'shop_id'),
                Arr::get($data, 'product'),
                Arr::get($data, 'price'),
                Arr::get($data, 'currency'),
                Arr::get($data, 'description'),
            );
        }, $data);
    }
}
