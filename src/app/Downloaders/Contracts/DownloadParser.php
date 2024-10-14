<?php

namespace App\Downloaders\Contracts;

interface DownloadParser
{
    public function parse(array $data): array;
}
