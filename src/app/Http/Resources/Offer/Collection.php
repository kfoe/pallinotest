<?php

namespace App\Http\Resources\Offer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Collection extends ResourceCollection
{
    public $collects = Model::class;
}
