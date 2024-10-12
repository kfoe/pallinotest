<?php

namespace App\Downloader\Contracts;

interface DownloadParser
{
    public function parse(array $data): array;
}
