<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Repository
{
    public function newModelInstance(): Model;
}
