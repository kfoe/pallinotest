<?php

namespace App\Downloaders\Dto;

use Illuminate\Contracts\Support\Arrayable;

class DownloadedOffer implements Arrayable
{
    public function __construct(
        public readonly int $externalId,
        public readonly int $externalShopId,
        public readonly string $product,
        public readonly float $price,
        public readonly string $currency,
        public readonly string $description,
    ) {}

    public function toArray(): array
    {
        return [
            'external_id' => $this->externalId,
            'external_shop_id' => $this->externalShopId,
            'product' => $this->product,
            'price' => $this->price,
            'currency' => $this->currency,
            'description' => $this->description,
        ];
    }
}
