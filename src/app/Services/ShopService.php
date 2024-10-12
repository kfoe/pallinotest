<?php

namespace App\Services;

use App\Models\Shop;
use App\Repositories\Contracts\ShopRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Contracts\ShopService as ShopServiceContract;

class ShopService implements ShopServiceContract
{
    public function __construct(private readonly ShopRepository $shopRepository) {}

    public function create(array $attributes): Shop
    {
        return $this->shopRepository->create($attributes);
    }

    public function update(Shop $shop, array $attributes): bool
    {
        return $shop->update($attributes);
    }

    public function findByExternalId(int $externalId, array|string $columns = ['*']): ?Shop
    {
        try {
            return $this->shopRepository->findBy('external_id', $externalId, $columns);
        } catch (ModelNotFoundException) {
            return null;
        }
    }
}
