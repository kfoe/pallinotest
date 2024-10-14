<?php

namespace App\Importers\Contracts;

interface Importer
{
    public function import(): bool;
}
