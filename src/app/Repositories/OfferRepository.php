<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Models\Offer;
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
}
