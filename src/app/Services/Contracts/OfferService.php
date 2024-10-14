<?php

namespace App\Services\Contracts;

use App\Models\Shop;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;

interface OfferService
{
    public function create(Shop $shop, array $attributes): Offer;

    public function update(Offer $offer, array $attributes): bool;

    public function findByExternalId(int $externalId, array|string $columns = ['*']): ?Offer;

    public function getOffersByShopCountry(string $country): Collection;

    public function getOffersByShop(Shop $shop): Collection;
}
