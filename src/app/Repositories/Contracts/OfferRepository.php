<?php

namespace App\Repositories\Contracts;

use App\Models\Shop;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;

interface OfferRepository extends Repository
{
    public function create(Shop $shop, array $data): Offer;

    public function findBy(string $column, mixed $attribute, array|string $columns = ['*']): Offer;

    public function getOffersByShopCountry(string $country): Collection;

    public function getOffersByShopId(string $shopId): Collection;
}
