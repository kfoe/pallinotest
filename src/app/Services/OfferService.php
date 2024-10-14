<?php

namespace App\Services;

use App\Models\Shop;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Contracts\OfferRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Contracts\OfferService as OfferServiceContract;

class OfferService implements OfferServiceContract
{
    public function __construct(private readonly OfferRepository $offerRepository) {}

    public function create(Shop $shop, array $attributes): Offer
    {
        return $this->offerRepository->create($shop, $attributes);
    }

    public function update(Offer $offer, array $attributes): bool
    {
        return $offer->update($attributes);
    }

    public function findByExternalId(int $externalId, array|string $columns = ['*']): ?Offer
    {
        try {
            return $this->offerRepository->findBy('external_id', $externalId, $columns);
        } catch (ModelNotFoundException) {
            return null;
        }
    }

    public function getOffersByShopCountry(string $country): Collection
    {
        return $this->offerRepository->getOffersByShopCountry($country);
    }

    public function getOffersByShop(Shop $shop): Collection
    {
        return $this->offerRepository->getOffersByShopId($shop->id);
    }
}
