<?php

namespace App\Downloaders\Contracts;

interface DownloadStrategy
{
    public function download(string $url): array;
}
