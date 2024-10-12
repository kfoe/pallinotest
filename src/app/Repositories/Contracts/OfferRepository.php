<?php

namespace App\Repositories\Contracts;

use App\Models\Shop;
use App\Models\Offer;

interface OfferRepository extends Repository
{
    public function create(Shop $shop, array $data): Offer;

    public function findBy(string $column, mixed $attribute, array|string $columns = ['*']): Offer;
}
