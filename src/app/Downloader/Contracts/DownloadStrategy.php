<?php

namespace App\Downloader\Contracts;

interface DownloadStrategy
{
    public function download(string $url): array;
}
