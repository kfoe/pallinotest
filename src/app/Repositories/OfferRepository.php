<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Abstracts\AbstractRepository;
use App\Repositories\Contracts\OfferRepository as OfferRepositoryContract;

/**
 * @method Offer newModelInstance()
 */
class OfferRepository extends AbstractRepository implements OfferRepositoryContract
{
    public function __construct(Offer $model)
    {
        parent::__construct($model);
    }

    public function create(Shop $shop, array $data): Offer
    {
        $offer = $this->newModelInstance()->newQuery()->make($data);
        $offer->shop()->associate($shop);
        $offer->save();

        return $offer;
    }

    public function findBy(string $column, mixed $attribute, array|string $columns = ['*']): Offer
    {
        return $this->newModelInstance()->newQuery()->where($column, $attribute)->firstOrFail($columns);
    }

    public function getOffersByShopCountry(string $country): Collection
    {
        return $this->newModelInstance()->newQuery()
            ->whereHas('shop', function (Builder $builder) use ($country) {
                $builder->where('country', $country);
            })
            ->with('shop')
            ->get();
    }

    public function getOffersByShopId(string $shopId): Collection
    {
        return $this->newModelInstance()->newQuery()
            ->whereHas('shop', function (Builder $builder) use ($shopId) {
                $builder->where('id', $shopId);
            })
            ->orderBy('price')
            ->get();
    }
}
