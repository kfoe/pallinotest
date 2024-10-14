<?php

namespace App\Downloaders\Strategies;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use App\Downloaders\Contracts\DownloadStrategy;

class JsonDownloadStrategy implements DownloadStrategy
{
    public function __construct(private readonly ClientInterface $client) {}

    public function download(string $url): array
    {
        try {
            $response = $this->client->get($url);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new \RuntimeException('Request Error: '.$e->getMessage());
        }
    }
}
