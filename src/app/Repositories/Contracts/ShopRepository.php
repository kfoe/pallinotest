<?php

namespace App\Repositories\Contracts;

use App\Models\Shop;

interface ShopRepository extends Repository
{
    public function create(array $data): Shop;

    public function findBy(string $column, mixed $attribute, array|string $columns = ['*']): Shop;
}
