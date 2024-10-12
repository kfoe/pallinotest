<?php

namespace App\Importer\Contracts;

interface Importer
{
    public function import(): bool;
}
