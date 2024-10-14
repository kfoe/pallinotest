<?php

namespace App\Repositories\Abstracts;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function newModelInstance(): Model
    {
        return $this->model->newModelInstance();
    }
}
