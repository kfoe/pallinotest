<?php

namespace App\Downloader\Dtos;

use Illuminate\Contracts\Support\Arrayable;

class DownloadedShop implements Arrayable
{
    public function __construct(
        public readonly int $externalId,
        public readonly string $name,
        public readonly string $address,
        public readonly string $country,
    ) {}

    public function toArray(): array
    {
        return [
            'external_id' => $this->externalId,
            'name' => $this->name,
            'address' => $this->address,
            'country' => $this->country,
        ];
    }
}
