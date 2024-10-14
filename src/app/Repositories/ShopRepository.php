<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Repositories\Abstracts\AbstractRepository;
use App\Repositories\Contracts\ShopRepository as ShopRepositoryContract;

/**
 * @method Shop newModelInstance()
 */
class ShopRepository extends AbstractRepository implements ShopRepositoryContract
{
    public function __construct(Shop $model)
    {
        parent::__construct($model);
    }

    public function create(array $data): Shop
    {
        return $this->newModelInstance()->newQuery()->create($data);
    }

    public function findBy(string $column, mixed $attribute, array|string $columns = ['*']): Shop
    {
        return $this->newModelInstance()->newQuery()->where($column, $attribute)->firstOrFail($columns);
    }
}
