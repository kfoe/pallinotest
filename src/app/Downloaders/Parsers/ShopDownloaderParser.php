<?php

namespace App\Downloaders\Parsers;

use Illuminate\Support\Arr;
use App\Downloaders\Dto\DownloadedShop;
use App\Downloaders\Contracts\DownloadParser;

class ShopDownloaderParser implements DownloadParser
{
    public function parse(array $data): array
    {
        return array_map(static function (array $data) {
            return new DownloadedShop(
                Arr::get($data, 'id'),
                Arr::get($data, 'name'),
                Arr::get($data, 'address'),
                Arr::get($data, 'country'),
            );
        }, $data);
    }
}
