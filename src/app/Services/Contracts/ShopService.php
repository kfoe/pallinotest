<?php

namespace App\Services\Contracts;

use App\Models\Shop;

interface ShopService
{
    public function create(array $attributes): Shop;

    public function update(Shop $shop, array $attributes): bool;

    public function findByExternalId(int $externalId, array|string $columns = ['*']): ?Shop;
}
