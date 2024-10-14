<?php

namespace App\Downloaders\Strategies;

use League\Csv\Reader;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use App\Downloaders\Contracts\DownloadStrategy;

class CsvDownloadStrategy implements DownloadStrategy
{
    public function __construct(private readonly ClientInterface $client) {}

    public function download(string $url): array
    {
        try {
            $response = $this->client->get($url);

            $csv = Reader::createFromString($response->getBody()->getContents());
            $csv->setHeaderOffset(0);

            return iterator_to_array($csv, false);
        } catch (RequestException $e) {
            throw new \RuntimeException('Request Error: '.$e->getMessage());
        }
    }
}
